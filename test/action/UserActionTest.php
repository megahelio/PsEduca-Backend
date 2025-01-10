<?php

class UserActionTest
{
    private string $validToken;
    private array $testUsersInserted;

    /**
     * @param string $validToken
     * @param array $usersInserted
     */
    public function __construct(string $validToken, array $usersInserted)
    {
        $this->validToken = $validToken;
        $this->testUsersInserted = $usersInserted;
    }

    /**
     * @throws Exception
     */
    public function runTests(): void
    {
        $validations = $this->defineActionFieldValidations();
        $baseUrl = SERVER_URL;
        $headers = ["Authorization: Bearer $this->validToken"];

        echo "<h3>Ejecutando pruebas para controlador USER</h3>\n";

        foreach ($validations as $validation => $fields) {
            $url = match ($fields['action']) {
                'ADD' => "$baseUrl/?controller=user&action=add",
                'EDIT' => "$baseUrl/?controller=user&action=edit",
                'DELETE' => "$baseUrl/?controller=user&action=delete",
                'GET' => "$baseUrl/?controller=user&action=get",
                'LIST' => "$baseUrl/?controller=user&action=list",
                default => throw new Exception("Unknown action: $validation"),
            };

//            echo "<h4>Ejecutando pruebas para $validation - $fieldName</h4>\n";
            BaseActionTest::runFieldValidationTest($fields['expectedOk'] , $fields['expectedHTTPCode'], $fields['expectedCodes'], $fields['action'], $url, $headers, $fields['payload']);
        }
    }

    private function defineActionFieldValidations(): array
    {
        $validUserName = 'TEST_USER_NAME_ACTION'. rand(0, 1000);
        $validFullName = 'TEST_FULL_USER_NAME_ACTION'. rand(0, 1000);
        $validPassword = 'TEST_PASSWORD_ACTION'. rand(0, 1000);
        $validRole = 'ADMIN_GLOBAL';

        return [
            // NOMBRE_USUARIO_YA_EXISTE_A_KO - ADD
            [
                'action' => 'ADD',
                'expectedOk' => false,
                'expectedHTTPCode' => 400,
                'expectedCodes' => ['NOMBRE_USUARIO_YA_EXISTE_A_KO'],
                'payload' =>
                    [
                        'userName' => $this->testUsersInserted[0]['name'],
                        'fullName' => $validFullName,
                        'password' => $validPassword,
                        'role' => $validRole
                    ]
            ],
            // NOMBRE_USUARIO_YA_EXISTE_A_KO - EDIT
            [
                'action' => 'EDIT',
                'expectedOk' => false,
                'expectedHTTPCode' => 400,
                'expectedCodes' => ['NOMBRE_USUARIO_YA_EXISTE_A_KO'],
                'payload' =>
                    [
                        'id' => $this->testUsersInserted[0]['id'],
                        'userName' => $this->testUsersInserted[1]['name'],
                        'fullName' => $validFullName,
                        'password' => $validPassword,
                        'role' => $validRole
                    ]
            ],
            // USUARIO_NO_ENCONTRADO_A_KO - EDIT
            [
                'action' => 'EDIT',
                'expectedOk' => false,
                'expectedHTTPCode' => 400,
                'expectedCodes' => ['USUARIO_NO_ENCONTRADO_A_KO'],
                'payload' =>
                    [
                        'id' => rand(10000, 20000),
                        'userName' => $this->testUsersInserted[1]['name'],
                        'fullName' => $validFullName,
                        'password' => $validPassword,
                        'role' => $validRole
                    ]
            ],
            // EDIT
            [
                'action' => 'EDIT',
                'expectedOk' => true,
                'expectedHTTPCode' => 200,
                'expectedCodes' => ['RECORDSET_DATA'],
                'payload' =>
                    [
                        'id' => $this->testUsersInserted[0]['id'],
                        'userName' => $validUserName,
                        'fullName' => $validFullName,
                        'password' => $validPassword,
                        'role' => $validRole
                    ]
            ],
            // USUARIO_NO_ENCONTRADO_A_KO - DELETE
            [
                'action' => 'DELETE',
                'expectedOk' => false,
                'expectedHTTPCode' => 400,
                'expectedCodes' => ['USUARIO_NO_ENCONTRADO_A_KO'],
                'payload' =>
                    [
                        'id' => rand(10000, 20000)
                    ]
            ],
            // USUARIO_NO_ENCONTRADO_A_KO - GET
            [
                'action' => 'GET',
                'expectedOk' => false,
                'expectedHTTPCode' => 400,
                'expectedCodes' => ['USUARIO_NO_ENCONTRADO_A_KO'],
                'payload' =>
                    [
                        'id' => rand(10000, 20000)
                    ]
            ],
            // GET
            [
                'action' => 'GET',
                'expectedOk' => true,
                'expectedHTTPCode' => 200,
                'expectedCodes' => ['RECORDSET_DATA'],
                'payload' =>
                    [
                        'id' => $this->testUsersInserted[0]['id']
                    ]
            ],
            // LIST
            [
                'action' => 'LIST',
                'expectedOk' => true,
                'expectedHTTPCode' => 200,
                'expectedCodes' => ['RECORDSET_DATA'],
                'payload' => [
                    'id' => rand(10000, 20000)
                ]
            ]
        ];
    }
}