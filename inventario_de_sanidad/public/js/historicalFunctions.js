window.addEventListener("load", inicio);

function updateDataRetrieveModifications() {
    let result = fetch('/historical/modificationsHistoricalData')
        .then(function (response) {
            let jsonData = response.json();
            return jsonData;
        })
        .then(function (data) {
            window.MODIFICATIONSDATA = data;
            return;
        });

    return result;
}

function updateDataRetrieve() {
    let href = window.location.href;
    let parts = href.split("/");
    let lastSegment = parts[parts.length - 1];
    let requestUrl = '/historical/historicalData?request=' + lastSegment;

    let result = fetch(requestUrl)
        .then(function (response) {
            let jsonData = response.json();
            return jsonData;
        })
        .then(function (data) {
            window.HISTORICALDATA = data;
            return;
        });

    return result;
}

function inicio() {
    updateDataRetrieveModifications().then(function () {
        return updateDataRetrieve();
    });
    return;
}
