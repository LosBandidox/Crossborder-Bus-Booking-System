// File: admin_customers.js
// Purpose: Manages fetching, displaying, and deleting customer records for the admin_customers.html page in the International Bus Booking System.
// Enables administrators to view, edit, or delete customer information, with a searchable table for managing customer data.

// Function to fetch and display customer data from the server
// - An async JavaScript function named fetchCustomers, taking no parameters.
// - Executes asynchronous operations using the fetch API to retrieve customer data and updates the DOM to populate a table.
// - Retrieves customer records from a backend PHP script and displays them in a table with columns for CustomerID, Name, Email, etc.
// - Clears existing table rows before adding new ones to ensure accurate data display in the admin dashboard.
async function fetchCustomers() {
    // Error handling block to manage network or data parsing issues
    // - A try-catch structure surrounding the function’s core logic.
    // - Executes the fetch and DOM operations in the try block, catching errors like network failures to prevent crashes.
    // - Ensures reliable operation when fetching customer data for the admin interface.
    try {
        // Sends an HTTP request to retrieve customer data
        // - A constant storing the result of an awaited fetch() call, a method that makes an HTTP request to a specified URL.
        // - Targets the backend PHP script at '../../../php/admin/customer/fetchCustomers.php' using a relative path.
        // - Stores the server’s response, containing a JSON array of customer objects with properties like CustomerID, Name, and Email.
        // - Communicates with the server to get all customer records for display in the admin table.
        const response = await fetch('../../../php/admin/customer/fetchCustomers.php');

        // Parses the server response into a JavaScript array
        // - A constant storing the result of an awaited response.json() call, converting the response body to a JavaScript array.
        // - Contains an array of customer objects, each with properties like CustomerID, Name, Email, PhoneNumber, Gender, PassportNumber, and Nationality.
        // - Prepares the data for rendering in the table on admin_customers.html.
        const customers = await response.json();

        // References the table body element in the HTML
        // - A constant storing the result of document.getElementById(), a method that locates an element by its ID attribute.
        // - Targets the <tbody> element with ID 'customerTableBody' in admin_customers.html, where customer rows will be inserted.
        // - Allows manipulation of the table’s content to display customer data.
        const tableBody = document.getElementById('customerTableBody');

        // Clears existing content in the table body
        // - Sets the innerHTML property of tableBody to an empty string.
        // - Removes all existing <tr> elements (table rows) to prevent duplicate or stale data.
        // - Prepares the table for fresh customer data from the server in the admin interface.
        tableBody.innerHTML = ''; // Clear existing rows

        // Iterates through each customer to create table rows
        // - A forEach loop, a method that executes a callback function for each element in the customers array.
        // - Processes each customer object to generate a corresponding table row with customer details and action buttons.
        // - Builds the table dynamically based on the number of customers retrieved for the admin dashboard.
        customers.forEach(customer => {
            // Creates a new table row element
            // - A constant storing the result of document.createElement(), a method that creates a new HTML element.
            // - Generates a <tr> (table row) element to hold one customer’s data.
            // - Serves as a container for table cells displaying customer details in the table.
            const row = document.createElement('tr');

            // Populates the table row with customer data and action buttons
            // - Sets the innerHTML property of the row element using a template literal, a string format enclosed in backticks allowing embedded expressions.
            // - Inserts table cells (<td>) with customer properties (e.g., customer.CustomerID, customer.Name) and HTML for Edit and Delete buttons.
            // - Provides interactive controls for editing or deleting customers in the admin interface.
            row.innerHTML = `
                <td>${customer.CustomerID}</td>
                <td>${customer.Name}</td>
                <td>${customer.Email}</td>
                <td>${customer.PhoneNumber}</td>
                <td>${customer.Gender}</td>
                <td>${customer.PassportNumber}</td>
                <td>${customer.Nationality}</td>
                <td>
                    <a href="edit_customer.html?id=${customer.CustomerID}" class="btn">Edit</a>
                    <button onclick="deleteCustomer(${customer.CustomerID})">Delete</button>
                </td>
            `;

            // Adds the table row to the table body
            // - Calls appendChild() on tableBody, a method that adds a child element (row) to the end of the tableBody’s children.
            // - Inserts the populated <tr> into the <tbody>, rendering it in the UI.
            // - Updates the table in admin_customers.html to display the customer row.
            tableBody.appendChild(row);
        });
    } catch (error) {
        // Logs errors encountered during fetching or processing
        // - Calls console.error(), a method that outputs an error message to the browser’s developer console.
        // - Includes the string 'Error fetching customers:' and the error object for debugging purposes.
        // - Helps identify issues like network failures or JSON parsing errors in the admin dashboard.
        console.error('Error fetching customers:', error);
    }
}

// Function to delete a customer by ID and refresh the table
// - An async JavaScript function named deleteCustomer, taking one parameter (customerId: a number representing a customer’s unique ID).
// - Sends an HTTP GET request to the backend to delete a customer and updates the UI table by calling fetchCustomers().
// - Prompts the user to confirm deletion before proceeding, enhancing user control and preventing accidental deletions in the bus booking system.
async function deleteCustomer(customerId) {
    // Prompts user confirmation before deleting a customer
    // - An if statement using the confirm() method, a browser function that displays a dialog box with a message and OK/Cancel buttons.
    // - Shows a message with the customerId (e.g., “Are you sure you want to delete customer with ID: 123?”).
    // - Proceeds with deletion only if the user clicks OK (returns true), ensuring intentional deletion in the admin interface.
    if (confirm(`Are you sure you want to delete customer with ID: ${customerId}?`)) {
        // Error handling block for deletion operations
        // - A try-catch structure surrounding the deletion logic.
        // - Executes the fetch and response processing in the try block, catching errors (e.g., server issues) in the catch block.
        // - Maintains application stability during deletion attempts in the customer management system.
        try {
            // Sends an HTTP request to delete the customer
            // - A constant storing the result of an awaited fetch() call, targeting the backend PHP script at '../../../php/admin/customer/deleteCustomer.php'.
            // - Appends the customerId as a query parameter (e.g., ?id=123) to identify the customer to delete.
            // - Stores the server’s response, expected to contain a JSON object with status and message properties.
            // - Communicates with the server to remove a specific customer record.
            const response = await fetch(`../../../php/admin/customer/deleteCustomer.php?id=${customerId}`);

            // Parses the server response into a JavaScript object
            // - A constant storing the result of an awaited response.json() call, converting the response body to a JavaScript object.
            // - Expects an object with status (e.g., 'success') and message (e.g., “Customer deleted”) properties indicating the deletion outcome.
            // - Prepares the result for conditional handling in the admin interface.
            const result = await response.json();

            // Checks the deletion outcome
            // - An if statement evaluating whether result.status equals 'success'.
            // - Handles successful deletions by notifying the user and refreshing the table; otherwise, shows an error with details.
            // - Provides feedback and updates the UI based on the server’s response in the customer management system.
            if (result.status === 'success') {
                // Notifies the user of successful deletion
                // - Calls alert(), a browser method that displays a popup message.
                // - Shows “Customer deleted successfully” to confirm the action.
                // - Informs the admin that the customer was removed from the database.
                alert('Customer deleted successfully');

                // Refreshes the customer table
                // - Calls the fetchCustomers() function to reload customer data from the server.
                // - Updates the table in admin_customers.html to reflect the deleted customer.
                // - Ensures the UI shows the current state of customers after deletion.
                fetchCustomers(); // Refresh the customer list
            } else {
                // Notifies the user of deletion failure
                // - Calls alert(), displaying “Failed to delete customer:” followed by result.message (e.g., “Customer has active bookings”).
                // - Informs the admin of the issue preventing deletion, providing server-provided details.
                // - Helps troubleshoot issues in the bus booking system.
                alert('Failed to delete customer: ' + result.message);
            }
        } catch (error) {
            // Logs errors encountered during deletion
            // - Calls console.error(), outputting the string 'Error deleting customer:' and the error object to the console.
            // - Aids in debugging issues like network failures or invalid server responses in the admin dashboard.
            console.error('Error deleting customer:', error);
        }
    }
}

// Initializes customer table and search functionality on page load
// - Adds an event listener to the document object for the 'DOMContentLoaded' event.
// - Executes a callback function when the HTML document is fully loaded, ensuring DOM elements are accessible.
// - Triggers fetchCustomers() to populate the table and sets up a search feature for filtering customers in the admin_customers.html page.
document.addEventListener('DOMContentLoaded', () => {
    // Populates the customer table with initial data
    // - Calls the fetchCustomers() function to load customer records from the server.
    // - Initializes the table in admin_customers.html with all available customers.
    // - Provides immediate access to customer data for admins upon page load.
    fetchCustomers();

    // Sets up real-time search functionality for the customer table
    // - Adds an event listener to the element with ID 'customerSearch' using addEventListener(), a method that attaches an event handler.
    // - Listens for the 'input' event, triggered whenever the user types in the search field.
    // - Executes a callback to filter the table based on user input in the admin interface.
    document.getElementById('customerSearch').addEventListener('input', () => {
        // Filters table rows based on search input
        // - Calls the filterTable() function, passing the table ID ('customerTable'), search input ID ('customerSearch'), message ID ('noCustomers'), and an array of column indices ([0, 1, 2, 3]).
        // - Filters rows in the table to show only those matching the search query in columns for CustomerID, Name, Email, or PhoneNumber.
        // - Enhances usability by enabling dynamic search in the admin customer interface.
        filterTable('customerTable', 'customerSearch', 'noCustomers', [0, 1, 2, 3]);
    });
});