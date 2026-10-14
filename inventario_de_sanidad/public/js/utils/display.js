export const TextCase = Object.freeze({
    CAPITALIZED: 'capitalized',
    LOWERCASE: 'lowercase',
    UPPERCASE: 'uppercase',
});

export const DisplayCategory = Object.freeze({
    STORAGE: 'storage',
    MODALITY: 'modality',
});

const categoryMap = Object.freeze({
    [DisplayCategory.STORAGE]: { CAE: 'CAE', odontology: 'Odontología' },
    [DisplayCategory.MODALITY]: { use: 'uso', reserve: 'reserva' },
});

export function displayName(value, displayCategory, textCase) {
    return applyTextCase(
        categoryMap[displayCategory]?.[value] ?? value,
        textCase
    );
}

function applyTextCase(str, textCase) {
    switch (textCase) {
        case TextCase.LOWERCASE: return str.toLowerCase();
        case TextCase.UPPERCASE: return str.toUpperCase();
        case TextCase.CAPITALIZED: return str[0].toUpperCase() + str.slice(1).toLowerCase();
        default: return str;
    }
}