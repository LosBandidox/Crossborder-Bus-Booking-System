// File: fetchProfile.js
// Purpose: Fetches user and customer details to display on the ProfileManagement.html page or pre-fill the UpdateProfile.html form in the International Bus Booking System.
// Retrieves data from the server and updates the webpage or form fields based on the page context.

// Function to get user and customer details
// - A user-defined JavaScript function named fetchProfile, with no inputs.
// - Sends two separate web requests to get user and customer data, then updates the profile display or form fields.
// - Shows or prepares profile information for customers on the profile management or update pages.
function fetchProfile() {
    // Sends a web request to get user details
    // - Uses the built-in JavaScript fetch() method to request data from a PHP file at '../../../php/fetchUserDetails.php'.
    // - Starts a chain of steps to process the server’s reply and show user info.
    // - Fetches user data like UserID and Name for the logged-in customer.
    fetch('../../../php/fetchUserDetails.php')
        // Processes the server’s reply
        // - A built-in JavaScript .then() method that converts the response to a JSON object using response.json().
        // - Turns the server’s reply into a usable object with user data.
        // - Prepares the user details for display or form filling.
        .then(response => response.json())
        // Handles the user data
        // - A .then() method that takes the data object and updates the profile section or form fields.
        // - Shows user details on ProfileManagement.html or fills fields on UpdateProfile.html.
        .then(data => {
            // Finds the user details section
            // - A user-defined constant named userDetailsElement, holding the result of a built-in JavaScript document.getElementById() method that finds the element with ID 'userDetails'.
            // - Targets the area on ProfileManagement.html where user details, like Name and Email, will be shown.
            // - Used only if the page has this element (ProfileManagement.html).
            const userDetailsElement = document.getElementById('userDetails');

            // Checks if on ProfileManagement.html
            // - A conditional statement checking if userDetailsElement exists.
            // - Updates the user details section if true, indicating the ProfileManagement.html page.
            // - Shows user info or an error based on the server’s reply.
            if (userDetailsElement) {
                // Checks if user data was received
                // - A conditional statement checking if data.user exists.
                // - Displays user details if available, or an error message if not.
                // - Handles the server’s reply for the user profile.
                if (data.user) {
                    // Fills the user details section
                    // - Sets userDetailsElement.innerHTML, a built-in JavaScript property, using a template literal (text with data inside backticks).
                    // - Adds paragraphs with user details like UserID, Name, Email, PhoneNumber, and Role.
                    // - Shows the customer’s user info clearly on ProfileManagement.html.
                    userDetailsElement.innerHTML = `
                        <p><strong>User ID:</strong> ${data.user.UserID}</p>
                        <p><strong>Name:</strong> ${data.user.Name}</p>
                        <p><strong>Email:</strong> ${data.user.Email}</p>
                        <p><strong>Phone Number:</strong> ${data.user.PhoneNumber}</p>
                        <p><strong>Role:</strong> ${data.user.Role}</p>
                    `;
                } else {
                    // Shows an error message
                    // - Sets userDetailsElement.innerHTML to a paragraph with “User details not found.”.
                    // - Tells the customer their user info couldn’t load, like if the server can’t find their record.
                    // - Keeps the profile section usable on ProfileManagement.html.
                    userDetailsElement.innerHTML = "<p>User details not found.</p>";
                }
            }

            // Finds the profile update form
            // - A user-defined constant named profileForm, holding the result of document.getElementById() for the element with ID 'profileUpdateForm'.
            // - Targets the form on UpdateProfile.html for pre-filling fields.
            // - Used only if the page has this form (UpdateProfile.html).
            const profileForm = document.getElementById('profileUpdateForm');

            // Checks if on UpdateProfile.html and user data exists
            // - A conditional statement checking if profileForm and data.user exist.
            // - Fills form fields with user data if true, indicating the UpdateProfile.html page.
            // - Prepares the form for editing user details.
            if (profileForm && data.user) {
                // Fills form fields with user data
                // - Sets the value property, a built-in JavaScript feature, of input elements with IDs 'name', 'email', and 'phone' using document.getElementById().
                // - Uses data.user properties or empty strings if undefined to fill Name, Email, and PhoneNumber.
                // - Makes it easy for the customer to edit their user info on UpdateProfile.html.
                document.getElementById('name').value = data.user.Name || '';
                document.getElementById('email').value = data.user.Email || '';
                document.getElementById('phone').value = data.user.PhoneNumber || '';
            }
        })
        // Handles errors during the user fetch
        // - A built-in JavaScript .catch() method that logs any errors in the fetch chain.
        // - Writes error details to the developer tools for troubleshooting.
        // - Ensures the page doesn’t break if fetching user data fails.
        .catch(error => console.error('Error fetching user details:', error));

    // Sends a web request to get customer details
    // - Uses the built-in JavaScript fetch() method to request data from a PHP file at '../../../php/fetchCustomerDetails.php'.
    // - Starts a chain of steps to process the server’s reply and show customer info.
    // - Fetches customer data like CustomerID and PassportNumber for the logged-in customer.
    fetch('../../../php/fetchCustomerDetails.php')
        // Processes the server’s reply
        // - A built-in JavaScript .then() method that converts the response to a JSON object using response.json().
        // - Turns the server’s reply into a usable object with customer data.
        // - Prepares the customer details for display or form filling.
        .then(response => response.json())
        // Handles the customer data
        // - A .then() method that takes the data object and updates the customer section or form fields.
        // - Shows customer details on ProfileManagement.html or fills fields on UpdateProfile.html.
        .then(data => {
            // Finds the customer details section
            // - A user-defined constant named customerDetailsElement, holding the result of document.getElementById() for the element with ID 'customerDetails'.
            // - Targets the area on ProfileManagement.html where customer details, like Gender and Nationality, will be shown.
            // - Used only if the page has this element (ProfileManagement.html).
            const customerDetailsElement = document.getElementById('customerDetails');

            // Checks if on ProfileManagement.html
            // - A conditional statement checking if customerDetailsElement exists.
            // - Updates the customer details section if true, indicating the ProfileManagement.html page.
            // - Shows customer info or an error based on the server’s reply.
            if (customerDetailsElement) {
                // Checks if customer data was received
                // - A conditional statement checking if data.customer exists.
                // - Displays customer details if available, or an error message if not.
                // - Handles the server’s reply for the customer profile.
                if (data.customer) {
                    // Fills the customer details section
                    // - Sets customerDetailsElement.innerHTML using a template literal.
                    // - Adds paragraphs with customer details like CustomerID, Name, Email, PhoneNumber, Gender, PassportNumber, and Nationality.
                    // - Shows the customer’s info clearly on ProfileManagement.html.
                    customerDetailsElement.innerHTML = `
                        <p><strong>Customer ID:</strong> ${data.customer.CustomerID}</p>
                        <p><strong>Name:</strong> ${data.customer.Name}</p>
                        <p><strong>Email:</strong> ${data.customer.Email}</p>
                        <p><strong>Phone Number:</strong> ${data.customer.PhoneNumber}</p>
                        <p><strong>Gender:</strong> ${data.customer.Gender}</p>
                        <p><strong>Passport Number:</strong> ${data.customer.PassportNumber}</p>
                        <p><strong>Nationality:</strong> ${data.customer.Nationality}</p>
                    `;
                } else {
                    // Shows an error message
                    // - Sets customerDetailsElement.innerHTML to a paragraph with “Customer details not found.”.
                    // - Tells the customer their info couldn’t load, like if the server can’t find their record.
                    // - Keeps the profile section usable on ProfileManagement.html.
                    customerDetailsElement.innerHTML = "<p>Customer details not found.</p>";
                }
            }

            // Finds the profile update form
            // - A user-defined constant named profileForm, holding the result of document.getElementById() for the element with ID 'profileUpdateForm'.
            // - Targets the form on UpdateProfile.html for pre-filling fields.
            // - Used only if the page has this form (UpdateProfile.html).
            const profileForm = document.getElementById('profileUpdateForm');

            // Checks if on UpdateProfile.html and customer data exists
            // - A conditional statement checking if profileForm and data.customer exist.
            // - Fills form fields with customer data if true, indicating the UpdateProfile.html page.
            // - Prepares the form for editing customer details.
            if (profileForm && data.customer) {
                // Fills form fields with customer data
                // - Sets the value property of input or select elements with IDs 'gender', 'passport', and 'nationality' using document.getElementById().
                // - Uses data.customer properties or defaults (e.g., 'Male' for Gender) to fill Gender, PassportNumber, and Nationality.
                // - Makes it easy for the customer to edit their info on UpdateProfile.html.
                document.getElementById('gender').value = data.customer.Gender || 'Male';
                document.getElementById('passport').value = data.customer.PassportNumber || '';
                document.getElementById('nationality').value = data.customer.Nationality || '';
            }
        })
        // Handles errors during the customer fetch
        // - A built-in JavaScript .catch() method that logs any errors in the fetch chain.
        // - Writes error details to the developer tools for troubleshooting.
        // - Ensures the page doesn’t break if fetching customer data fails.
        .catch(error => console.error('Error fetching customer details:', error));
}

// Loads profile data when the page opens
// - Sets the built-in JavaScript window.onload property to the user-defined fetchProfile() function.
// - Runs fetchProfile when the page fully loads, triggering the 'load' event.
// - Fills the profile sections or form with user and customer data on ProfileManagement.html or UpdateProfile.html.
window.onload = fetchProfile;