// File: bookings.js
// Purpose: Gets and shows customer booking records on a booking management page in the International Bus Booking System.
// Displays bookings in two tables (upcoming and past trips) based on departure time and status, with commented-out code for admin-only cancellation.

// Sets up the page to show bookings when it loads
// - Adds an event listener to the document for the 'DOMContentLoaded' event, a built-in JavaScript event that runs when the webpage is fully loaded.
// - Runs a callback function to fetch and display booking data in tables for upcoming and past trips.
// - Gets the booking page ready to show customer bookings as soon as it opens.
document.addEventListener('DOMContentLoaded', () => {
    // Sends a web request to get booking data
    // - Uses the built-in JavaScript fetch() method to ask the server for data from a PHP file at '/php/fetchBookings.php'.
    // - Starts a chain of steps to process the server’s reply and show bookings in tables.
    // - Fetches all booking records for the logged-in customer to display on the page.
    fetch('/php/fetchBookings.php')
        // Processes the server’s reply
        // - A built-in JavaScript .then() method that takes the response object and converts it to a JSON object using response.json().
        // - Turns the server’s reply into a usable list of bookings for the next step.
        // - Prepares the booking data to split into upcoming and past tables.
        .then(response => response.json())
        // Handles the booking data
        // - A .then() method that takes the data object and updates the page with booking details.
        // - Splits bookings into upcoming and past tables based on time and status.
        // - Organizes the customer’s bookings for clear display on the page.
        .then(data => {
            // Checks if the data was fetched successfully
            // - A conditional statement checking if data.status equals 'success'.
            // - Shows booking tables if the fetch worked, or an error message if it didn’t.
            // - Decides what to display based on the server’s reply for the booking page.
            if (data.status === 'success') {
                // Finds the upcoming bookings table section
                // - A user-defined constant named upcomingBody, holding the result of a built-in JavaScript document.querySelector() method that finds the <tbody> element inside the table with ID 'upcomingTable'.
                // - Targets the area where upcoming trip rows will be added.
                // - Lets the code update the upcoming bookings table with customer data.
                const upcomingBody = document.querySelector('#upcomingTable tbody');

                // Finds the past bookings table section
                // - A user-defined constant named pastBody, holding the result of document.querySelector() that finds the <tbody> element inside the table with ID 'pastTable'.
                // - Targets the area where past trip rows will be added.
                // - Lets the code update the past bookings table with customer data.
                const pastBody = document.querySelector('#pastTable tbody');

                // Gets the current date and time
                // - A user-defined constant named now, holding a built-in JavaScript Date object representing the current moment.
                // - Used to compare with booking departure times to sort trips into upcoming or past.
                // - Helps decide where each booking goes in the tables.
                const now = new Date();

                // Goes through each booking to make a table row
                // - A forEach loop, a built-in JavaScript method that runs code for each booking in the data.bookings list.
                // - Creates a row for each booking and places it in the right table based on time and status.
                // - Builds the tables dynamically for the customer’s bookings.
                data.bookings.forEach(booking => {
                    // Turns the departure time into a date
                    // - A user-defined constant named departure, holding a built-in JavaScript Date object created from booking.DepartureTime.
                    // - Converts the booking’s departure time into a format to compare with the current time.
                    // - Determines if the booking is upcoming or past.
                    const departure = new Date(booking.DepartureTime);

                    // Builds a table row’s content
                    // - A user-defined constant named row, holding a template literal (text with data inside backticks) with booking details.
                    // - Creates a <tr> row with table cells for BookingID, BusNumber, route, DepartureTime, SeatNumber, and Cost.
                    // - Note: Excludes an action column for cancellation, which is commented out for admin-only use.
                    const row = `
                        <tr>
                            <td>${booking.BookingID}</td>
                            <td>${booking.BusNumber}</td>
                            <td>${booking.StartLocation} to ${booking.Destination}</td>
                            <td>${booking.DepartureTime}</td>
                            <td>${booking.SeatNumber}</td>
                            <td>${booking.Cost}</td>
                        </tr>
                    `;

                    // Checks if the booking is upcoming and confirmed
                    // - A conditional statement checking if departure is later than now and booking.Status equals 'Confirmed'.
                    // - Places the row in the upcoming table if true, or the past table if false.
                    // - Sorts bookings into the correct table for the customer’s view.
                    if (departure > now && booking.Status === 'Confirmed') {
                        // Adds the row to the upcoming table
                        // - Updates upcomingBody.innerHTML, a built-in JavaScript property, by adding the row to the existing content.
                        // - Places the booking in the upcoming trips section of the page.
                        // - Shows confirmed future trips in the customer’s upcoming table.
                        upcomingBody.innerHTML += row;
                    } else {
                        // Adds the row to the past table
                        // - Updates pastBody.innerHTML by adding the row to the existing content.
                        // - Places the booking in the past trips section of the page.
                        // - Shows past or non-confirmed trips in the customer’s past table.
                        pastBody.innerHTML += row;
                    }
                    // Commented out code for admin-only cancellation; preserved for potential restoration
                    /*
                    if (departure > now && booking.Status === 'Confirmed') {
                        // Adds the row to the upcoming table
                        // Places the booking in the upcoming trips section
                        upcomingBody.innerHTML += row;
                    } else {
                        // Adds the row to the past table
                        // Places the booking in the past trips section without a cancel button
                        pastBody.innerHTML += row.replace('<td></td>', '');
                    }
                    */
                });
            } else {
                // Shows an error message
                // - Calls alert(), a built-in JavaScript method that shows a pop-up with data.message (e.g., “No bookings found”).
                // - Tells the customer why their bookings couldn’t load.
                // - Keeps the page usable even if there’s a problem fetching data.
                alert(data.message);
            }
        })
        // Handles errors during the fetch
        // - A built-in JavaScript .catch() method that runs a callback function if any error happens in the fetch chain.
        // - Logs the error to help developers troubleshoot issues.
        // - Ensures the page doesn’t break if there’s a problem.
        .catch(error => console.error('Error fetching bookings:', error));
});

// Commented out code for admin-only cancellation; preserved for potential restoration
/*
// Function to cancel a booking and refresh the page
// - A user-defined JavaScript function named cancelBooking, with one input (bookingID: a number for the booking’s unique ID).
// - Sends a web request to cancel a booking and reloads the page if successful.
// - Asks the user to confirm before canceling to avoid mistakes in the bus booking system.
function cancelBooking(bookingID) {
    // Shows a pop-up to confirm cancellation
    // - A conditional statement using the built-in JavaScript confirm() function, which shows a pop-up with a message and OK/Cancel buttons.
    // - Displays “Are you sure you want to cancel this booking?” to check the user’s intent.
    // - Only cancels if the user clicks OK (returns true), ensuring the customer wants to cancel.
    if (confirm('Are you sure you want to cancel this booking?')) {
        // Sends a web request to cancel the booking
        // - Uses the built-in JavaScript fetch() method to send data to a PHP file at '/php/cancelBooking.php' with POST settings.
        // - Sends the bookingID in URL-encoded format to cancel the specific booking.
        // - Starts a chain of steps to handle the server’s reply and update the page.
        fetch('/php/cancelBooking.php', {
            // Sets the request type
            // - A method property set to 'POST', a built-in JavaScript fetch option that tells the server to expect data.
            // - Ensures the server knows this is a cancellation request.
            method: 'POST',

            // Sets the data format
            // - A headers property with 'Content-Type' set to 'application/x-www-form-urlencoded', a built-in JavaScript fetch option.
            // - Tells the server the data is in a URL-encoded format, like a web form.
            // - Makes sure the server can read the sent bookingID.
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },

            // Sends the booking ID
            // - A body property set to a string with 'bookingID=' plus the bookingID value.
            // - Includes the ID of the booking to cancel on the server.
            // - Provides the info needed to cancel the booking.
            body: 'bookingID=' + bookingID
        })
            // Processes the server’s reply
            // - A built-in JavaScript .then() method that converts the response to a JSON object using response.json().
            // - Gets the server’s reply into a usable format to check the cancellation status.
            .then(response => response.json())
            // Handles the response data
            // - A .then() method that takes the data object and shows a message or reloads the page.
            // - Updates the page based on the server’s reply about the cancellation.
            .then(data => {
                // Checks if the cancellation worked
                // - A conditional statement checking if data.status equals 'success'.
                // - Shows a success message and refreshes the page if cancellation worked, or an error if it didn’t.
                // - Gives feedback to the customer based on the server’s reply.
                if (data.status === 'success') {
                    // Shows a success message
                    // - Calls alert() with data.message (e.g., “Booking canceled successfully”).
                    // - Tells the customer the booking was canceled.
                    // - Confirms the cancellation in the bus booking system.
                    alert(data.message);

                    // Reloads the page
                    // - Calls location.reload(), a built-in JavaScript method that refreshes the webpage.
                    // - Updates the booking tables to show the latest list without the canceled booking.
                    // - Keeps the page current after cancellation.
                    location.reload();
                } else {
                    // Shows an error message
                    // - Calls alert() with “Cancellation failed:” plus data.message (e.g., “Booking already canceled”).
                    // - Tells the customer why the cancellation didn’t work.
                    // - Helps customers understand issues with their cancellation.
                    alert('Cancellation failed: ' + data.message);
                }
            })
            // Handles errors during the cancellation
            // - A .catch() method that logs any errors in the fetch chain.
            // - Writes error details to the developer tools for troubleshooting.
            // - Keeps the page working even if cancellation fails.
            .catch(error => console.error('Error canceling booking:', error));
    }
}
*/