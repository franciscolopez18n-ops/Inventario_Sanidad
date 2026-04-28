// Al cargar la página, se ejecuta la función inicio()
window.addEventListener("load", inicio);

// Función que se ejecuta una vez carga la página.
function inicio() {
    // Se obtiene el botón con id "addButton" para agregar materiales a la cesta.
    let addButton = document.getElementById("addButton");
    // Se obtienen todos los elementos que tengan la clase "delete"; estos serán los botones para eliminar materiales.
    let deleteButtons = document.getElementsByClassName("delete");

    // Si existe el botón para agregar.
    if (addButton) {
        // Se asignan los eventos de "click".
        // Y se recorre cada botón de eliminación para asignar el evento "click".
        addButton.addEventListener("click", addMaterialDataCookie);
        for (let i = 0; i < deleteButtons.length; i++) {
            deleteButtons[i].addEventListener("click", deleteMaterialDataCookie);
        }
    }

    // Si existe un elemento con id "materialsBasketInput", se actualiza la tabla de materiales.
    if (document.getElementById("materialsBasketInput")) {
        updateTable();
    }
}

// Función para obtener el valor de una cookie a partir de su nombre.
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

// Función para guardar el valor de la cookie "materialsBasket".
function setCookieValue(basket) {
    let dateExpiration = new Date();

    // Se define la fecha de expiración en 2 días.
    dateExpiration.setDate(dateExpiration.getDate() + 2);

    // Se obtiene la fecha en formato UTC.
    let expiration = dateExpiration.toUTCString();

    // Se guarda la cookie codificada con el valor de la cesta.
    document.cookie = "materialsBasket=" + encodeURIComponent(JSON.stringify(basket)) + "; expires=" + expiration + "; path=/";
}

// Función para actualizar la tabla de materiales que se muestra en la página.
function updateTable() {
    let tbody = document.querySelector("table tbody");

    // Obtener la cesta de materiales almacenada en la cookie.
    let basket = getCookieValue("materialsBasket");

    // Se eliminan todas las filas (excepto la cabecera) dentro del tbody.
    while (tbody.rows.length > 1) {
        tbody.deleteRow(1);
    }

    // Para cada material en la cesta, se crea una nueva fila en la tabla.
    for (let i = 0; i < basket.length; i++) {
        let newTr = document.createElement("tr");
        let nameTd = document.createElement("td");
        let unitsTd = document.createElement("td");
        let buttonTd = document.createElement("td");
        // Se crea un botón para eliminar el material de la cesta.
        let deleteButton = document.createElement("button");

        // Se asignan las clases y atributos pertinentes al botón.
        deleteButton.setAttribute("class", "btn btn-danger delete");
        deleteButton.setAttribute("data-id", basket[i].material_id);
        deleteButton.setAttribute("type", "button");
        deleteButton.textContent = "Eliminar";

        // Se añade el evento de click al botón para eliminar.
        deleteButton.addEventListener("click", deleteMaterialDataCookie);
        

        // Se añaden las celdas a la fila: nombre del material, unidades y botón de eliminación.
        buttonTd.appendChild(deleteButton);
        unitsTd.textContent = basket[i].units;
        nameTd.textContent = basket[i].name;

        newTr.appendChild(nameTd);
        newTr.appendChild(unitsTd);
        newTr.appendChild(buttonTd);
        tbody.appendChild(newTr);
    }

    // Limpiar los campos de texto de ingreso de datos del material.
    document.getElementById("materialName").value = "";
    document.getElementById("units").value = "";
    // Se actualiza el valor del input oculto con el contenido actualizado de la cesta.
    document.getElementById("materialsBasketInput").value = JSON.stringify(basket);
}

/*
 * Función para agregar datos de un material a la cesta, guardándolos en la cookie.
 * Se obtienen los datos del material a partir de los campos de ingreso y se verifica que exista en la lista de opciones.
 */
function addMaterialDataCookie() {
    // Se obtiene la lista (select) de materiales.
    let list = document.getElementById("materials");
    let options = list.getElementsByTagName("option");
    // Se obtienen el nombre y las unidades del material a agregar desde los inputs.
    let materialName = document.getElementById("materialName").value;
    let materialUnits = document.getElementById("units").value;
    let materialId;
    let next = true;
    let index = options.length - 1;

    // Se valida que se hayan proporcionado tanto nombre como unidades.
    if (materialName != "" && materialUnits != "") {
        // Se recorre la lista de opciones para encontrar el material cuyo valor coincida
        // con el nombre ingresado. Se itera de atrás hacia adelante.
        while (next || index >= 0) {
            if (options[index].value === materialName) {
                materialId = options[index].getAttribute("data-id");
                materialName = options[index].value;
                next = false;
            }
            index -= 1;
        }
    
        // Si no se encontró el material en la lista, se muestra un mensaje de error.
        if (!materialId) {
            alert("Material no encontrado");
        } else {
            // Se crea un objeto con los datos del material.
            let materialData = {
                material_id: materialId,
                name: materialName,
                units: materialUnits
            };

            // Se obtiene la cesta actual desde la cookie.
            let basket = getCookieValue("materialsBasket");
    
            // Se verifica si el material ya existe en la cesta.
            let exists = basket.some(item => item.material_id === materialId);
            if (exists) {
                alert("El material ya está añadido");
            } else {
                // Si no existe, se añade el material a la cesta.
                basket.push(materialData);
                // Se guarda la cesta actualizada en la cookie.
                setCookieValue(basket);
                // Se actualiza la tabla visual de la cesta.
                updateTable();
            }
        }
    }
}

/*
 * Función para eliminar un material de la cesta.
 * Se obtiene el ID del material a eliminar a partir del atributo "data-id" del botón,
 * se busca en la cesta y se elimina.
 */
function deleteMaterialDataCookie(event) {
    // Se obtiene el botón que disparó el evento.
    let button = event.target;
    // Se extrae el atributo "data-id" del material.
    let materialId = button.getAttribute("data-id");

    if (!materialId) {
        console.error("No se encontró material_id en el botón.");
    } else {
        // Se obtiene la cesta actual desde la cookie.
        let basket = getCookieValue("materialsBasket");
        let deleted = false;
        let index = basket.length - 1;

        // Se recorre la cesta para encontrar y eliminar el material con el id coincidente.
        while (!deleted && index >= 0) {
            if (basket[index].material_id == materialId) {
                basket.splice(index, 1);
                deleted = true;
            }
            index -= 1;
        }

        // Se busca la fila (tr) que contiene el botón presionado y se elimina.
        let row = button.closest("tr");
        if (row && row.parentNode) {
            row.parentNode.removeChild(row);
        }

        // Se actualiza la cookie con la cesta actualizada.
        setCookieValue(basket);
    }
}
