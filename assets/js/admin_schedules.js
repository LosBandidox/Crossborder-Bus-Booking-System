// File: admin_schedules.js
// Purpose: Gets, shows, and deletes bus schedule records for the admin_schedules.html page in the International Bus Booking System.
// Helps admins view, edit, or remove bus schedules in a table that can be searched, making schedule management easy.

// Function to get schedule data and show it in a table
// - A user-defined async JavaScript function named fetchSchedules, with no inputs.
// - Waits for data from the server using a web request and updates the webpage to show schedules in a table.
// - Pulls schedule details from a PHP file and fills a table with info like ScheduleID, BusID, and DepartureTime.
// - Clears old table rows before adding new ones to keep the schedule list up-to-date on the admin dashboard.
async function fetchSchedules() {
    // Block to catch errors
    // - A try-catch structure that runs the main code in the try part and handles problems like bad internet in the catch part.
    // - Keeps the webpage working even if something goes wrong while getting schedule data.
    // - Makes sure admins can still use the schedule page if there’s a glitch.
    try {
        // Sends a web request to get schedule data
        // - A user-defined constant named response, holding the result of a built-in JavaScript fetch() method that asks the server for data.
        // - Points to a PHP file at '../../../php/admin/fetchSchedules.php' using a path from the current file.
        // - Saves the server’s reply, which has schedule details in a JSON format (like a list of schedule info).
        // - Talks to the server to grab all schedule records for the admin table.
        const response = await fetch('../../../php/admin/fetchSchedules.php');

        // Turns the server’s reply into a list of schedules
        // - A user-defined constant named schedules, holding the result of a built-in JavaScript response.json() method that changes the server’s data into a JavaScript list.
        // - Contains a list of schedule objects, each with details like ScheduleID, BusID, RouteID, DepartureTime, ArrivalTime, Cost, DriverID, and CodriverID.
        // - Gets the schedule data ready to show in the table on admin_schedules.html.
        const schedules = await response.json();

        // Finds the table section on the webpage
        // - A user-defined constant named tableBody, holding the result of a built-in JavaScript document.getElementById() method that finds an HTML element by its ID.
        // - Targets the <tbody> part of the table with ID 'scheduleTableBody' in admin_schedules.html, where schedule rows will go.
        // - Lets the code change the table to show schedule details.
        const tableBody = document.getElementById('scheduleTableBody');

        // Clears out old table rows
        // - Sets the tableBody’s innerHTML property, a built-in JavaScript feature that changes an element’s HTML content, to an empty string.
        // - Removes any old <tr> rows to avoid showing duplicate or outdated schedules.
        // - Makes the table ready for fresh schedule data in the admin view.
        tableBody.innerHTML = ''; // Clear existing rows

        // Goes through each schedule to make a table row
        // - A forEach loop, a built-in JavaScript method that runs code for each schedule in the schedules list.
        // - Creates a row for each schedule with its details and buttons for actions like editing or deleting.
        // - Builds the table based on how many schedules are in the list for the admin dashboard.
        schedules.forEach(schedule => {
            // Makes a new table row
            // - A user-defined constant named row, holding the result of a built-in JavaScript document.createElement() method that creates a <tr> HTML element.
            // - Creates a table row to hold one schedule’s information.
            // - Acts as a container for table cells showing schedule details.
            const row = document.createElement('tr');

            // Fills the row with schedule info and buttons
            // - Sets the row’s innerHTML using a template literal, a JavaScript way to mix text and data inside backticks.
            // - Adds table cells with schedule details like schedule ID and departure time, plus buttons for edit and delete.
            // - Shows schedule info and adds buttons for admins to manage or delete schedules in the admin interface.
            row.innerHTML = `
                <td>${schedule.ScheduleID}</td>
                <td>${schedule.BusID}</td>
                <td>${schedule.RouteID}</td>
                <td>${schedule.DepartureTime}</td>
                <td>${schedule.ArrivalTime}</td>
                <td>${schedule.Cost}</td>
                <td>${schedule.DriverID}</td>
                <td>${schedule.CodriverID}</td>
                <td>
                    <a href="edit_schedule.html?id=${schedule.ScheduleID}" class="btn">Edit</a>
                    <button onclick="deleteSchedule(${schedule.ScheduleID})">Delete</button>
                </td>
            `;

            // Adds the row to the table
            // - Calls appendChild(), a built-in JavaScript method of tableBody, to add the row to the end of the table’s rows.
            // - Puts the new <tr> into the <tbody>, showing it on the webpage.
            // - Updates the table in admin_schedules.html with the schedule row.
            tableBody.appendChild(row);
        });
    } catch (error) {
        // Shows error details in the console
        // - Calls console.error(), a built-in JavaScript method that writes an error message to the browser’s developer tools.
        // - Includes the text 'Error fetching schedules:' and the error details to help find problems.
        // - Helps developers fix issues like bad internet or wrong data when loading schedules.
        console.error('Error fetching schedules:', error);
    }
}

// Function to delete a schedule and update the table
// - A user-defined async JavaScript function named deleteSchedule, with one input (scheduleId: a number for the schedule’s unique ID).
// - Sends a web request to remove a schedule from the server and refreshes the table by calling fetchSchedules().
// - Asks the user to confirm before deleting to avoid mistakes in the bus booking system.
async function deleteSchedule(scheduleId) {
    // Shows a pop-up to confirm deletion
    // - A conditional statement using the built-in JavaScript confirm() function, which shows a pop-up with a message and OK/Cancel buttons.
    // - Displays a question like “Are you sure you want to delete schedule with ID: 123?” using the scheduleId.
    // - Only deletes if the user clicks OK (returns true), making sure the admin wants to delete the schedule.
    if (confirm(`Are you sure you want to delete schedule with ID: ${scheduleId}?`)) {
        // Block to catch errors
        // - A try-catch structure that runs the deletion code in the try part and handles problems like bad internet in the catch part.
        // - Keeps the webpage working even if deleting the schedule fails.
        // - Ensures admins can try again if there’s an issue with deletion.
        try {
            // Sends a web request to delete the schedule
            // - A user-defined constant named response, holding the result of a built-in JavaScript fetch() method that asks the server to delete the schedule.
            // - Points to a PHP file at '../../../php/admin/deleteSchedule.php' with the scheduleId added (e.g., ?id=123).
            // - Saves the server’s reply, which has a JSON object with a status about the deletion.
            // - Tells the server to remove the schedule with the given ID.
            const response = await fetch(`../../../php/admin/deleteSchedule.php?id=${scheduleId}`);

            // Turns the server’s reply into a JavaScript object
            // - A user-defined constant named result, holding the result of a built-in JavaScript response.json() method that changes the server’s data into a JavaScript object.
            // - Contains a status (like 'success') to show if the deletion worked.
            // - Gets the deletion result ready to check in the admin interface.
            const result = await response.json();

            // Checks if the deletion worked
            // - A conditional statement checking if result.status equals 'success'.
            // - Shows a success message and refreshes the table if deletion worked, or shows an error if it didn’t.
            // - Gives feedback to the admin based on what the server says about the deletion.
            if (result.status === 'success') {
                // Shows a pop-up for success
                // - Calls alert(), a built-in JavaScript method that shows a pop-up message.
                // - Displays “Schedule deleted successfully” to tell the admin the schedule is gone.
                // - Confirms the schedule was removed from the system.
                alert('Schedule deleted successfully');

                // Refreshes the schedule table
                // - Calls the user-defined fetchSchedules() function to reload schedule data from the server.
                // - Updates the table in admin_schedules.html to remove the deleted schedule.
                // - Keeps the table showing the latest schedule list.
                fetchSchedules(); // Refresh the schedule list
            } else {
                // Shows a pop-up for failure
                // - Calls alert(), a built-in JavaScript method, showing “Failed to delete schedule”.
                // - Tells the admin the deletion didn’t work.
                // - Helps admins know there was an issue with removing the schedule.
                alert('Failed to delete schedule');
            }
        } catch (error) {
            // Shows error details in the console
            // - Calls console.error(), a built-in JavaScript method that writes an error message to the developer tools.
            // - Includes the text 'Error deleting schedule:' and the error details to help fix problems.
            // - Assists developers in finding issues like bad internet during deletion.
            console.error('Error deleting schedule:', error);
        }
    }
}

// Sets up the table and search when the page loads
// - Adds an event listener to the document for the 'DOMContentLoaded' event, a built-in JavaScript event that runs when the webpage is fully loaded.
// - Runs a callback function to make sure the page is ready, starting fetchSchedules() and adding a search feature.
// - Gets the admin_schedules.html page ready to show schedules and let admins search them right away.
document.addEventListener('DOMContentLoaded', () => {
    // Gets schedule data to fill the table
    // - Calls the user-defined fetchSchedules() function to load schedules from the server.
    // - Fills the table in admin_schedules.html with all schedules.
    // - Shows schedule data as soon as the admin opens the page.
    fetchSchedules();

    // Adds a search feature for the schedule table
    // - Adds an event listener to the element with ID 'scheduleSearch' using addEventListener(), a built-in JavaScript method that watches for events.
    // - Listens for the 'input' event, which happens every time the admin types in the search box.
    // - Runs code to filter the table based on what the admin types in the search box.
    document.getElementById('scheduleSearch').addEventListener('input', () => {
        // Filters table rows based on search text
        // - Calls the user-defined filterTable() function, passing the table ID ('scheduleTable'), search box ID ('scheduleSearch'), message ID ('noSchedules'), and column numbers ([0, 1, 2, 3]).
        // - Hides table rows that don’t match the search text in columns for ScheduleID, BusID, RouteID, or DepartureTime.
        // - Makes it easy for admins to find specific schedules in the table.
        filterTable('scheduleTable', 'scheduleSearch', 'noSchedules', [0, 1, 2, 3]);
    });
});