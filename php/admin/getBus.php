<?php
// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to retrieve a bus record from the bus table for the International Bus Booking System.
include('../../php/databaseconnection.php');

// Variable: Stores the bus ID from the GET request.
// String or integer: $_GET['id'] is a superglobal array element containing the unique identifier of the bus to retrieve, later bound as an integer in the prepared statement.
// Identifies which bus record in the bus table to fetch based on the provided BusID.
$busId = $_GET['id'];

// Variable: SQL query string to select a bus record.
// String: Defines a SELECT query for the bus table, retrieving BusID, BusNumber, YearOfManufacture, Capacity, EngineNumber, Status, and Mileage with a WHERE clause targeting BusID = ?.
// Specifies the fields to retrieve for the bus identified by BusID, using a placeholder for secure data binding.
$sql = "SELECT BusID, BusNumber, YearOfManufacture, Capacity, EngineNumber, Status, Mileage FROM bus WHERE BusID = ?";

// Variable: Stores the prepared statement for the select query.
// Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with a placeholder, returning a statement object for binding and execution.
// Prepares the query to safely retrieve bus data, reducing the risk of SQL injection.
$stmt = $conn->prepare($sql);

// Method call: Binds the bus ID to the prepared statement’s placeholder.
// String: bind_param("i", ...) is a MySQLi method that binds $busId as an integer ('i') to the placeholder in the query.
// Securely links the bus ID to the query, preventing SQL injection by treating the input as data, not code.
$stmt->bind_param("i", $busId);

// Method call: Executes the select query.
// String: execute() is a MySQLi method that runs the prepared statement, querying the bus table for the specified BusID.
// Retrieves the bus record from the database.
$stmt->execute();

// Variable: Stores the result set from the executed query.
// Object: $stmt->get_result() is a MySQLi method that returns a result object containing the query results for further processing.
// Captures the query results to check for data and fetch the bus details.
$result = $stmt->get_result();

// Conditional statement: Checks if the query returned any bus records.
// Integer check: $result->num_rows is a MySQLi property that indicates the number of rows in the result set; greater than zero means a bus record was found.
// Determines whether a bus was found to send the appropriate JSON response.
if ($result->num_rows > 0) {
    // Variable: Stores the bus data as an associative array.
    // Array: $result->fetch_assoc() is a MySQLi method that fetches the first row of the result set as an associative array with column names as keys.
    // Extracts the bus details for encoding into the JSON response.
    $bus = $result->fetch_assoc();
    
    // Output statement: Sends a JSON success response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts the $bus array to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript (e.g., on the admin interface) of the bus details, allowing display or further processing.
    echo json_encode($bus);
} else {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['error' => 'Bus not found'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that no bus was found, enabling error handling (e.g., displaying a not-found message).
    echo json_encode(['error' => 'Bus not found']);
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