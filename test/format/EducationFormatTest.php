<?php

require_once __DIR__ . "/BaseFormatTest.php";

class EducationFormatTest
{
    private string $validToken;
    private array $testEducationData;

    public function __construct(string $validToken, array $testEducationData)
    {
        $this->validToken = $validToken;
        $this->testEducationData = $testEducationData;
    }


    public function runTests(): void
    {
        $validations = $this->defineActionFieldValidations();
        $baseUrl = SERVER_URL;
        $headers = ["Authorization: Bearer $this->validToken"];

        echo "<h3>Ejecutando pruebas para controlador EDUCATION</h3>\n";

        foreach ($validations as $action => $fields) {
            foreach ($fields as $fieldName => $testCases) {
                $url = match ($action) {
                    'ADD' => "$baseUrl/?controller=education&action=add",
                    'EDIT' => "$baseUrl/?controller=education&action=edit",
                    'DELETE' => "$baseUrl/?controller=education&action=delete",
                    'GET' => "$baseUrl/?controller=education&action=get",
                    'LIST' => "$baseUrl/?controller=education&action=list",
                    default => throw new Exception("Unknown action: $action"),
                };

                echo "<h4>Ejecutando pruebas para $action - $fieldName</h4>\n";
                BaseFormatTest::runFieldValidationTest($fieldName, $action, $testCases, $url, $headers);
            }
        }
    }

    private function defineActionFieldValidations(): array
    {
        $testEducationItemId = $this->testEducationData[0]['id'];
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

        $titleFormatTests = [
            [
                'value' => 'abc',
                'expectedErrors' => ['TITULO_FORMACION_MINIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testEducationItemId
                ]
            ],
            [
                'value' => str_repeat('a', 255),
                'expectedErrors' => ['TITULO_FORMACION_MAXIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testEducationItemId
                ]
            ],
            [
                'value' => 'maria@lopez',
                'expectedErrors' => ['TITULO_FORMACION_CARACTERES_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testEducationItemId
                ]
            ],
        ];

        $descriptionFormatTests = [
            [
                'value' => 'abc',
                'expectedErrors' => ['DESCRIPCION_MINIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testEducationItemId
                ]
            ],
            [
                'value' => str_repeat('a', 1001),
                'expectedErrors' => ['DESCRIPCION_MAXIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testEducationItemId
                ]
            ],
            [
                'value' => "This string contains a bell character \x07.",
                'expectedErrors' => ['DESCRIPCION_CARACTERES_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testEducationItemId
                ]
            ],
        ];

        $linkFormatTests = [
            [
                'value' => 'abc',
                'expectedErrors' => ['LINK_MINIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testEducationItemId
                ]
            ],
            [
                'value' => str_repeat('a', 255),
                'expectedErrors' => ['LINK_MAXIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testEducationItemId
                ]
            ],
            [
                'value' => 'mipagina.com',
                'expectedErrors' => ['LINK_INVALIDO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testEducationItemId
                ]
            ],
        ];

        $initYearFormatTests = [
            [
                'value' => 'diez',
                'expectedErrors' => ['ANHO_INICIO_INVALIDO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testEducationItemId
                ]
            ],
            [
                'value' => 1856,
                'expectedErrors' => ['ANHO_INICIO_MINIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testEducationItemId
                ]
            ],
            [
                'value' => 9999,
                'expectedErrors' => ['ANHO_INICIO_MAXIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testEducationItemId
                ]
            ],
        ];

        $endYearFormatTests = [
            [
                'value' => 'diez',
                'expectedErrors' => ['ANHO_FIN_INVALIDO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testEducationItemId
                ]
            ],
            [
                'value' => 1856,
                'expectedErrors' => ['ANHO_FIN_MINIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testEducationItemId
                ]
            ],
            [
                'value' => 9999,
                'expectedErrors' => ['ANHO_FIN_MAXIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testEducationItemId
                ]
            ],
        ];

        $typeFormatTests = [
            [
                'value' => 'bachillerato',
                'expectedErrors' => ['TIPO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testEducationItemId
                ]
            ],
        ];

        return [
            'ADD' => [
                'title' => $titleFormatTests,
                'description' => $descriptionFormatTests,
                'referenceURL' => $linkFormatTests,
                'initYear' => $initYearFormatTests,
                'endYear' => $endYearFormatTests,
                'type' => $typeFormatTests,
            ],
            'EDIT' => [
                'id' => $idFormatTests,
                'title' => $titleFormatTests,
                'description' => $descriptionFormatTests,
                'referenceURL' => $linkFormatTests,
                'initYear' => $initYearFormatTests,
                'endYear' => $endYearFormatTests,
                'type' => $typeFormatTests,
            ],
            'DELETE' => [
                'id' => $idFormatTests,
            ],
            'GET' => [
                'id' => $idFormatTests,
            ],
        ];
    }
}
