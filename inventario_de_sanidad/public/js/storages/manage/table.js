import { createTextTD, createDataLabel } from '../../utils/elements.js';

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

    initEvents();

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

    let filtrados = applyFilter(["name"]);

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

        let tdMaterial = createTextTD(material.name ?? "-");
        tdMaterial.colSpan = 8;
        tdMaterial.classList.add("material-title");

        trMaterial.appendChild(tdMaterial);
        tbody.appendChild(trMaterial);

        // Filas de storage USE
        material.storages.forEach(item => {

            let trUse = document.createElement("tr");

            trUse.appendChild(createDataLabel(createTextTD(item.storage ?? "-"), "Localización"));
            trUse.appendChild(createDataLabel(createTextTD("uso"), "Tipo"));
            trUse.appendChild(createDataLabel(createTextTD(item.units ?? "0"), "Cantidad"));
            trUse.appendChild(createDataLabel(createTextTD(item.min_units ?? "0"), "Cantidad mínima"));
            trUse.appendChild(createDataLabel(createTextTD(item.cabinet ?? "-"), "Armario"));
            trUse.appendChild(createDataLabel(createTextTD(item.shelf ?? "-"), "Balda"));
            trUse.appendChild(createDataLabel(createTextTD(item.drawer ?? "-"), "Cajón"));

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
    iconEdit.classList.add("fa", "fa-pencil", "table-icon-interactive");
    btnEditar.appendChild(iconEdit);

    btnEditar.onclick = () => {
        window.location.href = `/storages/manage/${id}/${storage}/subtract`;
    };

    tdAcciones.appendChild(btnEditar);
    return tdAcciones;
}