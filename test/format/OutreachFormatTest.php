<?php

require_once __DIR__ . "/BaseFormatTest.php";

class OutreachFormatTest
{
    private string $validToken;
    private array $testOutreachData;

    public function __construct(string $validToken, array $testOutreachData)
    {
        $this->validToken = $validToken;
        $this->testOutreachData = $testOutreachData;
    }


    public function runTests(): void
    {
        $validations = $this->defineActionFieldValidations();
        $headers = ["Authorization: Bearer $this->validToken"];
        $serverURL = SERVER_URL . (str_ends_with(SERVER_URL, '/') ? '' : '/');

        echo "<h3>Ejecutando pruebas para controlador OUTREACH</h3>\n";

        foreach ($validations as $action => $fields) {
            foreach ($fields as $fieldName => $testCases) {
                foreach ($testCases as &$case) {
                    $case['payload'] = $case['payload'] ?? [];
                    $case['payload']['controller'] = 'outreach';
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
        $testOutreachItemId = $this->testOutreachData[0]['id'];

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
                'expectedErrors' => ['TITULO_MINIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testOutreachItemId
                ]
            ],
            [
                'value' => str_repeat('a', 255),
                'expectedErrors' => ['TITULO_MAXIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testOutreachItemId
                ]
            ],
            [
                'value' => 'maria@lopez',
                'expectedErrors' => ['TITULO_CARACTERES_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testOutreachItemId
                ]
            ],
        ];

        $descriptionFormatTests = [
            [
                'value' => 'abc',
                'expectedErrors' => ['DESCRIPCION_MINIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testOutreachItemId
                ]
            ],
            [
                'value' => str_repeat('a', 1001),
                'expectedErrors' => ['DESCRIPCION_MAXIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testOutreachItemId
                ]
            ],
            [
                'value' => "This string contains a bell character \x07.",
                'expectedErrors' => ['DESCRIPCION_CARACTERES_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testOutreachItemId
                ]
            ],
        ];

        $linkFormatTests = [
            [
                'value' => 'abc',
                'expectedErrors' => ['LINK_MINIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testOutreachItemId,
                    'type' => 'LINK_EXTERNO'
                ]
            ],
            [
                'value' => str_repeat('a', 255),
                'expectedErrors' => ['LINK_MAXIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testOutreachItemId,
                    'type' => 'LINK_EXTERNO'
                ]
            ],
            [
                'value' => 'mipagina.com',
                'expectedErrors' => ['LINK_INVALIDO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testOutreachItemId,
                    'type' => 'LINK_EXTERNO'
                ]
            ],
        ];

        $pageContentFormatTests = [
            [
                'value' => 'abc',
                'expectedErrors' => ['PAGINA_DETALLE_MINIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testOutreachItemId,
                    'type' => 'PAGINA_INTERNA'
                ]
            ],
            [
                'value' => str_repeat('a', 65535),
                'expectedErrors' => ['PAGINA_DETALLE_MAXIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testOutreachItemId,
                    'type' => 'PAGINA_INTERNA'
                ]
            ],
            [
                'value' => "<html><head><title>HOLA</title></head><body></body></html>",
                'expectedErrors' => ['CAMPO_PAGINA_DETALLE_INCOMPATIBLE_CON_TIPO_LINK_EXTERNO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testOutreachItemId,
                    'type' => 'LINK_EXTERNO'
                ]
            ],
            [
                'value' => "<html><head><title>HOLA</title></head><body></body></html>",
                'expectedErrors' => ['CAMPO_PAGINA_DETALLE_INCOMPATIBLE_CON_TIPO_FICHERO_INTERNO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testOutreachItemId,
                    'type' => 'FICHERO_INTERNO'
                ]
            ],
        ];

        $typeFormatTests = [
            [
                'value' => 'PAGINA_INTERNO',
                'expectedErrors' => ['TIPO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $testOutreachItemId
                ]
            ],
        ];

        return [
            'ADD' => [
                'title' => $titleFormatTests,
                'description' => $descriptionFormatTests,
                'externalURL' => $linkFormatTests,
                'pageContent' => $pageContentFormatTests,
                'type' => $typeFormatTests,
            ],
            'EDIT' => [
                'id' => $idFormatTests,
                'title' => $titleFormatTests,
                'description' => $descriptionFormatTests,
                'externalURL' => $linkFormatTests,
                'pageContent' => $pageContentFormatTests,
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
