import { BatchResult, BatchStore } from '../utils/batchStore.js';
import { showAlert } from '../components/alerts.js';
import { DataRenderer } from '../utils/bases.js';
import { createTextTD } from '../utils/elements.js';
import { getClientField } from '../utils/forms.js';

function addMaterial() {
    const form = document.forms[0];

    const materialSelect = getClientField(form, "material");
    const materialName = materialSelect.options[materialSelect.selectedIndex].text;
    const materialId = materialSelect.value;
    const materialUnits = getClientField(form, "units");

    if (materialId && materialUnits.value > 0) {
        const materialData = {
            name: materialName,
            units: materialUnits.value
        };

        const result = store.add(materialId, materialData);
        if (result === BatchResult.DUPLICATE)
            showAlert("alert-warning", "El material ya está añadido.");
        else if (result === BatchResult.COOKIE_LIMIT)
            showAlert("alert-error", "El lote ha excedido su tamaño máximo.");
        else {
            // Limpiar entrada
            materialSelect.selectedIndex = 0;
            materialUnits.value = "";
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
        const tbody = document.querySelector("#materials-batch-table tbody.dynamic-rows");
        tbody.replaceChildren();

        batch.forEach(material => tbody.appendChild(this.#buildRow(material)));
    }

    #buildRow(material) {
        const tr = document.createElement("tr");

        // Celdas
        tr.appendChild(createTextTD(material.name));
        tr.appendChild(createTextTD(material.units));

        // Botón Eliminar
        const deleteTd = document.createElement("td");
        const deleteBtn = document.createElement("button");

        deleteBtn.setAttribute("class", "btn btn-danger");
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