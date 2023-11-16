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

// Obtener la conexión a la base de datos
$conexionBD = new Conexion(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
$conn = $conexionBD->getConexion();

// Declaracion de nºelementos/pag. y obtener la pag. en la que estamos
$elementosPorPagina = 5;
$paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

// Si existe la cookie y no ha iniciado sesión, le iniciamos sesión de forma automática
if (!Sesion::getUsuario() && isset($_COOKIE['sid'])) {
    // Nos conectamos a la BD para obtener el usuario completo y su sid
    $usuariosDAO = new UsuariosDAO($conn);
    if ($usuario = $usuariosDAO->getBySid($_COOKIE['sid'])) {
        // Inicio sesión y renuevo la cookie para 7 días más
        Sesion::iniciarSesion($usuario);
        setcookie('sid', $usuario->getSid(), time() + 60 * 60 * 24 * 7, '/');
    }
}

// Obtén el objeto de usuario de la sesión
$usuario = Sesion::getUsuario();

// Verificar si hay un mensaje de registro y mostrarlo
if (isset($_SESSION['registro_mensaje'])) {
    $mensaje_tipo = isset($_SESSION['registro_mensaje_tipo']) ? $_SESSION['registro_mensaje_tipo'] : 'error';
    $mensaje_clase = $mensaje_tipo === 'exito' ? 'mensaje-exito' : 'mensaje-error';

    echo "<div class='mensaje $mensaje_clase'>" . $_SESSION['registro_mensaje'] . "</div>";

    // Eliminar el mensaje después de mostrarlo
    unset($_SESSION['registro_mensaje']);
    unset($_SESSION['registro_mensaje_tipo']);

    // Verificar si hay un mensaje de inicio de sesión y mostrarlo
} elseif (isset($_SESSION['login_mensaje'])) {
    $mensaje_tipo = isset($_SESSION['login_mensaje_tipo']) ? $_SESSION['login_mensaje_tipo'] : 'error';
    $mensaje_clase = $mensaje_tipo === 'exito' ? 'mensaje-exito' : 'mensaje-error';

    echo "<div class='mensaje $mensaje_clase'>" . $_SESSION['login_mensaje'] . "</div>";

    // Eliminar el mensaje después de mostrarlo
    unset($_SESSION['login_mensaje']);
    unset($_SESSION['login_mensaje_tipo']);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="shortcut icon" href="./images/favicon_rerunmarket.ico" type="image/x-icon">
    <title>ReRunMarket - Compra/Venta Segunda Mano</title>
</head>

<body>

    <!-- Cabecera -->
    <header>
        <div class="logo">
            <img src="./images/logo_rerunmarket1.png" alt="ReRunMarket.png">
            <h1>ReRunMarket</h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Anuncios</a></li>
                <?php
                // Verificar si el usuario está autenticado
                if (isset($_SESSION['usuario'])) {
                    // Usuario autenticado, mostrar su nombre
                    echo '<li><a href="index.php?mis_anuncios=true">Mis Anuncios</a></li>';
                    echo '<li>User: ' . Sesion::getUsuario($usuario)->getNombre() . '</li>';
                    echo '<li><a href="Logout.php">Cerrar Sesión</a></li>';
                } else {
                    // Usuario no autenticado, mostrar enlaces de inicio de sesión y registro
                    echo '<li><a href="#" onclick="mostrarPopup(\'iniciar-sesion\')">Iniciar Sesión</a></li>';
                    echo '<li><a href="#" onclick="mostrarPopup(\'registrarme\')">Registrarme</a></li>';
                }
                ?>
            </ul>
        </nav>
    </header>

    <!-- Seccion donde se mostrarán todos los anuncios -->
    <section id="anuncios">
        <h2>Anuncios</h2>

        <?php
        // Obtener Anuncios ordenados por fecha
        $anunciosDAO = new AnunciosDAO($conn);

        // Obtener solo los elementos de la página actual
        $inicio = ($paginaActual - 1) * $elementosPorPagina;

        // Verificar si se están mostrando "Mis Anuncios"
        $mostrarMisAnuncios = isset($_GET['mis_anuncios']) && $_GET['mis_anuncios'] === 'true';

        // Calcular el total de anuncios
        $totalAnuncios = $mostrarMisAnuncios
            ? count($anunciosDAO->getByUserId($usuario->getId()))
            : count($anunciosDAO->getAllOrderedByDate());

        // Calcular el total de páginas
        $totalPaginas = ceil($totalAnuncios / $elementosPorPagina);

        // Obtener los anuncios según la página actual y la condición de 'Mis Anuncios'
        if ($mostrarMisAnuncios) {
            // Mostrar los anuncios del usuario actual
            $anuncios = $anunciosDAO->getByUserId($usuario->getId());
        } else {
            // Obtener todos los anuncios ordenados por fecha
            $anuncios = $anunciosDAO->getAllOrderedByDateLimited($inicio, $elementosPorPagina);
        }

        if (!empty($anuncios)) :
        ?>
            <div class="anuncios-container">
                <?php foreach ($anuncios as $anuncio) : ?>
                    <div class="anuncio">
                        <h3><?= $anuncio->getTitulo() ?></h3>
                        <p><?= $anuncio->getDescripcion() ?></p>
                        <p><?= $anuncio->getPrecio() ?>€</p>
                        <?php $imagenPath = 'images/fotosAnuncios/' . $anuncio->getFoto(); ?>
                        <img src="<?= $imagenPath ?>" alt="<?= $anuncio->getTitulo() ?>">
                        <p>Publicado hace <?= time_elapsed_string($anuncio->getFechaCreacion()) ?></p>
                    </div>
                <?php endforeach; ?>

            </div>

            <!-- APARTADO DE PAGINACION DE ANUNCIOS -->
            <div class='pagination'>
                <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
                    <a href='index.php?pagina=<?= $i ?><?php echo $mostrarMisAnuncios ? '&mis_anuncios=true' : ''; ?>'><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($paginaActual > 1) : ?>
                    <a href='index.php?pagina=<?= $paginaActual - 1 ?><?php echo $mostrarMisAnuncios ? '&mis_anuncios=true' : ''; ?>'>&laquo; Anterior</a>
                <?php endif; ?>

                <?php if ($paginaActual < $totalPaginas) : ?>
                    <a href='index.php?pagina=<?= $paginaActual + 1 ?><?php echo $mostrarMisAnuncios ? '&mis_anuncios=true' : ''; ?>'>Siguiente &raquo;</a>
                <?php endif; ?>

                <a href="#">Volver Arriba</a>
            </div>

        <?php else : ?>
            <p>No hay anuncios disponibles.</p>
        <?php endif; ?>
    </section>


    <!-- Popup de inicio de sesión -->
    <div id="popup" class="popup">
        <div class="popup-login" id="popup-login">
            <span class="cerrar" onclick="cerrarPopup()">&times;</span>
            <h2 id="popup-title">Iniciar Sesión</h2>
            <form id="formulario-login" method="post" action="Login.php">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>

                <div class="btn-login">
                    <button type="submit">Login</button>
                </div>
            </form>
        </div>

        <!-- Popup de registro -->
        <div class="popup-login" id="popup-registro">
            <span class="cerrar" onclick="cerrarPopup()">&times;</span>
            <h2 id="popup-title-registro">Registrarme</h2>

            <?php
            // Verificar si hay errores y valores almacenados en la sesión
            if (isset($_SESSION['registro_errores']) && isset($_SESSION['registro_values'])) {
                $errores = $_SESSION['registro_errores'];
                $registro_values = $_SESSION['registro_values'];

                // Mostrar errores
                foreach ($errores as $error) {
                    echo "<p class='error'>$error</p>";
                }
            } else {
                // Inicializar variables si no hay errores
                $registro_values = array(
                    'email-registro' => '',
                    'password-registro' => '',
                    'repeat-password-registro' => '',
                    'nombre-registro' => '',
                    'telefono-registro' => '',
                    'poblacion-registro' => ''
                );
            }
            ?>

            <form id="formulario-registro" method="post" action="Registro.php">
                <label for="email-registro">Email:</label>
                <input type="email" id="email-registro" name="email-registro" value="<?php echo $registro_values['email-registro']; ?>" required>

                <label for="password-registro">Contraseña:</label>
                <input type="password" id="password-registro" name="password-registro" value="<?php echo $registro_values['password-registro']; ?>" required>

                <label for="repeat-password-registro">Repetir Contraseña:</label>
                <input type="password" id="repeat-password-registro" name="repeat-password-registro" value="<?php echo $registro_values['repeat-password-registro']; ?>" required>

                <label for="nombre-registro">Nombre:</label>
                <input type="text" id="nombre-registro" name="nombre-registro" value="<?php echo $registro_values['nombre-registro']; ?>" required>

                <label for="telefono-registro">Teléfono:</label>
                <input type="tel" id="telefono-registro" name="telefono-registro" value="<?php echo $registro_values['telefono-registro']; ?>" required>

                <label for="poblacion-registro">Población:</label>
                <input type="text" id="poblacion-registro" name="poblacion-registro" value="<?php echo $registro_values['poblacion-registro']; ?>">

                <div class="btn-login">
                    <button type="submit">Registrarme</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Pie de página -->
    <footer>
        <p>&copy; 2023 ReRunMarket. Todos los derechos reservados.</p>
    </footer>
    <script src="./scripts/script.js"></script>
</body>

</html>