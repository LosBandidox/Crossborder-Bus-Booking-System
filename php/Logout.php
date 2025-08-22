<?php
// Function call: Session initiation function.
// String: session_start() is a PHP built-in function that starts or resumes a session to manage user data across pages.
// Ensures the session is active so it can be manipulated (e.g., cleared and destroyed) during logout in the International Bus Booking System.
session_start();

// Function call: Session variables clearing function.
// String: session_unset() is a PHP built-in function that removes all variables stored in the current session.
// Clears all session data (e.g., UserID, Email) while keeping the session ID, preparing for complete session termination.
session_unset();

// Function call: Session termination function.
// String: session_destroy() is a PHP built-in function that deletes the session and its data on the server.
// Invalidates the session ID, fully ending the user’s login session to ensure they are logged out.
session_destroy();

// Function call: HTTP header redirection function.
// String: header() is a PHP built-in function that sets an HTTP response header, here setting 'Location: ../frontend/HomePage.html'.
// Redirects the user’s browser to the homepage after logout, providing a clean post-logout experience.
header("Location: ../frontend/HomePage.html");

// Function call: Script termination function.
// String: exit() is a PHP built-in function that stops script execution.
// Halts the script after the redirect to prevent any further code from running, ensuring a smooth transition to the homepage.
exit();
?>