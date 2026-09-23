import { BatchResult, BatchStore } from '../utils/batchStore.js';
import { DataRenderer } from "../utils/bases.js";
import { createTextTD } from '../utils/elements.js';

function addMaterial() {
    const form = document.forms[0];

    const materialName = form.material.options[form.material.selectedIndex].text;
    const materialId = form.material.value;
    const materialUnits = form.units.value;

    if (materialId && materialUnits > 0) {       
        let materialData = {
            name: materialName,
            units: materialUnits
        };

        let result = store.add(materialId, materialData);
        if (result === BatchResult.DUPLICATE)
            showAlert("alert-warning", "El material ya está añadido.");
        else if (result === BatchResult.COOKIE_LIMIT)
            showAlert("alert-error", "El lote ha excedido su tamaño máximo.");
        else {
            // Limpiar entrada
            document.getElementById("material").selectedIndex = 0;
            document.getElementById("units").value = "";
        }
    }
}

class CreateActivitiesBatchTableRenderer extends DataRenderer {
    #store;

    constructor(store) {
        super();
        this.#store = store;
    }

    render(batch) {
        let tbody = document.querySelector("#materials-batch-table tbody.dynamic-rows");
        tbody.replaceChildren();

        batch.forEach(material => tbody.appendChild(this.#buildRow(material)));
    }

    #buildRow(material) {
        let tr = document.createElement("tr");

        // Celdas
        tr.appendChild(createTextTD(material.name));
        tr.appendChild(createTextTD(material.units));

        // Botón Eliminar
        let deleteTd = document.createElement("td");
        let deleteBtn = document.createElement("button");

        deleteBtn.setAttribute("class", "btn btn-danger delete");
        deleteBtn.setAttribute("type", "button");
        deleteBtn.textContent = "Eliminar";
        deleteBtn.addEventListener("click", () => this.#store.remove(material.id));

        deleteTd.appendChild(deleteBtn);
        tr.appendChild(deleteTd);
        
        return tr;
    }
}

const store = new BatchStore("activityFormBatch");

store.attach(
    new CreateActivitiesBatchTableRenderer(store)
);

document.getElementById("add-material-btn").addEventListener("click", addMaterial);