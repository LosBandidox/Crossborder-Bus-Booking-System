<?php
// Include statement: Loads the database connection configuration file.
// String: require is a PHP statement that imports '../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity; halts execution if the file is missing.
// Establishes a connection to the database to insert or update a user record in the users table for the International Bus Booking System.
require '../../php/databaseconnection.php';

// Variable: Retrieves and decodes the JSON data from the HTTP POST request body.
// Object: file_get_contents('php://input') is a PHP function that reads the raw POST data (JSON string) sent by the client; json_decode(..., false) is a PHP function that converts the JSON string into a PHP object.
// Captures the user details sent by the client (e.g., via an AJAX POST request) for processing and insertion or updating in the database.
$data = json_decode(file_get_contents("php://input"));

// Variable: Stores the user’s full name from the JSON input.
// String: $data->name is a property of the JSON-decoded object containing the user’s name (e.g., 'John Doe').
// Captures the name to insert or update in the users table for identification.
$name = $data->name;

// Variable: Stores the user’s email address from the JSON input.
// String: $data->email is a property of the JSON-decoded object containing the user’s email (e.g., 'john@example.com').
// Captures the email to insert or update in the users table for contact and login purposes.
$email = $data->email;

// Variable: Stores the user’s phone number from the JSON input.
// String: $data->phoneNumber is a property of the JSON-decoded object containing the user’s phone number (e.g., '+254123456789').
// Captures the phone number to insert or update in the users table for contact purposes.
$phoneNumber = $data->phoneNumber;

// Variable: Stores the hashed password from the JSON input.
// String: password_hash() is a PHP built-in function that hashes $data->password using the PASSWORD_DEFAULT algorithm, generating a secure hash.
// Prepares the password for secure storage in the users table during insertion, ensuring it is not saved in plain text.
$password = password_hash($data->password, PASSWORD_DEFAULT);

// Variable: Stores the user’s role from the JSON input.
// String: $data->role is a property of the JSON-decoded object containing the user’s role (e.g., 'Admin', 'Staff').
// Captures the role to insert or update in the users table for access control purposes.
$role = $data->role;

// Conditional statement: Determines whether to insert or update based on the presence of a user ID.
// Object property check: $data->userId evaluates to true if a UserID is provided in the JSON input, indicating an update operation; otherwise, an insert operation is performed.
// Decides whether to update an existing user or create a new user in the users table.
if ($data->userId) {
    // Variable: SQL query string to update an existing user record.
    // String: Defines an UPDATE query for the users table, setting Name, Email, PhoneNumber, and Role to placeholders (?), with a WHERE clause targeting UserID = ?.
    // Specifies the fields to update for the user identified by UserID, using placeholders for secure data binding.
    $sql = "UPDATE users SET Name=?, Email=?, PhoneNumber=?, Role=? WHERE UserID=?";
    
    // Variable: Stores the prepared statement for the update query.
    // Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with placeholders, returning a statement object for binding and execution.
    // Prepares the query to safely update user data, reducing the risk of SQL injection.
    $stmt = $conn->prepare($sql);
    
    // Method call: Binds form data to the prepared statement’s placeholders.
    // String: bind_param("ssssi", ...) is a MySQLi method that binds $name, $email, $phoneNumber, and $role as strings ('s') and $data->userId as an integer ('i') to the placeholders in the query, in that order.
    // Securely links the form data to the query, preventing SQL injection by treating inputs as data, not code.
    $stmt->bind_param("ssssi", $name, $email, $phoneNumber, $role, $data->userId);
} else {
    // Variable: SQL query string to insert a new user record.
    // String: Defines an INSERT query into the users table, setting Name, Email, PhoneNumber, Password, and Role to placeholders (?).
    // Specifies the fields and values to add a new user record securely to the database.
    $sql = "INSERT INTO users (Name, Email, PhoneNumber, Password, Role) VALUES (?, ?, ?, ?, ?)";
    
    // Variable: Stores the prepared statement for the insert query.
    // Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with placeholders, returning a statement object for binding and execution.
    // Prepares the query to safely insert user data, reducing the risk of SQL injection.
    $stmt = $conn->prepare($sql);
    
    // Method call: Binds form data to the prepared statement’s placeholders.
    // String: bind_param("sssss", ...) is a MySQLi method that binds $name, $email, $phoneNumber, $password, and $role as strings ('s') to the placeholders in the query, in that order.
    // Securely links the form data to the query, preventing SQL injection by treating inputs as data, not code.
    $stmt->bind_param("sssss", $name, $email, $phoneNumber, $password, $role);
}

// Conditional statement: Checks if the insertion or update was successful.
// Boolean check: $stmt->execute() is a MySQLi method that runs the prepared statement, returning true on success or false on failure (e.g., duplicate email or constraint violation).
// Determines whether the user was saved or updated to send the appropriate response.
if ($stmt->execute()) {
    // Output statement: Sends a success response to the client.
    // String: echo outputs text, here a plain text message 'User saved successfully.'.
    // Informs the client’s JavaScript (e.g., on the admin interface) that the user was saved or updated successfully, allowing the interface to update (e.g., show a confirmation message).
    echo "User saved successfully.";
} else {
    // Output statement: Sends an error response to the client.
    // String: echo outputs text, here a plain text message 'Error: ' concatenated with $stmt->error, a MySQLi property containing the query error message (e.g., duplicate email).
    // Informs the client of the save or update failure with specific error details, enabling error handling or debugging.
    echo "Error: " . $stmt->error;
}

// Method call: Frees the prepared statement resources.
// String: close() is a MySQLi method that releases the prepared statement ($stmt), freeing memory.
// Ensures efficient resource management after the save or update operation is complete.
$stmt->close();

// Method call: Closes the database connection.
// String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open after the operation, maintaining system efficiency.
$conn->close();
?>