import { PaginatedDataPresenter } from "../../components/paginatedDataPresenter.js";
import { PaginatedDataRenderer } from "../../utils/bases.js";
import { SearchBarTool } from "../../components/searchBarTool.js";
import { hideLoader } from "../../components/loader.js";
import { createTextTd, createPublicImageTd, createLinkButtonTd, createActionButtonTd } from "../../utils/fillable-tables.js";
import { createHiddenCSRFTokenInput } from "../../utils/csrf.js";

class MaterialsManagementTableRenderer extends PaginatedDataRenderer {
    #isAdmin;

    constructor(isAdmin) {
        super();
        this.#isAdmin = isAdmin;
    }

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
    
        tr.appendChild(createTextTd(material.name)); 
        tr.appendChild(createTextTd(material.description));
        tr.appendChild(createPublicImageTd(material.image_path));
        tr.appendChild(createLinkButtonTd({
            href: `/materials/manage/edit/${material.material_id}`,
            type: "icon",
            content: "fa-pencil" 
        }));
        if (this.#isAdmin) {
            tr.appendChild(createActionButtonTd({
                action: `/materials/manage/destroy/${material.material_id}`,
                type: "icon",
                content: "fa-trash",
                dialogId: "delete-cd"
            }));
        }

        return tr;
    }
}

const table = new PaginatedDataPresenter({
    renderers: [new MaterialsManagementTableRenderer(document.querySelector(".user-role").textContent.includes("admin"))],
    dataTool: new SearchBarTool()
});
table.loadFrom("materials-data");

hideLoader();