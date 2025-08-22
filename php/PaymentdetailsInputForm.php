<?php
// Include: Loads the database connection configuration
// File: databaseconnection.php defines $conn for database access
// Establishes a connection to the MySQL database
include 'databaseconnection.php';

// Conditional statement: Verifies the HTTP request method.
// Superglobal: Uses $_SERVER["REQUEST_METHOD"] to check for a POST request.
// Ensures the script processes only form submissions.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Variable: Stores the booking ID from the POST request.
    // String: Retrieved from $_POST["bookingID"].
    // Identifies the booking associated with the payment.
    $bookingID = $_POST["bookingID"];

    // Variable: Stores the amount paid from the POST request.
    // String: Retrieved from $_POST["amountPaid"].
    // Specifies the payment amount.
    $amountPaid = $_POST["amountPaid"];

    // Variable: Stores the payment mode from the POST request.
    // String: Retrieved from $_POST["paymentMode"].
    // Indicates the payment method (e.g., Mobile Money, Card).
    $paymentMode = $_POST["paymentMode"];

    // Variable: Stores the payment date from the POST request.
    // String: Retrieved from $_POST["paymentDate"].
    // Records the date of the payment.
    $paymentDate = $_POST["paymentDate"];

    // Variable: Stores the receipt number from the POST request.
    // String: Retrieved from $_POST["receiptNumber"].
    // Identifies the payment receipt.
    $receiptNumber = $_POST["receiptNumber"];

    // Variable: Stores the transaction ID from the POST request.
    // String: Retrieved from $_POST["transactionID"].
    // Identifies the payment transaction.
    $transactionID = $_POST["transactionID"];

    // Variable: Stores the cashier ID from the POST request.
    // String: Retrieved from $_POST["cashierID"].
    // Identifies the cashier processing the payment.
    $cashierID = $_POST["cashierID"];

    // Conditional statement: Validates form data for completeness.
    // Function calls: Uses empty() to check if any variable is unset or empty.
    // Terminates execution with an error message if any field is missing.
    if (empty($bookingID) || empty($amountPaid) || empty($paymentMode) || empty($paymentDate) || empty($receiptNumber) || empty($transactionID) || empty($cashierID)) {
        die("All fields are required.");
    }

    // String: SQL INSERT query with placeholders (?).
    // Structure: Inserts BookingID, AmountPaid, PaymentMode, PaymentDate, ReceiptNumber, TransactionID, and CashierID into the 'paymentdetails' table.
    // Records a new payment entry in the database.
    $sql = "INSERT INTO paymentdetails (BookingID, AmountPaid, PaymentMode, PaymentDate, ReceiptNumber, TransactionID, CashierID) VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Object: Creates a prepared statement for secure query execution.
    // Method call: Uses $conn->prepare($sql) to prepare the SQL query.
    // Binds the query for parameter substitution.
    $stmt = $conn->prepare($sql);

    // Conditional statement: Checks if statement preparation succeeded.
    // Comparison: Tests if $stmt is false to detect errors.
    // Terminates execution with an error message if preparation fails.
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Method call: Binds variables to the prepared statement’s placeholders.
    // String: 'idssssi' specifies types: integer (i) for $bookingID and $cashierID, double (d) for $amountPaid, strings (s) for $paymentMode, $paymentDate, $receiptNumber, $transactionID.
    // Links variables to the query for safe execution.
    $stmt->bind_param("idssssi", $bookingID, $amountPaid, $paymentMode, $paymentDate, $receiptNumber, $transactionID, $cashierID);

    // Conditional statement: Executes the prepared statement and checks the result.
    // Method call: Uses $stmt->execute() to insert the data.
    // Outputs a success or error message based on the outcome.
    if ($stmt->execute()) {
        // Output statement: Sends a success message.
        // String: Indicates the payment data was submitted successfully.
        // Informs the user of successful submission.
        echo "Payment data submitted successfully!";
    } else {
        // Output statement: Sends an error message.
        // String: Includes $stmt->error for details.
        // Informs the user of the failure.
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