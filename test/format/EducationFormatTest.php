<?php

require_once __DIR__ . "/BaseFormatTest.php";

class EducationFormatTest extends BaseFormatTest
{
    private string $validToken;
    private string $testEducationItemId;


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
        $this->removeTestData($this->testEducationItemId);
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

        $titleFormatTests = [
            [
                'value' => 'abc',
                'expectedErrors' => ['TITULO_FORMACION_MINIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $this->testEducationItemId
                ]
            ],
            [
                'value' => str_repeat('a', 255),
                'expectedErrors' => ['TITULO_FORMACION_MAXIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $this->testEducationItemId
                ]
            ],
            [
                'value' => 'maria@lopez',
                'expectedErrors' => ['TITULO_FORMACION_CARACTERES_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $this->testEducationItemId
                ]
            ],
        ];

        $descriptionFormatTests = [
            [
                'value' => 'abc',
                'expectedErrors' => ['DESCRIPCION_MINIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $this->testEducationItemId
                ]
            ],
            [
                'value' => str_repeat('a', 1001),
                'expectedErrors' => ['DESCRIPCION_MAXIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $this->testEducationItemId
                ]
            ],
            [
                'value' => "This string contains a bell character \x07.",
                'expectedErrors' => ['DESCRIPCION_CARACTERES_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $this->testEducationItemId
                ]
            ],
        ];

        $linkFormatTests = [
            [
                'value' => 'abc',
                'expectedErrors' => ['LINK_MINIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $this->testEducationItemId
                ]
            ],
            [
                'value' => str_repeat('a', 255),
                'expectedErrors' => ['LINK_MAXIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $this->testEducationItemId
                ]
            ],
            [
                'value' => 'mipagina.com',
                'expectedErrors' => ['LINK_INVALIDO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $this->testEducationItemId
                ]
            ],
        ];

        $yearFormatTests = [
            [
                'value' => 'diez',
                'expectedErrors' => ['ANHO_INICIO_INVALIDO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $this->testEducationItemId
                ]
            ],
            [
                'value' => 1856,
                'expectedErrors' => ['ANHO_INICIO_MINIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $this->testEducationItemId
                ]
            ],
            [
                'value' => 9999,
                'expectedErrors' => ['ANHO_INICIO_MAXIMO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $this->testEducationItemId
                ]
            ],
        ];

        $typeFormatTests = [
            [
                'value' => 'MÁSTER',
                'expectedErrors' => ['TIPO_F_KO'],
                'expectedHTTPCode' => 400,
                'payload' => [
                    'id' => $this->testEducationItemId
                ]
            ],
        ];

        return [
            'ADD' => [
                'id' => $idFormatTests,
                'titulo' => $titleFormatTests,
                'descripcion' => $descriptionFormatTests,
                'link_externo' => $linkFormatTests,
                'anho_inicio' => $yearFormatTests,
                'anho_fin' => $yearFormatTests,
                'tipo' => $typeFormatTests,
            ],
            'EDIT' => [
                'id' => $idFormatTests,
                'titulo' => $titleFormatTests,
                'descripcion' => $descriptionFormatTests,
                'link_externo' => $linkFormatTests,
                'anho_inicio' => $yearFormatTests,
                'anho_fin' => $yearFormatTests,
                'tipo' => $typeFormatTests,
            ],
            'DELETE' => [
                'id' => $idFormatTests,
            ],
            'GET' => [
                'id' => $idFormatTests,
            ],
        ];
    }


    /**
     * @throws Exception
     */
    public function insertTestData(): string
    {
        $serverURL = SERVER_URL . (str_ends_with(SERVER_URL, '/') ? '' : '/');
        $url = $serverURL . "?controller=education&action=add";
        $headers = ["Authorization: Bearer $this->validToken"];

        $payload = [
            'title' => 'Title test',
            'type' => 'MASTER',
            'description' => 'Test description',
            'referenceURL' => 'https://test.com',
            'initYear' => '2020',
            'endYear' => '2021',
        ];

        $response = makeRequest("POST", $url, $headers, $payload);

        $responseBody = $response['body'];

        if ($response['httpCode'] != 201 || $responseBody['ok'] === false) {
            throw new Exception("Error inserting test education item: " . print_r($responseBody, true));
        }

        echo "Item de formación de prueba insertado con id " . $responseBody['resource']['id'] . ".\n ";

        $this->testEducationItemId = $responseBody['resource']['id'];
        return $this->testEducationItemId;
    }

    /**
     * @throws Exception
     */
    private function removeTestData(string $id): void
    {
        $serverURL = SERVER_URL . (str_ends_with(SERVER_URL, '/') ? '' : '/');
        $url = $serverURL . "?controller=education&action=delete";
        $headers = ["Authorization: Bearer " .$this->validToken];
        $payload = [
            'id' => $id,
        ];

        $response = makeRequest("POST", $url, $headers, $payload);

        $responseBody = $response['body'];

        if ($responseBody['ok'] === false) {
            throw new Exception("Error deleting test education item: " . $id);
        }

        echo "Item de formación de prueba con id ". $id . " eliminado correctamente\n. ";
    }

}
