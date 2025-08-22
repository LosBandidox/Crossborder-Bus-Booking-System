// File: paymentHistory.js
// Purpose: Fetches and displays payment records on the customer payment history page in the International Bus Booking System.
// Shows payment details, like ID, amount, and status, in a table for the logged-in customer.

// Sets up the page to show payments when it loads
// - Adds an event listener to the document for the 'DOMContentLoaded' event, a built-in JavaScript event that runs when the webpage is fully loaded.
// - Runs a callback function to fetch and display payment data in a table.
// - Gets the payment history page ready to show customer payments as soon as it opens.
document.addEventListener('DOMContentLoaded', () => {
    // Sends a web request to get payment data
    // - Uses the built-in JavaScript fetch() method to request data from a PHP file at '/php/fetchPayments.php'.
    // - Starts a chain of steps to process the server’s reply and show payments in a table.
    // - Fetches all payment records for the logged-in customer to display on the page.
    fetch('/php/fetchPayments.php')
        // Processes the server’s reply
        // - A built-in JavaScript .then() method that converts the response to a JSON object using response.json().
        // - Turns the server’s reply into a usable object with payment data.
        // - Prepares the payment details to fill the table.
        .then(response => response.json())
        // Handles the payment data
        // - A .then() method that takes the data object and updates the table or shows an error.
        // - Fills the payment table if successful, or alerts the customer if there’s an issue.
        // - Manages the display of payment records on the history page.
        .then(data => {
            // Checks if the fetch was successful
            // - A conditional statement checking if data.status equals 'success'.
            // - Shows the payment table if the fetch worked, or an error message if it didn’t.
            // - Decides what to display based on the server’s reply for the payment history.
            if (data.status === 'success') {
                // Finds the payment table section
                // - A user-defined constant named paymentBody, holding the result of a built-in JavaScript document.querySelector() method that finds the <tbody> element inside the table with ID 'paymentTable'.
                // - Targets the area where payment rows will be added.
                // - Lets the code update the table with customer payment data.
                const paymentBody = document.querySelector('#paymentTable tbody');

                // Goes through each payment to make a table row
                // - A forEach loop, a built-in JavaScript method that runs code for each payment in the data.payments list.
                // - Creates a row for each payment with details like PaymentID and AmountPaid.
                // - Builds the table dynamically for the customer’s payment history.
                data.payments.forEach(payment => {
                    // Builds a table row’s content
                    // - A user-defined constant named row, holding a template literal (text with data inside backticks) with payment details.
                    // - Creates a <tr> row with table cells for PaymentID, BookingID, AmountPaid, PaymentMode, PaymentDate, ReceiptNumber, and Status.
                    // - Shows payment info in a clear format for the customer.
                    const row = `
                        <tr>
                            <td>${payment.PaymentID}</td>
                            <td>${payment.BookingID}</td>
                            <td>${payment.AmountPaid}</td>
                            <td>${payment.PaymentMode}</td>
                            <td>${payment.PaymentDate}</td>
                            <td>${payment.ReceiptNumber}</td>
                            <td>${payment.Status}</td>
                        </tr>
                    `;

                    // Adds the row to the table
                    // - Updates paymentBody.innerHTML, a built-in JavaScript property, by adding the row to the existing content.
                    // - Places the payment row in the table on the webpage.
                    // - Shows the payment details in the customer’s payment history table.
                    paymentBody.innerHTML += row;
                });
            } else {
                // Shows an error message
                // - Calls alert(), a built-in JavaScript method, with data.message (e.g., “No payments found”).
                // - Tells the customer why their payments couldn’t load.
                // - Keeps the page usable even if there’s a problem fetching data.
                alert(data.message);
            }
        })
        // Handles errors during the fetch
        // - A built-in JavaScript .catch() method that logs any errors in the fetch chain.
        // - Writes error details to the developer tools for troubleshooting.
        // - Ensures the page doesn’t break if fetching fails.
        .catch(error => console.error('Error fetching payments:', error));
});