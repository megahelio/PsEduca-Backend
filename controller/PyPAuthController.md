# PyPAuthController - Apartado Pruebas y Programas

API destinada a la gestión CRUD de autorizaciones para items de pruebas y programas de la plataforma PsEduca.

## Validaciones de permisos

En esta API se aplican validaciones de permisos a las acciones ADD, LIST y DELETE, para garantizar que solo los usuarios con los
permisos adecuados puedan realizarlas. Los posibles mensajes de error en esta API debido a validaciones de permisos se
encuentran en la carpeta `validation`. Solo se permite el acceso a los usuarios `ADMIN_GLOBAL`.

<span style="color: red;">OJO</span>: Estos errores no se vuelven a mostrar en cada acción de forma individual para
evitar enguarrar el documento. Las APIs podrán devolver errores de autenticación siempre que requieran autenticación.

## Validaciones de formato

En esta API se aplican validaciones de formato a todos los elementos enviados en las peticiones (salvo el formato de "fichero"),
así como validaciones de acción para garantizar la integridad de los datos. Los posibles mensajes de error en esta API 
debido a validaciones de formato se encuentran en la siguiente tabla:

<span style="color: red;">OJO</span>: Estos errores no se vuelven a mostrar en cada acción de forma individual para 
evitar enguarrar el documento. Las APIs podrán devolver errores de formato siempre que se envíe un elemento que no 
cumple el formato o no se envíe y sea obligatorio.

| Código de error             | Entidad     | Atributo  | Acción      | Prueba                         | Valor erróneo      | 
|-----------------------------|-------------|-----------|-------------|--------------------------------|--------------------|
| `ID_PYP_ITEM_MINIMO_F_KO`   | Divulgación | idPyPItem | ADD, DELETE | Validar que sea un número >= 0 | null, "abc"        |
| `ID_PYP_ITEM_INVALIDO_F_KO` | Divulgación | idPyPItem | ADD, DELETE | Validar que es número positivo | null, "abc", "-10" |
| `ID_USUARIO_MINIMO_F_KO`    | Divulgación | idUsuario | ADD, DELETE | Validar que sea un número >= 0 | null, "abc"        |
| `ID_USUARIO_INVALIDO_F_KO`  | Divulgación | idUsuario | ADD, DELETE | Validar que es número positivo | null, "abc", "-10" |

Nota: Para más información sobre los errores de formato, consultar la documentación de formato en la carpeta `validation/format`.


## Acciones implementadas

### Add

Permite generar una autorización para un item de pruebas y programas, de forma que un usuario pueda acceder a él.

#### Request

##### Requisitos de autenticación

Se necesita enviar token de autenticación en la cabecera `Authorization` de la petición, precedido de la palabra 
`Bearer`. Aplica validación de permisos. Ver tabla de validaciones de permisos para información sobre los códigos de
error relacionados.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=pypItem&action=add

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos
Se requiere enviar los siguientes campos:

- `idPyPItem` (string, obligatorio): Id del item de pruebas y programas.
- `idUser` (string, obligatorio): Id del usuario al que se le quiere conceder acceso.

#### Response

##### Códigos:

A estos códigos, hay que sumar los de formato y los de permisos (si procede).

| Tipo   | Código                            | Descripción                                                                    |
|--------|-----------------------------------|--------------------------------------------------------------------------------|
| Éxito  | `RECORDSET_DATA`                  | Datos recuperados correctamente.                                               |
| Error  | `INTERNAL_SERVER_ERROR_KO`        | Error interno del servidor (o de Base de datos).                               |
| Error  | `ITEM_PYP_NO_ENCONTRADO_A_KO`     | El item de pruebas y programas mencionado no ha sido encontrado.               |
| Error  | `USUARIO_NO_ENCONTRADO_A_KO`      | El usuario mencionado no ha sido encontrado.                                   |
| Error  | `AUTORIZACION_PYP_YA_EXISTE_A_KO` | La autorización de pruebas y programas a añadir ya existe en la base de datos. |

##### Ejemplo de respuesta exitosa (status 201):

Se ha añadido correctamente el item de pruebas y programas. Esta respueta coincide (salvo status) con las de **edit** y **get**.

~~~

{
    "ok": true,
    "code": [
        "RECORDSET_EMPTY"
    ]
}

~~~

##### Ejemplo de respuesta con error (status 400):

En esta respuesta se han enviado datos que no cumplen con el formato establecido.

~~~

{
    "ok": false,
    "code": [
        "ID_PYP_ITEM_INVALIDO_F_KO",
        "ID_USUARIO_INVALIDO_F_KO"
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

##### Ejemplo de respuesta con error (status 401):

Error provocado por intentar realizar una acción sin autenticación.

~~~

{
    "ok": false,
    "code": [
        "AUTHENTICATION_REQUIRED_KO"
    ]
}

~~~

### Delete

Permite eliminar una autorización para un item de pruebas y programas, de forma que al usuario se le revoca el acceso a dicho item.

#### Request

##### Requisitos de autenticación

Se necesita enviar token de autenticación en la cabecera `Authorization` de la petición, precedido de la palabra
`Bearer`. Aplica validación de permisos. Ver tabla de validaciones de permisos para información sobre los códigos de
error relacionados.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=pypAuth&action=delete

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos
Se requiere enviar los siguientes campos:

- `idPyPItem` (string, obligatorio): Id del item de pruebas y programas.
- `idUser` (string, obligatorio): Id del usuario al que se le quiere conceder acceso.

#### Response

##### Códigos:

A estos errores, hay que sumar los de formato y los de permisos (si procede).

| Tipo   | Código                                | Descripción                                                              |
|--------|---------------------------------------|--------------------------------------------------------------------------|
| Éxito  | `RECORDSET_EMPTY`                     | No aplica recuperar datos.                                               |
| Error  | `AUTORIZACION_PYP_NO_ENCONTRADA_A_KO` | La autorización de pruebas y programas a eliminar no ha sido encontrada. |
| Error  | `INTERNAL_SERVER_ERROR_KO`            | Error interno del servidor (o de Base de datos).                         |

##### Ejemplo de respuesta exitosa (status 200):

Se ha eliminado correctamente el item de pruebas y programas. No se envía recurso, ya que se ha eliminado. El status más correcto sería 204,
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
        "ID_PYP_ITEM_MINIMO_F_KO",
        "ID_USUARIO_INVALIDO_F_KO"
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

Error provocado por no existir una autorización para el usuario e item de pruebas y programas con los ids enviados.

~~~

{
    "ok": false,
    "code": [
        "AUTORIZACION_PYP_NO_ENCONTRADA_A_KO"
    ]
}

~~~

### List

Permite obtener las autorizaciones para todos los items de pruebas y programas existentes. 
Así mismo se facilita información sobre los usuarios que no tienen permisos sobre ningún item.

#### Request

##### Requisitos de autenticación

Se necesita enviar token de autenticación en la cabecera `Authorization` de la petición, precedido de la palabra
`Bearer`. Aplica validación de permisos. Ver tabla de validaciones de permisos para información sobre los códigos de
error relacionados.

Roles con acceso a esta API: `USUARIO_PYP`, `ADMIN_GLOBAL` y eventualmente `GESTOR_CATALOGO`.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=pypAuth&action=list

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos

No se requiere enviar campos.

#### Response

##### Códigos:

| Tipo   | Código                     | Descripción                                                                         |
|--------|----------------------------|-------------------------------------------------------------------------------------|
| Éxito  | `RECORDSET_DATA`           | Datos recuperados correctamente.                                                    |
| Error  | `INTERNAL_SERVER_ERROR_KO` | Error interno del servidor (o de Base de datos).                                    |

Notas:
- El error `RECORDSET_EMPTY` no se devuelve en esta acción, ya que siempre habrá al menos un usuario con o sin permisos en el momento de llamar a la API. 

##### Ejemplo de respuesta exitosa (status 200):

Se lista todas las autorizacioens items de pruebas y programas existentes.

~~~

{
    "ok": true,
    "code": [
        "RECORDSET_DATA"
    ],
    "resource": {
        "currentPyPAuthorizations": [
            {
                "idPyPItem": "1",
                "idUser": "1"
            }
        ],
        "otherPyPItems": [
            {
                "idPyPItem": "3",
                "title": "TITULO"
            }
        ],
        "otherUsers": []
    }
}

~~~

##### Ejemplo de respuesta exitosa (status 200):

No hay autorizaciones para items de pruebas y programas en la base de datos.

~~~

{
    "ok": true,
    "code": [
        "RECORDSET_DATA"
    ],
    "resource": {
        "currentPyPAuthorizations": [],
        "otherPyPItems": [
            {
                "idPyPItem": "1",
                "title": "Test 1"
            },
            {
                "idPyPItem": "3",
                "title": "TITULO"
            }
        ],
        "otherUsers": [
            {
                "idUser": "1",
                "name": "root"
            }
        ]
    }
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

