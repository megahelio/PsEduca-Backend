<?php

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use JetBrains\PhpStorm\NoReturn;

require_once(__DIR__."/../model/User.php");
require_once(__DIR__ . "/../service/UserService.php");

/**
* Class BaseController
*
* Superclass for Rest endpoints
*
* It contains a method to authenticate users via JWT against
* the User database via UserMapper.
*/
class BaseController {

    protected static string $jwt_key = JWT_KEY;
    private UserService $userService;

	public function __construct() {
        $this->userService = new UserService();
    }

	/**
	* Authenticates the current request. If the request does not contain
	* auth credentials, it will generate a 401 response code and end PHP processing
	* If the request contain credentials, it will be checked against the database.
	* If the credentials are ok, it will return the User object just logged. If the
	* credentials are invalid, it will generate a 401 code as well and end PHP
	* processing.
	*
	* @return User the user just authenticated.
	*/
	public function authenticateUser() : User {
        try {
            $authorizationHeader = apache_request_headers()["Authorization"];

            if (empty($authorizationHeader)){
                $this->generateHttpResponse(401, ResponseCodes::AUTHENTICATION_REQUIRED_KO);
            }else{
                // Separar el tipo de autenticación y el token
                list($type, $token) = explode(' ', $authorizationHeader, 2);

                // Verificar si el tipo es 'Bearer'
                if ($type === 'Bearer') {

                    // Usar firebase/php-jwt para verificar y decodificar el token JWT
                    $decoded_array = (array) JWT::decode($token, new Key(BaseController::$jwt_key, 'HS256'));

                    if (!empty($decoded_array["aud"]) && $this->userService->userNameExists($decoded_array["aud"])) {
                        return $this->userService->get($decoded_array["aud"]);
                    }else{
                        $this->generateHttpResponse(401, ResponseCodes::AUTHENTICATION_INVALID_KO);
                    }

                } else {
                    // Tipo de autenticación no soportado
                    $this->generateHttpResponse(400, ResponseCodes::AUTHENTICATION_TYPE_NOT_SUPPORTED_KO);
                }
            }
        }catch (\exception\NotFoundException){
            $this->generateHttpResponse(401, ResponseCodes::NOT_FOUND_KO);
        }catch (ExpiredException){
            $this->generateHttpResponse(401, ResponseCodes::AUTHENTICATION_EXPIRED_KO);
        }catch (SignatureInvalidException|UnexpectedValueException) {
            $this->generateHttpResponse(401, ResponseCodes::AUTHENTICATION_INVALID_KO);
        } catch (Exception){
            $this->generateHttpResponse(500, ResponseCodes::INTERNAL_SERVER_ERROR_KO);
        }
	}

    /**
     * Genera y envía una respuesta JSON con un código HTTP específico.
     *
     * @param int $httpCode Código de respuesta HTTP.
     * @param array|string $code Código(s) adicional(es) que se incluirán en la respuesta.
     * @param array|null $resource (Opcional) Datos adicionales para incluir en el JSON.
     * @return void Termina la ejecución del script.
     */
    #[NoReturn] function generateHttpResponse(int $httpCode, array|string $code, array $resource = null): void
    {
        // Determinar si el código HTTP representa éxito
        $isSuccess = $httpCode >= 200 && $httpCode < 300;

        // Crear la respuesta
        $response = [
            'ok' => $isSuccess,
            'code' => $code,
        ];

        // Agregar el recurso si se proporciona
        if ($resource !== null) {
            $response['resource'] = $resource;
        }

        // Configurar el código de respuesta HTTP
        http_response_code($httpCode);

        // Enviar el JSON como respuesta
        header('Content-Type: application/json');
        die(json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
}
