<?php

class PyPItemMapper {
    private static ?PyPItemMapper $instance = null;

    public static function getInstance(): PyPItemMapper
    {
        if (self::$instance == null) {
            self::$instance = new PyPItemMapper();
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
     * @throws FileException
     */
    public function save(PyPItem $pypItem): PyPItem
    {
        $image = $pypItem->getImage();
        $image?->save();

        $stmt = $this->db->prepare("INSERT INTO items_pyp (titulo, descripcion, imagen, link_externo) 
            values (:titulo, :descripcion, :imagen, :link_externo)");

        $stmt->execute(array(
            ':titulo' => $pypItem->getTitle(),
            ':descripcion' => $pypItem->getDescription(),
            ':imagen' => $pypItem->getImage()?->getStorageFileName(),
            ':link_externo' => $pypItem->getExternalURL()
        ));

        $pypItem->setId($this->db->lastInsertId());

        return $pypItem;
    }

    /**
     * @throws FileException
     */
    public function update(PyPItem $pypItem): PyPItem
    {
        // Actualizar imagen
        if ($pypItem->isImageChanged()){
            $oldImage = $pypItem->getImage();

            if (!is_null($oldImage) && $oldImage->exists()){
                !$oldImage->delete();
            }
            $image = $pypItem->getNewImage();
            $image?->save();

            $pypItem->setImage($image);
            $pypItem->setNewImage(null);
            $pypItem->resetImageChangedFlag();
        }

        $stmt = $this->db->prepare("UPDATE items_pyp SET titulo = :titulo, descripcion = :descripcion,
                     imagen = :imagen, link_externo = :link_externo WHERE id = :id");

        $stmt->execute(array(
            ':titulo' => $pypItem->getTitle(),
            ':descripcion' => $pypItem->getDescription(),
            ':imagen' => $pypItem->getImage()?->getStorageFileName(),
            ':link_externo' => $pypItem->getExternalURL(),
            ':id' => $pypItem->getId()
        ));

        return $pypItem;
    }

    public function delete(PyPItem $pypItem): void
    {
        $pypItem->getImage()?->delete();

        $stmt = $this->db->prepare("DELETE FROM items_pyp WHERE id = :id");
        $stmt->execute(array(
            ':id' => $pypItem->getId()
        ));
    }

    public function findById(?string $id): ?PyPItem
    {
        $stmt = $this->db->prepare("SELECT * FROM items_pyp WHERE id = :id");
        $stmt->execute(array(
            ':id' => $id
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return $this->pypItemFromRow($data);
        }
        return null;
    }

    public function findAll(bool $returnOnlyAuthorized, ?string $userId = null): array
    {
        if (!$returnOnlyAuthorized) {
            // Los admins pueden ver todos los items
            $stmt = $this->db->query("SELECT * FROM items_pyp");
        } else {
            // Los usuarios normales solo pueden ver los items para los que tienen autorización
            $stmt = $this->db->prepare("
                SELECT * 
                FROM items_pyp JOIN autorizaciones_usuarios_items_pyp ON items_pyp.id = autorizaciones_usuarios_items_pyp.id_item_pyp
                WHERE autorizaciones_usuarios_items_pyp.id_usuario = :userId
            ");
            $stmt->execute(array(
                ':userId' => $userId
            ));
        }

        $pypItems = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pypItems[] = $this->pypItemFromRow($data);
        }

        return $pypItems;
    }

    public function pypItemExists(int $pypItemId): bool
    {
        $stmt = $this->db->prepare("SELECT * FROM items_pyp WHERE id = :id");
        $stmt->execute(array(
            ':id' => $pypItemId
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data != null;
    }

    private function pypItemFromRow(mixed $data): PyPItem
    {
        $image = $this->loadFile($data['imagen']);

        return new PyPItem(
            id: $data['id'],
            title: $data['titulo'],
            description: $data['descripcion'],
            image: $image,
            externalURL: $data['link_externo'],
        );
    }

    private function loadFile(?string $fileName): ?File
    {
        try {
            return (!empty($fileName)) ? File::fromExistingFile($fileName) : null;
        } catch (FileException) {
            return null;
        }
    }
}