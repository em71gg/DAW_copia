$(document).ready(function() {
    // Mostrar el modal al cargar la página
    $('#cookieModal').fadeIn();
    $('body').addClass('modal-open');  // Bloquear el scroll

    // Cuando el usuario acepta
    $('#acceptCookies').click(function() {
        closeModal();
    });

    // Cuando el usuario no acepta
    $('#declineCookies').click(function() {
        closeModal();
    });

    // Función para cerrar el modal
    function closeModal() {
        $('#cookieModal').fadeOut();
        $('body').removeClass('modal-open');  // Liberar el scroll
    }
});
