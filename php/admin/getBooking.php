<?php
// Function call: HTTP header setting function.
// String: header() is a PHP built-in function that sets an HTTP response header, here setting 'Content-Type: application/json'.
// Specifies that the response will be in JSON format, ensuring compatibility with client-side JavaScript for processing booking data.
header('Content-Type: application/json');

// Include statement: File inclusion directive.
// String: include is a PHP statement that loads '../../php/databaseconnection.php', a file that defines the $conn MySQLi connection object.
// Loads the database connection settings to retrieve booking details from the International Bus Booking System’s database.
include('../../php/databaseconnection.php');

// Conditional statement: Logic to validate the BookingID input.
// Boolean checks: isset($_GET['id']) is a PHP built-in function that tests if the 'id' key exists in the $_GET superglobal array; empty($_GET['id']) checks if it’s empty (e.g., "", null, or unset).
// Stops the script with an error if no BookingID is provided in the URL query string, ensuring a valid request.
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Output statement: JSON error response output.
// String: echo outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('No Booking ID provided') to a JSON string.
// Informs the client (e.g., JavaScript) that the request failed due to a missing BookingID.
    echo json_encode(['status' => 'error', 'message' => 'No Booking ID provided']);
    // Method call: Connection closure function.
// String: close() is a MySQLi method that closes the database connection ($conn).
// Frees database resources before exiting to maintain system efficiency.
    $conn->close();
    // Function call: Script termination function.
// String: exit() is a PHP built-in function that stops script execution.
// Halts the script after sending the error response to prevent further processing.
    exit();
}

// Variable: Booking ID storage.
// String: $_GET['id'] is a superglobal array element from the URL query string, containing the unique identifier for the booking (e.g., "123").
// Captures the BookingID to retrieve the specific booking’s details from the database.
$bookingId = $_GET['id'];

// Variable: SQL SELECT query string.
// String: Defines a query to select BookingID, CustomerID, ScheduleID, SeatNumber, BookingDate, TravelDate, and Status from the 'bookingdetails' table where BookingID matches a placeholder (?).
// Retrieves the booking’s details for editing or display (e.g., in an admin or user interface).
$sql = "SELECT BookingID, CustomerID, ScheduleID, SeatNumber, BookingDate, TravelDate, Status 
        FROM bookingdetails WHERE BookingID = ?";

// Object: Prepared statement for database query.
// Object: $conn->prepare($sql) is a MySQLi method that creates a prepared statement object from the SQL query, where $conn is the MySQLi connection from databaseconnection.php.
// Prepares the query to fetch booking details securely, using a placeholder to prevent SQL injection.
$stmt = $conn->prepare($sql);

// Method call: Parameter binding function.
// String: bind_param("i", $bookingId) is a MySQLi method that binds $bookingId as an integer (i) to the query’s placeholder (?).
// Attaches the BookingID to the query safely, preventing SQL injection for secure data retrieval.
$stmt->bind_param("i", $bookingId);

// Method call: Query execution function.
// String: execute() is a MySQLi method that runs the prepared statement to query the database.
// Retrieves the booking’s details based on the provided BookingID.
$stmt->execute();

// Variable: Query result storage.
// Object: $stmt->get_result() is a MySQLi method that retrieves the result set from the executed prepared statement.
// Stores the booking data for processing and response creation.
$result = $stmt->get_result();

// Variable: Booking data storage.
// Array or null: $result->fetch_assoc() is a MySQLi method that retrieves a single row from the result set as an associative array (e.g., ['BookingID' => 123, ...]), or null if no record is found.
// Captures the booking’s details for JSON output to the client.
$booking = $result->fetch_assoc();

// Output statement: JSON response output.
// String: echo outputs the result of json_encode(), a PHP built-in function that converts $booking to a JSON string if it exists, or an empty array ([]) if $booking is null.
// Sends the booking’s details to the client (e.g., JavaScript for editing forms) or an empty array if no booking is found.
echo json_encode($booking ? $booking : []);

// Method call: Statement closure function.
// String: close() is a MySQLi method that frees resources associated with the prepared statement ($stmt).
// Releases database resources after fetching booking data to maintain system efficiency.
$stmt->close();

// Method call: Connection closure function.
// String: close() is a MySQLi method that closes the database connection ($conn).
// Frees database resources after all operations, ensuring no connections remain open.
$conn->close();
?>