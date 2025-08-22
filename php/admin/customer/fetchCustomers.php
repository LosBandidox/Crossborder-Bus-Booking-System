<?php
// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to query customer records for the International Bus Booking System’s admin dashboard or customer interface.
include('../../../php/databaseconnection.php');

// Variable: SQL query string to retrieve all customer records.
// String: Defines a SELECT query to fetch CustomerID, Name, Email, PhoneNumber, Gender, PassportNumber, and Nationality from the customer table, selecting all records without filtering.
// Retrieves all customer details to display comprehensive customer information, such as names and nationalities, on the dashboard.
$sql = "SELECT CustomerID, Name, Email, PhoneNumber, Gender, PassportNumber, Nationality 
        FROM customer";

// Variable: Stores the result of the customer query.
// Object: $conn->query($sql) is a MySQLi method that executes the SQL query directly and returns a result object containing the retrieved records or false if the query fails.
// Holds the customer data for processing into a JSON response for the client.
$result = $conn->query($sql);

// Variable: Initializes an array to store customer records.
// Array: Creates an empty array to hold associative arrays, each representing a customer with fields like CustomerID, Name, and Nationality.
// Prepares to collect customer details for inclusion in the JSON response.
$customers = [];

// Conditional statement: Checks if the query returned any customer records.
// Integer check: $result->num_rows is a MySQLi property that indicates the number of rows returned; greater than zero means records were found.
// Processes customer data only if records exist, ensuring valid data is sent to the client.
if ($result->num_rows > 0) {
    // Loop: Iterates over the query results to collect customer records.
    // Array: $result->fetch_assoc() is a MySQLi method that retrieves each row as an associative array with keys like CustomerID and Email, repeated using a while loop until no rows remain.
    // Processes each customer record to include in the JSON response for the dashboard.
    while ($row = $result->fetch_assoc()) {
        // Array operation: Appends a customer record to the array.
        // Array: Adds $row to $customers, containing customer details like Name and PassportNumber.
        // Collects customer data for display on the admin dashboard or customer interface.
        $customers[] = $row;
    }
}

// Function call: Sets the HTTP response header to specify JSON output.
// String: header() is a PHP built-in function that sets an HTTP header, here setting 'Content-Type: application/json', which informs the browser that the response is JSON data.
// Ensures the client’s JavaScript can parse the customer records as JSON, maintaining compatibility with web standards for the dashboard.
header('Content-Type: application/json');

// Output statement: Sends the customer records as a JSON response to the client.
// String: echo outputs text; json_encode() is a PHP built-in function that converts the $customers array to a JSON string, a data format for web communication.
// Delivers all customer records to the client for display, such as listing customers in a table or customer management interface.
echo json_encode($customers);

// Method call: Closes the database connection.
// String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open after the query, maintaining system efficiency and resource management.
$conn->close();
?>