# EducationController - Apartado catálogo

API destinada a la gestión CRUD de items de catálogo de la plataforma PsEduca.

## Validaciones de permisos

En esta API se aplican validaciones de permisos a las acciones ADD, EDIT, DELETE, para garantizar que solo los usuarios con los
permisos adecuados (ADMIN_GLOBAL o GESTOR_CATALOGO) puedan realizarlas. Los posibles mensajes de error en esta API debido
a validaciones de permisos se encuentran en la carpeta `validation`.

<span style="color: red;">OJO</span>: Estos errores no se vuelven a mostrar en cada acción de forma individual para
evitar enguarrar el documento. Las APIs podrán devolver errores de autenticación siempre que requieran autenticación.


## Validaciones de formato

En esta API se aplican validaciones de formato a todos los elementos enviados en las peticiones (salvo el formato de "fichero"),
así como validaciones de acción para garantizar la integridad de los datos. Los posibles mensajes de error en esta API 
debido a validaciones de formato se encuentran en la siguiente tabla:

<span style="color: red;">OJO</span>: Estos errores no se vuelven a mostrar en cada acción de forma individual para 
evitar enguarrar el documento. Las APIs podrán devolver errores de formato siempre que se envíe un elemento que no 
cumple el formato o no se envíe y sea obligatorio.

| Código de error                 | Entidad | Atributo      | Acción                                                      | Prueba                                                                                                                               | Valor erróneo               | 
|---------------------------------|---------|---------------|-------------------------------------------------------------|--------------------------------------------------------------------------------------------------------------------------------------|-----------------------------|
| `ID_MINIMO_F_KO`                | Catálogo   | id            | GET, EDIT, DELETE, ADDFILE, DELETEFILE, ADDLINK, DELETELINK | Validar que sea un número >= 0                                                                                                       | null, "abc"                 |
| `ID_INVALIDO_F_KO`              | Catálogo   | id            | GET, EDIT, DELETE, ADDFILE, DELETEFILE, ADDLINK, DELETELINK | Validar que es número positivo                                                                                                       | null, "abc", "-10"          |
| `ACRONIMO_MINIMO_F_KO`          | Catálogo   | acronimo      | ADD, EDIT                                                   | Validar longitud mínima (mínimo 4 caracteres)                                                                                        | null, "abc"                 |
| `ACRONIMO_MAXIMO_F_KO`          | Catálogo   | acronimo      | ADD, EDIT                                                   | Validar longitud máxima (máximo 254 caracteres)                                                                                      | [cadena de 255 caracteres]  |
| `ACRONIMO_CARACTERES_F_KO`      | Catálogo   | acronimo      | ADD, EDIT                                                   | Validar caracteres (solo a-z, ñ, A-Z, Ñ, -, _, 0-9, áéíóú, ÁÉÍÓÚ, espacio, ª, º, ., ',', ', ")                                       | "ESEI"                      |
| `NOMBRE_MINIMO_F_KO`            | Catálogo   | name          | ADD, EDIT                                                   | Validar longitud mínima (mínimo 4 caracteres).                                                                                       | "abc"                       |
| `NOMBRE_MAXIMO_F_KO`            | Catálogo   | name          | ADD, EDIT                                                   | Validar longitud máxima (máximo 1000 caracteres)                                                                                     | [cadena de 1000 caracteres] |
| `NOMBRE_CARACTERES_F_KO`        | Catálogo   | name          | ADD, EDIT                                                   | Validar caracteres (permitir cualquier carácter excepto \a (bell))                                                                   | [carácter no imprimible]    |
| `EDAD_MES_MIN_MINIMO_F_KO`      | Catálogo   | edad_mes_min  | ADD, EDIT                                                   | Validar que el número sea >= 0. **Si admite nulos**                                                                                  | "-1"                        |
| `EDAD_MES_MIN_MAXIMO_F_KO`      | Catálogo   | edad_mes_min  | ADD, EDIT                                                   | Validar que el número sea <= 150                                                                                                     | "999"                       |
| `EDAD_MES_MIN_CARACTERES_F_KO`  | Catálogo   | edad_mes_min  | ADD, EDIT                                                   | Validar que sea un número entero.                                                                                                    | "cero"                      |
| `EDAD_AÑO_MIN_MINIMO_F_KO`      | Catálogo   | edad_año_min  | ADD, EDIT                                                   | Validar que el número sea >= 0. **Si admite nulos**                                                                                  | "-1"                        |
| `EDAD_AÑO_MIN_MAXIMO_F_KO`      | Catálogo   | edad_año_min  | ADD, EDIT                                                   | Validar que el número sea <= 150                                                                                                     | "999"                       |
| `EDAD_AÑO_MIN_CARACTERES_F_KO`  | Catálogo   | edad_año_min  | ADD, EDIT                                                   | Validar que sea un número entero.                                                                                                    | "cero"                      |
| `EDAD_MES_MAX_MINIMO_F_KO`      | Catálogo   | edad_mes_max  | ADD, EDIT                                                   | Validar que el número sea >= 0. **Si admite nulos**                                                                                  | "-1"                        |
| `EDAD_MES_MAX_MAXIMO_F_KO`      | Catálogo   | edad_mes_max  | ADD, EDIT                                                   | Validar que el número sea <= 150                                                                                                     | "999"                       |
| `EDAD_MES_MAX_CARACTERES_F_KO`  | Catálogo   | edad_mes_max  | ADD, EDIT                                                   | Validar que sea un número entero.                                                                                                    | "cero"                      |
| `EDAD_AÑO_MAX_MINIMO_F_KO`      | Catálogo   | edad_año_max  | ADD, EDIT                                                   | Validar que el número sea >= 0. **Si admite nulos**                                                                                  | "-1"                        |
| `EDAD_AÑO_MAX_MAXIMO_F_KO`      | Catálogo   | edad_año_max  | ADD, EDIT                                                   | Validar que el número sea <= 150                                                                                                     | "999"                       |
| `EDAD_AÑO_MAX_CARACTERES_F_KO`  | Catálogo   | edad_año_max  | ADD, EDIT                                                   | Validar que sea un número entero.                                                                                                    | "cero"                      |
| `AUTORES_MINIMO_F_KO`           | Catálogo   | autores       | ADD, EDIT                                                   | Validar longitud mínima (mínimo 4 caracteres). **Si admite nulos**                                                                   | "abc"                       |
| `AUTORES_MAXIMO_F_KO`           | Catálogo   | autores       | ADD, EDIT                                                   | Validar longitud máxima (máximo 1000 caracteres)                                                                                     | [cadena de 1000 caracteres] |
| `AUTORES_CARACTERES_F_KO`       | Catálogo   | autores       | ADD, EDIT                                                   | Validar caracteres (permitir cualquier carácter excepto \a (bell))                                                                   | [carácter no imprimible]    |
| `TIEMPO_MINIMO_F_KO`            | Catálogo   | tiempo        | ADD, EDIT                                                   | Validar que el número sea >= 0.                                                                                                      | "-1"                        |
| `TIEMPO_MAXIMO_F_KO`            | Catálogo   | tiempo        | ADD, EDIT                                                   | Validar que el número sea <= 150                                                                                                     | "999"                       |
| `TIEMPO_CARACTERES_F_KO`        | Catálogo   | tiempo        | ADD, EDIT                                                   | Validar que sea un número entero.                                                                                                    | "cero"                      |
| `DESCRIPCION_MINIMO_F_KO`       | Catálogo   | descripcion   | ADD, EDIT                                                   | Validar longitud mínima (mínimo 4 caracteres). **Si admite nulos**                                                                   | "abc"                       |
| `DESCRIPCION_MAXIMO_F_KO`       | Catálogo   | descripcion   | ADD, EDIT                                                   | Validar longitud máxima (máximo 1000 caracteres)                                                                                     | [cadena de 1000 caracteres] |
| `DESCRIPCION_CARACTERES_F_KO`   | Catálogo   | descripcion   | ADD, EDIT                                                   | Validar caracteres (permitir cualquier carácter excepto \a (bell))                                                                   | [carácter no imprimible]    |
| `OBSERVACIONES_MINIMO_F_KO`     | Catálogo   | observaciones | ADD, EDIT                                                   | Validar longitud mínima (mínimo 4 caracteres). **Si admite nulos**                                                                   | "abc"                       |
| `OBSERVACIONES_MAXIMO_F_KO`     | Catálogo   | observaciones | ADD, EDIT                                                   | Validar longitud máxima (máximo 1000 caracteres)                                                                                     | [cadena de 1000 caracteres] |
| `OBSERVACIONES_CARACTERES_F_KO` | Catálogo   | observaciones | ADD, EDIT                                                   | Validar caracteres (permitir cualquier carácter excepto \a (bell))                                                                   | [carácter no imprimible]    |
| `IMAGEN_FORMATO_F_KO`           | Catálogo   | imagen        | ADD, EDIT                                                   | Validar que el formato de imagen esté entre los siguientes: jpeg, jpg, png, gif, svg, webp, avif, ico. **La imagen puede ser nula.** | "imagen.mp4"                |
| `IMAGEN_TAMAÑO_F_KO`            | Catálogo   | imagen        | ADD, EDIT                                                   | Validar que el tamaño del fichero sea menor a 10MiB. **La imagen puede ser nula.**                                                   | [Fichero de 10,1 mb]        |
| `AREA_MINIMO_F_KO`              | Catálogo   | areas         | ADD, EDIT                                                   | Validar longitud mínima (mínimo 4 caracteres) **Si admite nulos**                                                                    | null, "abc"                 |
| `AREA_MAXIMO_F_KO`              | Catálogo   | areas         | ADD, EDIT                                                   | Validar longitud máxima (máximo 254 caracteres)                                                                                      | [cadena de 255 caracteres]  |
| `AREA_CARACTERES_F_KO`          | Catálogo   | areas         | ADD, EDIT                                                   | Validar caracteres (solo a-z, ñ, A-Z, Ñ, -, _, 0-9, áéíóú, ÁÉÍÓÚ, espacio, ª, º, ., ',', ', ")                                       | "´APRENDIZAJE`"             |
| `ETIQUETA_MINIMO_F_KO`          | Catálogo   | etiquetas     | ADD, EDIT                                                   | Validar longitud mínima (mínimo 4 caracteres) **Si admite nulos**                                                                    | null, "abc"                 |
| `ETIQUETA_MAXIMO_F_KO`          | Catálogo   | etiquetas     | ADD, EDIT                                                   | Validar longitud máxima (máximo 254 caracteres)                                                                                      | [cadena de 255 caracteres]  |
| `ETIQUETA_CARACTERES_F_KO`      | Catálogo   | etiquetas     | ADD, EDIT                                                   | Validar caracteres (solo a-z, ñ, A-Z, Ñ, -, _, 0-9, áéíóú, ÁÉÍÓÚ, espacio, ª, º, ., ',', ', ")                                       | "´ETIQUETA`"                |
| `TIPO_RECURSO_MINIMO_F_KO`      | Catálogo   | tipos_recurso | ADD, EDIT                                                   | Validar longitud mínima (mínimo 4 caracteres) **Si admite nulos**                                                                    | null, "abc"                 |
| `TIPO_RECURSO_MAXIMO_F_KO`      | Catálogo   | tipos_recurso | ADD, EDIT                                                   | Validar longitud máxima (máximo 254 caracteres)                                                                                      | [cadena de 255 caracteres]  |
| `TIPO_RECURSO_CARACTERES_F_KO`  | Catálogo   | tipos_recurso | ADD, EDIT                                                   | Validar caracteres (solo a-z, ñ, A-Z, Ñ, -, _, 0-9, áéíóú, ÁÉÍÓÚ, espacio, ª, º, ., ',', ', ")                                       | "´INTERVENCION`"            |
| `FORMATO_MINIMO_F_KO`           | Catálogo   | formato       | ADD, EDIT                                                   | Validar longitud mínima (mínimo 4 caracteres) **Si admite nulos**                                                                    | null, "abc"                 |
| `FORMATO_MAXIMO_F_KO`           | Catálogo   | formato       | ADD, EDIT                                                   | Validar longitud máxima (máximo 254 caracteres)                                                                                      | [cadena de 255 caracteres]  |
| `FORMATO_CARACTERES_F_KO`       | Catálogo   | formato       | ADD, EDIT                                                   | Validar caracteres (solo a-z, ñ, A-Z, Ñ, -, _, 0-9, áéíóú, ÁÉÍÓÚ, espacio, ª, º, ., ',', ', ")                                       | "´COLECTIVO`"               |
| `APLICACION_MINIMO_F_KO`        | Catálogo   | aplicacion    | ADD, EDIT                                                   | Validar longitud mínima (mínimo 4 caracteres) **Si admite nulos**                                                                    | null, "abc"                 |
| `APLICACION_MAXIMO_F_KO`        | Catálogo   | aplicacion    | ADD, EDIT                                                   | Validar longitud máxima (máximo 254 caracteres)                                                                                      | [cadena de 255 caracteres]  |
| `APLICACION_CARACTERES_F_KO`    | Catálogo   | aplicacion    | ADD, EDIT                                                   | Validar caracteres (solo a-z, ñ, A-Z, Ñ, -, _, 0-9, áéíóú, ÁÉÍÓÚ, espacio, ª, º, ., ',', ', ")                                       | "´PAPEL`"                   |
| `ID_FICHERO_MINIMO_F_KO`        | Catálogo   | id_fichero    | DELETEFILE                                                  | Validar que sea un número >= 0                                                                                                       | null, "abc"                 |
| `ID_FICHERO_INVALIDO_F_KO`      | Catálogo   | id_fichero    | DELETEFILE                                                  | Validar que es número positivo                                                                                                       | null, "abc", "-10"          |
| `ID_ENLACE_MINIMO_F_KO`         | Catálogo   | id_enlace     | DELETELINK                                                  | Validar que sea un número >= 0                                                                                                       | null, "abc"                 |
| `ID_ENLACE_INVALIDO_F_KO`       | Catálogo   | id_enlace     | DELETELINK                                                  | Validar que es número positivo                                                                                                       | null, "abc", "-10"          |
| `FICHERO_FORMATO_F_KO`          | Catálogo   | ficheros      | ADDFILE                                                     | Validar que el formato de imagen esté entre los siguientes: jpeg, jpg, png, gif, svg, webp, avif, ico. **La imagen puede ser nula.** | "imagen.mp4"                |
| `FICHERO_TAMAÑO_F_KO`           | Catálogo   | ficheros      | ADDFILE                                                     | Validar que el tamaño del fichero sea menor a 1GiB. **Puede no haber ficheros.**                                                     | [Fichero de 1,1 gib]        |
| `LINK_MINIMO_F_KO`              | Catálogo   | link_externo  | ADDLINK                                                     | Validar longitud mínima (mínimo 4 caracteres).                                                                                       | "abc"                       |
| `LINK_MAXIMO_F_KO`              | Catálogo   | link_externo  | ADDLINK                                                     | Validar longitud máxima (máximo 254 caracteres)                                                                                      | [cadena de 255 caracteres]  |
| `LINK_INVALIDO_F_KO`            | Catálogo   | link_externo  | ADDLINK                                                     | Validar formato de URI (debe cumplir el estándar RFC 2396)                                                                           | "mipagina.com"              |

[//]: # (La siguiente restricción no aplica, creo que no es necesaria, ya que el nombre en sistema de archvo se genera aleatoriamente.)
[//]: # (| IMAGEN_NOMBRE_MAXIMO_F_KO               | Catálogo | imagen                    | ADD, EDIT          | Validar que el nombre del fichero sea menor a 1000 caracteres. **La imagen puede ser nula.**                                          | [cadena de 997 caracteres].jpg |)
[//]: # (| FICHERO_NOMBRE_MAXIMO_F_KO              | Catálogo | fichero                   | ADD, EDIT          | Validar que el nombre del fichero sea menor a 1000 caracteres. **El fichero puede ser nulo.**                                         | [cadena de 997 caracteres].pdf |)

Nota: Para más información sobre los errores de formato, consultar la documentación de formato en la carpeta `validation/format`.


## Acciones implementadas

### Add

Permite dar de alta a un nuevo item de catálogo.

#### Request

##### Requisitos de autenticación

Se necesita enviar token de autenticación en la cabecera `Authorization` de la petición, precedido de la palabra 
`Bearer`. Aplica validación de permisos. Ver tabla de validaciones de permisos para información sobre los códigos de
error relacionados.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=catalogue&action=add

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos
Se requiere enviar los siguientes campos:

- `acronym` (string, obligatorio): Acrónimo del item de catálogo.
- `name` (string, obligatorio): Nombre del item de catálogo.
- `yearMinAge` (string, voluntario): Edad mínima en años del item de catálogo.
- `monthMinAge` (string, voluntario): Edad mínima en meses del item de catálogo.
- `yearMaxAge` (string, voluntario): Edad máxima en años del item de catálogo.
- `monthMaxAge` (string, voluntario): Edad máxima en meses del item de catálogo.
- `authors` (string, voluntario): Autores del item de catálogo.
- `time` (string, obligatorio): Tiempo asociado al item de catálogo.
- `description` (string, obligatorio): Descripción del item de catálogo.
- `note` (string, voluntario): Nota adicional del item de catálogo.
- `image` (file, voluntario): Imagen del item de catálogo. Si no se indica, se asigna una por defecto. Ojo, por problemas de compatibilidad con form-data, una vez se asigne una imagen, no se podrá eliminar, solo se podrá cambiar.
- `areas` (array de strings, voluntario): Áreas asociadas al item de catálogo.
- `tags` (array de strings, voluntario): Etiquetas asociadas al item de catálogo.
- `resourceTypes` (array de strings, voluntario): Tipos de recursos asociados al item de catálogo.
- `formats` (array de strings, voluntario): Formatos asociados al item de catálogo.
- `applicationModes` (array de strings, voluntario): Modos de aplicación asociados al item de catálogo.

#### Response

##### Códigos:

A estos códigos, hay que sumar los de formato y los de permisos (si procede).

| Tipo   | Código                          | Descripción                                                               |
|--------|---------------------------------|---------------------------------------------------------------------------|
| Éxito  | `RECORDSET_DATA`                | Datos recuperados correctamente.                                          |
| Error  | `INTERNAL_SERVER_ERROR_KO`      | Error interno del servidor (o de Base de datos).                          |

##### Ejemplo de respuesta exitosa (status 201):

Se ha añadido correctamente el item de catálogo. Esta respueta coincide (salvo status) con las de **edit** y **get**.

~~~

<PENDIENTE>

~~~

##### Ejemplo de respuesta con error (status 400):

En esta respuesta se han enviado datos que no cumplen con el formato establecido.

~~~

<PENDIENTE>


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

Permite editar los datos de un item de catálogo ya existente.

#### Request

##### Requisitos de autenticación

Se necesita enviar token de autenticación en la cabecera `Authorization` de la petición, precedido de la palabra
`Bearer`. Aplica validación de permisos. Ver tabla de validaciones de permisos para información sobre los códigos de
error relacionados.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=catalogue&action=edit

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos
Se requiere enviar los siguientes campos:

- `id` (int, obligatorio): Identificador del item de catálogo.

Además, se pueden enviar los siguientes campos, tanto si se desean cambiar, como si se envían iguales y no se realizan
cambios:

- `acronym` (string, obligatorio): Acrónimo del item de catálogo.
- `name` (string, obligatorio): Nombre del item de catálogo.
- `yearMinAge` (string, voluntario): Edad mínima en años del item de catálogo.
- `monthMinAge` (string, voluntario): Edad mínima en meses del item de catálogo.
- `yearMaxAge` (string, voluntario): Edad máxima en años del item de catálogo.
- `monthMaxAge` (string, voluntario): Edad máxima en meses del item de catálogo.
- `authors` (string, voluntario): Autores del item de catálogo.
- `time` (string, obligatorio): Tiempo asociado al item de catálogo.
- `description` (string, obligatorio): Descripción del item de catálogo.
- `note` (string, voluntario): Nota adicional del item de catálogo.
- `image` (file, voluntario): Imagen del item de catálogo. Si no se indica, se asigna una por defecto. Ojo, por problemas de compatibilidad con form-data, una vez se asigne una imagen, no se podrá eliminar, solo se podrá cambiar.
- `areas` (array de strings, voluntario): Áreas asociadas al item de catálogo.
- `tags` (array de strings, voluntario): Etiquetas asociadas al item de catálogo.
- `resourceTypes` (array de strings, voluntario): Tipos de recursos asociados al item de catálogo.
- `formats` (array de strings, voluntario): Formatos asociados al item de catálogo.
- `applicationModes` (array de strings, voluntario): Modos de aplicación asociados al item de catálogo.

#### Response

##### Códigos:

A estos códigos, hay que sumar los de formato y los de permisos (si procede).

| Tipo   | Código                             | Descripción                                         |
|--------|------------------------------------|-----------------------------------------------------|
| Éxito  | `RECORDSET_DATA`                   | Datos recuperados correctamente.                    |
| Error  | `ITEM_CATALOGO_NO_ENCONTRADO_A_KO` | El item de catálogo a editar no ha sido encontrado. |
| Error  | `INTERNAL_SERVER_ERROR_KO`         | Error interno del servidor (o de Base de datos).    |

##### Ejemplo de respuesta exitosa (status 200):

Se ha editado correctamente el item de catálogo. Esta respueta coincide con la de **add** (salvo status) y **get**.

~~~

<PENDIENTE>

~~~

##### Ejemplo de respuesta con error (status 400):

En esta respuesta se han enviado datos que no cumplen con el formato establecido.

~~~

<PENDIENTE>

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

Error provocado por no existir un item de catálogo con el número de item de catálogo enviado. Los identificadores no se pueden repetir.

~~~

<PENDIENTE>

~~~

### Delete

Permite eliminar los datos de un item de catálogo ya existente.

#### Request

##### Requisitos de autenticación

Se necesita enviar token de autenticación en la cabecera `Authorization` de la petición, precedido de la palabra
`Bearer`. Aplica validación de permisos. Ver tabla de validaciones de permisos para información sobre los códigos de
error relacionados.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=catalogue&action=delete

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos
Se requiere enviar los siguientes campos:

- `id` (int, obligatorio): Identificador del item de catálogo.

#### Response

##### Códigos:

A estos errores, hay que sumar los de formato y los de permisos (si procede).

| Tipo   | Código                             | Descripción                                           |
|--------|------------------------------------|-------------------------------------------------------|
| Éxito  | `RECORDSET_EMPTY`                  | No aplica recuperar datos.                            |
| Error  | `ITEM_CATALOGO_NO_ENCONTRADO_A_KO` | El item de catálogo a eliminar no ha sido encontrado. |
| Error  | `INTERNAL_SERVER_ERROR_KO`         | Error interno del servidor (o de Base de datos).      |

##### Ejemplo de respuesta exitosa (status 200):

Se ha eliminado correctamente el item de catálogo. No se envía recurso, ya que se ha eliminado. El status más correcto sería 204,
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

Error provocado por no existir un item de catálogo con el id enviado.

~~~

{
    "ok": false,
    "code": [
        "ITEM_CATALOGO_NO_ENCONTRADO_A_KO"
    ]
}

~~~

### Get

Permite obtener los datos de un item de catálogo mediante su identificador (id).

#### Request

##### Requisitos de autenticación

No se necesita autenticación para esta acción.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=catalogue&action=get

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos
Se requiere enviar los siguientes campos:

- `id` (int, obligatorio): Identificador del item de catálogo.

#### Response

##### Códigos:

A estos errores, hay que sumar los de formato y los de permisos (si procede).

| Tipo   | Código                             | Descripción                                            |
|--------|------------------------------------|--------------------------------------------------------|
| Éxito  | `RECORDSET_DATA`                   | Datos recuperados correctamente.                       |
| Error  | `ITEM_CATALOGO_NO_ENCONTRADO_A_KO` | El item de catálogo a recuperar no ha sido encontrado. |
| Error  | `INTERNAL_SERVER_ERROR_KO`         | Error interno del servidor (o de Base de datos).       |

##### Ejemplo de respuesta exitosa (status 200):

Se ha encontrado el item de catálogo. Esta respueta coincide con la de **add** y **edit** (salvo status).

~~~

<PENDIENTE>

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

Error provocado por no existir un item de catálogo con el id enviado.

~~~

{
    "ok": false,
    "code": [
        "ITEM_CATALOGO_NO_ENCONTRADO_A_KO"
    ]
}

~~~

### List

Permite obtener los datos de todos los items de catálogo existentes.

#### Request

##### Requisitos de autenticación

No se necesita autenticación para esta acción.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=catalogue&action=list

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos

No se requiere enviar campos.

#### Response

##### Códigos:

| Tipo   | Código                     | Descripción                                          |
|--------|----------------------------|------------------------------------------------------|
| Éxito  | `RECORDSET_DATA`           | Datos recuperados correctamente.                     |
| Éxito  | `RECORDSET_EMPTY`          | No aplica recuperar datos. No hay items de catálogo. |
| Error  | `INTERNAL_SERVER_ERROR_KO` | Error interno del servidor (o de Base de datos).     |

##### Ejemplo de respuesta exitosa (status 200):

Se lista todos los items de catálogo existentes.

~~~

<PENDIENTE>

~~~

##### Ejemplo de respuesta exitosa (status 200):

No hay items de catálogo en la base de datos.

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

### Add File

Permite dar de alta a un nuevo fichero asociado a un item de catálogo ya existente.

#### Request

##### Requisitos de autenticación

Se necesita enviar token de autenticación en la cabecera `Authorization` de la petición, precedido de la palabra
`Bearer`. Aplica validación de permisos. Ver tabla de validaciones de permisos para información sobre los códigos de
error relacionados.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=catalogue&action=addFile

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos
Se requiere enviar los siguientes campos:

- `catalogueItemId` (int, obligatorio): Identificador del item de catálogo.
- `name` (string, obligatorio): Nombre del item de catálogo.
- `file` (file, obligatorio): Fichero asociado al item de catálogo.

#### Response

##### Códigos:

A estos códigos, hay que sumar los de formato y los de permisos (si procede).

| Tipo   | Código                             | Descripción                                                     |
|--------|------------------------------------|-----------------------------------------------------------------|
| Éxito  | `RECORDSET_DATA`                   | Fichero asociado correctamente.                                 |
| Error  | `INTERNAL_SERVER_ERROR_KO`         | Error interno del servidor (o de Base de datos).                |
| Error  | `ITEM_CATALOGO_NO_ENCONTRADO_A_KO` | El item de catálogo a asociar el fichero no ha sido encontrado. |

##### Ejemplo de respuesta exitosa (status 201):

Se ha añadido correctamente el fichero al item de catálogo.

~~~

<PENDIENTE>

~~~

##### Ejemplo de respuesta con error (status 400):

En esta respuesta se han enviado datos que no cumplen con el formato establecido.

~~~

<PENDIENTE>


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


### Add Link

Permite dar de alta a un nuevo enlace asociado a un item de catálogo ya existente.

#### Request

##### Requisitos de autenticación

Se necesita enviar token de autenticación en la cabecera `Authorization` de la petición, precedido de la palabra
`Bearer`. Aplica validación de permisos. Ver tabla de validaciones de permisos para información sobre los códigos de
error relacionados.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=catalogue&action=addLink

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos
Se requiere enviar los siguientes campos:

- `catalogueItemId` (int, obligatorio): Identificador del item de catálogo.
- `name` (string, obligatorio): Nombre del item de catálogo.
- `link` (string, obligatorio): Enlace asociado al item de catálogo.

#### Response

##### Códigos:

A estos códigos, hay que sumar los de formato y los de permisos (si procede).

| Tipo   | Código                             | Descripción                                                    |
|--------|------------------------------------|----------------------------------------------------------------|
| Éxito  | `RECORDSET_DATA`                   | Enlace asociado correctamente.                                 |
| Error  | `INTERNAL_SERVER_ERROR_KO`         | Error interno del servidor (o de Base de datos).               |
| Error  | `ITEM_CATALOGO_NO_ENCONTRADO_A_KO` | El item de catálogo a asociar el enlace no ha sido encontrado. |

##### Ejemplo de respuesta exitosa (status 201):

Se ha añadido correctamente el enlace al item de catálogo.

~~~

<PENDIENTE>

~~~

##### Ejemplo de respuesta con error (status 400):

En esta respuesta se han enviado datos que no cumplen con el formato establecido.

~~~

<PENDIENTE>

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

### Delete File

Permite eliminar los datos de un fichero asociado a un item de catálogo ya existente.

#### Request

##### Requisitos de autenticación

Se necesita enviar token de autenticación en la cabecera `Authorization` de la petición, precedido de la palabra
`Bearer`. Aplica validación de permisos. Ver tabla de validaciones de permisos para información sobre los códigos de
error relacionados.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=catalogue&action=deleteFile

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos
Se requiere enviar los siguientes campos:

- `catalogueItemId` (int, obligatorio): Identificador del item de catálogo.
- `fileId` (int, obligatorio): Identificador del fichero asociado al item de catálogo.

#### Response

##### Códigos:

A estos errores, hay que sumar los de formato y los de permisos (si procede).

| Tipo   | Código                             | Descripción                                           |
|--------|------------------------------------|-------------------------------------------------------|
| Éxito  | `RECORDSET_EMPTY`                  | No aplica recuperar datos.                            |
| Error  | `ITEM_CATALOGO_NO_ENCONTRADO_A_KO` | El item de catálogo a eliminar no ha sido encontrado. |
| Error  | `FICHERO_NO_ENCONTRADO_A_KO`       | El fichero a eliminar no ha sido encontrado.          |
| Error  | `INTERNAL_SERVER_ERROR_KO`         | Error interno del servidor (o de Base de datos).      |

##### Ejemplo de respuesta exitosa (status 200):

Se ha eliminado correctamente el fichero asociado al item de catálogo. No se envía recurso, ya que se ha eliminado. 
El status más correcto sería 204, pero ese código no permite enviar cuerpo en la respuesta y por ese motivo se decidió
mantener el status 200.

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

<PENDIENTE>

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

Error provocado por no existir un item de catálogo con el id enviado.

~~~

{
    "ok": false,
    "code": [
        "ITEM_CATALOGO_NO_ENCONTRADO_A_KO"
    ]
}

~~~

### Delete Link

Permite eliminar los datos de un enlace asociado a un item de catálogo ya existente.

#### Request

##### Requisitos de autenticación

Se necesita enviar token de autenticación en la cabecera `Authorization` de la petición, precedido de la palabra
`Bearer`. Aplica validación de permisos. Ver tabla de validaciones de permisos para información sobre los códigos de
error relacionados.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=catalogue&action=deleteLink

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos
Se requiere enviar los siguientes campos:

- `catalogueItemId` (int, obligatorio): Identificador del item de catálogo.
- `linkId` (int, obligatorio): Identificador del enlace asociado al item de catálogo.

#### Response

##### Códigos:

A estos errores, hay que sumar los de formato y los de permisos (si procede).

| Tipo   | Código                             | Descripción                                           |
|--------|------------------------------------|-------------------------------------------------------|
| Éxito  | `RECORDSET_EMPTY`                  | No aplica recuperar datos.                            |
| Error  | `ITEM_CATALOGO_NO_ENCONTRADO_A_KO` | El item de catálogo a eliminar no ha sido encontrado. |
| Error  | `ENLACE_NO_ENCONTRADO_A_KO`        | El enlace a eliminar no ha sido encontrado.           |
| Error  | `INTERNAL_SERVER_ERROR_KO`         | Error interno del servidor (o de Base de datos).      |

##### Ejemplo de respuesta exitosa (status 200):

Se ha eliminado correctamente el enlace asociado al item de catálogo. No se envía recurso, ya que se ha eliminado.
El status más correcto sería 204, pero ese código no permite enviar cuerpo en la respuesta y por ese motivo se decidió
mantener el status 200.

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

<PENDIENTE>

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

Error provocado por no existir un item de catálogo con el id enviado.

~~~

{
    "ok": false,
    "code": [
        "ITEM_CATALOGO_NO_ENCONTRADO_A_KO"
    ]
}

~~~

### List Available Filters

Permite obtener todos los datos de filtro existentes.
Estos son:
 - Áreas
 - Etiquetas
 - Tipos de recursos
 - Formatos
 - Modos de aplicación

#### Request

##### Requisitos de autenticación

No se necesita autenticación para esta acción.

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=catalogue&action=listAvailableFilters

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos

No se requiere enviar campos.

#### Response

##### Códigos:

| Tipo   | Código                     | Descripción                                             |
|--------|----------------------------|---------------------------------------------------------|
| Éxito  | `RECORDSET_DATA`           | Datos recuperados correctamente.                        |
| Éxito  | `RECORDSET_EMPTY`          | No aplica recuperar datos. No hay items de ningún tipo. |
| Error  | `INTERNAL_SERVER_ERROR_KO` | Error interno del servidor (o de Base de datos).        |

##### Ejemplo de respuesta exitosa (status 200):

Se lista todas las clasificaciones existentes.

~~~

<PENDIENTE>

~~~

##### Ejemplo de respuesta exitosa (status 200):

No hay items de catálogo en la base de datos.

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