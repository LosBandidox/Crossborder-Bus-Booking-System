<?php
// Include statement: File inclusion directive.
// String: include is a PHP statement that loads 'databaseconnection.php', a file that defines the $conn MySQLi connection object.
// Loads the database connection settings to store staff details in the International Bus Booking System’s database.
include 'databaseconnection.php';

// Conditional statement: Logic to verify the HTTP request method.
// String check: $_SERVER["REQUEST_METHOD"] is a superglobal array element that returns the request method (e.g., "POST"), compared to "POST".
// Ensures the script processes only POST form submissions, as staff data is sent via POST for security.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Variable: Staff name storage.
// String: $_POST["name"] is a superglobal array element from the form submission, containing the staff member’s name (e.g., "John Doe").
// Captures the name to identify the staff member in the database.
    $name = $_POST["name"];

    // Variable: Phone number storage.
// String: $_POST["phoneNumber"] is a superglobal array element from the form submission, containing the staff member’s phone number (e.g., "+254123456789").
// Captures the contact number for communication purposes.
    $phoneNumber = $_POST["phoneNumber"];

    // Variable: Email storage.
// String: $_POST["email"] is a superglobal array element from the form submission, containing the staff member’s email address (e.g., "john.doe@busbooking.com").
// Captures the email for identification and communication.
    $email = $_POST["email"];

    // Variable: Staff number storage.
// String: $_POST["staffNumber"] is a superglobal array element from the form submission, containing the unique staff identification number (e.g., "STF001").
// Captures the unique identifier for the staff member in the system.
    $staffNumber = $_POST["staffNumber"];

    // Variable: Role storage.
// String: $_POST["role"] is a superglobal array element from the form submission, containing the staff member’s role (e.g., "Driver", "Cashier").
// Specifies the staff member’s job function for operational purposes.
    $role = $_POST["role"];

    // Conditional statement: Logic to validate form inputs.
// Boolean checks: empty() is a PHP built-in function that tests if $name, $phoneNumber, $email, $staffNumber, or $role is empty (e.g., "", null, or unset).
// Stops the script with an error if any required field is missing, ensuring complete staff data.
    if (empty($name) || empty($phoneNumber) || empty($email) || empty($staffNumber) || empty($role)) {
        // Function call: Script termination function with message.
// String: die() is a PHP built-in function that outputs "All fields are required." and stops execution.
// Halts the script and informs the user to complete all fields in the form.
        die("All fields are required.");
    }

    // Variable: SQL INSERT query string.
// String: Defines a query to insert Name, PhoneNumber, Email, StaffNumber, and Role into the 'staff' table, using placeholders (?).
// Records the new staff member’s details in the database for management and scheduling.
    $sql = "INSERT INTO staff (Name, PhoneNumber, Email, StaffNumber, Role) VALUES (?, ?, ?, ?, ?)";

    // Object: Prepared statement for database insertion.
// Object: $conn->prepare($sql) is a MySQLi method that creates a prepared statement object from the SQL query, where $conn is the MySQLi connection from databaseconnection.php.
// Prepares the query to insert staff data securely, using placeholders to prevent SQL injection.
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
// String: bind_param("sssss", $name, $phoneNumber, $email, $staffNumber, $role) is a MySQLi method that binds variables to the query’s placeholders, all as strings (s).
// Attaches staff data to the query safely, preventing SQL injection for secure insertion.
    $stmt->bind_param("sssss", $name, $phoneNumber, $email, $staffNumber, $role);

    // Conditional statement: Logic to execute the query and check success.
// Boolean check: $stmt->execute() is a MySQLi method that runs the prepared statement, returning TRUE on success or FALSE on failure.
// Outputs a success or error message based on whether the staff data was inserted.
    if ($stmt->execute()) {
        // Output statement: Success message output.
// String: echo outputs "Staff data submitted successfully!".
// Informs the user (e.g., admin) that the staff member was successfully added to the database.
        echo "Staff data submitted successfully!";
    } else {
        // Output statement: Error message output.
// String: echo outputs "Error: " concatenated with $stmt->error, a MySQLi property with the error message.
// Informs the user of a database insertion failure, providing details for debugging (e.g., duplicate staff number).
        echo "Error: " . $stmt->error;
    }

    // Method call: Statement closure function.
// String: close() is a MySQLi method that frees resources associated with the prepared statement ($stmt).
// Releases database resources after inserting staff data to maintain system efficiency.
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