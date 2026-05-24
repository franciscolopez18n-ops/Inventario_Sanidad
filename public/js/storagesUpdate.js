window.addEventListener("load", inicio);

function updateDataRetrieve() {
    return fetch('/storages/updateData')
        .then(function(response) {
            if (!response.ok) {
                throw new Error("Error al obtener datos");
            }
            return response.json();
        })
        .then(function(data) {
            window.STORAGEDATA = data;
            return data;
        })
        .catch(function(error) {
            console.error("Error en updateDataRetrieve:", error);
            return null;
        });
}

function inicio() {
    updateDataRetrieve();
}