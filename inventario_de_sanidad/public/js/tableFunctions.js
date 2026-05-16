let allData = [];
let currentLimit = 5;
let paginatual = 0;   

/** 
 * Inicializa los eventos de búsqueda, filtros por radio y selector de cantidad de registros por página.
 */
function initLoad() {
    // Asigna el evento de búsqueda al campo de texto
    document.getElementById("buscarId").addEventListener("keyup", filtrarTabla);

    // Asigna el evento a cada radio button de filtro
    document.getElementsByName("filtro").forEach(radio => {
        radio.addEventListener("change", filtrarTabla);
    });

    // Evento para el cambio de registros por página
    document.getElementById("regsPorPagina").addEventListener("change", event => {
        currentLimit = parseInt(event.target.value); // Actualiza el límite por página
        paginatual = 0; // Reinicia a la primera página

        renderTable(currentLimit, paginatual); // Renderiza la tabla principal

        // Verifica si la página requiere vista tipo tarjetas
        let url = window.location.href.split("/").pop();
        if (url == "use" || url == "reserve") {
            renderTableCards(currentLimit, paginatual);
        }
    });
}

/** 
 * Aplica el filtro y actualiza la tabla y/o tarjetas según la URL.
 */
function filtrarTabla() {
    paginatual = 0; // Reinicia la paginación
    renderTable(currentLimit, paginatual); // Aplica filtros a la tabla

    // Determina si se deben renderizar tarjetas
    let url = window.location.href.split("/").pop();
    if (url == "use" || url == "reserve") {
        renderTableCards(currentLimit, paginatual);
    }
}

/**
 * Crea un elemento <td> con contenido y configuración especial si es admin.
 * @param {string} texto - Contenido de la celda.
 * @returns {HTMLTableCellElement} Celda generada.
 */
function crearTD(texto) {
    // Verifica si la página es la de gestionnar almacenamiento
    let url = window.location.href.split("/").pop();

    let isAdmin = document.querySelector(".user-role").textContent.includes("admin"); // Verifica si el usuario es admin

    let td = document.createElement("td");

    // Si es admin y la categoría coincide, aplica rowspan
    if (isAdmin && (url == "update") && (texto == "CAE" || texto == "Odontología")) {
        td.rowSpan = 2;
    }

    td.textContent = texto; // Asigna el texto a la celda
    return td;
}

/**
 * Asigna un atributo "data-label" a una celda para soporte responsive.
 * @param {HTMLElement} td - Elemento de celda.
 * @param {string} label - Texto del data-label.
 * @returns {HTMLElement} Celda con atributo asignado.
 */
function crearDataLabel(td, label) {
    td.setAttribute("data-label", label); // Asigna el atributo para estilos adaptables
    return td;
}

/**
 * Crea un <li> con una etiqueta fuerte y un valor.
 * @param {string} label - Etiqueta descriptiva.
 * @param {string} valor - Valor asociado.
 * @returns {HTMLLIElement} Elemento de lista generado.
 */
function crearLi(label, valor) {
    let li = document.createElement("li");
    let strong = document.createElement("strong");
    strong.textContent = `${label}: `;

    li.appendChild(strong);
    li.appendChild(document.createTextNode(valor ?? "-")); // Si valor es null/undefined, usa "-"
    return li;
}

/**
 * Obtiene el token CSRF desde el <meta> correspondiente.
 * @returns {string} Token CSRF o cadena vacía.
 */
function getCSRFToken() {
    let tokenMeta = document.querySelector('meta[name="csrf-token"]');
    return tokenMeta ? tokenMeta.getAttribute("content") : ""; // Retorna el valor del token
}

/**
 * Crea un input oculto con el token CSRF.
 * @returns {HTMLInputElement} Input generado.
 */
function getHiddenToken() {
    let token = document.createElement("input");
    token.type = "hidden";
    token.name = "_token";
    token.value = getCSRFToken(); // Asigna el token como valor
    return token;
}

/**
 * Crea un input oculto con un valor personalizado (por ejemplo, ID).
 * @param {string|number} param - Valor a asignar.
 * @param {string} nameId - Nombre del input.
 * @returns {HTMLInputElement} Input oculto generado.
 */
function getHiddenId(param, nameId) {
    console.log(nameId); // Para debug
    let hiddenId = document.createElement("input");
    hiddenId.type = "hidden";
    hiddenId.name = nameId;
    hiddenId.value = param;
    return hiddenId;
}

/**
 * Aplica filtros sobre `allData` según el campo seleccionado y el input de búsqueda.
 * @param {Array<string>} campos - Lista de campos filtrables.
 * @returns {Array<Object>} Lista de resultados filtrados.
 */
function aplicarFiltro(campos) {
    let input = document.getElementById("buscarId").value.trim().toLowerCase(); // Texto del input
    if (input === "") return allData; // Si está vacío, no filtra

    let filtro = document.querySelector('input[name="filtro"]:checked'); // Filtro seleccionado
    let campo = filtro ? campos[parseInt(filtro.value) - 1] : "name"; // Campo por índice (empezando en 1)

    // Devuelve solo los elementos que incluyen el texto buscado
    return allData.filter(item => {
        let valor = item[campo];
        return valor && valor.toString().toLowerCase().includes(input);
    });
}

/**
 * Renderiza botones de paginación y resumen del rango visible.
 * @param {number} total - Total de registros.
 * @param {number} limit - Registros por página.
 */
function renderPaginationButtons(total, limit) {
    console.log(limit); // Debug

    let pagContainer = document.querySelector(".pagination-buttons");
    if (!pagContainer) return;

    // Limpia cualquier botón de paginación anterior
    while (pagContainer.firstChild) pagContainer.removeChild(pagContainer.firstChild);
    
    let totalPages = Math.ceil(total / limit); // Total de páginas
    let startIdx = paginatual * limit + 1; // Primer registro visible
    let endIdx = Math.min((paginatual + 1) * limit, total); // Último registro visible

    // Elemento que muestra "X – Y de Z"
    let summary = document.createElement("span");
    summary.classList.add("pagination-summary");
    summary.textContent = `${startIdx} – ${endIdx} de ${total}`;
    pagContainer.appendChild(summary);

    /**
     * Crea un botón de paginación.
     * @param {string} text - Texto del botón.
     * @param {number} targetPage - Página destino.
     * @param {boolean} disabled - Si debe estar deshabilitado.
     * @returns {HTMLButtonElement} Botón de navegación.
     */
    let makeBtn = (text, targetPage, disabled) => {
        let btn = document.createElement("button");
        btn.textContent = text;

        if (disabled) {
            btn.disabled = true;
        } else {
            btn.addEventListener("click", () => {
                paginatual = targetPage; // Cambia a la página objetivo

                let url = window.location.href.split("/").pop();

                if (url != "history") {
                    renderTable(currentLimit, paginatual); // Renderiza tabla estándar
                    if (url == "use" || url == "reserve") {
                        renderTableCards(currentLimit, paginatual); // Tarjetas
                    }
                } else {
                    renderActivityCards(currentLimit, paginatual); // Tarjetas de historial
                }
            });
        }

        return btn;
    };

    // Agrega los botones de navegación
    pagContainer.appendChild(makeBtn("«", 0, paginatual === 0)); // Primera página
    pagContainer.appendChild(makeBtn("‹", paginatual - 1, paginatual === 0)); // Página anterior
    pagContainer.appendChild(makeBtn("›", paginatual + 1, paginatual >= totalPages - 1)); // Página siguiente
    pagContainer.appendChild(makeBtn("»", totalPages - 1, paginatual >= totalPages - 1)); // Última página
}
