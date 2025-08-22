<?php
// Function call: Enables error reporting.
// Constant: Sets error_reporting to E_ALL.
// Ensures all errors and warnings are logged for debugging.
error_reporting(E_ALL);

// Function call: Configures PHP runtime settings.
// String: Sets 'display_errors' to 1.
// Enables error display in the browser for debugging.
ini_set('display_errors', 1);

// Function call: Initiates a session.
// Uses session_start() to enable session management.
// Allows access to session variables like user email.
session_start();

// Include: Loads the database connection configuration
// File: /php/databaseconnection.php defines $conn for database access
// Establishes a connection to the MySQL database
include 'databaseconnection.php';

// Variable: Stores the schedule ID from the GET request.
// String: Retrieved from $_GET['scheduleID'].
// Identifies the bus schedule for booking.
$scheduleID = $_GET['scheduleID'];

// Variable: Stores the seat number from the GET request.
// String: Retrieved from $_GET['seatNumber'].
// Specifies the selected seat for booking.
$seatNumber = $_GET['seatNumber'];

// Conditional statement: Checks if the user’s email exists in the session.
// Function call: Uses isset() to verify $_SESSION["Email"].
// Determines if the user is logged in to proceed with booking.
if (isset($_SESSION["Email"])) {
    // Variable: Stores the user’s email from the session.
    // String: Retrieved from $_SESSION["Email"].
    // Identifies the logged-in user for customer lookup.
    $email = $_SESSION["Email"];

    // String: SQL SELECT query with a placeholder (?).
    // Structure: Retrieves CustomerID from the 'customer' table where Email matches.
    // Fetches the customer’s unique identifier.
    $sql = "SELECT CustomerID FROM customer WHERE Email = ?";

    // Object: Creates a prepared statement for secure query execution.
    // Method call: Uses $conn->prepare($sql) to prepare the SQL query.
    // Binds the query for parameter substitution.
    $stmt = $conn->prepare($sql);

    // Method call: Binds the email to the prepared statement’s placeholder.
    // String: 's' specifies the type as a string for $email.
    // Links the variable to the query for safe execution.
    $stmt->bind_param("s", $email);

    // Method call: Executes the prepared statement.
    // Queries the database to find the customer’s ID.
    $stmt->execute();

    // Variable: Stores the query result.
    // Method call: Uses $stmt->get_result() to fetch the result set.
    // Captures the data returned by the query.
    $result = $stmt->get_result();

    // Conditional statement: Checks if any rows were returned.
    // Property access: Uses $result->num_rows to verify result existence.
    // Redirects based on whether a customer record exists.
    if ($result->num_rows > 0) {
        // Variable: Stores the fetched row as an associative array.
        // Method call: Uses $result->fetch_assoc() to retrieve the row.
        // Extracts the customer’s data.
        $row = $result->fetch_assoc();

        // Variable: Stores the customer ID.
        // String: Retrieved from $row['CustomerID'].
        // Identifies the customer for booking.
        $customerID = $row['CustomerID'];

        // Function call: Redirects to the booking details form.
        // String: URL with query parameters for customerID, scheduleID, and seatNumber.
        // Sends the user to input booking details.
        header("Location: ../frontend/forms/BookingdetailsInputForm.html?customerID=$customerID&scheduleID=$scheduleID&seatNumber=$seatNumber");

        // Function call: Terminates script execution.
        // Ensures no further code runs after the redirect.
        exit();
    } else {
        // Function call: Redirects to the customer input form.
        // String: URL with query parameters for scheduleID and seatNumber.
        // Sends the user to input customer details.
        header("Location: ../frontend/forms/CustomerInputForm.html?scheduleID=$scheduleID&seatNumber=$seatNumber");

        // Function call: Terminates script execution.
        // Ensures no further code runs after the redirect.
        exit();
    }
} else {
    // Function call: Redirects to the login page.
    // String: URL '../frontend/login.html'.
    // Sends the user to log in if no session email is found.
    header("Location: ../frontend/login.html");

    // Function call: Terminates script execution.
    // Ensures no further code runs after the redirect.
    exit();
}

// Method call: Closes the prepared statement.
// Frees database resources associated with the statement.
$stmt->close();

// Method call: Closes the database connection.
// Frees database resources.
$conn->close();
?>