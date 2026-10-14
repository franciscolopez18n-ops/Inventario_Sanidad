// Contrato opcional/documental que falla con un mensaje claro, recomendable para escribir renderers
export class DataRenderer {
    render(_data) {
        throw new Error(`${this.constructor.name} debe implementar render(data)`);
    }
}

export class PaginatedDataRenderer {
    render(_pageData, _limit) {
        throw new Error(`${this.constructor.name} debe implementar render(pageData, limit)`);
    }
}

export class DataTool {
    treatData(data) {
        return data;
    }

    initEvents() {

    }
}