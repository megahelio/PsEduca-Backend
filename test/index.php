<?php

require_once __DIR__ . "/tools.php";
require_once __DIR__ . "/../core/config_file.php";
require_once __DIR__ . "/TestUser.php";
require_once __DIR__ . "/TestDataManagement.php";

require_once __DIR__ . "/permission/PermissionTest.php";
require_once __DIR__ . "/format/BaseFormatTest.php";
require_once __DIR__ . "/action/BaseActionTest.php";

class ValidationTests
{
    private TestUser $adminTestUser;
    private TestUser $gestCatTestUser;
    private TestUser $usuarioPYPTestUser;
    private string $token;

    private TestDataManagement $dataManagement;

    /**
     * @throws Exception
     */
    public function __construct($user, $password)
    {
        $this->token = $this->generateToken($user, $password, true);
        $this->adminTestUser = $this->insertTestUser("ADMIN_GLOBAL");
        $this->gestCatTestUser = $this->insertTestUser("GESTOR_CATALOGO");
        $this->usuarioPYPTestUser = $this->insertTestUser("USUARIO_PYP");

        $this->dataManagement = new TestDataManagement($this->token);
    }

    /**
     * @throws Exception
     */
    public function __destruct()
    {
        $this->removeTestUser($this->adminTestUser);
        $this->removeTestUser($this->gestCatTestUser);
        $this->removeTestUser($this->usuarioPYPTestUser);
    }

    /**
     * @throws Exception
     */
    private function generateToken(string $user, string $password, bool $checkAdmin): string
    {
        // Login
        $serverURL = SERVER_URL . (str_ends_with(SERVER_URL, '/') ? '' : '/');
        $url = $serverURL . "?controller=auth&action=login";

        $payload = [
            'userName' => $user,
            'password' => $password
        ];

        $response = makeRequest("POST", $url, [], $payload);

        if ($response['httpCode'] != 200 || $response['body']['ok'] === false) {
            throw new Exception("Error generando token: " . print_r($response, true));
        }

        if ($checkAdmin && $response['body']['resource']['user']['role'] !== 'ADMIN_GLOBAL') {
            throw new Exception("Usuario no tiene rol 'ADMIN_GLOBAL', tiene '{$response['body']['resource']['user']['role']}'");
        }

        return $response['body']['resource']['jwtToken'];

    }

    /**
     * @throws Exception
     */
    private function insertTestUser(string $role): TestUser
    {
        $serverURL = SERVER_URL . (str_ends_with(SERVER_URL, '/') ? '' : '/');
        $url = $serverURL . "?controller=user&action=add";
        $headers = ["Authorization: Bearer $this->token"];
        $userName = "TEST_USER" . rand(0, 1000);
        $password = "TEST_PASS" . rand(0, 1000);
        $payload = [
            'userName' => $userName,
            'password' => $password,
            'fullName' => "TEST_FULL_NAME" . rand(0, 1000),
            'role' => $role
        ];

        $response = makeRequest("POST", $url, $headers, $payload);

        $responseBody = $response['body'];

        if ($response['httpCode'] != 201 || $responseBody['ok'] === false) {
            throw new Exception("Error inserting test user: " . print_r($responseBody, true));
        }

        echo "Usuario de prueba con rol " . $responseBody['resource']['role']
            . " insertado con id " . $responseBody['resource']['id'] . ".\n ";

        return new TestUser(
                id: $responseBody['resource']['id'],
                role: $responseBody['resource']['role'],
                jwtToken: $this->generateToken($userName, $password, false),
        );
    }

    /**
     * @throws Exception
     */
    private function removeTestUser(TestUser $testUser): void
    {
        $serverURL = SERVER_URL . (str_ends_with(SERVER_URL, '/') ? '' : '/');
        $url = $serverURL . "?controller=user&action=delete";
        $headers = ["Authorization: Bearer " .$this->token];
        $payload = [
            'id' => $testUser->getId(),
        ];

        $response = makeRequest("POST", $url, $headers, $payload);

        $responseBody = $response['body'];

        if ($responseBody['ok'] === false) {
            throw new Exception("Error deleting test user: " . $testUser->getId());
        }

        echo "Usuario de prueba con id ". $testUser->getId() . " eliminado correctamente\n. ";
    }

    public function runTests(): void
    {
        ?>

        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Pruebas de validación - PsEduca Backend</title>
        </head>
        <body>
        <h1>Resultados de las pruebas</h1>

        <?php

        // Test de permisos
        $permissions = new PermissionTest(
                testUserAdmin: $this->adminTestUser,
                testUserGestor: $this->gestCatTestUser,
                testUserUsuario: $this->usuarioPYPTestUser,
                testMemberData: $this->dataManagement->getTestData(TestController::Member),
                testEducationData: $this->dataManagement->getTestData(TestController::Education)
        );
        $permissions->runTests();

        // Test de formato
        $format = new BaseFormatTest($this->adminTestUser->getJwtToken(), $this->dataManagement);
        $format->runTests();

        // Test de acciones
        $action = new BaseActionTest($this->adminTestUser->getJwtToken(), $this->dataManagement);
        $action->runTests();
    }

}

// Ejecución de las pruebas
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    try {
        $tests = new ValidationTests('root', 'root');
        $tests->runTests();
        die();
    } catch (Exception $e) {

        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Pruebas de validación de campos de usuario</title>
        </head>
        <body>
        <h1>Error en pruebas de validación de campos de usuario</h1>
        <p>Estas pruebas validan los campos de usuario en las acciones de agregar, editar y obtener.</p>
        <p style="color: darkred"><?= $e ?></p>
        <p style="color: darkred">No se ha podido iniciar sesión con usuario por defecto, por favor introduce credenciales de usuario con rol ADMIN_GLOBAL.</p>
        <form method="post">
            <!--        Es necesario que el usuario nos envíe usuario y contraseña de admin_global -->
            <label for="user">Usuario:</label>
            <input type="text" id="user" name="user" required>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Ejecutar pruebas</button>
        </form>
        </body>
        </html>
        <?php

    }


} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'] ?? null;
    $password = $_POST['password'] ?? null;

    if ($user === null || $password === null) {
        http_response_code(400);
        die("Missing user or password");
    }

    try {
        $tests = new ValidationTests($user, $password);
        $tests->runTests();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

} else {
    http_response_code(405);
    die("Method not allowed");
}
