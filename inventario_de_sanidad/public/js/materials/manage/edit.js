import "../../components/imagePreview.js";

document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll(".transfer-btn").forEach(button => {
        attachHoldRepeat(button, () => {
            moveStock(button.dataset.storage, button.dataset.direction);
        });
    });

    document.querySelectorAll(".supply-mode-btn").forEach(button => {
        button.addEventListener("click", () => {
            const nextMode = button.dataset.mode === "add" ? "subtract" : "add";
            applySupplyMode(button.dataset.storage, nextMode);
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

// Aplica comportamiento de "tap = una vez" y "mantener pulsado = repetir con aceleración"
function attachHoldRepeat(button, action) {
    const HOLD_CONFIRM_DELAY = 350; // ms para confirmar que es un hold, no un tap
    const STAGE_1_MS = 1000; // tras 1s de hold, acelera
    const STAGE_2_MS = 3000; // tras 3s de hold, acelera más

    let holdTimeout = null;
    let repeatInterval = null;
    let holdStartedAt = null;

    function currentDelay() {
        const elapsed = Date.now() - holdStartedAt;
        if (elapsed > STAGE_2_MS) return 15;
        if (elapsed > STAGE_1_MS) return 90;
        return 200; // fase 0
    }

    function tick() {
        if (button.disabled) {
            stop();
            return;
        }

        action();

        // Reprograma el intervalo con el delay actual, por si cambió de etapa
        clearInterval(repeatInterval);
        repeatInterval = setInterval(tick, currentDelay());
    }

    function startRepeating() {
        holdStartedAt = Date.now();
        repeatInterval = setInterval(tick, currentDelay());
    }

    function stop() {
        clearTimeout(holdTimeout);
        clearInterval(repeatInterval);
        holdTimeout = null;
        repeatInterval = null;
    }

    button.addEventListener("pointerdown", (e) => {
        e.preventDefault();

        action(); // el tap/primer toque siempre cuenta como una transferencia
        holdTimeout = setTimeout(startRepeating, HOLD_CONFIRM_DELAY);
    });

    ["pointerup", "pointerleave", "pointercancel"].forEach(evt => {
        button.addEventListener(evt, stop);
    });
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

const SUPPLY_MODES = {
    add: {
        sign: "+",
        label: "Sumar unidades de reserva",
        placeholder: "Unidades a sumar",
        button: "Sumar",
        direction: 1,
    },
    subtract: {
        sign: "−",
        label: "Restar unidades de reserva",
        placeholder: "Unidades a restar",
        button: "Restar",
        direction: -1,
    },
};

function applySupplyMode(storageKey, mode) {
    const config = SUPPLY_MODES[mode];
    const input = document.getElementById(`${storageKey}_supply`);
    const field = input.closest(".supply-field");

    const modeBtn = field.querySelector(".supply-mode-btn");
    modeBtn.dataset.mode = mode;
    modeBtn.textContent = config.sign;

    field.querySelector(".supply-label").textContent = config.label;
    field.querySelector(".supply-btn").textContent = config.button;
    input.placeholder = config.placeholder;
}

function setReserveUnits(storageKey) {
    const supplyInput = document.getElementById(`${storageKey}_supply`);
    const reserveInput = document.querySelector(`[name="${storageKey}[reserve_units]"]`);
    const modeBtn = supplyInput.closest(".supply-field").querySelector(".supply-mode-btn");

    const amount = parseInt(supplyInput.value, 10);
    if (isNaN(amount) || amount <= 0) return;

    const { direction } = SUPPLY_MODES[modeBtn.dataset.mode];
    const current = parseInt(reserveInput.value, 10) || 0;

    // Al restar, nunca bajamos de 0
    reserveInput.value = Math.max(0, current + direction * amount);
    supplyInput.value = "";

    syncTransferButtons(storageKey);
}