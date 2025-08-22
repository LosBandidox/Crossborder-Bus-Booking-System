// File: edit_booking.js
// Purpose: Manages getting and updating booking details on the edit_booking.html page in the International Bus Booking System.
// Fetches booking data, fills the edit form, validates input, and submits updates to the server for admin users.

// Function to convert dates for the database
// - A user-defined JavaScript function named convertDateFormat, with one input: dateStr (string).
// - Changes a date from DD-MM-YYYY to YYYY-MM-DD format for server submission.
// - Ensures dates match the database format when updating bookings.
function convertDateFormat(dateStr) {
    // Checks the date string length
    // - A conditional statement testing if dateStr is exactly 10 characters long (DD-MM-YYYY).
    // - Returns the original string if the length is wrong.
    // - Prevents processing invalid date formats.
    if (dateStr.length !== 10) {
        // Returns unchanged date
        // - Returns dateStr as is if it doesn’t have 10 characters.
        // - Keeps invalid inputs from being reformatted incorrectly.
        return dateStr;
    }

    // Checks hyphen positions
    // - A conditional statement testing if characters at positions 2 and 5 are hyphens (DD-MM-YYYY).
    // - Returns the original string if hyphens are missing or misplaced.
    // - Ensures the date follows the expected format.
    if (dateStr[2] !== '-' || dateStr[5] !== '-') {
        // Returns unchanged date
        // - Returns dateStr as is if hyphens aren’t in the right spots.
        // - Prevents invalid date formats from being processed.
        return dateStr;
    }

    // Gets date parts
    // - User-defined variables named dayStr, monthStr, and yearStr, holding substrings from dateStr using substring(), a built-in JavaScript method.
    // - Extracts day (positions 0-2), month (3-5), and year (6-10).
    // - Prepares date components for reformatting.
    let dayStr = dateStr.substring(0, 2);
    let monthStr = dateStr.substring(3, 5);
    let yearStr = dateStr.substring(6, 10);

    // Checks part lengths
    // - A conditional statement testing if dayStr (2), monthStr (2), and yearStr (4) have correct lengths.
    // - Returns the original string if any length is wrong.
    // - Ensures date components are properly sized.
    if (dayStr.length !== 2 || monthStr.length !== 2 || yearStr.length !== 4) {
        // Returns unchanged date
        // - Returns dateStr as is if any part has an incorrect length.
        // - Prevents invalid date components from being used.
        return dateStr;
    }

    // Converts parts to numbers
    // - User-defined variables named day, month, and year, holding numbers from parseInt(), a built-in JavaScript method, with base 10.
    // - Turns dayStr, monthStr, and yearStr into numbers for validation.
    // - Prepares to check if the parts are valid numbers.
    let day = parseInt(dayStr, 10);
    let month = parseInt(monthStr, 10);
    let year = parseInt(yearStr, 10);

    // Checks if parts are valid numbers
    // - A conditional statement using isNaN(), a built-in JavaScript method, to test if day, month, or year is not a number.
    // - Returns the original string if any part is invalid.
    // - Ensures only valid numbers are reformatted.
    if (isNaN(day) || isNaN(month) || isNaN(year)) {
        // Returns unchanged date
        // - Returns dateStr as is if any part isn’t a valid number.
        // - Prevents invalid data from being reformatted.
        return dateStr;
    }

    // Returns the new date format
    // - Returns a template literal combining yearStr, monthStr, and dayStr as YYYY-MM-DD.
    // - Creates a database-friendly date format.
    // - Prepares the date for server submission.
    return `${yearStr}-${monthStr}-${dayStr}`;
}

// Function to convert dates for form validation
// - A user-defined JavaScript function named convertToValidationFormat, with one input: dateStr (string).
// - Changes a date from YYYY-MM-DD to DD-MM-YYYY format for form validation.
// - Matches the format expected by the validation function.
function convertToValidationFormat(dateStr) {
    // Checks the date string length
    // - A conditional statement testing if dateStr is exactly 10 characters long (YYYY-MM-DD).
    // - Returns the original string if the length is wrong.
    // - Prevents processing invalid date formats.
    if (dateStr.length !== 10) {
        // Returns unchanged date
        // - Returns dateStr as is if it doesn’t have 10 characters.
        // - Keeps invalid inputs from being reformatted.
        return dateStr;
    }

    // Checks hyphen positions
    // - A conditional statement testing if characters at positions 4 and 7 are hyphens (YYYY-MM-DD).
    // - Returns the original string if hyphens are missing or misplaced.
    // - Ensures the date follows the expected format.
    if (dateStr[4] !== '-' || dateStr[7] !== '-') {
        // Returns unchanged date
        // - Returns dateStr as is if hyphens aren’t in the right spots.
        // - Prevents invalid date formats from processing.
        return dateStr;
    }

    // Gets date parts
    // - User-defined variables named yearStr, monthStr, and dayStr using substring().
    // - Extracts year (0-4), month (5-7), and day (8-10).
    // - Prepares date components for reformatting.
    let yearStr = dateStr.substring(0, 4);
    let monthStr = dateStr.substring(5, 7);
    let dayStr = dateStr.substring(8, 10);

    // Checks part lengths
    // - A conditional statement testing if yearStr (4), monthStr (2), and dayStr (2) have correct lengths.
    // - Returns the original string if any length is wrong.
    // - Ensures date components are properly sized.
    if (yearStr.length !== 4 || monthStr.length !== 2 || dayStr.length !== 2) {
        // Returns unchanged date
        // - Returns dateStr as is if any part has an incorrect length.
        // - Prevents invalid date components from being used.
        return dateStr;
    }

    // Converts parts to numbers
    // - User-defined variables named year, month, and day using parseInt() with base 10.
    // - Turns yearStr, monthStr, and dayStr into numbers for validation.
    // - Prepares to check if the parts are valid numbers.
    let year = parseInt(yearStr, 10);
    let month = parseInt(monthStr, 10);
    let day = parseInt(dayStr, 10);

    // Checks if parts are valid numbers
    // - A conditional statement using isNaN() to test if year, month, or day is not a number.
    // - Returns the original string if any part is invalid.
    // - Ensures only valid numbers are reformatted.
    if (isNaN(year) || isNaN(month) || isNaN(day)) {
        // Returns unchanged date
        // - Returns dateStr as is if any part isn’t a valid number.
        // - Prevents invalid data from being reformatted.
        return dateStr;
    }

    // Returns the new date format
    // - Returns a template literal combining dayStr, monthStr, and yearStr as DD-MM-YYYY.
    // - Creates a validation-friendly date format.
    // - Prepares the date for form checking.
    return `${dayStr}-${monthStr}-${yearStr}`;
}

// Function to change form field IDs for validation
// - A user-defined JavaScript function named mapIdsForValidation, with no inputs.
// - Temporarily changes edit form field IDs to match add form IDs for validation.
// - Allows reuse of the same validation logic for adding and editing bookings.
function mapIdsForValidation() {
    // Stores ID pairs
    // - A user-defined constant named idMappings, an object with edit form IDs as keys and add form IDs as values.
    // - Maps fields like editCustomerID to customerID for validation.
    // - Defines which fields need ID changes.
    const idMappings = {
        editCustomerID: 'customerID',
        editScheduleID: 'scheduleID',
        editSeatNumber: 'seatNumber',
        editBookingDate: 'bookingDate',
        editTravelDate: 'travelDate'
    };

    // Goes through each ID pair
    // - Uses Object.entries(), a built-in JavaScript method, with for...of to loop through idMappings.
    // - Changes each edit form field’s ID to its add form ID.
    // - Prepares the form for validation with matching IDs.
    for (const [editId, addId] of Object.entries(idMappings)) {
        // Finds the form field
        // - A user-defined constant named element, holding the result of document.getElementById(editId).
        // - Targets the field with the edit ID to change it.
        // - Prepares to update the field’s ID.
        const element = document.getElementById(editId);

        // Checks if the field exists
        // - A conditional statement checking if element is found.
        // - Changes the ID and converts date fields if the field exists.
        // - Ensures only valid fields are modified.
        if (element) {
            // Converts dates for validation
            // - A conditional statement checking if editId is 'editBookingDate' or 'editTravelDate'.
            // - Calls convertToValidationFormat() to change date values to DD-MM-YYYY.
            // - Matches the format expected by the validation function.
            if (editId === 'editBookingDate' || editId === 'editTravelDate') {
                element.value = convertToValidationFormat(element.value);
            }
            // Changes the field’s ID
            // - Sets element.id to addId, the corresponding add form ID.
            // - Updates the field to match the add form for validation.
            element.id = addId;
        }
    }
}

// Function to restore original form field IDs
// - A user-defined JavaScript function named restoreOriginalIds, with no inputs.
// - Changes add form IDs back to edit form IDs after validation.
// - Restores the form to its original state for submission.
function restoreOriginalIds() {
    // Stores ID pairs
    // - A user-defined constant named idMappings, an object with edit form IDs as keys and add form IDs as values.
    // - Maps fields like editCustomerID to customerID for restoration.
    // - Defines which fields need ID changes reversed.
    const idMappings = {
        editCustomerID: 'customerID',
        editScheduleID: 'scheduleID',
        editSeatNumber: 'seatNumber',
        editBookingDate: 'bookingDate',
        editTravelDate: 'travelDate'
    };

    // Goes through each ID pair
    // - Uses Object.entries() with for...of to loop through idMappings.
    // - Restores each form field’s original edit ID.
    // - Reverts the form to its original state.
    for (const [editId, addId] of Object.entries(idMappings)) {
        // Finds the form field
        // - A user-defined constant named element, holding the result of document.getElementById(addId).
        // - Targets the field with the add ID to restore it.
        // - Prepares to revert the field’s ID.
        const element = document.getElementById(addId);

        // Checks if the field exists
        // - A conditional statement checking if element is found.
        // - Restores the ID and converts date fields if the field exists.
        // - Ensures only valid fields are modified.
        if (element) {
            // Converts dates for submission
            // - A conditional statement checking if addId is 'bookingDate' or 'travelDate'.
            // - Calls convertDateFormat() to change date values back to YYYY-MM-DD.
            // - Matches the format expected by the server.
            if (addId === 'bookingDate' || addId === 'travelDate') {
                element.value = convertDateFormat(element.value);
            }
            // Restores the field’s ID
            // - Sets element.id to editId, the original edit form ID.
            // - Reverts the field to its original ID for submission.
            element.id = editId;
        }
    }
}

// Function to get booking details
// - A user-defined async JavaScript function named fetchBookingDetails, with no inputs.
// - Sends a web request to fetch booking data and fills the edit form.
// - Loads booking details for admins to edit on the edit_booking.html page.
async function fetchBookingDetails() {
    // Gets URL query parameters
    // - A user-defined constant named urlParams, holding a built-in URLSearchParams object from window.location.search.
    // - Parses the URL’s query string to find the booking ID.
    // - Prepares to fetch the specific booking to edit.
    const urlParams = new URLSearchParams(window.location.search);

    // Gets the booking ID
    // - A user-defined constant named bookingId, holding the result of urlParams.get('id').
    // - Stores the booking ID from the URL, like ?id=123.
    // - Identifies which booking to retrieve from the server.
    const bookingId = urlParams.get('id');

    // Checks if the booking ID exists
    // - A conditional statement checking if bookingId is falsy (null or undefined).
    // - Shows an error and redirects if no ID is provided.
    // - Prevents loading the page without a valid booking.
    if (!bookingId) {
        // Shows an error message
        // - Calls alert(), a built-in JavaScript method, with “Booking ID not provided”.
        // - Tells the admin the page needs a booking ID.
        // - Informs them they can’t proceed without it.
        alert('Booking ID not provided');

        // Redirects to the bookings page
        // - Sets window.location.href, a built-in property, to 'admin_bookings.html'.
        // - Sends the admin back to the main bookings list.
        // - Keeps the system usable without a valid ID.
        window.location.href = 'admin_bookings.html';
        return;
    }

    // Handles potential errors
    // - A try-catch block to manage issues like network failures or bad data.
    // - Attempts to fetch booking data and catches any problems.
    // - Keeps the page from breaking on errors.
    try {
        // Sends a web request
        // - A user-defined constant named response, holding the result of an await fetch() call to '../../../php/admin/getBooking.php?id=${bookingId}'.
        // - Requests booking details from the server using the booking ID.
        // - Waits for the server’s reply with booking information.
        const response = await fetch(`../../../php/admin/getBooking.php?id=${bookingId}`);

        // Gets the booking data
        // - A user-defined constant named booking, holding the JSON object from response.json().
        // - Turns the server’s reply into an object with booking details.
        // - Prepares the data to fill the form.
        const booking = await response.json();

        // Checks if the booking exists
        // - A conditional statement checking if booking.BookingID is falsy.
        // - Shows an error and redirects if the booking isn’t found.
        // - Prevents editing nonexistent bookings.
        if (!booking.BookingID) {
            // Shows an error message
            // - Calls alert() with “Booking not found”.
            // - Tells the admin the booking ID doesn’t exist.
            // - Informs them they can’t edit this booking.
            alert('Booking not found');

            // Redirects to the bookings page
            // - Sets window.location.href to 'admin_bookings.html'.
            // - Sends the admin back to the main bookings list.
            // - Keeps the system usable for valid bookings.
            window.location.href = 'admin_bookings.html';
            return;
        }

        // Fills the form fields
        // - Sets the value properties of form inputs using document.getElementById() for IDs like 'editBookingId'.
        // - Populates fields with booking data like BookingID, CustomerID, and dates.
        // - Displays booking details for the admin to edit.
        document.getElementById('editBookingId').value = booking.BookingID;
        document.getElementById('editCustomerID').value = booking.CustomerID;
        document.getElementById('editScheduleID').value = booking.ScheduleID;
        document.getElementById('editSeatNumber').value = booking.SeatNumber;
        document.getElementById('editBookingDate').value = booking.BookingDate;
        document.getElementById('editTravelDate').value = booking.TravelDate;
        document.getElementById('editStatus').value = booking.Status;
    } catch (error) {
        // Shows an error message
        // - Calls alert() with “Failed to fetch booking details:” plus error.message.
        // - Tells the admin there was a problem loading the booking.
        // - Provides specific error details for clarity.
        alert('Failed to fetch booking details: ' + error.message);

        // Logs the error
        // - Uses console.error(), a built-in method, to write “Error fetching booking details:” and the error to the developer tools.
        // - Helps developers debug issues like network or server problems.
        console.error('Error fetching booking details:', error);
    }
}

// Function to validate and submit the form
// - A user-defined async JavaScript function named validateAndSubmitBookingForm, with one input: event (form submission event).
// - Validates the form, submits updated booking data, and redirects or shows errors.
// - Processes the admin’s changes to a booking for server update.
async function validateAndSubmitBookingForm(event) {
    // Stops the page from refreshing
    // - Calls preventDefault(), a built-in method of the event object, to stop the form from reloading the page.
    // - Keeps the admin on the edit page after submitting.
    // - Makes the update process smooth without reloads.
    event.preventDefault();

    // Changes form IDs for validation
    // - Calls the user-defined mapIdsForValidation() function.
    // - Temporarily sets edit form IDs to add form IDs.
    // - Prepares the form for validation with reused logic.
    mapIdsForValidation();

    // Checks if the form is valid
    // - Calls a user-defined validateBookingForm() function (assumed in formValidation.js) to check form inputs.
    // - Stops if validation fails, restoring original IDs.
    // - Ensures only valid data is submitted.
    if (!validateBookingForm()) {
        // Restores original IDs
        // - Calls the user-defined restoreOriginalIds() function.
        // - Reverts form IDs to their edit form values.
        // - Keeps the form in its original state after failed validation.
        restoreOriginalIds();
        return;
    }

    // Restores original IDs
    // - Calls restoreOriginalIds() after successful validation.
    // - Reverts form IDs to their edit form values for submission.
    // - Prepares the form data with correct IDs.
    restoreOriginalIds();

    // Collects form data
    // - A user-defined constant named formData, holding a built-in FormData object from event.target (the form).
    // - Gathers all input values like booking ID and seat number.
    // - Prepares the data for processing.
    const formData = new FormData(event.target);

    // Creates a booking object
    // - A user-defined constant named bookingData, an object with form values accessed via formData.get().
    // - Includes booking details, with dates converted using convertDateFormat().
    // - Prepares structured data for the server.
    const bookingData = {
        bookingId: formData.get('bookingId'),
        customerID: formData.get('customerID'),
        scheduleID: formData.get('scheduleID'),
        seatNumber: formData.get('seatNumber'),
        bookingDate: convertDateFormat(formData.get('bookingDate')),
        travelDate: convertDateFormat(formData.get('travelDate')),
        status: formData.get('status')
    };

    // Handles potential errors
    // - A try-catch block to manage issues like network failures during submission.
    // - Attempts to update the booking and catches any problems.
    // - Keeps the page from breaking on errors.
    try {
        // Sends a web request
        // - A user-defined constant named response, holding the result of an await fetch() call to '../../../php/admin/updateBooking.php'.
        // - Sends bookingData as JSON using a POST request.
        // - Waits for the server’s reply on the update.
        const response = await fetch('../../../php/admin/updateBooking.php', {
            // Sets the request type
            // - A method property set to 'POST', a built-in fetch option.
            // - Tells the server to expect data.
            method: 'POST',

            // Sets the data format
            // - A headers property with 'Content-Type' set to 'application/json'.
            // - Informs the server the data is in JSON format.
            headers: {
                'Content-Type': 'application/json'
            },

            // Sends the booking data
            // - A body property with bookingData converted to a JSON string using JSON.stringify().
            // - Includes all booking details for the update.
            body: JSON.stringify(bookingData)
        });

        // Gets the server reply
        // - A user-defined constant named result, holding the JSON object from response.json().
        // - Turns the server’s reply into an object with status and message.
        // - Prepares to check if the update succeeded.
        const result = await response.json();

        // Checks for success
        // - A conditional statement checking if result.status equals 'success'.
        // - Shows a success message and redirects, or displays an error.
        // - Handles the server’s response to the update.
        if (result.status === 'success') {
            // Shows a success message
            // - Calls alert() with “Booking updated successfully”.
            // - Tells the admin the booking was updated.
            // - Confirms the changes were saved.
            alert('Booking updated successfully');

            // Redirects to the bookings page
            // - Sets window.location.href to 'admin_bookings.html'.
            // - Sends the admin back to the main bookings list.
            // - Completes the update process.
            window.location.href = 'admin_bookings.html';
        } else {
            // Shows an error message
            // - Calls alert() with “Failed to update booking:” plus result.message.
            // - Tells the admin why the update failed, like “Invalid seat number”.
            // - Provides specific error details for clarity.
            alert('Failed to update booking: ' + result.message);
        }
    } catch (error) {
        // Shows an error message
        // - Calls alert() with “Failed to update booking due to network error:” plus error.message.
        // - Tells the admin there was a problem updating the booking.
        // - Provides network-related error details.
        alert('Failed to update booking due to network error: ' + error.message);

        // Logs the error
        // - Uses console.error() to write “Error updating booking:” and the error to the developer tools.
        // - Helps developers debug issues like network or server problems.
        console.error('Error updating booking:', error);
    }
}

// Sets up the page to load booking details
// - Adds an event listener using document.addEventListener() for the “DOMContentLoaded” event.
// - Runs the user-defined fetchBookingDetails() function when the page’s HTML is fully loaded.
// - Loads booking details as soon as the edit page opens.
document.addEventListener('DOMContentLoaded', fetchBookingDetails);