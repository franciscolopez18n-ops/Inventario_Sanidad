window.addEventListener("load", () => {
    const userData = JSON.parse(document.getElementById('user-data').textContent);

    if (!userData["first_log"]) {
        const dialog = document.getElementById("first-log-dialog");

        dialog.addEventListener('keydown', event => {
            if (event.key === 'Escape') {
                event.preventDefault();
            }
        });

        dialog.showModal();
    }
});