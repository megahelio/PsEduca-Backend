<?php

require_once __DIR__ . "/../model/File.php";

enum FilterType: string {
    case Areas = "areas";
    case Tags = "etiquetas";
    case ResourceTypes = "tipos_recurso";
    case Formats = "formatos";
    case ApplicationModes = "aplicacion";
}

class CatalogueItemMapper {

    private static ?CatalogueItemMapper $instance = null;

    public static function getInstance(): CatalogueItemMapper
    {
        if (self::$instance == null) {
            self::$instance = new CatalogueItemMapper();
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
     * @throws Exception
     */
    public function save(CatalogueItem $catalogueItem): CatalogueItem
    {
        $image = $catalogueItem->getImage();
        $image?->save();

        foreach ($catalogueItem->getFiles() as $file) {
            $this->addFile($file, $catalogueItem->getId());
        }

        foreach ($catalogueItem->getLinks() as $link) {
            $this->addExternalLink($link, $catalogueItem->getId());
        }

        $stmt = $this->db->prepare("INSERT INTO items_catalogo (acronimo, nombre, imagen, edad_mes_min, 
                            edad_anho_min, edad_mes_max, edad_anho_max, autores, duracion, descripcion, observacion) 
            values (:acronimo, :nombre, :imagen, :edad_mes_min, :edad_anho_min, :edad_mes_max, :edad_anho_max, :autores, 
                    :duracion, :descripcion, :observacion)");

        $stmt->execute(array(
            ':acronimo' => $catalogueItem->getAcronym(),
            ':nombre' => $catalogueItem->getName(),
            ':imagen' => $catalogueItem->getImage()?->getStorageFileName(),
            ':edad_mes_min' => $catalogueItem->getMonthMinAge(),
            ':edad_anho_min' => $catalogueItem->getYearMinAge(),
            ':edad_mes_max' => $catalogueItem->getMonthMaxAge(),
            ':edad_anho_max' => $catalogueItem->getYearMaxAge(),
            ':autores' => $catalogueItem->getAuthors(),
            ':duracion' => $catalogueItem->getLength(),
            ':descripcion' => $catalogueItem->getDescription(),
            ':observacion' => $catalogueItem->getNote()
        ));

        $catalogueItem->setId($this->db->lastInsertId());

        foreach ($catalogueItem->getAreas() as $area) {
            $this->addFilterToItem(FilterType::Areas, $area, $catalogueItem->getId());
        }

        foreach ($catalogueItem->getTags() as $tag) {
            $this->addFilterToItem(FilterType::Tags, $tag, $catalogueItem->getId());
        }

        foreach ($catalogueItem->getResourceTypes() as $resourceType) {
            $this->addFilterToItem(FilterType::ResourceTypes, $resourceType, $catalogueItem->getId());
        }

        foreach ($catalogueItem->getFormats() as $format) {
            $this->addFilterToItem(FilterType::Formats, $format, $catalogueItem->getId());
        }

        foreach ($catalogueItem->getApplicationModes() as $applicationMode) {
            $this->addFilterToItem(FilterType::ApplicationModes, $applicationMode, $catalogueItem->getId());
        }

        return $catalogueItem;
    }

    /**
     * @param CatalogueItem $catalogueItem
     * @return mixed
     * @throws FileException
     * @throws Exception
     */
    public function update(CatalogueItem $catalogueItem): CatalogueItem
    {

        // Update image
        if ($catalogueItem->isImageChanged()){
            $oldImage = $catalogueItem->getImage();

            if (!is_null($oldImage) && $oldImage->exists()){
                !$oldImage->delete();
            }
            $image = $catalogueItem->getNewImage();
            $image?->save();

            $catalogueItem->setImage($image);
            $catalogueItem->setNewImage(null);
            $catalogueItem->resetImageChangedFlag();
        }

        // Update files
        $newFiles = $catalogueItem->getNewFiles();
        if ($newFiles != null) {
            foreach ($newFiles as $file) {
                $this->addFile($file, $catalogueItem->getId());
            }
        }

        $filesToDelete = $catalogueItem->getFilesToDelete();
        if ($filesToDelete != null) {
            foreach ($filesToDelete as $file) {
                $this->removeFile($file, $catalogueItem->getId());
            }
        }

        // Update links
        $newLinks = $catalogueItem->getNewLinks();
        if ($newLinks != null) {
            foreach ($newLinks as $link) {
                $this->addExternalLink($link, $catalogueItem->getId());
            }
        }

        $linksToDelete = $catalogueItem->getLinksToDelete();
        if ($linksToDelete != null) {
            foreach ($linksToDelete as $link) {
                $this->removeExternalLink($link->getId(), $catalogueItem->getId());
            }
        }

        $stmt = $this->db->prepare("UPDATE items_catalogo SET acronimo = :acronimo, nombre = :nombre, imagen = :imagen, 
            edad_mes_min = :edad_mes_min, edad_anho_min = :edad_anho_min, edad_mes_max = :edad_mes_max, edad_anho_max = :edad_anho_max, 
            autores = :autores, duracion = :duracion, descripcion = :descripcion, observacion = :observacion WHERE id = :id");

        $stmt->execute(array(
            ':id' => $catalogueItem->getId(),
            ':acronimo' => $catalogueItem->getAcronym(),
            ':nombre' => $catalogueItem->getName(),
            ':imagen' => $catalogueItem->getImage()?->getStorageFileName(),
            ':edad_mes_min' => $catalogueItem->getMonthMinAge(),
            ':edad_anho_min' => $catalogueItem->getYearMinAge(),
            ':edad_mes_max' => $catalogueItem->getMonthMaxAge(),
            ':edad_anho_max' => $catalogueItem->getYearMaxAge(),
            ':autores' => $catalogueItem->getAuthors(),
            ':duracion' => $catalogueItem->getLength(),
            ':descripcion' => $catalogueItem->getDescription(),
            ':observacion' => $catalogueItem->getNote()
        ));

        $this->updateFilters($catalogueItem);

        return $catalogueItem;
    }

    /**
     * @throws Exception
     */
    public function delete($catalogueItem): void
    {
        foreach ($catalogueItem->getFiles() as $file) {
            $this->removeFile($file, $catalogueItem->getId());
        }
        foreach ($catalogueItem->getLinks() as $link) {
            $this->removeExternalLink($link->getId(), $catalogueItem->getId());
        }

        foreach ($catalogueItem->getAreas() as $area) {
            $this->removeFilterToItem(FilterType::Areas, $area, $catalogueItem->getId());
        }
        foreach ($catalogueItem->getTags() as $tag) {
            $this->removeFilterToItem(FilterType::Tags, $tag, $catalogueItem->getId());
        }
        foreach ($catalogueItem->getResourceTypes() as $resourceType) {
            $this->removeFilterToItem(FilterType::ResourceTypes, $resourceType, $catalogueItem->getId());
        }
        foreach ($catalogueItem->getFormats() as $format) {
            $this->removeFilterToItem(FilterType::Formats, $format, $catalogueItem->getId());
        }
        foreach ($catalogueItem->getApplicationModes() as $applicationMode) {
            $this->removeFilterToItem(FilterType::ApplicationModes, $applicationMode, $catalogueItem->getId());
        }

        $stmt = $this->db->prepare("DELETE FROM items_catalogo WHERE id = :id");
        $stmt->execute(array(
            ':id' => $catalogueItem->getId()
        ));
    }

    /**
     * @throws FileException
     */
    public function findById(?string $id): ?CatalogueItem
    {
        $stmt = $this->db->prepare("SELECT * FROM items_catalogo WHERE id = :id");
        $stmt->execute(array(
            ':id' => $id
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return $this->catalogueItemFromRow($data);
        }
        return null;
    }

    /**
     * @throws FileException
     */
    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM items_catalogo");
        $catalogueItems = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $catalogueItems[] = $this->catalogueItemFromRow($data);
        }

        return $catalogueItems;
    }

    public function catalogueItemExists(int $catalogueItemId): bool
    {
        $stmt = $this->db->prepare("SELECT * FROM items_catalogo WHERE id = :id");
        $stmt->execute(array(
            ':id' => $catalogueItemId
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data != null;
    }

    /**
     * @throws FileException
     */
    private function catalogueItemFromRow(mixed $data): CatalogueItem
    {
        $image = (!empty($data['imagen'])? File::fromExistingFile($data['imagen']) : null);
        $files = $this->listFilesFromItem($data['id']);
        $links = $this->listExternalLinksFromItem($data['id']);
        $areas = $this->listFiltersFromItem($data['id'], FilterType::Areas);
        $tags = $this->listFiltersFromItem($data['id'], FilterType::Tags);
        $resourceTypes = $this->listFiltersFromItem($data['id'], FilterType::ResourceTypes);
        $formats = $this->listFiltersFromItem($data['id'], FilterType::Formats);
        $applicationModes = $this->listFiltersFromItem($data['id'], FilterType::ApplicationModes);

        return new CatalogueItem(
            id: $data['id'],
            acronym: $data['acronimo'],
            name: $data['nombre'],
            image: $image,
            yearMinAge: $data['edad_anho_min'],
            monthMinAge: $data['edad_mes_min'],
            yearMaxAge: $data['edad_anho_max'],
            monthMaxAge: $data['edad_mes_max'],
            authors: $data['autores'],
            length: $data['duracion'],
            description: $data['descripcion'],
            note: $data['observacion'],

            files: $files,
            links: $links,

            areas: $areas,
            tags: $tags,
            resourceTypes: $resourceTypes,
            formats: $formats,
            applicationModes: $applicationModes
        );
    }

    /******************************************************************************************************************/
    /******************************************************************************************************************/

    // Métodos CRUD de ficheros y enlaces de un item del catálogo

    /******************************************************************************************************************/
    /******************************************************************************************************************/

    public function fileExists(int $fileId, int $itemId): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM ficheros_items_catalogo WHERE id = :id AND id_item_catalogo = :id_item_catalogo");
        $stmt->execute(array(
            ':id' => $fileId,
            ':id_item_catalogo' => $itemId
        ));
        return $stmt->fetchColumn() > 0;
    }
    /**
     * @throws FileException
     */
    private function addFile(File $file, int $itemId): void
    {
        $file->save();
        $stmt = $this->db->prepare("INSERT INTO ficheros_items_catalogo (nombre, nombre_fichero, id_item_catalogo) 
            values (:nombre, :nombre_fichero, :id_item_catalogo)");
        $stmt->execute(array(
            ':nombre' => $file->getDescription(),
            ':nombre_fichero' => $file->getStorageFileName(),
            ':id_item_catalogo' => $itemId
        ));
        $file->setId($this->db->lastInsertId());
    }

    private function removeFile(File $file, int $itemId): void
    {
        $file->delete();
        $stmt = $this->db->prepare("DELETE FROM ficheros_items_catalogo WHERE id = :id AND id_item_catalogo = :id_item_catalogo");
        $stmt->execute(array(
            ':id' => $file->getId(),
            ':id_item_catalogo' => $itemId
        ));
    }

    /**
     * @throws FileException
     */
    private function listFilesFromItem(int $itemId): array
    {
        $stmt = $this->db->prepare("SELECT id, nombre, nombre_fichero FROM ficheros_items_catalogo WHERE id_item_catalogo = :id_item_catalogo");
        $stmt->execute(array(
            ':id_item_catalogo' => $itemId
        ));
        $files = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $fileData) {
            $files[] = File::fromExistingFile(
                fileName: $fileData['nombre_fichero'],
                id: $fileData['id'],
                description: $fileData['nombre']
            );
        }
        return $files;
    }

    public function linkExists(int $linkId, int $itemId): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM enlaces_items_catalogo WHERE id = :id AND id_item_catalogo = :id_item_catalogo");
        $stmt->execute(array(
            ':id' => $linkId,
            ':id_item_catalogo' => $itemId
        ));
        return $stmt->fetchColumn() > 0;
    }


    private function addExternalLink(Link $link, int $itemId): void
    {
        $stmt = $this->db->prepare("INSERT INTO enlaces_items_catalogo (nombre, enlace, id_item_catalogo) 
            values (:nombre, :enlace, :id_item_catalogo)");
        $stmt->execute(array(
            ':nombre' => $link->getName(),
            ':enlace' => $link->getURL(),
            ':id_item_catalogo' => $itemId
        ));
        $link->setId($this->db->lastInsertId());
    }

    private function removeExternalLink(string $linkId, int $itemId): void
    {
        $stmt = $this->db->prepare("DELETE FROM enlaces_items_catalogo WHERE id = :id AND id_item_catalogo = :id_item_catalogo");
        $stmt->execute(array(
            ':id' => $linkId,
            ':id_item_catalogo' => $itemId
        ));
    }

    private function listExternalLinksFromItem(int $itemId): array
    {
        $stmt = $this->db->prepare("SELECT id, nombre, enlace FROM enlaces_items_catalogo WHERE id_item_catalogo = :id_item_catalogo");
        $stmt->execute(array(
            ':id_item_catalogo' => $itemId
        ));
        $links = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $linkData) {
            $links[] = new Link(
                id: $linkData['id'],
                name: $linkData['nombre'],
                url: $linkData['enlace']
            );
        }
        return $links;
    }

    /******************************************************************************************************************/
    /******************************************************************************************************************/

    // Métodos CRUD de filtros de un item del catálogo (áreas, etiquetas, tipos de recurso, formatos y modos de aplicación)

    /******************************************************************************************************************/
    /******************************************************************************************************************/

    public function listCurrentFilterValues(FilterType $filter): array
    {
        $stmt = $this->db->prepare("SELECT DISTINCT nombre FROM {$filter->value}_items_catalogo");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * @throws Exception
     */
    private function addFilterToItem(FilterType $filterType, string $filterValue, int $itemId): void
    {
        if (!$this->existsFilter($filterType, $filterValue)) {
            $this->addNewFilter($filterType, $filterValue);
        }
        $stmt = $this->db->prepare("INSERT INTO relacion_catalogo_{$filterType->value} (nombre, id_item) 
            values (:nombre, :id_item_catalogo)");
        $stmt->execute(array(
            ':nombre' => $filterValue,
            ':id_item_catalogo' => $itemId
        ));
    }

    /**
     * @throws Exception
     */
    private function removeFilterToItem(FilterType $filterType, string $filterValue, int $itemId): void
    {
        $stmt = $this->db->prepare("DELETE FROM relacion_catalogo_{$filterType->value} WHERE nombre = :nombre AND id_item = :id_item_catalogo");
        $stmt->execute(array(
            ':nombre' => $filterValue,
            ':id_item_catalogo' => $itemId
        ));
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM relacion_catalogo_{$filterType->value} WHERE nombre = :nombre");
        $stmt->execute(array(':nombre' => $filterValue));
        // Por el momento no se va a eliminar el filtro de la BBDD si no se usa en ningún item (salvo etiquetas)
        if ($filterType == FilterType::Tags && $stmt->fetchColumn() == 0) {
            $this->removeUnusedFilter($filterType, $filterValue);
        }
    }

    private function existsFilter(FilterType $filterType, string $value): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$filterType->value}_items_catalogo WHERE LOWER(nombre) = LOWER(:nombre)");
        $stmt->execute(array(
            ':nombre' => $value
        ));
        return $stmt->fetchColumn() > 0;
    }

    /**
     * @throws Exception
     */
    private function addNewFilter(FilterType $filterType, string $value): void
    {
        $stmt = $this->db->prepare("INSERT INTO {$filterType->value}_items_catalogo (nombre) values (:nombre)");
        $stmt->execute(array(
            ':nombre' => $value
        ));
        if ($stmt->rowCount() == 0) {
            throw new Exception("Error adding new filter");
        }
    }

    /**
     * @throws Exception
     */
    private function removeUnusedFilter(FilterType $filterType, string $value): void
    {
        $stmt = $this->db->prepare("DELETE FROM {$filterType->value}_items_catalogo WHERE LOWER(nombre) = LOWER(:nombre)");
        $stmt->execute(array(
            ':nombre' => $value
        ));
        if ($stmt->rowCount() == 0) {
            throw new Exception("Error removing filter");
        }
    }

    /**
     * @throws Exception
     */
    private function updateFilters($catalogueItem): void
    {
        $this->updateFiltersForItem($catalogueItem, FilterType::Areas);
        $this->updateFiltersForItem($catalogueItem, FilterType::Tags);
        $this->updateFiltersForItem($catalogueItem, FilterType::ResourceTypes);
        $this->updateFiltersForItem($catalogueItem, FilterType::Formats);
        $this->updateFiltersForItem($catalogueItem, FilterType::ApplicationModes);
    }

    /**
     * @throws Exception
     */
    private function updateFiltersForItem($catalogueItem, FilterType $filterType): void
    {
    $currentFilters = $this->listFiltersFromItem($catalogueItem->getId(), $filterType);
    $newFilters = match ($filterType) {
        FilterType::Areas => $catalogueItem->getAreas(),
        FilterType::Tags => $catalogueItem->getTags(),
        FilterType::ResourceTypes => $catalogueItem->getResourceTypes(),
        FilterType::Formats => $catalogueItem->getFormats(),
        FilterType::ApplicationModes => $catalogueItem->getApplicationModes(),
    };
    $this->updateFiltersForItemHelper($catalogueItem->getId(), $filterType, $currentFilters, $newFilters);
}

    private function listFiltersFromItem($getId, FilterType $filterType): array
    {
        $stmt = $this->db->prepare("SELECT nombre FROM relacion_catalogo_{$filterType->value} WHERE id_item = :id_item_catalogo");
        $stmt->execute(array(
            ':id_item_catalogo' => $getId
        ));
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * @throws Exception
     */
    private function updateFiltersForItemHelper($getId, FilterType $filterType, array $currentFilters, mixed $newFilters): void
    {
        foreach ($currentFilters as $currentFilter) {
            if (!in_array($currentFilter, $newFilters)) {
                $this->removeFilterToItem($filterType, $currentFilter, $getId);
            }
        }
        foreach ($newFilters as $newFilter) {
            if (!in_array($newFilter, $currentFilters)) {
                $this->addFilterToItem($filterType, $newFilter, $getId);
            }
        }
    }
}