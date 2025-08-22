// File: driverDashboard.js
// Purpose: Handles loading and displaying schedules, passengers, and stats on the Driver Dashboard in the International Bus Booking System.
// Fetches driver schedules, shows them in a table, updates trip statistics, and displays passenger details for selected schedules.

// Function to get and show driver schedules
// - A user-defined JavaScript function named fetchAndDisplaySchedules, with no inputs.
// - Sends a web request to get schedule data, updates trip stats, fills a table with schedules, or redirects if the driver isn’t registered.
// - Shows the driver their assigned trips and stats on the dashboard.
function fetchAndDisplaySchedules() {
    // Sends a web request to get schedules
    // - Uses the built-in JavaScript fetch() method to request data from a PHP file at '/php/driver/fetchSchedules.php'.
    // - Starts a chain of steps to process the server’s reply and show schedule info.
    // - Fetches trip details for the logged-in driver.
    fetch('/php/driver/fetchSchedules.php')
        // Processes the server’s reply
        // - A built-in JavaScript .then() method that converts the response to a JSON object using response.json().
        // - Turns the server’s reply into a usable object with schedule data.
        // - Prepares the schedules for display or redirection.
        .then(response => response.json())
        // Handles the schedule data
        // - A .then() method that takes the data object and updates stats, the schedule table, or redirects the driver.
        // - Shows schedules, stats, or sends the driver to a form if they aren’t registered.
        .then(data => {
            // Checks if the driver is not registered
            // - A conditional statement checking if data.status equals “no_staff”.
            // - Redirects to a staff form if the driver isn’t found in the system.
            // - Ensures unregistered drivers complete their profile.
            if (data.status === "no_staff") {
                // Redirects to a staff form
                // - Sets window.location.href, a built-in JavaScript property, to navigate to “../../forms/StaffInputForm.html”.
                // - Sends the driver to a form to enter their details.
                // - Prevents access to the dashboard until registered.
                window.location.href = "../../forms/StaffInputForm.html";
            } else {
                // Updates trip statistics
                // - A user-defined constant named stats, holding data.stats or an empty object if undefined.
                // - Stores trip stats like total trips and hours for display.
                // - Prepares stats for the dashboard cards.
                const stats = data.stats || {};

                // Updates total trips
                // - Sets the textContent property, a built-in JavaScript feature, of the element with ID 'totalTrips' using document.getElementById().
                // - Shows the number of total trips from stats.totalTrips, defaulting to 0.
                // - Displays the driver’s trip count on the dashboard.
                document.getElementById('totalTrips').textContent = stats.totalTrips || 0;

                // Updates total hours
                // - Sets the textContent of the element with ID 'totalHours' to stats.totalHours formatted to two decimal places using Number().toFixed(2).
                // - Shows the driver’s total driving hours, defaulting to 0.00.
                // - Displays precise hours on the dashboard.
                document.getElementById('totalHours').textContent = Number(stats.totalHours || 0).toFixed(2);

                // Updates upcoming trips
                // - Sets the textContent of the element with ID 'upcomingTrips' to stats.upcomingTrips, defaulting to 0.
                // - Shows the number of future trips the driver has.
                // - Displays upcoming trip count on the dashboard.
                document.getElementById('upcomingTrips').textContent = stats.upcomingTrips || 0;

                // Finds the schedule table
                // - A user-defined constant named tableBody, holding the result of document.querySelector(), a built-in JavaScript method, targeting the <tbody> inside the element with ID 'scheduleTable'.
                // - Targets the area where schedule rows will be added.
                // - Prepares the table for schedule data.
                const tableBody = document.querySelector("#scheduleTable tbody");

                // Clears old schedule rows
                // - Sets tableBody.innerHTML, a built-in JavaScript property, to an empty string.
                // - Removes any existing rows to avoid showing old or duplicate schedules.
                // - Prepares the table for fresh schedule data.
                tableBody.innerHTML = "";

                // Finds the no-schedules message
                // - A user-defined constant named noSchedulesMsg, holding the result of document.getElementById() for the element with ID 'noSchedules'.
                // - Targets the message shown when the driver has no schedules.
                // - Controls visibility of the no-schedules message.
                const noSchedulesMsg = document.getElementById("noSchedules");

                // Checks if there are schedules
                // - A conditional statement checking if data.schedules.length equals 0.
                // - Shows a message if no schedules exist, or fills the table if they do.
                // - Updates the dashboard based on the driver’s schedule count.
                if (data.schedules.length === 0) {
                    // Shows the no-schedules message
                    // - Sets noSchedulesMsg.style.display, a built-in JavaScript property, to “block”.
                    // - Makes the message visible to tell the driver they have no trips.
                    // - Keeps the dashboard clear when no schedules are assigned.
                    noSchedulesMsg.style.display = "block";
                } else {
                    // Hides the no-schedules message
                    // - Sets noSchedulesMsg.style.display to “none”.
                    // - Makes the message invisible since schedules exist.
                    // - Clears the message to show the schedule table.
                    noSchedulesMsg.style.display = "none";

                    // Goes through each schedule
                    // - A forEach loop, a built-in JavaScript method, that runs code for each schedule in data.schedules.
                    // - Creates a table row for each schedule with details and a button.
                    // - Builds the schedule table dynamically for the driver.
                    data.schedules.forEach(schedule => {
                        // Adds a schedule row
                        // - Updates tableBody.innerHTML by adding a template literal (text with data inside backticks) with schedule details.
                        // - Creates a <tr> row with cells for BusNumber, RouteName, DepartureTime, ArrivalTime, and a button calling viewPassengers() with ScheduleID.
                        // - Shows trip details and lets the driver view passengers.
                        tableBody.innerHTML += `
                            <tr>
                                <td>${schedule.BusNumber}</td>
                                <td>${schedule.RouteName}</td>
                                <td>${schedule.DepartureTime}</td>
                                <td>${schedule.ArrivalTime}</td>
                                <td><button class="button" onclick="viewPassengers(${schedule.ScheduleID})">View Passengers</button></td>
                            </tr>
                        `;
                    });
                }
            }
        })
        // Handles errors during the fetch
        // - A built-in JavaScript .catch() method that logs any errors in the fetch chain.
        // - Writes error details to the developer tools for troubleshooting.
        // - Ensures the dashboard doesn’t break if fetching fails.
        .catch(error => console.error('Error fetching schedules:', error));
}

// Function to show passenger details
// - A user-defined JavaScript function named viewPassengers, with one input: scheduleID (a number).
// - Sends a web request to get passenger data for a schedule and shows it in a table.
// - Displays passenger names, phone numbers, and seat numbers for the driver’s selected trip.
function viewPassengers(scheduleID) {
    // Sends a web request to get passengers
    // - Uses the built-in JavaScript fetch() method to request data from a PHP file at `/php/driver/fetchPassengers.php?scheduleID=${data.id}`.
    // - Includes the scheduleID in the URL to fetch passengers for that trip.
    // - Starts a chain of steps to process the server’s reply and show passenger info.
    fetch(`/php/driver/fetchPassengers.php?scheduleID=${scheduleID}`)
        // Processes the server’s reply
        // - Uses .then() to convert the response to a JSON object with response.json().
        // - Turns the server’s reply into a usable object with passenger data.
        // - Prepares the passenger details for display.
        .then(response => response.json())
        // Handles the passenger data
        // - Updates the passenger table and shows or hides it based on the server’s success.
        .then(data => {
            // Finds the passenger section
            // - A user-defined constant named passengerSection, holding the result of document.getElementById() for the element with ID 'passengerSection'.
            // - Targets the area where the passenger table will be displayed or hidden.
            // - Controls visibility of the passenger section.
            const passengerSection = document.getElementById("passengerSection");

            // Finds the passenger table
            // - A user-defined constant named passengerBody, holding the result of document.querySelector() targeting the <tbody> inside the element with ID 'passengerTable'.
            // - Targets the area where passenger rows will be added.
            // - Prepares the table for passenger data.
            const passengerBody = document.querySelector("#passengerTable tbody");

            // Finds the no-passengers message
            // - A user-defined constant named noPassengersMsg, holding the result of document.getElementById() for the element with ID 'noPassengers'.
            // - Targets the message shown when no passengers are booked for the trip.
            // - Controls visibility of the no-passengers message.
            const noPassengersMsg = document.getElementById("noPassengers");

            // Clears old passenger rows
            // - Sets passengerBody.innerHTML to an empty string.
            // - Removes any existing rows to avoid showing old or duplicate passengers.
            // - Prepares the table for fresh passenger data.
            passengerBody.innerHTML = "";

            // Checks if the fetch was successful
            // - A conditional statement checking if data.status equals “success”.
            // - Shows passengers or an error based on the server’s reply.
            if (data.status === "success") {
                // Checks if there are passengers
                // - A conditional statement checking if data.passengers.length equals 0.
                // - Shows a message if no passengers, or fills the table if there are.
                // Updates the table based on the passenger count for the trip.
                if (data.passengers.length === 0) {
                    // Shows the no-passengers message
                    // - Sets noPassengersMsg.style.display to “block”.
                    // - Makes the message visible to tell the driver no passengers are booked.
                    // - Keeps the table clear when no passengers are booked.
                    noPassengersMsg.style.display = "block";

                    // Clears the passenger table
                    // - Sets passengerBody.innerHTML to an empty string (already done above, but reiterated for clarity).
                    // - Ensures the table stays empty if no passengers exist.
                    // - Maintains a clean display for the driver.
                    passengerBody.innerHTML = "";
                } else {
                    // Hides the no-passengers message
                    // - Sets noPassengersMsg.style.display to “none”.
                    // - Makes the message invisible since passengers exist.
                    // - Clears the message to show the passenger table.
                    noPassengersMsg.style.display = "none";

                    // Goes through each passenger
                    // - A forEach loop that runs code for each passenger in data.passengers.
                    // - Creates a table row for each passenger with their details.
                    // - Builds the passenger table dynamically for the driver.
                    data.passengers.forEach(passenger => {
                        // Adds a passenger row
                        // - Updates passengerBody.innerHTML by adding a template literal with passenger details.
                        // - Creates a <tr> row with cells for Name, PhoneNumber, and SeatNumber.
                        // - Shows passenger info for the selected trip.
                        passengerBody.innerHTML += `
                            <tr>
                                <td>${passenger.Name}</td>
                                <td>${passenger.PhoneNumber}</td>
                                <td>${passenger.SeatNumber}</td>
                            </tr>
                        `;
                    });
                }

                // Shows the passenger section
                // - Sets passengerSection.style.display to “block”.
                // - Makes the passenger table visible to show the data.
                // - Displays passenger details for the driver on the dashboard.
                passengerSection.style.display = "block";
            } else {
                // Shows an error message
                // - Calls alert(), a built-in JavaScript method, with data.message (e.g., “Failed to load passengers”).
                // - Tells the driver why passenger data couldn’t load.
                // - Informs the driver of issues with the server’s reply.
                alert(data.message);

                // Hides the passenger section
                // - Sets passengerSection.style.display to “none”.
                // - Makes the passenger table invisible on error.
                // - Keeps the dashboard clean if passenger data fails to load.
                passengerSection.style.display = "none";
            }
        })
        // Handles errors during the fetch
        // - A built-in JavaScript .catch() method that logs any errors in the fetch chain.
        // - Writes error details to the developer tools for troubleshooting.
        // - Ensures the dashboard doesn’t break if fetching passengers fails.
        .catch(error => console.error('Error fetching passengers:', error));
}

// Sets up the page to load schedules
// - Adds an event listener using document.addEventListener(), a built-in JavaScript method, for the “DOMContentLoaded” event.
// - Runs the user-defined fetchAndDisplaySchedules() function when the page’s HTML is fully loaded.
// - Loads the driver’s schedules and stats as soon as the dashboard opens.
document.addEventListener("DOMContentLoaded", fetchAndDisplaySchedules);