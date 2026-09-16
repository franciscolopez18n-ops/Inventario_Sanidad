import { DataTool } from "../utils/dataTool.js";

export class SearchBarTool extends DataTool {
    treatData(data) {
        let input = document.getElementById("search-input").value.trim().toLowerCase();
        if (input === "") return data;

        let path = document.querySelector('input[name="filter"]:checked').value;

        return data.filter(item => {
            let values = this.#resolvePath(item, path);
            return values.some(value => value.toString().toLowerCase().includes(input));
        });
    }

    initEvents(trigger) {
        document.getElementById("search-input").addEventListener("keyup", trigger);
        document.getElementsByName("filter").forEach(radio => radio.addEventListener("change", trigger));
        
        const optionsBox = document.getElementById('filter-options');

        document.getElementById('filter-toggle').addEventListener('click', (e) => {
            e.stopPropagation();
            optionsBox.style.display = optionsBox.style.display === 'block' ? 'none' : 'block';
        });

        // Añade un manejador para el click en cualquier parte del documento
        document.addEventListener('click', (e) => {
            // Si el click no ocurrió dentro de un elemento con la clase 'dropdown-container' cerrar la opción de filtro
            if (!e.target.closest('.dropdown-container')) {
                optionsBox.style.display = 'none';
            }
        });
    }

    #resolvePath(obj, path) {
        let segments = path.split(".");
        let current = [obj];

        for (let segment of segments) {
            current = current.flatMap(value => {
                if (value == null) return [];
                if (Array.isArray(value)) {
                    return value.map(v => v?.[segment]);
                }
                return [value[segment]];
            });
        }

        return current.filter(v => v !== undefined && v !== null);
    }
}