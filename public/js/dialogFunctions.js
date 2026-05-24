// Función que muestra un cuadro de diálogo de confirmación personalizado según el botón clicado
function mostrarDialogConfirmacion(event) {
    event.preventDefault();

    let botonConfirmar;
    let botonCancelar;
    let dialog;

    // Verifica si el botón clicado tiene un ID que contiene "ver" (por ejemplo, "btn-ver")
    if (event.target.id.split("-")[1] == "ver") {
        // Selecciona los elementos correspondientes para la variante "ver"
        botonConfirmar = document.getElementById("aceptarContra");
        botonCancelar = document.getElementById("cancelarContra");
        dialog = document.getElementById("confirmacionContra");
    } else {
        // Selecciona los elementos para el diálogo genérico
        botonConfirmar = document.getElementById("aceptar");
        botonCancelar = document.getElementById("cancelar");
        dialog = document.getElementById("confirmacion");
    }

    // Muestra el cuadro de diálogo añadiendo el atributo "open"
    dialog.setAttribute("open", "true");

    // Al hacer clic en "Confirmar", se envía el formulario original y se cierra el diálogo
    botonConfirmar.onclick = () => {
        event.target.submit();
        cerrarDialog(dialog);
    };

    // Al hacer clic en "Cancelar", simplemente se cierra el diálogo
    botonCancelar.onclick = () => {
        cerrarDialog(dialog);
    };
}

// Función que cierra el cuadro de diálogo
function cerrarDialog(dialog) {
    dialog.removeAttribute("open");
}
