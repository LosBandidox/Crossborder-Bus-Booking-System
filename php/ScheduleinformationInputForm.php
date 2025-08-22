<?php
// Include statement: Database connection inclusion directive.
// String: require_once is a PHP statement that loads 'databaseconnection.php', a file that defines the $conn MySQLi connection object, ensuring it’s included only once.
// Loads the database connection settings to store schedule details in the International Bus Booking System’s database.
require_once 'databaseconnection.php';

// Include statement: Date utilities inclusion directive.
// String: require_once is a PHP statement that loads 'dateUtils.php', a file containing the parseDateTimeInput() function, ensuring it’s included only once.
// Provides datetime parsing functions to convert DD-MM-YYYY HH:MM:SS to database-compatible format.
require_once 'dateUtils.php';

// Conditional statement: Logic to verify the HTTP request method.
// String check: $_SERVER["REQUEST_METHOD"] is a superglobal array element that returns the request method (e.g., "POST"), compared to "POST".
// Ensures the script processes only POST form submissions, as schedule data is sent via POST for security.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Variable: Bus ID storage.
// String: $_POST["busID"] is a superglobal array element from the form submission, containing the unique identifier for the bus (e.g., "1").
// Identifies the bus assigned to the schedule for operational planning.
    $busID = $_POST["busID"];

    // Variable: Route ID storage.
// String: $_POST["routeID"] is a superglobal array element from the form submission, containing the unique identifier for the route (e.g., "101").
// Identifies the route for the schedule to define the travel path.
    $routeID = $_POST["routeID"];

    // Variable: Departure time input storage.
// String: $_POST["departureTime"] is a superglobal array element from the form submission, containing the departure datetime in DD-MM-YYYY HH:MM:SS format (e.g., "24-07-2025 08:00:00").
// Records the scheduled departure time for the trip.
    $departureTimeInput = $_POST["departureTime"];

    // Variable: Arrival time input storage.
// String: $_POST["arrivalTime"] is a superglobal array element from the form submission, containing the arrival datetime in DD-MM-YYYY HH:MM:SS format (e.g., "24-07-2025 16:00:00").
// Records the scheduled arrival time for the trip.
    $arrivalTimeInput = $_POST["arrivalTime"];

    // Variable: Trip cost storage.
// String: $_POST["cost"] is a superglobal array element from the form submission, containing the cost of the trip (e.g., "1500.00").
// Captures the financial cost of the trip for ticketing purposes.
    $cost = $_POST["cost"];

    // Variable: Driver ID storage.
// String: $_POST["driverID"] is a superglobal array element from the form submission, containing the unique identifier for the driver (e.g., "201").
// Identifies the driver assigned to the schedule for accountability.
    $driverID = $_POST["driverID"];

    // Variable: Co-driver ID storage.
// String: $_POST["codriverID"] is a superglobal array element from the form submission, containing the unique identifier for the co-driver (e.g., "202").
// Identifies the co-driver assigned to the schedule for support and safety.
    $codriverID = $_POST["codriverID"];

    // Variables: Parsed datetime storage.
// Strings: parseDateTimeInput() is a custom function from dateUtils.php that converts $departureTimeInput and $arrivalTimeInput from DD-MM-YYYY HH:MM:SS to YYYY-MM-DD HH:MM:SS format (e.g., "2025-07-24 08:00:00").
// Converts user-entered datetimes to a database-compatible format for consistent storage.
    $departureTime = parseDateTimeInput($departureTimeInput);
    $arrivalTime = parseDateTimeInput($arrivalTimeInput);

    // Conditional statement: Logic to validate form inputs and datetimes.
// Boolean checks: empty() is a PHP built-in function that tests if $busID, $routeID, $departureTimeInput, $arrivalTimeInput, $cost, $driverID, or $codriverID is empty; also checks if $departureTime or $arrivalTime is null (indicating invalid datetimes).
// Stops the script with an error if any field is missing or datetimes are invalid, ensuring complete and valid data.
    if (empty($busID) || empty($routeID) || empty($departureTimeInput) || empty($arrivalTimeInput) || empty($cost) || empty($driverID) || empty($codriverID) || $departureTime === null || $arrivalTime === null) {
        // Function call: Script termination function with message.
// String: die() is a PHP built-in function that outputs "All fields are required, and datetimes must be valid (DD-MM-YYYY HH:MM:SS)." and stops execution.
// Halts the script and informs the user to complete all fields with valid datetimes in the correct format.
        die("All fields are required, and datetimes must be valid (DD-MM-YYYY HH:MM:SS).");
    }

    // Variable: SQL INSERT query string.
// String: Defines a query to insert BusID, RouteID, DepartureTime, ArrivalTime, Cost, DriverID, and CodriverID into the 'scheduleinformation' table, using placeholders (?).
// Records the new schedule’s details in the database for trip planning and booking.
    $sql = "INSERT INTO scheduleinformation (BusID, RouteID, DepartureTime, ArrivalTime, Cost, DriverID, CodriverID) VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Object: Prepared statement for database insertion.
// Object: $conn->prepare($sql) is a MySQLi method that creates a prepared statement object from the SQL query, where $conn is the MySQLi connection from databaseconnection.php.
// Prepares the query to insert schedule data securely, using placeholders to prevent SQL injection.
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
// String: bind_param("iissdii", $busID, $routeID, $departureTime, $arrivalTime, $cost, $driverID, $codriverID) is a MySQLi method that binds variables to the query’s placeholders: integers (i) for $busID, $routeID, $driverID, $codriverID, strings (s) for $departureTime, $arrivalTime, and double (d) for $cost.
// Attaches schedule data to the query safely, preventing SQL injection for secure insertion.
    $stmt->bind_param("iissdii", $busID, $routeID, $departureTime, $arrivalTime, $cost, $driverID, $codriverID);

    // Conditional statement: Logic to execute the query and check success.
// Boolean check: $stmt->execute() is a MySQLi method that runs the prepared statement, returning TRUE on success or FALSE on failure.
// Redirects to the admin schedules page on success or outputs an error on failure.
    if ($stmt->execute()) {
        // Function call: HTTP redirect function.
// String: header() is a PHP built-in function that sets the Location header to "/frontend/dashboard/admin/admin_schedules.html".
// Redirects the user to the admin schedules dashboard after successfully adding the schedule.
        header("Location: /frontend/dashboard/admin/admin_schedules.html");
        // Function call: Script termination function.
// String: exit() is a PHP built-in function thatisu that stops script execution.
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
// Releases database resources after inserting schedule data to maintain system efficiency.
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