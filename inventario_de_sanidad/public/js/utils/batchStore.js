import { setCookieValue, getCookieValue, deleteCookie } from './cookies.js';

export const BatchResult = Object.freeze({
    OK: 0,
    DUPLICATE: 1,
    COOKIE_LIMIT: 2
});

export class BatchStore {
    #cookieName;
    #listeners = [];

    constructor(cookieName) {
        this.#cookieName = cookieName;
    }

    getAll() {
        return getCookieValue(this.#cookieName);
    }

    add(id, item) {
        let batch = this.getAll();
        if (batch.some(i => i.id === id)) return BatchResult.DUPLICATE;

        batch.push({ ...item, id });
        if (!setCookieValue(batch, this.#cookieName)) return BatchResult.COOKIE_LIMIT;

        this.#notify();
        return BatchResult.OK;
    }

    remove(id) {
        let batch = this.getAll().filter(item => item.id != id);

        if (batch.length > 0) {
            setCookieValue(batch, this.#cookieName);
        } else {
            deleteCookie(this.#cookieName);
        }

        this.#notify();
        return BatchResult.OK;
    }

    attach(...renderers) {
        this.#listeners.push(...renderers);
        this.#notify();
        return this;
    }

    #notify() {
        let batch = this.getAll();
        this.#listeners.forEach(renderer => renderer.render(batch));
    }
}