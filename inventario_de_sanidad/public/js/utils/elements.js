// Crea un contenedor con texto compuesto por una etiqueta fuerte y un valor (solo sirve con contenedores compatibles)
export function createLabeledTextContainer(tagName, label, value) {
    const container = document.createElement(tagName);
    const strong = document.createElement("strong");

    strong.textContent = `${label}: `;
    container.appendChild(strong);
    container.appendChild(document.createTextNode(value));

    return container;
}

export function createHiddenInput(value, name) {
    const input = document.createElement("input");

    input.type = "hidden";
    input.name = name;
    input.value = value;

    return input;
}