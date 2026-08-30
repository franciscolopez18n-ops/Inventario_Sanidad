document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('filterToggle');
    const optionsBox = document.getElementById('filterOptions');
    
    // Añade un manejador para el click en el botón toggle
    toggleBtn.addEventListener('click', (e) => {
        e.stopPropagation(); // Evita que el evento click se propague hacia elementos padres
        // Alterna la visibilidad de las opciones entre 'block' y 'none'
        optionsBox.style.display = optionsBox.style.display === 'block' ? 'none' : 'block';
    });

    // Añade un manejador para el click en cualquier parte del documento
    document.addEventListener('click', (e) => {
        // Si el click no ocurrió dentro de un elemento con la clase 'dropdown-container' cerrar la opción de filtro
        if (!e.target.closest('.dropdown-container')) {
            optionsBox.style.display = 'none';
        }
    });
});