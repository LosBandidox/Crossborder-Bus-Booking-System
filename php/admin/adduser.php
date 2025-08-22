<?php
// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to insert a new user record into the users table for the International Bus Booking System.
include('../../php/databaseconnection.php');

// Variable: Retrieves and decodes the JSON data from the HTTP POST request body.
// Array: file_get_contents('php://input') is a PHP function that reads the raw POST data (JSON string) sent by the client; json_decode(..., true) is a PHP function that converts the JSON string into a PHP associative array, with true ensuring an array (not an object) is returned.
// Captures the user details sent by the client (e.g., via an AJAX POST request) for processing and insertion into the database.
$data = json_decode(file_get_contents('php://input'), true);

// Function call: Logs the received JSON data for debugging purposes.
// String: error_log() is a PHP built-in function that writes a message to the server’s error log; print_r($data, true) converts the $data array to a human-readable string.
// Records the incoming JSON data to assist with debugging or monitoring user addition requests.
error_log(print_r($data, true));

// Variable: Stores the user’s full name from the JSON input.
// String: $data['name'] is an associative array element containing the user’s name (e.g., 'John Doe').
// Captures the name to insert into the users table for identification.
$name = $data['name'];

// Variable: Stores the user’s email address from the JSON input.
// String: $data['email'] contains the user’s email (e.g., 'john@example.com').
// Captures the email to insert into the users table for contact and login purposes.
$email = $data['email'];

// Variable: Stores the user’s phone number from the JSON input.
// String: $data['phone'] contains the user’s phone number (e.g., '+254123456789').
// Captures the phone number to insert into the users table for contact purposes.
$phone = $data['phone'];

// Variable: Stores the user’s role from the JSON input.
// String: $data['role'] contains the role assigned to the user (e.g., 'Customer', 'Admin').
// Captures the role to insert into the users table for access control purposes.
$role = $data['role'];

// Variable: Stores the hashed password from the JSON input.
// String: password_hash() is a PHP built-in function that hashes $data['password'] using the PASSWORD_DEFAULT algorithm, generating a secure hash.
// Prepares the password for secure storage in the users table, ensuring it is not saved in plain text.
$password = password_hash($data['password'], PASSWORD_DEFAULT);

// Variable: SQL query string to insert a new user record.
// String: Defines an INSERT query into the users table, setting Name, Email, PhoneNumber, Role, and Password to placeholders (?).
// Specifies the fields and values to add a new user record securely to the database.
$sql = "INSERT INTO users (Name, Email, PhoneNumber, Role, Password) VALUES (?, ?, ?, ?, ?)";

// Variable: Stores the prepared statement for the insert query.
// Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with placeholders, returning a statement object for binding and execution.
// Prepares the query to safely insert user data, reducing the risk of SQL injection.
$stmt = $conn->prepare($sql);

// Method call: Binds form data to the prepared statement’s placeholders.
// String: bind_param("sssss", ...) is a MySQLi method that binds $name, $email, $phone, $role, and $password as strings ('s') to the placeholders in the query, in that order.
// Securely links the form data to the query, preventing SQL injection by treating inputs as data, not code.
$stmt->bind_param("sssss", $name, $email, $phone, $role, $password);

// Conditional statement: Checks if the insertion was successful.
// Boolean check: $stmt->execute() is a MySQLi method that runs the prepared statement, returning true on success or false on failure (e.g., duplicate email or constraint violation).
// Determines whether the user was added to send the appropriate JSON response.
if ($stmt->execute()) {
    // Output statement: Sends a JSON success response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'success', 'message' => 'User added successfully'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that the user was added successfully, allowing the admin interface to update (e.g., show a confirmation message).
    echo json_encode(['status' => 'success', 'message' => 'User added successfully']);
} else {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'Failed to add user'] to a JSON string, a data format for web communication.
    // Informs the client of the insertion failure, enabling error handling or debugging.
    echo json_encode(['status' => 'error', 'message' => 'Failed to add user']);
}

// Method call: Frees the prepared statement resources.
// String: close() is a MySQLi method that releases the prepared statement ($stmt), freeing memory.
// Ensures efficient resource management after the insert operation is complete.
$stmt->close();

// Method call: Closes the database connection.
// String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open after the operation, maintaining system efficiency.
$conn->close();
?>