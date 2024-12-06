<?php

require_once __DIR__ . "/BaseFormatTest.php";

class MemberFormatTest extends BaseFormatTest
{
    private string $validToken;
    private string $testMemberId;

    public function __construct(string $validToken, string $testUserId)
    {
        parent::__construct($validToken, $testUserId);
        $this->validToken = $validToken;
        $this->insertTestData();
    }

    /**
     * @throws Exception
     */
    public function __destruct()
    {
        $this->removeTestMember($this->testMemberId);
    }

    public function runTests(): void
    {
        $validations = $this->defineActionFieldValidations();
        $baseUrl = SERVER_URL;
        $headers = ["Authorization: Bearer $this->validToken"];

        echo "<h3>Ejecutando pruebas para controlador MEMBER</h3>\n";

        foreach ($validations as $action => $fields) {
            foreach ($fields as $fieldName => $testCases) {
                $url = match ($action) {
                    'ADD' => "$baseUrl/?controller=member&action=add",
                    'EDIT' => "$baseUrl/?controller=member&action=edit",
                    'DELETE' => "$baseUrl/?controller=member&action=delete",
                    'GET' => "$baseUrl/?controller=member&action=get",
                    'LIST' => "$baseUrl/?controller=member&action=list",
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

    /**
     * @throws Exception
     */
    public function insertTestData(): string
    {
        $serverURL = SERVER_URL . (str_ends_with(SERVER_URL, '/') ? '' : '/');
        $url = $serverURL . "?controller=member&action=add";
        $headers = ["Authorization: Bearer $this->validToken"];

        $name = "TEST_MEMBER" . rand(0, 1000);
        $payload = [
            'name' => $name,
        ];

        $response = makeRequest("POST", $url, $headers, $payload);

        $responseBody = $response['body'];

        if ($response['httpCode'] != 201 || $responseBody['ok'] === false) {
            throw new Exception("Error inserting test member: " . print_r($responseBody, true));
        }

        echo "Miembro de prueba insertado con id " . $responseBody['resource']['id'] . ".\n ";

        $this->testMemberId = $responseBody['resource']['id'];
        return $this->testMemberId;
    }

    /**
     * @throws Exception
     */
    private function removeTestMember(string $id): void
    {
        $serverURL = SERVER_URL . (str_ends_with(SERVER_URL, '/') ? '' : '/');
        $url = $serverURL . "?controller=member&action=delete";
        $headers = ["Authorization: Bearer " .$this->validToken];
        $payload = [
            'id' => $id,
        ];

        $response = makeRequest("POST", $url, $headers, $payload);

        $responseBody = $response['body'];

        if ($responseBody['ok'] === false) {
            throw new Exception("Error deleting test member: " . $id);
        }

        echo "Miembro de prueba con id ". $id . " eliminado correctamente\n. ";
    }

}
