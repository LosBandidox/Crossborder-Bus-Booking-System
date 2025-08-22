<?php
// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to perform deletion operations for user records.
include('../../php/databaseconnection.php');

// Conditional statement: Validates the presence of the User ID from the URL query string.
// Function calls: isset() is a PHP built-in function that checks if $_GET['id'], a superglobal array element from the URL, exists and is not null.
// Ensures a valid User ID is provided before attempting deletions, preventing invalid requests from proceeding.
if (isset($_GET['id'])) {
    // Variable: Stores the User ID from the URL query string.
    // String: $_GET['id'] is a superglobal array element containing the User ID passed via the URL (e.g., ?id=123), later validated as an integer through prepared statement binding.
    // Captures the User ID to identify which user record to delete from the database.
    $userId = $_GET['id'];

    // Variable: SQL query string to delete the user record.
    // String: Defines a query to DELETE FROM users WHERE UserID = ?, using a placeholder (?) for secure parameter binding.
    // Specifies the deletion of the user record identified by the User ID, completing the removal process.
    $sql = "DELETE FROM users WHERE UserID = ?";

    // Variable: Stores the prepared statement for user deletion.
    // Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with the placeholder, returning a statement object for binding and execution.
    // Prepares the query to safely delete the user record, reducing the risk of SQL injection.
    $stmt = $conn->prepare($sql);

    // Method call: Binds the User ID to the prepared statement.
    // String: bind_param("i", $userId) is a MySQLi method that binds $userId as an integer ('i') to the placeholder in the query, sanitizing the input.
    // Securely links the User ID to the query, preventing SQL injection by treating the input as data, not code.
    $stmt->bind_param("i", $userId);

    // Conditional statement: Executes the prepared statement and checks the result.
    // Function call: execute() is a MySQLi method that runs the prepared statement, deleting the user record matching the User ID.
    // Determines whether the deletion was successful to provide appropriate feedback to the client.
    if ($stmt->execute()) {
        // Output statement: Sends a JSON success response to the client.
        // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'success', 'message' => 'User deleted successfully'] to a JSON string, a data format for web communication.
        // Informs the client’s JavaScript that the deletion was successful, allowing the dashboard or interface to update (e.g., remove the user from the display).
        echo json_encode(['status' => 'success', 'message' => 'User deleted successfully']);
    } else {
        // Output statement: Sends a JSON error response to the client.
        // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'Failed to delete user'] to a JSON string, a data format for web communication.
        // Informs the client’s JavaScript of the deletion failure, allowing error handling (e.g., displaying an error message to the user).
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete user']);
    }

    // Method call: Frees the user statement resources.
    // String: close() is a MySQLi method that releases the prepared statement ($stmt), freeing memory.
    // Ensures efficient resource management after the user deletion is complete.
    $stmt->close();
} else {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'User ID not provided'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript of the missing User ID, allowing error handling (e.g., displaying an error message to the user).
    echo json_encode(['status' => 'error', 'message' => 'User ID not provided']);
}

// Method call: Closes the database connection.
// String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open after operations, maintaining system efficiency and resource management.
$conn->close();
?>