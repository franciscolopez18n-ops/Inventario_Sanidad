// Crea un <td> con el texto proporcionado
export function createTextTD(text) {
    let td = document.createElement("td");
    td.textContent = text;
    return td;
}

function stopShimmer(wrap, img) {
    img.style.opacity = "1";
    wrap.style.animation = "none";
    wrap.style.background = "none";
}

// Crea un <td> con la imagen proporcionada
export function createPublicImageTD(relativePublicImagePath) {
    let td = document.createElement("td");

    let wrap = document.createElement("div");
    wrap.classList.add("cell-img-wrap");

    let img = document.createElement("img");
    img.classList.add("cell-img");

    img.addEventListener("load", () => stopShimmer(wrap, img));
    img.addEventListener("error", () => {
        stopShimmer(wrap, img);
        let fallback = new URL('/img/no_image.jpg', window.location).href;
        if (img.src !== fallback) img.src = fallback; // Frenar bucle infinito si incluso el asset estático falla (caso extremo)
    });

    img.src = relativePublicImagePath
        ? new URL('/storage/', window.location).href + relativePublicImagePath
        : new URL('/img/no_image.jpg', window.location).href;

    wrap.appendChild(img);
    td.appendChild(wrap);

    return td;
}

// Asigna un data-label al <td> para soporte responsive
export function createDataLabel(td, label) {
    td.setAttribute("data-label", label);
    return td;
}

// Crea un <li> con una etiqueta fuerte y un valor
export function createLabeledLi(label, value) {
    let li = document.createElement("li");
    let strong = document.createElement("strong");
    
    strong.textContent = `${label}: `;

    li.appendChild(strong);
    li.appendChild(document.createTextNode(value ?? "-"));

    return li;
}

export function createHiddenInput(value, name) {
    let input = document.createElement("input");

    input.type = "hidden";
    input.name = name;
    input.value = value;

    return input;
}