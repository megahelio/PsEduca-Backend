<?php

require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../service/CatalogueService.php";
require_once __DIR__ . "/../core/ResponseCodes.php";

class CatalogueController extends BaseController
{
    private CatalogueService $catalogueService;

    public function __construct() {
        $this->catalogueService = new CatalogueService();
    }

    public function add($data): void
    {
        try{

            $acronym = parent::extractString($data, 'acronym');
            $name = parent::extractString($data, 'name');
            $yearMinAge = parent::extractString($data, 'yearMinAge');
            $monthMinAge = parent::extractString($data, 'monthMinAge');
            $yearMaxAge = parent::extractString($data, 'yearMaxAge');
            $monthMaxAge = parent::extractString($data, 'monthMaxAge');
            $authors = parent::extractString($data, 'authors');
            $time = parent::extractString($data, 'time');
            $description = parent::extractString($data, 'description');
            $note = parent::extractString($data, 'note');
            $image = $this->extractFile('image');

            $files = null;//$this->extractFileArray('files');
            $links = null;//$this->extractStringsArray($data, 'links');

            $areas = $this->extractStringsArray($data, 'areas');
            $tags = $this->extractStringsArray($data, 'tags');
            $resourceTypes = $this->extractStringsArray($data, 'resourceTypes');
            $formats = $this->extractStringsArray($data, 'formats');
            $applicationModes = $this->extractStringsArray($data, 'applicationModes');

            $catalogueItem = $this->catalogueService->add($acronym, $name, $image, $yearMinAge, $monthMinAge, $yearMaxAge,
                $monthMaxAge, $authors, $time, $description, $note, $areas, $tags, $resourceTypes,
                $formats, $applicationModes, $files, $links);

            parent::generateHttpResponse(201, array(ResponseCodes::RECORDSET_DATA),
                $this->generateJSONFromCatalogueItem($catalogueItem));

        } catch (ValidationException $e) {
            parent::generateHttpResponse(400, $e->getErrors());
        } catch (PDOException) {
            parent::generateHttpResponse(503, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        } catch (Throwable) {
            parent::generateHttpResponse(500, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        }
    }

    public function edit($data): void
    {
        try {

            // Datos generales
            $id = parent::extractString($data, 'id');

            $acronym = parent::extractString($data, 'acronym');
            $name = parent::extractString($data, 'name');
            $yearMinAge = parent::extractString($data, 'yearMinAge');
            $monthMinAge = parent::extractString($data, 'monthMinAge');
            $yearMaxAge = parent::extractString($data, 'yearMaxAge');
            $monthMaxAge = parent::extractString($data, 'monthMaxAge');
            $authors = parent::extractString($data, 'authors');
            $time = parent::extractString($data, 'time');
            $description = parent::extractString($data, 'description');
            $note = parent::extractString($data, 'note');
            $image = $this->extractFile('image');

            $files = $this->extractFileArray('files');
            $links = $this->extractFileArray('documents');

            $areas = $this->extractStringsArray($data, 'areas');
            $tags = $this->extractStringsArray($data, 'tags');
            $resourceTypes = $this->extractStringsArray($data, 'resourceTypes');
            $formats = $this->extractStringsArray($data, 'formats');
            $applicationModes = $this->extractStringsArray($data, 'applicationModes');

            $catalogueItem = $this->catalogueService->edit($id, $acronym, $name, $image, $yearMinAge, $monthMinAge, $yearMaxAge,
                $monthMaxAge, $authors, $time, $description, $note, $areas, $tags, $resourceTypes,
                $formats, $applicationModes, $files, $links);

            parent::generateHttpResponse(200, array(ResponseCodes::RECORDSET_DATA),
                $this->generateJSONFromCatalogueItem($catalogueItem));

        } catch (ValidationException $e) {
            parent::generateHttpResponse(400, $e->getErrors());
        } catch (NotFoundException) {
            parent::generateHttpResponse(404, array(ResponseCodes::RECORDSET_EMPTY));
        } catch (PDOException) {
            parent::generateHttpResponse(503, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        } catch (Throwable) {
            parent::generateHttpResponse(500, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        }
    }

    public function delete($data): void
    {
        try {
            $id = parent::extractString($data, 'id');

            $this->catalogueService->delete($id);

            parent::generateHttpResponse(200, array(ResponseCodes::RECORDSET_EMPTY));

        } catch (ValidationException $e) {
            parent::generateHttpResponse(400, $e->getErrors());
        } catch (NotFoundException) {
            parent::generateHttpResponse(404, array(ResponseCodes::RECORDSET_EMPTY));
        } catch (PDOException) {
            parent::generateHttpResponse(503, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        } catch (Throwable) {
            parent::generateHttpResponse(500, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        }
    }

    public function get($data): void
    {
        try {
            $id = parent::extractString($data, 'id');

            $outreachItem = $this->catalogueService->get($id);

            parent::generateHttpResponse(200, array(ResponseCodes::RECORDSET_DATA),
                $this->generateJSONFromCatalogueItem($outreachItem));

        } catch (ValidationException $e) {
            parent::generateHttpResponse(400, $e->getErrors());
        } catch (NotFoundException) {
            parent::generateHttpResponse(404, array(ResponseCodes::RECORDSET_EMPTY));
        } catch (PDOException) {
            parent::generateHttpResponse(503, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        } catch (Throwable) {
            parent::generateHttpResponse(500, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        }
    }

    public function list(): void
    {
        try {
            $catalogueItems = $this->catalogueService->list();

            $catalogueItemsData = [];
            foreach ($catalogueItems as $outreachItem) {

                $catalogueItemsData[] = $this->generateJSONFromCatalogueItem($outreachItem);
            }

            if ($catalogueItemsData == []) {
                parent::generateHttpResponse(200, array(ResponseCodes::RECORDSET_EMPTY));
            } else {
                parent::generateHttpResponse(200, array(ResponseCodes::RECORDSET_DATA), $catalogueItemsData);
            }

        } catch (PDOException) {
            parent::generateHttpResponse(503, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        } catch (Throwable) {
            parent::generateHttpResponse(500, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        }
    }

    /**
     * @param string|null $fileName
     * @return string
     */
    private function generateCatalogueItemImageURL(?string $fileName): string
    {
        if ($fileName != null) {
            $image = UPLOAD_FOLDER . $fileName;
        } else {
            $image = "static/catalogue_no_photo.jpg";
        }

        return (str_starts_with($image, '/') ? '' : '/').$image;
    }

    /**
     * @param string|null $fileName
     * @return string|null
     */
    private function generateCatalogueItemFileURL(?string $fileName): ?string
    {
        if ($fileName != null) {
            $image = UPLOAD_FOLDER . $fileName;
            return (str_starts_with($image, '/') ? '' : '/').$image;
        }
        return null;
    }

    /**
     * @param $catalogueItem
     * @return array
     */
    private function generateJSONFromCatalogueItem($catalogueItem): array
    {
        $imageName = $catalogueItem->getImage()?->getStorageFileName();
        $imageURL = $this->generateCatalogueItemImageURL($imageName);

        $files = array_map(function($file) {
            return [
                'id' => $file->getId(),
                'name' => $file->getOriginalFileName(),
                'url' => $this->generateCatalogueItemFileURL($file->getStorageFileName())
            ];
        }, $catalogueItem->getFiles() ?? []);

        $links = array_map(function($link) {
            return [
                'id' => $link->getId(),
                'name' => $link->getName(),
                'url' => $link->getUrl()
            ];
        }, $catalogueItem->getLinks() ?? []);

        return array(
            "id" => $catalogueItem->getId(),
            "acronym" => $catalogueItem->getAcronym(),
            "name" => $catalogueItem->getName(),
            "yearMinAge" => $catalogueItem->getYearMinAge(),
            "monthMinAge" => $catalogueItem->getMonthMinAge(),
            "yearMaxAge" => $catalogueItem->getYearMaxAge(),
            "monthMaxAge" => $catalogueItem->getMonthMaxAge(),
            "imageURL" => $imageURL,
            "authors" => $catalogueItem->getAuthors(),
            "time" => $catalogueItem->getTime(),
            "description" => $catalogueItem->getDescription(),
            "note" => $catalogueItem->getNote(),

            "files" => $files,
            "links" => $links,

            "areas" => $catalogueItem->getAreas(),
            "tags" => $catalogueItem->getTags(),
            "resourceTypes" => $catalogueItem->getResourceTypes(),
            "formats" => $catalogueItem->getFormats(),
            "applicationModes" => $catalogueItem->getApplicationModes()
        );
    }

    public function addFile($data): void
    {
        try {
            $catalogueItemId = parent::extractString($data, 'catalogueItemId');
            $name = parent::extractString($data, 'name');
            $file = $this->extractFile('file');

            $this->catalogueService->addFile($catalogueItemId, $file, $name);

            parent::generateHttpResponse(201, array(ResponseCodes::RECORDSET_DATA));

        } catch (ValidationException $e) {
            parent::generateHttpResponse(400, $e->getErrors());
        } catch (NotFoundException) {
            parent::generateHttpResponse(404, array(ResponseCodes::RECORDSET_EMPTY));
        } catch (PDOException) {
            parent::generateHttpResponse(503, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        } catch (Throwable) {
            parent::generateHttpResponse(500, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        }
    }

    public function addLink($data): void
    {
        try {
            $catalogueItemId = parent::extractString($data, 'catalogueItemId');
            $name = parent::extractString($data, 'name');
            $link = $this->extractFile('link');

            $this->catalogueService->addLink($catalogueItemId, $link, $name);

            parent::generateHttpResponse(201, array(ResponseCodes::RECORDSET_DATA));

        } catch (ValidationException $e) {
            parent::generateHttpResponse(400, $e->getErrors());
        } catch (NotFoundException) {
            parent::generateHttpResponse(404, array(ResponseCodes::RECORDSET_EMPTY));
        } catch (PDOException) {
            parent::generateHttpResponse(503, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        } catch (Throwable) {
            parent::generateHttpResponse(500, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        }
    }

    public function deleteFile($data): void
    {
        try {
            $catalogueItemId = parent::extractString($data, 'catalogueItemId');
            $fileId = parent::extractString($data, 'fileId');

            $this->catalogueService->deleteFile($catalogueItemId, $fileId);

            parent::generateHttpResponse(200, array(ResponseCodes::RECORDSET_EMPTY));

        } catch (ValidationException $e) {
            parent::generateHttpResponse(400, $e->getErrors());
        } catch (NotFoundException) {
            parent::generateHttpResponse(404, array(ResponseCodes::RECORDSET_EMPTY));
        } catch (PDOException) {
            parent::generateHttpResponse(503, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        } catch (Throwable) {
            parent::generateHttpResponse(500, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        }
    }

    public function deleteLink($data): void
    {
        try {
            $catalogueItemId = parent::extractString($data, 'catalogueItemId');
            $linkId = parent::extractString($data, 'linkId');

            $this->catalogueService->deleteLink($catalogueItemId, $linkId);

            parent::generateHttpResponse(200, array(ResponseCodes::RECORDSET_EMPTY));

        } catch (ValidationException $e) {
            parent::generateHttpResponse(400, $e->getErrors());
        } catch (NotFoundException) {
            parent::generateHttpResponse(404, array(ResponseCodes::RECORDSET_EMPTY));
        } catch (PDOException) {
            parent::generateHttpResponse(503, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        } catch (Throwable) {
            parent::generateHttpResponse(500, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        }
    }

    public function listAvailableFilters(): void
    {
        try {
            $areaFilters = $this->catalogueService->listAvailableAreas();
            $tagFilters = $this->catalogueService->listAvailableTags();
            $resourceTypeFilters = $this->catalogueService->listAvailableResourceTypes();
            $formatFilters = $this->catalogueService->listAvailableFormats();
            $applicationModeFilters = $this->catalogueService->listAvailableApplicationModes();

            parent::generateHttpResponse(200, array(ResponseCodes::RECORDSET_DATA), array(
                'areas' => $areaFilters,
                'tags' => $tagFilters,
                'resourceTypes' => $resourceTypeFilters,
                'formats' => $formatFilters,
                'applicationModes' => $applicationModeFilters
            ));
        } catch (PDOException) {
            parent::generateHttpResponse(503, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        } catch (Throwable) {
            parent::generateHttpResponse(500, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        }
    }
}