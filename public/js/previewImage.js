const imageInput = document.getElementById("image");
const fileNameDisplay = document.getElementById("file-name");

imageInput.addEventListener("change", handleImageChange);

function handleImageChange() {
    const fileName = this.files[0] ? this.files[0].name : "Ningún archivo seleccionado";
    fileNameDisplay.textContent = fileName;
}

function previewImage(event, previewElement) {
    let input = event.target;
    let imgPreview = document.querySelector(previewElement);

    if(!input.files.length) return;

    let file = input.files[0];

    let objectURL = URL.createObjectURL(file);
    imgPreview.src = objectURL;
}
