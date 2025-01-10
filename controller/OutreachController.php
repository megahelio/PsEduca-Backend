<?php

require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../service/OutreachService.php";
require_once __DIR__ . "/../core/ResponseCodes.php";

class OutreachController extends BaseController
{
    private OutreachService $outreachService;

    public function __construct() {
        $this->outreachService = new OutreachService();
    }

    public function add($data): void
    {
        try{

            // Datos generales
            $type = parent::extractString($data, 'type');
            $title = parent::extractString($data, 'title');
            $description = parent::extractString($data, 'description');
            $image = $this->extractFile('image');

            // Datos específicos (en función del tipo)
            $externalURL = parent::extractString($data, 'externalURL');
            $pageContent = parent::extractString($data, 'pageContent');
            $file = $this->extractFile('file');

            $outreachItem = $this->outreachService->add($type, $title, $description, $image, $externalURL, $pageContent, $file);

            $imageName = $outreachItem->getImage()?->getStorageFileName();
            $imageURL = $this->generateEducationItemImageURL($imageName);

            $fileName = $outreachItem->getFile()?->getStorageFileName();
            $fileURL = $this->generateEducationItemFileURL($fileName);

            parent::generateHttpResponse(201, array(ResponseCodes::RECORDSET_DATA), array(
                // Datos generales
                "id" => $outreachItem->getId(),
                "type" => $outreachItem->getType(),
                "title" => $outreachItem->getTitle(),
                "description" => $outreachItem->getDescription(),
                "imageURL" => $imageURL,
                "lastModified" => $outreachItem->getLastModified(),
                // Datos específicos (en función del tipo)
                "externalURL" => $outreachItem->getExternalURL(),
                "pageContent" => $outreachItem->getPageContent(),
                "fileURL" => $fileURL
            ));

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
            $type = parent::extractString($data, 'type');
            $title = parent::extractString($data, 'title');
            $description = parent::extractString($data, 'description');
            $image = $this->extractFile('image');

            // Datos específicos (en función del tipo)
            $externalURL = parent::extractString($data, 'externalURL');
            $pageContent = parent::extractString($data, 'pageContent');
            $file = $this->extractFile('file');

            $outreachItem = $this->outreachService->edit($id, $type, $title, $description, $image, $externalURL, $pageContent, $file);

            $imageName = $outreachItem->getImage()?->getStorageFileName();
            $imageURL = $this->generateEducationItemImageURL($imageName);

            $fileName = $outreachItem->getFile()?->getStorageFileName();
            $fileURL = $this->generateEducationItemFileURL($fileName);

            parent::generateHttpResponse(200, array(ResponseCodes::RECORDSET_DATA), array(
                // Datos generales
                "id" => $outreachItem->getId(),
                "type" => $outreachItem->getType(),
                "title" => $outreachItem->getTitle(),
                "description" => $outreachItem->getDescription(),
                "imageURL" => $imageURL,
                "lastModified" => $outreachItem->getLastModified(),
                // Datos específicos (en función del tipo)
                "externalURL" => $outreachItem->getExternalURL(),
                "pageContent" => $outreachItem->getPageContent(),
                "fileURL" => $fileURL
            ));

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

            $this->outreachService->delete($id);

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

            $outreachItem = $this->outreachService->get($id);

            $imageName = $outreachItem->getImage()?->getStorageFileName();
            $imageURL = $this->generateEducationItemImageURL($imageName);

            $fileName = $outreachItem->getFile()?->getStorageFileName();
            $fileURL = $this->generateEducationItemFileURL($fileName);

            parent::generateHttpResponse(200, array(ResponseCodes::RECORDSET_DATA), array(
                // Datos generales
                "id" => $outreachItem->getId(),
                "type" => $outreachItem->getType(),
                "title" => $outreachItem->getTitle(),
                "description" => $outreachItem->getDescription(),
                "imageURL" => $imageURL,
                "lastModified" => $outreachItem->getLastModified(),
                // Datos específicos (en función del tipo)
                "externalURL" => $outreachItem->getExternalURL(),
                "pageContent" => $outreachItem->getPageContent(),
                "fileURL" => $fileURL
            ));

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
            $outreachItems = $this->outreachService->list();

            $outreachItemsData = [];
            foreach ($outreachItems as $outreachItem) {

                $imageName = $outreachItem->getImage()?->getStorageFileName();
                $imageURL = $this->generateEducationItemImageURL($imageName);

                $fileName = $outreachItem->getFile()?->getStorageFileName();
                $fileURL = $this->generateEducationItemFileURL($fileName);

                $outreachItemsData[] = array(
                    // Datos generales
                    "id" => $outreachItem->getId(),
                    "type" => $outreachItem->getType(),
                    "title" => $outreachItem->getTitle(),
                    "description" => $outreachItem->getDescription(),
                    "imageURL" => $imageURL,
                    "lastModified" => $outreachItem->getLastModified(),
                    // Datos específicos (en función del tipo)
                    "externalURL" => $outreachItem->getExternalURL(),
                    "pageContent" => $outreachItem->getPageContent(),
                    "fileURL" => $fileURL
                );
            }

            if ($outreachItemsData == []) {
                parent::generateHttpResponse(200, array(ResponseCodes::RECORDSET_EMPTY));
            } else {
                parent::generateHttpResponse(200, array(ResponseCodes::RECORDSET_DATA), $outreachItemsData);
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
    private function generateEducationItemImageURL(?string $fileName): string
    {
        if ($fileName != null) {
            $image = UPLOAD_FOLDER . $fileName;
        } else {
            $image = "static/outreach_no_photo.jpg";
        }

        return (str_starts_with($image, '/') ? '' : '/').$image;
    }

    /**
     * @param string|null $fileName
     * @return string|null
     */
    private function generateEducationItemFileURL(?string $fileName): ?string
    {
        if ($fileName != null) {
            $image = UPLOAD_FOLDER . $fileName;
            return (str_starts_with($image, '/') ? '' : '/').$image;
        }
        return null;
    }
}