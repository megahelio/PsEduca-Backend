# AuthController

API destinada a la autenticación de usuarios.

## Acciones

### Login

Permite iniciar sesión en la aplicación.

#### Request

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=auth&action=login

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos
Se requiere enviar los siguientes campos:

 - `userName` (string, obligatorio): Nombre de usuario.
 - `password` (string, obligatorio): Contraseña. Se envía en claro, se cifra en el servidor.

#### Response

##### Códigos:

| Tipo  | Código                           | Descripción                                                                                            |
|-------|----------------------------------|--------------------------------------------------------------------------------------------------------|
| Éxito | `RECORDSET_DATA`                 | Datos recuperados correctamente.                                                                       |
| Error | `USER_CREDENTIALS_INVALID_KO`    | Las credenciales de usuario no son válidas. No se ha encontrado un usuaario y contraseña coincidentes. |
| Error | `INTERNAL_SERVER_ERROR_KO`       | Error interno del servidor (o de Base de datos).                                                       |
| Error | `NOMBRE_USUARIO_MINIMO_F_KO`     | nombre_usuario no cumple longitud mínima (mínimo 4 caracteres)                                         |
| Error | `NOMBRE_USUARIO_MAXIMO_F_KO`     | nombre_usuario no cumple longitud máxima (máximo 254 caracteres)                                       |
| Error | `NOMBRE_USUARIO_CARACTERES_F_KO` | nombre_usuario no cumple caracteres (solo a-z, A-Z, -, _, 0-9)                                         |
| Error | `CONTRASENHA_MINIMO_F_KO`        | contrasenha no cumple longitud mínima (mínimo 4 caracteres)                                            |
| Error | `CONTRASENHA_MAXIMO_F_KO`        | contrasenha no cumple longitud máxima (máximo 254 caracteres)                                          |
| Error | `CONTRASENHA_CARACTERES_F_KO`    | contrasenha no cumple caracteres (solo a-z, A-Z, -, _, 0-9, $, @, (, ), ., +, =, /)                    |

##### Ejemplo de respuesta exitosa (status 200):

~~~
{
    "ok": true,
    "code": [
        "RECORDSET_DATA"
    ],
    "resource": {
        "jwtToken": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlzcyI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MFwvUHNFZHVjYS1CYWNrZW5kIn0.eyJzdWIiOiIxMCIsImlhdCI6MTczMjgzODA4NywiZXhwIjoxNzM1NDMwMDg3LCJyb2xlIjoiQURNSU5fR0xPQkFMIiwiaXNzIjoiaHR0cDpcL1wvbG9jYWxob3N0OjgwXC9Qc0VkdWNhLUJhY2tlbmQifQ.idMGKSoU1fREhT4W90Y7DU4pzH2UdGyT15jqsl9YOVI",
        "tokenExpirationDate": 1735430087,
        "user": {
            "id": "10",
            "role": "ADMIN_GLOBAL",
            "name": "marimar",
            "fullName": "María del Mar García Señorán"
        }
    }
}
~~~

##### Ejemplo de respuesta con error (status 404):

En esta respuesta se ha enviado un nombre de usuario o contraseña que no coincide con el de ningún usuario.

~~~

{
    "ok": false,
    "code": [
        "USER_CREDENTIALS_INVALID_KO"
    ]
}

~~~

##### Ejemplo de respuesta con error (status 503 o status 500):

En esta respuesta se ha producido un error del servidor, con origen en la base de datos (status 503) o error no previsto en servidor (status 500).

~~~

{
    "ok": false,
    "code": [
        "INTERNAL_SERVER_ERROR_KO"
    ]
}

~~~

##### Ejemplo de respuesta con error (status 400):

Error provocado por no cumplir con el formato establecido para el nombre de usuario y contraseña.

~~~

{
    "ok": false,
    "code": [
        "NOMBRE_USUARIO_CARACTERES_F_KO",
        "CONTRASENHA_CARACTERES_F_KO"
    ]
}

~~~