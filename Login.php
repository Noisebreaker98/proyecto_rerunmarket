<?php
session_start();

require_once 'modelos/Funciones.php';
require_once 'modelos/Conexion.php';
require_once 'modelos/Config.php';
require_once 'modelos/UsuariosDAO.php';
require_once 'modelos/Usuario.php';
require_once 'modelos/Sesion.php';

// Verificar si se ha enviado el formulario de inicio de sesión
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recuperar los datos del formulario
    $email = validarEntrada($_POST['email']);
    $password = validarEntrada($_POST['password']);

    // Validar los datos
    if (empty($email) || empty($password)) {
        guardarMensaje("Todos los campos son obligatorios.");
        header('Location: index.php');
        die();
    }

    // Obtener la conexión a la base de datos
    $conexionBD = new Conexion(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
    $conn = $conexionBD->getConexion();

    // Crear un objeto UsuariosDAO
    $usuarioDAO = new UsuariosDAO($conn);

    // Verificar las credenciales del usuario
    $usuario = $usuarioDAO->getByEmail($email);

    if ($usuario && password_verify($password, $usuario->getPassword())) {
        // Inicio de sesión exitoso
        Sesion::iniciarSesion($usuario);
        
        // Guardar el nombre del usuario en la sesión
        $_SESSION['nombre_usuario'] = $usuario->getNombre();

        //Creamos la cookie para que nos recuerde durante 1 semana
        setcookie('sid',$usuario->getSid(),time()+60*60*24*7, '/');

        // Guardar mensaje de exito para mostrar en index
        $_SESSION['login_mensaje'] = "El usuario " . $usuario->getNombre() . " inició sesión correctamente";
        $_SESSION['login_mensaje_tipo'] = 'exito';
        
        header('Location: index.php');
        die();
    } else {
        // Guardamos el mensaje de error para mostrar en index en caso de error.
        $_SESSION['login_mensaje'] = "Credenciales incorrectas.";
        $_SESSION['login_mensaje_tipo'] = 'error';
        header('Location: index.php');
        die();
    }
}
