// File: adminManagement.js
// Purpose: Gets and updates admin profile details for the Admin Profile Management page in the International Bus Booking System.
// Helps admins view and edit their personal info, like name, phone, and password, on a dedicated profile page.

// Function to get admin details and show them on the page
// - A user-defined JavaScript function named fetchStaffDetails, with no inputs.
// - Sends a web request to get admin data from the server and updates the webpage to show details like name and email.
// - Fills a section of the page with admin info and pre-fills a form for editing in the admin profile section.
function fetchStaffDetails() {
    // Sends a web request to get admin details
    // - Uses the built-in JavaScript fetch() method to ask the server for data from a PHP file at '../../../php/admin/fetchAdminDetails.php'.
    // - Starts a chain of steps to handle the server’s reply and show admin info on the profile page.
    fetch('../../../php/admin/fetchAdminDetails.php')
        // Processes the server’s reply
        // - A built-in JavaScript .then() method that takes the response object and runs a callback function.
        // - Checks if the reply is okay and gets the raw text to work with later.
        // - Makes sure the server sent a valid reply before moving forward.
        .then(response => {
            // Checks for server errors
            // - A conditional statement checking if response.ok, a built-in JavaScript property, is false (meaning an error like 404 or 500).
            // - Throws an error with the response status if the reply isn’t okay, stopping the process.
            // - Helps catch problems early when fetching admin details.
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            // Gets the raw text from the reply
            // - Returns response.text(), a built-in JavaScript method that extracts the reply as plain text.
            // - Prepares the text to be turned into data in the next step.
            // - Ensures the raw data is ready for parsing into admin details.
            return response.text(); // Get raw text first
        })
        // Handles the text reply
        // - A built-in JavaScript .then() method that takes the text and runs a callback function.
        // - Logs the text and turns it into a JavaScript object to show admin info on the page.
        // - Processes the server’s reply to update the profile page.
        .then(text => {
            // Logs the raw text for debugging
            // - Calls console.log(), a built-in JavaScript method that writes the text to the browser’s developer tools.
            // - Shows the raw server reply to help developers spot issues if something goes wrong.
            // - Aids in troubleshooting problems with the admin details fetch.
            console.log('Raw response:', text); // Log raw response

            // Turns text into a JavaScript object
            // - A user-defined constant named data, holding the result of a built-in JavaScript JSON.parse() method that converts the text into an object.
            // - Contains admin details like Name, PhoneNumber, Email, and Role inside a user object, plus a status and message.
            // - Prepares the admin info to display on the profile page.
            const data = JSON.parse(text); // Parse JSON

            // Finds the section on the webpage
            // - A user-defined constant named staffDetails, holding the result of a built-in JavaScript document.getElementById() method that finds an HTML element by its ID.
            // - Targets the element with ID 'staffDetails' in the admin profile page, where admin info will show.
            // - Lets the code update the page with admin details.
            const staffDetails = document.getElementById("staffDetails");

            // Checks if the data was fetched successfully
            // - A conditional statement checking if data.status equals 'success'.
            // - Shows admin details if the fetch worked, or an error message if it didn’t.
            // - Decides what to display based on the server’s reply for the admin profile.
            if (data.status === "success") {
                // Fills the staffDetails section with admin info
                // - Sets the staffDetails.innerHTML property, a built-in JavaScript feature that changes an element’s HTML content, using a template literal (text with data inside backticks).
                // - Adds paragraphs with admin details like Name and Email, wrapped in <span> tags for easy updates later.
                // - Shows the admin’s current info clearly on the profile page.
                staffDetails.innerHTML = `
                    <p><strong>Name:</strong> <span id="currentName">${data.user.Name}</span></p>
                    <p><strong>Phone Number:</strong> <span id="currentPhone">${data.user.PhoneNumber}</span></p>
                    <p><strong>Email:</strong> <span id="currentEmail">${data.user.Email}</span></p>
                    <p><strong>Role:</strong> <span id="currentRole">${data.user.Role}</span></p>
                `;

                // Fills the form with current details
                // - Sets the value property, a built-in JavaScript feature, of input elements with IDs 'name' and 'phone' using document.getElementById().
                // - Puts the admin’s Name and PhoneNumber into the form fields for editing.
                // - Makes it easy for admins to update their profile info.
                document.getElementById("name").value = data.user.Name;
                document.getElementById("phone").value = data.user.PhoneNumber;
            } else {
                // Shows an error message on the page
                // - Sets staffDetails.innerHTML to a paragraph with the error message from data.message.
                // - Tells the admin why the details couldn’t load, like “User not found.”
                // - Keeps the profile page usable even if there’s a problem.
                staffDetails.innerHTML = "<p>Error loading staff details: " + data.message + "</p>";
            }
        })
        // Handles errors during the fetch
        // - A built-in JavaScript .catch() method that runs a callback function if any error happens in the fetch chain.
        // - Logs the error and shows it on the page to keep the admin informed.
        // - Ensures the profile page doesn’t break if something goes wrong.
        .catch(error => {
            // Logs the error for debugging
            // - Calls console.error(), a built-in JavaScript method that writes an error message to the developer tools.
            // - Includes the text 'Error fetching staff details:' and the error details to help fix problems.
            // - Helps developers find issues like bad internet or server errors.
            console.error('Error fetching staff details:', error);

            // Shows an error message on the page
            // - Sets the staffDetails.innerHTML to a paragraph with the error message from error.message.
            // - Tells the admin there was a problem loading their details, like “Network error.”
            // - Keeps the profile page clear and informative even during errors.
            document.getElementById("staffDetails").innerHTML = "<p>Error loading staff details: " + error.message + "</p>";
        });
}

// Function to update admin profile details
// - A user-defined JavaScript function named handleProfileUpdate, with one input (event: a form submission event object).
// - Stops the page from reloading, grabs form data, and sends it to the server to update the admin’s profile.
// - Updates the displayed info and shows a success or error message in the admin profile section.
function handleProfileUpdate(event) {
    // Stops the page from refreshing
    // - Calls preventDefault(), a built-in JavaScript method of the event object, to stop the form from reloading the page.
    // - Keeps the admin on the profile page after submitting the form.
    // - Makes the update process smooth without page reloads.
    event.preventDefault();

    // Gets the name from the form
    // - A user-defined constant named name, holding the value property of the input element with ID 'name', found using the built-in document.getElementById() method.
    // - Stores the new name the admin typed in the form.
    // - Prepares the name to send to the server for updating.
    const name = document.getElementById("name").value;

    // Gets the phone number from the form
    // - A user-defined constant named phone, holding the value property of the input element with ID 'phone', found using document.getElementById().
    // - Stores the new phone number the admin typed in the form.
    // - Prepares the phone number to send to the server for updating.
    const phone = document.getElementById("phone").value;

    // Gets the password from the form
    // - A user-defined constant named password, holding the value property of the input element with ID 'password', found using document.getElementById().
    // - Stores the new password, if the admin typed one in the form.
    // - Prepares the password to send to the server, if provided.
    const password = document.getElementById("password").value;

    // Creates form data to send
    // - A user-defined constant named data, a built-in JavaScript URLSearchParams object that holds key-value pairs for form data.
    // - Adds name and phone using the append() method, and password only if it’s not empty.
    // - Gets the form data ready to send to the server in a URL-encoded format.
    const data = new URLSearchParams();
    data.append("name", name);
    data.append("phone", phone);
    if (password) data.append("password", password);

    // Sends a web request to update admin details
    // - Uses the built-in JavaScript fetch() method to send data to a PHP file at '../../../php/admin/updateAdminDetails.php' with specific settings.
    // - Sends the request as a POST with URL-encoded data to update the admin’s profile.
    // - Starts a chain of steps to handle the server’s reply and update the page.
    fetch('../../../php/admin/updateAdminDetails.php', {
        // Sets the request type
        // - A method property set to 'POST', a built-in JavaScript fetch option that tells the server to expect data.
        // - Ensures the server knows this is an update request.
        method: 'POST',

        // Sets the data format
        // - A headers property with 'Content-Type' set to 'application/x-www-form-urlencoded', a built-in JavaScript fetch option.
        // - Tells the server the data is in a URL-encoded format, like a web form.
        // - Makes sure the server can read the sent data correctly.
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },

        // Sends the form data
        // - A body property set to the data object, containing name, phone, and optional password.
        // - Includes the admin’s updated details to save on the server.
        // - Sends the info needed to update the profile.
        body: data
    })
        // Processes the server’s reply
        // - A built-in JavaScript .then() method that takes the response and converts it to a JSON object using response.json().
        // - Gets the server’s reply into a usable format to check the update status.
        .then(response => response.json())
        // Handles the response data
        // - A .then() method that takes the data object and updates the page or shows an error.
        .then(data => {
            // Checks if the update worked
            // - A conditional statement checking if data.status equals 'success'.
            // - Updates the displayed info and shows a success message if the update worked, or an error if it didn’t.
            // - Updates the profile page based on the server’s reply.
            if (data.status === "success") {
                // Updates the displayed name
                // - Sets the textContent property, a built-in JavaScript feature, of the element with ID 'currentName' to data.user.Name.
                // - Changes the name shown on the page to the updated value.
                // - Keeps the profile page showing the latest name.
                document.getElementById("currentName").textContent = data.user.Name;

                // Updates the displayed phone number
                // - Sets the textContent property of the element with ID 'currentPhone' to data.user.PhoneNumber.
                // - Changes the phone number shown on the page to the updated value.
                // - Keeps the profile page showing the latest phone number.
                document.getElementById("currentPhone").textContent = data.user.PhoneNumber;

                // Shows a success message
                // - Calls alert(), a built-in JavaScript method that shows a pop-up message.
                // - Displays “Profile updated successfully!” to confirm the update.
                // - Lets the admin know their changes were saved.
                alert("Profile updated successfully!");
            } else {
                // Shows an error message
                // - Calls alert(), showing “Error updating profile:” plus data.message (e.g., “Invalid phone number”).
                // - Tells the admin why the update didn’t work, using the server’s message.
                // - Helps admins understand issues with their profile changes.
                alert("Error updating profile: " + data.message);
            }
        })
        // Handles errors during the update
        // - A built-in JavaScript .catch() method that catches any errors in the fetch chain.
        // - Logs the error and shows it to the user to keep them informed.
        // - Keeps the profile page working even if there’s a problem.
        .catch(error => {
            // Logs the error for debugging
            // - Calls console.error() to write the error to the developer tools.
            // - Includes “Error updating profile:” and the error details.
            // - Helps developers fix issues like bad internet or server errors.
            console.error('Error updating profile:', error);

            // Shows an error message
            // - Calls alert() with “Error updating profile:” plus error.message.
            // - Tells the admin there was a problem saving their changes, like “Network error.”
            // - Provides feedback to keep the admin informed during issues.
            alert("Error updating profile: " + error.message);
        });
}

// Sets up the page to show admin details when it loads
// - Adds an event listener to the document for the 'DOMContentLoaded' event, a built-in JavaScript event that runs when the webpage is fully loaded.
// - Calls the user-defined fetchStaffDetails() function to load and show admin info right away.
// - Gets the admin profile page ready to display details as soon as it opens.
document.addEventListener("DOMContentLoaded", fetchStaffDetails);

// Sets up the form to handle updates
// - Adds an event listener to the form with ID 'profileForm' using addEventListener(), a built-in JavaScript method that watches for events.
// - Listens for the 'submit' event, which happens when the admin submits the form, and calls the user-defined handleProfileUpdate() function.
// - Lets admins save their profile changes by submitting the form.
document.getElementById("profileForm").addEventListener("submit", handleProfileUpdate);