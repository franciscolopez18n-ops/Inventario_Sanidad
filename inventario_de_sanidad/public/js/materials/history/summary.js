import { PaginatedDataPresenter } from "../../components/paginatedDataPresenter.js";
import { PaginatedDataRenderer } from "../../utils/bases.js";
import { SearchBarTool } from "../../components/searchBarTool.js";
import { ViewToggle } from "../../components/viewToggle.js";
import { hideLoader } from "../../components/loader.js";
import { createLabeledTextContainer } from "../../utils/elements.js";
import { createPublicImageTd, createTextTd } from "../../utils/fillable-tables.js";
import { displayName, DisplayCategory } from "../../utils/display.js";

class SummaryCardViewRenderer extends PaginatedDataRenderer {
    render(pageData, limit) {
        // Resetea la vista de tarjetas
        const view = document.querySelector("#summary-card-view");
        view.style.setProperty("--cards-per-page", limit);
        view.replaceChildren();

        // La reconstruye
        pageData.forEach(material => view.appendChild(this.#buildCard(material)));
    }

    #buildCard(material) {
        const card = document.createElement("div");
        card.className = "material-card";

        card.appendChild(this.#buildCardImage(material));
        card.appendChild(this.#buildCardBody(material));
        
        return card;
    }

    #buildCardImage(material) {
        const img = document.createElement("img");

        img.src = material.image_path
            ? `/storage/${material.image_path}`
            : `/img/no_image.jpg`;
        img.alt = material.name;

        return img;
    }

    #buildCardBody(material) {
        const body = document.createElement("div");
        body.className = "material-card-body";

        body.appendChild(this.#buildCardTitle(material));
        body.appendChild(this.#buildCardDescription(material));
        body.appendChild(this.#buildCardDetailsList(material));

        return body;
    }

    #buildCardTitle(material) {
        const title = document.createElement("h5");
        title.textContent = material.name;
        return title;
    }

    #buildCardDescription(material) {
        const description = document.createElement("p");
        description.textContent = material.description;
        return description;
    }

    #buildCardDetailsList(material) {
        const list = document.createElement("ul");

        list.appendChild(createLabeledTextContainer("li", "Localización", displayName(material.storage, DisplayCategory.STORAGE)));
        list.appendChild(createLabeledTextContainer("li", "Armario", material.cabinet));
        list.appendChild(createLabeledTextContainer("li", "Balda", material.shelf));
        if ('drawer' in material) list.appendChild(createLabeledTextContainer("li", "Cajón", material.drawer));
        if ('units' in material) list.appendChild(createLabeledTextContainer("li", "Unidades", material.units));
        if ('min_units' in material) list.appendChild(createLabeledTextContainer("li", "Unidades mínimas", material.min_units));

        return list;
    }
}

class SummaryTableRenderer extends PaginatedDataRenderer {
    render(pageData, limit) {
        const wrapper = document.getElementById("summary-table-view");
        wrapper.style.setProperty("--rows-per-page", limit);

        // Resetea la tabla
        const tbody = document.querySelector("#summary-table tbody.dynamic-rows");
        tbody.replaceChildren();

        // La reconstruye
        pageData.forEach(material => tbody.appendChild(this.#buildRow(material)));
    }

    #buildRow(material) {
        const tr = document.createElement("tr");

        // Celdas
        tr.appendChild(createPublicImageTd(material.image_path));
        tr.appendChild(createTextTd(material.name));
        tr.appendChild(createTextTd(material.description));
        tr.appendChild(createTextTd(displayName(material.storage, DisplayCategory.STORAGE)));
        tr.appendChild(createTextTd(material.cabinet));
        tr.appendChild(createTextTd(material.shelf));
        if ('drawer' in material) tr.appendChild(createTextTd(material.drawer));
        if ('units' in material) tr.appendChild(createTextTd(material.units));
        if ('min_units' in material) tr.appendChild(createTextTd(material.min_units));

        return tr;
    }
}

new ViewToggle([
    { button: document.getElementById("card-view-btn"), container: document.getElementById("summary-card-view") },
    { button: document.getElementById("table-view-btn"), container: document.getElementById("summary-table-view") }
]).init();

const table = new PaginatedDataPresenter({
    renderers: [
        new SummaryCardViewRenderer(),
        new SummaryTableRenderer()
    ],
    dataTool: new SearchBarTool()
});
table.loadFrom("summary-data");

hideLoader();