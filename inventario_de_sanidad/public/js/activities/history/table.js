window.addEventListener("DOMContentLoaded", inicio);

async function inicio() {
    while (typeof window.ACTIVITYDATA === 'undefined') {
        await new Promise(resolve => setTimeout(resolve, 100));
    }

    allData = window.ACTIVITYDATA;
    console.log(allData);

    hideLoader();
    paginaActual = 0;
    currentLimit = parseInt(document.getElementById("regsPorPagina").value);

    renderActivityCards(currentLimit, paginaActual);

    document.getElementById("regsPorPagina").addEventListener("change", event => {
        currentLimit = parseInt(event.target.value);
        paginaActual = 0;
        renderActivityCards(currentLimit, paginaActual);
    });
}

function renderActivityCards(limit, paginaActual) {
    let container = document.querySelector("#activityCardContainer");
    if (!container) return;

    while (container.firstChild) container.removeChild(container.firstChild);

    let inicio = paginaActual * limit;
    let fin = inicio + limit;
    let datosPagina = allData.slice(inicio, fin);

    datosPagina.forEach(activity => {
        container.appendChild(crearActivityCard(activity));
    });

    renderPaginationButtons(allData.length, limit);
}

function crearActivityCard(activity) {
    let card = document.createElement("div");
    card.className = "activity-card";

    // Header con fecha
    let header = document.createElement("div");
    header.className = "activity-header";
    let fecha = new Date(activity.created_at);
    header.textContent = fecha.toLocaleDateString('es-ES') + ' ' + fecha.toLocaleTimeString('es-ES', {
        hour: '2-digit',
        minute: '2-digit'
    });
    card.appendChild(header);

    let content = document.createElement("div");

    let pDesc = document.createElement("p");
    let strong = document.createElement("strong");
    strong.textContent = "Descripción:";
    pDesc.appendChild(strong);
    pDesc.appendChild(document.createTextNode(" " + (activity.title ?? "-")));
    content.appendChild(pDesc);
    let pTeach = document.createElement("p");
    let strongTeach = document.createElement("strong");

    let isTeacher = document.querySelector(".user-role").textContent.includes("teacher");
    if (isTeacher) {
        strongTeach.textContent = "Alumno/a:";
        pTeach.appendChild(strongTeach);
        pTeach.appendChild(document.createTextNode(" " + (activity.user.first_name ?? "-") + " " + (activity.user.last_name ?? "-")));

    }else{
        strongTeach.textContent = "Profesor/a:";
        pTeach.appendChild(strongTeach);
        pTeach.appendChild(document.createTextNode(" " + (activity.teacher.first_name ?? "-") + " " + (activity.teacher.last_name ?? "-")));
    }
    content.appendChild(pTeach);

    if (!activity.materials || activity.materials.length === 0) {
        let pEmpty = document.createElement("p");
        let em = document.createElement("em");
        em.textContent = "No se usaron materiales.";
        pEmpty.appendChild(em);
        content.appendChild(pEmpty);
    } else {
        let wrapper = document.createElement("div");
        wrapper.className = "table-wrapper";

        let table = document.createElement("table");
        table.className = "table activity-table";

        let thead = document.createElement("thead");
        let trHead = document.createElement("tr");
        ["Material", "Cantidad"].forEach(text => {
            let th = document.createElement("th");
            th.textContent = text;
            trHead.appendChild(th);
        });
        thead.appendChild(trHead);
        table.appendChild(thead);

        let tbody = document.createElement("tbody");
        activity.materials.forEach(material => {
            let tr = document.createElement("tr");
            let tdMaterial = crearTD(material.name ?? "-");
            crearDataLabel(tdMaterial, "Material");
            let tdCantidad = crearTD(material.pivot.units ?? "-");
            crearDataLabel(tdCantidad, "Cantidad");
            tr.appendChild(tdMaterial);
            tr.appendChild(tdCantidad);
            tbody.appendChild(tr);
        });
        table.appendChild(tbody);

        wrapper.appendChild(table);
        content.appendChild(wrapper);
    }

    card.appendChild(content);
    return card;
}