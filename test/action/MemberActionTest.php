<?php

class MemberActionTest
{
    private string $validToken;
    private array $testMembersInserted;

    /**
     * @param string $validToken
     * @param array $membersInserted
     */
    public function __construct(string $validToken, array $membersInserted)
    {
        $this->validToken = $validToken;
        $this->testMembersInserted = $membersInserted;
    }

    /**
     * @throws Exception
     */
    public function runTests(): void
    {
        $validations = $this->defineActionFieldValidations();
        $baseUrl = SERVER_URL;
        $headers = ["Authorization: Bearer $this->validToken"];

        echo "<h3>Ejecutando pruebas para controlador MEMBER</h3>\n";

        foreach ($validations as $validation => $fields) {
            $url = match ($fields['action']) {
                'ADD' => "$baseUrl/?controller=member&action=add",
                'EDIT' => "$baseUrl/?controller=member&action=edit",
                'DELETE' => "$baseUrl/?controller=member&action=delete",
                'GET' => "$baseUrl/?controller=member&action=get",
                'LIST' => "$baseUrl/?controller=member&action=list",
                default => throw new Exception("Unknown action: $validation"),
            };

//            echo "<h4>Ejecutando pruebas para $validation - $fieldName</h4>\n";
            BaseActionTest::runFieldValidationTest($fields['expectedOk'] , $fields['expectedHTTPCode'], $fields['expectedCodes'], $fields['action'], $url, $headers, $fields['payload']);
        }
    }

    private function defineActionFieldValidations(): array
    {
        $validName = 'TEST_MEMBER_NAME_ACTION'. rand(0, 1000);
        $validEmail = 'TEST_MEMBER_EMAIL_ACTION'. rand(0, 1000) . '@test.com';
        $validDescription = 'TEST_MEMBER_DESCRIPTION_ACTION'. rand(0, 1000);
        $validReferenceURL = 'https://example.com/TEST_MEMBER_REFERENCE_URL_ACTION' . rand(0, 1000);

        return [
            // MIEMBRO_NO_ENCONTRADO_A_KO - EDIT
            [
                'action' => 'EDIT',
                'expectedOk' => false,
                'expectedHTTPCode' => 400,
                'expectedCodes' => ['MIEMBRO_NO_ENCONTRADO_A_KO'],
                'payload' =>
                    [
                        'id' => rand(10000, 20000),
                        'name' => $validName,
                        'email' => $validEmail,
                        'description' => $validDescription,
                        'referenceURL' => $validReferenceURL
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
                        'id' => $this->testMembersInserted[0]['id'],
                        'name' => $validName,
                        'email' => $validEmail,
                        'description' => $validDescription,
                        'referenceURL' => $validReferenceURL
                    ]
            ],
            // MIEMBRO_NO_ENCONTRADO_A_KO - DELETE
            [
                'action' => 'DELETE',
                'expectedOk' => false,
                'expectedHTTPCode' => 400,
                'expectedCodes' => ['MIEMBRO_NO_ENCONTRADO_A_KO'],
                'payload' =>
                    [
                        'id' => rand(10000, 20000)
                    ]
            ],
            // MIEMBRO_NO_ENCONTRADO_A_KO - GET
            [
                'action' => 'GET',
                'expectedOk' => false,
                'expectedHTTPCode' => 400,
                'expectedCodes' => ['MIEMBRO_NO_ENCONTRADO_A_KO'],
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
                        'id' => $this->testMembersInserted[0]['id']
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