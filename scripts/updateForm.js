//EDITOR JQTE PARA CAMPO DESCRIPCION
$(document).ready(function () {
    // Inicializa el editor jqte en el campo de descripción
    $('#descripcion-anuncio').jqte({
        fsize: true,
        bold: true,
        italic: true,
        underline: true,
        color: false,
        link: false,
        unlink: false,
        ol: false,
        ul: false,
        indent: false,
        outdent: false,
        left: true,
        center: true,
        right: true,
        justify: true,
        remove: false,
        source: false,
        sub: false,
        sup: false,
        fsizeItems: ['10', '12', '16', '20'],
        placeholder: 'Escribe aquí...',
        height: 200,
        maxlength: 200
    });
});

/**
 * Redirige al usuario a la sección de Mis anuncios
 */
function volverAtras() {
    window.location.href = 'index.php?mis_anuncios=true';
}