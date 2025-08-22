// File: driverProfile.js
// Purpose: Manages profile functionality on the Driver Dashboard in the International Bus Booking System.
// Fetches and displays driver details, switches between schedule, profile, and passenger sections, and updates profile information.

// Tracks the current visible section
// - A user-defined global variable named currentSection, a string initialized to "schedule".
// - Stores whether the schedule, profile, or passenger section is currently shown on the dashboard.
// - Helps the dashboard switch between views for the driver.
let currentSection = "schedule";

// Function to get and show driver details
// - A user-defined JavaScript function named fetchStaffDetails, with no inputs.
// - Sends a web request to get driver data from the server and updates the profile section with details like name and email.
// - Fills the driver’s profile section and pre-fills a form for editing on the dashboard.
function fetchStaffDetails() {
    // Sends a web request to get driver details
    // - Uses the built-in JavaScript fetch() method to request data from a PHP file at '/php/driver/fetchStaffDetails.php'.
    // - Starts a chain of steps to process the server’s reply and show driver info.
    // - Fetches details for the logged-in driver to display on the profile section.
    fetch('/php/driver/fetchStaffDetails.php')
        // Processes the server’s reply
        // - A built-in JavaScript .then() method that converts the response to a JSON object using response.json().
        // - Turns the server’s reply into a usable object with driver data.
        // - Prepares the data to update the profile section.
        .then(response => response.json())
        // Handles the driver data
        // - A .then() method that takes the data object and updates the profile section or shows an error.
        // - Displays driver details if successful, or an error message if not.
        .then(data => {
            // Finds the profile section
            // - A user-defined constant named staffDetails, holding the result of a built-in JavaScript document.getElementById() method that finds the element with ID 'staffDetails'.
            // - Targets the area where driver details, like name and phone, will be shown.
            // - Lets the code update the profile section with driver info.
            const staffDetails = document.getElementById("staffDetails");

            // Checks if the fetch was successful
            // - A conditional statement checking if data.status equals 'success'.
            // - Shows driver details and fills the form if successful, or displays an error if not.
            // - Decides what to show based on the server’s reply for the driver’s profile.
            if (data.status === "success") {
                // Fills the profile section with driver info
                // - Sets staffDetails.innerHTML, a built-in JavaScript property, using a template literal (text with data inside backticks).
                // - Adds paragraphs with driver details like Name, PhoneNumber, Email, StaffNumber, and Role, wrapped in <span> tags for easy updates.
                // - Shows the driver’s current info clearly on the dashboard.
                staffDetails.innerHTML = `
                    <p><strong>Name:</strong> <span id="currentName">${data.staff.Name}</span></p>
                    <p><strong>Phone Number:</strong> <span id="currentPhone">${data.staff.PhoneNumber}</span></p>
                    <p><strong>Email:</strong> <span id="currentEmail">${data.staff.Email}</span></p>
                    <p><strong>Staff Number:</strong> <span id="currentStaffNumber">${data.staff.StaffNumber}</span></p>
                    <p><strong>Role:</strong> <span id="currentRole">${data.staff.Role}</span></p>
                `;

                // Fills the form with current details
                // - Sets the value property, a built-in JavaScript feature, of input elements with IDs 'name' and 'phone' using document.getElementById().
                // - Puts the driver’s Name and PhoneNumber into the form fields for editing.
                // - Makes it easy for the driver to update their profile info.
                document.getElementById("name").value = data.staff.Name;
                document.getElementById("phone").value = data.staff.PhoneNumber;
            } else {
                // Shows an error message
                // - Sets staffDetails.innerHTML to a paragraph with “Error loading staff details.”
                // - Tells the driver their details couldn’t load, like if the server can’t find their record.
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
// - Shows or hides the schedule, profile, or passenger sections based on the current section.
// - Switches the driver’s view between their schedule, profile, or passenger list on the dashboard.
function toggleSection() {
    // Finds the schedule section
    // - A user-defined constant named scheduleSection, holding the result of document.getElementById() for the element with ID 'scheduleSection'.
    // - Targets the area where the driver’s schedule details are shown.
    // - Lets the code show or hide the schedule section.
    const scheduleSection = document.getElementById("scheduleSection");

    // Finds the profile section
    // - A user-defined constant named profileSection, holding the result of document.getElementById() for the element with ID 'profileSection'.
    // - Targets the area where the driver’s profile details are shown.
    // - Lets the code show or hide the profile section.
    const profileSection = document.getElementById("profileSection");

    // Finds the passenger section
    // - A user-defined constant named passengerSection, holding the result of document.getElementById() for the element with ID 'passengerSection'.
    // - Targets the area where passenger details for the driver’s trips are shown.
    // - Lets the code show or hide the passenger list section.
    const passengerSection = document.getElementById("passengerSection");

    // Checks the current section
    // - A conditional statement checking if currentSection equals 'schedule'.
    // - Switches to the profile section if true, or back to the schedule section if false, hiding the passenger section in both cases.
    // - Updates the dashboard view and loads profile data when needed.
    if (currentSection === "schedule") {
        // Hides the schedule section
        // - Sets scheduleSection.style.display, a built-in JavaScript property, to 'none'.
        // - Makes the schedule section invisible to show the profile instead.
        // - Clears the view for the profile section.
        scheduleSection.style.display = "none";

        // Shows the profile section
        // - Sets profileSection.style.display to 'block'.
        // - Makes the profile section visible to show driver details.
        // - Displays the driver’s profile info on the dashboard.
        profileSection.style.display = "block";

        // Hides the passenger section
        // - Sets passengerSection.style.display to 'none'.
        // - Makes the passenger list invisible when switching to the profile.
        // - Keeps the dashboard focused on the profile view.
        passengerSection.style.display = "none";

        // Updates the current section
        // - Sets the global currentSection variable to 'profile'.
        // - Tracks that the profile section is now active.
        // - Ensures the dashboard knows which view is shown.
        currentSection = "profile";

        // Loads driver details
        // - Calls the user-defined fetchStaffDetails() function to fetch and display driver info.
        // - Fills the profile section with data when switching to it.
        // - Ensures the profile shows the latest driver details.
        fetchStaffDetails();
    } else {
        // Shows the schedule section
        // - Sets scheduleSection.style.display to 'block'.
        // - Makes the schedule section visible to show trip details.
        // - Returns the dashboard to the default schedule view.
        scheduleSection.style.display = "block";

        // Hides the profile section
        // - Sets profileSection.style.display to 'none'.
        // - Makes the profile section invisible to show the schedule instead.
        // - Clears the view for the schedule section.
        profileSection.style.display = "none";

        // Hides the passenger section
        // - Sets passengerSection.style.display to 'none'.
        // - Makes the passenger list invisible when returning to the schedule.
        // - Keeps the dashboard focused on the schedule view.
        passengerSection.style.display = "none";

        // Updates the current section
        // - Sets currentSection to 'schedule'.
        // - Tracks that the schedule section is now active.
        // - Ensures the dashboard knows which view is shown.
        currentSection = "schedule";
    }
}

// Function to update driver profile details
// - A user-defined JavaScript function named handleProfileUpdate, with one input (event: a form submission event object).
// - Stops the form submission, collects form data, sends it to the server, and updates the displayed profile if successful.
// - Saves changes to the driver’s profile and refreshes the display on the dashboard.
function handleProfileUpdate(event) {
    // Stops the page from refreshing
    // - Calls preventDefault(), a built-in JavaScript method of the event object, to stop the form from reloading the page.
    // - Keeps the driver on the dashboard after submitting the form.
    // - Makes the profile update process smooth without page reloads.
    event.preventDefault();

    // Gets the name from the form
    // - A user-defined constant named name, holding the value property of the input element with ID 'name', found using document.getElementById().
    // - Stores the new name the driver typed in the form.
    // - Prepares the name to send to the server for updating.
    const name = document.getElementById("name").value;

    // Gets the phone number from the form
    // - A user-defined constant named phone, holding the value property of the input element with ID 'phone', found using document.getElementById().
    // - Stores the new phone number the driver typed in the form.
    // - Prepares the phone number to send to the server for updating.
    const phone = document.getElementById("phone").value;

    // Gets the password from the form
    // - A user-defined constant named password, holding the value property of the input element with ID 'password', found using document.getElementById().
    // - Stores the new password, if the driver typed one in the form.
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

    // Sends a web request to update driver details
    // - Uses the built-in JavaScript fetch() method to send a POST request to a PHP file at '/php/driver/fetchStaffUpdate.php' with the form data.
    // - Starts a chain of steps to process the server’s reply and update the profile.
    // - Sends the updated driver details to the server for saving.
    fetch('/php/driver/fetchStaffUpdate.php', {
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
        // - Includes the driver’s updated details to save on the server.
        // - Provides the info needed to update the profile.
        body: data
    })
        // Processes the server’s reply
        // - A built-in JavaScript .then() method that converts the response to a JSON object using response.json().
        // - Gets the server’s reply into a usable format to check the update status.
        .then(response => response.json())
        // Handles the update result
        // - A .then() method that takes the data object and updates the displayed details or shows an error.
        // - Refreshes the profile section if successful, or alerts the driver if there’s an issue.
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
                // - Tells the driver their changes were saved.
                // - Confirms the profile update on the dashboard.
                alert("Profile updated successfully!");
            } else {
                // Shows an error message
                // - Calls alert() with “Error updating profile:” plus data.message (e.g., “Invalid phone number”).
                // - Tells the driver why the update didn’t work, using the server’s message.
                // - Helps the driver understand issues with their profile changes.
                alert("Error updating profile: " + data.message);
            }
        })
        // Handles errors during the update
        // - A built-in JavaScript .catch() method that catches any errors in the fetch chain.
        // - Logs the error and shows a generic error message to the driver.
        // - Keeps the dashboard working even if the update fails.
        .catch(error => {
            // Logs an error for debugging
            // - Calls console.error() to write “Error updating profile:” and the error details to the developer tools.
            // - Helps developers find issues like bad internet or server errors.
            console.error('Error updating profile:', error);

            // Shows an error message
            // - Calls alert() with a generic “Error updating profile” message.
            // - Tells the driver there was a problem saving their changes.
            // - Provides basic feedback when specific error details aren’t available.
            alert("Error updating profile");
        });
}

// Sets up the profile link to switch sections
// - Adds an event listener to the element with ID 'profileLink' using addEventListener(), a built-in JavaScript method.
// - Listens for the 'click' event, which happens when the driver clicks the profile link, and calls the user-defined toggleSection() function.
// - Lets the driver switch to the profile section from the dashboard.
document.getElementById("profileLink").addEventListener("click", toggleSection);

// Sets up the form to handle profile updates
// - Adds an event listener to the form with ID 'profileForm' using addEventListener().
// - Listens for the 'submit' event, which happens when the driver submits the form, and calls the user-defined handleProfileUpdate() function with the event object.
// - Lets the driver save changes to their profile on the dashboard.
document.getElementById("profileForm").addEventListener("submit", handleProfileUpdate);