# ContactController

API destinada a la recepción de correos electrónicos de contacto.

> ## Verificar correos electrónicos 
> Como la API no tiene como objetivo devolver datos, para visualizar la emisión de correos electrónicos de 
> contacto, se hace uso de servidor SMTP. A efectos de demostración, se puede acceder a la bandeja de salida fictícea
> del servidor SMTP Etheral desde:
>
> [Ethereal Fake SMTP](https://ethereal.email/login)
> 
> Credenciales:
> - **Usuario**: `moses.volkman@ethereal.email`
> - **Contraseña**: `thQraTYyVv6fUgqW3A`

## Acciones

### Send

Permite iniciar enviar un correo electrónico de contacto.

#### Request

##### URL
La URL de acceso es:
http://localhost/PsEduca-Backend/?controller=contact&action=send

Únicamente se aceptará envío mediante método **POST** y utilizando **form-data**.
Las respuestas serán siempre en formato **JSON**.

##### Campos
Se requiere enviar los siguientes campos:

- `name` (string, obligatorio): Nombre del remitente.
- `email` (string, obligatorio): Contraseña. Se envía en claro, se cifra en el servidor.
- `subject` (string, obligatorio): Asunto del mensaje.
- `message` (string, obligatorio): Mensaje a enviar.

#### Response

##### Códigos:

| Tipo  | Código                          | Descripción                                                                                             |
|-------|---------------------------------|---------------------------------------------------------------------------------------------------------|
| Éxito | `RECORDSET_DATA`                | Email enviado correctamente.                                                                            |
| Error | `INTERNAL_SERVER_ERROR_KO`      | Error interno del servidor (o del servidor SMTP).                                                       |
| Error | `NOMBRE_EMISOR_MINIMO_F_KO`     | El nombre del emisor no cumple longitud mínima (mínimo 4 caracteres)                                    |
| Error | `NOMBRE_EMISOR_MAXIMO_F_KO`     | El nombre del emisor no cumple longitud máxima (máximo 254 caracteres)                                  |
| Error | `NOMBRE_EMISOR_CARACTERES_F_KO` | El nombre del emisor no cumple caracteres (solo a-z, ñ, A-Z, Ñ, -, _, 0-9, áéíóú, ÁÉÍÓÚ, espacio, ª, º) |
| Error | `EMAIL_EMISOR_INVALIDO_F_KO`    | El email no cumple formato de email (debe cumplir el estándar RFC 5322)                                 |
| Error | `ASUNTO_MINIMO_F_KO`            | El asunto no cumple longitud mínima (mínimo 4 caracteres)                                               |
| Error | `ASUNTO_MAXIMO_F_KO`            | El asunto no cumple longitud máxima (máximo 50 caracteres)                                              |
| Error | `ASUNTO_CARACTERES_F_KO`        | El asunto no cumple caracteres (solo a-z, ñ, A-Z, Ñ, -, _, 0-9, áéíóú, ÁÉÍÓÚ, espacio, ., ¿, ?, ',')    |
| Error | `MENSAJE_MINIMO_F_KO`           | El mensaje no cumple longitud mínima (mínimo 10 caracteres)                                             |
| Error | `MENSAJE_MAXIMO_F_KO`           | El mensaje no cumple longitud máxima (máximo 5000 caracteres)                                           |
| Error | `MENSAJE_CARACTERES_F_KO`       | El mensaje no cumple caracteres (permitir cualquier carácter imprimible)                                |

##### Ejemplo de respuesta exitosa (status 200):

El email ha sido enviado correctamente

~~~
{
    "ok": true,
    "code": [
        "RECORDSET_EMPTY"
    ]
}
~~~

##### Ejemplo de respuesta con error (status 400):

En esta respuesta no se ha enviado el mensaje, el asunto es demasiado largo y el email del emisor no es válido., 

~~~

{
    "ok": false,
    "code": [
        "EMAIL_EMISOR_INVALIDO_F_KO",
        "ASUNTO_MAXIMO_F_KO",
        "MENSAJE_MINIMO_F_KO"
    ]
}

~~~

##### Ejemplo de respuesta con error (status 503 o status 500):

En esta respuesta se ha producido un error del servidor, con origen en el servidor SMTP (status 503) o error no previsto en servidor (status 500).

~~~

{
    "ok": false,
    "code": [
        "INTERNAL_SERVER_ERROR_KO"
    ]
}

~~~
