<?php
// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to retrieve a schedule record from the scheduleinformation table for the International Bus Booking System.
include('../../php/databaseconnection.php');

// Variable: Stores the schedule ID from the GET request.
// String or integer: $_GET['id'] is a superglobal array element containing the unique identifier of the schedule to retrieve, later bound as an integer in the prepared statement.
// Identifies which schedule record in the scheduleinformation table to fetch based on the provided ScheduleID.
$scheduleId = $_GET['id'];

// Variable: SQL query string to select a schedule record.
// String: Defines a SELECT query for the scheduleinformation table, retrieving ScheduleID, BusID, RouteID, DepartureTime, ArrivalTime, Cost, DriverID, and CodriverID with a WHERE clause targeting ScheduleID = ?.
// Specifies the fields to retrieve for the schedule identified by ScheduleID, using a placeholder for secure data binding.
$sql = "SELECT ScheduleID, BusID, RouteID, DepartureTime, ArrivalTime, Cost, DriverID, CodriverID FROM scheduleinformation WHERE ScheduleID = ?";

// Variable: Stores the prepared statement for the select query.
// Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with a placeholder, returning a statement object for binding and execution.
// Prepares the query to safely retrieve schedule data, reducing the risk of SQL injection.
$stmt = $conn->prepare($sql);

// Method call: Binds the schedule ID to the prepared statement’s placeholder.
// String: bind_param("i", ...) is a MySQLi method that binds $scheduleId as an integer ('i') to the placeholder in the query.
// Securely links the schedule ID to the query, preventing SQL injection by treating the input as data, not code.
$stmt->bind_param("i", $scheduleId);

// Method call: Executes the select query.
// String: execute() is a MySQLi method that runs the prepared statement, querying the scheduleinformation table for the specified ScheduleID.
// Retrieves the schedule record from the database.
$stmt->execute();

// Variable: Stores the result set from the executed query.
// Object: $stmt->get_result() is a MySQLi method that returns a result object containing the query results for further processing.
// Captures the query results to check for data and fetch the schedule details.
$result = $stmt->get_result();

// Conditional statement: Checks if the query returned any schedule records.
// Integer check: $result->num_rows is a MySQLi property that indicates the number of rows in the result set; greater than zero means a schedule record was found.
// Determines whether a schedule was found to send the appropriate JSON response.
if ($result->num_rows > 0) {
    // Variable: Stores the schedule data as an associative array.
    // Array: $result->fetch_assoc() is a MySQLi method that fetches the first row of the result set as an associative array with column names as keys.
    // Extracts the schedule details for encoding into the JSON response.
    $schedule = $result->fetch_assoc();
    
    // Output statement: Sends a JSON success response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts the $schedule array to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript (e.g., on the admin interface) of the schedule details, allowing display or further processing.
    echo json_encode($schedule);
} else {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['error' => 'Schedule not found'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that no schedule was found, enabling error handling (e.g., displaying a not-found message).
    echo json_encode(['error' => 'Schedule not found']);
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