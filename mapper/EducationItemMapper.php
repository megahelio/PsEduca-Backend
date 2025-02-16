<?php
// file: model/EducationItemMapper.php

require_once(__DIR__."/../core/PDOConnection.php");

/**
* Class EducationItemMapper
*
* Database interface for EucationItem entities
*/
class EducationItemMapper {

    private static ?EducationItemMapper $instance = null;

    public static function getInstance(): EducationItemMapper
    {
        if (self::$instance == null) {
            self::$instance = new EducationItemMapper();
        }
        return self::$instance;
    }
    public function enableTesting(): void
    {
        $this->db = PDOConnectionTesting::getInstance();
    }

    public function disableTesting(): void
    {
        $this->db = PDOConnection::getInstance();
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
     * Saves an education item into the database and the file into the filesystem (if it is not null)
     *
     * @param EducationItem $educationItem The education item to be saved
     * @return EducationItem
     * @throws FileException
     */
    public function save(EducationItem $educationItem) : EducationItem {

        $file = $educationItem->getImage();
        $file?->save();

        $stmt = $this->db->prepare("INSERT INTO items_formacion (titulo, tipo, descripcion, link_externo, anho_inicio, anho_fin, imagen) 
            values (:titulo, :tipo, :descripcion, :link_externo, :anho_inicio, :anho_fin, :imagen)");
        $stmt->execute(array(
            ':titulo' => $educationItem->getTitle(),
            ':tipo' => $educationItem->getType(),
            ':descripcion' => $educationItem->getDescription(),
            ':link_externo' => $educationItem->getReferenceURL(),
            ':anho_inicio' => $educationItem->getInitYear(),
            ':anho_fin' => $educationItem->getEndYear(),
            ':imagen' => $educationItem->getImage()?->getStorageFileName()
        ));
        $educationItem->setId($this->db->lastInsertId());

        return $educationItem;
    }

    /**
     * @throws FileException
     */
    public function update(EducationItem $educationItem): EducationItem
    {
        if ($educationItem->isImageChanged()){

            $oldFile = $educationItem->getImage();

            if (!is_null($oldFile) && $oldFile->exists()){
                !$oldFile->delete();
            }

            $image = $educationItem->getNewImage();
            $image?->save();

            $educationItem->setImage($image);
            $educationItem->setNewImage(null);
            $educationItem->resetImageChangedFlag();

        } else {
            $image = $educationItem->getImage();
        }

        $stmt = $this->db->prepare("UPDATE items_formacion SET titulo = :titulo, descripcion = :descripcion, imagen = :imagen,
            link_externo = :link_externo, anho_inicio = :anho_inicio, anho_fin = :anho_fin, tipo = :tipo WHERE id = :id");
        $stmt->execute(array(
            ':id' => $educationItem->getId(),
            ':titulo' => $educationItem->getTitle(),
            ':tipo' => $educationItem->getType(),
            ':descripcion' => $educationItem->getDescription(),
            ':link_externo' => $educationItem->getReferenceURL(),
            ':anho_inicio' => $educationItem->getInitYear(),
            ':anho_fin' => $educationItem->getEndYear(),
            ':imagen' => $image?->getStorageFileName()
        ));

        return $educationItem;
    }

    public function findById(?string $id): ?EducationItem
    {
        $stmt = $this->db->prepare("SELECT * FROM items_formacion WHERE id= :id");
        $stmt->execute(array(
            ':id' => $id
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return $this->educationItemFromRow($data);
        }
        return null;
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM items_formacion");
        $educationItem = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $educationItem[] = $this->educationItemFromRow($data);
        }

        return $educationItem;
    }

    public function delete(EducationItem $educationItem): void
    {
        $educationItem->getImage()?->delete();

        $stmt = $this->db->prepare("DELETE FROM items_formacion WHERE id= :id");
        $stmt->execute(array(
            ':id' => $educationItem->getId()
        ));
    }

    public function educationItemExists(int $educationItemId): bool
    {
        $stmt = $this->db->prepare("SELECT * FROM items_formacion WHERE id= :id");
        $stmt->execute(array(
            ':id' => $educationItemId
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data != null;
    }

    private function educationItemFromRow(mixed $data): EducationItem
    {
        try {
            $file = (!empty($data['imagen'])? File::fromExistingFile($data['imagen']) : null);
        } catch (FileException) {
            $file = null;
        }
        return new EducationItem(
            $data['id'],
            $data['tipo'],
            $data['titulo'],
            $data['descripcion'],
            $file,
            $data['link_externo'],
            $data['anho_inicio'],
            $data['anho_fin'],
            null, false
        );
    }

}