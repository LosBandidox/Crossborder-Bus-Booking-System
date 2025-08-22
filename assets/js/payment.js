// File: payment.js
// Purpose: Sets up the payment form and fetches booking details on the customer payment page in the International Bus Booking System.
// Prepares the form with booking ID and cost, toggles payment fields, processes payments, and redirects to booking history upon success.

// Sets up the payment form and fetches data when the page loads
// - Adds an event listener to the document for the 'DOMContentLoaded' event, a built-in JavaScript event that runs when the webpage is fully loaded.
// - Runs a callback function to get URL parameters, fill the form, and set up event listeners for payment options and form submission.
// - Gets the payment page ready to show booking details and handle payments right away.
document.addEventListener('DOMContentLoaded', () => {
    // Gets URL query parameters
    // - A user-defined constant named urlParams, holding a built-in JavaScript URLSearchParams object created from window.location.search, the query part of the page’s URL.
    // - Stores key-value pairs from the URL, like bookingID and message, to use in the form.
    // - Helps find the booking ID and any messages for the payment process.
    const urlParams = new URLSearchParams(window.location.search);

    // Gets the booking ID from the URL
    // - A user-defined constant named bookingID, holding the result of urlParams.get('bookingID'), a built-in JavaScript method, or an empty string if not found.
    // - Stores the booking ID from the URL to identify which booking to fetch.
    // - Ensures a default value to avoid errors if no ID is provided.
    const bookingID = urlParams.get('bookingID') || '';

    // Gets an optional message from the URL
    // - A user-defined constant named message, holding the result of urlParams.get('message'), which returns a string or null if not set.
    // - Stores any message, like “Seats unavailable,” to show to the customer.
    // - Used to alert the customer about booking issues.
    const message = urlParams.get('message');

    // Shows a message if one exists
    // - A conditional statement checking if message is truthy (not null or empty).
    // - Calls alert(), a built-in JavaScript method, with decodeURIComponent(), a built-in method that decodes URL-encoded characters in the message.
    // - Displays booking-related alerts, like partial booking issues, to the customer.
    if (message) {
        alert(decodeURIComponent(message));
    }

    // Fills the booking ID field
    // - Sets the value property, a built-in JavaScript feature, of the input element with ID 'bookingID' using document.getElementById(), a built-in method.
    // - Puts the bookingID into the form’s booking ID field.
    // - Shows the booking ID on the payment form for the customer.
    document.getElementById('bookingID').value = bookingID;

    // Checks if a booking ID exists
    // - A conditional statement checking if bookingID is falsy (empty).
    // - Shows an alert with “No booking selected.” and exits with return if no ID is provided.
    // - Prevents fetching data if no booking is selected, keeping the form safe.
    if (!bookingID) {
        alert('No booking selected.');
        return;
    }

    // Sends a web request to get the booking cost
    // - Uses the built-in JavaScript fetch() method to request data from a PHP file at '/php/fetchBookingCost.php' with the bookingID as a query parameter.
    // - Starts a chain of steps to fetch and display the booking amount and seat count.
    // - Gets the cost and seat details for the customer’s booking.
    fetch('/php/fetchBookingCost.php?bookingID=' + bookingID)
        // Processes the server’s reply
        // - A built-in JavaScript .then() method that converts the response to a JSON object using response.json().
        // - Turns the server’s reply into a usable object with booking data.
        // - Prepares the cost and seat count to show on the form.
        .then(response => response.json())
        // Handles the booking data
        // - A .then() method that takes the data object and updates the form or shows an error.
        // - Fills the amount field and shows seat count if successful, or logs an error if not.
        .then(data => {
            // Checks if the fetch was successful
            // - A conditional statement checking if data.status equals 'success'.
            // - Updates the form with amount and seat count if successful, or shows an error if not.
            // - Decides what to display based on the server’s reply for the payment form.
            if (data.status === 'success') {
                // Fills the amount field
                // - Sets the value property of the input element with ID 'amountPaid' to data.amount, formatted to two decimal places using the built-in toFixed(2) method.
                // - Shows the booking cost in the form’s amount field.
                // - Displays the exact amount the customer needs to pay.
                document.getElementById('amountPaid').value = data.amount.toFixed(2);
                // Adds seat count to the form
                // - Uses insertAdjacentHTML(), a built-in JavaScript method, to add a paragraph before the paymentForm element’s content.
                // - Shows the number of seats (e.g., “2 seats selected”) using a template literal and a ternary operator to pluralize “seat” if data.seatCount is greater than 1.
                // - Informs the customer how many seats are in their booking.
                document.getElementById('paymentForm').insertAdjacentHTML('afterbegin', `<p>${data.seatCount} seat${data.seatCount > 1 ? 's' : ''} selected</p>`);
            } else {
                // Logs an error for debugging
                // - Calls console.error(), a built-in JavaScript method, with a message including the bookingID and data.message (e.g., “Booking not found”).
                // - Writes error details to the browser’s developer tools to help troubleshoot.
                // - Helps developers fix issues with fetching the booking amount.
                console.error('Error fetching amount for Booking ID ' + bookingID + ': ' + data.message);
                // Shows an error message
                // - Calls alert() with “Error fetching booking amount:” plus data.message.
                // - Tells the customer why the amount couldn’t load, like “Invalid booking ID.”
                // - Keeps the form usable even if there’s a problem.
                alert('Error fetching booking amount: ' + data.message);
            }
        })
        // Handles errors during the fetch
        // - A built-in JavaScript .catch() method that logs any errors in the fetch chain.
        // - Logs the error with the bookingID and shows it to the customer.
        // - Keeps the form working even if fetching fails.
        .catch(error => {
            // Logs an error for debugging
            // - Calls console.error() with the bookingID and error object.
            // - Writes error details to the developer tools, like “Network error.”
            // - Helps developers find issues like bad internet during the fetch.
            console.error('Error fetching amount for Booking ID ' + bookingID + ':', error);
            // Shows an error message
            // - Calls alert() with “Error fetching booking amount:” plus error.message.
            // - Tells the customer there was a problem, like “Server unreachable.”
            // - Provides feedback to keep the customer informed during issues.
            alert('Error fetching booking amount: ' + error.message);
        });

    // Sets up the mobile money option
    // - Adds an event listener to the radio button with ID 'mobileMoney' using addEventListener(), a built-in JavaScript method.
    // - Listens for the 'change' event, which happens when the customer selects mobile money, and calls the user-defined toggleFields() function.
    // - Lets the form show or hide mobile payment fields based on the selection.
    document.getElementById('mobileMoney').addEventListener('change', toggleFields);

    // Sets up the card option
    // - Adds an event listener to the radio button with ID 'card' using addEventListener().
    // - Listens for the 'change' event, which happens when the customer selects card payment, and calls toggleFields().
    // - Lets the form show or hide card payment fields based on the selection.
    document.getElementById('card').addEventListener('change', toggleFields);

    // Sets up the form submission
    // - A user-defined constant named paymentForm, holding the result of document.getElementById() for the form with ID 'paymentForm'.
    // - Adds an event listener for the 'submit' event using addEventListener(), which runs a callback that logs “Form submitted” and calls the user-defined processPayment() function with the event object.
    // - Handles payment form submission with debugging to track when it’s sent.
    const paymentForm = document.getElementById('paymentForm');
    // Event listener attachment: Uses addEventListener for 'submit' event
    // Method: Calls processPayment with the event object
    paymentForm.addEventListener('submit', (event) => {
        // Logs form submission for debugging
        // - Calls console.log(), a built-in JavaScript method, to write “Form submitted” to the developer tools.
        // - Helps developers confirm the form was submitted during troubleshooting.
        // - Tracks when the customer tries to process a payment.
        console.log('Form submitted');
        processPayment(event);
    });
});

// Function to show or hide payment fields
// - A user-defined JavaScript function named toggleFields, with no inputs.
// - Changes the form to show mobile or card payment fields based on the selected payment method.
// - Makes sure only the relevant payment fields are visible to the customer.
function toggleFields() {
    // Finds the mobile payment fields
    // - A user-defined constant named mobileFields, holding the result of document.getElementById() for the element with ID 'mobileFields'.
    // - Targets the section of the form with mobile payment details, like phone number input.
    // - Lets the code show or hide these fields based on selection.
    const mobileFields = document.getElementById('mobileFields');

    // Finds the card payment fields
    // - A user-defined constant named cardFields, holding the result of document.getElementById() for the element with ID 'cardFields'.
    // - Targets the section with card payment details, like card number input.
    // - Lets the code show or hide these fields based on selection.
    const cardFields = document.getElementById('cardFields');

    // Checks if mobile money is selected
    // - A user-defined constant named mobileSelected, holding the checked property, a built-in JavaScript feature, of the radio button with ID 'mobileMoney'.
    // - Stores true if mobile money is selected, false if not.
    // - Decides which payment fields to show or hide.
    const mobileSelected = document.getElementById('mobileMoney').checked;

    // Shows or hides mobile fields
    // - Uses classList.toggle(), a built-in JavaScript method, to add the 'hidden' CSS class to mobileFields if mobileSelected is false, or remove it if true.
    // - Shows mobile fields only when mobile money is selected.
    // - Keeps the form clean by hiding unused fields.
    mobileFields.classList.toggle('hidden', !mobileSelected);

    // Shows or hides card fields
    // - Uses classList.toggle() to add the 'hidden' class to cardFields if mobileSelected is true, or remove it if false.
    // - Shows card fields only when mobile money is not selected (i.e., card is selected).
    // - Ensures only relevant payment fields are visible to the customer.
    cardFields.classList.toggle('hidden', mobileSelected);
}

// Function to process a payment
// - A user-defined JavaScript function named processPayment, taking one input (event: a form submission event object).
// - Stops the form submission, collects payment data, sends it to the server, and redirects or shows errors based on the response.
// - Completes the payment and updates the customer’s history if successful.
function processPayment(event) {
    // Stops the page from refreshing
    // - Calls preventDefault(), a built-in JavaScript method of the event object, to stop the form from reloading the page.
    // - Keeps the customer on the payment page after submitting.
    // - Makes the payment process smooth without page reloads.
    event.preventDefault();

    // Gets the form data
    // - A user-defined constant named formData, holding a built-in JavaScript FormData object created from the form with ID 'paymentForm'.
    // - Collects all form fields, like bookingID, amountPaid, and payment method details.
    // - Prepares the payment data to send to the server.
    const formData = new FormData(document.getElementById('paymentForm'));

    // Logs form data for debugging
    // - Calls console.log() to write the form data as a plain object, created with Object.fromEntries(formData), to the developer tools.
    // - Shows the exact data being sent, like payment method and amount, for troubleshooting.
    // - Helps developers check what’s being submitted during payment.
    console.log('Sending form data:', Object.fromEntries(formData));

    // Sends a web request to process the payment
    // - Uses the built-in JavaScript fetch() method to send a POST request to a PHP file at '/php/processPayment.php' with formData.
    // - Starts a chain of steps to process the payment and handle the server’s reply.
    // - Sends all payment details to the server for processing.
    fetch('/php/processPayment.php', {
        // Sets the request type
        // - A method property set to 'POST', a built-in JavaScript fetch option that tells the server to expect data.
        // - Ensures the server knows this is a payment submission.
        method: 'POST',

        // Sends the form data
        // - A body property set to the formData object, containing all payment fields.
        // - Includes details like bookingID, amount, and method for the server.
        // - Provides the info needed to process the payment.
        body: formData
    })
        // Processes the server’s reply
        // - A built-in JavaScript .then() method that checks if the response is okay and converts it to a JSON object.
        // - Handles the server’s status and data to proceed or catch errors.
        .then(response => {
            // Logs response status for debugging
            // - Calls console.log() to write the response.status, a built-in HTTP status code, to the console.
            // - Helps track the server’s response, like 200 OK or 400 Bad Request.
            // - Aids in debugging issues with the payment request.
            console.log('Response status:', response.status);
            // Checks for server errors
            // - A conditional statement checking if response.ok is false (indicating an error like 404 or 500).
            // - Throws an error with the status code if not okay, stopping the process.
            // - Catches problems early when processing the payment.
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            // Gets the response data
            // - Returns response.json() to convert the reply into a JavaScript object.
            // - Prepares the data to check the payment status in the next step.
            // - Moves forward with the server’s reply.
            return response.json();
        })
        // Handles the payment result
        // - A .then() method that takes the data object and redirects or shows an error.
        // - Updates the page based on the server’s reply about the payment.
        .then(data => {
            // Logs response data for debugging
            // - Calls console.log() to write the entire data object to the developer tools.
            // - Shows the server’s reply, like status and message, for troubleshooting.
            // - Helps developers verify what the server sent back.
            console.log('Response data:', data);
            // Checks if the payment worked
            // - A conditional statement checking if data.status equals 'success'.
            // - Shows a success message and redirects if the payment worked, or an error if it didn’t.
            // - Decides the next step based on the server’s reply.
            if (data.status === 'success') {
                // Shows a success message
                // - Calls alert() with data.message (e.g., “Payment successful”).
                // - Tells the customer their payment was processed correctly.
                // - Confirms the payment in the bus booking system.
                alert(data.message);

                // Redirects to booking history
                // - Sets window.location.href, a built-in JavaScript property, to '/frontend/dashboard/customer/BookingHistory.html'.
                // - Sends the customer to their booking history page after a successful payment.
                // - Lets them see their updated booking records.
                window.location.href = '/frontend/dashboard/customer/BookingHistory.html';
            } else {
                // Shows an error message
                // - Calls alert() with “Payment failed:” plus data.message (e.g., “Invalid payment details”).
                // - Tells the customer why the payment didn’t work.
                // - Helps customers understand issues with their payment.
                alert('Payment failed: ' + data.message);
            }
        })
        // Handles errors during the process
        // - A .catch() method that logs any errors in the fetch chain.
        // - Logs the error and shows it to the customer to keep them informed.
        // - Keeps the form working even if processing fails.
        .catch(error => {
            // Logs an error for debugging
            // - Calls console.error() with the error object to write details to the developer tools.
            // - Includes “Error processing payment:” for clarity, like “Network error.”
            // - Helps developers find issues like bad internet during payment.
            console.error('Error processing payment:', error);
            // Shows an error message
            // - Calls alert() with “Error processing payment:” plus error.message.
            // - Tells the customer there was a problem, like “Server error.”
            // - Provides feedback to keep the customer informed during issues.
            alert('Error processing payment: ' + error.message);
        });
}