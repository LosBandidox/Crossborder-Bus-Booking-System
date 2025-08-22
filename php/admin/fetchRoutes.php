<?php
// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to query route records for the International Bus Booking System’s admin dashboard or customer interface.
include('../../php/databaseconnection.php');

// Variable: SQL query string to retrieve all route records.
// String: Defines a SELECT query to fetch RouteID, StartLocation, Destination, Distance, RouteName, RouteType, and Security from the route table, selecting all records without filtering.
// Retrieves all route details to display comprehensive route information, such as start and destination locations, on the dashboard.
$sql = "SELECT RouteID, StartLocation, Destination, Distance, RouteName, RouteType, Security FROM route";

// Variable: Stores the result of the route query.
// Object: $conn->query($sql) is a MySQLi method that executes the SQL query directly and returns a result object containing the retrieved records or false if the query fails.
// Holds the route data for processing into a JSON response for the client.
$result = $conn->query($sql);

// Variable: Initializes an array to store route records.
// Array: Creates an empty array to hold associative arrays, each representing a route with fields like RouteID, StartLocation, and Destination.
// Prepares to collect route details for inclusion in the JSON response.
$routes = [];

// Conditional statement: Checks if the query returned any route records.
// Integer check: $result->num_rows is a MySQLi property that indicates the number of rows returned; greater than zero means records were found.
// Processes route data only if records exist, ensuring valid data is sent to the client.
if ($result->num_rows > 0) {
    // Loop: Iterates over the query results to collect route records.
    // Array: $result->fetch_assoc() is a MySQLi method that retrieves each row as an associative array with keys like RouteID and Destination, repeated using a while loop until no rows remain.
    // Processes each route record to include in the JSON response for the dashboard.
    while ($row = $result->fetch_assoc()) {
        // Array operation: Appends a route record to the array.
        // Array: Adds $row to $routes, containing route details like StartLocation and Distance.
        // Collects route data for display on the admin dashboard or customer interface.
        $routes[] = $row;
    }
}

// Function call: Sets the HTTP response header to specify JSON output.
// String: header() is a PHP built-in function that sets an HTTP header, here setting 'Content-Type: application/json', which informs the browser that the response is JSON data.
// Ensures the client’s JavaScript can parse the route records as JSON, maintaining compatibility with web standards for the dashboard.
header('Content-Type: application/json');

// Output statement: Sends the route records as a JSON response to the client.
// String: echo outputs text; json_encode() is a PHP built-in function that converts the $routes array to a JSON string, a data format for web communication.
// Delivers all route records to the client for display, such as listing routes in a table or route selection interface.
echo json_encode($routes);

// Method call: Closes the database connection.
// String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open after the query, maintaining system efficiency and resource management.
$conn->close();
?>