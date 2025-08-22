<?php
// Function call: Session initialization function.
// String: session_start() is a PHP built-in function that starts or resumes a session, enabling access to session variables.
// Enables the script to check the logged-in user’s email and update session data in the International Bus Booking System.
session_start();

// Conditional statement: Logic to check if the user is logged in.
// Boolean check: isset($_SESSION['Email']) is a PHP built-in function that tests if the 'Email' key exists in the $_SESSION superglobal array and is not null.
// Ensures the user is authenticated before allowing profile updates, preventing unauthorized access.
if (!isset($_SESSION['Email'])) {
    // Function call: HTTP redirect function.
// String: header() is a PHP built-in function that sets the Location header to "/frontend/Login.html".
// Redirects unauthenticated users to the login page to enforce security.
    header("Location: /frontend/Login.html");
    // Function call: Script termination function.
// String: exit() is a PHP built-in function that stops script execution.
// Halts the script after the redirect to prevent further processing.
    exit();
}

// Include statement: File inclusion directive.
// String: include is a PHP statement that loads 'databaseconnection.php', a file that defines the $conn MySQLi connection object.
// Loads the database connection settings to update user and customer details in the database.
include 'databaseconnection.php';

// Variable: User name storage.
// String: $_POST["name"] is a superglobal array element from the form submission, containing the updated name (e.g., "John Doe").
// Captures the new name to update the user’s profile in the database.
$name = $_POST["name"];

// Variable: New email storage.
// String: $_POST["email"] is a superglobal array element from the form submission, containing the updated email (e.g., "john.doe@busbooking.com").
// Captures the new email to update the user’s account and session.
$newEmail = $_POST["email"];

// Variable: Phone number storage.
// String: $_POST["phone"] is a superglobal array element from the form submission, containing the updated phone number (e.g., "+254123456789").
// Captures the new phone number for both user and customer profiles in the database.
$phoneNumber = $_POST["phone"];

// Variable: Gender storage.
// String: $_POST["gender"] is a superglobal array element from the form submission, containing the gender (e.g., "Male").
// Captures the gender for updating the customer profile in the database.
$gender = $_POST["gender"];

// Variable: Passport number storage.
// String: $_POST["passport"] is a superglobal array element from the form submission, containing the passport number (e.g., "A12345678").
// Captures the passport number for updating the customer profile in the database.
$passportNumber = $_POST["passport"];

// Variable: Nationality storage.
// String: $_POST["nationality"] is a superglobal array element from the form submission, containing the nationality (e.g., "Kenyan").
// Captures the nationality for updating the customer profile in the database.
$nationality = $_POST["nationality"];

// Variable: Current session email storage.
// String: $_SESSION["Email"] is a superglobal array element containing the current email of the logged-in user (e.g., "old.email@busbooking.com").
// Identifies the user’s existing record in the database for targeted updates.
$sessionEmail = $_SESSION["Email"];

// Variable: SQL UPDATE query string for users table.
// String: Defines a query to update Name, Email, and PhoneNumber in the 'users' table where Email matches a placeholder (?).
// Updates the user’s core account details in the database.
$sql = "UPDATE users SET Name = ?, Email = ?, PhoneNumber = ? WHERE Email = ?";

// Object: Prepared statement for updating users table.
// Object: $conn->prepare($sql) is a MySQLi method that creates a prepared statement object from the SQL query, where $conn is the MySQLi connection from databaseconnection.php.
// Prepares the query to update user data securely, using placeholders to prevent SQL injection.
$stmt = $conn->prepare($sql);

// Conditional statement: Logic to check statement preparation.
// Boolean check: Tests if $stmt is false, indicating preparation failure due to SQL errors or connection issues.
// Stops the script with an error if the query cannot be prepared, ensuring reliable execution.
if ($stmt === false) {
    // Function call: Script termination function with message.
// String: die() is a PHP built-in function that outputs "Error preparing statement: " concatenated with $conn->error, a MySQLi property with the error message, and stops execution.
// Halts the script and informs the user of a server issue with the query preparation.
    die("Error preparing statement: " . $conn->error);
}

// Method call: Parameter binding function for users update.
// String: bind_param("ssss", $name, $newEmail, $phoneNumber, $sessionEmail) is a MySQLi method that binds variables to the query’s placeholders, all as strings (s).
// Attaches user data to the query safely, preventing SQL injection for secure updating.
$stmt->bind_param("ssss", $name, $newEmail, $phoneNumber, $sessionEmail);

// Method call: Query execution function for users update.
// String: execute() is a MySQLi method that runs the prepared statement to update the users table.
// Updates the user’s name, email, and phone number in the database.
$stmt->execute();

// Method call: Statement closure function for users update.
// String: close() is a MySQLi method that frees resources associated with the prepared statement ($stmt).
// Releases database resources after updating user data to maintain system efficiency.
$stmt->close();

// Variable: SQL UPDATE query string for customer table.
// String: Defines a query to update Gender, PassportNumber, Nationality, and PhoneNumber in the 'customer' table where Email matches a placeholder (?).
// Updates customer-specific details in the database for the user’s profile.
$sql = "UPDATE customer SET Gender = ?, PassportNumber = ?, Nationality = ?, PhoneNumber = ? WHERE Email = ?";

// Object: Prepared statement for updating customer table.
// Object: $conn->prepare($sql) is a MySQLi method that creates a prepared statement object from the SQL query.
// Prepares the query to update customer data securely, using placeholders to prevent SQL injection.
$stmt = $conn->prepare($sql);

// Conditional statement: Logic to check statement preparation.
// Boolean check: Tests if $stmt is false, indicating preparation failure due to SQL errors or connection issues.
// Stops the script with an error if the query cannot be prepared, ensuring reliable execution.
if ($stmt === false) {
    // Function call: Script termination function with message.
// String: die() is a PHP built-in function that outputs "Error preparing statement: " concatenated with $conn->error and stops execution.
// Halts the script and informs the user of a server issue with the query preparation.
    die("Error preparing statement: " . $conn->error);
}

// Method call: Parameter binding function for customer update.
// String: bind_param("sssss", $gender, $passportNumber, $nationality, $phoneNumber, $sessionEmail) is a MySQLi method that binds variables to the query’s placeholders, all as strings (s).
// Attaches customer data to the query safely, preventing SQL injection for secure updating.
$stmt->bind_param("sssss", $gender, $passportNumber, $nationality, $phoneNumber, $sessionEmail);

// Method call: Query execution function for customer update.
// String: execute() is a MySQLi method that runs the prepared statement to update the customer table.
// Updates the customer’s gender, passport number, nationality, and phone number in the database.
$stmt->execute();

// Method call: Statement closure function for customer update.
// String: close() is a MySQLi method that frees resources associated with the prepared statement ($stmt).
// Releases database resources after updating customer data to maintain system efficiency.
$stmt->close();

// Method call: Connection closure function.
// String: close() is a MySQLi method that closes the database connection ($conn).
// Frees database resources after all operations, ensuring no connections remain open.
$conn->close();

// Assignment: Session email update.
// String: Assigns $newEmail to $_SESSION["Email"] in the superglobal session array.
// Updates the session to reflect the new email if it was changed, ensuring subsequent requests use the updated email.
$_SESSION["Email"] = $newEmail;

// Function call: HTTP redirect function.
// String: header() is a PHP built-in function that sets the Location header to "/frontend/dashboard/customer/ProfileManagement.html".
// Redirects the user to the profile management page after successfully updating their profile.
header("Location: /frontend/dashboard/customer/ProfileManagement.html");

// Function call: Script termination function.
// String: exit() is a PHP built-in function that stops script execution.
// Halts the script after the redirect to prevent further processing.
exit();
?>