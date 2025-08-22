<?php
// Return statement: Configuration data return.
// Array: return delivers an associative array with database connection settings (host, user, encrypted_pass, dbname).
// Provides the necessary credentials for databaseconnection.php to establish a MySQL connection in the International Bus Booking System.
return [
    // Array key-value pair: Database server hostname.
    // String: 'host' key maps to 'localhost', indicating the database server is on the same machine as the PHP application.
    // Specifies the server location for connecting to the MySQL database.
    'host' => 'localhost',

    // Array key-value pair: Database username.
    // String: 'user' key maps to 'busbooking', the username for database authentication.
    // Identifies the user account authorized to access the database.
    'user' => 'busbooking',

    // Array key-value pair: Encrypted database password.
    // String: 'encrypted_pass' key maps to 'U2FsdGVkX1+6Lj4jnUiywSqoT047h3jpY+7/eIitWdA=', a base64-encoded encrypted string.
    // Stores the password in an encrypted format for security, to be decrypted by databaseconnection.php before use.
    'encrypted_pass' => 'U2FsdGVkX1+6Lj4jnUiywSqoT047h3jpY+7/eIitWdA=',

    // Array key-value pair: Database name.
    // String: 'dbname' key maps to 'InternationalBusBookingSystem', the name of the database.
    // Identifies the specific database containing the system’s tables (e.g., users, bookingdetails).
    'dbname' => 'InternationalBusBookingSystem'
];
?>