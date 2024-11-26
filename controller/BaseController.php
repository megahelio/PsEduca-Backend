<?php

//use Firebase\JWT\ExpiredException;
//use Firebase\JWT\JWT;
//use Firebase\JWT\Key;
//use Firebase\JWT\SignatureInvalidException;
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
            $authorizationHeader = apache_request_headers()['Authorization']??null;
            if (empty($authorizationHeader)) {
                $this->generateHttpResponse(401, ResponseCodes::AUTHENTICATION_REQUIRED_KO);
            }

            if (empty($authorizationHeader)){
                $this->generateHttpResponse(401, ResponseCodes::AUTHENTICATION_REQUIRED_KO);
            }else{
                // Separar el tipo de autenticación y el token
                list($type, $token) = explode(' ', $authorizationHeader, 2);

                // Verificar si el tipo es 'Bearer'
                if ($type === 'Bearer') {

                    // Usar firebase/php-jwt para verificar y decodificar el token JWT
                    $jwt_instance = new JWT();
                    $decoded_array = $jwt_instance->decode($token);

                    return $this->userService->get($decoded_array["sub"]);

                } else {
                    // Tipo de autenticación no soportado
                    $this->generateHttpResponse(400, ResponseCodes::AUTHENTICATION_TYPE_NOT_SUPPORTED_KO);
                }
            }
        } catch (\exception\NotFoundException $exc) {
            $this->generateHttpResponse(401, ResponseCodes::AUTHENTICATION_INVALID_KO);
        } catch (ValidationException $exc) {
            $this->generateHttpResponse(401, $exc->getErrors());
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
