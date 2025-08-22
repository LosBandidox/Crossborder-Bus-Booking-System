<?php
// Function call: HTTP header setting function.
// String: header() is a PHP built-in function that sets an HTTP response header, here setting 'Content-Type: text/plain'.
// Sets the response format to plain text, ensuring compatibility with client-side JavaScript for processing responses.
header('Content-Type: text/plain');

// Include statement: File inclusion directive.
// String: include is a PHP statement that loads '../php/databaseconnection.php', a file that defines the $conn MySQLi connection object.
// Loads the database connection settings to verify tokens and update passwords in the International Bus Booking System’s database.
include('../php/databaseconnection.php');

// Conditional statement: Logic to verify the HTTP request method.
// String check: $_SERVER["REQUEST_METHOD"] is a superglobal array element that returns the request method (e.g., "POST"), compared to "POST" using !==.
// Ensures the script processes only POST form submissions, as token and password are sent via POST for security.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    // Output statement: Error message output.
// String: echo outputs "Invalid request method.".
// Informs the client that a non-POST request was used, enforcing secure form submission.
    echo "Invalid request method.";
    // Function call: Script termination function.
// String: exit() is a PHP built-in function that stops script execution.
// Halts the script to prevent processing with an invalid request method.
    exit();
}

// Variable: Password reset token storage.
// String: $_POST["token"] is a superglobal array element from the form submission, accessed with the null coalescing operator (??), returning an empty string if unset.
// Captures the token from the password reset link to verify the reset request.
$token = $_POST["token"] ?? '';

// Variable: New password storage.
// String: $_POST["password"] is a superglobal array element from the form submission, accessed with the null coalescing operator (??), returning an empty string if unset.
// Captures the user’s new password for hashing and storage.
$password = $_POST["password"] ?? '';

// Conditional statement: Logic to validate form inputs.
// Boolean checks: empty() is a PHP built-in function that tests if $token or $password is empty (e.g., "", null, or unset).
// Stops the script with an error if either field is missing, ensuring valid input for password reset.
if (empty($token) || empty($password)) {
    // Output statement: Error message output.
// String: echo outputs "Token and password are required.".
// Informs the client that both fields must be provided in the form.
    echo "Token and password are required.";
    // Function call: Script termination function.
// String: exit() is a PHP built-in function that stops script execution.
// Halts the script to prevent processing without required inputs.
    exit();
}

// Try-catch block: Exception handling structure.
// Structure: try contains database operations for token verification and password update; catch captures any Exception object thrown, storing it in $e.
// Handles errors (e.g., database issues) during the password reset process, ensuring robust execution.
try {
    // Variable: SQL SELECT query string.
// String: Defines a query to select email from the 'password_resets' table where token matches a placeholder (?) and expires_at is greater than the current time (NOW()).
// Verifies if the token is valid and not expired, retrieving the associated email.
    $sql = "SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW()";

    // Object: Prepared statement for token verification.
// Object: $conn->prepare($sql) is a MySQLi method that creates a prepared statement object from the SQL query, where $conn is the MySQLi connection from databaseconnection.php.
// Prepares the query to check the token securely, using a placeholder to prevent SQL injection.
    $stmt = $conn->prepare($sql);

    // Conditional statement: Logic to check statement preparation.
// Boolean check: Tests if $stmt is false, indicating preparation failure due to SQL errors or connection issues.
// Throws an exception with an error message if the query cannot be prepared, ensuring reliable execution.
    if (!$stmt) {
        // Function call: Exception throwing function.
// String: throw new Exception() creates a new Exception object with the message "Error preparing statement: " concatenated with $conn->error, a MySQLi property with the error message.
// Triggers the catch block to handle the database error gracefully.
        throw new Exception("Error preparing statement: " . $conn->error);
    }

    // Method call: Parameter binding function.
// String: bind_param("s", $token) is a MySQLi method that binds $token as a string (s) to the query’s placeholder (?).
// Attaches the token to the query safely, preventing SQL injection for secure verification.
    $stmt->bind_param("s", $token);

    // Method call: Query execution function.
// String: execute() is a MySQLi method that runs the prepared statement to query the database.
// Checks if the token exists and is still valid in the password_resets table.
    $stmt->execute();

    // Variable: Query result storage.
// Object: $stmt->get_result() is a MySQLi method that retrieves the result set from the executed prepared statement.
// Stores the result to check if the token was found and valid.
    $result = $stmt->get_result();

    // Method call: Statement closure function.
// String: close() is a MySQLi method that frees resources associated with the prepared statement ($stmt).
// Releases database resources after verifying the token to maintain system efficiency.
    $stmt->close();

    // Conditional statement: Logic to check if a valid token was found.
// Integer check: $result->num_rows is a MySQLi property that returns the number of rows in the result set, compared to 0.
// Stops the script with an error if no valid token is found, preventing invalid reset attempts.
    if ($result->num_rows === 0) {
        // Output statement: Error message output.
// String: echo outputs "Invalid or expired token.".
// Informs the client that the provided token is invalid or has expired.
        echo "Invalid or expired token.";
        // Function call: Script termination function.
// String: exit() is a PHP built-in function that stops script execution.
// Halts the script to prevent further processing with an invalid token.
        exit();
    }

    // Variable: Email storage.
// Array: $result->fetch_assoc() is a MySQLi method that retrieves a single row from the result set as an associative array; $row['email'] extracts the email.
// Captures the email associated with the valid token for password update.
    $row = $result->fetch_assoc();

    // Variable: Email storage continuation.
// String: $row['email'] is the value of the email column from the fetched row.
// Identifies the user whose password will be updated in the database.
    $email = $row['email'];

    // Variable: Hashed password storage.
// String: password_hash($password, PASSWORD_DEFAULT) is a PHP built-in function that hashes $password using the default algorithm (e.g., bcrypt).
// Creates a secure, encrypted version of the new password for safe storage in the database.
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Variable: SQL UPDATE query string.
// String: Defines a query to update the 'users' table, setting Password to a placeholder (?) where Email matches another placeholder (?).
// Updates the user’s password in the database with the new hashed password.
    $sql = "UPDATE users SET Password = ? WHERE Email = ?";

    // Object: Prepared statement for password update.
// Object: $conn->prepare($sql) is a MySQLi method that creates a prepared statement object from the SQL query.
// Prepares the query to update the password securely, using placeholders to prevent SQL injection.
    $stmt = $conn->prepare($sql);

    // Conditional statement: Logic to check statement preparation.
// Boolean check: Tests if $stmt is false, indicating preparation failure due to SQL errors or connection issues.
// Throws an exception with an error message if the query cannot be prepared, ensuring reliable execution.
    if (!$stmt) {
        // Function call: Exception throwing function.
// String: throw new Exception() creates a new Exception object with the message "Error preparing statement: " concatenated with $conn->error.
// Triggers the catch block to handle the database error gracefully.
        throw new Exception("Error preparing statement: " . $conn->error);
    }

    // Method call: Parameter binding function.
// String: bind_param("ss", $hashedPassword, $email) is a MySQLi method that binds $hashedPassword and $email as strings (s) to the query’s placeholders (?).
// Attaches the hashed password and email to the query safely, preventing SQL injection for secure update.
    $stmt->bind_param("ss", $hashedPassword, $email);

    // Method call: Query execution function.
// String: execute() is a MySQLi method that runs the prepared statement to update the password in the database.
// Updates the user’s password in the users table.
    $stmt->execute();

    // Method call: Statement closure function.
// String: close() is a MySQLi method that frees resources associated with the prepared statement ($stmt).
// Releases database resources after updating the password to maintain system efficiency.
    $stmt->close();

    // Variable: SQL DELETE query string.
// String: Defines a query to delete records from the 'password_resets' table where email matches a placeholder (?).
// Removes the used or expired token to prevent reuse and maintain database cleanliness.
    $sql = "DELETE FROM password_resets WHERE email = ?";

    // Object: Prepared statement for token deletion.
// Object: $conn->prepare($sql) is a MySQLi method that creates a prepared statement object from the SQL query.
// Prepares the query to delete the token securely, using a placeholder to prevent SQL injection.
    $stmt = $conn->prepare($sql);

    // Method call: Parameter binding function.
// String: bind_param("s", $email) is a MySQLi method that binds $email as a string (s) to the query’s placeholder (?).
// Attaches the email to the query safely, preventing SQL injection for secure deletion.
    $stmt->bind_param("s", $email);

    // Method call: Query execution function.
// String: execute() is a MySQLi method that runs the prepared statement to delete the token from the database.
// Removes the token record from the password_resets table.
    $stmt->execute();

    // Method call: Statement closure function.
// String: close() is a MySQLi method that frees resources associated with the prepared statement ($stmt).
// Releases database resources after deleting the token to maintain system efficiency.
    $stmt->close();

    // Output statement: Success message output.
// String: echo outputs "Password reset successfully!".
// Informs the client (e.g., JavaScript in ResetPasswordForm.html) that the password was successfully updated.
    echo "Password reset successfully!";
} catch (Exception $e) {
    // Output statement: Error message output.
// String: echo outputs the result of $e->getMessage(), a method of the Exception object that retrieves the error message.
// Informs the client of any errors during the password reset process (e.g., database issues).
    echo $e->getMessage();
}

// Method call: Connection closure function.
// String: close() is a MySQLi method that closes the database connection ($conn).
// Frees database resources after all operations, ensuring no connections remain open.
$conn->close();
?>