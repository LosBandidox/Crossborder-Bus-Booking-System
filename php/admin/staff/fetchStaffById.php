<?php
// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to query a specific staff record for the International Bus Booking System’s admin dashboard or customer interface.
include('../../../php/databaseconnection.php');

// Conditional statement: Validates the presence and non-emptiness of the Staff ID from the URL query string.
// Function calls: isset() is a PHP built-in function that checks if $_GET['id'], a superglobal array element from the URL, exists and is not null; empty() is a PHP function that checks if $_GET['id'] is empty (e.g., an empty string).
// Ensures a valid Staff ID is provided before attempting to query the staff record, preventing invalid requests from proceeding.
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'No Staff ID provided'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript of the missing Staff ID, allowing error handling (e.g., displaying an error message to the user).
    echo json_encode(['status' => 'error', 'message' => 'No Staff ID provided']);
    
    // Function call: Terminates script execution immediately.
    // String: exit() is a PHP built-in function that stops the script from running further.
    // Halts processing to prevent further actions after an invalid request.
    exit();
}

// Variable: Stores the Staff ID from the URL query string.
// String: $_GET['id'] is a superglobal array element containing the Staff ID passed via the URL (e.g., ?id=123), later validated as an integer through prepared statement binding.
// Captures the Staff ID to identify which staff record to retrieve from the database.
$staffId = $_GET['id'];

// Variable: SQL query string to retrieve a specific staff record.
// String: Defines a SELECT query to fetch all columns (*) from the staff table WHERE StaffID = ?, using a placeholder (?) for secure parameter binding.
// Retrieves the specific staff member’s details, such as name and role, for display on the admin dashboard or customer interface.
$sql = "SELECT * FROM staff WHERE StaffID = ?";

// Variable: Stores the prepared statement for staff retrieval.
// Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with the placeholder, returning a statement object for binding and execution.
// Prepares the query to safely retrieve the staff record, reducing the risk of SQL injection.
$stmt = $conn->prepare($sql);

// Method call: Binds the Staff ID to the prepared statement.
// String: bind_param("i", $staffId) is a MySQLi method that binds $staffId as an integer ('i') to the placeholder in the query, sanitizing the input.
// Securely links the Staff ID to the query, preventing SQL injection by treating the input as data, not code.
$stmt->bind_param("i", $staffId);

// Method call: Executes the staff retrieval query.
// String: execute() is a MySQLi method that runs the prepared statement, querying the staff record matching the Staff ID.
// Retrieves the staff record from the staff table for processing.
$stmt->execute();

// Variable: Stores the result set from the executed query.
// Object: $stmt->get_result() is a MySQLi method that retrieves the result set from the prepared statement, allowing row fetching.
// Holds the staff data for further processing into a JSON response.
$result = $stmt->get_result();

// Conditional statement: Checks if the query returned any staff records.
// Integer check: $result->num_rows is a MySQLi property that indicates the number of rows returned; greater than zero means a record was found.
// Processes the staff data only if a record exists, ensuring valid data or an error message is sent to the client.
if ($result->num_rows > 0) {
    // Variable: Stores the staff record as an associative array.
    // Array: $result->fetch_assoc() is a MySQLi method that retrieves the row as an associative array with keys corresponding to staff table columns (e.g., StaffID, Name).
    // Extracts the single staff record for inclusion in the JSON response.
    $staff = $result->fetch_assoc();
    
    // Output statement: Sends the staff record as a JSON response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts the $staff array to a JSON string, a data format for web communication.
    // Delivers the staff record to the client for display, such as showing staff details in a profile view or admin interface.
    echo json_encode($staff);
} else {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'Staff not found'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that no staff was found, allowing error handling (e.g., displaying an error message to the user).
    echo json_encode(['status' => 'error', 'message' => 'Staff not found']);
}

// Method call: Frees the staff statement resources.
// String: close() is a MySQLi method that releases the prepared statement ($stmt), freeing memory.
// Ensures efficient resource management after the staff retrieval is complete.
$stmt->close();

// Method call: Closes the database connection.
// String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open after the query, maintaining system efficiency and resource management.
$conn->close();
?>