// File: edit_schedule.js
// Handles fetching and updating schedule details for the edit_schedule.html page in the bus booking system.

// Defines function to convert date format
// - User-defined function named convertDateFormat, with one input: dateStr (string, date to convert).
// - Returns reformatted date as YYYY-MM-DD HH:MM:SS or original string if invalid.
// - Converts schedule dates from DD-MM-YYYY HH:MM:SS to database-compatible format.
function convertDateFormat(dateStr) {
    // Checks date string length
    // - Built-in conditional using dateStr.length property to test if string is at least 19 characters.
    // - Ensures dateStr matches DD-MM-YYYY HH:MM:SS format (e.g., 31-12-2025 14:30:00).
    // - Proceeds only if length is sufficient.
    if (dateStr.length < 19) {
        // Returns original string
        // - Built-in return statement with dateStr.
        // - Preserves input if format is too short to avoid invalid conversion.
        // - Indicates an error in date format for schedule submission.
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
    // - Stores day for reformatting schedule date.
    let dayStr = dateStr.substring(0, 2);
    // Declares month string variable
    // - User-defined constant named monthStr using substring().
    // - Extracts month from positions 3-5 (e.g., "12" from "31-12-2025").
    // - Stores month for reformatting schedule date.
    let monthStr = dateStr.substring(3, 5);
    // Declares year string variable
    // - User-defined constant named yearStr using substring().
    // - Extracts year from positions 6-10 (e.g., "2025" from "31-12-2025").
    // - Stores year for reformatting schedule date.
    let yearStr = dateStr.substring(6, 10);
    // Declares time string variable
    // - User-defined constant named timeStr using substring().
    // - Extracts time from positions 11-19 (e.g., "14:30:00" from "31-12-2025 14:30:00").
    // - Stores time for reformatting schedule date.
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
        // - Avoids invalid date conversion for schedule submission.
        return dateStr;
    }

    // Returns reformatted date
    // - Built-in return statement using template literal with yearStr, monthStr, dayStr, timeStr.
    // - Combines components as YYYY-MM-DD HH:MM:SS (e.g., "2025-12-31 14:30:00").
    // - Provides database-compatible date format for schedule records.
    return `${yearStr}-${monthStr}-${dayStr} ${timeStr}`;
}

// Defines function to convert date for validation
// - User-defined function named convertToValidationFormat, with one input: dateStr (string, date to convert).
// - Returns reformatted date as DD-MM-YYYY HH:MM:SS or original string if invalid.
// - Converts schedule dates from YYYY-MM-DD HH:MM:SS to form validation format.
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
    // - Stores year for reformatting schedule date.
    let yearStr = dateStr.substring(0, 4);
    // Declares month string variable
    // - User-defined constant named monthStr using substring().
    // - Extracts month from positions 5-7 (e.g., "12" from "2025-12-31").
    // - Stores month for reformatting schedule date.
    let monthStr = dateStr.substring(5, 7);
    // Declares day string variable
    // - User-defined constant named dayStr using substring().
    // - Extracts day from positions 8-10 (e.g., "31" from "2025-12-31").
    // - Stores day for reformatting schedule date.
    let dayStr = dateStr.substring(8, 10);
    // Declares time string variable
    // - User-defined constant named timeStr using substring().
    // - Extracts time from positions 11-19 (e.g., "14:30:00" from "2025-12-31 14:30:00").
    // - Stores time for reformatting schedule date.
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
    // - Provides format compatible with validateScheduleForm in formValidation.js.
    return `${dayStr}-${monthStr}-${yearStr} ${timeStr}`;
}

// Defines function to map form IDs
// - User-defined function named mapIdsForValidation, with no inputs.
// - Changes edit form field IDs to match add form IDs for validation.
// - Enables reuse of validateScheduleForm for editing schedules in admin_schedules.html.
function mapIdsForValidation() {
    // Declares ID mappings
    // - User-defined constant named idMappings using an object literal.
    // - Maps edit form IDs (e.g., editBusID) to add form IDs (e.g., busID).
    // - Defines fields for validation compatibility with formValidation.js.
    const idMappings = {
        // Maps BusID field
        // - User-defined property with key editBusID and value 'busID'.
        // - Links edit form’s BusID to add form’s ID for validation.
        // - Ensures bus ID validation in schedule editing.
        editBusID: 'busID',
        // Maps RouteID field
        // - User-defined property with key editRouteID and value 'routeID'.
        // - Links edit form’s RouteID to add form’s ID for validation.
        // - Ensures route ID validation in schedule editing.
        editRouteID: 'routeID',
        // Maps DepartureTime field
        // - User-defined property with key editDepartureTime and value 'departureTime'.
        // - Links edit form’s DepartureTime to add form’s ID for validation.
        // - Ensures departure time validation in schedule editing.
        editDepartureTime: 'departureTime',
        // Maps ArrivalTime field
        // - User-defined property with key editArrivalTime and value 'arrivalTime'.
        // - Links edit form’s ArrivalTime to add form’s ID for validation.
        // - Ensures arrival time validation in schedule editing.
        editArrivalTime: 'arrivalTime',
        // Maps Cost field
        // - User-defined property with key editCost and value 'cost'.
        // - Links edit form’s Cost to add form’s ID for validation.
        // - Ensures cost validation in schedule editing.
        editCost: 'cost',
        // Maps DriverID field
        // - User-defined property with key editDriverID and value 'driverID'.
        // - Links edit form’s DriverID to add form’s ID for validation.
        // - Ensures driver ID validation in schedule editing.
        editDriverID: 'driverID',
        // Maps CodriverID field
        // - User-defined property with key editCodriverID and value 'codriverID'.
        // - Links edit form’s CodriverID to add form’s ID for validation.
        // - Ensures co-driver ID validation in schedule editing.
        editCodriverID: 'codriverID'
    };

    // Iterates through ID mappings
    // - Built-in for...of loop using Object.entries(), a built-in method, to process idMappings.
    // - Loops over each editId-addId pair to update form field IDs.
    // - Prepares fields for validation in schedule form.
    for (const [editId, addId] of Object.entries(idMappings)) {
        // Declares element variable
        // - User-defined constant named element using document.getElementById(), a built-in method.
        // - Finds the form field with editId (e.g., editBusID).
        // - Targets the field for ID modification.
        const element = document.getElementById(editId);
        
        // Checks if element exists
        // - Built-in conditional testing element for truthiness.
        // - Proceeds only if the form field is found in edit_schedule.html.
        // - Prevents errors from missing fields.
        if (element) {
            // Checks for date fields
            // - Built-in conditional using logical OR to compare editId to 'editDepartureTime' or 'editArrivalTime'.
            // - Identifies if the current field is a date input (departure or arrival time).
            // - Triggers date conversion for validation compatibility.
            if (editId === 'editDepartureTime' || editId === 'editArrivalTime') {
                // Converts date value
                // - Built-in assignment using user-defined convertToValidationFormat.
                // - Updates element.value to DD-MM-YYYY HH:MM:SS format.
                // - Ensures date fields match validateScheduleForm requirements.
                element.value = convertToValidationFormat(element.value);
            }
            // Updates field ID
            // - Built-in assignment setting element.id, a built-in property.
            // - Changes ID from editId to addId (e.g., editBusID to busID).
            // - Aligns field with add form validation in formValidation.js.
            element.id = addId;
        }
    }
}

// Defines function to restore form IDs
// - User-defined function named restoreOriginalIds, with no inputs.
// - Reverts form field IDs to original edit form values after validation.
// - Ensures correct IDs for schedule submission in admin_schedules.html.
function restoreOriginalIds() {
    // Declares ID mappings
    // - User-defined constant named idMappings using an object literal.
    // - Maps edit form IDs to add form IDs for restoration.
    // - Reuses mapping from mapIdsForValidation for consistency.
    const idMappings = {
        // Maps BusID field
        // - User-defined property with key editBusID and value 'busID'.
        // - Links edit form’s BusID to add form’s ID for restoration.
        // - Restores original ID after validation.
        editBusID: 'busID',
        // Maps RouteID field
        // - User-defined property with key editRouteID and value 'routeID'.
        // - Links edit form’s RouteID to add form’s ID for restoration.
        // - Restores original ID after validation.
        editRouteID: 'routeID',
        // Maps DepartureTime field
        // - User-defined property with key editDepartureTime and value 'departureTime'.
        // - Links edit form’s DepartureTime to add form’s ID for restoration.
        // - Restores original ID after validation.
        editDepartureTime: 'departureTime',
        // Maps ArrivalTime field
        // - User-defined property with key editArrivalTime and value 'arrivalTime'.
        // - Links edit form’s ArrivalTime to add form’s ID for restoration.
        // - Restores original ID after validation.
        editArrivalTime: 'arrivalTime',
        // Maps Cost field
        // - User-defined property with key editCost and value 'cost'.
        // - Links edit form’s Cost to add form’s ID for restoration.
        // - Restores original ID after validation.
        editCost: 'cost',
        // Maps DriverID field
        // - User-defined property with key editDriverID and value 'driverID'.
        // - Links edit form’s DriverID to add form’s ID for restoration.
        // - Restores original ID after validation.
        editDriverID: 'driverID',
        // Maps CodriverID field
        // - User-defined property with key editCodriverID and value 'codriverID'.
        // - Links edit form’s CodriverID to add form’s ID for restoration.
        // - Restores original ID after validation.
        editCodriverID: 'codriverID'
    };

    // Iterates through ID mappings
    // - Built-in for...of loop using Object.entries() to process idMappings.
    // - Loops over each editId-addId pair to restore form field IDs.
    // - Reverts fields to original IDs for submission.
    for (const [editId, addId] of Object.entries(idMappings)) {
        // Declares element variable
        // - User-defined constant named element using document.getElementById().
        // - Finds the form field with addId (e.g., busID).
        // - Targets the field for ID restoration.
        const element = document.getElementById(addId);
        
        // Checks if element exists
        // - Built-in conditional testing element for truthiness.
        // - Proceeds only if the form field is found after ID mapping.
        // - Prevents errors from missing fields.
        if (element) {
            // Checks for date fields
            // - Built-in conditional using logical OR to compare addId to 'departureTime' or 'arrivalTime'.
            // - Identifies if the current field is a date input (departure or arrival time).
            // - Triggers date conversion for submission compatibility.
            if (addId === 'departureTime' || addId === 'arrivalTime') {
                // Converts date value
                // - Built-in assignment using user-defined convertDateFormat.
                // - Updates element.value to YYYY-MM-DD HH:MM:SS format.
                // - Ensures date fields match database requirements.
                element.value = convertDateFormat(element.value);
            }
            // Restores original ID
            // - Built-in assignment setting element.id.
            // - Changes ID from addId to editId (e.g., busID to editBusID).
            // - Restores edit form ID for schedule submission.
            element.id = editId;
        }
    }
}

// Defines async function to fetch schedule details
// - User-defined async function named fetchScheduleDetails, with no inputs.
// - Fetches schedule data from server and populates edit form.
// - Loads schedule details for editing in edit_schedule.html.
async function fetchScheduleDetails() {
    // Declares URL parameters variable
    // - User-defined constant named urlParams using new URLSearchParams(), a built-in API.
    // - Extracts query parameters from window.location.search, a built-in property.
    // - Retrieves schedule ID from URL (e.g., ?id=123).
    const urlParams = new URLSearchParams(window.location.search);
    
    // Declares schedule ID variable
    // - User-defined constant named scheduleId using urlParams.get(), a built-in method.
    // - Fetches the 'id' parameter from the URL.
    // - Identifies which schedule to fetch for editing.
    const scheduleId = urlParams.get('id');

    // Checks if schedule ID is missing
    // - Built-in conditional testing scheduleId for falsiness.
    // - Triggers error handling if no ID is provided.
    // - Prevents fetching with invalid or missing ID.
    if (!scheduleId) {
        // Shows error message
        // - Built-in alert() method with error message.
        // - Notifies user that no schedule ID was provided.
        // - Informs user of invalid access to edit_schedule.html.
        alert('Schedule ID not provided');
        // Redirects to schedules page
        // - Built-in assignment to window.location.href, a built-in property.
        // - Navigates to admin_schedules.html.
        // - Returns user to main schedules page.
        window.location.href = 'admin_schedules.html';
        // Stops function execution
        // - Built-in return statement.
        // - Halts further processing due to missing ID.
        // - Prevents unnecessary server requests.
        return;
    }

    // Starts error handling block
    // - Built-in try-catch block to handle network or data issues.
    // - Ensures robust error handling during schedule data fetching.
    try {
        // Declares response variable
        // - User-defined constant named response using fetch(), a built-in async method.
        // - Sends GET request to PHP endpoint with scheduleId in query string.
        // - Retrieves schedule details from the server.
        const response = await fetch(`../../../php/admin/getSchedule.php?id=${scheduleId}`);
        
        // Declares schedule data variable
        // - User-defined constant named schedule using response.json(), a built-in method.
        // - Parses JSON response into an object with schedule details.
        // - Contains fields like ScheduleID, BusID, RouteID, etc.
        const schedule = await response.json();

        // Checks if schedule exists
        // - Built-in conditional testing schedule.ScheduleID for falsiness.
        // - Verifies if the schedule was found in the database.
        // - Triggers error handling if schedule is not found.
        if (!schedule.ScheduleID) {
            // Shows error message
            // - Built-in alert() method with error message.
            // - Notifies user that the schedule was not found.
            // - Informs user of invalid schedule ID.
            alert('Schedule not found');
            // Redirects to schedules page
            // - Built-in assignment to window.location.href.
            // - Navigates to admin_schedules.html.
            // - Returns user to main schedules page.
            window.location.href = 'admin_schedules.html';
            // Stops function execution
            // - Built-in return statement.
            // - Halts further processing due to missing schedule.
            // - Prevents form population with invalid data.
            return;
        }

        // Sets ScheduleID field
        // - Built-in assignment using document.getElementById().value.
        // - Sets editScheduleId input value to schedule.ScheduleID.
        // - Displays schedule ID in the form for reference.
        document.getElementById('editScheduleId').value = schedule.ScheduleID;
        // Sets BusID field
        // - Built-in assignment using document.getElementById().value.
        // - Sets editBusID input value to schedule.BusID.
        // - Displays bus ID associated with the schedule.
        document.getElementById('editBusID').value = schedule.BusID;
        // Sets RouteID field
        // - Built-in assignment using document.getElementById().value.
        // - Sets editRouteID input value to schedule.RouteID.
        // - Displays route ID associated with the schedule.
        document.getElementById('editRouteID').value = schedule.RouteID;
        // Sets DepartureTime field
        // - Built-in assignment using document.getElementById().value.
        // - Sets editDepartureTime input value to schedule.DepartureTime.
        // - Displays departure time in the form for editing.
        document.getElementById('editDepartureTime').value = schedule.DepartureTime;
        // Sets ArrivalTime field
        // - Built-in assignment using document.getElementById().value.
        // - Sets editArrivalTime input value to schedule.ArrivalTime.
        // - Displays arrival time in the form for editing.
        document.getElementById('editArrivalTime').value = schedule.ArrivalTime;
        // Sets Cost field
        // - Built-in assignment using document.getElementById().value.
        // - Sets editCost input value to schedule.Cost.
        // - Displays schedule cost in the form for editing.
        document.getElementById('editCost').value = schedule.Cost;
        // Sets DriverID field
        // - Built-in assignment using document.getElementById().value.
        // - Sets editDriverID input value to schedule.DriverID.
        // - Displays driver ID associated with the schedule.
        document.getElementById('editDriverID').value = schedule.DriverID;
        // Sets CodriverID field
        // - Built-in assignment using document.getElementById().value.
        // - Sets editCodriverID input value to schedule.CodriverID.
        // - Displays co-driver ID associated with the schedule.
        document.getElementById('editCodriverID').value = schedule.CodriverID;
    } catch (error) {
        // Shows error message
        // - Built-in alert() method with template literal including error.message.
        // - Notifies user of failure to fetch schedule details (e.g., network issue).
        // - Provides specific error for clarity in admin_schedules.html.
        alert(`Failed to fetch schedule details: ${error.message}`);
        // Logs error
        // - Built-in console.error() method with error object.
        // - Outputs fetch issues to developer tools for debugging.
        // - Helps diagnose server or network problems.
        console.error('Error fetching schedule details:', error);
    }
}

// Defines async function to validate and submit form
// - User-defined async function named validateAndSubmitScheduleForm, with one input: event (form submission event).
// - Validates and submits schedule updates, redirects or shows errors.
// - Processes schedule form submission in edit_schedule.html.
async function validateAndSubmitScheduleForm(event) {
    // Prevents page reload
    // - Built-in method event.preventDefault().
    // - Stops default form submission to avoid page refresh.
    // - Allows async processing of schedule form.
    event.preventDefault();

    // Maps form IDs
    // - Built-in function call to user-defined mapIdsForValidation.
    // - Changes edit form IDs to match add form IDs.
    // - Prepares form for validation with formValidation.js.
    mapIdsForValidation();

    // Checks form validity
    // - Built-in conditional using user-defined validateScheduleForm from formValidation.js.
    // - Validates fields like BusID, RouteID, DepartureTime, etc.
    // - Proceeds only if validation passes.
    if (!validateScheduleForm()) {
        // Restores original IDs
        // - Built-in function call to user-defined restoreOriginalIds.
        // - Reverts form IDs to edit form values.
        // - Ensures correct IDs after failed validation.
        restoreOriginalIds();
        // Stops function execution
        // - Built-in return statement.
        // - Halts submission due to validation failure.
        // - Prevents invalid schedule data from being sent.
        return;
    }

    // Restores original IDs
    // - Built-in function call to user-defined restoreOriginalIds.
    // - Reverts form IDs to edit form values after validation.
    // - Prepares form for submission with correct IDs.
    restoreOriginalIds();

    // Declares form data variable
    // - User-defined constant named formData using new FormData(), a built-in API.
    // - Collects data from form using event.target, a built-in property.
    // - Captures schedule details like bus ID and cost.
    const formData = new FormData(event.target);
    
    // Declares schedule data object
    // - User-defined constant named scheduleData using an object literal.
    // - Structures schedule details for JSON submission.
    // - Prepares data for server update.
    const scheduleData = {
        // Sets schedule ID
        // - User-defined property named scheduleId using formData.get(), a built-in method.
        // - Retrieves scheduleId value from form (editScheduleId).
        // - Identifies schedule record for update.
        scheduleId: formData.get('scheduleId'),
        // Sets bus ID
        // - User-defined property named busID using formData.get().
        // - Retrieves busID value from form (editBusID).
        // - Specifies bus for the schedule.
        busID: formData.get('busID'),
        // Sets route ID
        // - User-defined property named routeID using formData.get().
        // - Retrieves routeID value from form (editRouteID).
        // - Specifies route for the schedule.
        routeID: formData.get('routeID'),
        // Sets departure time
        // - User-defined property named departureTime using formData.get() and convertDateFormat.
        // - Converts departureTime to YYYY-MM-DD HH:MM:SS format.
        // - Ensures database-compatible departure time.
        departureTime: convertDateFormat(formData.get('departureTime')),
        // Sets arrival time
        // - User-defined property named arrivalTime using formData.get() and convertDateFormat.
        // - Converts arrivalTime to YYYY-MM-DD HH:MM:SS format.
        // - Ensures database-compatible arrival time.
        arrivalTime: convertDateFormat(formData.get('arrivalTime')),
        // Sets cost
        // - User-defined property named cost using formData.get().
        // - Retrieves cost value from form (editCost).
        // - Specifies schedule cost for update.
        cost: formData.get('cost'),
        // Sets driver ID
        // - User-defined property named driverID using formData.get().
        // - Retrieves driverID value from form (editDriverID).
        // - Specifies driver for the schedule.
        driverID: formData.get('driverID'),
        // Sets co-driver ID
        // - User-defined property named codriverID using formData.get().
        // - Retrieves codriverID value from form (editCodriverID).
        // - Specifies co-driver for the schedule.
        codriverID: formData.get('codriverID')
    };

    // Starts error handling block
    // - Built-in try-catch block to handle network or server issues.
    // - Ensures robust error handling during schedule submission.
    try {
        // Declares response variable
        // - User-defined constant named response using fetch() with POST method.
        // - Sends scheduleData to PHP endpoint as JSON string.
        // - Awaits server response for schedule update.
        const response = await fetch('../../../php/admin/updateSchedule.php', {
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
            // - Converts scheduleData object to JSON string.
            // - Sends schedule details to server.
            body: JSON.stringify(scheduleData)
        });

        // Declares result variable
        // - User-defined constant named result using response.json().
        // - Parses JSON response into object with status and message.
        // - Indicates success or failure of schedule update.
        const result = await response.json();

        // Checks update status
        // - Built-in conditional testing result.status for 'success'.
        // - Determines if schedule update succeeded.
        // - Decides next action based on server reply.
        if (result.status === 'success') {
            // Shows success message
            // - Built-in alert() method with success message.
            // - Notifies user that schedule was updated successfully.
            // - Confirms update in admin_schedules.html.
            alert('Schedule updated successfully');
            // Redirects to schedules page
            // - Built-in assignment to window.location.href.
            // - Navigates to admin_schedules.html after successful update.
            // - Returns user to main schedules page.
            window.location.href = 'admin_schedules.html';
        } else {
            // Shows error message
            // - Built-in alert() method with template literal including result.message.
            // - Notifies user of update failure with specific server message.
            // - Informs user of issues like invalid bus ID.
            alert(`Failed to update schedule: ${result.message}`);
        }
    } catch (error) {
        // Shows error message
        // - Built-in alert() method with template literal including error.message.
        // - Notifies user of submission failure due to network or server error.
        // - Provides specific error for clarity.
        alert(`Failed to update schedule due to network error: ${error.message}`);
        // Logs error
        // - Built-in console.error() method with error object.
        // - Outputs submission issues to developer tools for debugging.
        // - Helps diagnose server or network problems.
        console.error('Error updating schedule:', error);
    }
}

// Attaches event listener for page load
// - Built-in event listener using document.addEventListener(), with 'DOMContentLoaded' event.
// - Executes user-defined fetchScheduleDetails when edit_schedule.html fully loads.
// - Fetches and displays schedule details in the edit form.
document.addEventListener('DOMContentLoaded', fetchScheduleDetails);
