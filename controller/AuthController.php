<?php

require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../core/ResponseCodes.php";

class AuthController extends BaseController
{
    private UserService $userService;

    public function __construct() {
        parent::__construct();

        $this->userService = new UserService();
    }

    public function login($data): void
    {
        $userName = $data['userName'] ?? null;
        $password = $data['password'] ?? null;

        try {
            $data = $this->userService->login($userName, $password);
            parent::generateHttpResponse(200, ResponseCodes::RECORDSET_DATA, array(
                "jwtToken" => $data['jwtToken'],
                "tokenExpirationDate" => $data['tokenExpirationDate'],
                "user" => array(
                    "id" => $data['user']->getId(),
                    "role" => $data['user']->getRole()->name,
                    "name" => $data['user']->getUserName(),
                    "fullName" => $data['user']->getFullName(),
                )
            ));
        } catch (\exception\NotFoundException) {
            parent::generateHttpResponse(404, array(ResponseCodes::USER_CREDENTIALS_INVALID_KO));
        } catch (PDOException $e) {
            parent::generateHttpResponse(503, ResponseCodes::INTERNAL_SERVER_ERROR_KO);
        } catch (Exception $e) {
            parent::generateHttpResponse(500, ResponseCodes::INTERNAL_SERVER_ERROR_KO);
        }
    }
}