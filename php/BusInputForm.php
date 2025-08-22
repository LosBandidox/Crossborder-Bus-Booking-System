<?php
// Include statement: File inclusion directive.
// String: include is a PHP statement that loads 'databaseconnection.php', a file that defines the $conn MySQLi connection object.
// Loads the database connection settings to store bus details in the International Bus Booking System’s database.
include 'databaseconnection.php';

// Conditional statement: Logic to verify the HTTP request method.
// String check: $_SERVER["REQUEST_METHOD"] is a superglobal array element that returns the request method (e.g., "POST"), compared to "POST".
// Ensures the script processes only POST form submissions, as bus data is sent via POST for security.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Variable: Bus number storage.
    // String: $_POST["busNumber"] is a superglobal array element from the form submission, containing the unique identifier for the bus (e.g., "BUS123").
    // Captures the bus number to identify the bus in the database.
    $busNumber = $_POST["busNumber"];

    // Variable: Year of manufacture storage.
    // String: $_POST["yearOfManufacture"] is a superglobal array element from the form submission, containing the year the bus was made (e.g., "2020").
    // Captures the manufacturing year to track the bus’s age and condition.
    $yearOfManufacture = $_POST["yearOfManufacture"];

    // Variable: Bus capacity storage.
    // String: $_POST["capacity"] is a superglobal array element from the form submission, containing the maximum number of passengers (e.g., "50").
    // Captures the bus’s seating capacity for scheduling and booking purposes.
    $capacity = $_POST["capacity"];

    // Variable: Engine number storage.
    // String: $_POST["engineNumber"] is a superglobal array element from the form submission, containing the unique engine identifier (e.g., "ENG456789").
    // Captures the engine number to uniquely identify the bus’s engine for maintenance records.
    $engineNumber = $_POST["engineNumber"];

    // Variable: Bus status storage.
    // String: $_POST["status"] is a superglobal array element from the form submission, containing the operational state (e.g., "active", "inactive").
    // Captures the bus’s current status to determine its availability for scheduling.
    $status = $_POST["status"];

    // Variable: Bus mileage storage.
    // String: $_POST["mileage"] is a superglobal array element from the form submission, containing the total distance traveled (e.g., "150000").
    // Captures the bus’s mileage to track its usage and maintenance needs.
    $mileage = $_POST["mileage"];

    // Conditional statement: Logic to validate form inputs.
    // Boolean checks: empty() is a PHP built-in function that tests if $busNumber, $yearOfManufacture, $capacity, $engineNumber, $status, or $mileage is empty (e.g., "", null, or unset).
    // Stops the script with an error if any required field is missing, ensuring complete bus data.
    if (empty($busNumber) || empty($yearOfManufacture) || empty($capacity) || empty($engineNumber) || empty($status) || empty($mileage)) {
        // Function call: Script termination function with message.
        // String: die() is a PHP built-in function that outputs "All fields are required." and stops execution.
        // Halts the script and informs the user to complete all fields in the form.
        die("All fields are required.");
    }

    // Variable: SQL INSERT query string.
    // String: Defines a query to insert BusNumber, YearOfManufacture, Capacity, EngineNumber, Status, and Mileage into the 'bus' table, using placeholders (?).
    // Records the new bus’s details in the database for use in scheduling and management.
    $sql = "INSERT INTO bus (BusNumber, YearOfManufacture, Capacity, EngineNumber, Status, Mileage) VALUES (?, ?, ?, ?, ?, ?)";

    // Object: Prepared statement for database insertion.
    // Object: $conn->prepare($sql) is a MySQLi method that creates a prepared statement object from the SQL query, where $conn is the MySQLi connection from databaseconnection.php.
    // Prepares the query to insert bus data securely, using placeholders to prevent SQL injection.
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
    // String: bind_param("siisss", $busNumber, $yearOfManufacture, $capacity, $engineNumber, $status, $mileage) is a MySQLi method that binds variables to the query’s placeholders: string (s) for $busNumber, integers (i) for $yearOfManufacture and $capacity, strings (s) for $engineNumber, $status, and $mileage.
    // Attaches bus data to the query safely, preventing SQL injection for secure insertion.
    $stmt->bind_param("siisss", $busNumber, $yearOfManufacture, $capacity, $engineNumber, $status, $mileage);

    // Conditional statement: Logic to execute the query and check success.
    // Boolean check: $stmt->execute() is a MySQLi method that runs the prepared statement, returning TRUE on success or FALSE on failure.
    // Outputs a success or error message based on whether the bus data was inserted.
    if ($stmt->execute()) {
        // Output statement: Success message output.
        // String: echo outputs "Bus data submitted successfully!".
        // Informs the user (e.g., admin) that the bus was successfully added to the database.
        echo "Bus data submitted successfully!";
    } else {
        // Output statement: Error message output.
        // String: echo outputs "Error: " concatenated with $stmt->error, a MySQLi property with the error message.
        // Informs the user of a database insertion failure, providing details for debugging (e.g., duplicate bus number).
        echo "Error: " . $stmt->error;
    }

    // Method call: Statement closure function.
    // String: close() is a MySQLi method that frees resources associated with the prepared statement ($stmt).
    // Releases database resources after inserting bus data to maintain system efficiency.
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