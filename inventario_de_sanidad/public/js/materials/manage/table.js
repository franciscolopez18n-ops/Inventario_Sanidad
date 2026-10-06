import { PaginatedDataPresenter } from "../../components/paginatedDataPresenter.js";
import { PaginatedDataRenderer } from "../../utils/bases.js";
import { SearchBarTool } from "../../components/searchBarTool.js";
import { showConfirmDialog } from "../../components/confirmDialog.js";
import { hideLoader } from "../../components/loader.js";
import { createTextTD, createPublicImageTD } from '../../utils/elements.js';
import { createHiddenCSRFTokenInput } from '../../utils/csrf.js';

class MaterialsManagementTableRenderer extends PaginatedDataRenderer {
    render(pageData, limit) {
        const wrapper = document.getElementById("materials-management-table-wrapper");
        wrapper.style.setProperty("--rows-per-page", limit);

        // Resetea la tabla
        const tbody = document.querySelector("#materials-management-table tbody.dynamic-rows");
        tbody.replaceChildren();

        // La reconstruye
        pageData.forEach(material => tbody.appendChild(this.#buildRow(material)));
    }

    #buildRow(material) {
        const tr = document.createElement("tr");
    
        // Celdas
        tr.appendChild(createTextTD(material.name)); 
        tr.appendChild(createTextTD(material.description));
        tr.appendChild(createPublicImageTD(material.image_path));

        // Botón Editar
        const editTd = document.createElement("td");

        const editLink = document.createElement("a");
        editLink.href = `/materials/manage/edit/${material.material_id}`;
        editLink.style.cssText = "color: inherit; text-decoration: none; cursor: pointer;";

        const editIcon = document.createElement("i");
        editIcon.classList.add("fa", "fa-pencil", "interactive-table-icon");

        editLink.appendChild(editIcon);
        editTd.appendChild(editLink);
        tr.appendChild(editTd);

        // Botón Eliminar
        const deleteTd = document.createElement("td");

        const deleteBtn = document.createElement("button");
        deleteBtn.type = "submit";
        deleteBtn.style.cssText = "background: none; border: none; cursor: pointer;";
        const trashIcon = document.createElement("i");
        trashIcon.classList.add("fa", "fa-trash", "interactive-table-icon");
        deleteBtn.appendChild(trashIcon);

        const deleteForm = document.createElement("form");
        deleteForm.method = "POST";
        deleteForm.action = `/materials/manage/destroy/${material.material_id}`;

        deleteForm.appendChild(createHiddenCSRFTokenInput());
        deleteForm.appendChild(deleteBtn);
        deleteForm.addEventListener("submit", event => showConfirmDialog(event, "delete-cd"));
        deleteTd.appendChild(deleteForm);
        tr.appendChild(deleteTd);

        return tr;
    }
}

const table = new PaginatedDataPresenter({
    renderers: [new MaterialsManagementTableRenderer()],
    dataTool: new SearchBarTool()
});
table.loadFrom("materials-data");

hideLoader();