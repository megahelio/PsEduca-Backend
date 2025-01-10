<?php

class PermissionValidation
{
    private static array $permissions = [
        Model::User->name => [
            Action::GET->name => UserRole::ADMIN_GLOBAL,
            Action::LIST->name => UserRole::ADMIN_GLOBAL,
            Action::ADD->name => UserRole::ADMIN_GLOBAL,
            Action::EDIT->name => UserRole::ADMIN_GLOBAL,
            Action::DELETE->name => UserRole::ADMIN_GLOBAL,
            Action::LOGIN->name => true
        ],
        Model::Mail->name => [
            Action::SEND->name => true
        ],
        Model::Member->name => [
            Action::GET->name => true,
            Action::LIST->name => true,
            Action::ADD->name => UserRole::ADMIN_GLOBAL,
            Action::EDIT->name => UserRole::ADMIN_GLOBAL,
            Action::DELETE->name => UserRole::ADMIN_GLOBAL,
        ],
        Model::EducationItem->name => [
            Action::GET->name => true,
            Action::LIST->name => true,
            Action::ADD->name => UserRole::ADMIN_GLOBAL,
            Action::EDIT->name => UserRole::ADMIN_GLOBAL,
            Action::DELETE->name => UserRole::ADMIN_GLOBAL,
        ],
        Model::OutreachItem->name => [
            Action::GET->name => true,
            Action::LIST->name => true,
            Action::ADD->name => UserRole::ADMIN_GLOBAL,
            Action::EDIT->name => UserRole::ADMIN_GLOBAL,
            Action::DELETE->name => UserRole::ADMIN_GLOBAL,
        ],
    ];

    private static array $roleHierarchy = [
        UserRole::ADMIN_GLOBAL->name => [UserRole::ADMIN_GLOBAL], // Admin global puede hacer cualquier cosa
        UserRole::GESTOR_CATALOGO->name => [UserRole::GESTOR_CATALOGO, UserRole::ADMIN_GLOBAL], // Gestor y admin global
        UserRole::USUARIO_PYP->name => [UserRole::USUARIO_PYP, UserRole::ADMIN_GLOBAL], // Usuario y admin global
    ];

    private array $errors;

    private UserMapper $userMapper;

    public function __construct()
    {
        $this->errors = [];
        $this->userMapper = UserMapper::getInstance();
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function validate(Model $model, Action $action): array
    {

        if (!array_key_exists($model->name, self::$permissions)) {
            throw new Exception("Model not found in PermissionValidation");
        }

        if (!array_key_exists($action->name, self::$permissions[$model->name])) {
            throw new Exception("Action not found in PermissionValidation");
        }

        $permissionMatrixValue = self::$permissions[$model->name][$action->name];

        if ($permissionMatrixValue !== true) {
            $authUserRole = $this->authenticateUser()->getRole();

            // Verifica si el rol del usuario está en la jerarquía permitida
            if (!in_array($authUserRole, self::$roleHierarchy[$permissionMatrixValue->name] ?? [])) {
//                $this->errors[] = ResponseCodes::FORBIDDEN_ACCESS_KO;
                BaseController::generateHttpResponse(403, array(ResponseCodes::FORBIDDEN_ACCESS_KO));
            }
        }

        return $this->errors;
    }

    /**
     * Authenticates the current request. If the request does not contain
     * auth credentials, it will generate a 401 response code and end PHP processing
     * If the request contain credentials, it will be checked against the database.
     * If the credentials are ok, it will return the User object just logged. If the
     * credentials are invalid, it will generate a 401 code as well and end PHP
     * processing.
     *
     * @return User the user just authenticated.
     */
    public function authenticateUser() : User {
        try {
            $authorizationHeader = apache_request_headers()['Authorization']??null;
            if (empty($authorizationHeader)) {
                BaseController::generateHttpResponse(401, array(ResponseCodes::AUTHENTICATION_REQUIRED_KO));
            }

            // Separar el tipo de autenticación y el token
            list($type, $token) = explode(' ', $authorizationHeader, 2);

            // Verificar si el tipo es 'Bearer'
            if ($type === 'Bearer') {

                $jwt_instance = new JWT();
                $decoded_array = $jwt_instance->decode($token);

                $user = $this->userMapper->getUserInfo($decoded_array["sub"], true);
                if ($user != null) {
                    return $user;
                } else {
                    // No damos detalles sobre si el usuario o la contraseña son incorrectos, se trata del mismo modo.
                    BaseController::generateHttpResponse(401, array(ResponseCodes::AUTHENTICATION_INVALID_KO));
                }

            } else {
                // Tipo de autenticación no soportado
                BaseController::generateHttpResponse(400, array(ResponseCodes::AUTHENTICATION_TYPE_NOT_SUPPORTED_KO));
            }

        } catch (ValidationException $exc) {
            BaseController::generateHttpResponse(401, $exc->getErrors());
        }
	}
}