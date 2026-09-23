// Contrato opcional/documental que falla con un mensaje claro, recomendable para escribir renderers
export class DataRenderer {
    render(_pageData) {
        throw new Error(`${this.constructor.name} debe implementar render(pageData)`);
    }
}

export class DataTool {
    treatData(data) {
        return data;
    }

    initEvents() {

    }
}