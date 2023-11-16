<?php
require_once 'modelos/Sesion.php';

session_start();
Sesion::cerrarSesion();
setcookie('sid','',0,'/');    //Borra las cookies
header("Location: index.php"); // Redirige a la página principal u otra página después de cerrar sesión
exit;
?>
