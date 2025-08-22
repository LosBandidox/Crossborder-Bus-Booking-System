// File: edit_staff.js
// Handles fetching and updating staff details for the edit_staff.html page in the bus booking system.

// Attaches event listener for page load
// - Built-in event listener using document.addEventListener(), with 'DOMContentLoaded' event.
// - Executes async arrow function when edit_staff.html fully loads.
// - Fetches and populates staff details in the edit form for admin_staff.html.
document.addEventListener('DOMContentLoaded', async () => {
    // Declares URL parameters variable
    // - User-defined constant named urlParams using new URLSearchParams(), a built-in API.
    // - Extracts query parameters from window.location.search, a built-in property.
    // - Retrieves staff ID from URL (e.g., ?id=123).
    const urlParams = new URLSearchParams(window.location.search);
    
    // Declares staff ID variable
    // - User-defined constant named staffId using urlParams.get(), a built-in method.
    // - Fetches the 'id' parameter from the URL.
    // - Identifies which staff member to fetch for editing.
    const staffId = urlParams.get('id');

    // Checks if staff ID exists
    // - Built-in conditional testing staffId for truthiness.
    // - Proceeds with data fetching only if an ID is provided.
    // - Prevents errors from missing or invalid staff IDs.
    if (staffId) {
        // Starts error handling block
        // - Built-in try-catch block to handle network or data issues.
        // - Ensures robust error handling during staff data fetching.
        try {
            // Declares response variable
            // - User-defined constant named response using fetch(), a built-in async method.
            // - Sends GET request to PHP endpoint with staffId in query string.
            // - Retrieves staff details from the server.
            const response = await fetch(`../../../php/admin/staff/fetchStaffById.php?id=${staffId}`);
            
            // Declares staff data variable
            // - User-defined constant named staff using response.json(), a built-in method.
            // - Parses JSON response into an object with staff details.
            // - Contains fields like StaffID, Name, PhoneNumber, etc.
            const staff = await response.json();

            // Sets StaffID field
            // - Built-in assignment using document.getElementById().value.
            // - Sets editStaffId input value to staff.StaffID.
            // - Displays staff ID in the form for reference.
            document.getElementById('editStaffId').value = staff.StaffID;
            // Sets Name field
            // - Built-in assignment using document.getElementById().value.
            // - Sets name input value to staff.Name.
            // - Displays staff name in the form for editing.
            document.getElementById('name').value = staff.Name;
            // Sets PhoneNumber field
            // - Built-in assignment using document.getElementById().value.
            // - Sets phoneNumber input value to staff.PhoneNumber.
            // - Displays staff phone number in the form for editing.
            document.getElementById('phoneNumber').value = staff.PhoneNumber;
            // Sets Email field
            // - Built-in assignment using document.getElementById().value.
            // - Sets email input value to staff.Email.
            // - Displays staff email in the form for editing.
            document.getElementById('email').value = staff.Email;
            // Sets StaffNumber field
            // - Built-in assignment using document.getElementById().value.
            // - Sets staffNumber input value to staff.StaffNumber.
            // - Displays staff number in the form for editing.
            document.getElementById('staffNumber').value = staff.StaffNumber;
            // Sets Role field
            // - Built-in assignment using document.getElementById().value.
            // - Sets role input value to staff.Role.
            // - Displays staff role (e.g., driver, admin) in the form for editing.
            document.getElementById('role').value = staff.Role;
        } catch (error) {
            // Logs error
            // - Built-in console.error() method with error object.
            // - Outputs fetch issues to developer tools for debugging.
            // - Helps diagnose server or network problems with staff data.
            console.error('Error fetching staff:', error);
        }
    }
});

// Defines function to validate and submit form
// - User-defined function named validateAndSubmitStaffForm, with one input: event (form submission event).
// - Returns true if validation passes and submission proceeds, false otherwise.
// - Validates and submits staff updates in edit_staff.html.
function validateAndSubmitStaffForm(event) {
    // Prevents page reload
    // - Built-in method event.preventDefault().
    // - Stops default form submission to avoid page refresh.
    // - Allows async processing of staff form.
    event.preventDefault();
    
    // Checks form validity
    // - Built-in conditional using user-defined validateStaffForm from formValidation.js.
    // - Validates fields like Name, PhoneNumber, Email, StaffNumber, and Role.
    // - Proceeds only if validation passes.
    if (validateStaffForm()) {
        // Submits form data
        // - Built-in function call to user-defined submitStaffForm.
        // - Initiates async submission of staff data to server.
        // - Sends validated staff details for update.
        submitStaffForm();
        // Returns true
        // - Built-in return statement with boolean true.
        // - Indicates validation passed and submission started.
        // - Signals successful form processing.
        return true;
    }
    // Returns false
    // - Built-in return statement with boolean false.
    // - Stops submission due to validation failure.
    // - Prevents invalid staff data from being sent.
    return false;
}

// Defines async function to submit staff data
// - User-defined async function named submitStaffForm, with no inputs.
// - Sends staff form data to server and handles response.
// - Updates staff details in the database for admin_staff.html.
async function submitStaffForm() {
    // Declares form variable
    // - User-defined constant named form using document.getElementById(), a built-in method.
    // - Finds the form with ID 'editStaffForm' in edit_staff.html.
    // - Targets the form containing staff data for submission.
    const form = document.getElementById('editStaffForm');
    
    // Declares form data variable
    // - User-defined constant named formData using new FormData(), a built-in API.
    // - Collects data from form using the form variable.
    // - Captures staff details like name, email, and role.
    const formData = new FormData(form);

    // Starts error handling block
    // - Built-in try-catch block to handle network or server issues.
    // - Ensures robust error handling during staff submission.
    try {
        // Declares response variable
        // - User-defined constant named response using fetch() with POST method.
        // - Sends formData to PHP endpoint for staff update.
        // - Awaits server response for update result.
        const response = await fetch('../../../php/admin/staff/updateStaff.php', {
            // Sets request method
            // - User-defined property named method with value 'POST'.
            // - Specifies POST request type for data submission.
            // - Informs server to expect form data.
            method: 'POST',
            // Sets request body
            // - User-defined property named body with formData.
            // - Sends formData containing staff details to server.
            // - Uses FormData format for compatibility with PHP endpoint.
            body: formData
        });
        
        // Declares result variable
        // - User-defined constant named result using response.json(), a built-in method.
        // - Parses JSON response into object with status and message.
        // - Indicates success or failure of staff update.
        const result = await response.json();

        // Checks update status
        // - Built-in conditional testing result.status for 'success'.
        // - Determines if staff update succeeded.
        // - Decides next action based on server reply.
        if (result.status === 'success') {
            // Shows success message
            // - Built-in alert() method with success message.
            // - Notifies user that staff details were updated successfully.
            // - Confirms update in admin_staff.html.
            alert('Staff updated successfully');
            // Redirects to staff page
            // - Built-in assignment to window.location.href, a built-in property.
            // - Navigates to admin_staff.html after successful update.
            // - Returns user to main staff page.
            window.location.href = 'admin_staff.html';
        } else {
            // Shows error message
            // - Built-in alert() method with template literal including result.message.
            // - Notifies user of update failure with specific server message.
            // - Informs user of issues like invalid email or staff number.
            alert(`Failed to update staff: ${result.message}`);
        }
    } catch (error) {
        // Shows error message
        // - Built-in alert() method with template literal including error.message.
        // - Notifies user of submission failure due to network or server error.
        // - Provides specific error for clarity in admin_staff.html.
        alert(`Failed to update staff due to network error: ${error.message}`);
        // Logs error
        // - Built-in console.error() method with error object.
        // - Outputs submission issues to developer tools for debugging.
        // - Helps diagnose server or network problems with staff update.
        console.error('Error updating staff:', error);
    }
}