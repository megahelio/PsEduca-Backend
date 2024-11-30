| Código de error                 | Entidad | Atributo        | Acción            | Prueba                                                                                   | Valor erróneo               |
|---------------------------------|---------|-----------------|-------------------|------------------------------------------------------------------------------------------|-----------------------------|
| NOMBRE_EMISOR_MINIMO_F_KO       | Mail    | nombre_emisor   | ADD, EDIT         | Validar longitud mínima (mínimo 4 caracteres)                                            | null, "abc"                 |
| NOMBRE_EMISOR_MAXIMO_F_KO       | Mail    | nombre_emisor   | ADD, EDIT         | Validar longitud máxima (máximo 254 caracteres)                                          | [cadena de 255 caracteres]  |
| NOMBRE_EMISOR_CARACTERES_F_KO   | Mail    | nombre_emisor   | ADD, EDIT         | Validar caracteres (solo a-z, ñ, A-Z, Ñ, -, _, 0-9, áéíóú, ÁÉÍÓÚ, espacio, ª, º)         | "J@hn$on"                   |
| EMAIL_EMISOR_INVALIDO_F_KO      | Mail    | email_emisor    | ADD, EDIT         | Validar formato de email (debe cumplir el estándar RFC 5322)                             | "email_sin_formato"         |
| ASUNTO_MINIMO_F_KO              | Mail    | asunto          | ADD, EDIT         | Validar longitud mínima (mínimo 4 caracteres)                                            | null, "Hi!"                 |
| ASUNTO_MAXIMO_F_KO              | Mail    | asunto          | ADD, EDIT         | Validar longitud máxima (máximo 50 caracteres)                                           | [cadena de 51 caracteres]   |
| ASUNTO_CARACTERES_F_KO          | Mail    | asunto          | ADD, EDIT         | Validar caracteres (solo a-z, ñ, A-Z, Ñ, -, _, 0-9, áéíóú, ÁÉÍÓÚ, espacio, ., ¿, ?, ',') | "Asunto!!!@@"               |
| MENSAJE_MINIMO_F_KO             | Mail    | mensaje         | ADD, EDIT         | Validar longitud mínima (mínimo 10 caracteres)                                           | null, "HOLAAAAAA"           |
| MENSAJE_MAXIMO_F_KO             | Mail    | mensaje         | ADD, EDIT         | Validar longitud máxima (máximo 5000 caracteres)                                         | [cadena de 5001 caracteres] |
| MENSAJE_CARACTERES_F_KO         | Mail    | mensaje         | ADD, EDIT         | Validar caracteres (permitir cualquier carácter imprimible)                              | [carácter no imprimible]    |

