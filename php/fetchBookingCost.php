<?php
// Function call: HTTP header setting function.
// String: header() is a PHP built-in function that sets an HTTP response header, here setting 'Content-Type: application/json'.
// Sets the response format to JSON, ensuring the client (e.g., JavaScript) can parse the booking cost and seat count correctly in the International Bus Booking System.
header('Content-Type: application/json');

// Include statement: File inclusion directive.
// String: include is a PHP statement that loads 'databaseconnection.php', a file that defines the $conn MySQLi connection object.
// Loads the database connection settings to query booking details for calculating the total cost.
include 'databaseconnection.php';

// Variable: Booking ID storage.
// String or null: $_GET['bookingID'] is a superglobal array element from the URL query string (e.g., ?bookingID=123), accessed with the null coalescing operator (??), a PHP operator that returns the first operand if it exists and is not null, otherwise null.
// Stores the booking ID to identify the specific booking for cost and seat count retrieval, defaulting to null if missing.
$bookingID = $_GET['bookingID'] ?? null;

// Conditional statement: Logic to validate the booking ID.
// Boolean check: Tests if $bookingID is false or null, indicating a missing or invalid booking ID.
// Stops the script with an error if no booking ID is provided, ensuring valid input for the query.
if (!$bookingID) {
    // Output statement: JSON error response output.
    // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Booking ID is required') to a JSON string.
    // Sends an error to the client if the booking ID is missing, preventing invalid database queries.
    echo json_encode(["status" => "error", "message" => "Booking ID is required"]);
    // Function call: Script termination function.
    // String: exit() is a PHP built-in function that stops script execution.
    // Halts the script to prevent further processing without a valid booking ID.
    exit();
}

// Variable: SQL SELECT query string.
// String: Defines a query joining 'bookingdetails' and 'scheduleinformation' tables on ScheduleID to select Cost and SeatNumber where BookingID matches a placeholder (?).
// Retrieves the cost and seat numbers for the specified booking to calculate the total amount.
$sql = "SELECT s.Cost, b.SeatNumber 
        FROM bookingdetails b
        JOIN scheduleinformation s ON b.ScheduleID = s.ScheduleID
        WHERE b.BookingID = ?";

// Object: Prepared statement for database query.
// Object: $conn->prepare($sql) is a MySQLi method that creates a prepared statement object from the SQL query, where $conn is the MySQLi connection from databaseconnection.php.
// Prepares the query to fetch booking details securely, using a placeholder to prevent SQL injection.
$stmt = $conn->prepare($sql);

// Conditional statement: Logic to check statement preparation.
// Boolean check: Tests if $stmt is false, indicating preparation failure due to SQL errors or connection issues.
// Stops the script with an error if the query cannot be prepared, ensuring reliable data retrieval.
if ($stmt === false) {
    // Output statement: JSON error response output.
    // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Prepare failed: ' concatenated with $conn->error, a MySQLi property with the error message) to a JSON string.
    // Sends an error to the client if the query preparation fails, alerting them to a server issue.
    echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
    // Function call: Script termination function.
    // String: exit() is a PHP built-in function that stops script execution.
    // Halts the script to prevent execution with an invalid query.
    exit();
}

// Method call: Parameter binding function.
// String: bind_param("i", $bookingID) is a MySQLi method that binds $bookingID as an integer (i) to the query’s placeholder (?).
// Attaches the booking ID to the query safely, preventing SQL injection for secure cost and seat data retrieval.
$stmt->bind_param("i", $bookingID);

// Method call: Query execution function.
// String: execute() is a MySQLi method that runs the prepared statement on the database.
// Executes the query to fetch the cost and seat numbers for the specified booking.
$stmt->execute();

// Variable: Query result storage.
// Object: $stmt->get_result() is a MySQLi method that retrieves the result set from the executed prepared statement.
// Stores the booking cost and seat data for processing into a response.
$result = $stmt->get_result();

// Conditional statement: Logic to check if a booking record was found.
// Array: $result->fetch_assoc() is a MySQLi method that retrieves a single row from the result set as an associative array, assigned to $row if a record exists.
// Processes the booking data to calculate and send the total cost, or sends an error if no record is found.
if ($row = $result->fetch_assoc()) {
    // Variable: Seat count calculation.
    // Integer: explode(',', $row['SeatNumber']) is a PHP built-in function that splits the SeatNumber string by commas into an array; array_filter() removes empty elements; count() returns the number of non-empty elements; empty($row['SeatNumber']) checks if the string is empty, defaulting to 0.
    // Counts the number of seats booked (e.g., “1,2,3” yields 3) to calculate the total cost.
    $seatCount = empty($row['SeatNumber']) ? 0 : count(array_filter(explode(',', $row['SeatNumber'])));
    // Variable: Total cost calculation.
    // Float: Multiplies $row['Cost'] (the per-seat cost from the query) by $seatCount.
    // Calculates the total amount for the booking based on the number of seats.
    $totalAmount = $row['Cost'] * $seatCount;
    // Output statement: JSON success response output.
    // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('success'), 'amount' ($totalAmount), and 'seatCount' ($seatCount) to a JSON string.
    // Sends the total cost and seat count to the client (e.g., JavaScript) for display in the booking system.
    echo json_encode(["status" => "success", "amount" => $totalAmount, "seatCount" => $seatCount]);
} else {
    // Output statement: JSON error response output.
    // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Booking not found for ID: ' concatenated with $bookingID) to a JSON string.
    // Sends an error to the client if no booking is found, including the booking ID for clarity.
    echo json_encode(["status" => "error", "message" => "Booking not found for ID: $bookingID"]);
}

// Method call: Statement closure function.
// String: close() is a MySQLi method that frees resources associated with the prepared statement ($stmt).
// Releases database resources after fetching data to maintain system efficiency.
$stmt->close();

// Method call: Connection closure function.
// String: close() is a MySQLi method that closes the database connection ($conn).
// Frees database resources after all operations, ensuring no connections remain open.
$conn->close();
?>