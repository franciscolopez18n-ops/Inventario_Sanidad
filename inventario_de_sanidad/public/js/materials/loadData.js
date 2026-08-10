window.addEventListener("load", () => {
    fetch('/materials/manage/data')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener datos');
            }
            return response.json();
        })
        .then(data => {
            window.MATERIALDATA = data;
        })
        .catch(error => {
            console.error('Error en fetch:', error);
        });
});