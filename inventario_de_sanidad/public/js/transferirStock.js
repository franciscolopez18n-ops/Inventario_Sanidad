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
