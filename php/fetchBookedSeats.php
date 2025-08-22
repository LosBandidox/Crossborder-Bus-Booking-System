<?php
// Include statement: File inclusion directive.
// String: include is a PHP statement that loads 'databaseconnection.php', a file that defines the $conn MySQLi connection object.
// Loads the database connection settings to query booked seats for the International Bus Booking System.
include 'databaseconnection.php';

// Variable: Schedule ID storage.
// String: $_GET['scheduleID'] is a superglobal array element from the URL query string (e.g., ?scheduleID=123).
// Stores the bus schedule ID to identify which trip’s booked seats to fetch from the database.
$scheduleID = $_GET['scheduleID'];

// Function call: Log message function.
// String: error_log() is a PHP built-in function that writes "Received scheduleID in fetchBookedSeats.php: " concatenated with $scheduleID to the server’s error log (e.g., C:/Apache24/logs/php_errors.log).
// Logs the schedule ID for debugging, helping track issues with the received input during seat fetching.
error_log("Received scheduleID in fetchBookedSeats.php: " . $scheduleID);

// Variable: SQL SELECT query string.
// String: Defines a query to select SeatNumber from the 'bookingdetails' table where ScheduleID matches a placeholder (?) and Status is 'Confirmed'.
// Retrieves the list of confirmed booked seat numbers for the specified bus schedule to display on the frontend.
$sql = "SELECT SeatNumber FROM bookingdetails WHERE ScheduleID = ? AND Status = 'Confirmed'";

// Object: Prepared statement for database query.
// Object: $conn->prepare($sql) is a MySQLi method that creates a prepared statement object from the SQL query, where $conn is the MySQLi connection from databaseconnection.php.
// Prepares the query to fetch booked seats securely, using a placeholder to prevent SQL injection.
$stmt = $conn->prepare($sql);

// Conditional statement: Logic to check statement preparation.
// Boolean check: Tests if $stmt is false, indicating preparation failure due to SQL errors or connection issues.
// Terminates the script with an error message if the query cannot be prepared, ensuring reliable data retrieval.
if ($stmt === false) {
    // Function call: Script termination function.
    // String: die() is a PHP built-in function that outputs "Error preparing statement: " concatenated with $conn->error, a MySQLi property with the error message, and stops execution.
    // Stops the script to prevent execution with an invalid query.
    die("Error preparing statement: " . $conn->error);
}

// Method call: Parameter binding function.
// String: bind_param("i", $scheduleID) is a MySQLi method that binds $scheduleID as an integer (i) to the query’s placeholder (?).
// Attaches the schedule ID to the query safely, preventing SQL injection for secure seat data retrieval.
$stmt->bind_param("i", $scheduleID);

// Method call: Query execution function.
// String: execute() is a MySQLi method that runs the prepared statement on the database.
// Executes the query to fetch confirmed booked seat numbers for the specified schedule.
$stmt->execute();

// Variable: Query result storage.
// Object: $stmt->get_result() is a MySQLi method that retrieves the result set from the executed prepared statement.
// Stores the booked seat data for processing into a response.
$result = $stmt->get_result();

// Variable: Booked seats storage.
// Array: An empty array initialized to hold seat numbers as strings.
// Prepares to collect booked seat numbers for the JSON response to the frontend.
$bookedSeats = [];

// Loop: Iteration over query results.
// Array: $result->fetch_assoc() is a MySQLi method that retrieves each row as an associative array, repeated using a while loop.
// Processes each booked seat record to build the $bookedSeats array for the dashboard or seat selection display.
while ($row = $result->fetch_assoc()) {
    // Array operation: Append seat number to array.
    // String: $row['SeatNumber'] is the SeatNumber field from the fetched associative array.
    // Adds each booked seat number to $bookedSeats for the JSON response.
    $bookedSeats[] = $row['SeatNumber'];
}

// Output statement: JSON response output.
// String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'bookedSeats' key and $bookedSeats value to a JSON string.
// Sends the list of booked seat numbers to the client (e.g., JavaScript) for rendering available seats in the bus booking system.
echo json_encode(["bookedSeats" => $bookedSeats]);

// Method call: Statement closure function.
// String: close() is a MySQLi method that frees resources associated with the prepared statement ($stmt).
// Releases database resources after fetching seat data to maintain system efficiency.
$stmt->close();

// Method call: Connection closure function.
// String: close() is a MySQLi method that closes the database connection ($conn).
// Frees database resources after all operations, ensuring no connections remain open.
$conn->close();
?>