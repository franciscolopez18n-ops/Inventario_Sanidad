window.addEventListener("load", function () {
    let lastSegment = window.location.href.split("/").pop();

    fetch('/historical/historicalData?request=' + lastSegment)
        .then(response => response.json())
        .then(data => {
            window.HISTORICALDATA = data;
        });
});