import { PaginatedDataPresenter } from "../components/paginatedDataPresenter.js";
import { PaginatedDataRenderer } from "../utils/bases.js";
import { SearchBarTool } from "../components/searchBarTool.js";
import { hideLoader } from "../components/loader.js";
import { createTextTD, createLabeledTextContainer } from '../utils/elements.js';

class ActivitiesPanelViewRenderer extends PaginatedDataRenderer {
    #isTeacher;

    constructor(isTeacher) {
        super();
        this.#isTeacher = isTeacher;
    }

    render(pageData, limit) {
        // Resetea la vista de tarjetas
        const view = document.querySelector("#activities-panel-view");
        view.style.setProperty("--panels-per-page", limit);
        view.replaceChildren();

        // La reconstruye
        pageData.forEach(activity => view.appendChild(this.#buildPanel(activity)));
    }

    #buildPanel(activity) {
        const panel = document.createElement("div");
        panel.className = "narrow-column panel panel-fixed";

        panel.appendChild(this.#buildPanelHeader(activity));
        panel.appendChild(this.#buildPanelContent(activity));

        return panel;
    }

    #buildPanelHeader(activity) {
        const header = document.createElement("div");
        header.className = "activity-header";

        const date = new Date(activity.created_at);
        header.textContent = date.toLocaleDateString('es-ES') + ' ' + date.toLocaleTimeString('es-ES', {
            hour: '2-digit',
            minute: '2-digit'
        });

        return header;
    }

    #buildPanelContent(activity) {
        const content = document.createElement("div");
        content.className = "panel-body"

        content.appendChild(createLabeledTextContainer("p", "Título", activity.title));
        content.appendChild(createLabeledTextContainer("p", (this.#isTeacher) ? "Alumno/a" : "Profesor/a", activity.partner.full_name));
        content.appendChild(this.#buildMaterialsSection(activity));

        return content;
    }

    #buildMaterialsSection(activity) {
        if (!activity.materials || activity.materials.length === 0) {
            const emptyParagraph = document.createElement("p");
            const em = document.createElement("em");

            em.textContent = "No se usaron materiales.";
            emptyParagraph.appendChild(em);

            return emptyParagraph;
        }

        const wrapper = document.createElement("div");
        wrapper.className = "table-wrapper activity-materials-table-wrapper";
        wrapper.appendChild(this.#buildMaterialsTable(activity.materials));

        return wrapper;
    }

    #buildMaterialsTable(materials) {
        const table = document.createElement("table");

        table.className = "table activity-materials-table";
        table.appendChild(this.#buildMaterialsTableHead());
        table.appendChild(this.#buildMaterialsTableBody(materials));

        return table;
    }

    #buildMaterialsTableHead() {
        const thead = document.createElement("thead");
        const headerRow = document.createElement("tr");

        ["Material", "Cantidad"].forEach(text => {
            const th = document.createElement("th");
            th.textContent = text;
            headerRow.appendChild(th);
        });

        thead.appendChild(headerRow);
        return thead;
    }

    #buildMaterialsTableBody(materials) {
        const tbody = document.createElement("tbody");

        materials.forEach(material => {
            const row = document.createElement("tr");

            row.appendChild(createTextTD(material.name));
            row.appendChild(createTextTD(material.units));

            tbody.appendChild(row);
        });

        return tbody;
    }
}

const panels = new PaginatedDataPresenter({
    renderers: [new ActivitiesPanelViewRenderer(document.querySelector(".user-role").textContent.includes("teacher"))],
    dataTool: new SearchBarTool()
});
panels.loadFrom("activities-data");

hideLoader();