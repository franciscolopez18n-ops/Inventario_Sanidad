import { PaginatedDataPresenter } from "../components/paginatedDataPresenter.js";
import { DataRenderer } from "../utils/bases.js";
import { SearchBarTool } from "../components/searchBarTool.js";
import { hideLoader } from "../components/loader.js";
import { createTextTD, createLabeledTextContainer } from '../utils/elements.js';

class ActivitiesCardViewRenderer extends DataRenderer {
    #isTeacher;

    constructor(isTeacher) {
        super();
        this.#isTeacher = isTeacher;
    }

    render(pageData) {
        // Resetea la vista de tarjetas
        let view = document.querySelector("#activities-card-view");
        view.replaceChildren();

        // La reconstruye
        pageData.forEach(activity => view.appendChild(this.#buildCard(activity)));
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

        content.appendChild(createLabeledTextContainer("p", "Título", activity.title));
        content.appendChild(createLabeledTextContainer("p", (this.#isTeacher) ? "Alumno/a" : "Profesor/a", activity.partner.full_name));
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

            row.appendChild(createTextTD(material.name));
            row.appendChild(createTextTD(material.units));

            tbody.appendChild(row);
        });

        return tbody;
    }
}

const cards = new PaginatedDataPresenter({
    renderers: [new ActivitiesCardViewRenderer(document.querySelector(".user-role").textContent.includes("teacher"))],
    dataTool: new SearchBarTool()
});
cards.loadFrom("activities-data");

hideLoader();