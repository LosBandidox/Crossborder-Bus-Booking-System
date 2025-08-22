<?php
// Function call: Sets the HTTP response header to specify JSON output.
// String: header() is a PHP built-in function that sets an HTTP header, here setting 'Content-Type: application/json', which informs the browser that the response is JSON data.
// Ensures the client’s JavaScript (e.g., on the admin or registration interface) can parse the response as JSON, maintaining compatibility with web standards in the International Bus Booking System.
header('Content-Type: application/json');

// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to insert a new customer record into the customer table.
include('../../../php/databaseconnection.php');

// Conditional statement: Verifies that the HTTP request method is POST.
// String: $_SERVER['REQUEST_METHOD'] is a superglobal variable containing the HTTP method (e.g., 'POST', 'GET'); checks if it equals 'POST' to confirm a form submission.
// Ensures the request is a valid form submission, preventing unauthorized or incorrect access to the customer addition process.
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

// Variable: Stores the customer’s full name from the POST form data.
// String: $_POST['name'] is a superglobal array element containing the customer’s name (e.g., 'John Doe') submitted via the form.
// Captures the name to insert into the customer table for identification.
$name = $_POST['name'];

// Variable: Stores the customer’s email address from the POST form data.
// String: $_POST['email'] contains the customer’s email (e.g., 'john@example.com').
// Captures the email to insert into the customer table for contact and login purposes.
$email = $_POST['email'];

// Variable: Stores the customer’s phone number from the POST form data.
// String: $_POST['phoneNumber'] contains the customer’s phone number (e.g., '+254123456789').
// Captures the phone number to insert into the customer table for contact purposes.
$phoneNumber = $_POST['phoneNumber'];

// Variable: Stores the customer’s gender from the POST form data.
// String: $_POST['gender'] contains the customer’s gender (e.g., 'Male', 'Female').
// Captures the gender to insert into the customer table for demographic information.
$gender = $_POST['gender'];

// Variable: Stores the customer’s passport number from the POST form data.
// String: $_POST['passportNumber'] contains the customer’s passport number (e.g., 'A12345678').
// Captures the passport number to insert into the customer table for identification, especially for international travel.
$passportNumber = $_POST['passportNumber'];

// Variable: Stores the customer’s nationality from the POST form data.
// String: $_POST['nationality'] contains the customer’s country of citizenship (e.g., 'Kenya').
// Captures the nationality to insert into the customer table for travel documentation purposes.
$nationality = $_POST['nationality'];

// Variable: SQL query string to insert a new customer record.
// String: Defines an INSERT query into the customer table, setting Name, Email, PhoneNumber, Gender, PassportNumber, and Nationality to placeholders (?).
// Specifies the fields and values to add a new customer record securely to the database.
$sql = "INSERT INTO customer (Name, Email, PhoneNumber, Gender, PassportNumber, Nationality) 
        VALUES (?, ?, ?, ?, ?, ?)";

// Variable: Stores the prepared statement for the insert query.
// Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with placeholders, returning a statement object for binding and execution.
// Prepares the query to safely insert customer data, reducing the risk of SQL injection.
$stmt = $conn->prepare($sql);

// Conditional statement: Checks if the query preparation was successful.
// Boolean check: Tests if $stmt is false, indicating a preparation failure (e.g., syntax error or database issue).
// Stops the script with an error response if preparation fails, ensuring robust error handling.
if (!$stmt) {
    // Output statement: Sends a JSON error response to the client.
// String: echo outputs text; json_encode() converts an array ['status' => 'error', 'message' => 'Failed to prepare query'] to a JSON string.
// Informs the client’s JavaScript that the query preparation failed, enabling error handling or debugging.
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare query']);
    // Function call: Terminates script execution immediately.
// String: exit() stops the script from running further.
// Halts processing to prevent further actions after a preparation failure.
    exit();
}

// Method call: Binds form data to the prepared statement’s placeholders.
// String: bind_param("ssssss", ...) is a MySQLi method that binds $name, $email, $phoneNumber, $gender, $passportNumber, and $nationality as strings ('s') to the placeholders in the query, in that order.
// Securely links the form data to the query, preventing SQL injection by treating inputs as data, not code.
$stmt->bind_param("ssssss", $name, $email, $phoneNumber, $gender, $passportNumber, $nationality);

// Method call: Executes the insert query.
// String: execute() is a MySQLi method that runs the prepared statement, inserting the customer data into the customer table.
// Adds the new customer record to the database.
$stmt->execute();

// Conditional statement: Checks if the insertion was successful.
// Integer check: $stmt->affected_rows is a MySQLi property that indicates the number of rows affected by the query; greater than zero means the insertion succeeded.
// Determines whether the customer was added to send the appropriate JSON response.
if ($stmt->affected_rows > 0) {
    // Output statement: Sends a JSON success response to the client.
// String: echo outputs text; json_encode() converts an array ['status' => 'success', 'message' => 'Customer added successfully'] to a JSON string.
// Informs the client’s JavaScript that the customer was added successfully, allowing the interface to update (e.g., show a confirmation message).
    echo json_encode(['status' => 'success', 'message' => 'Customer added successfully']);
} else {
    // Output statement: Sends a JSON error response to the client.
// String: echo outputs text; json_encode() converts an array ['status' => 'error', 'message' => 'Failed to add customer: ' . $stmt->error] to a JSON string, where $stmt->error is a MySQLi property with the query error message (e.g., duplicate email).
// Informs the client of the insertion failure with specific error details, enabling error handling or debugging.
    echo json_encode(['status' => 'error', 'message' => 'Failed to add customer: ' . $stmt->error]);
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