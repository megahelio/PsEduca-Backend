<?php

//require_once '../vendor/autoload.php';

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use Firebase\JWT\SignatureInvalidException;
use JetBrains\PhpStorm\NoReturn;

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../service/UserService.php");

/**
* Class BaseController
*
* Superclass for Rest endpoints
*
* It contains a method to authenticate users via JWT against
* the User database via UserMapper.
*/
class BaseController {

    protected static $jwt_key = 'jwt_key';
    private $userService;

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
                $this->error401('This operation requires authentication');
            }else{
                // Separar el tipo de autenticación y el token
                list($type, $token) = explode(' ', $authorizationHeader, 2);

                // Verificar si el tipo es 'Bearer'
                if ($type === 'Bearer') {

                    // Usar firebase/php-jwt para verificar y decodificar el token JWT
                    $decoded = JWT::decode($token, new Key(BaseController::$jwt_key, 'HS256'));
                    // Pass a stdClass in as the third parameter to get the decoded header values
                    $decoded_array = (array) JWT::decode($token, new Key(BaseController::$jwt_key, 'HS256'));

                    if (!empty($decoded_array["aud"]) && $this->userService->userNameExists($decoded_array["aud"])) {
                        return $this->userService->get($decoded_array["aud"]);
                    }else{
                        $this->error401('This operation requires authentication');
                    }

                } else {
                    // Tipo de autenticación no soportado
                    $this->error400(array('Authentication type not supported'));
                }
            }
        }catch (ExpiredException $exception){
            $this->error401($exception->getMessage());
        }catch (SignatureInvalidException $exception) {
            $this->error400($exception->getMessage());
        }catch (UnexpectedValueException $exception){
            $this->error400($exception->getMessage());
        }catch (Exception $exception){
            $this->error500();
        }
	}

	/**
	 * The request succeeded. The result meaning of "success" depends on the HTTP method:
	 * <ul>
	 *   <li>GET: The resource has been fetched and transmitted in the message body.</li>
	 * 	 <li>HEAD: The representation headers are included in the response without any message body.</li>
	 * 	 <li>PUT or POST: The resource describing the result of the action is transmitted in the message body.</li>
	 * 	 <li>TRACE: The message body contains the request message as received by the server.</li>
	 * </ul>
	 * @param string $message
	 * @return void
	 */
	#[NoReturn] public function answerString200 (string $message=""): void
	{
		header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		die($message);
	}


	/**
	 * The HTTP 204 No Content success status response code indicates that a request has succeeded, but that the client
	 * doesn't need to navigate away from its current page.
	 *
	 * A 204 response is cacheable by default (an ETag header is included in such a response).
	 * @param string $message
	 * @return void
	 */
	#[NoReturn] public function answerString204 (string $message=""): void
	{
		header($_SERVER['SERVER_PROTOCOL'].' 204 No Content');
		die($message);
	}

	/**
	 * The request succeeded.
	 * @param array $toJson the array to be converted to JSON
	 * @return void
	 */
	#[NoReturn] public function answerJson200 (array $toJson): void
	{
		header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		header('Content-Type: application/json');
		die(json_encode($toJson));
	}




	#[NoReturn] public function answerJson201 (array $toJson): void
	{
		header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
		header('Content-Type: application/json');
		die(json_encode($toJson));
	}


	#[NoReturn] public function answerJson204 (array $toJson): void
	{
		header($_SERVER['SERVER_PROTOCOL'].' 204 No content');
		header('Content-Type: application/json');
		die(json_encode($toJson));
	}

	/**
     * 400 Bad Request
	 * The server cannot or will not process the request due to something that is perceived to be a client error
	 * (e.g., malformed request syntax, invalid request message framing, or deceptive request routing).
	 * @param $errors array map with all errors. Each entry on the map is made up of key-value pairs (key: <para_error>, content: <description_error>)
	 * @return void
	 */
	#[NoReturn] public function error400(array $errors): void
	{
		http_response_code(400);
		header('Content-Type: application/json');
		die(json_encode($errors));
	}

	/**
     * 401 Unauthorized
	 * Although the HTTP standard specifies "unauthorized", semantically this response means "unauthenticated".
	 * That is, the client must authenticate itself to get the requested response.
	 * @param $message string an explanation of the error.
	 * @return void
	 */
	#[NoReturn] public function error401(string $error): void
	{
		http_response_code(401);
        header($_SERVER['SERVER_PROTOCOL'].' 401 Unauthorized');
        header("WWW-Authenticate: Bearer realm=\"$error\"");
//        die(json_encode($errors));
        die();
	}

	/**
     * 403 Forbidden
	 * The client does not have access rights to the content; that is, it is unauthorized, so the server is refusing to
	 * give the requested resource. Unlike 401 Unauthorized, the client's identity is known to the server.
	 * @param $message string an explanation of the error.
	 * @return void
	 */
	#[NoReturn] public function error403(array $errors): void
	{
		header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
        die(json_encode($errors));
	}

	/**
     * 404 Not Found
	 * The server cannot find the requested resource. In the browser, this means the URL is not recognized. In an API,
	 * this can also mean that the endpoint is valid but the resource itself does not exist. Servers may also send this
	 * response instead of 403 Forbidden to hide the existence of a resource from an unauthorized client. This response
	 * code is probably the most well known due to its frequent occurrence on the web.
	 * @param $message string an explanation of the error.
	 * @return void
	 */
	#[NoReturn] public function error404(array $errors): void
	{
		header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
        die(json_encode($errors));
	}


    /**
     *  409 Conflict
     * @param $message string an explanation of the error.
     * @return void
     */
	#[NoReturn] public function error409(array $errors): void
	{
		header($_SERVER['SERVER_PROTOCOL'].' 409 Conflict');
        die(json_encode($errors));
	}

	/**
     * 500 Internal Server Error
	 * The server has encountered a situation it does not know how to handle.
	 * @return void
	 */
	#[NoReturn] public function error500(): void
	{
		header($_SERVER['SERVER_PROTOCOL'].' 500 Internal Server Error');
		die();
	}

	/**
     * 503 Service Unavailable
	 * The server is not ready to handle the request. Common causes are a server that is down for maintenance or that
	 * is overloaded. Note that together with this response, a user-friendly page explaining the problem should be sent.
	 * This response should be used for temporary conditions and the HTTP header should, if possible, contain the
	 * estimated time before the recovery of the service. The webmaster must also take care about the caching-related
	 * headers that are sent along with this response, as these temporary condition responses should usually not be
	 * cached.
	 * @return void
	 */
	#[NoReturn] public function error503($message): void
	{
		header($_SERVER['SERVER_PROTOCOL'].' 503 Service Unavailable');
//		header('Retry-After:');
		die($message);
	}
}
