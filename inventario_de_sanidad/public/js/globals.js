const TextCase = Object.freeze({
    CAPITALIZED: 'capitalized',
    LOWERCASE: 'lowercase',
    UPPERCASE: 'uppercase',
});

const DisplayCategory = Object.freeze({
    STORAGE: 'storage',
    MODALITY: 'modality',
});

function applyTextCase(str, textCase) {
    switch (textCase) {
        case TextCase.LOWERCASE: return str.toLowerCase();
        case TextCase.UPPERCASE: return str.toUpperCase();
        case TextCase.CAPITALIZED: return str[0].toUpperCase() + str.slice(1).toLowerCase();
        default: return str;
    }
}

// Module Pattern para encapsular el mapa: se construye una única vez y no se expone al scope global
const displayName = (function () {
    const categoryMap = Object.freeze({
        [DisplayCategory.STORAGE]: { CAE: 'CAE', odontology: 'Odontología' },
        [DisplayCategory.MODALITY]: { use: 'uso', reserve: 'reserva' },
    });

    return function (value, displayCategory, textCase) {
        return applyTextCase(categoryMap[displayCategory]?.[value] ?? value, textCase);
    };
})();

/*
function storageDisplayName(storage, { caeTextCase, odontologyTextCase } = {}) {
    return storage === 'CAE'
        ? applyTextCase('CAE', caeTextCase)
        : applyTextCase('Odontología', odontologyTextCase);
}

function modalityDisplayName(modality, { useTextCase, reserveTextCase } = {}) {
    return modality === 'use'
        ? applyTextCase('uso', useTextCase)
        : applyTextCase('reserva', reserveTextCase);
}
*/