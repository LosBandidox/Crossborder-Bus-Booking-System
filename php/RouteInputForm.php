<?php
// Include statement: File inclusion directive.
// String: include is a PHP statement that loads 'databaseconnection.php', a file that defines the $conn MySQLi connection object.
// Loads the database connection settings to store route details in the International Bus Booking System’s database.
include 'databaseconnection.php';

// Conditional statement: Logic to verify the HTTP request method.
// String check: $_SERVER["REQUEST_METHOD"] is a superglobal array element that returns the request method (e.g., "POST"), compared to "POST".
// Ensures the script processes only POST form submissions, as route data is sent via POST for security.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Variable: Start location storage.
// String: $_POST["startLocation"] is a superglobal array element from the form submission, containing the starting point of the route (e.g., "Nairobi").
// Captures the route’s origin for scheduling and booking purposes.
    $startLocation = $_POST["startLocation"];

    // Variable: Destination storage.
// String: $_POST["destination"] is a superglobal array element from the form submission, containing the endpoint of the route (e.g., "Mombasa").
// Captures the route’s destination for defining the travel path.
    $destination = $_POST["destination"];

    // Variable: Distance storage.
// String: $_POST["distance"] is a superglobal array element from the form submission, containing the route’s distance (e.g., "500.5").
// Captures the distance in kilometers for fare calculation and planning.
    $distance = $_POST["distance"];

    // Variable: Route name storage.
// String: $_POST["routeName"] is a superglobal array element from the form submission, containing the name of the route (e.g., "Nairobi-Mombasa Express").
// Provides a descriptive name for the route for easy identification.
    $routeName = $_POST["routeName"];

    // Variable: Route type storage.
// String: $_POST["routeType"] is a superglobal array element from the form submission, containing the route category (e.g., "Express", "Local").
// Categorizes the route for operational and scheduling purposes.
    $routeType = $_POST["routeType"];

    // Variable: Security information storage.
// String: $_POST["security"] is a superglobal array element from the form submission, containing security details (e.g., "Secure with patrols").
// Records security measures or status for the route to ensure passenger safety.
    $security = $_POST["security"];

    // Conditional statement: Logic to validate form inputs.
// Boolean checks: empty() is a PHP built-in function that tests if $startLocation, $destination, $distance, $routeName, $routeType, or $security is empty (e.g., "", null, or unset).
// Stops the script with an error if any required field is missing, ensuring complete route data.
    if (empty($startLocation) || empty($destination) || empty($distance) || empty($routeName) || empty($routeType) || empty($security)) {
        // Function call: Script termination function with message.
// String: die() is a PHP built-in function that outputs "All fields are required." and stops execution.
// Halts the script and informs the user to complete all fields in the form.
        die("All fields are required.");
    }

    // Variable: SQL INSERT query string.
// String: Defines a query to insert StartLocation, Destination, Distance, RouteName, RouteType, and Security into the 'route' table, using placeholders (?).
// Records the new route’s details in the database for scheduling and booking management.
    $sql = "INSERT INTO route (StartLocation, Destination, Distance, RouteName, RouteType, Security) VALUES (?, ?, ?, ?, ?, ?)";

    // Object: Prepared statement for database insertion.
// Object: $conn->prepare($sql) is a MySQLi method that creates a prepared statement object from the SQL query, where $conn is the MySQLi connection from databaseconnection.php.
// Prepares the query to insert route data securely, using placeholders to prevent SQL injection.
    $stmt = $conn->prepare($sql);

    // Conditional statement: Logic to check statement preparation.
// Boolean check: Tests if $stmt is false, indicating preparation failure due to SQL errors or connection issues.
// Stops the script with an error if the query cannot be prepared, ensuring reliable insertion.
    if ($stmt === false) {
        // Function call: Script termination function with message.
// String: die() is a PHP built-in function that outputs "Error preparing statement: " concatenated with $conn->error, a MySQLi property with the error message, and stops execution.
// Halts the script and informs the user of a server issue with the query preparation.
        die("Error preparing statement: " . $conn->error);
    }

    // Method call: Parameter binding function.
// String: bind_param("ssdsss", $startLocation, $destination, $distance, $routeName, $routeType, $security) is a MySQLi method that binds variables to the query’s placeholders: strings (s) for $startLocation, $destination, $routeName, $routeType, $security, and double (d) for $distance.
// Attaches route data to the query safely, preventing SQL injection for secure insertion.
    $stmt->bind_param("ssdsss", $startLocation, $destination, $distance, $routeName, $routeType, $security);

    // Conditional statement: Logic to execute the query and check success.
// Boolean check: $stmt->execute() is a MySQLi method that runs the prepared statement, returning TRUE on success or FALSE on failure.
// Redirects to the admin routes page on success or outputs an error on failure.
    if ($stmt->execute()) {
        // Function call: HTTP redirect function.
// String: header() is a PHP built-in function that sets the Location header to "../../frontend/dashboard/admin/admin_routes.html".
// Redirects the user to the admin routes dashboard after successfully adding the route.
        header("Location: ../../frontend/dashboard/admin/admin_routes.html");
        // Function call: Script termination function.
// String: exit() is a PHP built-in function that stops script execution.
// Halts the script after the redirect to prevent further processing.
        exit();
    } else {
        // Output statement: Error message output.
// String: echo outputs "Error: " concatenated with $stmt->error, a MySQLi property with the error message.
// Informs the user of a database insertion failure, providing details for debugging (e.g., duplicate route name).
        echo "Error: " . $stmt->error;
    }

    // Method call: Statement closure function.
// String: close() is a MySQLi method that frees resources associated with the prepared statement ($stmt).
// Releases database resources after inserting route data to maintain system efficiency.
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