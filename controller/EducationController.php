<?php

require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../service/EducationService.php";
require_once __DIR__ . "/../core/ResponseCodes.php";

class EducationController extends BaseController
{
    private EducationService $educationService;

    public function __construct() {
        $this->educationService = new EducationService();
    }

    public function add($data): void
    {
        try{

            $type = parent::extractString($data, 'type');
            $title = parent::extractString($data, 'title');
            $description = parent::extractString($data, 'description');
            $referenceURL = parent::extractString($data, 'referenceURL');
            $initYear = parent::extractString($data, 'initYear');
            $endYear = parent::extractString($data, 'endYear');

            $file = $this->extractFile();

            $educationItem = $this->educationService->add($type, $title, $description, $referenceURL, $initYear, $endYear, $file);

            $fileName = $educationItem->getImage()?->getStorageFileName();
            $imageURL = $this->generateEducationItemImageURL($fileName);

            parent::generateHttpResponse(201, array(ResponseCodes::RECORDSET_DATA), array(
                "id" => $educationItem->getId(),
                "type" => $educationItem->getType(),
                "title" => $educationItem->getTitle(),
                "description" => $educationItem->getDescription(),
                "referenceURL" => $educationItem->getReferenceURL(),
                "initYear" => $educationItem->getInitYear(),
                "endYear" => $educationItem->getEndYear(),
                "imageURL" => $imageURL
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
            $id = parent::extractString($data, 'id');

            $type = parent::extractString($data, 'type');
            $title = parent::extractString($data, 'title');
            $description = parent::extractString($data, 'description');
            $referenceURL = parent::extractString($data, 'referenceURL');
            $initYear = parent::extractString($data, 'initYear');
            $endYear = parent::extractString($data, 'endYear');

            $file = $this->extractFile();

            $educationItem = $this->educationService->edit($id, $type, $title, $description, $referenceURL, $initYear, $endYear, $file);

            $fileName = $educationItem->getImage()?->getStorageFileName();
            $imageURL = $this->generateEducationItemImageURL($fileName);

            parent::generateHttpResponse(200, array(ResponseCodes::RECORDSET_DATA), array(
                "id" => $educationItem->getId(),
                "type" => $educationItem->getType(),
                "title" => $educationItem->getTitle(),
                "description" => $educationItem->getDescription(),
                "referenceURL" => $educationItem->getReferenceURL(),
                "initYear" => $educationItem->getInitYear(),
                "endYear" => $educationItem->getEndYear(),
                "imageURL" => $imageURL
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

            $this->educationService->delete($id);

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

            $educationItem = $this->educationService->get($id);

            $fileName = $educationItem->getImage()?->getStorageFileName();
            $imageURL = $this->generateEducationItemImageURL($fileName);

            parent::generateHttpResponse(200, array(ResponseCodes::RECORDSET_DATA), array(
                "id" => $educationItem->getId(),
                "type" => $educationItem->getType(),
                "title" => $educationItem->getTitle(),
                "description" => $educationItem->getDescription(),
                "referenceURL" => $educationItem->getReferenceURL(),
                "initYear" => $educationItem->getInitYear(),
                "endYear" => $educationItem->getEndYear(),
                "imageURL" => $imageURL
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
            $educationItems = $this->educationService->list();

            $membersData = [];
            foreach ($educationItems as $educationItem) {

                $fileName = $educationItem->getImage()?->getStorageFileName();
                $imageURL = $this->generateEducationItemImageURL($fileName);

                $membersData[] = array(
                    "id" => $educationItem->getId(),
                    "type" => $educationItem->getType(),
                    "title" => $educationItem->getTitle(),
                    "description" => $educationItem->getDescription(),
                    "referenceURL" => $educationItem->getReferenceURL(),
                    "initYear" => $educationItem->getInitYear(),
                    "endYear" => $educationItem->getEndYear(),
                    "imageURL" => $imageURL
                );
            }

            if ($membersData == []) {
                parent::generateHttpResponse(200, array(ResponseCodes::RECORDSET_EMPTY));
            } else {
                parent::generateHttpResponse(200, array(ResponseCodes::RECORDSET_DATA), $membersData);
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
            $image = "static/education_no_photo.jpg";
        }

        $serverURL = SERVER_URL . (str_ends_with(SERVER_URL, '/') ? '' : '/');

        return $serverURL . $image;
    }

}