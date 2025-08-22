<?php
// Function call: Session initialization function.
// String: session_start() is a PHP built-in function that starts or resumes a session, enabling access to session variables.
// Enables the script to retrieve the logged-in user’s email from the session for the International Bus Booking System.
session_start();

// Include statement: File inclusion directive.
// String: include is a PHP statement that loads 'databaseconnection.php', a file that defines the $conn MySQLi connection object.
// Loads the database connection settings to query customer details from the database.
include 'databaseconnection.php';

// Conditional statement: Logic to check if the user is logged in.
// Boolean check: isset($_SESSION["Email"]) is a PHP built-in function that tests if the 'Email' key exists in the $_SESSION superglobal array and is not null.
// Ensures the user is logged in before attempting to fetch their customer details, preventing unauthorized access.
if (isset($_SESSION["Email"])) {
    // Variable: User email storage.
// String: $_SESSION["Email"] is a superglobal array element containing the email of the logged-in user.
// Identifies the user to retrieve their specific customer details from the database.
    $email = $_SESSION["Email"];

    // Variable: SQL SELECT query string.
// String: Defines a query to select CustomerID, Name, Email, PhoneNumber, Gender, PassportNumber, and Nationality from the 'customer' table where Email matches a placeholder (?).
// Retrieves the customer’s profile information for display (e.g., in ProfileManagement.html).
    $sql = "SELECT CustomerID, Name, Email, PhoneNumber, Gender, PassportNumber, Nationality FROM customer WHERE Email = ?";

    // Object: Prepared statement for database query.
// Object: $conn->prepare($sql) is a MySQLi method that creates a prepared statement object from the SQL query, where $conn is the MySQLi connection from databaseconnection.php.
// Prepares the query to fetch customer details securely, using a placeholder to prevent SQL injection.
    $stmt = $conn->prepare($sql);

    // Method call: Parameter binding function.
// String: bind_param("s", $email) is a MySQLi method that binds $email as a string (s) to the query’s placeholder (?).
// Attaches the user’s email to the query safely, preventing SQL injection for secure data retrieval.
    $stmt->bind_param("s", $email);

    // Method call: Query execution function.
// String: execute() is a MySQLi method that runs the prepared statement to query the database.
// Sends the query to retrieve the customer’s details based on their email.
    $stmt->execute();

    // Variable: Query result storage.
// Object: $stmt->get_result() is a MySQLi method that retrieves the result set from the executed prepared statement.
// Stores the customer data for processing and response creation.
    $result = $stmt->get_result();

    // Conditional statement: Logic to check if customer data was found.
// Integer check: $result->num_rows is a MySQLi property that returns the number of rows in the result set, compared to 0.
// Outputs customer data if found, or a null response if no customer matches the email.
    if ($result->num_rows > 0) {
        // Variable: Customer data storage.
// Array: $result->fetch_assoc() is a MySQLi method that retrieves a single row from the result set as an associative array.
// Extracts the customer’s details (e.g., CustomerID, Name) for JSON output to the client.
        $customer = $result->fetch_assoc();

        // Output statement: JSON response output.
// String: echo outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'customer' key set to $customer to a JSON string.
// Sends the customer’s details to the client (e.g., JavaScript in ProfileManagement.html) for dynamic display.
        echo json_encode(["customer" => $customer]);
    } else {
        // Output statement: JSON null response output.
// String: echo outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'customer' key set to null to a JSON string.
// Informs the client that no customer data was found for the provided email, indicating a potential issue.
        echo json_encode(["customer" => null]);
    }
} else {
    // Output statement: JSON null response output.
// String: echo outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'customer' key set to null to a JSON string.
// Informs the client that no user is logged in, preventing unauthorized access to customer details.
    echo json_encode(["customer" => null]);
}

// Method call: Statement closure function.
// String: close() is a MySQLi method that frees resources associated with the prepared statement ($stmt).
// Releases database resources after fetching customer data to maintain system efficiency.
$stmt->close();

// Method call: Connection closure function.
// String: close() is a MySQLi method that closes the database connection ($conn).
// Frees database resources after all operations, ensuring no connections remain open.
$conn->close();
?>