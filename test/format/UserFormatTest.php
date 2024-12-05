<?php

class UserFormatTest extends BaseFormatTest
{
    private string $validToken;

    public function __construct(string $validToken, string $testUserId)
    {
        parent::__construct($validToken, $testUserId);

        $this->validToken = $validToken;
    }

    public function runTests(): void
    {
        $validations = $this->defineActionFieldValidations();
        $baseUrl = SERVER_URL;
        $headers = ["Authorization: Bearer $this->validToken"];

        echo "<h3>Ejecutando pruebas para controlador USER</h3>\n";

        foreach ($validations as $action => $fields) {
            foreach ($fields as $fieldName => $testCases) {
                $url = match ($action) {
                    'ADD' => "$baseUrl/?controller=user&action=add",
                    'EDIT' => "$baseUrl/?controller=user&action=edit",
                    'DELETE' => "$baseUrl/?controller=user&action=delete",
                    'GET' => "$baseUrl/?controller=user&action=get",
                    'LIST' => "$baseUrl/?controller=user&action=list",
                    default => throw new Exception("Unknown action: $action"),
                };

                echo "<h4>Ejecutando pruebas para $action - $fieldName</h4>\n";
                $this->runFieldValidationTest($fieldName, $action, $testCases, $url, $headers);
            }
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
//                        'role' => 'ADMIN_GLOBAL',
//                        'password' => 'securePass'
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
//                        'role' => 'ADMIN_GLOBAL',
//                        'password' => 'securePass'
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
//                        'role' => 'ADMIN_GLOBAL',
//                        'password' => 'securePass'
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
//                    'role' => 'ADMIN_GLOBAL',
//                    'password' => 'securePass'
                ]
            ],
            [
                'value' => str_repeat('a', 255),
                'expectedErrors' => [
                    'NOMBRE_COMPLETO_MAXIMO_F_KO'
                ],
                'expectedHTTPCode' => 400,
                'payload' => [
//                    'role' => 'ADMIN_GLOBAL',
//                    'password' => 'securePass'
                ]
            ],
            [
                'value' => 'maria@lopez',
                'expectedErrors' => [
                    'NOMBRE_COMPLETO_CARACTERES_F_KO'
                ],
                'expectedHTTPCode' => 400,
                'payload' => [
//                    'role' => 'ADMIN_GLOBAL',
//                    'password' => 'securePass'
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
//                        'userName' => "TEST_USER" . rand(0, 1000),
//                        'role' => 'ADMIN_GLOBAL'
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
//                        'userName' => "TEST_USER" . rand(0, 1000),
//                        'role' => 'ADMIN_GLOBAL'
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
//                        'userName' => "TEST_USER" . rand(0, 1000),
//                        'role' => 'ADMIN_GLOBAL'
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
//                        'userName' => "TEST_USER" . rand(0, 1000),
//                        'password' => 'securePass'
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