<?php

require_once __DIR__ . "/core/config_file.php";
require_once __DIR__ . "/core/JWT.php";

function run(): void
{

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Configuración para solicitudes preflight (OPTIONS)
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, OPTIONS");
        header("Access-Control-Allow-Headers: Authorization, Content-Type");
        http_response_code(200);
        exit();
    }

    // Configuración CORS para todas las solicitudes
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, OPTIONS");
    header("Access-Control-Allow-Headers: Authorization, Content-Type");

    // Verificar que la solicitud sea POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        die("Only POST requests are accepted");
    }

    // Obtener datos del cuerpo de la solicitud
    $body = $_POST;

    // Validar que el body contenga "controller" y "action"
    if (!isset($body["controller"]) || !isset($body["action"])) {
        http_response_code(400);
        die("Missing controller or action in request body");
    }

    $controllerName = $body["controller"];
    $controllerClassName = getControllerClassName($controllerName);

    $filePath = __DIR__ . "/controller/" . $controllerClassName . ".php";
    if (!file_exists($filePath)) {
        http_response_code(404);
        die("Controller '" . $controllerName . "' does not exist");
    }
    require_once($filePath);

    if (!class_exists($controllerClassName)) {
        http_response_code(404);
        die("Controller '" . $controllerName . "' does not exist");
    }

    $restController = new $controllerClassName();

    $actionName = $body["action"];
    if (!method_exists($restController, $actionName)) {
        http_response_code(404);
        die("Action '$actionName' does not exist in controller '$controllerClassName'");
    }

    // Llamar al método correspondiente del controlador
    $restController->$actionName($body);
}

/**
 * Obtiene el nombre de la clase del controlador con base en el nombre recibido
 *
 * @param string $controllerName El nombre del controlador en los encabezados
 * @return string El nombre de la clase del controlador
 */

function getControllerClassName(string $controllerName): string{
    return ucfirst($controllerName) . "Controller";
}

run();
