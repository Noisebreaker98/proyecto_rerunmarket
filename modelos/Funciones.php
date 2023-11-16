<?php

/**
 * Genera un hash aleatorio para un nombre de archivo manteniendo la extensión original
 */
function generarNombreArchivo(String $nombreOriginal): string
{
    $nuevoNombre = md5(time() + rand());
    $partes = explode('.', $nombreOriginal);
    $extension = $partes[count($partes) - 1];
    return $nuevoNombre . '.' . $extension;
}

/**
 * Guarda el mensaje que pasemos por parámetro en una variable de sesión "error"
 */
function guardarMensaje($mensaje)
{
    $_SESSION['error'] = $mensaje;
}

/**
 * Imprime el mensaje que hayamos guardado con "guardarMensaje()" en un elemento span con clase "error"
 * Después, limpia la variable de sesión con nombre "error"
 */
function imprimirMensaje()
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
function validarEntrada($entrada) {
    // Elimina espacios en blanco al inicio y al final
    $entrada = trim($entrada);
    
    // Elimina barras invertidas que escapen caracteres
    $entrada = stripslashes($entrada);
    
    // Convierte caracteres especiales en entidades HTML
    $entrada = htmlspecialchars($entrada);
    
    return $entrada;
}

/**
 * Calcula el tiempo transcurrido desde la fecha proporcionada hasta ahora.
 *
 * @param string $datetime Fecha y hora en formato 'Y-m-d H:i:s'.
 * @param bool $full Mostrar un formato completo (por ejemplo, "hace 2 días" en lugar de "hace 2d").
 * @return string Tiempo transcurrido formateado.
 */
function time_elapsed_string($datetime, $full = false) {
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



