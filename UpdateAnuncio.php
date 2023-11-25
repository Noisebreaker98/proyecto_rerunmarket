<?php
session_start();

require_once 'modelos/Funciones.php';
require_once 'modelos/UsuariosDAO.php';
require_once 'modelos/Usuario.php';
require_once 'modelos/AnunciosDAO.php';
require_once 'modelos/Anuncio.php';
require_once 'modelos/Conexion.php';
require_once 'modelos/Config.php';
require_once 'modelos/Sesion.php';
require_once 'modelos/FotosDAO.php';
require_once 'modelos/Foto.php';

// Verificar si el usuario no está autenticado
if (!Sesion::getUsuario()) {
    header('Location: index.php');
    exit();
}

// Obtén mensajes de error de la variable de sesión
$errors = isset($_SESSION['errorUpdate']) ? [$_SESSION['errorUpdate']] : [];
unset($_SESSION['errorUpdate']); // Limpia la variable de sesión después de usarla

// Obtener la conexión a la base de datos
$conexionBD = new Conexion(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
$conn = $conexionBD->getConexion();

// Creamos los objetos AnunciosDAO y FotosDAO con la conexión 
$anunciosDAO = new AnunciosDAO($conn);
$fotosDAO = new FotosDAO($conn);

// Si existe el id del anuncio, lo filtramos y lo recogemos. Después, obtenemos el anuncio
$id = (isset($_GET['idAnuncio']) ? htmlspecialchars($_GET['idAnuncio']) : null);

if ($id) {
    $anuncio = $anunciosDAO->getById($id);

    // Verificamos si la llamada a getById devolvió un anuncio válido
    if (!$anuncio) {
        die("Error: Anuncio no encontrado.");
    }
}

// Al enviar el formulario limpiamos los datos de entrada 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Inicializamos un array para almacenar mensajes de error
    $errors = [];

    // Limpiamos los datos que vienen del usuario
    $titulo = validarEntrada($_POST['titulo-anuncio']);
    $descripcion = validarEntrada($_POST['descripcion-anuncio']);
    $precio = validarEntrada($_POST['precio-anuncio']);
    $idAnuncio = validarEntrada($_POST['id-anuncio']);

    // Metemos los datos al nuevo anuncio si no hay ningún error
    $anuncioN = new Anuncio();
    $anuncioN->setTitulo($titulo);
    $anuncioN->setDescripcion($descripcion);
    $anuncioN->setPrecio($precio);
    $anuncioN->setIdAnuncio($idAnuncio);

    echo "Después de la limpieza: <pre>";
    var_dump($descripcion);
    echo "</pre>";

    //Validaciones
    if (strlen($descripcion) > 300) {
        $_SESSION['errorUpdate'] = "La descripción no puede tener más de 300 caracteres";
        header("Location: UpdateAnuncio.php?idAnuncio=$idAnuncio");
        die();
    }
    if (strlen($titulo) > 80) {
        $_SESSION['errorUpdate'] = "El título no puede tener más de 80 caracteres";
        header("Location: UpdateAnuncio.php?idAnuncio=$idAnuncio");
        die();
    }
    if (!is_numeric($precio) || $precio <= 0 || $precio > 999999999) {
        $_SESSION['errorUpdate'] = "El precio introducido no es válido";
        header("Location: UpdateAnuncio.php?idAnuncio=$idAnuncio");
        die();
    }

    // Verificamos si se ha subido una nueva foto
    if (isset($_FILES['foto-portada']['name'])) {
        // Subimos la nueva foto y obtenemos su nombre
        $fotoPrincipal = subirFoto($_FILES['foto-portada']);

        if (!$fotoPrincipal) {
            // Si subirFoto devuelve false, es un mensaje de error
            header("Location: index.php?errorArchivos=" . "El archivo seleccionado no es válido o no se ha seleccionado.");
            die();
        }
        $anuncioN->setFoto($fotoPrincipal);

        // La metemos en la tabla de FOTOS asociada al anuncio
        $fotoP = new Foto();
        $fotoP->setIdAnuncio($idAnuncio);
        $fotoP->setNombreFoto($fotoPrincipal);
        $fotosDAO->insert($fotoP);
    } else {
        $errors[] = 'La foto del anuncio es requerida.';
    }

    // Verificamos si se ha subido una segunda foto
    if (!isset($_FILES['foto-opcional1']['error']) || $_FILES['foto-opcional1']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Subimos la nueva foto y obtenemos su nombre
        $fotoSecundaria1 = subirFoto($_FILES['foto-opcional1']);

        // La metemos en la tabla de FOTOS asociada al anuncio
        $fotoS1 = new Foto();
        $fotoS1->setIdAnuncio($idAnuncio);
        $fotoS1->setNombreFoto($fotoSecundaria1);
        $fotosDAO->insert($fotoS1);
    }

    // Verificamos si se ha subido una tercera foto
    if (!isset($_FILES['foto-opcional2']['error']) || $_FILES['foto-opcional2']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Subimos la nueva foto y obtenemos su nombre
        $fotoSecundaria2 = subirFoto($_FILES['foto-opcional2']);

        //La metemos en la tabla de FOTOS asociada al anuncio
        $fotoS2 = new Foto();
        $fotoS2->setIdAnuncio($idAnuncio);
        $fotoS2->setNombreFoto($fotoSecundaria2);
        $fotosDAO->insert($fotoS2);
    }

    //Si no hay errores en las validaciones, pasamos a intentar actualizar el anuncio
    if (empty($errors)) {
        // Si no hay ningún problema en la consulta insertamos los datos en la BD y redirigimos al index
        if ($anunciosDAO->update($anuncioN)) {
            header("Location: index.php");
            die();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="shortcut icon" href="./images/favicon_rerunmarket.ico" type="image/x-icon">
    <link rel="stylesheet" href="./styles/updateForm.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-te/1.4.0/jquery-te.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-te/1.4.0/jquery-te.min.js"></script>
    <script src="./scripts/updateForm.js"></script>
    <title>Editar Anuncio - ReRunMarket</title>
</head>

<body>
    <section id="editar-anuncio">
        <h2>Editar Anuncio</h2>

        <!-- Mostrar mensajes de error -->
        <?php if (!empty($errors)) : ?>
            <div class="error-container">
                <ul>
                    <?php foreach ($errors as $error) : ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>


        <form id="formulario-editar-anuncio" method="post" action="UpdateAnuncio.php" enctype="multipart/form-data">
            <!-- Autocompletamos los campos del formulario -->
            <label for="titulo-anuncio">Título:</label>
            <input type="text" id="titulo-anuncio" name="titulo-anuncio" value="<?= $anuncio->getTitulo() ?>" required>

            <label for="descripcion-anuncio">Descripción:</label>
            <textarea id="descripcion-anuncio" name="descripcion-anuncio" required><?= html_entity_decode(htmlspecialchars_decode($anuncio->getDescripcion())) ?></textarea>

            <label for="precio-anuncio">Precio:</label>
            <input type="text" id="precio-anuncio" name="precio-anuncio" pattern="\d+(\.\d{1,2})?" value="<?= $anuncio->getPrecio() ?>" required placeholder="Ejemplo: 15.99">

            <!-- Campo para foto principal -->
            <label for="foto-portada">Foto Principal:</label>
            <input type="file" id="foto-portada" name="foto-portada" accept="image/*">
            <!-- Campos para fotos secundarias -->
            <label for="foto-opcional1">Más fotos:</label>
            <input type="file" id="foto-opcional1" name="foto-opcional1" accept="image/*">
            <label for="foto-opcional2">Más fotos:</label>
            <input type="file" id="foto-opcional2" name="foto-opcional2" accept="image/*">

            <input type="hidden" name="id-anuncio" value="<?= $anuncio->getIdAnuncio() ?>">
            <div class="btn-login">
                <button type="submit">Guardar Cambios</button>
            </div>

        </form>
    </section>
</body>

</html>