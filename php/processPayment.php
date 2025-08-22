<?php
// Function call: Error reporting configuration function.
// Constant: error_reporting() is a PHP built-in function that sets the error reporting level, using E_ALL to include all error types (e.g., notices, warnings, fatal errors).
// Enables all error reporting for debugging during development of the International Bus Booking System, typically disabled in production to avoid exposing errors.
error_reporting(E_ALL);

// Function call: PHP configuration setting function.
// String: ini_set() is a PHP built-in function that sets the 'display_errors' configuration to '1', enabling error output to the client.
// Displays errors directly in the browser for debugging, typically disabled in production for security.
ini_set('display_errors', 1);

// Function call: HTTP header setting function.
// String: header() is a PHP built-in function that sets an HTTP response header, here setting 'Content-Type: application/json'.
// Sets the response format to JSON, ensuring the client (e.g., JavaScript) can parse payment processing results correctly.
header('Content-Type: application/json');

// Include statement: File inclusion directive.
// String: include is a PHP statement that loads 'databaseconnection.php', a file that defines the $conn MySQLi connection object.
// Loads the database connection settings to store payment details in the database.
include 'databaseconnection.php';

// Function call: File writing function for debugging.
// Parameters: file_put_contents() is a PHP built-in function that writes to 'debug.log', appending ("POST data: " concatenated with print_r($_POST, true), a function that converts the $_POST array to a string) with the FILE_APPEND flag.
// Logs POST data (e.g., bookingID, amountPaid) to a file for troubleshooting payment issues.
file_put_contents('debug.log', "POST data: " . print_r($_POST, true) . "\n", FILE_APPEND);

// Variable: Booking ID storage.
// Mixed: $_POST['bookingID'] is a superglobal array element from the form submission, accessed with the null coalescing operator (??), returning null if unset.
// Identifies the booking for which the payment is being processed.
$bookingID = $_POST['bookingID'] ?? null;

// Variable: Payment amount storage.
// Mixed: $_POST['amountPaid'] is a superglobal array element from the form submission, accessed with the null coalescing operator (??), returning null if unset.
// Specifies the amount paid by the user for the booking.
$amountPaid = $_POST['amountPaid'] ?? null;

// Variable: Payment mode storage.
// Mixed: $_POST['paymentMode'] is a superglobal array element from the form submission, accessed with the null coalescing operator (??), returning null if unset.
// Indicates the payment method (e.g., Mobile Money, Card) used for the transaction.
$paymentMode = $_POST['paymentMode'] ?? null;

// Variable: Payment timestamp storage.
// String: date('Y-m-d H:i:s') is a PHP built-in function that formats the current date and time as YYYY-MM-DD HH:MM:SS (e.g., 2025-07-24 12:39:00).
// Records the exact date and time of the payment for the transaction record.
$paymentDate = date('Y-m-d H:i:s');

// Conditional statement: Logic to validate input fields.
// Boolean checks: Tests if $bookingID, $amountPaid, or $paymentMode is null, or if $bookingID and $amountPaid are not numeric using is_numeric().
// Stops the script with an error if any field is missing or invalid, ensuring valid payment data.
if (!$bookingID || !is_numeric($bookingID) || !$amountPaid || !is_numeric($amountPaid) || !$paymentMode) {
    // Output statement: JSON error response output.
    // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Valid Booking ID, amount, and payment mode are required') to a JSON string.
    // Sends an error to the client if required fields are missing or invalid, prompting correction.
    echo json_encode(["status" => "error", "message" => "Valid Booking ID, amount, and payment mode are required"]);
    // Function call: Script termination function.
    // String: exit() is a PHP built-in function that stops script execution.
    // Halts the script to prevent processing with invalid data.
    exit();
}

// Variable: SQL SELECT query string for validation.
// String: Defines a query joining 'bookingdetails' and 'scheduleinformation' tables on ScheduleID to select Cost and SeatNumber where BookingID matches a placeholder (?).
// Retrieves the cost and seat numbers to validate the payment amount against the expected total.
$sql_validate = "SELECT s.Cost, b.SeatNumber 
                FROM bookingdetails b
                JOIN scheduleinformation s ON b.ScheduleID = s.ScheduleID
                WHERE b.BookingID = ?";

// Object: Prepared statement for validation query.
// Object: $conn->prepare($sql_validate) is a MySQLi method that creates a prepared statement object from the SQL query, where $conn is the MySQLi connection from databaseconnection.php.
// Prepares the query to validate the payment amount securely, using a placeholder to prevent SQL injection.
$stmt_validate = $conn->prepare($sql_validate);

// Conditional statement: Logic to check validation statement preparation.
// Boolean check: Tests if $stmt_validate is false, indicating preparation failure due to SQL errors or connection issues.
// Stops the script with an error if the query cannot be prepared, ensuring reliable validation.
if ($stmt_validate === false) {
    // Output statement: JSON error response output.
    // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Prepare failed: ' concatenated with $conn->error, a MySQLi property with the error message) to a JSON string.
    // Sends an error to the client if query preparation fails, alerting them to a server issue.
    echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
    // Function call: Script termination function.
    // String: exit() is a PHP built-in function that stops script execution.
    // Halts the script to prevent execution with an invalid query.
    exit();
}

// Method call: Parameter binding function for validation.
// String: bind_param("i", $bookingID) is a MySQLi method that binds $bookingID as an integer (i) to the validation query’s placeholder (?).
// Attaches the booking ID to the query safely, preventing SQL injection for secure validation.
$stmt_validate->bind_param("i", $bookingID);

// Method call: Query execution function for validation.
// String: execute() is a MySQLi method that runs the prepared statement on the database.
// Executes the query to fetch the cost and seat numbers for validation.
$stmt_validate->execute();

// Variable: Validation query result storage.
// Object: $stmt_validate->get_result() is a MySQLi method that retrieves the result set from the executed prepared statement.
// Stores the cost and seat data for validating the payment amount.
$result_validate = $stmt_validate->get_result();

// Conditional statement: Logic to check if a booking record was found.
// Array: $result_validate->fetch_assoc() is a MySQLi method that retrieves a single row from the result set as an associative array, assigned to $row if a record exists.
// Proceeds with validation if a booking is found, or sends an error if not.
if ($row = $result_validate->fetch_assoc()) {
    // Variable: Seat count calculation.
    // Integer: explode(',', $row['SeatNumber']) is a PHP built-in function that splits the SeatNumber string by commas into an array; array_filter() removes empty elements; count() returns the number of non-empty elements; empty($row['SeatNumber']) checks if the string is empty, defaulting to 0.
    // Counts the number of seats booked (e.g., “1,2,3” yields 3) to calculate the expected cost.
    $seatCount = empty($row['SeatNumber']) ? 0 : count(array_filter(explode(',', $row['SeatNumber'])));
    // Variable: Expected cost calculation.
    // Float: Multiplies $row['Cost'] (the per-seat cost from the query) by $seatCount.
    // Calculates the expected total payment amount for the booking.
    $expectedAmount = $row['Cost'] * $seatCount;
    // Conditional statement: Logic to validate payment amount.
    // Float comparison: round($amountPaid, 2) and round($expectedAmount, 2) use the PHP built-in round() function to compare amounts to 2 decimal places for precision.
    // Stops the script with an error if the paid amount doesn’t match the expected amount, ensuring accurate payments.
    if (round($amountPaid, 2) != round($expectedAmount, 2)) {
        // Output statement: JSON error response output.
        // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Amount paid...' with $amountPaid, $expectedAmount, and $seatCount) to a JSON string.
        // Sends an error to the client if the payment amount is incorrect, providing details for correction.
        echo json_encode(["status" => "error", "message" => "Amount paid ($amountPaid) does not match expected amount ($expectedAmount) for $seatCount seat(s)"]);
        // Function call: Script termination function.
        // String: exit() is a PHP built-in function that stops script execution.
        // Halts the script to prevent processing with an incorrect payment amount.
        exit();
    }
} else {
    // Output statement: JSON error response output.
    // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Booking not found for ID: ' concatenated with $bookingID) to a JSON string.
    // Sends an error to the client if no booking is found, including the booking ID for clarity.
    echo json_encode(["status" => "error", "message" => "Booking not found for ID: $bookingID"]);
    // Function call: Script termination function.
    // String: exit() is a PHP built-in function that stops script execution.
    // Halts the script to prevent processing with an invalid booking ID.
    exit();
}

// Method call: Statement closure function for validation.
// String: close() is a MySQLi method that frees resources associated with the validation prepared statement ($stmt_validate).
// Releases database resources after validation to maintain system efficiency.
$stmt_validate->close();

// Conditional statement: Logic to validate Mobile Money payment details.
// String comparison: Checks if $paymentMode equals 'Mobile Money'.
// Processes additional validation specific to Mobile Money payments.
if ($paymentMode === 'Mobile Money') {
    // Variable: Phone number storage.
    // Mixed: $_POST['phoneNumber'] is a superglobal array element from the form submission, accessed with the null coalescing operator (??), returning null if unset.
    // Captures the phone number used for the Mobile Money payment.
    $phoneNumber = $_POST['phoneNumber'] ?? null;

    // Conditional statement: Logic to check phone number presence.
    // Boolean check: Tests if $phoneNumber is null or empty.
    // Stops the script with an error if the phone number is missing, ensuring required data for Mobile Money.
    if (!$phoneNumber) {
        // Output statement: JSON error response output.
        // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Phone number is required') to a JSON string.
        // Sends an error to the client if the phone number is missing, prompting correction.
        echo json_encode(["status" => "error", "message" => "Phone number is required"]);
        // Function call: Script termination function.
        // String: exit() is a PHP built-in function that stops script execution.
        // Halts the script to prevent processing without a phone number.
        exit();
    }

    // Conditional statement: Logic to validate phone number length.
    // Integer check: strlen($phoneNumber) is a PHP built-in function that returns the length of the phone number, compared to 10.
    // Stops the script with an error if the phone number is not 10 digits, ensuring correct format.
    if (strlen($phoneNumber) != 10) {
        // Output statement: JSON error response output.
        // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Phone number must be 10 digits') to a JSON string.
        // Sends an error to the client if the phone number length is invalid, prompting correction.
        echo json_encode(["status" => "error", "message" => "Phone number must be 10 digits"]);
        // Function call: Script termination function.
        // String: exit() is a PHP built-in function that stops script execution.
        // Halts the script to prevent processing with an invalid phone number.
        exit();
    }

    // Conditional statement: Logic to validate phone number prefix.
    // String check: substr($phoneNumber, 0, 2) is a PHP built-in function that extracts the first two characters, compared to "07".
    // Stops the script with an error if the phone number doesn’t start with '07', ensuring a valid mobile format.
    if (substr($phoneNumber, 0, 2) != "07") {
        // Output statement: JSON error response output.
        // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Phone number must start with 07') to a JSON string.
        // Sends an error to the client if the phone number prefix is invalid, prompting correction.
        echo json_encode(["status" => "error", "message" => "Phone number must start with 07"]);
        // Function call: Script termination function.
        // String: exit() is a PHP built-in function that stops script execution.
        // Halts the script to prevent processing with an invalid phone number.
        exit();
    }

    // Variable: Phone number format validation flag.
    // Boolean: Initialized to true, updated in the loop to verify if $phoneNumber contains only digits.
    // Tracks whether the phone number is numeric for validation.
    $isAllDigits = true;

    // Loop: Iteration over phone number characters.
    // Integer: for loop with $i from 0 to strlen($phoneNumber) - 1, accessing each character of $phoneNumber.
    // Checks each character to ensure the phone number contains only digits.
    for ($i = 0; $i < strlen($phoneNumber); $i++) {
        // Conditional statement: Logic to check for non-numeric characters.
        // Boolean check: is_numeric($phoneNumber[$i]) is a PHP built-in function that tests if a character is numeric, negated to detect non-digits.
        // Sets $isAllDigits to false and exits the loop if a non-digit is found.
        if (!is_numeric($phoneNumber[$i])) {
            $isAllDigits = false;
            break;
        }
    }

    // Conditional statement: Logic to validate phone number digits.
    // Boolean check: Tests if $isAllDigits is false, indicating non-numeric characters.
    // Stops the script with an error if the phone number contains non-digits, ensuring a valid format.
    if (!$isAllDigits) {
        // Output statement: JSON error response output.
        // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Phone number must contain only digits') to a JSON string.
        // Sends an error to the client if the phone number is invalid, prompting correction.
        echo json_encode(["status" => "error", "message" => "Phone number must contain only digits"]);
        // Function call: Script termination function.
        // String: exit() is a PHP built-in function that stops script execution.
        // Halts the script to prevent processing with an invalid phone number.
        exit();
    }

    // Variable: Transaction ID storage.
    // String: uniqid("MPESA_") is a PHP built-in function that generates a unique identifier prefixed with "MPESA_".
    // Creates a unique ID for the Mobile Money transaction to track it in the system.
    $transactionID = uniqid("MPESA_");

    // Variable: Receipt number storage.
    // String: uniqid("REC_") is a PHP built-in function that generates a unique identifier prefixed with "REC_".
    // Creates a unique receipt number for the payment record.
    $receiptNumber = uniqid("REC_");

    // Variable: Success message storage.
    // String: Concatenates "Payment of KES $amountPaid received from $phoneNumber".
    // Describes the successful Mobile Money payment for the client response.
    $message = "Payment of KES $amountPaid received from $phoneNumber";

// Conditional statement: Logic to validate Card payment details.
// String comparison: Checks if $paymentMode equals 'Card'.
// Processes additional validation specific to Card payments.
} else if ($paymentMode === 'Card') {
    // Variable: Card number storage.
    // Mixed: $_POST['cardNumber'] is a superglobal array element from the form submission, accessed with the null coalescing operator (??), returning null if unset.
    // Captures the card number used for the payment.
    $cardNumber = $_POST['cardNumber'] ?? null;

    // Variable: Expiry date storage.
    // Mixed: $_POST['expiry'] is a superglobal array element from the form submission, accessed with the null coalescing operator (??), returning null if unset.
    // Captures the card’s expiry date for validation.
    $expiry = $_POST['expiry'] ?? null;

    // Variable: CVV storage.
    // Mixed: $_POST['cvv'] is a superglobal array element from the form submission, accessed with the null coalescing operator (??), returning null if unset.
    // Captures the card’s CVV code for validation.
    $cvv = $_POST['cvv'] ?? null;

    // Conditional statement: Logic to check card details presence.
    // Boolean check: Tests if $cardNumber, $expiry, or $cvv is null or empty.
    // Stops the script with an error if any card detail is missing, ensuring complete payment data.
    if (!$cardNumber || !$expiry || !$cvv) {
        // Output statement: JSON error response output.
        // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('All card details are required') to a JSON string.
        // Sends an error to the client if card details are missing, prompting correction.
        echo json_encode(["status" => "error", "message" => "All card details are required"]);
        // Function call: Script termination function.
        // String: exit() is a PHP built-in function that stops script execution.
        // Halts the script to prevent processing without complete card details.
        exit();
    }

    // Conditional statement: Logic to validate card number length.
    // Integer check: strlen($cardNumber) is a PHP built-in function that returns the length, compared to 19 (16 digits + 3 hyphens, e.g., 4111-1111-1111-1111).
    // Stops the script with an error if the card number length is incorrect, ensuring correct format.
    if (strlen($cardNumber) != 19) {
        // Output statement: JSON error response output.
        // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Card number must be 16 digits with hyphens...') to a JSON string.
        // Sends an error to the client if the card number format is invalid, prompting correction.
        echo json_encode(["status" => "error", "message" => "Card number must be 16 digits with hyphens (e.g., 4111-1111-1111-1111)"]);
        // Function call: Script termination function.
        // String: exit() is a PHP built-in function that stops script execution.
        // Halts the script to prevent processing with an invalid card number.
        exit();
    }

    // Conditional statement: Logic to validate hyphen positions in card number.
    // String checks: Tests if characters at positions 4, 9, and 14 ($cardNumber[4], [9], [14]) are hyphens ('-').
    // Stops the script with an error if hyphens are incorrectly placed, ensuring proper card format.
    if ($cardNumber[4] != '-' || $cardNumber[9] != '-' || $cardNumber[14] != '-') {
        // Output statement: JSON error response output.
        // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Card number must have hyphens in the correct positions') to a JSON string.
        // Sends an error to the client if hyphen positions are invalid, prompting correction.
        echo json_encode(["status" => "error", "message" => "Card number must have hyphens in the correct positions"]);
        // Function call: Script termination function.
        // String: exit() is a PHP built-in function that stops script execution.
        // Halts the script to prevent processing with an invalid card number.
        exit();
    }

    // Variable: Card digits storage.
    // String: str_replace('-', '', $cardNumber) is a PHP built-in function that removes hyphens from the card number.
    // Extracts the 16-digit card number for further validation.
    $cardDigits = str_replace('-', '', $cardNumber);

    // Conditional statement: Logic to validate card digits.
    // Boolean checks: strlen($cardDigits) checks for 16 characters, and is_numeric($cardDigits) verifies all characters are digits.
    // Stops the script with an error if the card number isn’t purely numeric or the wrong length, ensuring validity.
    if (strlen($cardDigits) != 16 || !is_numeric($cardDigits)) {
        // Output statement: JSON error response output.
        // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Card number must contain only digits') to a JSON string.
        // Sends an error to the client if the card number is invalid, prompting correction.
        echo json_encode(["status" => "error", "message" => "Card number must contain only digits"]);
        // Function call: Script termination function.
        // String: exit() is a PHP built-in function that stops script execution.
        // Halts the script to prevent processing with an invalid card number.
        exit();
    }

    // Conditional statement: Logic to validate expiry date length.
    // Integer check: strlen($expiry) is a PHP built-in function that returns the length, compared to 5 (e.g., MM/YY like 12/25).
    // Stops the script with an error if the expiry date format is incorrect, ensuring proper format.
    if (strlen($expiry) != 5) {
        // Output statement: JSON error response output.
        // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Expiry must be in MM/YY format...') to a JSON string.
        // Sends an error to the client if the expiry date format is invalid, prompting correction.
        echo json_encode(["status" => "error", "message" => "Expiry must be in MM/YY format (e.g., 12/25)"]);
        // Function call: Script termination function.
        // String: exit() is a PHP built-in function that stops script execution.
        // Halts the script to prevent processing with an invalid expiry date.
        exit();
    }

    // Conditional statement: Logic to validate slash in expiry date.
    // String check: Tests if $expiry[2] is a slash ('/').
    // Stops the script with an error if the slash is missing, ensuring proper MM/YY format.
    if ($expiry[2] != '/') {
        // Output statement: JSON error response output.
        // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Expiry must have a slash...') to a JSON string.
        // Sends an error to the client if the expiry date format is invalid, prompting correction.
        echo json_encode(["status" => "error", "message" => "Expiry must have a slash between month and year"]);
        // Function call: Script termination function.
        // String: exit() is a PHP built-in function that stops script execution.
        // Halts the script to prevent processing with an invalid expiry date.
        exit();
    }

    // Variable: Expiry month storage.
    // String: substr($expiry, 0, 2) is a PHP built-in function that extracts the first two characters (MM) from the expiry date.
    // Captures the month for validation checks.
    $month = substr($expiry, 0, 2);

    // Variable: Expiry year storage.
    // String: substr($expiry, 3, 2) is a PHP built-in function that extracts the last two characters (YY) from the expiry date.
    // Captures the year for validation checks.
    $year = substr($expiry, 3, 2);

    // Conditional statement: Logic to validate month and year digits.
    // Boolean checks: is_numeric($month) and is_numeric($year) are PHP built-in functions that verify if the month and year are numeric.
    // Stops the script with an error if the month or year contains non-digits, ensuring valid expiry data.
    if (!is_numeric($month) || !is_numeric($year)) {
        // Output statement: JSON error response output.
        // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Expiry month and year must be digits') to a JSON string.
        // Sends an error to the client if the expiry date is invalid, prompting correction.
        echo json_encode(["status" => "error", "message" => "Expiry month and year must be digits"]);
        // Function call: Script termination function.
        // String: exit() is a PHP built-in function that stops script execution.
        // Halts the script to prevent processing with an invalid expiry date.
        exit();
    }

    // Conditional statement: Logic to validate month range.
    // Integer checks: Converts $month to an integer and checks if it’s between 1 and 12.
    // Stops the script with an error if the month is invalid, ensuring a valid expiry date.
    if ($month < 1 || $month > 12) {
        // Output statement: JSON error response output.
        // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Expiry month must be between 01 and 12') to a JSON string.
        // Sends an error to the client if the month is out of range, prompting correction.
        echo json_encode(["status" => "error", "message" => "Expiry month must be between 01 and 12"]);
        // Function call: Script termination function.
        // String: exit() is a PHP built-in function that stops script execution.
        // Halts the script to prevent processing with an invalid expiry month.
        exit();
    }

    // Conditional statement: Logic to validate CVV format.
    // Boolean checks: strlen($cvv) checks for exactly 3 characters, and is_numeric($cvv) verifies all characters are digits.
    // Stops the script with an error if the CVV is invalid, ensuring secure card payment data.
    if (strlen($cvv) != 3 || !is_numeric($cvv)) {
        // Output statement: JSON error response output.
        // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('CVV must be exactly 3 digits') to a JSON string.
        // Sends an error to the client if the CVV is invalid, prompting correction.
        echo json_encode(["status" => "error", "message" => "CVV must be exactly 3 digits"]);
        // Function call: Script termination function.
        // String: exit() is a PHP built-in function that stops script execution.
        // Halts the script to prevent processing with an invalid CVV.
        exit();
    }

    // Variable: Transaction ID storage.
    // String: uniqid("CARD_") is a PHP built-in function that generates a unique identifier prefixed with "CARD_".
    // Creates a unique ID for the Card transaction to track it in the system.
    $transactionID = uniqid("CARD_");

    // Variable: Receipt number storage.
    // String: uniqid("REC_") is a PHP built-in function that generates a unique identifier prefixed with "REC_".
    // Creates a unique receipt number for the payment record.
    $receiptNumber = uniqid("REC_");

    // Variable: Success message storage.
    // String: Concatenates "Card payment of KES $amountPaid processed".
    // Describes the successful Card payment for the client response.
    $message = "Card payment of KES $amountPaid processed";
} else {
    // Output statement: JSON error response output.
    // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Invalid payment mode') to a JSON string.
    // Sends an error to the client if an unsupported payment mode is provided, prompting correction.
    echo json_encode(["status" => "error", "message" => "Invalid payment mode"]);
    // Function call: Script termination function.
    // String: exit() is a PHP built-in function that stops script execution.
    // Halts the script to prevent processing with an invalid payment mode.
    exit();
}

// Variable: SQL INSERT query string.
// String: Defines a query to insert BookingID, AmountPaid, PaymentMode, PaymentDate, ReceiptNumber, TransactionID, and 'Completed' status into the 'paymentdetails' table, using placeholders (?).
// Records the payment details in the database for the booking.
$sql = "INSERT INTO paymentdetails (BookingID, AmountPaid, PaymentMode, PaymentDate, ReceiptNumber, TransactionID, Status) 
        VALUES (?, ?, ?, ?, ?, ?, 'Completed')";

// Object: Prepared statement for database insertion.
// Object: $conn->prepare($sql) is a MySQLi method that creates a prepared statement object from the SQL query.
// Prepares the query to insert payment details securely, using placeholders to prevent SQL injection.
$stmt = $conn->prepare($sql);

// Conditional statement: Logic to check statement preparation.
// Boolean check: Tests if $stmt is false, indicating preparation failure due to SQL errors or connection issues.
// Stops the script with an error if the query cannot be prepared, ensuring reliable insertion.
if ($stmt === false) {
    // Output statement: JSON error response output.
    // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Prepare failed: ' concatenated with $conn->error) to a JSON string.
    // Sends an error to the client if query preparation fails, alerting them to a server issue.
    echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
    // Function call: Script termination function.
    // String: exit() is a PHP built-in function that stops script execution.
    // Halts the script to prevent execution with an invalid query.
    exit();
}

// Method call: Parameter binding function for insertion.
// String: bind_param("idssss", ...) is a MySQLi method that binds variables to the query’s placeholders: integer (i) for $bookingID, double (d) for $amountPaid, strings (s) for $paymentMode, $paymentDate, $receiptNumber, and $transactionID.
// Attaches payment data to the query safely, preventing SQL injection for secure insertion.
$stmt->bind_param("idssss", $bookingID, $amountPaid, $paymentMode, $paymentDate, $receiptNumber, $transactionID);

// Method call: Query execution function for insertion.
// String: execute() is a MySQLi method that runs the prepared statement on the database.
// Inserts the payment details into the 'paymentdetails' table.
$stmt->execute();

// Conditional statement: Logic to check insertion success.
// Integer check: $stmt->affected_rows is a MySQLi property that returns the number of rows affected by the query, compared to 0.
// Sends a success or error response based on whether the payment was saved.
if ($stmt->affected_rows > 0) {
    // Output statement: JSON success response output.
    // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('success') and 'message' ($message) to a JSON string.
    // Sends a success message to the client, confirming the payment was processed.
    echo json_encode(["status" => "success", "message" => $message]);
} else {
    // Output statement: JSON error response output.
    // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Failed to save payment: ' concatenated with $stmt->error, a MySQLi property with the error message) to a JSON string.
    // Sends an error to the client if the payment insertion fails, providing the error details.
    echo json_encode(["status" => "error", "message" => "Failed to save payment: " . $stmt->error]);
}

// Method call: Statement closure function for insertion.
// String: close() is a MySQLi method that frees resources associated with the prepared statement ($stmt).
// Releases database resources after inserting payment data to maintain system efficiency.
$stmt->close();

// Method call: Connection closure function.
// String: close() is a MySQLi method that closes the database connection ($conn).
// Frees database resources after all operations, ensuring no connections remain open.
$conn->close();
?>