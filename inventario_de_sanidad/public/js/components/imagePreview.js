document.querySelectorAll('input[type="file"].file-upload-input').forEach(input => {
    const elements = getImagePreviewElements(input);

    elements.input.addEventListener('change', () => previewImageSelection(
        elements.input,
        elements.imgPreview,
        elements.removeFlag,
        elements.fileNameDisplay
    ));

    elements.removeBtn.addEventListener('click', () => clearImageSelection(
        elements.input,
        elements.imgPreview,
        elements.removeFlag,
        elements.fileNameDisplay
    ));
});

function getImagePreviewElements(input) {
    const group = input.parentElement.querySelector('.image-preview-group');

    return {
        input,
        imgPreview: group.querySelector('.image-preview'),
        removeBtn: group.querySelector('.image-preview-remove'),
        removeFlag: group.querySelector('.remove-image-flag'),
        fileNameDisplay: group.querySelector('.file-name-display')
    };
}

function discardCurrentImage(imgPreview, removeFlag) {
    const src = imgPreview.getAttribute('src');
    if (!src) return;

    if (src.startsWith('blob:')) {
        // Si es una imagen temporal, se libera
        URL.revokeObjectURL(src);
    } else if (removeFlag) {
        // Si no es una imagen temporal y el backend quiere enterarse del borrado a través de un campo oculto,
        //      se trata de una imagen ya almacenada en el servidor
        removeFlag.value = "1";
    }
}

function previewImageSelection(input, imgPreview, removeFlag, fileNameDisplay) {
    discardCurrentImage(imgPreview, removeFlag); // borrado de la imagen anterior si el usuario la sustituye directamente
    
    const file = input.files[0];
    const objectURL = URL.createObjectURL(file);

    imgPreview.src = objectURL;
    imgPreview.alt = `Vista previa de ${file.name}`;
    fileNameDisplay.textContent = file.name;

    imgPreview.closest('.image-preview-wrapper').classList.remove('hidden');
}

function clearImageSelection(input, imgPreview, removeFlag, fileNameDisplay) {
    discardCurrentImage(imgPreview, removeFlag);

    input.value = "";
    imgPreview.src = "";
    imgPreview.alt = "";
    fileNameDisplay.textContent = "Ningún archivo seleccionado";

    imgPreview.closest('.image-preview-wrapper').classList.add('hidden');
}