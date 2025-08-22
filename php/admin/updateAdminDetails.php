<?php
// Function call: Sets the HTTP response header to specify JSON output.
// String: header() is a PHP built-in function that sets an HTTP header, here setting 'Content-Type: application/json', which informs the browser that the response is JSON data.
// Ensures the client’s JavaScript (e.g., on the admin dashboard) can parse the response as JSON, maintaining compatibility with web standards in the International Bus Booking System.
header('Content-Type: application/json');

// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to update admin details in the users table for the International Bus Booking System.
include('../../php/databaseconnection.php');

// Function call: Starts or resumes a PHP session to access user data.
// String: session_start() is a PHP built-in function that initializes or resumes a session, allowing access to $_SESSION variables.
// Enables retrieval of the logged-in admin’s email for identifying the user to update.
session_start();

// Variable: Stores the logged-in admin’s email from the session.
// String: $_SESSION["Email"] is a superglobal array element containing the admin’s email, with ?? '' providing an empty string if not set.
// Identifies the admin user to update, ensuring the correct record is modified.
$email = $_SESSION["Email"] ?? '';

// Conditional statement: Validates the request method and user authentication.
// Boolean check: $_SERVER['REQUEST_METHOD'] !== 'POST' checks if the request is not POST; empty($email) checks if the admin is not logged in.
// Ensures the request is valid and the user is authenticated before proceeding with the update.
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($email)) {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ["status" => "error", "message" => "Invalid request or not logged in"] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that the request is invalid or the user is not logged in, allowing error handling (e.g., redirecting to a login page).
    echo json_encode(["status" => "error", "message" => "Invalid request or not logged in"]);
    
    // Function call: Terminates script execution immediately.
    // String: exit() is a PHP built-in function that stops the script from running further.
    // Halts processing to prevent unauthorized updates.
    exit();
}

// Variable: Stores the admin’s name from the POST input.
// String: $_POST['name'] is a superglobal array element containing the admin’s full name, with ?? '' providing an empty string if not set.
// Updates the admin’s name, ensuring accurate personal information.
$name = $_POST['name'] ?? '';

// Variable: Stores the admin’s phone number from the POST input.
// String: $_POST['phone'] is a superglobal array element containing the admin’s contact phone number, with ?? '' providing an empty string if not set.
// Updates the admin’s phone number, ensuring accurate contact information.
$phone = $_POST['phone'] ?? '';

// Variable: Stores the admin’s new password from the POST input.
// String: $_POST['password'] is a superglobal array element containing the admin’s new password (optional), with ?? '' providing an empty string if not set.
// Conditionally prepares the password for hashing if provided, supporting secure password updates.
$password = $_POST['password'] ?? '';

// Conditional statement: Validates required input fields.
// Boolean check: empty($name) || empty($phone) tests if either the name or phone number is empty.
// Ensures the required fields are provided before proceeding with the update.
if (empty($name) || empty($phone)) {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ["status" => "error", "message" => "Name and Phone Number are required"] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that required fields are missing, allowing error handling (e.g., displaying a validation error).
    echo json_encode(["status" => "error", "message" => "Name and Phone Number are required"]);
    
    // Function call: Terminates script execution immediately.
    // String: exit() is a PHP built-in function that stops the script from running further.
    // Halts processing to prevent incomplete updates.
    exit();
}

// Try-catch block: Manages database operations and handles exceptions.
// String: try initiates a block where exceptions can be caught; catch handles errors thrown during execution.
// Safely executes database operations, capturing errors for proper response handling.
try {
    // Conditional statement: Determines the SQL query based on whether a password update is included.
    // Boolean check: !empty($password) tests if a password is provided, indicating a password update is requested.
    // Selects the appropriate SQL query and parameter binding to handle cases with or without a password update.
    if (!empty($password)) {
        // Variable: SQL query string to update the admin record with password.
        // String: Defines an UPDATE query for the users table, setting Name, PhoneNumber, and Password to placeholders (?), with a WHERE clause targeting Email = ?.
        // Specifies the fields, including password, to update for the admin identified by Email, using placeholders for secure data binding.
        $sql = "UPDATE users SET Name = ?, PhoneNumber = ?, Password = ? WHERE Email = ?";
        
        // Variable: Stores the prepared statement for the update query with password.
        // Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with placeholders, returning a statement object for binding and execution.
        // Prepares the query to safely update the admin record, including password, reducing the risk of SQL injection.
        $stmt = $conn->prepare($sql);
        
        // Conditional statement: Checks if the prepared statement was successfully created.
        // Boolean check: $stmt === false indicates a preparation failure (e.g., syntax error or database issue).
        // Throws an exception to handle preparation errors gracefully.
        if ($stmt === false) {
            // Function call: Throws an exception with an error message.
            // String: throw new Exception() creates a new Exception object with the message "Error preparing statement: " concatenated with $conn->error, a MySQLi property containing the error description.
            // Triggers the catch block to handle the preparation failure.
            throw new Exception("Error preparing statement: " . $conn->error);
        }
        
        // Variable: Stores the hashed password for secure storage.
        // String: password_hash() is a PHP built-in function that hashes $password using the PASSWORD_DEFAULT algorithm, generating a secure hash.
        // Prepares the password for secure database storage, enhancing security.
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Method call: Binds input variables to the prepared statement’s placeholders.
        // String: bind_param("ssss", ...) is a MySQLi method that binds variables to the placeholders in order: 's' (string) for $name, $phone, $hashedPassword, and $email.
        // Securely links the input data to the query, preventing SQL injection by treating inputs as data, not code.
        $stmt->bind_param("ssss", $name, $phone, $hashedPassword, $email);
    } else {
        // Variable: SQL query string to update the admin record without password.
        // String: Defines an UPDATE query for the users table, setting Name and PhoneNumber to placeholders (?), with a WHERE clause targeting Email = ?.
        // Specifies the fields, excluding password, to update for the admin identified by Email, using placeholders for secure data binding.
        $sql = "UPDATE users SET Name = ?, PhoneNumber = ? WHERE Email = ?";
        
        // Variable: Stores the prepared statement for the update query without password.
        // Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with placeholders, returning a statement object for binding and execution.
        // Prepares the query to safely update the admin record, excluding password, reducing the risk of SQL injection.
        $stmt = $conn->prepare($sql);
        
        // Conditional statement: Checks if the prepared statement was successfully created.
        // Boolean check: $stmt === false indicates a preparation failure (e.g., syntax error or database issue).
        // Throws an exception to handle preparation errors gracefully.
        if ($stmt === false) {
            // Function call: Throws an exception with an error message.
            // String: throw new Exception() creates a new Exception object with the message "Error preparing statement: " concatenated with $conn->error, a MySQLi property containing the error description.
            // Triggers the catch block to handle the preparation failure.
            throw new Exception("Error preparing statement: " . $conn->error);
        }
        
        // Method call: Binds input variables to the prepared statement’s placeholders.
        // String: bind_param("sss", ...) is a MySQLi method that binds variables to the placeholders in order: 's' (string) for $name, $phone, and $email.
        // Securely links the input data to the query, preventing SQL injection by treating inputs as data, not code.
        $stmt->bind_param("sss", $name, $phone, $email);
    }
    
    // Method call: Executes the admin update query.
    // String: execute() is a MySQLi method that runs the prepared statement, updating the admin record matching the Email.
    // Modifies the admin record in the users table with the new details.
    $stmt->execute();
    
    // Conditional statement: Checks if the update affected any admin records.
    // Integer check: $stmt->affected_rows is a MySQLi property that indicates the number of rows modified; greater than zero means the update was successful.
    // Verifies that the admin record was updated before sending the success response.
    if ($stmt->affected_rows > 0) {
        // Variable: Stores the updated admin details for the response.
        // Array: Creates an associative array with keys "Name", "PhoneNumber", and "Email", containing the updated $name, $phone, and $email values.
        // Prepares the updated admin details to include in the success response.
        $updatedUser = ["Name" => $name, "PhoneNumber" => $phone, "Email" => $email];
        
        // Output statement: Sends a JSON success response to the client.
        // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ["status" => "success", "user" => $updatedUser] to a JSON string, a data format for web communication.
        // Informs the client’s JavaScript that the admin update was successful, allowing the admin dashboard to refresh with updated user details.
        echo json_encode(["status" => "success", "user" => $updatedUser]);
    } else {
        // Output statement: Sends a JSON error response to the client.
        // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ["status" => "error", "message" => "No changes made or user not found"] to a JSON string, a data format for web communication.
        // Informs the client’s JavaScript that no admin record was updated or found, enabling error handling (e.g., displaying an error message to the user).
        echo json_encode(["status" => "error", "message" => "No changes made or user not found"]);
    }
    
    // Method call: Frees the prepared statement resources.
    // String: close() is a MySQLi method that releases the prepared statement ($stmt), freeing memory.
    // Ensures efficient resource management after the update operation is complete.
    $stmt->close();
} catch (Exception $e) {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ["status" => "error", "message" => $e->getMessage()] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript of the database operation failure, providing the exception message for debugging or user feedback.
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}

// Method call: Closes the database connection.
// String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open after operations, maintaining system efficiency and resource management.
$conn->close();
?>