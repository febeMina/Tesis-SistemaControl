<!-- app/Views/includes/alerts.php -->

<!-- Funciones JavaScript para mostrar alertas -->
<script>
    // Función para mostrar una alerta de éxito
    function mostrarAlertaExito(mensaje) {
        Swal.fire({
            title: '¡Éxito!',
            text: mensaje,
            icon: 'success',
            confirmButtonText: 'OK'
        });
    }

    // Función para mostrar una alerta de error
    function mostrarAlertaError(mensaje) {
        Swal.fire({
            title: '¡Error!',
            text: mensaje,
            icon: 'error',
            confirmButtonText: 'OK'
        });
    }
</script>
