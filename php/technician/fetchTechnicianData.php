<?php
// Function call: Starts or resumes a PHP session to access session data.
// String: session_start() is a PHP built-in function that initializes a session or resumes an existing one, enabling access to session variables like the technician’s email.
// Enables authentication by checking the logged-in technician’s email, ensuring only authorized users access maintenance data in the International Bus Booking System.
session_start();

// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to query technician, maintenance, and bus status information for the technician dashboard.
include '../databaseconnection.php';

// Conditional statement: Checks if the user is logged in by verifying the session email.
// Boolean check: isset() is a PHP built-in function that tests if $_SESSION["Email"], a superglobal session variable, exists and is not null.
// Ensures the user is authenticated before accessing technician data, preventing unauthorized access.
if (!isset($_SESSION["Email"])) {
    // Output statement: Sends a JSON error response to the client.
// String: echo outputs text; json_encode() is a PHP built-in function that converts an array ["status" => "error", "message" => "Please log in"] to a JSON string, a data format for web communication.
// Informs the client’s JavaScript that login is required, enabling the dashboard to redirect to a login page.
    echo json_encode(["status" => "error", "message" => "Please log in"]);
    // Function call: Terminates script execution immediately.
// String: exit() is a PHP built-in function that stops the script from running further.
// Halts processing to prevent unauthorized access to technician data.
    exit();
}

// Variable: Stores the logged-in technician’s email from the session.
// String: $_SESSION["Email"] is a superglobal session variable containing the email of the authenticated technician (e.g., "tech@example.com").
// Identifies the technician to retrieve their ID for querying maintenance and bus status data.
$email = $_SESSION["Email"];

// Variable: SQL query string to retrieve technician details by email and role.
// String: Defines a SELECT query to fetch StaffID from the staff table where Email matches the placeholder (?) and Role is 'Technician', ensuring only technicians are queried.
// Retrieves the technician’s ID to filter maintenance and bus status records specific to them.
$sql = "SELECT StaffID FROM staff WHERE Email = ? AND Role = 'Technician'";

// Variable: Stores the prepared statement for the technician query.
// Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with a placeholder, returning a statement object for binding and execution.
// Prepares the query to safely retrieve technician details, reducing the risk of SQL injection.
$stmt = $conn->prepare($sql);

// Conditional statement: Checks if the technician query preparation was successful.
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

// Method call: Executes the technician query.
// String: execute() is a MySQLi method that runs the prepared statement, querying the staff table for the matching email and role.
// Retrieves the technician’s ID from the database.
$stmt->execute();

// Variable: Stores the result set from the technician query.
// Object: $stmt->get_result() is a MySQLi method that retrieves the query results as a result object, containing rows matching the email and role.
// Holds the technician data for further processing, such as fetching StaffID.
$result = $stmt->get_result();

// Conditional statement: Checks if no technician record was found for the email.
// Integer check: $result->num_rows is a MySQLi property that indicates the number of rows returned; zero means no matching technician record.
// Stops the script if no technician is found, preventing invalid maintenance queries.
if ($result->num_rows == 0) {
    // Output statement: Sends a JSON error response to the client.
// String: echo outputs text; json_encode() converts an array ["status" => "no_staff"] to a JSON string.
// Informs the client’s JavaScript that no technician record exists, enabling error handling (e.g., displaying an error message).
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
// Halts processing to prevent further actions for an invalid technician.
    exit();
}

// Variable: Stores the technician data from the query result.
// Array: $result->fetch_assoc() is a MySQLi method that retrieves a single row as an associative array with the key StaffID.
// Extracts the technician’s ID for use in subsequent maintenance and bus status queries.
$staff = $result->fetch_assoc();

// Variable: Stores the Technician ID from the query result.
// Integer: $staff["StaffID"] is the unique identifier of the technician, used to filter maintenance and bus status records.
// Identifies the specific technician for querying their assigned maintenance tasks and bus statuses.
$technicianID = $staff["StaffID"];

// Variable: Initializes the statistics array for the technician.
// Array: Creates an associative array with keys totalTasks (integer), totalCost (float), busesServiced (integer), and recentServices (integer), all initialized to zero.
// Prepares to store aggregated statistics (tasks, cost, buses serviced, recent services) for the technician dashboard.
$stats = [
    "totalTasks" => 0,
    "totalCost" => 0.00,
    "busesServiced" => 0,
    "recentServices" => 0
];

// Variable: SQL query string to count total maintenance tasks for the technician.
// String: Defines a SELECT query with COUNT(*) as count from the maintenance table where TechnicianID matches the placeholder (?).
// Aggregates the total number of maintenance tasks performed by the technician for display on the dashboard.
$sql = "SELECT COUNT(*) as count FROM maintenance WHERE TechnicianID = ?";

// Variable: Stores the prepared statement for the total tasks query.
// Object: $conn->prepare($sql) compiles the SQL query with a placeholder, returning a statement object.
// Prepares the query to safely count tasks, reducing the risk of SQL injection.
$stmt_total_tasks = $conn->prepare($sql);

// Conditional statement: Checks if the total tasks query preparation was successful.
// Boolean check: Tests if $stmt_total_tasks is not false, indicating successful preparation.
// Proceeds with binding and execution only if the query is prepared correctly.
if ($stmt_total_tasks) {
    // Method call: Binds the Technician ID to the prepared statement’s placeholder.
// String: bind_param("i", $technicianID) binds $technicianID as an integer ('i') to the placeholder in the query.
// Securely links the Technician ID to the query, preventing SQL injection.
    $stmt_total_tasks->bind_param("i", $technicianID);
    // Method call: Executes the total tasks query.
// String: execute() runs the prepared statement, counting matching maintenance records.
// Retrieves the total number of tasks performed by the technician.
    $stmt_total_tasks->execute();
    // Variable: Stores the result set from the total tasks query.
// Object: $stmt_total_tasks->get_result() retrieves the query results as a result object.
// Holds the task count for further processing.
    $result_total_tasks = $stmt_total_tasks->get_result();
    // Array operation: Updates the total tasks statistic.
// Integer: $result_total_tasks->fetch_assoc()['count'] retrieves the count from the result as an integer, stored in $stats["totalTasks"].
// Records the total number of maintenance tasks for the technician in the statistics array.
    $stats["totalTasks"] = $result_total_tasks->fetch_assoc()['count'];
    // Method call: Frees the prepared statement resources.
// String: close() releases the prepared statement ($stmt_total_tasks), freeing memory.
// Ensures efficient resource management after the query is complete.
    $stmt_total_tasks->close();
}

// Variable: SQL query string to sum total maintenance costs.
// String: Defines a SELECT query using COALESCE(SUM(Cost), 0) to calculate the total cost of maintenance tasks where TechnicianID matches the placeholder (?); COALESCE ensures 0 is returned if the sum is null.
// Aggregates the total maintenance costs incurred by the technician for display on the dashboard, handling cases with no data.
$sql = "SELECT COALESCE(SUM(Cost), 0) as total FROM maintenance WHERE TechnicianID = ?";

// Variable: Stores the prepared statement for the total cost query.
// Object: $conn->prepare($sql) compiles the SQL query, returning a statement object.
// Prepares the query to safely calculate costs, reducing the risk of SQL injection.
$stmt_total_cost = $conn->prepare($sql);

// Conditional statement: Checks if the total cost query preparation was successful.
// Boolean check: Tests if $stmt_total_cost is not false.
// Proceeds with binding and execution only if the query is prepared correctly.
if ($stmt_total_cost) {
    // Method call: Binds the Technician ID to the prepared statement’s placeholder.
// String: bind_param("i", $technicianID) binds $technicianID as an integer to the placeholder.
// Securely links the Technician ID to the query, ensuring safe execution.
    $stmt_total_cost->bind_param("i", $technicianID);
    // Method call: Executes the total cost query.
// String: execute() runs the prepared statement, summing the costs of maintenance tasks.
// Retrieves the total maintenance costs for the technician.
    $stmt_total_cost->execute();
    // Variable: Stores the result set from the total cost query.
// Object: $stmt_total_cost->get_result() retrieves the query results.
// Holds the total cost for further processing.
    $result_total_cost = $stmt_total_cost->get_result();
    // Array operation: Updates the total cost statistic.
// Float: (float) $result_total_cost->fetch_assoc()['total'] retrieves the total cost and casts it to a float, stored in $stats["totalCost"].
// Records the total maintenance costs, ensuring a numeric format for dashboard display.
    $stats["totalCost"] = (float) $result_total_cost->fetch_assoc()['total'];
    // Method call: Frees the prepared statement resources.
// String: close() releases the prepared statement ($stmt_total_cost).
// Ensures efficient resource management after the query is complete.
    $stmt_total_cost->close();
}

// Variable: SQL query string to count distinct buses serviced by the technician.
// String: Defines a SELECT query with COUNT(DISTINCT BusID) as count from the maintenance table where TechnicianID matches the placeholder (?).
// Aggregates the number of unique buses serviced by the technician for display on the dashboard.
$sql = "SELECT COUNT(DISTINCT BusID) as count FROM maintenance WHERE TechnicianID = ?";

// Variable: Stores the prepared statement for the buses serviced query.
// Object: $conn->prepare($sql) compiles the SQL query, returning a statement object.
// Prepares the query to safely count unique buses, reducing the risk of SQL injection.
$stmt_buses_serviced = $conn->prepare($sql);

// Conditional statement: Checks if the buses serviced query preparation was successful.
// Boolean check: Tests if $stmt_buses_serviced is not false.
// Proceeds with binding and execution only if the query is prepared correctly.
if ($stmt_buses_serviced) {
    // Method call: Binds the Technician ID to the prepared statement’s placeholder.
// String: bind_param("i", $technicianID) binds $technicianID as an integer to the placeholder.
// Securely links the Technician ID to the query, ensuring safe execution.
    $stmt_buses_serviced->bind_param("i", $technicianID);
    // Method call: Executes the buses serviced query.
// String: execute() runs the prepared statement, counting distinct BusID values.
// Retrieves the number of unique buses serviced by the technician.
    $stmt_buses_serviced->execute();
    // Variable: Stores the result set from the buses serviced query.
// Object: $stmt_buses_serviced->get_result() retrieves the query results.
// Holds the bus count for further processing.
    $result_buses_serviced = $stmt_buses_serviced->get_result();
    // Array operation: Updates the buses serviced statistic.
// Integer: $result_buses_serviced->fetch_assoc()['count'] retrieves the count, stored in $stats["busesServiced"].
// Records the number of unique buses serviced for the technician dashboard.
    $stats["busesServiced"] = $result_buses_serviced->fetch_assoc()['count'];
    // Method call: Frees the prepared statement resources.
// String: close() releases the prepared statement ($stmt_buses_serviced).
// Ensures efficient resource management after the query is complete.
    $stmt_buses_serviced->close();
}

// Variable: SQL query string to count recent maintenance tasks.
// String: Defines a SELECT query with COUNT(*) as count from the maintenance table where TechnicianID matches the placeholder (?) and ServiceDate is within the last 30 days, using DATE_SUB(NOW(), INTERVAL 30 DAY) to calculate the date threshold.
// Aggregates the number of maintenance tasks performed in the last 30 days for display on the technician dashboard.
$sql = "SELECT COUNT(*) as count FROM maintenance WHERE TechnicianID = ? AND ServiceDate >= DATE_SUB(NOW(), INTERVAL 30 DAY)";

// Variable: Stores the prepared statement for the recent services query.
// Object: $conn->prepare($sql) compiles the SQL query, returning a statement object.
// Prepares the query to safely count recent tasks, reducing the risk of SQL injection.
$stmt_recent_services = $conn->prepare($sql);

// Conditional statement: Checks if the recent services query preparation was successful.
// Boolean check: Tests if $stmt_recent_services is not false.
// Proceeds with binding and execution only if the query is prepared correctly.
if ($stmt_recent_services) {
    // Method call: Binds the Technician ID to the prepared statement’s placeholder.
// String: bind_param("i", $technicianID) binds $technicianID as an integer to the placeholder.
// Securely links the Technician ID to the query, ensuring safe execution.
    $stmt_recent_services->bind_param("i", $technicianID);
    // Method call: Executes the recent services query.
// String: execute() runs the prepared statement, counting recent maintenance tasks.
// Retrieves the number of tasks performed in the last 30 days.
    $stmt_recent_services->execute();
    // Variable: Stores the result set from the recent services query.
// Object: $stmt_recent_services->get_result() retrieves the query results.
// Holds the recent task count for further processing.
    $result_recent_services = $stmt_recent_services->get_result();
    // Array operation: Updates the recent services statistic.
// Integer: $result_recent_services->fetch_assoc()['count'] retrieves the count, stored in $stats["recentServices"].
// Records the number of recent maintenance tasks for the technician dashboard.
    $stats["recentServices"] = $result_recent_services->fetch_assoc()['count'];
    // Method call: Frees the prepared statement resources.
// String: close() releases the prepared statement ($stmt_recent_services).
// Ensures efficient resource management after the query is complete.
    $stmt_recent_services->close();
}

// Variable: SQL query string to retrieve maintenance task details.
// String: Defines a SELECT query to fetch BusNumber, ServiceDone, ServiceDate, NSD (Next Service Date), and Cost from the maintenance table, joined with the bus table on BusID, where TechnicianID matches the placeholder (?).
// Retrieves detailed maintenance task records for the technician, including bus details, for display on the dashboard.
$sql = "SELECT b.BusNumber, m.ServiceDone, m.ServiceDate, m.NSD, m.Cost 
        FROM maintenance m 
        JOIN bus b ON m.BusID = b.BusID 
        WHERE m.TechnicianID = ?";

// Variable: Stores the prepared statement for the maintenance tasks query.
// Object: $conn->prepare($sql) compiles the SQL query, returning a statement object.
// Prepares the query to safely retrieve maintenance tasks, reducing the risk of SQL injection.
$stmt = $conn->prepare($sql);

// Conditional statement: Checks if the maintenance tasks query preparation was successful.
// Boolean check: Tests if $stmt is false.
// Stops processing with an error message if preparation fails, ensuring robustness.
if ($stmt === false) {
    // Function call: Terminates script execution with an error message.
// String: die() outputs a message with $conn->error and stops the script.
// Provides detailed error information for debugging if the query cannot be prepared.
    die("Error preparing statement: " . $conn->error);
}

// Method call: Binds the Technician ID to the prepared statement’s placeholder.
// String: bind_param("i", $technicianID) binds $technicianID as an integer to the placeholder.
// Securely links the Technician ID to the query, ensuring accurate task retrieval.
$stmt->bind_param("i", $technicianID);

// Method call: Executes the maintenance tasks query.
// String: execute() runs the prepared statement, retrieving matching maintenance records.
// Fetches the technician’s maintenance task details from the database.
$stmt->execute();

// Variable: Stores the result set from the maintenance tasks query.
// Object: $stmt->get_result() retrieves the query results as a result object.
// Holds the maintenance task data for further processing.
$result = $stmt->get_result();

// Variable: Initializes an array to store maintenance task records.
// Array: Creates an empty array to hold associative arrays, each representing a maintenance task with fields like BusNumber and ServiceDone.
// Prepares to collect maintenance task details for inclusion in the JSON response.
$maintenanceTasks = [];

// Loop: Iterates over the maintenance tasks query results.
// Array: $result->fetch_assoc() retrieves each row as an associative array with keys BusNumber, ServiceDone, ServiceDate, NSD, and Cost, repeated using a while loop until no rows remain.
// Processes each maintenance task record to include in the JSON response for the dashboard.
while ($row = $result->fetch_assoc()) {
    // Array operation: Appends a maintenance task record to the array.
// Array: Adds $row to $maintenanceTasks, containing task details.
// Collects maintenance task data for display on the technician dashboard.
    $maintenanceTasks[] = $row;
}

// Variable: SQL query string to retrieve bus status details.
// String: Defines a SELECT query to fetch BusNumber, RouteName, the earliest DepartureTime (NextDepartureTime), and NSD from the bus table, left-joined with scheduleinformation, route, and a subquery for the latest maintenance record by the technician, grouped by BusID, BusNumber, NSD, and RouteName; the subquery selects the most recent NSD for the technician’s maintenance records per bus, ordered by ServiceDate descending and limited to one.
// Aggregates bus status information, including route assignments and maintenance schedules, for display on the technician dashboard, handling cases where buses have no schedules or maintenance records.
$sql = "SELECT b.BusNumber, 
               r.RouteName, 
               MIN(s.DepartureTime) AS NextDepartureTime,
               m_latest.NSD
        FROM bus b 
        LEFT JOIN scheduleinformation s ON b.BusID = s.BusID
        LEFT JOIN route r ON s.RouteID = r.RouteID
        LEFT JOIN (
            SELECT BusID, NSD
            FROM maintenance 
            WHERE TechnicianID = ? 
            ORDER BY ServiceDate DESC 
            LIMIT 1
        ) m_latest ON b.BusID = m_latest.BusID
        GROUP BY b.BusID, b.BusNumber, m_latest.NSD, r.RouteName";

// Variable: Stores the prepared statement for the bus status query.
// Object: $conn->prepare($sql) compiles the SQL query, returning a statement object.
// Prepares the query to safely retrieve bus statuses, reducing the risk of SQL injection.
$stmt = $conn->prepare($sql);

// Conditional statement: Checks if the bus status query preparation was successful.
// Boolean check: Tests if $stmt is false.
// Stops processing with an error message if preparation fails, ensuring robustness.
if ($stmt === false) {
    // Function call: Terminates script execution with an error message.
// String: die() outputs a message with $conn->error and stops the script.
// Provides detailed error information for debugging if the query cannot be prepared.
    die("Error preparing statement: " . $conn->error);
}

// Method call: Binds the Technician ID to the prepared statement’s placeholder.
// String: bind_param("i", $technicianID) binds $technicianID as an integer to the placeholder in the subquery.
// Securely links the Technician ID to the query, ensuring accurate bus status retrieval.
$stmt->bind_param("i", $technicianID);

// Method call: Executes the bus status query.
// String: execute() runs the prepared statement, retrieving bus status records.
// Fetches bus assignment and maintenance status details from the database.
$stmt->execute();

// Variable: Stores the result set from the bus status query.
// Object: $stmt->get_result() retrieves the query results as a result object.
// Holds the bus status data for further processing.
$result = $stmt->get_result();

// Variable: Initializes an array to store bus status records.
// Array: Creates an empty array to hold associative arrays, each representing a bus status with fields like BusNumber and RouteName.
// Prepares to collect bus status details for inclusion in the JSON response.
$busStatuses = [];

// Loop: Iterates over the bus status query results.
// Array: $result->fetch_assoc() retrieves each row as an associative array with keys BusNumber, RouteName, NextDepartureTime, and NSD, repeated using a while loop until no rows remain.
// Processes each bus status record to include in the JSON response for the dashboard.
while ($row = $result->fetch_assoc()) {
    // Array operation: Appends a bus status record to the array.
// Array: Adds $row to $busStatuses, containing status details.
// Collects bus status data for display on the technician dashboard.
    $busStatuses[] = $row;
}

// Output statement: Sends a JSON success response to the client.
// String: echo outputs text; json_encode() converts an array ["status" => "success", "stats" => $stats, "maintenanceTasks" => $maintenanceTasks, "busStatus" => $busStatuses] to a JSON string, containing statistics, maintenance tasks, and bus statuses.
// Sends the technician’s statistics (tasks, costs, buses serviced, recent services), maintenance tasks, and bus statuses to the client for dashboard display.
echo json_encode([
    "status" => "success",
    "stats" => $stats,
    "maintenanceTasks" => $maintenanceTasks,
    "busStatus" => $busStatuses
]);

// Method call: Frees the prepared statement resources.
// String: close() releases the prepared statement ($stmt), freeing memory.
// Ensures efficient resource management after the bus status query is complete.
$stmt->close();

// Method call: Closes the database connection.
// String: close() terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open, maintaining system efficiency.
$conn->close();
?>