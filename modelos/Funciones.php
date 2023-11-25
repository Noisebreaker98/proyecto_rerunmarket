<?php

/**
 * Genera un hash aleatorio para un nombre de archivo manteniendo la extensión original.
 *
 * @param string $nombreOriginal El nombre original del archivo del cual se generará el nuevo nombre.
 * @return string El nuevo nombre de archivo generado, que consiste en un hash aleatorio y la extensión original.
 */
function generarNombreArchivo(String $nombreOriginal): string
{
    $nuevoNombre = md5(time() + rand());
    $partes = explode('.', $nombreOriginal);
    $extension = $partes[count($partes) - 1];
    return $nuevoNombre . '.' . $extension;
}

/**
 * Guarda un mensaje en la sesión para su posterior recuperación.
 *
 * @param string $mensaje El mensaje que se desea almacenar en la sesión.
 */
function guardarMensaje($mensaje): void
{
    $_SESSION['error'] = $mensaje;
}

/**
 * Imprime el mensaje almacenado con "guardarMensaje()" en un elemento span con clase "error".
 * Luego, limpia la variable de sesión con nombre "error".
 */
function imprimirMensaje(): void
{
    if (isset($_SESSION['error'])) {
        echo '<div id="err" class="err"><span class="error">' . $_SESSION['error'] . '</span></div>';
        unset($_SESSION['error']);
    }
}

/**
 * Limpia y valida una entrada de datos.
 *
 * @param string $entrada La entrada de datos a validar.
 * @return string La entrada de datos validada y limpiada.
 */
function validarEntrada($entrada): string
{
    // Elimina espacios en blanco al inicio y al final
    $entrada = trim($entrada);

    // Elimina barras invertidas que escapen caracteres
    $entrada = stripslashes($entrada);

    // Convierte caracteres especiales en entidades HTML
    $entrada = htmlspecialchars($entrada);
    $entrada = htmlentities($entrada);

    return $entrada;
}

/**
 * Calcula el tiempo transcurrido desde la fecha proporcionada hasta ahora.
 *
 * @param string $datetime Fecha y hora en formato 'Y-m-d H:i:s'.
 * @param bool $full Mostrar un formato completo (por ejemplo, "hace 2 días" en lugar de "hace 2d").
 * @return string Tiempo transcurrido formateado.
 */
function time_elapsed_string($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    // Definir los nombres de las unidades de tiempo
    $string = array('y' => 'año', 'm' => 'mes', 'd' => 'día', 'h' => 'hora', 'i' => 'minuto', 's' => 'segundo');

    // Construir la cadena de tiempo transcurrido
    foreach ($string as $key => &$value) {
        if ($diff->$key) {
            $value = $diff->$key . ' ' . $value . ($diff->$key > 1 ? 's' : '');
        } else {
            unset($string[$key]);
        }
    }

    // Limitar la cadena al formato corto si no se solicita el formato completo
    if (!$full) $string = array_slice($string, 0, 1);
    // Devolver la cadena formateada
    return $string ? implode(', ', $string) . ' ' : 'ahora';
}

/**
 * Sube una foto al servidor y retorna el nombre del archivo si la subida es exitosa.
 *
 * @param array $archivo El archivo de imagen a subir, generalmente proveniente de un formulario HTML.
 * @return string|null|false El nombre del archivo si se subió correctamente, null si no se proporcionó ningún archivo, false si la subida falló.
 */
function subirFoto($archivo)
{
    // Verificar si se proporcionó un archivo
    if (!isset($archivo['tmp_name']) || empty($archivo['tmp_name'])) {
        // No se seleccionó ninguna foto
        return null;
    }

    $directorio = 'images/fotosAnuncios/';
    $nombreOriginal = basename($archivo['name']);
    $nuevoNombre = generarNombreArchivo($nombreOriginal);
    $rutaCompleta = $directorio . $nuevoNombre;

    // Verificar si el archivo es una imagen
    $tipoImagen = exif_imagetype($archivo['tmp_name']);
    $tiposPermitidos = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_WEBP];

    // Verificar también por la extensión del archivo
    $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if ((in_array($tipoImagen, $tiposPermitidos) || in_array($extension, $extensionesPermitidas))
    && move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
        return $nuevoNombre;
    } else {
        return false;
    }
}
