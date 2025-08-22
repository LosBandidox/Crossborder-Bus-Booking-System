<?php
// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to query schedule records for the International Bus Booking System’s admin dashboard or customer interface.
include('../../php/databaseconnection.php');

// Variable: SQL query string to retrieve all schedule records.
// String: Defines a SELECT query to fetch ScheduleID, BusID, RouteID, DepartureTime, ArrivalTime, Cost, DriverID, and CodriverID from the scheduleinformation table, selecting all records without filtering.
// Retrieves all schedule details to display comprehensive schedule information, such as departure times and costs, on the dashboard.
$sql = "SELECT ScheduleID, BusID, RouteID, DepartureTime, ArrivalTime, Cost, DriverID, CodriverID FROM scheduleinformation";

// Variable: Stores the result of the schedule query.
// Object: $conn->query($sql) is a MySQLi method that executes the SQL query directly and returns a result object containing the retrieved records or false if the query fails.
// Holds the schedule data for processing into a JSON response for the client.
$result = $conn->query($sql);

// Variable: Initializes an array to store schedule records.
// Array: Creates an empty array to hold associative arrays, each representing a schedule with fields like ScheduleID, DepartureTime, and Cost.
// Prepares to collect schedule details for inclusion in the JSON response.
$schedules = [];

// Conditional statement: Checks if the query returned any schedule records.
// Integer check: $result->num_rows is a MySQLi property that indicates the number of rows returned; greater than zero means records were found.
// Processes schedule data only if records exist, ensuring valid data is sent to the client.
if ($result->num_rows > 0) {
    // Loop: Iterates over the query results to collect schedule records.
    // Array: $result->fetch_assoc() is a MySQLi method that retrieves each row as an associative array with keys like ScheduleID and DepartureTime, repeated using a while loop until no rows remain.
    // Processes each schedule record to include in the JSON response for the dashboard.
    while ($row = $result->fetch_assoc()) {
        // Array operation: Appends a schedule record to the array.
        // Array: Adds $row to $schedules, containing schedule details like BusID and ArrivalTime.
        // Collects schedule data for display on the admin dashboard or customer interface.
        $schedules[] = $row;
    }
}

// Function call: Sets the HTTP response header to specify JSON output.
// String: header() is a PHP built-in function that sets an HTTP header, here setting 'Content-Type: application/json', which informs the browser that the response is JSON data.
// Ensures the client’s JavaScript can parse the schedule records as JSON, maintaining compatibility with web standards for the dashboard.
header('Content-Type: application/json');

// Output statement: Sends the schedule records as a JSON response to the client.
// String: echo outputs text; json_encode() is a PHP built-in function that converts the $schedules array to a JSON string, a data format for web communication.
// Delivers all schedule records to the client for display, such as listing schedules in a table or travel planning interface.
echo json_encode($schedules);

// Method call: Closes the database connection.
// String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open after the query, maintaining system efficiency and resource management.
$conn->close();
?>