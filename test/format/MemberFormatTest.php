<?php

require_once __DIR__ . "/BaseFormatTest.php";

class MemberFormatTest
{
    private string $validToken;
    private string $testMemberId;

    public function __construct(string $validToken, array $testMemberData)
    {
        $this->validToken = $validToken;
        $this->testMemberId = $testMemberData[0]['id'];
    }

    public function runTests(): void
    {
        $validations = $this->defineActionFieldValidations();
        $headers = ["Authorization: Bearer $this->validToken"];
        $serverURL = SERVER_URL . (str_ends_with(SERVER_URL, '/') ? '' : '/');

        echo "<h3>Ejecutando pruebas para controlador MEMBER</h3>\n";

        foreach ($validations as $action => $fields) {
            foreach ($fields as $fieldName => $testCases) {
                foreach ($testCases as &$case) {
                    $case['payload'] = $case['payload'] ?? [];
                    $case['payload']['controller'] = 'member';
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
        $idFormatTests = [
            [
                'value' => null,
                'expectedErrors' => ['ID_MINIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => []
            ],
            [
                'value' => 'abc',
                'expectedErrors' => ['ID_INVALIDO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => []
            ],
            [
                'value' => -10,
                'expectedErrors' => ['ID_INVALIDO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => []
            ],
        ];

        $nameFormatTests = [
            [
                'value' => 'abc',
                'expectedErrors' => ['NOMBRE_MIEMBRO_MINIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $this->testMemberId
                ]
            ],
            [
                'value' => str_repeat('a', 255),
                'expectedErrors' => ['NOMBRE_MIEMBRO_MAXIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $this->testMemberId
                ]
            ],
            [
                'value' => 'maria@lopez',
                'expectedErrors' => ['NOMBRE_MIEMBRO_CARACTERES_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $this->testMemberId
                ]
            ]
        ];

        $emailFormatTests = [
            [
                'value' => 'email_sin_formato',
                'expectedErrors' => ['EMAIL_INVALIDO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $this->testMemberId
                ]
            ]
        ];

        $descriptionFormatTests = [
            [
                'value' => 'abc',
                'expectedErrors' => ['DESCRIPCION_MINIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $this->testMemberId
                ]
            ],
            [
                'value' => str_repeat('a', 1001),
                'expectedErrors' => ['DESCRIPCION_MAXIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $this->testMemberId
                ]
            ],
            [
                'value' => "This is an invalid string with a bell character \x07 inside.", // Carácter no imprimible
                'expectedErrors' => ['DESCRIPCION_CARACTERES_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $this->testMemberId
                ]
            ]
        ];

        $linkFormatTests = [
            [
                'value' => 'abc',
                'expectedErrors' => ['LINK_MINIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $this->testMemberId
                ]
            ],
            [
                'value' => str_repeat('a', 255),
                'expectedErrors' => ['LINK_MAXIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $this->testMemberId
                ]
            ],
            [
                'value' => 'mipagina.com',
                'expectedErrors' => ['LINK_INVALIDO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $this->testMemberId
                ]
            ]
        ];

        return [
            'ADD' => [
                'name' => $nameFormatTests,
                'email' => $emailFormatTests,
                'description' => $descriptionFormatTests,
                'referenceURL' => $linkFormatTests,
            ],
            'EDIT' => [
                'id' => $idFormatTests,
                'name' => $nameFormatTests,
                'email' => $emailFormatTests,
                'description' => $descriptionFormatTests,
                'referenceURL' => $linkFormatTests,
            ],
            'DELETE' => [
                'id' => $idFormatTests,
            ],
            'GET' => [
                'id' => $idFormatTests,
            ],
            'LIST' => []
        ];
    }
}
