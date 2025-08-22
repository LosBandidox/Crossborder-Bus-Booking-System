<?php
// Include statement: File inclusion directive with error handling.
// String: require_once is a PHP statement that loads 'databaseconnection.php', a file that defines the $conn MySQLi connection object, and stops execution if the file is missing.
// Loads the database connection settings to query bus schedules for the search in the International Bus Booking System.
require_once 'databaseconnection.php';

// Include statement: File inclusion directive with error handling.
// String: require_once is a PHP statement that loads 'dateUtils.php', a file that defines the parseDateInput() function for date formatting.
// Loads the date parsing function to convert user input dates to a database-compatible format.
require_once 'dateUtils.php';

// Conditional statement: Logic to verify the HTTP request method.
// String check: $_SERVER["REQUEST_METHOD"] is a superglobal array element that returns the request method (e.g., "POST"), compared to "POST".
// Ensures the script processes only POST form submissions, as search data is sent via POST for security.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Variable: Starting location storage.
    // String: $_POST["from"] is a superglobal array element from the form submission, containing the starting city of the travel route.
    // Captures the user’s selected starting location for the bus search.
    $from = $_POST["from"];

    // Variable: Destination storage.
    // String: $_POST["to"] is a superglobal array element from the form submission, containing the destination city of the travel route.
    // Captures the user’s selected destination for the bus search.
    $to = $_POST["to"];

    // Variable: Travel date input storage.
    // String: $_POST["date"] is a superglobal array element from the form submission, containing the travel date in DD-MM-YYYY format.
    // Captures the user’s selected travel date for filtering bus schedules.
    $dateInput = $_POST["date"];

    // Variable: Parsed travel date storage.
    // String or null: parseDateInput($dateInput) is a custom function from dateUtils.php that converts DD-MM-YYYY to YYYY-MM-DD for database use, returning null if invalid.
    // Converts the user’s date input to a format compatible with the database’s DATE function.
    $date = parseDateInput($dateInput);

    // Conditional statement: Logic to validate form inputs and date.
    // Boolean checks: empty() is a PHP built-in function that tests if $from, $to, or $dateInput is empty; $date === null checks if parseDateInput() failed.
    // Stops the script with an error if any field is missing or the date is invalid, ensuring valid search parameters.
    if (empty($from) || empty($to) || empty($dateInput) || $date === null) {
        // Function call: Script termination function with message.
        // String: die() is a PHP built-in function that outputs "All fields are required, and date must be valid (DD-MM-YYYY)." and stops execution.
        // Halts the script and informs the user to complete all fields with a valid date format.
        die("All fields are required, and date must be valid (DD-MM-YYYY).");
    }

    // Variable: SQL SELECT query string.
    // String: Defines a query joining 'scheduleinformation', 'route', and 'bus' tables to select ScheduleID, BusNumber, DepartureTime, and Cost where StartLocation, Destination, and the date part of DepartureTime match placeholders (?).
    // Retrieves available bus schedules for the user’s route and date to display search results.
    $sql = "SELECT scheduleinformation.ScheduleID, bus.BusNumber, scheduleinformation.DepartureTime, scheduleinformation.Cost 
            FROM scheduleinformation 
            JOIN route ON scheduleinformation.RouteID = route.RouteID 
            JOIN bus ON scheduleinformation.BusID = bus.BusID 
            WHERE route.StartLocation = ? 
            AND route.Destination = ? 
            AND DATE(scheduleinformation.DepartureTime) = ?";

    // Object: Prepared statement for database query.
    // Object: $conn->prepare($sql) is a MySQLi method that creates a prepared statement object from the SQL query, where $conn is the MySQLi connection from databaseconnection.php.
    // Prepares the query to fetch bus schedules securely, using placeholders to prevent SQL injection.
    $stmt = $conn->prepare($sql);

    // Conditional statement: Logic to check statement preparation.
    // Boolean check: Tests if $stmt is false, indicating preparation failure due to SQL errors or connection issues.
    // Stops the script with an error if the query cannot be prepared, ensuring reliable data retrieval.
    if ($stmt === false) {
        // Function call: Script termination function with message.
        // String: die() is a PHP built-in function that outputs "Error preparing statement: " concatenated with $conn->error, a MySQLi property with the error message, and stops execution.
        // Halts the script and informs the user of a server issue with the query preparation.
        die("Error preparing statement: " . $conn->error);
    }

    // Method call: Parameter binding function.
    // String: bind_param("sss", $from, $to, $date) is a MySQLi method that binds $from, $to, and $date as strings (s) to the query’s placeholders (?).
    // Attaches the search parameters to the query safely, preventing SQL injection for secure data retrieval.
    $stmt->bind_param("sss", $from, $to, $date);

    // Conditional statement: Logic to execute the query and check success.
    // Boolean check: $stmt->execute() is a MySQLi method that runs the prepared statement, returning TRUE on success or FALSE on failure.
    // Proceeds with result processing if the query executes successfully, or sends an error if it fails.
    if ($stmt->execute()) {
        // Variable: Query result storage.
        // Object: $stmt->get_result() is a MySQLi method that retrieves the result set from the executed prepared statement.
        // Stores the bus schedule data for displaying search results.
        $result = $stmt->get_result();

        // Conditional statement: Logic to check if results were found.
        // Integer check: $result->num_rows is a MySQLi property that returns the number of rows in the result set, compared to 0.
        // Displays a results page if buses are found, or a no-results page if none are found.
        if ($result->num_rows > 0) {
            // Output statement: HTML document start.
            // String: echo outputs an HTML document with DOCTYPE, meta tags for UTF-8 and responsive viewport, a title ("Search Results"), and a link to '/assets/css/Styles.css' for styling.
            // Begins the search results page structure for displaying available buses.
            echo "<!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Search Results</title>
                <link rel='stylesheet' href='/assets/css/Styles.css'>
            </head>
            <body>
                <header>
                    <h1>Search Results</h1>
                    <nav>
                        <ul>
                            <li><a href='/frontend/dashboard/customer/CustomerDashboard.html'>Home</a></li>
                            <li><a href='/frontend/dashboard/customer/BookingHistory.html'>Booking History</a></li>
                            <li><a href='/frontend/dashboard/customer/ProfileManagement.html'>Profile</a></li>
                            <li><a href='/php/logout.php'>Logout</a></li>
                        </ul>
                    </nav>
                </header>
                <main>
                    <div class='dashboard-container'>
                        <h2>Search Results</h2>
                        <button class='back-btn' onclick='history.back()'>Back</button>
                        <p>From: $from | To: $to | Date: $dateInput</p>
                        <table>
                            <tr>
                                <th>Bus Number</th>
                                <th>Departure Time</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>";

            // Loop: Iteration over query results.
            // Array: $result->fetch_assoc() is a MySQLi method that retrieves each row as an associative array, repeated using a while loop.
            // Processes each bus schedule to display its details in a table row.
            while ($row = $result->fetch_assoc()) {
                // Output statement: HTML table row for each bus.
                // String: echo outputs a table row with $row['BusNumber'], $row['DepartureTime'], $row['Cost'] (prefixed with $), and a link to '/frontend/dashboard/customer/SeatSelection.html?scheduleID=' with $row['ScheduleID'].
                // Displays bus details and a "Book Now" link to proceed to seat selection.
                echo "<tr>
                        <td>{$row['BusNumber']}</td>
                        <td>{$row['DepartureTime']}</td>
                        <td>\${$row['Cost']}</td>
                        <td><a href='/frontend/dashboard/customer/SeatSelection.html?scheduleID={$row['ScheduleID']}'>Book Now</a></td>
                      </tr>";
            }

            // Output statement: HTML document completion.
            // String: echo outputs the closing table, main, footer with a copyright notice ("© 2025 International Bus Booking System..."), and body/html tags.
            // Completes the search results page with a styled table and navigation footer.
            echo "</table>
                    </div>
                </main>
                <footer>
                    <p>© 2025 International Bus Booking System. All rights reserved.</p>
                </footer>
            </body>
            </html>";
        } else {
            // Output statement: HTML document for no results.
            // String: echo outputs an HTML document with DOCTYPE, meta tags for UTF-8 and responsive viewport, a title ("No Buses Available"), a link to '/assets/css/Styles.css', a header with navigation, a message with $from, $to, and $dateInput, and a "Back to Search" link.
            // Displays a styled page informing the user no buses were found for the specified route and date.
            echo "<!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>No Buses Available</title>
                <link rel='stylesheet' href='/assets/css/Styles.css'>
            </head>
            <body>
                <header>
                    <h1>No Buses Available</h1>
                    <nav>
                        <ul>
                            <li><a href='/frontend/dashboard/customer/CustomerDashboard.html'>Home</a></li>
                            <li><a href='/frontend/dashboard/customer/BookingHistory.html'>Booking History</a></li>
                            <li><a href='/frontend/dashboard/customer/ProfileManagement.html'>Profile</a></li>
                            <li><a href='/php/logout.php'>Logout</a></li>
                        </ul>
                    </nav>
                </header>
                <main>
                    <div class='dashboard-container'>
                        <h2>No Buses Found</h2>
                        <p>Sorry, there are no buses available for your selected route from <strong>$from</strong> to <strong>$to</strong> on <strong>$dateInput</strong>.</p>
                        <p>Please try a different route or date.</p>
                        <a href='/frontend/dashboard/customer/CustomerDashboard.html' class='btn'>Back to Search</a>
                    </div>
                </main>
                <footer>
                    <p>© 2025 International Bus Booking System. All rights reserved.</p>
                </footer>
            </body>
            </html>";
        }
    } else {
        // Output statement: Error message output.
        // String: echo outputs "Error executing query: " concatenated with $stmt->error, a MySQLi property with the error message.
        // Informs the user of a query execution failure, likely due to database issues.
        echo "Error executing query: " . $stmt->error;
    }

    // Method call: Statement closure function.
    // String: close() is a MySQLi method that frees resources associated with the prepared statement ($stmt).
    // Releases database resources after fetching search results to maintain system efficiency.
    $stmt->close();

    // Method call: Connection closure function.
    // String: close() is a MySQLi method that closes the database connection ($conn).
    // Frees database resources after all operations, ensuring no connections remain open.
    $conn->close();
} else {
    // Output statement: Error message output.
    // String: echo outputs "Invalid request method.".
    // Informs the user that a non-POST request was used, enforcing secure form submission.
    echo "Invalid request method.";
}
?>