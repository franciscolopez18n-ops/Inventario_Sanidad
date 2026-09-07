document.querySelectorAll('input[type="file"].file-upload-input').forEach(input => {
    const group = input.parentElement.querySelector('.image-preview-group');
    const imgPreview = group.querySelector('.image-preview');
    const removeBtn = group.querySelector('.image-preview-remove');
    const removeFlag = group.querySelector('.remove-image-flag');
    const fileNameDisplay = group.querySelector('.file-name-display');

    input.addEventListener('change', () => previewImageSelection(input, imgPreview, removeFlag, fileNameDisplay));
    removeBtn.addEventListener('click', () => clearImageSelection(input, imgPreview, removeFlag, fileNameDisplay));
});

function discardCurrentImage(input, imgPreview, removeFlag) {
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
    const file = input.files[0];
    const objectURL = URL.createObjectURL(file);

    discardCurrentImage(input, imgPreview, removeFlag); // borrado de la imagen anterior si el usuario la sustituye directamente

    imgPreview.src = objectURL;
    imgPreview.alt = `Vista previa de ${file.name}`;
    fileNameDisplay.textContent = file.name;

    imgPreview.closest('.image-preview-wrapper').classList.remove('hidden');
}

function clearImageSelection(input, imgPreview, removeFlag, fileNameDisplay) {
    discardCurrentImage(input, imgPreview, removeFlag);

    input.value = "";
    imgPreview.src = "";
    imgPreview.alt = "";
    fileNameDisplay.textContent = "Ningún archivo seleccionado";

    imgPreview.closest('.image-preview-wrapper').classList.add('hidden');
}