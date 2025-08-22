<?php
// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to query a specific customer record for the International Bus Booking System’s admin dashboard or customer interface.
include('../../../php/databaseconnection.php');

// Conditional statement: Validates the presence and non-emptiness of the Customer ID from the URL query string.
// Function calls: isset() is a PHP built-in function that checks if $_GET['id'], a superglobal array element from the URL, exists and is not null; empty() is a PHP function that checks if $_GET['id'] is empty (e.g., an empty string).
// Ensures a valid Customer ID is provided before attempting to query the customer record, preventing invalid requests from proceeding.
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'No Customer ID provided'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript of the missing Customer ID, allowing error handling (e.g., displaying an error message to the user).
    echo json_encode(['status' => 'error', 'message' => 'No Customer ID provided']);
    
    // Function call: Terminates script execution immediately.
    // String: exit() is a PHP built-in function that stops the script from running further.
    // Halts processing to prevent further actions after an invalid request.
    exit();
}

// Variable: Stores the Customer ID from the URL query string.
// String: $_GET['id'] is a superglobal array element containing the Customer ID passed via the URL (e.g., ?id=123), later validated as an integer through prepared statement binding.
// Captures the Customer ID to identify which customer record to retrieve from the database.
$customerId = $_GET['id'];

// Variable: SQL query string to retrieve a specific customer record.
// String: Defines a SELECT query to fetch all columns (*) from the customer table WHERE CustomerID = ?, using a placeholder (?) for secure parameter binding.
// Retrieves the specific customer’s details, such as name and email, for display on the admin dashboard or customer interface.
$sql = "SELECT * FROM customer WHERE CustomerID = ?";

// Variable: Stores the prepared statement for customer retrieval.
// Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with the placeholder, returning a statement object for binding and execution.
// Prepares the query to safely retrieve the customer record, reducing the risk of SQL injection.
$stmt = $conn->prepare($sql);

// Method call: Binds the Customer ID to the prepared statement.
// String: bind_param("i", $customerId) is a MySQLi method that binds $customerId as an integer ('i') to the placeholder in the query, sanitizing the input.
// Securely links the Customer ID to the query, preventing SQL injection by treating the input as data, not code.
$stmt->bind_param("i", $customerId);

// Method call: Executes the customer retrieval query.
// String: execute() is a MySQLi method that runs the prepared statement, querying the customer record matching the Customer ID.
// Retrieves the customer record from the customer table for processing.
$stmt->execute();

// Variable: Stores the result set from the executed query.
// Object: $stmt->get_result() is a MySQLi method that retrieves the result set from the prepared statement, allowing row fetching.
// Holds the customer data for further processing into a JSON response.
$result = $stmt->get_result();

// Conditional statement: Checks if the query returned any customer records.
// Integer check: $result->num_rows is a MySQLi property that indicates the number of rows returned; greater than zero means a record was found.
// Processes the customer data only if a record exists, ensuring valid data or an error message is sent to the client.
if ($result->num_rows > 0) {
    // Variable: Stores the customer record as an associative array.
    // Array: $result->fetch_assoc() is a MySQLi method that retrieves the row as an associative array with keys corresponding to customer table columns (e.g., CustomerID, Name).
    // Extracts the single customer record for inclusion in the JSON response.
    $customer = $result->fetch_assoc();
    
    // Output statement: Sends the customer record as a JSON response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts the $customer array to a JSON string, a data format for web communication.
    // Delivers the customer record to the client for display, such as showing customer details in a profile view or admin interface.
    echo json_encode($customer);
} else {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'Customer not found'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that no customer was found, allowing error handling (e.g., displaying an error message to the user).
    echo json_encode(['status' => 'error', 'message' => 'Customer not found']);
}

// Method call: Frees the customer statement resources.
// String: close() is a MySQLi method that releases the prepared statement ($stmt), freeing memory.
// Ensures efficient resource management after the customer retrieval is complete.
$stmt->close();

// Method call: Closes the database connection.
// String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open after the query, maintaining system efficiency and resource management.
$conn->close();
?>