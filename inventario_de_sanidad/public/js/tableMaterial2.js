// ====================== EVENTO INICIAL ======================

/**
 * Asigna el evento de carga inicial compatible con navegadores modernos y antiguos.
 * Ejecuta la función `inicio` una vez que el DOM esté completamente cargado.
 */
if (document.addEventListener)
    window.addEventListener("DOMContentLoaded", inicio); // Moderno
else if (document.attachEvent)
    window.attachEvent("DOMContentLoaded", inicio); // IE

// ====================== FUNCIÓN INICIO ======================

/**
 * Espera a que los datos `MATERIALDATA` estén disponibles en `window`.
 * Luego oculta el loader, guarda los datos globales, inicializa eventos y renderiza la tabla inicial.
 */
async function inicio() {
    while (typeof window.MATERIALDATA === 'undefined') {
        await new Promise(resolve => setTimeout(resolve, 100)); // Espera 100ms
    }

    hideLoader(); // Oculta el loader de carga

    allData = window.MATERIALDATA; // Asigna los datos cargados
    paginaActual = 0; // Reinicia la página actual

    initLoad(); // Inicializa eventos

    renderTable(currentLimit, paginaActual); // Muestra tabla inicial
}

// ====================== RENDER TABLA ======================

/**
 * Renderiza la tabla HTML con los materiales filtrados y paginados.
 * También agrega los botones de editar y eliminar.
 * @param {number} limit - Cantidad de registros por página.
 * @param {number} paginaActual - Página actual que se desea mostrar.
 */
function renderTable(limit, paginaActual) {
    let tbody = document.querySelector("table tbody"); // Selección del <tbody>

    while (tbody.firstChild) tbody.removeChild(tbody.firstChild); // Limpia tabla

    // Aplica filtro a los datos
    let filtrados = aplicarFiltro(["name", "description", "units", "min_units", "cabinet", "shelf", "drawer"]);

    let inicio = paginaActual * limit;
    let fin = inicio + limit;
    let datosPagina = filtrados.slice(inicio, fin); // Datos paginados

    datosPagina.forEach(item => {
        let tr = document.createElement("tr");

        // Celdas con info básica
        tr.appendChild(crearDataLabel(crearTD(item.name ?? "-"), "Material")); 
        tr.appendChild(crearDataLabel(crearTD(item.description ?? "-"), "Descripción"));

        // Imagen del material
        let td = document.createElement("td");
        let img = document.createElement("img");
        img.src = item.image_path
            ? new URL('/storage/', window.location).href + item.image_path
            : new URL('/img/no_image.jpg', window.location).href;
        img.style.maxWidth = "100px";
        td.appendChild(img);
        tr.appendChild(td);

        // Botón Editar
        let tdAc = document.createElement("td");
        let formAc = document.createElement("form");
        formAc.method = "GET";
        formAc.action = `/materials/${item.material_id}/edit2`;
        formAc.id = `btn-ver-${item.material_id}`;

        let formToken = getHiddenToken(); // CSRF
        let formId = getHiddenId(item.material_id, "material_id");

        let btnAc = document.createElement("button");
        btnAc.type = "submit";
        btnAc.style.cssText = "background: none; border: none; cursor: pointer;";
        let iconEdit = document.createElement("i");
        iconEdit.classList.add("fa", "fa-pencil");
        btnAc.appendChild(iconEdit);

        formAc.appendChild(formToken);
        formAc.appendChild(formId);
        formAc.appendChild(btnAc);
        tdAc.appendChild(formAc);
        tr.appendChild(tdAc);

        // Botón Eliminar
        let tdDel = document.createElement("td");
        let formDel = document.createElement("form");
        formDel.method = "POST";
        formDel.action = `/materials/${item.material_id}/destroy`;
        formDel.id = "btn-delete-" + item.material_id;

        let token = getHiddenToken(); // CSRF
        let hiddenId = getHiddenId(item.material_id, "material_id");

        let btn = document.createElement("button");
        btn.type = "submit";
        btn.style.cssText = "background: none; border: none; cursor: pointer;";
        let iconTrash = document.createElement("i");
        iconTrash.classList.add("fa", "fa-trash");
        btn.appendChild(iconTrash);

        formDel.appendChild(token);
        formDel.appendChild(hiddenId);
        formDel.appendChild(btn);
        tdDel.appendChild(formDel);
        tr.appendChild(tdDel);

        tbody.appendChild(tr); // Añade la fila
    });

    renderPaginationButtons(filtrados.length, limit); // Renderiza paginación

    rebindDynamicEvents(); // Reactiva eventos para formularios
}

// ====================== EVENTOS DINÁMICOS ======================

/**
 * Reasigna eventos a botones de eliminación que se regeneran dinámicamente al paginar o filtrar.
 */
function rebindDynamicEvents() {
    document.querySelectorAll("[id^='btn-delete-']").forEach(form => {
        form.addEventListener("submit", mostrarDialogConfirmacion);
    });
}

// ====================== FUNCIONES AUXILIARES ======================

/**
 * Devuelve el token CSRF desde el <meta>
 * @returns {string}
 */
function getCSRFToken() {
    let tokenMeta = document.querySelector('meta[name="csrf-token"]');
    return tokenMeta ? tokenMeta.getAttribute("content") : "";
}

/**
 * Crea un campo oculto con el token CSRF
 * @returns {HTMLInputElement}
 */
function getHiddenToken() {
    let token = document.createElement("input");
    token.type = "hidden";
    token.name = "_token";
    token.value = getCSRFToken();
    return token;
}

/**
 * Crea un input hidden con el ID del elemento
 * @param {*} param - valor a guardar
 * @param {*} nameId - nombre del campo
 * @returns {HTMLInputElement}
 */
function getHiddenId(param, nameId) {
    let hiddenId = document.createElement("input");
    hiddenId.type = "hidden";
    hiddenId.name = nameId;
    hiddenId.value = param;
    return hiddenId;
}

/**
 * Crea un <td> con el texto proporcionado.
 * @param {string} texto - Texto a mostrar
 * @returns {HTMLTableCellElement}
 */
function crearTD(texto) {
    let td = document.createElement("td");
    td.textContent = texto;
    return td;
}

/**
 * Agrega un data-label al <td> para diseño responsive.
 * @param {HTMLTableCellElement} td - Celda objetivo
 * @param {string} label - Etiqueta para mobile
 * @returns {HTMLTableCellElement}
 */
function crearDataLabel(td, label) {
    td.setAttribute("data-label", label);
    return td;
}

/**
 * Filtra los datos globales de `allData` según el texto del input y el filtro seleccionado.
 * @param {string[]} campos - Lista de campos a considerar para el filtro.
 * @returns {Object[]} - Datos filtrados.
 */
function aplicarFiltro(campos) {
    let input = document.getElementById("buscarId").value.trim().toLowerCase();
    if (input === "") return allData;

    let filtro = document.querySelector('input[name="filtro"]:checked');
    let campo = filtro ? campos[parseInt(filtro.value) - 1] : "name";

    return allData.filter(item => {
        let valor = item[campo];
        return valor && valor.toString().toLowerCase().includes(input);
    });
}

/**
 * Renderiza los botones de paginación con resumen de resultados.
 * @param {number} total - Total de elementos filtrados.
 * @param {number} limit - Elementos por página.
 */
function renderPaginationButtons(total, limit) {
    let pagContainer = document.querySelector(".pagination-buttons");
    if (!pagContainer) return;

    // Limpia botones previos
    while (pagContainer.firstChild) pagContainer.removeChild(pagContainer.firstChild);

    let totalPages = Math.ceil(total / limit);
    let startIdx = paginaActual * limit + 1;
    let endIdx = Math.min((paginaActual + 1) * limit, total);

    // Resumen "1 – 5 de 34"
    let summary = document.createElement("span");
    summary.classList.add("pagination-summary");
    summary.textContent = `${startIdx} – ${endIdx} de ${total}`;
    pagContainer.appendChild(summary);

    // Helper para botones
    const makeBtn = (text, targetPage, disabled) => {
        let btn = document.createElement("button");
        btn.textContent = text;
        if (!disabled) {
            btn.addEventListener("click", () => {
                paginaActual = targetPage;
                renderTable(currentLimit, paginaActual);
            });
        } else {
            btn.disabled = true;
        }
        return btn;
    };

    pagContainer.appendChild(makeBtn("«", 0, paginaActual === 0));
    pagContainer.appendChild(makeBtn("‹", paginaActual - 1, paginaActual === 0));
    pagContainer.appendChild(makeBtn("›", paginaActual + 1, paginaActual >= totalPages - 1));
    pagContainer.appendChild(makeBtn("»", totalPages - 1, paginaActual >= totalPages - 1));
}

// ====================== EVENTOS DE BÚSQUEDA Y SELECT ======================

/**
 * Inicializa los eventos de búsqueda, filtros y paginación.
 */
function initLoad() {
    document.getElementById("buscarId").addEventListener("keyup", filtrarTabla);
    document.getElementsByName("filtro").forEach(radio => {
        radio.addEventListener("change", filtrarTabla);
    });
    document.getElementById("regsPorPagina").addEventListener("change", event => {
        currentLimit = parseInt(event.target.value);
        paginaActual = 0;
        renderTable(currentLimit, paginaActual);
    });
}

/**
 * Refiltra los datos y reinicia la tabla.
 */
function filtrarTabla() {
    paginaActual = 0;
    renderTable(currentLimit, paginaActual);
}
