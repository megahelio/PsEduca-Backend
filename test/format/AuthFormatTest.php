<?php

class AuthFormatTest extends BaseFormatTest
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

        echo "<h3>Ejecutando pruebas para controlador AUTH</h3>\n";

        foreach ($validations as $action => $fields) {
            foreach ($fields as $fieldName => $testCases) {
                $url = match ($action) {
                    'LOGIN' => "$baseUrl/?controller=auth&action=login",
                    default => throw new Exception("Unknown action: $action"),
                };

                echo "<h4>Ejecutando pruebas para $action - $fieldName</h4>\n";
                $this->runFieldValidationTest($fieldName, $action, $testCases, $url, $headers);
            }
        }
    }


    private function defineActionFieldValidations(): array
    {
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

        return [
            'LOGIN' => [
                'userName' => $userNameFormatTests,
                'password' => $passwordFormatTests
            ],
        ];
    }
}