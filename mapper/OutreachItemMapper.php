<?php

class OutreachItemMapper {
    private static ?OutreachItemMapper $instance = null;

    public static function getInstance(): OutreachItemMapper
    {
        if (self::$instance == null) {
            self::$instance = new OutreachItemMapper();
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
     * @throws FileException
     */
    public function save(OutreachItem $outreachItem): OutreachItem
    {
        $image = $outreachItem->getImage();
        $image?->save();

        $file = $outreachItem->getFile();
        $file?->save();

        $stmt = $this->db->prepare("INSERT INTO items_divulgacion (tipo_item, titulo, descripcion, link_externo, pagina_detalle, imagen, fichero) 
            values (:tipo_item, :titulo, :descripcion, :link_externo, :pagina_detalle, :imagen, :fichero_interno)");

        $stmt->execute(array(
            ':tipo_item' => $outreachItem->getType(),
            ':titulo' => $outreachItem->getTitle(),
            ':descripcion' => $outreachItem->getDescription(),
            ':link_externo' => $outreachItem->getExternalURL(),
            ':pagina_detalle' => $outreachItem->getPageContent(),
            ':imagen' => $outreachItem->getImage()?->getStorageFileName(),
            ':fichero_interno' => $outreachItem->getFile()?->getStorageFileName()
        ));

        $outreachItem->setId($this->db->lastInsertId());

        return $outreachItem;
    }

    /**
     * @throws FileException
     */
    public function update(OutreachItem $outreachItem): OutreachItem
    {
        // Actualizar imagen
        if ($outreachItem->isImageChanged()){
            $oldImage = $outreachItem->getImage();

            if (!is_null($oldImage) && $oldImage->exists()){
                !$oldImage->delete();
            }
            $image = $outreachItem->getNewImage();
            $image?->save();

            $outreachItem->setImage($image);
            $outreachItem->setNewImage(null);
            $outreachItem->resetImageChangedFlag();
        }

        // Actualizar fichero
        if ($outreachItem->isFileChanged()){
            $oldFile = $outreachItem->getFile();

            if (!is_null($oldFile) && $oldFile->exists()){
                !$oldFile->delete();
            }
            $file = $outreachItem->getNewFile();
            $file?->save();

            $outreachItem->setFile($file);
            $outreachItem->setNewFile(null);
            $outreachItem->resetFileChangedFlag();
        }

        $stmt = $this->db->prepare("UPDATE items_divulgacion SET tipo_item = :tipo_item, titulo = :titulo,
                             descripcion = :descripcion, link_externo = :link_externo, pagina_detalle = :pagina_detalle,
                             imagen = :imagen, fichero = :fichero WHERE id = :id");

        $stmt->execute(array(
            ':id' => $outreachItem->getId(),
            ':tipo_item' => $outreachItem->getType(),
            ':titulo' => $outreachItem->getTitle(),
            ':descripcion' => $outreachItem->getDescription(),
            ':link_externo' => $outreachItem->getExternalURL(),
            ':pagina_detalle' => $outreachItem->getPageContent(),
            ':imagen' => $outreachItem->getImage()?->getStorageFileName(),
            ':fichero' => $outreachItem->getFile()?->getStorageFileName()
        ));

        return $outreachItem;
    }

    public function delete(OutreachItem $outreachItem): void
    {
        $outreachItem->getImage()?->delete();
        $outreachItem->getFile()?->delete();

        $stmt = $this->db->prepare("DELETE FROM items_divulgacion WHERE id = :id");
        $stmt->execute(array(
            ':id' => $outreachItem->getId()
        ));
    }

    public function findById(?string $id): ?OutreachItem
    {
        $stmt = $this->db->prepare("SELECT * FROM items_divulgacion WHERE id = :id");
        $stmt->execute(array(
            ':id' => $id
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return $this->outreachItemFromRow($data);
        }
        return null;
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM items_divulgacion");
        $outreachItems = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $outreachItems[] = $this->outreachItemFromRow($data);
        }

        return $outreachItems;
    }

    public function outreachItemExists(int $outreachItemId)
    {
        $stmt = $this->db->prepare("SELECT * FROM items_divulgacion WHERE id = :id");
        $stmt->execute(array(
            ':id' => $outreachItemId
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data != null;
    }

    private function outreachItemFromRow(mixed $data): OutreachItem
    {
        $image = $this->loadFile($data['imagen']);
        $file = $this->loadFile($data['fichero']);

        return new OutreachItem(
            id: $data['id'],
            type: $data['tipo_item'],
            title: $data['titulo'],
            description: $data['descripcion'],
            image: $image,
            lastModified: $data['ultima_actualizacion'],
            file: $file,
            externalURL: $data['link_externo'],
            pageContent: $data['pagina_detalle']
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