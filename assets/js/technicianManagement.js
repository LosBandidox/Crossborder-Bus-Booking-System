// File: technicianProfile.js
// Purpose: Manages profile functionality on the Technician Dashboard in the International Bus Booking System.
// Fetches and displays technician details, switches between maintenance/bus status and profile sections, and updates profile information.

// Tracks the current visible section
// - A user-defined global variable named currentSection, a string initialized to "maintenance".
// - Stores whether the maintenance/bus status or profile section is currently shown on the dashboard.
// - Helps the dashboard switch between views for the technician.
let currentSection = "maintenance";

// Function to get and show technician details
// - A user-defined JavaScript function named fetchStaffDetails, with no inputs.
// - Sends a web request to get technician data from the server and updates the profile section with details like name and email.
// - Fills the technician’s profile section and pre-fills a form for editing on the dashboard.
function fetchStaffDetails() {
    // Sends a web request to get technician details
    // - Uses the built-in JavaScript fetch() method to request data from a PHP file at '/php/technician/fetchStaffDetails.php'.
    // - Starts a chain of steps to process the server’s reply and show technician info.
    // - Fetches details for the logged-in technician to display on the profile section.
    fetch('/php/technician/fetchStaffDetails.php')
        // Processes the server’s reply
        // - A built-in JavaScript .then() method that converts the response to a JSON object using response.json().
        // - Turns the server’s reply into a usable object with technician data.
        // - Prepares the data to update the profile section.
        .then(response => response.json())
        // Handles the technician data
        // - A .then() method that takes the data object and updates the profile section or shows an error.
        // - Displays technician details if successful, or an error message if not.
        .then(data => {
            // Finds the profile section
            // - A user-defined constant named staffDetails, holding the result of a built-in JavaScript document.getElementById() method that finds the element with ID 'staffDetails'.
            // - Targets the area where technician details, like name and phone, will be shown.
            // - Lets the code update the profile section with technician info.
            const staffDetails = document.getElementById("staffDetails");

            // Checks if the fetch was successful
            // - A conditional statement checking if data.status equals 'success'.
            // - Shows technician details and fills the form if successful, or displays an error if not.
            // - Decides what to show based on the server’s reply for the technician’s profile.
            if (data.status === "success") {
                // Fills the profile section with technician info
                // - Sets staffDetails.innerHTML, a built-in JavaScript property, using a template literal (text with data inside backticks).
                // - Adds paragraphs with technician details like Name, PhoneNumber, Email, StaffNumber, and Role, wrapped in <span> tags for easy updates.
                // - Shows the technician’s current info clearly on the dashboard.
                staffDetails.innerHTML = `
                    <p><strong>Name:</strong> <span id="currentName">${data.staff.Name}</span></p>
                    <p><strong>Phone Number:</strong> <span id="currentPhone">${data.staff.PhoneNumber}</span></p>
                    <p><strong>Email:</strong> <span id="currentEmail">${data.staff.Email}</span></p>
                    <p><strong>Staff Number:</strong> <span id="currentStaffNumber">${data.staff.StaffNumber}</span></p>
                    <p><strong>Role:</strong> <span id="currentRole">${data.staff.Role}</span></p>
                `;

                // Fills the form with current details
                // - Sets the value property, a built-in JavaScript feature, of input elements with IDs 'name' and 'phone' using document.getElementById().
                // - Puts the technician’s Name and PhoneNumber into the form fields for editing.
                // - Makes it easy for the technician to update their profile info.
                document.getElementById("name").value = data.staff.Name;
                document.getElementById("phone").value = data.staff.PhoneNumber;
            } else {
                // Shows an error message
                // - Sets staffDetails.innerHTML to a paragraph with “Error loading staff details.”
                // - Tells the technician their details couldn’t load, like if the server can’t find their record.
                // - Keeps the profile section usable even if there’s a problem.
                staffDetails.innerHTML = "<p>Error loading staff details.</p>";
            }
        })
        // Handles errors during the fetch
        // - A built-in JavaScript .catch() method that logs any errors in the fetch chain.
        // - Writes error details to the developer tools for troubleshooting.
        // - Ensures the dashboard doesn’t break if fetching fails.
        .catch(error => console.error('Error fetching staff details:', error));
}

// Function to switch between dashboard sections
// - A user-defined JavaScript function named toggleSection, with no inputs.
// - Shows or hides the maintenance/bus status or profile sections based on the current section.
// - Switches the technician’s view between maintenance tasks/bus status and their profile on the dashboard.
function toggleSection() {
    // Finds the maintenance section
    // - A user-defined constant named maintenanceSection, holding the result of document.getElementById() for the element with ID 'maintenanceSection'.
    // - Targets the area where maintenance task details are shown.
    // - Lets the code show or hide the maintenance section.
    const maintenanceSection = document.getElementById("maintenanceSection");

    // Finds the bus status section
    // - A user-defined constant named busStatusSection, holding the result of document.getElementById() for the element with ID 'busStatusSection'.
    // - Targets the area where bus status details are shown.
    // - Lets the code show or hide the bus status section.
    const busStatusSection = document.getElementById("busStatusSection");

    // Finds the profile section
    // - A user-defined constant named profileSection, holding the result of document.getElementById() for the element with ID 'profileSection'.
    // - Targets the area where the technician’s profile details are shown.
    // - Lets the code show or hide the profile section.
    const profileSection = document.getElementById("profileSection");

    // Checks the current section
    // - A conditional statement checking if currentSection equals 'maintenance'.
    // - Switches to the profile section if true, or back to maintenance/bus status sections if false.
    // - Updates the dashboard view and loads profile data when needed.
    if (currentSection === "maintenance") {
        // Hides the maintenance section
        // - Sets maintenanceSection.style.display, a built-in JavaScript property, to 'none'.
        // - Makes the maintenance section invisible to show the profile instead.
        // - Clears the view for the profile section.
        maintenanceSection.style.display = "none";

        // Hides the bus status section
        // - Sets busStatusSection.style.display to 'none'.
        // - Makes the bus status section invisible to show the profile instead.
        // - Clears the view for the profile section.
        busStatusSection.style.display = "none";

        // Shows the profile section
        // - Sets profileSection.style.display to 'block'.
        // - Makes the profile section visible to show technician details.
        // - Displays the technician’s profile info on the dashboard.
        profileSection.style.display = "block";

        // Updates the current section
        // - Sets the global currentSection variable to 'profile'.
        // - Tracks that the profile section is now active.
        // - Ensures the dashboard knows which view is shown.
        currentSection = "profile";

        // Loads technician details
        // - Calls the user-defined fetchStaffDetails() function to fetch and display technician info.
        // - Fills the profile section with data when switching to it.
        // - Ensures the profile shows the latest technician details.
        fetchStaffDetails();
    } else {
        // Shows the maintenance section
        // - Sets maintenanceSection.style.display to 'block'.
        // - Makes the maintenance section visible to show task details.
        // - Returns the dashboard to the maintenance view.
        maintenanceSection.style.display = "block";

        // Shows the bus status section
        // - Sets busStatusSection.style.display to 'block'.
        // - Makes the bus status section visible to show bus details.
        // - Returns the dashboard to the bus status view.
        busStatusSection.style.display = "block";

        // Hides the profile section
        // - Sets profileSection.style.display to 'none'.
        // - Makes the profile section invisible to show maintenance/bus status instead.
        // - Clears the view for the maintenance/bus status sections.
        profileSection.style.display = "none";

        // Updates the current section
        // - Sets currentSection to 'maintenance'.
        // - Tracks that the maintenance/bus status sections are now active.
        // - Ensures the dashboard knows which view is shown.
        currentSection = "maintenance";
    }
}

// Function to update technician profile details
// - A user-defined JavaScript function named handleProfileUpdate, with one input (event: a form submission event object).
// - Stops the form submission, collects form data, sends it to the server, and updates the displayed profile if successful.
// - Saves changes to the technician’s profile and refreshes the display on the dashboard.
function handleProfileUpdate(event) {
    // Stops the page from refreshing
    // - Calls preventDefault(), a built-in JavaScript method of the event object, to stop the form from reloading the page.
    // - Keeps the technician on the dashboard after submitting the form.
    // - Makes the profile update process smooth without page reloads.
    event.preventDefault();

    // Gets the name from the form
    // - A user-defined constant named name, holding the value property of the input element with ID 'name', found using document.getElementById().
    // - Stores the new name the technician typed in the form.
    // - Prepares the name to send to the server for updating.
    const name = document.getElementById("name").value;

    // Gets the phone number from the form
    // - A user-defined constant named phone, holding the value property of the input element with ID 'phone', found using document.getElementById().
    // - Stores the new phone number the technician typed in the form.
    // - Prepares the phone number to send to the server for updating.
    const phone = document.getElementById("phone").value;

    // Gets the password from the form
    // - A user-defined constant named password, holding the value property of the input element with ID 'password', found using document.getElementById().
    // - Stores the new password, if the technician typed one in the form.
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

    // Sends a web request to update technician details
    // - Uses the built-in JavaScript fetch() method to send a POST request to a PHP file at '/php/technician/fetchStaffUpdate.php' with the form data.
    // - Starts a chain of steps to process the server’s reply and update the profile.
    // - Sends the updated technician details to the server for saving.
    fetch('/php/technician/fetchStaffUpdate.php', {
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
        // - Includes the technician’s updated details to save on the server.
        // - Provides the info needed to update the profile.
        body: data
    })
        // Processes the server’s reply
        // - A built-in JavaScript .then() method that converts the response to a JSON object using response.json().
        // - Gets the server’s reply into a usable format to check the update status.
        .then(response => response.json())
        // Handles the update result
        // - A .then() method that takes the data object and updates the displayed details or shows an error.
        // - Refreshes the profile section if successful, or alerts the technician if there’s an issue.
        .then(data => {
            // Checks if the update worked
            // - A conditional statement checking if data.status equals 'success'.
            // - Updates the displayed name and phone and shows a success message if the update worked, or an error if it didn’t.
            // - Updates the dashboard based on the server’s reply.
            if (data.status === "success") {
                // Updates the displayed name
                // - Sets the textContent property, a built-in JavaScript feature, of the element with ID 'currentName' to data.staff.Name.
                // - Changes the name shown on the profile section to the updated value.
                // - Keeps the dashboard showing the latest name.
                document.getElementById("currentName").textContent = data.staff.Name;

                // Updates the displayed phone number
                // - Sets the textContent property of the element with ID 'currentPhone' to data.staff.PhoneNumber.
                // - Changes the phone number shown on the profile section to the updated value.
                // - Keeps the dashboard showing the latest phone number.
                document.getElementById("currentPhone").textContent = data.staff.PhoneNumber;

                // Shows a success message
                // - Calls alert(), a built-in JavaScript method, with “Profile updated successfully!”.
                // - Tells the technician their changes were saved.
                // - Confirms the profile update on the dashboard.
                alert("Profile updated successfully!");
            } else {
                // Shows an error message
                // - Calls alert() with “Error updating profile:” plus data.message (e.g., “Invalid phone number”).
                // - Tells the technician why the update didn’t work, using the server’s message.
                // - Helps the technician understand issues with their profile changes.
                alert("Error updating profile: " + data.message);
            }
        })
        // Handles errors during the update
        // - A built-in JavaScript .catch() method that catches any errors in the fetch chain.
        // - Logs the error and shows a generic error message to the technician.
        // - Keeps the dashboard working even if the update fails.
        .catch(error => {
            // Logs an error for debugging
            // - Calls console.error() to write “Error updating profile:” and the error details to the developer tools.
            // - Helps developers find issues like bad internet or server errors.
            console.error('Error updating profile:', error);

            // Shows an error message
            // - Calls alert() with a generic “Error updating profile” message.
            // - Tells the technician there was a problem saving their changes.
            // - Provides basic feedback when specific error details aren’t available.
            alert("Error updating profile");
        });
}

// Sets up the profile link to switch sections
// - Adds an event listener to the element with ID 'profileLink' using addEventListener(), a built-in JavaScript method.
// - Listens for the 'click' event, which happens when the technician clicks the profile link, and calls the user-defined toggleSection() function.
// - Lets the technician switch to the profile section from the dashboard.
document.getElementById("profileLink").addEventListener("click", toggleSection);

// Sets up the form to handle profile updates
// - Adds an event listener to the form with ID 'profileForm' using addEventListener().
// - Listens for the 'submit' event, which happens when the technician submits the form, and calls the user-defined handleProfileUpdate() function with the event object.
// - Lets the technician save changes to their profile on the dashboard.
document.getElementById("profileForm").addEventListener("submit", handleProfileUpdate);