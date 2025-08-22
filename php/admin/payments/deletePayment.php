<?php
// Function call: Sets the HTTP response header to specify JSON output.
// String: header() is a PHP built-in function that sets an HTTP header, here setting 'Content-Type: application/json', which informs the browser that the response is JSON data.
// Ensures the client’s JavaScript (e.g., on the admin dashboard or customer interface) can parse the response as JSON, maintaining compatibility with web standards in the International Bus Booking System.
header('Content-Type: application/json');

// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to perform deletion operations for payment records.
include('../../../php/databaseconnection.php');

// Conditional statement: Validates the presence and non-emptiness of the Payment ID from the URL query string.
// Function calls: isset() is a PHP built-in function that checks if $_GET['id'], a superglobal array element from the URL, exists and is not null; empty() is a PHP function that checks if $_GET['id'] is empty (e.g., an empty string).
// Ensures a valid Payment ID is provided before attempting deletions, preventing invalid requests from proceeding.
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'No Payment ID provided'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript of the missing Payment ID, allowing error handling (e.g., displaying an error message to the user).
    echo json_encode(['status' => 'error', 'message' => 'No Payment ID provided']);
    
    // Method call: Closes the database connection.
    // String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
    // Ensures no database connections remain open after an invalid request, maintaining system efficiency.
    $conn->close();
    
    // Function call: Terminates script execution immediately.
    // String: exit() is a PHP built-in function that stops the script from running further.
    // Halts processing to prevent further actions after an invalid request.
    exit();
}

// Variable: Stores the Payment ID from the URL query string.
// String: $_GET['id'] is a superglobal array element containing the Payment ID passed via the URL (e.g., ?id=123), later validated as an integer through prepared statement binding.
// Captures the Payment ID to identify which payment record to delete from the database.
$paymentId = $_GET['id'];

// Variable: SQL query string to delete the payment record.
// String: Defines a query to DELETE FROM paymentdetails WHERE PaymentID = ?, using a placeholder (?) for secure parameter binding.
// Specifies the deletion of the payment record identified by the Payment ID, completing the removal process.
$sql = "DELETE FROM paymentdetails WHERE PaymentID = ?";

// Variable: Stores the prepared statement for payment deletion.
// Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with the placeholder, returning a statement object for binding and execution.
// Prepares the query to safely delete the payment record, reducing the risk of SQL injection.
$stmt = $conn->prepare($sql);

// Conditional statement: Checks if the payment deletion query preparation was successful.
// Boolean check: Tests if $stmt is false, indicating a preparation failure (e.g., syntax error or database issue).
// Throws an exception to trigger error handling if preparation fails, ensuring no changes occur.
if (!$stmt) {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'Failed to prepare query'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript of the preparation failure, allowing error handling (e.g., displaying an error message to the user).
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare query']);
    
    // Method call: Closes the database connection.
    // String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
    // Ensures no database connections remain open after a failed preparation, maintaining system efficiency.
    $conn->close();
    
    // Function call: Terminates script execution immediately.
    // String: exit() is a PHP built-in function that stops the script from running further.
    // Halts processing to prevent further actions after a failed preparation.
    exit();
}

// Method call: Binds the Payment ID to the prepared statement.
// String: bind_param("i", $paymentId) is a MySQLi method that binds $paymentId as an integer ('i') to the placeholder in the query, sanitizing the input.
// Securely links the Payment ID to the query, preventing SQL injection by treating the input as data, not code.
$stmt->bind_param("i", $paymentId);

// Method call: Executes the payment deletion query.
// String: execute() is a MySQLi method that runs the prepared statement, deleting the payment record matching the Payment ID.
// Removes the payment record from the paymentdetails table.
$stmt->execute();

// Conditional statement: Checks if the deletion affected any rows to determine success.
// Property access: affected_rows is a MySQLi property that returns the number of rows modified by the executed query, used to verify if the payment record was found and deleted.
// Determines whether the deletion was successful to provide appropriate feedback to the client.
if ($stmt->affected_rows > 0) {
    // Output statement: Sends a JSON success response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'success', 'message' => 'Payment deleted successfully'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that the deletion was successful, allowing the dashboard or interface to update (e.g., remove the payment from the display).
    echo json_encode(['status' => 'success', 'message' => 'Payment deleted successfully']);
} else {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'Payment not found'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript of the deletion failure, allowing error handling (e.g., displaying an error message to the user).
    echo json_encode(['status' => 'error', 'message' => 'Payment not found']);
}

// Method call: Frees the payment statement resources.
// String: close() is a MySQLi method that releases the prepared statement ($stmt), freeing memory.
// Ensures efficient resource management after the payment deletion is complete.
$stmt->close();

// Method call: Closes the database connection.
// String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open after operations, maintaining system efficiency and resource management.
$conn->close();
?>