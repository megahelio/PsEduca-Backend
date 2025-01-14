<?php

require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../service/PyPService.php";
require_once __DIR__ . "/../core/ResponseCodes.php";

class PypAuthController extends BaseController
{
    private PyPService $pypService;

    public function __construct() {
        $this->pypService = new PyPService();
    }

    public function add($data): void
    {
        try{

            $idPyPItem = parent::extractString($data, 'idPyPItem');
            $idUser = parent::extractString($data, 'idUser');

            $this->pypService->addAuthorization($idPyPItem, $idUser);

            parent::generateHttpResponse(201, array(ResponseCodes::RECORDSET_EMPTY));

        } catch (ValidationException $e) {
            parent::generateHttpResponse(400, $e->getErrors());
        } catch (PDOException) {
            parent::generateHttpResponse(503, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        } catch (Throwable) {
            parent::generateHttpResponse(500, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        }
    }

    public function delete($data): void
    {
        try {

            $idPyPItem = parent::extractString($data, 'idPyPItem');
            $idUser = parent::extractString($data, 'idUser');

            $this->pypService->removeAuthorization($idPyPItem, $idUser);

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

    public function list(): void
    {
        try {
            $neededData = $this->pypService->listCurrentAuthorizations();

            $pypAuthItemsData = $neededData['currentPyPAuthorizations'];
            $pypData = $neededData['otherPyPItems'];
            $usersData = $neededData['otherUsers'];

            parent::generateHttpResponse(200, array(ResponseCodes::RECORDSET_DATA), array(
                "currentPyPAuthorizations" => $this->pypAuthsToArray($pypAuthItemsData),
                "otherPyPItems" => $this->otherPyPItemToArray($pypData),
                "otherUsers" => $this->otherUsersToArray($usersData)
            ));

        } catch (PDOException) {
            parent::generateHttpResponse(503, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        } catch (Throwable) {
            parent::generateHttpResponse(500, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        }
    }

    /**
     * @param mixed $pypAuthItemsData
     * @return array
     */
    public function pypAuthsToArray(mixed $pypAuthItemsData): array
    {
        $pypAuthsData = [];
        foreach ($pypAuthItemsData as $pypAuth) {
            $pypAuthsData[] = array(
                "idPyPItem" => $pypAuth->getIdPyPItem(),
                "idUser" => $pypAuth->getIdUser(),
            );
        }
        return $pypAuthsData;
    }

    /**
     * @param array $pypItems
     * @return array
     */
    public function otherPyPItemToArray(array $pypItems): array
    {
        $pypItemsData = [];
        foreach ($pypItems as $pypItem) {
            $pypItemsData[] = array(
                "idPyPItem" => $pypItem->getId(),
                "title" => $pypItem->getTitle(),
            );
        }
        return $pypItemsData;
    }

    /**
     * @param mixed $users
     * @return array
     */
    public function otherUsersToArray(mixed $users): array
    {
        $otherUsersData = [];
        foreach ($users as $user) {
            $otherUsersData[] = array(
                "idUser" => $user->getId(),
                "name" => $user->getUserName(),
                "isAdmin" => $user->getRole() === UserRole::ADMIN_GLOBAL,
            );
        }
        return $otherUsersData;
    }
}