window.addEventListener("load", function () {
    let lastSegment = window.location.href.split("/").pop();

    fetch('/materials/history/data-summary?request=' + lastSegment)
        .then(response => response.json())
        .then(data => {
            window.HISTORICALDATA = data;
        });
});