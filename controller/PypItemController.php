<?php

require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../service/PyPService.php";
require_once __DIR__ . "/../core/ResponseCodes.php";

class PypItemController extends BaseController
{
    private PyPService $pypService;

    public function __construct() {
        $this->pypService = new PyPService();
    }

    public function add($data): void
    {
        try{

            $title = parent::extractString($data, 'title');
            $description = parent::extractString($data, 'description');
            $image = $this->extractFile('image');
            $externalURL = parent::extractString($data, 'externalURL');

            $pypItem = $this->pypService->add($title, $description, $image, $externalURL);

            parent::generateHttpResponse(201, array(ResponseCodes::RECORDSET_DATA),
                $this->generateJSONFromPyPItem($pypItem));

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
            $title = parent::extractString($data, 'title');
            $description = parent::extractString($data, 'description');
            $image = $this->extractFile('image');
            $externalURL = parent::extractString($data, 'externalURL');

            $pypItem = $this->pypService->edit($id, $title, $description, $image, $externalURL);

            parent::generateHttpResponse(200, array(ResponseCodes::RECORDSET_DATA),
                $this->generateJSONFromPyPItem($pypItem));

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

            $this->pypService->delete($id);

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

            $pypItem = $this->pypService->get($id);

            parent::generateHttpResponse(200, array(ResponseCodes::RECORDSET_DATA),
                $this->generateJSONFromPyPItem($pypItem));

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
            $pypItems = $this->pypService->list();

            $pypItemsData = [];
            foreach ($pypItems as $pypItem) {
                $pypItemsData[] = $this->generateJSONFromPyPItem($pypItem);
            }

            if ($pypItemsData == []) {
                parent::generateHttpResponse(200, array(ResponseCodes::RECORDSET_EMPTY));
            } else {
                parent::generateHttpResponse(200, array(ResponseCodes::RECORDSET_DATA), $pypItemsData);
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
            $image = "static/pyp_no_photo.jpg";
        }

        return (str_starts_with($image, '/') ? '' : '/').$image;
    }

    /**
     * @param $pypItem
     * @return array
     */
    public function generateJSONFromPyPItem($pypItem): array
    {
        $imageName = $pypItem->getImage()?->getStorageFileName();
        $imageURL = $this->generateEducationItemImageURL($imageName);

        return array(
            "id" => $pypItem->getId(),
            "title" => $pypItem->getTitle(),
            "description" => $pypItem->getDescription(),
            "imageURL" => $imageURL,
            "externalURL" => $pypItem->getExternalURL(),
        );
    }
}