| Código de error                    | Entidad           | Acción                                                      | Prueba                                                         |
|------------------------------------|-------------------|-------------------------------------------------------------|----------------------------------------------------------------|
| `ITEM_CATALOGO_NO_ENCONTRADO_A_KO` | items_divulgacion | EDIT, DELETE, GET, ADDFILE, ADDLINK, DELETEFILE, DELETELINK | El item de divulgación a editar/eliminar no ha sido encontrado |
| `FICHERO_NO_ENCONTRADO_A_KO`       | items_divulgacion | EDIT, DELETE, GET, DELETEFILE                               | El item de divulgación a editar/eliminar no ha sido encontrado |
| `ENLACE_NO_ENCONTRADO_A_KO`        | items_divulgacion | EDIT, DELETE, GET, DELETELINK                               | El item de divulgación a editar/eliminar no ha sido encontrado |

Notas: 
 - Doy por hecho que no se pueden modificar los atributos marcados como ‘Primary Key’ de una tabla, ya que son los atributos identificadores de las tablas y a la base de datos solo le pasamos un valor para cada atributo. Para poder “editar” la primary key, habría que hacer un DELETE de la tupla y, posteriormente, un ADD.
 - No se reflejan los códigos comunes como INTERNAL_SERVER_ERROR_KO, ya que se consideran implícitos en la respuesta de la API.
 - No se reflejan los códigos de éxito, ya que se consideran implícitos en la respuesta de la API (RECORDSET_DATA o RECORDSET_EMPTY).
