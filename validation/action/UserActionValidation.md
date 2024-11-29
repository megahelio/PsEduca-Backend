| Código de error               | Entidad | Acción            | Prueba                                                                   |
|-------------------------------|---------|-------------------|--------------------------------------------------------------------------|
| NOMBRE_USUARIO_YA_EXISTE_A_KO | Usuario | ADD, EDIT         | El nombre de usuario coincide con el de otro usuario del sistema PsEduca |
| USUARIO_NO_ENCONTRADO_A_KO    | Usuario | EDIT, DELETE, GET | El usuario a editar/eliminar no ha sido encontrado                       |

Notas: 
 - Doy por hecho que no se pueden modificar los atributos marcados como ‘Primary Key’ de una tabla, ya que son los atributos identificadores de las tablas y a la base de datos solo le pasamos un valor para cada atributo. Para poder “editar” la primary key, habría que hacer un DELETE de la tupla y, posteriormente, un ADD.
 - En la columna ‘Prueba’, cuando afirmo que un valor de un atributo existe (o no existe) sin identificar en qué tabla, me refiero a que existe en la tabla equivalente en la columna ‘Entidad’.
 - Al eliminar un usuario, se eliminarán los permisos PYP asociados a él. Se realiza la eliminación mediante un DELETE en cascada. Esto no genera desde backend un error, pero sí debería advertirse en front a los usuarios al realizar la acción.
