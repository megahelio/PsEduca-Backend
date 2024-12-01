| Código de error                 | Entidad | Atributo        | Acción                  | Prueba                                                                           | Valor erróneo        | 
|---------------------------------|---------|-----------------|-------------------------|----------------------------------------------------------------------------------|----------------------|
| ID_MINIMO_F_KO                  | Usuario | id              | EDIT                    | Validar que sea un número >= 0                                                   | null, "abc"          |
| ID_INVALIDO_F_KO                | Usuario | id              | GET, LIST, EDIT, DELETE | Validar que es número positivo                                                   | null, "abc", "-10"   |
| NOMBRE_USUARIO_MINIMO_F_KO      | Usuario | nombre_usuario  | ADD, EDIT, LOGIN        | Validar longitud mínima (mínimo 4 caracteres)                                    | null, "abc"          |
| NOMBRE_USUARIO_MAXIMO_F_KO      | Usuario | nombre_usuario  | ADD, EDIT, LOGIN        | Validar longitud máxima (máximo 254 caracteres)                                  | [cadena de 255 caracteres] |
| NOMBRE_USUARIO_CARACTERES_F_KO  | Usuario | nombre_usuario  | ADD, EDIT, LOGIN        | Validar caracteres (solo a-z, A-Z, -, _, 0-9)                                    | "maria@lopez"        |
| NOMBRE_COMPLETO_MINIMO_F_KO     | Usuario | nombre_usuario  | ADD, EDIT               | Validar longitud mínima (mínimo 4 caracteres). Admite nulos                      | "abc"                |
| NOMBRE_COMPLETO_MAXIMO_F_KO     | Usuario | nombre_completo | ADD, EDIT               | Validar longitud máxima (máximo 254 caracteres)                                  | [cadena de 255 caracteres] |
| NOMBRE_COMPLETO_CARACTERES_F_KO | Usuario | nombre_completo | ADD, EDIT               | Validar caracteres (solo a-z, ñ, A-Z, Ñ, -, _, 0-9, áéíóú, ÁÉÍÓÚ, espacio, ª, º) | "maria@lopez"        |
| CONTRASENHA_MINIMO_F_KO         | Usuario | contrasenha     | ADD, EDIT, LOGIN        | Validar longitud mínima (mínimo 4 caracteres)                                    | "123"                |
| CONTRASENHA_MAXIMO_F_KO         | Usuario | contrasenha     | ADD, EDIT, LOGIN        | Validar longitud máxima (máximo 254 caracteres)                                  | [cadena de 255 caracteres] |
| CONTRASENHA_CARACTERES_F_KO     | Usuario | contrasenha     | ADD, EDIT, LOGIN        | Validar caracteres (solo a-z, A-Z, -, _, 0-9, $, @, (, ), ., +, =, /)            | "a&b&c&d"            |
| ROL_F_KO                        | Usuario | rol             | ADD, EDIT               | Validar que el rol sea válido (ADMIN_GLOBAL, GESTOR_CATALOGO, USUARIO_PYP)       | "ADMINISTRADOR"      |
