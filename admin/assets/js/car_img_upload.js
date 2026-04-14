const fileInput = document.getElementById("images");
const customBtn = document.getElementById("custom-images-btn");
const previewContainer = document.getElementById("preview-container");
let selectedFiles = [];

// Open file selection dialog when custom button is clicked
customBtn.addEventListener("click", () => fileInput.click());

// Handle file selection
fileInput.addEventListener("change", (event) => {
    let files = Array.from(event.target.files);

    // Preserve the order of first uploaded files by appending new ones
    files.forEach(file => {
        if (!selectedFiles.some(f => f.name === file.name)) {
            selectedFiles.push(file);
        }
    });

    displayImages();
});

function displayImages() {
    previewContainer.innerHTML = ""; // Clear previous previews

    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const imgContainer = document.createElement("div");
            imgContainer.classList.add("m-2", "position-relative");

            imgContainer.innerHTML = `
                <img src="${e.target.result}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" onclick="removeImage(${index})">X</button>
            `;
            previewContainer.appendChild(imgContainer);
        };
        reader.readAsDataURL(file);
    });
}

function removeImage(index) {
    selectedFiles.splice(index, 1);
    displayImages();
}
