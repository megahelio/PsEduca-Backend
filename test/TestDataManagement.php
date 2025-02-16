<?php

class TestDataManagement
{
    private string $validToken;

    private array $testData;

    /**
     * @throws Exception
     */
    public function __construct(string $validToken)
    {
        $this->validToken = $validToken;
        $this->testData = [
            TestController::User->name => [],
            TestController::Member->name => [],
            TestController::Education->name => [],
            TestController::Outreach->name => [],
            TestController::Catalogue->name => [],
            TestController::PyPItem->name => [],
        ];
        $this->insertTestData();
    }

    public function getTestData(TestController $controller): array
    {
        return $this->testData[$controller->name];
    }

    /**
     * @throws Exception
     */
    public function __destruct()
    {
        $this->removeTestData();
    }

    /**
     * @throws Exception
     */
    private function insertTestData(): void
    {
        $users = [
            [
                'userName' => "TEST_USER" . rand(0, 1000),
                'fullName' => "Test User",
                'password' => "123456",
                'role' => "GESTOR_CATALOGO",
            ],
            [
                'userName' => "TEST_USER" . rand(0, 1000),
                'fullName' => "Test User",
                'password' => "123456",
                'role' => "GESTOR_CATALOGO",
            ],
        ];

        $members = [
            [
                'name' => "TEST_MEMBER" . rand(0, 1000),
            ],
        ];

        $education_items = [
            [
                'title' => 'Title test',
                'type' => 'MASTER',
                'description' => 'Test description',
                'referenceURL' => 'https://test.com',
                'initYear' => '2020',
                'endYear' => '2021',
            ],
        ];

        $outreach_items = [
            [
                'title' => 'Title test',
                'type' => 'PAGINA_INTERNA',
                'description' => 'Test description',
                'externalURL' => 'https://test.com',
                'pageContent' => '<html><body></body></html>',
            ],
        ];

        $catalogue_items = [
            [
                'acronym' => 'ACRONYM',
                'name' => 'Name',
                'yearMinAge' => '1',
                'monthMinAge' => '1',
                'yearMaxAge' => '2',
                'monthMaxAge' => '2',
                'authors' => 'Authors',
                'time' => '15',
                'description' => 'Description',
                'note' => 'Note',
                'areas' => ['Area 1', 'Area 2'],
                'tags' => ['Tag 1', 'Tag 2'],
                'resourceTypes' => ['Resource Type 1', 'Resource Type 2'],
                'formats' => ['Format 1', 'Format 2'],
                'applicationModes' => ['Application Mode 1', 'Application Mode 2'],
            ],
        ];

        $pyp_items = [
            [
                'title' => 'Item divulgación 1',
                'description' => 'Los detalles convenientes sobre el item 1.',
                'imageURL' => '/uploads/67844f60644811.03749564.jpg',
                'externalURL' => 'https://europa.eu',
            ],
        ];

        $data = [
            [
                'controller' => TestController::User,
                'action' => 'add',
                'payloads' => $users,
            ],
            [
                'controller' => TestController::Member,
                'action' => 'add',
                'payloads' => $members,
            ],
            [
                'controller' => TestController::Education,
                'action' => 'add',
                'payloads' => $education_items,
            ],
            [
                'controller' => TestController::Outreach,
                'action' => 'add',
                'payloads' => $outreach_items,
            ],
            [
                'controller' => TestController::Catalogue,
                'action' => 'add',
                'payloads' => $catalogue_items,
            ],
            [
                'controller' => TestController::PyPItem,
                'action' => 'add',
                'payloads' => $pyp_items,
            ],
        ];

        foreach ($data as $item) {
            $this->insertTestDataForController($item['controller'], $item['action'], $item['payloads']);
        }
    }

    /**
     * @throws Exception
     */
    private function insertTestDataForController(TestController $controller, mixed $action, mixed $payloads): void
    {
        $serverURL = SERVER_URL . (str_ends_with(SERVER_URL, '/') ? '' : '/');
        foreach ($payloads as $payload) {

            $payload['controller'] = $controller->value;
            $payload['action'] = $action;

            $headers = ["Authorization: Bearer " .$this->validToken];

            $response = makeRequest("POST", $serverURL, $headers, $payload);

            if ($response['httpCode'] !== 201) {
                throw new Exception("Error inserting test data for controller: $controller->value " . print_r($response, true));
            }

            $responseBody = $response['body'];

            if ($responseBody['ok'] === false) {
                throw new Exception("Error inserting test data for controller: $controller->value " . print_r($responseBody, true));
            }

            $this->testData[$controller->name][] = $responseBody['resource'];
        }

        echo "Datos de prueba insertados correctamente para el controlador '$controller->value'. ";
    }

    /**
     * @throws Exception
     */
    private function removeTestData(): void
    {
        foreach ($this->testData as $controller => $items) {
            foreach ($items as $item) {
                $this->removeTestDataForController($controller, $item['id']);
            }
            echo "Datos de prueba eliminados correctamente para el controlador '$controller'. ";
        }
    }

    /**
     * @throws Exception
     */
    private function removeTestDataForController(string $controller, mixed $id): void
    {
        $serverURL = SERVER_URL . (str_ends_with(SERVER_URL, '/') ? '' : '/');
        $headers = ["Authorization: Bearer " .$this->validToken];
        $payload = [
            'controller' => $controller,
            'action' => 'delete',
            'id' => $id,
        ];

        $response = makeRequest("POST", $serverURL, $headers, $payload);

        $responseBody = $response['body'];

        if ($response['httpCode'] != 200) {
            throw new Exception("Error deleting test data for controller: $controller " . print_r($response, true));
        }

        if ($responseBody['ok'] === false) {
            throw new Exception("Error deleting test data for controller: $controller " . print_r($responseBody, true));
        }
    }
}