window.addEventListener("load", function () {
    fetch('/materials/history/data-modifications')
        .then(response => response.json())
        .then(data => {
            window.MODIFICATIONSDATA = data;
        });
});