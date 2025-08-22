<?php
// Include statement: File inclusion directive.
// String: include is a PHP statement that loads 'databaseconnection.php', a file that defines the $conn MySQLi connection object.
// Loads the database connection settings to update booking and payment details in the International Bus Booking System’s database.
include 'databaseconnection.php';

// Variable: Booking ID storage.
// String: $_POST["bookingID"] is a superglobal array element from the POST request, containing the unique identifier for the booking to be canceled.
// Identifies the specific booking to update its status in the database.
$bookingID = $_POST["bookingID"];

// Variable: SQL UPDATE query string for booking cancellation.
// String: Defines a query to update the 'bookingdetails' table, setting Status to 'Canceled' where BookingID matches a placeholder (?) and Status is 'Confirmed'.
// Ensures only confirmed bookings are canceled, preventing invalid updates.
$sql = "UPDATE bookingdetails SET Status = 'Canceled' WHERE BookingID = ? AND Status = 'Confirmed'";

// Object: Prepared statement for booking update.
// Object: $conn->prepare($sql) is a MySQLi method that creates a prepared statement object from the SQL query, where $conn is the MySQLi connection from databaseconnection.php.
// Prepares the query to update the booking status securely, using a placeholder to prevent SQL injection.
$stmt = $conn->prepare($sql);

// Method call: Parameter binding function for booking update.
// String: bind_param("i", $bookingID) is a MySQLi method that binds $bookingID as an integer (i) to the query’s placeholder (?).
// Attaches the booking ID to the query safely, preventing SQL injection for secure execution.
$stmt->bind_param("i", $bookingID);

// Method call: Query execution function.
// String: execute() is a MySQLi method that runs the prepared statement to update the booking status in the database.
// Performs the cancellation update on the bookingdetails table.
$stmt->execute();

// Conditional statement: Logic to check if the booking was updated.
// Integer check: $stmt->affected_rows is a MySQLi property that returns the number of rows modified by the query, compared to 0.
// Proceeds to update payment status if the booking was canceled, or sends an error if no rows were affected.
if ($stmt->affected_rows > 0) {
    // Variable: SQL UPDATE query string for payment status.
// String: Defines a query to update the 'paymentdetails' table, setting Status to 'Refund Pending' where BookingID matches a placeholder (?) and Status is 'Completed'.
// Marks the associated payment as pending a refund, ensuring only completed payments are updated.
    $sql = "UPDATE paymentdetails SET Status = 'Refund Pending' WHERE BookingID = ? AND Status = 'Completed'";

    // Object: Prepared statement for payment update.
// Object: $conn->prepare($sql) is a MySQLi method that creates a prepared statement object from the SQL query.
// Prepares the query to update the payment status securely, using a placeholder to prevent SQL injection.
    $stmt = $conn->prepare($sql);

    // Method call: Parameter binding function for payment update.
// String: bind_param("i", $bookingID) is a MySQLi method that binds $bookingID as an integer (i) to the query’s placeholder (?).
// Attaches the booking ID to the payment query safely, preventing SQL injection for secure execution.
    $stmt->bind_param("i", $bookingID);

    // Method call: Query execution function.
// String: execute() is a MySQLi method that runs the prepared statement to update the payment status in the database.
// Updates the paymentdetails table to reflect the refund pending status.
    $stmt->execute();

    // Conditional statement: Logic to check if the payment was updated.
// Integer check: $stmt->affected_rows is a MySQLi property that returns the number of rows modified by the query, compared to 0.
// Retrieves the payment amount if updated, or indicates no payment was found.
    if ($stmt->affected_rows > 0) {
        // Variable: SQL SELECT query string for payment amount.
// String: Defines a query to select AmountPaid from the 'paymentdetails' table where BookingID matches a placeholder (?).
// Fetches the payment amount to include in the refund confirmation message.
        $sql = "SELECT AmountPaid FROM paymentdetails WHERE BookingID = ?";

        // Object: Prepared statement for payment amount retrieval.
// Object: $conn->prepare($sql) is a MySQLi method that creates a prepared statement object from the SQL query.
// Prepares the query to retrieve the payment amount securely, using a placeholder to prevent SQL injection.
        $stmt = $conn->prepare($sql);

        // Method call: Parameter binding function for payment amount query.
// String: bind_param("i", $bookingID) is a MySQLi method that binds $bookingID as an integer (i) to the query’s placeholder (?).
// Attaches the booking ID to the query safely, preventing SQL injection for secure retrieval.
        $stmt->bind_param("i", $bookingID);

        // Method call: Query execution function.
// String: execute() is a MySQLi method that runs the prepared statement to retrieve the payment amount from the database.
// Fetches the payment data for the refund message.
        $stmt->execute();

        // Variable: Query result storage.
// Object: $stmt->get_result() is a MySQLi method that retrieves the result set from the executed prepared statement.
// Stores the payment data for processing the amount.
        $result = $stmt->get_result();

        // Variable: Payment row storage.
// Array: $result->fetch_assoc() is a MySQLi method that retrieves a single row from the result set as an associative array.
// Extracts the payment details, including AmountPaid, for the refund message.
        $row = $result->fetch_assoc();

        // Variable: Payment amount storage.
// String: $row['AmountPaid'] is the value of the AmountPaid column from the fetched row.
// Captures the payment amount to include in the success message.
        $amount = $row['AmountPaid'];

        // Output statement: JSON success response output.
// String: echo outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('success') and 'message' ("Booking canceled. Refund of KES $amount will be processed.") to a JSON string.
// Informs the client (e.g., JavaScript) that the booking was canceled and a refund is being processed with the amount in KES.
        echo json_encode(["status" => "success", "message" => "Booking canceled. Refund of KES $amount will be processed."]);
    } else {
        // Output statement: JSON success response output.
// String: echo outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('success') and 'message' ("Booking canceled. No payment found.") to a JSON string.
// Informs the client that the booking was canceled successfully, but no payment was found to refund.
        echo json_encode(["status" => "success", "message" => "Booking canceled. No payment found."]);
    }
} else {
    // Output statement: JSON error response output.
// String: echo outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ("Booking not found or already canceled") to a JSON string.
// Informs the client that the booking could not be canceled because it was not found or was already canceled.
    echo json_encode(["status" => "error", "message" => "Booking not found or already canceled"]);
}

// Method call: Statement closure function.
// String: close() is a MySQLi method that frees resources associated with the prepared statement ($stmt).
// Releases database resources after all operations to maintain system efficiency.
$stmt->close();

// Method call: Connection closure function.
// String: close() is a MySQLi method that closes the database connection ($conn).
// Frees database resources after all operations, ensuring no connections remain open.
$conn->close();
?>