<?php
// Function call: Sets the HTTP response header to specify JSON output.
// String: header() is a PHP built-in function that sets an HTTP header, here setting 'Content-Type: application/json', which informs the browser that the response is JSON data.
// Ensures the client’s JavaScript (e.g., on the admin dashboard or customer interface) can parse the response as JSON, maintaining compatibility with web standards in the International Bus Booking System.
header('Content-Type: application/json');

// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to perform deletion operations for booking and payment records.
include('../../php/databaseconnection.php');

// Conditional statement: Validates the presence and non-emptiness of the Booking ID from the URL query string.
// Function calls: isset() is a PHP built-in function that checks if $_GET['id'], a superglobal array element from the URL, exists and is not null; empty() is a PHP function that checks if $_GET['id'] is empty (e.g., an empty string).
// Ensures a valid Booking ID is provided before attempting deletions, preventing invalid requests from proceeding.
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Output statement: Sends a JSON error response to the client.
// String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'No Booking ID provided'] to a JSON string, a data format for web communication.
// Informs the client’s JavaScript of the missing Booking ID, allowing error handling (e.g., displaying an error message to the user).
    echo json_encode(['status' => 'error', 'message' => 'No Booking ID provided']);
    
    // Method call: Closes the database connection.
// String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open after an invalid request, maintaining system efficiency.
    $conn->close();
    
    // Function call: Terminates script execution immediately.
// String: exit() is a PHP built-in function that stops the script from running further.
// Halts processing to prevent further actions after an invalid request.
    exit();
}

// Variable: Stores the Booking ID from the URL query string.
// String: $_GET['id'] is a superglobal array element containing the Booking ID passed via the URL (e.g., ?id=123), later validated as an integer through prepared statement binding.
// Captures the Booking ID to identify which booking and payment records to delete from the database.
$bookingId = $_GET['id'];

// Try-catch block: Handles errors during the deletion process with a transaction.
// Structure: try contains code to perform deletions within a transaction; catch captures any Exception object (stored in $e) thrown due to errors like query failures or invalid IDs.
// Ensures database operations are atomic (all succeed or none are applied) and provides error handling with a clean JSON response.
try {
    // Method call: Starts a database transaction.
// String: begin_transaction() is a MySQLi method that initiates a transaction, grouping subsequent database operations so they can be committed or rolled back together.
// Guarantees data consistency by ensuring both booking and payment deletions succeed or fail as a unit, preventing partial deletions.
    $conn->begin_transaction();

    // Variable: SQL query string to delete payment records.
// String: Defines a query to DELETE FROM paymentdetails WHERE BookingID = ?, using a placeholder (?) for secure parameter binding.
// Specifies the deletion of payment records associated with the given Booking ID, ensuring related data is removed.
    $pay_sql = "DELETE FROM paymentdetails WHERE BookingID = ?";
    
    // Variable: Stores the prepared statement for payment deletion.
// Object: $conn->prepare($pay_sql) is a MySQLi method that compiles the SQL query with the placeholder, returning a statement object for binding and execution.
// Prepares the query to safely delete payment records, reducing the risk of SQL injection.
    $pay_stmt = $conn->prepare($pay_sql);
    
    // Conditional statement: Checks if the payment deletion query preparation was successful.
// Boolean check: Tests if $pay_stmt is false, indicating a preparation failure (e.g., syntax error or database issue).
// Throws an exception to trigger rollback if preparation fails, ensuring no partial changes occur.
    if (!$pay_stmt) throw new Exception('Failed to prepare payment delete query');
    
    // Method call: Binds the Booking ID to the prepared statement.
// String: bind_param("i", $bookingId) is a MySQLi method that binds $bookingId as an integer ('i') to the placeholder in the query, sanitizing the input.
// Securely links the Booking ID to the query, preventing SQL injection by treating the input as data, not code.
    $pay_stmt->bind_param("i", $bookingId);
    
    // Method call: Executes the payment deletion query.
// String: execute() is a MySQLi method that runs the prepared statement, deleting payment records matching the Booking ID.
// Removes associated payment records from the paymentdetails table as part of the transaction.
    $pay_stmt->execute();
    
    // Method call: Frees the payment statement resources.
// String: close() is a MySQLi method that releases the prepared statement ($pay_stmt), freeing memory.
// Ensures efficient resource management after the payment deletion is complete.
    $pay_stmt->close();

    // Variable: SQL query string to delete the booking record.
// String: Defines a query to DELETE FROM bookingdetails WHERE BookingID = ?, using a placeholder for secure binding.
// Specifies the deletion of the booking record identified by the Booking ID, completing the removal process.
    $sql = "DELETE FROM bookingdetails WHERE BookingID = ?";
    
    // Variable: Stores the prepared statement for booking deletion.
// Object: $conn->prepare($sql) compiles the SQL query, returning a statement object for binding and execution.
// Prepares the query to safely delete the booking record, maintaining security against SQL injection.
    $stmt = $conn->prepare($sql);
    
    // Conditional statement: Checks if the booking deletion query preparation was successful.
// Boolean check: Tests if $stmt is false, indicating a preparation failure.
// Throws an exception to trigger rollback if preparation fails, ensuring data consistency.
    if (!$stmt) throw new Exception('Failed to prepare booking delete query');
    
    // Method call: Binds the Booking ID to the booking deletion statement.
// String: bind_param("i", $bookingId) binds $bookingId as an integer to the placeholder in the query.
// Securely links the Booking ID to the booking deletion query, ensuring safe execution.
    $stmt->bind_param("i", $bookingId);
    
    // Method call: Executes the booking deletion query.
// String: execute() runs the prepared statement, deleting the booking record matching the Booking ID.
// Removes the booking record from the bookingdetails table as part of the transaction.
    $stmt->execute();
    
    // Method call: Frees the booking statement resources.
// String: close() releases the prepared statement ($stmt), freeing memory.
// Ensures efficient resource management after the booking deletion is complete.
    $stmt->close();

    // Method call: Commits the database transaction.
// String: commit() is a MySQLi method that finalizes all changes made during the transaction, permanently applying the deletions to the database.
// Confirms both payment and booking deletions, ensuring data consistency in the database.
    $conn->commit();

    // Output statement: Sends a JSON success response to the client.
// String: echo outputs text; json_encode() converts an array ['status' => 'success', 'message' => 'Booking and payments deleted successfully'] to a JSON string.
// Informs the client’s JavaScript that the deletion was successful, allowing the dashboard or interface to update (e.g., remove the booking from the display).
    echo json_encode(['status' => 'success', 'message' => 'Booking and payments deleted successfully']);
    
} catch (Exception $e) {
    // Method call: Rolls back the database transaction.
// String: rollback() is a MySQLi method that undoes all changes made during the current transaction, reverting the database to its state before the transaction began.
// Prevents partial deletions (e.g., payments deleted but booking not deleted) if an error occurs, maintaining data integrity.
    $conn->rollback();
    
    // Output statement: Sends a JSON error response to the client.
// String: echo outputs text; json_encode() converts an array ['status' => 'error', 'message' => 'Failed to delete booking: ' . $e->getMessage()] to a JSON string, where getMessage() retrieves the exception’s error message (e.g., preparation failure).
// Informs the client of the deletion failure with specific error details, enabling error handling or debugging.
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete booking: ' . $e->getMessage()]);
}

// Method call: Closes the database connection.
// String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open after operations, maintaining system efficiency and resource management.
$conn->close();

?>
