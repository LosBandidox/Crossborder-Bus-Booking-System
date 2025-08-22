<?php
// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to insert a new bus record into the bus table for the International Bus Booking System.
include('../../php/databaseconnection.php');

// Variable: Retrieves and decodes the JSON data from the HTTP POST request body.
// Array: file_get_contents('php://input') is a PHP function that reads the raw POST data (JSON string) sent by the client; json_decode(..., true) is a PHP function that converts the JSON string into a PHP associative array, with true ensuring an array (not an object) is returned.
// Captures the bus details sent by the client (e.g., via an AJAX POST request) for processing and insertion into the database.
$data = json_decode(file_get_contents('php://input'), true);

// Variable: Stores the bus number from the JSON input.
// String: $data['busNumber'] is an associative array element containing the bus identification number (e.g., 'ABC123').
// Captures the bus number to insert into the bus table for unique identification.
$busNumber = $data['busNumber'];

// Variable: Stores the year of manufacture from the JSON input.
// String or integer: $data['yearOfManufacture'] is an associative array element containing the year the bus was manufactured (e.g., 2020), later bound as an integer in the prepared statement.
// Captures the year of manufacture to insert into the bus table for tracking bus age.
$yearOfManufacture = $data['yearOfManufacture'];

// Variable: Stores the bus capacity from the JSON input.
// String or integer: $data['capacity'] is an associative array element containing the total seating capacity of the bus (e.g., 50), later bound as an integer in the prepared statement.
// Captures the capacity to insert into the bus table for scheduling and booking purposes.
$capacity = $data['capacity'];

// Variable: Stores the engine number from the JSON input.
// String: $data['engineNumber'] is an associative array element containing the engine number for identification (e.g., 'ENG456789').
// Captures the engine number to insert into the bus table for maintenance and identification.
$engineNumber = $data['engineNumber'];

// Variable: Stores the bus status from the JSON input.
// String: $data['status'] is an associative array element containing the current operational status of the bus (e.g., 'Active', 'Inactive').
// Captures the status to insert into the bus table for operational management.
$status = $data['status'];

// Variable: Stores the bus mileage from the JSON input.
// String or float: $data['mileage'] is an associative array element containing the total distance the bus has traveled (e.g., 150000.5), later bound as a double/float in the prepared statement.
// Captures the mileage to insert into the bus table for maintenance tracking.
$mileage = $data['mileage'];

// Variable: SQL query string to insert a new bus record.
// String: Defines an INSERT query into the bus table, setting BusNumber, YearOfManufacture, Capacity, EngineNumber, Status, and Mileage to placeholders (?).
// Specifies the fields and values to add a new bus record securely to the database.
$sql = "INSERT INTO bus (BusNumber, YearOfManufacture, Capacity, EngineNumber, Status, Mileage) VALUES (?, ?, ?, ?, ?, ?)";

// Variable: Stores the prepared statement for the insert query.
// Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with placeholders, returning a statement object for binding and execution.
// Prepares the query to safely insert bus data, reducing the risk of SQL injection.
$stmt = $conn->prepare($sql);

// Method call: Binds form data to the prepared statement’s placeholders.
// String: bind_param("siissd", ...) is a MySQLi method that binds $busNumber as a string ('s'), $yearOfManufacture and $capacity as integers ('i'), $engineNumber and $status as strings ('s'), and $mileage as a double/float ('d') to the placeholders in the query, in that order.
// Securely links the form data to the query, preventing SQL injection by treating inputs as data, not code.
$stmt->bind_param("siissd", $busNumber, $yearOfManufacture, $capacity, $engineNumber, $status, $mileage);

// Conditional statement: Checks if the insertion was successful.
// Boolean check: $stmt->execute() is a MySQLi method that runs the prepared statement, returning true on success or false on failure (e.g., duplicate entry or constraint violation).
// Determines whether the bus was added to send the appropriate JSON response.
if ($stmt->execute()) {
    // Output statement: Sends a JSON success response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'success', 'message' => 'Bus added successfully'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that the bus was added successfully, allowing the admin interface to update (e.g., show a confirmation message).
    echo json_encode(['status' => 'success', 'message' => 'Bus added successfully']);
} else {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'Failed to add bus'] to a JSON string, a data format for web communication.
    // Informs the client of the insertion failure, enabling error handling or debugging.
    echo json_encode(['status' => 'error', 'message' => 'Failed to add bus']);
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