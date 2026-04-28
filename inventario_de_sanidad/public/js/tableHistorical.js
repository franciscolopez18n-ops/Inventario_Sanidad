window.addEventListener("DOMContentLoaded", inicio);

/**
 * Función principal que espera a que `window.MODIFICATIONSDATA` esté disponible, oculta el loader,
 * carga los datos y genera la tabla inicial.
 */
async function inicio() {
    // Espera hasta que la variable global MODIFICATIONSDATA esté definida
    while (typeof window.MODIFICATIONSDATA === 'undefined') {
        await new Promise(resolve => setTimeout(resolve, 100)); // Espera 100ms y vuelve a intentar
    }

    hideLoader(); // Oculta el indicador de carga

    allData = window.MODIFICATIONSDATA; // Asigna los datos a la variable global
    console.log(allData); // Debug: muestra los datos cargados

    paginaActual = 0; // Reinicia el contador de página

    initLoad(); // Inicializa los eventos y lógica de filtros/paginación

    renderTable(currentLimit, paginaActual); // Muestra la tabla con los datos iniciales
}

/**
 * Renderiza la tabla HTML con los datos filtrados y paginados.
 * @param {number} limit - Número de registros por página.
 * @param {number} paginaActual - Página actual que se debe mostrar.
 */
function renderTable(limit, paginaActual) {
    let tbody = document.querySelector("table tbody"); // Selecciona el cuerpo de la tabla

    // Limpia cualquier contenido previo en la tabla
    while (tbody.firstChild) tbody.removeChild(tbody.firstChild);

    // Aplica el filtro de búsqueda sobre los campos definidos
    let filtrados = aplicarFiltro([
        "first_name", "last_name", "email", "user_type",
        "material_name", "units", "storage", "storage_type", "action_datetime"
    ]);

    // Calcula los índices de inicio y fin según la página actual y el límite
    let inicio = paginaActual * limit;
    let fin = inicio + limit;

    // Obtiene los datos correspondientes a la página actual
    let datosPagina = filtrados.slice(inicio, fin);
    console.log(datosPagina);
    // Recorre los datos de la página y genera las filas de la tabla
    datosPagina.forEach(item => {
        let tr = document.createElement("tr");

        // Crea y agrega las celdas con sus respectivos labels responsivos
        tr.appendChild(crearDataLabel(crearTD(item.first_name ?? "-"), "Nombre"));
        tr.appendChild(crearDataLabel(crearTD(item.last_name ?? "-"), "Apellidos"));
        tr.appendChild(crearDataLabel(crearTD(item.email ?? "-"), "Email"));
        tr.appendChild(crearDataLabel(crearTD(item.user_type ?? "-"), "Tipo de usuario"));
        tr.appendChild(crearDataLabel(crearTD(item.material_name ?? "-"), "Material"));
        tr.appendChild(crearDataLabel(crearTD(item.units ?? "-"), "Unidades modificadas"));
        tr.appendChild(crearDataLabel(
            crearTD(item.storage == "CAE" ? "CAE" : "Odontología"),
            "Localización"
        ));
        tr.appendChild(crearDataLabel(
            crearTD(item.storage_type == "reserve" ? "reserva" : "uso"),
            "Tipo de almacenamiento"
        ));
        tr.appendChild(crearDataLabel(crearTD(item.action_datetime ?? "-"), "Fecha de modificación"));

        tbody.appendChild(tr); // Añade la fila a la tabla
    });

    // Muestra los botones de paginación correspondientes
    renderPaginationButtons(filtrados.length, limit);
}



