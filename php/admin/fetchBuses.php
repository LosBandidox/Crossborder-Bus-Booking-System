<?php
// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to query bus records for the International Bus Booking System’s admin dashboard or customer interface.
include('../../php/databaseconnection.php');

// Variable: SQL query string to retrieve all bus records.
// String: Defines a SELECT query to fetch BusID, BusNumber, YearOfManufacture, Capacity, EngineNumber, Status, and Mileage from the bus table, selecting all records without filtering.
// Retrieves all bus details to display comprehensive bus information, such as capacity and status, on the dashboard.
$sql = "SELECT BusID, BusNumber, YearOfManufacture, Capacity, EngineNumber, Status, Mileage FROM bus";

// Variable: Stores the result of the bus query.
// Object: $conn->query($sql) is a MySQLi method that executes the SQL query directly and returns a result object containing the retrieved records or false if the query fails.
// Holds the bus data for processing into a JSON response for the client.
$result = $conn->query($sql);

// Variable: Initializes an array to store bus records.
// Array: Creates an empty array to hold associative arrays, each representing a bus with fields like BusID, BusNumber, and Status.
// Prepares to collect bus details for inclusion in the JSON response.
$buses = [];

// Conditional statement: Checks if the query returned any bus records.
// Integer check: $result->num_rows is a MySQLi property that indicates the number of rows returned; greater than zero means records were found.
// Processes bus data only if records exist, ensuring valid data is sent to the client.
if ($result->num_rows > 0) {
    // Loop: Iterates over the query results to collect bus records.
    // Array: $result->fetch_assoc() is a MySQLi method that retrieves each row as an associative array with keys like BusID and Status, repeated using a while loop until no rows remain.
    // Processes each bus record to include in the JSON response for the dashboard.
    while ($row = $result->fetch_assoc()) {
        // Array operation: Appends a bus record to the array.
        // Array: Adds $row to $buses, containing bus details like BusNumber and Capacity.
        // Collects bus data for display on the admin dashboard or customer interface.
        $buses[] = $row;
    }
}

// Function call: Sets the HTTP response header to specify JSON output.
// String: header() is a PHP built-in function that sets an HTTP header, here setting 'Content-Type: application/json', which informs the browser that the response is JSON data.
// Ensures the client’s JavaScript can parse the bus records as JSON, maintaining compatibility with web standards for the dashboard.
header('Content-Type: application/json');

// Output statement: Sends the bus records as a JSON response to the client.
// String: echo outputs text; json_encode() is a PHP built-in function that converts the $buses array to a JSON string, a data format for web communication.
// Delivers all bus records to the client for display, such as listing buses in a table or fleet management interface.
echo json_encode($buses);

// Method call: Closes the database connection.
// String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open after the query, maintaining system efficiency and resource management.
$conn->close();

?>
