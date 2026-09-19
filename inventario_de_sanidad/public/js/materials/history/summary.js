import { PaginatedDataPresenter, DataRenderer } from "../../components/paginatedDataPresenter.js";
import { SearchBarTool } from "../../components/searchBarTool.js";
import { ViewToggle } from "../../components/viewToggle.js";
import { hideLoader } from "../../components/loader.js";
import { createPublicImageTD, createTextTD, createDataLabel, createLabeledTextContainer } from '../../utils/elements.js';

class SummaryCardViewRenderer extends DataRenderer {
    render(pageData) {
        // Resetea la vista de tarjetas
        let view = document.querySelector("#summary-card-view");
        view.replaceChildren();

        // La reconstruye
        pageData.forEach(material => view.appendChild(this.#buildCard(material)));
    }

    #buildCard(material) {
        let card = document.createElement("div");
        card.className = "material-card";

        card.appendChild(this.#buildCardImage(material));
        card.appendChild(this.#buildCardBody(material));
        
        return card;
    }

    #buildCardImage(material) {
        let img = document.createElement("img");

        img.src = material.image_path
            ? `/storage/${material.image_path}`
            : `/img/no_image.jpg`;
        img.alt = material.name;

        return img;
    }

    #buildCardBody(material) {
        let body = document.createElement("div");
        body.className = "material-card-body";

        body.appendChild(this.#buildCardTitle(material));
        body.appendChild(this.#buildCardDescription(material));
        body.appendChild(this.#buildCardDetailsList(material));

        return body;
    }

    #buildCardTitle(material) {
        let title = document.createElement("h5");
        title.textContent = material.name;
        return title;
    }

    #buildCardDescription(material) {
        let description = document.createElement("p");
        description.textContent = material.description;
        return description;
    }

    #buildCardDetailsList(material) {
        let list = document.createElement("ul");

        list.appendChild(createLabeledTextContainer("li", "Localización", displayName(material.storage, DisplayCategory.STORAGE)));
        list.appendChild(createLabeledTextContainer("li", "Armario", material.cabinet));
        list.appendChild(createLabeledTextContainer("li", "Balda", material.shelf));
        if ('drawer' in material) list.appendChild(createLabeledTextContainer("li", "Cajón", material.drawer));
        if ('units' in material) list.appendChild(createLabeledTextContainer("li", "Unidades", material.units));
        if ('min_units' in material) list.appendChild(createLabeledTextContainer("li", "Unidades mínimas", material.min_units));

        return list;
    }
}

class SummaryTableRenderer extends DataRenderer {
    render(pageData) {
        // Resetea la tabla
        let tbody = document.querySelector("#summary-table tbody");
        tbody.replaceChildren();

        // La reconstruye
        pageData.forEach(material => tbody.appendChild(this.#buildRow(material)));
    }

    #buildRow(material) {
        let tr = document.createElement("tr");

        // Celdas
        tr.appendChild(createPublicImageTD(material.image_path));
        tr.appendChild(createDataLabel(createTextTD(material.name), "Nombre"));
        tr.appendChild(createDataLabel(createTextTD(material.description), "Descripción"));
        tr.appendChild(createDataLabel(createTextTD(displayName(material.storage, DisplayCategory.STORAGE)), "Localización"));
        tr.appendChild(createDataLabel(createTextTD(material.cabinet), "Armario"));
        tr.appendChild(createDataLabel(createTextTD(material.shelf), "Balda"));
        if ('drawer' in material) tr.appendChild(createDataLabel(createTextTD(material.drawer), "Cajón"));
        if ('units' in material) tr.appendChild(createDataLabel(createTextTD(material.units), "Unidades"));
        if ('min_units' in material) tr.appendChild(createDataLabel(createTextTD(material.min_units), "Mínimo"));

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