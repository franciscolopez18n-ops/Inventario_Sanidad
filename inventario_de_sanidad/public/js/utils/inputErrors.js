export function clearInputErrors(form) {
    form.querySelectorAll(".input-error").forEach(input =>
        input.classList.remove("input-error")
    );

    form.querySelectorAll(".input-error-msg").forEach(error =>
        error.remove()
    );
}

export function showInputErrors(form, errorsMap) {
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