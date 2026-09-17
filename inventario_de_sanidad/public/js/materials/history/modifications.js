import { PaginatedDataPresenter, DataRenderer } from "../../components/paginatedDataPresenter.js";
import { SearchBarTool } from "../../components/searchBarTool.js";
import { createTextTD, createDataLabel } from '../../utils/elements.js';

class ModificationsHistoryTableRenderer extends DataRenderer {
    render(pageData) {
        // Resetea la tabla
        let tbody = document.querySelector("#modifications-history-table tbody");
        tbody.replaceChildren();

        // La reconstruye
        pageData.forEach(modification => tbody.appendChild(this.#buildRow(modification)));
    }

    #buildRow(modification) {
        let tr = document.createElement("tr");

        // Celdas
        tr.appendChild(createDataLabel(createTextTD(modification.first_name), "Nombre"));
        tr.appendChild(createDataLabel(createTextTD(modification.last_name), "Apellidos"));
        tr.appendChild(createDataLabel(createTextTD(modification.email), "Email"));
        tr.appendChild(createDataLabel(createTextTD(modification.user_type), "Tipo de usuario"));
        tr.appendChild(createDataLabel(createTextTD(modification.material_name), "Material"));
        tr.appendChild(createDataLabel(createTextTD(modification.units), "Unidades modificadas"));
        tr.appendChild(createDataLabel(createTextTD(displayName(modification.storage, DisplayCategory.STORAGE)), "Localización"));
        tr.appendChild(createDataLabel(createTextTD(displayName(modification.storage_type, DisplayCategory.MODALITY)), "Tipo de almacenamiento"));
        tr.appendChild(createDataLabel(createTextTD(modification.action_datetime), "Fecha de modificación"));

        return tr;
    }
}

const table = new PaginatedDataPresenter({
    dataTool: new SearchBarTool()
});
table.addRenderer(new ModificationsHistoryTableRenderer()).loadFrom("modifications-data");