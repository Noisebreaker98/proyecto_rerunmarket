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

//Crear objeto FotosDAO
$fotosDAO = new FotosDAO($conn);

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar datos del formulario
    $titulo = validarEntrada($_POST['titulo-anuncio']);
    $descripcion = validarEntrada($_POST['descripcion-anuncio']);
    $precio = validarEntrada($_POST['precio-anuncio']);

    // Array donde se guardarán posibles errores
    $errores = [];

    // Validaciones
    if (empty($titulo)) {
        $errores[] = 'El título no puede estar vacío.';
    }
    if (empty($descripcion)) {
        $errores[] = 'La descripción no puede estar vacía.';
    }
    if (!is_numeric($precio) || $precio <= 0 || $precio > 999999999) {
        $errores[] = 'El precio debe ser un número positivo.';
    }
    // Verificar si se ha subido una foto
    if (isset($_FILES['foto-anuncio']) && $_FILES['foto-anuncio']['error'] === 0) {
        $fotoNombre = subirFoto($_FILES['foto-anuncio']);
    } else {
        $errores[] = 'Debes subir al menos una foto del producto';
    }

    // Verificar si hay errores
    if (!empty($errores)) {
        // Almacena los errores en la sesión y redirige a la página anterior
        $_SESSION['errores_creacion_anuncio'] = $errores;
        header('Location: index.php');
        exit();
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
        // Si se ha insertado bien el anuncio insertamos la foto principal asociada al anuncio recien creado
        $fotoP = new Foto();
        $fotoP->setIdAnuncio($idAnuncio);
        $fotoP->setNombreFoto($fotoNombre);
        $fotosDAO->insert($fotoP);

        // Subir y asociar la segunda foto opcional
        if (isset($_FILES['foto-anuncio2']) && $_FILES['foto-anuncio2']['error'] === 0) {
            $fotoNombre2 = subirFoto($_FILES['foto-anuncio2']);

            // La metemos en la tabla de FOTOS asociada al anuncio
            $fotoOpcional1 = new Foto();
            $fotoOpcional1->setIdAnuncio($idAnuncio);
            $fotoOpcional1->setNombreFoto($fotoNombre2);
            $fotosDAO->insert($fotoOpcional1);
        }

        // Subir y asociar la tercera foto opcional
        if (isset($_FILES['foto-anuncio3']) && $_FILES['foto-anuncio3']['error'] === 0) {
            $fotoNombre3 = subirFoto($_FILES['foto-anuncio3']);

            // La metemos en la tabla de FOTOS asociada al anuncio
            $fotoOpcional2 = new Foto();
            $fotoOpcional2->setIdAnuncio($idAnuncio);
            $fotoOpcional2->setNombreFoto($fotoNombre3);
            $fotosDAO->insert($fotoOpcional2);
        }

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
