# UserController

API destinada a la gestión CRUD de usuarios

## Validaciones de permisos

En esta API se aplican validaciones de permisos a todas las acciones, para garantizar que solo los usuarios con los
permisos adecuados puedan realizarlas. Los posibles mensajes de error en esta API debido a validaciones de permisos se
encuentran en la carpeta `validation`.

<span style="color: red;">OJO</span>: Estos errores no se vuelven a mostrar en cada acción de forma individual para
evitar enguarrar el documento. Las APIs podrán devolver errores de autenticación siempre que requieran autenticación.


## Validaciones de formato

En esta API se aplican validaciones de formato a todos los elementos enviados en las peticiones, así como validaciones 
de acción para garantizar la integridad de los datos. Los posibles mensajes de error en esta API debido a validaciones 
de formato se encuentran en la siguiente tabla:

<span style="color: red;">OJO</span>: Estos errores no se vuelven a mostrar en cada acción de forma individual para 
evitar enguarrar el documento. Las APIs podrán devolver errores de formato siempre que se envíe un elemento que no 
cumple el formato o no se envíe y sea obligatorio.

| Tipo  | Código                           | Descripción                                                                                      |
|-------|----------------------------------|--------------------------------------------------------------------------------------------------|
| Error | `ID_MINIMO_F_KO`                 | id no cumple que sea un número >= 0                                                              |
| Error | `ID_INVALIDO_F_KO`               | id no cumple que es número positivo                                                              |
| Error | `NOMBRE_USUARIO_MINIMO_F_KO`     | nombre_usuario no cumple longitud mínima (mínimo 4 caracteres)                                   |
| Error | `NOMBRE_USUARIO_MAXIMO_F_KO`     | nombre_usuario no cumple longitud máxima (máximo 254 caracteres)                                 |
| Error | `NOMBRE_USUARIO_CARACTERES_F_KO` | nombre_usuario no cumple caracteres (solo a-z, A-Z, -, _, 0-9)                                   |
| Error | `NOMBRE_COMPLETO_MINIMO_F_KO`    | nombre_usuario no cumple longitud mínima (mínimo 4 caracteres)                                   |
| Error | `NOMBRE_COMPLETO_MAXIMO_F_KO`    | nombre_completo no cumple longitud máxima (máximo 254 caracteres)                                |
| Error | `NOMBRE_COMPLETO_CARACTERES_F_KO`| nombre_completo no cumple caracteres (solo a-z, ñ, A-Z, Ñ, -, _, 0-9, áéíóú, ÁÉÍÓÚ, espacio, ª, º) |
| Error | `CONTRASENHA_MINIMO_F_KO`        | contrasenha no cumple longitud mínima (mínimo 4 caracteres)                                      |
| Error | `CONTRASENHA_MAXIMO_F_KO`        | contrasenha no cumple longitud máxima (máximo 254 caracteres)                                    |
| Error | `CONTRASENHA_CARACTERES_F_KO`    | contrasenha no cumple caracteres (solo a-z, A-Z, -, _, 0-9, $, @, (, ), ., +, =, /)              |
| Error | `ROL_F_KO`                       | rol no cumple con los valores permitidos (ADMIN_GLOBAL, GESTOR_CATALOGO, USUARIO_PYP)            |

Nota: Para más información sobre los errores de formato, consultar la documentación de formato en la carpeta `validation/format`.


## Acciones implementadas

### Add

Permite dar de alta a un nuevo usuario.

#### Request

##### Requisitos de autenticación

Se necesita enviar token de autenticación en la cabecera `Authorization` de la petición, precedido de la palabra 
`Bearer`. Aplica validación de permisos. Ver tabla de validaciones de permisos para información sobre los códigos de
error relacionados.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=user&action=add

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos
Se requiere enviar los siguientes campos:

- `userName` (string, obligatorio): Nombre de usuario.
- `fullName` (string, obligatorio): Nombre completo del usuario.
- `role` (string, obligatorio): Rol del usuario. Puede ser `ADMIN_GLOBAL`, `GESTOR_CATALOGO` o `USUARIO_PYP`.
- `password` (string, obligatorio): Contraseña. Se envía en claro, se cifra en el servidor.

#### Response

##### Códigos:

A estos errores, hay que sumar los de formato y los de permisos (si procede).

| Tipo   | Código                          | Descripción                                                               |
|--------|---------------------------------|---------------------------------------------------------------------------|
| Éxito  | `RECORDSET_DATA`                | Datos recuperados correctamente.                                          |
| Error  | `NOMBRE_USUARIO_YA_EXISTE_A_KO` | El nombre de usuario coincide con el de otro usuario del sistema PsEduca. |
| Error  | `INTERNAL_SERVER_ERROR_KO`      | Error interno del servidor (o de Base de datos).                          |

##### Ejemplo de respuesta exitosa (status 201):

Se ha añadido correctamente el usuario. Esta respueta coincide (salvo status) con las de **edit** y **get**.

~~~
{
    "ok": true,
    "code": [
        "RECORDSET_DATA"
    ],
    "resource": {
        "id": "29",
        "role": "ADMIN_GLOBAL",
        "name": "marimar1",
        "fullName": "María del Mar García Señorán1"
    }
}
~~~

##### Ejemplo de respuesta con error (status 400):

En esta respuesta se han enviado datos que no cumplen con el formato establecido.

~~~

{
    "ok": false,
    "code": [
        "NOMBRE_USUARIO_MINIMO_F_KO",
        "CONTRASENHA_CARACTERES_F_KO",
        "ROL_F_KO"
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

Error provocado por existir ya un usuario con el nombre de usuario enviado. Los nombres de usuario no se pueden repetir.

~~~

{
    "ok": false,
    "code": [
        "NOMBRE_USUARIO_YA_EXISTE_A_KO"
    ]
}

~~~

### Edit

Permite editar los datos de un usuario ya existente.

#### Request

##### Requisitos de autenticación

Se necesita enviar token de autenticación en la cabecera `Authorization` de la petición, precedido de la palabra
`Bearer`. Aplica validación de permisos. Ver tabla de validaciones de permisos para información sobre los códigos de
error relacionados.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=user&action=edit

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos
Se requiere enviar los siguientes campos:

- `id` (int, obligatorio): Identificador del usuario.

Además, se pueden enviar los siguientes campos, tanto si se desean cambiar, como si se envían iguales y no se realizan
cambios:

- `userName` (string, voluntario): Nombre de usuario.
- `fullName` (string, voluntario): Nombre completo del usuario.
- `role` (string, voluntario): Rol del usuario. Puede ser `ADMIN_GLOBAL`, `GESTOR_CATALOGO` o `USUARIO_PYP`.
- `password` (string, voluntario): Contraseña. Se envía en claro, se cifra en el servidor.

#### Response

##### Códigos:

A estos errores, hay que sumar los de formato y los de permisos (si procede).

| Tipo   | Código                          | Descripción                                                               |
|--------|---------------------------------|---------------------------------------------------------------------------|
| Éxito  | `RECORDSET_DATA`                | Datos recuperados correctamente.                                          |
| Error  | `NOMBRE_USUARIO_YA_EXISTE_A_KO` | El nombre de usuario coincide con el de otro usuario del sistema PsEduca. |
| Error  | `USUARIO_NO_ENCONTRADO_A_KO`    | El usuario a editar no ha sido encontrado.                                |
| Error  | `INTERNAL_SERVER_ERROR_KO`      | Error interno del servidor (o de Base de datos).                          |

##### Ejemplo de respuesta exitosa (status 200):

Se ha editado correctamente el usuario. Esta respueta coincide con la de **add** (salvo status) y **get**.

~~~
{
    "ok": true,
    "code": [
        "RECORDSET_DATA"
    ],
    "resource": {
        "id": "10",
        "role": "ADMIN_GLOBAL",
        "name": "marimar",
        "fullName": "María del Mar García Señorán"
    }
}
~~~

##### Ejemplo de respuesta con error (status 400):

En esta respuesta se han enviado datos que no cumplen con el formato establecido.

~~~

{
    "ok": false,
    "code": [
        "NOMBRE_USUARIO_MINIMO_F_KO",
        "CONTRASENHA_CARACTERES_F_KO",
        "ROL_F_KO"
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

Error provocado por existir ya un usuario con el nombre de usuario enviado. Los nombres de usuario no se pueden repetir.

~~~

{
    "ok": false,
    "code": [
        "NOMBRE_USUARIO_YA_EXISTE_A_KO"
    ]
}

~~~

### Delete

Permite eliminar los datos de un usuario ya existente.

#### Request

##### Requisitos de autenticación

Se necesita enviar token de autenticación en la cabecera `Authorization` de la petición, precedido de la palabra
`Bearer`. Aplica validación de permisos. Ver tabla de validaciones de permisos para información sobre los códigos de
error relacionados.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=user&action=delete

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos
Se requiere enviar los siguientes campos:

- `id` (int, obligatorio): Identificador del usuario.

#### Response

##### Códigos:

A estos errores, hay que sumar los de formato y los de permisos (si procede).

| Tipo   | Código                          | Descripción                                      |
|--------|---------------------------------|--------------------------------------------------|
| Éxito  | `RECORDSET_DATA`                | Datos recuperados correctamente.                 |
| Error  | `USUARIO_NO_ENCONTRADO_A_KO`    | El usuario a eliminar no ha sido encontrado.     |
| Error  | `INTERNAL_SERVER_ERROR_KO`      | Error interno del servidor (o de Base de datos). |

##### Ejemplo de respuesta exitosa (status 200):

Se ha eliminado correctamente el usuario. No se envía recurso, ya que se ha eliminado. El status más correcto sería 204,
pero ese código no permite enviar cuerpo en la respuesta y por ese motivo se decidió mantener el status 200.

~~~
{
    "ok": true,
    "code": [
        "RECORDSET_EMPTY"
    ]
}
~~~

##### Ejemplo de respuesta con error (status 400):

En esta respuesta se han enviado datos que no cumplen con el formato establecido. Es decir, el id no está o no es un 
número positivo.

~~~

{
    "ok": false,
    "code": [
        "ID_MINIMO_F_KO"
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

Error provocado por no existir un usuario con el id enviado.

~~~

{
    "ok": false,
    "code": [
        "USUARIO_NO_ENCONTRADO_A_KO"
    ]
}

~~~

### Get

Permite obtener los datos de un usuario mediante su identificador (id).

#### Request

##### Requisitos de autenticación

Se necesita enviar token de autenticación en la cabecera `Authorization` de la petición, precedido de la palabra
`Bearer`. Aplica validación de permisos. Ver tabla de validaciones de permisos para información sobre los códigos de
error relacionados.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=user&action=get

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos
Se requiere enviar los siguientes campos:

- `id` (int, obligatorio): Identificador del usuario.

#### Response

##### Códigos:

A estos errores, hay que sumar los de formato y los de permisos (si procede).

| Tipo   | Código                          | Descripción                                      |
|--------|---------------------------------|--------------------------------------------------|
| Éxito  | `RECORDSET_DATA`                | Datos recuperados correctamente.                 |
| Error  | `USUARIO_NO_ENCONTRADO_A_KO`    | El usuario a recuperar no ha sido encontrado.    |
| Error  | `INTERNAL_SERVER_ERROR_KO`      | Error interno del servidor (o de Base de datos). |

##### Ejemplo de respuesta exitosa (status 200):

Se ha encontrado el usuario. Esta respueta coincide con la de **add** y **edit** (salvo status).

~~~
{
    "ok": true,
    "code": [
        "RECORDSET_DATA"
    ],
    "resource": {
        "id": "10",
        "role": "ADMIN_GLOBAL",
        "name": "marimar",
        "fullName": "María del Mar García Señorán"
    }
}
~~~

##### Ejemplo de respuesta con error (status 400):

En esta respuesta se han enviado datos que no cumplen con el formato establecido. Es decir, el id no está o no es un
número positivo.

~~~

{
    "ok": false,
    "code": [
        "ID_MINIMO_F_KO"
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

Error provocado por no existir un usuario con el id enviado.

~~~

{
    "ok": false,
    "code": [
        "USUARIO_NO_ENCONTRADO_A_KO"
    ]
}

~~~

### List

Permite obtener los datos de todos los usuarios

#### Request

##### Requisitos de autenticación

Se necesita enviar token de autenticación en la cabecera `Authorization` de la petición, precedido de la palabra
`Bearer`. Aplica validación de permisos. Ver tabla de validaciones de permisos para información sobre los códigos de
error relacionados.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=user&action=list

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos
No se requiere enviar campos.

#### Response

##### Códigos:

A estos errores, hay que sumar los de permisos (si procede).

| Tipo   | Código                     | Descripción                                                               |
|--------|----------------------------|---------------------------------------------------------------------------|
| Éxito  | `RECORDSET_DATA`           | Datos recuperados correctamente.                                          |
| Error  | `INTERNAL_SERVER_ERROR_KO` | Error interno del servidor (o de Base de datos).                          |

Nota: El código `RECORDSET_EMPTY` no puede ser devuelto en esta acción, ya que siempre tiene que haber
usuarios en el sistema. No se contempla el caso de que no haya usuarios.

##### Ejemplo de respuesta exitosa (status 200):

Se lista todos los usuarios existentes.

~~~
{
    "ok": true,
    "code": [
        "RECORDSET_DATA"
    ],
    "resource": [
        {
            "id": "10",
            "role": "ADMIN_GLOBAL",
            "name": "marimar",
            "fullName": "María del Mar García Señorán"
        },
        {
            "id": "30",
            "role": "ADMIN_GLOBAL",
            "name": "root",
            "fullName": "root"
        }
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

