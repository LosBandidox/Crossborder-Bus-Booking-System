<?php
// Conditional statement: Logic to prevent multiple inclusions of the file.
// Boolean check: defined() is a PHP built-in function that checks if a constant named 'DB_CONNECTION_INCLUDED' exists.
// Prevents the file from being included more than once to avoid redefinition errors in the International Bus Booking System.
if (defined('DB_CONNECTION_INCLUDED')) {
    // Return statement: Early exit directive.
    // String: return is a PHP statement that exits the script without returning a value.
    // Stops further execution if the file is already included, ensuring no duplicate constants or functions are defined.
    return;
}

// Constant: Identifier to mark the file as included.
// Boolean value: define() is a PHP built-in function that sets 'DB_CONNECTION_INCLUDED' to true, creating a constant.
// Marks the file as included to prevent redefinition errors when other scripts include it.
define('DB_CONNECTION_INCLUDED', true);

// Variable: File path to the database credentials configuration.
// String: realpath() is a PHP built-in function that converts __DIR__ . '/db_credentials.php' to an absolute file path, where __DIR__ is a PHP constant for the current file’s directory.
// Locates the db_credentials.php file, which contains the database host, user, encrypted password, and database name for secure connection setup.
$credentials_path = realpath(__DIR__ . '/db_credentials.php');

// Conditional statement: Logic to validate the credentials file’s existence.
// Boolean checks: Tests if $credentials_path is false (path resolution failed) or if file_exists(), a PHP built-in function that checks if a file exists at the path, returns false.
// Terminates the script with an error message if db_credentials.php is missing or invalid, ensuring a valid configuration file is available.
if ($credentials_path === false || !file_exists($credentials_path)) {
    // Function call: Script termination function.
    // String: die() is a PHP built-in function that outputs "Failed to load db_credentials.php" and stops execution.
    // Stops the script to prevent database connection attempts without valid credentials.
    die("Failed to load db_credentials.php");
}

// Variable: Array containing database configuration data.
// Array: include() is a PHP statement that loads db_credentials.php, returning an associative array with keys 'host', 'user', 'encrypted_pass', and 'dbname'.
// Stores the database connection details needed to connect to the International Bus Booking System’s database.
$config = include($credentials_path);

// Conditional statement: Logic to validate the configuration format.
// Boolean check: is_array() is a PHP built-in function that checks if $config is an associative array.
// Terminates the script with an error message if db_credentials.php doesn’t return an array, ensuring valid configuration data.
if (!is_array($config)) {
    // Function call: Script termination function.
    // String: die() is a PHP built-in function that outputs "Invalid db_credentials.php format" and stops execution.
    // Stops the script to prevent connection attempts with invalid configuration data.
    die("Invalid db_credentials.php format");
}

// Variable: Secret key for decrypting the database password.
// String: Set to 'my-secret-key', a hardcoded value.
// Provides the key needed to decrypt the encrypted password stored in db_credentials.php, ensuring secure access to the database.
$encryptionKey = 'my-secret-key';

// Function definition: Logic to decrypt an encrypted password using PBKDF2 and AES-256-CBC.
// Parameters: $ciphertext (string, base64-encoded encrypted password), $passphrase (string, secret key matching 'my-secret-key'). Returns a string (decrypted password) or terminates on failure.
// Defines a custom function to decrypt the database password securely for the International Bus Booking System.
if (!function_exists('decryptPBKDF2Manual')) {
    function decryptPBKDF2Manual($ciphertext, $passphrase) {
        // Variable: Decoded binary data from the encrypted password.
        // String: base64_decode() is a PHP built-in function that converts $ciphertext from base64 encoding to binary data.
        // Decodes the encrypted password for further processing in the decryption algorithm.
        $ciphertext = base64_decode($ciphertext);

        // Conditional statement: Logic to validate the ciphertext format.
        // Boolean checks: Tests if $ciphertext is false (decoding failed) or if substr(), a PHP function that extracts a substring from $ciphertext (positions 0 to 8), doesn’t equal 'Salted__'.
        // Terminates the script with an error if the encrypted password format is invalid, ensuring correct decryption input.
        if ($ciphertext === false || substr($ciphertext, 0, 8) !== 'Salted__') {
            // Function call: Script termination function.
            // String: die() is a PHP built-in function that outputs "Invalid encrypted password format" and stops execution.
            // Stops the script to prevent decryption attempts with invalid data.
            die("Invalid encrypted password format");
        }

        // Variable: Salt for key derivation.
        // String: substr() is a PHP built-in function that extracts 8 bytes from $ciphertext (positions 8 to 16).
        // Retrieves the salt, a random value used in PBKDF2 to generate a secure decryption key.
        $salt = substr($ciphertext, 8, 8);

        // Variable: Encrypted password data.
        // String: substr() is a PHP built-in function that extracts the remaining bytes from $ciphertext (position 16 onward).
        // Isolates the encrypted password content for decryption after the salt.
        $enc = substr($ciphertext, 16);

        // Variable: Derived key and initialization vector (IV) for decryption.
        // Binary string: hash_pbkdf2() is a PHP built-in function that generates a 48-byte key/IV pair using the SHA-256 algorithm, $passphrase, $salt, 10,000 iterations, and raw binary output (true).
        // Creates a secure key and IV for AES-256-CBC decryption, ensuring robust password security.
        $key_iv = hash_pbkdf2('sha256', $passphrase, $salt, 10000, 48, true);

        // Variable: Encryption key for AES-256-CBC.
        // Binary string: substr() is a PHP built-in function that extracts 32 bytes from $key_iv (positions 0 to 32).
        // Provides the 32-byte key needed for the AES-256-CBC decryption algorithm.
        $key = substr($key_iv, 0, 32);

        // Variable: Initialization vector for AES-256-CBC.
        // Binary string: substr() is a PHP built-in function that extracts 16 bytes from $key_iv (positions 32 to 48).
        // Provides the 16-byte IV, a random value used to initialize the AES-256-CBC decryption process.
        $iv = substr($key_iv, 32, 16);

        // Variable: Decrypted password.
        // String or false: openssl_decrypt() is a PHP built-in function that decrypts $enc using the AES-256-CBC algorithm, $key, OPENSSL_RAW_DATA flag (raw output), and $iv, with error suppression (@).
        // Attempts to decrypt the password, returning the plaintext or false if decryption fails.
        $decrypted = @openssl_decrypt($enc, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

        // Conditional statement: Logic to validate decryption success.
        // Boolean check: Tests if $decrypted is false, indicating a decryption failure.
        // Terminates the script with an error if decryption fails, ensuring a valid password is obtained.
        if ($decrypted === false) {
            // Function call: Script termination function.
            // String: die() is a PHP built-in function that outputs "Password decryption failed" and stops execution.
            // Stops the script to prevent connection attempts with an invalid password.
            die("Password decryption failed");
        }

        // Return statement: Decrypted password.
        // String: trim() is a PHP built-in function that removes whitespace from $decrypted.
        // Returns the plaintext password, cleaned of extra spaces, for database authentication.
        return trim($decrypted);
    }
}

// Constant: Database server hostname.
// String: $config['host'] is an associative array element from db_credentials.php, typically set to 'localhost'.
// Specifies the server address (e.g., localhost) hosting the International Bus Booking System’s database.
$DB_HOST = $config['host'];

// Constant: Database username.
// String: $config['user'] is an associative array element from db_credentials.php, typically set to 'busbooking'.
// Specifies the username for authenticating with the MySQL database.
$DB_USER = $config['user'];

// Constant: Database password.
// String: decryptPBKDF2Manual() is the custom function defined above, called with $config['encrypted_pass'] (base64-encoded password) and $encryptionKey ('my-secret-key').
// Provides the decrypted password for authenticating with the MySQL database.
$DB_PASS = decryptPBKDF2Manual($config['encrypted_pass'], $encryptionKey);

// Constant: Database name.
// String: $config['dbname'] is an associative array element from db_credentials.php, typically set to 'InternationalBusBookingSystem'.
// Identifies the specific database to connect to for bus booking data.
$DB_NAME = $config['dbname'];

// Object: MySQLi connection instance.
// MySQLi object: new mysqli() is a PHP built-in constructor that creates a MySQLi connection object using $DB_HOST, $DB_USER, $DB_PASS, and $DB_NAME, with error suppression (@).
// Establishes a connection to the database for executing queries in the bus booking system.
$conn = @new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Conditional statement: Logic to check for database connection errors.
// String check: $conn->connect_error is a MySQLi property that returns an error message if the connection fails, evaluated for truthiness.
// Terminates the script with an error message if the connection fails, ensuring no queries are attempted without a valid connection.
if ($conn->connect_error) {
    // Function call: Script termination function.
    // String: die() is a PHP built-in function that outputs "Connection failed: " concatenated with $conn->connect_error and stops execution.
    // Stops the script to prevent operations with an invalid database connection.
    die("Connection failed: " . $conn->connect_error);
}

// Commented statement: Success message for debugging (inactive).
// String: echo is a PHP statement that would output "Connected successfully" if uncommented.
// Provides a debugging confirmation of a successful database connection, disabled in production to avoid unnecessary output.
//echo "Connected successfully";

// Commented method call: Database connection closure (inactive).
// String: $conn->close() is a MySQLi method that would close the database connection if uncommented.
// Frees database resources after use, disabled here as the connection is needed by including scripts.
//$conn->close();
?>