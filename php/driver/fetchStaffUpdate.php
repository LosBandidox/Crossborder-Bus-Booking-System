<?php
// Function call: Starts or resumes a PHP session to access session data.
// String: session_start() is a PHP built-in function that initializes a new session or resumes an existing one, enabling access to $_SESSION variables.
// Allows retrieval of the logged-in staff’s email stored in the session for authentication and updating purposes in the International Bus Booking System.
session_start();

// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to update staff profile details for the International Bus Booking System’s staff profile interface.
include '../databaseconnection.php';

// Variable: Stores the logged-in user’s email from the session.
// String: $_SESSION["Email"] is a superglobal array element containing the email of the logged-in user.
// Captures the email to identify which staff member’s profile details to update in the database.
$email = $_SESSION["Email"];

// Conditional statement: Checks if the HTTP request method is POST to validate form submission.
// String comparison: $_SERVER["REQUEST_METHOD"] is a superglobal variable that contains the request method; checks if it equals "POST".
// Ensures the update request is valid and initiated from a form submission, preventing unauthorized or incorrect requests.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Variable: Stores the staff member’s updated name from the form submission.
    // String: $_POST["name"] is a superglobal array element containing the name submitted via the POST request.
    // Captures the updated name to modify the staff record in the database.
    $name = $_POST["name"];
    
    // Variable: Stores the staff member’s updated phone number from the form submission.
    // String: $_POST["phone"] is a superglobal array element containing the phone number submitted via the POST request.
    // Captures the updated phone number to modify the staff record in the database.
    $phone = $_POST["phone"];
    
    // Variable: Stores the new password from the form submission, if provided.
    // String: $_POST["password"] is a superglobal array element containing the optional password submitted via the POST request.
    // Captures the new password for updating the users table, if specified.
    $password = $_POST["password"];
    
    // Conditional statement: Checks if required fields (name and phone) are provided.
    // Function call: empty() is a PHP built-in function that checks if $name or $phone is empty (e.g., an empty string).
    // Ensures all required fields are filled before attempting to update the staff record, preventing incomplete updates.
    if (empty($name) || empty($phone)) {
        // Output statement: Sends a JSON error response to the client.
        // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ["status" => "error", "message" => "Name and phone number are required"] to a JSON string, a data format for web communication.
        // Informs the client’s JavaScript that required fields are missing, allowing error handling (e.g., displaying an error message to the user).
        echo json_encode(["status" => "error", "message" => "Name and phone number are required"]);
        
        // Method call: Closes the database connection.
        // String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
        // Ensures no database connections remain open after the error, maintaining system efficiency and resource management.
        $conn->close();
        
        // Function call: Terminates script execution immediately.
        // String: exit() is a PHP built-in function that stops the script from running further.
        // Halts processing to prevent further actions after an invalid request.
        exit();
    }
    
    // Variable: SQL query string to update a specific staff record.
    // String: Defines an UPDATE query to set Name = ? and PhoneNumber = ? in the staff table WHERE Email = ?, using placeholders for secure parameter binding.
    // Updates the staff member’s details in the database for the specified email.
    $sql = "UPDATE staff SET Name = ?, PhoneNumber = ? WHERE Email = ?";
    
    // Variable: Stores the prepared statement for staff update.
    // Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with placeholders, returning a statement object for binding and execution.
    // Prepares the query to safely update the staff record, reducing the risk of SQL injection.
    $stmt = $conn->prepare($sql);
    
    // Conditional statement: Checks if the prepared statement was successfully created.
    // Boolean check: Tests if $stmt is false, indicating a preparation failure (e.g., syntax error or database issue).
    // Ensures the query is valid before proceeding, preventing execution of a faulty statement.
    if ($stmt === false) {
        // Output statement: Sends a JSON error response to the client.
        // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ["status" => "error", "message" => "Error preparing statement: " . $conn->error] to a JSON string, a data format for web communication.
        // Informs the client’s JavaScript of the preparation failure, allowing error handling (e.g., displaying a system error message).
        echo json_encode(["status" => "error", "message" => "Error preparing statement: " . $conn->error]);
        
        // Method call: Closes the database connection.
        // String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
        // Ensures no database connections remain open after the error, maintaining system efficiency and resource management.
        $conn->close();
        
        // Function call: Terminates script execution immediately.
        // String: exit() is a PHP built-in function that stops the script from running further.
        // Halts processing to prevent further actions after a preparation failure.
        exit();
    }
    
    // Method call: Binds parameters to the prepared statement.
    // String: bind_param("sss", $name, $phone, $email) is a MySQLi method that binds $name, $phone, and $email as strings ('sss') to the placeholders in the query, sanitizing the inputs.
    // Securely links the input values to the query, preventing SQL injection by treating the inputs as data, not code.
    $stmt->bind_param("sss", $name, $phone, $email);
    
    // Method call: Executes the staff update query.
    // String: execute() is a MySQLi method that runs the prepared statement, updating the staff record matching the email.
    // Modifies the staff record in the staff table with the new name and phone number.
    $stmt->execute();
    
    // Conditional statement: Checks if the update affected any staff records.
    // Integer check: $stmt->affected_rows is a MySQLi property that indicates the number of rows modified; greater than zero means the update was successful.
    // Verifies that the staff record was updated before proceeding with the response or password update.
    if ($stmt->affected_rows > 0) {
        // Variable: Stores the response data with updated staff details.
        // Array: Creates an associative array with keys "status" and "staff", where "staff" contains the updated Name, PhoneNumber, and Email.
        // Prepares the success response to confirm the update to the client.
        $response = ["status" => "success", "staff" => ["Name" => $name, "PhoneNumber" => $phone, "Email" => $email]];
        
        // Conditional statement: Checks if a new password was provided.
        // Function call: empty() is a PHP built-in function that checks if $password is empty (e.g., an empty string).
        // Determines whether to update the password in the users table for the staff member.
        if (!empty($password)) {
            // Variable: Stores the hashed password for secure storage.
            // String: password_hash() is a PHP built-in function that hashes $password using the PASSWORD_DEFAULT algorithm, generating a secure hash.
            // Prepares the password for secure storage in the users table to maintain security standards.
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Variable: SQL query string to update the password in the users table.
            // String: Defines an UPDATE query to set Password = ? in the users table WHERE Email = ?, using placeholders for secure parameter binding.
            // Updates the user’s password in the users table for the specified email.
            $sqlPassword = "UPDATE users SET Password = ? WHERE Email = ?";
            
            // Variable: Stores the prepared statement for password update.
            // Object: $conn->prepare($sqlPassword) is a MySQLi method that compiles the SQL query with placeholders, returning a statement object for binding and execution.
            // Prepares the query to safely update the password, reducing the risk of SQL injection.
            $stmtPassword = $conn->prepare($sqlPassword);
            
            // Conditional statement: Checks if the prepared statement for password update was successfully created.
            // Boolean check: Tests if $stmtPassword is false, indicating a preparation failure (e.g., syntax error or database issue).
            // Ensures the password query is valid before proceeding, preventing execution of a faulty statement.
            if ($stmtPassword === false) {
                // Output statement: Sends a JSON error response to the client.
                // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ["status" => "error", "message" => "Error preparing password statement: " . $conn->error] to a JSON string, a data format for web communication.
                // Informs the client’s JavaScript of the password preparation failure, allowing error handling (e.g., displaying a system error message).
                echo json_encode(["status" => "error", "message" => "Error preparing password statement: " . $conn->error]);
                
                // Method call: Frees the staff statement resources.
                // String: close() is a MySQLi method that releases the prepared statement ($stmt), freeing memory.
                // Ensures efficient resource management after the error.
                $stmt->close();
                
                // Method call: Closes the database connection.
                // String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
                // Ensures no database connections remain open after the error, maintaining system efficiency and resource management.
                $conn->close();
                
                // Function call: Terminates script execution immediately.
                // String: exit() is a PHP built-in function that stops the script from running further.
                // Halts processing to prevent further actions after a preparation failure.
                exit();
            }
            
            // Method call: Binds parameters to the prepared statement for password update.
            // String: bind_param("ss", $hashedPassword, $email) is a MySQLi method that binds $hashedPassword and $email as strings ('ss') to the placeholders in the query, sanitizing the inputs.
            // Securely links the hashed password and email to the query, preventing SQL injection by treating the inputs as data, not code.
            $stmtPassword->bind_param("ss", $hashedPassword, $email);
            
            // Method call: Executes the password update query.
            // String: execute() is a MySQLi method that runs the prepared statement, updating the password in the users table.
            // Modifies the user’s password for the specified email.
            $stmtPassword->execute();
            
            // Method call: Frees the password statement resources.
            // String: close() is a MySQLi method that releases the prepared statement ($stmtPassword), freeing memory.
            // Ensures efficient resource management after the password update is complete.
            $stmtPassword->close();
        }
        
        // Output statement: Sends a JSON success response with the updated staff details to the client.
        // String: echo outputs text; json_encode() is a PHP built-in function that converts the $response array to a JSON string, a data format for web communication.
        // Delivers the updated staff details to the client for display, such as confirming the update in the staff profile interface.
        echo json_encode($response);
    } else {
        // Output statement: Sends a JSON error response to the client.
        // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ["status" => "error", "message" => "No staff updated"] to a JSON string, a data format for web communication.
        // Informs the client’s JavaScript that no staff record was updated, allowing error handling (e.g., displaying an error message to the user).
        echo json_encode(["status" => "error", "message" => "No staff updated"]);
    }
    
    // Method call: Frees the staff statement resources.
    // String: close() is a MySQLi method that releases the prepared statement ($stmt), freeing memory.
    // Ensures efficient resource management after the staff update is complete.
    $stmt->close();
    
    // Method call: Closes the database connection.
    // String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
    // Ensures no database connections remain open after the query, maintaining system efficiency and resource management.
    $conn->close();
} else {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ["status" => "error", "message" => "Invalid request method"] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that the request method is invalid, allowing error handling (e.g., displaying an error message to the user).
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
    
    // Method call: Closes the database connection.
    // String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
    // Ensures no database connections remain open after the error, maintaining system efficiency and resource management.
    $conn->close();
}
?>