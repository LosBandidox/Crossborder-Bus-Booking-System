<?php
// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to update schedule details in the scheduleinformation table for the International Bus Booking System.
include('../../php/databaseconnection.php');

// Variable: Retrieves and decodes the JSON data from the HTTP POST request body.
// Array: file_get_contents('php://input') is a PHP function that reads the raw POST data (JSON string) sent by the client; json_decode(..., true) is a PHP function that converts the JSON string into a PHP associative array, with true ensuring an array (not an object) is returned.
// Captures the schedule details sent by the client (e.g., via an AJAX POST request) for processing and updating the database.
$data = json_decode(file_get_contents('php://input'), true);

// Variable: Stores the Schedule ID from the JSON input.
// String or integer: $data['scheduleId'] is an associative array element containing the unique identifier of the schedule to update, later bound as an integer in the prepared statement.
// Identifies which schedule record in the scheduleinformation table to modify.
$scheduleId = $data['scheduleId'];

// Variable: Stores the Bus ID from the JSON input.
// String or integer: $data['busID'] is an associative array element containing the identifier of the bus assigned to the schedule, bound as an integer in the prepared statement.
// Updates the bus assigned to the schedule, ensuring accurate vehicle allocation.
$busID = $data['busID'];

// Variable: Stores the Route ID from the JSON input.
// String or integer: $data['routeID'] is an associative array element containing the identifier of the route for the schedule, bound as an integer in the prepared statement.
// Updates the route linked to the schedule, reflecting changes in travel paths.
$routeID = $data['routeID'];

// Variable: Stores the departure time from the JSON input.
// String: $data['departureTime'] is an associative array element containing the departure time of the schedule (e.g., 'YYYY-MM-DD HH:MM:SS'), bound as a string in the prepared statement.
// Updates the schedule’s departure time, ensuring accurate travel timing.
$departureTime = $data['departureTime'];

// Variable: Stores the arrival time from the JSON input.
// String: $data['arrivalTime'] is an associative array element containing the arrival time of the schedule (e.g., 'YYYY-MM-DD HH:MM:SS'), bound as a string in the prepared statement.
// Updates the schedule’s arrival time, ensuring accurate travel timing.
$arrivalTime = $data['arrivalTime'];

// Variable: Stores the cost from the JSON input.
// String or double: $data['cost'] is an associative array element containing the cost of the schedule (e.g., ticket price), bound as a double in the prepared statement.
// Updates the schedule’s cost, supporting fare calculations and pricing updates.
$cost = $data['cost'];

// Variable: Stores the Driver ID from the JSON input.
// String or integer: $data['driverID'] is an associative array element containing the identifier of the driver assigned to the schedule, bound as an integer in the prepared statement.
// Updates the driver assigned to the schedule, ensuring accurate staff allocation.
$driverID = $data['driverID'];

// Variable: Stores the Co-driver ID from the JSON input.
// String or integer: $data['codriverID'] is an associative array element containing the identifier of the co-driver assigned to the schedule, bound as an integer in the prepared statement.
// Updates the co-driver assigned to the schedule, ensuring accurate staff allocation.
$codriverID = $data['codriverID'];

// Variable: SQL query string to update the schedule record.
// String: Defines an UPDATE query for the scheduleinformation table, setting BusID, RouteID, DepartureTime, ArrivalTime, Cost, DriverID, and CodriverID to placeholders (?), with a WHERE clause targeting ScheduleID = ?.
// Specifies the fields to update for the schedule identified by Schedule ID, using placeholders for secure data binding.
$sql = "UPDATE scheduleinformation SET BusID = ?, RouteID = ?, DepartureTime = ?, ArrivalTime = ?, Cost = ?, DriverID = ?, CodriverID = ? WHERE ScheduleID = ?";

// Variable: Stores the prepared statement for the update query.
// Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with placeholders, returning a statement object for binding and execution.
// Prepares the query to safely update the schedule record, reducing the risk of SQL injection.
$stmt = $conn->prepare($sql);

// Method call: Binds input variables to the prepared statement’s placeholders.
// String: bind_param("iissdiii", ...) is a MySQLi method that binds variables to the placeholders in order: 'i' (integer) for $busID, $routeID, $driverID, $codriverID, and $scheduleId; 's' (string) for $departureTime and $arrivalTime; 'd' (double) for $cost.
// Securely links the input data to the query, preventing SQL injection by treating inputs as data, not code.
$stmt->bind_param("iissdiii", $busID, $routeID, $departureTime, $arrivalTime, $cost, $driverID, $codriverID, $scheduleId);

// Conditional statement: Executes the update query and checks the outcome.
// Boolean check: $stmt->execute() is a MySQLi method that runs the prepared statement, returning true on success or false on failure (e.g., invalid data or database error).
// Determines whether the schedule was updated successfully to send the appropriate JSON response.
if ($stmt->execute()) {
    // Output statement: Sends a JSON success response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'success', 'message' => 'Schedule updated successfully'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that the schedule update was successful, allowing the admin dashboard to refresh (e.g., show updated schedule details).
    echo json_encode(['status' => 'success', 'message' => 'Schedule updated successfully']);
} else {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'Failed to update schedule'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript of the update failure, enabling error handling or debugging.
    echo json_encode(['status' => 'error', 'message' => 'Failed to update schedule']);
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