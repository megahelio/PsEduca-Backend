<?php

class BaseFormatTest
{
    private string $validToken;
    private string $testUserId;

    public function __construct(string $validToken, string $testUserId)
    {
        $this->validToken = $validToken;
        $this->testUserId = $testUserId;
    }

    /**
     * @throws Exception
     */
    public function runTests(): void
    {
        ?>

        <h2>Pruebas de FORMATO</h2>

        <?php

        require_once __DIR__ . "/UserFormatTest.php";
        $userFormatTest = new UserFormatTest($this->validToken, $this->testUserId);
        $userFormatTest->runTests();

        require_once __DIR__ . "/AuthFormatTest.php";
        $authFormatTest = new AuthFormatTest($this->validToken, $this->testUserId);
        $authFormatTest->runTests();

        require_once __DIR__ . "/MemberFormatTest.php";
        $memberFormatTest = new MemberFormatTest($this->validToken, $this->testUserId);
        $memberFormatTest->runTests();
    }

    protected function runFieldValidationTest(string $fieldName, string $action, array $testCases, string $url, array $headers): void
    {
        foreach ($testCases as $case) {
            $value = $case['value'] ?? "NULO";
            test("$action: Validar $fieldName con valor '{$value}'", function () use ($action, $fieldName, $case, $url, $headers) {

                $payload = $case['payload'];

                $payload[$fieldName] = $case['value'];

                if ($action === 'EDIT' || $action === 'DELETE' || $action === 'GET') {
                    if (!array_key_exists('id', $payload)) {
                        $payload['id'] = $this->testUserId;
                    }
                }

                $response = makeRequest("POST", $url, $headers, $payload);

                validateResponse(
                    response: $response,
                    expectedHTTPCode: $case['expectedHTTPCode'], // Personalización del código HTTP
                    expectedOk: false,
                    expectedCodes: $case['expectedErrors'],
                );
            });
        }
    }

}