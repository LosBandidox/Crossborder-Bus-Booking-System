// File: forgotPassword.js
// Purpose: Manages the forgot password form submission process on the forgot password page in the International Bus Booking System.
// Validates the form, sends a password reset request to the server, and shows feedback or redirects the user.

// Sets up the page to handle the form
// - Adds an event listener using document.addEventListener(), a built-in JavaScript method, for the “DOMContentLoaded” event.
// - Runs an anonymous arrow function when the page’s HTML is fully loaded.
// - Prepares the forgot password form to handle submissions when the page opens.
document.addEventListener("DOMContentLoaded", () => {
    // Finds the form
    // - A user-defined constant named form, holding the result of document.getElementById(), a built-in JavaScript method, targeting the form with ID 'forgotPasswordForm'.
    // - Targets the form where users enter their email for password reset.
    // - Prepares the form for submission handling.
    const form = document.getElementById("forgotPasswordForm");

    // Sets up form submission
    // - Adds an event listener to form using addEventListener() for the 'submit' event.
    // - Calls the user-defined handleForgotPassword() function when the form is submitted.
    // - Triggers the password reset process when the user submits their email.
    form.addEventListener("submit", handleForgotPassword);
});

// Function to handle password reset requests
// - A user-defined JavaScript function named handleForgotPassword, with one input: event (a form submission event object).
// - Stops the form from refreshing, validates input, sends a reset request, and shows feedback or redirects.
// - Processes the user’s request to reset their password using their email.
function handleForgotPassword(event) {
    // Stops the page from refreshing
    // - Calls preventDefault(), a built-in JavaScript method of the event object, to stop the form from reloading the page.
    // - Keeps the user on the forgot password page after submitting.
    // - Makes the reset process smooth without page reloads.
    event.preventDefault();

    // Checks if the form is valid
    // - Calls a user-defined validateForgotPasswordForm() function (assumed to be in another file) to check form input.
    // - Ensures the email entered is valid before sending to the server.
    // - Stops the process if validation fails.
    if (validateForgotPasswordForm()) {
        // Collects form data
        // - A user-defined constant named formData, holding a built-in JavaScript FormData object created from the form with ID 'forgotPasswordForm'.
        // - Gathers the email entered by the user in the form.
        // - Prepares the data to send to the server for the reset request.
        const formData = new FormData(document.getElementById("forgotPasswordForm"));

        // Sends a web request for password reset
        // - Uses the built-in JavaScript fetch() method to send a POST request to a PHP file at '../../php/forgotPassword.php'.
        // - Starts a chain of steps to process the server’s reply and handle the reset request.
        // - Sends the user’s email to request a password reset link.
        fetch('../../php/forgotPassword.php', {
            // Sets the request type
            // - A method property set to 'POST', a built-in JavaScript fetch option that tells the server to expect data.
            // - Ensures the server knows this is a data submission request.
            method: 'POST',

            // Sends the form data
            // - A body property set to the formData object, containing the user’s email.
            // - Includes the email needed for the password reset process.
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
            // - Shows a message and redirects on success, or displays an error.
            .then(text => {
                // Logs the response for debugging
                // - Uses console.log(), a built-in JavaScript method, to write “Raw response:” and the text to the developer tools.
                // - Helps developers check what the server sent back.
                // - Tracks the server’s reply for troubleshooting.
                console.log('Raw response:', text);

                // Checks for a successful reset request
                // - A conditional statement checking if the text includes “Reset link sent” using the built-in includes() method.
                // - Handles success by showing a message and redirecting, or shows an error otherwise.
                // - Decides the next step based on the server’s reply.
                if (text.includes("Reset link sent")) {
                    // Shows a success message
                    // - Calls alert(), a built-in JavaScript method, with “A password reset link has been sent to your email.”.
                    // - Tells the user their reset link was sent successfully.
                    // - Confirms the password reset request worked.
                    alert("A password reset link has been sent to your email.");

                    // Redirects to the login page
                    // - Sets window.location.href, a built-in JavaScript property, to navigate to '../../frontend/Login.html'.
                    // - Sends the user to the login page after requesting the reset.
                    // - Moves the user to log in with their new password later.
                    window.location.href = '../../frontend/Login.html';
                } else {
                    // Shows an error message
                    // - Calls alert() with “Error:” plus the server’s text (e.g., “Email not found”).
                    // - Tells the user why the reset request failed.
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
                // - Uses console.error(), a built-in JavaScript method, to write “Error during forgot password:” and the error details to the developer tools.
                // - Helps developers find issues like bad internet or server errors.
                console.error('Error during forgot password:', error);

                // Shows a generic error message
                // - Calls alert() with “An error occurred. Please try again.”.
                // - Tells the user there was a problem with their request.
                // - Provides basic feedback when specific error details aren’t available.
                alert("An error occurred. Please try again.");
            });
    }
}