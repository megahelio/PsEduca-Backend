| Código de error                      | Descripción                                                                               | HTTP Status Code |
|--------------------------------------|-------------------------------------------------------------------------------------------|------------------|
| FORBIDDEN_ACCESS_KO                  | El usuario se ha autenticado correctamente pero no tiene permisos para ejecutar la acción | 403              |
| AUTHENTICATION_REQUIRED_KO           | No se ha enviado token de autenticación pero se requiere para la acción a realizar.       | 401              |            
| AUTHENTICATION_INVALID_KO            | El token que se ha proporcionado no es válido (vencido, modificado, ...).                 | 401              |
| AUTHENTICATION_TYPE_NOT_SUPPORTED_KO | Se ha utilizado la cabecera `Authorization` con una autenticación distinta de token JWT.  | 400              |
