<?php
// Function call: Session initialization function.
// String: session_start() is a PHP built-in function that starts or resumes a session, enabling access to session variables.
// Enables session management in the International Bus Booking System, though not directly used here, ensuring compatibility with other scripts.
session_start();

// Function call: HTTP header setting function.
// String: header() is a PHP built-in function that sets an HTTP response header, here setting 'Content-Type: text/plain'.
// Sets the response format to plain text, ensuring compatibility with client-side JavaScript for processing responses.
header('Content-Type: text/plain');

// Include statement: File inclusion directive.
// String: include is a PHP statement that loads '../php/databaseconnection.php', a file that defines the $conn MySQLi connection object.
// Loads the database connection settings to verify user emails and store reset tokens in the database.
include('../php/databaseconnection.php');

// Include statement: Composer autoloader inclusion directive.
// String: require is a PHP statement that loads '../vendor/autoload.php', which includes the PHPMailer library for email functionality.
// Enables the use of PHPMailer to send password reset emails to users.
require '../vendor/autoload.php';

// Namespace import: PHPMailer class import.
// String: use PHPMailer\PHPMailer\PHPMailer imports the PHPMailer class from the PHPMailer namespace.
// Allows the script to create a PHPMailer instance for sending emails without fully qualified class names.
use PHPMailer\PHPMailer\PHPMailer;

// Conditional statement: Logic to verify the HTTP request method.
// String check: $_SERVER["REQUEST_METHOD"] is a superglobal array element that returns the request method (e.g., "POST"), compared to "POST" using !=.
// Ensures the script processes only POST form submissions, as the email is sent via POST for security.
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    // Output statement: Error message output.
// String: echo outputs "Please submit the form.".
// Informs the client that a non-POST request was used, enforcing secure form submission.
    echo "Please submit the form.";
    // Function call: Script termination function.
// String: exit() is a PHP built-in function that stops script execution.
// Halts the script to prevent processing with an invalid request method.
    exit();
}

// Variable: User email storage.
// String: $_POST["email"] is a superglobal array element from the form submission, accessed with the null coalescing operator (??), returning an empty string if unset.
// Captures the user’s email to initiate the password reset process.
$email = $_POST["email"] ?? '';

// Conditional statement: Logic to validate email input.
// Boolean check: empty() is a PHP built-in function that tests if $email is empty (e.g., "", null, or unset).
// Stops the script with an error if the email field is missing, ensuring a valid email is provided.
if (empty($email)) {
    // Output statement: Error message output.
// String: echo outputs "Email is required.".
// Informs the client that the email field must be filled in the form.
    echo "Email is required.";
    // Function call: Script termination function.
// String: exit() is a PHP built-in function that stops script execution.
// Halts the script to prevent processing without an email.
    exit();
}

// Try-catch block: Exception handling structure.
// Structure: try contains database and email operations; catch captures any Exception object thrown, storing it in $e.
// Handles errors (e.g., database issues, email failures) during the password reset process, ensuring robust execution.
try {
    // Variable: SQL SELECT query string.
// String: Defines a query to select Email from the 'users' table where Email matches a placeholder (?).
// Verifies if the provided email exists in the database to proceed with the reset.
    $sql = "SELECT Email FROM users WHERE Email = ?";

    // Object: Prepared statement for email verification.
// Object: $conn->prepare($sql) is a MySQLi method that creates a prepared statement object from the SQL query, where $conn is the MySQLi connection from databaseconnection.php.
// Prepares the query to check the email securely, using a placeholder to prevent SQL injection.
    $stmt = $conn->prepare($sql);

    // Conditional statement: Logic to check statement preparation.
// Boolean check: Tests if $stmt is false, indicating preparation failure due to SQL errors or connection issues.
// Stops the script with an error if the query cannot be prepared, ensuring reliable execution.
    if (!$stmt) {
        // Output statement: Error message output.
// String: echo outputs "Database error.".
// Informs the client of a server issue with the database query preparation.
        echo "Database error.";
        // Function call: Script termination function.
// String: exit() is a PHP built-in function that stops script execution.
// Halts the script to prevent further processing with an invalid query.
        exit();
    }

    // Method call: Parameter binding function.
// String: bind_param("s", $email) is a MySQLi method that binds $email as a string (s) to the query’s placeholder (?).
// Attaches the email to the query safely, preventing SQL injection for secure verification.
    $stmt->bind_param("s", $email);

    // Method call: Query execution function.
// String: execute() is a MySQLi method that runs the prepared statement to query the database.
// Checks if the email exists in the users table.
    $stmt->execute();

    // Variable: Query result storage.
// Object: $stmt->get_result() is a MySQLi method that retrieves the result set from the executed prepared statement.
// Stores the result to check if the email was found.
    $result = $stmt->get_result();

    // Method call: Statement closure function.
// String: close() is a MySQLi method that frees resources associated with the prepared statement ($stmt).
// Releases database resources after verifying the email to maintain system efficiency.
    $stmt->close();

    // Conditional statement: Logic to check if the email was found.
// Integer check: $result->num_rows is a MySQLi property that returns the number of rows in the result set, compared to 0.
// Stops the script with an error if no matching email is found, preventing invalid reset attempts.
    if ($result->num_rows == 0) {
        // Output statement: Error message output.
// String: echo outputs "Email not found.".
// Informs the client that the provided email is not registered in the system.
        echo "Email not found.";
        // Function call: Script termination function.
// String: exit() is a PHP built-in function that stops script execution.
// Halts the script to prevent further processing for an unregistered email.
        exit();
    }

    // Variable: Password reset token storage.
// String: bin2hex(random_bytes(32)) is a PHP built-in function that generates 32 random bytes and converts them to a 64-character hexadecimal string.
// Creates a secure, random token for the password reset link to ensure uniqueness and security.
    $token = bin2hex(random_bytes(32));

    // Variable: SQL INSERT/UPDATE query string.
// String: Defines a query to insert email, token, and expiration time (1 hour from now using NOW() + INTERVAL 1 HOUR) into the 'password_resets' table, or update token and expires_at on duplicate email using ON DUPLICATE KEY UPDATE.
// Stores or updates the reset token with a 1-hour expiration for secure password reset.
    $sql = "INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, NOW() + INTERVAL 1 HOUR) 
            ON DUPLICATE KEY UPDATE token = ?, expires_at = NOW() + INTERVAL 1 HOUR";

    // Object: Prepared statement for token storage.
// Object: $conn->prepare($sql) is a MySQLi method that creates a prepared statement object from the SQL query.
// Prepares the query to store or update the reset token securely, using placeholders to prevent SQL injection.
    $stmt = $conn->prepare($sql);

    // Conditional statement: Logic to check statement preparation.
// Boolean check: Tests if $stmt is false, indicating preparation failure due to SQL errors or connection issues.
// Stops the script with an error if the query cannot be prepared, ensuring reliable execution.
    if (!$stmt) {
        // Output statement: Error message output.
// String: echo outputs "Database error.".
// Informs the client of a server issue with the database query preparation.
        echo "Database error.";
        // Function call: Script termination function.
// String: exit() is a PHP built-in function that stops script execution.
// Halts the script to prevent further processing with an invalid query.
        exit();
    }

    // Method call: Parameter binding function.
// String: bind_param("sss", $email, $token, $token) is a MySQLi method that binds $email, $token, and $token (for update) as strings (s) to the query’s placeholders (?).
// Attaches the email and token to the query safely, preventing SQL injection for secure storage.
    $stmt->bind_param("sss", $email, $token, $token);

    // Method call: Query execution function.
// String: execute() is a MySQLi method that runs the prepared statement to insert or update the token in the database.
// Saves the reset token and expiration time for later verification.
    $stmt->execute();

    // Method call: Statement closure function.
// String: close() is a MySQLi method that frees resources associated with the prepared statement ($stmt).
// Releases database resources after storing the token to maintain system efficiency.
    $stmt->close();

    // Object: PHPMailer instance creation.
// Object: new PHPMailer() creates a PHPMailer object for configuring and sending emails.
// Prepares to send a password reset email to the user with the reset link.
    $mail = new PHPMailer();

    // Method call: SMTP configuration function.
// String: isSMTP() is a PHPMailer method that configures the mailer to use the SMTP protocol for sending emails.
// Enables SMTP for reliable email delivery through a mail server.
    $mail->isSMTP();

    // Property: SMTP server hostname setting.
// String: Host is a PHPMailer property set to 'sandbox.smtp.mailtrap.io'.
// Specifies the mail server used for sending the password reset email (Mailtrap sandbox for testing).
    $mail->Host = 'sandbox.smtp.mailtrap.io';

    // Property: SMTP authentication setting.
// Boolean: SMTPAuth is a PHPMailer property set to true.
// Enables authentication to secure email sending with a username and password.
    $mail->SMTPAuth = true;

    // Property: SMTP username setting.
// String: Username is a PHPMailer property set to '3ee39eb591d0ba'.
// Provides the username for authenticating with the Mailtrap SMTP server.
    $mail->Username = '3ee39eb591d0ba';

    // Property: SMTP password setting.
// String: Password is a PHPMailer property set to '22c67c9ea1e5b4'.
// Provides the password for authenticating with the Mailtrap SMTP server.
    $mail->Password = '22c67c9ea1e5b4';

    // Property: SMTP encryption setting.
// String: SMTPSecure is a PHPMailer property set to 'tls'.
// Enables TLS encryption for secure email transmission over the SMTP server.
    $mail->SMTPSecure = 'tls';

    // Property: SMTP port setting.
// Integer: Port is a PHPMailer property set to 2525.
// Specifies the network port for SMTP communication with the Mailtrap server.
    $mail->Port = 2525;

    // Method call: Sender configuration function.
// String: setFrom('no-reply@busbooking.com', 'Bus Booking') is a PHPMailer method that sets the sender’s email address and name.
// Defines the “from” address and name for the password reset email, appearing as sent from the system.
    $mail->setFrom('no-reply@busbooking.com', 'Bus Booking');

    // Method call: Recipient configuration function.
// String: addAddress($email) is a PHPMailer method that sets the recipient’s email address.
// Specifies the user’s email as the destination for the password reset email.
    $mail->addAddress($email);

    // Property: Email subject setting.
// String: Subject is a PHPMailer property set to 'Password Reset Request'.
// Defines the subject line of the password reset email for clarity.
    $mail->Subject = 'Password Reset Request';

    // Variable: Password reset URL storage.
// String: Concatenates "http://localhost/frontend/forms/ResetPasswordForm.html?token=" with urlencode($token), a PHP built-in function that encodes the token for safe URL use.
// Creates a clickable link for the user to access the password reset form.
    $resetLink = "http://localhost/frontend/forms/ResetPasswordForm.html?token=" . urlencode($token);

    // Property: Email body setting.
// String: Body is a PHPMailer property set to a message containing $resetLink and a note that the link expires in 1 hour.
// Defines the content of the email, providing the reset link and expiration information.
    $mail->Body = "Click this link to reset your password: $resetLink\nThis link expires in 1 hour.";

    // Conditional statement: Logic to send the email and check success.
// Boolean check: $mail->send() is a PHPMailer method that attempts to send the email, returning true on success or false on failure.
// Outputs a success or error message based on whether the email was sent.
    if ($mail->send()) {
        // Output statement: Success message output.
// String: echo outputs "Reset link sent to your email.".
// Informs the user that the password reset email was successfully sent.
        echo "Reset link sent to your email.";
    } else {
        // Function call: Error logging function.
// String: error_log() is a PHP built-in function that logs "Failed to send email to $email: " concatenated with $mail->ErrorInfo, a PHPMailer property with the error details, to the server log (level 0).
// Records the email sending failure for debugging purposes.
        error_log("Failed to send email to $email: " . $mail->ErrorInfo, 0);

        // Output statement: Error message output.
// String: echo outputs "Failed to send email.".
// Informs the user that the email could not be sent, prompting them to try again.
        echo "Failed to send email.";
    }
} catch (Exception $e) {
    // Function call: Error logging function.
// String: error_log() is a PHP built-in function that logs "Error in forgotPassword.php: " concatenated with $e->getMessage(), a method of the Exception object that retrieves the error message, to the server log (level 0).
// Records any caught errors (e.g., database or PHPMailer issues) for debugging purposes.
    error_log("Error in forgotPassword.php: " . $e->getMessage(), 0);

    // Output statement: Error message output.
// String: echo outputs "An error occurred.".
// Informs the user of a general error during the password reset process, keeping details vague for security.
    echo "An error occurred.";
}

// Method call: Connection closure function.
// String: close() is a MySQLi method that closes the database connection ($conn).
// Frees database resources after all operations, ensuring no connections remain open.
$conn->close();
?>