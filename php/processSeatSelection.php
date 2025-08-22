<?php
// Function call: Session initiation function.
// String: Calls session_start(), a PHP function that starts or resumes a session.
// Enables access to session variables like $_SESSION["Email"] for user data.
session_start();

// Function call: HTTP header setting function.
// String: Calls header(), a PHP function that sets the HTTP response header with 'Content-Type: text/plain'.
// Ensures the response is plain text for client-side processing.
header('Content-Type: text/plain');

// Include statement: File inclusion directive.
// String: Includes 'databaseconnection.php' via require_once, a PHP statement that imports the file once.
// Establishes a database connection using predefined settings from databaseconnection.php.
require_once 'databaseconnection.php';

// Conditional statement: Logic to check user authentication.
// Function call: Calls isset(), a PHP function that checks if a variable ($_SESSION["Email"]) is set and not null.
// Redirects to the login page if the user is not logged in.
if (!isset($_SESSION["Email"])) {
    // Function call: HTTP redirect function.
    // String: Calls header(), a PHP function that sets the Location header to '../frontend/login.html'.
    // Sends the user to the login page if no session email is found.
    header("Location: ../frontend/login.html");
    // Function call: Script termination function.
    // String: Calls exit(), a PHP function that stops script execution.
    // Ensures no further code runs after the redirect.
    exit();
}

// Variable: User email storage.
// String: Assigned from $_SESSION["Email"], a session superglobal array element.
// Identifies the logged-in user for customer lookup.
$email = $_SESSION["Email"];

// Variable: Schedule ID storage.
// String: Assigned from $_GET["scheduleID"] using null coalescing operator (??), defaults to empty string if unset.
// Identifies the bus schedule for booking.
$scheduleID = $_GET["scheduleID"] ?? '';

// Variable: Seat numbers storage.
// String: Assigned from $_GET["seatNumbers"] using null coalescing operator (??), defaults to empty string if unset.
// Specifies the selected seats for booking as a comma-separated string.
$seatNumbers = $_GET["seatNumbers"] ?? '';

// Conditional statement: Logic to validate input data.
// Function call: Calls empty(), a PHP function that checks if $scheduleID or $seatNumbers is empty or unset.
// Outputs an error message and stops execution if any field is missing.
if (empty($scheduleID) || empty($seatNumbers)) {
    // Output statement: Error message output.
    // String: Outputs "Schedule ID and at least one seat number are required." using echo, a PHP statement.
    // Informs the client of missing required fields.
    echo "Schedule ID and at least one seat number are required.";
    // Function call: Script termination function.
    // String: Calls exit(), a PHP function that stops script execution.
    // Ensures no further code runs after the error.
    exit();
}

// Try-catch block: Exception handling structure.
// Structure: Wraps database operations in a try block to catch exceptions, with a catch block for error handling.
// Ensures graceful error handling for database queries and insertions.
try {
    // Variable: SQL SELECT query string.
    // String: Defines a query to select CustomerID from the 'customer' table where Email matches a placeholder (?).
    // Fetches the customer’s unique identifier based on session email.
    $sql_customer = "SELECT CustomerID FROM customer WHERE Email = ?";

    // Object: Prepared statement for database query.
    // Object: Created by $conn->prepare($sql_customer), a MySQLi method that prepares the SQL query for execution.
    // Binds the query for parameter substitution to prevent SQL injection.
    $stmt_customer = $conn->prepare($sql_customer);

    // Conditional statement: Logic to check statement preparation.
    // Boolean check: Tests if $stmt_customer is false, indicating preparation failure.
    // Throws an exception if preparation fails.
    if ($stmt_customer === false) {
        throw new Exception("Error preparing customer query: " . $conn->error);
    }

    // Method call: Parameter binding function.
    // String: Calls bind_param("s", $email), a MySQLi method that binds $email as a string (s) to the query’s placeholder.
    // Links the email variable to the query for secure execution.
    $stmt_customer->bind_param("s", $email);

    // Method call: Query execution function.
    // String: Calls execute(), a MySQLi method that runs the prepared statement.
    // Queries the database to find the customer’s ID.
    $stmt_customer->execute();

    // Variable: Query result storage.
    // Object: Assigned from $stmt_customer->get_result(), a MySQLi method that retrieves the result set.
    // Captures the customer data returned by the query.
    $result_customer = $stmt_customer->get_result();

    // Conditional statement: Logic to check if a customer record was found.
    // Integer check: Evaluates $result_customer->num_rows, a MySQLi property indicating the number of rows.
    // Throws an exception if no customer record exists.
    if ($result_customer->num_rows === 0) {
        throw new Exception("Customer not found for email: $email");
    }

    // Variable: Customer data storage.
    // Array: Assigned from $result_customer->fetch_assoc(), a MySQLi method that retrieves a row as an associative array.
    // Extracts the customer’s data for processing.
    $customer_row = $result_customer->fetch_assoc();

    // Variable: Customer ID storage.
    // Integer: Assigned from $customer_row['CustomerID'], the CustomerID field from the query result.
    // Identifies the customer for booking.
    $customerID = $customer_row['CustomerID'];

    // Method call: Statement closure function.
    // String: Calls close(), a MySQLi method that frees resources associated with $stmt_customer.
    // Releases database resources for the customer query.
    $stmt_customer->close();

    // Variable: SQL SELECT query string.
    // String: Defines a query to select DATE(DepartureTime) as TravelDate from 'scheduleinformation' where ScheduleID matches a placeholder (?).
    // Fetches the travel date for the selected schedule.
    $sql_schedule = "SELECT DATE(DepartureTime) AS TravelDate FROM scheduleinformation WHERE ScheduleID = ?";

    // Object: Prepared statement for database query.
    // Object: Created by $conn->prepare($sql_schedule), a MySQLi method that prepares the SQL query for execution.
    // Binds the query for parameter substitution to prevent SQL injection.
    $stmt_schedule = $conn->prepare($sql_schedule);

    // Conditional statement: Logic to check statement preparation.
    // Boolean check: Tests if $stmt_schedule is false, indicating preparation failure.
    // Throws an exception if preparation fails.
    if ($stmt_schedule === false) {
        throw new Exception("Error preparing schedule query: " . $conn->error);
    }

    // Method call: Parameter binding function.
    // String: Calls bind_param("i", $scheduleID), a MySQLi method that binds $scheduleID as an integer (i) to the query’s placeholder.
    // Links the schedule ID to the query for secure execution.
    $stmt_schedule->bind_param("i", $scheduleID);

    // Method call: Query execution function.
    // String: Calls execute(), a MySQLi method that runs the prepared statement.
    // Queries the database to find the travel date.
    $stmt_schedule->execute();

    // Variable: Query result storage.
    // Object: Assigned from $stmt_schedule->get_result(), a MySQLi method that retrieves the result set.
    // Captures the schedule data returned by the query.
    $result_schedule = $stmt_schedule->get_result();

    // Conditional statement: Logic to check if a schedule record was found.
    // Integer check: Evaluates $result_schedule->num_rows, a MySQLi property indicating the number of rows.
    // Throws an exception if no schedule record exists.
    if ($result_schedule->num_rows === 0) {
        throw new Exception("Invalid schedule ID: $scheduleID");
    }

    // Variable: Schedule data storage.
    // Array: Assigned from $result_schedule->fetch_assoc(), a MySQLi method that retrieves a row as an associative array.
    // Extracts the schedule’s data for processing.
    $schedule_row = $result_schedule->fetch_assoc();

    // Variable: Travel date storage.
    // String: Assigned from $schedule_row['TravelDate'], the TravelDate field in YYYY-MM-DD format.
    // Represents the date of travel extracted from DepartureTime.
    $travelDate = $schedule_row['TravelDate'];

    // Method call: Statement closure function.
    // String: Calls close(), a MySQLi method that frees resources associated with $stmt_schedule.
    // Releases database resources for the schedule query.
    $stmt_schedule->close();

    // Process multiple seats from seatNumbers
    // Variable: Array of seat numbers
    // Array: Splits $seatNumbers by comma using explode()
    // Contains individual seat numbers for validation
    $seatNumberArray = explode(',', $seatNumbers);

    // maximum seat limit validation
    // Conditional statement: Checks seat count
    // Integer check: Evaluates count($seatNumberArray) against maximum of 5
    // Throws an exception if more than 5 seats are selected
    if (count($seatNumberArray) > 5) {
        throw new Exception("Cannot book more than 5 seats.");
    }

    // fetch all booked seats as comma-separated strings
    // Variable: SQL SELECT query string.
    // String: Defines a query to select SeatNumber from 'bookingdetails' where ScheduleID matches a placeholder (?) and Status is 'Confirmed'.
    // Fetches all booked seats for the schedule.
    $sql_seat_check = "SELECT SeatNumber FROM bookingdetails WHERE ScheduleID = ? AND Status = 'Confirmed'";

    // Object: Prepared statement for database query.
    // Object: Created by $conn->prepare($sql_seat_check), a MySQLi method that prepares the SQL query for execution.
    // Binds the query for parameter substitution to prevent SQL injection.
    $stmt_seat_check = $conn->prepare($sql_seat_check);

    // Conditional statement: Logic to check statement preparation.
    // Boolean check: Tests if $stmt_seat_check is false, indicating preparation failure.
    // Throws an exception if preparation fails.
    if ($stmt_seat_check === false) {
        throw new Exception("Error preparing seat check query: " . $conn->error);
    }

    // bind only scheduleID
    // Method call: Parameter binding function.
    // String: Calls bind_param("i", $scheduleID), a MySQLi method that binds $scheduleID as an integer (i).
    // Links schedule ID to the query for secure execution.
    $stmt_seat_check->bind_param("i", $scheduleID);

    // Method call: Query execution function.
    // String: Calls execute(), a MySQLi method that runs the prepared statement.
    // Queries the database to check seat availability.
    $stmt_seat_check->execute();

    // Variable: Query result storage.
    // Object: Assigned from $stmt_seat_check->get_result(), a MySQLi method that retrieves the result set.
    // Captures the booked seats data.
    $result_seat_check = $stmt_seat_check->get_result();

    // Collect all booked seats into an array
    // Variable: Array to store booked seat numbers
    // Array: Initialized as empty, populated by splitting SeatNumber strings
    // Contains all booked seats for the schedule
    $bookedSeats = [];
    // Loop: Iterates over query results
    // Method call: Uses fetch_assoc() to retrieve each row
    // Splits SeatNumber by comma and merges into $bookedSeats
    while ($row = $result_seat_check->fetch_assoc()) {
        $bookedSeats = array_merge($bookedSeats, explode(',', $row['SeatNumber']));
    }

    // Method call: Statement closure function.
    // String: Calls close(), a MySQLi method that frees resources associated with $stmt_seat_check.
    // Releases database resources for the seat check query.
    $stmt_seat_check->close();

    // Validate each seat and collect valid/unavailable seats
    // Variable: Array to store valid seats
    // Array: Initialized as empty, stores seats that are available
    // Used to insert valid seats into the database
    $validSeats = [];
    // Variable: Array to store unavailable seats
    // Array: Initialized as empty, stores seats that are already booked
    // Used to inform user of unavailable seats
    $unavailableSeats = [];
    // Loop: Iterates over each seat in $seatNumberArray
    // Method call: Uses trim() to clean seat number
    // Checks seat availability and sorts into valid or unavailable arrays
    foreach ($seatNumberArray as $seatNumber) {
        // Method call: String cleaning function
        // String: Calls trim() to remove whitespace from $seatNumber
        // Ensures clean seat number for comparison
        $seatNumber = trim($seatNumber);
        // Conditional statement: Skips empty seat numbers
        // Function call: Uses empty() to check if $seatNumber is empty
        // Continues loop if seat number is empty
        if (empty($seatNumber)) {
            continue;
        }
        // Conditional statement: Checks if seat is booked
        // Function call: Uses in_array() to check if $seatNumber is in $bookedSeats
        // Sorts seat into valid or unavailable array
        if (in_array($seatNumber, $bookedSeats)) {
            $unavailableSeats[] = $seatNumber;
        } else {
            $validSeats[] = $seatNumber;
        }
    }

    // Ensure at least one valid seat is booked
    // Conditional statement: Checks if any valid seats were selected
    // Array check: Evaluates if $validSeats is empty
    // Throws an exception if no valid seats are available
    if (empty($validSeats)) {
        // Exception: Error object creation
        // Object: Creates a new Exception with a message listing unavailable seats
        // Reports that no seats could be booked
        throw new Exception("No seats were booked. Unavailable seats: " . implode(', ', $unavailableSeats));
    }

    // Variable: Current date storage.
    // String: Assigned from date('Y-m-d'), a PHP function that formats the current date as YYYY-MM-DD.
    // Represents the booking date for the database.
    $bookingDate = date('Y-m-d');

    // Variable: SQL INSERT query string.
    // String: Defines a query to insert CustomerID, ScheduleID, SeatNumber, BookingDate, TravelDate, and 'Confirmed' into 'bookingdetails' with placeholders (?).
    // Records a new booking in the database.
    $sql_booking = "INSERT INTO bookingdetails (CustomerID, ScheduleID, SeatNumber, BookingDate, TravelDate, Status) VALUES (?, ?, ?, ?, ?, 'Confirmed')";

    // Object: Prepared statement for database query.
    // Object: Created by $conn->prepare($sql_booking), a MySQLi method that prepares the SQL query for execution.
    // Binds the query for parameter substitution to prevent SQL injection.
    $stmt_booking = $conn->prepare($sql_booking);

    // Conditional statement: Logic to check statement preparation.
    // Boolean check: Tests if $stmt_booking is false, indicating preparation failure.
    // Throws an exception if preparation fails.
    if ($stmt_booking === false) {
        throw new Exception("Error preparing booking query: " . $conn->error);
    }

    // Use comma-separated string of valid seats
    // Variable: String of valid seat numbers
    // String: Joins $validSeats with commas using implode()
    // Represents the seats to be booked
    $seatNumberString = implode(',', $validSeats);

    // Method call: Parameter binding function.
    // String: Calls bind_param("iisss", $customerID, $scheduleID, $seatNumberString, $bookingDate, $travelDate), a MySQLi method that binds variables as integers (i) for $customerID and $scheduleID, and strings (s) for $seatNumberString, $bookingDate, and $travelDate.
    // Links variables to the query for secure execution.
    $stmt_booking->bind_param("iisss", $customerID, $scheduleID, $seatNumberString, $bookingDate, $travelDate);

    // Conditional statement: Logic to execute the booking insertion.
    // Boolean check: Evaluates $stmt_booking->execute(), a MySQLi method that runs the prepared statement and returns true on success.
    // Redirects to payment form on success or throws an exception on failure.
    if ($stmt_booking->execute()) {
        // Variable: Booking ID storage.
        // Integer: Assigned from $conn->insert_id, a MySQLi property that retrieves the auto-generated ID of the last insert.
        // Captures the ID of the newly inserted booking.
        $bookingID = $conn->insert_id;

        // Method call: Statement closure function.
        // String: Calls close(), a MySQLi method that frees resources associated with $stmt_booking.
        // Releases database resources for the booking query.
        $stmt_booking->close();

        // Method call: Connection closure function.
        // String: Calls close(), a MySQLi method that closes the database connection.
        // Frees database resources.
        $conn->close();

        // message for partial bookings
        // Variable: Success or partial booking message
        // String: Constructs message if any seats were unavailable
        // Lists booked and unavailable seats for user feedback
        $message = count($unavailableSeats) > 0 ? "Booked seats: " . implode(', ', $validSeats) . ". Unavailable seats: " . implode(', ', $unavailableSeats) : "";

        // CHANGED: Added message to redirect URL
        // Function call: HTTP redirect function.
        // String: Calls header(), a PHP function that sets the Location header with bookingID and optional message.
        // Sends the user to the payment form with booking ID and message.
        header("Location: ../frontend/forms/PaymentForm.html?bookingID=$bookingID" . ($message ? "&message=" . urlencode($message) : ""));

        // Function call: Script termination function.
        // String: Calls exit(), a PHP function that stops script execution.
        // Ensures no further code runs after the redirect.
        exit();
    } else {
        // Exception: Error object creation.
        // Object: Creates a new Exception with a message including $stmt_booking->error, a MySQLi property with the error details.
        // Reports the database insertion failure.
        throw new Exception("Error inserting booking: " . $stmt_booking->error);
    }
} catch (Exception $e) {
    // Output statement: Error message output.
    // String: Outputs $e->getMessage(), a method of the Exception class that retrieves the exception message.
    // Informs the client of errors like seat conflicts or invalid data.
    echo $e->getMessage();

    // Method call: Connection closure function.
    // String: Calls close(), a MySQLi method that closes the database connection.
    // Frees database resources.
    $conn->close();
}
?>