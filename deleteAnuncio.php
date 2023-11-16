<?php
session_start();

// Verificar si el usuario está autenticado
if (!Sesion::getUsuario()) {
    header("Location: index.php");
    exit();
}

require_once 'modelos/AnunciosDAO.php';
require_once 'modelos/Anuncio.php';
require_once 'modelos/Conexion.php';
require_once 'modelos/Config.php';
require_once 'modelos/Sesion.php';
require_once 'modelos/Funciones.php';

// Obtener la conexión a la base de datos
$conexionBD = new Conexion(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
$conn = $conexionBD->getConexion();
// Obtén el objeto de usuario de la sesión
$usuario = Sesion::getUsuario();

// Verificar si se proporciona un ID de anuncio
if (isset($_GET['id'])) {
    $anuncioId = validarEntrada($_GET['id']);

    // Obtener el anuncio por ID
    $anunciosDAO = new AnunciosDAO($conn);
    $anuncio = $anunciosDAO->getById($anuncioId);

    // Verificar si el usuario actual es el propietario del anuncio
    if ($anuncio && $usuario->getId() === $anuncio->getIdUsuario()) {
        // Eliminar el anuncio
        $anunciosDAO->delete($anuncioId);
        header("Location: index.php?mis_anuncios=true");
        exit();
    }
}

// Si no se proporciona un ID válido o el usuario no es el propietario, redireccionar a la página principal
header("Location: index.php");
exit();
?>
