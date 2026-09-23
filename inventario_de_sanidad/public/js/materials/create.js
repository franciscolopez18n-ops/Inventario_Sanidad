import { BatchResult, BatchStore } from '../utils/batchStore.js';
import { DataRenderer } from "../utils/bases.js";
import { ViewToggle } from "../components/viewToggle.js";
import { hideLoader } from "../components/loader.js";
import { createTextTD, createPublicImageTD } from '../utils/elements.js';
import { clearInputErrors, showInputErrors, withSubmitLock } from '../utils/forms.js';

// Captura y valida los datos del formulario y añade el material al lote.
async function addMaterial() {
    let errorsMap = {};
    const form = document.form;

    clearInputErrors(form); // Limpiar posibles errores anteriores.

    // Validaciones del formulario.
    const name = form.name.value.trim();
    if (!name)
        errorsMap.name = "El nombre es obligatorio.";
    else if (name.length > 60)
        errorsMap.name = "El nombre no puede superar 60 caracteres.";

    const description = form.description.value.trim();
    if (!description)
        errorsMap.description = "La descripción es obligatoria.";
    else if (description.length > 255)
        errorsMap.description = "La descripción no puede superar 255 caracteres.";
    
    const storage = form.storage.value;
    if (!storage) errorsMap.storage = "Debes seleccionar un almacenamiento.";

    const units_use = form.units_use.value;
    if (units_use === "" || isNaN(units_use) || units_use < 0)
        errorsMap.units_use = "Debe ser ≥ 0";

    const min_units_use = form.min_units_use.value;
    if (min_units_use === "" || isNaN(min_units_use) || min_units_use < 0)
        errorsMap.min_units_use = "Debe ser ≥ 0";

    const cabinet_use = form.cabinet_use.value;
    if (cabinet_use === "" || isNaN(cabinet_use) || cabinet_use <= 0)
        errorsMap.cabinet_use = "Debe ser > 0";

    const shelf_use = form.shelf_use.value;
    if (shelf_use === "" || isNaN(shelf_use) || shelf_use <= 0)
        errorsMap.shelf_use = "Debe ser > 0";

    const drawer_use = form.drawer_use.value;
    if (drawer_use === "" || isNaN(drawer_use) || drawer_use <= 0)
        errorsMap.drawer_use = "Debe ser > 0";

    const units_reserve = form.units_reserve.value;
    if (units_reserve === "" || isNaN(units_reserve) || units_reserve < 0)
        errorsMap.units_reserve = "Debe ser ≥ 0";

    const min_units_reserve = form.min_units_reserve.value;
    if (min_units_reserve === "" || isNaN(min_units_reserve) || min_units_reserve < 0)
        errorsMap.min_units_reserve = "Debe ser ≥ 0";

    const cabinet_reserve = form.cabinet_reserve.value.trim();
    if (!cabinet_reserve)
        errorsMap.cabinet_reserve = "El armario de reserva es obligatorio";
    else if (cabinet_reserve.length > 30)
        errorsMap.cabinet_reserve = "El armario de reserva no puede superar 30 caracteres.";

    const shelf_reserve = form.shelf_reserve.value;
    if (shelf_reserve === "" || isNaN(shelf_reserve) || shelf_reserve <= 0)
        errorsMap.shelf_reserve = "Debe ser > 0";

    // Procesar imagen si existe.
    const image = form.image.files[0];
    if (image && !['image/jpeg', 'image/png'].includes(image.type)) {
        errorsMap.image = "Solo JPG o PNG";
    }

    if (Object.keys(errorsMap).length > 0) {
        showInputErrors(form, errorsMap);
        return;
    }

    // Si hay imagen y es válida, subirla temporalmente al servidor
    let tempPath = null;
    if (image) {
        tempPath = await uploadTempImage(image);
        
        if (!tempPath) {
            showAlert("alert-error", "Error al procesar la imagen en el servidor.");
            return;
        }
    }

    // Se crea un objeto con los datos del material.
    const newMaterial = {
        name: name,
        description: description,
        storage: storage,
        temp_image_path: tempPath,
        
        units_use: units_use,
        min_units_use: min_units_use,
        cabinet_use: cabinet_use,
        shelf_use: shelf_use,
        drawer_use: drawer_use,

        units_reserve: units_reserve,
        min_units_reserve: min_units_reserve,
        cabinet_reserve: cabinet_reserve,
        shelf_reserve: shelf_reserve
    };

    let result = store.add(Date.now(), newMaterial);
    if (result === BatchResult.DUPLICATE) {
        showAlert("alert-warning", "El material ya está añadido.");
        return;
    } else if (result === BatchResult.COOKIE_LIMIT) {
        showAlert("alert-error", "El lote ha excedido su tamaño máximo.");
        return;
    }

    // Limpiar formulario.
    document.form.reset();
    const elements = getImagePreviewElements(form.image);
    clearImageSelection(elements.input, elements.imgPreview, elements.removeFlag, elements.fileNameDisplay);

    // Mostrar mensaje de éxito.
    showAlert("alert-success", "Material añadido al lote.");
}

// Sube la imagen al servidor y devuelve la ruta temporal.
async function uploadTempImage(image) {
    let formData = new FormData();
    formData.append('image', image);

    try {
        const response = await fetch('/materials/upload-temp', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.form._token.value
            }
        });
        
        const data = await response.json();
        return data.tempPath;
    } catch (error) {
        console.error('Error en la subida:', error);
        return null;
    }
}

class CreateMaterialsBatchTableRenderer extends DataRenderer {
    #store;

    constructor(store) {
        super();
        this.#store = store;
    }

    render(batch) {
        let tbody = document.querySelector("#materials-batch-table tbody.dynamic-rows");
        tbody.replaceChildren();

        batch.forEach(material => tbody.appendChild(this.#buildRow(material)));
    }

    #buildRow(material) {
        let tr = document.createElement("tr");

        // Celdas
        tr.appendChild(createTextTD(material.name));
        tr.appendChild(createTextTD(material.description));
        tr.appendChild(createTextTD(material.storage));
        tr.appendChild(createTextTD(material.units_use));
        tr.appendChild(createTextTD(material.min_units_use));
        tr.appendChild(createTextTD(material.cabinet_use));
        tr.appendChild(createTextTD(material.shelf_use));
        tr.appendChild(createTextTD(material.drawer_use));
        tr.appendChild(createTextTD(material.units_reserve));
        tr.appendChild(createTextTD(material.min_units_reserve));
        tr.appendChild(createTextTD(material.cabinet_reserve));
        tr.appendChild(createTextTD(material.shelf_reserve));
        tr.appendChild(createPublicImageTD(material.temp_image_path));

        // Botón Eliminar
        let deleteTd = document.createElement("td");

        let deleteBtn = document.createElement("button");
        deleteBtn.style.cssText = "background: none; border: none; cursor: pointer;";
        let trashIcon = document.createElement("i");
        trashIcon.classList.add("fa", "fa-trash", "table-icon-interactive");
        deleteBtn.addEventListener("click", () => this.#store.remove(material.id));
        deleteBtn.appendChild(trashIcon);

        deleteTd.appendChild(deleteBtn);
        tr.appendChild(deleteTd);

        return tr;
    }
}

class SubmitButtonToggler extends DataRenderer {
    #button;

    constructor(button) {
        super();
        this.#button = button;
    }

    render(batch) {
        this.#button.disabled = batch.length === 0;
    }
}

class VisibilityToggler extends DataRenderer {
    #viewToggler;
    #container;

    constructor(viewToggler, container) {
        super();
        this.#viewToggler = viewToggler;
        this.#container = container;
    }

    render(batch) {
        if (batch.length === 0) {
            this.#viewToggler.activate(0);
            this.#container.classList.add("hidden");
        } else {
            this.#container.classList.remove("hidden");
        }
    }
}

const store = new BatchStore("materialFormBatch");

const viewToggle = new ViewToggle([
    { button: document.getElementById("form-view-btn"), container: document.querySelector(".material-form") },
    { button: document.getElementById("batch-view-btn"), container: document.querySelector(".batch-section") }
]).init();

store.attach(
    new CreateMaterialsBatchTableRenderer(store),
    new SubmitButtonToggler(document.getElementById("btn-submit-create")),
    new VisibilityToggler(viewToggle, document.querySelector(".view-toggle"))
);

document.form.add.addEventListener("click", (event) => withSubmitLock(event.target, () => addMaterial()));

hideLoader();