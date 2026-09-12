// Nota: este script JS podría ser un módulo importable, lo que resultaría más limpio y mantenible. Sin embargo, 'allData'  y
//      'currentLimit' son reasignados directamente por otros scripts pensados para compartir scope con este, y los módulos ES
//      no le permiten eso a un importador con las variables que exporta. Convertir esto en módulo exige ese refactor
// Nota: 'paginatual' parece un typo. Además, en los scripts de tablas que comparten scope con este script, se crea una
//      variable global implícita llamada 'paginaActual', que muy probablemente quería referirse a esta variable
// Nota: hay funciones definidas aquí que son redefinidas por otros scripts de tablas que comparten scope con este script

/*
Conclusión: esto parece realmente frágil y puede romperse en cualquier momento. Al momento de escribir esto, los scripts asociados
    (los que se cargan debajo de este en las vistas) son los siguientes:
	- /users/manage/table.js
	- /storages/manage/table.js
	- /materials/manage/table.js
	- /materials/history/summary/table.js
	- /materials/history/modifications/table.js
	- /activities/history/table.js
*/

let allData = [];
let currentLimit = 5;
let paginatual = 0;

// Inicializa los eventos de búsqueda, filtros por radio y selector de cantidad de registros por página
function initEvents() {
    // Asigna el evento de búsqueda al campo de texto
    document.getElementById("search-input").addEventListener("keyup", filterTable);

    // Asigna el evento a cada radio button de filtro
    document.getElementsByName("filter").forEach(radio => {
        radio.addEventListener("change", filterTable);
    });

    // Evento para el cambio de registros por página
    document.getElementById("rows-per-page").addEventListener("change", event => {
        currentLimit = parseInt(event.target.value); // Actualiza el límite por página
        paginatual = 0; // Reinicia a la primera página

        renderTable(currentLimit, paginatual); // Renderiza la tabla principal

        // Verifica si la página requiere vista tipo tarjetas
        let url = window.location.href.split("/").pop();
        if (url == "use" || url == "reserve") {
            renderTableCards(currentLimit, paginatual);
        }
    });
}

// Aplica el filtro y reinicia la tabla y/o tarjetas según la URL
function filterTable() {
    paginatual = 0; // Reinicia la paginación
    renderTable(currentLimit, paginatual); // Aplica filtros a la tabla

    // Determina si se deben renderizar tarjetas
    let url = window.location.href.split("/").pop();
    if (url == "use" || url == "reserve") {
        renderTableCards(currentLimit, paginatual);
    }
}

// Filtra `allData` según el dato seleccionado y el texto introducido en la barra de búsqueda
function applyFilter(fields) {
    let input = document.getElementById("search-input").value.trim().toLowerCase(); // Texto del input
    if (input === "") return allData; // Si está vacío, no filtra

    let filter = document.querySelector('input[name="filter"]:checked');
    let field = filter ? fields[parseInt(filter.value) - 1] : "name";

    // Devuelve solo los elementos que incluyen el texto buscado
    return allData.filter(item => {
        let value = item[field];
        return value && value.toString().toLowerCase().includes(input);
    });
}

// Renderiza botones de paginación y resumen del rango visible
function renderPaginationButtons(total, limit) {
    console.log(limit); // Debug

    let pagContainer = document.querySelector(".pagination-buttons");
    if (!pagContainer) return;

    // Limpia cualquier botón de paginación anterior
    while (pagContainer.firstChild) pagContainer.removeChild(pagContainer.firstChild);
    
    let totalPages = Math.ceil(total / limit); // Total de páginas
    let startIdx = paginatual * limit + 1; // Primer registro visible
    let endIdx = Math.min((paginatual + 1) * limit, total); // Último registro visible

    // Elemento que muestra "X – Y de Z"
    let summary = document.createElement("span");
    summary.classList.add("pagination-summary");
    summary.textContent = `${startIdx} – ${endIdx} de ${total}`;
    pagContainer.appendChild(summary);

    // Crea un botón de paginación
    let makeBtn = (text, targetPage, disabled) => {
        let btn = document.createElement("button");
        btn.textContent = text;

        if (disabled) {
            btn.disabled = true;
        } else {
            btn.addEventListener("click", () => {
                paginatual = targetPage; // Cambia a la página objetivo

                let url = window.location.href.split("/").pop();

                if (url != "history") {
                    renderTable(currentLimit, paginatual); // Renderiza tabla estándar
                    if (url == "use" || url == "reserve") {
                        renderTableCards(currentLimit, paginatual); // Tarjetas
                    }
                } else {
                    renderActivityCards(currentLimit, paginatual); // Tarjetas de historial
                }
            });
        }

        return btn;
    };

    // Agrega los botones de navegación
    pagContainer.appendChild(makeBtn("«", 0, paginatual === 0)); // Primera página
    pagContainer.appendChild(makeBtn("‹", paginatual - 1, paginatual === 0)); // Página anterior
    pagContainer.appendChild(makeBtn("›", paginatual + 1, paginatual >= totalPages - 1)); // Página siguiente
    pagContainer.appendChild(makeBtn("»", totalPages - 1, paginatual >= totalPages - 1)); // Última página
}