<?php

require_once __DIR__ . "/../format/MemberFormatTest.php";
require_once __DIR__ . "/../format/EducationFormatTest.php";

enum TestController: string
{
    case User = "user";
    case Mail = "contact";
    case Member = "member";
    case Education = "education";
}
enum TestAction: string
{
    case GET = "get";
    case LIST = "list";
    case ADD = "add";
    case EDIT = "edit";
    case DELETE = "delete";
    case LOGIN = "login";
    case SEND = "send";
}


enum TestUserRole: string {
    case ADMIN_GLOBAL = 'ADMIN_GLOBAL';
    case GESTOR_CATALOGO = 'GESTOR_CATALOGO';
    case USUARIO_PYP = 'USUARIO_PYP';
    case NO_AUTH = 'usuario sin autenticar';
}


class PermissionTest
{
    private static array $permissions = [
        TestController::User->name => [
            TestAction::GET->name => TestUserRole::ADMIN_GLOBAL,
            TestAction::LIST->name => TestUserRole::ADMIN_GLOBAL,
            TestAction::ADD->name => TestUserRole::ADMIN_GLOBAL,
            TestAction::EDIT->name => TestUserRole::ADMIN_GLOBAL,
            TestAction::DELETE->name => TestUserRole::ADMIN_GLOBAL,
            TestAction::LOGIN->name => TestUserRole::NO_AUTH
        ],
        TestController::Mail->name => [
            TestAction::SEND->name => TestUserRole::NO_AUTH
        ],
        TestController::Member->name => [
            TestAction::GET->name => TestUserRole::NO_AUTH,
            TestAction::LIST->name => TestUserRole::NO_AUTH,
            TestAction::ADD->name => TestUserRole::ADMIN_GLOBAL,
            TestAction::EDIT->name => TestUserRole::ADMIN_GLOBAL,
            TestAction::DELETE->name => TestUserRole::ADMIN_GLOBAL,
        ],
        TestController::Education->name => [
            TestAction::GET->name => TestUserRole::NO_AUTH,
            TestAction::LIST->name => TestUserRole::NO_AUTH,
            TestAction::ADD->name => TestUserRole::ADMIN_GLOBAL,
            TestAction::EDIT->name => TestUserRole::ADMIN_GLOBAL,
            TestAction::DELETE->name => TestUserRole::ADMIN_GLOBAL,
        ],
    ];

    private static array $roleHierarchy = [
        TestUserRole::ADMIN_GLOBAL->name => [TestUserRole::ADMIN_GLOBAL], // Admin global puede hacer cualquier cosa
        TestUserRole::GESTOR_CATALOGO->name => [TestUserRole::GESTOR_CATALOGO, TestUserRole::ADMIN_GLOBAL], // Gestor y admin global
        TestUserRole::USUARIO_PYP->name => [TestUserRole::USUARIO_PYP, TestUserRole::ADMIN_GLOBAL], // Usuario y admin global
        TestUserRole::NO_AUTH->name => [TestUserRole::NO_AUTH, TestUserRole::USUARIO_PYP, TestUserRole::GESTOR_CATALOGO, TestUserRole::ADMIN_GLOBAL] // Usuario sin autenticar (todos son válidos)
    ];

    public function runTests()
    {
        echo "<h2>Pruebas de PERMISOS</h2>\n";

        foreach (self::$permissions as $controller => $actions) {
            foreach ($actions as $action => $role) {

                echo "<h4>Ejecutando pruebas para $controller - $action - $role->name</h4>\n";

                foreach (TestUserRole::cases() as $currentRole) {

                    // Necesario payload para ciertas acciones, debido a doble validación: GET + ACCIÓN (EDIT, DELETE)
                    if (in_array($action, [TestAction::EDIT->name, TestAction::DELETE->name])
                            && $currentRole !== TestUserRole::ADMIN_GLOBAL) {
                        switch ($controller){
                            case TestController::Member->name:
                                $payload = [
                                    'id' => $this->testMemberId
                                ];
                                break;
                            case TestController::Education->name:
                                $payload = [
                                    'id' => $this->testEducationItemId
                                ];
                        }
                    } else {
                        $payload = [];
                    }

                    $headers = match ($currentRole) {
                        TestUserRole::ADMIN_GLOBAL => ["Authorization: Bearer {$this->testUserAdmin->getJwtToken()}"],
                        TestUserRole::GESTOR_CATALOGO => ["Authorization: Bearer {$this->testUserGestor->getJwtToken()}"],
                        TestUserRole::USUARIO_PYP => ["Authorization: Bearer {$this->testUserUsuario->getJwtToken()}"],
                        TestUserRole::NO_AUTH => [],
                        default => throw new Exception("Unknown role: $currentRole->name"),
                    };

                    if (in_array($currentRole, self::$roleHierarchy[$role->name]))
                    {
                        // Si el rol actual tiene permisos para la acción
                        $expectedHTTPCode = null;
                        $notExpectedHTTPCodes = [401, 403];
                        $expectedOk = null;
                        $expectedErrors = [];
                    }
                    elseif ($currentRole === TestUserRole::NO_AUTH)
                    {
                        // Si el rol actual no tiene permisos para la acción ni está autenticado
                        $expectedHTTPCode = 401;
                        $notExpectedHTTPCodes = [];
                        $expectedOk = false;
                        $expectedErrors = ["AUTHENTICATION_REQUIRED_KO"];
                    }
                    else
                    {
                        // Si el rol actual no tiene permisos para la acción
                        $expectedHTTPCode = 403;
                        $notExpectedHTTPCodes = [];
                        $expectedOk = false;
                        $expectedErrors = ["FORBIDDEN_ACCESS_KO"];
                    }

                    $serverURL = SERVER_URL . (str_ends_with(SERVER_URL, '/') ? '' : '/');
                    $url = $serverURL . "?controller=$controller&action=$action";

                    test("$controller -> $action: Validar permisos de '$currentRole->value'.",
                        function () use ($action, $expectedHTTPCode, $notExpectedHTTPCodes, $expectedOk, $expectedErrors, $url, $headers, $payload) {

                            $response = makeRequest("POST", $url, $headers, $payload);

                            $this->checkResponse(
                                response: $response,
                                expectedHTTPCode: $expectedHTTPCode,
                                notExpectedHTTPCodes: $notExpectedHTTPCodes,
                                expectedOk: $expectedOk,
                                expectedCodes: $expectedErrors,
                            );
                        });
                }
            }
        }
    }

    public function checkResponse(array $response, ?int $expectedHTTPCode, array $notExpectedHTTPCodes, ?bool $expectedOk, array $expectedCodes)
    {
        // Validar código HTTP
        if ($expectedHTTPCode !== null) {
            assertEquals($expectedHTTPCode, $response['httpCode'], "El código HTTP no es el esperado.");
        }
        foreach ($notExpectedHTTPCodes as $notExpectedHTTPCode) {
            assertNotEquals($notExpectedHTTPCode, $response['httpCode'], "El código HTTP no es el esperado.");
        }
        // Validar ok
        if ($expectedOk !== null) {
            if ($expectedOk) {
                assertTrue($response['body']['ok'], "La respuesta indica fallo.");
            } else {
                assertFalse($response['body']['ok'], "La respuesta indica éxito.");
            }
        }
        // Validar códigos de respuesta
        foreach ($expectedCodes as $expectedCode) {
            assertContains($expectedCode, $response['body']['code'], "El código de respuesta no es correcto.");
        }
    }


    private TestUser $testUserAdmin;
    private TestUser $testUserGestor;
    private TestUser $testUserUsuario;

    private MemberFormatTest $memberFormatTest;
    private string $testMemberId;

    private EducationFormatTest $educationFormatTest;
    private string $testEducationItemId;

    public function __construct(?TestUser $testUserAdmin, ?TestUser $testUserGestor, ?TestUser $testUserUsuario)
    {
        $this->testUserAdmin = $testUserAdmin;
        $this->testUserGestor = $testUserGestor;
        $this->testUserUsuario = $testUserUsuario;

        // Nos hace falta un miembro para las pruebas
        $this->memberFormatTest = new MemberFormatTest($this->testUserAdmin->getJwtToken(), $this->testUserAdmin->getId());
        $this->testMemberId = $this->memberFormatTest->insertTestData();

        // Nos hace falta un item de formación para las pruebas
        $this->educationFormatTest = new EducationFormatTest($this->testUserAdmin->getJwtToken(), $this->testUserAdmin->getId());
        $this->testEducationItemId = $this->educationFormatTest->insertTestData();

    }
}
