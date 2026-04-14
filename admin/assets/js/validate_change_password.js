$(document).ready(function() {
    $(".password_form").submit(function(e) {
        e.preventDefault(); // Prevent normal form submission

        let currentPassword = $("#currentPassword").val().trim();
        let newPassword = $("#newPassword").val().trim();
        let renewPassword = $("#renewPassword").val().trim();
        let isValid = true;

        // Remove previous error messages
        $(".error-message").remove();
        $(".alert").remove();

        // Validation
        if (currentPassword === "") {
            $("#currentPassword").after("<small class='error-message text-danger d-block'>Current password is required</small>");
            isValid = false;
        }

        if (newPassword === "") {
            $("#newPassword").after("<small class='error-message text-danger d-block'>New password is required</small>");
            isValid = false;
        }

        if (renewPassword === "") {
            $("#renewPassword").after("<small class='error-message text-danger d-block'>Please re-enter new password</small>");
            isValid = false;
        }

        if (newPassword !== "" && renewPassword !== "" && newPassword !== renewPassword) {
            $("#renewPassword").after("<small class='error-message text-danger d-block'>Passwords do not match</small>");
            isValid = false;
        }

        if (!isValid) return; // Stop form submission if validation fails

        // Disable submit button to prevent multiple clicks
        $(".password_form button[type='submit']").prop("disabled", true).text("Processing...");

        // AJAX request to submit form data
        $.ajax({
            type: "POST",
            url: "processes/process_user.php",
            data: $(".password_form").serialize(),
            dataType: "json",
            success: function(response) {
                $(".alert").remove(); // Remove previous messages
                
                if (response && response.success) {
                    $(".password_form")[0].reset(); // Reset the form
                    $(".text-center").before("<div class='alert alert-success'>Password changed successfully. Redirecting...</div>");
                    
                    // Redirect after 3 seconds
                    setTimeout(function() {
                        window.location.href = "user-profile.php"; // Change to the correct redirect path
                    }, 3000);
                } else {
                    let errorMsg = response && response.message ? response.message : "An unexpected error occurred.";
                    $(".text-center").before("<div class='alert alert-danger'>" + errorMsg + "</div>");
                }
            },
            error: function() {
                $(".text-center").before("<div class='alert alert-danger'>An error occurred. Please try again.</div>");
            },
            complete: function() {
                $(".password_form button[type='submit']").prop("disabled", false).text("Change Password");
            }
        });
    });
});
