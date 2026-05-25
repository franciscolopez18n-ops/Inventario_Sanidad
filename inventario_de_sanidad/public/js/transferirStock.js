document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[name$="[use_units]"]').forEach(input => {
        const storageKey = input.name.replace('[use_units]', '');
        updateButtons(storageKey);
    });
});

function updateButtons(storageKey) {
    const useInput = document.querySelector(`[name="${storageKey}[use_units]"]`);
    const reserveInput = document.querySelector(`[name="${storageKey}[reserve_units]"]`);
    const btnToReserve = document.querySelector(`[onclick="moveStock('${storageKey}', -1)"]`);
    const btnToUse = document.querySelector(`[onclick="moveStock('${storageKey}', 1)"]`);

    if (btnToReserve) btnToReserve.disabled = (parseInt(useInput.value) || 0) <= 0;
    if (btnToUse) btnToUse.disabled = (parseInt(reserveInput.value) || 0) <= 0;
}

function moveStock(storageKey, direction) {
    const useInput = document.querySelector(`[name="${storageKey}[use_units]"]`);
    const reserveInput = document.querySelector(`[name="${storageKey}[reserve_units]"]`);

    let use = parseInt(useInput.value) || 0;
    let reserve = parseInt(reserveInput.value) || 0;

    if (direction === 1 && reserve > 0) { reserve--; use++; }
    if (direction === -1 && use > 0) { use--; reserve++; }

    useInput.value = use;
    reserveInput.value = reserve;

    updateButtons(storageKey);
}

function suministrar(storageKey) {
    const suministroInput = document.querySelector(`[name="${storageKey}[suministro]"]`);
    const reserveInput = document.querySelector(`[name="${storageKey}[reserve_units]"]`);

    let cantidad = parseInt(suministroInput.value);
    if (isNaN(cantidad) || cantidad < 0) return;

    reserveInput.value = cantidad;
    suministroInput.value = '';

    updateButtons(storageKey);
}