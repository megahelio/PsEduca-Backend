| Código de error             | Entidad     | Atributo  | Acción      | Prueba                         | Valor erróneo      | 
|-----------------------------|-------------|-----------|-------------|--------------------------------|--------------------|
| `ID_PYP_ITEM_MINIMO_F_KO`   | Divulgación | idPyPItem | ADD, DELETE | Validar que sea un número >= 0 | null, "abc"        |
| `ID_PYP_ITEM_INVALIDO_F_KO` | Divulgación | idPyPItem | ADD, DELETE | Validar que es número positivo | null, "abc", "-10" |
| `ID_USUARIO_MINIMO_F_KO`    | Divulgación | idUsuario | ADD, DELETE | Validar que sea un número >= 0 | null, "abc"        |
| `ID_USUARIO_INVALIDO_F_KO`  | Divulgación | idUsuario | ADD, DELETE | Validar que es número positivo | null, "abc", "-10" |