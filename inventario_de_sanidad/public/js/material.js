// Nombre de la cookie donde se almacenará el carrito de materiales.
const COOKIE_NAME = "materialsAddBasket";
// URL base para cargar imágenes desde el almacenamiento.
const storageUrl = new URL('/storage/', window.location).href;

// Al cargar la página, se ejecuta la función inicio() con compatibilidad para navegadores antiguos.
if (document.addEventListener) {
    window.addEventListener("load",inicio)
} else if (document.attachEvent) {
    window.attachEvent("onload",inicio);
}

// Función que se ejecuta una vez carga la página.
function inicio() {
    // Configura el botón para alternar entre el formulario y el carrito.
    initToggleBasket();

    // Botón de "Añadir material".
    let addButton = document.form.add;

    // Asigna evento click al botón "Añadir".
    if (document.addEventListener) {
        addButton.addEventListener("click", getMaterialData);
    } else if (document.attachEvent) {
        addButton.attachEvent("onclick", getMaterialData);
    }

    // Muestra la cesta con los datos almacenados.
    renderBasket();
}

// Alterna la visibilidad entre el formulario y la sección del carrito.
function initToggleBasket() {
    const toggleBtn = document.getElementById("toggleBasketBtn");
    const formSections = document.querySelectorAll(".material-form, .form-title, .form-group, fieldset, .form-actions");
    const basketSection = document.querySelector(".basket-section");
    let showingBasketOnly = false;

    toggleBtn.addEventListener("click", function () {
        showingBasketOnly = !showingBasketOnly;

        formSections.forEach(el => el.classList.toggle("hidden", showingBasketOnly));

        if (basketSection) {
            basketSection.classList.toggle("hidden", !showingBasketOnly);
        }

        toggleBtn.classList.remove(showingBasketOnly ? "btn-outline" : "btn-primary");
        toggleBtn.classList.add(showingBasketOnly ? "btn-primary" : "btn-outline");
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

    // Se guarda la cookie codificada con el valor de la cesta.
    document.cookie = name + "=" + encodeURIComponent(JSON.stringify(basket)) + "; expires=" + expiration + "; path=/";
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
            createRow(basket[i].use.units, newTr, "Cant. Uso");
            createRow(basket[i].use.min_units, newTr, "Mín. Uso");
            createRow(basket[i].use.cabinet, newTr, "Armario Uso");
            createRow(basket[i].use.shelf, newTr, "Balda Uso");
            createRow(basket[i].use.drawer, newTr, "Cajón Uso");
            createRow(basket[i].reserve.units, newTr, "Cant. Reserva");
            createRow(basket[i].reserve.min_units, newTr, "Mín. Reserva");
            createRow(basket[i].reserve.cabinet, newTr, "Armario Reserva");
            createRow(basket[i].reserve.shelf, newTr, "Balda Reserva");

            // Imagen del material.
            let imageTd = document.createElement("td");
            let newImg = document.createElement("img");
            newImg.className = "cell-img";
            newImg.src = storageUrl + (basket[i].image_temp || "no_image.jpg");
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
            if (document.addEventListener) {
                newButton.addEventListener("click", deleteMaterialData);
            } else if (document.attachEvent) {
                newButton.attachEvent("onclick", deleteMaterialData);
            }

            buttonTd.appendChild(newButton);
            newTr.appendChild(buttonTd);

            tbody.appendChild(newTr);
        }

        // Se actualiza el valor del input oculto con el contenido actualizado de la cesta.
        document.getElementById(COOKIE_NAME).value = JSON.stringify(basket);
    }
}

// Captura y valida los datos del formulario y añade el material al carrito.
async function getMaterialData(event) {
    // Desactiva el botón para evitar doble envío.
    event.target.disabled = true;
    let errors = [];
    let tempPath = null;

    // Validaciones del formulario.
    let name = document.form.name.value.trim();
    if (!name) {
        errors.push("El nombre es obligatorio.");
    }

    let description = document.form.description.value.trim();
    if (!description) {
        errors.push("La descripción es obligatoria.");
    }

    let storage = document.form.storage.value;
    if (!storage) {
        errors.push("Debes seleccionar un almacenamiento.");
    }

    let units_use = document.form.units_use.value;
    if (isNaN(units_use) || units_use <= 0) {
        errors.push("La cantidad de unidades de uso debe ser un número mayor que 0.");
    }

    let min_units_use = document.form.min_units_use.value;
    if (isNaN(min_units_use) || min_units_use <= 0) {
        errors.push("La cantidad mínima de unidades de uso debe ser un número mayor que 0.");
    }

    let cabinet_use = document.form.cabinet_use.value;
    if (!cabinet_use) {
        errors.push("El armario de uso es obligatorio.");
    }

    let shelf_use = document.form.shelf_use.value;
    if (isNaN(shelf_use) || shelf_use <= 0) {
        errors.push("La balda de uso debe ser un número mayor que 0.");
    }

    let drawer = document.form.drawer.value;
    if (drawer && (isNaN(drawer) || drawer <= 0)) {
        errors.push("El cajón de uso debe ser un número mayor que 0.");
    }

    let units_reserve = document.form.units_reserve.value;
    if (isNaN(units_reserve) || units_reserve <= 0) {
        errors.push("La cantidad de unidades de reserva debe ser un número mayor que 0.");
    }

    let min_units_reserve = document.form.min_units_reserve.value;
    if (isNaN(min_units_reserve) || min_units_reserve <= 0) {
        errors.push("La cantidad mínima de unidades de reserva debe ser un número mayor que 0.");
    }

    let cabinet_reserve = document.form.cabinet_reserve.value.trim();
    if (!cabinet_reserve) {
        errors.push("El armario de reserva es obligatorio.");
    }

    let shelf_reserve = document.form.shelf_reserve.value;
    if (isNaN(shelf_reserve) || shelf_reserve <= 0) {
        errors.push("La balda de reserva debe ser un número mayor que 0.");
    }

    // Procesar imagen si existe.
    let image = document.form.image.files[0];
    if (image) {
        let validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg'];
        if (!validTypes.includes(image.type)) {
            errors.push('Formato de imagen inválido (solo JPG, PNG o GIF)');
        } else {
            tempPath = await uploadTempImage(image);
        }
    }

    if (errors.length > 0) {
        displayErrors(errors);
    } else {
        // Se crea un objeto con los datos del material.
        let newMaterial = {
            id: Date.now(),
            name: name,
            description: description,
            storage: storage,
            image_temp: tempPath,
            use: {
                units: units_use,
                min_units: min_units_use,
                cabinet: cabinet_use,
                shelf: shelf_use,
                drawer: drawer
            },
            reserve: {
                units: units_reserve,
                min_units: min_units_reserve,
                cabinet: cabinet_reserve,
                shelf: shelf_reserve,
                drawer: null
            }
        };

        // Se obtiene la cesta actual desde la cookie.
        let basket = getCookieValue(COOKIE_NAME);
        // Se añade el material a la cesta.
        basket.push(newMaterial);
        // Se guarda la cesta actualizada en la cookie.
        setCookieValue(basket, COOKIE_NAME);
        // Se actualiza la tabla visual de la cesta.
        renderBasket();

        // Limpiar formulario.
        document.form.reset();
        document.getElementById("imgPreview").src = "";
        document.getElementById("file-name").textContent = "Ningún archivo seleccionado";
    
        // Mostrar mensaje de éxito.
        const successMsg = document.getElementById("success-message");
        successMsg.textContent = "Material añadido al carrito.";
        successMsg.classList.remove("hidden");

        // Ocultarlo después de unos segundos.
        setTimeout(() => {
            successMsg.classList.add("hidden");
        }, 5000);
    }

    event.target.disabled = false;
}

// Muestra los errores como un alert.
function displayErrors(errors) {
    let message = "";

    if (errors.length > 0) {
        for (let i = 0; i < errors.length; i++) {
            message += "- " + errors[i] + "\n";
        }

        window.alert(message);
    }
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