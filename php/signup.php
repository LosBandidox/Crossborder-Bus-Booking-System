<?php
// Function call: HTTP header setting function.
// String: header() is a PHP built-in function that sets an HTTP response header, here setting 'Content-Type: text/plain'.
// Sets the response format to plain text, ensuring the client (e.g., JavaScript) receives simple text messages for registration results.
header('Content-Type: text/plain');

// Include statement: File inclusion directive.
// String: include() is a PHP statement that loads '../php/databaseconnection.php', a file that defines the $conn MySQLi connection object.
// Loads the database connection settings to store user and customer data in the International Bus Booking System’s database.
include('../php/databaseconnection.php');

// Conditional statement: Logic to verify the HTTP request method.
// String check: $_SERVER["REQUEST_METHOD"] is a superglobal array element that returns the request method (e.g., "POST"), compared to "POST" using !==.
// Ensures the script processes only POST form submissions, as registration data is sent via POST for security.
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

// Variable: User name storage.
// String: $_POST["name"] is a superglobal array element from the form submission, accessed with the null coalescing operator (??), returning an empty string if unset.
// Captures the user’s full name input for registration in the users and customer tables.
$name = $_POST["name"] ?? '';

// Variable: User email storage.
// String: $_POST["email"] is a superglobal array element from the form submission, accessed with the null coalescing operator (??), returning an empty string if unset.
// Captures the user’s email input for unique identification and login purposes.
$email = $_POST["email"] ?? '';

// Variable: User phone number storage.
// String: $_POST["phoneNumber"] is a superglobal array element from the form submission, accessed with the null coalescing operator (??), returning an empty string if unset.
// Captures the user’s phone number input for contact and verification purposes.
$phoneNumber = $_POST["phoneNumber"] ?? '';

// Variable: User password storage.
// String: $_POST["password"] is a superglobal array element from the form submission, accessed with the null coalescing operator (??), returning an empty string if unset.
// Captures the user’s password input for securing their account.
$password = $_POST["password"] ?? '';

// Variable: User role storage.
// String: $_POST["role"] is a superglobal array element from the form submission, accessed with the null coalescing operator (??), defaulting to 'Customer' if unset.
// Specifies the user’s role (e.g., Customer), defaulting to Customer for standard registrations.
$role = $_POST["role"] ?? 'Customer';

// Conditional statement: Logic to validate form inputs.
// Boolean checks: empty() is a PHP built-in function that tests if $name, $email, $phoneNumber, or $password is empty (e.g., "", null, or unset).
// Stops the script with an error if any required field is missing, ensuring complete registration data.
if (empty($name) || empty($email) || empty($phoneNumber) || empty($password)) {
    // Output statement: Error message output.
    // String: echo outputs "All fields are required.".
    // Informs the client that required fields are missing, prompting them to complete the form.
    echo "All fields are required.";
    // Function call: Script termination function.
    // String: exit() is a PHP built-in function that stops script execution.
    // Halts the script to prevent processing with incomplete data.
    exit();
}

// Variable: Hashed password storage.
// String: password_hash($password, PASSWORD_DEFAULT) is a PHP built-in function that creates a secure hash of the password using the default algorithm (e.g., bcrypt).
// Secures the user’s password by storing a hash instead of plain text in the database.
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Try-catch block: Exception handling structure.
// Structure: try contains database operations; catch captures any Exception object thrown, storing it in $e.
// Handles errors (e.g., duplicate email) during user and customer data insertion, ensuring robust registration.
try {
    // Variable: SQL INSERT query string for users table.
    // String: Defines a query to insert Name, Email, PhoneNumber, Password, and Role into the 'users' table, using placeholders (?).
    // Records a new user in the database for authentication and role-based access.
    $sql_users = "INSERT INTO users (Name, Email, PhoneNumber, Password, Role) VALUES (?, ?, ?, ?, ?)";

    // Object: Prepared statement for users table insertion.
    // Object: $conn->prepare($sql_users) is a MySQLi method that creates a prepared statement object from the SQL query, where $conn is the MySQLi connection from databaseconnection.php.
    // Prepares the query to insert user data securely, using placeholders to prevent SQL injection.
    $stmt_users = $conn->prepare($sql_users);

    // Conditional statement: Logic to check statement preparation for users table.
    // Boolean check: Tests if $stmt_users is false, indicating preparation failure due to SQL errors or connection issues.
    // Throws an exception if the query cannot be prepared, ensuring reliable insertion.
    if ($stmt_users === false) {
        // Exception: Error throwing statement.
        // Object: throw new Exception() creates an Exception object with a message ("Error preparing statement for users: " concatenated with $conn->error, a MySQLi property with the error message).
        // Triggers the catch block to handle the preparation failure for the users table.
        throw new Exception("Error preparing statement for users: " . $conn->error);
    }

    // Method call: Parameter binding function for users table.
    // String: bind_param("sssss", $name, $email, $phoneNumber, $hashedPassword, $role) is a MySQLi method that binds variables as strings (s) to the query’s placeholders (?).
    // Attaches user data to the query safely, preventing SQL injection for secure insertion.
    $stmt_users->bind_param("sssss", $name, $email, $phoneNumber, $hashedPassword, $role);

    // Conditional statement: Logic to execute the users table insertion.
    // Boolean check: $stmt_users->execute() is a MySQLi method that runs the prepared statement, returning FALSE on failure.
    // Throws an exception if the insertion fails, ensuring the user record is created successfully before proceeding.
    if (!$stmt_users->execute()) {
        // Exception: Error throwing statement.
        // Object: throw new Exception() creates an Exception object with a message ("Error inserting into users: " concatenated with $stmt_users->error, a MySQLi property with the error message).
        // Triggers the catch block to handle the insertion failure for the users table (e.g., duplicate email).
        throw new Exception("Error inserting into users: " . $stmt_users->error);
    }

    // Method call: Statement closure function for users table.
    // String: close() is a MySQLi method that frees resources associated with the prepared statement ($stmt_users).
    // Releases database resources after inserting user data to maintain system efficiency.
    $stmt_users->close();

    // Variable: SQL INSERT query string for customer table.
    // String: Defines a query to insert Name, Email, PhoneNumber, and NULL for Gender, PassportNumber, and Nationality into the 'customer' table, using placeholders (?).
    // Records the user as a customer to enable access to customer-specific features like booking.
    $sql_customer = "INSERT INTO customer (Name, Email, PhoneNumber, Gender, PassportNumber, Nationality) VALUES (?, ?, ?, NULL, NULL, NULL)";

    // Object: Prepared statement for customer table insertion.
    // Object: $conn->prepare($sql_customer) is a MySQLi method that creates a prepared statement object from the SQL query.
    // Prepares the query to insert customer data securely, using placeholders to prevent SQL injection.
    $stmt_customer = $conn->prepare($sql_customer);

    // Conditional statement: Logic to check statement preparation for customer table.
    // Boolean check: Tests if $stmt_customer is false, indicating preparation failure due to SQL errors or connection issues.
    // Throws an exception if the query cannot be prepared, ensuring reliable insertion.
    if ($stmt_customer === false) {
        // Exception: Error throwing statement.
        // Object: throw new Exception() creates an Exception object with a message ("Error preparing statement for customer: " concatenated with $conn->error).
        // Triggers the catch block to handle the preparation failure for the customer table.
        throw new Exception("Error preparing statement for customer: " . $conn->error);
    }

    // Method call: Parameter binding function for customer table.
    // String: bind_param("sss", $name, $email, $phoneNumber) is a MySQLi method that binds variables as strings (s) to the query’s placeholders (?).
    // Attaches customer data to the query safely, preventing SQL injection for secure insertion.
    $stmt_customer->bind_param("sss", $name, $email, $phoneNumber);

    // Conditional statement: Logic to execute the customer table insertion.
    // Boolean check: $stmt_customer->execute() is a MySQLi method that runs the prepared statement, returning TRUE on success or FALSE on failure.
    // Outputs a success message if the insertion succeeds, or throws an exception if it fails.
    if ($stmt_customer->execute()) {
        // Output statement: Success message output.
        // String: echo outputs "User registered successfully!".
        // Informs the client that the user was successfully registered in both the users and customer tables.
        echo "User registered successfully!";
    } else {
        // Exception: Error throwing statement.
        // Object: throw new Exception() creates an Exception object with a message ("Error inserting into customer: " concatenated with $stmt_customer->error, a MySQLi property with the error message).
        // Triggers the catch block to handle the insertion failure for the customer table.
        throw new Exception("Error inserting into customer: " . $stmt_customer->error);
    }

    // Method call: Statement closure function for customer table.
    // String: close() is a MySQLi method that frees resources associated with the prepared statement ($stmt_customer).
    // Releases database resources after inserting customer data to maintain system efficiency.
    $stmt_customer->close();
} catch (Exception $e) {
    // Output statement: Error message output.
    // String: echo outputs $e->getMessage(), a method of the Exception object that retrieves the error message (e.g., duplicate email error).
    // Informs the client of the specific error that occurred during registration, such as a database issue.
    echo $e->getMessage();
}

// Method call: Connection closure function.
// String: close() is a MySQLi method that closes the database connection ($conn).
// Frees database resources after all operations, ensuring no connections remain open.
$conn->close();
?>