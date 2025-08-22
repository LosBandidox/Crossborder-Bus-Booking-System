<?php
// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to query payment records for the International Bus Booking System’s admin dashboard or customer interface.
include('../../../php/databaseconnection.php');

// Variable: SQL query string to retrieve all payment records.
// String: Defines a SELECT query to fetch PaymentID, BookingID, AmountPaid, PaymentMode, PaymentDate, ReceiptNumber, TransactionID, and Status from the paymentdetails table, selecting all records without filtering.
// Retrieves all payment details to display comprehensive payment information, such as amounts and transaction IDs, on the dashboard.
$sql = "SELECT PaymentID, BookingID, AmountPaid, PaymentMode, PaymentDate, ReceiptNumber, TransactionID, Status 
        FROM paymentdetails";

// Variable: Stores the result of the payment query.
// Object: $conn->query($sql) is a MySQLi method that executes the SQL query directly and returns a result object containing the retrieved records or false if the query fails.
// Holds the payment data for processing into a JSON response for the client.
$result = $conn->query($sql);

// Variable: Initializes an array to store payment records.
// Array: Creates an empty array to hold associative arrays, each representing a payment with fields like PaymentID, AmountPaid, and Status.
// Prepares to collect payment details for inclusion in the JSON response.
$payments = [];

// Conditional statement: Checks if the query returned any payment records.
// Integer check: $result->num_rows is a MySQLi property that indicates the number of rows returned; greater than zero means records were found.
// Processes payment data only if records exist, ensuring valid data is sent to the client.
if ($result->num_rows > 0) {
    // Loop: Iterates over the query results to collect payment records.
    // Array: $result->fetch_assoc() is a MySQLi method that retrieves each row as an associative array with keys like PaymentID and PaymentDate, repeated using a while loop until no rows remain.
    // Processes each payment record to include in the JSON response for the dashboard.
    while ($row = $result->fetch_assoc()) {
        // Array operation: Appends a payment record to the array.
        // Array: Adds $row to $payments, containing payment details like BookingID and TransactionID.
        // Collects payment data for display on the admin dashboard or customer interface.
        $payments[] = $row;
    }
}

// Function call: Sets the HTTP response header to specify JSON output.
// String: header() is a PHP built-in function that sets an HTTP header, here setting 'Content-Type: application/json', which informs the browser that the response is JSON data.
// Ensures the client’s JavaScript can parse the payment records as JSON, maintaining compatibility with web standards for the dashboard.
header('Content-Type: application/json');

// Output statement: Sends the payment records as a JSON response to the client.
// String: echo outputs text; json_encode() is a PHP built-in function that converts the $payments array to a JSON string, a data format for web communication.
// Delivers all payment records to the client for display, such as listing payments in a table or transaction history interface.
echo json_encode($payments);

// Method call: Closes the database connection.
// String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open after the query, maintaining system efficiency and resource management.
$conn->close();
?>