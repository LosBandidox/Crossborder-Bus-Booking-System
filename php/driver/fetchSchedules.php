<?php
// Function call: Starts or resumes a PHP session to access session data.
// String: session_start() is a PHP built-in function that initializes a session or resumes an existing one, enabling access to session variables like the user’s email.
// Enables authentication by checking the logged-in staff member’s email, ensuring only authorized users access schedules in the International Bus Booking System.
session_start();

// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to query staff and schedule information for the staff dashboard.
include '../databaseconnection.php';

// Conditional statement: Checks if the user is logged in by verifying the session email.
// Boolean check: isset() is a PHP built-in function that tests if $_SESSION["Email"], a superglobal session variable, exists and is not null.
// Ensures the user is authenticated before accessing schedules, preventing unauthorized access.
if (!isset($_SESSION["Email"])) {
    // Output statement: Sends a JSON error response to the client.
// String: echo outputs text; json_encode() is a PHP built-in function that converts an array ["status" => "error", "message" => "Please log in"] to a JSON string, a data format for web communication.
// Informs the client’s JavaScript that login is required, enabling the dashboard to redirect to a login page.
    echo json_encode(["status" => "error", "message" => "Please log in"]);
    // Function call: Terminates script execution immediately.
// String: exit() is a PHP built-in function that stops the script from running further.
// Halts processing to prevent unauthorized access to schedule data.
    exit();
}

// Variable: Stores the logged-in staff member’s email from the session.
// String: $_SESSION["Email"] is a superglobal session variable containing the email of the authenticated staff member (e.g., "driver@example.com").
// Identifies the staff member to retrieve their ID and role for querying schedules.
$email = $_SESSION["Email"];

// Variable: SQL query string to retrieve staff details by email.
// String: Defines a SELECT query to fetch StaffID and Role from the staff table, using a placeholder (?) for secure email binding.
// Retrieves the staff member’s ID and role (e.g., 'Driver' or 'Co-Driver') to customize schedule queries based on their role.
$sql = "SELECT StaffID, Role FROM staff WHERE Email = ?";

// Variable: Stores the prepared statement for the staff query.
// Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with a placeholder, returning a statement object for binding and execution.
// Prepares the query to safely retrieve staff details, reducing the risk of SQL injection.
$stmt = $conn->prepare($sql);

// Conditional statement: Checks if the staff query preparation was successful.
// Boolean check: Tests if $stmt is false, indicating a preparation failure (e.g., syntax error or database issue).
// Stops the script with an error message if preparation fails, ensuring robust error handling.
if ($stmt === false) {
    // Function call: Terminates script execution with an error message.
// String: die() is a PHP built-in function that outputs a message ("Error preparing statement: " . $conn->error, where $conn->error is a MySQLi property with the error details) and stops the script.
// Provides detailed error information for debugging if the query cannot be prepared.
    die("Error preparing statement: " . $conn->error);
}

// Method call: Binds the email to the prepared statement’s placeholder.
// String: bind_param("s", $email) is a MySQLi method that binds $email as a string ('s') to the placeholder in the query, sanitizing the input.
// Securely links the email to the query, preventing SQL injection by treating the input as data, not code.
$stmt->bind_param("s", $email);

// Method call: Executes the staff query.
// String: execute() is a MySQLi method that runs the prepared statement, querying the staff table for the matching email.
// Retrieves the staff member’s ID and role from the database.
$stmt->execute();

// Variable: Stores the result set from the staff query.
// Object: $stmt->get_result() is a MySQLi method that retrieves the query results as a result object, containing rows matching the email.
// Holds the staff data for further processing, such as fetching StaffID and Role.
$result = $stmt->get_result();

// Conditional statement: Checks if no staff record was found for the email.
// Integer check: $result->num_rows is a MySQLi property that indicates the number of rows returned; zero means no matching staff record.
// Stops the script if no staff is found, preventing invalid schedule queries.
if ($result->num_rows == 0) {
    // Output statement: Sends a JSON error response to the client.
// String: echo outputs text; json_encode() converts an array ["status" => "no_staff"] to a JSON string.
// Informs the client’s JavaScript that no staff record exists, enabling error handling (e.g., displaying an error message).
    echo json_encode(["status" => "no_staff"]);
    // Method call: Frees the prepared statement resources.
// String: close() is a MySQLi method that releases the prepared statement ($stmt), freeing memory.
// Ensures efficient resource management after the query is complete.
    $stmt->close();
    // Method call: Closes the database connection.
// String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open, maintaining system efficiency.
    $conn->close();
    // Function call: Terminates script execution immediately.
// String: exit() stops the script from running further.
// Halts processing to prevent further actions for an invalid staff member.
    exit();
}

// Variable: Stores the staff data from the query result.
// Array: $result->fetch_assoc() is a MySQLi method that retrieves a single row as an associative array with keys StaffID and Role.
// Extracts the staff member’s ID and role for use in subsequent schedule and statistics queries.
$staff = $result->fetch_assoc();

// Variable: Stores the Staff ID from the query result.
// Integer: $staff["StaffID"] is the unique identifier of the staff member, used to filter schedules.
// Identifies the specific staff member for querying their assigned schedules.
$staffID = $staff["StaffID"];

// Variable: Stores the staff member’s role from the query result.
// String: $staff["Role"] contains the role (e.g., 'Driver' or 'Co-Driver'), determining which schedule field to query (DriverID or CodriverID).
// Customizes the schedule query based on the staff member’s role.
$role = $staff["Role"];

// Variable: Initializes the statistics array for the staff member.
// Array: Creates an associative array with keys totalTrips (integer), totalHours (float), and upcomingTrips (integer), all initialized to zero.
// Prepares to store aggregated statistics (trips, hours, upcoming trips) for the staff dashboard.
$stats = [
    "totalTrips" => 0,
    "totalHours" => 0.00,
    "upcomingTrips" => 0
];

// Variable: Defines the SQL condition based on the staff member’s role.
// String: Uses a ternary operator to set $condition to "s.DriverID = ?" if $role is 'Driver', or "s.CodriverID = ?" if $role is 'Co-Driver'.
// Tailors the schedule queries to filter by the appropriate role-specific ID field (DriverID or CodriverID).
$condition = ($role === "Driver") ? "s.DriverID = ?" : "s.CodriverID = ?";

// Variable: SQL query string to count total trips assigned to the staff member.
// String: Defines a SELECT query with COUNT(*) as count from scheduleinformation, using $condition to filter by DriverID or CodriverID.
// Aggregates the total number of trips assigned to the staff member for display on the dashboard.
$sql = "SELECT COUNT(*) as count FROM scheduleinformation s WHERE $condition";

// Variable: Stores the prepared statement for the total trips query.
// Object: $conn->prepare($sql) compiles the SQL query with a placeholder, returning a statement object.
// Prepares the query to safely count trips, reducing the risk of SQL injection.
$stmt_total_trips = $conn->prepare($sql);

// Conditional statement: Checks if the total trips query preparation was successful.
// Boolean check: Tests if $stmt_total_trips is not false, indicating successful preparation.
// Proceeds with binding and execution only if the query is prepared correctly.
if ($stmt_total_trips) {
    // Method call: Binds the Staff ID to the prepared statement’s placeholder.
// String: bind_param("i", $staffID) binds $staffID as an integer ('i') to the placeholder in the query.
// Securely links the Staff ID to the query, preventing SQL injection.
    $stmt_total_trips->bind_param("i", $staffID);
    // Method call: Executes the total trips query.
// String: execute() runs the prepared statement, counting matching schedule records.
// Retrieves the total number of trips assigned to the staff member.
    $stmt_total_trips->execute();
    // Variable: Stores the result set from the total trips query.
// Object: $stmt_total_trips->get_result() retrieves the query results as a result object.
// Holds the trip count for further processing.
    $result_total_trips = $stmt_total_trips->get_result();
    // Array operation: Updates the total trips statistic.
// Integer: $result_total_trips->fetch_assoc()['count'] retrieves the count from the result as an integer, stored in $stats["totalTrips"].
// Records the total number of trips for the staff member in the statistics array.
    $stats["totalTrips"] = $result_total_trips->fetch_assoc()['count'];
    // Method call: Frees the prepared statement resources.
// String: close() releases the prepared statement ($stmt_total_trips), freeing memory.
// Ensures efficient resource management after the query is complete.
    $stmt_total_trips->close();
}

// Variable: SQL query string to sum hours of upcoming trips.
// String: Defines a SELECT query using COALESCE(SUM(TIMESTAMPDIFF(HOUR, s.DepartureTime, s.ArrivalTime)), 0) to calculate total hours between DepartureTime and ArrivalTime, with $condition and a filter for DepartureTime >= NOW() to include only future trips; COALESCE ensures 0 is returned if the sum is null.
// Aggregates the total hours of upcoming trips for display on the staff dashboard, handling cases with no data.
$sql = "SELECT COALESCE(SUM(TIMESTAMPDIFF(HOUR, s.DepartureTime, s.ArrivalTime)), 0) as total 
        FROM scheduleinformation s WHERE $condition AND s.DepartureTime >= NOW()";

// Variable: Stores the prepared statement for the total hours query.
// Object: $conn->prepare($sql) compiles the SQL query, returning a statement object.
// Prepares the query to safely calculate hours, reducing the risk of SQL injection.
$stmt_total_hours = $conn->prepare($sql);

// Conditional statement: Checks if the total hours query preparation was successful.
// Boolean check: Tests if $stmt_total_hours is not false.
// Proceeds with binding and execution only if the query is prepared correctly.
if ($stmt_total_hours) {
    // Method call: Binds the Staff ID to the prepared statement’s placeholder.
// String: bind_param("i", $staffID) binds $staffID as an integer to the placeholder.
// Securely links the Staff ID to the query, ensuring safe execution.
    $stmt_total_hours->bind_param("i", $staffID);
    // Method call: Executes the total hours query.
// String: execute() runs the prepared statement, summing the hours of upcoming trips.
// Retrieves the total hours for future trips assigned to the staff member.
    $stmt_total_hours->execute();
    // Variable: Stores the result set from the total hours query.
// Object: $stmt_total_hours->get_result() retrieves the query results.
// Holds the total hours for further processing.
    $result_total_hours = $stmt_total_hours->get_result();
    // Array operation: Updates the total hours statistic.
// Float: (float) $result_total_hours->fetch_assoc()['total'] retrieves the total hours and casts it to a float, stored in $stats["totalHours"].
// Records the total hours of upcoming trips, ensuring a numeric format for dashboard display.
    $stats["totalHours"] = (float) $result_total_hours->fetch_assoc()['total'];
    // Method call: Frees the prepared statement resources.
// String: close() releases the prepared statement ($stmt_total_hours).
// Ensures efficient resource management after the query is complete.
    $stmt_total_hours->close();
}

// Variable: SQL query string to count upcoming trips.
// String: Defines a SELECT query with COUNT(*) as count from scheduleinformation, using $condition and DepartureTime >= NOW() to include only future trips.
// Aggregates the number of upcoming trips for display on the staff dashboard.
$sql = "SELECT COUNT(*) as count FROM scheduleinformation s WHERE $condition AND s.DepartureTime >= NOW()";

// Variable: Stores the prepared statement for the upcoming trips query.
// Object: $conn->prepare($sql) compiles the SQL query, returning a statement object.
// Prepares the query to safely count upcoming trips, reducing the risk of SQL injection.
$stmt_upcoming_trips = $conn->prepare($sql);

// Conditional statement: Checks if the upcoming trips query preparation was successful.
// Boolean check: Tests if $stmt_upcoming_trips is not false.
// Proceeds with binding and execution only if the query is prepared correctly.
if ($stmt_upcoming_trips) {
    // Method call: Binds the Staff ID to the prepared statement’s placeholder.
// String: bind_param("i", $staffID) binds $staffID as an integer to the placeholder.
// Securely links the Staff ID to the query, ensuring safe execution.
    $stmt_upcoming_trips->bind_param("i", $staffID);
    // Method call: Executes the upcoming trips query.
// String: execute() runs the prepared statement, counting future trips.
// Retrieves the number of upcoming trips assigned to the staff member.
    $stmt_upcoming_trips->execute();
    // Variable: Stores the result set from the upcoming trips query.
// Object: $stmt_upcoming_trips->get_result() retrieves the query results.
// Holds the trip count for further processing.
    $result_upcoming_trips = $stmt_upcoming_trips->get_result();
    // Array operation: Updates the upcoming trips statistic.
// Integer: $result_upcoming_trips->fetch_assoc()['count'] retrieves the count, stored in $stats["upcomingTrips"].
// Records the number of upcoming trips for the staff dashboard.
    $stats["upcomingTrips"] = $result_upcoming_trips->fetch_assoc()['count'];
    // Method call: Frees the prepared statement resources.
// String: close() releases the prepared statement ($stmt_upcoming_trips).
// Ensures efficient resource management after the query is complete.
    $stmt_upcoming_trips->close();
}

// Conditional statement: Defines the SQL query for schedules based on the staff member’s role.
// Boolean check: Tests if $role is 'Driver' to determine the appropriate WHERE clause (DriverID or CodriverID).
// Customizes the schedule retrieval query to match the staff member’s role, ensuring relevant data.
if ($role === "Driver") {
    // Variable: SQL query string to retrieve driver schedules.
// String: Defines a SELECT query to fetch ScheduleID, BusNumber, RouteName, DepartureTime, and ArrivalTime from scheduleinformation, joined with bus and route tables, where DriverID matches the placeholder (?).
// Retrieves detailed schedule information for drivers, including bus and route details, for the staff dashboard.
    $sql = "SELECT s.ScheduleID, b.BusNumber, r.RouteName, s.DepartureTime, s.ArrivalTime 
            FROM scheduleinformation s
            JOIN bus b ON s.BusID = b.BusID
            JOIN route r ON s.RouteID = r.RouteID
            WHERE s.DriverID = ?";
} else {
    // Variable: SQL query string to retrieve co-driver schedules.
// String: Defines a SELECT query to fetch ScheduleID, BusNumber, RouteName, DepartureTime, and ArrivalTime from scheduleinformation, joined with bus and route tables, where CodriverID matches the placeholder (?).
// Retrieves detailed schedule information for co-drivers, including bus and route details, for the staff dashboard.
    $sql = "SELECT s.ScheduleID, b.BusNumber, r.RouteName, s.DepartureTime, s.ArrivalTime 
            FROM scheduleinformation s
            JOIN bus b ON s.BusID = b.BusID
            JOIN route r ON s.RouteID = r.RouteID
            WHERE s.CodriverID = ?";
}

// Variable: Stores the prepared statement for the schedules query.
// Object: $conn->prepare($sql) compiles the SQL query, returning a statement object.
// Prepares the query to safely retrieve schedules, reducing the risk of SQL injection.
$stmt = $conn->prepare($sql);

// Conditional statement: Checks if the schedules query preparation was successful.
// Boolean check: Tests if $stmt is false, indicating a preparation failure.
// Stops the script with an error message if preparation fails, ensuring robust error handling.
if ($stmt === false) {
    // Function call: Terminates script execution with an error message.
// String: die() outputs a message with $conn->error and stops the script.
// Provides detailed error information for debugging if the query cannot be prepared.
    die("Error preparing statement: " . $conn->error);
}

// Method call: Binds the Staff ID to the prepared statement’s placeholder.
// String: bind_param("i", $staffID) binds $staffID as an integer to the placeholder.
// Securely links the Staff ID to the query, ensuring safe execution.
$stmt->bind_param("i", $staffID);

// Method call: Executes the schedules query.
// String: execute() runs the prepared statement, retrieving matching schedule records.
// Fetches the staff member’s assigned schedules from the database.
$stmt->execute();

// Variable: Stores the result set from the schedules query.
// Object: $stmt->get_result() retrieves the query results as a result object.
// Holds the schedule data for further processing.
$result = $stmt->get_result();

// Variable: Initializes an array to store schedule records.
// Array: Creates an empty array to hold associative arrays, each representing a schedule with fields like ScheduleID and RouteName.
// Prepares to collect schedule details for inclusion in the JSON response.
$schedules = [];

// Loop: Iterates over the schedules query results.
// Array: $result->fetch_assoc() retrieves each row as an associative array with keys ScheduleID, BusNumber, RouteName, DepartureTime, and ArrivalTime, repeated using a while loop until no rows remain.
// Processes each schedule record to include in the JSON response for the dashboard.
while ($row = $result->fetch_assoc()) {
    // Array operation: Appends a schedule record to the array.
// Array: Adds $row to $schedules, containing schedule details.
// Collects schedule data for display on the staff dashboard.
    $schedules[] = $row;
}

// Output statement: Sends a JSON success response to the client.
// String: echo outputs text; json_encode() converts an array ["status" => "success", "stats" => $stats, "schedules" => $schedules] to a JSON string, containing statistics and schedule records.
// Sends the staff member’s statistics (total trips, hours, upcoming trips) and schedules to the client for dashboard display.
echo json_encode(["status" => "success", "stats" => $stats, "schedules" => $schedules]);

// Method call: Frees the prepared statement resources.
// String: close() releases the prepared statement ($stmt), freeing memory.
// Ensures efficient resource management after the schedules query is complete.
$stmt->close();

// Method call: Closes the database connection.
// String: close() terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open, maintaining system efficiency.
$conn->close();
?>