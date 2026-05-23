// Nombre de la cookie donde se almacenará el carrito de materiales.
const COOKIE_NAME = "materialsAddBasket";
// URL base para cargar imágenes desde el almacenamiento.
const storageUrl = new URL('/storage/', window.location).href;

// Al cargar la página, se ejecuta la función inicio()
window.addEventListener("load", inicio);

// Función que se ejecuta una vez carga la página.
function inicio() {
    // Configura el botón para alternar entre el formulario y el carrito.
    initToggleBasket();

    // Botón de "Añadir material".
    let addButton = document.form.add;

    // Escuchar evento de clic del botón de Añadir
    addButton.addEventListener("click", (event) => withDisabled(event.target, () => getMaterialData()));

    // Muestra la cesta con los datos almacenados.
    renderBasket();
}

// Alterna la visibilidad entre el formulario y la sección del carrito.
function initToggleBasket() {
    const toggleBtn = document.getElementById("toggleBasketBtn");
    const cartText = document.getElementById("cart-text"); // Para cambiar el texto
    const icon = toggleBtn.querySelector("i"); // Para cambiar el icono
    
    const formSections = document.querySelectorAll(".material-form, .form-title, .form-group, fieldset, .form-actions");
    const basketSection = document.querySelector(".basket-section");
    
    let muestraSoloCesta = false;

    // Al hacer click
    toggleBtn.addEventListener("click", function () {
        muestraSoloCesta = !muestraSoloCesta;

        formSections.forEach(el => el.classList.toggle("hidden", muestraSoloCesta)); // ocultamos-mostramos el formulario

        if (basketSection) {
            basketSection.classList.toggle("hidden", !muestraSoloCesta); // ocultamos-mostramos la cesta
        }

        // cambiamos el texto y el icono
        if (muestraSoloCesta) {
            cartText.textContent = "Volver al formulario";
            icon.className = "fa-solid fa-arrow-left"; // Icono de volver  
            toggleBtn.classList.add("active"); 
        } else {
            cartText.textContent = "Ver carrito";
            icon.className = "fa-solid fa-cart-shopping"; // Icono de carrito
            
            toggleBtn.classList.remove("active");
        }
    });
}
// Recupera el valor de una cookie y lo convierte en objeto JS.
function getCookieValue(name) {
    let cookieString = document.cookie;
    let cookies = cookieString.split(";");
    let value;
    let exist = false;
    let index = 0;

    while (!exist && index < cookies.length) {
        let cookie = cookies[index].trim();
        if (cookie.startsWith(name + '=')) {
            try {
                // Se decodifica y se parsea el valor JSON de la cookie.
                value = JSON.parse(decodeURIComponent(cookie.substring(name.length + 1)));
            } catch (error) {
                console.error("Error al parsear la cookie:", error);
                value = [];
            }
            exist = true;
        }
        index += 1;
    }

    // Si no existe la cookie o no pudo parsearse, retorna un array vacío.
    return value ?? [];
}

function setCookieValue(basket, name) {
    let dateExpiration = new Date();

    // Se define la fecha de expiración en 2 días.
    dateExpiration.setDate(dateExpiration.getDate() + 2);

    // Se obtiene la fecha en formato UTC.
    let expiration = dateExpiration.toUTCString();

    let value = encodeURIComponent(JSON.stringify(basket));
    if (value.length > 4096) {
        return false;
    }

    // Se guarda la cookie codificada con el valor de la cesta.
    document.cookie = name + "=" + value + "; expires=" + expiration + "; path=/";

    return true;
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

// Dibuja el contenido la cesta en la tabla.
function renderBasket() {
    let basket = getCookieValue(COOKIE_NAME);
    let tbody = document.querySelector("table tbody");

    const btnAlta = document.getElementById("btn-submit-alta");
    if (btnAlta) {
        // Se desactiva si la cesta está vacía (length es 0)
        btnAlta.disabled = (basket.length === 0);
    }
    // Limpia el contenido anterior de la tabla.
    while (tbody.rows.length > 0) {
        tbody.deleteRow(0);
    }

    // Si hay materiales en el carrito, se renderizan en la tabla.
    if (basket && basket.length > 0) {
        for (let i = 0; i < basket.length; i++) {
            let newTr = document.createElement("tr");

            // Añadir celdas con la información del material.
            createRow(basket[i].name, newTr, "Nombre");
            createRow(basket[i].description, newTr, "Descripción");
            createRow(basket[i].storage, newTr, "Localización");
            createRow(basket[i].units_use, newTr, "Cant. Uso");
            createRow(basket[i].min_units_use, newTr, "Mín. Uso");
            createRow(basket[i].cabinet_use, newTr, "Armario Uso");
            createRow(basket[i].shelf_use, newTr, "Balda Uso");
            createRow(basket[i].drawer_use, newTr, "Cajón Uso");
            createRow(basket[i].units_reserve, newTr, "Cant. Reserva");
            createRow(basket[i].min_units_reserve, newTr, "Mín. Reserva");
            createRow(basket[i].cabinet_reserve, newTr, "Armario Reserva");
            createRow(basket[i].shelf_reserve, newTr, "Balda Reserva");

            // Imagen del material.
            let imageTd = document.createElement("td");
            let newImg = document.createElement("img");
            newImg.className = "cell-img";
            newImg.src = basket[i].image_temp ? storageUrl + basket[i].image_temp : '/img/no_image.jpg';
            newImg.alt = basket[i].name;
            imageTd.appendChild(newImg);
            newTr.appendChild(imageTd);

            // Botón de eliminación.
            let buttonTd = document.createElement("td");
            let newButton = document.createElement("button");
            newButton.style.cssText = "background: none; border: none; cursor: pointer;";
            let iconTrash = document.createElement("i");
            iconTrash.classList.add("fa", "fa-trash");
            iconTrash.setAttribute("data-id", basket[i].id);
            newButton.appendChild(iconTrash);

            // Asignar evento al botón.
            newButton.addEventListener("click", deleteMaterialData);

            buttonTd.appendChild(newButton);
            newTr.appendChild(buttonTd);

            tbody.appendChild(newTr);
        }
    }
}

// Wrapper pensado para desactivar automáticamente el botón asociado a un evento, para evitar doble envío.
async function withDisabled(button, fn) {
    button.disabled = true;
    await fn();
    button.disabled = false;
}

// Captura y valida los datos del formulario y añade el material al carrito.
async function getMaterialData() {
    let errorsMap = {};
    let tempPath = null;
    let form = document.form;

    // Validaciones del formulario.
    let name = form.name.value.trim();
    if (!name) errorsMap.name = "El nombre es obligatorio.";

    let description = form.description.value.trim();
    if (!description) errorsMap.description = "La descripción es obligatoria.";

    let storage = form.storage.value;
    if (!storage) errorsMap.storage = "Debes seleccionar un almacenamiento.";

    let units_use = Number(form.units_use.value);
    if (form.units_use.value === "" || isNaN(units_use) || units_use < 0)
        errorsMap.units_use = "Debe ser un número ≥ 0";

    let min_units_use = Number(form.min_units_use.value);
    if (form.min_units_use.value === "" || isNaN(min_units_use) || min_units_use < 0)
        errorsMap.min_units_use = "Debe ser un número ≥ 0";




    
    let cabinet_use = form.cabinet_use.value;
    if (isNaN(cabinet_use) || cabinet_use <= 0)
        errorsMap.cabinet_use = "El armario es obligatorio";

    let shelf_use = form.shelf_use.value;
    if (isNaN(shelf_use) || shelf_use <= 0)
        errorsMap.shelf_use = "Debe ser > 0";

    let drawer_use = form.drawer_use.value;
    if (isNaN(drawer_use) || drawer_use <= 0)
        errorsMap.drawer_use = "Debe ser > 0";

    let units_reserve = Number(form.units_reserve.value);
    if (form.units_reserve.value === "" || isNaN(units_reserve) || units_reserve < 0)
        errorsMap.units_reserve = "Debe ser un número ≥ 0";

    let min_units_reserve = Number(form.min_units_reserve.value);
    if (form.min_units_reserve.value === "" || isNaN(min_units_reserve) || min_units_reserve < 0)
        errorsMap.min_units_reserve = "Debe ser un número ≥ 0";

    let cabinet_reserve = form.cabinet_reserve.value.trim();
    if (!cabinet_reserve)
        errorsMap.cabinet_reserve = "El armario es obligatorio";

    let shelf_reserve = form.shelf_reserve.value;
    if (isNaN(shelf_reserve) || shelf_reserve <= 0)
        errorsMap.shelf_reserve = "Debe ser > 0";

    // Procesar imagen si existe.
    let image = form.image.files[0];
    if (image) {
        let validTypes = ['image/jpeg', 'image/png'];
        if (!validTypes.includes(image.type)) {
            errorsMap.image = "Solo JPG o PNG";
        } else {
            tempPath = await uploadTempImage(image);
        }
    }

    let hasError = showInputErrors(form, errorsMap);

    if (hasError) {
        alertaError(); 
        return;
    }

    // Se crea un objeto con los datos del material.
    let newMaterial = {
        id: Date.now(),
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

    // Se obtiene la cesta actual desde la cookie.
    let basket = getCookieValue(COOKIE_NAME);
    // Se añade el material a la cesta.
    basket.push(newMaterial);
    // Se intenta guardar la cesta actualizada en la cookie.
    if (!setCookieValue(basket, COOKIE_NAME)) {
        displayErrors("La cesta es demasiado grande para ser almacenada.");
        return;
    }
    // Se actualiza la tabla visual de la cesta.
    renderBasket();

    // Limpiar formulario.
    document.form.reset();
    document.getElementById("imgPreview").src = "";
    document.getElementById("file-name").textContent = "Ningún archivo seleccionado";

    // Mostrar mensaje de éxito.
    showGlobalAlert("success", "Material añadido al carrito.");

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

// Elimina un material de la cesta.
function deleteMaterialData(event) {
    let button = event.target;
    let materialId = button.getAttribute("data-id");

    if (!materialId) {
        console.error("No se encontró material_id en el botón.");
        return;
    }

    let basket = getCookieValue(COOKIE_NAME);
    let deleted = false;
    let index = basket.length - 1;

    // Buscar y eliminar el material por id.
    while (!deleted && index >= 0) {
        if (basket[index].id == materialId) {
            basket.splice(index, 1);
            deleted = true;
        }

        index--;
    }

    // Se guarda la cesta actualizada en la cookie.
    if (basket.length > 0) {
        setCookieValue(basket, COOKIE_NAME);
    } else {
        deleteCookie(COOKIE_NAME);
    }

    renderBasket();
}

// Borra una cookie estableciendo una fecha de expiración en el pasado.
function deleteCookie(name) {
    document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
}
// --------------------------------------------------------------------------------------------
// Función para que los avisos desaparezcan solos

// Muestra los errores como un alert.



// --------------------------------------------------------------------------------------------
// CREO Q PUEDO ELIMINAR ESTO
