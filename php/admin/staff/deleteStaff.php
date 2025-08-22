<?php
// Function call: Sets the HTTP response header to specify JSON output.
// String: header() is a PHP built-in function that sets an HTTP header, here setting 'Content-Type: application/json', which informs the browser that the response is JSON data.
// Ensures the client’s JavaScript (e.g., on the admin dashboard or customer interface) can parse the response as JSON, maintaining compatibility with web standards in the International Bus Booking System.
header('Content-Type: application/json');

// Include statement: Loads the database connection configuration file.
// String: include is a PHP statement that imports '../../../php/databaseconnection.php', a file that defines the $conn variable, a MySQLi object for database connectivity.
// Establishes a connection to the database to perform update and deletion operations for staff and related records.
include('../../../php/databaseconnection.php');

// Conditional statement: Validates the presence and non-emptiness of the Staff ID from the URL query string.
// Function calls: isset() is a PHP built-in function that checks if $_GET['id'], a superglobal array element from the URL, exists and is not null; empty() is a PHP function that checks if $_GET['id'] is empty (e.g., an empty string).
// Ensures a valid Staff ID is provided before attempting updates and deletions, preventing invalid requests from proceeding.
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'No Staff ID provided'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript of the missing Staff ID, allowing error handling (e.g., displaying an error message to the user).
    echo json_encode(['status' => 'error', 'message' => 'No Staff ID provided']);
    
    // Method call: Closes the database connection.
    // String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
    // Ensures no database connections remain open after an invalid request, maintaining system efficiency.
    $conn->close();
    
    // Function call: Terminates script execution immediately.
    // String: exit() is a PHP built-in function that stops the script from running further.
    // Halts processing to prevent further actions after an invalid request.
    exit();
}

// Variable: Stores the Staff ID from the URL query string.
// String: $_GET['id'] is a superglobal array element containing the Staff ID passed via the URL (e.g., ?id=123), later validated as an integer through prepared statement binding.
// Captures the Staff ID to identify which staff record and related records to update or delete from the database.
$staffId = $_GET['id'];

// Try-catch block: Handles errors during the update and deletion process with a transaction.
// Structure: try contains code to perform updates and deletions within a transaction; catch captures any Exception object (stored in $e) thrown due to errors like query failures or invalid IDs.
// Ensures database operations are atomic (all succeed or none are applied) and provides error handling with a clean JSON response.
try {
    // Method call: Starts a database transaction.
    // String: begin_transaction() is a MySQLi method that initiates a transaction, grouping subsequent database operations so they can be committed or rolled back together.
    // Guarantees data consistency by ensuring all updates (DriverID, CodriverID, TechnicianID) and staff deletion succeed or fail as a unit, preventing partial changes.
    $conn->begin_transaction();

    // Variable: SQL query string to update DriverID in the scheduleinformation table.
    // String: Defines a query to UPDATE scheduleinformation SET DriverID = NULL WHERE DriverID = ?, using a placeholder (?) for secure parameter binding.
    // Sets DriverID to NULL for schedules associated with the given Staff ID, ensuring related data is updated before staff deletion.
    $scheduleSql = "UPDATE scheduleinformation SET DriverID = NULL WHERE DriverID = ?";
    
    // Variable: Stores the prepared statement for DriverID update.
    // Object: $conn->prepare($scheduleSql) is a MySQLi method that compiles the SQL query with the placeholder, returning a statement object for binding and execution.
    // Prepares the query to safely update DriverID, reducing the risk of SQL injection.
    $scheduleStmt = $conn->prepare($scheduleSql);
    
    // Conditional statement: Checks if the DriverID update query preparation was successful.
    // Boolean check: Tests if $scheduleStmt is false, indicating a preparation failure (e.g., syntax error or database issue).
    // Throws an exception to trigger rollback if preparation fails, ensuring no partial changes occur.
    if (!$scheduleStmt) throw new Exception('Failed to prepare DriverID update query');
    
    // Method call: Binds the Staff ID to the prepared statement.
    // String: bind_param("i", $staffId) is a MySQLi method that binds $staffId as an integer ('i') to the placeholder in the query, sanitizing the input.
    // Securely links the Staff ID to the query, preventing SQL injection by treating the input as data, not code.
    $scheduleStmt->bind_param("i", $staffId);
    
    // Method call: Executes the DriverID update query.
    // String: execute() is a MySQLi method that runs the prepared statement, setting DriverID to NULL for matching schedules.
    // Updates the scheduleinformation table as part of the transaction.
    $scheduleStmt->execute();
    
    // Method call: Frees the DriverID statement resources.
    // String: close() is a MySQLi method that releases the prepared statement ($scheduleStmt), freeing memory.
    // Ensures efficient resource management after the DriverID update is complete.
    $scheduleStmt->close();

    // Variable: SQL query string to update CodriverID in the scheduleinformation table.
    // String: Defines a query to UPDATE scheduleinformation SET CodriverID = NULL WHERE CodriverID = ?, using a placeholder for secure binding.
    // Sets CodriverID to NULL for schedules associated with the given Staff ID, ensuring related data is updated before staff deletion.
    $coScheduleSql = "UPDATE scheduleinformation SET CodriverID = NULL WHERE CodriverID = ?";
    
    // Variable: Stores the prepared statement for CodriverID update.
    // Object: $conn->prepare($coScheduleSql) compiles the SQL query, returning a statement object for binding and execution.
    // Prepares the query to safely update CodriverID, maintaining security against SQL injection.
    $coScheduleStmt = $conn->prepare($coScheduleSql);
    
    // Conditional statement: Checks if the CodriverID update query preparation was successful.
    // Boolean check: Tests if $coScheduleStmt is false, indicating a preparation failure.
    // Throws an exception to trigger rollback if preparation fails, ensuring data consistency.
    if (!$coScheduleStmt) throw new Exception('Failed to prepare CodriverID update query');
    
    // Method call: Binds the Staff ID to the CodriverID update statement.
    // String: bind_param("i", $staffId) binds $staffId as an integer to the placeholder in the query.
    // Securely links the Staff ID to the CodriverID update query, ensuring safe execution.
    $coScheduleStmt->bind_param("i", $staffId);
    
    // Method call: Executes the CodriverID update query.
    // String: execute() runs the prepared statement, setting CodriverID to NULL for matching schedules.
    // Updates the scheduleinformation table as part of the transaction.
    $coScheduleStmt->execute();
    
    // Method call: Frees the CodriverID statement resources.
    // String: close() releases the prepared statement ($coScheduleStmt), freeing memory.
    // Ensures efficient resource management after the CodriverID update is complete.
    $coScheduleStmt->close();

    // Variable: SQL query string to update TechnicianID in the maintenance table.
    // String: Defines a query to UPDATE maintenance SET TechnicianID = NULL WHERE TechnicianID = ?, using a placeholder for secure binding.
    // Sets TechnicianID to NULL for maintenance records associated with the given Staff ID, ensuring related data is updated before staff deletion.
    $maintSql = "UPDATE maintenance SET TechnicianID = NULL WHERE TechnicianID = ?";
    
    // Variable: Stores the prepared statement for TechnicianID update.
    // Object: $conn->prepare($maintSql) compiles the SQL query, returning a statement object for binding and execution.
    // Prepares the query to safely update TechnicianID, maintaining security against SQL injection.
    $maintStmt = $conn->prepare($maintSql);
    
    // Conditional statement: Checks if the TechnicianID update query preparation was successful.
    // Boolean check: Tests if $maintStmt is false, indicating a preparation failure.
    // Throws an exception to trigger rollback if preparation fails, ensuring data consistency.
    if (!$maintStmt) throw new Exception('Failed to prepare TechnicianID update query');
    
    // Method call: Binds the Staff ID to the TechnicianID update statement.
    // String: bind_param("i", $staffId) binds $staffId as an integer to the placeholder in the query.
    // Securely links the Staff ID to the TechnicianID update query, ensuring safe execution.
    $maintStmt->bind_param("i", $staffId);
    
    // Method call: Executes the TechnicianID update query.
    // String: execute() runs the prepared statement, setting TechnicianID to NULL for matching maintenance records.
    // Updates the maintenance table as part of the transaction.
    $maintStmt->execute();
    
    // Method call: Frees the TechnicianID statement resources.
    // String: close() releases the prepared statement ($maintStmt), freeing memory.
    // Ensures efficient resource management after the TechnicianID update is complete.
    $maintStmt->close();

    // Variable: SQL query string to delete the staff record.
    // String: Defines a query to DELETE FROM staff WHERE StaffID = ?, using a placeholder for secure binding.
    // Specifies the deletion of the staff record identified by the Staff ID, completing the removal process.
    $sql = "DELETE FROM staff WHERE StaffID = ?";
    
    // Variable: Stores the prepared statement for staff deletion.
    // Object: $conn->prepare($sql) compiles the SQL query, returning a statement object for binding and execution.
    // Prepares the query to safely delete the staff record, maintaining security against SQL injection.
    $stmt = $conn->prepare($sql);
    
    // Conditional statement: Checks if the staff deletion query preparation was successful.
    // Boolean check: Tests if $stmt is false, indicating a preparation failure.
    // Throws an exception to trigger rollback if preparation fails, ensuring data consistency.
    if (!$stmt) throw new Exception('Failed to prepare staff delete query');
    
    // Method call: Binds the Staff ID to the staff deletion statement.
    // String: bind_param("i", $staffId) binds $staffId as an integer to the placeholder in the query.
    // Securely links the Staff ID to the staff deletion query, ensuring safe execution.
    $stmt->bind_param("i", $staffId);
    
    // Method call: Executes the staff deletion query.
    // String: execute() runs the prepared statement, deleting the staff record matching the Staff ID.
    // Removes the staff record from the staff table as part of the transaction.
    $stmt->execute();
    
    // Method call: Frees the staff statement resources.
    // String: close() releases the prepared statement ($stmt), freeing memory.
    // Ensures efficient resource management after the staff deletion is complete.
    $stmt->close();

    // Method call: Commits the database transaction.
    // String: commit() is a MySQLi method that finalizes all changes made during the transaction, permanently applying the updates and deletion to the database.
    // Confirms all updates (DriverID, CodriverID, TechnicianID) and staff deletion, ensuring data consistency in the database.
    $conn->commit();

    // Output statement: Sends a JSON success response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'success', 'message' => 'Staff and related data updated'] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript that the updates and deletion were successful, allowing the dashboard or interface to update (e.g., remove the staff from the display).
    echo json_encode(['status' => 'success', 'message' => 'Staff and related data updated']);
} catch (Exception $e) {
    // Method call: Rolls back the database transaction.
    // String: rollback() is a MySQLi method that undoes all changes made during the current transaction, reverting the database to its state before the transaction began.
    // Prevents partial updates or deletions (e.g., DriverID updated but staff not deleted) if an error occurs, maintaining data integrity.
    $conn->rollback();
    
    // Output statement: Sends a JSON error response to the client.
    // String: echo outputs text; json_encode() is a PHP built-in function that converts an array ['status' => 'error', 'message' => 'Failed to delete staff: ' . $e->getMessage()] to a JSON string, a data format for web communication.
    // Informs the client’s JavaScript of the update or deletion failure, allowing error handling (e.g., displaying an error message to the user).
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete staff: ' . $e->getMessage()]);
}

// Method call: Closes the database connection.
// String: close() is a MySQLi method that terminates the database connection ($conn), freeing associated resources.
// Ensures no database connections remain open after operations, maintaining system efficiency and resource management.
$conn->close();
?>