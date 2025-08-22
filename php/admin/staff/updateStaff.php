<?php
// Function call: Sets the HTTP response header to specify JSON output.
// String: header() is a PHP built-in function that sets an HTTP header, here setting 'Content-Type: application/json', which informs the browser that the response is JSON data.
// Ensures the client’s JavaScript (e.g., on the admin dashboard) can parse the response as JSON, maintaining compatibility with web standards in the International Bus Booking System.
header('Content-Type: application/json');

// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to update staff details in the staff table for the International Bus Booking System.
include('../../../php/databaseconnection.php');

// Conditional statement: Checks if the HTTP request method is POST to validate form submission.
// String comparison: $_SERVER['REQUEST_METHOD'] is a superglobal variable that contains the request method; checks if it does not equal "POST".
// Ensures the update request is valid and initiated from a POST request, preventing unauthorized or incorrect requests.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'Invalid request method'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that the request method is invalid, allowing error handling (e.g., displaying an error message to the user).
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    
    // Function call: Terminates script execution immediately.
    // String: exit() is a PHP built-in function that stops the script from running further.
    // Halts processing to prevent further actions after an invalid request.
    exit();
}

// Variable: Stores the Staff ID from the POST input.
// String or integer: $_POST['staffId'] is a superglobal array element containing the unique identifier of the staff member to update, later bound as an integer in the prepared statement.
// Identifies which staff record in the staff table to modify.
$staffId = $_POST['staffId'];

// Variable: Stores the staff member’s name from the POST input.
// String: $_POST['name'] is a superglobal array element containing the staff member’s full name, bound as a string in the prepared statement.
// Updates the staff member’s name, ensuring accurate personal information.
$name = $_POST['name'];

// Variable: Stores the staff member’s phone number from the POST input.
// String: $_POST['phoneNumber'] is a superglobal array element containing the staff member’s contact phone number, bound as a string in the prepared statement.
// Updates the staff member’s phone number, ensuring accurate contact information.
$phoneNumber = $_POST['phoneNumber'];

// Variable: Stores the staff member’s email from the POST input.
// String: $_POST['email'] is a superglobal array element containing the staff member’s email address, bound as a string in the prepared statement.
// Updates the staff member’s email, ensuring accurate contact information.
$email = $_POST['email'];

// Variable: Stores the staff number from the POST input.
// String: $_POST['staffNumber'] is a superglobal array element containing the unique employee number or identifier for the staff member, bound as a string in the prepared statement.
// Updates the staff member’s unique identifier, ensuring accurate employee records.
$staffNumber = $_POST['staffNumber'];

// Variable: Stores the staff member’s role from the POST input.
// String: $_POST['role'] is a superglobal array element containing the staff member’s role or position (e.g., 'Driver', 'Manager'), bound as a string in the prepared statement.
// Updates the staff member’s role, ensuring accurate position and access control.
$role = $_POST['role'];

// Variable: SQL query string to update the staff record.
// String: Defines an UPDATE query for the staff table, setting Name, PhoneNumber, Email, StaffNumber, and Role to placeholders (?), with a WHERE clause targeting StaffID = ?.
// Specifies the fields to update for the staff member identified by Staff ID, using placeholders for secure data binding.
$sql = "UPDATE staff SET Name = ?, PhoneNumber = ?, Email = ?, StaffNumber = ?, Role = ? 
        WHERE StaffID = ?";

// Variable: Stores the prepared statement for the update query.
// Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with placeholders, returning a statement object for binding and execution.
// Prepares the query to safely update the staff record, reducing the risk of SQL injection.
$stmt = $conn->prepare($sql);

// Conditional statement: Checks if the prepared statement was successfully created.
// Boolean check: Tests if $stmt is false, indicating a preparation failure (e.g., syntax error or database issue).
// Ensures the query is valid before proceeding, preventing execution of a faulty statement.
if (!$stmt) {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'Failed to prepare query'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript of the preparation failure, allowing error handling (e.g., displaying a system error message).
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare query']);
    
    // Function call: Terminates script execution immediately.
    // String: exit() is a PHP built-in function that stops the script from running further.
    // Halts processing to prevent further actions after a preparation failure.
    exit();
}

// Method call: Binds input variables to the prepared statement’s placeholders.
// String: bind_param("sssssi", ...) is a MySQLi method that binds variables to the placeholders in order: 's' (string) for $name, $phoneNumber, $email, $staffNumber, and $role; 'i' (integer) for $staffId.
// Securely links the input data to the query, preventing SQL injection by treating inputs as data, not code.
$stmt->bind_param("sssssi", $name, $phoneNumber, $email, $staffNumber, $role, $staffId);

// Method call: Executes the staff update query.
// String: execute() is a MySQLi method that runs the prepared statement, updating the staff record matching the Staff ID.
// Modifies the staff record in the staff table with the new details.
$stmt->execute();

// Conditional statement: Checks if the update affected any staff records.
// Integer check: $stmt->affected_rows is a MySQLi property that indicates the number of rows modified; greater than zero means the update was successful.
// Verifies that the staff record was updated before sending the success response.
if ($stmt->affected_rows > 0) {
    // Output statement: Sends a JSON success response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'success', 'message' => 'Staff updated successfully'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that the staff update was successful, allowing the admin dashboard to refresh (e.g., show updated staff details).
    echo json_encode(['status' => 'success', 'message' => 'Staff updated successfully']);
} else {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'No changes made or staff not found'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that no staff record was updated or found, enabling error handling (e.g., displaying an error message to the user).
    echo json_encode(['status' => 'error', 'message' => 'No changes made or staff not found']);
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