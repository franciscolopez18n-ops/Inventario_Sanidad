import { getCookieValue } from './utils/cookies.js';
import { BatchResult, removeBatchItem, addBatchItem } from './utils/batch.js';

// Nombre de la cookie donde se almacenará el lote de materiales.
const COOKIE_NAME = "materialFormBatch";
// URL base para cargar imágenes desde el almacenamiento.
const storageUrl = new URL('/storage/', window.location).href;

// Al cargar la página, se ejecuta la función inicio()
window.addEventListener("load", inicio);

// Función que se ejecuta una vez carga la página
function inicio() {
    initToggleBatch(); // Configura el botón para alternar entre el formulario y el lote
    document.form.add.addEventListener("click", (event) => withDisabled(event.target, () => getMaterialData()));
    renderBatch(); // Carga los datos de la cookie en la página, si hay
}

// Alterna la visibilidad entre el formulario y la sección del lote.
function initToggleBatch() {
    const toggleBtn = document.getElementById("toggleBatchBtn");
    const batchText = document.getElementById("batch-text"); // Para cambiar el texto
    
    const formSections = document.querySelectorAll(".material-form, .form-title, .form-group, fieldset, .form-actions");
    const batchSection = document.querySelector(".batch-section");
    
    let isBatchVisible = false;
    toggleBtn.addEventListener("click", function () {
        isBatchVisible = !isBatchVisible;

        formSections.forEach(el => el.classList.toggle("hidden", isBatchVisible)); // ocultamos-mostramos el formulario
        batchSection.classList.toggle("hidden", !isBatchVisible); // ocultamos-mostramos el lote

        batchText.textContent = (isBatchVisible) ? "Volver al formulario" : "Ver lote de materiales";
    });
}

// Dibuja el contenido del lote en la tabla.
function renderBatch() {
    let batch = getCookieValue(COOKIE_NAME);
    let tbody = document.querySelector("table tbody");

    const btnAlta = document.getElementById("btn-submit-alta");
    if (btnAlta) {
        // Se desactiva si el lote está vacío
        btnAlta.disabled = (batch.length === 0);
    }

    // Limpia el contenido anterior de la tabla.
    while (tbody.rows.length > 0) {
        tbody.deleteRow(0);
    }

    // Si hay materiales en el lote, se renderizan en la tabla.
    if (batch.length > 0) {
        for (let i = 0; i < batch.length; i++) {
            let newTr = document.createElement("tr");

            // Añadir celdas con la información del material.
            createRow(truncateString(batch[i].name, 40), newTr, "Nombre");
            createRow(truncateString(batch[i].description, 60), newTr, "Descripción");
            createRow(truncateString(batch[i].storage, 40), newTr, "Localización");
            createRow(truncateString(batch[i].units_use, 10), newTr, "Cant. Uso");
            createRow(truncateString(batch[i].min_units_use, 10), newTr, "Mín. Uso");
            createRow(truncateString(batch[i].cabinet_use, 20), newTr, "Armario Uso");
            createRow(truncateString(batch[i].shelf_use, 20), newTr, "Balda Uso");
            createRow(truncateString(batch[i].drawer_use, 20), newTr, "Cajón Uso");
            createRow(truncateString(batch[i].units_reserve, 10), newTr, "Cant. Reserva");
            createRow(truncateString(batch[i].min_units_reserve, 10), newTr, "Mín. Reserva");
            createRow(truncateString(batch[i].cabinet_reserve, 20), newTr, "Armario Reserva");
            createRow(truncateString(batch[i].shelf_reserve, 20), newTr, "Balda Reserva");

            // Imagen del material.
            let imageTd = document.createElement("td");
            let newImg = document.createElement("img");
            newImg.className = "cell-img";
            newImg.src = batch[i].image_temp ? storageUrl + batch[i].image_temp : '/img/no_image.jpg';
            newImg.alt = batch[i].name;
            imageTd.appendChild(newImg);
            newTr.appendChild(imageTd);

            // Botón de eliminación.
            let buttonTd = document.createElement("td");
            let deleteButton = document.createElement("button");
            deleteButton.style.cssText = "background: none; border: none; cursor: pointer;";
            deleteButton.dataset.id = batch[i].id;
            let iconTrash = document.createElement("i");
            iconTrash.classList.add("fa", "fa-trash");
            deleteButton.appendChild(iconTrash);

            // Se añade el evento de click al botón para eliminar.
            deleteButton.addEventListener("click", () => {
                removeBatchItem(deleteButton.dataset.id, COOKIE_NAME, renderBatch);
            });

            buttonTd.appendChild(deleteButton);
            newTr.appendChild(buttonTd);

            tbody.appendChild(newTr);
        }
    }
}

// Captura y valida los datos del formulario y añade el material al lote.
async function getMaterialData() {
    let errorsMap = {};
    let tempPath = null;
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
    if (image) {
        const validTypes = ['image/jpeg', 'image/png'];
        if (!validTypes.includes(image.type)) {
            errorsMap.image = "Solo JPG o PNG";
        } else {
            tempPath = await uploadTempImage(image);
        }
    }

    if (Object.keys(errorsMap).length > 0) {
        showInputErrors(form, errorsMap);
        return;
    }

    // Se crea un objeto con los datos del material.
    const newMaterial = {
        name: name,
        description: description,
        storage: storage,
        image_temp: tempPath,
        
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

    const result = addBatchItem(Date.now(), newMaterial, COOKIE_NAME, renderBatch);
    if (result === BatchResult.COOKIE_LIMIT) {
        showGlobalAlert("error", "El lote ha excedido su tamaño máximo.");
        return;
    }

    // Limpiar formulario.
    document.form.reset();
    document.getElementById("imgPreview").src = "";
    document.getElementById("file-name").textContent = "Ningún archivo seleccionado";

    // Mostrar mensaje de éxito.
    showGlobalAlert("success", "Material añadido al lote.");
}

// Wrapper pensado para desactivar automáticamente el botón asociado a un evento, para evitar doble envío.
async function withDisabled(button, fn) {
    button.disabled = true;
    await fn();
    button.disabled = false;
}

// Sube la imagen al servidor y devuelve la ruta temporal.
async function uploadTempImage(image) {
    let formData = new FormData();
    formData.append('image', image);

    return fetch('/materials/upload-temp', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.form._token.value
        }
    })
    .then(response => response.json())
    .then(data => {
        return data.tempPath;
    })
    .catch(error => {
        console.error('Error en la subida:', error);
        return null;
    });
}

function truncateString(text, maxLength = 50) {
    if (!text) {
        return "";
    }
    
    if (text.length > maxLength) {
        return text.substring(0, maxLength) + "...";
    } else {
        return text;
    }
}

// Crea una celda <td> en una fila con contenido y etiqueta opcional.
function createRow(content, trElement, label) {
    let td = document.createElement("td");
    td.textContent = content;
    if (label) {
        td.setAttribute("data-label", label);
    }
    trElement.appendChild(td);
}