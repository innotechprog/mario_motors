document.getElementById("carForm").addEventListener("submit", function (e) {
    // Validate the form (without image validation)
    if (!validateForm()) {
        e.preventDefault(); // Prevent form submission if validation fails
        console.log("Form validation failed. Scrolling to first error.");
    } else {
        console.log("Form is valid. Proceeding with normal submission.");
    }
});

function validateForm() {
    let isValid = true;
    let firstErrorElement = null; // Store the first error field to scroll to

    // Clear previous errors
    document.querySelectorAll('.text-danger').forEach(function (el) {
        el.textContent = '';
    });

    function showError(field, errorId, message) {
        document.getElementById(errorId).textContent = message;
        if (!firstErrorElement) {
            firstErrorElement = field; // Capture first error field
        }
        isValid = false;
    }

    // Validate Year
    const year = document.querySelector('select[name="year"]');
    if (!year.value) {
        showError(year, 'year-error', 'Please select a year.');
    }

    // Validate Make
    const make = document.querySelector('input[name="make"]');
    if (!make.value.trim()) {
        showError(make, 'make-error', 'Please enter the car make.');
    }

    // Validate Model
    const model = document.querySelector('input[name="model"]');
    if (!model.value.trim()) {
        showError(model, 'model-error', 'Please enter the car model.');
    }

    // Validate Mileage
    const mileage = document.querySelector('input[name="mileage"]');
    if (!mileage.value || mileage.value < 0) {
        showError(mileage, 'mileage-error', 'Please enter a valid mileage.');
    }

    // Validate Price
    const price = document.querySelector('input[name="price"]');
    if (!price.value || price.value < 0) {
        showError(price, 'price-error', 'Please enter a valid price.');
    }

    // Validate Color
    const color = document.querySelector('input[name="color"]');
    if (!color.value.trim()) {
        showError(color, 'color-error', 'Please enter the car color.');
    }

    // Validate Transmission
    const transmission = document.querySelector('select[name="transmission"]');
    if (!transmission.value) {
        showError(transmission, 'transmission-error', 'Please select the transmission type.');
    }

    // Validate Fuel Type
    const fuelType = document.querySelector('select[name="fuel_type"]');
    if (!fuelType.value) {
        showError(fuelType, 'fuel-type-error', 'Please select the fuel type.');
    }

    // Validate Description
    const description = document.querySelector('textarea[name="description"]');
    if (!description.value.trim()) {
        showError(description, 'description-error', 'Please enter a description.');
    }

    // Validate Condition
    const condition = document.querySelector('select[name="condition"]');
    if (!condition.value) {
        showError(condition, 'condition-error', 'Please select the car condition.');
    }

    // Scroll to the first error if there is one
    if (!isValid && firstErrorElement) {
        firstErrorElement.scrollIntoView({ behavior: "smooth", block: "center" });
    }

    return isValid; // Return validation result
}
