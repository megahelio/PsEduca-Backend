<?php

class MemberMapper
{
    private static ?MemberMapper $instance = null;

    public static function getInstance(): MemberMapper
    {
        if (self::$instance == null) {
            self::$instance = new MemberMapper();
        }
        return self::$instance;
    }



    /**
     * Reference to the PDO connection
     * @var PDO
     */
    private PDO $db;

    public function __construct() {
        $this->db = PDOConnection::getInstance();
    }

    /**
     * Saves a Member into the database and the file into the filesystem (if it is not null)
     *
     * @param Member $member The member to be saved
     * @return Member
     * @throws FileException
     */
    public function save(Member $member) : Member {

        $file = $member->getImage();
        $file?->save();

        $stmt = $this->db->prepare("INSERT INTO miembros (nombre, email, descripcion, link_aportaciones_externo, imagen)
            values (:nombre, :email, :descripcion, :link_aportaciones_externo, :imagen)");
        $stmt->execute(array(
            ':nombre' => $member->getName(),
            ':email' => $member->getEmail(),
            ':descripcion' => $member->getDescription(),
            ':link_aportaciones_externo' => $member->getReferenceURL(),
            ':imagen' => $member->getImage()?->getStorageFileName()
        ));
        $member->setId($this->db->lastInsertId());

        return $member;
    }

    /**
     * @throws FileException
     */
    public function update(Member $member): Member
    {
        if ($member->isImageChanged()){
            $oldFile = $member->getImage();

            if (!is_null($oldFile) && $oldFile->exists()){
                !$oldFile->delete();
            }

            $image = $member->getNewImage();
            $image?->save();

            $member->setImage($image);
            $member->setNewImage(null);
            $member->resetImageChangedFlag();

        } else {
            $image = $member->getImage();
        }

        $stmt = $this->db->prepare("UPDATE miembros SET nombre = :nombre, email = :email, descripcion = :descripcion,
                    link_aportaciones_externo = :link_aportaciones_externo, imagen = :imagen WHERE id = :id");
        $stmt->execute(array(
            ':id' => $member->getId(),
            ':nombre' => $member->getName(),
            ':email' => $member->getEmail(),
            ':descripcion' => $member->getDescription(),
            ':link_aportaciones_externo' => $member->getReferenceURL(),
            ':imagen' => $image?->getStorageFileName()
        ));

        return $member;
    }

    public function findById(?string $id): ?Member
    {
        $stmt = $this->db->prepare("SELECT * FROM miembros WHERE id= :id");
        $stmt->execute(array(
            ':id' => $id
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return $this->memberFromRow($data);
        }
        return null;
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM miembros");
        $members = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $members[] = $this->memberFromRow($data);
        }

        return $members;
    }

    public function delete(Member $member): void
    {
        $member->getImage()?->delete();

        $stmt = $this->db->prepare("DELETE FROM miembros WHERE id= :id");
        $stmt->execute(array(
            ':id' => $member->getId()
        ));
    }

    public function memberExists(int $memberId): bool
    {
        $stmt = $this->db->prepare("SELECT * FROM miembros WHERE id= :id");
        $stmt->execute(array(
            ':id' => $memberId
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data != null;
    }

    private function memberFromRow(mixed $data): Member
    {
        try {
            $file = (!empty($data['imagen'])? File::fromExistingFile($data['imagen']) : null);
        } catch (FileException) {
//            echo "Error creating file from existing file: " . $data['imagen'];
            $file = null;
        }
        return new Member(
            $data['id'],
            $data['nombre'],
            $data['email'],
            $data['descripcion'],
            $data['link_aportaciones_externo'],
            $file
        );
    }

}