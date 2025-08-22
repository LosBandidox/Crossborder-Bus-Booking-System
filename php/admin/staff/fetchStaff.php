<?php
// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to query staff records for the International Bus Booking System’s admin dashboard or customer interface.
include('../../../php/databaseconnection.php');

// Variable: SQL query string to retrieve all staff records.
// String: Defines a SELECT query to fetch StaffID, Name, PhoneNumber, Email, StaffNumber, and Role from the staff table, selecting all records without filtering.
// Retrieves all staff details to display comprehensive staff information, such as names and roles, on the dashboard.
$sql = "SELECT StaffID, Name, PhoneNumber, Email, StaffNumber, Role FROM staff";

// Variable: Stores the result of the staff query.
// Object: $conn->query($sql) is a MySQLi method that executes the SQL query directly and returns a result object containing the retrieved records or false if the query fails.
// Holds the staff data for processing into a JSON response for the client.
$result = $conn->query($sql);

// Variable: Initializes an array to store staff records.
// Array: Creates an empty array to hold associative arrays, each representing a staff member with fields like StaffID, Name, and Role.
// Prepares to collect staff details for inclusion in the JSON response.
$staff = [];

// Conditional statement: Checks if the query returned any staff records.
// Integer check: $result->num_rows is a MySQLi property that indicates the number of rows returned; greater than zero means records were found.
// Processes staff data only if records exist, ensuring valid data is sent to the client.
if ($result->num_rows > 0) {
    // Loop: Iterates over the query results to collect staff records.
    // Array: $result->fetch_assoc() is a MySQLi method that retrieves each row as an associative array with keys like StaffID and Role, repeated using a while loop until no rows remain.
    // Processes each staff record to include in the JSON response for the dashboard.
    while ($row = $result->fetch_assoc()) {
        // Array operation: Appends a staff record to the array.
        // Array: Adds $row to $staff, containing staff details like Name and StaffNumber.
        // Collects staff data for display on the admin dashboard or customer interface.
        $staff[] = $row;
    }
}

// Function call: Sets the HTTP response header to specify JSON output.
// String: header() is a PHP built-in function that sets an HTTP header, here setting 'Content-Type: application/json', which informs the browser that the response is JSON data.
// Ensures the client’s JavaScript can parse the staff records as JSON, maintaining compatibility with web standards for the dashboard.
header('Content-Type: application/json');

// Output statement: Sends the staff records as a JSON response to the client.
// String: echo outputs text; json_encode() is a PHP built-in function that converts the $staff array to a JSON string, a data format for web communication.
// Delivers all staff records to the client for display, such as listing staff in a table or staff management interface.
echo json_encode($staff);

// Method call: Closes the database connection.
// String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open after the query, maintaining system efficiency and resource management.
$conn->close();
?>