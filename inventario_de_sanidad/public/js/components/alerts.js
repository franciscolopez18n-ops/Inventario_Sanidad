let alertQueue = Promise.resolve();

window.addEventListener("load", () => {
    const alerts = document.querySelectorAll(".alerts-container .alert");

    alerts.forEach(alert => {
        alertQueue = alertQueue.then(() => manageAlert(alert));
    });
});

async function manageAlert(alert) {
    alert.classList.remove("hidden");

    await new Promise(resolve => setTimeout(resolve, 3000));

    alert.classList.add("hide");

    await new Promise(resolve => setTimeout(resolve, 350));

    alert.remove();
}

function showAlert(alertClass, message) {
    const container = document.querySelector(".alerts-container");
    if (!container) return;

    const alert = document.createElement("p");
    alert.className = `alert ${alertClass} hidden`;
    alert.textContent = message;

    container.appendChild(alert);

    alertQueue = alertQueue.then(() => manageAlert(alert));
}