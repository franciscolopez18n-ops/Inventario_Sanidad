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
// Con esto 
function añadirReserva(storage) {
    const input = document.getElementById(`supply_units_${storage}`);
    const error = document.getElementById(`supply_error_${storage}`);

    let value = parseInt(input.value);

    // LIMPIAR ERROR PREVIO
    error.style.display = "none";
    error.textContent = "";

    // VALIDACIÓN
    if (!input.value || isNaN(value) || value <= 0) {
        error.textContent = "Introduce una cantidad válida (> 0)";
        error.style.display = "block";
        return;
    }

    const reserve = document.querySelector(`[name="${storage}[reserve_units]"]`);
    reserve.value = parseInt(reserve.value || 0) + value;

    // RESET A VACÍO (como querías)
    input.value = "";

    showGlobalAlert("success", "Unidades añadidas correctamente");
}