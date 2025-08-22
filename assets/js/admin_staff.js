// File: admin_staff.js
// Purpose: Gets, shows, and deletes staff records for the admin_staff.html page in the International Bus Booking System.
// Helps admins view, edit, or remove staff details in a table that can be searched, making staff management easy.

// Function to get staff data and show it in a table
// - A user-defined async JavaScript function named fetchStaff, with no inputs.
// - Waits for data from the server using a web request and updates the webpage to show staff in a table.
// - Pulls staff details from a PHP file and fills a table with info like StaffID, Name, and Email.
// - Clears old table rows before adding new ones to keep the staff list up-to-date on the admin dashboard.
async function fetchStaff() {
    // Block to catch errors
    // - A try-catch structure that runs the main code in the try part and handles problems like bad internet in the catch part.
    // - Keeps the webpage working even if something goes wrong while getting staff data.
    // - Makes sure admins can still use the staff page if there’s a glitch.
    try {
        // Sends a web request to get staff data
        // - A user-defined constant named response, holding the result of a built-in JavaScript fetch() method that asks the server for data.
        // - Points to a PHP file at '../../../php/admin/staff/fetchStaff.php' using a path from the current file.
        // - Saves the server’s reply, which has staff details in a JSON format (like a list of staff info).
        // - Talks to the server to grab all staff records for the admin table.
        const response = await fetch('../../../php/admin/staff/fetchStaff.php');

        // Turns the server’s reply into a list of staff
        // - A user-defined constant named staff, holding the result of a built-in JavaScript response.json() method that changes the server’s data into a JavaScript list.
        // - Contains a list of staff objects, each with details like StaffID, Name, PhoneNumber, Email, StaffNumber, and Role.
        // - Gets the staff data ready to show in the table on admin_staff.html.
        const staff = await response.json();

        // Finds the table section on the webpage
        // - A user-defined constant named tableBody, holding the result of a built-in JavaScript document.getElementById() method that finds an HTML element by its ID.
        // - Targets the <tbody> part of the table with ID 'staffTableBody' in admin_staff.html, where staff rows will go.
        // - Lets the code change the table to show staff details.
        const tableBody = document.getElementById('staffTableBody');

        // Clears out old table rows
        // - Sets the tableBody’s innerHTML property, a built-in JavaScript feature that changes an element’s HTML content, to an empty string.
        // - Removes any old <tr> rows to avoid showing duplicate or outdated staff.
        // - Makes the table ready for fresh staff data in the admin view.
        tableBody.innerHTML = '';

        // Goes through each staff member to make a table row
        // - A forEach loop, a built-in JavaScript method that runs code for each staff member in the staff list.
        // - Creates a row for each staff member with their details and buttons for actions like editing or deleting.
        // - Builds the table based on how many staff members are in the list for the admin dashboard.
        staff.forEach(staffMember => {
            // Makes a new table row
            // - A user-defined constant named row, holding the result of a built-in JavaScript document.createElement() method that creates a <tr> HTML element.
            // - Creates a table row to hold one staff member’s information.
            // - Acts as a container for table cells showing staff details.
            const row = document.createElement('tr');

            // Fills the row with staff info and buttons
            // - Sets the row’s innerHTML using a template literal, a JavaScript way to mix text and data inside backticks.
            // - Adds table cells (<td>) with staff details like StaffID and Name, plus buttons for Edit and Delete.
            // - Shows staff info and adds buttons for admins to edit or delete staff in the admin interface.
            row.innerHTML = `
                <td>${staffMember.StaffID}</td>
                <td>${staffMember.Name}</td>
                <td>${staffMember.PhoneNumber}</td>
                <td>${staffMember.Email}</td>
                <td>${staffMember.StaffNumber}</td>
                <td>${staffMember.Role}</td>
                <td>
                    <a href="edit_staff.html?id=${staffMember.StaffID}" class="btn">Edit</a>
                    <button onclick="deleteStaff(${staffMember.StaffID})">Delete</button>
                </td>
            `;

            // Adds the row to the table
            // - Calls appendChild(), a built-in JavaScript method of tableBody, to add the row to the end of the table’s rows.
            // - Puts the new <tr> into the <tbody>, showing it on the webpage.
            // - Updates the table in admin_staff.html with the staff row.
            tableBody.appendChild(row);
        });
    } catch (error) {
        // Shows error details in the console
        // - Calls console.error(), a built-in JavaScript method that writes an error message to the browser’s developer tools.
        // - Includes the text 'Error fetching staff:' and the error details to help find problems.
        // - Helps developers fix issues like bad internet or wrong data when loading staff.
        console.error('Error fetching staff:', error);
    }
}

// Function to delete a staff member and update the table
// - A user-defined async JavaScript function named deleteStaff, with one input (staffId: a number for the staff member’s unique ID).
// - Sends a web request to remove a staff member from the server and refreshes the table by calling fetchStaff().
// - Asks the user to confirm before deleting to avoid mistakes in the bus booking system.
async function deleteStaff(staffId) {
    // Shows a pop-up to confirm deletion
    // - A conditional statement using the built-in JavaScript confirm() function, which shows a pop-up with a message and OK/Cancel buttons.
    // - Displays a question like “Are you sure you want to delete staff with ID: 123?” using the staffId.
    // - Only deletes if the user clicks OK (returns true), making sure the admin wants to delete the staff member.
    if (confirm(`Are you sure you want to delete staff with ID: ${staffId}?`)) {
        // Block to catch errors
        // - A try-catch structure that runs the deletion code in the try part and handles problems like bad internet in the catch part.
        // - Keeps the webpage working even if deleting the staff member fails.
        // - Ensures admins can try again if there’s an issue with deletion.
        try {
            // Sends a web request to delete the staff member
            // - A user-defined constant named response, holding the result of a built-in JavaScript fetch() method that asks the server to delete the staff member.
            // - Points to a PHP file at '../../../php/admin/staff/deleteStaff.php' with the staffId added (e.g., ?id=123).
            // - Saves the server’s reply, which has a JSON object with a status and message about the deletion.
            // - Tells the server to remove the staff member with the given ID.
            const response = await fetch(`../../../php/admin/staff/deleteStaff.php?id=${staffId}`);

            // Turns the server’s reply into a JavaScript object
            // - A user-defined constant named result, holding the result of a built-in JavaScript response.json() method that changes the server’s data into a JavaScript object.
            // - Contains a status (like 'success') and a message (like “Staff deleted”) to show if the deletion worked.
            // - Gets the deletion result ready to check in the admin interface.
            const result = await response.json();

            // Checks if the deletion worked
            // - A conditional statement checking if result.status equals 'success'.
            // - Shows a success message and refreshes the table if deletion worked, or shows an error if it didn’t.
            // - Gives feedback to the admin based on what the server says about the deletion.
            if (result.status === 'success') {
                // Shows a pop-up for success
                // - Calls alert(), a built-in JavaScript method that shows a pop-up message.
                // - Displays “Staff deleted successfully” to tell the admin the staff member is gone.
                // - Confirms the staff member was removed from the system.
                alert('Staff deleted successfully');

                // Refreshes the staff table
                // - Calls the user-defined fetchStaff() function to reload staff data from the server.
                // - Updates the table in admin_staff.html to remove the deleted staff member.
                // - Keeps the table showing the latest staff list.
                fetchStaff();
            } else {
                // Shows a pop-up for failure
                // - Calls alert(), a built-in JavaScript method, showing “Failed to delete staff:” plus result.message (e.g., “Staff linked to active schedule”).
                // - Tells the admin why the deletion didn’t work, using the server’s message.
                // - Helps admins understand issues in the bus booking system.
                alert('Failed to delete staff: ' + result.message);
            }
        } catch (error) {
            // Shows error details in the console
            // - Calls console.error(), a built-in JavaScript method that writes an error message to the developer tools.
            // - Includes the text 'Error deleting staff:' and the error details to help fix problems.
            // - Assists developers in finding issues like bad internet during deletion.
            console.error('Error deleting staff:', error);
        }
    }
}

// Sets up the table and search when the page loads
// - Adds an event listener to the document for the 'DOMContentLoaded' event, a built-in JavaScript event that runs when the webpage is fully loaded.
// - Runs a callback function to make sure the page is ready, starting fetchStaff() and adding a search feature.
// - Gets the admin_staff.html page ready to show staff and let admins search them right away.
document.addEventListener('DOMContentLoaded', () => {
    // Gets staff data to fill the table
    // - Calls the user-defined fetchStaff() function to load staff records from the server.
    // - Fills the table in admin_staff.html with all staff members.
    // - Shows staff data as soon as the admin opens the page.
    fetchStaff();

    // Adds a search feature for the staff table
    // - Adds an event listener to the element with ID 'staffSearch' using addEventListener(), a built-in JavaScript method that watches for events.
    // - Listens for the 'input' event, which happens every time the admin types in the search box.
    // - Runs code to filter the table based on what the admin types in the search box.
    document.getElementById('staffSearch').addEventListener('input', () => {
        // Filters table rows based on search text
        // - Calls the user-defined filterTable() function, passing the table ID ('staffTable'), search box ID ('staffSearch'), message ID ('noStaff'), and column numbers ([0, 1, 3, 2]).
        // - Hides table rows that don’t match the search text in columns for StaffID, Name, Email, or PhoneNumber.
        // - Makes it easy for admins to find specific staff in the table.
        filterTable('staffTable', 'staffSearch', 'noStaff', [0, 1, 3, 2]);
    });
});