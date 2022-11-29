export function displayFile(elementId , displayPreview = false) {
    const preview = document.getElementById("preview-" + elementId);
    const element = document.getElementById(elementId)
    element.addEventListener("change", () => {
        var fileName = element.value;
        var filesList = element.files;
        var imageType = /^image\//
        var file = filesList[0];
        if (!imageType.test(file.type)) {
            var message = document.createElement("div");
            message.classList.add("alert-danger", "m-2", "p-2");
            message.textContent = "Veuillez sélectionner une image"
            message.id = 'alertFile';
            message = element.parentNode.parentNode.insertBefore(message, element.parentNode);
        } else {
            if (document.getElementById("alertFile")) {
                document.getElementById("alertFile").remove();
            }
        }
        var parts = fileName.split("\\");
        document.querySelector("input[type='file']+label").innerHTML = parts.pop();
        preview.innerHTML = "";
        if (displayPreview) {
            var img = document.createElement("img");
            img.classList.add("rounded")
            img.file = file;
            preview.appendChild(img);
            var reader = new FileReader();
            reader.onload = (function (aImg) {
                return function (e) {
                    aImg.src = e.target.result;
                };
            })(img);
            reader.readAsDataURL(file);
        }

    })

    if (element.dataset.file) {
        console.log(element.dataset.file)
        var fileName = element.dataset.file;
        var parts = fileName.split("\\");
        document.querySelector("input[type='file']+label").innerHTML = parts.pop();
    }
}