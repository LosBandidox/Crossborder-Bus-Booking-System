<?php
// Function call: Sets the HTTP response header to specify JSON output.
// String: header() is a PHP built-in function that sets an HTTP header, here setting 'Content-Type: application/json', which informs the browser that the response is JSON data.
// Ensures the client’s JavaScript (e.g., on the admin or registration interface) can parse the response as JSON, maintaining compatibility with web standards in the International Bus Booking System.
header('Content-Type: application/json');

// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to insert a new staff record into the staff table.
include('../../../php/databaseconnection.php');

// Conditional statement: Verifies that the HTTP request method is POST.
// String: $_SERVER['REQUEST_METHOD'] is a superglobal variable containing the HTTP method (e.g., 'POST', 'GET'); checks if it equals 'POST' to confirm a form submission.
// Ensures the request is a valid form submission, preventing unauthorized or incorrect access to the staff addition process.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'Invalid request method'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that the request method is invalid, enabling error handling (e.g., displaying an error message).
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    
    // Function call: Terminates script execution immediately.
    // String: exit() is a PHP built-in function that stops the script from running further.
    // Halts processing to prevent further actions for an invalid request method.
    exit();
}

// Variable: Stores the staff member’s full name from the POST form data.
// String: $_POST['name'] is a superglobal array element containing the staff member’s name (e.g., 'Jane Smith') submitted via the form.
// Captures the name to insert into the staff table for identification.
$name = $_POST['name'];

// Variable: Stores the staff member’s phone number from the POST form data.
// String: $_POST['phoneNumber'] contains the staff member’s phone number (e.g., '+254987654321').
// Captures the phone number to insert into the staff table for contact purposes.
$phoneNumber = $_POST['phoneNumber'];

// Variable: Stores the staff member’s email address from the POST form data.
// String: $_POST['email'] contains the staff member’s email (e.g., 'jane@example.com').
// Captures the email to insert into the staff table for contact and administrative purposes.
$email = $_POST['email'];

// Variable: Stores the staff member’s unique identifier from the POST form data.
// String: $_POST['staffNumber'] contains the staff member’s employee number (e.g., 'EMP12345').
// Captures the staff number to insert into the staff table for unique identification.
$staffNumber = $_POST['staffNumber'];

// Variable: Stores the staff member’s role from the POST form data.
// String: $_POST['role'] contains the staff member’s role or position (e.g., 'Driver', 'Manager').
// Captures the role to insert into the staff table for position and access control purposes.
$role = $_POST['role'];

// Variable: SQL query string to insert a new staff record.
// String: Defines an INSERT query into the staff table, setting Name, PhoneNumber, Email, StaffNumber, and Role to placeholders (?).
// Specifies the fields and values to add a new staff record securely to the database.
$sql = "INSERT INTO staff (Name, PhoneNumber, Email, StaffNumber, Role) 
VALUES (?, ?, ?, ?, ?)";

// Variable: Stores the prepared statement for the insert query.
// Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with placeholders, returning a statement object for binding and execution.
// Prepares the query to safely insert staff data, reducing the risk of SQL injection.
$stmt = $conn->prepare($sql);

// Conditional statement: Checks if the query preparation was successful.
// Boolean check: Tests if $stmt is false, indicating a preparation failure (e.g., syntax error or database issue).
// Stops the script with an error response if preparation fails, ensuring robust error handling.
if (!$stmt) {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'Failed to prepare query'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that the query preparation failed, enabling error handling or debugging.
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare query']);
    
    // Function call: Terminates script execution immediately.
    // String: exit() is a PHP built-in function that stops the script from running further.
    // Halts processing to prevent further actions after a preparation failure.
    exit();
}

// Method call: Binds form data to the prepared statement’s placeholders.
// String: bind_param("sssss", ...) is a MySQLi method that binds $name, $phoneNumber, $email, $staffNumber, and $role as strings ('s') to the placeholders in the query, in that order.
// Securely links the form data to the query, preventing SQL injection by treating inputs as data, not code.
$stmt->bind_param("sssss", $name, $phoneNumber, $email, $staffNumber, $role);

// Method call: Executes the insert query.
// String: execute() is a MySQLi method that runs the prepared statement, inserting the staff data into the staff table.
// Adds the new staff record to the database.
$stmt->execute();

// Conditional statement: Checks if the insertion was successful.
// Integer check: $stmt->affected_rows is a MySQLi property that indicates the number of rows affected by the query; greater than zero means the insertion succeeded.
// Determines whether the staff member was added to send the appropriate JSON response.
if ($stmt->affected_rows > 0) {
    // Output statement: Sends a JSON success response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'success', 'message' => 'Staff added successfully'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that the staff member was added successfully, allowing the interface to update (e.g., show a confirmation message).
    echo json_encode(['status' => 'success', 'message' => 'Staff added successfully']);
} else {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'Failed to add staff: ' . $stmt->error] to a JSON string, where $stmt->error is a MySQLi property with the query error message (e.g., duplicate email).
    // Informs the client of the insertion failure with specific error details, enabling error handling or debugging.
    echo json_encode(['status' => 'error', 'message' => 'Failed to add staff: ' . $stmt->error]);
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