// File: checkTechnician.js
// Purpose: Manages the Technician Dashboard in the International Bus Booking System by checking if a technician exists, loading their tasks, bus status, and stats, and setting up table search.
// Displays maintenance tasks and bus status in tables, updates stats cards, and redirects to a form if no technician is found.

// Function to get and show technician data
// - A user-defined JavaScript function named fetchAndDisplayTechnicianData, with no inputs.
// - Sends a web request to get technician tasks, bus status, and stats, then updates tables and cards or redirects if needed.
// - Fills the technician dashboard with maintenance tasks, bus status, and stats like total tasks and costs.
function fetchAndDisplayTechnicianData() {
    // Sends a web request to get technician data
    // - Uses the built-in JavaScript fetch() method to ask the server for data from a PHP file at '/php/technician/fetchTechnicianData.php'.
    // - Starts a chain of steps to process the server’s reply and update the dashboard.
    // - Fetches tasks, bus status, and stats for the logged-in technician.
    fetch('/php/technician/fetchTechnicianData.php')
        // Processes the server’s reply
        // - A built-in JavaScript .then() method that converts the response to a JSON object using response.json().
        // - Turns the server’s reply into a usable object with technician data.
        // - Prepares the data to update tables and stats or handle redirects.
        .then(response => response.json())
        // Handles the technician data
        // - A .then() method that takes the data object and updates tables, stats cards, or redirects based on the response.
        // - Manages the dashboard display or navigation for the technician.
        .then(data => {
            // Checks if the technician exists
            // - A conditional statement checking if data.status equals 'no_staff'.
            // - Redirects to a form page if no technician is found, or updates the dashboard if they exist.
            // - Ensures only valid technicians see the dashboard data.
            if (data.status === "no_staff") {
                // Redirects to a staff input form
                // - Sets window.location.href, a built-in JavaScript property, to '../../forms/StaffInputForm.html'.
                // - Sends the user to a form page to enter technician details if none are found.
                // - Guides new technicians to register before accessing the dashboard.
                window.location.href = "../../forms/StaffInputForm.html";
            } else {
                // Updates stats cards with technician data
                // - A user-defined constant named stats, holding the data.stats object or an empty object if undefined.
                // - Uses document.getElementById(), a built-in JavaScript method, to find elements with IDs 'totalTasks', 'totalCost', and 'busesServiced', and sets their textContent to stats values or defaults to 0.
                // - Shows stats like total tasks and cost on the technician dashboard, formatting cost to two decimal places.
                const stats = data.stats || {};
                document.getElementById('totalTasks').textContent = stats.totalTasks || 0;
                document.getElementById('totalCost').textContent = Number(stats.totalCost || 0).toFixed(2);
                document.getElementById('busesServiced').textContent = stats.busesServiced || 0;
                // Commented out for a fourth stat; preserved for potential restoration
                // document.getElementById('recentServices').textContent = stats.recentServices || 0;

                // Finds the maintenance tasks table section
                // - A user-defined constant named maintenanceTableBody, holding the result of a built-in JavaScript document.querySelector() method that finds the <tbody> element inside the table with ID 'maintenanceTable'.
                // - Targets the area where maintenance task rows will be added.
                // - Lets the code update the tasks table with technician data.
                const maintenanceTableBody = document.querySelector("#maintenanceTable tbody");

                // Clears out old task rows
                // - Sets maintenanceTableBody.innerHTML, a built-in JavaScript property, to an empty string.
                // - Removes any old <tr> rows to avoid showing duplicate or outdated tasks.
                // - Makes the tasks table ready for fresh data on the dashboard.
                maintenanceTableBody.innerHTML = "";

                // Finds the no-tasks message element
                // - A user-defined constant named noMaintenanceMsg, holding the result of document.getElementById() that finds the element with ID 'noMaintenance'.
                // - Targets the message shown when there are no maintenance tasks.
                // - Lets the code show or hide a message based on task count.
                const noMaintenanceMsg = document.getElementById("noMaintenance");

                // Checks if there are maintenance tasks
                // - A conditional statement checking if data.maintenanceTasks.length equals 0.
                // - Shows a no-tasks message if empty, or hides it and fills the table if tasks exist.
                // - Keeps the dashboard clear for technicians with or without tasks.
                if (data.maintenanceTasks.length === 0) {
                    // Shows the no-tasks message
                    // - Sets noMaintenanceMsg.style.display, a built-in JavaScript property, to 'block'.
                    // - Makes the message visible to tell the technician there are no tasks.
                    // - Informs the user when no maintenance tasks are assigned.
                    noMaintenanceMsg.style.display = "block";
                } else {
                    // Hides the no-tasks message
                    // - Sets noMaintenanceMsg.style.display to 'none'.
                    // - Makes the message invisible since tasks are available.
                    // - Ensures the message doesn’t show when tasks are present.
                    noMaintenanceMsg.style.display = "none";

                    // Goes through each maintenance task to make a table row
                    // - A forEach loop, a built-in JavaScript method that runs code for each task in data.maintenanceTasks.
                    // - Adds a row for each task with details like BusNumber and Cost.
                    // - Fills the tasks table with the technician’s assigned tasks.
                    data.maintenanceTasks.forEach(task => {
                        // Adds a row to the maintenance table
                        // - Updates maintenanceTableBody.innerHTML by adding a template literal (text with data inside backticks) with task details.
                        // - Creates a <tr> row with cells for BusNumber, ServiceDone, ServiceDate, NSD (next service date), and Cost.
                        // - Shows task info in the technician’s task table.
                        maintenanceTableBody.innerHTML += `
                            <tr>
                                <td>${task.BusNumber}</td>
                                <td>${task.ServiceDone}</td>
                                <td>${task.ServiceDate}</td>
                                <td>${task.NSD}</td>
                                <td>${task.Cost}</td>
                            </tr>
                        `;
                    });
                }

                // Finds the bus status table section
                // - A user-defined constant named busStatusTableBody, holding the result of document.querySelector() that finds the <tbody> element inside the table with ID 'busStatusTable'.
                // - Targets the area where bus status rows will be added.
                // - Lets the code update the bus status table with data.
                const busStatusTableBody = document.querySelector("#busStatusTable tbody");

                // Clears out old bus status rows
                // - Sets busStatusTableBody.innerHTML to an empty string.
                // - Removes any old <tr> rows to avoid showing duplicate or outdated bus statuses.
                // - Makes the bus status table ready for fresh data.
                busStatusTableBody.innerHTML = "";

                // Goes through each bus status to make a table row
                // - A forEach loop that runs code for each status in data.busStatus.
                // - Adds a row for each bus with its status and details like BusNumber and RouteName.
                // - Fills the bus status table for the technician’s view.
                data.busStatus.forEach(status => {
                    // Determines the bus’s maintenance status
                    // - A user-defined constant named maintenanceStatus, checking if status.NSD (next service date) exists and is before the current date using a built-in JavaScript Date object.
                    // - Sets the status to 'Overdue' if the next service date is past due, or 'Ready' if not.
                    // - Shows if a bus needs maintenance in the status table.
                    const maintenanceStatus = status.NSD && new Date(status.NSD) < new Date() ? "Overdue" : "Ready";

                    // Adds a row to the bus status table
                    // - Updates busStatusTableBody.innerHTML by adding a template literal with bus details.
                    // - Creates a <tr> row with cells for BusNumber, RouteName (or “Not Assigned” if none), NextDepartureTime (or “N/A” if none), and maintenanceStatus.
                    // - Shows bus info and status in the technician’s table.
                    busStatusTableBody.innerHTML += `
                        <tr>
                            <td>${status.BusNumber}</td>
                            <td>${status.RouteName || "Not Assigned"}</td>
                            <td>${status.NextDepartureTime || "N/A"}</td>
                            <td>${maintenanceStatus}</td>
                        </tr>
                    `;
                });
            }
        })
        // Handles errors during the fetch
        // - A built-in JavaScript .catch() method that logs any errors in the fetch chain.
        // - Writes error details to the developer tools for troubleshooting.
        // - Keeps the dashboard working even if data fetching fails.
        .catch(error => console.error('Error fetching technician data:', error));
}

// Sets up the dashboard and search when the page loads
// - Adds an event listener to the document for the 'DOMContentLoaded' event, a built-in JavaScript event that runs when the webpage is fully loaded.
// - Runs a callback function to start fetchAndDisplayTechnicianData() and set up table search.
// - Gets the technician dashboard ready to show data and enable searching right away.
document.addEventListener("DOMContentLoaded", () => {
    // Gets and shows technician data
    // - Calls the user-defined fetchAndDisplayTechnicianData() function to load tasks, bus status, and stats.
    // - Fills the maintenance and bus status tables and stats cards on the dashboard.
    // - Shows all technician data as soon as the page opens.
    fetchAndDisplayTechnicianData();

    // Sets up search for the bus status table
    // - Adds an event listener to the element with ID 'busSearch' using addEventListener(), a built-in JavaScript method that watches for events.
    // - Listens for the 'input' event, which happens every time the technician types in the search box.
    // - Filters the bus status table based on what the technician types.
    document.getElementById("busSearch").addEventListener("input", () => {
        // Filters bus status table rows based on search text
        // - Calls the user-defined filterTable() function from script.js, passing the table ID ('busStatusTable'), search box ID ('busSearch'), message ID ('noBuses'), and column numbers ([0, 1, 3]).
        // - Hides rows that don’t match the search text in columns for BusNumber, RouteName, or MaintenanceStatus.
        // - Makes it easy for technicians to find specific buses in the table.
        filterTable("busStatusTable", "busSearch", "noBuses", [0, 1, 3]);
    });
});