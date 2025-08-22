<?php
// Include statement: Database connection inclusion directive.
// String: require_once is a PHP statement that loads 'databaseconnection.php', a file that defines the $conn MySQLi connection object, ensuring it’s included only once.
// Loads the database connection settings to store maintenance data in the International Bus Booking System’s database.
require_once 'databaseconnection.php';

// Include statement: Date utilities inclusion directive.
// String: require_once is a PHP statement that loads 'dateUtils.php', a file containing the parseDateInput() function, ensuring it’s included only once.
// Provides date parsing functions to convert DD-MM-YYYY dates to database-compatible format.
require_once 'dateUtils.php';

// Conditional statement: Logic to verify the HTTP request method.
// String check: $_SERVER["REQUEST_METHOD"] is a superglobal array element that returns the request method (e.g., "POST"), compared to "POST".
// Ensures the script processes only POST form submissions, as maintenance data is sent via POST for security.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Variable: Bus ID storage.
// String: $_POST["busID"] is a superglobal array element from the form submission, containing the unique identifier for the bus (e.g., "1").
// Identifies the bus undergoing maintenance for database storage.
    $busID = $_POST["busID"];

    // Variable: Service performed storage.
// String: $_POST["serviceDone"] is a superglobal array element from the form submission, containing the description of the maintenance service (e.g., "Oil Change").
// Captures the details of the maintenance work performed.
    $serviceDone = $_POST["serviceDone"];

    // Variable: Service date input storage.
// String: $_POST["serviceDate"] is a superglobal array element from the form submission, containing the maintenance date in DD-MM-YYYY format (e.g., "24-07-2025").
// Records the date when the maintenance was performed.
    $serviceDateInput = $_POST["serviceDate"];

    // Variable: Maintenance cost storage.
// String: $_POST["cost"] is a superglobal array element from the form submission, containing the cost of maintenance (e.g., "5000.00").
// Captures the financial cost of the maintenance service.
    $cost = $_POST["cost"];

    // Variable: Materials used storage.
// String: $_POST["materialUsed"] is a superglobal array element from the form submission, containing the materials used (e.g., "Oil Filter, Engine Oil").
// Lists the materials used during the maintenance for record-keeping.
    $materialUsed = $_POST["materialUsed"];

    // Variable: Last service date input storage.
// String: $_POST["lsd"] is a superglobal array element from the form submission, containing the previous service date in DD-MM-YYYY format (e.g., "15-06-2025").
// Records the date of the bus’s last maintenance for scheduling purposes.
    $lsdInput = $_POST["lsd"];

    // Variable: Next service date input storage.
// String: $_POST["nsd"] is a superglobal array element from the form submission, containing the scheduled next service date in DD-MM-YYYY format (e.g., "24-10-2025").
// Records the planned date for the next maintenance service.
    $nsdInput = $_POST["nsd"];

    // Variable: Technician ID storage.
// String: $_POST["technicianID"] is a superglobal array element from the form submission, containing the unique identifier for the technician (e.g., "101").
// Identifies the technician performing the maintenance for accountability.
    $technicianID = $_POST["technicianID"];

    // Variables: Parsed date storage.
// Strings: parseDateInput() is a custom function from dateUtils.php that converts $serviceDateInput, $lsdInput, and $nsdInput from DD-MM-YYYY to YYYY-MM-DD format (e.g., "2025-07-24").
// Converts user-entered dates to a database-compatible format for consistent storage.
    $serviceDate = parseDateInput($serviceDateInput);
    $lsd = parseDateInput($lsdInput);
    $nsd = parseDateInput($nsdInput);

    // Conditional statement: Logic to validate form inputs and dates.
// Boolean checks: empty() is a PHP built-in function that tests if $busID, $serviceDone, $serviceDateInput, $cost, $materialUsed, $lsdInput, $nsdInput, or $technicianID is empty; also checks if $serviceDate, $lsd, or $nsd is null (indicating invalid dates).
// Stops the script with an error if any field is missing or dates are invalid, ensuring complete and valid data.
    if (empty($busID) || empty($serviceDone) || empty($serviceDateInput) || empty($cost) || empty($materialUsed) || empty($lsdInput) || empty($nsdInput) || empty($technicianID) || $serviceDate === null || $lsd === null || $nsd === null) {
        // Function call: Script termination function with message.
// String: die() is a PHP built-in function that outputs "All fields are required, and dates must be valid (DD-MM-YYYY)." and stops execution.
// Halts the script and informs the user to complete all fields with valid dates in the correct format.
        die("All fields are required, and dates must be valid (DD-MM-YYYY).");
    }

    // Variable: SQL INSERT query string.
// String: Defines a query to insert BusID, ServiceDone, ServiceDate, Cost, MaterialUsed, LSD, NSD, and TechnicianID into the 'maintenance' table, using placeholders (?).
// Records the maintenance details in the database for tracking and scheduling.
    $sql = "INSERT INTO maintenance (BusID, ServiceDone, ServiceDate, Cost, MaterialUsed, LSD, NSD, TechnicianID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Object: Prepared statement for database insertion.
// Object: $conn->prepare($sql) is a MySQLi method that creates a prepared statement object from the SQL query, where $conn is the MySQLi connection from databaseconnection.php.
// Prepares the query to insert maintenance data securely, using placeholders to prevent SQL injection.
    $stmt = $conn->prepare($sql);

    // Conditional statement: Logic to check statement preparation.
// Boolean check: Tests if $stmt is false, indicating preparation failure due to SQL errors or connection issues.
// Stops the script with an error if the query cannot be prepared, ensuring reliable insertion.
    if ($stmt === false) {
        // Function call: Script termination function with message.
// String: die() is a PHP built-in function that outputs "Error preparing statement: " concatenated with $conn->error, a MySQLi property with the error message, and stops execution.
// Halts the script and informs the user of a server issue with the query preparation.
        die("Error preparing statement: " . $conn->error);
    }

    // Method call: Parameter binding function.
// String: bind_param("issdsssi", $busID, $serviceDone, $serviceDate, $cost, $materialUsed, $lsd, $nsd, $technicianID) is a MySQLi method that binds variables to the query’s placeholders: integers (i) for $busID and $technicianID, strings (s) for $serviceDone, $serviceDate, $materialUsed, $lsd, $nsd, and double (d) for $cost.
// Attaches maintenance data to the query safely, preventing SQL injection for secure insertion.
    $stmt->bind_param("issdsssi", $busID, $serviceDone, $serviceDate, $cost, $materialUsed, $lsd, $nsd, $technicianID);

    // Conditional statement: Logic to execute the query and check success.
// Boolean check: $stmt->execute() is a MySQLi method that runs the prepared statement, returning TRUE on success or FALSE on failure.
// Redirects to the technician dashboard on success or outputs an error on failure.
    if ($stmt->execute()) {
        // Function call: HTTP redirect function.
// String: header() is a PHP built-in function that sets the Location header to "/frontend/dashboard/technician/TechnicianDashboard.html".
// Redirects the user to the technician dashboard after successfully saving maintenance data.
        header("Location: /frontend/dashboard/technician/TechnicianDashboard.html");
        // Function call: Script termination function.
// String: exit() is a PHP built-in function that stops script execution.
// Halts the script after the redirect to prevent further processing.
        exit();
    } else {
        // Output statement: Error message output.
// String: echo outputs "Error: " concatenated with $stmt->error, a MySQLi property with the error message.
// Informs the user of a database insertion failure, providing details for debugging (e.g., invalid BusID).
        echo "Error: " . $stmt->error;
    }

    // Method call: Statement closure function.
// String: close() is a MySQLi method that frees resources associated with the prepared statement ($stmt).
// Releases database resources after inserting maintenance data to maintain system efficiency.
    $stmt->close();

    // Method call: Connection closure function.
// String: close() is a MySQLi method that closes the database connection ($conn).
// Frees database resources after all operations, ensuring no connections remain open.
    $conn->close();
} else {
    // Output statement: Error message output.
// String: echo outputs "Invalid request method.".
// Informs the user that a non-POST request was used, enforcing secure form submission.
    echo "Invalid request method.";
}
?>