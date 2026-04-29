window.addEventListener("DOMContentLoaded", inicio);

/**
 * Función principal que inicializa la carga y renderizado de datos
 * @async
 */
async function inicio () {
    /**
     * Espera que window.USERDATA esté definido
     * @returns {Promise<void>}
     */
    while (typeof window.USERDATA === 'undefined') {
        await new Promise(resolve => setTimeout(resolve, 100));
    }
    hideLoader();

    currentLimit = 5; // Número de filas por página
    paginaActual = 0; // Página actual

    allData = window.USERDATA; // Datos cargados

    initLoad();

    renderTable(currentLimit,paginaActual);
}

/**
 * Renderiza la tabla de usuarios con paginación y filtrado
 * @param {number} limit - Número de filas por página
 * @param {number} paginaActual - Página actual a mostrar
 */
function renderTable(limit, paginaActual) {
    let tbody = document.querySelector("#tabla-usuarios tbody");
    // Limpia el tbody antes de renderizar
    while (tbody.firstChild) tbody.removeChild(tbody.firstChild);

    // Aplica filtro sobre campos indicados
    let filtrados = aplicarFiltro(["first_name", "last_name", "email", "user_type", "created_at"]);

    let inicio = paginaActual * limit; // Índice inicial para paginación
    let fin = inicio + limit;          // Índice final para paginación
    let datosPagina = filtrados.slice(inicio, fin); // Datos de la página actual

    datosPagina.forEach((usuario) => {
        let tr = document.createElement("tr");

        // Crea y añade celdas con etiquetas accesibles (label)
        tr.appendChild(crearDataLabel(crearTD(usuario.first_name),"Nombre"));
        tr.appendChild(crearDataLabel(crearTD(usuario.last_name),"Apellidos"));
        tr.appendChild(crearDataLabel(crearTD(usuario.email),"Email"));
        tr.appendChild(crearDataLabel(crearTD(usuario.user_type),"Tipo de usuario"));
        tr.appendChild(crearDataLabel(crearTD(usuario.created_at),"Fecha de alta"));

        // Columna para formulario "Generar contraseña"
        let tdAc = document.createElement("td");
        let formAc = document.createElement("form");
        formAc.method = "POST";
        formAc.action = "/users/management/password";
        formAc.id = `btn-ver-${usuario.user_id}`;

        let formToken = getHiddenToken(); // Token CSRF oculto
        let formId = getHiddenId(usuario.user_id,"user_id"); // ID oculto

        let btnAc = document.createElement("button");
        btnAc.type = "submit";
        btnAc.classList = "btn btn-primary";
        btnAc.textContent = "Generar contraseña";

        formAc.appendChild(formToken);
        formAc.appendChild(formId);
        formAc.appendChild(btnAc);
        tdAc.appendChild(formAc);

        tr.appendChild(tdAc);

        // Columna para formulario "Eliminar usuario"
        let tdDel = document.createElement("td");

        // No muestra botón eliminar para el usuario logueado
        if ((usuario.first_name + " " + usuario.last_name) != document.getElementsByClassName("user-name")[0].textContent) {
            let formDel = document.createElement("form");
            formDel.method = "POST";
            formDel.action = "/users/management/delete";
            formDel.id = `btn-delete-${usuario.user_id}`;

            let formToken = getHiddenToken(); // Token CSRF oculto
            let formId = getHiddenId(usuario.user_id,"user_id"); // ID oculto

            let btn = document.createElement("button");
            btn.type = "submit";
            btn.style.cssText = "background: none; border: none; cursor: pointer;";

            let icon = document.createElement("i");
            icon.classList.add("fa", "fa-trash");

            btn.appendChild(icon);
            formDel.appendChild(formToken);
            formDel.appendChild(formId);
            formDel.appendChild(btn);
            tdDel.appendChild(formDel);
        }

        tr.appendChild(tdDel);
        tbody.appendChild(tr);
    });

    renderPaginationButtons(filtrados.length, limit); // Renderiza botones de paginación
    rebindDynamicEvents(); // Añade eventos a formularios dinámicos
}

/**
 * Añade eventos submit para formularios dinámicos de generar contraseña y eliminar usuario
 */
function rebindDynamicEvents() {
    // Añade evento submit para confirmar acción en formularios "Generar contraseña"
    document.querySelectorAll("[id^='btn-ver-']").forEach(form => {
        form.addEventListener("submit", mostrarDialogConfirmacion);
    });

    // Añade evento submit para confirmar acción en formularios "Eliminar usuario"
    document.querySelectorAll("[id^='btn-delete-']").forEach(form => {
        form.addEventListener("submit", mostrarDialogConfirmacion);
    });
}

/**
 * Obtiene el token CSRF oculto desde meta etiqueta
 * @returns {string} Token CSRF
 */
function getCSRFToken() {
    let tokenMeta = document.querySelector('meta[name="csrf-token"]');
    return tokenMeta ? tokenMeta.getAttribute("content") : "";
}
