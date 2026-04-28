window.addEventListener("load", inicio);

// Función asíncrona que obtiene los datos de usuario del servidor
async function userDataRetrieve() {
    return fetch('/users/usersManagementData') // Realiza una solicitud al backend
        .then(response => {
            // Verifica que la respuesta sea exitosa
            if (!response.ok) {
                throw new Error('Error al obtener datos');
            }
            return response.json(); // Convierte la respuesta en JSON
        })
        .then(data => {
            // Guarda los datos globalmente en `window.USERDATA`
            window.USERDATA = data;
            return data;
        })
        .catch(error => {
            console.error('Error en fetch:', error);
            return null;
        });
}

// Función que se ejecuta cuando la ventana ha cargado
function inicio() {
    userDataRetrieve();

    let botonesVer = document.querySelectorAll("[id^='btn-ver-']");
    let botonesBaja = document.querySelectorAll("[id^='btn-delete-']");

    for (let btn of botonesVer) {
        btn.addEventListener("submit", mostrarDialogConfirmacion);
    }

    for (let btn of botonesBaja) {
        btn.addEventListener("submit", mostrarDialogConfirmacion);
    }
}
