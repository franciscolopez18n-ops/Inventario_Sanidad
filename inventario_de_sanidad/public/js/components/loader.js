function hideLoader() {
    setTimeout(() => {
        const loader = document.getElementById("loader-overlay");
        if (!loader) return;

        // Añade la clase "fade-out" para iniciar la animación de desvanecimiento
        loader.classList.add("fade-out");

        // Escucha el evento "animationend" para saber cuándo termina la animación
        loader.addEventListener("animationend", () => {
            // Cuando termina la animación, oculta el loader por completo
            loader.style.display = "none";
        }, { once: true }); // Elimina el listener automáticamente tras ejecutarse una vez
    });
}