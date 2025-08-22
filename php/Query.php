<?php
// Database connection parameters
$DB_HOST = 'localhost';        // Hostname of the MySQL server
$DB_USER = 'busbooking';             // My MySQL username
$DB_PASS = 'password123';       // My MySQL password
$DB_NAME = 'InternationalBusBookingSystem'; // My database name

// Create connection
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If successful
echo "Connected successfully<br><br>";

// Function to execute a SQL query and display the results
function executeQuery($conn, $query, $title) {
    // Display the title of the report (using <h3> for a header)
    echo "<h3>$title</h3>";
    
    // Execute the SQL query using the provided connection ($conn)
    // The query result is stored in the $result variable
    $result = $conn->query($query);
    
    // Check if the query returned any rows (results)
    if ($result->num_rows > 0) {
        // Loop through each row in the result set
        while ($row = $result->fetch_assoc()) {
            // Combine the values of the row into a single string, separated by " | "
            // Then display the string followed by a line break (<br>)
            echo implode(" | ", $row) . "<br>";
        }
    } else {
        // If no rows were returned, display a message saying "No results found."
        echo "No results found.<br>";
    }

    // Add an extra line break (<br>) for spacing before the next report
    echo "<br>";
}

// SQL queries for various reports
$queries = [
    [
        'query' => "SELECT BookingDetails.BookingID, Customer.Name AS CustomerName, ScheduleInformation.DepartureTime, Route.RouteName FROM BookingDetails JOIN Customer ON BookingDetails.CustomerID = Customer.CustomerID JOIN ScheduleInformation ON BookingDetails.ScheduleID = ScheduleInformation.ScheduleID JOIN Route ON ScheduleInformation.RouteID = Route.RouteID WHERE ScheduleInformation.DepartureTime BETWEEN '2024-12-01' AND '2024-12-31';",
        'title' => 'Monthly Booking Report'
    ],
    [
        'query' => "SELECT Route.RouteName, SUM(PaymentDetails.AmountPaid) AS TotalRevenue FROM PaymentDetails JOIN BookingDetails ON PaymentDetails.BookingID = BookingDetails.BookingID JOIN ScheduleInformation ON BookingDetails.ScheduleID = ScheduleInformation.ScheduleID JOIN Route ON ScheduleInformation.RouteID = Route.RouteID GROUP BY Route.RouteName;",
        'title' => 'Revenue Report'
    ],
    [
        'query' => "SELECT Bus.BusID, Bus.BusNumber, COUNT(ScheduleInformation.ScheduleID) AS NumberOfTrips FROM Bus JOIN ScheduleInformation ON Bus.BusID = ScheduleInformation.BusID GROUP BY Bus.BusID, Bus.BusNumber;",
        'title' => 'Bus Utilization Report'
    ],
    [
        'query' => "SELECT Customer.Name, Customer.Nationality, COUNT(BookingDetails.BookingID) AS TotalBookings FROM Customer JOIN BookingDetails ON Customer.CustomerID = BookingDetails.CustomerID WHERE Customer.Nationality LIKE 'Kenya%' GROUP BY Customer.Name, Customer.Nationality;",
        'title' => 'Customer Report'
    ],
    [
        'query' => "SELECT Route.RouteName, COUNT(BookingDetails.BookingID) AS TotalBookings, SUM(PaymentDetails.AmountPaid) AS TotalRevenue FROM Route JOIN ScheduleInformation ON Route.RouteID = ScheduleInformation.RouteID JOIN BookingDetails ON ScheduleInformation.ScheduleID = BookingDetails.ScheduleID JOIN PaymentDetails ON BookingDetails.BookingID = PaymentDetails.BookingID WHERE Route.RouteName LIKE '%Domestic%' AND ScheduleInformation.DepartureTime BETWEEN '2024-01-01' AND '2024-12-31' GROUP BY Route.RouteName;",
        'title' => 'Route Performance Report'
    ],
    [
        'query' => "SELECT Bus.BusID, Bus.BusNumber, Maintenance.ServiceDate, Maintenance.ServiceDone FROM Bus LEFT JOIN Maintenance ON Bus.BusID = Maintenance.BusID WHERE Maintenance.ServiceDate IS NULL OR Maintenance.ServiceDone LIKE '%engine%';",
        'title' => 'Maintenance Report'
    ],
    [
        'query' => "SELECT PaymentDetails.PaymentID, PaymentDetails.AmountPaid, PaymentDetails.PaymentMode, Customer.Name AS CustomerName, Staff.Name AS CashierName FROM PaymentDetails JOIN BookingDetails ON PaymentDetails.BookingID = BookingDetails.BookingID JOIN Customer ON BookingDetails.CustomerID = Customer.CustomerID LEFT JOIN Staff ON PaymentDetails.CashierID = Staff.StaffID WHERE PaymentDetails.PaymentMode = 'Credit Card' OR PaymentDetails.PaymentMode = 'Cash';",
        'title' => 'Payment Report'
    ],
    [
        'query' => "SELECT BusNumber, Status, Mileage FROM bus;",
        'title' => 'Bus Information'
    ],
    [
        'query' => "SELECT RouteName, RouteType, Security FROM route;",
        'title' => 'Route Information'
    ],
    [
        'query' => "SELECT Name, Nationality FROM customer;",
        'title' => 'Customer Information'
    ],
    [
        'query' => "SELECT Name, Role FROM staff;",
        'title' => 'Staff Information'
    ],
    [
        'query' => "SELECT ScheduleID, BusID, RouteID, DepartureTime, ArrivalTime, Cost FROM scheduleinformation;",
        'title' => 'Schedule Information'
    ],
];

// Execute each query and display the results
foreach ($queries as $report) {
    executeQuery($conn, $report['query'], $report['title']);
}

// Close connection when done (optional, but good practice)
$conn->close();
?>
