<?php

require_once(__DIR__ . "/BaseController.php");
require_once (__DIR__ . "/../core/ResponseCodes.php");
require_once(__DIR__."/../model/User.php");
require_once(__DIR__ . "/../service/UserService.php");

/**
 * Class UserController
 *
 * It contains operations for adding and removing users.
 */
class UserController extends BaseController {

    private UserService $userService;

    public function __construct() {

        $this->userService = new UserService();
    }

    public function get($data): void
    {
        try {

            $userId = parent::extractString($data, 'id');

            $user = $this->userService->get($userId);

            parent::generateHttpResponse(200, array(ResponseCodes::RECORDSET_DATA), array(
                "id" => $user->getId(),
                "role" => $user->getRole()->name,
                "name" => $user->getUserName(),
                "fullName" => $user->getFullName()
            ));

        } catch (ValidationException $e) {
            parent::generateHttpResponse(400, $e->getErrors());
        } catch (NotFoundException) {
            parent::generateHttpResponse(404, array(ResponseCodes::USUARIO_NO_ENCONTRADO_A_KO));
        } catch (PDOException) {
            parent::generateHttpResponse(503, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        } catch (Throwable) {
            parent::generateHttpResponse(500, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        }
    }

    public function list() : void
    {
        try {

            $users = $this->userService->list();

            $usersArray = array();

            foreach ($users as $user) {
                $usersArray[] = array(
                    "id" => $user->getId(),
                    "role" => $user->getRole()->name,
                    "name" => $user->getUserName(),
                    "fullName" => $user->getFullName()
                );
            }

            parent::generateHttpResponse(200, array(ResponseCodes::RECORDSET_DATA), $usersArray);

        } catch (PDOException) {
            parent::generateHttpResponse(503, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        } catch (Throwable) {
            parent::generateHttpResponse(500, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        }

    }

    public function add($data): void
    {
        try {

            $userName = parent::extractString($data, 'userName');
            $fullName = parent::extractString($data, 'fullName');
            $password = parent::extractString($data, 'password');
            $strRole = parent::extractString($data, 'role');

            $newUser = $this->userService->add($userName, $fullName, $password, $strRole);

            parent::generateHttpResponse(201, array(ResponseCodes::RECORDSET_DATA), array(
                "id" => $newUser->getId(),
                "role" => $newUser->getRole()->name,
                "name" => $newUser->getUserName(),
                "fullName" => $newUser->getFullName(),
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
            $userName = parent::extractString($data, 'userName');
            $fullName = parent::extractString($data, 'fullName');
            $password = parent::extractString($data, 'password');
            $strRole = parent::extractString($data, 'role');

            $user = $this->userService->edit($id, $userName, $fullName, $password, $strRole);

            parent::generateHttpResponse(200, array(ResponseCodes::RECORDSET_DATA), array(
                "id" => $user->getId(),
                "role" => $user->getRole()->name,
                "name" => $user->getUserName(),
                "fullName" => $user->getFullName(),
            ));

        } catch (ValidationException $e) {
            parent::generateHttpResponse(400, $e->getErrors());
        } catch (NotFoundException) {
            parent::generateHttpResponse(404, array(ResponseCodes::USUARIO_NO_ENCONTRADO_A_KO));
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

            $this->userService->delete($id);

            // El código 204 NO CONTENT indica que la petición se ha completado con éxito, pero su respuesta no tiene ningún contenido.
            // Como tengo que devolver un JSON con ok y code, tengo que devolver un 200 OK.
            parent::generateHttpResponse(200, array(ResponseCodes::RECORDSET_EMPTY));

        } catch (ValidationException $e) {
            parent::generateHttpResponse(400, $e->getErrors());
        } catch (NotFoundException) {
            parent::generateHttpResponse(404, array(ResponseCodes::USUARIO_NO_ENCONTRADO_A_KO));
        } catch (PDOException) {
            parent::generateHttpResponse(503, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        } catch (Throwable) {
            parent::generateHttpResponse(500, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        }
    }


    /*
     * Posible código (necesita reparaciones) para hacer un cambio de contraseña mediante envío de código de
     * verificación por correo electrónico
     */

//    public function resetPassword($data){
//        // Comprueba si el usuario existe
//        try {
//            // Verifica si la contraseña es correcta
//            if ($this->userMapper->userEmailExists($data->user_email)) {
//
//                $arraySecurityElems = $this->userMapper->getSecurityElementsFromEmail($data->user_email);
//                $securityCode = $arraySecurityElems[0];
//
//                $securityCodeDate = $arraySecurityElems[1]->modify("+ 10 minutes");
//
//                $currentTime = new DateTime();
//
//                if ($securityCode == $data->security_code){
//                    if ($securityCodeDate < $currentTime){
//                        $this->error403("El código ha caducado.");
//                    }else{
//                        $user = $this->userMapper->getUserFromEmail($data->user_email);
//                        $this->userMapper->updateUser($user->getUsername(), $data->user_password, $user->getEmail());
//                        parent::answerString200("Se ha cambiado correctamente la contraseña. Ahora puedes iniciar sesión.");
//                    }
//                }else{
//                    parent::error403("Código no registrado");
//                }
//            } else {
//                // Si el email no existe, devuelve una respuesta HTTP 401
//                parent::error404("Email no existente.");
//            }
//        } catch (Exception $e) {
//            // Si la base de datos falla o hay un error imprevisto
//            parent::error500();
//        }
//    }
//
//    public function generateSecurityCode($data) {
//
//        try {
//            // Comprueba si el nombre de usuario o el correo electrónico ya existen
//            if (is_null($data->user_email) || !$this->userMapper->userEmailExists($data->user_email)) {
//                // Si no existe, devuelve una respuesta HTTP 404
//                parent::error404("El correo electrónico no existe.");
//            }
//            $user = $this->userMapper->getUserFromEmail($data->user_email);
//            $this->userMapper->generateAndSendSecurityCode($user);
//
//            parent::answerString200("Te hemos enviado un correo. Introduce el código que te hemos enviado.");
//
//        } catch (Exception $e) {
//            // Si la base de datos falla o hay un error imprevisto
//            parent::error500($e->getMessage());
//        }
//    }

}
