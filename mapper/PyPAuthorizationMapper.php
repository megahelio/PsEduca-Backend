<?php

class PyPAuthorizationMapper
{
    private static ?PyPAuthorizationMapper $instance = null;

    public static function getInstance(): PyPAuthorizationMapper
    {
        if (self::$instance == null) {
            self::$instance = new PyPAuthorizationMapper();
        }
        return self::$instance;
    }




    /**
     * Reference to the PDO connection
     * @var PDO
     */
    private PDO $db;

    public function __construct()
    {
        $this->db = PDOConnection::getInstance();
    }

    public function save(PyPAuthorization $pypAuthorization): void
    {
        $stmt = $this->db->prepare("INSERT INTO autorizaciones_usuarios_items_pyp (id_item_pyp, id_usuario) 
            values (:id_item_pyp, :id_usuario)");

        $stmt->execute(array(
            ':id_item_pyp' => $pypAuthorization->getIdPyPItem(),
            ':id_usuario' => $pypAuthorization->getIdUser()
        ));
    }

    /**
     * @throws NotFoundException
     */
    public function delete(PyPAuthorization $pypAuthorization): void
    {
        $stmt = $this->db->prepare("DELETE FROM autorizaciones_usuarios_items_pyp WHERE id_item_pyp = :id_item_pyp AND id_usuario = :id_usuario");

        $stmt->execute(array(
            ':id_item_pyp' => $pypAuthorization->getIdPyPItem(),
            ':id_usuario' => $pypAuthorization->getIdUser()
        ));

        if ($stmt->rowCount() == 0) {
            throw new NotFoundException("Authorization not found");
        }
    }

    public function hasAuthorization(int $pypItemId, int $userId): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM autorizaciones_usuarios_items_pyp WHERE id_item_pyp = :id_item_pyp AND id_usuario = :id_usuario");
        $stmt->execute(array(
            ':id_item_pyp' => $pypItemId,
            ':id_usuario' => $userId
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data['COUNT(*)'] > 0;
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("
            SELECT authPyP.id_item_pyp, items_pyp.titulo, authPyP.id_usuario, usuarios.nombre_usuario 
            FROM autorizaciones_usuarios_items_pyp authPyP
                JOIN items_pyp ON authPyP.id_item_pyp = items_pyp.id
                JOIN usuarios ON authPyP.id_usuario = usuarios.id
                ");
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'PyPAuthorization');
        $currentAuthorizations = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $currentAuthorizations[] = $this->pypAuthorizationFromRow($row);
        }
        return $currentAuthorizations;
    }

    private function pypAuthorizationFromRow(mixed $data): PyPAuthorization
    {
        return new PyPAuthorization(
            idPyPItem: $data['id_item_pyp'],
            titlePyPItem: $data['titulo'],
            idUser: $data['id_usuario'],
            nameUser: $data['nombre_usuario']
        );
    }

    public function pypAuthorizationExists(string $pypItemId, string $userId): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM autorizaciones_usuarios_items_pyp WHERE id_item_pyp = :id_item_pyp AND id_usuario = :id_usuario");
        $stmt->execute(array(
            ':id_item_pyp' => $pypItemId,
            ':id_usuario' => $userId
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data['total'] > 0;
    }
}