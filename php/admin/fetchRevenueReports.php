<?php
// Function call: Starts output buffering to capture all output.
// String: ob_start() is a PHP built-in function that begins capturing all output (e.g., text or error messages) sent to the browser, storing it in a buffer instead of sending it immediately.
// Prevents unwanted output, such as error messages, from corrupting the JSON response sent to the client’s dashboard in the International Bus Booking System.
ob_start();

// Function call: Sets the HTTP response header to specify JSON output.
// String: header() is a PHP built-in function that sets an HTTP header, here setting 'Content-Type: application/json', which informs the browser that the response is JSON data.
// Ensures the client’s JavaScript can parse the revenue and report data as JSON, maintaining compatibility with web standards for the admin dashboard.
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
// Ensures all date and time operations, like filtering payments or logging errors, use the correct time zone for consistency in the Kenyan context.
date_default_timezone_set('Africa/Nairobi');

// Try-catch block: Handles errors during database connection setup.
// Structure: try contains code to include the database connection file; catch captures any Exception object (stored in $e) thrown due to errors like file not found or connection failures.
// Manages potential issues when connecting to the database, ensuring a clean JSON error response is sent to the client.
try {
    // Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to query revenue and report data for the admin dashboard.
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

// Variable: Stores the start date for filtering reports.
// String or null: isset() is a PHP built-in function that checks if $_GET['start'], a superglobal array element from the URL query string, exists and is not null; $conn->real_escape_string() sanitizes the input by escaping special characters to prevent SQL injection; defaults to null if unset.
// Captures the start date from the client’s request to filter revenue and report data, ensuring safe database queries.
$startDate = isset($_GET['start']) ? $conn->real_escape_string($_GET['start']) : null;

// Variable: Stores the end date for filtering reports.
// String or null: isset() checks if $_GET['end'], a superglobal array element from the URL query string, exists and is not null; $conn->real_escape_string() sanitizes the input; defaults to null if unset.
// Captures the end date from the client’s request to filter revenue and report data, ensuring secure query execution.
$endDate = isset($_GET['end']) ? $conn->real_escape_string($_GET['end']) : null;

// Variable: Queries the database for the current server time.
// Object: $conn->query() is a MySQLi method that executes the SQL query "SELECT NOW() AS db_time", which returns the current database server time as a datetime value.
// Retrieves the database’s current time to verify timezone alignment with the server for debugging purposes.
$dbTimeResult = $conn->query("SELECT NOW() AS db_time");

// Variable: Stores the database server time.
// String: $dbTimeResult->fetch_assoc() is a MySQLi method that retrieves the query result as an associative array; ['db_time'] accesses the datetime value, defaulting to 'unknown' if the query fails.
// Stores the database time for inclusion in the response, helping confirm consistent timezone handling (Africa/Nairobi).
$dbTime = $dbTimeResult ? $dbTimeResult->fetch_assoc()['db_time'] : 'unknown';

// Variable: Initializes the response data structure for reports.
// Array: Creates an associative array with 'response_status' set to 'success', 'applied_filters' as a subarray containing $startDate and $endDate, 'server_timezone' from date_default_timezone_get() (a PHP function returning the current timezone), and 'database_time' set to $dbTime.
// Organizes revenue and report data with metadata (status, filters, timezone) for the JSON response, aiding dashboard display and debugging.
$revenueData = [
    'response_status' => 'success',
    'applied_filters' => ['startDate' => $startDate, 'endDate' => $endDate],
    'server_timezone' => date_default_timezone_get(),
    'database_time' => $dbTime
];

// Variable: Stores the SQL filter clause for payment dates.
// String: Initialized as an empty string, later set to a WHERE clause if both start and end dates are provided.
// Holds the condition to filter payment records by PaymentDate within the specified date range for targeted revenue reports.
$paymentDateFilter = "";

// Conditional statement: Builds a payment date filter clause if both dates are provided.
// Boolean check: Tests if $startDate and $endDate are both non-null, ensuring a valid date range.
// Creates a WHERE clause to apply date filtering to payment-related queries for accurate revenue analysis.
if ($startDate && $endDate) {
    // String assignment: Constructs a WHERE clause for payment date filtering.
// String: Concatenates " WHERE DATE(p.PaymentDate) BETWEEN '$startDate' AND '$endDate'" to filter payments by PaymentDate, where DATE() extracts the date part of a datetime field.
// Applies the date range filter to limit payment data to the client-specified period, ensuring relevant revenue reports.
    $paymentDateFilter = " WHERE DATE(p.PaymentDate) BETWEEN '$startDate' AND '$endDate'";
}

// Variable: Stores the SQL filter clause for booking dates.
// String: Initialized as an empty string, set to a WHERE clause if both dates are provided.
// Holds the condition to filter booking records by BookingDate within the specified date range for operational reports.
$bookingDateFilter = "";

// Conditional statement: Builds a booking date filter clause if both dates are provided.
// Boolean check: Tests if $startDate and $endDate are both non-null.
// Creates a WHERE clause to apply date filtering to booking-related queries for accurate statistics.
if ($startDate && $endDate) {
    // String assignment: Constructs a WHERE clause for booking date filtering.
// String: Concatenates " WHERE DATE(bd.BookingDate) BETWEEN '$startDate' AND '$endDate'" to filter bookings by BookingDate, using the DATE() function for date extraction.
// Applies the date range filter to limit booking data to the client-specified period, ensuring relevant operational reports.
    $bookingDateFilter = " WHERE DATE(bd.BookingDate) BETWEEN '$startDate' AND '$endDate'";
}

// Variable: Stores the SQL filter clause for schedule dates.
// String: Initialized as an empty string, set to a WHERE clause if both dates are provided.
// Holds the condition to filter schedule records by DepartureTime within the specified date range for fleet analysis.
$scheduleDateFilter = "";

// Conditional statement: Builds a schedule date filter clause if both dates are provided.
// Boolean check: Tests if $startDate and $endDate are both non-null.
// Creates a WHERE clause to apply date filtering to schedule-related queries for accurate utilization reports.
if ($startDate && $endDate) {
    // String assignment: Constructs a WHERE clause for schedule date filtering.
// String: Concatenates " WHERE DATE(s.DepartureTime) BETWEEN '$startDate' AND '$endDate'" to filter schedules by DepartureTime, using DATE() for date extraction.
// Applies the date range filter to limit schedule data to the client-specified period, ensuring relevant fleet usage reports.
    $scheduleDateFilter = " WHERE DATE(s.DepartureTime) BETWEEN '$startDate' AND '$endDate'";
}

// Variable: SQL query string for daily revenue summary.
// String: Defines a query to select DATE(p.PaymentDate) as PaymentDate and SUM(p.AmountPaid) as TotalRevenue from the 'paymentdetails' table, applying $paymentDateFilter, grouping by date, and sorting by PaymentDate in descending order (DESC).
// Aggregates total revenue per day to display daily financial performance on the admin dashboard.
$sqlSummary = "
    SELECT 
        DATE(p.PaymentDate) AS PaymentDate,
        SUM(p.AmountPaid) AS TotalRevenue
    FROM 
        paymentdetails p
    $paymentDateFilter
    GROUP BY 
        DATE(p.PaymentDate)
    ORDER BY 
        PaymentDate DESC
";

// Variable: Stores the result of the daily revenue query.
// Object: $conn->query($sqlSummary) is a MySQLi method that executes the SQL query and returns a result object containing daily revenue data or false if the query fails.
// Holds the retrieved daily revenue records for processing into the JSON response.
$resultSummary = $conn->query($sqlSummary);

// Array operation: Initializes storage for daily revenue data.
// Arrays: Adds 'summary' as an empty array, 'summary_row_count' as 0, and 'summary_error' as null to $revenueData.
// Prepares the response array to store daily revenue records, track their count, and handle potential query errors.
$revenueData['summary'] = [];
$revenueData['summary_row_count'] = 0;
$revenueData['summary_error'] = null;

// Conditional statement: Handles daily revenue query outcomes.
// Boolean and integer checks: Tests if $resultSummary is false (query failure) or if $resultSummary->num_rows, a MySQLi property, is greater than 0 (rows returned).
// Processes daily revenue data if available or sets an error message if the query fails.
if ($resultSummary === false) {
    // Array operation: Records the query error message.
// String: Sets $revenueData['summary_error'] to $conn->error, a MySQLi property containing the query error message.
// Stores the error details in the response to inform the client of a query failure for debugging.
    $revenueData['summary_error'] = $conn->error;
} elseif ($resultSummary->num_rows > 0) {
    // Loop: Iterates over daily revenue query results.
// Array: $resultSummary->fetch_assoc() is a MySQLi method that retrieves each row as an associative array with keys PaymentDate and TotalRevenue, repeated using a while loop until no rows remain.
// Processes each daily revenue record to include in the JSON response for the dashboard.
    while ($row = $resultSummary->fetch_assoc()) {
        // Array operation: Appends a daily revenue record to the response.
// Array: Adds $row, containing PaymentDate and TotalRevenue, to $revenueData['summary'].
// Collects daily revenue data for display on the dashboard’s financial summary section.
        $revenueData['summary'][] = $row;
        // Array operation: Increments the count of daily revenue records.
// Integer: Increments $revenueData['summary_row_count'] by 1 for each row processed.
// Tracks the number of daily revenue records for metadata in the JSON response.
        $revenueData['summary_row_count']++;
    }
}

// Variable: SQL query string for revenue by route.
// String: Defines a query to select RouteName, StartLocation, Destination, and SUM(p.AmountPaid) as TotalRevenue from 'paymentdetails', joined with 'bookingdetails', 'scheduleinformation', and 'route' tables, applying $paymentDateFilter, grouping by RouteID and route details, and sorting by TotalRevenue in descending order (DESC).
// Aggregates revenue per route to analyze route profitability on the admin dashboard.
$sqlByRoute = "
    SELECT 
        r.RouteName,
        r.StartLocation,
        r.Destination,
        SUM(p.AmountPaid) AS TotalRevenue
    FROM 
        paymentdetails p
    JOIN 
        bookingdetails bd ON p.BookingID = bd.BookingID
    JOIN 
        scheduleinformation s ON bd.ScheduleID = s.ScheduleID
    JOIN 
        route r ON s.RouteID = r.RouteID
    $paymentDateFilter
    GROUP BY 
        r.RouteID, r.RouteName, r.StartLocation, r.Destination
    ORDER BY 
        TotalRevenue DESC
";

// Variable: Stores the result of the revenue by route query.
// Object: $conn->query($sqlByRoute) is a MySQLi method that executes the SQL query and returns a result object containing route revenue data or false if the query fails.
// Holds the retrieved route revenue records for processing into the JSON response.
$resultByRoute = $conn->query($sqlByRoute);

// Array operation: Initializes storage for route revenue data.
// Arrays: Adds 'byRoute' as an empty array, 'byRoute_row_count' as 0, and 'byRoute_error' as null to $revenueData.
// Prepares the response array to store route revenue records, track their count, and handle potential query errors.
$revenueData['byRoute'] = [];
$revenueData['byRoute_row_count'] = 0;
$revenueData['byRoute_error'] = null;

// Conditional statement: Handles route revenue query outcomes.
// Boolean and integer checks: Tests if $resultByRoute is false (query failure) or if $resultByRoute->num_rows is greater than 0 (rows returned).
// Processes route revenue data if available or sets an error message if the query fails.
if ($resultByRoute === false) {
    // Array operation: Records the query error message.
// String: Sets $revenueData['byRoute_error'] to $conn->error, the query error message.
// Stores the error details in the response to inform the client of a query failure for debugging.
    $revenueData['byRoute_error'] = $conn->error;
} elseif ($resultByRoute->num_rows > 0) {
    // Loop: Iterates over route revenue query results.
// Array: $resultByRoute->fetch_assoc() retrieves each row as an associative array with keys RouteName, StartLocation, Destination, and TotalRevenue, repeated using a while loop.
// Processes each route revenue record to include in the JSON response for the dashboard.
    while ($row = $resultByRoute->fetch_assoc()) {
        // Array operation: Appends a route revenue record to the response.
// Array: Adds $row, containing RouteName, StartLocation, Destination, and TotalRevenue, to $revenueData['byRoute'].
// Collects route revenue data for display on the dashboard’s route profitability section.
        $revenueData['byRoute'][] = $row;
        // Array operation: Increments the count of route revenue records.
// Integer: Increments $revenueData['byRoute_row_count'] by 1 for each row processed.
// Tracks the number of route revenue records for metadata in the JSON response.
        $revenueData['byRoute_row_count']++;
    }
}

// Variable: SQL query string for revenue by payment mode.
// String: Defines a query to select PaymentMode and SUM(p.AmountPaid) as TotalRevenue from 'paymentdetails', applying $paymentDateFilter, grouping by PaymentMode, and sorting by TotalRevenue in descending order (DESC).
// Aggregates revenue per payment method (e.g., cash, card) to analyze payment trends on the admin dashboard.
$sqlByPaymentMode = "
    SELECT 
        p.PaymentMode,
        SUM(p.AmountPaid) AS TotalRevenue
    FROM 
        paymentdetails p
    $paymentDateFilter
    GROUP BY 
        p.PaymentMode
    ORDER BY 
        TotalRevenue DESC
";

// Variable: Stores the result of the revenue by payment mode query.
// Object: $conn->query($sqlByPaymentMode) is a MySQLi method that executes the SQL query and returns a result object containing payment mode revenue data or false if the query fails.
// Holds the retrieved payment mode revenue records for processing into the JSON response.
$resultByPaymentMode = $conn->query($sqlByPaymentMode);

// Array operation: Initializes storage for payment mode revenue data.
// Arrays: Adds 'byPaymentMode' as an empty array, 'byPaymentMode_row_count' as 0, and 'byPaymentMode_error' as null to $revenueData.
// Prepares the response array to store payment mode revenue records, track their count, and handle potential query errors.
$revenueData['byPaymentMode'] = [];
$revenueData['byPaymentMode_row_count'] = 0;
$revenueData['byPaymentMode_error'] = null;

// Conditional statement: Handles payment mode revenue query outcomes.
// Boolean and integer checks: Tests if $resultByPaymentMode is false (query failure) or if $resultByPaymentMode->num_rows is greater than 0 (rows returned).
// Processes payment mode revenue data if available or sets an error message if the query fails.
if ($resultByPaymentMode === false) {
    // Array operation: Records the query error message.
// String: Sets $revenueData['byPaymentMode_error'] to $conn->error, the query error message.
// Stores the error details in the response to inform the client of a query failure for debugging.
    $revenueData['byPaymentMode_error'] = $conn->error;
} elseif ($resultByPaymentMode->num_rows > 0) {
    // Loop: Iterates over payment mode revenue query results.
// Array: $resultByPaymentMode->fetch_assoc() retrieves each row as an associative array with keys PaymentMode and TotalRevenue, repeated using a while loop.
// Processes each payment mode revenue record to include in the JSON response for the dashboard.
    while ($row = $resultByPaymentMode->fetch_assoc()) {
        // Array operation: Appends a payment mode revenue record to the response.
// Array: Adds $row, containing PaymentMode and TotalRevenue, to $revenueData['byPaymentMode'].
// Collects payment mode revenue data for display on the dashboard’s payment trends section.
        $revenueData['byPaymentMode'][] = $row;
        // Array operation: Increments the count of payment mode revenue records.
// Integer: Increments $revenueData['byPaymentMode_row_count'] by 1 for each row processed.
// Tracks the number of payment mode revenue records for metadata in the JSON response.
        $revenueData['byPaymentMode_row_count']++;
    }
}

// Variable: SQL query string for revenue by nationality.
// String: Defines a query to select Nationality and SUM(p.AmountPaid) as TotalRevenue from 'paymentdetails', joined with 'bookingdetails' and 'customer' tables, applying $paymentDateFilter, grouping by Nationality, and sorting by TotalRevenue in descending order (DESC).
// Aggregates revenue per customer nationality to analyze demographic spending patterns on the admin dashboard.
$sqlByNationality = "
    SELECT 
        c.Nationality,
        SUM(p.AmountPaid) AS TotalRevenue
    FROM 
        paymentdetails p
    JOIN 
        bookingdetails bd ON p.BookingID = bd.BookingID
    JOIN 
        customer c ON bd.CustomerID = c.CustomerID
    $paymentDateFilter
    GROUP BY 
        c.Nationality
    ORDER BY 
        TotalRevenue DESC
";

// Variable: Stores the result of the revenue by nationality query.
// Object: $conn->query($sqlByNationality) is a MySQLi method that executes the SQL query and returns a result object containing nationality revenue data or false if the query fails.
// Holds the retrieved nationality revenue records for processing into the JSON response.
$resultByNationality = $conn->query($sqlByNationality);

// Array operation: Initializes storage for nationality revenue data.
// Arrays: Adds 'byNationality' as an empty array, 'byNationality_row_count' as 0, and 'byNationality_error' as null to $revenueData.
// Prepares the response array to store nationality revenue records, track their count, and handle potential query errors.
$revenueData['byNationality'] = [];
$revenueData['byNationality_row_count'] = 0;
$revenueData['byNationality_error'] = null;

// Conditional statement: Handles nationality revenue query outcomes.
// Boolean and integer checks: Tests if $resultByNationality is false (query failure) or if $resultByNationality->num_rows is greater than 0 (rows returned).
// Processes nationality revenue data if available or sets an error message if the query fails.
if ($resultByNationality === false) {
    // Array operation: Records the query error message.
// String: Sets $revenueData['byNationality_error'] to $conn->error, the query error message.
// Stores the error details in the response to inform the client of a query failure for debugging.
    $revenueData['byNationality_error'] = $conn->error;
} elseif ($resultByNationality->num_rows > 0) {
    // Loop: Iterates over nationality revenue query results.
// Array: $resultByNationality->fetch_assoc() retrieves each row as an associative array with keys Nationality and TotalRevenue, repeated using a while loop.
// Processes each nationality revenue record to include in the JSON response for the dashboard.
    while ($row = $resultByNationality->fetch_assoc()) {
        // Array operation: Appends a nationality revenue record to the response.
// Array: Adds $row, containing Nationality and TotalRevenue, to $revenueData['byNationality'].
// Collects nationality revenue data for display on the dashboard’s demographic analysis section.
        $revenueData['byNationality'][] = $row;
        // Array operation: Increments the count of nationality revenue records.
// Integer: Increments $revenueData['byNationality_row_count'] by 1 for each row processed.
// Tracks the number of nationality revenue records for metadata in the JSON response.
        $revenueData['byNationality_row_count']++;
    }
}

// Variable: SQL query string for booking status counts.
// String: Defines a query to select Status and COUNT(*) as Count from 'bookingdetails', applying $bookingDateFilter, grouping by Status, and sorting by Count in descending order (DESC).
// Counts bookings by status (e.g., Confirmed, Cancelled) to display operational metrics on the admin dashboard.
$sqlBookingStatus = "
    SELECT 
        bd.Status,
        COUNT(*) AS Count
    FROM 
        bookingdetails bd
    $bookingDateFilter
    GROUP BY 
        bd.Status
    ORDER BY 
        Count DESC
";

// Variable: Stores the result of the booking status query.
// Object: $conn->query($sqlBookingStatus) is a MySQLi method that executes the SQL query and returns a result object containing booking status data or false if the query fails.
// Holds the retrieved booking status records for processing into the JSON response.
$resultBookingStatus = $conn->query($sqlBookingStatus);

// Array operation: Initializes storage for booking status data.
// Arrays: Adds 'bookingStatus' as an empty array, 'bookingStatus_row_count' as 0, and 'bookingStatus_error' as null to $revenueData.
// Prepares the response array to store booking status records, track their count, and handle potential query errors.
$revenueData['bookingStatus'] = [];
$revenueData['bookingStatus_row_count'] = 0;
$revenueData['bookingStatus_error'] = null;

// Conditional statement: Handles booking status query outcomes.
// Boolean and integer checks: Tests if $resultBookingStatus is false (query failure) or if $resultBookingStatus->num_rows is greater than 0 (rows returned).
// Processes booking status data if available or sets an error message if the query fails.
if ($resultBookingStatus === false) {
    // Array operation: Records the query error message.
// String: Sets $revenueData['bookingStatus_error'] to $conn->error, the query error message.
// Stores the error details in the response to inform the client of a query failure for debugging.
    $revenueData['bookingStatus_error'] = $conn->error;
} elseif ($resultBookingStatus->num_rows > 0) {
    // Loop: Iterates over booking status query results.
// Array: $resultBookingStatus->fetch_assoc() retrieves each row as an associative array with keys Status and Count, repeated using a while loop.
// Processes each booking status record to include in the JSON response for the dashboard.
    while ($row = $resultBookingStatus->fetch_assoc()) {
        // Array operation: Appends a booking status record to the response.
// Array: Adds $row, containing Status and Count, to $revenueData['bookingStatus'].
// Collects booking status data for display on the dashboard’s operational metrics section.
        $revenueData['bookingStatus'][] = $row;
        // Array operation: Increments the count of booking status records.
// Integer: Increments $revenueData['bookingStatus_row_count'] by 1 for each row processed.
// Tracks the number of booking status records for metadata in the JSON response.
        $revenueData['bookingStatus_row_count']++;
    }
}

// Variable: SQL query string for bus utilization metrics.
// String: Defines a query to select BusNumber, COUNT(DISTINCT s.ScheduleID) as TripsScheduled, and COUNT(bd.BookingID) as SeatsBooked from 'bus', left joined with a subquery on 'scheduleinformation' (with $scheduleDateFilter) and 'bookingdetails', grouping by BusID and BusNumber, and sorting by TripsScheduled in descending order (DESC).
// Aggregates trips and booked seats per bus to analyze fleet utilization on the admin dashboard.
$sqlBusUtilization = "
    SELECT 
        b.BusNumber,
        COUNT(DISTINCT s.ScheduleID) AS TripsScheduled,
        COUNT(bd.BookingID) AS SeatsBooked
    FROM 
        bus b
    LEFT JOIN (
        SELECT ScheduleID, BusID
        FROM scheduleinformation s
        $scheduleDateFilter
    ) s ON b.BusID = s.BusID
    LEFT JOIN 
        bookingdetails bd ON s.ScheduleID = bd.ScheduleID
    GROUP BY 
        b.BusID, b.BusNumber
    ORDER BY 
        TripsScheduled DESC
";

// Variable: Stores the result of the bus utilization query.
// Object: $conn->query($sqlBusUtilization) is a MySQLi method that executes the SQL query and returns a result object containing bus utilization data or false if the query fails.
// Holds the retrieved bus utilization records for processing into the JSON response.
$resultBusUtilization = $conn->query($sqlBusUtilization);

// Array operation: Initializes storage for bus utilization data.
// Arrays: Adds 'busUtilization' as an empty array, 'busUtilization_row_count' as 0, and 'busUtilization_error' as null to $revenueData.
// Prepares the response array to store bus utilization records, track their count, and handle potential query errors.
$revenueData['busUtilization'] = [];
$revenueData['busUtilization_row_count'] = 0;
$revenueData['busUtilization_error'] = null;

// Conditional statement: Handles bus utilization query outcomes.
// Boolean and integer checks: Tests if $resultBusUtilization is false (query failure) or if $resultBusUtilization->num_rows is greater than 0 (rows returned).
// Processes bus utilization data if available or sets an error message if the query fails.
if ($resultBusUtilization === false) {
    // Array operation: Records the query error message.
// String: Sets $revenueData['busUtilization_error'] to $conn->error, the query error message.
// Stores the error details in the response to inform the client of a query failure for debugging.
    $revenueData['busUtilization_error'] = $conn->error;
} elseif ($resultBusUtilization->num_rows > 0) {
    // Loop: Iterates over bus utilization query results.
// Array: $resultBusUtilization->fetch_assoc() retrieves each row as an associative array with keys BusNumber, TripsScheduled, and SeatsBooked, repeated using a while loop.
// Processes each bus utilization record to include in the JSON response for the dashboard.
    while ($row = $resultBusUtilization->fetch_assoc()) {
        // Array operation: Appends a bus utilization record to the response.
// Array: Adds $row, containing BusNumber, TripsScheduled, and SeatsBooked, to $revenueData['busUtilization'].
// Collects bus utilization data for display on the dashboard’s fleet management section.
        $revenueData['busUtilization'][] = $row;
        // Array operation: Increments the count of bus utilization records.
// Integer: Increments $revenueData['busUtilization_row_count'] by 1 for each row processed.
// Tracks the number of bus utilization records for metadata in the JSON response.
        $revenueData['busUtilization_row_count']++;
    }
}

// Variable: SQL query string for route popularity.
// String: Defines a query to select RouteName, StartLocation, Destination, and COUNT(bd.BookingID) as Bookings from 'route', joined with 'scheduleinformation' and 'bookingdetails', applying $bookingDateFilter, grouping by RouteID and route details, and sorting by Bookings in descending order (DESC).
// Counts bookings per route to analyze route demand on the admin dashboard.
$sqlRoutePopularity = "
    SELECT 
        r.RouteName,
        r.StartLocation,
        r.Destination,
        COUNT(bd.BookingID) AS Bookings
    FROM 
        route r
    JOIN 
        scheduleinformation s ON r.RouteID = s.RouteID
    JOIN 
        bookingdetails bd ON s.ScheduleID = bd.ScheduleID
    $bookingDateFilter
    GROUP BY 
        r.RouteID, r.RouteName, r.StartLocation, r.Destination
    ORDER BY 
        Bookings DESC
";

// Variable: Stores the result of the route popularity query.
// Object: $conn->query($sqlRoutePopularity) is a MySQLi method that executes the SQL query and returns a result object containing route popularity data or false if the query fails.
// Holds the retrieved route popularity records for processing into the JSON response.
$resultRoutePopularity = $conn->query($sqlRoutePopularity);

// Array operation: Initializes storage for route popularity data.
// Arrays: Adds 'routePopularity' as an empty array, 'routePopularity_row_count' as 0, and 'routePopularity_error' as null to $revenueData.
// Prepares the response array to store route popularity records, track their count, and handle potential query errors.
$revenueData['routePopularity'] = [];
$revenueData['routePopularity_row_count'] = 0;
$revenueData['routePopularity_error'] = null;

// Conditional statement: Handles route popularity query outcomes.
// Boolean and integer checks: Tests if $resultRoutePopularity is false (query failure) or if $resultRoutePopularity->num_rows is greater than 0 (rows returned).
// Processes route popularity data if available or sets an error message if the query fails.
if ($resultRoutePopularity === false) {
    // Array operation: Records the query error message.
// String: Sets $revenueData['routePopularity_error'] to $conn->error, the query error message.
// Stores the error details in the response to inform the client of a query failure for debugging.
    $revenueData['routePopularity_error'] = $conn->error;
} elseif ($resultRoutePopularity->num_rows > 0) {
    // Loop: Iterates over route popularity query results.
// Array: $resultRoutePopularity->fetch_assoc() retrieves each row as an associative array with keys RouteName, StartLocation, Destination, and Bookings, repeated using a while loop.
// Processes each route popularity record to include in the JSON response for the dashboard.
    while ($row = $resultRoutePopularity->fetch_assoc()) {
        // Array operation: Appends a route popularity record to the response.
// Array: Adds $row, containing RouteName, StartLocation, Destination, and Bookings, to $revenueData['routePopularity'].
// Collects route popularity data for display on the dashboard’s route demand section.
        $revenueData['routePopularity'][] = $row;
        // Array operation: Increments the count of route popularity records.
// Integer: Increments $revenueData['routePopularity_row_count'] by 1 for each row processed.
// Tracks the number of route popularity records for metadata in the JSON response.
        $revenueData['routePopularity_row_count']++;
    }
}

// Variable: SQL query string for staff activity metrics.
// String: Defines a query to select Name as StaffName, Role, and COUNT(DISTINCT s.ScheduleID) as TripsAssigned from 'staff', left joined with a subquery on 'scheduleinformation' (with $scheduleDateFilter), filtering for 'Driver' or 'Co-driver' roles, grouping by StaffID, Name, and Role, and sorting by TripsAssigned in descending order (DESC).
// Counts trip assignments per staff member to analyze workforce activity on the admin dashboard.
$sqlDriversActivity = "
    SELECT 
        st.Name AS StaffName,
        st.Role,
        COUNT(DISTINCT s.ScheduleID) AS TripsAssigned
    FROM 
        staff st
    LEFT JOIN (
        SELECT ScheduleID, DriverID, CodriverID
        FROM scheduleinformation s
        $scheduleDateFilter
    ) s ON st.StaffID = s.DriverID OR st.StaffID = s.CodriverID
    WHERE 
        st.Role IN ('Driver', 'Co-driver')
    GROUP BY 
        st.StaffID, st.Name, st.Role
    ORDER BY 
        TripsAssigned DESC
";

// Variable: Stores the result of the staff activity query.
// Object: $conn->query($sqlDriversActivity) is a MySQLi method that executes the SQL query and returns a result object containing staff activity data or false if the query fails.
// Holds the retrieved staff activity records for processing into the JSON response.
$resultDriversActivity = $conn->query($sqlDriversActivity);

// Array operation: Initializes storage for staff activity data.
// Arrays: Adds 'driversActivity' as an empty array, 'driversActivity_row_count' as 0, and 'driversActivity_error' as null to $revenueData.
// Prepares the response array to store staff activity records, track their count, and handle potential query errors.
$revenueData['driversActivity'] = [];
$revenueData['driversActivity_row_count'] = 0;
$revenueData['driversActivity_error'] = null;

// Conditional statement: Handles staff activity query outcomes.
// Boolean and integer checks: Tests if $resultDriversActivity is false (query failure) or if $resultDriversActivity->num_rows is greater than 0 (rows returned).
// Processes staff activity data if available or sets an error message if the query fails.
if ($resultDriversActivity === false) {
    // Array operation: Records the query error message.
// String: Sets $revenueData['driversActivity_error'] to $conn->error, the query error message.
// Stores the error details in the response to inform the client of a query failure for debugging.
    $revenueData['driversActivity_error'] = $conn->error;
} elseif ($resultDriversActivity->num_rows > 0) {
    // Loop: Iterates over staff activity query results.
// Array: $resultDriversActivity->fetch_assoc() retrieves each row as an associative array with keys StaffName, Role, and TripsAssigned, repeated using a while loop.
// Processes each staff activity record to include in the JSON response for the dashboard.
    while ($row = $resultDriversActivity->fetch_assoc()) {
        // Array operation: Appends a staff activity record to the response.
// Array: Adds $row, containing StaffName, Role, and TripsAssigned, to $revenueData['driversActivity'].
// Collects staff activity data for display on the dashboard’s workforce analysis section.
        $revenueData['driversActivity'][] = $row;
        // Array operation: Increments the count of staff activity records.
// Integer: Increments $revenueData['driversActivity_row_count'] by 1 for each row processed.
// Tracks the number of staff activity records for metadata in the JSON response.
        $revenueData['driversActivity_row_count']++;
    }
}

// Function call: Discards buffered output and sends the final JSON response.
// String: ob_end_clean() is a PHP built-in function that clears all data in the output buffer; echo outputs text; json_encode() converts $revenueData to a JSON string containing all report data and metadata.
// Sends the complete set of revenue and operational reports (daily revenue, route revenue, payment modes, nationalities, booking status, bus utilization, route popularity, staff activity) to the client for dashboard display.
ob_end_clean();
echo json_encode($revenueData);

// Method call: Closes the database connection.
// String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open after queries, maintaining system efficiency and resource management.
$conn->close();
?>