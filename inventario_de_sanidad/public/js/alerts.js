// SISTEMA DE ALERTAS
window.addEventListener("load", () => {
    autoHideBackendAlerts();
});

function showGlobalAlert(type, message) {
    const container = document.querySelector(".alerts-container");
    if (!container) return;

    const alert = document.createElement("p");
    alert.className = `alert alert-${type}`;
    alert.textContent = message;

    container.appendChild(alert);

    autoHideElement(alert);
}


function autoHideBackendAlerts() {
    const alerts = document.querySelectorAll(".alerts-container .alert");

    alerts.forEach(alert => {
        autoHideElement(alert);
    });
}

// DESAPARECEN EN 3 SEGUNDOS
function autoHideElement(element) {
    setTimeout(() => {
        element.classList.add("hide");

        setTimeout(() => {
            element.remove();
        }, 350);

    }, 3000);
}

// LIMPIAR ERRORES DE INPUTS
function clearInputErrors(form) {
    const inputs = form.querySelectorAll("input, textarea, select");

    inputs.forEach(input => {
        input.classList.remove("input-error");

        const error = input.parentNode.querySelector(".input-error-msg");
        if (error) error.remove();
    });
}

// MOSTRAR ERRORES EN INPUTS 
function showInputErrors(form, errorsMap) {
    clearInputErrors(form);

    let hasError = false;

    Object.keys(errorsMap).forEach(fieldName => {
        const input = form.querySelector(`[name="${fieldName}"]`);
        if (!input) return;

        hasError = true;

        // Lo ponemos en rojo
        input.classList.add("input-error");

        // Buscamos el contenedor correcto
        const wrapper = input.closest(".field") || input.parentNode;

        // Creamos el mensaje
        const msg = document.createElement("small");
        msg.className = "input-error-msg";
        msg.textContent = errorsMap[fieldName];

        // Evitar duplicados
        const old = wrapper.querySelector(".input-error-msg");
        if (old) old.remove();

        // Insertar debajo del input
        wrapper.appendChild(msg);
    });

    return hasError;
}