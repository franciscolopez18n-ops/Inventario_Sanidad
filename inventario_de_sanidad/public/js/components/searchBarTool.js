import { DataTool } from "../utils/dataTool.js";

export class SearchBarTool extends DataTool {
    treatData(data) {
        let input = document.getElementById("search-input").value.trim().toLowerCase();
        if (input === "") return data;

        let field = document.querySelector('input[name="filter"]:checked').value;

        return data.filter(item => {
            let value = item[field];
            return value && value.toString().toLowerCase().includes(input);
        });
    }

    initEvents(trigger) {
        document.getElementById("search-input").addEventListener("keyup", trigger);
        document.getElementsByName("filter").forEach(radio => radio.addEventListener("change", trigger));
    }
}