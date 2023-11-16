console.log('scripts.js loaded');
/**
 * Muestra el popup según el tipo proporcionado.
 *
 * @param {string} tipo - El tipo de popup a mostrar ('iniciar-sesion' o 'registrarme').
 */
function mostrarPopup(tipo) {
    // Obtener elementos del DOM relacionados con el popup
    var popup = document.getElementById('popup');
    var popupTitleLogin = document.getElementById('popup-title');
    var popupTitleRegistro = document.getElementById('popup-title-registro');
    var popupLogin = document.getElementById('popup-login');
    var popupRegistro = document.getElementById('popup-registro');

    // Lógica para mostrar el popup según el tipo
    if (tipo === 'iniciar-sesion') {
        popupTitleLogin.innerText = 'Iniciar Sesión';
        popupRegistro.style.display = 'none';
        mostrarPopupElemento(popupLogin);
    } else if (tipo === 'registrarme') {
        popupTitleRegistro.innerText = 'Registrarme';
        popupLogin.style.display = 'none';
        mostrarPopupElemento(popupRegistro);
    }

    // Mostrar el popup
    popup.style.display = 'block';
}

/**
 * Muestra el elemento del formulario.
 *
 * @param {HTMLElement} elemento - El elemento del formulario a mostrar.
 */
function mostrarPopupElemento(elemento) {
    elemento.style.display = 'block';
}

/**
 * Cierra el popup y reinicia los formularios.
 */
function cerrarPopup() {
    // Obtener elementos del DOM relacionados con los formularios
    var popup = document.getElementById('popup');

    // Ocultar el popup
    popup.style.display = 'none';
}

// Ocultar el mensaje después de 2 segundos
setTimeout(function() {
    var mensaje = document.querySelector('.mensaje');
    if (mensaje) {
        mensaje.style.display = 'none';
    }
}, 2000); // 2000 milisegundos = 2 segundos



