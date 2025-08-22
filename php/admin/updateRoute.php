<?php
// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to update route details in the route table for the International Bus Booking System.
include('../../php/databaseconnection.php');

// Variable: Retrieves and decodes the JSON data from the HTTP POST request body.
// Array: file_get_contents('php://input') is a PHP function that reads the raw POST data (JSON string) sent by the client; json_decode(..., true) is a PHP function that converts the JSON string into a PHP associative array, with true ensuring an array (not an object) is returned.
// Captures the route details sent by the client (e.g., via an AJAX POST request) for processing and updating the database.
$data = json_decode(file_get_contents('php://input'), true);

// Variable: Stores the Route ID from the JSON input.
// String or integer: $data['routeId'] is an associative array element containing the unique identifier of the route to update, later bound as an integer in the prepared statement.
// Identifies which route record in the route table to modify.
$routeId = $data['routeId'];

// Variable: Stores the start location from the JSON input.
// String: $data['startLocation'] is an associative array element containing the starting point of the route, bound as a string in the prepared statement.
// Updates the route’s starting location, ensuring accurate route information.
$startLocation = $data['startLocation'];

// Variable: Stores the destination from the JSON input.
// String: $data['destination'] is an associative array element containing the ending point of the route, bound as a string in the prepared statement.
// Updates the route’s destination, ensuring accurate route information.
$destination = $data['destination'];

// Variable: Stores the distance from the JSON input.
// String or double: $data['distance'] is an associative array element containing the distance of the route (e.g., in kilometers), bound as a double in the prepared statement.
// Updates the route’s distance, supporting scheduling and fare calculations.
$distance = $data['distance'];

// Variable: Stores the route name from the JSON input.
// String: $data['routeName'] is an associative array element containing the name or identifier of the route, bound as a string in the prepared statement.
// Updates the route’s name, providing a clear identifier for the route.
$routeName = $data['routeName'];

// Variable: Stores the route type from the JSON input.
// String: $data['routeType'] is an associative array element containing the type of route (e.g., 'Express', 'Regular'), bound as a string in the prepared statement.
// Updates the route’s type, allowing categorization of travel options.
$routeType = $data['routeType'];

// Variable: Stores the security status from the JSON input.
// String: $data['security'] is an associative array element containing the security level or status of the route, bound as a string in the prepared statement.
// Updates the route’s security status, ensuring safety information is current.
$security = $data['security'];

// Variable: SQL query string to update the route record.
// String: Defines an UPDATE query for the route table, setting StartLocation, Destination, Distance, RouteName, RouteType, and Security to placeholders (?), with a WHERE clause targeting RouteID = ?.
// Specifies the fields to update for the route identified by Route ID, using placeholders for secure data binding.
$sql = "UPDATE route SET StartLocation = ?, Destination = ?, Distance = ?, RouteName = ?, RouteType = ?, Security = ? WHERE RouteID = ?";

// Variable: Stores the prepared statement for the update query.
// Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with placeholders, returning a statement object for binding and execution.
// Prepares the query to safely update the route record, reducing the risk of SQL injection.
$stmt = $conn->prepare($sql);

// Method call: Binds input variables to the prepared statement’s placeholders.
// String: bind_param("ssdsssi", ...) is a MySQLi method that binds variables to the placeholders in order: 's' (string) for $startLocation, $destination, $routeName, $routeType, and $security; 'd' (double) for $distance; 'i' (integer) for $routeId.
// Securely links the input data to the query, preventing SQL injection by treating inputs as data, not code.
$stmt->bind_param("ssdsssi", $startLocation, $destination, $distance, $routeName, $routeType, $security, $routeId);

// Conditional statement: Executes the update query and checks the outcome.
// Boolean check: $stmt->execute() is a MySQLi method that runs the prepared statement, returning true on success or false on failure (e.g., invalid data or database error).
// Determines whether the route was updated successfully to send the appropriate JSON response.
if ($stmt->execute()) {
    // Output statement: Sends a JSON success response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'success', 'message' => 'Route updated successfully'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that the route update was successful, allowing the admin dashboard to refresh (e.g., show updated route details).
    echo json_encode(['status' => 'success', 'message' => 'Route updated successfully']);
} else {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'Failed to update route'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript of the update failure, enabling error handling or debugging.
    echo json_encode(['status' => 'error', 'message' => 'Failed to update route']);
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