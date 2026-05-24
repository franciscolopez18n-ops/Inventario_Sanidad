window.addEventListener("load", inicio);

// Función que retorna una promesa con los datos
function updateDataRetrieve() {
    let rute ="/activities/activityData";
    let isTeacher = document.querySelector(".user-role").textContent.includes("teacher");

    if (isTeacher) {
        rute ="/activities/activityTeacherData";
    }
    return fetch(rute)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener datos');
            }
            return response.json();
        })
        .then(data => {
            window.ACTIVITYDATA = data;
            return data;
        })
        .catch(error => {
            console.error('Error en fetch:', error);
            return null;
        });
}

// Función inicio que espera la promesa antes de continuar
function inicio() {
    return updateDataRetrieve();
}
