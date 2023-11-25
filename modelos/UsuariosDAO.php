<?php

require_once 'Conexion.php';
require_once 'Usuario.php';

class UsuariosDAO {
    private mysqli $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function insert(Usuario $usuario): int|bool {
        if (!$stmt = $this->conn->prepare("INSERT INTO Usuarios (email, password, sid, nombre, telefono, poblacion) VALUES (?, ?, ?, ?, ?, ?)")) {
            die("Error al preparar la consulta insert: " . $this->conn->error);
        }

        $email = $usuario->getEmail();
        $password = $usuario->getPassword();
        $sid = $usuario->getSid();
        $nombre = $usuario->getNombre();
        $telefono = $usuario->getTelefono();
        $poblacion = $usuario->getPoblacion();

        $stmt->bind_param('ssssis', $email, $password, $sid, $nombre, $telefono, $poblacion);
        $stmt->execute();

        return $stmt->insert_id;
    }

    public function getAll(): array {
        if (!$stmt = $this->conn->prepare("SELECT * FROM Usuarios")) {
            echo "Error en la SQL: " . $this->conn->error;
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $usuarios = array();
        while ($usuario = $result->fetch_object(Usuario::class)) {
            $usuarios[] = $usuario;
        }

        return $usuarios;
    }

    public function getByEmail($email): Usuario|null {
        if (!$stmt = $this->conn->prepare("SELECT * FROM Usuarios WHERE email = ?")) {
            echo "Error en la SQL: " . $this->conn->error;
        }

        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows >= 1) {
            $usuario = $result->fetch_object(Usuario::class);
            return $usuario;
        } else {
            return null;
        }
    }

    public function getBySid($sid): Usuario|null {
        if (!$stmt = $this->conn->prepare("SELECT * FROM Usuarios WHERE sid = ?")) {
            echo "Error en la SQL: " . $this->conn->error;
        }

        $stmt->bind_param('s', $sid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows >= 1) {
            $usuario = $result->fetch_object(Usuario::class);
            return $usuario;
        } else {
            return null;
        }
    }

    public function getById($id): Usuario|null {
        if (!$stmt = $this->conn->prepare("SELECT * FROM Usuarios WHERE id = ?")) {
            echo "Error en la SQL: " . $this->conn->error;
        }

        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows >= 1) {
            $usuario = $result->fetch_object(Usuario::class);
            return $usuario;
        } else {
            return null;
        }
    }

    public function update(Usuario $usuario): bool {
        if (!$stmt = $this->conn->prepare("UPDATE Usuarios SET email=?, password=?, sid=?, nombre=?, telefono=?, poblacion=? WHERE id=?")) {
            echo "Error en la SQL: " . $this->conn->error;
        }

        $stmt->bind_param("ssssisi", $usuario->getEmail(), $usuario->getPassword(), $usuario->getSid(), $usuario->getNombre(), $usuario->getTelefono(), $usuario->getPoblacion(), $usuario->getId());

        return $stmt->execute();
    }

    public function delete($id): bool {
        if (!$stmt = $this->conn->prepare("DELETE FROM Usuarios WHERE id=?")) {
            echo "Error en la SQL: " . $this->conn->error;
        }

        $stmt->bind_param('i', $id);
        $stmt->execute();

        return $stmt->affected_rows == 1;
    }

    public function existeUsuarioPorEmail($email) {
        $sql = "SELECT COUNT(*) FROM Usuarios WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        return $count > 0;
    }

    public function existeUsuarioPorTelefono($telefono) {
        $sql = "SELECT COUNT(*) FROM Usuarios WHERE telefono = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $telefono);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        return $count > 0;
    }
}
?>
