<?php
// Include statement: Imports the database connection settings.
// String: Path 'databaseconnection.php' to the connection script.
// Establishes a database connection using predefined settings.
require_once 'databaseconnection.php';

// Include statement: Imports date utility functions.
// String: Path 'dateUtils.php' to the date utilities script.
// Provides functions for parsing date inputs.
require_once 'dateUtils.php';

// Conditional statement: Verifies the HTTP request method.
// Superglobal: Uses $_SERVER["REQUEST_METHOD"] to check for a POST request.
// Ensures the script processes only form submissions.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Variable: Stores the customer ID from the POST request.
    // String: Retrieved from $_POST["customerID"].
    // Identifies the customer making the booking.
    $customerID = $_POST["customerID"];

    // Variable: Stores the schedule ID from the POST request.
    // String: Retrieved from $_POST["scheduleID"].
    // Identifies the bus schedule for the booking.
    $scheduleID = $_POST["scheduleID"];

    // Variable: Stores the seat number from the POST request.
    // String: Retrieved from $_POST["seatNumber"].
    // Specifies the seat selected for the booking.
    $seatNumber = $_POST["seatNumber"];

    // Variable: Stores the booking date from the POST request.
    // String: Retrieved from $_POST["bookingDate"] in DD-MM-YYYY format.
    // Represents the date the booking was made.
    $bookingDateInput = $_POST["bookingDate"];

    // Variable: Stores the travel date from the POST request.
    // String: Retrieved from $_POST["travelDate"] in DD-MM-YYYY format.
    // Represents the date of travel.
    $travelDateInput = $_POST["travelDate"];

    // Variable: Stores the parsed booking date.
    // String or null: Result of parseDateInput($bookingDateInput) from dateUtils.php.
    // Converts DD-MM-YYYY to YYYY-MM-DD for database storage.
    $bookingDate = parseDateInput($bookingDateInput);

    // Variable: Stores the parsed travel date.
    // String or null: Result of parseDateInput($travelDateInput) from dateUtils.php.
    // Converts DD-MM-YYYY to YYYY-MM-DD for database storage.
    $travelDate = parseDateInput($travelDateInput);

    // Conditional statement: Validates form data and date parsing.
    // Function calls: Uses empty() for fields and checks $bookingDate/$travelDate for null.
    // Terminates with an error if any field is missing or dates are invalid.
    if (empty($customerID) || empty($scheduleID) || empty($seatNumber) || empty($bookingDateInput) || empty($travelDateInput) || $bookingDate === null || $travelDate === null) {
        die("All fields are required, and dates must be valid (DD-MM-YYYY).");
    }

    // String: SQL INSERT query with placeholders (?).
    // Structure: Inserts CustomerID, ScheduleID, SeatNumber, BookingDate, TravelDate, and 'Confirmed' into 'bookingdetails'.
    // Records a new booking in the database.
    $sql = "INSERT INTO bookingdetails (CustomerID, ScheduleID, SeatNumber, BookingDate, TravelDate, Status) 
            VALUES (?, ?, ?, ?, ?, 'Confirmed')";

    // Object: Creates a prepared statement for secure query execution.
    // Method call: Uses $conn->prepare($sql) to prepare the SQL query.
    // Binds the query for parameter substitution.
    $stmt = $conn->prepare($sql);

    // Conditional statement: Checks if statement preparation succeeded.
    // Comparison: Tests if $stmt is false to detect errors.
    // Terminates with an error message if preparation fails.
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Method call: Binds variables to the prepared statement’s placeholders.
    // String: 'iisss' specifies types: integers (i) for $customerID and $scheduleID, strings (s) for $seatNumber, $bookingDate, and $travelDate.
    // Links variables to the query for safe execution.
    $stmt->bind_param("iisss", $customerID, $scheduleID, $seatNumber, $bookingDate, $travelDate);

    // Conditional statement: Executes the prepared statement and checks the result.
    // Method call: Uses $stmt->execute() to insert the data.
    // Redirects to payment form on success or outputs an error on failure.
    if ($stmt->execute()) {
        // Variable: Stores the auto-generated booking ID.
        // Integer: Retrieved from $conn->insert_id.
        // Captures the ID of the newly inserted booking.
        $bookingID = $conn->insert_id;

        // Method call: Closes the prepared statement.
        // Frees database resources associated with the statement.
        $stmt->close();

        // Method call: Closes the database connection.
        // Frees database resources.
        $conn->close();

        // Function call: Redirects to the payment form.
        // String: URL with query parameter for bookingID.
        // Sends the user to process payment for the booking.
        header("Location: /frontend/forms/PaymentForm.html?bookingID=$bookingID");

        // Function call: Terminates script execution.
        // Ensures no further code runs after the redirect.
        exit();
    } else {
        // Output statement: Sends an error message.
        // String: Includes $stmt->error for details.
        // Informs the user of the insertion failure.
        echo "Error: " . $stmt->error;

        // Method call: Closes the prepared statement.
        // Frees database resources.
        $stmt->close();

        // Method call: Closes the database connection.
        // Frees database resources.
        $conn->close();
    }
} else {
    // Output statement: Sends an error message.
    // String: Indicates the request method is not POST.
    // Informs the user of an invalid request.
    echo "Invalid request method.";
}
?>