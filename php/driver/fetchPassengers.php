<?php
// Function call: Starts or resumes a PHP session to access session data.
// String: session_start() is a PHP built-in function that initializes a new session or resumes an existing one, enabling access to $_SESSION variables.
// Allows retrieval of the logged-in driver’s email stored in the session for authentication and querying purposes in the International Bus Booking System.
session_start();

// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to query passenger records for the International Bus Booking System’s driver interface.
include '../databaseconnection.php';

// Conditional statement: Checks if the user is logged in by verifying the presence of an email in the session.
// Function call: isset() is a PHP built-in function that checks if $_SESSION["Email"], a superglobal array element, exists and is not null.
// Ensures the user is authenticated before accessing passenger details, preventing unauthorized access.
if (isset($_SESSION["Email"])) {
    // Variable: Stores the logged-in user’s email from the session.
    // String: $_SESSION["Email"] is a superglobal array element containing the email of the logged-in user.
    // Captures the email to identify the driver or co-driver for querying passenger records.
    $email = $_SESSION["Email"];
    
    // Variable: Stores the schedule ID from the URL query string.
    // String: $_GET['scheduleID'] is a superglobal array element containing the schedule ID passed via the URL (e.g., ?scheduleID=123), with the null coalescing operator (??) providing null if not set.
    // Captures the schedule ID to filter passenger records for a specific bus schedule.
    $scheduleID = $_GET['scheduleID'] ?? null;
    
    // Conditional statement: Checks if a schedule ID is provided.
    // Boolean check: Tests if $scheduleID is falsy (null or empty).
    // Ensures a valid schedule ID is present before querying the database, preventing invalid requests.
    if (!$scheduleID) {
        // Output statement: Sends a JSON error response to the client.
        // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ["status" => "error", "message" => "Schedule ID is required"] to a JSON string, a data format for web communication.
        // Informs the client’s JavaScript that the schedule ID is missing, allowing error handling (e.g., displaying an error message to the driver).
        echo json_encode(["status" => "error", "message" => "Schedule ID is required"]);
        
        // Function call: Terminates script execution immediately.
        // String: exit() is a PHP built-in function that stops the script from running further.
        // Halts processing to prevent further actions after an invalid request.
        exit();
    }
    
    // Variable: SQL query string to retrieve the Staff ID for the logged-in user.
    // String: Defines a SELECT query to fetch StaffID from the staff table WHERE Email = ?, using a placeholder (?) for secure parameter binding.
    // Retrieves the Staff ID to verify the user’s role as a driver or co-driver for the specified schedule.
    $sql = "SELECT StaffID FROM staff WHERE Email = ?";
    
    // Variable: Stores the prepared statement for staff ID retrieval.
    // Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with the placeholder, returning a statement object for binding and execution.
    // Prepares the query to safely retrieve the staff record, reducing the risk of SQL injection.
    $stmt = $conn->prepare($sql);
    
    // Method call: Binds the email to the prepared statement.
    // String: bind_param("s", $email) is a MySQLi method that binds $email as a string ('s') to the placeholder in the query, sanitizing the input.
    // Securely links the email to the query, preventing SQL injection by treating the input as data, not code.
    $stmt->bind_param("s", $email);
    
    // Method call: Executes the staff ID retrieval query.
    // String: execute() is a MySQLi method that runs the prepared statement, querying the staff record matching the email.
    // Retrieves the staff record from the staff table for further processing.
    $stmt->execute();
    
    // Variable: Stores the result set from the executed query.
    // Object: $stmt->get_result() is a MySQLi method that retrieves the result set from the prepared statement, allowing row fetching.
    // Holds the staff ID data for verification of driver or co-driver status.
    $result = $stmt->get_result();
    
    // Conditional statement: Checks if a staff record was found for the provided email.
    // Integer check: $result->num_rows is a MySQLi property that indicates the number of rows returned; greater than zero means a record was found.
    // Verifies that the logged-in user is a valid staff member (driver or co-driver) before querying passenger details.
    if ($result->num_rows > 0) {
        // Variable: Stores the staff record as an associative array.
        // Array: $result->fetch_assoc() is a MySQLi method that retrieves the row as an associative array with the key StaffID.
        // Extracts the Staff ID for use in the passenger query.
        $row = $result->fetch_assoc();
        
        // Variable: Stores the Staff ID as the driver ID.
        // Integer: $row['StaffID'] is the unique identifier of the logged-in staff member, used to check driver or co-driver assignment.
        // Prepares the driver ID for filtering passenger records based on schedule assignments.
        $driverID = $row['StaffID'];
        
        // Variable: SQL query string to retrieve confirmed passenger details for a specific schedule.
        // String: Defines a SELECT query to fetch Name, PhoneNumber, and SeatNumber from BookingDetails, joined with Customer and ScheduleInformation, WHERE ScheduleID = ? AND (DriverID = ? OR CodriverID = ?) AND Status = 'Confirmed', using placeholders for secure binding.
        // Retrieves passenger details for confirmed bookings under the specified schedule, ensuring only authorized drivers or co-drivers access the data.
        $sql = "SELECT c.Name, c.PhoneNumber, b.SeatNumber
                FROM BookingDetails b
                JOIN Customer c ON b.CustomerID = c.CustomerID
                JOIN ScheduleInformation s ON b.ScheduleID = s.ScheduleID
                WHERE s.ScheduleID = ? AND (s.DriverID = ? OR s.CodriverID = ?) AND b.Status = 'Confirmed'";
        
        // Variable: Stores the prepared statement for passenger retrieval.
        // Object: $conn->prepare($sql) is a MySQLi method that compiles the SQL query with placeholders, returning a statement object for binding and execution.
        // Prepares the query to safely retrieve passenger records, reducing the risk of SQL injection.
        $stmt = $conn->prepare($sql);
        
        // Method call: Binds parameters to the prepared statement.
        // String: bind_param("iii", $scheduleID, $driverID, $driverID) is a MySQLi method that binds $scheduleID, $driverID, and $driverID as integers ('iii') to the placeholders in the query, sanitizing the inputs.
        // Securely links the schedule and driver IDs to the query, ensuring only authorized passenger data is retrieved.
        $stmt->bind_param("iii", $scheduleID, $driverID, $driverID);
        
        // Method call: Executes the passenger retrieval query.
        // String: execute() is a MySQLi method that runs the prepared statement, querying passenger records for the specified schedule and driver.
        // Retrieves passenger records from the joined tables for processing.
        $stmt->execute();
        
        // Variable: Stores the result set from the executed query.
        // Object: $stmt->get_result() is a MySQLi method that retrieves the result set from the prepared statement, allowing row fetching.
        // Holds the passenger data for further processing into a JSON response.
        $result = $stmt->get_result();
        
        // Variable: Initializes an array to store passenger records.
        // Array: Creates an empty array to hold associative arrays, each representing a passenger with fields like Name and SeatNumber.
        // Prepares to collect passenger details for inclusion in the JSON response.
        $passengers = [];
        
        // Loop: Iterates over the query results to collect passenger records.
        // Array: $result->fetch_assoc() is a MySQLi method that retrieves each row as an associative array with keys like Name and SeatNumber, repeated using a while loop until no rows remain.
        // Processes each passenger record to include in the JSON response for the driver interface.
        while ($row = $result->fetch_assoc()) {
            // Array operation: Appends a passenger record to the array.
            // Array: Adds $row to $passengers, containing passenger details like Name and PhoneNumber.
            // Collects passenger data for display on the driver’s interface, such as a passenger manifest.
            $passengers[] = $row;
        }
        
        // Output statement: Sends a JSON success response with the passenger records to the client.
        // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ["status" => "success", "passengers" => $passengers] to a JSON string, a data format for web communication.
        // Delivers the passenger records to the client for display, such as listing passengers in a table for the driver.
        echo json_encode(["status" => "success", "passengers" => $passengers]);
    } else {
        // Output statement: Sends a JSON error response to the client.
        // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ["status" => "error", "message" => "Driver not found"] to a JSON string, a data format for web communication.
        // Informs the client’s JavaScript that no driver was found, allowing error handling (e.g., displaying an error message to the user).
        echo json_encode(["status" => "error", "message" => "Driver not found"]);
    }
} else {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ["status" => "error", "message" => "Please log in to view passengers"] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that the user is not logged in, allowing error handling (e.g., redirecting to a login page).
    echo json_encode(["status" => "error", "message" => "Please log in to view passengers"]);
}

// Method call: Frees the statement resources.
// String: close() is a MySQLi method that releases the prepared statement ($stmt), freeing memory.
// Ensures efficient resource management after the passenger retrieval is complete.
$stmt->close();

// Method call: Closes the database connection.
// String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open after the query, maintaining system efficiency and resource management.
$conn->close();
?>