import { PaginatedDataPresenter } from "../../components/paginatedDataPresenter.js";
import { DataRenderer } from "../../utils/bases.js";
import { SearchBarTool } from "../../components/searchBarTool.js";
import { hideLoader } from "../../components/loader.js";
import { createTextTD } from '../../utils/elements.js';

class ModificationsHistoryTableRenderer extends DataRenderer {
    render(pageData) {
        // Resetea la tabla
        let tbody = document.querySelector("#modifications-history-table tbody.dynamic-rows");
        tbody.replaceChildren();

        // La reconstruye
        pageData.forEach(modification => tbody.appendChild(this.#buildRow(modification)));
    }

    #buildRow(modification) {
        let tr = document.createElement("tr");

        // Celdas
        tr.appendChild(createTextTD(modification.first_name));
        tr.appendChild(createTextTD(modification.last_name));
        tr.appendChild(createTextTD(modification.email));
        tr.appendChild(createTextTD(modification.user_type));
        tr.appendChild(createTextTD(modification.material_name));
        tr.appendChild(createTextTD(modification.units));
        tr.appendChild(createTextTD(displayName(modification.storage, DisplayCategory.STORAGE)));
        tr.appendChild(createTextTD(displayName(modification.storage_type, DisplayCategory.MODALITY)));
        tr.appendChild(createTextTD(modification.action_datetime));

        return tr;
    }
}

const table = new PaginatedDataPresenter({
    renderers: [new ModificationsHistoryTableRenderer()],
    dataTool: new SearchBarTool()
});
table.loadFrom("modifications-data");

hideLoader();