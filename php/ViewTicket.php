<?php
// Include statement: File inclusion directive.
// String: include is a PHP statement that loads 'databaseconnection.php', a file that defines the $conn MySQLi connection object.
// Loads the database connection settings to query ticket details from the BookingSummary view in the International Bus Booking System.
include 'databaseconnection.php';

// Conditional statement: Logic to verify the HTTP request method.
// String check: $_SERVER["REQUEST_METHOD"] is a superglobal array element that returns the request method (e.g., "POST"), compared to "POST".
// Ensures the script processes only POST form submissions, as the booking ID is sent via POST for security.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Variable: Booking ID storage.
    // String: $_POST["bookingID"] is a superglobal array element from the form submission, containing the booking ID for the ticket.
    // Identifies the specific booking to retrieve ticket details for display.
    $bookingID = $_POST["bookingID"];
    
    // Variable: SQL SELECT query string.
    // String: Defines a query to select all columns from the 'BookingSummary' view where BookingID matches a placeholder (?).
    // Retrieves comprehensive ticket details (e.g., route, date, cost) for the specified booking.
    $sql = "SELECT * FROM BookingSummary WHERE BookingID = ?";
    
    // Object: Prepared statement for database query.
    // Object: $conn->prepare($sql) is a MySQLi method that creates a prepared statement object from the SQL query, where $conn is the MySQLi connection from databaseconnection.php.
    // Prepares the query to fetch ticket details securely, using a placeholder to prevent SQL injection.
    $stmt = $conn->prepare($sql);
    
    // Conditional statement: Logic to check statement preparation.
    // Boolean check: Tests if $stmt is false, indicating preparation failure due to SQL errors or connection issues.
    // Stops the script with a JSON error if the query cannot be prepared, ensuring reliable data retrieval.
    if ($stmt === false) {
        // Output statement: JSON error response output.
        // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Error preparing statement: ' concatenated with $conn->error, a MySQLi property with the error message) to a JSON string.
        // Sends an error to the client (e.g., JavaScript in Ticket.html) if query preparation fails, alerting them to a server issue.
        echo json_encode(['status' => 'error', 'message' => 'Error preparing statement: ' . $conn->error]);
        // Function call: Script termination function.
        // String: exit() is a PHP built-in function that stops script execution.
        // Halts the script to prevent execution with an invalid query.
        exit();
    }
    
    // Method call: Parameter binding function.
    // String: bind_param("i", $bookingID) is a MySQLi method that binds $bookingID as an integer (i) to the query’s placeholder (?).
    // Attaches the booking ID to the query safely, preventing SQL injection for secure data retrieval.
    $stmt->bind_param("i", $bookingID);
    
    // Conditional statement: Logic to execute the query and check success.
    // Boolean check: $stmt->execute() is a MySQLi method that runs the prepared statement, returning TRUE on success or FALSE on failure.
    // Proceeds with result processing if the query executes successfully, or sends an error if it fails.
    if ($stmt->execute()) {
        // Variable: Query result storage.
        // Object: $stmt->get_result() is a MySQLi method that retrieves the result set from the executed prepared statement.
        // Stores the ticket details from the BookingSummary view for processing.
        $result = $stmt->get_result();
        
        // Conditional statement: Logic to check if results were found.
        // Integer check: $result->num_rows is a MySQLi property that returns the number of rows in the result set, compared to 0.
        // Outputs ticket data if found, or an error if no ticket matches the booking ID.
        if ($result->num_rows > 0) {
            // Variable: Ticket data storage.
            // Array: $result->fetch_assoc() is a MySQLi method that retrieves a single row from the result set as an associative array.
            // Extracts ticket details (e.g., route, date, cost) for JSON output to the client.
            $ticket = $result->fetch_assoc();
            
            // Output statement: JSON success response output.
            // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('success') and 'ticket' ($ticket array) to a JSON string.
            // Sends the ticket details to the client (e.g., JavaScript in Ticket.html) for dynamic display.
            echo json_encode(['status' => 'success', 'ticket' => $ticket]);
        } else {
            // Output statement: JSON error response output.
            // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('No ticket found for the provided Booking ID') to a JSON string.
            // Sends an error to the client if no ticket is found, prompting them to check the booking ID.
            echo json_encode(['status' => 'error', 'message' => 'No ticket found for the provided Booking ID']);
        }
    } else {
        // Output statement: JSON error response output.
        // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Error executing query: ' concatenated with $stmt->error, a MySQLi property with the error message) to a JSON string.
        // Sends an error to the client if the query fails, providing details for debugging.
        echo json_encode(['status' => 'error', 'message' => 'Error executing query: ' . $stmt->error]);
    }
    
    // Method call: Statement closure function.
    // String: close() is a MySQLi method that frees resources associated with the prepared statement ($stmt).
    // Releases database resources after fetching ticket data to maintain system efficiency.
    $stmt->close();
    
    // Method call: Connection closure function.
    // String: close() is a MySQLi method that closes the database connection ($conn).
    // Frees database resources after all operations, ensuring no connections remain open.
    $conn->close();
} else {
    // Output statement: JSON error response output.
    // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Invalid request method...' concatenated with $_SERVER['REQUEST_METHOD']) to a JSON string.
    // Informs the client that a non-POST request was used, including the actual method for clarity.
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method. Expected POST, got ' . $_SERVER['REQUEST_METHOD']]);
}
?>