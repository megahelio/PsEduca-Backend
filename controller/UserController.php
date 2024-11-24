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
		parent::__construct();

		$this->userService = new UserService();
	}

    public function get($data): void
    {
        try {
            $user = parent::authenticateUser();

            if ($user->getRole() != UserRole::ADMIN_GLOBAL) {
                parent::generateHttpResponse(403, ResponseCodes::FORBIDDEN_ACCESS_KO);
            }

            $userId = $data['id'] ?? null;

            $user = $this->userService->get($userId);

            $toRet = array(
                "id" => $user->getId(),
                "role" => $user->getRole()->name,
                "name" => $user->getUserName(),
                "fullName" => $user->getFullName()
            );
            parent::generateHttpResponse(200, ResponseCodes::RECORDSET_DATA, $toRet);

        } catch (\exception\NotFoundException) {
            parent::generateHttpResponse(404, ResponseCodes::NOT_FOUND_KO);
        } catch (PDOException) {
            parent::generateHttpResponse(503, ResponseCodes::INTERNAL_SERVER_ERROR_KO);
        } catch (Exception) {
            parent::generateHttpResponse(500, ResponseCodes::INTERNAL_SERVER_ERROR_KO);
        }
    }


	public function add($data): void
    {

//        $userName = $data['user_name'] ?? null;
//        $email = $data['user_email'] ?? null;
//        $password = $data['user_password'] ?? null;

        // Prueba
        $userName = $data['userName'] ?? null;
        $nombreCompleto = $data['fullName'] ?? null;
        $strRole = $data['role'] ?? null;
        $rol = UserRole::from($strRole);
        $contrasenha = $data['password'] ?? null;

		try {
            $errors = array();

//			if ($this->userService->userEmailExists($email)) {
//				$errors['user_name'] = "El correo electrónico ya existe.";
//			}
//
//			if ($this->userService->userNameExists($userName)) {
//                $errors['user_email'] = "El correo electrónico ya existe.";
//			}

            // Si existen, devuelve una respuesta HTTP 409
            if (sizeof($errors) > 0){
                parent::error409($errors);
            }

			// Si no existen, inserta el nuevo usuario en la base de datos
			$this->userService->add($userName, $nombreCompleto, $contrasenha, $rol);

			parent::generateHttpResponse(201, ResponseCodes::RECORDSET_DATA, array(
                "user_name" => $userName
            ));

		} catch (ValidationException $e) {
            parent::error400($e->getErrors());
        } catch (Exception) {
            parent::error500();
        }
	}

	public function delete($data): void
    {
		try {
            $user = parent::authenticateUser();

            $this->userService->delete($user->getUserName());

            parent::answerString204("User deleted");

		} catch (ValidationException $e) {
            parent::error400($e->getErrors());
        } catch (Exception) {
            parent::error500();
        }
	}


    /*
     * Posible código (necesita reparaciones) para hacer un cambio de contraseña mediante envío de código de
     * verificación por correo electrónico
     */

//    public function resetPassword($data){
//		// Comprueba si el usuario existe
//		try {
//			// Verifica si la contraseña es correcta
//			if ($this->userMapper->userEmailExists($data->user_email)) {
//
//				$arraySecurityElems = $this->userMapper->getSecurityElementsFromEmail($data->user_email);
//				$securityCode = $arraySecurityElems[0];
//
//				$securityCodeDate = $arraySecurityElems[1]->modify("+ 10 minutes");
//
//				$currentTime = new DateTime();
//
//				if ($securityCode == $data->security_code){
//					if ($securityCodeDate < $currentTime){
//						$this->error403("El código ha caducado.");
//					}else{
//						$user = $this->userMapper->getUserFromEmail($data->user_email);
//						$this->userMapper->updateUser($user->getUsername(), $data->user_password, $user->getEmail());
//						parent::answerString200("Se ha cambiado correctamente la contraseña. Ahora puedes iniciar sesión.");
//					}
//				}else{
//					parent::error403("Código no registrado");
//				}
//			} else {
//				// Si el email no existe, devuelve una respuesta HTTP 401
//				parent::error404("Email no existente.");
//			}
//		} catch (Exception $e) {
//			// Si la base de datos falla o hay un error imprevisto
//			parent::error500();
//		}
//	}
//
//	public function generateSecurityCode($data) {
//
//		try {
//			// Comprueba si el nombre de usuario o el correo electrónico ya existen
//			if (is_null($data->user_email) || !$this->userMapper->userEmailExists($data->user_email)) {
//				// Si no existe, devuelve una respuesta HTTP 404
//				parent::error404("El correo electrónico no existe.");
//			}
//			$user = $this->userMapper->getUserFromEmail($data->user_email);
//			$this->userMapper->generateAndSendSecurityCode($user);
//
//			parent::answerString200("Te hemos enviado un correo. Introduce el código que te hemos enviado.");
//
//		} catch (Exception $e) {
//			// Si la base de datos falla o hay un error imprevisto
//			parent::error500($e->getMessage());
//		}
//	}

}