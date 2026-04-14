document.getElementById('carForm').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevent default form submission

    // Create FormData object
    const formData = new FormData(this);

    // Send data via fetch
    fetch('processes/process_car.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) // Parse JSON response
    .then(data => {
        if (data.success) {
            // Display success message
            document.getElementById('message').innerHTML = `
                <div class="alert alert-success">${data.message}</div>
            `;
            // Clear the form
            document.getElementById('carForm').reset();
        } else {
            // Display error message
            document.getElementById('message').innerHTML = `
                <div class="alert alert-danger">${data.message}</div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('message').innerHTML = `
            <div class="alert alert-danger">An error occurred. Please try again.</div>
        `;
    });
});
