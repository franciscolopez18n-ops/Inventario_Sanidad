import { PaginatedDataPresenter, TableRenderer } from "../../components/paginatedDataPresenter.js";
import { SearchBarTool } from "../../components/searchBarTool.js";
import { createTextTD, createPublicImageTD, createDataLabel } from '../../utils/elements.js';
import { createHiddenCSRFTokenInput } from '../../utils/csrf.js';

class MaterialsTableRenderer extends TableRenderer {
    render(pageData) {
        // Resetea la tabla
        let tbody = document.querySelector("table tbody");
        tbody.replaceChildren();

        // La reconstruye
        pageData.forEach(item => tbody.appendChild(this.#buildRow(item)));
    }

    #buildRow(item) {
        let tr = document.createElement("tr");
    
        // Celdas
        tr.appendChild(createDataLabel(createTextTD(item.name ?? "-"), "Material")); 
        tr.appendChild(createDataLabel(createTextTD(item.description ?? "-"), "Descripción"));
        tr.appendChild(createPublicImageTD(item.image_path));

        // Botón Editar
        let editTd = document.createElement("td");

        let editLink = document.createElement("a");
        editLink.href = `/materials/manage/edit/${item.material_id}`;
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
        deleteForm.action = `/materials/manage/destroy/${item.material_id}`;
        deleteForm.id = "btn-delete-" + item.material_id;

        deleteForm.appendChild(createHiddenCSRFTokenInput());
        deleteForm.appendChild(deleteBtn);
        deleteForm.addEventListener("submit", showConfirmDialog);
        deleteTd.appendChild(deleteForm);
        tr.appendChild(deleteTd);

        return tr;
    }
}

const table = new PaginatedDataPresenter({
    dataTool: new SearchBarTool()
});
table.addRenderer(new MaterialsTableRenderer()).loadFrom("materials-data");