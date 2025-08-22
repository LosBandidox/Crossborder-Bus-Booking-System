<!DOCTYPE html> 
<html>
<head>
    <title>Query Results</title>
</head>
<body>
    <h1>Database Query Results</h1> 

    <?php
    // Step 1: Initialize database connection parameters
    $DB_HOST = 'localhost'; // Variable storing the database server hostname
    $DB_USER = 'busbooking'; // Variable storing the database username
    $DB_PASS = 'password123'; // Variable storing the database password
    $DB_NAME = 'InternationalBusBookingSystem'; // Variable storing the database name

    // Step 2: Establish connection to the database using the `mysqli` class
    $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

    // Error handling: Check if the connection failed
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error); // Terminates the script if connection fails
    }

    // Query 1: Monthly booking report
    echo "<h2>Monthly Booking Report</h2>"; // Subheading for the report
    $query1 = "SELECT BookingDetails.BookingID, Customer.Name AS CustomerName, 
               ScheduleInformation.DepartureTime, Route.RouteName 
               FROM BookingDetails 
               JOIN Customer ON BookingDetails.CustomerID = Customer.CustomerID 
               JOIN ScheduleInformation ON BookingDetails.ScheduleID = ScheduleInformation.ScheduleID 
               JOIN Route ON ScheduleInformation.RouteID = Route.RouteID 
               WHERE ScheduleInformation.DepartureTime BETWEEN '2024-12-01' AND '2024-12-31'";
               // SQL query to fetch booking details for December 2024

    // Executes the query and stores the result set in `$result1`
    $result1 = $conn->query($query1);

    // Display results in an unordered list 
    echo "<ul>";
    while ($row = $result1->fetch_assoc()) { // Loop to iterate through each record in the result set
        echo "<li>Booking ID: " . $row["BookingID"] . ", Customer Name: " . $row["CustomerName"] . 
             ", Departure Time: " . $row["DepartureTime"] . ", Route: " . $row["RouteName"] . "</li>";
             // Prints details of each booking record
    }
    echo "</ul>"; // Ends the unordered list

    // Query 2: Total revenue per route
    echo "<h2>Revenue Report</h2>";
    $query2 = "SELECT Route.RouteName, SUM(PaymentDetails.AmountPaid) AS TotalRevenue 
               FROM PaymentDetails 
               JOIN BookingDetails ON PaymentDetails.BookingID = BookingDetails.BookingID 
               JOIN ScheduleInformation ON BookingDetails.ScheduleID = ScheduleInformation.ScheduleID 
               JOIN Route ON ScheduleInformation.RouteID = Route.RouteID 
               GROUP BY Route.RouteName";
               // SQL query to calculate revenue grouped by route name
    $result2 = $conn->query($query2);

    echo "<ul>";
    while ($row = $result2->fetch_assoc()) { // Fetches each row of the query result
        echo "<li>Route: " . $row["RouteName"] . ", Total Revenue: " . $row["TotalRevenue"] . "</li>";
        // Displays the route name and total revenue
    }
    echo "</ul>";

    // Query 3: Number of trips each bus completed
    echo "<h2>Bus Utilization Report</h2>";
    $query3 = "SELECT Bus.BusID, Bus.BusNumber, COUNT(ScheduleInformation.ScheduleID) AS NumberOfTrips 
               FROM Bus 
               JOIN ScheduleInformation ON Bus.BusID = ScheduleInformation.BusID 
               GROUP BY Bus.BusID, Bus.BusNumber";
               // SQL query to count trips per bus
    $result3 = $conn->query($query3);

    echo "<ul>";
    while ($row = $result3->fetch_assoc()) { // Iterates through each bus record
        echo "<li>Bus ID: " . $row["BusID"] . ", Bus Number: " . $row["BusNumber"] . 
             ", Number of Trips: " . $row["NumberOfTrips"] . "</li>";
             // Outputs bus usage details
    }
    echo "</ul>";

    // Query 4: Customers with nationality starting with "Kenya"
    echo "<h2>Customer Report</h2>";
    $query4 = "SELECT Customer.Name, Customer.Nationality, COUNT(BookingDetails.BookingID) AS TotalBookings 
               FROM Customer 
               JOIN BookingDetails ON Customer.CustomerID = BookingDetails.CustomerID 
               WHERE Customer.Nationality LIKE 'Kenya%' 
               GROUP BY Customer.Name, Customer.Nationality";
               // SQL query to fetch details of customers from Kenya
    $result4 = $conn->query($query4);

    echo "<ul>";
    while ($row = $result4->fetch_assoc()) { // Loop to fetch each customer record
        echo "<li>Name: " . $row["Name"] . ", Nationality: " . $row["Nationality"] . 
             ", Total Bookings: " . $row["TotalBookings"] . "</li>";
             // Displays customer details and their total bookings
    }
    echo "</ul>";

    // Query 5: Route Performance Report
    echo "<h2>Route Performance Report</h2>";
    $query5 = "SELECT 
               Route.RouteName AS RouteName,COUNT(BookingDetails.BookingID) AS TotalBookings,
               SUM(PaymentDetails.AmountPaid) AS TotalRevenue
               FROM Route
               INNER JOIN ScheduleInformation ON Route.RouteID = ScheduleInformation.RouteID
               INNER JOIN BookingDetails ON ScheduleInformation.ScheduleID = BookingDetails.ScheduleID
               INNER JOIN PaymentDetails ON BookingDetails.BookingID = PaymentDetails.BookingID
               WHERE Route.RouteType LIKE '%Domestic%'
               AND ScheduleInformation.DepartureTime BETWEEN '2024-01-01' AND '2024-12-31'
               GROUP BY Route.RouteName";

    $result5 = $conn->query($query5);

    echo "<ul>";
    while ($row = $result5->fetch_assoc()) {
        echo "<li>Route: " . $row["RouteName"] . ", Total Bookings: " . $row["TotalBookings"] . 
             ", Total Revenue: " . $row["TotalRevenue"] . "</li>";
    }
    echo "</ul>";

    // Query 6: Maintenance Report
    echo "<h2>Maintenance Report</h2>";
    $query6 = "SELECT Bus.BusID, Bus.BusNumber, Maintenance.ServiceDate, Maintenance.ServiceDone 
               FROM Bus 
               LEFT JOIN Maintenance ON Bus.BusID = Maintenance.BusID 
               WHERE Maintenance.ServiceDate IS NULL 
               OR Maintenance.ServiceDone LIKE '%engine%'";
    $result6 = $conn->query($query6);

    echo "<ul>";
    while ($row = $result6->fetch_assoc()) {
        echo "<li>Bus ID: " . $row["BusID"] . ", Bus Number: " . $row["BusNumber"] . 
             ", Service Date: " . $row["ServiceDate"] . ", Service Done: " . $row["ServiceDone"] . "</li>";
    }
    echo "</ul>";

    // Query 7: Payment Report
    echo "<h2>Payment Report</h2>";
    $query7 = "SELECT PaymentDetails.PaymentID, PaymentDetails.AmountPaid, PaymentDetails.PaymentMode, 
               Customer.Name AS CustomerName, Staff.Name AS CashierName 
               FROM PaymentDetails 
               JOIN BookingDetails ON PaymentDetails.BookingID = BookingDetails.BookingID 
               JOIN Customer ON BookingDetails.CustomerID = Customer.CustomerID 
               LEFT JOIN Staff ON PaymentDetails.CashierID = Staff.StaffID 
               WHERE PaymentDetails.PaymentMode = 'Credit Card' 
               OR PaymentDetails.PaymentMode = 'Cash'";
    $result7 = $conn->query($query7);

    echo "<ul>";
    while ($row = $result7->fetch_assoc()) {
        echo "<li>Payment ID: " . $row["PaymentID"] . ", Amount Paid: " . $row["AmountPaid"] . 
             ", Payment Mode: " . $row["PaymentMode"] . ", Customer Name: " . $row["CustomerName"] . 
             ", Cashier Name: " . $row["CashierName"] . "</li>";
    }
    echo "</ul>";

    // Query 8: Bus Details
    echo "<h2>Bus Details</h2>";
    $query8 = "SELECT BusNumber, Status, Mileage FROM bus";
    $result8 = $conn->query($query8);

    echo "<ul>";
    while ($row = $result8->fetch_assoc()) {
        echo "<li>Bus Number: " . $row["BusNumber"] . ", Status: " . $row["Status"] . 
             ", Mileage: " . $row["Mileage"] . "</li>";
    }
    echo "</ul>";

    // Query 9: Route Details
    echo "<h2>Route Details</h2>";
    $query9 = "SELECT RouteName, RouteType, Security FROM route";
    $result9 = $conn->query($query9);

    echo "<ul>";
    while ($row = $result9->fetch_assoc()) {
        echo "<li>Route Name: " . $row["RouteName"] . ", Route Type: " . $row["RouteType"] . 
             ", Security: " . $row["Security"] . "</li>";
    }
    echo "</ul>";

    // Query 10: Customer Details
    echo "<h2>Customer Details</h2>";
    $query10 = "SELECT Name, Nationality FROM customer";
    $result10 = $conn->query($query10);

    echo "<ul>";
    while ($row = $result10->fetch_assoc()) {
        echo "<li>Name: " . $row["Name"] . ", Nationality: " . $row["Nationality"] . "</li>";
    }
    echo "</ul>";

    // Query 11: Staff Details
    echo "<h2>Staff Details</h2>";
    $query11 = "SELECT Name, Role FROM staff";
    $result11 = $conn->query($query11);

    echo "<ul>";
    while ($row = $result11->fetch_assoc()) {
        echo "<li>Name: " . $row["Name"] . ", Role: " . $row["Role"] . "</li>";
    }
    echo "</ul>";

    // Query 12: Schedule Information
    echo "<h2>Schedule Information</h2>";
    $query12 = "SELECT ScheduleID, BusID, RouteID, DepartureTime, ArrivalTime, Cost FROM scheduleinformation";
    $result12 = $conn->query($query12);

    echo "<ul>";
    while ($row = $result12->fetch_assoc()) {
        echo "<li>Schedule ID: " . $row["ScheduleID"] . ", Bus ID: " . $row["BusID"] . 
             ", Route ID: " . $row["RouteID"] . ", Departure Time: " . $row["DepartureTime"] . 
             ", Arrival Time: " . $row["ArrivalTime"] . ", Cost: " . $row["Cost"] . "</li>";
    }
    echo "</ul>";

    // Closing the database connection
    $conn->close(); // Function call to safely terminate the connection to the database
    ?>
</body>
</html>
