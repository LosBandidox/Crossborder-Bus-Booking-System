// File: edit_payment.js
// Handles fetching and updating payment details for the edit_payment.html page in the bus booking system.

// Defines function to convert date format
// - User-defined function named convertDateFormat, with one input: dateStr (string, date to convert).
// - Returns reformatted date as YYYY-MM-DD HH:MM:SS or original string if invalid.
// - Converts payment dates from DD-MM-YYYY HH:MM:SS to database-compatible format.
function convertDateFormat(dateStr) {
    // Checks date string length
    // - Built-in conditional using dateStr.length property to test if string is at least 19 characters.
    // - Ensures dateStr matches DD-MM-YYYY HH:MM:SS format (e.g., 31-12-2025 14:30:00).
    // - Proceeds only if length is sufficient.
    if (dateStr.length < 19) {
        // Returns original string
        // - Built-in return statement with dateStr.
        // - Preserves input if format is too short to avoid invalid conversion.
        // - Indicates an error in date format for payment submission.
        return dateStr;
    }

    // Checks hyphen and space positions
    // - Built-in conditional using string indexing (dateStr[2], dateStr[5], dateStr[10]).
    // - Verifies hyphens at positions 2 and 5, space at position 10 for DD-MM-YYYY HH:MM:SS.
    // - Ensures correct structure before extracting components.
    if (dateStr[2] !== '-' || dateStr[5] !== '-' || dateStr[10] !== ' ') {
        // Returns original string
        // - Built-in return statement with dateStr.
        // - Preserves input if hyphens or space are misplaced.
        // - Prevents incorrect date parsing for database submission.
        return dateStr;
    }

    // Declares day string variable
    // - User-defined constant named dayStr using substring(), a built-in method.
    // - Extracts day from positions 0-2 (e.g., "31" from "31-12-2025").
    // - Stores day for reformatting payment date.
    let dayStr = dateStr.substring(0, 2);
    // Declares month string variable
    // - User-defined constant named monthStr using substring().
    // - Extracts month from positions 3-5 (e.g., "12" from "31-12-2025").
    // - Stores month for reformatting payment date.
    let monthStr = dateStr.substring(3, 5);
    // Declares year string variable
    // - User-defined constant named yearStr using substring().
    // - Extracts year from positions 6-10 (e.g., "2025" from "31-12-2025").
    // - Stores year for reformatting payment date.
    let yearStr = dateStr.substring(6, 10);
    // Declares time string variable
    // - User-defined constant named timeStr using substring().
    // - Extracts time from positions 11-19 (e.g., "14:30:00" from "31-12-2025 14:30:00").
    // - Stores time for reformatting payment date.
    let timeStr = dateStr.substring(11, 19);

    // Checks component lengths
    // - Built-in conditional using length properties of dayStr, monthStr, yearStr, timeStr.
    // - Verifies lengths are 2, 2, 4, and 8 respectively for valid DD-MM-YYYY HH:MM:SS format.
    // - Ensures components are correctly sized before conversion.
    if (dayStr.length !== 2 || monthStr.length !== 2 || yearStr.length !== 4 || timeStr.length !== 8) {
        // Returns original string
        // - Built-in return statement with dateStr.
        // - Preserves input if component lengths are incorrect.
        // - Prevents invalid date formatting for database submission.
        return dateStr;
    }

    // Declares day number variable
    // - User-defined constant named day using parseInt(), a built-in method with base 10.
    // - Converts dayStr to a number (e.g., "31" to 31).
    // - Prepares day for numerical validation.
    let day = parseInt(dayStr, 10);
    // Declares month number variable
    // - User-defined constant named month using parseInt() with base 10.
    // - Converts monthStr to a number (e.g., "12" to 12).
    // - Prepares month for numerical validation.
    let month = parseInt(monthStr, 10);
    // Declares year number variable
    // - User-defined constant named year using parseInt() with base 10.
    // - Converts yearStr to a number (e.g., "2025" to 2025).
    // - Prepares year for numerical validation.
    let year = parseInt(yearStr, 10);

    // Checks if components are numbers
    // - Built-in conditional using isNaN(), a built-in method, on day, month, and year.
    // - Ensures day, month, and year are valid numbers (not NaN).
    // - Prevents non-numeric components from being reformatted.
    if (isNaN(day) || isNaN(month) || isNaN(year)) {
        // Returns original string
        // - Built-in return statement with dateStr.
        // - Preserves input if any component is not a number.
        // - Avoids invalid date conversion for payment submission.
        return dateStr;
    }

    // Returns reformatted date
    // - Built-in return statement using template literal with yearStr, monthStr, dayStr, timeStr.
    // - Combines components as YYYY-MM-DD HH:MM:SS (e.g., "2025-12-31 14:30:00").
    // - Provides database-compatible date format for payment records.
    return `${yearStr}-${monthStr}-${dayStr} ${timeStr}`;
}

// Defines function to convert date for validation
// - User-defined function named convertToValidationFormat, with one input: dateStr (string, date to convert).
// - Returns reformatted date as DD-MM-YYYY HH:MM:SS or original string if invalid.
// - Converts payment dates from YYYY-MM-DD HH:MM:SS to form validation format.
function convertToValidationFormat(dateStr) {
    // Checks date string length
    // - Built-in conditional using dateStr.length to test if string is at least 19 characters.
    // - Ensures dateStr matches YYYY-MM-DD HH:MM:SS format (e.g., 2025-12-31 14:30:00).
    // - Proceeds only if length is sufficient.
    if (dateStr.length < 19) {
        // Returns original string
        // - Built-in return statement with dateStr.
        // - Preserves input if format is too short to avoid invalid conversion.
        // - Indicates an error in date format for form validation.
        return dateStr;
    }

    // Checks hyphen and space positions
    // - Built-in conditional using string indexing (dateStr[4], dateStr[7], dateStr[10]).
    // - Verifies hyphens at positions 4 and 7, space at position 10 for YYYY-MM-DD HH:MM:SS.
    // - Ensures correct structure before extracting components.
    if (dateStr[4] !== '-' || dateStr[7] !== '-' || dateStr[10] !== ' ') {
        // Returns original string
        // - Built-in return statement with dateStr.
        // - Preserves input if hyphens or space are misplaced.
        // - Prevents incorrect date parsing for form validation.
        return dateStr;
    }

    // Declares year string variable
    // - User-defined constant named yearStr using substring().
    // - Extracts year from positions 0-4 (e.g., "2025" from "2025-12-31").
    // - Stores year for reformatting payment date.
    let yearStr = dateStr.substring(0, 4);
    // Declares month string variable
    // - User-defined constant named monthStr using substring().
    // - Extracts month from positions 5-7 (e.g., "12" from "2025-12-31").
    // - Stores month for reformatting payment date.
    let monthStr = dateStr.substring(5, 7);
    // Declares day string variable
    // - User-defined constant named dayStr using substring().
    // - Extracts day from positions 8-10 (e.g., "31" from "2025-12-31").
    // - Stores day for reformatting payment date.
    let dayStr = dateStr.substring(8, 10);
    // Declares time string variable
    // - User-defined constant named timeStr using substring().
    // - Extracts time from positions 11-19 (e.g., "14:30:00" from "2025-12-31 14:30:00").
    // - Stores time for reformatting payment date.
    let timeStr = dateStr.substring(11, 19);

    // Checks component lengths
    // - Built-in conditional using length properties of yearStr, monthStr, dayStr, timeStr.
    // - Verifies lengths are 4, 2, 2, and 8 respectively for valid YYYY-MM-DD HH:MM:SS format.
    // - Ensures components are correctly sized before conversion.
    if (yearStr.length !== 4 || monthStr.length !== 2 || dayStr.length !== 2 || timeStr.length !== 8) {
        // Returns original string
        // - Built-in return statement with dateStr.
        // - Preserves input if component lengths are incorrect.
        // - Prevents invalid date formatting for form validation.
        return dateStr;
    }

    // Declares year number variable
    // - User-defined constant named year using parseInt() with base 10.
    // - Converts yearStr to a number (e.g., "2025" to 2025).
    // - Prepares year for numerical validation.
    let year = parseInt(yearStr, 10);
    // Declares month number variable
    // - User-defined constant named month using parseInt() with base 10.
    // - Converts monthStr to a number (e.g., "12" to 12).
    // - Prepares month for numerical validation.
    let month = parseInt(monthStr, 10);
    // Declares day number variable
    // - User-defined constant named day using parseInt() with base 10.
    // - Converts dayStr to a number (e.g., "31" to 31).
    // - Prepares day for numerical validation.
    let day = parseInt(dayStr, 10);

    // Checks if components are numbers
    // - Built-in conditional using isNaN() on year, month, and day.
    // - Ensures year, month, and day are valid numbers (not NaN).
    // - Prevents non-numeric components from being reformatted.
    if (isNaN(year) || isNaN(month) || isNaN(day)) {
        // Returns original string
        // - Built-in return statement with dateStr.
        // - Preserves input if any component is not a number.
        // - Avoids invalid date conversion for form validation.
        return dateStr;
    }

    // Returns reformatted date
    // - Built-in return statement using template literal with dayStr, monthStr, yearStr, timeStr.
    // - Combines components as DD-MM-YYYY HH:MM:SS (e.g., "31-12-2025 14:30:00").
    // - Provides format compatible with validatePaymentForm in formValidation.js.
    return `${dayStr}-${monthStr}-${yearStr} ${timeStr}`;
}

// Defines function to map form IDs
// - User-defined function named mapIdsForValidation, with no inputs.
// - Changes edit form field IDs to match add form IDs for validation.
// - Enables reuse of validatePaymentForm for editing payments in admin_payments.html.
function mapIdsForValidation() {
    // Declares ID mappings
    // - User-defined constant named idMappings using an object literal.
    // - Maps edit form IDs (e.g., editBookingID) to add form IDs (e.g., bookingID).
    // - Defines fields for validation compatibility with formValidation.js.
    const idMappings = {
        // Maps BookingID field
        // - User-defined property with key editBookingID and value 'bookingID'.
        // - Links edit form’s BookingID to add form’s ID for validation.
        // - Ensures booking ID validation in payment editing.
        editBookingID: 'bookingID',
        // Maps AmountPaid field
        // - User-defined property with key editAmountPaid and value 'amountPaid'.
        // - Links edit form’s AmountPaid to add form’s ID for validation.
        // - Ensures amount paid validation in payment editing.
        editAmountPaid: 'amountPaid',
        // Maps PaymentMode field
        // - User-defined property with key editPaymentMode and value 'paymentMode'.
        // - Links edit form’s PaymentMode to add form’s ID for validation.
        // - Ensures payment mode validation in payment editing.
        editPaymentMode: 'paymentMode',
        // Maps PaymentDate field
        // - User-defined property with key editPaymentDate and value 'paymentDate'.
        // - Links edit form’s PaymentDate to add form’s ID for validation.
        // - Ensures payment date validation in payment editing.
        editPaymentDate: 'paymentDate',
        // Maps ReceiptNumber field
        // - User-defined property with key editReceiptNumber and value 'receiptNumber'.
        // - Links edit form’s ReceiptNumber to add form’s ID for validation.
        // - Ensures receipt number validation in payment editing.
        editReceiptNumber: 'receiptNumber',
        // Maps TransactionID field
        // - User-defined property with key editTransactionID and value 'transactionID'.
        // - Links edit form’s TransactionID to add form’s ID for validation.
        // - Ensures transaction ID validation in payment editing.
        editTransactionID: 'transactionID',
        // Maps Status field
        // - User-defined property with key editStatus and value 'status'.
        // - Links edit form’s Status to add form’s ID for validation.
        // - Ensures status validation in payment editing.
        editStatus: 'status'
    };

    // Iterates through ID mappings
    // - Built-in for...of loop using Object.entries(), a built-in method, to process idMappings.
    // - Loops over each editId-addId pair to update form field IDs.
    // - Prepares fields for validation in payment form.
    for (const [editId, addId] of Object.entries(idMappings)) {
        // Declares element variable
        // - User-defined constant named element using document.getElementById(), a built-in method.
        // - Finds the form field with editId (e.g., editBookingID).
        // - Targets the field for ID modification.
        const element = document.getElementById(editId);
        
        // Checks if element exists
        // - Built-in conditional testing element for truthiness.
        // - Proceeds only if the form field is found in edit_payment.html.
        // - Prevents errors from missing fields.
        if (element) {
            // Checks for payment date field
            // - Built-in conditional comparing editId to 'editPaymentDate'.
            // - Identifies if the current field is the payment date input.
            // - Triggers date conversion for validation compatibility.
            if (editId === 'editPaymentDate') {
                // Converts date value
                // - Built-in assignment using user-defined convertToValidationFormat.
                // - Updates element.value to DD-MM-YYYY HH:MM:SS format.
                // - Ensures payment date matches validatePaymentForm requirements.
                element.value = convertToValidationFormat(element.value);
            }
            // Updates field ID
            // - Built-in assignment setting element.id, a built-in property.
            // - Changes ID from editId to addId (e.g., editBookingID to bookingID).
            // - Aligns field with add form validation in formValidation.js.
            element.id = addId;
        }
    }
}

// Defines function to restore form IDs
// - User-defined function named restoreOriginalIds, with no inputs.
// - Reverts form field IDs to original edit form values after validation.
// - Ensures correct IDs for payment submission in admin_payments.html.
function restoreOriginalIds() {
    // Declares ID mappings
    // - User-defined constant named idMappings using an object literal.
    // - Maps edit form IDs to add form IDs for restoration.
    // - Reuses mapping from mapIdsForValidation for consistency.
    const idMappings = {
        // Maps BookingID field
        // - User-defined property with key editBookingID and value 'bookingID'.
        // - Links edit form’s BookingID to add form’s ID for restoration.
        // - Restores original ID after validation.
        editBookingID: 'bookingID',
        // Maps AmountPaid field
        // - User-defined property with key editAmountPaid and value 'amountPaid'.
        // - Links edit form’s AmountPaid to add form’s ID for restoration.
        // - Restores original ID after validation.
        editAmountPaid: 'amountPaid',
        // Maps PaymentMode field
        // - User-defined property with key editPaymentMode and value 'paymentMode'.
        // - Links edit form’s PaymentMode to add form’s ID for restoration.
        // - Restores original ID after validation.
        editPaymentMode: 'paymentMode',
        // Maps PaymentDate field
        // - User-defined property with key editPaymentDate and value 'paymentDate'.
        // - Links edit form’s PaymentDate to add form’s ID for restoration.
        // - Restores original ID after validation.
        editPaymentDate: 'paymentDate',
        // Maps ReceiptNumber field
        // - User-defined property with key editReceiptNumber and value 'receiptNumber'.
        // - Links edit form’s ReceiptNumber to add form’s ID for restoration.
        // - Restores original ID after validation.
        editReceiptNumber: 'receiptNumber',
        // Maps TransactionID field
        // - User-defined property with key editTransactionID and value 'transactionID'.
        // - Links edit form’s TransactionID to add form’s ID for restoration.
        // - Restores original ID after validation.
        editTransactionID: 'transactionID',
        // Maps Status field
        // - User-defined property with key editStatus and value 'status'.
        // - Links edit form’s Status to add form’s ID for restoration.
        // - Restores original ID after validation.
        editStatus: 'status'
    };

    // Iterates through ID mappings
    // - Built-in for...of loop using Object.entries() to process idMappings.
    // - Loops over each editId-addId pair to restore form field IDs.
    // - Reverts fields to original IDs for submission.
    for (const [editId, addId] of Object.entries(idMappings)) {
        // Declares element variable
        // - User-defined constant named element using document.getElementById().
        // - Finds the form field with addId (e.g., bookingID).
        // - Targets the field for ID restoration.
        const element = document.getElementById(addId);
        
        // Checks if element exists
        // - Built-in conditional testing element for truthiness.
        // - Proceeds only if the form field is found after ID mapping.
        // - Prevents errors from missing fields.
        if (element) {
            // Checks for payment date field
            // - Built-in conditional comparing addId to 'paymentDate'.
            // - Identifies if the current field is the payment date input.
            // - Triggers date conversion for submission compatibility.
            if (addId === 'paymentDate') {
                // Converts date value
                // - Built-in assignment using user-defined convertDateFormat.
                // - Updates element.value to YYYY-MM-DD HH:MM:SS format.
                // - Ensures payment date matches database requirements.
                element.value = convertDateFormat(element.value);
            }
            // Restores original ID
            // - Built-in assignment setting element.id.
            // - Changes ID from addId to editId (e.g., bookingID to editBookingID).
            // - Restores edit form ID for payment submission.
            element.id = editId;
        }
    }
}

// Attaches event listener for page load
// - Built-in event listener using document.addEventListener(), with 'DOMContentLoaded' event.
// - Executes async arrow function when edit_payment.html fully loads.
// - Fetches payment data to populate the edit form in admin_payments.html.
document.addEventListener('DOMContentLoaded', async () => {
    // Declares URL parameters variable
    // - User-defined constant named urlParams using new URLSearchParams(), a built-in API.
    // - Extracts query parameters from window.location.search, a built-in property.
    // - Retrieves payment ID from URL (e.g., ?id=123).
    const urlParams = new URLSearchParams(window.location.search);
    
    // Declares payment ID variable
    // - User-defined constant named paymentId using urlParams.get(), a built-in method.
    // - Fetches the 'id' parameter from the URL.
    // - Identifies which payment to fetch for editing.
    const paymentId = urlParams.get('id');

    // Checks if payment ID exists
    // - Built-in conditional testing paymentId for truthiness.
    // - Proceeds with data fetching only if an ID is provided.
    // - Prevents errors from missing or invalid payment IDs.
    if (paymentId) {
        // Starts error handling block
        // - Built-in try-catch block to handle network or data issues.
        // - Ensures robust error handling during payment data fetching.
        try {
            // Declares response variable
            // - User-defined constant named response using fetch(), a built-in async method.
            // - Sends GET request to PHP endpoint with paymentId in query string.
            // - Retrieves payment details from the server.
            const response = await fetch(`../../../php/admin/payments/fetchPaymentById.php?id=${paymentId}`);
            
            // Declares payment data variable
            // - User-defined constant named payment using response.json(), a built-in method.
            // - Parses JSON response into an object with payment details.
            // - Contains fields like PaymentID, BookingID, AmountPaid, etc.
            const payment = await response.json();

            // Sets PaymentID field
            // - Built-in assignment using document.getElementById().value.
            // - Sets editPaymentId input value to payment.PaymentID.
            // - Displays payment ID in the form for reference.
            document.getElementById('editPaymentId').value = payment.PaymentID;
            // Sets BookingID field
            // - Built-in assignment using document.getElementById().value.
            // - Sets editBookingID input value to payment.BookingID.
            // - Displays booking ID associated with the payment.
            document.getElementById('editBookingID').value = payment.BookingID;
            // Sets AmountPaid field
            // - Built-in assignment using document.getElementById().value.
            // - Sets editAmountPaid input value to payment.AmountPaid.
            // - Displays payment amount in the form.
            document.getElementById('editAmountPaid').value = payment.AmountPaid;
            // Sets PaymentMode field
            // - Built-in assignment using document.getElementById().value.
            // - Sets editPaymentMode input value to payment.PaymentMode.
            // - Displays payment method (e.g., cash, card) in the form.
            document.getElementById('editPaymentMode').value = payment.PaymentMode;
            
            // Sets PaymentDate field
            // - Built-in assignment using document.getElementById().value and user-defined convertToValidationFormat.
            // - Converts payment.PaymentDate to DD-MM-YYYY HH:MM:SS format.
            // - Displays payment date in the form for editing.
            document.getElementById('editPaymentDate').value = convertToValidationFormat(payment.PaymentDate);
            
            // Sets ReceiptNumber field
            // - Built-in assignment using document.getElementById().value.
            // - Sets editReceiptNumber input value to payment.ReceiptNumber.
            // - Displays receipt number in the form.
            document.getElementById('editReceiptNumber').value = payment.ReceiptNumber;
            // Sets TransactionID field
            // - Built-in assignment using document.getElementById().value.
            // - Sets editTransactionID input value to payment.TransactionID.
            // - Displays transaction ID in the form.
            document.getElementById('editTransactionID').value = payment.TransactionID;
            // Sets Status field
            // - Built-in assignment using document.getElementById().value.
            // - Sets editStatus input value to payment.Status.
            // - Displays payment status (e.g., completed) in the form.
            document.getElementById('editStatus').value = payment.Status;
        } catch (error) {
            // Shows error message
            // - Built-in alert() method with template literal including error.message.
            // - Notifies user of failure to fetch payment details (e.g., network issue).
            // - Provides specific error for clarity in admin_payments.html.
            alert(`Failed to fetch payment details: ${error.message}`);
            // Logs error
            // - Built-in console.error() method with error object.
            // - Outputs fetch issues to developer tools for debugging.
            // - Helps diagnose server or network problems.
            console.error('Error fetching payment:', error);
        }
    }
});

// Defines function to validate and submit form
// - User-defined function named validateAndSubmitPaymentForm, with one input: event (form submission event).
// - Returns true if validation passes and submission proceeds, false otherwise.
// - Validates and submits payment updates in edit_payment.html.
function validateAndSubmitPaymentForm(event) {
    // Prevents page reload
    // - Built-in method event.preventDefault().
    // - Stops default form submission to avoid page refresh.
    // - Allows async processing of payment form.
    event.preventDefault();

    // Maps form IDs
    // - Built-in function call to user-defined mapIdsForValidation.
    // - Changes edit form IDs to match add form IDs.
    // - Prepares form for validation with formValidation.js.
    mapIdsForValidation();

    // Checks form validity
    // - Built-in conditional using user-defined validatePaymentForm from formValidation.js.
    // - Validates fields like BookingID, AmountPaid, PaymentDate, etc.
    // - Proceeds only if validation passes.
    if (!validatePaymentForm()) {
        // Restores original IDs
        // - Built-in function call to user-defined restoreOriginalIds.
        // - Reverts form IDs to edit form values.
        // - Ensures correct IDs after failed validation.
        restoreOriginalIds();
        // Returns false
        // - Built-in return statement with boolean false.
        // - Stops submission due to validation failure.
        // - Prevents invalid payment data from being sent.
        return false;
    }

    // Restores original IDs
    // - Built-in function call to user-defined restoreOriginalIds.
    // - Reverts form IDs to edit form values after validation.
    // - Prepares form for submission with correct IDs.
    restoreOriginalIds();

    // Submits form data
    // - Built-in function call to user-defined submitPaymentForm.
    // - Initiates async submission of payment data to server.
    // - Sends validated payment details for update.
    submitPaymentForm();
    
    // Returns true
    // - Built-in return statement with boolean true.
    // - Indicates validation passed and submission started.
    // - Signals successful form processing.
    return true;
}

// Defines async function to submit payment data
// - User-defined async function named submitPaymentForm, with no inputs.
// - Sends payment form data as JSON to server and handles response.
// - Updates payment details in the database for admin_payments.html.
async function submitPaymentForm() {
    // Declares form data variable
    // - User-defined constant named formData using new FormData(), a built-in API.
    // - Collects data from form with ID 'editPaymentForm' using document.getElementById().
    // - Captures payment details like amount and transaction ID.
    const formData = new FormData(document.getElementById('editPaymentForm'));

    // Declares payment data object
    // - User-defined constant named paymentData using an object literal.
    // - Structures payment details for JSON submission.
    // - Prepares data for server update.
    const paymentData = {
        // Sets payment ID
        // - User-defined property named paymentId using formData.get(), a built-in method.
        // - Retrieves paymentId value from form (editPaymentId).
        // - Identifies payment record for update.
        paymentId: formData.get('paymentId'),
        // Sets booking ID
        // - User-defined property named bookingID using formData.get().
        // - Retrieves bookingID value from form (editBookingID).
        // - Links payment to associated booking.
        bookingID: formData.get('bookingID'),
        // Sets amount paid
        // - User-defined property named amountPaid using formData.get().
        // - Retrieves amountPaid value from form (editAmountPaid).
        // - Specifies payment amount for update.
        amountPaid: formData.get('amountPaid'),
        // Sets payment mode
        // - User-defined property named paymentMode using formData.get().
        // - Retrieves paymentMode value from form (editPaymentMode).
        // - Specifies payment method (e.g., cash, card).
        paymentMode: formData.get('paymentMode'),
        // Sets payment date
        // - User-defined property named paymentDate using formData.get() and convertDateFormat.
        // - Converts paymentDate to YYYY-MM-DD HH:MM:SS format.
        // - Ensures database-compatible date format.
        paymentDate: convertDateFormat(formData.get('paymentDate')),
        // Sets receipt number
        // - User-defined property named receiptNumber using formData.get().
        // - Retrieves receiptNumber value from form (editReceiptNumber).
        // - Specifies receipt number for payment record.
        receiptNumber: formData.get('receiptNumber'),
        // Sets transaction ID
        // - User-defined property named transactionID using formData.get().
        // - Retrieves transactionID value from form (editTransactionID).
        // - Specifies transaction ID for payment record.
        transactionID: formData.get('transactionID'),
        // Sets status
        // - User-defined property named status using formData.get().
        // - Retrieves status value from form (editStatus).
        // - Specifies payment status (e.g., completed).
        status: formData.get('status')
    };

    // Starts error handling block
    // - Built-in try-catch block to handle network or server issues.
    // - Ensures robust error handling during payment submission.
    try {
        // Declares response variable
        // - User-defined constant named response using fetch() with POST method.
        // - Sends paymentData to PHP endpoint as JSON string.
        // - Awaits server response for payment update.
        const response = await fetch('../../../php/admin/payments/updatePayment.php', {
            // Sets request method
            // - User-defined property named method with value 'POST'.
            // - Specifies POST request type for data submission.
            // - Informs server to expect new data.
            method: 'POST',
            // Defines headers
            // - User-defined property named headers using an object literal.
            // - Sets Content-Type for JSON data submission.
            // - Prepares server for JSON payload.
            headers: {
                // Sets Content-Type header
                // - User-defined property named 'Content-Type' with value 'application/json'.
                // - Indicates data is in JSON format.
                // - Ensures server parses payload correctly.
                'Content-Type': 'application/json'
            },
            // Sets request body
            // - User-defined property named body using JSON.stringify(), a built-in method.
            // - Converts paymentData object to JSON string.
            // - Sends payment details to server.
            body: JSON.stringify(paymentData)
        });

        // Declares content type variable
        // - User-defined constant named contentType using response.headers.get(), a built-in method.
        // - Retrieves Content-Type header from server response.
        // - Checks if response is in JSON format.
        const contentType = response.headers.get('Content-Type');
        
        // Checks if response is JSON
        // - Built-in conditional testing contentType and includes() method.
        // - Verifies Content-Type includes 'application/json'.
        // - Ensures response can be parsed as JSON.
        if (!contentType || !contentType.includes('application/json')) {
            // Declares response text variable
            // - User-defined constant named responseText using response.text(), a built-in method.
            // - Retrieves raw response body as text.
            // - Prepares for error reporting.
            const responseText = await response.text();
            // Throws error
            // - Built-in throw statement with new Error(), a built-in constructor.
            // - Creates error with responseText for debugging.
            // - Stops execution due to non-JSON response.
            throw new Error(`Server returned non-JSON response: ${responseText}`);
        }

        // Declares result variable
        // - User-defined constant named result using response.json().
        // - Parses JSON response into object with status and message.
        // - Indicates success or failure of payment update.
        const result = await response.json();

        // Checks update status
        // - Built-in conditional testing result.status for 'success'.
        // - Determines if payment update succeeded.
        // - Decides next action based on server reply.
        if (result.status === 'success') {
            // Shows success message
            // - Built-in alert() method with success message.
            // - Notifies user that payment was updated successfully.
            // - Confirms update in admin_payments.html.
            alert('Payment updated successfully');
            // Redirects to payments page
            // - Built-in assignment to window.location.href, a built-in property.
            // - Navigates to admin_payments.html after successful update.
            // - Returns user to payment list page.
            window.location.href = 'admin_payments.html';
        } else {
            // Shows error message
            // - Built-in alert() method with template literal including result.message.
            // - Notifies user of update failure with specific server message.
            // - Informs user of issues like invalid booking ID.
            alert(`Failed to update payment: ${result.message}`);
        }
    } catch (error) {
        // Shows error message
        // - Built-in alert() method with template literal including error.message.
        // - Notifies user of submission failure (e.g., network or JSON error).
        // - Provides specific error for clarity.
        alert(`Failed to update payment: ${error.message}`);
        // Logs error
        // - Built-in console.error() method with error object.
        // - Outputs submission issues to developer tools for debugging.
        // - Helps diagnose server or network problems.
        console.error('Error updating payment:', error);
    }
}