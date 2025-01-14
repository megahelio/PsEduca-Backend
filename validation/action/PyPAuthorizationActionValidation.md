| Código de error                       | Entidad   | Acción | Prueba                                                                        |
|---------------------------------------|-----------|--------|-------------------------------------------------------------------------------|
| `ITEM_PYP_NO_ENCONTRADO_A_KO`         | items_pyp | ADD    | El item de pruebas y programas mencionado no ha sido encontrado               |
| `USUARIO_NO_ENCONTRADO_A_KO`          | items_pyp | ADD    | El usuario mencionado no ha sido encontrado                                   |
| `AUTORIZACION_PYP_YA_EXISTE_A_KO`     | items_pyp | ADD    | La autorización de pruebas y programas a añadir ya existe en la base de datos |
| `AUTORIZACION_PYP_NO_ENCONTRADA_A_KO` | items_pyp | DELETE | La autorización de pruebas y programas a eliminar no ha sido encontrada       |
Notas:
 - Doy por hecho que no se pueden modificar los atributos marcados como ‘Primary Key’ de una tabla, ya que son los atributos identificadores de las tablas y a la base de datos solo le pasamos un valor para cada atributo. Para poder “editar” la primary key, habría que hacer un DELETE de la tupla y, posteriormente, un ADD.
