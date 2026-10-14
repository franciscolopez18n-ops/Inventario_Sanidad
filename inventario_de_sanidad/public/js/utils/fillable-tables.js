import { showConfirmDialog } from "../components/confirmDialog.js";
import { createHiddenCSRFTokenInput } from "./csrf.js";

function wrapInTd(container) {
    const td = document.createElement("td");
    td.appendChild(container);
    return td;
}

// Crea un <td> con el texto proporcionado
export function createTextTd(text) {
    const div = document.createElement("div");

    div.className = "cell-text";
    div.textContent = text;

    return wrapInTd(div);
}

function stopShimmer(wrap, img) {
    img.style.opacity = "1";
    wrap.style.animation = "none";
    wrap.style.background = "none";
}

// Crea un <td> con la imagen pública proporcionada
export function createPublicImageTd(relativePublicImagePath) {
    const wrap = document.createElement("div");
    wrap.classList.add("cell-img-wrap");

    const img = document.createElement("img");
    img.classList.add("cell-img");

    img.addEventListener("load", () => stopShimmer(wrap, img));
    img.addEventListener("error", () => {
        stopShimmer(wrap, img);
        const fallback = new URL('/img/no_image.jpg', window.location).href;
        if (img.src !== fallback) img.src = fallback; // Frenar bucle infinito si incluso el asset estático falla (caso extremo)
    });

    img.src = relativePublicImagePath
        ? new URL('/storage/', window.location).href + relativePublicImagePath
        : new URL('/img/no_image.jpg', window.location).href;

    wrap.appendChild(img);

    return wrapInTd(wrap);
}

const BUTTON_VARIANTS = {
    icon: {
        className: "interactive-table-icon-btn",
        fill(btn, content) {
            const icon = document.createElement("i");
            icon.className = `fa ${content} interactive-table-icon`;
            btn.appendChild(icon);
        },
    },
    text: {
        className: "btn",
        fill(btn, content) {
            btn.textContent = content;
        },
    },
};

export function createLinkButtonTd({ href, type, content, className = "" }) {
    if (!Object.hasOwn(BUTTON_VARIANTS, type)) {
        throw new TypeError(`createLinkButtonTd: tipo de botón no válido: ${type}`);
    }
    
    const variant = BUTTON_VARIANTS[type];

    const link = document.createElement("a");
    link.href = href;
    link.className = `${variant.className} ${className}`.trim();
    variant.fill(link, content);

    return wrapInTd(link);
}

export function createActionButtonTd({ action, type, content, dialogId = null, className = "" } = {}) {
    if (!Object.hasOwn(BUTTON_VARIANTS, type)) {
        throw new TypeError(`createActionButtonTd: tipo de botón no válido: ${type}`);
    }

    if (typeof action !== "function" && typeof action !== "string") {
        throw new TypeError("createActionButtonTd: la acción debe ser una función o una URL");
    }

    const variant = BUTTON_VARIANTS[type];

    const btn = document.createElement("button");
    btn.type = "button";
    btn.className = `${variant.className} ${className}`.trim();
    variant.fill(btn, content);

    const run = (event, fn) => {
        if (dialogId) {
            showConfirmDialog(event, dialogId, fn);
        } else {
            event.preventDefault();
            fn();
        }
    };

    let container;
    if (typeof action === "function") {
        btn.addEventListener("click", event => run(event, action));
        container = btn;

    } else {
        btn.type = "submit";

        const form = document.createElement("form");
        form.method = "POST";
        form.action = action;

        form.appendChild(createHiddenCSRFTokenInput());
        form.appendChild(btn);
        form.addEventListener("submit", event => run(event, () => form.submit()));

        container = form;
    }

    return wrapInTd(container);
}