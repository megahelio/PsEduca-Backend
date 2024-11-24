<?php
require_once __DIR__ . "/core/config_file.php";
require_once __DIR__ . '/vendor/autoload.php';

function run(): void {
	$headers = apache_request_headers();

	try {
		// Validar que los encabezados incluyan "controller" y "action"
		if (!isset($headers["controller"]) || !isset($headers["action"])) {
			http_response_code(400);
			die("Missing controller or action");
		}

		if ($_SERVER['REQUEST_METHOD'] != 'POST') {
			http_response_code(405);
			die("Only POST requests are accepted");
		}

		$controllerName = $headers["controller"];
		$controllerClassName = getControllerClassName($controllerName);

		$filePath = __DIR__ . "/controller/" . $controllerClassName . ".php";
		if (!file_exists($filePath)) {
			http_response_code(404);
			die("Controller '".$headers["controller"]."' does not exist");
		}
		require_once($filePath);

		if (!class_exists($controllerClassName)) {
			http_response_code(404);
			die("Controller '".$headers["controller"]."' does not exist");
		}

		$restController = new $controllerClassName();

		$actionName = $headers["action"];
		if (!method_exists($restController, $actionName)) {
			http_response_code(404);
			die("Action '$actionName' does not exist in controller '$controllerClassName'");
		}

		$restController->$actionName($_POST);

	} catch (PDOException $ex) {
        http_response_code(503);
        $response = [
            'ok' => false,
            'code' => [ResponseCodes::INTERNAL_SERVER_ERROR_KO],
        ];
        header('Content-Type: application/json');
        die(json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

    } catch (Exception $ex) {
		http_response_code(500);
        $response = [
            'ok' => false,
            'code' => [ResponseCodes::INTERNAL_SERVER_ERROR_KO],
        ];
        header('Content-Type: application/json');
        die(json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
	}
}

/**
 * Obtiene el nombre de la clase del controlador con base en el nombre recibido
 *
 * @param string $controllerName El nombre del controlador en los encabezados
 * @return string El nombre de la clase del controlador
 */
function getControllerClassName(string $controllerName): string {
	return ucfirst($controllerName) . "Controller";
}

run();
