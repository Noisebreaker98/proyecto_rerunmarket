<?php
session_start();

require_once 'modelos/Usuario.php';
require_once 'modelos/UsuariosDAO.php';
require_once 'modelos/Conexion.php';
require_once 'modelos/Config.php';
require_once 'modelos/Funciones.php';

// Si se ha enviado el formulario de registro (por POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recupera los datos del formulario
    $email = validarEntrada($_POST['email-registro']);
    $password = password_hash(validarEntrada($_POST['password-registro']), PASSWORD_DEFAULT);
    $sid = md5(rand() + time()); // Genera un nuevo sid
    $nombre = validarEntrada($_POST['nombre-registro']);
    $telefono = validarEntrada($_POST['telefono-registro']);
    $poblacion = validarEntrada($_POST['poblacion-registro']);

    // conexionDB
    $conexionBD = new Conexion(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
    $conn = $conexionBD->getConexion();

    // Crear UsuariosDAO
    $usuarioDAO = new UsuariosDAO($conn);

    // Realiza las validaciones
    $errores = array();

    // Validación de campos
    if (empty($email) || empty($password) || empty($nombre) || empty($telefono)) {
        $errores[] = "Todos los campos son obligatorios, excepto Población.";
    }

    // Verifica si la contraseña tiene al menos 4 caracteres
    if (strlen(validarEntrada($_POST['password-registro'])) < 4) {
        $errores[] = "La contraseña debe tener al menos 4 caracteres.";
    }

    // Verifica si el correo electrónico tiene una estructura válida
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El formato del correo electrónico no es válido.";
    }

    // Verifica si las contraseñas coinciden
    if ($_POST['password-registro'] !== $_POST['repeat-password-registro']) {
        $errores[] = "Las contraseñas no coinciden.";
    }

    // Validación de email único
    if ($usuarioDAO->existeUsuarioPorEmail($email)) {
        $errores[] = "Ya existe un usuario con este email.";
    }

    // Validación de teléfono único
    if ($usuarioDAO->existeUsuarioPorTelefono($telefono)) {
        $errores[] = "Ya existe un usuario con este teléfono.";
    }

    // Si hay errores, los mostramos
    if (!empty($errores)) {
        // Limpiar y almacenar los valores del formulario en la sesión para autocompletar
        $_SESSION['registro_values'] = array_map('htmlentities', $_POST);

        // Guardamos el mensaje de error en la sesión
        $_SESSION['registro_mensaje'] = "No ha sido posible registrarse.";
        $_SESSION['registro_mensaje_tipo'] = 'error';

        // Almacenamos los errores especificos posibles del formulario
        $_SESSION['registro_errores'] = $errores;

        // Redirigimos de nuevo al formulario de registro con los errores
        header("Location: index.php#popup-registro");
        exit; 
    }

    // Crea un nuevo objeto Usuario con los datos del formulario
    $nuevoUsuario = new Usuario();
    $nuevoUsuario->setEmail($email);
    $nuevoUsuario->setPassword($password);
    $nuevoUsuario->setSid($sid);
    $nuevoUsuario->setNombre($nombre);
    $nuevoUsuario->setTelefono($telefono);
    $nuevoUsuario->setPoblacion($poblacion);

    // Intenta insertar el nuevo usuario en la base de datos
    $idUsuarioInsertado = $usuarioDAO->insert($nuevoUsuario);

    // Si no ha habido problemas, se guarda un mensaje de tipo éxito en la sesión y se redirige a index.php para mostrarlo
    if ($idUsuarioInsertado) {
        $_SESSION['registro_mensaje'] = "Se ha registrado correctamente.";
        $_SESSION['registro_mensaje_tipo'] = 'exito';
        header('Location: index.php');
        die();
    }
}
