<?php
// file: index.php

// Load controller classes here in order to be exposed
require_once (__DIR__ . "/controller/UserController.php");

/**
* Main router (single entry-point for all requests)
* of the REST implementation.
*
* This router will create an instance of the corresponding
* controller, based on the "controller" parameter and call
* the corresponding method, based on the "action" parameter.
*
* The controller of GET or POST parameters should be handled by
* the controller itself.
*
* Parameters:
* <ul>
* <li>controller: The controller name (via HTTP GET)
* <li>action: The name inside the controller (via HTTP GET)
* </ul>
*
* @return void
*
*/
function run(): void {
	// invoke action!
	try {
		if (!isset($_GET["controller"]) || !isset($_GET["action"])) {
			http_response_code(400);
			die("Missing controller or action");
		}

		if ($_SERVER['REQUEST_METHOD'] != 'POST') {
			http_response_code(405);
			die("Only POST requests are accepted");
		}

		// Here is where the "magic" occurs.
		// URLs like: index.php?controller=posts&action=add
		// will provoke a call to: new PostsController()->add()

		// Instantiate the corresponding controller
		$controllerName = $_GET["controller"];
		if (!class_exists($controllerName)) {
			http_response_code(404);
			die("Controller '$controllerName' does not exist");
		}
		$restController = loadController($controllerName);

		// Call the corresponding action
		$actionName = $_GET["action"];
		if (!method_exists($restController, $actionName)) {
			http_response_code(404);
			die("Action '$actionName' does not exist in controller '$controllerName'");
		}

		$restController->$actionName($_POST);

	} catch(Exception $ex) {
		http_response_code(500);
		die("An exception occurred!!!!!".$ex->getMessage());
	}
}

/**
* Load the required controller file and create the controller instance
*
* @param string $restControllerName The controller name found in the URL
* @return Object A Controller instance
*/
function loadController(string $restControllerName): object {
	$restControllerClassName = getControllerClassName($restControllerName);

	require_once(__DIR__ . "/controller/" .$restControllerClassName.".php");
	return new $restControllerClassName();
}

/**
* Obtain the class name for a controller name in the URL
*
* For example $controllerName = "users" will return "UsersController"
*
* @param string $controllerName The name of the controller controller found in the URL
* @return string The controller class name
*/
function getControllerClassName(string $controllerName): string {
	return strToUpper(substr($controllerName, 0, 1)).substr($controllerName, 1)."Controller";
}

run();

?>
