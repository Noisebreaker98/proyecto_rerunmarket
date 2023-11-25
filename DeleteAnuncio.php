<?php
require_once 'modelos/AnunciosDAO.php';
require_once 'modelos/Anuncio.php';
require_once 'modelos/Conexion.php';
require_once 'modelos/Config.php';
require_once 'modelos/Sesion.php';
require_once 'modelos/Funciones.php';
require_once 'modelos/Usuario.php';
require_once 'modelos/FotosDAO.php';

session_start();

// Verificar si el usuario está autenticado
if (!Sesion::getUsuario()) {
    header("Location: index.php");
    exit();
}

// Obtener la conexión a la base de datos
$conexionBD = new Conexion(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
$conn = $conexionBD->getConexion();

// Obtener el objeto de usuario de la sesión
$usuario = Sesion::getUsuario();

// Verificar si se proporciona un ID de anuncio
if (isset($_GET['id'])) {
    $anuncioId = validarEntrada($_GET['id']);

    // Obtener el anuncio por ID
    $anunciosDAO = new AnunciosDAO($conn);
    $anuncio = $anunciosDAO->getById($anuncioId);

    // Verificar si el usuario actual es el propietario del anuncio
    if ($anuncio && $usuario->getId() === $anuncio->getIdUsuario()) {
        // Obtener el nombre de las fotos asociadas al anuncio
        $fotosDAO = new FotosDAO($conn);
        $fotos = $fotosDAO->getByAnuncioId($anuncioId);

        // Eliminar las fotos en la carpeta
        foreach ($fotos as $foto) {
            $rutaFoto = 'images/fotosAnuncios/' . $foto->getNombreFoto();
            if (file_exists($rutaFoto)) {
                // Intentar eliminar la foto y verificar errores
                if (!unlink($rutaFoto)) {
                    die("Error al eliminar la foto: " . $rutaFoto);
                }
            } else {
                die("La foto no existe: " . $rutaFoto);
            }
        }

        // Eliminar el anuncio y las fotos asociadas en la base de datos
        if ($anunciosDAO->delete($anuncioId) && $fotosDAO->deleteByAnuncioId($anuncioId)) {
            // Éxito al eliminar, redirigir a la página de mis anuncios
            header("Location: index.php?mis_anuncios=true");
            exit();
        } else {
            die("Error al eliminar el anuncio y las fotos asociadas en la base de datos.");
        }
    }
}

// Si no se proporciona un ID válido o el usuario no es el propietario, redireccionar a la página principal
header("Location: index.php");
exit();
?>
