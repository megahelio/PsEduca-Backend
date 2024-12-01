<?php

require_once __DIR__ . "/tools.php";
require_once __DIR__ . "/../../core/config_file.php";

class UserValidationTests
{
    private string $validToken;
    private ?string $testUserId;

    /**
     * @throws Exception
     */
    public function __construct($user, $password)
    {
        $this->generateToken($user, $password);
        //$this->validToken = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlzcyI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MFwvUHNFZHVjYS1CYWNrZW5kIn0.eyJzdWIiOiIzMiIsImlhdCI6MTczMzA4MTYzNCwiZXhwIjoxNzM1NjczNjM0LCJyb2xlIjoiQURNSU5fR0xPQkFMIiwiaXNzIjoiaHR0cDpcL1wvbG9jYWxob3N0OjgwXC9Qc0VkdWNhLUJhY2tlbmQifQ.x2OW8ncUn6loBRS7gkg5ENmWuroHpK1UvWwMO9YdI7Y";
        $this->insertTestUser();
    }

    /**
     * @throws Exception
     */
    private function generateToken(string $user, string $password): void
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

        if ($response['body']['resource']['user']['role'] !== 'ADMIN_GLOBAL') {
            throw new Exception("Usuario no tiene rol 'ADMIN_GLOBAL', tiene '{$response['body']['resource']['user']['role']}'");
        }

        $this->validToken = $response['body']['resource']['jwtToken'];

    }

    /**
     * @throws Exception
     */
    private function insertTestUser(): void
    {
        $serverURL = SERVER_URL . (str_ends_with(SERVER_URL, '/') ? '' : '/');
        $url = $serverURL . "?controller=user&action=add";
        $headers = ["Authorization: Bearer $this->validToken"];
        $payload = [
            'userName' => "TEST_USER" . rand(0, 1000),
            'password' => "TEST_PASS" . rand(0, 1000),
            'fullName' => "TEST_PASS" . rand(0, 1000),
            'role' => "ADMIN_GLOBAL"
        ];

        $response = makeRequest("POST", $url, $headers, $payload);

        $responseBody = $response['body'];

        if ($response['httpCode'] != 201 || $responseBody['ok'] === false) {
            throw new Exception("Error inserting test user: " . $responseBody);
        }
        $this->testUserId = $responseBody['resource']['id'] ?? null;
        echo "Usuario de prueba insertado con id '$this->testUserId'\n";
    }

    public function __destruct()
    {
        $this->removeTestUser();
    }

    private function removeTestUser(): void
    {
        $url = SERVER_URL . "?controller=user&action=delete";
        $headers = ["Authorization: Bearer $this->validToken"];
        $payload = [
            'id' => $this->testUserId,
        ];

        $response = makeRequest("POST", $url, $headers, $payload);

        $responseBody = $response['body'];

        if ($responseBody['ok'] === false) {
            throw new Exception("Error inserting test user: " . $responseBody);
        }

        echo "Usuario de prueba con id '$this->testUserId' eliminado correctamente\n";
        $this->testUserId = null;
    }

    public function runTests(): void
    {
        $validations = $this->defineActionFieldValidations();
        $baseUrl = SERVER_URL;
        $headers = ["Authorization: Bearer $this->validToken"];

        ?>

        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Pruebas de validación de campos de usuario</title>
        </head>
        <body>
        <h1>Resultados de las pruebas</h1>

        <?php
        foreach ($validations as $action => $fields) {
            foreach ($fields as $fieldName => $testCases) {
                $url = match ($action) {
                    'ADD' => "$baseUrl/?controller=user&action=add",
                    'EDIT' => "$baseUrl/?controller=user&action=edit",
                    'DELETE' => "$baseUrl/?controller=user&action=delete",
                    'GET' => "$baseUrl/?controller=user&action=get",
                    'LIST' => "$baseUrl/?controller=user&action=list",
                    'LOGIN' => "$baseUrl/?controller=user&action=login",
                    default => throw new Exception("Unknown action: $action"),
                };

                echo "<h3>Ejecutando pruebas para $action - $fieldName</h3>\n";
                $this->runFieldValidationTest($fieldName, $action, $testCases, $url, $headers);
            }
        }
    }

    private function runFieldValidationTest(string $fieldName, string $action, array $testCases, string $url, array $headers): void
    {
        foreach ($testCases as $case) {
            $value = $case['value'] ?? "NULO";
            test("$action: Validar $fieldName con valor '{$value}'", function () use ($action, $fieldName, $case, $url, $headers) {

                $payload = $case['payload'];

                $payload[$fieldName] = $case['value'];

                if ($action === 'EDIT' || $action === 'DELETE' || $action === 'GET') {
                    if (!array_key_exists('id', $payload)) {
                        $payload['id'] = $this->testUserId;
}
                }

                $response = makeRequest("POST", $url, $headers, $payload);

                validateResponse(
                    response: $response,
                    expectedHTTPCode: $case['expectedHTTPCode'], // Personalización del código HTTP
                    expectedOk: false,
                    expectedCodes: $case['expectedErrors'],
                );
            });
        }
    }

    private function defineActionFieldValidations(): array
    {
        $idFormatTests = [
            [
                'value' => null,
                'expectedErrors' =>
                    [
                        'ID_MINIMO_F_KO'
                    ],
                'expectedHTTPCode' => 400,
                'payload' => []
            ],
            [
                'value' => 'abc',
                'expectedErrors' =>
                    [
                        'ID_INVALIDO_F_KO'
                    ],
                'expectedHTTPCode' => 400,
                'payload' => []
            ],
            [
                'value' => -10,
                'expectedErrors' =>
                    [
                        'ID_INVALIDO_F_KO'
                    ],
                'expectedHTTPCode' => 400,
                'payload' => []
            ],
        ];

        $userNameFormatTests = [
            [
                'value' => 'abc',
                'expectedErrors' =>
                    [
                        'NOMBRE_USUARIO_MINIMO_F_KO'
                    ],
                'expectedHTTPCode' => 400,
                'payload' =>
                    [
                        'role' => 'ADMIN_GLOBAL',
                        'password' => 'securePass'
                    ]
            ],
            [
                'value' => str_repeat('a', 255),
                'expectedErrors' =>
                    [
                        'NOMBRE_USUARIO_MAXIMO_F_KO'
                    ],
                'expectedHTTPCode' => 400,
                'payload' =>
                    [
                        'role' => 'ADMIN_GLOBAL',
                        'password' => 'securePass'
                    ]
            ],
            [
                'value' => 'user@123',
                'expectedErrors' =>
                    [
                        'NOMBRE_USUARIO_CARACTERES_F_KO'
                    ],
                'expectedHTTPCode' => 400,
                'payload' =>
                    [
                        'role' => 'ADMIN_GLOBAL',
                        'password' => 'securePass'
                    ]
            ]
        ];

        $fullNameFormatTests = [
            [
                'value' => 'abc',
                'expectedErrors' => [
                    'NOMBRE_COMPLETO_MINIMO_F_KO'
                ],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'role' => 'ADMIN_GLOBAL',
                    'password' => 'securePass'
                ]
            ],
            [
                'value' => str_repeat('a', 255),
                'expectedErrors' => [
                    'NOMBRE_COMPLETO_MAXIMO_F_KO'
                ],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'role' => 'ADMIN_GLOBAL',
                    'password' => 'securePass'
                ]
            ],
            [
                'value' => 'maria@lopez',
                'expectedErrors' => [
                    'NOMBRE_COMPLETO_CARACTERES_F_KO'
                ],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'role' => 'ADMIN_GLOBAL',
                    'password' => 'securePass'
                ]
            ]
        ];


        $passwordFormatTests = [
            [
                'value' => '123',
                'expectedErrors' =>
                    [
                        'CONTRASENHA_MINIMO_F_KO'
                    ],
                'expectedHTTPCode' => 400,
                'payload' =>
                    [
                        'userName' => "TEST_USER" . rand(0, 1000),
                        'role' => 'ADMIN_GLOBAL'
                    ]
            ],
            [
                'value' => str_repeat('a', 255),
                'expectedErrors' =>
                    [
                        'CONTRASENHA_MAXIMO_F_KO'
                    ],
                'expectedHTTPCode' => 400,
                'payload' =>
                    [
                        'userName' => "TEST_USER" . rand(0, 1000),
                        'role' => 'ADMIN_GLOBAL'
                    ]
            ],
            [
                'value' => 'a b c d',
                'expectedErrors' =>
                    [
                        'CONTRASENHA_CARACTERES_F_KO'
                    ],
                'expectedHTTPCode' => 400,
                'payload' =>
                    [
                        'userName' => "TEST_USER" . rand(0, 1000),
                        'role' => 'ADMIN_GLOBAL'
                    ]
            ]
        ];

        $roleFormatTests = [
            [
                'value' => 'ADMINISTRADOR',
                'expectedErrors' =>
                    [
                        'ROL_F_KO'
                    ],
                'expectedHTTPCode' => 400,
                'payload' =>
                    [
                        'userName' => "TEST_USER" . rand(0, 1000),
                        'password' => 'securePass'
                    ]
            ]
        ];

        return [
            'ADD' => [
                'userName' => $userNameFormatTests,
                'password' => $passwordFormatTests,
                'fullName' => $fullNameFormatTests,
                'role' => $roleFormatTests
            ],
            'EDIT' => [
                'id' => $idFormatTests,
                'userName' => $userNameFormatTests,
                'password' => $passwordFormatTests,
                'fullName' => $fullNameFormatTests,
                'role' => $roleFormatTests
            ],
            'GET' => [
                'id' => $idFormatTests
            ],
            'LIST' => [],
            'DELETE' => [
                'id' => $idFormatTests
            ],
        ];
    }
}

// Ejecución de las pruebas
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    try {
        $tests = new UserValidationTests('root', 'root');
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
        <p style="color: darkred"><?= $e->getMessage() ?></p>
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
        $tests = new UserValidationTests($user, $password);
        $tests->runTests();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

} else {
    http_response_code(405);
    die("Method not allowed");
}
