# MemberController - Apartado Quienes Somos

API destinada a la gestión CRUD de miembros de la plataforma PsEduca.

## Validaciones de permisos

En esta API se aplican validaciones de permisos a las acciones ADD, EDIT, DELETE, para garantizar que solo los usuarios con los
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


| Tipo | Código de error                | Descripcion                                                                                                                          
| ---- |--------------------------------|--------------------------------------------------------------------------------------------------------------------------------------|
| Error| `ID_MINIMO_F_KO`               | id no cumple que sea un número >= 0                                                                                                  |
| Error| `ID_INVALIDO_F_KO`             | id no cumple que es número positivo                                                                                                  |
| Error| `NOMBRE_MIEMBRO_MINIMO_F_KO`    | Validar longitud mínima (mínimo 4 caracteres)                                                                                        |
| Error| `NOMBRE_MIEMBRO_MAXIMO_F_KO`    | Validar longitud máxima (máximo 254 caracteres)                                                                                      |
| Error| `NOMBRE_MIEMBRO_CARACTERES_F_KO` | Validar caracteres (solo a-z, ñ, A-Z, Ñ, -, _, 0-9, áéíóú, ÁÉÍÓÚ, espacio, ª, º)                                                     |
| Error| `DESCRIPCION_MINIMO_F_KO`       | Validar longitud mínima (mínimo 4 caracteres). **Si admite nulos (o cadenas vacías)**                                                |
| Error| `DESCRIPCION_MAXIMO_F_KO`       | Validar longitud máxima (máximo 1000 caracteres)                                                                                     |
| Error| `DESCRIPCION_CARACTERES_F_KO`   | Validar caracteres (permitir cualquier carácter excepto \a (bell))                                                                   |
| Error| `EMAIL_MIEMBRO_INVALIDO_F_KO`   | Validar formato de email (debe cumplir el estándar RFC 5322)                                                                         |
| Error| `LINK_MINIMO_F_KO`              | Validar longitud mínima (mínimo 4 caracteres). **Si admite nulos (o cadenas vacías)**                                                |
| Error| `LINK_MAXIMO_F_KO`              | Validar longitud máxima (máximo 254 caracteres)                                                                                      |
| Error| `LINK_INVALIDO_F_KO`            | Validar formato de URI (debe cumplir el estándar RFC 2396)                                                                           |
| Error| `IMAGEN_FORMATO_F_KO`           | Validar que el formato de imagen esté entre los siguientes: jpeg, jpg, png, gif, svg, webp, avif, ico. **La imagen puede ser nula.** |
| Error| `IMAGEN_TAMAÑO_F_KO`            | Validar que el tamaño del fichero sea menor a 10mb. **La imagen puede ser nula.**                                                    |

Nota: Para más información sobre los errores de formato, consultar la documentación de formato en la carpeta `validation/format`.


## Acciones implementadas

### Add

Permite dar de alta a un nuevo miembro.

#### Request

##### Requisitos de autenticación

Se necesita enviar token de autenticación en la cabecera `Authorization` de la petición, precedido de la palabra 
`Bearer`. Aplica validación de permisos. Ver tabla de validaciones de permisos para información sobre los códigos de
error relacionados.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=member&action=add

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos
Se requiere enviar los siguientes campos:

- `name` (string, obligatorio): Nombre del miembro.
- `email` (string, voluntario): Email del miembro.
- `description` (string, voluntario): Descripción del miembro.
- `referenceURL` (string, voluntario): Enlace a información adicional del miembro.
- `image` (file, voluntario): Imagen del miembro. Si no se indica, se asigna una por defecto. Ojo, por problemas de 
compatibilidad con form-data, una vez se asigne una imagen, no se podrá eliminar, solo se podra cambiar.

#### Response

##### Códigos:

A estos códigos, hay que sumar los de formato y los de permisos (si procede).

| Tipo   | Código                          | Descripción                                                               |
|--------|---------------------------------|---------------------------------------------------------------------------|
| Éxito  | `RECORDSET_DATA`                | Datos recuperados correctamente.                                          |
| Error  | `INTERNAL_SERVER_ERROR_KO`      | Error interno del servidor (o de Base de datos).                          |

##### Ejemplo de respuesta exitosa (status 201):

Se ha añadido correctamente el miembro. Esta respueta coincide (salvo status) con las de **edit** y **get**.

~~~

{
    "ok": true,
    "code": [
        "RECORDSET_DATA"
    ],
    "resource": {
        "id": "21",
        "name": "Mariano",
        "email": "mariano@uyvigo.gal",
        "description": "Lo que Mariano tenga que decir.",
        "referenceURL": "http://mariano.com",
        "imageURL": "http://localhost:80/PsEduca-Backend/uploads/6751e45fd99cd1.60751926.jpg"
    }
}

~~~

##### Ejemplo de respuesta con error (status 400):

En esta respuesta se han enviado datos que no cumplen con el formato establecido.

~~~

{
    "ok": false,
    "code": [
        "NOMBRE_MIEMBRO_MINIMO_F_KO",
        "DESCRIPCION_CARACTERES_F_KO",
        "LINK_INVALIDO_F_KO"
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

### Edit

Permite editar los datos de un miembro ya existente.

#### Request

##### Requisitos de autenticación

Se necesita enviar token de autenticación en la cabecera `Authorization` de la petición, precedido de la palabra
`Bearer`. Aplica validación de permisos. Ver tabla de validaciones de permisos para información sobre los códigos de
error relacionados.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=member&action=edit

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos
Se requiere enviar los siguientes campos:

- `id` (int, obligatorio): Identificador del miembro.

Además, se pueden enviar los siguientes campos, tanto si se desean cambiar, como si se envían iguales y no se realizan
cambios:

- `name` (string, obligatorio): Nombre del miembro.
- `email` (string, voluntario): Email del miembro.
- `description` (string, voluntario): Descripción del miembro.
- `referenceURL` (string, voluntario): Enlace a información adicional del miembro.
- `image` (file, voluntario): Imagen del miembro. Si no se indica, se asigna una por defecto. Ojo, por problemas de
  compatibilidad con form-data, una vez se asigne una imagen, no se podrá eliminar, solo se podra cambiar.

#### Response

##### Códigos:

A estos códigos, hay que sumar los de formato y los de permisos (si procede).

| Tipo   | Código                          | Descripción                                      |
|--------|---------------------------------|--------------------------------------------------|
| Éxito  | `RECORDSET_DATA`                | Datos recuperados correctamente.                 |
| Error  | `MIEMBRO_NO_ENCONTRADO_A_KO`    | El miembro a editar no ha sido encontrado.       |
| Error  | `INTERNAL_SERVER_ERROR_KO`      | Error interno del servidor (o de Base de datos). |

##### Ejemplo de respuesta exitosa (status 200):

Se ha editado correctamente el miembro. Esta respueta coincide con la de **add** (salvo status) y **get**.

~~~

{
    "ok": true,
    "code": [
        "RECORDSET_DATA"
    ],
    "resource": {
        "id": "29",
        "name": "Mariano",
        "email": "mariano@uyvigo.gal",
        "description": "Lo que Mariano tenga que decir",
        "referenceURL": "http://mariano.com",
        "imageURL": "http://localhost:80/PsEduca-Backend/uploads/675201e0e64952.82738337.png"
    }
}

~~~

##### Ejemplo de respuesta con error (status 400):

En esta respuesta se han enviado datos que no cumplen con el formato establecido.

~~~

{
    "ok": false,
    "code": [
        "ID_INVALIDO_F_KO"
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

Error provocado por no existir un miembro con el número de miembro enviado. Los identificadores no se pueden repetir.

~~~

{
    "ok": false,
    "code": [
        "MIEMBRO_NO_ENCONTRADO_A_KO"
    ]
}

~~~

### Delete

Permite eliminar los datos de un miembro ya existente.

#### Request

##### Requisitos de autenticación

Se necesita enviar token de autenticación en la cabecera `Authorization` de la petición, precedido de la palabra
`Bearer`. Aplica validación de permisos. Ver tabla de validaciones de permisos para información sobre los códigos de
error relacionados.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=member&action=delete

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos
Se requiere enviar los siguientes campos:

- `id` (int, obligatorio): Identificador del miembro.

#### Response

##### Códigos:

A estos errores, hay que sumar los de formato y los de permisos (si procede).

| Tipo   | Código                       | Descripción                                      |
|--------|------------------------------|--------------------------------------------------|
| Éxito  | `RECORDSET_EMPTY`            | No aplica recuperar datos.                       |
| Error  | `MIEMBRO_NO_ENCONTRADO_A_KO` | El miembro a eliminar no ha sido encontrado.     |
| Error  | `INTERNAL_SERVER_ERROR_KO`   | Error interno del servidor (o de Base de datos). |

##### Ejemplo de respuesta exitosa (status 200):

Se ha eliminado correctamente el miembro. No se envía recurso, ya que se ha eliminado. El status más correcto sería 204,
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

Error provocado por no existir un miembro con el id enviado.

~~~

{
    "ok": false,
    "code": [
        "MIEMBRO_NO_ENCONTRADO_A_KO"
    ]
}

~~~

### Get

Permite obtener los datos de un miembro mediante su identificador (id).

#### Request

##### Requisitos de autenticación

No se necesita autenticación para esta acción.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=member&action=get

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos
Se requiere enviar los siguientes campos:

- `id` (int, obligatorio): Identificador del miembro.

#### Response

##### Códigos:

A estos errores, hay que sumar los de formato y los de permisos (si procede).

| Tipo   | Código                       | Descripción                                      |
|--------|------------------------------|--------------------------------------------------|
| Éxito  | `RECORDSET_DATA`             | Datos recuperados correctamente.                 |
| Error  | `MIEMBRO_NO_ENCONTRADO_A_KO` | El miembro a recuperar no ha sido encontrado.    |
| Error  | `INTERNAL_SERVER_ERROR_KO`   | Error interno del servidor (o de Base de datos). |

##### Ejemplo de respuesta exitosa (status 200):

Se ha encontrado el miembro. Esta respueta coincide con la de **add** y **edit** (salvo status).

~~~

{
    "ok": true,
    "code": [
        "RECORDSET_DATA"
    ],
    "resource": {
        "id": "28",
        "name": "Mariano",
        "email": "mariano@uyvigo.gal",
        "description": "Lo que Mariano tenga que decir",
        "referenceURL": "http://mariano.com",
        "imageURL": "http://localhost:80/PsEduca-Backend/uploads/675201855dc8b2.19802091.jpg"
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

Error provocado por no existir un miembro con el id enviado.

~~~

{
    "ok": false,
    "code": [
        "USUARIO_NO_ENCONTRADO_A_KO"
    ]
}

~~~

### List

Permite obtener los datos de todos los miembros

#### Request

##### Requisitos de autenticación

No se necesita autenticación para esta acción.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=member&action=list

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos

No se requiere enviar campos.

#### Response

##### Códigos:

| Tipo   | Código                     | Descripción                                      |
|--------|----------------------------|--------------------------------------------------|
| Éxito  | `RECORDSET_DATA`           | Datos recuperados correctamente.                 |
| Éxito  | `RECORDSET_EMPTY`          | No aplica recuperar datos. No hay miembros.      |
| Error  | `INTERNAL_SERVER_ERROR_KO` | Error interno del servidor (o de Base de datos). |

##### Ejemplo de respuesta exitosa (status 200):

Se lista todos los miembros existentes.

~~~

{
    "ok": true,
    "code": [
        "RECORDSET_DATA"
    ],
    "resource": [
        {
            "id": "28",
            "name": "Mariano",
            "email": "mariano@uyvigo.gal",
            "description": "Lo que Mariano tenga que decir",
            "referenceURL": "http://mariano.com",
            "imageURL": "http://localhost:80/PsEduca-Backend/uploads/675201855dc8b2.19802091.jpg"
        },
        {
            "id": "30",
            "name": "Mariano",
            "email": "mariano@uyvigo.gal",
            "description": "Lo que Mariano tenga que decir",
            "referenceURL": "http://mariano.com",
            "imageURL": "http://localhost:80/PsEduca-Backend/uploads/67520486a77750.44789697.jpg"
        }
    ]
}

~~~

##### Ejemplo de respuesta exitosa (status 200):

No hay miembros en la base de datos.

~~~

{
    "ok": true,
    "code": [
        "RECORDSET_EMPTY"
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

