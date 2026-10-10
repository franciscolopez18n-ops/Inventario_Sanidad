import { PaginatedDataPresenter } from "../../components/paginatedDataPresenter.js";
import { PaginatedDataRenderer } from "../../utils/bases.js";
import { SearchBarTool } from "../../components/searchBarTool.js";
import { showConfirmDialog } from "../../components/confirmDialog.js";
import { hideLoader } from "../../components/loader.js";
import { createTextTd, createActionButtonTd } from "../../utils/fillable-tables.js";
import { createHiddenCSRFTokenInput } from "../../utils/csrf.js";

class UsersManagementTableRenderer extends PaginatedDataRenderer {
    render(pageData, limit) {
        const wrapper = document.getElementById("users-management-table-wrapper");
        wrapper.style.setProperty("--rows-per-page", limit);

        // Resetea la tabla
        const tbody = document.querySelector("#users-management-table tbody.dynamic-rows");
        tbody.replaceChildren();

        // La reconstruye
        pageData.forEach(user => tbody.appendChild(this.#buildRow(user)));
    }

    #buildRow(user) {
        const tr = document.createElement("tr");

        tr.appendChild(createTextTd(user.first_name));
        tr.appendChild(createTextTd(user.last_name));
        tr.appendChild(createTextTd(user.email));
        tr.appendChild(createTextTd(user.user_type));
        tr.appendChild(createTextTd(user.created_at));
        tr.appendChild(createActionButtonTd({
            action: `/users/manage/change-password/${user.user_id}`,
            type: "text",
            content: "Generar contraseña",
            dialogId: "change-password-cd",
            className: "btn-primary"
        }));
        tr.appendChild(
            ((user.first_name + " " + user.last_name) !== document.getElementsByClassName("user-name")[0].textContent)
                ? createActionButtonTd({
                    action: `/users/manage/destroy/${user.user_id}`,
                    type: "icon",
                    content: "fa-trash",
                    dialogId: "delete-cd"
                })
                : document.createElement("td")
        );

        return tr;
    }
}

const table = new PaginatedDataPresenter({
    renderers: [new UsersManagementTableRenderer()],
    dataTool: new SearchBarTool()
});
table.loadFrom("users-data");

hideLoader();