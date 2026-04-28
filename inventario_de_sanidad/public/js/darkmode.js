// Ejecutar al cargar el DOM
document.addEventListener("DOMContentLoaded", function () {
    // Obtener estado guardado del modo oscuro desde el localStorage
    let darkmode = localStorage.getItem('darkmode');
    let themeSwitch = document.getElementById('theme-switch');

    // Si el modo oscuro está activo, habilitarlo
    if (darkmode === "active") {
        enableDarkMode();
    }

    // Añadir evento click al botón para alternar tema
    themeSwitch.addEventListener("click", toggleTheme);
});


// Alternar entre modo oscuro y claro
function toggleTheme() {
    darkmode = localStorage.getItem('darkmode');
    if (darkmode !== "active")
        enableDarkMode();
    else
        disableDarkMode();
}

// Habilita el modo oscuro: añade clase y guarda estado
function enableDarkMode() {
    document.body.classList.add('darkmode');
    localStorage.setItem('darkmode', 'active');
}

// Deshabilita el modo oscuro: remueve clase y limpia estado
function disableDarkMode() {
    document.body.classList.remove('darkmode');
    localStorage.setItem('darkmode', null);
}