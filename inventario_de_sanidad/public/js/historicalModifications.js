window.addEventListener("load", function () {
    fetch('/historical/modificationsHistoricalData')
        .then(response => response.json())
        .then(data => {
            window.MODIFICATIONSDATA = data;
        });
});