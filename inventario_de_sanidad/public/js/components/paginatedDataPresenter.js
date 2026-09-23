import { DataTool } from "../utils/bases.js";

export class PaginatedDataPresenter {
    #rawData = [];
    #limit = 0;
    #page = 0;
    #renderers;
    #dataTool; /* el data presenter solo acepta una herramienta de datos (opcional).
        Por el momento solo existe la barra de búsqueda. Si se quiere admitir más de uno (por ejemplo, barra de búsqueda y
        ordenador ascendente/descendente), hay que convertir esta propiedad en array y modificar la clase para que lo gestione. 
        Todos las herramientas igualmente serían DataTool */
    
    constructor({ renderers = [], dataTool = new DataTool() } = {}) {
        this.#renderers = renderers;
        this.#dataTool = dataTool;
    }

    loadFrom(elementId) {
        let el = document.getElementById(elementId);
        this.#rawData = el ? JSON.parse(el.textContent) : [];
        this.#limit = parseInt(document.getElementById("rows-per-page").value);
        this.#render();
        this.#initEvents();
        
        return this;
    }

    #render() {
        let treatedData = this.#dataTool.treatData(this.#rawData);
        let start = this.#page * this.#limit;
        let end = start + this.#limit;
        
        let pageData = treatedData.slice(start, end);

        this.#renderers.forEach(r => r.render(pageData));
        this.#renderPaginationButtons(start, end, treatedData.length);
    }

    #initEvents() {
        document.getElementById("rows-per-page")
            .addEventListener("change", e => {
                this.#limit = parseInt(e.target.value);
                this.#reset();
            });

        this.#dataTool.initEvents(this.#reset);
    }

    // Como función flecha para bind automático
    #reset = () => {
        this.#page = 0;
        this.#render();
    };

    #renderPaginationButtons(start, end, total) {
        let container = document.querySelector(".pagination-buttons");
        container.replaceChildren();

        let summary = document.createElement("span");
        summary.classList.add("pagination-summary");
        summary.textContent = `${start + 1} – ${Math.min(end, total)} de ${total}`;
        container.appendChild(summary);

        let makeBtn = (text, targetPage, isDisabled) => {
            let btn = document.createElement("button");

            btn.textContent = text;
            btn.disabled = isDisabled;
            
            if (!isDisabled) btn.addEventListener("click", () => this.#goToPage(targetPage));
            
            return btn;
        };

        let totalPages = Math.ceil(total / this.#limit);

        container.append(
            makeBtn("«", 0, this.#page === 0),
            makeBtn("‹", this.#page - 1, this.#page === 0),
            makeBtn("›", this.#page + 1, this.#page >= totalPages - 1),
            makeBtn("»", totalPages - 1, this.#page >= totalPages - 1)
        );
    }

    #goToPage(page) {
        this.#page = page;
        this.#render();
    }
}