<?php
// Include statement: File inclusion directive.
// String: include() is a PHP statement that loads 'databaseconnection.php', a file that defines the $conn MySQLi connection object.
// Loads the database connection settings to query user data for login in the International Bus Booking System.
include('databaseconnection.php');

// Include statement: File inclusion directive.
// String: include() is a PHP statement that loads 'logActivity.php', a file that defines the logActivity() function for recording user actions.
// Enables logging of login activities for auditing and tracking.
include('logActivity.php');

// Function call: HTTP header setting function.
// String: header() is a PHP built-in function that sets an HTTP response header, here setting 'Content-Type: application/json'.
// Sets the response format to JSON, ensuring the client (e.g., JavaScript) can parse login results correctly.
header('Content-Type: application/json');

// Conditional statement: Logic to verify the HTTP request method.
// String check: $_SERVER["REQUEST_METHOD"] is a superglobal array element that returns the request method (e.g., "POST"), compared to "POST".
// Ensures the script processes only POST form submissions, as login data is sent via POST for security.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Variable: Email input storage.
    // String: $_POST["email"] is a superglobal array element from the form submission, accessed with the null coalescing operator (??), a PHP operator that returns an empty string if unset.
    // Captures the user’s email input from the login form for authentication.
    $email = $_POST["email"] ?? '';

    // Variable: Password input storage.
    // String: $_POST["password"] is a superglobal array element from the form submission, accessed with the null coalescing operator (??), returning an empty string if unset.
    // Captures the user’s password input from the login form for verification.
    $password = $_POST["password"] ?? '';

    // Variable: CAPTCHA answer storage.
    // String: $_POST["captcha"] is a superglobal array element from the form submission, accessed with the null coalescing operator (??), returning an empty string if unset.
    // Captures the user’s CAPTCHA input to verify they are not a bot.
    $captchaAnswer = $_POST["captcha"] ?? '';

    // Variable: CAPTCHA solution storage.
    // String: $_POST["captcha_solution"] is a superglobal array element from the form submission, accessed with the null coalescing operator (??), returning an empty string if unset.
    // Captures the expected CAPTCHA answer to compare with the user’s input.
    $captchaSolution = $_POST["captcha_solution"] ?? '';

    // Conditional statement: Logic to validate form inputs.
    // Function call: empty() is a PHP built-in function that checks if $email, $password, or $captchaAnswer is empty (e.g., "", null, or unset).
    // Ensures all required fields are filled, preventing incomplete login attempts.
    if (empty($email) || empty($password) || empty($captchaAnswer)) {
        // Output statement: JSON error response output.
        // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('All fields are required.') to a JSON string.
        // Sends an error to the client if any form field is missing, prompting them to complete the form.
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        // Function call: Script termination function.
        // String: exit() is a PHP built-in function that stops script execution.
        // Halts the script to prevent further processing with incomplete data.
        exit();
    }

    // Conditional statement: Logic to verify CAPTCHA.
    // String comparison: Checks if $captchaAnswer does not equal $captchaSolution using !==.
    // Ensures the user entered the correct CAPTCHA to block automated login attempts.
    if ($captchaAnswer !== $captchaSolution) {
        // Output statement: JSON error response output.
        // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('CAPTCHA verification failed.') to a JSON string.
        // Sends an error to the client if the CAPTCHA is incorrect, enhancing security.
        echo json_encode(["status" => "error", "message" => "CAPTCHA verification failed."]);
        // Function call: Script termination function.
        // String: exit() is a PHP built-in function that stops script execution.
        // Halts the script to prevent further processing with an invalid CAPTCHA.
        exit();
    }

    // Variable: SQL SELECT query string.
    // String: Defines a query to select UserID, Name, Password, and Role from the 'users' table where Email matches a placeholder (?).
    // Retrieves user data for authentication and session setup.
    $sql = "SELECT UserID, Name, Password, Role FROM users WHERE Email = ?";

    // Object: Prepared statement for database query.
    // Object: $conn->prepare($sql) is a MySQLi method that creates a prepared statement object from the SQL query, where $conn is the MySQLi connection from databaseconnection.php.
    // Prepares the query to fetch user data securely, using a placeholder to prevent SQL injection.
    $stmt = $conn->prepare($sql);

    // Conditional statement: Logic to check statement preparation.
    // Boolean check: Tests if $stmt is false, indicating preparation failure due to SQL errors or connection issues.
    // Stops the script with an error if the query cannot be prepared, ensuring reliable data retrieval.
    if ($stmt === false) {
        // Output statement: JSON error response output.
        // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Error preparing statement: ' concatenated with $conn->error, a MySQLi property with the error message) to a JSON string.
        // Sends an error to the client if query preparation fails, alerting them to a server issue.
        echo json_encode(["status" => "error", "message" => "Error preparing statement: " . $conn->error]);
        // Function call: Script termination function.
        // String: exit() is a PHP built-in function that stops script execution.
        // Halts the script to prevent execution with an invalid query.
        exit();
    }

    // Method call: Parameter binding function.
    // String: bind_param("s", $email) is a MySQLi method that binds $email as a string (s) to the query’s placeholder (?).
    // Attaches the user’s email to the query safely, preventing SQL injection for secure user data retrieval.
    $stmt->bind_param("s", $email);

    // Method call: Query execution function.
    // String: execute() is a MySQLi method that runs the prepared statement on the database.
    // Executes the query to fetch user data based on the provided email.
    $stmt->execute();

    // Variable: Query result storage.
    // Object: $stmt->get_result() is a MySQLi method that retrieves the result set from the executed prepared statement.
    // Stores the user data query results for authentication.
    $result = $stmt->get_result();

    // Conditional statement: Logic to check if a single user was found.
    // Integer check: $result->num_rows is a MySQLi property that returns the number of rows in the result set, compared to 1.
    // Proceeds with authentication if exactly one user matches the email, ensuring unique identification.
    if ($result->num_rows == 1) {
        // Variable: User data storage.
        // Array: $result->fetch_assoc() is a MySQLi method that retrieves a single row from the result set as an associative array.
        // Extracts user details (UserID, Name, Password, Role) for verification and session storage.
        $user = $result->fetch_assoc();

        // Conditional statement: Logic to verify the password.
        // Function call: password_verify() is a PHP built-in function that compares $password (user input) with $user["Password"] (hashed password from the database).
        // Ensures the provided password matches the stored hash, securing the login process.
        if (password_verify($password, $user["Password"])) {
            // Function call: Session initiation function.
            // String: session_start() is a PHP built-in function that starts or resumes a session to manage user data across pages.
            // Ensures a session is active to store user data, redundant here as it’s typically called earlier, but included for robustness.
            session_start();

            // Session variables: User data storage.
            // Array: $_SESSION is a superglobal array with keys 'UserID' (integer, from $user["UserID"]), 'Role' (string, from $user["Role"]), and 'Email' (string, from $email).
            // Stores user information in the session to maintain login state across pages.
            $_SESSION["UserID"] = $user["UserID"];
            $_SESSION["Role"] = $user["Role"];
            $_SESSION["Email"] = $email;

            // Function call: Activity logging function.
            // Parameters: $description ("User logged in"), $whoDidIt ($user["Name"]), $role ($user["Role"]) are strings passed to logActivity() from logActivity.php.
            // Records the successful login in the activity table for auditing user actions.
            $description = "User logged in";
            $whoDidIt = $user["Name"];
            $role = $user["Role"];
            logActivity($description, $whoDidIt, $role);

            // Variable: Redirect URL storage.
            // String: match is a PHP expression (since PHP 8.0) that maps $user["Role"] to a specific dashboard URL based on roles like "Customer" or "Admin", defaulting to "../frontend/index.html".
            // Determines the appropriate dashboard page to redirect the user to after login.
            $redirectUrl = match ($user["Role"]) {
                "Customer" => "../frontend/dashboard/customer/CustomerDashboard.html",
                "Driver", "Co-Driver" => "../frontend/dashboard/driver/DriverDashboard.html",
                "Technician" => "../frontend/dashboard/technician/TechnicianDashboard.html",
                "Cashier" => "../frontend/dashboard/cashier/CashierDashboard.html",
                "Staff" => "../frontend/dashboard/staff/StaffDashboard.html",
                "Admin" => "../frontend/dashboard/admin/Admin.html",
                default => "../frontend/index.html"
            };

            // Output statement: JSON success response output.
            // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('success') and 'redirect' ($redirectUrl) to a JSON string.
            // Sends the success status and redirect URL to the client (e.g., JavaScript) for navigating to the user’s dashboard.
            echo json_encode(["status" => "success", "redirect" => $redirectUrl]);
        } else {
            // Output statement: JSON error response output.
            // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Invalid email or password.') to a JSON string.
            // Sends an error to the client if the password is incorrect, prompting them to retry.
            echo json_encode(["status" => "error", "message" => "Invalid email or password."]);
        }
    } else {
        // Output statement: JSON error response output.
        // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Invalid email or password.') to a JSON string.
        // Sends an error to the client if no user or multiple users are found, ensuring secure authentication.
        echo json_encode(["status" => "error", "message" => "Invalid email or password."]);
    }

    // Method call: Statement closure function.
    // String: close() is a MySQLi method that frees resources associated with the prepared statement ($stmt).
    // Releases database resources after fetching user data to maintain system efficiency.
    $stmt->close();

    // Method call: Connection closure function.
    // String: close() is a MySQLi method that closes the database connection ($conn).
    // Frees database resources after all operations, ensuring no connections remain open.
    $conn->close();
} else {
    // Output statement: JSON error response output.
    // String: echo is a PHP statement that outputs the result of json_encode(), a PHP built-in function that converts an associative array with 'status' ('error') and 'message' ('Invalid request method.') to a JSON string.
    // Sends an error to the client if a non-POST request is used, enforcing secure form submission.
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>