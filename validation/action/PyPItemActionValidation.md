| Código de error               | Entidad   | Acción            | Prueba                                                                           |
|-------------------------------|-----------|-------------------|----------------------------------------------------------------------------------|
| `ITEM_PYP_NO_ENCONTRADO_A_KO` | items_pyp | EDIT, DELETE, GET | El item de pruebas y programas a editar/eliminar/recuperar no ha sido encontrado |

Notas: 
 - Doy por hecho que no se pueden modificar los atributos marcados como ‘Primary Key’ de una tabla, ya que son los atributos identificadores de las tablas y a la base de datos solo le pasamos un valor para cada atributo. Para poder “editar” la primary key, habría que hacer un DELETE de la tupla y, posteriormente, un ADD.
