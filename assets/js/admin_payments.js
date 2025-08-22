// File: admin_payments.js
// Purpose: Gets, shows, and deletes payment records for the admin_payments.html page in the International Bus Booking System.
// Helps admins view, edit, or remove payment details in a table that can be searched, making payment management easy.

// Function to get payment data and show it in a table
// - A user-defined async JavaScript function named fetchPayments, with no inputs.
// - Waits for data from the server using a web request and updates the webpage to show payments in a table.
// - Pulls payment details from a PHP file and fills a table with info like PaymentID and AmountPaid.
// - Clears old table rows before adding new ones to keep the payment list up-to-date on the admin dashboard.
async function fetchPayments() {
    // Block to catch errors
    // - A try-catch structure that runs the main code in the try part and handles problems like bad internet in the catch part.
    // - Keeps the webpage working even if something goes wrong while getting payment data.
    // - Makes sure admins can still use the payment page if there’s a glitch.
    try {
        // Sends a web request to get payment data
        // - A user-defined constant named response, holding the result of a built-in JavaScript fetch() method that asks the server for data.
        // - Points to a PHP file at '../../../php/admin/payments/fetchPayments.php' using a path from the current file.
        // - Saves the server’s reply, which has payment details in a JSON format (like a list of payment info).
        // - Talks to the server to grab all payment records for the admin table.
        const response = await fetch('../../../php/admin/payments/fetchPayments.php');

        // Turns the server’s reply into a list of payments
        // - A user-defined constant named payments, holding the result of a built-in JavaScript response.json() method that changes the server’s data into a JavaScript list.
        // - Contains a list of payment objects, each with details like PaymentID, BookingID, AmountPaid, PaymentMode, PaymentDate, ReceiptNumber, TransactionID, and Status.
        // - Gets the payment data ready to show in the table on admin_payments.html.
        const payments = await response.json();

        // Finds the table section on the webpage
        // - A user-defined constant named tableBody, holding the result of a built-in JavaScript document.getElementById() method that finds an HTML element by its ID.
        // - Targets the <tbody> part of the table with ID 'paymentTableBody' in admin_payments.html, where payment rows will go.
        // - Lets the code change the table to show payment details.
        const tableBody = document.getElementById('paymentTableBody');

        // Clears out old table rows
        // - Sets the tableBody’s innerHTML property, a built-in JavaScript feature that changes an element’s HTML content, to an empty string.
        // - Removes any old <tr> rows to avoid showing duplicate or outdated payments.
        // - Makes the table ready for fresh payment data in the admin view.
        tableBody.innerHTML = ''; // Clear existing rows

        // Goes through each payment to make a table row
        // - A forEach loop, a built-in JavaScript method that runs code for each payment in the payments list.
        // - Creates a row for each payment with its details and buttons for actions like editing or deleting.
        // - Builds the table based on how many payments are in the list for the admin dashboard.
        payments.forEach(payment => {
            // Makes a new table row
            // - A user-defined constant named row, holding the result of a built-in JavaScript document.createElement() method that creates a <tr> HTML element.
            // - Creates a table row to hold one payment’s information.
            // - Acts as a container for table cells showing payment details.
            const row = document.createElement('tr');

            // Fills the row with payment info and buttons
            // - Sets the row’s innerHTML using a template literal, a JavaScript way to mix text and data inside backticks.
            // - Adds table cells (<td>) with payment details like payment.PaymentID and payment.AmountPaid, plus Edit and Delete buttons.
            // - Shows payment info and adds buttons for admins to edit or delete payments in the table.
            row.innerHTML = `
                <td>${payment.PaymentID}</td>
                <td>${payment.BookingID}</td>
                <td>${payment.AmountPaid}</td>
                <td>${payment.PaymentMode}</td>
                <td>${payment.PaymentDate}</td>
                <td>${payment.ReceiptNumber}</td>
                <td>${payment.TransactionID}</td>
                <td>${payment.Status}</td>
                <td>
                    <a href="edit_payment.html?id=${payment.PaymentID}" class="btn">Edit</a>
                    <button onclick="deletePayment(${payment.PaymentID})">Delete</button>
                </td>
            `;

            // Adds the row to the table
            // - Calls appendChild(), a built-in JavaScript method of tableBody, to add the row to the end of the table’s rows.
            // - Puts the new <tr> into the <tbody>, showing it on the webpage.
            // - Updates the table in admin_payments.html with the payment row.
            tableBody.appendChild(row);
        });
    } catch (error) {
        // Shows error details in the console
        // - Calls console.error(), a built-in JavaScript method that writes an error message to the browser’s developer tools.
        // - Includes the text 'Error fetching payments:' and the error details to help find problems.
        // - Helps developers fix issues like bad internet or wrong data when loading payments.
        console.error('Error fetching payments:', error);
    }
}

// Function to delete a payment and update the table
// - A user-defined async JavaScript function named deletePayment, with one input (paymentId: a number for the payment’s unique ID).
// - Sends a web request to remove a payment from the server and refreshes the table by calling fetchPayments().
// - Asks the user to confirm before deleting to avoid mistakes in the bus booking system.
async function deletePayment(paymentId) {
    // Shows a pop-up to confirm deletion
    // - A conditional statement using the built-in JavaScript confirm() function, which shows a pop-up with a message and OK/Cancel buttons.
    // - Displays a question like “Are you sure you want to delete payment with ID: 123?” using the paymentId.
    // - Only deletes if the user clicks OK (returns true), making sure the admin wants to delete the payment.
    if (confirm(`Are you sure you want to delete payment with ID: ${paymentId}?`)) {
        // Block to catch errors
        // - A try-catch structure that runs the deletion code in the try part and handles problems like bad internet in the catch part.
        // - Keeps the webpage working even if deleting the payment fails.
        // - Ensures admins can try again if there’s an issue with deletion.
        try {
            // Sends a web request to delete the payment
            // - A user-defined constant named response, holding the result of a built-in JavaScript fetch() method that asks the server to delete the payment.
            // - Points to a PHP file at '../../../php/admin/payments/deletePayment.php' with the paymentId added (e.g., ?id=123).
            // - Saves the server’s reply, which has a JSON object with a status and message about the deletion.
            // - Tells the server to remove the payment with the given ID.
            const response = await fetch(`../../../php/admin/payments/deletePayment.php?id=${paymentId}`);

            // Turns the server’s reply into a JavaScript object
            // - A user-defined constant named result, holding the result of a built-in JavaScript response.json() method that changes the server’s data into a JavaScript object.
            // - Contains a status (like 'success') and a message (like “Payment deleted”) to show if the deletion worked.
            // - Gets the deletion result ready to check in the admin interface.
            const result = await response.json();

            // Checks if the deletion worked
            // - A conditional statement checking if result.status equals 'success'.
            // - Shows a success message and refreshes the table if deletion worked, or shows an error if it didn’t.
            // - Gives feedback to the admin based on what the server says about the deletion.
            if (result.status === 'success') {
                // Shows a pop-up for success
                // - Calls alert(), a built-in JavaScript method that shows a pop-up message.
                // - Displays “Payment deleted successfully” to tell the admin the payment is gone.
                // - Confirms the payment was removed from the system.
                alert('Payment deleted successfully');

                // Refreshes the payment table
                // - Calls the user-defined fetchPayments() function to reload payment data from the server.
                // - Updates the table in admin_payments.html to remove the deleted payment.
                // - Keeps the table showing the latest payment list.
                fetchPayments(); // Refresh the payment list
            } else {
                // Shows a pop-up for failure
                // - Calls alert(), a built-in JavaScript method, showing “Failed to delete payment:” plus result.message (e.g., “Payment linked to active booking”).
                // - Tells the admin why the deletion didn’t work, using the server’s message.
                // - Helps admins understand issues in the bus booking system.
                alert('Failed to delete payment: ' + result.message);
            }
        } catch (error) {
            // Shows error details in the console
            // - Calls console.error(), a built-in JavaScript method that writes an error message to the developer tools.
            // - Includes the text 'Error deleting payment:' and the error details to help fix problems.
            // - Assists developers in finding issues like bad internet during deletion.
            console.error('Error deleting payment:', error);
        }
    }
}

// Sets up the table and search when the page loads
// - Adds an event listener to the document for the 'DOMContentLoaded' event, a built-in JavaScript event that runs when the webpage is fully loaded.
// - Runs a callback function to make sure the page is ready, starting fetchPayments() and adding a search feature.
// - Gets the admin_payments.html page ready to show payments and let admins search them right away.
document.addEventListener('DOMContentLoaded', () => {
    // Gets payment data to fill the table
    // - Calls the user-defined fetchPayments() function to load payments from the server.
    // - Fills the table in admin_payments.html with all payments.
    // - Shows payment data as soon as the admin opens the page.
    fetchPayments();

    // Adds a search feature for the payment table
    // - Adds an event listener to the element with ID 'paymentSearch' using addEventListener(), a built-in JavaScript method that watches for events.
    // - Listens for the 'input' event, which happens every time the admin types in the search box.
    // - Runs code to filter the table based on what the admin types in the search box.
    document.getElementById('paymentSearch').addEventListener('input', () => {
        // Filters table rows based on search text
        // - Calls the user-defined filterTable() function, passing the table ID ('paymentTablee'), search box ID ('paymentSearch'), message ID ('noPayments'), and column numbers ([0, 1, 5, 6]).
        // - Hides table rows that don’t match the search text in columns for PaymentID, BookingID, ReceiptNumber, or TransactionID.
        // - Makes it easy for admins to find specific payments in the table.
        filterTable('paymentTablee', 'paymentSearch', 'noPayments', [0, 1, 5, 6]);
    });
});