<?php
// Function call: Output buffering start function.
// String: ob_start() is a PHP built-in function that starts capturing output sent to the browser.
// Prevents stray output (e.g., errors or warnings) from corrupting the JSON response sent to the client.
ob_start();

// Function call: HTTP header setting function.
// String: header() is a PHP built-in function that sets an HTTP response header, here setting 'Content-Type: application/json'.
// Sets the response format to JSON, ensuring client-side JavaScript can process the maintenance records correctly.
header('Content-Type: application/json');

// Function call: Error reporting configuration function.
// String: error_reporting() is a PHP built-in function that sets the error reporting level to E_ALL, a constant capturing all errors and warnings.
// Enables logging of all errors and warnings for debugging without displaying them to users.
error_reporting(E_ALL);

// Function call: PHP configuration setting function.
// String: ini_set() is a PHP built-in function that sets 'display_errors' to 0, disabling error output to the browser.
// Prevents error messages from appearing in the browser, ensuring a clean JSON response.
ini_set('display_errors', 0);

// Function call: PHP configuration setting function.
// String: ini_set() is a PHP built-in function that sets 'log_errors' to 1, enabling error logging.
// Ensures errors are recorded in a log file for later review, improving debugging.
ini_set('log_errors', 1);

// Function call: PHP configuration setting function.
// String: ini_set() is a PHP built-in function that sets 'error_log' to 'C:/Apache24/logs/php_errors.log', specifying the log file path.
// Directs error logs to a specific file for centralized error tracking in the server environment.
ini_set('error_log', 'C:/Apache24/logs/php_errors.log');

// Function call: Time zone setting function.
// String: date_default_timezone_set() is a PHP built-in function that sets the default timezone to 'Africa/Nairobi' (EAT, UTC+3).
// Ensures consistent datetime handling for database queries and logging in the East Africa Time zone.
date_default_timezone_set('Africa/Nairobi');

// Try-catch block: Exception handling structure.
// Structure: try contains the database connection code; catch captures any Exception object thrown, storing it in $e.
// Handles errors during database connection setup, ensuring a clean JSON error response.
try {
    // Include statement: File inclusion directive.
// String: include is a PHP statement that loads '../databaseconnection.php', a file that defines the $conn MySQLi connection object.
// Loads the database connection settings to fetch maintenance records from the International Bus Booking System’s database.
    include('../databaseconnection.php');

    // Conditional statement: Logic to check database connection status.
// Boolean check: Tests if $conn is null or if $conn->connect_error (a MySQLi property) indicates a connection failure.
// Stops the script with a JSON error if the database connection fails, ensuring reliable execution.
    if (!$conn || $conn->connect_error) {
        // Function call: Output buffer cleanup function.
// String: ob_end_clean() is a PHP built-in function that discards all buffered output.
// Ensures no stray output corrupts the JSON response sent to the client.
        ob_end_clean();
        // Output statement: JSON error response output.
// String: json_encode() is a PHP built-in function that converts an array ['response_status' => 'error', 'error' => 'Database connection failed: ' . $conn->connect_error] to a JSON string.
// Informs the client of a database connection failure with details for debugging.
        echo json_encode(['response_status' => 'error', 'error' => 'Database connection failed: ' . $conn->connect_error]);
        // Function call: Script termination function.
// String: exit is a PHP built-in function that stops script execution.
// Halts the script to prevent further processing after a connection error.
        exit;
    }
} catch (Exception $e) {
    // Function call: Output buffer cleanup function.
// String: ob_end_clean() is a PHP built-in function that discards all buffered output.
// Ensures no stray output corrupts the JSON response sent to the client.
    ob_end_clean();
    // Output statement: JSON error response output.
// String: json_encode() converts an array ['response_status' => 'error', 'error' => $e->getMessage()] to a JSON string, where getMessage() retrieves the exception message.
// Informs the client of a general error (e.g., file inclusion failure) during connection setup.
    echo json_encode(['response_status' => 'error', 'error' => $e->getMessage()]);
    // Function call: Script termination function.
// String: exit is a PHP built-in function that stops script execution.
// Halts the script to prevent further processing after an error.
    exit;
}

// Variable: Start date filter storage.
// String or null: $_GET['start'] is a superglobal array element from the query string, escaped with $conn->real_escape_string() to sanitize input, or null if unset.
// Captures the start date for filtering maintenance records, ensuring safe database input.
$startDate = isset($_GET['start']) ? $conn->real_escape_string($_GET['start']) : null;

// Variable: End date filter storage.
// String or null: $_GET['end'] is a superglobal array element from the query string, escaped with $conn->real_escape_string() to sanitize input, or null if unset.
// Captures the end date for filtering maintenance records, ensuring safe database input.
$endDate = isset($_GET['end']) ? $conn->real_escape_string($_GET['end']) : null;

// Variable: SQL date filter clause storage.
// String: Initialized as an empty string, later set to a WHERE clause if dates are provided.
// Holds the SQL condition to filter maintenance records by ServiceDate within a date range.
$maintenanceDateFilter = "";

// Conditional statement: Logic to build date filter clause.
// Boolean check: Tests if both $startDate and $endDate are non-null.
// Adds a WHERE clause to filter maintenance records by date range if both dates are provided.
if ($startDate && $endDate) {
    // String assignment: Constructs a WHERE clause for date filtering.
// String: Concatenates " WHERE DATE(ServiceDate) BETWEEN '$startDate' AND '$endDate'" to filter ServiceDate within the specified range.
// Applies the date range filter to the SQL query for targeted maintenance record retrieval.
    $maintenanceDateFilter = " WHERE DATE(ServiceDate) BETWEEN '$startDate' AND '$endDate'";
}

// Variable: SQL SELECT query string.
// String: Defines a query to select MaintenanceID, BusID, ServiceDone, ServiceDate, Cost, LSD, NSD, and TechnicianID from the 'maintenance' table, appending $maintenanceDateFilter and sorting by ServiceDate in descending order (DESC).
// Retrieves maintenance records, optionally filtered by date, for display or reporting.
$sql = "
    SELECT 
        MaintenanceID, BusID, ServiceDone, ServiceDate, Cost, LSD, NSD, TechnicianID
    FROM 
        maintenance
    $maintenanceDateFilter
    ORDER BY 
        ServiceDate DESC
";

// Variable: Query result storage.
// Object: $conn->query($sql) is a MySQLi method that executes the SQL query and returns a result object or false on failure.
// Stores the retrieved maintenance records for processing.
$result = $conn->query($sql);

// Variable: Maintenance data storage.
// Array: Initializes an empty array to store maintenance records as associative arrays.
// Collects maintenance details for inclusion in the JSON response.
$maintenanceData = [];

// Variable: Response data structure.
// Array: Initializes an associative array with 'response_status' set to 'success', 'applied_filters' containing $startDate and $endDate, 'row_count' set to 0, and 'data' referencing $maintenanceData.
// Organizes the JSON response with metadata (status, filters, count) and maintenance data.
$responseData = [
    'response_status' => 'success',
    'applied_filters' => ['startDate' => $startDate, 'endDate' => $endDate],
    'row_count' => 0,
    'data' => &$maintenanceData
];

// Conditional statement: Logic to handle query results or errors.
// Boolean and integer checks: Tests if $result is false (query failure) or if $result->num_rows > 0 (rows returned).
// Processes results if available or sets an error status if the query fails.
if ($result === false) {
    // Array operation: Updates response status and error message.
// Strings: Sets $responseData['response_status'] to 'error' and $responseData['error'] to $conn->error, a MySQLi property with the error message.
// Indicates a query failure in the JSON response for client-side handling.
    $responseData['response_status'] = 'error';
    $responseData['error'] = $conn->error;
} elseif ($result->num_rows > 0) {
    // Loop: Iterates over query result rows.
// Object: $result->fetch_assoc() is a MySQLi method that retrieves each row as an associative array, assigning it to $row until no rows remain.
// Processes each maintenance record to build the response data.
    while ($row = $result->fetch_assoc()) {
        // Array operation: Appends maintenance row to data array.
// Array: Adds $row (containing MaintenanceID, BusID, ServiceDone, ServiceDate, Cost, LSD, NSD, TechnicianID) to $maintenanceData.
// Collects maintenance details for the JSON response.
        $maintenanceData[] = $row;
        // Array operation: Increments row count.
// Integer: Increments $responseData['row_count'] by 1 for each row.
// Tracks the number of maintenance records retrieved for metadata.
        $responseData['row_count']++;
    }
}

// Function call: Output buffer cleanup function.
// String: ob_end_clean() is a PHP built-in function that discards all buffered output.
// Ensures no stray output corrupts the JSON response sent to the client.
ob_end_clean();

// Output statement: JSON response output.
// String: json_encode() is a PHP built-in function that converts $responseData to a JSON string.
// Sends the maintenance data and metadata (status, filters, count) to the client for processing.
echo json_encode($responseData);

// Method call: Connection closure function.
// String: close() is a MySQLi method that closes the database connection ($conn).
// Frees database resources after all operations, ensuring no connections remain open.
$conn->close();
?>