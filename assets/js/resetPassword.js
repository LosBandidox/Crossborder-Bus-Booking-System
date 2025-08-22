// File: resetPassword.js
// Purpose: Manages the reset password form submission process on the reset password page in the International Bus Booking System.
// Checks for a valid reset token in the URL, validates form input, sends the new password to the server, and redirects or shows errors.

// Sets up the page to handle the reset password form
// - Adds an event listener using document.addEventListener(), a built-in JavaScript method, for the “DOMContentLoaded” event.
// - Runs an anonymous arrow function when the page’s HTML is fully loaded.
// - Prepares the reset password form and checks the token when the page opens.
document.addEventListener("DOMContentLoaded", () => {
    // Finds the reset password form
    // - A user-defined constant named form, holding the result of document.getElementById(), a built-in JavaScript method, targeting the form with ID 'resetPasswordForm'.
    // - Targets the form where users enter their new password and token.
    // - Prepares the form for submission handling.
    const form = document.getElementById("resetPasswordForm");

    // Gets URL query parameters
    // - A user-defined constant named urlParams, holding a built-in JavaScript URLSearchParams object created from window.location.search.
    // - Parses the URL’s query string to extract parameters like the reset token.
    // - Prepares to retrieve the token for password reset verification.
    const urlParams = new URLSearchParams(window.location.search);

    // Gets the reset token
    // - A user-defined constant named token, holding the result of urlParams.get('token'), a built-in method returning a string or null.
    // - Stores the token from the URL, used to verify the password reset request.
    // - Identifies the specific reset request linked to the user’s email.
    const token = urlParams.get('token');

    // Checks if a token is present
    // - A conditional statement checking if token exists (not null or undefined).
    // - Fills the token field if valid, or redirects to login if missing.
    // - Ensures the page only proceeds with a valid reset token.
    if (token) {
        // Sets the token in the form
        // - Sets the value property, a built-in JavaScript feature, of the input with ID 'token' using document.getElementById().
        // - Fills a hidden form field with the token from the URL.
        // - Prepares the form to send the token with the new password.
        document.getElementById("token").value = token;
    } else {
        // Shows an error message
        // - Calls alert(), a built-in JavaScript method, with “Invalid or missing token.”.
        // - Tells the user the reset link is invalid or lacks a token.
        // - Informs them they can’t proceed without a valid link.
        alert("Invalid or missing token.");

        // Redirects to the login page
        // - Sets window.location.href, a built-in JavaScript property, to navigate to '../../frontend/Login.html'.
        // - Sends the user to the login page if the token is missing.
        // - Prevents unauthorized access to the reset page.
        window.location.href = '../../frontend/Login.html';
    }

    // Sets up form submission
    // - Adds an event listener to form using addEventListener() for the 'submit' event.
    // - Calls the user-defined handleResetPassword() function when the form is submitted.
    // - Triggers the password reset process when the user submits their new password.
    form.addEventListener("submit", handleResetPassword);
});

// Function to process password reset requests
// - A user-defined JavaScript function named handleResetPassword, with one input: event (a form submission event object).
// - Stops the form from refreshing, validates input, sends reset data, and redirects or shows errors.
// - Handles the user’s request to set a new password using a reset token.
function handleResetPassword(event) {
    // Stops the page from refreshing
    // - Calls preventDefault(), a built-in JavaScript method of the event object, to stop the form from reloading the page.
    // - Keeps the user on the reset password page after submitting.
    // - Makes the reset process smooth without page reloads.
    event.preventDefault();

    // Checks if the form is valid
    // - Calls a user-defined validateResetPasswordForm() function (assumed to be in another file) to check form inputs like the new password.
    // - Ensures the new password meets requirements before sending to the server.
    // - Stops the process if validation fails.
    if (validateResetPasswordForm()) {
        // Collects form data
        // - A user-defined constant named formData, holding a built-in JavaScript FormData object created from the form with ID 'resetPasswordForm'.
        // - Gathers the new password and token entered in the form.
        // - Prepares the data to send to the server for the reset request.
        const formData = new FormData(document.getElementById("resetPasswordForm"));

        // Sends a web request for password reset
        // - Uses the built-in JavaScript fetch() method to send a POST request to a PHP file at '../../php/resetPassword.php'.
        // - Starts a chain of steps to process the server’s reply and handle the reset.
        // - Sends the new password and token to update the user’s account.
        fetch('../../php/resetPassword.php', {
            // Sets the request type
            // - A method property set to 'POST', a built-in JavaScript fetch option that tells the server to expect data.
            // - Ensures the server knows this is a data submission request.
            method: 'POST',

            // Sends the form data
            // - A body property set to the formData object, containing the new password and token.
            // - Includes the data needed to reset the user’s password.
            // - Provides the data in a format the server can process.
            body: formData
        })
            // Processes the server’s reply
            // - A built-in JavaScript .then() method that converts the response to text using response.text().
            // - Gets the server’s reply as a raw string for further processing.
            // - Prepares the response to show a message or redirect.
            .then(response => response.text())
            // Handles the response
            // - A .then() method that takes the text string and checks for success or error.
            // - Shows a success message and redirects on success, or displays an error.
            .then(text => {
                // Logs the response for debugging
                // - Uses console.log(), a built-in JavaScript method, to write “Raw response:” and the text to the developer tools.
                // - Helps developers check what the server sent back.
                // - Tracks the server’s reply for troubleshooting.
                console.log('Raw response:', text);

                // Checks for a successful reset
                // - A conditional statement checking if the text includes “Password reset successfully” using the built-in includes() method.
                // - Handles success by showing a message and redirecting, or shows an error otherwise.
                // - Decides the next step based on the server’s reply.
                if (text.includes("Password reset successfully")) {
                    // Shows a success message
                    // - Calls alert() with “Password reset successfully! Please log in.”.
                    // - Tells the user their password was updated successfully.
                    // - Confirms the reset process worked.
                    alert("Password reset successfully! Please log in.");

                    // Redirects to the login page
                    // - Sets window.location.href to navigate to '../forms/Login.html'.
                    // - Sends the user to the login page to use their new password.
                    // - Completes the reset process by moving to login.
                    window.location.href = '../forms/Login.html';
                } else {
                    // Shows an error message
                    // - Calls alert() with “Error:” plus the server’s text (e.g., “Invalid token”).
                    // - Tells the user why the reset failed.
                    // - Provides feedback on issues with the request.
                    alert("Error: " + text);
                }
            })
            // Handles errors during the fetch
            // - A built-in JavaScript .catch() method that logs any errors in the fetch chain.
            // - Writes error details to the developer tools and shows a generic error message.
            // - Ensures the page doesn’t break if the request fails.
            .catch(error => {
                // Logs the error for debugging
                // - Uses console.error(), a built-in JavaScript method, to write “Error during reset password:” and the error details to the developer tools.
                // - Helps developers find issues like bad internet or server errors.
                console.error('Error during reset password:', error);

                // Shows a generic error message
                // - Calls alert() with “An error occurred. Please try again.”.
                // - Tells the user there was a problem with their request.
                // - Provides basic feedback when specific error details aren’t available.
                alert("An error occurred. Please try again.");
            });
    }
}