<?php

class AuthFormatTest
{
    private string $validToken;

    public function __construct(string $validToken, array $testUserData)
    {
        $this->validToken = $validToken;
    }

    public function runTests(): void
    {
        $validations = $this->defineActionFieldValidations();
        $headers = ["Authorization: Bearer $this->validToken"];
        $serverURL = SERVER_URL . (str_ends_with(SERVER_URL, '/') ? '' : '/');

        echo "<h3>Ejecutando pruebas para controlador AUTH</h3>\n";

        foreach ($validations as $action => $fields) {
            foreach ($fields as $fieldName => $testCases) {
                foreach ($testCases as &$case) {
                    $case['payload'] = $case['payload'] ?? [];
                    $case['payload']['controller'] = 'auth';
                    $case['payload']['action'] = match ($action) {
                        'LOGIN' => 'login',
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