/**
 * Registra el evento para ejecutar la función `inicio` cuando el DOM esté listo.
 */
window.addEventListener("DOMContentLoaded", inicio);

/**
 * Función principal que inicializa la vista, espera los datos históricos,
 * oculta el loader, inicializa eventos y renderiza las vistas de tabla y tarjetas.
 */
async function inicio() {
    initViewToggle(); // Inicializa el toggle entre vista tarjeta y tabla

    // Espera hasta que `window.HISTORICALDATA` esté definido
    while (typeof window.HISTORICALDATA === 'undefined') {
        await new Promise(resolve => setTimeout(resolve, 100));
    }
    
    hideLoader(); // Oculta el loader de carga

    allData = window.HISTORICALDATA; // Guarda los datos globalmente
    paginaActual = 0; // Página actual inicia en 0
    
    initLoad(); // Inicializa eventos de búsqueda y paginación

    renderTable(currentLimit, paginaActual); // Renderiza tabla con paginación
    renderTableCards(currentLimit, paginaActual); // Renderiza tarjetas con paginación
}

/**
 * Inicializa los botones para alternar entre la vista de tarjetas y la vista de tabla.
 * También configura el filtro en tiempo real.
 */
function initViewToggle() {
    let isStudent = document.querySelector(".user-role").textContent.includes("student"); // Verifica si el usuario es admin
    if (!isStudent)
    {
        let cardViewBtn = document.getElementById('cardViewBtn');
        let tableViewBtn = document.getElementById('tableViewBtn');
        let cardView = document.getElementById('cardView');
        let tableView = document.getElementById('tableView');

        /**
         * Muestra la vista en tarjetas y oculta la tabla.
         * Cambia estilos para indicar botón activo.
         */
        function activateCardView() {
            cardView.style.display = 'grid';
            tableView.style.display = 'none';
            cardViewBtn.classList.add('active');
            tableViewBtn.classList.remove('active');
        }

        /**
         * Muestra la vista en tabla y oculta las tarjetas.
         * Cambia estilos para indicar botón activo.
         */
        function activateTableView() {
            cardView.style.display = 'none';
            tableView.style.display = 'block';
            tableViewBtn.classList.add('active');
            cardViewBtn.classList.remove('active');
        }

        // Event listeners para botones de cambio de vista
        cardViewBtn.addEventListener('click', (e) => {
            e.preventDefault();
            activateCardView();
        });

        tableViewBtn.addEventListener('click', (e) => {
            e.preventDefault();
            activateTableView();
        });

        activateCardView(); // Vista por defecto: tarjetas
    }
}

/**
 * Renderiza las tarjetas con la información paginada y filtrada.
 * @param {number} limit - Cantidad de elementos por página.
 * @param {number} paginaActual - Página actual.
 */
function renderTableCards(limit, paginaActual) {
    let container = document.querySelector("#cardView");
    if (!container) return;

    // Limpia el contenedor de tarjetas
    while (container.firstChild) container.removeChild(container.firstChild);
    let isStudent = document.querySelector(".user-role").textContent.includes("student"); // Verifica si el usuario es admin

    let filtro =  ["name", "description", "storage", "cabinet", "shelf", "units", "min_units"];
    if (isStudent) {
        filtro =  ["name", "description", "storage", "cabinet", "shelf"];
    }
    // Aplica filtro según campos relevantes
    let filtrados = aplicarFiltro(filtro);

    let inicio = paginaActual * limit;
    let fin = inicio + limit;
    let datosPagina = filtrados.slice(inicio, fin);

    // Por cada material, crea una tarjeta y la agrega al contenedor
    datosPagina.forEach(material => {
        container.appendChild(crearMaterialCard(material));
    });

    renderPaginationButtons(filtrados.length, limit); // Renderiza paginación
}

/**
 * Crea una tarjeta HTML para mostrar la información de un material.
 * @param {Object} material - Objeto con los datos del material.
 * @returns {HTMLDivElement} - Elemento div con la tarjeta.
 */
function crearMaterialCard(material) {
    let card = document.createElement("div");
    card.className = "material-card";
    let isStudent = document.querySelector(".user-role").textContent.includes("student"); // Verifica si el usuario es admin

    let img = document.createElement("img");
    img.src = material.image_path
        ? `/storage/${material.image_path}`
        : `/img/no_image.jpg`;
    img.alt = material.name ?? "Sin nombre";
    card.appendChild(img);

    let body = document.createElement("div");
    body.className = "material-card-body";

    let h5 = document.createElement("h5");
    h5.textContent = material.name ?? "-";
    body.appendChild(h5);

    let p = document.createElement("p");
    p.textContent = material.description ?? "-";
    body.appendChild(p);

    let ul = document.createElement("ul");
    ul.appendChild(crearLi("Localización", material.storage == "CAE" ? "CAE" : "Odontología"));
    ul.appendChild(crearLi("Armario", material.cabinet));
    ul.appendChild(crearLi("Balda", material.shelf));
    if (!isStudent) {
        ul.appendChild(crearLi("Unidades", material.units));
        ul.appendChild(crearLi("Unidades mínimas", material.min_units));
    }
    body.appendChild(ul);

    card.appendChild(body);
    return card;
}

/**
 * Renderiza la tabla con los datos paginados y filtrados.
 * @param {number} limit - Cantidad de registros por página.
 * @param {number} paginaActual - Página actual.
 */
function renderTable(limit, paginaActual) {
    let tbody = document.querySelector("table tbody");
    while (tbody.firstChild) tbody.removeChild(tbody.firstChild);

    let filtrados = aplicarFiltro(["name", "description", "storage", "cabinet", "shelf", "units", "min_units"]);

    let inicio = paginaActual * limit;
    let fin = inicio + limit;
    let datosPagina = filtrados.slice(inicio, fin);

    datosPagina.forEach(item => {
        let tr = document.createElement("tr");

        // Columna con imagen del material
        let td = document.createElement("td");
        let img = document.createElement("img");
        img.src = new URL(
            item.image_path ? '/storage/' + item.image_path : '/img/no_image.jpg',
            window.location
        ).href;
        img.style.maxWidth = "100px";
        td.appendChild(img);
        tr.appendChild(td);

        // Columnas con datos y data-label para responsive
        tr.appendChild(crearDataLabel(crearTD(item.name ?? "-"), "Nombre"));
        tr.appendChild(crearDataLabel(crearTD(item.description ?? "-"), "Descripción"));
        tr.appendChild(crearDataLabel(crearTD(item.storage == "CAE" ? "CAE" : "Odontología"), "Localización"));
        tr.appendChild(crearDataLabel(crearTD(item.cabinet ?? "-"), "Armario"));
        tr.appendChild(crearDataLabel(crearTD(item.shelf ?? "-"), "Balda"));
        tr.appendChild(crearDataLabel(crearTD(item.units ?? "-"), "Unidades"));
        tr.appendChild(crearDataLabel(crearTD(item.min_units ?? "-"), "Mínimo"));

        tbody.appendChild(tr);
    });

    renderPaginationButtons(filtrados.length, limit); // Actualiza botones de paginación
}

/**
 * Retorna la URL para editar un elemento según si el usuario es admin o no.
 * @param {number|string} id - ID del elemento a editar.
 * @returns {string} - URL de edición.
 */
function getEditUrl(id) {
    let isStudent = document.querySelector(".user-role").textContent.includes("admin");
    return isStudent ? `/storages/update/${id}/edit` : `/storages/update/teacher/${id}/edit`;
}
