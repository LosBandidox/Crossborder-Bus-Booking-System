<?php
// Function call: HTTP header setting function.
// String: header() is a PHP built-in function that sets an HTTP response header, here setting 'Content-Type: application/json'.
// Sets the response format to JSON, ensuring the dashboard’s client-side JavaScript can parse the customer stats and schedules correctly.
header('Content-Type: application/json');

// Function call: Session initiation function.
// String: session_start() is a PHP built-in function that starts or resumes a session to manage user data across pages.
// Starts a session to access the user’s email, enabling authentication checks for secure data retrieval in the International Bus Booking System.
session_start();

// Include statement: File inclusion directive.
// String: require_once is a PHP statement that includes 'databaseconnection.php' once, referencing the file that establishes a MySQLi connection.
// Loads database connection settings to query customer and schedule data for the dashboard.
require_once 'databaseconnection.php';

// Variable: Customer statistics storage.
// Array: An associative array with keys 'totalBookings' (integer, initialized to 0), 'totalSpent' (float, initialized to 0.00), and 'upcomingTrips' (integer, initialized to 0).
// Stores default customer statistics (bookings, spending, upcoming trips) to display on the dashboard if no data is found.
$stats = ['totalBookings' => 0, 'totalSpent' => 0.00, 'upcomingTrips' => 0];

// Variable: Bus schedules storage.
// Array: An empty array to hold associative arrays of schedule records.
// Prepares to store bus schedule details (e.g., departure time, cost) for the dashboard’s schedule viewer.
$schedules = [];

// Conditional statement: Logic to verify user authentication.
// Function call: isset() is a PHP built-in function that checks if a variable exists and is not null, here checking $_SESSION['Email'], a session superglobal array element storing the user’s email.
// Ensures only logged-in users can access their dashboard data, preventing unauthorized access to customer stats and schedules.
if (!isset($_SESSION['Email'])) {
    // Output statement: JSON response output.
    // String: echo is a PHP statement that outputs text, here combined with json_encode(), a PHP function that converts an array to a JSON string, containing 'status' ('error') and 'message' ('Please log in').
    // Sends an error response to the client if the user isn’t logged in, prompting them to log in before accessing the dashboard.
    echo json_encode(['status' => 'error', 'message' => 'Please log in']);
    // Function call: Script termination function.
    // String: exit() is a PHP built-in function that stops script execution.
    // Halts the script to prevent further processing for unauthenticated users.
    exit;
}

// Variable: User email storage.
// String: $_SESSION['Email'] is a session superglobal array element holding the logged-in user’s email address.
// Stores the email to fetch the customer’s ID from the database, linking dashboard data to the correct user.
$email = $_SESSION['Email'];

// Variable: SQL SELECT query string.
// String: Defines a query to select CustomerID from the 'customer' table where Email matches a placeholder (?).
// Retrieves the customer’s unique ID to query their booking and payment data securely.
$sql = "SELECT CustomerID FROM customer WHERE Email = ?";

// Object: Prepared statement for database query.
// Object: $conn->prepare($sql) is a MySQLi method that creates a prepared statement object from the SQL query, where $conn is the MySQLi connection from databaseconnection.php.
// Prepares the query for secure execution, using a placeholder to prevent SQL injection when fetching the customer ID.
$stmt = $conn->prepare($sql);

// Conditional statement: Logic to check statement preparation.
// Boolean check: Tests if $stmt is false, indicating preparation failure due to SQL errors or connection issues.
// Stops the script with an error if the query cannot be prepared, ensuring reliable dashboard data retrieval.
if (!$stmt) {
    // Output statement: JSON error response output.
    // String: echo with json_encode() converts an array with 'status' ('error') and 'message' ('Query preparation failed') to a JSON string.
    // Sends an error to the client if the customer ID query fails, alerting them to a server issue.
    echo json_encode(['status' => 'error', 'message' => 'Query preparation failed']);
    // Function call: Script termination function.
    // String: exit() is a PHP built-in function that stops script execution.
    // Halts the script to prevent further processing if the query preparation fails.
    exit;
}

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
// Returns default dashboard data if no customer is found, ensuring the client receives a valid response.
if ($result->num_rows == 0) {
    // Method call: Statement closure function.
    // String: close() is a MySQLi method that frees resources associated with the prepared statement ($stmt).
    // Releases database resources to prevent memory leaks after the customer query.
    $stmt->close();
    // Output statement: JSON success response output.
    // String: echo with json_encode() converts an array with 'status' ('success'), 'stats' ($stats), and 'schedules' ($schedules) to a JSON string.
    // Sends default empty data to the client if no customer is found, ensuring the dashboard displays zeros.
    echo json_encode(['status' => 'success', 'stats' => $stats, 'schedules' => $schedules]);
    // Function call: Script termination function.
    // String: exit() is a PHP built-in function that stops script execution.
    // Halts the script to prevent further processing if no customer is found.
    exit;
}

// Variable: Customer data storage.
// Array: $result->fetch_assoc() is a MySQLi method that retrieves a single row from the result set as an associative array.
// Extracts the customer’s data (CustomerID) for use in subsequent queries.
$row = $result->fetch_assoc();

// Variable: Customer ID storage.
// Integer: $row['CustomerID'] is the CustomerID field from the fetched associative array.
// Stores the customer’s unique ID to query their bookings and payments.
$customerID = $row['CustomerID'];

// Method call: Statement closure function.
// String: close() is a MySQLi method that frees resources associated with the prepared statement ($stmt).
// Releases database resources after fetching the customer ID to maintain efficiency.
$stmt->close();

// Variable: SQL SELECT query string.
// String: Defines a query to count rows in 'bookingdetails' where CustomerID matches a placeholder (?).
// Counts the total number of bookings for the customer to display on the dashboard.
$sql = "SELECT COUNT(*) as count FROM bookingdetails WHERE CustomerID = ?";

// Object: Prepared statement for database query.
// Object: $conn->prepare($sql) is a MySQLi method that creates a prepared statement object from the SQL query.
// Prepares the query to count bookings securely, using a placeholder to avoid SQL injection.
$stmt = $conn->prepare($sql);

// Conditional statement: Logic to process booking count.
// Boolean check: Tests if $stmt is true, indicating successful preparation of the query.
// Proceeds to execute the query and update booking statistics if preparation succeeds.
if ($stmt) {
    // Method call: Parameter binding function.
    // String: bind_param("i", $customerID) is a MySQLi method that binds $customerID as an integer (i) to the query’s placeholder (?).
    // Attaches the customer ID to the query safely for secure booking count retrieval.
    $stmt->bind_param("i", $customerID);
    // Method call: Query execution function.
    // String: execute() is a MySQLi method that runs the prepared statement.
    // Executes the query to count the customer’s bookings.
    $stmt->execute();
    // Array operation: Update to booking statistics.
    // Integer: $stmt->get_result()->fetch_assoc()['count'] retrieves the count field from the query result’s associative array.
    // Updates the 'totalBookings' key in $stats with the number of bookings.
    $stats['totalBookings'] = $stmt->get_result()->fetch_assoc()['count'];
    // Method call: Statement closure function.
    // String: close() is a MySQLi method that frees resources associated with the prepared statement ($stmt).
    // Releases database resources after counting bookings.
    $stmt->close();
}

// Variable: SQL SELECT query string.
// String: Defines a query to sum AmountPaid from 'paymentdetails' joined with 'bookingdetails' where CustomerID matches a placeholder (?).
// Calculates the total amount spent by the customer for the dashboard’s spending card.
$sql = "SELECT SUM(p.AmountPaid) as total FROM paymentdetails p 
        JOIN bookingdetails b ON p.BookingID = b.BookingID 
        WHERE b.CustomerID = ?";

// Object: Prepared statement for database query.
// Object: $conn->prepare($sql) is a MySQLi method that creates a prepared statement object from the SQL query.
// Prepares the query to sum payments securely, using a placeholder to prevent SQL injection.
$stmt = $conn->prepare($sql);

// Conditional statement: Logic to process total spending.
// Boolean check: Tests if $stmt is true, indicating successful preparation of the query.
// Proceeds to execute the query and update spending statistics if preparation succeeds.
if ($stmt) {
    // Method call: Parameter binding function.
    // String: bind_param("i", $customerID) is a MySQLi method that binds $customerID as an integer (i) to the query’s placeholder (?).
    // Attaches the customer ID to the query safely for secure payment summation.
    $stmt->bind_param("i", $customerID);
    // Method call: Query execution function.
    // String: execute() is a MySQLi method that runs the prepared statement.
    // Executes the query to sum the customer’s payments.
    $stmt->execute();
    // Variable: Total spending storage.
    // Float or null: $stmt->get_result()->fetch_assoc()['total'] retrieves the total field from the query result’s associative array.
    // Stores the sum of payments or null if no payments exist.
    $total = $stmt->get_result()->fetch_assoc()['total'];
    // Array operation: Update to spending statistics.
    // Float: Assigns $total to 'totalSpent' if set, otherwise defaults to 0.00 using the Elvis operator (?:), a PHP shorthand for a ternary operator.
    // Updates the 'totalSpent' key in $stats with the customer’s total spending.
    $stats['totalSpent'] = $total ?: 0.00;
    // Method call: Statement closure function.
    // String: close() is a MySQLi method that frees resources associated with the prepared statement ($stmt).
    // Releases database resources after summing payments.
    $stmt->close();
}

// Variable: SQL SELECT query string.
// String: Defines a query to count rows in 'bookingdetails' where CustomerID matches a placeholder (?), TravelDate is on or after today (CURDATE()), and Status is 'Confirmed'.
// Counts upcoming confirmed trips for the dashboard’s upcoming trips card.
$sql = "SELECT COUNT(*) as count FROM bookingdetails 
        WHERE CustomerID = ? AND TravelDate >= CURDATE() AND Status = 'Confirmed'";
        
// Object: Prepared statement for database query.
// Object: $conn->prepare($sql) is a MySQLi method that creates a prepared statement object from the SQL query.
// Prepares the query to count upcoming trips securely, using a placeholder to prevent SQL injection.
$stmt = $conn->prepare($sql);

// Conditional statement: Logic to process upcoming trips count.
// Boolean check: Tests if $stmt is true, indicating successful preparation of the query.
// Proceeds to execute the query and update trip statistics if preparation succeeds.
if ($stmt) {
    // Method call: Parameter binding function.
    // String: bind_param("i", $customerID) is a MySQLi method that binds $customerID as an integer (i) to the query’s placeholder (?).
    // Attaches the customer ID to the query safely for secure trip count retrieval.
    $stmt->bind_param("i", $customerID);
    // Method call: Query execution function.
    // String: execute() is a MySQLi method that runs the prepared statement.
    // Executes the query to count the customer’s upcoming confirmed trips.
    $stmt->execute();
    // Array operation: Update to trip statistics.
    // Integer: $stmt->get_result()->fetch_assoc()['count'] retrieves the count field from the query result’s associative array.
    // Updates the 'upcomingTrips' key in $stats with the number of upcoming trips.
    $stats['upcomingTrips'] = $stmt->get_result()->fetch_assoc()['count'];
    // Method call: Statement closure function.
    // String: close() is a MySQLi method that frees resources associated with the prepared statement ($stmt).
    // Releases database resources after counting upcoming trips.
    $stmt->close();
}

// Variable: SQL SELECT query string.
// String: Defines a query joining 'scheduleinformation', 'route', and 'bus' tables to select ScheduleID, StartLocation, Destination, DepartureTime, ArrivalTime, Cost, and BusNumber for trips within the next 7 days, ordered by DepartureTime, StartLocation, and Destination. Includes a subquery to count individual seats by splitting SeatNumber strings.
// Retrieves upcoming bus schedules with seat counts for the dashboard’s schedule viewer, ensuring a 7-day lookahead.
$sql = "SELECT s.ScheduleID, r.StartLocation, r.Destination, s.DepartureTime, s.ArrivalTime, s.Cost, b.BusNumber,
               (SELECT SUM(LENGTH(bd.SeatNumber) - LENGTH(REPLACE(bd.SeatNumber, ',', '')) + 1) 
                FROM bookingdetails bd 
                WHERE bd.ScheduleID = s.ScheduleID AND bd.Status = 'Confirmed') as seat_count
        FROM scheduleinformation s
        JOIN route r ON s.RouteID = r.RouteID
        JOIN bus b ON s.BusID = b.BusID
        WHERE DATE(s.DepartureTime) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 6 DAY)
        ORDER BY DATE(s.DepartureTime) ASC, r.StartLocation ASC, r.Destination ASC, s.DepartureTime ASC";

// Variable: Query result storage.
// Object: $conn->query($sql) is a MySQLi method that executes the SQL query and returns a result set.
// Stores the schedule data (e.g., departure times, costs) for processing into the dashboard display.
$result = $conn->query($sql);

// Conditional statement: Logic to process schedule results.
// Boolean and integer check: Tests if $result is true (query succeeded) and $result->num_rows > 0, where num_rows is a MySQLi property indicating the number of rows.
// Processes schedule data into the $schedules array if results are found.
if ($result && $result->num_rows > 0) {
    // Loop: Iteration over query results.
    // Array: $result->fetch_assoc() is a MySQLi method that retrieves each row as an associative array, repeated using a while loop.
    // Processes each schedule record to format for dashboard display.
    while ($row = $result->fetch_assoc()) {
        // Array operation: Status determination for schedule.
        // Integer check: Compares $row['seat_count'], the subquery’s seat count, to 37 (bus capacity) to determine if the bus is full.
        // Sets 'status' to 'Full' if 37 or more seats are booked, or 'Available' if fewer, for clear dashboard display.
        $row['status'] = ($row['seat_count'] >= 37) ? 'Full' : 'Available';
        // Function call: Array element removal.
        // String: unset() is a PHP built-in function that removes $row['seat_count'] from the associative array.
        // Excludes seat_count from the output to keep the dashboard data clean and focused on relevant details.
        unset($row['seat_count']);
        // Array operation: Schedule data storage.
        // Array: Appends $row, an associative array with ScheduleID, StartLocation, Destination, DepartureTime, ArrivalTime, Cost, BusNumber, and status, to $schedules.
        // Collects formatted schedule data for the dashboard’s schedule viewer.
        $schedules[] = $row;
    }
}

// Method call: Connection closure function.
// String: close() is a MySQLi method that closes the database connection ($conn).
// Frees database resources after all queries are complete to maintain system efficiency.
$conn->close();

// Output statement: JSON success response output.
// String: echo with json_encode() converts an array with 'status' ('success'), 'stats' ($stats), and 'schedules' ($schedules) to a JSON string, where json_encode() is a PHP function that formats arrays as JSON.
// Sends customer statistics and schedule data to the client for display on the dashboard’s stats cards and schedule viewer.
echo json_encode(['status' => 'success', 'stats' => $stats, 'schedules' => $schedules]);
?>