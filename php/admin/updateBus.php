<?php
// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to update bus details in the bus table for the International Bus Booking System.
include('../../php/databaseconnection.php');

// Variable: Retrieves and decodes the JSON data from the HTTP POST request body.
// Array: file_get_contents('php://input') is a PHP function that reads the raw POST data (JSON string) sent by the client; json_decode(..., true) is a PHP function that converts the JSON string into a PHP associative array, with true ensuring an array (not an object) is returned.
// Captures the bus details sent by the client (e.g., via an AJAX POST request) for processing and updating the database.
$data = json_decode(file_get_contents('php://input'), true);

// Variable: Stores the Bus ID from the JSON input.
// String or integer: $data['busId'] is an associative array element containing the unique identifier of the bus to update, later bound as an integer in the prepared statement.
// Identifies which bus record in the bus table to modify.
$busId = $data['busId'];

// Variable: Stores the bus number from the JSON input.
// String: $data['busNumber'] is an associative array element containing the unique identifier or registration number of the bus, bound as a string in the prepared statement.
// Updates the bus’s registration number, ensuring accurate identification.
$busNumber = $data['busNumber'];

// Variable: Stores the year of manufacture from the JSON input.
// String or integer: $data['yearOfManufacture'] is an associative array element containing the year the bus was manufactured, bound as an integer in the prepared statement.
// Updates the bus’s manufacturing year, maintaining accurate vehicle records.
$yearOfManufacture = $data['yearOfManufacture'];

// Variable: Stores the capacity from the JSON input.
// String or integer: $data['capacity'] is an associative array element containing the number of seats or passengers the bus can hold, bound as an integer in the prepared statement.
// Updates the bus’s seating capacity, ensuring correct scheduling and booking limits.
$capacity = $data['capacity'];

// Variable: Stores the engine number from the JSON input.
// String: $data['engineNumber'] is an associative array element containing the unique engine number of the bus, bound as a string in the prepared statement.
// Updates the bus’s engine number, maintaining detailed vehicle information.
$engineNumber = $data['engineNumber'];

// Variable: Stores the status from the JSON input.
// String: $data['status'] is an associative array element containing the current status of the bus (e.g., 'Active', 'Inactive'), bound as a string in the prepared statement.
// Updates the bus’s operational status, allowing management of fleet availability.
$status = $data['status'];

// Variable: Stores the mileage from the JSON input.
// String or double: $data['mileage'] is an associative array element containing the total mileage of the bus, bound as a double in the prepared statement.
// Updates the bus’s mileage, tracking vehicle usage for maintenance purposes.
$mileage = $data['mileage'];

// Variable: SQL query string to update the bus record.
// String: Defines an UPDATE query for the bus table, setting BusNumber, YearOfManufacture, Capacity, EngineNumber, Status, and Mileage to placeholders (?), with a WHERE clause targeting BusID = ?.
// Specifies the fields to update for the bus identified by Bus ID, using placeholders for secure data binding.
$sql = "UPDATE bus SET BusNumber = ?, YearOfManufacture = ?, Capacity = ?, EngineNumber = ?, Status = ?, Mileage = ? WHERE BusID = ?";

// Variable: Stores the prepared statement for the update query.
// Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with placeholders, returning a statement object for binding and execution.
// Prepares the query to safely update the bus record, reducing the risk of SQL injection.
$stmt = $conn->prepare($sql);

// Method call: Binds input variables to the prepared statement’s placeholders.
// String: bind_param("siissdi", ...) is a MySQLi method that binds variables to the placeholders in order: 's' (string) for $busNumber, $engineNumber, and $status; 'i' (integer) for $yearOfManufacture, $capacity, and $busId; 'd' (double) for $mileage.
// Securely links the input data to the query, preventing SQL injection by treating inputs as data, not code.
$stmt->bind_param("siissdi", $busNumber, $yearOfManufacture, $capacity, $engineNumber, $status, $mileage, $busId);

// Conditional statement: Executes the update query and checks the outcome.
// Boolean check: $stmt->execute() is a MySQLi method that runs the prepared statement, returning true on success or false on failure (e.g., invalid data or database error).
// Determines whether the bus was updated successfully to send the appropriate JSON response.
if ($stmt->execute()) {
    // Output statement: Sends a JSON success response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'success', 'message' => 'Bus updated successfully'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that the bus update was successful, allowing the admin dashboard to refresh (e.g., show updated bus details).
    echo json_encode(['status' => 'success', 'message' => 'Bus updated successfully']);
} else {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'Failed to update bus'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript of the update failure, enabling error handling or debugging.
    echo json_encode(['status' => 'error', 'message' => 'Failed to update bus']);
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