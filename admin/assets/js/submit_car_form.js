document.getElementById("carForm").addEventListener("submit", function (e) {
    e.preventDefault(); // Prevent the default form submission

    const files = document.getElementById("images").files;
    if (files.length > 0) {
        // Compress images before submitting the form
        const compressedFiles = [];
        let processedCount = 0;

        Array.from(files).forEach((file, index) => {
            compressImage(file, 300, 200, 0.7, (compressedFile) => {
                compressedFiles.push(compressedFile);
                processedCount++;

                // Once all images are compressed, submit the form
                if (processedCount === files.length) {
                    submitForm(compressedFiles);
                }
            });
        });
    } else {
        // If no images are selected, submit the form directly
        submitForm([]);
    }
});

function compressImage(file, targetWidth, targetHeight, quality, callback) {
    const reader = new FileReader();
    reader.readAsDataURL(file);

    reader.onload = function (event) {
        const img = new Image();
        img.src = event.target.result;

        img.onload = function () {
            const canvas = document.createElement("canvas");
            canvas.width = targetWidth;
            canvas.height = targetHeight;
            const ctx = canvas.getContext("2d");

            // Resize image and maintain aspect ratio
            ctx.drawImage(img, 0, 0, targetWidth, targetHeight);

            // Convert canvas to compressed file
            canvas.toBlob((blob) => {
                const compressedFile = new File([blob], file.name, { type: "image/jpeg", lastModified: Date.now() });
                callback(compressedFile);
            }, "image/jpeg", quality);
        };
    };
}

function submitForm(compressedFiles) {
    const form = document.getElementById('carForm');
    const formData = new FormData(form); // Create FormData object
    const progress = document.getElementById('progress');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const message = document.getElementById('message');

    // Append compressed files to the FormData
    compressedFiles.forEach((file, index) => {
        formData.append("images[]", file);
    });

    // Show progress bar
    progress.style.display = 'block';
    progressBar.style.width = '0%';
    progressText.textContent = 'Uploading...';

    // Create an XMLHttpRequest object
    const xhr = new XMLHttpRequest();

    // Handle upload progress
    xhr.upload.addEventListener('progress', function (event) {
        if (event.lengthComputable) {
            const percentComplete = (event.loaded / event.total) * 100;
            progressBar.style.width = percentComplete + '%';
            progressText.textContent = `Uploading: ${Math.round(percentComplete)}%`;
        }
    });

    // Handle response
    xhr.addEventListener('load', function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                // Show success message
                message.style.display = 'block';
                message.innerHTML = `<div class="alert alert-success">${response.message}</div>`;

                // Clear form fields
                form.reset();

                // Redirect to cars page after 3 seconds
                setTimeout(() => {
                    window.location.href = 'cars';
                }, 3000);
            } else {
                // Show error message
                message.style.display = 'block';
                message.innerHTML = `<div class="alert alert-danger">${response.message}</div>`;
            }
        } else {
            // Show error message
            message.style.display = 'block';
            message.innerHTML = `<div class="alert alert-danger">An error occurred. Please try again.</div>`;
        }

        // Hide progress bar
        progress.style.display = 'none';
    });

    // Handle errors
    xhr.addEventListener('error', function () {
        message.style.display = 'block';
        message.innerHTML = `<div class="alert alert-danger">An error occurred. Please try again.</div>`;
        progress.style.display = 'none';
    });

    // Send the request
    xhr.open('POST', form.action, true);
    xhr.send(formData);
}