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
                'pageContent' => '<html><body><h1>Un ejemplo de test</h1></body></html>',
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
        $url = SERVER_URL . (str_ends_with(SERVER_URL, '/') ? '' : '/');
        foreach ($payloads as $payload) {
            $headers = ["Authorization: Bearer " .$this->validToken];
            $payload['controller'] = $controller->value;
            $payload['action'] = $action;
            $response = makeRequest("POST", $url, $headers, $payload);

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
        $url = $serverURL . "?controller=$controller&action=delete";
        $headers = ["Authorization: Bearer " .$this->validToken];
        $payload = [
            'id' => $id,
        ];

        $response = makeRequest("POST", $url, $headers, $payload);

        $responseBody = $response['body'];

        if ($response['httpCode'] != 200) {
            throw new Exception("Error deleting test data for controller: $controller " . print_r($response, true));
        }

        if ($responseBody['ok'] === false) {
            throw new Exception("Error deleting test data for controller: $controller " . print_r($responseBody, true));
        }
    }
}