import { PaginatedDataPresenter, DataRenderer } from "../../components/paginatedDataPresenter.js";
import { SearchBarTool } from "../../components/searchBarTool.js";
import { showConfirmDialog } from "../../components/confirmDialog.js";
import { createTextTD, createDataLabel } from '../../utils/elements.js';
import { createHiddenCSRFTokenInput } from '../../utils/csrf.js';

class UsersManagementTableRenderer extends DataRenderer {
    render(pageData) {
        // Resetea la tabla
        let tbody = document.querySelector("#users-management-table tbody");
        tbody.replaceChildren();

        // La reconstruye
        pageData.forEach(user => tbody.appendChild(this.#buildRow(user)));
    }

    #buildRow(user) {
        let tr = document.createElement("tr");

        // Celdas
        tr.appendChild(createDataLabel(createTextTD(user.first_name), "Nombre"));
        tr.appendChild(createDataLabel(createTextTD(user.last_name), "Apellidos"));
        tr.appendChild(createDataLabel(createTextTD(user.email), "Email"));
        tr.appendChild(createDataLabel(createTextTD(user.user_type), "Tipo de usuario"));
        tr.appendChild(createDataLabel(createTextTD(user.created_at), "Fecha de alta"));

        // Botón Generar contraseña
        let changePasswordTd = document.createElement("td");

        let changePasswordBtn = document.createElement("button");
        changePasswordBtn.type = "submit";
        changePasswordBtn.classList = "btn btn-primary";
        changePasswordBtn.textContent = "Generar contraseña";

        let changePasswordForm = document.createElement("form");
        changePasswordForm.method = "POST";
        changePasswordForm.action = `/users/manage/change-password/${user.user_id}`;

        changePasswordForm.appendChild(createHiddenCSRFTokenInput());
        changePasswordForm.appendChild(changePasswordBtn);
        changePasswordForm.addEventListener("submit", event => showConfirmDialog(event, "change-password-cd"));
        changePasswordTd.appendChild(changePasswordForm);
        tr.appendChild(changePasswordTd);

        // Botón Eliminar
        let deleteTd = document.createElement("td");

        // No muestra el botón para el usuario logueado
        if ((user.first_name + " " + user.last_name) !== document.getElementsByClassName("user-name")[0].textContent) {
            let deleteBtn = document.createElement("button");
            deleteBtn.type = "submit";
            deleteBtn.style.cssText = "background: none; border: none; cursor: pointer;";
            let trashIcon = document.createElement("i");
            trashIcon.classList.add("fa", "fa-trash", "table-icon-interactive");
            deleteBtn.appendChild(trashIcon);

            let deleteForm = document.createElement("form");
            deleteForm.method = "POST";
            deleteForm.action = `/users/manage/destroy/${user.user_id}`;

            deleteForm.appendChild(createHiddenCSRFTokenInput());
            deleteForm.appendChild(deleteBtn);
            deleteForm.addEventListener("submit", event => showConfirmDialog(event, "delete-cd"));
            deleteTd.appendChild(deleteForm);
        }

        tr.appendChild(deleteTd);

        return tr;
    }
}

const table = new PaginatedDataPresenter({
    dataTool: new SearchBarTool()
});
table.addRenderer(new UsersManagementTableRenderer()).loadFrom("users-data");