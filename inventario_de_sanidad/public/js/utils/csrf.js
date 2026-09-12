import { createHiddenInput } from './elements.js';

// Devuelve el token CSRF desde el <meta>
export function getCSRFToken() {
    let tokenMeta = document.querySelector('meta[name="csrf-token"]');
    return tokenMeta ? tokenMeta.getAttribute("content") : "";
}

// Crea un input oculto con el token CSRF
export function createHiddenCSRFTokenInput() {
    return createHiddenInput(getCSRFToken(), "_token");
}