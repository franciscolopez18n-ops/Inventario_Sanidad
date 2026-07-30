import { getCookieValue } from './utils/cookies.js';
import { BatchResult, removeBatchItem, addBatchItem } from './utils/batch.js';

const COOKIE_NAME = "activityFormBatch";

// Al cargar la página, se ejecuta la función inicio()
window.addEventListener("load", inicio);

// Función que se ejecuta una vez carga la página.
function inicio() {
    document.getElementById("addButton").addEventListener("click", addMaterialDataCookie);
    updateTable(); // Carga los datos de la cookie en el formulario, si hay
}

// Función para actualizar la tabla de materiales que se muestra en la página.
function updateTable() {
    let tbody = document.querySelector("table tbody");

    // Obtener el lote de materiales almacenado en la cookie.
    let batch = getCookieValue(COOKIE_NAME);

    // Se eliminan todas las filas (excepto la cabecera) dentro del tbody.
    while (tbody.rows.length > 1) {
        tbody.deleteRow(1);
    }

    // Para cada material en el lote, se crea una nueva fila en la tabla.
    for (let i = 0; i < batch.length; i++) {
        let newTr = document.createElement("tr");
        let nameTd = document.createElement("td");
        let unitsTd = document.createElement("td");
        let buttonTd = document.createElement("td");
        // Se crea un botón para eliminar el material del lote.
        let deleteButton = document.createElement("button");

        // Se asignan las clases y atributos pertinentes al botón.
        deleteButton.setAttribute("class", "btn btn-danger delete");
        deleteButton.setAttribute("data-id", batch[i].id);
        deleteButton.setAttribute("type", "button");
        deleteButton.textContent = "Eliminar";

        // Se añade el evento de click al botón para eliminar.
        deleteButton.addEventListener("click", () => {
            removeBatchItem(deleteButton.dataset.id, COOKIE_NAME, () => {
                deleteButton.closest("tr").remove(); // Eliminar material de la tabla.
            });
        });
        
        // Se añaden las celdas a la fila: nombre del material, unidades y botón de eliminación.
        buttonTd.appendChild(deleteButton);
        unitsTd.textContent = batch[i].units;
        nameTd.textContent = batch[i].name;

        newTr.appendChild(nameTd);
        newTr.appendChild(unitsTd);
        newTr.appendChild(buttonTd);
        tbody.appendChild(newTr);
    }

    // Limpiar los campos de ingreso de datos del material.
    document.getElementById("material").selectedIndex = 0;
    document.getElementById("units").value = "";
}

/*
 * Función para agregar datos de un material al lote, guardándolos en la cookie.
 * Se obtienen los datos del material a partir de los campos de ingreso y se verifica que exista en la lista de opciones.
 */
function addMaterialDataCookie() {
    const form = document.forms[0];

    const materialName = form.material.options[form.material.selectedIndex].text;
    const materialId = form.material.value;
    const materialUnits = form.units.value;

    // Se valida que se hayan proporcionado tanto material como unidades.
    if (materialId != "" && materialUnits != "") {       
        let materialData = {
            name: materialName,
            units: materialUnits
        };

        let result = addBatchItem(materialId, materialData, COOKIE_NAME, updateTable);
        if (result === BatchResult.DUPLICATE)
            showGlobalAlert("warning", "El material ya está añadido.");
        if (result === BatchResult.COOKIE_LIMIT)
            showGlobalAlert("error", "El lote ha excedido su tamaño máximo.");
    }
}