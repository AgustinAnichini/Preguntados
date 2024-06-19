// Detectar si la página se ha recargado
window.onload = function () {
    if (performance.navigation.type === 1) {
        // El usuario ha recargado la página (F5) y el tiempo ha expirado
        // Puedes redirigir al usuario o mostrar un mensaje aquí
        // Ejemplo de redirección:
        window.location.href = '/partida/recargo';
    }
};