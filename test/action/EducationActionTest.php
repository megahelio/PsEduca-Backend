<?php

class EducationActionTest
{
    private string $validToken;
    private array $testEducationItemsInserted;

    /**
     * @param string $validToken
     * @param array $educationItemsInserted
     */
    public function __construct(string $validToken, array $educationItemsInserted)
    {
        $this->validToken = $validToken;
        $this->testEducationItemsInserted = $educationItemsInserted;
    }

    /**
     * @throws Exception
     */
    public function runTests(): void
    {
        $validations = $this->defineActionFieldValidations();
        $serverURL = SERVER_URL . (str_ends_with(SERVER_URL, '/') ? '' : '/');
        $headers = ["Authorization: Bearer $this->validToken"];

        echo "<h3>Ejecutando pruebas para controlador EDUCATION</h3>\n";

        foreach ($validations as $validation => $fields) {
            $fields['payload'] = $fields['payload'] ?? [];
            $fields['payload']['controller'] = 'education';
            $fields['payload']['action'] = match ($fields['action']) {
                'ADD' => 'add',
                'EDIT' => 'edit',
                'DELETE' => 'delete',
                'GET' => 'get',
                'LIST' => 'list',
                default => throw new Exception("Unknown action: $validation"),
            };

    //        echo "<h4>Ejecutando pruebas para $validation - $fieldName</h4>\n";
            BaseActionTest::runFieldValidationTest($fields['expectedOk'] , $fields['expectedHTTPCode'],
                $fields['expectedCodes'], $fields['action'], $serverURL, $headers, $fields['payload']);
        }
    }

    private function defineActionFieldValidations(): array
    {
        $validTitle = 'TEST_EDUCATION_TITLE_ACTION'. rand(0, 1000);
        $validType = 'MASTER';
        $validDescription = 'TEST_EDUCATION_DESCRIPTION_ACTION'. rand(0, 1000);
        $validReferenceURL = 'https://example.com/TEST_EDUCATION_REFERENCE_URL_ACTION'. rand(0, 1000);
        $validInitYear = 2020;
        $validEndYear = 2021;

        return [
            // ITEM_FORMACION_NO_ENCONTRADO_A_KO - EDIT
            [
                'action' => 'EDIT',
                'expectedOk' => false,
                'expectedHTTPCode' => 400,
                'expectedCodes' => ['ITEM_FORMACION_NO_ENCONTRADO_A_KO'],
                'payload' =>
                    [
                        'id' => rand(10000, 20000),
                        'title' => $validTitle,
                        'type' => $validType,
                        'description' => $validDescription,
                        'referenceURL' => $validReferenceURL,
                        'initYear' => $validInitYear,
                        'endYear' => $validEndYear
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
                        'id' => $this->testEducationItemsInserted[0]['id'],
                        'title' => $validTitle,
                        'type' => $validType,
                        'description' => $validDescription,
                        'referenceURL' => $validReferenceURL,
                        'initYear' => $validInitYear,
                        'endYear' => $validEndYear
                    ]
            ],
            // ITEM_FORMACION_NO_ENCONTRADO_A_KO - DELETE
            [
                'action' => 'DELETE',
                'expectedOk' => false,
                'expectedHTTPCode' => 400,
                'expectedCodes' => ['ITEM_FORMACION_NO_ENCONTRADO_A_KO'],
                'payload' =>
                    [
                        'id' => rand(10000, 20000)
                    ]
            ],
            // ITEM_FORMACION_NO_ENCONTRADO_A_KO - GET
            [
                'action' => 'GET',
                'expectedOk' => false,
                'expectedHTTPCode' => 400,
                'expectedCodes' => ['ITEM_FORMACION_NO_ENCONTRADO_A_KO'],
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
                        'id' => $this->testEducationItemsInserted[0]['id']
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