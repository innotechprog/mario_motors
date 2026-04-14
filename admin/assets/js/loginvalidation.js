const form = document.querySelector('.needs-validation');
const emailLoginInput = document.getElementById('yourEmail');
const passwordInput = document.getElementById('yourPassword');
const errorMessageDiv = document.getElementById('error-message');
const forgotPasswordForm = document.getElementById('forgotPasswordForm');
const resetMessageDiv = document.getElementById('reset-message');

async function parseJsonSafe(response) {
  const text = await response.text();
  if (!text || !text.trim()) {
    return null;
  }

  try {
    return JSON.parse(text);
  } catch (error) {
    console.error('Invalid JSON response:', text);
    return null;
  }
}

form.addEventListener('submit', async (e) => {
  e.preventDefault(); // Prevent default form submission

  // Clear previous errors
  emailLoginInput.classList.remove('is-invalid');
  passwordInput.classList.remove('is-invalid');
  errorMessageDiv.classList.add('d-none'); // Hide the error message div

  // Check if all fields are filled before submitting
  if (!emailLoginInput.value || !passwordInput.value) {
    // Show error message if fields are empty
    errorMessageDiv.classList.remove('d-none');
    errorMessageDiv.textContent = 'Please fill in all fields.';

    // Add 'is-invalid' class to empty fields
    if (!emailLoginInput.value) {
      emailLoginInput.classList.add('is-invalid');
    }
    if (!passwordInput.value) {
      passwordInput.classList.add('is-invalid');
    }
    return; // Stop further execution
  }

  const formData = new FormData(form);

  try {
    const response = await fetch(form.action, {
      method: 'POST',
      body: formData
    });

    const data = await parseJsonSafe(response);

    if (!data) {
      errorMessageDiv.classList.remove('d-none');
      errorMessageDiv.textContent = 'Server returned an invalid response. Please try again.';
      return;
    }

    if (data.status === 'success') {
      // Redirect if login is successful
      window.location.href = data.redirect;
    } else {
      // Show error messages at the top
      errorMessageDiv.classList.remove('d-none');
      errorMessageDiv.textContent = data.message;

      // Add 'is-invalid' class to inputs with errors
     
    }
  } catch (error) {
    console.error('Error:', error);
    errorMessageDiv.classList.remove('d-none');
    errorMessageDiv.textContent = 'Something went wrong. Please try again later.';
  }
});

if (forgotPasswordForm) {
  forgotPasswordForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const emailInput = document.getElementById('resetEmail');
    const newPasswordInput = document.getElementById('resetNewPassword');
    const confirmPasswordInput = document.getElementById('resetConfirmPassword');

    resetMessageDiv.classList.add('d-none');
    resetMessageDiv.classList.remove('alert-danger', 'alert-success');

    if (!emailInput.value || !newPasswordInput.value || !confirmPasswordInput.value) {
      resetMessageDiv.classList.remove('d-none');
      resetMessageDiv.classList.add('alert-danger');
      resetMessageDiv.textContent = 'Please fill in all reset fields.';
      return;
    }

    if (newPasswordInput.value.length < 8) {
      resetMessageDiv.classList.remove('d-none');
      resetMessageDiv.classList.add('alert-danger');
      resetMessageDiv.textContent = 'Password must be at least 8 characters long.';
      return;
    }

    if (newPasswordInput.value !== confirmPasswordInput.value) {
      resetMessageDiv.classList.remove('d-none');
      resetMessageDiv.classList.add('alert-danger');
      resetMessageDiv.textContent = 'Passwords do not match.';
      return;
    }

    try {
      const formData = new FormData(forgotPasswordForm);
      const response = await fetch(forgotPasswordForm.action, {
        method: 'POST',
        body: formData
      });

      const data = await parseJsonSafe(response);
      if (!data) {
        resetMessageDiv.classList.remove('d-none');
        resetMessageDiv.classList.add('alert-danger');
        resetMessageDiv.textContent = 'Server returned an invalid response. Please try again.';
        return;
      }

      resetMessageDiv.classList.remove('d-none');

      if (data.status === 'success') {
        resetMessageDiv.classList.add('alert-success');
        resetMessageDiv.textContent = data.message;
        forgotPasswordForm.reset();
      } else {
        resetMessageDiv.classList.add('alert-danger');
        resetMessageDiv.textContent = data.message;
      }
    } catch (error) {
      console.error('Error:', error);
      resetMessageDiv.classList.remove('d-none');
      resetMessageDiv.classList.add('alert-danger');
      resetMessageDiv.textContent = 'Unable to reset password right now. Please try again later.';
    }
  });
}