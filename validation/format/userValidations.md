| Código de error                 | Entidad | Atributo        | Acción     | Prueba                                                                      | Valor erróneo              | 
|---------------------------------|---------|-----------------|------------|-----------------------------------------------------------------------------|----------------------------|
| NOMBRE_USUARIO_VACIO_F_KO       | Usuario | nombre_usuario  | ADD, EDIT  | Validar no vacío                                                            | null, ""                   |
| NOMBRE_USUARIO_MAXIMO_F_KO      | Usuario | nombre_usuario  | ADD, EDIT  | Validar longitud máxima (máximo 254 caracteres)                             | [cadena de 255 caracteres] |
| NOMBRE_USUARIO_CARACTERES_F_KO  | Usuario | nombre_usuario  | ADD, EDIT  | Validar caracteres (solo a-z, A-Z, -, _, 1-9, á, é, í, ó, ú)                | "maria@lopez"              |
| NOMBRE_COMPLETO_MAXIMO_F_KO     | Usuario | nombre_completo | ADD, EDIT  | Validar longitud máxima (máximo 254 caracteres)                             | [cadena de 255 caracteres] |
| NOMBRE_COMPLETO_CARACTERES_F_KO | Usuario | nombre_completo | ADD, EDIT  | Validar caracteres (solo a-z, A-Z, -, _, 1-9, á, é, í, ó, ú, espacio, ª, º) | "maria@lopez"              |
| CONTRASENHA_MINIMO_F_KO         | Usuario | contrasenha     | ADD, EDIT  | Validar longitud mínima (mínimo 4 caracteres)                               | "123"                      |
| CONTRASENHA_MAXIMO_F_KO         | Usuario | contrasenha     | ADD, EDIT  | Validar longitud máxima (máximo 254 caracteres)                             | [cadena de 255 caracteres] |
| CONTRASENHA_CARACTERES_F_KO     | Usuario | contrasenha     | ADD, EDIT  | Validar caracteres (solo a-z, A-Z, -, _, 1-9, $, @, /, \)                   | "maria@lopez"              |
| ROL_F_KO                        | Usuario | rol             | ADD, EDIT  | Validar que el rol sea válido (ADMIN_GLOBAL, GESTOR_CATALOGO, USUARIO_PYP)  | "ADMINISTRADOR"            |
