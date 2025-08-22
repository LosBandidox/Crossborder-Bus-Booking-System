// File: script.js
// Purpose: Contains functions to toggle the navigation menu and filter tables across pages in the International Bus Booking System.
// Provides reusable functionality for showing/hiding the navigation menu and searching table rows on admin or user dashboards.

// Function to show or hide the navigation menu
// - A user-defined JavaScript function named toggleMenu, with no inputs.
// - Uses DOM manipulation to add or remove a class that controls menu visibility.
// - Allows users to open or close the navigation menu on various system pages.
function toggleMenu() {
    // Toggles the menu’s visibility
    // - Uses document.getElementById(), a built-in JavaScript method, to find the element with ID 'nav-menu'.
    // - Calls classList.toggle(), a built-in method, to add or remove the 'active' class.
    // - Shows the menu if 'active' is added, hides it if removed, for easy navigation.
    document.getElementById('nav-menu').classList.toggle('active');
}

// Function to search and filter table rows
// - A user-defined JavaScript function named filterTable, with four inputs: tableId (string), searchInputId (string), noResultsId (string), columnIndices (array of numbers).
// - Filters table rows based on a search term in specified columns, showing matches and hiding non-matches.
// - Helps users like admins or drivers find specific data in tables, like schedules or bookings.
function filterTable(tableId, searchInputId, noResultsId, columnIndices) {
    // Gets the search term
    // - A user-defined variable named searchTerm, holding the value of the input with ID searchInputId, found using document.getElementById().
    // - A method call using getElementById to get the input value.
    // - Converts the input to lowercase with toLowerCase(), a built-in method, for case-insensitive searching.
    // - Stores what the user typed to filter table rows.
    let searchTerm = document.getElementById(searchInputId).value.toLowerCase();

    // Finds the table
    // - A user-defined variable named table, holding the result of document.getElementById(tableId) or document.querySelector(`.${tableId}`), built-in methods.
    // - A method call using getElementById to get the input value.
    // - Tries to find the table by ID first, then by class name if ID isn’t found.
    // - Ensures the function works with tables identified by either ID or class.
    let table = document.getElementById(tableId) || document.querySelector(`.${tableId}`);

    // Gets all table rows
    // - A user-defined variable named rows, holding the result of table.querySelectorAll('tbody tr'), a built-in method.
    // - Finds all <tr> elements inside the table’s <tbody> section.
    // - Collects the rows to check for search term matches.
    let rows = table.querySelectorAll('tbody tr');

    // Tracks if any rows match
    // - A user-defined variable named hasMatches, a boolean initially set to false.
    // - Becomes true if at least one row matches the search term.
    // - Decides whether to show a no-results message.
    let hasMatches = false;

    // Finds the no-results message
    // - A user-defined variable named noResultsMsg, holding the result of document.getElementById(noResultsId).
    // - Targets the element that shows a message when no rows match the search.
    // - Prepares to show or hide the message based on search results.
    let noResultsMsg = document.getElementById(noResultsId);

    // Checks each table row
    // - Uses forEach(), a built-in JavaScript method, to run code for each row in the rows collection.
    // - A method call using forEach to iterate over rows.
    // - Looks for the search term in specified columns to decide if the row should be shown.
    // - Filters the table dynamically as the user types.
    rows.forEach(row => {
        // Gets all cells in the row
        // - A user-defined variable named cells, holding the result of row.querySelectorAll("td").
        // - A method call using querySelectorAll to get cells.
        // - Finds all <td> elements in the current row.
        // - Collects cell contents to check against the search term.
        let cells = row.querySelectorAll("td");

        // Checks for a match in specified columns
        // - A user-defined variable named matches, a boolean from the built-in some() method on columnIndices.
        // - A method call using some to test columnIndices.
        // - Tests if any column index in columnIndices has a cell whose textContent (lowercase) includes the searchTerm.
        // - Determines if the row should be shown based on matching content.
        let matches = columnIndices.some(index => {
            return cells[index].textContent.toLowerCase().includes(searchTerm);
        });

        // Shows or hides the row
        // A method call to update row visibility
        // - Sets row.style.display, a built-in JavaScript property, to an empty string (show) if matches is true, or "none" (hide) if false.
        // - Makes the row visible if it contains the search term, or hides it if not.
        // - Updates the table display for the user’s search.
        row.style.display = matches ? "" : "none";

        // Tracks matching rows
        // - Sets hasMatches to true if the row matches the search term.
        // - Keeps track of whether any rows were found to control the no-results message.
        // - Ensures accurate feedback for empty search results.
        if (matches) hasMatches = true;
    });

    // Shows or hides the no-results message
    // A method call to update the no-results message
    // - Sets noResultsMsg.style.display to "none" if hasMatches is true, or "block" if false.
    // - Hides the message if any rows match, or shows it if no rows match.
    // - Gives clear feedback when the search finds no results.
    noResultsMsg.style.display = hasMatches ? "none" : "block";
}

// Function to navigate to the previous page
// - A user-defined JavaScript function named goBack, with no inputs.
// - Uses the built-in history.back() method to navigate to the previous page in the browser’s history.
// - Provides reusable back button functionality for pages like BookingHistory.html and SearchResults.php.
function goBack() {
    // Navigates to the previous page
    // - Uses window.history.back(), a built-in JavaScript method, to go to the previous page in the browser’s history.
    // - Mimics the browser’s back button, returning the user to the page they came from (e.g., dashboard).
    // - Ensures simple navigation across the system’s pages.
    window.history.back();
}