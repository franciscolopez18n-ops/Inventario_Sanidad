document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".transfer-btn").forEach(button => {
        button.addEventListener("click", () => {
            moveStock(
                button.dataset.storage,
                button.dataset.direction
            );
        });
    });

    document.querySelectorAll(".supply-btn").forEach(button => {
        button.addEventListener("click", () => {
            setReserveUnits(button.dataset.storage);
        });
    });
});

function syncTransferButtons(storageKey) {
    const useInput = document.querySelector(`[name="${storageKey}[use_units]"]`);
    const reserveInput = document.querySelector(`[name="${storageKey}[reserve_units]"]`);
    const btnToReserve = document.querySelector(`.transfer-btn[data-storage="${storageKey}"][data-direction="to-reserve"]`);
    const btnToUse = document.querySelector(`.transfer-btn[data-storage="${storageKey}"][data-direction="to-use"]`);

    btnToReserve.disabled = useInput.value <= 0;
    btnToUse.disabled = reserveInput.value <= 0;
}

function moveStock(storageKey, direction) {
    const useInput = document.querySelector(`[name="${storageKey}[use_units]"]`);
    const reserveInput = document.querySelector(`[name="${storageKey}[reserve_units]"]`);

    let use = useInput.value;
    let reserve = reserveInput.value;

    if (direction === "to-use" && reserve > 0) { reserve--; use++; }
    if (direction === "to-reserve" && use > 0) { use--; reserve++; }

    useInput.value = use;
    reserveInput.value = reserve;

    syncTransferButtons(storageKey);
}

function setReserveUnits(storageKey) {
    const supplyInput = document.getElementById(`${storageKey}_supply`);
    const reserveInput = document.querySelector(`[name="${storageKey}[reserve_units]"]`);

    let newTotalUnits = supplyInput.value;
    if (newTotalUnits === "" || isNaN(newTotalUnits) || newTotalUnits < 0) return;

    reserveInput.value = newTotalUnits;
    supplyInput.value = '';

    syncTransferButtons(storageKey);
}