// File: formValidation.js
// Contains utility functions to validate form inputs for various data entry forms

// Debugging statement
// - A console log statement.
// - Outputs a string to the console.
// - Confirms that the script has loaded successfully.
console.log("formValidation.js loaded");

// Function to check if a value is empty
// - A user-defined function named isEmpty.
// - Takes two parameters: value (string to check), fieldName (string for error messages).
// - Returns true if the value is empty, false otherwise.
function isEmpty(value, fieldName) {
    // Conditional block to check for empty value
    // - An if statement checking if value.trim() is an empty string.
    // - Uses trim() to remove leading/trailing whitespace.
    // - Alerts the user and returns true if empty.
    if (value.trim() === "") {
        // Alert statement
        // - A function call to alert().
        // - Displays a message with the fieldName.
        // - Notifies the user that the field is required.
        alert(`${fieldName} must be filled out`);
        
        // Return statement
        // - Returns a boolean value (true).
        // - Indicates the field is empty.
        return true;
    }
    
    // Return statement
    // - Returns a boolean value (false).
    // - Indicates the field is not empty.
    return false;
}

// Function to check if a value is a valid date in DD-MM-YYYY format
// - A user-defined function named isValidDate.
// - Takes two parameters: value (date string to validate), fieldName (string for error messages).
// - Returns a Date object if valid, false otherwise.
function isValidDate(value, fieldName) {
    // Conditional block to check for empty date
    // - An if statement checking if value.length is 0.
    // - Tests if the date string is empty.
    // - Alerts the user and returns false if empty.
    if (value.length === 0) {
        // Alert statement
        // - A function call to alert().
        // - Displays a message with the fieldName.
        // - Notifies the user that the date is required and must be in DD-MM-YYYY format.
        alert(`${fieldName} must be entered and of the format (DD-MM-YYYY)`);
        
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates the date is invalid due to being empty.
        return false;
    }

    // Conditional block to check date length
    // - An if statement checking if value.length is not 10.
    // - Ensures the date string has exactly 10 characters (DD-MM-YYYY).
    // - Alerts the user and returns false if incorrect length.
    if (value.length !== 10) {
        // Alert statement
        // - A function call to alert().
        // - Displays a message with the fieldName.
        // - Notifies the user that the date must be in DD-MM-YYYY format.
        alert(`${fieldName} must be of the format (DD-MM-YYYY)`);
        
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates the date is invalid due to incorrect length.
        return false;
    }

    // Conditional block to check hyphen positions
    // - An if statement checking if characters at positions 2 and 5 are not hyphens.
    // - Ensures the date string follows DD-MM-YYYY structure.
    // - Alerts the user and returns false if hyphens are missing.
    if (value[2] !== '-' || value[5] !== '-') {
        // Alert statement
        // - A function call to alert().
        // - Displays a message with the fieldName.
        // - Notifies the user that the date must be in DD-MM-YYYY format.
        alert(`${fieldName} must be of the format (DD-MM-YYYY)`);
        
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates the date is invalid due to missing hyphens.
        return false;
    }

    // Local variables to extract date components
    // - Constants for day, month, and year strings.
    // - Uses substring() to parse DD-MM-YYYY.
    // - Stores day (positions 0-2), month (positions 3-5), and year (positions 6-10).
    let dayStr = value.substring(0, 2);
    let monthStr = value.substring(3, 5);
    let yearStr = value.substring(6, 10);

    // Conditional block to check component lengths
    // - An if statement checking lengths of dayStr, monthStr, and yearStr.
    // - Ensures components match DD-MM-YYYY (2-2-4 characters).
    // - Alerts the user and returns false if lengths are incorrect.
    if (dayStr.length !== 2 || monthStr.length !== 2 || yearStr.length !== 4) {
        // Alert statement
        // - A function call to alert().
        // - Displays a message with the fieldName.
        // - Notifies the user that the date must be in DD-MM-YYYY format.
        alert(`${fieldName} must be of the format (DD-MM-YYYY)`);
        
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates the date is invalid due to incorrect component lengths.
        return false;
    }

    // Local variables to convert strings to numbers
    // - Constants for day, month, and year as integers.
    // - Uses parseInt() with base 10 to convert strings.
    // - Represents numerical values of date components.
    let day = parseInt(dayStr, 10);
    let month = parseInt(monthStr, 10);
    let year = parseInt(yearStr, 10);

    // Conditional block to check if components are numbers
    // - An if statement checking if day, month, or year is NaN.
    // - Ensures all components are valid numbers.
    // - Alerts the user and returns false if any component is not a number.
    if (isNaN(day) || isNaN(month) || isNaN(year)) {
        // Alert statement
        // - A function call to alert().
        // - Displays a message with the fieldName.
        // - Notifies the user that date components must be numbers in DD-MM-YYYY format.
        alert(`${fieldName} components must be numbers and of the format (DD-MM-YYYY)`);
        
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates the date is invalid due to non-numeric components.
        return false;
    }

    // Conditional block for basic range checks
    // - An if statement checking ranges for day, month, and year.
    // - Ensures day is 1-31, month is 1-12, year is 1900-2099.
    // - Alerts the user and returns false if ranges are invalid.
    if (day < 1 || day > 31 || month < 1 || month > 12 || year < 1900 || year > 2099) {
        // Alert statement
        // - A function call to alert().
        // - Displays a message with the fieldName.
        // - Notifies the user that the day, month, or year is invalid.
        alert(`${fieldName} contains invalid day, month, or year`);
        
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates the date is invalid due to out-of-range values.
        return false;
    }

    // Conditional block to check days in 30-day months
    // - An if statement checking months with 30 days (April, June, September, November).
    // - Ensures day is not greater than 30 for these months.
    // - Alerts the user and returns false if day is too high.
    if ((month === 4 || month === 6 || month === 9 || month === 11) && day > 30) {
        // Alert statement
        // - A function call to alert().
        // - Displays a message with the fieldName.
        // - Notifies the user that the month has too many days.
        alert(`${fieldName} has too many days for the specified month`);
        
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates the date is invalid due to excessive days.
        return false;
    }

    // Conditional block to check February days
    // - An if statement checking if the month is February (2).
    // - Validates day count based on leap year rules.
    // - Alerts the user and returns false if day is too high.
    if (month === 2) {
        // Local variable to determine leap year
        // - A constant using modulo operations.
        // - Checks if year is divisible by 4 and not 100, or divisible by 400.
        // - Indicates if the year is a leap year (true/false).
        let isLeapYear = (year % 4 === 0 && year % 100 !== 0) || (year % 400 === 0);
        
        // Conditional block to check February days
        // - An if statement comparing day to 29 (leap year) or 28 (non-leap year).
        // - Ensures day does not exceed the valid count for February.
        // - Alerts the user and returns false if day is too high.
        if (day > (isLeapYear ? 29 : 28)) {
            // Alert statement
            // - A function call to alert().
            // - Displays a message with the fieldName.
            // - Notifies the user that February has too many days.
            alert(`${fieldName} has too many days for February`);
            
            // Return statement
            // - Returns a boolean value (false).
            // - Indicates the date is invalid due to excessive days in February.
            return false;
        }
    }

    // Local variable to create Date object
    // - A constant using the Date API.
    // - Created with new Date(year, month - 1, day).
    // - Represents the entered date (month is 0-indexed).
    let givenDate = new Date(year, month - 1, day);

    // Conditional block to validate date logic
    // - An if statement checking if the Date object matches input values.
    // - Compares getDate(), getMonth() + 1, and getFullYear() to day, month, and year.
    // - Alerts the user and returns false if the date is invalid (e.g., 31-04-2025).
    if (givenDate.getDate() !== day || givenDate.getMonth() + 1 !== month || givenDate.getFullYear() !== year) {
        // Alert statement
        // - A function call to alert().
        // - Displays a message with the fieldName.
        // - Notifies the user that the date is not valid.
        alert(`${fieldName} is not a valid date`);
        
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates the date is invalid due to logical inconsistency.
        return false;
    }

    // Return statement
    // - Returns the givenDate (Date object).
    // - Indicates the date is valid and can be used for further checks.
    return givenDate;
}

// Function to restrict dates to past or present
// - A user-defined function named restrictFutureDate.
// - Takes two parameters: givenDate (Date object), fieldName (string for error messages).
// - Returns true if the date is not in the future, false otherwise.
function restrictFutureDate(givenDate, fieldName) {
    // Local variable to hold today’s date
    // - A constant using the Date API.
    // - Created with new Date().
    // - Represents the current date for comparison.
    let today = new Date();
    
    // Normalize today’s date
    // - Method call on today using setHours().
    // - Sets hours, minutes, seconds, and milliseconds to 0.
    // - Ensures comparison is date-only, ignoring time.
    today.setHours(0, 0, 0, 0);

    // Conditional block to check for future date
    // - An if statement comparing givenDate to today.
    // - Checks if givenDate is greater than today.
    // - Alerts the user and returns false if the date is in the future.
    if (givenDate > today) {
        // Alert statement
        // - A function call to alert().
        // - Displays a message with the fieldName.
        // - Notifies the user that the date cannot be in the future.
        alert(`Incorrect Date: ${fieldName} cannot be greater than today`);
        
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates the date is invalid due to being in the future.
        return false;
    }
    
    // Return statement
    // - Returns a boolean value (true).
    // - Indicates the date is valid (not in the future).
    return true;
}

// Function to check if a value is a positive number
// - A user-defined function named isPositiveNumber.
// - Takes two parameters: value (value to check), fieldName (string for error messages).
// - Returns true if the value is a positive number, false otherwise.
function isPositiveNumber(value, fieldName) {
    // Conditional block to check for valid number
    // - An if statement checking if value is NaN or less than 0.
    // - Uses isNaN() to check if value is not a number.
    // - Alerts the user and returns false if invalid.
    if (isNaN(value) || value < 0) {
        // Alert statement
        // - A function call to alert().
        // - Displays a message with the fieldName.
        // - Notifies the user that the value must be a positive number.
        alert(`${fieldName} must be a positive number`);
        
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates the value is not a positive number.
        return false;
    }
    
    // Return statement
    // - Returns a boolean value (true).
    // - Indicates the value is a positive number.
    return true;
}

// Function to check if a value is a valid email address
// - A user-defined function named isEmail.
// - Takes one parameter: value (string to check).
// - Returns true if the value is a valid email, false otherwise.
function isEmail(value) {
    // Conditional block to check email format
    // - An if statement checking for empty string, missing @, or missing dot.
    // - Uses length and indexOf() to perform basic email validation.
    // - Alerts the user and returns false if invalid.
    if (value.length == 0 || value.indexOf("@") == -1 || value.indexOf(".") == -1) {
        // Alert statement
        // - A function call to alert().
        // - Displays a generic message.
        // - Notifies the user that the email is invalid.
        alert("Please enter a valid email address");
        
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates the email is invalid.
        return false;
    }
    
    // Return statement
    // - Returns a boolean value (true).
    // - Indicates the email is valid.
    return true;
}

// Function to check if a value is a valid phone number
// - A user-defined function named isPhoneNumber.
// - Takes one parameter: value (string to check).
// - Returns true if the value is a 12-digit phone number, false otherwise.
function isPhoneNumber(value) {
    // Conditional block to check phone number format
    // - An if statement checking if value is not 12 digits or not a number.
    // - Uses length and isNaN() to validate.
    // - Alerts the user and returns false if invalid.
    if (value.length != 12 || isNaN(value)) {
        // Alert statement
        // - A function call to alert().
        // - Displays a message with format example.
        // - Notifies the user that the phone number must be 12 digits.
        alert("Phone Number must contain exactly 12 digits (e.g., 254719202363)");
        
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates the phone number is invalid.
        return false;
    }
    
    // Return statement
    // - Returns a boolean value (true).
    // - Indicates the phone number is valid.
    return true;
}

// Function to check if a value is a valid datetime in DD-MM-YYYY HH:MM:SS format
// - A user-defined function named isValidDateTime.
// - Takes two parameters: value (datetime string to validate), fieldName (string for error messages).
// - Returns true if valid, false otherwise.
function isValidDateTime(value, fieldName) {
    // Conditional block to check for empty or missing space
    // - An if statement checking if value is empty or lacks a space.
    // - Uses length and indexOf() to validate format.
    // - Alerts the user and returns false if invalid.
    if (value.length === 0 || value.indexOf(" ") === -1) {
        // Alert statement
        // - A function call to alert().
        // - Displays a message with the fieldName.
        // - Notifies the user that the datetime must be in DD-MM-YYYY HH:MM:SS format.
        alert(`${fieldName} must be entered and of the format (DD-MM-YYYY HH:MM:SS)`);
        
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates the datetime is invalid due to missing input or space.
        return false;
    }

    // Local variables to split datetime
    // - Constants using array destructuring.
    // - Splits value on space to separate date and time.
    // - Stores date (DD-MM-YYYY) and time (HH:MM:SS).
    let [date, time] = value.split(" ");
    
    // Validate date part
    // - A conditional block calling isValidDate().
    // - Passes date and fieldName to check DD-MM-YYYY format.
    // - Returns false if the date part is invalid.
    if (!isValidDate(date, fieldName)) {
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates the datetime is invalid due to an invalid date part.
        return false;
    }

    // Local variable to split time components
    // - A constant splitting time on colons.
    // - Uses split(":") to separate hours, minutes, seconds.
    // - Stores time components in an array.
    let timeComps = time.split(":");
    
    // Conditional block to check time component lengths
    // - An if statement checking if timeComps has 3 parts and each has correct length.
    // - Ensures hours, minutes, and seconds are 2 characters each.
    // - Alerts the user and returns false if invalid.
    if (timeComps.length !== 3 || timeComps[0].length !== 2 || timeComps[1].length !== 2 || timeComps[2].length !== 2) {
        // Alert statement
        // - A function call to alert().
        // - Displays a message with the fieldName.
        // - Notifies the user that the time must be in HH:MM:SS format.
        alert(`${fieldName} time must be of the format (HH:MM:SS)`);
        
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates the datetime is invalid due to incorrect time format.
        return false;
    }

    // Local variables to convert time components to numbers
    // - Constants for hours, minutes, and seconds as integers.
    // - Uses parseInt() with base 10 to convert strings.
    // - Represents numerical values of time components.
    let hours = parseInt(timeComps[0], 10);
    let minutes = parseInt(timeComps[1], 10);
    let seconds = parseInt(timeComps[2], 10);
    
    // Conditional block to check time component validity
    // - An if statement checking if components are numbers and within valid ranges.
    // - Ensures hours (0-23), minutes (0-59), seconds (0-59).
    // - Alerts the user and returns false if invalid.
    if (isNaN(hours) || isNaN(minutes) || isNaN(seconds) || hours < 0 || hours > 23 || minutes < 0 || minutes > 59 || seconds < 0 || seconds > 59) {
        // Alert statement
        // - A function call to alert().
        // - Displays a message with the fieldName.
        // - Notifies the user that time components must be valid.
        alert(`${fieldName} time components must be valid (HH:MM:SS)`);
        
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates the datetime is invalid due to invalid time components.
        return false;
    }

    // Return statement
    // - Returns a boolean value (true).
    // - Indicates the datetime is valid.
    return true;
}

// Function to validate radio button selection
// - A user-defined function named isRadioSelected.
// - Takes one parameter: groupName (string, name attribute of radio group).
// - Returns true if a radio button is selected, false otherwise.
function isRadioSelected(groupName) {
    // Local variable to hold radio buttons
    // - A constant using getElementsByName().
    // - Fetches all radio buttons with the given groupName.
    // - Stores a NodeList of radio elements.
    let radios = document.getElementsByName(groupName);
    
    // Loop through radio buttons
    // - A for...of loop iterating over radios.
    // - Uses let to declare radio as the loop variable.
    // - Checks each radio button’s checked property.
    for (let radio of radios) {
        // Conditional block to check if radio is selected
        // - An if statement checking radio.checked.
        // - Tests if the radio button is selected.
        // - Returns true if any radio is checked.
        if (radio.checked) {
            // Return statement
            // - Returns a boolean value (true).
            // - Indicates a radio button is selected.
            return true;
        }
    }
    
    // Alert statement
    // - A function call to alert().
    // - Displays a message with the groupName.
    // - Notifies the user to select a value.
    alert(`Please select a value for ${groupName}`);
    
    // Return statement
    // - Returns a boolean value (false).
    // - Indicates no radio button is selected.
    return false;
}

// Function to validate select dropdown selection
// - A user-defined function named isSelectSelected.
// - Takes one parameter: selectId (string, ID of the select element).
// - Returns true if a valid option is selected, false otherwise.
function isSelectSelected(selectId) {
    // Local variable to reference the select element
    // - A constant using getElementById().
    // - Fetches the select element with the given selectId.
    // - Stores the select DOM element.
    let select = document.getElementById(selectId);
    
    // Conditional block to check if an option is selected
    // - An if statement checking if select.value is an empty string.
    // - Tests if no valid option is selected.
    // - Alerts the user and returns false if invalid.
    if (select.value === "") {
        // Alert statement
        // - A function call to alert().
        // - Displays a message with the selectId.
        // - Notifies the user to select a valid option.
        alert(`Please select a valid option for ${selectId}`);
        
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates no valid option is selected.
        return false;
    }
    
    // Return statement
    // - Returns a boolean value (true).
    // - Indicates a valid option is selected.
    return true;
}

// Function to validate bus form (version 2)
// - A user-defined function named validateBusFormV2.
// - Takes no parameters, uses DOM elements to get form values.
// - Returns true if all validations pass, false otherwise.
function validateBusFormV2() {
    // Debugging statement
    // - A console log statement.
    // - Outputs a string to the console.
    // - Confirms the function is running.
    console.log("Validating bus form...");

    // Local variables to hold form field values
    // - Constants using getElementById().value.
    // - Fetches values from form inputs with specific IDs.
    // - Stores busNumber, yearOfManufacture, capacity, engineNumber, status, and mileage.
    const busNumber = document.getElementById("busNumber").value;
    const yearOfManufacture = document.getElementById("yearOfManufacture").value;
    const capacity = document.getElementById("capacity").value;
    const engineNumber = document.getElementById("engineNumber").value;
    const status = document.getElementById("status").value;
    const mileage = document.getElementById("mileage").value;

    // Conditional block to check for empty fields
    // - An if statement calling isEmpty() for required fields.
    // - Checks busNumber, yearOfManufacture, capacity, engineNumber, and mileage.
    // - Returns false if any field is empty.
    if (
        isEmpty(busNumber, "Bus Number") ||
        isEmpty(yearOfManufacture, "Year of Manufacture") ||
        isEmpty(capacity, "Capacity") ||
        isEmpty(engineNumber, "Engine Number") ||
        isEmpty(mileage, "Mileage")
    ) {
        // Debugging statement
        // - A console log statement.
        // - Outputs a string to the console.
        // - Indicates validation failed due to empty fields.
        console.log("Validation failed: Empty fields");
        
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates validation failed due to empty fields.
        return false;
    }

    // Local variable to hold current year
    // - A constant using the Date API.
    // - Fetches the current year with getFullYear().
    // - Used to validate yearOfManufacture.
    const currentYear = new Date().getFullYear();
    
    // Conditional block to validate year of manufacture
    // - An if statement checking if yearOfManufacture is a number and within range.
    // - Ensures it’s between 1900 and currentYear.
    // - Alerts the user and returns false if invalid.
    if (isNaN(yearOfManufacture) || yearOfManufacture < 1900 || yearOfManufacture > currentYear) {
        // Alert statement
        // - A function call to alert().
        // - Displays a message with the valid year range.
        // - Notifies the user that the year is invalid.
        alert(`Year of Manufacture must be a valid year between 1900 and ${currentYear}`);
        
        // Debugging statement
        // - A console log statement.
        // - Outputs a string to the console.
        // - Indicates validation failed due to invalid year.
        console.log("Validation failed: Invalid year");
        
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates validation failed due to invalid year.
        return false;
    }

    // Conditional block to validate capacity
    // - An if statement calling isPositiveNumber().
    // - Checks if capacity is a positive number.
    // - Returns false if invalid.
    if (!isPositiveNumber(capacity, "Capacity")) {
        // Debugging statement
        // - A console log statement.
        // - Outputs a string to the console.
        // - Indicates validation failed due to invalid capacity.
        console.log("Validation failed: Invalid capacity");
        
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates validation failed due to invalid capacity.
        return false;
    }

    // Conditional block to validate mileage
    // - An if statement calling isPositiveNumber().
    // - Checks if mileage is a positive number.
    // - Returns false if invalid.
    if (!isPositiveNumber(mileage, "Mileage")) {
        // Debugging statement
        // - A console log statement.
        // - Outputs a string to the console.
        // - Indicates validation failed due to invalid mileage.
        console.log("Validation failed: Invalid mileage");
        
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates validation failed due to invalid mileage.
        return false;
    }

    // Conditional block to validate status
    // - An if statement calling isSelectSelected().
    // - Checks if a valid status option is selected.
    // - Returns false if invalid.
    if (!isSelectSelected("status")) {
        // Debugging statement
        // - A console log statement.
        // - Outputs a string to the console.
        // - Indicates validation failed due to invalid status.
        console.log("Validation failed: Invalid status");
        
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates validation failed due to invalid status.
        return false;
    }

    // Debugging statement
    // - A console log statement.
    // - Outputs a string to the console.
    // - Confirms all validations passed.
    console.log("Validation passed");
    
    // Return statement
    // - Returns a boolean value (true).
    // - Indicates all validations passed.
    return true;
}

// Function to validate bus form (original version)
// - A user-defined function named validateBusForm.
// - Takes no parameters, uses DOM elements to get form values.
// - Returns true if all validations pass, false otherwise.
function validateBusForm() {
    // Local variables to hold form field values
    // - Let variables using getElementById().value.
    // - Fetches values from form inputs with specific IDs.
    // - Stores busNumber, yearOfManufacture, capacity, engineNumber, status, and mileage.
    let busNumber = document.getElementById("busNumber").value;
    let yearOfManufacture = document.getElementById("yearOfManufacture").value;
    let capacity = document.getElementById("capacity").value;
    let engineNumber = document.getElementById("engineNumber").value;
    let status = document.getElementById("status").value;
    let mileage = document.getElementById("mileage").value;

    // Conditional block to check for empty fields and other validations
    // - An if statement combining multiple validation checks.
    // - Checks for empty fields, status selection, and positive mileage.
    // - Returns false if any validation fails.
    if (isEmpty(busNumber, "Bus Number") || isEmpty(engineNumber, "Engine Number") || isEmpty(yearOfManufacture, "YearOfManufacture") 
        || isEmpty(mileage, "Mileage")|| isEmpty(capacity, "Capacity")||!isSelectSelected("status")||!isPositiveNumber(mileage, "Mileage")||isEmpty(status, "Status")) {
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates validation failed due to empty fields or invalid inputs.
        return false;
    }

    // Local variable to hold current year
    // - A let variable using the Date API.
    // - Fetches the current year with getFullYear().
    // - Used to validate yearOfManufacture.
    let currentYear = new Date().getFullYear();
    
    // Conditional block to validate year of manufacture
    // - An if statement checking if yearOfManufacture is a number and within range.
    // - Ensures it’s between 1900 and currentYear.
    // - Alerts the user and returns false if invalid.
    if (isNaN(yearOfManufacture) || yearOfManufacture < 1900 || yearOfManufacture > currentYear) {
        // Alert statement
        // - A function call to alert().
        // - Displays a message with the valid year range.
        // - Notifies the user that the year is invalid.
        alert("Please enter a valid Year of Manufacture (between 1900 and " + currentYear + ")");
        
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates validation failed due to invalid year.
        return false;
    }

    // Conditional block to validate capacity
    // - An if statement calling isPositiveInteger().
    // - Checks if capacity is a positive integer.
    // - Returns false if invalid (function not defined in this file).
    if (!isPositiveInteger(capacity, "Capacity")) {
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates validation failed due to invalid capacity.
        return false;
    }

    // Conditional block to validate mileage
    // - An if statement checking if mileage is a number and non-negative.
    // - Uses isNaN() and comparison to ensure validity.
    // - Alerts the user and returns false if invalid.
    if (isNaN(mileage) || mileage < 0) {
        // Alert statement
        // - A function call to alert().
        // - Displays a message.
        // - Notifies the user that mileage must be non-negative.
        alert("Mileage must be a non-negative number");
        
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates validation failed due to invalid mileage.
        return false;
    }

    // Return statement
    // - Returns a boolean value (true).
    // - Indicates all validations passed.
    return true;
}

// Function to validate booking form
// - A user-defined function named validateBookingForm.
// - Takes no parameters, uses DOM elements to get form values.
// - Returns true if all validations pass, false otherwise.
function validateBookingForm() {
    // Local variables to hold form field values
    // - Let variables using getElementById().value.
    // - Fetches values from form inputs with specific IDs.
    // - Stores customerID, scheduleID, seatNumber, bookingDate, and travelDate.
    let customerID = document.getElementById("customerID").value;
    let scheduleID = document.getElementById("scheduleID").value;
    let seatNumber = document.getElementById("seatNumber").value;
    let bookingDate = document.getElementById("bookingDate").value;
    let travelDate = document.getElementById("travelDate").value;

    // Conditional block to check for empty fields and invalid dates
    // - An if statement combining multiple validation checks.
    // - Checks for empty fields and valid dates using isEmpty() and isValidDate().
    // - Returns false if any validation fails.
    if (isEmpty(customerID, "Customer ID") || 
        isEmpty(scheduleID, "Schedule ID") ||
        isEmpty(seatNumber, "Seat Number") ||
        !isValidDate(bookingDate, "Booking Date") || 
        !isValidDate(travelDate, "Travel Date")) {
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates validation failed due to empty fields or invalid dates.
        return false;
    }

    // Return statement
    // - Returns a boolean value (true).
    // - Indicates all validations passed.
    return true;
}

// Function to validate customer form
// - A user-defined function named validateCustomerForm.
// - Takes no parameters, uses DOM elements to get form values.
// - Returns true if all validations pass, false otherwise.
function validateCustomerForm() {
    // Debugging statement
    // - A console log statement.
    // - Outputs a string to the console.
    // - Confirms the function is running.
    console.log("Validation started...");

    // Local variables to hold form field values
    // - Let variables using getElementById().value.
    // - Fetches values from form inputs with specific IDs.
    // - Stores name, email, phoneNumber, passportNumber, and nationality.
    let name = document.getElementById("name").value;
    let email = document.getElementById("email").value;
    let phoneNumber = document.getElementById("phoneNumber").value;
    let passportNumber = document.getElementById("passportNumber").value;
    let nationality = document.getElementById("nationality").value;

    // Conditional block to check for empty fields and invalid inputs
    // - An if statement combining multiple validation checks.
    // - Checks for empty fields, valid phone, email, nationality, and gender.
    // - Returns false if any validation fails.
    if (isEmpty(name, "Name") || 
        !isPhoneNumber(phoneNumber) || 
        isEmpty(passportNumber, "Passport Number") ||
        !isEmail(email) || 
        !isSelectSelected("nationality") || 
        !isRadioSelected("gender")) {
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates validation failed due to empty fields or invalid inputs.
        return false;
    }
    
    // Debugging statement
    // - A console log statement.
    // - Outputs a string to the console.
    // - Confirms all validations passed.
    console.log("Validation successful!");
    
    // Return statement
    // - Returns a boolean value (true).
    // - Indicates all validations passed.
    return true;
}

// Function to validate login form
// - A user-defined function named validateLoginForm.
// - Takes no parameters, uses DOM elements to get form values.
// - Returns true if all validations pass, false otherwise.
function validateLoginForm() {
    // Local variables to hold form field values
    // - Let variables using getElementById().value.
    // - Fetches values from form inputs with specific IDs.
    // - Stores email and password.
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;

    // Conditional block to check for invalid email or empty password
    // - An if statement combining validation checks.
    // - Checks for valid email and non-empty password.
    // - Returns false if any validation fails.
    if (!isEmail(email) || isEmpty(password, "Password")) {
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates validation failed due to invalid email or empty password.
        return false;
    }

    // Return statement
    // - Returns a boolean value (true).
    // - Indicates all validations passed.
    return true;
}

// Function to validate forgot password form
// - A user-defined function named validateForgotPasswordForm.
// - Takes no parameters, uses DOM elements to get form values.
// - Returns true if all validations pass, false otherwise.
function validateForgotPasswordForm() {
    // Local variable to hold form field value
    // - A let variable using getElementById().value.
    // - Fetches the value from the email input.
    // - Stores the email address.
    let email = document.getElementById("email").value;
    
    // Conditional block to check for valid email
    // - An if statement calling isEmail().
    // - Checks if the email is valid.
    // - Returns false if invalid.
    if (!isEmail(email)) {
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates validation failed due to invalid email.
        return false;
    }
    
    // Return statement
    // - Returns a boolean value (true).
    // - Indicates all validations passed.
    return true;
}

// Function to validate reset password form
// - A user-defined function named validateResetPasswordForm.
// - Takes no parameters, uses DOM elements to get form values.
// - Returns true if all validations pass, false otherwise.
function validateResetPasswordForm() {
    // Local variables to hold form field values
    // - Let variables using getElementById().value.
    // - Fetches values from form inputs with specific IDs.
    // - Stores password and confirmPassword.
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirmPassword").value;

    // Conditional block to check for empty passwords
    // - An if statement calling isEmpty().
    // - Checks if password or confirmPassword is empty.
    // - Returns false if either is empty.
    if (isEmpty(password, "Password") || isEmpty(confirmPassword, "Confirm Password")) {
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates validation failed due to empty fields.
        return false;
    }
    
    // Conditional block to check if passwords match
    // - An if statement comparing password and confirmPassword.
    // - Ensures both passwords are identical.
    // - Alerts the user and returns false if they don’t match.
    if (password !== confirmPassword) {
        // Alert statement
        // - A function call to alert().
        // - Displays a message.
        // - Notifies the user that passwords do not match.
        alert("Passwords do not match");
        
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates validation failed due to mismatched passwords.
        return false;
    }
    
    // Conditional block to check password length
    // - An if statement checking if password.length is less than 6.
    // - Ensures the password is at least 6 characters.
    // - Alerts the user and returns false if too short.
    if (password.length < 6) {
        // Alert statement
        // - A function call to alert().
        // - Displays a message.
        // - Notifies the user that the password is too short.
        alert("Password must be at least 6 characters");
        
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates validation failed due to short password.
        return false;
    }
    
    // Return statement
    // - Returns a boolean value (true).
    // - Indicates all validations passed.
    return true;
}

// Function to validate maintenance form
// - A user-defined function named validateMaintenanceForm.
// - Takes no parameters, uses DOM elements to get form values.
// - Returns true if all validations pass, false otherwise.
function validateMaintenanceForm() {
    // Local variables to hold form field values
    // - Let variables using getElementById().value.
    // - Fetches values from form inputs with specific IDs.
    // - Stores busID, serviceDone, serviceDate, cost, materialUsed, lsd, nsd, and technicianID.
    let busID = document.getElementById("busID").value;
    let serviceDone = document.getElementById("serviceDone").value;
    let serviceDate = document.getElementById("serviceDate").value;
    let cost = document.getElementById("cost").value;
    let materialUsed = document.getElementById("materialUsed").value;
    let lsd = document.getElementById("lsd").value;
    let nsd = document.getElementById("nsd").value;
    let technicianID = document.getElementById("technicianID").value;

    // Conditional block to check for empty fields and invalid inputs
    // - An if statement combining multiple validation checks.
    // - Checks for empty fields, valid dates, and positive cost.
    // - Returns false if any validation fails.
    if (isEmpty(busID, "Bus ID") || 
        isEmpty(serviceDone, "Service Done") || 
        !isValidDate(serviceDate, "Service Date") || 
        isEmpty(cost, "Cost") || 
        !isPositiveNumber(cost, "Cost") || 
        isEmpty(materialUsed, "Material Used") || 
        !isValidDate(lsd, "Last Service Date (LSD)") || 
        isEmpty(nsd, "Next Service Date (NSD)") || 
        isEmpty(technicianID, "Technician ID")) {
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates validation failed due to empty fields or invalid inputs.
        return false;
    }

    // Return statement
    // - Returns a boolean value (true).
    // - Indicates all validations passed.
    return true;
}

// Function to validate payment form
// - A user-defined function named validatePaymentForm.
// - Takes no parameters, uses DOM elements to get form values.
// - Returns true if all validations pass, false otherwise.
function validatePaymentForm() {
    // Local variables to hold form field values
    // - Let variables using getElementById().value.
    // - Fetches values from form inputs with specific IDs.
    // - Stores bookingID, amountPaid, paymentDate, receiptNumber, transactionID, and Status.
    let bookingID = document.getElementById("bookingID").value;
    let amountPaid = document.getElementById("amountPaid").value;
    let paymentMode = document.getElementById("paymentMode").value;
    let paymentDate = document.getElementById("paymentDate").value;
    let receiptNumber = document.getElementById("receiptNumber").value;
    let transactionID = document.getElementById("transactionID").value;
    let status = document.getElementById("status").value;

    // Conditional block to check for empty fields and invalid inputs
    // - An if statement combining multiple validation checks.
    // - Checks for empty fields, valid payment mode, positive amount, and valid datetime.
    // - Returns false if any validation fails.
    if (isEmpty(bookingID, "Booking ID") || 
        isEmpty(paymentMode, "Payment Mode") || 
        isEmpty(receiptNumber, "Receipt Number") || 
        isEmpty(transactionID, "Transaction ID") || 
        isEmpty(amountPaid, "Amount Paid") || 
        isEmpty(paymentDate, "Payment Date") || 
        isEmpty(status, "Status") || 
        !isPositiveNumber(amountPaid, "Amount Paid") ||
        !isValidDateTime(paymentDate, "Payment Date")) {
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates validation failed due to empty fields or invalid inputs.
        return false;
    }

    // Return statement
    // - Returns a boolean value (true).
    // - Indicates all validations passed.
    return true;
}

// Function to validate route form
// - A user-defined function named validateRouteForm.
// - Takes no parameters, uses DOM elements to get form values.
// - Returns true if all validations pass, false otherwise.
function validateRouteForm() {
    // Local variables to hold form field values
    // - Let variables using getElementById().value.
    // - Fetches values from form inputs with specific IDs.
    // - Stores startLocation, destination, distance, routeName, routeType, and security.
    let startLocation = document.getElementById("startLocation").value;
    let destination = document.getElementById("destination").value;
    let distance = document.getElementById("distance").value;
    let routeName = document.getElementById("routeName").value;
    let routeType = document.getElementById("routeType").value;
    let security = document.getElementById("security").value;

    // Conditional block to check for empty fields and invalid inputs
    // - An if statement combining multiple validation checks.
    // - Checks for valid selections, positive distance, and non-empty security.
    // - Returns false if any validation fails.
    if (isEmpty(startLocation, "Start Location") || 
        isEmpty(destination, "Destination") ||
        isEmpty(routeName, "Route Name") || 
        !isSelectSelected("routeType") ||
        isEmpty(security, "Security") || 
        !isPositiveNumber(distance, "Distance") || 
        isEmpty(distance, "distance")) {
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates validation failed due to empty fields or invalid inputs.
        return false;
    }

    // Return statement
    // - Returns a boolean value (true).
    // - Indicates all validations passed.
    return true;
}

// Function to validate schedule form
// - A user-defined function named validateScheduleForm.
// - Takes no parameters, uses DOM elements to get form values.
// - Returns true if all validations pass, false otherwise.
function validateScheduleForm() {
    // Local variables to hold form field values
    // - Let variables using getElementById().value.
    // - Fetches values from form inputs with specific IDs.
    // - Stores busID, routeID, departureTime, arrivalTime, cost, driverID, and codriverID.
    let busID = document.getElementById("busID").value;
    let routeID = document.getElementById("routeID").value;
    let departureTime = document.getElementById("departureTime").value;
    let arrivalTime = document.getElementById("arrivalTime").value;
    let cost = document.getElementById("cost").value;
    let driverID = document.getElementById("driverID").value;
    let codriverID = document.getElementById("codriverID").value;

    // Conditional block to check for empty fields and invalid inputs
    // - An if statement combining multiple validation checks.
    // - Checks for empty fields, valid datetimes, and positive cost.
    // - Returns false if any validation fails.
    if (isEmpty(busID, "Bus ID") || 
        isEmpty(routeID, "Route ID") || 
        isEmpty(cost, "Cost") ||
        isEmpty(driverID, "Driver ID") || 
        isEmpty(codriverID, "Co-driver ID") || 
        isEmpty(departureTime, "Departure Time") ||
        isEmpty(arrivalTime, "Arrival Time") ||
        !isPositiveNumber(cost, "Cost") ||
        !isValidDateTime(departureTime, "Departure Time") ||
        !isValidDateTime(arrivalTime, "Arrival Time")) {
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates validation failed due to empty fields or invalid inputs.
        return false;
    }

    // Return statement
    // - Returns a boolean value (true).
    // - Indicates all validations passed.
    return true;
}

// Function to validate staff form
// - A user-defined function named validateStaffForm.
// - Takes no parameters, uses DOM elements to get form values.
// - Returns true if all validations pass, false otherwise.
function validateStaffForm() {
    // Local variables to hold form field values
    // - Let variables using getElementById().value.
    // - Fetches values from form inputs with specific IDs.
    // - Stores name, phoneNumber, email, staffNumber, and role.
    let name = document.getElementById("name").value;
    let phoneNumber = document.getElementById("phoneNumber").value;
    let email = document.getElementById("email").value;
    let staffNumber = document.getElementById("staffNumber").value;
    let role = document.getElementById("role").value;

    // Conditional block to check for empty fields and invalid inputs
    // - An if statement combining multiple validation checks.
    // - Checks for empty fields, valid phone, email, and role selection.
    // - Returns false if any validation fails.
    if (isEmpty(name, "Name") || 
        !isPhoneNumber(phoneNumber) ||
        isEmpty(staffNumber, "Staff Number") || 
        !isSelectSelected("role") || 
        !isEmail(email)) {
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates validation failed due to empty fields or invalid inputs.
        return false;
    }

    // Return statement
    // - Returns a boolean value (true).
    // - Indicates all validations passed.
    return true;
}

// Function to validate activity form
// - A user-defined function named validateActivityForm.
// - Takes no parameters, uses DOM elements to get form values.
// - Returns true if all validations pass, false otherwise.
function validateActivityForm() {
    // Local variables to hold form field values
    // - Let variables using getElementById().value.
    // - Fetches values from form inputs with specific IDs.
    // - Stores description, date, time, whoDidIt, and role.
    let description = document.getElementById("description").value;
    let date = document.getElementById("date").value;
    let time = document.getElementById("time").value;
    let whoDidIt = document.getElementById("whoDidIt").value;
    let role = document.getElementById("role").value;

    // Conditional block to check for empty fields and invalid inputs
    // - An if statement combining multiple validation checks.
    // - Checks for empty fields, valid date, and valid time.
    // - Returns false if any validation fails.
    if (isEmpty(description, "Description") || 
        isEmpty(whoDidIt, "Who Did It") || 
        isEmpty(role, "Role") || 
        !isValidDate(date, "Date") || 
        !isValidTime(time, "Time")) {
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates validation failed due to empty fields or invalid inputs.
        return false;
    }

    // Return statement
    // - Returns a boolean value (true).
    // - Indicates all validations passed.
    return true;
}

// Function to validate user form
// - A user-defined function named validateUserForm.
// - Takes no parameters, uses DOM elements to get form values.
// - Returns true if all validations pass, false otherwise.
function validateUserForm() {
    // Local variables to hold form field values
    // - Let variables using getElementById().value.
    // - Fetches values from form inputs with specific IDs.
    // - Stores name, email, phoneNumber, password, and role.
    let name = document.getElementById("name").value;
    let email = document.getElementById("email").value;
    let phoneNumber = document.getElementById("phoneNumber").value;
    let password = document.getElementById("password").value;
    let role = document.getElementById("role").value;

    // Conditional block to check for empty fields and invalid inputs
    // - An if statement combining multiple validation checks.
    // - Checks for empty fields, valid email, phone, and role selection.
    // - Returns false if any validation fails.
    if (isEmpty(name, "Name") || 
        isEmpty(password, "Password") ||
        !isEmail(email) || 
        !isSelectSelected("role") || 
        !isPhoneNumber(phoneNumber)) {
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates validation failed due to empty fields or invalid inputs.
        return false;
    }

    // Return statement
    // - Returns a boolean value (true).
    // - Indicates all validations passed.
    return true;
}

// Function to validate signup form
// - A user-defined function named validateSignupForm.
// - Takes no parameters, uses DOM elements to get form values.
// - Returns true if all validations pass, false otherwise.
function validateSignupForm() {
    // Local variables to hold form field values
    // - Let variables using getElementById().value.
    // - Fetches values from form inputs with specific IDs.
    // - Stores name, email, phoneNumber, password, and role.
    let name = document.getElementById("name").value;
    let email = document.getElementById("email").value;
    let phoneNumber = document.getElementById("phoneNumber").value;
    let password = document.getElementById("password").value;
    let role = document.getElementById("role").value;

    // Conditional block to check for empty fields and invalid inputs
    // - An if statement combining multiple validation checks.
    // - Checks for empty fields, valid email, and phone number.
    // - Returns false if any validation fails.
    if (isEmpty(name, "Name") || 
        isEmpty(password, "Password") ||
        !isEmail(email) || 
        !isPhoneNumber(phoneNumber)) {
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates validation failed due to empty fields or invalid inputs.
        return false;
    }

    // Return statement
    // - Returns a boolean value (true).
    // - Indicates all validations passed.
    return true;
}

// Function to validate search form
// - A user-defined function named validateSearchForm.
// - Takes no parameters, uses DOM elements to get form values.
// - Returns true if all validations pass, false otherwise.
function validateSearchForm() {
    // Local variable to hold form field value
    // - A let variable using getElementById().value.
    // - Fetches the value from the date input.
    // - Stores the travel date.
    let date = document.getElementById("date").value;
    
    // Conditional block to check for valid date
    // - An if statement calling isValidDate().
    // - Checks if the date is valid in DD-MM-YYYY format.
    // - Returns false if invalid.
    if (!isValidDate(date, "Travel Date")) {
        // Return statement
        // - Returns a boolean value (false).
        // - Indicates validation failed due to invalid date.
        return false;
    }
    
    // Return statement
    // - Returns a boolean value (true).
    // - Indicates all validations passed.
    return true;
}