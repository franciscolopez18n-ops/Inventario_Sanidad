import { PaginatedDataPresenter, DataRenderer } from "../components/paginatedDataPresenter.js";
import { SearchBarTool } from "../components/searchBarTool.js";
import { createTextTD, createDataLabel, createLabeledTextContainer } from '../utils/elements.js';

class ActivitiesCardRenderer extends DataRenderer {
    render(pageData) {
        // Resetea el contenedor
        let container = document.querySelector("#activities-card-container");
        container.replaceChildren();

        // Lo reconstruye
        pageData.forEach(activity => container.appendChild(this.#buildCard(activity)));
    }

    #buildCard(activity) {
        let card = document.createElement("div");
        card.className = "activity-card";

        card.appendChild(this.#buildCardHeader(activity));
        card.appendChild(this.#buildCardContent(activity));

        return card;
    }

    #buildCardHeader(activity) {
        let header = document.createElement("div");
        header.className = "activity-header";

        let date = new Date(activity.created_at);
        header.textContent = date.toLocaleDateString('es-ES') + ' ' + date.toLocaleTimeString('es-ES', {
            hour: '2-digit',
            minute: '2-digit'
        });

        return header;
    }

    #buildCardContent(activity) {
        let content = document.createElement("div");
        let isTeacher = document.querySelector(".user-role").textContent.includes("teacher");

        content.appendChild(createLabeledTextContainer("p", "Título", activity.title));
        content.appendChild(createLabeledTextContainer("p", (isTeacher) ? "Alumno/a" : "Profesor/a", activity.partner.full_name));
        content.appendChild(this.#buildMaterialsSection(activity));

        return content;
    }

    #buildMaterialsSection(activity) {
        if (!activity.materials || activity.materials.length === 0) {
            let emptyParagraph = document.createElement("p");
            let em = document.createElement("em");

            em.textContent = "No se usaron materiales.";
            emptyParagraph.appendChild(em);

            return emptyParagraph;
        }

        let wrapper = document.createElement("div");
        wrapper.className = "table-wrapper";
        wrapper.appendChild(this.#buildMaterialsTable(activity.materials));

        return wrapper;
    }

    #buildMaterialsTable(materials) {
        let table = document.createElement("table");

        table.className = "table activity-table";
        table.appendChild(this.#buildMaterialsTableHead());
        table.appendChild(this.#buildMaterialsTableBody(materials));

        return table;
    }

    #buildMaterialsTableHead() {
        let thead = document.createElement("thead");
        let headerRow = document.createElement("tr");

        ["Material", "Cantidad"].forEach(text => {
            let th = document.createElement("th");
            th.textContent = text;
            headerRow.appendChild(th);
        });

        thead.appendChild(headerRow);
        return thead;
    }

    #buildMaterialsTableBody(materials) {
        let tbody = document.createElement("tbody");

        materials.forEach(material => {
            let row = document.createElement("tr");

            row.appendChild(createDataLabel(createTextTD(material.name), "Material"));
            row.appendChild(createDataLabel(createTextTD(material.units), "Cantidad"));

            tbody.appendChild(row);
        });

        return tbody;
    }
}

const cards = new PaginatedDataPresenter({
    dataTool: new SearchBarTool()
});
cards.addRenderer(new ActivitiesCardRenderer()).loadFrom("activities-data");