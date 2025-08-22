<?php
// Include statement: File inclusion directive.
// String: include() is a PHP statement that loads 'databaseconnection.php', a file that defines the $conn MySQLi connection object.
// Loads the database connection settings to enable activity logging in the International Bus Booking System’s database.
include('databaseconnection.php');

// Function definition: Activity logging function.
// Parameters: $description (string, activity details), $whoDidIt (string, user identifier), $role (string, user’s role). No return value.
// Defines a function to record user activities (e.g., booking actions) in the database for auditing and tracking.
function logActivity($description, $whoDidIt, $role) {
    // Global variable: Database connection access.
    // Object: global is a PHP keyword that imports $conn, the MySQLi connection object from databaseconnection.php, into the function’s scope.
    // Allows the function to use the existing database connection for logging activities.
    global $conn;

    // Variable: Current date storage.
    // String: date('Y-m-d') is a PHP built-in function that formats the current date as YYYY-MM-DD (e.g., 2025-07-24).
    // Captures the date when the activity occurs for accurate record-keeping.
    $currentDate = date('Y-m-d');

    // Variable: Current time storage.
    // String: date('H:i:s') is a PHP built-in function that formats the current time as HH:MM:SS (e.g., 12:17:00).
    // Captures the time when the activity occurs to provide a precise timestamp.
    $currentTime = date('H:i:s');

    // Variable: SQL INSERT query string.
    // String: Defines a query to insert Description, Date, Time, WhoDidIt, and Role into the 'activity' table, using direct variable substitution for $description, $currentDate, $currentTime, $whoDidIt, and $role.
    // Records the activity details in the database for tracking user actions like bookings or payments.
    $sql = "INSERT INTO activity (Description, Date, Time, WhoDidIt, Role) 
            VALUES ('$description', '$currentDate', '$currentTime', '$whoDidIt', '$role')";

    // Conditional statement: Logic to execute the SQL query.
    // Boolean check: $conn->query($sql) is a MySQLi method that executes the SQL query, returning TRUE on success or FALSE on failure, compared to TRUE.
    // Attempts to log the activity in the database, handling errors if the query fails.
    if ($conn->query($sql) === TRUE) {
        // No action: Successful query execution.
        // Indicates the activity was logged successfully, with no output as per the function’s design.
    } else {
        // Function call: Log error message function.
        // String: error_log() is a PHP built-in function that writes "Error logging activity: " concatenated with $conn->error, a MySQLi property with the error message, to the server’s error log (e.g., C:/Apache24/logs/php_errors.log).
        // Records query failures in the server log for debugging without affecting the user experience.
        error_log("Error logging activity: " . $conn->error);
    }

    // Comment: Note on connection handling.
    // String: Explains that the database connection ($conn) is not closed.
    // Keeps the connection open for reuse by other parts of the application, as it’s managed globally.
    // Note: Connection is not closed to allow reuse across the application.
}
?>