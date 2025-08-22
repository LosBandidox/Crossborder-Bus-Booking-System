<?php
// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to update user details in the users table for the International Bus Booking System.
include('../../php/databaseconnection.php');

// Variable: Retrieves and decodes the JSON data from the HTTP POST request body.
// Array: file_get_contents('php://input') is a PHP function that reads the raw POST data (JSON string) sent by the client; json_decode(..., true) is a PHP function that converts the JSON string into a PHP associative array, with true ensuring an array (not an object) is returned.
// Captures the user details sent by the client (e.g., via an AJAX POST request) for processing and updating the database.
$data = json_decode(file_get_contents('php://input'), true);

// Variable: Stores the User ID from the JSON input.
// String or integer: $data['userId'] is an associative array element containing the unique identifier of the user to update, later bound as an integer in the prepared statement.
// Identifies which user record in the users table to modify.
$userId = $data['userId'];

// Variable: Stores the user’s name from the JSON input.
// String: $data['name'] is an associative array element containing the user’s full name, bound as a string in the prepared statement.
// Updates the user’s name, ensuring accurate personal information.
$name = $data['name'];

// Variable: Stores the user’s email from the JSON input.
// String: $data['email'] is an associative array element containing the user’s email address, bound as a string in the prepared statement.
// Updates the user’s email, ensuring accurate contact information.
$email = $data['email'];

// Variable: Stores the user’s phone number from the JSON input.
// String: $data['phone'] is an associative array element containing the user’s phone number, bound as a string in the prepared statement.
// Updates the user’s phone number, ensuring accurate contact information.
$phone = $data['phone'];

// Variable: Stores the user’s role from the JSON input.
// String: $data['role'] is an associative array element containing the user’s role (e.g., 'Customer', 'Admin'), bound as a string in the prepared statement.
// Updates the user’s role, ensuring accurate access control and permissions.
$role = $data['role'];

// Variable: Stores the hashed password or null from the JSON input.
// String or null: !empty($data['password']) checks if a password is provided; if so, password_hash() is a PHP built-in function that hashes $data['password'] using the PASSWORD_DEFAULT algorithm, generating a secure hash; otherwise, set to null.
// Conditionally prepares the password for secure storage or skips it if not provided, maintaining security standards.
$password = !empty($data['password']) ? password_hash($data['password'], PASSWORD_DEFAULT) : null;

// Conditional statement: Determines the SQL query based on whether a password update is included.
// Boolean check: Tests if $password is truthy (not null), indicating a password update is requested.
// Selects the appropriate SQL query and parameter binding to handle cases with or without a password update.
if ($password) {
    // Variable: SQL query string to update the user record with password.
// String: Defines an UPDATE query for the users table, setting Name, Email, PhoneNumber, Role, and Password to placeholders (?), with a WHERE clause targeting UserID = ?.
// Specifies the fields, including password, to update for the user identified by User ID, using placeholders for secure data binding.
    $sql = "UPDATE users SET Name = ?, Email = ?, PhoneNumber = ?, Role = ?, Password = ? WHERE UserID = ?";

    // Variable: Stores the prepared statement for the update query with password.
// Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with placeholders, returning a statement object for binding and execution.
// Prepares the query to safely update the user record, including password, reducing the risk of SQL injection.
    $stmt = $conn->prepare($sql);

    // Method call: Binds input variables to the prepared statement’s placeholders.
// String: bind_param("sssssi", ...) is a MySQLi method that binds variables to the placeholders in order: 's' (string) for $name, $email, $phone, $role, and $password; 'i' (integer) for $userId.
// Securely links the input data to the query, preventing SQL injection by treating inputs as data, not code.
    $stmt->bind_param("sssssi", $name, $email, $phone, $role, $password, $userId);
} else {
    // Variable: SQL query string to update the user record without password.
// String: Defines an UPDATE query for the users table, setting Name, Email, PhoneNumber, and Role to placeholders (?), with a WHERE clause targeting UserID = ?.
// Specifies the fields, excluding password, to update for the user identified by User ID, using placeholders for secure data binding.
    $sql = "UPDATE users SET Name = ?, Email = ?, PhoneNumber = ?, Role = ? WHERE UserID = ?";

    // Variable: Stores the prepared statement for the update query without password.
// Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with placeholders, returning a statement object for binding and execution.
// Prepares the query to safely update the user record, excluding password, reducing the risk of SQL injection.
    $stmt = $conn->prepare($sql);

    // Method call: Binds input variables to the prepared statement’s placeholders.
// String: bind_param("ssssi", ...) is a MySQLi method that binds variables to the placeholders in order: 's' (string) for $name, $email, $phone, and $role; 'i' (integer) for $userId.
// Securely links the input data to the query, preventing SQL injection by treating inputs as data, not code.
    $stmt->bind_param("ssssi", $name, $email, $phone, $role, $userId);
}

// Conditional statement: Executes the update query and checks the outcome.
// Boolean check: $stmt->execute() is a MySQLi method that runs the prepared statement, returning true on success or false on failure (e.g., invalid data or database error).
// Determines whether the user was updated successfully to send the appropriate JSON response.
if ($stmt->execute()) {
    // Output statement: Sends a JSON success response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'success', 'message' => 'User updated successfully'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that the user update was successful, allowing the admin dashboard to refresh (e.g., show updated user details).
    echo json_encode(['status' => 'success', 'message' => 'User updated successfully']);
} else {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'Failed to update user'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript of the update failure, enabling error handling or debugging.
    echo json_encode(['status' => 'error', 'message' => 'Failed to update user']);
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