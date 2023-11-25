<?php
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

session_start();

// Obtener la conexión a la base de datos
$conexionBD = new Conexion(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
$conn = $conexionBD->getConexion();

// Obtener el ID del anuncio desde la URL
$idAnuncio = isset($_GET['idAnuncio']) ? $_GET['idAnuncio'] : null;

// Obtener el anuncio por su ID
$anunciosDAO = new AnunciosDAO($conn);
$anuncio = $anunciosDAO->getById($idAnuncio);

// Verificar si el anuncio existe
if (!$anuncio) {
    // Puedes redirigir a una página de error o mostrar un mensaje
    die("Anuncio no encontrado.");
}

// Obtener las fotos asociadas al anuncio
$fotosDAO = new FotosDAO($conn);
$fotos = $fotosDAO->getByAnuncioId($idAnuncio);

// Obtener el usuario propietario del anuncio
$usuariosDAO = new UsuariosDAO($conn);
$propietario = $usuariosDAO->getById($anuncio->getIdUsuario());
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Anuncio - <?= $anuncio->getTitulo(); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/css/glide.core.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./styles/showProduct.css">
</head>

<body>
    <div id="overlay">
        <div id="anuncio-container">
            <button id="cerrar-btn" onclick="cerrarAnuncio()">&times;</button>
            <div id="anuncio-content">
                <h2><?= $anuncio->getTitulo(); ?></h2>
                <p><?= html_entity_decode(htmlspecialchars_decode($anuncio->getDescripcion())); ?></p>
                <p>Precio: <?= $anuncio->getPrecio(); ?>€</p>

                <!-- Carrousel de imágenes utilizando Glide.js -->
                <div id="carrousel" class="glide">
                    <div class="glide__track" data-glide-el="track">
                        <ul class="glide__slides">
                            <?php
                            foreach ($fotos as $foto) {
                                $imagenPath = 'images/fotosAnuncios/' . $foto->getNombreFoto();
                                echo "<li class='glide__slide'><img src='$imagenPath' alt='Foto del anuncio'></li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <p>Publicado hace <?= time_elapsed_string($anuncio->getFechaCreacion()) ?> por <strong> <?= $propietario->getNombre(); ?></strong></p>

                <!-- Mostrar acciones solo si el usuario está autenticado y es el propietario del anuncio -->
                <?php
                if ($propietario && $propietario instanceof Usuario && $anuncio && $anuncio instanceof Anuncio && Sesion::getUsuario() && $propietario->getId() === Sesion::getUsuario()->getId()) :
                ?>
                    <div class="actions">
                        <a href="UpdateAnuncio.php?idAnuncio=<?= $anuncio->getIdAnuncio() ?>"><i class="fa-solid fa-pen-to-square" style="color: #444E4E;"></i></a>
                        <a href="DeleteAnuncio.php?id=<?= $anuncio->getIdAnuncio() ?>"><i class="fa-solid fa-trash" style="color: #444E4E;"></i></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Scripts para Glide.js -->
    <script src="https://cdn.jsdelivr.net/npm/@glidejs/glide"></script>
    <script>
        new Glide('.glide').mount();

        function cerrarAnuncio() {
            window.location.href = 'index.php';
        }
    </script>
</body>

</html>