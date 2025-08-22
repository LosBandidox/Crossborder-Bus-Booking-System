<?php
// Function definition: Date parsing function.
// String: parseDateInput($input) is a custom function that takes a string parameter ($input) in DD-MM-YYYY format and returns a string in YYYY-MM-DD format or null if invalid.
// Converts user-entered dates to a database-compatible format for the International Bus Booking System’s queries (e.g., in SearchBuses.php).
function parseDateInput($input) {
    // Conditional statement: Logic to check for empty input.
    // Boolean check: empty() is a PHP built-in function that tests if $input is empty (e.g., "", null, or unset).
    // Returns null to indicate an invalid date if no input is provided, preventing further processing.
    if (empty($input)) return null;

    // Variable: Date object storage.
    // Object or boolean: DateTime::createFromFormat('d-m-Y', $input) is a PHP DateTime class method that parses $input in DD-MM-YYYY format (e.g., 24-07-2025), returning a DateTime object or false if parsing fails.
    // Attempts to convert the input string into a structured date for validation and formatting.
    $date = DateTime::createFromFormat('d-m-Y', $input);

    // Conditional statement: Logic to check parsing success.
    // Boolean check: Tests if $date is false, indicating the input does not match the DD-MM-YYYY format.
    // Returns null to indicate an invalid date if parsing fails, ensuring only valid dates proceed.
    if ($date === false) return null;

    // Variables: Date component storage.
    // Integers: $date->format('d'), $date->format('m'), and $date->format('Y') are DateTime methods that extract day, month, and year as strings, converted to integers using (int).
    // Extracts day, month, and year for further validation to ensure a valid date.
    $day = (int)$date->format('d');
    $month = (int)$date->format('m');
    $year = (int)$date->format('Y');

    // Conditional statement: Logic to validate date components.
    // Boolean check: checkdate($month, $day, $year) is a PHP built-in function that verifies if the month (1–12), day (1–31, depending on month), and year form a valid date (e.g., not 30-02-2025).
    // Returns null if the date is invalid, ensuring only valid dates are returned.
    if (!checkdate($month, $day, $year)) return null;

    // Return statement: Formatted date output.
    // String: $date->format('Y-m-d') is a DateTime method that returns the date as a string in YYYY-MM-DD format (e.g., 2025-07-24).
    // Provides the database-compatible date format for use in SQL queries.
    return $date->format('Y-m-d');
}

// Function definition: Datetime parsing function.
// String: parseDateTimeInput($input) is a custom function that takes a string parameter ($input) in DD-MM-YYYY HH:MM:SS format and returns a string in YYYY-MM-DD HH:MM:SS format or null if invalid.
// Converts user-entered datetimes to a database-compatible format for the International Bus Booking System’s queries or storage.
function parseDateTimeInput($input) {
    // Conditional statement: Logic to check for empty input.
    // Boolean check: empty() is a PHP built-in function that tests if $input is empty (e.g., "", null, or unset).
    // Returns null to indicate an invalid datetime if no input is provided, preventing further processing.
    if (empty($input)) return null;

    // Variable: Datetime object storage.
    // Object or boolean: DateTime::createFromFormat('d-m-Y H:i:s', $input) is a PHP DateTime class method that parses $input in DD-MM-YYYY HH:MM:SS format (e.g., 24-07-2025 13:55:00), returning a DateTime object or false if parsing fails.
    // Attempts to convert the input string into a structured datetime for validation and formatting.
    $dateTime = DateTime::createFromFormat('d-m-Y H:i:s', $input);

    // Conditional statement: Logic to check parsing success.
    // Boolean check: Tests if $dateTime is false, indicating the input does not match the DD-MM-YYYY HH:MM:SS format.
    // Returns null to indicate an invalid datetime if parsing fails, ensuring only valid datetimes proceed.
    if ($dateTime === false) return null;

    // Variables: Date component storage.
    // Integers: $dateTime->format('d'), $dateTime->format('m'), and $dateTime->format('Y') are DateTime methods that extract day, month, and year as strings, converted to integers using (int).
    // Extracts date components for validation to ensure a valid date portion.
    $day = (int)$dateTime->format('d');
    $month = (int)$dateTime->format('m');
    $year = (int)$dateTime->format('Y');

    // Conditional statement: Logic to validate date components.
    // Boolean check: checkdate($month, $day, $year) is a PHP built-in function that verifies if the month (1–12), day (1–31, depending on month), and year form a valid date.
    // Returns null if the date portion is invalid, ensuring only valid datetimes proceed.
    if (!checkdate($month, $day, $year)) return null;

    // Variables: Time component storage.
    // Integers: $dateTime->format('H'), $dateTime->format('i'), and $dateTime->format('s') are DateTime methods that extract hour, minute, and second as strings, converted to integers using (int).
    // Extracts time components for validation to ensure a valid time portion.
    $hour = (int)$dateTime->format('H');
    $minute = (int)$dateTime->format('i');
    $second = (int)$dateTime->format('s');

    // Conditional statement: Logic to validate time components.
    // Integer checks: Tests if $hour (0–23), $minute (0–59), and $second (0–59) are within valid ranges for a 24-hour clock.
    // Returns null if any time component is invalid, ensuring only valid datetimes are returned.
    if ($hour < 0 || $hour > 23 || $minute < 0 || $minute > 59 || $second < 0 || $second > 59) return null;

    // Return statement: Formatted datetime output.
    // String: $dateTime->format('Y-m-d H:i:s') is a DateTime method that returns the datetime as a string in YYYY-MM-DD HH:MM:SS format (e.g., 2025-07-24 13:55:00).
    // Provides the database-compatible datetime format for use in SQL queries or storage.
    return $dateTime->format('Y-m-d H:i:s');
}
?>