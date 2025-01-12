<?php

require_once __DIR__ . "/../validation/PermissionValidation.php";
require_once __DIR__ . "/../exception/ValidationException.php";

enum Model: string
{
    case User = "User";
    case Mail = "Mail";
    case Member = "Member";
    case EducationItem = "EducationItem";
    case OutreachItem = "OutreachItem";
    case CatalogueItem = "CatalogueItem";
}

enum Action
{
    case GET;
    case LIST;

    case ADD;
    case EDIT;
    case DELETE;

    // Específico de UserController
    case LOGIN;

    // Específico de ContactController
    case SEND;

    // Específico de CatalogueItemController
//    case ADDFILE;
//    case DELETEFILE;
//    case ADDLINK;
//    case DELETELINK;
//    case LISTAVAILABLEFILTERS;

}

class Validator {
    /**
     * @throws ReflectionException|ValidationException
     */
    public static function validate($object, Action $action): void
    {
        $errors = [];

        $reflection = new ReflectionClass($object);
        $model = Model::from($reflection->getShortName());

        // Antes de nada, se comprueba si se tienen los permisos necesarios (auth)
        $permissionValidation = new PermissionValidation();
        $errors = $permissionValidation->validate($model, $action);

        // Si hay errores de autenticación, no se valida el formato ni la acción
        if ($errors) {
            throw new ValidationException($errors);
        }

        $formatValidatorClassName = self::getFormatValidatorClassName($model);
        $actionValidatorClassName = self::getActionValidatorClassName($model);

        $formatValidatorFilePath = __DIR__ . "/../validation/format/$formatValidatorClassName.php";
        $actionValidatorFilePath = __DIR__ . "/../validation/action/$actionValidatorClassName.php";

        if (file_exists($formatValidatorFilePath)) {
            require_once $formatValidatorFilePath;
            $formatValidator = new $formatValidatorClassName();

            $errors = $formatValidator->validate($action, $object);

            // Si hay errores de formato, no se valida la acción
            if ($errors) {
                throw new ValidationException($errors);
            }
        }
        if (file_exists($actionValidatorFilePath)) {
            require_once $actionValidatorFilePath;
            $actionValidator = new $actionValidatorClassName();

            $errors = $actionValidator->validate($action, $object);

            if ($errors) {
                throw new ValidationException($errors);
            }
        }
    }

    /**
     * Obtiene el nombre de la clase del validador de formato con base en el modelo recibido
     *
     * @param Model $model El modelo para el cual se obtiene el nombre de la clase del validador
     * @return string El nombre de la clase del validador de formato
     */
    private static function getFormatValidatorClassName(Model $model): string {
        return $model->value . "FormatValidation";
    }

    /**
     * Obtiene el nombre de la clase del validador de acciones con base en el modelo recibido
     *
     * @param Model $model El modelo para el cual se obtiene el nombre de la clase del validador
     * @return string El nombre de la clase del validador de acciones
     */
    private static function getActionValidatorClassName(Model $model): string {
        return $model->value . "ActionValidation";
    }
}