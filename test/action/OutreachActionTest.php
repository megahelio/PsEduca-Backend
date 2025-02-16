<?php

class OutreachActionTest
{
    private string $validToken;
    private array $testOutreachItemsInserted;

    /**
     * @param string $validToken
     * @param array $outreachItemsInserted
     */
    public function __construct(string $validToken, array $outreachItemsInserted)
    {
        $this->validToken = $validToken;
        $this->testOutreachItemsInserted = $outreachItemsInserted;
    }

    /**
     * @throws Exception
     */
    public function runTests(): void
        {
        $validations = $this->defineActionFieldValidations();
        $serverURL = SERVER_URL . (str_ends_with(SERVER_URL, '/') ? '' : '/');
        $headers = ["Authorization: Bearer $this->validToken"];

        echo "<h3>Ejecutando pruebas para controlador OUTREACH</h3>\n";

        foreach ($validations as $validation => $fields) {
            $fields['payload'] = $fields['payload'] ?? [];
            $fields['payload']['controller'] = 'outreach';
            $fields['payload']['action'] = match ($fields['action']) {
                'ADD' => 'add',
                'EDIT' => 'edit',
                'DELETE' => 'delete',
                'GET' => 'get',
                'LIST' => 'list',
                default => throw new Exception("Unknown action: $validation"),
            };

    //            echo "<h4>Ejecutando pruebas para $validation - $fieldName</h4>\n";
            BaseActionTest::runFieldValidationTest($fields['expectedOk'] , $fields['expectedHTTPCode'],
                $fields['expectedCodes'], $fields['action'], $serverURL, $headers, $fields['payload']);
        }
    }

    private function defineActionFieldValidations(): array
    {
        $validTitle = 'TEST_OUTREACH_TITLE_ACTION'. rand(0, 1000);
        $validType = 'PAGINA_INTERNA';
        $validDescription = 'TEST_OUTREACH_DESCRIPTION_ACTION'. rand(0, 1000);
        $validExternalURL = 'https://example.com/TEST_OUTREACH_REFERENCE_URL_ACTION'. rand(0, 1000);
        $validPageContent = '<html><body><h1>TEST_OUTREACH_PAGE_CONTENT_ACTION</h1></body></html>';

        return [
            // ITEM_DIVULGACION_NO_ENCONTRADO_A_KO - EDIT
            [
                'action' => 'EDIT',
                'expectedOk' => false,
                'expectedHTTPCode' => 400,
                'expectedCodes' => ['ITEM_DIVULGACION_NO_ENCONTRADO_A_KO'],
                'payload' =>
                    [
                        'id' => rand(10000, 20000),
                        'title' => $validTitle,
                        'type' => $validType,
                        'description' => $validDescription,
                        'externalURL' => $validExternalURL,
                        'pageContent' => $validPageContent,
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
                        'id' => $this->testOutreachItemsInserted[0]['id'],
                        'title' => $validTitle,
                        'type' => $validType,
                        'description' => $validDescription,
                        'externalURL' => $validExternalURL,
                        'pageContent' => $validPageContent,
                    ]
            ],
            // ITEM_DIVULGACION_NO_ENCONTRADO_A_KO - DELETE
            [
                'action' => 'DELETE',
                'expectedOk' => false,
                'expectedHTTPCode' => 400,
                'expectedCodes' => ['ITEM_DIVULGACION_NO_ENCONTRADO_A_KO'],
                'payload' =>
                    [
                        'id' => rand(10000, 20000)
                    ]
            ],
            // ITEM_DIVULGACION_NO_ENCONTRADO_A_KO - GET
            [
                'action' => 'GET',
                'expectedOk' => false,
                'expectedHTTPCode' => 400,
                'expectedCodes' => ['ITEM_DIVULGACION_NO_ENCONTRADO_A_KO'],
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
                        'id' => $this->testOutreachItemsInserted[0]['id']
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