<?php
// Include: Loads the database connection configuration
// File: databaseconnection.php defines $conn for database access
// Establishes a connection to the MySQL database
include 'databaseconnection.php';

// Conditional statement: Verifies the HTTP request method.
// Superglobal: Uses $_SERVER["REQUEST_METHOD"] to check for a POST request.
// Ensures the script processes only form submissions.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Variable: Stores the user’s name from the POST request.
    // String: Retrieved from $_POST["name"].
    // Captures the name input for registration.
    $name = $_POST["name"];

    // Variable: Stores the user’s email from the POST request.
    // String: Retrieved from $_POST["email"].
    // Captures the email input for registration.
    $email = $_POST["email"];

    // Variable: Stores the user’s phone number from the POST request.
    // String: Retrieved from $_POST["phoneNumber"].
    // Captures the phone number input for registration.
    $phoneNumber = $_POST["phoneNumber"];

    // Variable: Stores the user’s password from the POST request.
    // String: Retrieved from $_POST["password"].
    // Captures the password input for registration.
    $password = $_POST["password"];

    // Variable: Stores the user’s role from the POST request.
    // String: Retrieved from $_POST["role"].
    // Specifies the user’s role (e.g., Customer, Admin).
    $role = $_POST["role"];

    // Conditional statement: Validates form data for completeness.
    // Function calls: Uses empty() to check if $name, $email, $phoneNumber, $password, or $role is unset or empty.
    // Terminates execution with an error message if any field is missing.
    if (empty($name) || empty($email) || empty($phoneNumber) || empty($password) || empty($role)) {
        die("All fields are required.");
    }

    // Variable: Stores the hashed password.
    // String: Generated using password_hash($password, PASSWORD_DEFAULT).
    // Creates a secure hash of the password for storage.
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // String: SQL INSERT query with placeholders (?).
    // Structure: Inserts Name, Email, PhoneNumber, Password, and Role into the 'users' table.
    // Records a new user entry in the database.
    $sql = "INSERT INTO users (Name, Email, PhoneNumber, Password, Role) VALUES (?, ?, ?, ?, ?)";

    // Object: Creates a prepared statement for secure query execution.
    // Method call: Uses $conn->prepare($sql) to prepare the SQL query.
    // Binds the query for parameter substitution.
    $stmt = $conn->prepare($sql);

    // Conditional statement: Checks if statement preparation succeeded.
    // Comparison: Tests if $stmt is false to detect errors.
    // Terminates execution with an error message if preparation fails.
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Method call: Binds variables to the prepared statement’s placeholders.
    // String: 'sssss' specifies types as strings for $name, $email, $phoneNumber, $hashedPassword, and $role.
    // Links variables to the query for safe execution.
    $stmt->bind_param("sssss", $name, $email, $phoneNumber, $hashedPassword, $role);

    // Conditional statement: Executes the prepared statement and checks the result.
    // Method call: Uses $stmt->execute() to insert the data.
    // Outputs a success or error message based on the outcome.
    if ($stmt->execute()) {
        // Output statement: Sends a success message.
        // String: Indicates the user was registered successfully.
        // Informs the user of successful registration.
        echo "User registered successfully!";
    } else {
        // Output statement: Sends an error message.
        // String: Includes $stmt->error for details.
        // Informs the user of the failure.
        echo "Error: " . $stmt->error;
    }

    // Method call: Closes the prepared statement.
    // Frees database resources associated with the statement.
    $stmt->close();

    // Method call: Closes the database connection.
    // Frees database resources.
    $conn->close();
} else {
    // Output statement: Sends an error message.
    // String: Indicates the request method is not POST.
    // Informs the user of an invalid request.
    echo "Invalid request method.";
}
?>