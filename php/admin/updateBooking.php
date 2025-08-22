<?php
// Function call: Sets the HTTP response header to specify JSON output.
// String: header() is a PHP built-in function that sets an HTTP header, here setting 'Content-Type: application/json', which informs the browser that the response is JSON data.
// Ensures the client’s JavaScript (e.g., on the admin dashboard or customer interface) can parse the response as JSON, maintaining compatibility with web standards in the International Bus Booking System.
header('Content-Type: application/json');

// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to update booking details in the bookingdetails table.
include('../../php/databaseconnection.php');

// Variable: Retrieves and decodes the JSON data from the HTTP POST request body.
// Array: file_get_contents('php://input') is a PHP function that reads the raw POST data (JSON string) sent by the client; json_decode(..., true) is a PHP function that converts the JSON string into a PHP associative array, with true ensuring an array (not an object) is returned.
// Captures the booking details sent by the client (e.g., via an AJAX POST request) for processing and updating the database.
$input = json_decode(file_get_contents('php://input'), true);

// Conditional statement: Validates the presence of the input data and the required bookingId field.
// Boolean check: Tests if $input is false (invalid JSON or no data) or if $input['bookingId'] is not set using isset(), a PHP function that checks for the existence of an array key.
// Ensures valid input and a Booking ID are provided before attempting the update, preventing invalid requests from proceeding.
if (!$input || !isset($input['bookingId'])) {
    // Output statement: Sends a JSON error response to the client.
// String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'Invalid input'] to a JSON string, a data format for web communication.
// Informs the client’s JavaScript of invalid or missing input, allowing error handling (e.g., displaying an error message to the user).
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    // Method call: Closes the database connection.
// String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open after an invalid request, maintaining system efficiency.
    $conn->close();
    // Function call: Terminates script execution immediately.
// String: exit() is a PHP built-in function that stops the script from running further.
// Halts processing to prevent further actions after an invalid request.
    exit();
}

// Variable: Stores the Booking ID from the JSON input.
// String or integer: $input['bookingId'] is an associative array element containing the unique identifier of the booking to update, later bound as an integer in the prepared statement.
// Identifies which booking record in the bookingdetails table to modify.
$bookingId = $input['bookingId'];

// Variable: Stores the Customer ID from the JSON input.
// String or integer: $input['customerID'] contains the identifier of the customer associated with the booking, bound as an integer in the prepared statement.
// Updates the customer associated with the booking, ensuring accurate customer data.
$customerID = $input['customerID'];

// Variable: Stores the Schedule ID from the JSON input.
// String or integer: $input['scheduleID'] contains the identifier of the bus schedule for the booking, bound as an integer in the prepared statement.
// Updates the schedule linked to the booking, reflecting changes in travel plans.
$scheduleID = $input['scheduleID'];

// Variable: Stores the seat number from the JSON input.
// String: $input['seatNumber'] contains the seat number (e.g., 'A12') assigned to the booking, bound as a string in the prepared statement.
// Updates the seat assignment for the booking, ensuring correct seating information.
$seatNumber = $input['seatNumber'];

// Variable: Stores the booking date from the JSON input.
// String: $input['bookingDate'] contains the date the booking was made (typically YYYY-MM-DD), bound as a string in the prepared statement.
// Updates the booking creation date, maintaining accurate booking records.
$bookingDate = $input['bookingDate'];

// Variable: Stores the travel date from the JSON input.
// String: $input['travelDate'] contains the date of travel (typically YYYY-MM-DD), bound as a string in the prepared statement.
// Updates the travel date, reflecting changes in the passenger’s itinerary.
$travelDate = $input['travelDate'];

// Variable: Stores the booking status from the JSON input.
// String: $input['status'] contains the booking status (e.g., 'Confirmed', 'Cancelled'), bound as a string in the prepared statement.
// Updates the booking status, allowing modifications like cancellations or confirmations.
$status = $input['status'];

// Variable: SQL query string to update the booking record.
// String: Defines an UPDATE query for the bookingdetails table, setting CustomerID, ScheduleID, SeatNumber, BookingDate, TravelDate, and Status to placeholders (?), with a WHERE clause targeting BookingID = ?.
// Specifies the fields to update for the booking identified by Booking ID, using placeholders for secure data binding.
$sql = "UPDATE bookingdetails SET 
        CustomerID = ?, ScheduleID = ?, SeatNumber = ?, BookingDate = ?, TravelDate = ?, Status = ?
        WHERE BookingID = ?";

// Variable: Stores the prepared statement for the update query.
// Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with placeholders, returning a statement object for binding and execution.
// Prepares the query to safely update the booking record, reducing the risk of SQL injection.
$stmt = $conn->prepare($sql);

// Method call: Binds input variables to the prepared statement’s placeholders.
// String: bind_param("iissssi", ...) is a MySQLi method that binds variables to the placeholders in order: 'i' (integer) for $customerID, $scheduleID, and $bookingId; 's' (string) for $seatNumber, $bookingDate, $travelDate, and $status.
// Securely links the input data to the query, preventing SQL injection by treating inputs as data, not code.
$stmt->bind_param("iissssi", $customerID, $scheduleID, $seatNumber, $bookingDate, $travelDate, $status, $bookingId);

// Conditional statement: Executes the update query and checks the outcome.
// Boolean check: $stmt->execute() is a MySQLi method that runs the prepared statement, returning true on success or false on failure (e.g., invalid data or database error).
// Determines whether the booking was updated successfully to send the appropriate JSON response.
if ($stmt->execute()) {
    // Output statement: Sends a JSON success response to the client.
// String: echo outputs text; json_encode() converts an array ['status' => 'success', 'message' => 'Booking updated successfully'] to a JSON string.
// Informs the client’s JavaScript that the booking update was successful, allowing the dashboard or interface to refresh (e.g., show updated booking details).
    echo json_encode(['status' => 'success', 'message' => 'Booking updated successfully']);
} else {
    // Output statement: Sends a JSON error response to the client.
// String: echo outputs text; json_encode() converts an array ['status' => 'error', 'message' => 'Failed to update booking: ' . $stmt->error] to a JSON string, where $stmt->error is a MySQLi property with the query error message (e.g., foreign key violation).
// Informs the client of the update failure with specific error details, enabling error handling or debugging.
    echo json_encode(['status' => 'error', 'message' => 'Failed to update booking: ' . $stmt->error]);
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