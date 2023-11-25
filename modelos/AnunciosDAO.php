<?php

require_once 'Conexion.php';
require_once 'Anuncio.php';

class AnunciosDAO
{
    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function insert(Anuncio $anuncio): int|bool
    {
        if (!$stmt = $this->conn->prepare("INSERT INTO Anuncios (idUsuario, titulo, descripcion, precio, foto) VALUES (?, ?, ?, ?, ?)")) {
            die("Error al preparar la consulta insert: " . $this->conn->error);
        }

        $idUsuario = $anuncio->getIdUsuario();
        $titulo = $anuncio->getTitulo();
        $descripcion = $anuncio->getDescripcion();
        $precio = $anuncio->getPrecio();
        $foto = $anuncio->getFoto();

        $stmt->bind_param("issds", $idUsuario, $titulo, $descripcion, $precio, $foto);
        $stmt->execute();

        return $stmt->insert_id;
    }

    public function getAllOrderedByDate(): array
    {
        if (!$stmt = $this->conn->prepare("SELECT * FROM Anuncios ORDER BY fecha_creacion DESC")) {
            echo "Error en la SQL: " . $this->conn->error;
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $anuncios = array();
        while ($anuncio = $result->fetch_object(Anuncio::class)) {
            $anuncios[] = $anuncio;
        }

        return $anuncios;
    }

    // Nuevo método para obtener Anuncios con límites para la paginación
    public function getAllOrderedByDateLimited($inicio, $elementosPorPagina): array
    {
        if (!$stmt = $this->conn->prepare("SELECT * FROM Anuncios ORDER BY fecha_creacion DESC LIMIT ?, ?")) {
            echo "Error en la SQL: " . $this->conn->error;
        }

        $stmt->bind_param("ii", $inicio, $elementosPorPagina);
        $stmt->execute();
        $result = $stmt->get_result();

        $anuncios = array();
        while ($anuncio = $result->fetch_object(Anuncio::class)) {
            $anuncios[] = $anuncio;
        }

        return $anuncios;
    }

    public function getByUserId($idUsuario): array
    {
        if (!$stmt = $this->conn->prepare("SELECT * FROM Anuncios WHERE idUsuario = ?")) {
            echo "Error en la SQL: " . $this->conn->error;
        }

        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();

        $anuncios = array();
        while ($anuncio = $result->fetch_object(Anuncio::class)) {
            $anuncios[] = $anuncio;
        }

        return $anuncios;
    }

    /**
     * Obtiene un anuncio por su ID.
     *
     * @param int $id ID del anuncio a obtener.
     * @return Anuncio|null Objeto Anuncio si se encuentra, o null si no se encuentra.
     */
    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Anuncios WHERE idAnuncio = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $anuncio = $result->fetch_object('Anuncio');
            return $anuncio;

        } else {
            return null;
        }
    }

    public function update(Anuncio $anuncio): bool
    {
        if (!$stmt = $this->conn->prepare("UPDATE Anuncios SET titulo = ?, descripcion = ?, precio = ?, foto = ? WHERE idAnuncio = ?")) {
            die("Error al preparar la consulta update: " . $this->conn->error);
        }
    
        $titulo = $anuncio->getTitulo();
        $descripcion = $anuncio->getDescripcion();
        $precio = $anuncio->getPrecio();
        $foto = $anuncio->getFoto();
        $idAnuncio = $anuncio->getIdAnuncio();
    
        $stmt->bind_param("ssdsi", $titulo, $descripcion, $precio, $foto, $idAnuncio);
        $stmt->execute();
    
        return $stmt->affected_rows > 0;
    }

    public function delete($idAnuncio): bool
    {
        if (!$stmt = $this->conn->prepare("DELETE FROM Anuncios WHERE idAnuncio=?")) {
            echo "Error en la SQL: " . $this->conn->error;
        }

        $stmt->bind_param('i', $idAnuncio);
        $stmt->execute();

        return $stmt->affected_rows == 1;
    }
}
