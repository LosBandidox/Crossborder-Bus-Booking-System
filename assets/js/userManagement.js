// File: admin_users.js
// Purpose: Manages user administration on the admin user management page in the International Bus Booking System.
// Fetches and displays a list of users, shows forms to add or edit users, saves user data, and deletes users.

// Function to get and show users
// - A user-defined JavaScript function named fetchUsers, with no inputs.
// - Sends a web request to get user data from the server and fills a table with user details, including edit and delete buttons.
// - Populates the user management table for the admin to view all users.
function fetchUsers() {
    // Sends a web request to get user data
    // - Uses the built-in JavaScript fetch() method to request data from a PHP file at '../../php/fetchUsers.php'.
    // - Starts a chain of steps to process the server’s reply and show users in a table.
    // - Fetches all user records for the admin to display on the page.
    fetch('../../php/fetchUsers.php')
        // Processes the server’s reply
        // - A built-in JavaScript .then() method that converts the response to a JSON object using response.json().
        // - Turns the server’s reply into a usable object with an array of user data.
        // - Prepares the user details to fill the table.
        .then(response => response.json())
        // Handles the user data
        // - A .then() method that takes the data object and updates the table with user rows.
        // - Clears the table and adds rows for each user, including buttons for editing and deleting.
        .then(data => {
            // Finds the table section
            // - A user-defined constant named tableBody, holding the result of a built-in JavaScript document.getElementById() method that finds the <tbody> element with ID 'userTableBody'.
            // - Targets the area where user rows will be added.
            // - Lets the code update the table with user data.
            const tableBody = document.getElementById("userTableBody");

            // Clears old table rows
            // - Sets tableBody.innerHTML, a built-in JavaScript property, to an empty string.
            // - Removes any existing rows to avoid showing duplicate or outdated users.
            // - Prepares the table for fresh user data.
            tableBody.innerHTML = "";

            // Goes through each user to make a table row
            // - A forEach loop, a built-in JavaScript method that runs code for each user in the data array.
            // - Creates a row for each user with details like UserID and Name, plus edit and delete buttons.
            // - Builds the table dynamically for the admin’s user management view.
            data.forEach(user => {
                // Builds a table row’s content
                // - A user-defined variable named row, holding a template literal (text with data inside backticks) with user details.
                // - Creates a <tr> row with cells for UserID, Name, Email, PhoneNumber, Role, and buttons calling editUser() and deleteUser() with user data.
                // - Shows user info and provides clickable options for editing or deleting.
                let row = `<tr>
                    <td>${user.UserID}</td>
                    <td>${user.Name}</td>
                    <td>${user.Email}</td>
                    <td>${user.PhoneNumber}</td>
                    <td>${user.Role}</td>
                    <td>
                        <button onclick="editUser(${user.UserID}, '${user.Name}', '${user.Email}', '${user.PhoneNumber}', '${user.Role}')">Edit</button>
                        <button onclick="deleteUser(${user.UserID})">Delete</button>
                    </td>
                </tr>`;

                // Adds the row to the table
                // - Updates tableBody.innerHTML by adding the row to the existing content.
                // - Places the user row in the table on the webpage.
                // - Shows the user’s details and action buttons in the table.
                tableBody.innerHTML += row;
            });
        })
        // Handles errors during the fetch
        // - A built-in JavaScript .catch() method that logs any errors in the fetch chain.
        // - Writes error details to the developer tools for troubleshooting.
        // - Ensures the page doesn’t break if fetching fails.
        .catch(error => console.error("Error fetching users:", error));
}

// Function to show the form for adding a new user
// - A user-defined JavaScript function named showAddUserForm, with no inputs.
// - Clears and displays a form with default values for adding a new user.
// - Prepares the form for the admin to enter a new user’s details.
function showAddUserForm() {
    // Sets the form title
    // - Sets the textContent property, a built-in JavaScript feature, of the element with ID 'formTitle' to “Add User” using document.getElementById().
    // - Changes the form’s title to show it’s for adding a new user.
    // - Helps the admin know they’re creating a new account.
    document.getElementById("formTitle").textContent = "Add User";

    // Clears the user ID field
    // - Sets the value property of the input element with ID 'userId' to an empty string.
    // - Ensures the ID field is empty since new users don’t have an ID yet.
    // - Prepares the form for a new user entry.
    document.getElementById("userId").value = "";

    // Clears the name field
    // - Sets the value property of the input element with ID 'name' to an empty string.
    // - Clears the name field for the admin to enter a new name.
    // - Keeps the form ready for new user data.
    document.getElementById("name").value = "";

    // Clears the email field
    // - Sets the value property of the input element with ID 'email' to an empty string.
    // - Clears the email field for the admin to enter a new email.
    // - Ensures the form is empty for new user details.
    document.getElementById("email").value = "";

    // Clears the phone number field
    // - Sets the value property of the input element with ID 'phoneNumber' to an empty string.
    // - Clears the phone number field for the admin to enter a new phone number.
    // - Prepares the form for new user data.
    document.getElementById("phoneNumber").value = "";

    // Clears the password field
    // - Sets the value property of the input element with ID 'password' to an empty string.
    // - Clears the password field for the admin to enter a new password.
    // - Ensures the form is ready for a new user’s credentials.
    document.getElementById("password").value = "";

    // Sets the default role
    // - Sets the value property of the input element with ID 'role' to “Customer”.
    // - Defaults the role dropdown to “Customer” for new users.
    // - Makes it easy for the admin to set a common role.
    document.getElementById("role").value = "Customer";

    // Shows the form
    // - Sets the style.display property, a built-in JavaScript feature, of the form element with ID 'userForm' to 'block'.
    // - Makes the form visible so the admin can enter new user details.
    // - Displays the form on the user management page.
    document.getElementById("userForm").style.display = "block";
}

// Function to edit an existing user
// - A user-defined JavaScript function named editUser, with five inputs: userId (number), name (string), email (string), phone (string), role (string).
// - Fills a form with existing user data and displays it for editing.
// - Prepares the form for the admin to update a user’s details.
function editUser(userId, name, email, phone, role) {
    // Sets the form title
    // - Sets the textContent property of the element with ID 'formTitle' to “Edit User”.
    // - Changes the form’s title to show it’s for editing an existing user.
    // - Helps the admin know they’re updating a user’s account.
    document.getElementById("formTitle").textContent = "Edit User";

    // Fills the user ID field
    // - Sets the value property of the input element with ID 'userId' to the userId parameter.
    // - Puts the user’s ID into the form to identify which user is being edited.
    // - Ensures the form links to the correct user record.
    document.getElementById("userId").value = userId;

    // Fills the name field
    // - Sets the value property of the input element with ID 'name' to the name parameter.
    // - Puts the user’s current name into the form for editing.
    // - Makes it easy for the admin to update the name.
    document.getElementById("name").value = name;

    // Fills the email field
    // - Sets the value property of the input element with ID 'email' to the email parameter.
    // - Puts the user’s current email into the form for editing.
    // - Allows the admin to update the email address.
    document.getElementById("email").value = email;

    // Fills the phone number field
    // - Sets the value property of the input element with ID 'phoneNumber' to the phone parameter.
    // - Puts the user’s current phone number into the form for editing.
    // - Lets the admin update the phone number.
    document.getElementById("phoneNumber").value = phone;

    // Clears the password field
    // - Sets the value property of the input element with ID 'password' to an empty string.
    // - Leaves the password field blank for security, requiring manual entry if needed.
    // - Prevents accidental password changes during editing.
    document.getElementById("password").value = "";

    // Sets the role
    // - Sets the value property of the input element with ID 'role' to the role parameter.
    // - Puts the user’s current role into the form’s dropdown for editing.
    // - Allows the admin to change the user’s role, like from Customer to Staff.
    document.getElementById("role").value = role;

    // Shows the form
    // - Sets the style.display property of the form element with ID 'userForm' to 'block'.
    // - Makes the form visible with the user’s data filled in for editing.
    // - Displays the form on the user management page for updates.
    document.getElementById("userForm").style.display = "block";
}

// Function to hide the user form
// - A user-defined JavaScript function named hideUserForm, with no inputs.
// - Hides the form used for adding or editing users.
// - Makes the form invisible to clean up the page after use.
function hideUserForm() {
    // Hides the form
    // - Sets the style.display property of the form element with ID 'userForm' to 'none'.
    // - Makes the form invisible after adding or editing a user.
    // - Keeps the user management page tidy.
    document.getElementById("userForm").style.display = "none";
}

// Function to save a user (add or edit)
// - A user-defined JavaScript function named saveUser, with one input (event: a form submission event object).
// - Stops the form submission, collects form data, sends it to the server, and refreshes the user list if successful.
// - Saves new or updated user details and updates the table on the page.
function saveUser(event) {
    // Stops the page from refreshing
    // - Calls preventDefault(), a built-in JavaScript method of the event object, to stop the form from reloading the page.
    // - Keeps the admin on the user management page after submitting.
    // - Makes the save process smooth without page reloads.
    event.preventDefault();

    // Gets form input values
    // - User-defined variables named userId, name, email, phoneNumber, password, and role, holding the value properties of input elements with corresponding IDs, found using document.getElementById().
    // - Stores the data entered in the form, like user ID and email, for sending to the server.
    // - Prepares the user’s details for saving, whether adding or editing.
    let userId = document.getElementById("userId").value;
    let name = document.getElementById("name").value;
    let email = document.getElementById("email").value;
    let phoneNumber = document.getElementById("phoneNumber").value;
    let password = document.getElementById("password").value;
    let role = document.getElementById("role").value;

    // Sends a web request to save user data
    // - Uses the built-in JavaScript fetch() method to send a POST request to a PHP file at '../../php/saveUser.php' with a JSON payload.
    // - Starts a chain of steps to process the server’s reply and save the user.
    // - Sends the user’s details to the server to add or update their account.
    fetch('../../php/saveUser.php', {
        // Sets the request type
        // - A method property set to 'POST', a built-in JavaScript fetch option that tells the server to expect data.
        // - Ensures the server knows this is a save request.
        method: "POST",

        // Sets the data format
        // - A headers property with 'Content-Type' set to 'application/json', a built-in JavaScript fetch option.
        // - Tells the server the data is in JSON format.
        // - Makes sure the server can read the sent data correctly.
        headers: { "Content-Type": "application/json" },

        // Sends the form data
        // - A body property set to a JSON string, created using the built-in JSON.stringify() method, containing userId, name, email, phoneNumber, password, and role.
        // - Includes the user’s details in a structured format for the server.
        // - Provides the info needed to save the user account.
        body: JSON.stringify({ userId, name, email, phoneNumber, password, role })
    })
        // Processes the server’s reply
        // - A built-in JavaScript .then() method that converts the response to text using response.text().
        // - Gets the server’s reply as a raw string for further processing.
        // - Prepares the response to show a message and update the table.
        .then(response => response.text())
        // Handles the save result
        // - A .then() method that takes the text string and shows a message, refreshes the table, and hides the form.
        // - Updates the page based on the server’s reply about the save operation.
        .then(data => {
            // Shows a message
            // - Calls alert(), a built-in JavaScript method, with the data (e.g., “User saved successfully”).
            // - Tells the admin the result of the save operation, like success or error.
            // - Confirms the action or informs about issues.
            alert(data);

            // Refreshes the user table
            // - Calls the user-defined fetchUsers() function to reload the user list.
            // - Updates the table with the latest user data after saving.
            // - Ensures the table shows the new or updated user.
            fetchUsers();

            // Hides the form
            // - Calls the user-defined hideUserForm() function to hide the form.
            // - Clears the form from view after saving the user.
            // - Keeps the user management page tidy.
            hideUserForm();
        })
        // Handles errors during the save
        // - A built-in JavaScript .catch() method that logs any errors in the fetch chain.
        // - Writes error details to the developer tools for troubleshooting.
        // - Ensures the page doesn’t break if saving fails.
        .catch(error => console.error("Error saving user:", error));
}

// Function to delete a user
// - A user-defined JavaScript function named deleteUser, with one input (userId: a number).
// - Confirms deletion with the admin, sends a request to delete the user, and refreshes the table if successful.
// - Removes a user from the system and updates the user management page.
function deleteUser(userId) {
    // Asks for confirmation
    // - Uses the built-in JavaScript confirm() method to show a dialog asking “Are you sure you want to delete this user?”.
    // - Returns early if the admin cancels (returns false), stopping the deletion.
    // - Ensures the admin intends to delete the user before proceeding.
    if (!confirm("Are you sure you want to delete this user?")) return;

    // Sends a web request to delete the user
    // - Uses the built-in JavaScript fetch() method to send a POST request to a PHP file at '../../php/deleteUser.php' with a JSON payload.
    // - Starts a chain of steps to process the server’s reply and delete the user.
    // - Sends the userId to the server to remove the user’s account.
    fetch('../../php/deleteUser.php', {
        // Sets the request type
        // - A method property set to 'POST', a built-in JavaScript fetch option that tells the server to expect data.
        // - Ensures the server knows this is a delete request.
        method: "POST",

        // Sets the data format
        // - A headers property with 'Content-Type' set to 'application/json'.
        // - Tells the server the data is in JSON format.
        // - Makes sure the server can read the sent userId.
        headers: { "Content-Type": "application/json" },

        // Sends the user ID
        // - A body property set to a JSON string, created using JSON.stringify(), containing the userId.
        // - Includes the ID of the user to delete.
        // - Provides the info needed to remove the user account.
        body: JSON.stringify({ userId })
    })
        // Processes the server’s reply
        // - A built-in JavaScript .then() method that converts the response to text using response.text().
        // - Gets the server’s reply as a raw string for further processing.
        // - Prepares the response to show a message and update the table.
        .then(response => response.text())
        // Handles the delete result
        // - A .then() method that takes the text string and shows a message and refreshes the table.
        // - Updates the page based on the server’s reply about the deletion.
        .then(data => {
            // Shows a message
            // - Calls alert() with the data (e.g., “User deleted successfully”).
            // - Tells the admin the result of the delete operation, like success or error.
            // - Confirms the action or informs about issues.
            alert(data);

            // Refreshes the user table
            // - Calls fetchUsers() to reload the user list.
            // - Updates the table to remove the deleted user.
            // - Ensures the table shows the latest user data.
            fetchUsers();
        })
        // Handles errors during the delete
        // - A built-in JavaScript .catch() method that logs any errors in the fetch chain.
        // - Writes error details to the developer tools for troubleshooting.
        // - Ensures the page doesn’t break if deletion fails.
        .catch(error => console.error("Error deleting user:", error));
}

// Loads users when the page opens
// - Sets the built-in JavaScript window.onload property to the user-defined fetchUsers() function.
// - Runs fetchUsers when the page fully loads, triggering the 'load' event.
// - Fills the user table with data as soon as the admin opens the user management page.
window.onload = fetchUsers;