<?php
// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to query a specific payment record for the International Bus Booking System’s admin dashboard or customer interface.
include('../../../php/databaseconnection.php');

// Conditional statement: Validates the presence and non-emptiness of the Payment ID from the URL query string.
// Function calls: isset() is a PHP built-in function that checks if $_GET['id'], a superglobal array element from the URL, exists and is not null; empty() is a PHP function that checks if $_GET['id'] is empty (e.g., an empty string).
// Ensures a valid Payment ID is provided before attempting to query the payment record, preventing invalid requests from proceeding.
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'No Payment ID provided'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript of the missing Payment ID, allowing error handling (e.g., displaying an error message to the user).
    echo json_encode(['status' => 'error', 'message' => 'No Payment ID provided']);
    
    // Function call: Terminates script execution immediately.
    // String: exit() is a PHP built-in function that stops the script from running further.
    // Halts processing to prevent further actions after an invalid request.
    exit();
}

// Variable: Stores the Payment ID from the URL query string.
// String: $_GET['id'] is a superglobal array element containing the Payment ID passed via the URL (e.g., ?id=123), later validated as an integer through prepared statement binding.
// Captures the Payment ID to identify which payment record to retrieve from the database.
$paymentId = $_GET['id'];

// Variable: SQL query string to retrieve a specific payment record.
// String: Defines a SELECT query to fetch all columns (*) from the paymentdetails table WHERE PaymentID = ?, using a placeholder (?) for secure parameter binding.
// Retrieves the specific payment’s details, such as amount and transaction ID, for display on the admin dashboard or customer interface.
$sql = "SELECT * FROM paymentdetails WHERE PaymentID = ?";

// Variable: Stores the prepared statement for payment retrieval.
// Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with the placeholder, returning a statement object for binding and execution.
// Prepares the query to safely retrieve the payment record, reducing the risk of SQL injection.
$stmt = $conn->prepare($sql);

// Method call: Binds the Payment ID to the prepared statement.
// String: bind_param("i", $paymentId) is a MySQLi method that binds $paymentId as an integer ('i') to the placeholder in the query, sanitizing the input.
// Securely links the Payment ID to the query, preventing SQL injection by treating the input as data, not code.
$stmt->bind_param("i", $paymentId);

// Method call: Executes the payment retrieval query.
// String: execute() is a MySQLi method that runs the prepared statement, querying the payment record matching the Payment ID.
// Retrieves the payment record from the paymentdetails table for processing.
$stmt->execute();

// Variable: Stores the result set from the executed query.
// Object: $stmt->get_result() is a MySQLi method that retrieves the result set from the prepared statement, allowing row fetching.
// Holds the payment data for further processing into a JSON response.
$result = $stmt->get_result();

// Conditional statement: Checks if the query returned any payment records.
// Integer check: $result->num_rows is a MySQLi property that indicates the number of rows returned; greater than zero means a record was found.
// Processes the payment data only if a record exists, ensuring valid data or an error message is sent to the client.
if ($result->num_rows > 0) {
    // Variable: Stores the payment record as an associative array.
    // Array: $result->fetch_assoc() is a MySQLi method that retrieves the row as an associative array with keys corresponding to paymentdetails table columns (e.g., PaymentID, AmountPaid).
    // Extracts the single payment record for inclusion in the JSON response.
    $payment = $result->fetch_assoc();
    
    // Output statement: Sends the payment record as a JSON response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts the $payment array to a JSON string, a data format for web communication.
    // Delivers the payment record to the client for display, such as showing payment details in a transaction view or admin interface.
    echo json_encode($payment);
} else {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'Payment not found'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that no payment was found, allowing error handling (e.g., displaying an error message to the user).
    echo json_encode(['status' => 'error', 'message' => 'Payment not found']);
}

// Method call: Frees the payment statement resources.
// String: close() is a MySQLi method that releases the prepared statement ($stmt), freeing memory.
// Ensures efficient resource management after the payment retrieval is complete.
$stmt->close();

// Method call: Closes the database connection.
// String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open after the query, maintaining system efficiency and resource management.
$conn->close();
?>