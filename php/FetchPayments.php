<?php
// Function call: Session initiation function.
// String: session_start() is a PHP built-in function that starts or resumes a session to manage user data across pages.
// Starts a session to access the user’s email, enabling authentication checks for secure payment history retrieval in the International Bus Booking System.
session_start();

// Include statement: File inclusion directive.
// String: include is a PHP statement that loads 'databaseconnection.php', a file that defines the $conn MySQLi connection object.
// Loads the database connection settings to query customer and payment data for the payment history.
include 'databaseconnection.php';

// Conditional statement: Logic to verify user authentication.
// Function call: isset() is a PHP built-in function that checks if a variable exists and is not null, here checking $_SESSION["Email"], a session superglobal array element storing the user’s email.
// Ensures only logged-in users can access their payment history, preventing unauthorized access to sensitive financial data.
if (isset($_SESSION["Email"])) {
    // Variable: User email storage.
    // String: $_SESSION["Email"] is a session superglobal array element holding the logged-in user’s email address.
    // Stores the email to fetch the customer’s ID from the database, linking payment history to the correct user.
    $email = $_SESSION["Email"];

    // Variable: SQL SELECT query string.
    // String: Defines a query to select CustomerID from the 'customer' table where Email matches a placeholder (?).
    // Retrieves the customer’s unique ID to query their payment details securely.
    $sql = "SELECT CustomerID FROM customer WHERE Email = ?";

    // Object: Prepared statement for database query.
    // Object: $conn->prepare($sql) is a MySQLi method that creates a prepared statement object from the SQL query, where $conn is the MySQLi connection from databaseconnection.php.
    // Prepares the query to fetch the customer ID securely, using a placeholder to prevent SQL injection.
    $stmt = $conn->prepare($sql);

    // Method call: Parameter binding function.
    // String: bind_param("s", $email) is a MySQLi method that binds $email as a string (s) to the query’s placeholder (?).
    // Attaches the user’s email to the query safely, preventing SQL injection for secure customer ID retrieval.
    $stmt->bind_param("s", $email);

    // Method call: Query execution function.
    // String: execute() is a MySQLi method that runs the prepared statement on the database.
    // Executes the query to fetch the customer ID based on the provided email.
    $stmt->execute();

    // Variable: Query result storage.
    // Object: $stmt->get_result() is a MySQLi method that retrieves the result set from the executed prepared statement.
    // Stores the customer ID query results for further processing.
    $result = $stmt->get_result();

    // Conditional statement: Logic to check if a customer record was found.
    // Integer check: $result->num_rows is a MySQLi property that returns the number of rows in the result set, compared to 0.
    // Proceeds to fetch payment details if a customer is found, or sends an error if not.
    if ($result->num_rows > 0) {
        // Variable: Customer data storage.
        // Array: $result->fetch_assoc() is a MySQLi method that retrieves a single row from the result set as an associative array.
        // Extracts the customer’s data (CustomerID) for use in payment queries.
        $row = $result->fetch_assoc();

        // Variable: Customer ID storage.
        // String: $row['CustomerID'] is the CustomerID field from the fetched associative array.
        // Stores the customer’s unique ID to query their payment records.
        $customerID = $row['CustomerID'];

        // Variable: SQL SELECT query string.
        // String: Defines a query joining 'paymentdetails' and 'bookingdetails' tables to select PaymentID, BookingID, AmountPaid, PaymentMode, PaymentDate, ReceiptNumber, TransactionID, and Status where CustomerID matches a placeholder (?).
        // Retrieves all payment details for the customer’s bookings to display their payment history.
        $sql = "SELECT p.PaymentID, p.BookingID, p.AmountPaid, p.PaymentMode, p.PaymentDate, 
                       p.ReceiptNumber, p.TransactionID, p.Status
                FROM paymentdetails p
                JOIN bookingdetails b ON p.BookingID = b.BookingID
                WHERE b.CustomerID = ?";

        // Object: Prepared statement for database query.
        // Object: $conn->prepare($sql) is a MySQLi method that creates a prepared statement object from the SQL query.
        // Prepares the query to fetch payment details securely, using a placeholder to prevent SQL injection.
        $stmt = $conn->prepare($sql);

        // Method call: Parameter binding function.
        // String: bind_param("i", $customerID) is a MySQLi method that binds $customerID as an integer (i) to the query’s placeholder (?).
        // Attaches the customer ID to the query safely, preventing SQL injection for secure payment data retrieval.
        $stmt->bind_param("i", $customerID);

        // Method call: Query execution function.
        // String: execute() is a MySQLi method that runs the prepared statement on the database.
        // Executes the query to fetch the customer’s payment details.
        $stmt->execute();

        // Variable: Query result storage.
        // Object: $stmt->get_result() is a MySQLi method that retrieves the result set from the executed prepared statement.
        // Stores the payment data for processing into a response.
        $result = $stmt->get_result();

        // Variable: Payments storage.
        // Array: An empty array initialized to hold associative arrays of payment records.
        // Prepares to collect payment details (e.g., PaymentID, AmountPaid) for the JSON response to the frontend.
        $payments = [];

        // Loop: Iteration over query results.
        // Array: $result->fetch_assoc() is a MySQLi method that retrieves each row as an associative array, repeated using a while loop.
        // Processes each payment record to build the $payments array for the payment history display.
        while ($row = $result->fetch_assoc()) {
            // Array operation: Append payment record to array.
            // Array: $row is an associative array containing PaymentID, BookingID, AmountPaid, PaymentMode, PaymentDate, ReceiptNumber, TransactionID, and Status.
            // Adds each payment record to $payments for the JSON response.
            $payments[] = $row;
        }

        // Output statement: JSON success response output.
        // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('success') and 'payments' ($payments) to a JSON string.
        // Sends the customer’s payment history to the client (e.g., JavaScript) for rendering on the payment history page.
        echo json_encode(["status" => "success", "payments" => $payments]);
    } else {
        // Output statement: JSON error response output.
        // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Customer not found') to a JSON string.
        // Sends an error to the client if no customer record is found, indicating an issue with the user’s account.
        echo json_encode(["status" => "error", "message" => "Customer not found"]);
    }
} else {
    // Output statement: JSON error response output.
    // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Please log in to view your payment history') to a JSON string.
    // Sends an error to the client if the user is not logged in, prompting them to log in before accessing payment history.
    echo json_encode(["status" => "error", "message" => "Please log in to view your payment history"]);
}

// Method call: Statement closure function.
// String: close() is a MySQLi method that frees resources associated with the prepared statement ($stmt).
// Releases database resources after fetching data to maintain system efficiency.
$stmt->close();

// Method call: Connection closure function.
// String: close() is a MySQLi method that closes the database connection ($conn).
// Frees database resources after all operations, ensuring no connections remain open.
$conn->close();
?>