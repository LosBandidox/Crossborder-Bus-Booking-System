// File: signup.js
// Purpose: Handles customer sign-up form submission on the sign-up page in the International Bus Booking System.
// Validates user input, sends sign-up data to the server, and redirects to the login page upon success.

// Sets up the sign-up form when the page loads
// - Adds an event listener to the document for the 'DOMContentLoaded' event, a built-in JavaScript event that runs when the webpage is fully loaded.
// - Runs a callback function to attach a submit handler to the sign-up form.
// - Gets the sign-up page ready to process customer input as soon as it opens.
document.addEventListener("DOMContentLoaded", () => {
    // Finds the sign-up form
    // - A user-defined constant named form, holding the result of a built-in JavaScript document.getElementById() method that finds the form element with ID 'signupForm'.
    // - Targets the form containing input fields like name, email, and password.
    // - Lets the code handle form submission for customer sign-up.
    const form = document.getElementById("signupForm");

    // Sets up form submission
    // - Adds an event listener to the form using addEventListener(), a built-in JavaScript method that watches for events.
    // - Listens for the 'submit' event, which happens when the customer submits the form, and calls the user-defined handleSignup() function.
    // - Starts the sign-up process when the customer submits their details.
    form.addEventListener("submit", handleSignup);
});

// Function to process sign-up form submission
// - A user-defined JavaScript function named handleSignup, taking one input (event: a form submission event object).
// - Stops the form submission, validates input, sends data to the server, and handles the response with a redirect or error message.
// - Creates a new customer account and guides the user to log in if successful.
function handleSignup(event) {
    // Stops the page from refreshing
    // - Calls preventDefault(), a built-in JavaScript method of the event object, to stop the form from reloading the page.
    // - Keeps the customer on the sign-up page after submitting.
    // - Makes the sign-up process smooth without page reloads.
    event.preventDefault();

    // Checks if the form is valid
    // - A conditional statement calling a user-defined validateSignupForm() function (assumed to be in another file, like script.js).
    // - Proceeds with submission only if the form passes validation (e.g., all required fields filled correctly).
    // - Ensures the customer’s input is correct before sending to the server.
    if (validateSignupForm()) {
        // Gets the form data
        // - A user-defined constant named formData, holding a built-in JavaScript FormData object created from the form with ID 'signupForm'.
        // - Collects all form fields, like name, email, and password, as key-value pairs.
        // - Prepares the customer’s sign-up data to send to the server.
        const formData = new FormData(document.getElementById("signupForm"));

        // Sends a web request to submit sign-up data
        // - Uses the built-in JavaScript fetch() method to send a POST request to a PHP file at '../../php/signup.php' with formData.
        // - Starts a chain of steps to process the server’s reply and handle the sign-up.
        // - Sends the customer’s details to the server to create an account.
        fetch('../../php/signup.php', {
            // Sets the request type
            // - A method property set to 'POST', a built-in JavaScript fetch option that tells the server to expect data.
            // - Ensures the server knows this is a sign-up submission.
            method: 'POST',

            // Sends the form data
            // - A body property set to the formData object, containing all form fields.
            // - Includes the customer’s sign-up details, like name and password.
            // - Provides the info needed to create a new account.
            body: formData
        })
            // Processes the server’s reply
            // - A built-in JavaScript .then() method that converts the response to text using response.text().
            // - Gets the server’s reply as a raw string for further processing.
            // - Prepares the response to check for success or errors.
            .then(response => response.text())
            // Handles the response text
            // - A .then() method that takes the text string and checks for a success message.
            // - Logs the response, then redirects or shows an error based on the content.
            .then(text => {
                // Logs the raw response for debugging
                // - Calls console.log(), a built-in JavaScript method, to write “Raw response:” and the text to the developer tools.
                // - Shows the server’s reply to help developers troubleshoot issues.
                // - Tracks what the server sent back during sign-up.
                console.log('Raw response:', text);

                // Checks if sign-up worked
                // - A conditional statement using the built-in includes() method to check if the text contains “User registered successfully”.
                // - Redirects to the login page if successful, or shows an error if not.
                // - Decides the next step based on the server’s reply.
                if (text.includes("User registered successfully")) {
                    // Shows a success message
                    // - Calls alert(), a built-in JavaScript method, with “Sign-up successful! Please log in.”.
                    // - Tells the customer their account was created successfully.
                    // - Confirms the sign-up in the bus booking system.
                    alert("Sign-up successful! Please log in.");

                    // Redirects to the login page
                    // - Sets window.location.href, a built-in JavaScript property, to '../../frontend/Login.html'.
                    // - Sends the customer to the login page to sign in with their new account.
                    // - Guides them to the next step after sign-up.
                    window.location.href = '../../frontend/Login.html';
                } else {
                    // Shows an error message
                    // - Calls alert() with “Error signing up:” plus the text from the server.
                    // - Tells the customer why the sign-up failed, like “Email already exists.”
                    // - Helps customers understand issues with their sign-up attempt.
                    alert("Error signing up: " + text);
                }
            })
            // Handles errors during the fetch
            // - A built-in JavaScript .catch() method that logs any errors in the fetch chain.
            // - Logs the error and shows a generic message to the customer.
            // - Keeps the sign-up page working even if the request fails.
            .catch(error => {
                // Logs an error for debugging
                // - Calls console.error() to write “Error during sign-up:” and the error details to the developer tools.
                // - Helps developers find issues like bad internet or server errors.
                console.error('Error during sign-up:', error);
                // Shows an error message
                // - Calls alert() with a generic “An error occurred during sign-up.” message.
                // - Tells the customer there was a problem without specific details.
                // - Provides basic feedback when errors occur during sign-up.
                alert("An error occurred during sign-up.");
            });
    }
}