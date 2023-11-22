<?php
session_start();

require_once 'modelos/Funciones.php';
require_once 'modelos/UsuariosDAO.php';
require_once 'modelos/Usuario.php';
require_once 'modelos/AnunciosDAO.php';
require_once 'modelos/Anuncio.php';
require_once 'modelos/FotosDAO.php';
require_once 'modelos/Foto.php';
require_once 'modelos/Conexion.php';
require_once 'modelos/Config.php';
require_once 'modelos/Sesion.php';

// Verificar si el usuario no está autenticado
if (!Sesion::getUsuario()) {
    header('Location: index.php');
    exit();
}

// Obtener la conexión a la base de datos
$conexionBD = new Conexion(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
$conn = $conexionBD->getConexion();

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar datos del formulario
    $titulo = $_POST['titulo-anuncio'];
    $descripcion = $_POST['descripcion-anuncio'];
    $precio = $_POST['precio-anuncio'];

    // Validar datos
    $errores = [];

    // Verificar que el título no esté vacío
    if (empty($titulo)) {
        $errores[] = 'El título no puede estar vacío.';
    }

    // Verificar que la descripción no esté vacía
    if (empty($descripcion)) {
        $errores[] = 'La descripción no puede estar vacía.';
    } else {
        // Permitir solo ciertas etiquetas HTML en la descripción
        $descripcion = strip_tags($descripcion, '<strong><em><br><u>');

        // Verificar si la descripción es válida después de eliminar las etiquetas no permitidas
        if (empty(trim($descripcion))) {
            $errores[] = 'La descripción no puede consistir solo en etiquetas HTML no permitidas.';
        }
    }

    // Verificar que el precio sea un número positivo
    if (!is_numeric($precio) || $precio <= 0) {
        $errores[] = 'El precio debe ser un número positivo.';
    }

    // Verificar si hay errores
    if (!empty($errores)) {
        // Almacena los errores en la sesión y redirige a la página anterior
        $_SESSION['errores_creacion_anuncio'] = $errores;
        header('Location: index.php');
        exit();
    }

    // Verificar si se ha subido una foto
    if (isset($_FILES['foto-anuncio']) && $_FILES['foto-anuncio']['error'] === 0) {
        $fotoNombre = subirFoto($_FILES['foto-anuncio']);
    } else {
        $fotoNombre = null;
    }

    // Obtener el usuario actual
    $usuario = Sesion::getUsuario();

    // Crear objeto Anuncio
    $anuncio = new Anuncio();
    $anuncio->setIdUsuario($usuario->getId());
    $anuncio->setTitulo($titulo);
    $anuncio->setDescripcion($descripcion);
    $anuncio->setPrecio($precio);
    $anuncio->setFoto($fotoNombre);

    // Crear objeto AnunciosDAO y realizar la inserción
    $anunciosDAO = new AnunciosDAO($conn);
    $idAnuncio = $anunciosDAO->insert($anuncio);

    if ($idAnuncio) {
        // La inserción fue exitosa
        $_SESSION['mensaje'] = 'Anuncio creado con éxito.';
        $_SESSION['mensaje_tipo'] = 'exito';
    } else {
        // Hubo un error en la inserción
        $_SESSION['mensaje'] = 'Error al crear el anuncio. Por favor, inténtalo de nuevo.';
        $_SESSION['mensaje_tipo'] = 'error';
    }

    // Redirigir a la página principal
    header('Location: index.php');
    exit();
}
