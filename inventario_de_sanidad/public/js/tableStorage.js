/**
 * Detecta cuando el DOM está listo y ejecuta la función `inicio`.
 */
window.addEventListener("DOMContentLoaded", inicio);

/**
 * Función principal que espera que los datos estén disponibles, oculta el loader,
 * inicializa la carga y renderiza la tabla con paginación.
 */
async function inicio() {
    while (typeof window.STORAGEDATA === 'undefined') {
        await new Promise(resolve => setTimeout(resolve, 100));
    }

    hideLoader();

    allData = window.STORAGEDATA;
    paginaActual = 0;

    initLoad();

    renderTable(currentLimit, paginaActual);
}

/**
 * Renderiza la tabla con los datos paginados y agrupados por material y ubicación.
 * @param {number} limit - Número de registros por página.
 * @param {number} paginaActual - Página actual.
 */
function renderTable(limit, paginaActual) {
    let tbody = document.querySelector("table tbody");

    while (tbody.firstChild) {
        tbody.removeChild(tbody.firstChild);
    }

    let filtrados = aplicarFiltro(["name"]);

    // =========================
    // AGRUPAR POR MATERIAL
    // =========================
    let grouped = {};

    filtrados.forEach(item => {
        if (!grouped[item.material_id]) {
            grouped[item.material_id] = {
                material_id: item.material_id,
                name: item.name,
                storages: []
            };
        }

        grouped[item.material_id].storages.push(item);
    });

    let groupedArray = Object.values(grouped);

    // =========================
    // PAGINACIÓN
    // =========================
    let inicio = paginaActual * limit;
    let fin = inicio + limit;
    let datosPagina = groupedArray.slice(inicio, fin);

    // =========================
    // RENDER
    // =========================
    datosPagina.forEach(material => {

        // Fila título material
        let trMaterial = document.createElement("tr");

        let tdMaterial = crearTD(material.name ?? "-");
        tdMaterial.colSpan = 8;
        tdMaterial.classList.add("material-title");

        trMaterial.appendChild(tdMaterial);
        tbody.appendChild(trMaterial);

        // Filas de storage USE
        material.storages.forEach(item => {

            let trUse = document.createElement("tr");

            trUse.appendChild(crearDataLabel(crearTD(item.storage ?? "-"), "Localización"));
            trUse.appendChild(crearDataLabel(crearTD("uso"), "Tipo"));
            trUse.appendChild(crearDataLabel(crearTD(item.units ?? "0"), "Cantidad"));
            trUse.appendChild(crearDataLabel(crearTD(item.min_units ?? "0"), "Cantidad mínima"));
            trUse.appendChild(crearDataLabel(crearTD(item.cabinet ?? "-"), "Armario"));
            trUse.appendChild(crearDataLabel(crearTD(item.shelf ?? "-"), "Balda"));
            trUse.appendChild(crearDataLabel(crearTD(item.drawer ?? "-"), "Cajón"));

            let tdAcciones = crearAccionesTd(item.material_id, item.storage);
            trUse.appendChild(tdAcciones);

            tbody.appendChild(trUse);
        });
    });

    renderPaginationButtons(groupedArray.length, limit);
}

/**
 * Crea un td con botones de acción (editar) para cada fila.
 * @param {number|string} id - ID del material.
 * @param {string} storage - Ubicación ('CAE' u 'odontology').
 * @returns {HTMLTableCellElement} td con botones.
 */
function crearAccionesTd(id, storage) {
    let tdAcciones = document.createElement("td");
    tdAcciones.classList.add("acciones");

    let btnEditar = document.createElement("button");
    btnEditar.type = "submit";
    btnEditar.style.cssText = "background: none; border: none; cursor: pointer;";

    let iconEdit = document.createElement("i");
    iconEdit.classList.add("fa", "fa-pencil");
    btnEditar.appendChild(iconEdit);

    btnEditar.onclick = () => {
        window.location.href = `/storages/update/${id}/${storage}/teacher/edit`;
    };

    tdAcciones.appendChild(btnEditar);
    return tdAcciones;
}