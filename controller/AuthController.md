# AuthController

API destinada a la autenticación de usuarios.

## Acciones

### login

Permite iniciar sesión en la aplicación.

#### Request

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

http://localhost/PsEduca-Backend/?controller=auth&action=login

Se requiere enviar los siguientes campos:

 - `userName` (string, obligatorio): Nombre de usuario.
 - `password` (string, obligatorio): Contraseña. Se envía en claro, se cifra en el servidor.

##### **Códigos posibles:**

| Tipo   | Código                         | Descripción                                                                                            |
|--------|--------------------------------|--------------------------------------------------------------------------------------------------------|
| Éxito  | `RECORDSET_DATA`               | Datos recuperados correctamente.                                                                       |
| Error  | `USER_CREDENTIALS_INVALID_KO`  | Las credenciales de usuario no son válidas. No se ha encontrado un usuaario y contraseña coincidentes. |
| Error  | `INTERNAL_SERVER_ERROR_KO`     | Error interno del servidor (o de Base de datos).                                                       |

##### Ejemplo de respuesta exitosa (status 200):

~~~
{
    "ok": true,
    "code": "RECORDSET_DATA",
    "resource": {
        "jwtToken": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwL1BzRWR1Y2EtQmFja2VuZCIsInN1YiI6MSwiaWF0IjoxNzMyNDU0NjYwLCJleHAiOjE3MzUwNDY2NjAsInJvbGUiOiJBRE1JTl9HTE9CQUwifQ.mFZTb0qS3C35UEg-PexWmt_Heg-JtRtapZ-ssRuW0Kc",
        "tokenExpirationDate": 1735046660,
        "user": {
            "id": 1,
            "role": "ADMIN_GLOBAL",
            "name": "marimar",
            "fullName": "marimar señoran garcia"
        }
    }
}
~~~

##### Ejemplo de respuesta con error (status 404):

~~~

{
    "ok": false,
    "code": [
        "USER_CREDENTIALS_INVALID_KO"
    ]
}

~~~

##### Ejemplo de respuesta con error (status 503 o status 500):

~~~

{
    "ok": false,
    "code": [
        "INTERNAL_SERVER_ERROR_KO"
    ]
}

~~~