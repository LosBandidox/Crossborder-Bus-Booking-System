// File: login.js
// Purpose: Manages the login form submission process on the login page in the International Bus Booking System.
// Validates CAPTCHA and form input, sends login details to the server, and redirects or shows errors based on the response.

// Sets up the page to handle the login form
// - Adds an event listener using document.addEventListener(), a built-in JavaScript method, for the “DOMContentLoaded” event.
// - Runs an anonymous arrow function when the page’s HTML is fully loaded.
// - Prepares the login form to handle submissions when the page opens.
document.addEventListener("DOMContentLoaded", () => {
    // Finds the login form
    // - A user-defined constant named form, holding the result of document.getElementById(), a built-in JavaScript method, targeting the form with ID 'loginForm'.
    // - Targets the form where users enter their login details like username and password.
    // - Prepares the form for submission handling.
    const form = document.getElementById("loginForm");

    // Sets up form submission
    // - Adds an event listener to form using addEventListener() for the 'submit' event.
    // - Calls the user-defined handleLogin() function when the form is submitted.
    // - Triggers the login process when the user submits their details.
    form.addEventListener("submit", handleLogin);
});

// Function to process login requests
// - A user-defined JavaScript function named handleLogin, with one input: event (a form submission event object).
// - Stops the form from refreshing, checks CAPTCHA and form validity, sends login data, and redirects or shows errors.
// - Handles user login attempts with security checks for the system.
function handleLogin(event) {
    // Stops the page from refreshing
    // - Calls preventDefault(), a built-in JavaScript method of the event object, to stop the form from reloading the page.
    // - Keeps the user on the login page after submitting.
    // - Makes the login process smooth without page reloads.
    event.preventDefault();

    // Gets CAPTCHA input and solution
    // - User-defined constants named userAnswer and correctAnswer, holding the value properties of inputs with IDs 'captchaAnswer' and 'captchaSolution' using document.getElementById().
    // - Stores the user’s CAPTCHA answer and the correct answer for comparison.
    // - Prepares to verify the CAPTCHA before login.
    const userAnswer = document.getElementById("captchaAnswer").value;
    const correctAnswer = document.getElementById("captchaSolution").value;

    // Checks if the CAPTCHA is correct
    // - A conditional statement comparing userAnswer to correctAnswer using !==.
    // - Stops the login and updates the CAPTCHA if the user’s answer is wrong.
    // - Ensures only valid CAPTCHA entries proceed to login.
    if (userAnswer !== correctAnswer) {
        // Shows an error message
        // - Calls alert(), a built-in JavaScript method, with “CAPTCHA verification failed. Please try again.”.
        // - Tells the user their CAPTCHA answer was incorrect.
        // - Prompts them to try again with a new CAPTCHA.
        alert("CAPTCHA verification failed. Please try again.");

        // Creates a new CAPTCHA question
        // - User-defined constants named num1 and num2, holding random integers from 1 to 10 using Math.floor() and Math.random(), built-in JavaScript methods.
        // - Generates two numbers for a simple addition question (e.g., “What is 5 + 3?”).
        // - Prepares a new CAPTCHA challenge for the user.
        const num1 = Math.floor(Math.random() * 10) + 1;
        const num2 = Math.floor(Math.random() * 10) + 1;

        // Updates the CAPTCHA question
        // - Sets the textContent property of the element with ID 'captchaQuestion' to a string like “What is 5 + 3?”.
        // - Shows the new addition question to the user.
        // - Refreshes the CAPTCHA display on the login page.
        document.getElementById("captchaQuestion").textContent = `What is ${num1} + ${num2}?`;

        // Updates the CAPTCHA solution
        // - Sets the value property of the input with ID 'captchaSolution' to the sum of num1 and num2.
        // - Stores the correct answer for the new CAPTCHA question.
        // - Prepares the form for the next CAPTCHA attempt.
        document.getElementById("captchaSolution").value = num1 + num2;

        // Clears the user’s CAPTCHA input
        // - Sets the value property of the input with ID 'captchaAnswer' to an empty string.
        // - Resets the user’s answer field for a new attempt.
        // - Keeps the form ready for the next CAPTCHA entry.
        document.getElementById("captchaAnswer").value = "";

        // Stops the login process
        // - Uses return to exit the function if the CAPTCHA is incorrect.
        // - Prevents further login steps until the CAPTCHA is correct.
        // - Ensures security by requiring CAPTCHA verification.
        return;
    }

    // Checks if the form is valid
    // - Calls a user-defined validateLoginForm() function (assumed to be in another file) to check form inputs like username and password.
    // - Ensures the login details are valid before sending to the server.
    // - Stops the process if validation fails.
    if (validateLoginForm()) {
        // Collects form data
        // - A user-defined constant named formData, holding a built-in JavaScript FormData object created from the form with ID 'loginForm'.
        // - Gathers login details like username and password entered by the user.
        // - Prepares the data to send to the server for login.
        const formData = new FormData(document.getElementById("loginForm"));

        // Sends a web request for login
        // - Uses the built-in JavaScript fetch() method to send a POST request to a PHP file at '../../php/Login.php'.
        // - Starts a chain of steps to process the server’s reply and handle the login.
        // - Sends the user’s login details to verify their account.
        fetch('../../php/Login.php', {
            // Sets the request type
            // - A method property set to 'POST', a built-in JavaScript fetch option that tells the server to expect data.
            // - Ensures the server knows this is a login submission request.
            method: 'POST',

            // Sends the form data
            // - A body property set to the formData object, containing the user’s login details.
            // - Includes the username and password for verification.
            // - Provides the data in a format the server can process.
            body: formData
        })
            // Processes the server’s reply
            // - A built-in JavaScript .then() method that converts the response to a JSON object using response.json().
            // - Turns the server’s reply into a usable object with login status and details.
            // - Prepares the response for success or error handling.
            .then(response => response.json())
            // Handles the response
            // - A .then() method that takes the data object and checks for login success or failure.
            // - Redirects to a new page on success or shows an error and refreshes CAPTCHA.
            .then(data => {
                // Logs the response for debugging
                // - Uses console.log(), a built-in JavaScript method, to write “Response:” and the data object to the developer tools.
                // - Helps developers check what the server sent back.
                // - Tracks the server’s reply for troubleshooting.
                console.log('Response:', data);

                // Checks for successful login
                // - A conditional statement checking if data.status equals “success”.
                // - Redirects to a new page on success or handles errors otherwise.
                // - Decides the next step based on the server’s reply.
                if (data.status === "success") {
                    // Redirects to a new page
                    // - Sets window.location.href, a built-in JavaScript property, to data.redirect (a URL from the server).
                    // - Sends the user to the appropriate page, like a dashboard, after login.
                    // - Completes the login process by navigating to the next page.
                    window.location.href = data.redirect;
                } else {
                    // Shows an error message
                    // - Calls alert() with “Error:” plus data.message (e.g., “Invalid credentials”).
                    // - Tells the user why the login failed, based on the server’s reply.
                    // - Provides feedback on login issues.
                    alert("Error: " + data.message);

                    // Creates a new CAPTCHA question
                    // - User-defined constants named num1 and num2, holding random integers from 1 to 10 using Math.floor() and Math.random().
                    // - Generates a new addition question for the CAPTCHA.
                    // - Prepares a fresh CAPTCHA challenge after a failed login.
                    const num1 = Math.floor(Math.random() * 10) + 1;
                    const num2 = Math.floor(Math.random() * 10) + 1;

                    // Updates the CAPTCHA question
                    // - Sets the textContent of the element with ID 'captchaQuestion' to a string like “What is 5 + 3?”.
                    // - Shows the new question to the user.
                    // - Refreshes the CAPTCHA display for the next attempt.
                    document.getElementById("captchaQuestion").textContent = `What is ${num1} + ${num2}?`;

                    // Updates the CAPTCHA solution
                    // - Sets the value of the input with ID 'captchaSolution' to the sum of num1 and num2.
                    // - Stores the correct answer for the new CAPTCHA.
                    // - Prepares the form for the next login attempt.
                    document.getElementById("captchaSolution").value = num1 + num2;

                    // Clears the user’s CAPTCHA input
                    // - Sets the value of the input with ID 'captchaAnswer' to an empty string.
                    // - Resets the user’s answer field for a new attempt.
                    // - Keeps the form ready for the next CAPTCHA entry.
                    document.getElementById("captchaAnswer").value = "";
                }
            })
            // Handles errors during the fetch
            // - A built-in JavaScript .catch() method that logs any errors in the fetch chain.
            // - Writes error details to the developer tools and shows a generic error message.
            // - Ensures the page doesn’t break if the login request fails.
            .catch(error => {
                // Logs the error for debugging
                // - Uses console.error(), a built-in JavaScript method, to write “Error during login:” and the error details to the developer tools.
                // - Helps developers find issues like bad internet or server errors.
                console.error('Error during login:', error);

                // Shows a generic error message
                // - Calls alert() with “An error occurred during login.”.
                // - Tells the user there was a problem with their login attempt.
                // - Provides basic feedback when specific error details aren’t available.
                alert("An error occurred during login.");
            });
    }
}