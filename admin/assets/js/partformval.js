const fileInput = document.getElementById("images");
const customBtn = document.getElementById("custom-images-btn");
const previewContainer = document.getElementById("preview-container");
const dropZone = document.getElementById("drop-zone");
let selectedFiles = []; // Final selected files
let pickerFiles = []; // All files loaded in picker
let pickerSelected = []; // Files selected in picker with order

// Open custom image picker modal
customBtn.addEventListener("click", (e) => {
    e.preventDefault();
    e.stopPropagation();

    customBtn.disabled = true;
    customBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';

    fileInput.click();
});

// Also allow clicking anywhere on the drop zone
dropZone.addEventListener("click", (e) => {
    if (e.target !== customBtn && !customBtn.contains(e.target)) {
        customBtn.click();
    }
});

// Drag and Drop Events on drop zone
dropZone.addEventListener("dragover", (e) => {
    e.preventDefault();
    dropZone.style.borderColor = "#0d6efd";
    dropZone.style.background = "#e7f1ff";
});

dropZone.addEventListener("dragleave", (e) => {
    e.preventDefault();
    dropZone.style.borderColor = "#ccc";
    dropZone.style.background = "#f8f9fa";
});

dropZone.addEventListener("drop", (e) => {
    e.preventDefault();
    dropZone.style.borderColor = "#ccc";
    dropZone.style.background = "#f8f9fa";

    const files = Array.from(e.dataTransfer.files);
    const imageFiles = files.filter(file => file.type.startsWith('image/'));

    if (imageFiles.length > 0) {
        addFilesDirectly(imageFiles);
    } else {
        alert("Please drop only image files");
    }
});

// Mobile detection
function isMobileDevice() {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ||
           (window.innerWidth <= 768 && window.ontouchstart !== undefined);
}

function getSelectImagesButtonText() {
    if (isMobileDevice()) {
        return '<i class="bi bi-images"></i> Select Images (Order Matters)';
    }
    return '<i class="bi bi-images"></i> Select Images in Order';
}

document.addEventListener('DOMContentLoaded', function () {
    if (customBtn) {
        customBtn.innerHTML = getSelectImagesButtonText();
    }
});

// Handle file selection
fileInput.addEventListener("change", (event) => {
    customBtn.disabled = false;
    customBtn.innerHTML = getSelectImagesButtonText();

    const files = Array.from(event.target.files);
    if (files.length > 0) {
        if (isMobileDevice()) {
            addFilesDirectly(files);
        } else {
            showPickerModal(files);
        }
    }
});

// Reset button if user cancels file dialog
window.addEventListener('focus', () => {
    setTimeout(() => {
        if (customBtn.disabled && fileInput.files.length === 0) {
            customBtn.disabled = false;
            customBtn.innerHTML = getSelectImagesButtonText();
        }
    }, 300);
});

// Show the picker modal
function showPickerModal(files) {
    try {
        pickerFiles = files;
        pickerSelected = [...selectedFiles];

        const modalElement = document.getElementById('imagePickerModal');
        if (!modalElement) {
            addFilesDirectly(files);
            return;
        }

        const modal = new bootstrap.Modal(modalElement);
        modal.show();

        const grid = document.getElementById('picker-grid');
        grid.innerHTML = `
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted">Loading ${files.length} images...</p>
            </div>
        `;

        setTimeout(() => {
            renderPickerGrid();
            setTimeout(() => {
                updatePickerVisualState();
            }, pickerFiles.length * 10 + 100);
            updatePickerPreview();
        }, 100);

    } catch (error) {
        console.error('Error showing picker modal:', error);
        addFilesDirectly(files);
    }
}

// Render picker grid
function renderPickerGrid() {
    const grid = document.getElementById('picker-grid');
    grid.innerHTML = '';

    const placeholders = [];
    pickerFiles.forEach((file, index) => {
        const col = document.createElement('div');
        col.className = 'col-6 col-sm-4 col-md-3 col-lg-2';
        col.innerHTML = `
            <div class="card h-100">
                <div class="d-flex align-items-center justify-content-center" style="height: 120px; background: #f0f0f0;">
                    <div class="spinner-border spinner-border-sm text-primary"></div>
                </div>
                <div class="card-body p-1">
                    <small class="text-muted" style="font-size: 0.7rem;">Loading...</small>
                </div>
            </div>
        `;
        grid.appendChild(col);
        placeholders[index] = col;
    });

    let loadedCount = 0;
    pickerFiles.forEach((file, index) => {
        setTimeout(() => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const selectedIndex = pickerSelected.findIndex(
                    f => f.name === file.name && f.size === file.size && f.lastModified === file.lastModified
                );
                const isSelected = selectedIndex !== -1;
                const orderNumber = isSelected ? selectedIndex + 1 : '';

                placeholders[index].innerHTML = `
                    <div class="card picker-card h-100 ${isSelected ? 'selected' : ''}"
                         data-index="${index}"
                         data-order="${orderNumber}"
                         style="cursor: pointer;">
                        <div class="position-relative">
                            <img src="${e.target.result}" class="card-img-top">
                            <div class="picker-overlay"></div>
                        </div>
                        <div class="card-body p-1">
                            <small class="text-truncate d-block" style="font-size: 0.7rem;" title="${file.name}">${file.name}</small>
                        </div>
                    </div>
                `;

                const card = placeholders[index].querySelector('.picker-card');
                if (card) {
                    card.addEventListener('click', () => toggleImageSelection(index));
                    loadedCount++;
                }
            };
            reader.readAsDataURL(file);
        }, index * 10);
    });
}

// Toggle image selection in picker
function toggleImageSelection(index) {
    const file = pickerFiles[index];
    if (!file) return;

    const grid = document.getElementById('picker-grid');
    const cards = grid.querySelectorAll('.picker-card');
    const clickedCard = cards[index];
    if (!clickedCard) return;

    const selectedIndex = pickerSelected.findIndex(
        f => f.name === file.name && f.size === file.size && f.lastModified === file.lastModified
    );

    if (selectedIndex !== -1) {
        pickerSelected.splice(selectedIndex, 1);
        clickedCard.classList.remove('selected');
        clickedCard.setAttribute('data-order', '');
    } else {
        pickerSelected.push(file);
        clickedCard.classList.add('selected');
        clickedCard.setAttribute('data-order', pickerSelected.length);
    }

    requestAnimationFrame(() => {
        updateOtherCardsNumbers();
        updatePickerPreview();
    });
}

function updateOtherCardsNumbers() {
    const grid = document.getElementById('picker-grid');
    const cards = grid.querySelectorAll('.picker-card.selected');

    cards.forEach((card) => {
        const cardIndex = parseInt(card.getAttribute('data-index'));
        const file = pickerFiles[cardIndex];
        if (!file) return;

        const selectedIndex = pickerSelected.findIndex(
            f => f.name === file.name && f.size === file.size && f.lastModified === file.lastModified
        );

        if (selectedIndex !== -1) {
            const orderNumber = selectedIndex + 1;
            if (card.getAttribute('data-order') !== String(orderNumber)) {
                card.setAttribute('data-order', orderNumber);
            }
        }
    });
}

function updatePickerVisualState() {
    const grid = document.getElementById('picker-grid');
    const cards = grid.querySelectorAll('.picker-card');

    cards.forEach((card, cardIndex) => {
        const file = pickerFiles[cardIndex];
        if (!file) return;

        const selectedIndex = pickerSelected.findIndex(
            f => f.name === file.name && f.size === file.size && f.lastModified === file.lastModified
        );

        const isSelected = selectedIndex !== -1;
        const orderNumber = isSelected ? selectedIndex + 1 : 0;

        if (isSelected) {
            card.classList.add('selected');
            card.setAttribute('data-order', orderNumber);
        } else {
            card.classList.remove('selected');
            card.setAttribute('data-order', '');
        }
    });
}

// Update preview strip at bottom of picker
function updatePickerPreview() {
    const preview = document.getElementById('picker-preview');
    const counter = document.getElementById('picker-counter');

    counter.textContent = `${pickerSelected.length} selected`;

    if (pickerSelected.length === 0) {
        preview.innerHTML = '<div class="text-muted small">No images selected</div>';
        return;
    }

    preview.innerHTML = '';

    pickerSelected.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const thumb = document.createElement('div');
            thumb.className = 'position-relative';
            thumb.style.minWidth = '60px';

            const isPrimary = index === 0;
            const borderColor = isPrimary ? '#dc3545' : '#0d6efd';
            const badgeColor = isPrimary ? 'bg-danger' : 'bg-primary';
            const badgeText = isPrimary ? '★1' : `#${index + 1}`;

            thumb.innerHTML = `
                <img src="${e.target.result}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px; border: 2px solid ${borderColor};">
                <span class="badge ${badgeColor} position-absolute" style="top: -5px; left: -5px; font-size: 0.7rem;">${badgeText}</span>
                <button type="button" class="btn btn-sm btn-danger position-absolute" style="top: -5px; right: -5px; width: 20px; height: 20px; padding: 0; font-size: 0.7rem; line-height: 1;" onclick="window.removeFromPicker(${index})">
                    <i class="bi bi-x"></i>
                </button>
                ${isPrimary ? '<div class="text-center mt-1"><small class="text-danger fw-bold">PRIMARY</small></div>' : ''}
            `;
            preview.appendChild(thumb);
        };
        reader.readAsDataURL(file);
    });
}

window.removeFromPicker = function (index) {
    pickerSelected.splice(index, 1);
    renderPickerGrid();
    updatePickerPreview();
};

window.clearPickerSelection = function () {
    if (confirm('Remove all selected images?')) {
        pickerSelected = [];
        renderPickerGrid();
        updatePickerPreview();
    }
};

// Confirm selection and close picker
window.confirmImageSelection = function () {
    selectedFiles = [...pickerSelected];

    if (selectedFiles.length > 0) {
        dropZone.style.display = 'none';
    }

    displayImages();

    const modal = bootstrap.Modal.getInstance(document.getElementById('imagePickerModal'));
    modal.hide();

    fileInput.value = '';
};

// Add files directly (drag & drop or mobile)
function addFilesDirectly(files) {
    files.forEach(file => {
        const isDuplicate = selectedFiles.some(
            f => f.name === file.name && f.size === file.size && f.lastModified === file.lastModified
        );
        if (!isDuplicate) {
            selectedFiles.push(file);
        }
    });

    if (selectedFiles.length > 0) {
        dropZone.style.display = 'none';
    }

    displayImages();
}

// Display selected images in preview
function displayImages() {
    previewContainer.innerHTML = "";

    if (selectedFiles.length > 0) {
        const headerDiv = document.createElement("div");
        headerDiv.classList.add("mb-3");
        const primaryText = ` | <span class="text-danger fw-bold">First image is PRIMARY</span>`;
        headerDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                <div>
                    <i class="bi bi-images text-primary"></i>
                    <strong class="ms-2">${selectedFiles.length} image${selectedFiles.length > 1 ? 's' : ''} selected in order${primaryText}</strong>
                </div>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-secondary" onclick="window.sortImagesByName()" title="Sort alphabetically">
                        <i class="bi bi-sort-alpha-down"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="window.addMoreImages()" title="Add more images">
                        <i class="bi bi-plus-circle"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger" onclick="window.clearAllImages()" title="Remove all">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;
        previewContainer.appendChild(headerDiv);

        const gridContainer = document.createElement("div");
        gridContainer.classList.add("row", "g-2");
        gridContainer.id = "image-grid-container";
        previewContainer.appendChild(gridContainer);

        if (!isMobileDevice()) {
            const instructionsDiv = document.createElement("div");
            instructionsDiv.classList.add("alert", "alert-light", "small", "mb-3");
            instructionsDiv.innerHTML = `<i class="bi bi-hand-index"></i> <strong>Tip:</strong> Drag and drop images to reorder them. The first image will be the primary image.`;
            previewContainer.insertBefore(instructionsDiv, gridContainer);
        } else {
            const instructionsDiv = document.createElement("div");
            instructionsDiv.classList.add("alert", "alert-info", "small", "mb-3");
            instructionsDiv.innerHTML = `<i class="bi bi-phone"></i> <strong>Mobile Mode:</strong> Images are numbered in the order you selected them. The first image is your primary image.`;
            previewContainer.insertBefore(instructionsDiv, gridContainer);
        }

        const placeholders = [];
        selectedFiles.forEach((file, index) => {
            const colDiv = document.createElement("div");
            colDiv.classList.add("col-6", "col-sm-4", "col-md-3");

            if (!isMobileDevice()) {
                colDiv.draggable = true;
                colDiv.dataset.index = index;
                colDiv.addEventListener('dragstart', handleDragStart);
                colDiv.addEventListener('dragover', handleDragOver);
                colDiv.addEventListener('dragleave', handleDragLeave);
                colDiv.addEventListener('drop', handleDrop);
                colDiv.addEventListener('dragend', handleDragEnd);
            } else {
                colDiv.draggable = false;
                colDiv.dataset.index = index;
            }

            colDiv.innerHTML = `
                <div class="card h-100 shadow-sm">
                    <div class="position-relative d-flex align-items-center justify-content-center" style="height: 150px; background: #f0f0f0;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="position-absolute top-0 end-0 p-2">
                            <i class="bi bi-grip-vertical text-muted grip-handle" title="Drag to reorder"></i>
                        </div>
                    </div>
                    <div class="card-body p-2">
                        <p class="text-muted small">Loading...</p>
                    </div>
                </div>
            `;
            gridContainer.appendChild(colDiv);
            placeholders[index] = colDiv;
        });

        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const fileSize = file.size > 1024 * 1024
                    ? (file.size / (1024 * 1024)).toFixed(2) + ' MB'
                    : (file.size / 1024).toFixed(2) + ' KB';

                const isPrimary = index === 0;
                const borderColor = isPrimary ? 'border-danger' : '';
                const badgeColor = isPrimary ? 'bg-danger' : 'bg-primary';
                const badgeText = isPrimary ? '★1 PRIMARY' : `#${index + 1}`;

                placeholders[index].innerHTML = `
                    <div class="card h-100 shadow-sm ${borderColor}">
                        <div class="position-relative" onclick="window.showImagePreview(${index})">
                            <img src="${e.target.result}" class="card-img-top" style="height: 150px; object-fit: cover; cursor: pointer;">
                            <span class="badge ${badgeColor} position-absolute" style="top: 5px; left: 5px;">${badgeText}</span>
                            <div class="position-absolute top-0 end-0 p-2">
                                <i class="bi bi-grip-vertical text-white bg-dark rounded px-1 grip-handle" title="Drag to reorder"></i>
                            </div>
                        </div>
                        <div class="card-body p-2">
                            <p class="card-text small text-truncate mb-1" title="${file.name}">
                                <i class="bi bi-file-image"></i> ${file.name}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">${fileSize}</small>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="window.removeImage(${index})" title="Remove">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                            ${isPrimary ? '<div class="text-center mt-1"><small class="text-danger fw-bold">This will be the main image</small></div>' : ''}
                        </div>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        });
    } else {
        dropZone.style.display = 'block';
    }
}

// Show image in full-screen modal
window.showImagePreview = function (index) {
    try {
        const file = selectedFiles[index];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = (e) => {
            try {
                const modalElement = document.getElementById('imagePreviewModal');
                const modalImageName = document.getElementById('modalImageName');
                const modalImagePreview = document.getElementById('modalImagePreview');

                if (!modalElement || !modalImageName || !modalImagePreview) {
                    window.open(e.target.result, '_blank');
                    return;
                }

                modalImageName.textContent = file.name;
                modalImagePreview.src = e.target.result;

                if (typeof bootstrap !== 'undefined') {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                } else {
                    window.open(e.target.result, '_blank');
                }
            } catch (error) {
                window.open(e.target.result, '_blank');
            }
        };
        reader.readAsDataURL(file);
    } catch (error) {
        console.error('Error in showImagePreview:', error);
    }
};

window.removeImage = function (index) {
    selectedFiles.splice(index, 1);
    displayImages();
};

window.clearAllImages = function () {
    if (confirm('Remove all selected images?')) {
        selectedFiles = [];
        fileInput.value = '';
        displayImages();
    }
};

window.addMoreImages = function () {
    fileInput.click();
};

window.sortImagesByName = function () {
    if (confirm('Warning: Sorting by name will change the order you selected. Are you sure?')) {
        selectedFiles.sort((a, b) => a.name.localeCompare(b.name));
        displayImages();
    }
};

// Drag-and-drop reorder handlers
let draggedElement = null;
let isDragging = false;

function handleDragStart(e) {
    isDragging = true;
    draggedElement = this;
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', this.outerHTML);
    const card = this.querySelector('.card');
    if (card) card.classList.add('dragging-card');
}

function handleDragOver(e) {
    if (e.preventDefault) e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
    if (this !== draggedElement) this.classList.add('drag-over');
    return false;
}

function handleDragLeave(e) {
    if (!this.contains(e.relatedTarget)) this.classList.remove('drag-over');
}

function handleDrop(e) {
    if (e.stopPropagation) e.stopPropagation();
    this.classList.remove('drag-over');

    if (draggedElement !== this) {
        const draggedIndex = parseInt(draggedElement.dataset.index);
        const droppedIndex = parseInt(this.dataset.index);
        const draggedFile = selectedFiles[draggedIndex];
        selectedFiles.splice(draggedIndex, 1);
        selectedFiles.splice(droppedIndex, 0, draggedFile);
        displayImages();
    }
    return false;
}

function handleDragEnd(e) {
    isDragging = false;
    const card = this.querySelector('.card');
    if (card) card.classList.remove('dragging-card');
    this.classList.remove('drag-over');
    draggedElement = null;

    const gridContainer = document.getElementById('image-grid-container');
    if (gridContainer) {
        const allCols = gridContainer.querySelectorAll('[draggable="true"]');
        allCols.forEach(col => {
            col.classList.remove('drag-over');
            const cardInCol = col.querySelector('.card');
            if (cardInCol) cardInCol.classList.remove('dragging-card');
        });
    }
}

// Form submission
document.getElementById("partsForm").addEventListener("submit", function (e) {
    e.preventDefault();

    if (!validateForm()) return false;

    if (selectedFiles.length > 0) {
        const progress = document.getElementById('progress');
        const progressBar = document.getElementById('progressBar');
        const progressText = document.getElementById('progressText');

        progress.style.display = 'block';
        progressBar.style.width = '0%';
        progressText.textContent = 'Preparing images...';

        const compressedFiles = new Array(selectedFiles.length);
        let processedCount = 0;

        selectedFiles.forEach((file, index) => {
            compressImage(file, 1280, 960, 0.7, (compressedFile) => {
                compressedFiles[index] = compressedFile;
                processedCount++;

                const compressionProgress = (processedCount / selectedFiles.length) * 30;
                progressBar.style.width = compressionProgress + '%';
                progressText.textContent = `Preparing images... ${processedCount}/${selectedFiles.length}`;

                if (processedCount === selectedFiles.length) {
                    submitForm(compressedFiles);
                }
            });
        });
    } else {
        submitForm([]);
    }
});

// Parts form validation
function validateForm() {
    let isValid = true;
    let firstErrorElement = null;

    document.querySelectorAll('.text-danger[id$="-error"]').forEach(el => el.textContent = '');

    function setError(fieldId, message) {
        const errorElement = document.getElementById(fieldId + '-error');
        if (errorElement) {
            errorElement.textContent = message;
            if (!firstErrorElement) firstErrorElement = errorElement;
        }
        isValid = false;
    }

    if (!document.querySelector('input[name="part_name"]').value.trim()) setError('part_name', 'Please enter the part name.');
    if (!document.querySelector('select[name="category"]').value) setError('category', 'Please select a category.');
    if (!document.querySelector('input[name="make"]').value.trim()) setError('make', 'Please enter the make.');
    const priceVal = parseFloat(document.querySelector('input[name="price"]').value);
    if (!priceVal || priceVal <= 0) setError('price', 'Please enter a valid price.');
    if (selectedFiles.length === 0) setError('images', 'Please upload at least one image.');

    if (firstErrorElement) {
        firstErrorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    return isValid;
}

// Compress image
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
            ctx.drawImage(img, 0, 0, targetWidth, targetHeight);

            canvas.toBlob((blob) => {
                const compressedFile = new File([blob], file.name, { type: "image/jpeg", lastModified: Date.now() });
                callback(compressedFile);
            }, "image/jpeg", quality);
        };
    };
}

// Submit form via XHR
function submitForm(compressedFiles) {
    const form = document.getElementById('partsForm');
    const formData = new FormData(form);
    const progress = document.getElementById('progress');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const message = document.getElementById('message');

    formData.delete("images[]");

    // Add compressed files in the exact order selected
    compressedFiles.forEach((file, index) => {
        const orderedFile = new File([file], `${index.toString().padStart(3, '0')}_${file.name}`, {
            type: file.type,
            lastModified: file.lastModified
        });
        formData.append("images[]", orderedFile);
    });

    formData.append("image_order", JSON.stringify(compressedFiles.map((file, index) => ({
        index: index,
        originalName: file.name,
        size: file.size
    }))));

    if (compressedFiles.length > 0) {
        progress.style.display = 'block';
        progressBar.style.width = '30%';
        progressText.textContent = 'Uploading images...';
    }

    const xhr = new XMLHttpRequest();

    if (compressedFiles.length > 0) {
        xhr.upload.addEventListener('progress', function (event) {
            if (event.lengthComputable) {
                const uploadProgress = (event.loaded / event.total) * 70;
                const totalProgress = 30 + uploadProgress;
                progressBar.style.width = totalProgress + '%';
                progressText.textContent = `Uploading: ${Math.round(totalProgress)}%`;
            }
        });
    }

    xhr.addEventListener('load', function () {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    progressBar.style.width = '100%';
                    progressText.textContent = 'Upload complete!';

                    message.style.display = 'block';
                    message.innerHTML = `<div class="alert alert-success">${response.message}</div>`;

                    form.reset();
                    selectedFiles = [];
                    displayImages();

                    setTimeout(() => {
                        const pathname = window.location.pathname;
                        const adminIndex = pathname.toLowerCase().indexOf('/admin/');
                        const appBase = adminIndex >= 0 ? pathname.slice(0, adminIndex) : '';
                        window.location.href = `${window.location.origin}${appBase}/admin/parts`;
                    }, 3000);
                } else {
                    message.style.display = 'block';
                    message.innerHTML = `<div class="alert alert-danger">${response.message}</div>`;
                    if (progress) progress.style.display = 'none';
                }
            } catch (error) {
                console.error("JSON parse error:", error);
                message.style.display = 'block';
                message.innerHTML = `<div class="alert alert-danger">Server response error. Please check the console for details.</div>`;
            }
        } else {
            message.style.display = 'block';
            message.innerHTML = `<div class="alert alert-danger">HTTP Error: ${xhr.status}. Please try again.</div>`;
        }
    });

    xhr.addEventListener('error', function () {
        message.style.display = 'block';
        message.innerHTML = `<div class="alert alert-danger">Network error. Please check your connection and try again.</div>`;
    });

    xhr.open('POST', form.action, true);
    xhr.send(formData);
}
