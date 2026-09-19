import { PaginatedDataPresenter, DataRenderer } from "../../components/paginatedDataPresenter.js";
import { SearchBarTool } from "../../components/searchBarTool.js";
import { showConfirmDialog } from "../../components/confirmDialog.js";
import { hideLoader } from "../../components/loader.js";
import { createTextTD, createPublicImageTD, createDataLabel } from '../../utils/elements.js';
import { createHiddenCSRFTokenInput } from '../../utils/csrf.js';

class MaterialsManagementTableRenderer extends DataRenderer {
    render(pageData) {
        // Resetea la tabla
        let tbody = document.querySelector("#materials-management-table tbody");
        tbody.replaceChildren();

        // La reconstruye
        pageData.forEach(material => tbody.appendChild(this.#buildRow(material)));
    }

    #buildRow(material) {
        let tr = document.createElement("tr");
    
        // Celdas
        tr.appendChild(createDataLabel(createTextTD(material.name), "Material")); 
        tr.appendChild(createDataLabel(createTextTD(material.description), "Descripción"));
        tr.appendChild(createPublicImageTD(material.image_path));

        // Botón Editar
        let editTd = document.createElement("td");

        let editLink = document.createElement("a");
        editLink.href = `/materials/manage/edit/${material.material_id}`;
        editLink.style.cssText = "color: inherit; text-decoration: none; cursor: pointer;";

        let editIcon = document.createElement("i");
        editIcon.classList.add("fa", "fa-pencil", "table-icon-interactive");

        editLink.appendChild(editIcon);
        editTd.appendChild(editLink);
        tr.appendChild(editTd);

        // Botón Eliminar
        let deleteTd = document.createElement("td");

        let deleteBtn = document.createElement("button");
        deleteBtn.type = "submit";
        deleteBtn.style.cssText = "background: none; border: none; cursor: pointer;";
        let trashIcon = document.createElement("i");
        trashIcon.classList.add("fa", "fa-trash", "table-icon-interactive");
        deleteBtn.appendChild(trashIcon);

        let deleteForm = document.createElement("form");
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