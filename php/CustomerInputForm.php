<?php
// Function call: Initiates a session.
// Uses session_start() to enable session management.
// Allows access to session variables for user data.
session_start();

// Include: Loads the database connection configuration
// File: /php/databaseconnection.php defines $conn for database access
// Establishes a connection to the MySQL database
include 'databaseconnection.php';

// Conditional statement: Verifies the HTTP request method.
// Superglobal: Uses $_SERVER["REQUEST_METHOD"] to check for a POST request.
// Ensures the script processes only form submissions.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Variable: Stores the customer’s name from the POST request.
    // String: Retrieved from $_POST["name"].
    // Represents the full name of the customer.
    $name = $_POST["name"];

    // Variable: Stores the customer’s email from the POST request.
    // String: Retrieved from $_POST["email"].
    // Represents the email address of the customer.
    $email = $_POST["email"];

    // Variable: Stores the customer’s phone number from the POST request.
    // String: Retrieved from $_POST["phoneNumber"].
    // Represents the contact number of the customer.
    $phoneNumber = $_POST["phoneNumber"];

    // Variable: Stores the customer’s gender from the POST request.
    // String: Retrieved from $_POST["gender"].
    // Indicates the customer’s gender (e.g., Male, Female).
    $gender = $_POST["gender"];

    // Variable: Stores the customer’s passport number from the POST request.
    // String: Retrieved from $_POST["passportNumber"].
    // Represents the unique passport identifier of the customer.
    $passportNumber = $_POST["passportNumber"];

    // Variable: Stores the customer’s nationality from the POST request.
    // String: Retrieved from $_POST["nationality"].
    // Represents the customer’s country of citizenship.
    $nationality = $_POST["nationality"];

    // Variable: Stores the schedule ID from the POST request.
    // String: Retrieved from $_POST["scheduleID"].
    // Identifies the bus schedule for the booking.
    $scheduleID = $_POST["scheduleID"];

    // Conditional statement: Validates form data for completeness.
    // Function calls: Uses empty() to check if any required field is unset or empty.
    // Terminates execution with an error message if any field is missing.
    if (empty($name) || empty($email) || empty($phoneNumber) || empty($gender) || empty($passportNumber) || empty($nationality)) {
        die("All fields are required.");
    }

    // String: SQL INSERT query with placeholders (?).
    // Structure: Inserts Name, Email, PhoneNumber, Gender, PassportNumber, and Nationality into the 'customer' table.
    // Records a new customer entry in the database.
    $sql = "INSERT INTO customer (Name, Email, PhoneNumber, Gender, PassportNumber, Nationality) VALUES (?, ?, ?, ?, ?, ?)";

    // Object: Creates a prepared statement for secure query execution.
    // Method call: Uses $conn->prepare($sql) to prepare the SQL query.
    // Binds the query for parameter substitution.
    $stmt = $conn->prepare($sql);

    // Method call: Binds variables to the prepared statement’s placeholders.
    // String: 'ssssss' specifies types as strings for $name, $email, $phoneNumber, $gender, $passportNumber, and $nationality.
    // Links variables to the query for safe execution.
    $stmt->bind_param("ssssss", $name, $email, $phoneNumber, $gender, $passportNumber, $nationality);

    // Conditional statement: Executes the prepared statement and checks the result.
    // Method call: Uses $stmt->execute() to insert the data.
    // Redirects on success or outputs an error on failure.
    if ($stmt->execute()) {
        // Variable: Stores the auto-generated customer ID.
        // Integer: Retrieved from $stmt->insert_id.
        // Captures the ID of the newly inserted customer.
        $customerID = $stmt->insert_id;

        // Variable: Stores the seat number from the POST request.
        // String: Retrieved from $_POST["seatNumber"].
        // Specifies the selected seat for the booking.
        $seatNumber = $_POST["seatNumber"];

        // Function call: Redirects to the booking details form.
        // String: URL with query parameters for customerID, scheduleID, and seatNumber.
        // Sends the user to input booking details.
        header("Location: ../frontend/forms/BookingdetailsInputForm.html?customerID=$customerID&scheduleID=$scheduleID&seatNumber=$seatNumber");

        // Function call: Terminates script execution.
        // Ensures no further code runs after the redirect.
        exit();
    } else {
        // Output statement: Sends an error message.
        // String: Includes $stmt->error for details.
        // Informs the user of the insertion failure.
        echo "Error: " . $stmt->error;
    }

    // Method call: Closes the prepared statement.
    // Frees database resources associated with the statement.
    $stmt->close();

    // Method call: Closes the database connection.
    // Frees database resources.
    $conn->close();
} else {
    // Output statement: Sends an error message.
    // String: Indicates the request method is not POST.
    // Informs the user of an invalid request.
    echo "Invalid request method.";
}
?>