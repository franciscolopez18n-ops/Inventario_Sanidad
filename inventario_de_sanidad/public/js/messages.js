let alertQueue = Promise.resolve();

window.addEventListener("load", () => {
    const alerts = document.querySelectorAll(".alerts-container .alert");

    alerts.forEach(alert => {
        alertQueue = alertQueue.then(() => manageAlert(alert));
    });
});

async function manageAlert(alert) {
    alert.classList.remove("hidden");

    await new Promise(resolve => setTimeout(resolve, 3000));

    alert.classList.add("hide");

    await new Promise(resolve => setTimeout(resolve, 350));

    alert.remove();
}

function showAlert(alertClass, message) {
    const container = document.querySelector(".alerts-container");
    if (!container) return;

    const alert = document.createElement("p");
    alert.className = `alert ${alertClass} hidden`;
    alert.textContent = message;

    container.appendChild(alert);

    alertQueue = alertQueue.then(() => manageAlert(alert));
}

// LIMPIAR ERRORES DE INPUTS
function clearInputErrors(form) {
    form.querySelectorAll(".input-error").forEach(input =>
        input.classList.remove("input-error")
    );

    form.querySelectorAll(".input-error-msg").forEach(error =>
        error.remove()
    );
}

// MOSTRAR ERRORES EN INPUTS 
function showInputErrors(form, errorsMap) {
    Object.keys(errorsMap).forEach(fieldName => {
        const input = form.querySelector(`[name="${fieldName}"]`);
        if (!input) return;

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
}