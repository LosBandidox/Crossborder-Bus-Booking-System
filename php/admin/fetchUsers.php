<?php
// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to query user records for the International Bus Booking System’s admin dashboard or customer interface.
include('../../php/databaseconnection.php');

// Variable: SQL query string to retrieve all user records.
// String: Defines a SELECT query to fetch UserID, Name, Email, PhoneNumber, and Role from the users table, selecting all records without filtering.
// Retrieves all user details to display comprehensive user information, such as names and roles, on the dashboard.
$sql = "SELECT UserID, Name, Email, PhoneNumber, Role FROM users";

// Variable: Stores the result of the user query.
// Object: $conn->query($sql) is a MySQLi method that executes the SQL query directly and returns a result object containing the retrieved records or false if the query fails.
// Holds the user data for processing into a JSON response for the client.
$result = $conn->query($sql);

// Variable: Initializes an array to store user records.
// Array: Creates an empty array to hold associative arrays, each representing a user with fields like UserID, Name, and Role, or an error message if no records are found.
// Prepares to collect user details for inclusion in the JSON response.
$users = [];

// Conditional statement: Checks if the query returned any user records.
// Integer check: $result->num_rows is a MySQLi property that indicates the number of rows returned; greater than zero means records were found.
// Processes user data only if records exist, otherwise adds an error message to ensure valid feedback is sent to the client.
if ($result->num_rows > 0) {
    // Loop: Iterates over the query results to collect user records.
    // Array: $result->fetch_assoc() is a MySQLi method that retrieves each row as an associative array with keys like UserID and Role, repeated using a while loop until no rows remain.
    // Processes each user record to include in the JSON response for the dashboard.
    while ($row = $result->fetch_assoc()) {
        // Array operation: Appends a user record to the array.
        // Array: Adds $row to $users, containing user details like Name and Email.
        // Collects user data for display on the admin dashboard or customer interface.
        $users[] = $row;
    }
} else {
    // Array operation: Adds an error message to the user array.
    // Array: Adds an associative array with key 'error' and value 'No users found' to $users.
    // Informs the client that no user records were retrieved for display or further processing.
    $users[] = ['error' => 'No users found'];
}

// Function call: Sets the HTTP response header to specify JSON output.
// String: header() is a PHP built-in function that sets an HTTP header, here setting 'Content-Type: application/json', which informs the browser that the response is JSON data.
// Ensures the client’s JavaScript can parse the user records or error message as JSON, maintaining compatibility with web standards for the dashboard.
header('Content-Type: application/json');

// Output statement: Sends the user records or error message as a JSON response to the client.
// String: echo outputs text; json_encode() is a PHP built-in function that converts the $users array to a JSON string, a data format for web communication.
// Delivers all user records or an error message to the client for display, such as listing users in a table or user management interface.
echo json_encode($users);

// Method call: Closes the database connection.
// String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open after the query, maintaining system efficiency and resource management.
$conn->close();
?>