function moveStock(storageKey, direction) {
    const useInput = document.querySelector(`[name="${storageKey}[use_units]"]`);
    const reserveInput = document.querySelector(`[name="${storageKey}[reserve_units]"]`);

    let use = parseInt(useInput.value) || 0;
    let reserve = parseInt(reserveInput.value) || 0;

    // mover de reserva a uso
    if (direction === 1 && reserve > 0) {
        reserve--;
        use++;
    }

    // mover de uso a reserva
    if (direction === -1 && use > 0) {
        use--;
        reserve++;
    }

    useInput.value = use;
    reserveInput.value = reserve;
}
function applySupply(storage) {
    const input = document.getElementById(`supply_units_${storage}`);
    let value = parseInt(input.value || 0);

    if (value <= 0) return;

    const reserve = document.querySelector(`[name="${storage}[reserve_units]"]`);
    reserve.value = parseInt(reserve.value || 0) + value;

    input.value = 0;
}