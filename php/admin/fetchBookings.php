<?php
// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to query booking records for the International Bus Booking System’s admin dashboard or customer interface.
include('../../php/databaseconnection.php');

// Variable: SQL query string to retrieve all booking records.
// String: Defines a SELECT query to fetch BookingID, CustomerID, ScheduleID, SeatNumber, BookingDate, TravelDate, and Status from the bookingdetails table, selecting all records without filtering.
// Retrieves all booking details to display comprehensive booking information, such as customer assignments and travel dates, on the dashboard.
$sql = "SELECT BookingID, CustomerID, ScheduleID, SeatNumber, BookingDate, TravelDate, Status FROM bookingdetails";

// Variable: Stores the result of the booking query.
// Object: $conn->query($sql) is a MySQLi method that executes the SQL query directly and returns a result object containing the retrieved records or false if the query fails.
// Holds the booking data for processing into a JSON response for the client.
$result = $conn->query($sql);

// Variable: Initializes an array to store booking records.
// Array: Creates an empty array to hold associative arrays, each representing a booking with fields like BookingID, CustomerID, and Status.
// Prepares to collect booking details for inclusion in the JSON response.
$bookings = [];

// Conditional statement: Checks if the query returned any booking records.
// Integer check: $result->num_rows is a MySQLi property that indicates the number of rows returned; greater than zero means records were found.
// Processes booking data only if records exist, ensuring valid data is sent to the client.
if ($result->num_rows > 0) {
    // Loop: Iterates over the query results to collect booking records.
// Array: $result->fetch_assoc() is a MySQLi method that retrieves each row as an associative array with keys like BookingID and Status, repeated using a while loop until no rows remain.
// Processes each booking record to include in the JSON response for the dashboard.
    while ($row = $result->fetch_assoc()) {
        // Array operation: Appends a booking record to the array.
// Array: Adds $row to $bookings, containing booking details like CustomerID and TravelDate.
// Collects booking data for display on the admin dashboard or customer interface.
        $bookings[] = $row;
    }
}

// Function call: Sets the HTTP response header to specify JSON output.
// String: header() is a PHP built-in function that sets an HTTP header, here setting 'Content-Type: application/json', which informs the browser that the response is JSON data.
// Ensures the client’s JavaScript can parse the booking records as JSON, maintaining compatibility with web standards for the dashboard.
header('Content-Type: application/json');

// Output statement: Sends the booking records as a JSON response to the client.
// String: echo outputs text; json_encode() is a PHP built-in function that converts the $bookings array to a JSON string, a data format for web communication.
// Delivers all booking records to the client for display, such as listing bookings in a table or customer history.
echo json_encode($bookings);

// Method call: Closes the database connection.
// String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open after the query, maintaining system efficiency and resource management.
$conn->close();
?>