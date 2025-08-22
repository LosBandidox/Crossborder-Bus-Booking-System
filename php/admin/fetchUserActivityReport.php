<?php
// Function call: Starts output buffering to capture all output.
// String: ob_start() is a PHP built-in function that begins capturing all output (e.g., text or error messages) sent to the browser, storing it in a buffer instead of sending it immediately.
// Prevents unwanted output, such as error messages, from corrupting the JSON response sent to the client’s admin dashboard in the International Bus Booking System.
ob_start();

// Function call: Sets the HTTP response header to specify JSON output.
// String: header() is a PHP built-in function that sets an HTTP header, here setting 'Content-Type: application/json', which informs the browser that the response is JSON data.
// Ensures the client’s JavaScript can parse the activity log data as JSON, maintaining compatibility with web standards for the dashboard.
header('Content-Type: application/json');

// Function call: Configures error reporting to capture all issues.
// String: error_reporting() is a PHP built-in function that sets the error reporting level to E_ALL, a constant that enables logging of all errors, warnings, and notices.
// Captures all potential issues during script execution for debugging, ensuring problems are logged without affecting the user experience.
error_reporting(E_ALL);

// Function call: Disables error display in the browser.
// String: ini_set() is a PHP built-in function that sets the 'display_errors' configuration to 0, preventing errors from appearing in the browser output.
// Keeps error messages hidden from users to maintain a clean JSON response and enhance security by not exposing server details.
ini_set('display_errors', 0);

// Function call: Enables error logging to a file.
// String: ini_set() is a PHP built-in function that sets the 'log_errors' configuration to 1, turning on error logging to a specified file.
// Records errors in a log file for developers to review, aiding in debugging without disrupting the dashboard’s functionality.
ini_set('log_errors', 1);

// Function call: Specifies the error log file location.
// String: ini_set() is a PHP built-in function that sets the 'error_log' configuration to 'C:/Apache24/logs/php_errors.log', defining the file path for error logs.
// Centralizes error logs in a specific file on the server, making it easier to track and resolve issues in the bus booking system.
ini_set('error_log', 'C:/Apache24/logs/php_errors.log');

// Function call: Sets the server’s default time zone.
// String: date_default_timezone_set() is a PHP built-in function that sets the time zone to 'Africa/Nairobi' (East Africa Time, UTC+3).
// Ensures all date and time operations, like filtering activity logs or logging errors, use the correct time zone for consistency in the Kenyan context.
date_default_timezone_set('Africa/Nairobi');

// Try-catch block: Handles errors during database connection setup.
// Structure: try contains code to include the database connection file; catch captures any Exception object (stored in $e) thrown due to errors like file not found or connection failures.
// Manages potential issues when connecting to the database, ensuring a clean JSON error response is sent to the client.
try {
    // Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to query activity logs for the admin dashboard.
    include('../databaseconnection.php');

    // Conditional statement: Checks if the database connection was successful.
// Boolean check: Tests if $conn is null or if $conn->connect_error, a MySQLi property containing error details, indicates a connection failure.
// Stops the script with an error response if the database is unreachable, ensuring reliable data retrieval.
    if (!$conn || $conn->connect_error) {
        // Function call: Discards buffered output to ensure a clean response.
// String: ob_end_clean() is a PHP built-in function that clears all data in the output buffer, discarding any unintended output.
// Prevents stray text from corrupting the JSON error message sent to the client.
        ob_end_clean();
        // Output statement: Sends a JSON error response to the client.
// String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['response_status' => 'error', 'error' => 'Database connection failed: ' . $conn->connect_error] to a JSON string, including the connection error details.
// Informs the client’s JavaScript of a database connection failure, enabling error handling on the dashboard.
        echo json_encode(['response_status' => 'error', 'error' => 'Database connection failed: ' . $conn->connect_error]);
        // Function call: Terminates script execution immediately.
// String: exit() is a PHP built-in function that stops the script from running further.
// Halts processing to prevent attempts to query an unavailable database.
        exit;
    }
} catch (Exception $e) {
    // Function call: Discards buffered output to ensure a clean response.
// String: ob_end_clean() is a PHP built-in function that clears all data in the output buffer.
// Prevents stray text from corrupting the JSON error message sent to the client.
    ob_end_clean();
    // Output statement: Sends a JSON error response to the client.
// String: echo outputs text; json_encode() converts an array ['response_status' => 'error', 'error' => $e->getMessage()] to a JSON string, where getMessage() is a method that retrieves the exception’s error message (e.g., file not found).
// Informs the client of a general error, such as a missing databaseconnection.php file, for debugging purposes.
    echo json_encode(['response_status' => 'error', 'error' => $e->getMessage()]);
    // Function call: Terminates script execution immediately.
// String: exit() is a PHP built-in function that stops the script from running further.
// Halts processing to prevent further errors after an exception is caught.
    exit;
}

// Variable: Stores the start date for filtering activity logs.
// String or null: isset() is a PHP built-in function that checks if $_GET['start'], a superglobal array element from the URL query string, exists and is not null; $conn->real_escape_string() sanitizes the input by escaping special characters to prevent SQL injection; defaults to null if unset.
// Captures the start date from the client’s request to filter activity logs, ensuring safe database queries.
$startDate = isset($_GET['start']) ? $conn->real_escape_string($_GET['start']) : null;

// Variable: Stores the end date for filtering activity logs.
// String or null: isset() checks if $_GET['end'], a superglobal array element from the URL query string, exists and is not null; $conn->real_escape_string() sanitizes the input; defaults to null if unset.
// Captures the end date from the client’s request to filter activity logs, ensuring secure query execution.
$endDate = isset($_GET['end']) ? $conn->real_escape_string($_GET['end']) : null;

// Variable: Stores the SQL filter clause for activity dates.
// String: Initialized as an empty string, later set to a WHERE clause if both start and end dates are provided.
// Holds the condition to filter activity log records by Date within the specified date range for targeted reporting.
$activityDateFilter = "";

// Conditional statement: Builds an activity date filter clause if both dates are provided.
// Boolean check: Tests if $startDate and $endDate are both non-null, ensuring a valid date range.
// Creates a WHERE clause to apply date filtering to activity log queries for accurate reporting.
if ($startDate && $endDate) {
    // String assignment: Constructs a WHERE clause for activity date filtering.
// String: Concatenates " WHERE DATE(Date) BETWEEN '$startDate' AND '$endDate'" to filter activity logs by Date, where DATE() extracts the date part of a datetime field.
// Applies the date range filter to limit activity log data to the client-specified period, ensuring relevant audit reports.
    $activityDateFilter = " WHERE DATE(Date) BETWEEN '$startDate' AND '$endDate'";
}

// Variable: SQL query string for activity logs.
// String: Defines a query to select ActivityID, Description, Date, Time, WhoDidIt, and Role from the 'activity' table, applying $activityDateFilter, and sorting by Date and Time in descending order (DESC).
// Retrieves user activity logs, such as login attempts or booking actions, to display on the admin dashboard for audit purposes.
$sql = "
    SELECT 
        ActivityID, Description, Date, Time, WhoDidIt, Role
    FROM 
        activity
    $activityDateFilter
    ORDER BY 
        Date DESC, Time DESC
";

// Variable: Stores the result of the activity log query.
// Object: $conn->query($sql) is a MySQLi method that executes the SQL query and returns a result object containing activity log records or false if the query fails.
// Holds the retrieved activity log data for processing into the JSON response.
$result = $conn->query($sql);

// Variable: Initializes storage for activity log data.
// Array: Creates an empty array to hold associative arrays, each representing an activity log record with fields like ActivityID and Description.
// Prepares to collect activity log details for inclusion in the JSON response.
$activityData = [];

// Variable: Initializes the response data structure for activity logs.
// Array: Creates an associative array with 'response_status' set to 'success', 'applied_filters' as a subarray containing $startDate and $endDate, 'row_count' set to 0, and 'data' as a reference to $activityData using the & operator to link the arrays.
// Organizes the JSON response with metadata (status, filters, count) and activity log data for dashboard display.
$responseData = [
    'response_status' => 'success',
    'applied_filters' => ['startDate' => $startDate, 'endDate' => $endDate],
    'row_count' => 0,
    'data' => &$activityData
];

// Conditional statement: Handles activity log query outcomes.
// Boolean and integer checks: Tests if $result is false (query failure) or if $result->num_rows, a MySQLi property, is greater than 0 (rows returned).
// Processes activity log data if available or sets an error message if the query fails.
if ($result === false) {
    // Array operation: Updates the response with error details.
// Strings: Sets $responseData['response_status'] to 'error' and $responseData['error'] to $conn->error, a MySQLi property containing the query error message.
// Notifies the client of a query failure, providing error details for debugging on the dashboard.
    $responseData['response_status'] = 'error';
    $responseData['error'] = $conn->error;
} elseif ($result->num_rows > 0) {
    // Loop: Iterates over activity log query results.
// Array: $result->fetch_assoc() is a MySQLi method that retrieves each row as an associative array with keys ActivityID, Description, Date, Time, WhoDidIt, and Role, repeated using a while loop until no rows remain.
// Processes each activity log record to include in the JSON response for the dashboard.
    while ($row = $result->fetch_assoc()) {
        // Array operation: Appends an activity log record to the response.
// Array: Adds $row, containing ActivityID, Description, Date, Time, WhoDidIt, and Role, to $activityData.
// Collects activity log data for display on the dashboard’s audit log section.
        $activityData[] = $row;
        // Array operation: Increments the count of activity log records.
// Integer: Increments $responseData['row_count'] by 1 for each row processed.
// Tracks the number of activity log records for metadata in the JSON response.
        $responseData['row_count']++;
    }
}

// Function call: Discards buffered output and sends the final JSON response.
// String: ob_end_clean() is a PHP built-in function that clears all data in the output buffer; echo outputs text; json_encode() converts $responseData to a JSON string containing activity logs and metadata.
// Sends the complete set of activity log records and metadata (status, filters, count) to the client for dashboard display.
ob_end_clean();

// Output statement: Sends a JSON-encoded response.
// Function call: Uses json_encode($responseData) to convert the response array to JSON.
// Returns activity log data and metadata to the client.
echo json_encode($responseData);

// Method call: Closes the database connection.
// String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open after queries, maintaining system efficiency and resource management.
$conn->close();
?>