<?php
// Function call: Sets the HTTP response header to specify JSON output.
// String: header() is a PHP built-in function that sets an HTTP header, here setting 'Content-Type: application/json', which informs the browser that the response is JSON data.
// Ensures the client’s JavaScript (e.g., on the admin dashboard) can parse the response as JSON, maintaining compatibility with web standards in the International Bus Booking System.
header('Content-Type: application/json');

// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to update payment details in the paymentdetails table for the International Bus Booking System.
include('../../../php/databaseconnection.php');

// Conditional statement: Checks if the HTTP request method is POST to validate form submission.
// String comparison: $_SERVER['REQUEST_METHOD'] is a superglobal variable that contains the request method; checks if it does not equal "POST".
// Ensures the update request is valid and initiated from a POST request, preventing unauthorized or incorrect requests.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'Invalid request method'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that the request method is invalid, allowing error handling (e.g., displaying an error message to the user).
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    
    // Function call: Terminates script execution immediately.
    // String: exit() is a PHP built-in function that stops the script from running further.
    // Halts processing to prevent further actions after an invalid request.
    exit();
}

// Variable: Retrieves the raw JSON data from the HTTP POST request body.
// String: file_get_contents('php://input') is a PHP function that reads the raw POST data (JSON string) sent by the client.
// Captures the raw JSON input for further decoding, compatible with client-side scripts like edit_payment.js.
$jsonInput = file_get_contents('php://input');

// Variable: Decodes the JSON data into a PHP associative array.
// Array: json_decode($jsonInput, true) is a PHP function that converts the JSON string into a PHP associative array, with true ensuring an array (not an object) is returned.
// Converts the raw JSON input into a usable format for extracting payment details.
$inputData = json_decode($jsonInput, true);

// Variable: Stores the Payment ID from the JSON input.
// String or integer: $inputData['paymentId'] is an associative array element containing the unique identifier of the payment to update, later bound as an integer in the prepared statement.
// Identifies which payment record in the paymentdetails table to modify.
$paymentId = $inputData['paymentId'];

// Variable: Stores the Booking ID from the JSON input.
// String or integer: $inputData['bookingID'] is an associative array element containing the identifier of the associated booking, bound as an integer in the prepared statement.
// Updates the booking linked to the payment, ensuring accurate transaction association.
$bookingId = $inputData['bookingID'];

// Variable: Stores the amount paid from the JSON input.
// String or double: $inputData['amountPaid'] is an associative array element containing the amount paid for the transaction, bound as a double in the prepared statement.
// Updates the payment amount, ensuring accurate financial records.
$amountPaid = $inputData['amountPaid'];

// Variable: Stores the payment mode from the JSON input.
// String: $inputData['paymentMode'] is an associative array element containing the method of payment (e.g., 'Credit Card', 'Cash'), bound as a string in the prepared statement.
// Updates the payment method, ensuring accurate transaction details.
$paymentMode = $inputData['paymentMode'];

// Variable: Stores the payment date from the JSON input.
// String: $inputData['paymentDate'] is an associative array element containing the date of the payment (typically in YYYY-MM-DD HH:MM:SS format), bound as a string in the prepared statement.
// Updates the payment date, maintaining accurate transaction timing.
$paymentDate = $inputData['paymentDate'];

// Variable: Stores the receipt number from the JSON input.
// String: $inputData['receiptNumber'] is an associative array element containing the unique receipt number for the payment, bound as a string in the prepared statement.
// Updates the receipt number, ensuring accurate transaction identification.
$receiptNumber = $inputData['receiptNumber'];

// Variable: Stores the transaction ID from the JSON input.
// String: $inputData['transactionID'] is an associative array element containing the unique transaction identifier, bound as a string in the prepared statement.
// Updates the transaction identifier, ensuring accurate transaction tracking.
$transactionId = $inputData['transactionID'];

// Variable: Stores the payment status from the JSON input.
// String: $inputData['status'] is an associative array element containing the payment status (e.g., 'Completed', 'Pending'), bound as a string in the prepared statement.
// Updates the payment status, allowing management of transaction states.
$status = $inputData['status'];

// Variable: SQL query string to update the payment record.
// String: Defines an UPDATE query for the paymentdetails table, setting BookingID, AmountPaid, PaymentMode, PaymentDate, ReceiptNumber, TransactionID, and Status to placeholders (?), with a WHERE clause targeting PaymentID = ?.
// Specifies the fields to update for the payment identified by Payment ID, using placeholders for secure data binding.
$sql = "UPDATE paymentdetails SET 
        BookingID = ?, AmountPaid = ?, PaymentMode = ?, PaymentDate = ?, 
        ReceiptNumber = ?, TransactionID = ?, Status = ? 
        WHERE PaymentID = ?";

// Variable: Stores the prepared statement for the update query.
// Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with placeholders, returning a statement object for binding and execution.
// Prepares the query to safely update the payment record, reducing the risk of SQL injection.
$stmt = $conn->prepare($sql);

// Conditional statement: Checks if the prepared statement was successfully created.
// Boolean check: Tests if $stmt is false, indicating a preparation failure (e.g., syntax error or database issue).
// Ensures the query is valid before proceeding, preventing execution of a faulty statement.
if (!$stmt) {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'Failed to prepare query'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript of the preparation failure, allowing error handling (e.g., displaying a system error message).
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare query']);
    
    // Function call: Terminates script execution immediately.
    // String: exit() is a PHP built-in function that stops the script from running further.
    // Halts processing to prevent further actions after a preparation failure.
    exit();
}

// Method call: Binds input variables to the prepared statement’s placeholders.
// String: bind_param("idsssssi", ...) is a MySQLi method that binds variables to the placeholders in order: 'i' (integer) for $bookingId; 'd' (double) for $amountPaid; 's' (string) for $paymentMode, $paymentDate, $receiptNumber, $transactionId, and $status; 'i' (integer) for $paymentId.
// Securely links the input data to the query, preventing SQL injection by treating inputs as data, not code.
$stmt->bind_param("idsssssi", $bookingId, $amountPaid, $paymentMode, $paymentDate, 
$receiptNumber, $transactionId, $status, $paymentId);

// Method call: Executes the payment update query.
// String: execute() is a MySQLi method that runs the prepared statement, updating the payment record matching the Payment ID.
// Modifies the payment record in the paymentdetails table with the new details.
$stmt->execute();

// Conditional statement: Checks if the update affected any payment records.
// Integer check: $stmt->affected_rows is a MySQLi property that indicates the number of rows modified; greater than zero means the update was successful.
// Verifies that the payment record was updated before sending the success response.
if ($stmt->affected_rows > 0) {
    // Output statement: Sends a JSON success response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'success', 'message' => 'Payment updated successfully'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that the payment update was successful, allowing the admin dashboard to refresh (e.g., show updated payment details).
    echo json_encode(['status' => 'success', 'message' => 'Payment updated successfully']);
} else {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'No changes made or payment not found'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that no payment record was updated or found, enabling error handling (e.g., displaying an error message to the user).
    echo json_encode(['status' => 'error', 'message' => 'No changes made or payment not found']);
}

// Method call: Frees the prepared statement resources.
// String: close() is a MySQLi method that releases the prepared statement ($stmt), freeing memory.
// Ensures efficient resource management after the update operation is complete.
$stmt->close();

// Method call: Closes the database connection.
// String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open after operations, maintaining system efficiency and resource management.
$conn->close();
?>