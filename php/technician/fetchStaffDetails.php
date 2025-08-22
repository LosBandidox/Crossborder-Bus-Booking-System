<?php
// Function call: Starts or resumes a PHP session to access session data.
// String: session_start() is a PHP built-in function that initializes a new session or resumes an existing one, enabling access to $_SESSION variables.
// Allows retrieval of the logged-in staff’s email stored in the session for authentication and querying purposes in the International Bus Booking System.
session_start();

// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to query staff profile details for the International Bus Booking System’s staff profile interface.
include '../databaseconnection.php';

// Variable: Stores the logged-in user’s email from the session.
// String: $_SESSION["Email"] is a superglobal array element containing the email of the logged-in user.
// Captures the email to identify which staff member’s profile details to retrieve from the database.
$email = $_SESSION["Email"];

// Variable: SQL query string to retrieve a specific staff record.
// String: Defines a SELECT query to fetch Name, PhoneNumber, Email, StaffNumber, and Role from the staff table WHERE Email = ?, using a placeholder (?) for secure parameter binding.
// Retrieves the specific staff member’s details for display on the staff profile interface.
$sql = "SELECT Name, PhoneNumber, Email, StaffNumber, Role FROM staff WHERE Email = ?";

// Variable: Stores the prepared statement for staff profile retrieval.
// Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with the placeholder, returning a statement object for binding and execution.
// Prepares the query to safely retrieve the staff record, reducing the risk of SQL injection.
$stmt = $conn->prepare($sql);

// Conditional statement: Checks if the prepared statement was successfully created.
// Boolean check: Tests if $stmt is false, indicating a preparation failure (e.g., syntax error or database issue).
// Ensures the query is valid before proceeding, preventing execution of a faulty statement.
if ($stmt === false) {
    // Function call: Terminates script execution with an error message.
    // String: die() is a PHP built-in function that outputs a message ("Error preparing statement: " . $conn->error) and stops the script.
    // Halts processing and informs the client of a preparation failure to ensure robust error handling.
    die("Error preparing statement: " . $conn->error);
}

// Method call: Binds the email to the prepared statement.
// String: bind_param("s", $email) is a MySQLi method that binds $email as a string ('s') to the placeholder in the query, sanitizing the input.
// Securely links the email to the query, preventing SQL injection by treating the input as data, not code.
$stmt->bind_param("s", $email);

// Method call: Executes the staff profile retrieval query.
// String: execute() is a MySQLi method that runs the prepared statement, querying the staff record matching the email.
// Retrieves the staff record from the staff table for processing.
$stmt->execute();

// Variable: Stores the result set from the executed query.
// Object: $stmt->get_result() is a MySQLi method that retrieves the result set from the prepared statement, allowing row fetching.
// Holds the staff profile data for further processing into a JSON response.
$result = $stmt->get_result();

// Conditional statement: Checks if exactly one staff record was returned.
// Integer check: $result->num_rows is a MySQLi property that indicates the number of rows returned; exactly one row confirms a valid staff record.
// Processes the staff data only if a single record exists, ensuring valid data or an error message is sent to the client.
if ($result->num_rows == 1) {
    // Variable: Stores the staff record as an associative array.
    // Array: $result->fetch_assoc() is a MySQLi method that retrieves the row as an associative array with keys like Name and Role.
    // Extracts the single staff record for inclusion in the JSON response.
    $staff = $result->fetch_assoc();
    
    // Output statement: Sends a JSON success response with the staff record to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ["status" => "success", "staff" => $staff] to a JSON string, a data format for web communication.
    // Delivers the staff record to the client for display, such as showing profile details in the staff profile interface.
    echo json_encode(["status" => "success", "staff" => $staff]);
} else {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ["status" => "error", "message" => "Staff not found"] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that no staff was found, allowing error handling (e.g., displaying an error message to the user).
    echo json_encode(["status" => "error", "message" => "Staff not found"]);
}

// Method call: Frees the staff statement resources.
// String: close() is a MySQLi method that releases the prepared statement ($stmt), freeing memory.
// Ensures efficient resource management after the staff profile retrieval is complete.
$stmt->close();

// Method call: Closes the database connection.
// String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open after the query, maintaining system efficiency and resource management.
$conn->close();
?>