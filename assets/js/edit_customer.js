// File: edit_customer.js
// Handles fetching and updating customer details for the edit_customer.html page in the bus booking system.

// Attaches event listener for page load
// - Uses document.addEventListener(), a built-in method, to listen for the 'DOMContentLoaded' event.
// - Runs when the webpage fully loads, ensuring DOM elements are accessible.
// - Fetches customer data to populate the edit form.
document.addEventListener('DOMContentLoaded', async () => {
    // Gets URL query parameters
    // - User-defined constant named urlParams using new URLSearchParams(), a built-in API.
    // - Extracts parameters from window.location.search, a built-in property.
    // - Retrieves the customer ID from the URL (e.g., ?id=123).
    const urlParams = new URLSearchParams(window.location.search);
    
    // Gets customer ID
    // - User-defined constant named customerId using urlParams.get(), a built-in method.
    // - Fetches the 'id' parameter from the URL.
    // - Identifies which customer’s data to load for editing.
    const customerId = urlParams.get('id');

    // Checks if customer ID exists
    // - Tests if customerId is truthy using an if statement.
    // - Proceeds with fetching data only if an ID is provided.
    // - Prevents errors from missing or invalid IDs.
    if (customerId) {
        // Handles potential errors
        // - Uses a try-catch block, a built-in structure, to manage network or data issues.
        // - Ensures robust error handling during data fetching.
        try {
            // Fetches customer data
            // - User-defined constant named response using fetch(), a built-in async method.
            // - Sends a GET request to a PHP endpoint with customerId in the query string.
            // - Retrieves customer details from the server.
            const response = await fetch(`../../../php/admin/customer/fetchCustomerById.php?id=${customerId}`);
            
            // Parses response
            // - User-defined constant named customer using response.json(), a built-in method.
            // - Converts the server’s JSON response into an object.
            // - Contains fields like CustomerID, Name, Email, etc.
            const customer = await response.json();

            // Populates form fields
            // - Uses document.getElementById().value, built-in methods and properties.
            // - Sets input values for CustomerID, Name, Email, PhoneNumber, PassportNumber, and Nationality.
            // - Displays customer data in the form for editing.
            document.getElementById('editCustomerId').value = customer.CustomerID;
            document.getElementById('name').value = customer.Name;
            document.getElementById('email').value = customer.Email;
            document.getElementById('phoneNumber').value = customer.PhoneNumber;
            
            // Sets gender radio button
            // - Checks customer.Gender and uses document.getElementById().checked, built-in methods and properties.
            // - Selects 'Male' or 'Female' radio button based on the customer’s gender.
            // - Ensures the form reflects the stored gender value.
            if (customer.Gender === 'Male') {
                document.getElementById('male').checked = true;
            } else if (customer.Gender === 'Female') {
                document.getElementById('female').checked = true;
            }
            
            // Sets additional fields
            // - Uses document.getElementById().value to set PassportNumber and Nationality.
            // - Completes form population with remaining customer data.
            document.getElementById('passportNumber').value = customer.PassportNumber;
            document.getElementById('nationality').value = customer.Nationality;
        } catch (error) {
            // Logs fetch errors
            // - Uses console.error(), a built-in method, to output error details.
            // - Shows network or parsing issues in developer tools for debugging.
            // - Helps developers diagnose problems with the PHP endpoint.
            console.error('Error fetching customer:', error);
        }
    }
});

// Validates and submits the customer edit form
// - User-defined function named validateAndSubmitCustomerForm, with one input: event (form submission event).
// - Returns true if validation passes and submission proceeds, false otherwise.
// - Ensures form data is valid before sending updates to the server.
function validateAndSubmitCustomerForm(event) {
    // Prevents page reload
    // - Uses event.preventDefault(), a built-in method.
    // - Stops the form’s default submission behavior.
    // - Keeps the page active for async processing.
    event.preventDefault();

    // Validates form data
    // - Calls validateCustomerForm(), a user-defined function from formValidation.js.
    // - Checks fields like Name, Email, PhoneNumber, etc., for validity.
    // - Proceeds to submission only if all checks pass.
    if (validateCustomerForm()) {
        // Submits form data
        // - Calls user-defined submitCustomerForm(), an async function.
        // - Sends validated data to the server for updating.
        submitCustomerForm();
        return true;
    }
    // Stops if validation fails
    // - Returns false to indicate errors in the form.
    // - Prevents submission of invalid data.
    return false;
}

// Submits updated customer data to the server
// - User-defined async function named submitCustomerForm, with no inputs.
// - Sends form data to a PHP endpoint and handles the response.
// - Updates customer details in the database.
async function submitCustomerForm() {
    // Gets the form element
    // - User-defined constant named form using document.getElementById(), a built-in method.
    // - Targets the form with ID 'editCustomerForm'.
    // - Accesses all input fields for submission.
    const form = document.getElementById('editCustomerForm');
    
    // Collects form data
    // - User-defined constant named formData using new FormData(), a built-in API.
    // - Gathers all form inputs (e.g., CustomerID, Name, Email) into a FormData object.
    // - Prepares data for sending to the server.
    const formData = new FormData(form);

    // Handles potential errors
    // - Uses a try-catch block to manage network or server issues.
    // - Ensures robust error handling during submission.
    try {
        // Sends form data
        // - User-defined constant named response using fetch() with POST method.
        // - Sends formData to a PHP endpoint for updating customer details.
        // - Awaits the server’s response.
        const response = await fetch('../../../php/admin/customer/updateCustomer.php', {
         // Specifies the request type as POST
        // Tells the server to expect form data
            method: 'POST',
            
            
            // Sends the form data
            // Includes all customer details from the form
            body: formData
        });
        
        // Parses server response
        // - User-defined constant named result using response.json().
        // - Converts the JSON response into an object with status and message.
        // - Indicates whether the update succeeded or failed.
        const result = await response.json();

        // Checks update status
        // - Tests if result.status equals 'success' using a conditional.
        // - Handles success or failure based on the server’s reply.
        if (result.status === 'success') {
            // Shows success message
            // - Uses alert(), a built-in method, to notify the user.
            // - Confirms the customer was updated successfully.
            alert('Customer updated successfully');
            
            // Redirects to customer list
            // - Uses window.location.href, a built-in property, to navigate to admin_customers.html.
            // - Returns the user to the main customers page after a successful update.
            window.location.href = 'admin_customers.html';
        } else {
            // Shows error message
            // - Uses alert() with result.message to display the server’s error.
            // - Informs the user why the update failed (e.g., duplicate email).
            alert('Failed to update customer: ' + result.message);
        }
    } catch (error) {
        // Logs submission errors
        // - Uses console.error() to output error details.
        // - Shows network or parsing issues in developer tools for debugging.
        // - Helps developers diagnose problems with the PHP endpoint.
        console.error('Error updating customer:', error);
    }
}