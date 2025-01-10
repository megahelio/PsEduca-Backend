# EducationController - Apartado Divulgación

API destinada a la gestión CRUD de items de divulgación de la plataforma PsEduca.

## Validaciones de permisos

En esta API se aplican validaciones de permisos a las acciones ADD, EDIT, DELETE, para garantizar que solo los usuarios con los
permisos adecuados puedan realizarlas. Los posibles mensajes de error en esta API debido a validaciones de permisos se
encuentran en la carpeta `validation`.

<span style="color: red;">OJO</span>: Estos errores no se vuelven a mostrar en cada acción de forma individual para
evitar enguarrar el documento. Las APIs podrán devolver errores de autenticación siempre que requieran autenticación.


## Validaciones de formato

En esta API se aplican validaciones de formato a todos los elementos enviados en las peticiones (salvo el formato de "fichero"),
así como validaciones de acción para garantizar la integridad de los datos. Los posibles mensajes de error en esta API 
debido a validaciones de formato se encuentran en la siguiente tabla:

<span style="color: red;">OJO</span>: Estos errores no se vuelven a mostrar en cada acción de forma individual para 
evitar enguarrar el documento. Las APIs podrán devolver errores de formato siempre que se envíe un elemento que no 
cumple el formato o no se envíe y sea obligatorio.

| Código de error                                                   | Entidad     | Atributo       | Acción            | Prueba                                                                                                                               | Valor erróneo                                                | 
|-------------------------------------------------------------------|-------------|----------------|-------------------|--------------------------------------------------------------------------------------------------------------------------------------|--------------------------------------------------------------|
| `ID_MINIMO_F_KO`                                                  | Divulgación | id             | GET, EDIT, DELETE | Validar que sea un número >= 0                                                                                                       | null, "abc"                                                  |
| `ID_INVALIDO_F_KO`                                                | Divulgación | id             | GET, EDIT, DELETE | Validar que es número positivo                                                                                                       | null, "abc", "-10"                                           |
| `TITULO_MINIMO_F_KO`                                              | Divulgación | titulo         | ADD, EDIT         | Validar longitud mínima (mínimo 4 caracteres)                                                                                        | null, "abc"                                                  |
| `TITULO_MAXIMO_F_KO`                                              | Divulgación | titulo         | ADD, EDIT         | Validar longitud máxima (máximo 254 caracteres)                                                                                      | [cadena de 255 caracteres]                                   |
| `TITULO_CARACTERES_F_KO`                                          | Divulgación | titulo         | ADD, EDIT         | Validar caracteres (solo a-z, ñ, A-Z, Ñ, -, _, 0-9, áéíóú, ÁÉÍÓÚ, espacio, ª, º, ., ',', ', ")                                       | "maria@lopez"                                                |
| `DESCRIPCION_MINIMO_F_KO`                                         | Divulgación | descripcion    | ADD, EDIT         | Validar longitud mínima (mínimo 4 caracteres). **Si admite nulos**                                                                   | "abc"                                                        |
| `DESCRIPCION_MAXIMO_F_KO`                                         | Divulgación | descripcion    | ADD, EDIT         | Validar longitud máxima (máximo 1000 caracteres)                                                                                     | [cadena de 1000 caracteres]                                  |
| `DESCRIPCION_CARACTERES_F_KO`                                     | Divulgación | descripcion    | ADD, EDIT         | Validar caracteres (permitir cualquier carácter excepto \a (bell))                                                                   | [carácter no imprimible]                                     |
| `LINK_MINIMO_F_KO`                                                | Divulgación | link_externo   | ADD, EDIT         | Validar longitud mínima (mínimo 4 caracteres). **Admite nulos si tipo != LINK_EXTERNO**                                              | "abc"                                                        |
| `LINK_MAXIMO_F_KO`                                                | Divulgación | link_externo   | ADD, EDIT         | Validar longitud máxima (máximo 254 caracteres)                                                                                      | [cadena de 255 caracteres]                                   |
| `LINK_INVALIDO_F_KO`                                              | Divulgación | link_externo   | ADD, EDIT         | Validar formato de URI (debe cumplir el estándar RFC 2396)                                                                           | "mipagina.com"                                               |
| `IMAGEN_FORMATO_F_KO`                                             | Divulgación | imagen         | ADD, EDIT         | Validar que el formato de imagen esté entre los siguientes: jpeg, jpg, png, gif, svg, webp, avif, ico. **La imagen puede ser nula.** | "imagen.mp4"                                                 |
| `IMAGEN_TAMAÑO_F_KO`                                              | Divulgación | imagen         | ADD, EDIT         | Validar que el tamaño del fichero sea menor a 10MiB. **La imagen puede ser nula.**                                                   | [Fichero de 10,1 mb]                                         |
| `PAGINA_DETALLE_MINIMO_F_KO`                                      | Divulgación | pagina_detalle | ADD, EDIT         | Validar longitud mínima (mínimo 4 caracteres). **Admite nulos si tipo != PAGINA_INTERNA**                                            | "abc"                                                        |
| `PAGINA_DETALLE_MAXIMO_F_KO`                                      | Divulgación | pagina_detalle | ADD, EDIT         | Validar longitud máxima (máximo 65534 caracteres)                                                                                    | [cadena de 65535 caracteres]                                 |
| `FICHERO_OBLIGATORIO_F_KO`                                        | Divulgación | fichero        | ADD, EDIT         | Validar que el fichero no sea nulo SOLO si tipo = "FICHERO_INTERNO". **Admite nulos si tipo != FICHERO_INTERNO**                     | null                                                         |
| `FICHERO_TAMAÑO_F_KO`                                             | Divulgación | fichero        | ADD, EDIT         | Validar que el tamaño del fichero sea menor a 1GiB.                                                                                  | [Fichero de 1,1 GiB]                                         |
| `TIPO_F_KO`                                                       | Divulgación | tipo           | ADD, EDIT         | Validar que el tipo sea válido (PAGINA_INTERNA, FICHERO_INTERNO, LINK_EXTERNO)                                                       | "PAGINA_INTERNO"                                             |
| `CAMPO_LINK_EXTERNO_INCOMPATIBLE_CON_TIPO_FICHERO_INTERNO_F_KO`   | Divulgación | link_externo   | ADD, EDIT         | Validar que el campo link_externo sea nulo si el tipo es FICHERO_INTERNO.                                                            | "https://www.google.com"                                     |
| `CAMPO_PAGINA_DETALLE_INCOMPATIBLE_CON_TIPO_LINK_EXTERNO_F_KO`    | Divulgación | pagina_detalle | ADD, EDIT         | Validar que el campo pagina_detalle sea nulo si el tipo es LINK_EXTERNO.                                                             | "<html><head><title>HOLA</title></head><body></body></html>" |
| `CAMPO_PAGINA_DETALLE_INCOMPATIBLE_CON_TIPO_FICHERO_INTERNO_F_KO` | Divulgación | pagina_detalle | ADD, EDIT         | Validar que el campo pagina_detalle sea nulo si el tipo es FICHERO_INTERNO.                                                          | "<html><head><title>HOLA</title></head><body></body></html>" |
| `CAMPO_FICHERO_INCOMPATIBLE_CON_TIPO_LINK_EXTERNO_F_KO`           | Divulgación | fichero        | ADD, EDIT         | Validar que el campo fichero_interno sea nulo si el tipo es LINK_EXTERNO.                                                            | "fichero.pdf"                                                |

Nota: ultima_actualización es un campo que se actualiza automáticamente al editar el registro. No es necesaria validación.

Nota: Para más información sobre los errores de formato, consultar la documentación de formato en la carpeta `validation/format`.


## Acciones implementadas

### Add

Permite dar de alta a un nuevo item de divulgación.

#### Request

##### Requisitos de autenticación

Se necesita enviar token de autenticación en la cabecera `Authorization` de la petición, precedido de la palabra 
`Bearer`. Aplica validación de permisos. Ver tabla de validaciones de permisos para información sobre los códigos de
error relacionados.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=outreach&action=add

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos
Se requiere enviar los siguientes campos:

- `title` (string, obligatorio): Título del item de divulgación.
- `type` (string, obligatorio): Tipo del item de divulgación (LINK_EXTERNO, PAGINA_INTERNA, FICHERO_INTERNO).
- `description` (string, obligatorio): Descripción del item de divulgación.
- `image` (file, voluntario): Imagen del item de divulgación. Si no se indica, se asigna una por defecto. Ojo, por problemas de
    compatibilidad con form-data, una vez se asigne una imagen, no se podrá eliminar, solo se podra cambiar.
- `externalURL` (string, voluntario salvo type = "LINK_EXTERNO"): Enlace externo a información adicional del item de divulgación.
- `pageContent` (string, voluntario salvo type = "PAGINA_INTERNA"): Contentido de la página de detalle del item de divulgación.
- `file` (file, voluntario salvo type = "FICHERO_INTERNO"): Fichero asociado al item de divulgación. Ojo, por problemas de
  compatibilidad con form-data, una vez se asigne uno, no se podrá eliminar, solo se podra cambiar. Para eliminarlo, se
  deberá eliminar el item y crear uno nuevo.

#### Response

##### Códigos:

A estos códigos, hay que sumar los de formato y los de permisos (si procede).

| Tipo   | Código                          | Descripción                                                               |
|--------|---------------------------------|---------------------------------------------------------------------------|
| Éxito  | `RECORDSET_DATA`                | Datos recuperados correctamente.                                          |
| Error  | `INTERNAL_SERVER_ERROR_KO`      | Error interno del servidor (o de Base de datos).                          |

##### Ejemplo de respuesta exitosa (status 201):

Se ha añadido correctamente el item de divulgación. Esta respueta coincide (salvo status) con las de **edit** y **get**.

~~~

{
    "ok": true,
    "code": [
        "RECORDSET_DATA"
    ],
    "resource": {
        "id": "5",
        "type": "PAGINA_INTERNA",
        "title": "Item divulgación 1",
        "description": "Los detalles convenientes sobre el item 1.",
        "imageURL": "/static/outreach_no_photo.jpg",
        "lastModified": "2024-12-31 01:05:54",
        "externalURL": "https://europa.eu",
        "pageContent": "<html><head><title>Item divulgación 1</title></head><body></body></html>",
        "fileURL": "/uploads/677335628e04d9.64011331.pdf"
    }
}

~~~

##### Ejemplo de respuesta con error (status 400):

En esta respuesta se han enviado datos que no cumplen con el formato establecido.

~~~

{
    "ok": false,
    "code": [
        "LINK_INVALIDO_F_KO",
        "PAGINA_DETALLE_MINIMO_F_KO"
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

Permite editar los datos de un item de divulgación ya existente.

#### Request

##### Requisitos de autenticación

Se necesita enviar token de autenticación en la cabecera `Authorization` de la petición, precedido de la palabra
`Bearer`. Aplica validación de permisos. Ver tabla de validaciones de permisos para información sobre los códigos de
error relacionados.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=outreach&action=edit

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos
Se requiere enviar los siguientes campos:

- `id` (int, obligatorio): Identificador del item de divulgación.

Además, se pueden enviar los siguientes campos, tanto si se desean cambiar, como si se envían iguales y no se realizan
cambios:

- `title` (string, voluntario): Título del item de divulgación.
- `type` (string, voluntario): Tipo del item de divulgación (LINK_EXTERNO, PAGINA_INTERNA, FICHERO_INTERNO).
- `description` (string, voluntario): Descripción del item de divulgación.
- `image` (file, voluntario): Imagen del item de divulgación. Si no se indica, se asigna una por defecto. Ojo, por problemas de
  compatibilidad con form-data, una vez se asigne una imagen, no se podrá eliminar, solo se podra cambiar.
- `externalURL` (string, voluntario salvo que sea nulo y type = "LINK_EXTERNO"): Enlace externo a información adicional del item de divulgación.
- `pageContent` (string, voluntario salvo que sea nulo y type = "PAGINA_INTERNA"): Contentido de la página de detalle del item de divulgación.
- `file` (file, voluntario salvo que sea nulo y type = "FICHERO_INTERNO"): Fichero asociado al item de divulgación. Ojo, por problemas de
  compatibilidad con form-data, una vez se asigne uno, no se podrá eliminar, solo se podra cambiar. Para eliminarlo, se
  deberá eliminar el item y crear uno nuevo.

#### Response

##### Códigos:

A estos códigos, hay que sumar los de formato y los de permisos (si procede).

| Tipo   | Código                                | Descripción                                            |
|--------|---------------------------------------|--------------------------------------------------------|
| Éxito  | `RECORDSET_DATA`                      | Datos recuperados correctamente.                       |
| Error  | `ITEM_DIVULGACION_NO_ENCONTRADO_A_KO` | El item de divulgación a editar no ha sido encontrado. |
| Error  | `INTERNAL_SERVER_ERROR_KO`            | Error interno del servidor (o de Base de datos).       |

##### Ejemplo de respuesta exitosa (status 200):

Se ha editado correctamente el item de divulgación. Esta respueta coincide con la de **add** (salvo status) y **get**.

~~~

{
    "ok": true,
    "code": [
        "RECORDSET_DATA"
    ],
    "resource": {
        "id": "3",
        "type": "PAGINA_INTERNA",
        "title": "Item divulgación 1.1",
        "description": "Los detalles convenientes sobre el item 1.",
        "imageURL": "/static/outreach_no_photo.jpg",
        "lastModified": "2024-12-31 17:03:06",
        "externalURL": "https://europa.eu",
        "pageContent": "<html><head><title>Item divulgación 1</title></head><body></body></html>",
        "fileURL": "/uploads/677334aee85500.63292821.pdf"
    }
}

~~~

##### Ejemplo de respuesta con error (status 400):

En esta respuesta se han enviado datos que no cumplen con el formato establecido.

~~~

{
    "ok": false,
    "code": [
        "CAMPO_LINK_EXTERNO_INCOMPATIBLE_CON_TIPO_FICHERO_INTERNO_F_KO",
        "CAMPO_PAGINA_DETALLE_INCOMPATIBLE_CON_TIPO_FICHERO_INTERNO_F_KO"
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

Error provocado por no existir un item de divulgación con el número de item de divulgación enviado. Los identificadores no se pueden repetir.

~~~

{
    "ok": false,
    "code": [
        "ITEM_DIVULGACION_NO_ENCONTRADO_A_KO"
    ]
}

~~~

### Delete

Permite eliminar los datos de un item de divulgación ya existente.

#### Request

##### Requisitos de autenticación

Se necesita enviar token de autenticación en la cabecera `Authorization` de la petición, precedido de la palabra
`Bearer`. Aplica validación de permisos. Ver tabla de validaciones de permisos para información sobre los códigos de
error relacionados.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=outreach&action=delete

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos
Se requiere enviar los siguientes campos:

- `id` (int, obligatorio): Identificador del item de divulgación.

#### Response

##### Códigos:

A estos errores, hay que sumar los de formato y los de permisos (si procede).

| Tipo   | Código                                | Descripción                                              |
|--------|---------------------------------------|----------------------------------------------------------|
| Éxito  | `RECORDSET_EMPTY`                     | No aplica recuperar datos.                               |
| Error  | `ITEM_DIVULGACION_NO_ENCONTRADO_A_KO` | El item de divulgación a eliminar no ha sido encontrado. |
| Error  | `INTERNAL_SERVER_ERROR_KO`            | Error interno del servidor (o de Base de datos).         |

##### Ejemplo de respuesta exitosa (status 200):

Se ha eliminado correctamente el item de divulgación. No se envía recurso, ya que se ha eliminado. El status más correcto sería 204,
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

Error provocado por no existir un item de divulgación con el id enviado.

~~~

{
    "ok": false,
    "code": [
        "ITEM_DIVULGACION_NO_ENCONTRADO_A_KO"
    ]
}

~~~

### Get

Permite obtener los datos de un item de divulgación mediante su identificador (id).

#### Request

##### Requisitos de autenticación

No se necesita autenticación para esta acción.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=outreach&action=get

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos
Se requiere enviar los siguientes campos:

- `id` (int, obligatorio): Identificador del item de divulgación.

#### Response

##### Códigos:

A estos errores, hay que sumar los de formato y los de permisos (si procede).

| Tipo   | Código                                | Descripción                                               |
|--------|---------------------------------------|-----------------------------------------------------------|
| Éxito  | `RECORDSET_DATA`                      | Datos recuperados correctamente.                          |
| Error  | `ITEM_DIVULGACION_NO_ENCONTRADO_A_KO` | El item de divulgación a recuperar no ha sido encontrado. |
| Error  | `INTERNAL_SERVER_ERROR_KO`            | Error interno del servidor (o de Base de datos).          |

##### Ejemplo de respuesta exitosa (status 200):

Se ha encontrado el item de divulgación. Esta respueta coincide con la de **add** y **edit** (salvo status).

~~~

{
    "ok": true,
    "code": [
        "RECORDSET_DATA"
    ],
    "resource": {
        "id": "3",
        "type": "PAGINA_INTERNA",
        "title": "Item divulgación 1.1",
        "description": "Los detalles convenientes sobre el item 1.",
        "imageURL": "/static/outreach_no_photo.jpg",
        "lastModified": "2024-12-31 17:03:52",
        "externalURL": "https://europa.eu",
        "pageContent": "<html><head><title>Item divulgación 1</title></head><body></body></html>",
        "fileURL": "/uploads/677334aee85500.63292821.pdf"
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

Error provocado por no existir un item de divulgación con el id enviado.

~~~

{
    "ok": false,
    "code": [
        "ITEM_DIVULGACION_NO_ENCONTRADO_A_KO"
    ]
}

~~~

### List

Permite obtener los datos de todos los items de divulgación existentes.

#### Request

##### Requisitos de autenticación

No se necesita autenticación para esta acción.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=outreach&action=list

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos

No se requiere enviar campos.

#### Response

##### Códigos:

| Tipo   | Código                     | Descripción                                             |
|--------|----------------------------|---------------------------------------------------------|
| Éxito  | `RECORDSET_DATA`           | Datos recuperados correctamente.                        |
| Éxito  | `RECORDSET_EMPTY`          | No aplica recuperar datos. No hay items de divulgación. |
| Error  | `INTERNAL_SERVER_ERROR_KO` | Error interno del servidor (o de Base de datos).        |

##### Ejemplo de respuesta exitosa (status 200):

Se lista todos los items de divulgación existentes.

~~~

{
    "ok": true,
    "code": [
        "RECORDSET_DATA"
    ],
    "resource": [
        {
            "id": "1",
            "type": "PAGINA_INTERNA",
            "title": "Item divulgación 1",
            "description": "Los detalles convenientes sobre el item 1.",
            "imageURL": "/static/outreach_no_photo.jpg",
            "lastModified": "2024-12-31 00:58:52",
            "externalURL": "http://europa.eu",
            "pageContent": "<html><head><title>Item divulgación 1</title></head><body></body></html>",
            "fileURL": "/uploads/677333bcd5ade8.99826428.pdf"
        },
        {
            "id": "3",
            "type": "PAGINA_INTERNA",
            "title": "Item divulgación 1.1",
            "description": "Los detalles convenientes sobre el item 1.",
            "imageURL": "/static/outreach_no_photo.jpg",
            "lastModified": "2024-12-31 17:03:52",
            "externalURL": "https://europa.eu",
            "pageContent": "<html><head><title>Item divulgación 1</title></head><body></body></html>",
            "fileURL": "/uploads/677334aee85500.63292821.pdf"
        },
        {
            "id": "4",
            "type": "PAGINA_INTERNA",
            "title": "Item divulgación 1",
            "description": "Los detalles convenientes sobre el item 1.",
            "imageURL": "/static/outreach_no_photo.jpg",
            "lastModified": "2024-12-31 01:05:08",
            "externalURL": "http://europa.eu",
            "pageContent": "<html><head><title>Item divulgación 1</title></head><body></body></html>",
            "fileURL": "/uploads/677335348640b6.24955906.pdf"
        }
    ]
}

~~~

##### Ejemplo de respuesta exitosa (status 200):

No hay items de divulgación en la base de datos.

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

