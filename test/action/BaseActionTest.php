<?php

class BaseActionTest
{
    private string $validToken;
    private TestDataManagement $dataManagement;

    public function __construct(string $validToken, TestDataManagement $dataManagement)
    {
        $this->validToken = $validToken;
        $this->dataManagement = $dataManagement;
    }

    /**
     * @throws Exception
     */
    public function runTests(): void
    {
        ?>

        <h2>Pruebas de ACCIÓN</h2>

        <?php

        require_once __DIR__ . "/UserActionTest.php";
        $userActionTest = new UserActionTest($this->validToken, $this->dataManagement->getTestData(TestController::User));
        $userActionTest->runTests();

        require_once __DIR__ . "/MemberActionTest.php";
        $memberActionTest = new MemberActionTest($this->validToken, $this->dataManagement->getTestData(TestController::Member));
        $memberActionTest->runTests();

        require_once __DIR__ . "/EducationActionTest.php";
        $educationActionTest = new EducationActionTest($this->validToken, $this->dataManagement->getTestData(TestController::Education));
        $educationActionTest->runTests();

        require_once __DIR__ . "/OutreachActionTest.php";
        $outreachActionTest = new OutreachActionTest($this->validToken, $this->dataManagement->getTestData(TestController::Outreach));
        $outreachActionTest->runTests();
    }

    public static function runFieldValidationTest(bool $expectedOk, int $expectedHTTPCode, array $expectedCodes,
                                                  string $action, string $url, array $headers, array $payload): void
    {
        test("$action: Validar '" . implode(", ", $expectedCodes) . "'",
            function () use ($payload, $expectedCodes, $expectedHTTPCode, $expectedOk, $action, $url, $headers) {

            $response = makeRequest("POST", $url, $headers, $payload);

            validateResponse(
                response: $response,
                expectedHTTPCode: $expectedHTTPCode, // Personalización del código HTTP
                expectedOk: $expectedOk,
                expectedCodes: $expectedCodes,
            );
        });
    }

}