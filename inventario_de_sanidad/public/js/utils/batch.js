import { setCookieValue, getCookieValue, deleteCookie } from './cookies.js';

export const BatchResult = Object.freeze({
    OK: 0,
    DUPLICATE: 1,
    COOKIE_LIMIT: 2
});

/*
 * Función para eliminar un ítem de un lote
 * Se obtiene el ID del ítem a eliminar a partir de un atributo "data-id" del botón, se busca en el lote y se elimina
 */
export function removeBatchItem(itemId, cookieName, onRemoved) {
    let batch = getCookieValue(cookieName);

    for (let i = 0; i < batch.length; i++) { // Buscar y eliminar el ítem por id.
        if (batch[i].id == itemId) {
            batch.splice(i, 1);
            break;
        }
    }

    if (batch.length > 0) {
        setCookieValue(batch, cookieName);
    } else {
        deleteCookie(cookieName);
    }

    onRemoved();

    return BatchResult.OK;
}

export function addBatchItem(id, item, cookieName, onAdded) {
    let batch = getCookieValue(cookieName);
    
    // Se verifica si el ítem ya existe en el lote.
    if (batch.some(i => i.id === id)) {
        return BatchResult.DUPLICATE;
    }

    batch.push({ ...item, id });

    // Se intenta guardar el lote actualizado en la cookie.
    if (!setCookieValue(batch, cookieName)) {
        return BatchResult.COOKIE_LIMIT;
    }

    onAdded();

    return BatchResult.OK;
}