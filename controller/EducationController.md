# EducationController - Apartado Formación

API destinada a la gestión CRUD de items de formación de la plataforma PsEduca.

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


| Tipo  | Código de error                  | Descripción                                                                                                                            |
|-------|----------------------------------|----------------------------------------------------------------------------------------------------------------------------------------|
| Error | `ID_MINIMO_F_KO`                | Validar no cumple que sea un número >= 0                                                                                              |
| Error | `ID_INVALIDO_F_KO`              | Validar no cumple que es número positivo                                                                                              |
| Error | `TITULO_FORMACION_MINIMO_F_KO`  | Validar longitud mínima (mínimo 4 caracteres)                                                                                         |
| Error | `TITULO_FORMACION_MAXIMO_F_KO`  | Validar longitud máxima (máximo 254 caracteres)                                                                                       |
| Error | `TITULO_FORMACION_CARACTERES_F_KO` | Validar caracteres (solo a-z, ñ, A-Z, Ñ, -, _, 0-9, áéíóú, ÁÉÍÓÚ, espacio, ª, º)                                                      |
| Error | `DESCRIPCION_MINIMO_F_KO`       | Validar longitud mínima (mínimo 4 caracteres). **Si admite nulos (o cadenas vacías)**                                                 |
| Error | `DESCRIPCION_MAXIMO_F_KO`       | Validar longitud máxima (máximo 1000 caracteres)                                                                                      |
| Error | `DESCRIPCION_CARACTERES_F_KO`   | Validar caracteres (permitir cualquier carácter excepto \a (bell))                                                                    |
| Error | `LINK_MINIMO_F_KO`              | Validar longitud mínima (mínimo 4 caracteres). **Si admite nulos (o cadenas vacías)**                                                 |
| Error | `LINK_MAXIMO_F_KO`              | Validar longitud máxima (máximo 254 caracteres)                                                                                       |
| Error | `LINK_INVALIDO_F_KO`            | Validar formato de URI (debe cumplir el estándar RFC 2396)                                                                            |
| Error | `IMAGEN_FORMATO_F_KO`           | Validar que el formato de imagen esté entre los siguientes: jpeg, jpg, png, gif, svg, webp, avif, ico. **La imagen puede ser nula.**  |
| Error | `IMAGEN_TAMAÑO_F_KO`            | Validar que el tamaño del fichero sea menor a 10mb. **La imagen puede ser nula.**                                                     |
| Error | `ANHO_INICIO_INVALIDO_F_KO`     | Validar no cumple que es número positivo                                                                                              |
| Error | `ANHO_INICIO_MINIMO_F_KO`       | Validar no cumple que sea un número >= 1900. **Si admite nulos (o cadenas vacías)**                                                   |
| Error | `ANHO_INICIO_MAXIMO_F_KO`       | Validar no cumple que sea un número <= 3000                                                                                           |
| Error | `ANHO_FIN_INVALIDO_F_KO`        | Validar no cumple que es número positivo                                                                                              |
| Error | `ANHO_FIN_MINIMO_F_KO`          | Validar no cumple que sea un número >= 1900. **Si admite nulos (o cadenas vacías)**                                                   |
| Error | `ANHO_FIN_MAXIMO_F_KO`          | Validar no cumple que sea un número <= 3000                                                                                           |
| Error | `TIPO_F_KO`                     | Validar que el tipo sea válido (MASTER, DOCTORADO, CURSO)                                                                             |


Nota: Para más información sobre los errores de formato, consultar la documentación de formato en la carpeta `validation/format`.


## Acciones implementadas

### Add

Permite dar de alta a un nuevo item de formación.

#### Request

##### Requisitos de autenticación

Se necesita enviar token de autenticación en la cabecera `Authorization` de la petición, precedido de la palabra 
`Bearer`. Aplica validación de permisos. Ver tabla de validaciones de permisos para información sobre los códigos de
error relacionados.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=education&action=add

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos
Se requiere enviar los siguientes campos:

- `title` (string, obligatorio): Título del item de formación.
- `type` (string, obligatorio): Tipo del item de formación (MASTER, DOCTORADO, CURSO).
- `description` (string, obligatorio): Descripción del item de formación.
- `referenceURL` (string, obligatorio): Enlace a información adicional del item de formación.
- `initYear` (int, obligatorio): Año de inicio del item de formación.
- `endYear` (int, voluntario): Año de fin del item de formación.
- `image` (file, voluntario): Imagen del item de formación. Si no se indica, se asigna una por defecto. Ojo, por problemas de 
compatibilidad con form-data, una vez se asigne una imagen, no se podrá eliminar, solo se podra cambiar.

#### Response

##### Códigos:

A estos códigos, hay que sumar los de formato y los de permisos (si procede).

| Tipo   | Código                          | Descripción                                                               |
|--------|---------------------------------|---------------------------------------------------------------------------|
| Éxito  | `RECORDSET_DATA`                | Datos recuperados correctamente.                                          |
| Error  | `INTERNAL_SERVER_ERROR_KO`      | Error interno del servidor (o de Base de datos).                          |

##### Ejemplo de respuesta exitosa (status 201):

Se ha añadido correctamente el item de formación. Esta respueta coincide (salvo status) con las de **edit** y **get**.

~~~

{
    "ok": true,
    "code": [
        "RECORDSET_DATA"
    ],
    "resource": {
        "id": "4",
        "type": "MASTER",
        "title": "Master en ....",
        "description": "Descripción del master",
        "referenceURL": "http://master.pseduca.com",
        "initYear": "2010",
        "endYear": "2030",
        "imageURL": "http://localhost:80/PsEduca-Backend/static/education_no_photo.jpg"
    }
}

~~~

##### Ejemplo de respuesta con error (status 400):

En esta respuesta se han enviado datos que no cumplen con el formato establecido.

~~~

{
    "ok": false,
    "code": [
        "TITULO_FORMACION_CARACTERES_F_KO",
        "ANHO_INICIO_INVALIDO_F_KO",
        "ANHO_FIN_INVALIDO_F_KO",
        "TIPO_F_KO"
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

Permite editar los datos de un item de formación ya existente.

#### Request

##### Requisitos de autenticación

Se necesita enviar token de autenticación en la cabecera `Authorization` de la petición, precedido de la palabra
`Bearer`. Aplica validación de permisos. Ver tabla de validaciones de permisos para información sobre los códigos de
error relacionados.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=education&action=edit

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos
Se requiere enviar los siguientes campos:

- `id` (int, obligatorio): Identificador del item de formación.

Además, se pueden enviar los siguientes campos, tanto si se desean cambiar, como si se envían iguales y no se realizan
cambios:

- `title` (string, voluntario): Título del item de formación.
- `type` (string, voluntario): Tipo del item de formación (MASTER, DOCTORADO, CURSO).
- `description` (string, voluntario): Descripción del item de formación.
- `referenceURL` (string, voluntario): Enlace a información adicional del item de formación.
- `initYear` (int, voluntario): Año de inicio del item de formación.
- `endYear` (int, voluntario): Año de fin del item de formación.
- `image` (file, voluntario): Imagen del item de formación. Si no se indica, se asigna una por defecto. Ojo, por problemas de
  compatibilidad con form-data, una vez se asigne una imagen, no se podrá eliminar, solo se podra cambiar.

#### Response

##### Códigos:

A estos códigos, hay que sumar los de formato y los de permisos (si procede).

| Tipo   | Código                              | Descripción                                      |
|--------|-------------------------------------|--------------------------------------------------|
| Éxito  | `RECORDSET_DATA`                    | Datos recuperados correctamente.                 |
| Error  | `ITEM_FORMACION_NO_ENCONTRADO_A_KO` | El item de formación a editar no ha sido encontrado.       |
| Error  | `INTERNAL_SERVER_ERROR_KO`          | Error interno del servidor (o de Base de datos). |

##### Ejemplo de respuesta exitosa (status 200):

Se ha editado correctamente el item de formación. Esta respueta coincide con la de **add** (salvo status) y **get**.

~~~

{
    "ok": true,
    "code": [
        "RECORDSET_DATA"
    ],
    "resource": {
        "id": "1",
        "type": "MASTER",
        "title": "Master en A.",
        "description": "Descripción del master",
        "referenceURL": "http://master.pseduca.com",
        "initYear": "2010",
        "endYear": "2030",
        "imageURL": "http://localhost:80/PsEduca-Backend/uploads/6753990c8690b1.31709173.png"
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

Error provocado por no existir un item de formación con el número de item de formación enviado. Los identificadores no se pueden repetir.

~~~

{
    "ok": false,
    "code": [
        "ITEM_FORMACION_NO_ENCONTRADO_A_KO"
    ]
}

~~~

### Delete

Permite eliminar los datos de un item de formación ya existente.

#### Request

##### Requisitos de autenticación

Se necesita enviar token de autenticación en la cabecera `Authorization` de la petición, precedido de la palabra
`Bearer`. Aplica validación de permisos. Ver tabla de validaciones de permisos para información sobre los códigos de
error relacionados.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=education&action=delete

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos
Se requiere enviar los siguientes campos:

- `id` (int, obligatorio): Identificador del item de formación.

#### Response

##### Códigos:

A estos errores, hay que sumar los de formato y los de permisos (si procede).

| Tipo   | Código                              | Descripción                                      |
|--------|-------------------------------------|--------------------------------------------------|
| Éxito  | `RECORDSET_EMPTY`                   | No aplica recuperar datos.                       |
| Error  | `ITEM_FORMACION_NO_ENCONTRADO_A_KO` | El item de formación a eliminar no ha sido encontrado.     |
| Error  | `INTERNAL_SERVER_ERROR_KO`          | Error interno del servidor (o de Base de datos). |

##### Ejemplo de respuesta exitosa (status 200):

Se ha eliminado correctamente item de formación. No se envía recurso, ya que se ha eliminado. El status más correcto sería 204,
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

Error provocado por no existir un item de formación con el id enviado.

~~~

{
    "ok": false,
    "code": [
        "ITEM_FORMACION_NO_ENCONTRADO_A_KO"
    ]
}

~~~

### Get

Permite obtener los datos de un item de formación mediante su identificador (id).

#### Request

##### Requisitos de autenticación

No se necesita autenticación para esta acción.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=education&action=get

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos
Se requiere enviar los siguientes campos:

- `id` (int, obligatorio): Identificador del item de formación.

#### Response

##### Códigos:

A estos errores, hay que sumar los de formato y los de permisos (si procede).

| Tipo   | Código                              | Descripción                                             |
|--------|-------------------------------------|---------------------------------------------------------|
| Éxito  | `RECORDSET_DATA`                    | Datos recuperados correctamente.                        |
| Error  | `ITEM_FORMACION_NO_ENCONTRADO_A_KO` | El item de formación a recuperar no ha sido encontrado. |
| Error  | `INTERNAL_SERVER_ERROR_KO`          | Error interno del servidor (o de Base de datos).        |

##### Ejemplo de respuesta exitosa (status 200):

Se ha encontrado el item de formación. Esta respueta coincide con la de **add** y **edit** (salvo status).

~~~

{
    "ok": true,
    "code": [
        "RECORDSET_DATA"
    ],
    "resource": {
        "id": "2",
        "type": "MASTER",
        "title": "Master en ....",
        "description": "Descripción del master",
        "referenceURL": "http://master.pseduca.com",
        "initYear": "2010",
        "endYear": "2030",
        "imageURL": "http://localhost:80/PsEduca-Backend/uploads/67537fc3bb5210.56276578.png"
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

Error provocado por no existir un item de formación con el id enviado.

~~~

{
    "ok": false,
    "code": [
        "ITEM_FORMACION_NO_ENCONTRADO_A_KO"
    ]
}

~~~

### List

Permite obtener los datos de todos los items de formación existentes.

#### Request

##### Requisitos de autenticación

No se necesita autenticación para esta acción.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=education&action=list

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos

No se requiere enviar campos.

#### Response

##### Códigos:

| Tipo   | Código                     | Descripción                                           |
|--------|----------------------------|-------------------------------------------------------|
| Éxito  | `RECORDSET_DATA`           | Datos recuperados correctamente.                      |
| Éxito  | `RECORDSET_EMPTY`          | No aplica recuperar datos. No hay items de formación. |
| Error  | `INTERNAL_SERVER_ERROR_KO` | Error interno del servidor (o de Base de datos).      |

##### Ejemplo de respuesta exitosa (status 200):

Se lista todos los items de formación existentes.

~~~

{
    "ok": true,
    "code": [
        "RECORDSET_DATA"
    ],
    "resource": [
        {
            "id": "2",
            "type": "MASTER",
            "title": "Master en ....",
            "description": "Descripción del master",
            "referenceURL": "http://master.pseduca.com",
            "initYear": "2010",
            "endYear": "2030",
            "imageURL": "http://localhost:80/PsEduca-Backend/uploads/67537fc3bb5210.56276578.png"
        },
        {
            "id": "3",
            "type": "MASTER",
            "title": "Master en ....",
            "description": "Descripción del master",
            "referenceURL": "http://master.pseduca.com",
            "initYear": "2010",
            "endYear": "2030",
            "imageURL": "http://localhost:80/PsEduca-Backend/static/education_no_photo.jpg"
        }
    ]
}

~~~

##### Ejemplo de respuesta exitosa (status 200):

No hay items de formación en la base de datos.

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

