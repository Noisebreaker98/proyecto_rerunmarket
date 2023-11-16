<?php

require_once 'Conexion.php';
require_once 'Foto.php';

class FotoDAO {
    private mysqli $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function insert(Foto $foto): int|bool {
        if (!$stmt = $this->conn->prepare("INSERT INTO Fotos (idAnuncio, nombre_foto) VALUES (?, ?)")) {
            die("Error al preparar la consulta insert: " . $this->conn->error);
        }

        $stmt->bind_param("is", $foto->getIdAnuncio(), $foto->getNombreFoto());
        $stmt->execute();

        return $stmt->insert_id;
    }

    public function getAll(): array {
        if (!$stmt = $this->conn->prepare("SELECT * FROM Fotos")) {
            echo "Error en la SQL: " . $this->conn->error;
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $fotos = array();
        while ($foto = $result->fetch_object(Foto::class)) {
            $fotos[] = $foto;
        }

        return $fotos;
    }

    public function getByAnuncioId($idAnuncio): array {
        if (!$stmt = $this->conn->prepare("SELECT * FROM Fotos WHERE idAnuncio = ?")) {
            echo "Error en la SQL: " . $this->conn->error;
        }

        $stmt->bind_param('i', $idAnuncio);
        $stmt->execute();
        $result = $stmt->get_result();

        $fotos = array();
        while ($foto = $result->fetch_object(Foto::class)) {
            $fotos[] = $foto;
        }

        return $fotos;
    }

    public function update(Foto $foto): bool {
        if (!$stmt = $this->conn->prepare("UPDATE Fotos SET idAnuncio=?, nombre_foto=? WHERE idFoto=?")) {
            echo "Error en la SQL: " . $this->conn->error;
        }

        $stmt->bind_param("isi", $foto->getIdAnuncio(), $foto->getNombreFoto(), $foto->getIdFoto());

        return $stmt->execute();
    }

    public function delete($idFoto): bool {
        if (!$stmt = $this->conn->prepare("DELETE FROM Fotos WHERE idFoto=?")) {
            echo "Error en la SQL: " . $this->conn->error;
        }

        $stmt->bind_param('i', $idFoto);
        $stmt->execute();

        return $stmt->affected_rows == 1;
    }
}
?>
