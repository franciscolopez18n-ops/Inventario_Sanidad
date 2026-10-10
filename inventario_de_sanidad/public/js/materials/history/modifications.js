import { PaginatedDataPresenter } from "../../components/paginatedDataPresenter.js";
import { PaginatedDataRenderer } from "../../utils/bases.js";
import { SearchBarTool } from "../../components/searchBarTool.js";
import { hideLoader } from "../../components/loader.js";
import { createTextTd } from "../../utils/fillable-tables.js";
import { displayName, DisplayCategory } from "../../utils/display.js";

class ModificationsHistoryTableRenderer extends PaginatedDataRenderer {
    render(pageData, limit) {
        let wrapper = document.getElementById("modifications-history-table-wrapper");
        wrapper.style.setProperty("--rows-per-page", limit);

        // Resetea la tabla
        let tbody = document.querySelector("#modifications-history-table tbody.dynamic-rows");
        tbody.replaceChildren();

        // La reconstruye
        pageData.forEach(modification => tbody.appendChild(this.#buildRow(modification)));
    }

    #buildRow(modification) {
        let tr = document.createElement("tr");

        // Celdas
        tr.appendChild(createTextTd(modification.first_name));
        tr.appendChild(createTextTd(modification.last_name));
        tr.appendChild(createTextTd(modification.email));
        tr.appendChild(createTextTd(modification.user_type));
        tr.appendChild(createTextTd(modification.material_name));
        tr.appendChild(createTextTd(modification.units));
        tr.appendChild(createTextTd(displayName(modification.storage, DisplayCategory.STORAGE)));
        tr.appendChild(createTextTd(displayName(modification.storage_type, DisplayCategory.MODALITY)));
        tr.appendChild(createTextTd(modification.action_datetime));

        return tr;
    }
}

const table = new PaginatedDataPresenter({
    renderers: [new ModificationsHistoryTableRenderer()],
    dataTool: new SearchBarTool()
});
table.loadFrom("modifications-data");

hideLoader();