// File: admin_bookings.js
// Handles fetching, displaying, deleting, and canceling bookings for the admin_bookings.html page in the bus booking system.

// Defines async function to fetch and display bookings
// - User-defined async function named fetchBookings, with no inputs.
// - Retrieves booking data from server and populates table in admin_bookings.html.
// - Clears and updates table with booking details and action buttons.
async function fetchBookings() {
    // Starts error handling block
    // - Built-in try-catch block to handle network or data issues.
    // - Ensures robust error handling during booking data fetching.
    // - Prevents crashes in the booking management interface.
    try {
        // Declares response variable
        // - User-defined constant named response using fetch(), a built-in async method.
        // - Sends GET request to PHP endpoint at '../../../php/admin/fetchBookings.php'.
        // - Retrieves all booking records for display in the admin table.
        const response = await fetch('../../../php/admin/fetchBookings.php');
        
        // Declares bookings array variable
        // - User-defined constant named bookings using response.json(), a built-in method.
        // - Parses JSON response into an array of booking objects.
        // - Contains fields like BookingID, CustomerID, ScheduleID, etc.
        const bookings = await response.json();

        // Declares table body variable
        // - User-defined constant named tableBody using document.getElementById(), a built-in method.
        // - Finds the <tbody> element with ID 'bookingTableBody' in admin_bookings.html.
        // - Targets the table body for populating booking rows.
        const tableBody = document.getElementById('bookingTableBody');
        
        // Clears table body content
        // - Built-in assignment setting tableBody.innerHTML to an empty string.
        // - Removes all existing table rows to prevent duplicates.
        // - Prepares the table for fresh booking data.
        tableBody.innerHTML = ''; // Clear existing rows

        // Iterates through bookings
        // - Built-in forEach method on bookings array, with booking parameter (object).
        // - Processes each booking to create and append a table row.
        // - Builds the table dynamically for the admin dashboard.
        bookings.forEach(booking => {
            // Declares row variable
            // - User-defined constant named row using document.createElement(), a built-in method.
            // - Creates a <tr> element to hold one booking’s data.
            // - Serves as a container for table cells in the table.
            const row = document.createElement('tr');
            
            // Populates row with booking data
            // - Built-in assignment setting row.innerHTML using a template literal.
            // - Inserts <td> elements with booking properties and action buttons (Edit, Delete, Cancel).
            // - Conditionally includes Cancel button if booking.Status is 'Confirmed' using a ternary operator.
            row.innerHTML = `
                <td>${booking.BookingID}</td>
                <td>${booking.CustomerID}</td>
                <td>${booking.ScheduleID}</td>
                <td>${booking.SeatNumber}</td>
                <td>${booking.BookingDate}</td>
                <td>${booking.TravelDate}</td>
                <td>${booking.Status}</td>
                <td>
                    <a href="edit_booking.html?id=${booking.BookingID}" class="btn">Edit</a>
                    <button onclick="deleteBooking(${booking.BookingID})">Delete</button>
                    ${booking.Status === 'Confirmed' ? `<button class="btn" onclick="cancelBooking(${booking.BookingID})">Cancel</button>` : ''}
                </td>
            `;
            
            // Appends row to table
            // - Built-in method tableBody.appendChild() with row parameter.
            // - Adds the populated <tr> to the <tbody> element.
            // - Updates the table in admin_bookings.html to display the booking.
            tableBody.appendChild(row);
        });
    } catch (error) {
        // Logs error
        // - Built-in console.error() method with error object.
        // - Outputs fetch issues to developer tools for debugging.
        // - Helps diagnose network or JSON parsing problems.
        console.error('Error fetching bookings:', error);
    }
}

// Defines async function to delete a booking
// - User-defined async function named deleteBooking, with one input: bookingId (number, booking identifier).
// - Deletes a booking via server request and refreshes the table.
// - Confirms deletion with user before proceeding in admin_bookings.html.
async function deleteBooking(bookingId) {
    // Prompts user confirmation
    // - Built-in conditional using confirm(), a built-in method with a template literal.
    // - Displays a dialog asking if user wants to delete booking with bookingId.
    // - Proceeds only if user clicks OK (returns true).
    if (confirm(`Are you sure you want to delete booking with ID: ${bookingId}?`)) {
        // Starts error handling block
        // - Built-in try-catch block to handle network or server issues.
        // - Ensures robust error handling during booking deletion.
        // - Maintains stability in the booking management system.
        try {
            // Declares response variable
            // - User-defined constant named response using fetch(), a built-in async method.
            // - Sends GET request to PHP endpoint with bookingId in query string.
            // - Requests deletion of the specified booking from the server.
            const response = await fetch(`../../../php/admin/deleteBooking.php?id=${bookingId}`);
            
            // Declares result variable
            // - User-defined constant named result using response.json(), a built-in method.
            // - Parses JSON response into an object with status property.
            // - Indicates success or failure of the deletion request.
            const result = await response.json();

            // Checks deletion status
            // - Built-in conditional testing result.status for 'success'.
            // - Handles successful deletion or displays error message.
            // - Determines next action based on server response.
            if (result.status === 'success') {
                // Shows success message
                // - Built-in alert() method with success message.
                // - Notifies user that the booking was deleted successfully.
                // - Confirms deletion in admin_bookings.html.
                alert('Booking deleted successfully');
                
                // Refreshes booking table
                // - Built-in function call to user-defined fetchBookings.
                // - Reloads booking data from server to update table.
                // - Reflects deletion in the admin interface.
                fetchBookings(); // Refresh the booking list
            } else {
                // Shows error message
                // - Built-in alert() method with failure message.
                // - Notifies user that deletion failed (e.g., due to server constraints).
                // - Provides feedback for troubleshooting.
                alert('Failed to delete booking');
            }
        } catch (error) {
            // Logs error
            // - Built-in console.error() method with error object.
            // - Outputs deletion issues to developer tools for debugging.
            // - Helps diagnose network or server problems.
            console.error('Error deleting booking:', error);
        }
    }
}

// Defines async function to cancel a booking
// - User-defined async function named cancelBooking, with one input: bookingId (number, booking identifier).
// - Cancels a booking via server request and refreshes the table.
// - Confirms cancellation with user before proceeding in admin_bookings.html.
async function cancelBooking(bookingId) {
    // Prompts user confirmation
    // - Built-in conditional using confirm(), a built-in method with a template literal.
    // - Displays a dialog asking if user wants to cancel booking with bookingId.
    // - Proceeds only if user clicks OK (returns true).
    if (confirm(`Are you sure you want to cancel booking with ID: ${bookingId}?`)) {
        // Starts error handling block
        // - Built-in try-catch block to handle network or server issues.
        // - Ensures robust error handling during booking cancellation.
        // - Maintains stability in the booking management system.
        try {
            // Declares response variable
            // - User-defined constant named response using fetch(), a built-in async method.
            // - Sends POST request to PHP endpoint at '/php/cancelBooking.php'.
            // - Includes bookingId in URL-encoded form data for cancellation.
            const response = await fetch('/php/cancelBooking.php', {
                // Sets request method
                // - User-defined property named method with value 'POST'.
                // - Specifies POST request type for data submission.
                // - Informs server to expect form data.
                method: 'POST',
                // Defines headers
                // - User-defined property named headers using an object literal.
                // - Sets Content-Type to 'application/x-www-form-urlencoded'.
                // - Prepares server for URL-encoded payload.
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                // Sets request body
                // - User-defined property named body using a string with bookingId.
                // - Sends bookingID as URL-encoded data (e.g., 'bookingID=123').
                // - Identifies the booking to cancel on the server.
                body: 'bookingID=' + bookingId
            });
            
            // Declares result variable
            // - User-defined constant named result using response.json(), a built-in method.
            // - Parses JSON response into an object with status and message properties.
            // - Indicates success or failure of the cancellation request.
            const result = await response.json();

            // Checks cancellation status
            // - Built-in conditional testing result.status for 'success'.
            // - Handles successful cancellation or displays error message.
            // - Determines next action based on server response.
            if (result.status === 'success') {
                // Shows success message
                // - Built-in alert() method with result.message.
                // - Notifies user of successful cancellation with server-provided details (e.g., refund info).
                // - Confirms cancellation in admin_bookings.html.
                alert(result.message);
                
                // Refreshes booking table
                // - Built-in function call to user-defined fetchBookings.
                // - Reloads booking data from server to update table.
                // - Reflects cancellation in the admin interface.
                fetchBookings(); // Refresh the booking list
            } else {
                // Shows error message
                // - Built-in alert() method with template literal including result.message.
                // - Notifies user of cancellation failure with specific server message.
                // - Informs user of issues like invalid booking ID.
                alert(`Failed to cancel booking: ${result.message}`);
            }
        } catch (error) {
            // Logs error
            // - Built-in console.error() method with error object.
            // - Outputs cancellation issues to developer tools for debugging.
            // - Helps diagnose network or server problems.
            console.error('Error canceling booking:', error);
            // Shows error message
            // - Built-in alert() method with error message.
            // - Notifies user of cancellation failure due to network error.
            // - Provides feedback for troubleshooting in admin_bookings.html.
            alert('Failed to cancel booking due to network error');
        }
    }
}

// Attaches event listener for page load
// - Built-in event listener using document.addEventListener(), with 'DOMContentLoaded' event.
// - Executes arrow function to initialize booking table and search functionality.
// - Triggers when admin_bookings.html fully loads.
document.addEventListener('DOMContentLoaded', () => {
    // Fetches initial bookings
    // - Built-in function call to user-defined fetchBookings.
    // - Populates the booking table with data from the server.
    // - Initializes the table display in admin_bookings.html.
    fetchBookings();

    // Attaches search event listener
    // - Built-in method document.getElementById().addEventListener() with 'input' event.
    // - Listens for input changes on element with ID 'bookingSearch'.
    // - Triggers filtering of booking table based on user input.
    document.getElementById('bookingSearch').addEventListener('input', () => {
        // Filters table rows
        // - Built-in function call to user-defined filterTable from script.js.
        // - Passes table ID 'bookingTable', input ID 'bookingSearch', message ID 'noBookings', and column indices [0, 1, 2, 5].
        // - Filters rows by BookingID, CustomerID, ScheduleID, or TravelDate.
        filterTable('bookingTable', 'bookingSearch', 'noBookings', [0, 1, 2, 5]);
    });
});