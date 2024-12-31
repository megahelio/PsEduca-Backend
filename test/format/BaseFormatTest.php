<?php

class BaseFormatTest
{
    private string $validToken;
    private TestDataManagement $dataManagement;

    public function __construct(string $validToken, TestDataManagement $testUserData)
    {
        $this->validToken = $validToken;
        $this->dataManagement = $testUserData;
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
        $userFormatTest = new UserFormatTest($this->validToken, $this->dataManagement->getTestData(TestController::User));
        $userFormatTest->runTests();

        require_once __DIR__ . "/AuthFormatTest.php";
        $authFormatTest = new AuthFormatTest($this->validToken, $this->dataManagement->getTestData(TestController::User));
        $authFormatTest->runTests();

        require_once __DIR__ . "/MemberFormatTest.php";
        $memberFormatTest = new MemberFormatTest($this->validToken, $this->dataManagement->getTestData(TestController::Member));
        $memberFormatTest->runTests();

        require_once __DIR__ . "/EducationFormatTest.php";
        $educationFormatTest = new EducationFormatTest($this->validToken, $this->dataManagement->getTestData(TestController::Education));
        $educationFormatTest->runTests();

        require_once __DIR__ . "/OutreachFormatTest.php";
        $outreachFormatTest = new OutreachFormatTest($this->validToken, $this->dataManagement->getTestData(TestController::Outreach));
        $outreachFormatTest->runTests();
    }

    public static function runFieldValidationTest(string $fieldName, string $action, array $testCases, string $url, array $headers): void
    {
        foreach ($testCases as $case) {
            $value = $case['value'] ?? "NULO";
            test("$action: Validar $fieldName con valor '{$value}'", function () use ($action, $fieldName, $case, $url, $headers) {

                $payload = $case['payload'];

                $payload[$fieldName] = $case['value'];

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