<?php

class UserFormatTest
{
    private string $validToken;
    private array $testUserData;

    public function __construct(string $validToken, array $testUserData)
    {
        $this->validToken = $validToken;
        $this->testUserData = $testUserData;
    }

    public function runTests(): void
    {
        $validations = $this->defineActionFieldValidations();
        $headers = ["Authorization: Bearer $this->validToken"];
        $serverURL = SERVER_URL . (str_ends_with(SERVER_URL, '/') ? '' : '/');

        echo "<h3>Ejecutando pruebas para controlador USER</h3>\n";

        foreach ($validations as $action => $fields) {
            foreach ($fields as $fieldName => $testCases) {
                foreach ($testCases as &$case) {
                    $case['payload'] = $case['payload'] ?? [];
                    $case['payload']['controller'] = 'user';
                    $case['payload']['action'] = match ($action) {
                        'ADD' => 'add',
                        'EDIT' => 'edit',
                        'DELETE' => 'delete',
                        'GET' => 'get',
                        'LIST' => 'list',
                        default => throw new Exception("Unknown action: $action"),
                    };
                }

                unset($case);

                echo "<h4>Ejecutando pruebas para $action - $fieldName</h4>\n";
                BaseFormatTest::runFieldValidationTest($fieldName, $action, $testCases, $serverURL, $headers);
            }
        }
    }

    private function defineActionFieldValidations(): array
    {
        $userId = $this->testUserData[0]['id'];

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
                        'id' => $userId
                    ]
            ],
            [
                'value' => str_repeat('a', 255),
                'expectedErrors' =>
                    [
                        'NOMBRE_USUARIO_MAXIMO_F_KO'
                    ],
                'expectedHTTPCode' => 400,
                'payload' => [
                        'id' => $userId
                    ]
            ],
            [
                'value' => 'user@123',
                'expectedErrors' =>
                    [
                        'NOMBRE_USUARIO_CARACTERES_F_KO'
                    ],
                'expectedHTTPCode' => 400,
                'payload' => [
                        'id' => $userId
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
                        'id' => $userId
                    ]
            ],
            [
                'value' => str_repeat('a', 255),
                'expectedErrors' => [
                    'NOMBRE_COMPLETO_MAXIMO_F_KO'
                ],
                'expectedHTTPCode' => 400,
                'payload' => [
                        'id' => $userId
                    ]
            ],
            [
                'value' => 'maria@lopez',
                'expectedErrors' => [
                    'NOMBRE_COMPLETO_CARACTERES_F_KO'
                ],
                'expectedHTTPCode' => 400,
                'payload' => [
                        'id' => $userId
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
                        'id' => $userId
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
                        'id' => $userId
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
                        'id' => $userId
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
                        'id' => $userId
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
            'DELETE' => [
                'id' => $idFormatTests
            ],
            'GET' => [
                'id' => $idFormatTests
            ],
            'LIST' => [
            ]
        ];
    }
}