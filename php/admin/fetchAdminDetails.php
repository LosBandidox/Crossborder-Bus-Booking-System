<?php
// Function call: Sets the HTTP response header to specify JSON output.
// String: header() is a PHP built-in function that sets an HTTP header, here setting 'Content-Type: application/json', which informs the browser that the response is JSON data.
// Ensures the client’s JavaScript can parse the user details as JSON, maintaining compatibility with web standards for the International Bus Booking System’s admin dashboard.
header('Content-Type: application/json');

// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to query admin user details for the International Bus Booking System’s admin interface.
include('../../php/databaseconnection.php');

// Function call: Starts or resumes a PHP session to access session data.
// String: session_start() is a PHP built-in function that initializes a new session or resumes an existing one, enabling access to $_SESSION variables.
// Allows retrieval of the logged-in admin’s email stored in the session for authentication and querying purposes.
session_start();

// Variable: Stores the logged-in user’s email from the session.
// String: $_SESSION["Email"] is a superglobal array element containing the email of the logged-in user, with the null coalescing operator (??) providing an empty string if not set.
// Captures the email to identify which admin user’s details to retrieve from the database.
$email = $_SESSION["Email"] ?? '';

// Conditional statement: Checks if the email is empty, indicating no logged-in user.
// Function call: empty() is a PHP built-in function that checks if $email is empty (e.g., an empty string).
// Ensures a valid email is present before querying the database, preventing unauthorized access to user details.
if (empty($email)) {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ["status" => "error", "message" => "Not logged in"] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that the user is not logged in, allowing error handling (e.g., redirecting to a login page).
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    
    // Function call: Terminates script execution immediately.
    // String: exit() is a PHP built-in function that stops the script from running further.
    // Halts processing to prevent further actions after an unauthorized request.
    exit();
}

// Variable: SQL query string to retrieve a specific user record.
// String: Defines a SELECT query to fetch Name, PhoneNumber, Email, and Role from the users table WHERE Email = ?, using a placeholder (?) for secure parameter binding.
// Retrieves the specific admin user’s details for display on the admin dashboard or profile interface.
$sql = "SELECT Name, PhoneNumber, Email, Role FROM users WHERE Email = ?";

// Variable: Stores the prepared statement for user retrieval.
// Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with the placeholder, returning a statement object for binding and execution.
// Prepares the query to safely retrieve the user record, reducing the risk of SQL injection.
$stmt = $conn->prepare($sql);

// Conditional statement: Checks if the prepared statement was successfully created.
// Boolean check: Tests if $stmt is false, indicating a preparation failure (e.g., syntax error or database issue).
// Ensures the query is valid before proceeding, preventing execution of a faulty statement.
if ($stmt === false) {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ["status" => "error", "message" => "Error preparing statement: " . $conn->error] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript of the preparation failure, allowing error handling (e.g., displaying a system error message).
    echo json_encode(["status" => "error", "message" => "Error preparing statement: " . $conn->error]);
    
    // Function call: Terminates script execution immediately.
    // String: exit() is a PHP built-in function that stops the script from running further.
    // Halts processing to prevent further actions after a preparation failure.
    exit();
}

// Method call: Binds the email to the prepared statement.
// String: bind_param("s", $email) is a MySQLi method that binds $email as a string ('s') to the placeholder in the query, sanitizing the input.
// Securely links the email to the query, preventing SQL injection by treating the input as data, not code.
$stmt->bind_param("s", $email);

// Method call: Executes the user retrieval query.
// String: execute() is a MySQLi method that runs the prepared statement, querying the user record matching the email.
// Retrieves the user record from the users table for processing.
$stmt->execute();

// Variable: Stores the result set from the executed query.
// Object: $stmt->get_result() is a MySQLi method that retrieves the result set from the prepared statement, allowing row fetching.
// Holds the user data for further processing into a JSON response.
$result = $stmt->get_result();

// Conditional statement: Checks if exactly one user record was returned.
// Integer check: $result->num_rows is a MySQLi property that indicates the number of rows returned; exactly one row confirms a valid user record.
// Processes the user data only if a single record exists, ensuring valid data or an error message is sent to the client.
if ($result->num_rows == 1) {
    // Variable: Stores the user record as an associative array.
    // Array: $result->fetch_assoc() is a MySQLi method that retrieves the row as an associative array with keys like Name and Role.
    // Extracts the single user record for inclusion in the JSON response.
    $user = $result->fetch_assoc();
    
    // Output statement: Sends a JSON success response with the user record to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ["status" => "success", "user" => $user] to a JSON string, a data format for web communication.
    // Delivers the user record to the client for display, such as showing admin details in a profile view or dashboard interface.
    echo json_encode(["status" => "success", "user" => $user]);
} else {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ["status" => "error", "message" => "User not found"] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that no user was found, allowing error handling (e.g., displaying an error message to the user).
    echo json_encode(["status" => "error", "message" => "User not found"]);
}

// Method call: Frees the user statement resources.
// String: close() is a MySQLi method that releases the prepared statement ($stmt), freeing memory.
// Ensures efficient resource management after the user retrieval is complete.
$stmt->close();

// Method call: Closes the database connection.
// String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open after the query, maintaining system efficiency and resource management.
$conn->close();
?>