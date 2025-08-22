<?php
// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to retrieve a user record from the users table for the International Bus Booking System.
include('../../php/databaseconnection.php');

// Variable: Stores the user ID from the GET request.
// String or integer: $_GET['id'] is a superglobal array element containing the unique identifier of the user to retrieve, later bound as an integer in the prepared statement.
// Identifies which user record in the users table to fetch based on the provided UserID.
$userId = $_GET['id'];

// Variable: SQL query string to select a user record.
// String: Defines a SELECT query for the users table, retrieving UserID, Name, Email, PhoneNumber, and Role with a WHERE clause targeting UserID = ?.
// Specifies the fields to retrieve for the user identified by UserID, using a placeholder for secure data binding.
$sql = "SELECT UserID, Name, Email, PhoneNumber, Role FROM users WHERE UserID = ?";

// Variable: Stores the prepared statement for the select query.
// Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with a placeholder, returning a statement object for binding and execution.
// Prepares the query to safely retrieve user data, reducing the risk of SQL injection.
$stmt = $conn->prepare($sql);

// Method call: Binds the user ID to the prepared statement’s placeholder.
// String: bind_param("i", ...) is a MySQLi method that binds $userId as an integer ('i') to the placeholder in the query.
// Securely links the user ID to the query, preventing SQL injection by treating the input as data, not code.
$stmt->bind_param("i", $userId);

// Method call: Executes the select query.
// String: execute() is a MySQLi method that runs the prepared statement, querying the users table for the specified UserID.
// Retrieves the user record from the database.
$stmt->execute();

// Variable: Stores the result set from the executed query.
// Object: $stmt->get_result() is a MySQLi method that returns a result object containing the query results for further processing.
// Captures the query results to check for data and fetch the user details.
$result = $stmt->get_result();

// Conditional statement: Checks if the query returned any user records.
// Integer check: $result->num_rows is a MySQLi property that indicates the number of rows in the result set; greater than zero means a user record was found.
// Determines whether a user was found to send the appropriate JSON response.
if ($result->num_rows > 0) {
    // Variable: Stores the user data as an associative array.
    // Array: $result->fetch_assoc() is a MySQLi method that fetches the first row of the result set as an associative array with column names as keys.
    // Extracts the user details for encoding into the JSON response.
    $user = $result->fetch_assoc();
    
    // Output statement: Sends a JSON success response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts the $user array to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript (e.g., on the admin interface) of the user details, allowing display or further processing.
    echo json_encode($user);
} else {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['error' => 'User not found'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that no user was found, enabling error handling (e.g., displaying a not-found message).
    echo json_encode(['error' => 'User not found']);
}

// Method call: Frees the prepared statement resources.
// String: close() is a MySQLi method that releases the prepared statement ($stmt), freeing memory.
// Ensures efficient resource management after the fetch operation is complete.
$stmt->close();

// Method call: Closes the database connection.
// String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open after the operation, maintaining system efficiency.
$conn->close();
?>