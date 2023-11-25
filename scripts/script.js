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
    var popupPublicarAnuncio = document.getElementById('popup-publicar-anuncio');

    // Ocultar todos los popups antes de mostrar el deseado
    popupLogin.style.display = 'none';
    popupRegistro.style.display = 'none';
    popupPublicarAnuncio.style.display = 'none';

    // Lógica para mostrar el popup según el tipo
    if (tipo === 'iniciar-sesion') {
        popupTitleLogin.innerText = 'Iniciar Sesión';
        mostrarPopupElemento(popupLogin);
    } else if (tipo === 'registrarme') {
        popupTitleRegistro.innerText = 'Registrarme';
        mostrarPopupElemento(popupRegistro);
    } else if (tipo === 'publicar-anuncio') {
        popupTitleRegistro.innerText = 'Publicar Anuncio';
        mostrarPopupElemento(popupPublicarAnuncio);

        // Reinicializar el editor jqte cada vez que se muestra el popup de publicar anuncio
        $('#descripcion-anuncio').jqte({
            // Tamaño de fuente
            fsize: true,
            // Negrita
            bold: true,
            // Cursiva
            italic: true,
            // Subrayado
            underline: true,
            // Color de texto
            color: false,
            // Añadir enlace
            link: false,
            // Eliminar enlace
            unlink: false,
            // Agregar lista ordenada
            ol: false,
            // Agregar lista desordenada
            ul: false,
            // Sangría izquierda
            indent: false,
            // Sangría derecha
            outdent: false,
            // Alineación izquierda
            left: true,
            // Alineación centro
            center: true,
            // Alineación derecha
            right: true,
            // Alineación justificada
            justify: true,
            // Eliminar formato
            remove: false,
            // Insertar imagen
            source: false,
            // Mostrar código fuente
            sub: false,
            // Superíndice
            sup: false,
            // Agregar lista de opciones de tamaño de fuente
            fsizeItems: ['10', '12', '16', '20'],
            // Placeholder del editor
            placeholder: 'Escribe aquí...',
            // Altura máxima del editor
            height: 200,
            maxlength: 300
        });
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

    // Inicializar jqte si el elemento es el campo de descripción
    if (elemento.id === 'popup-publicar-anuncio') {
        $('#descripcion-anuncio').jqte();
    }
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
setTimeout(function () {
    var mensaje = document.querySelector('.mensaje');
    if (mensaje) {
        mensaje.style.display = 'none';
    }
}, 2000); // 2000 milisegundos = 2 segundos

//Cuando la pagina está cargada
$(document).ready(function () {
    // Inicializar jqte
    $('#descripcion-anuncio').jqte()
    // Documentación de jqte: https://github.com/jquery-text-editor/jqte#options
});

document.addEventListener("DOMContentLoaded", function () {
    // Función para alternar el modo oscuro
    function toggleDarkMode() {
        const body = document.body;
        body.classList.toggle("dark-mode");

       // Cambiar la imagen del logo en el header
    const logoImage = document.getElementById("logo-img");

    if (logoImage) {
        const isDarkMode = body.classList.contains("dark-mode");
        const newSrc = isDarkMode ? "./images/logo_rerunmarket_dark.png" : "./images/logo_rerunmarket1.png";

        logoImage.src = newSrc;
        }
    }
    // Asociar la función al botón o evento que desencadena el cambio de modo oscuro
    const darkModeButton = document.getElementById("dark-mode-button");
    if (darkModeButton) {
        darkModeButton.addEventListener("click", toggleDarkMode);
    }
});
