<?php

require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../service/MembersService.php";
require_once __DIR__ . "/../core/ResponseCodes.php";

class MemberController extends BaseController
{
    private MembersService $membersService;

    public function __construct() {

        $this->membersService = new MembersService();
    }

    public function add($data): void
    {

        try {
            $name = parent::extractString($data, 'name');
            $email = parent::extractString($data, 'email');
            $description = parent::extractString($data, 'description');
            $referenceURL = parent::extractString($data, 'referenceURL');

            $file = $this->extractFile();

            $member = $this->membersService->add($name, $email, $description, $referenceURL, $file);

            $fileName = $member->getImage()?->getStorageFileName();
            $imageURL = $this->generateMemberImageURL($fileName);

            parent::generateHttpResponse(201, array(ResponseCodes::RECORDSET_DATA), array(
                "id" => $member->getId(),
                "name" => $member->getName(),
                "email" => $member->getEmail(),
                "description" => $member->getDescription(),
                "referenceURL" => $member->getReferenceURL(),
                "imageURL" => $imageURL
            ));

        } catch (ValidationException $e) {
            parent::generateHttpResponse(400, $e->getErrors());
        } catch (PDOException) {
            parent::generateHttpResponse(503, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        } catch (Throwable $e) {
            echo $e;
            parent::generateHttpResponse(500, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        }
    }

    public function edit($data): void
    {
        try {
            $id = parent::extractString($data, 'id');

            $name = parent::extractString($data, 'name');
            $email = parent::extractString($data, 'email');
            $description = parent::extractString($data, 'description');
            $referenceURL = parent::extractString($data, 'referenceURL');

            $file = $this->extractFile();

            $member = $this->membersService->edit($id, $name, $email, $description, $referenceURL, $file);

            $fileName = $member->getImage()?->getStorageFileName();
            $imageURL = $this->generateMemberImageURL($fileName);

            parent::generateHttpResponse(200, array(ResponseCodes::RECORDSET_DATA), array(
                "id" => $member->getId(),
                "name" => $member->getName(),
                "email" => $member->getEmail(),
                "description" => $member->getDescription(),
                "referenceURL" => $member->getReferenceURL(),
                "imageURL" => $imageURL
            ));

        } catch (ValidationException $e) {
            parent::generateHttpResponse(400, $e->getErrors());
        } catch (NotFoundException) {
            parent::generateHttpResponse(404, array(ResponseCodes::RECORDSET_EMPTY));
        } catch (PDOException) {
            parent::generateHttpResponse(503, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        } catch (Throwable $e) {
            echo $e;
            parent::generateHttpResponse(500, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        }
    }

    public function delete($data): void
    {
        try {
            $id = parent::extractString($data, 'id');

            $this->membersService->delete($id);

            parent::generateHttpResponse(200, array(ResponseCodes::RECORDSET_EMPTY));

        } catch (ValidationException $e) {
            parent::generateHttpResponse(400, $e->getErrors());
        } catch (NotFoundException) {
            parent::generateHttpResponse(404, array(ResponseCodes::RECORDSET_EMPTY));
        } catch (PDOException) {
            parent::generateHttpResponse(503, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        } catch (Throwable $e) {
            echo $e;
            parent::generateHttpResponse(500, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        }
    }

    public function get($data): void
    {
        try {
            $id = parent::extractString($data, 'id');

            $member = $this->membersService->get($id);

            $fileName = $member->getImage()?->getStorageFileName();
            $imageURL = $this->generateMemberImageURL($fileName);

            parent::generateHttpResponse(200, array(ResponseCodes::RECORDSET_DATA), array(
                "id" => $member->getId(),
                "name" => $member->getName(),
                "email" => $member->getEmail(),
                "description" => $member->getDescription(),
                "referenceURL" => $member->getReferenceURL(),
                "imageURL" => $imageURL
            ));

        } catch (ValidationException $e) {
            parent::generateHttpResponse(400, $e->getErrors());
        } catch (NotFoundException) {
            parent::generateHttpResponse(404, array(ResponseCodes::RECORDSET_EMPTY));
        } catch (PDOException) {
            parent::generateHttpResponse(503, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        } catch (Throwable $e) {
            echo $e;
            parent::generateHttpResponse(500, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        }
    }

    public function list(): void
    {
        try {
            $members = $this->membersService->list();

            $membersData = [];
            foreach ($members as $member) {

                $fileName = $member->getImage()?->getStorageFileName();
                $imageURL = $this->generateMemberImageURL($fileName);

                $membersData[] = array(
                    "id" => $member->getId(),
                    "name" => $member->getName(),
                    "email" => $member->getEmail(),
                    "description" => $member->getDescription(),
                    "referenceURL" => $member->getReferenceURL(),
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
        } catch (Throwable $e) {
            echo $e;
            parent::generateHttpResponse(500, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        }
    }

    /**
     * @param string|null $fileName
     * @return string
     */
    private function generateMemberImageURL(?string $fileName): string
    {
        if ($fileName != null) {
            $image = UPLOAD_FOLDER . $fileName;
        } else {
            $image = "static/member_no_photo.png";
        }

         $imageURN = (str_starts_with($image, '/') ? '' : '/').$image;

        return $imageURN;
    }

}