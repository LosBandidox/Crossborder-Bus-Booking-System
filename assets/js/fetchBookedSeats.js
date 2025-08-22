// File: seatSelection.js
// Purpose: Manages seat selection on the customer seat selection page in the International Bus Booking System.
// Fetches booked seats for a bus schedule, displays a seat layout, and handles multiple seat selections for booking.

// Function to get booked seats from the server
// - A user-defined JavaScript function named fetchBookedSeats, with no inputs.
// - Gets the schedule ID from the URL, fetches booked seats, and shows them in a seat layout.
// - Loads seat data for a specific bus trip to help customers pick available seats.
function fetchBookedSeats() {
    // Logs a message for debugging
    // - Uses console.log(), a built-in JavaScript method, to write “Fetching booked seats...” to the developer tools.
    // - Helps developers confirm the function is running.
    // - Tracks when the seat fetching process starts.
    console.log("Fetching booked seats...");

    // Gets the schedule ID from the URL
    // - A user-defined constant named urlParams, holding a built-in JavaScript URLSearchParams object created from window.location.search.
    // - Parses the URL’s query string to extract parameters like scheduleID.
    // - Prepares to get the specific bus trip ID for fetching seats.
    const urlParams = new URLSearchParams(window.location.search);

    // Gets the schedule ID value
    // - A user-defined constant named scheduleID, holding the result of urlParams.get('scheduleID'), a built-in method returning a string or null.
    // - Stores the bus trip ID from the URL, like “123” for a specific schedule.
    // - Identifies which trip’s seats to fetch.
    const scheduleID = urlParams.get('scheduleID');

    // Checks if the schedule ID is valid
    // - A conditional statement checking if scheduleID is missing or null.
    // - Shows an alert and stops if no valid ID is found, preventing errors.
    // - Ensures the page only proceeds with a proper trip ID.
    if (!scheduleID) {
        // Shows an error message
        // - Calls alert(), a built-in JavaScript method, with “Invalid schedule ID.”.
        // - Tells the customer the trip ID is missing or incorrect.
        // - Stops the seat fetching process to avoid errors.
        alert("Invalid schedule ID.");
        return;
    }

    // Sets the schedule ID in a form field
    // - Sets the value property, a built-in JavaScript feature, of the input element with ID 'scheduleID' using document.getElementById().
    // - Stores the scheduleID in a hidden form field for use during booking.
    // - Keeps the trip ID ready for form submission later.
    document.getElementById('scheduleID').value = scheduleID;

    // Sends a web request to get booked seats
    // - Uses the built-in JavaScript fetch() method to request data from a PHP file at `/php/fetchBookedSeats.php?scheduleID=${scheduleID}`.
    // - Starts a chain of steps to process the server’s reply and show booked seats.
    // - Fetches seat data for the chosen bus trip.
    fetch(`/php/fetchBookedSeats.php?scheduleID=${scheduleID}`)
        // Processes the server’s reply
        // - A built-in JavaScript .then() method that converts the response to a JSON object using response.json().
        // - Turns the server’s reply into a usable object with seat data.
        // - Prepares the seat details for display.
        .then(response => response.json())
        // Handles the seat data
        // - A .then() method that takes the data object and processes booked seats or shows an error.
        // - Logs data and calls a function to show the seat layout if successful.
        .then(data => {
            // Logs the seat data for debugging
            // - Uses console.log() to write “Booked seats data:” and the data object to the developer tools.
            // - Helps developers check what seat data the server sent.
            // - Tracks the fetched seat information.
            console.log("Booked seats data:", data);

            // Checks if seat data was received
            // - A conditional statement checking if data.bookedSeats exists.
            // - Processes seats if available, or shows an error if not.
            // - Decides how to proceed based on the server’s reply.
            if (data.bookedSeats) {
                // Creates a list of booked seats
                // - A user-defined constant named bookedSeats, an empty array to store individual seat numbers.
                // - Stores seat numbers like “1A”, “2B” from the server data.
                // - Prepares seats for display in the layout.
                const bookedSeats = [];

                // Splits seat strings into individual seats
                // - A forEach loop, a built-in JavaScript method, that processes each seatString in data.bookedSeats.
                // - Uses the built-in split(',') method to break comma-separated seat numbers and adds them to bookedSeats with the spread operator (...).
                // - Combines all booked seat numbers into one list.
                data.bookedSeats.forEach(seatString => {
                    bookedSeats.push(...seatString.split(','));
                });

                // Shows the seat layout
                // - Calls the user-defined displaySeatLayout() function with bookedSeats and scheduleID.
                // - Builds the visual seat grid with booked seats marked.
                // - Displays the bus layout for the customer to pick seats.
                displaySeatLayout(bookedSeats, scheduleID);
            } else {
                // Shows an error message
                // - Calls alert() with “Error fetching booked seats.”.
                // - Tells the customer the seat data couldn’t load.
                // - Keeps the page usable if the server fails to send seat info.
                alert("Error fetching booked seats.");
            }
        })
        // Handles errors during the fetch
        // - A built-in JavaScript .catch() method that logs any errors in the fetch chain.
        // - Writes error details to the developer tools for troubleshooting.
        // - Ensures the page doesn’t break if fetching fails.
        .catch(error => console.error('Error:', error));
}

// Function to show the seat layout
// - A user-defined JavaScript function named displaySeatLayout, with two inputs: bookedSeats (array of strings), scheduleID (string).
// - Builds a visual grid of bus seats, marking booked and available seats, based on a Kenyan bus design.
// - Creates a clickable seat layout for customers to select seats for booking.
function displaySeatLayout(bookedSeats, scheduleID) {
    // Finds the seat layout section
    // - A user-defined constant named seatLayout, holding the result of document.getElementById() for the element with ID 'seatLayout'.
    // - Targets the area where the seat grid will be shown.
    // - Lets the code build the bus seat layout.
    const seatLayout = document.getElementById('seatLayout');

    // Clears old seat layout
    // - Sets seatLayout.innerHTML, a built-in JavaScript property, to an empty string.
    // - Removes any existing seat elements to avoid duplicates.
    // - Prepares the layout area for a new seat grid.
    seatLayout.innerHTML = "";

    // Sets the number of seat rows
    // - A user-defined constant named totalRows, an integer set to 10.
    // - Defines the number of rows in the bus, including a special first row for Door and Driver.
    // - Matches the Kenyan bus design with 10 rows.
    const totalRows = 10;

    // Sets seats per row
    // - A user-defined constant named seatsPerRow, an array of integers [5, 4, 4, 4, 4, 4, 4, 4, 4, 5].
    // - Specifies how many seats each row has, with first row (Door, Driver) and last row having 5, others having 4.
    // - Defines the bus layout structure for seat generation.
    const seatsPerRow = [5, 4, 4, 4, 4, 4, 4, 4, 4, 5];

    // Goes through each row to build seats
    // - A for loop using a user-defined variable named row, iterating from 0 to totalRows - 1.
    // - Creates a <div> for each row of seats in the bus layout.
    // - Builds the grid row by row for the customer to see.
    for (let row = 0; row < totalRows; row++) {
        // Creates a row container
        // - A user-defined variable named rowDiv, holding a new <div> element created with the built-in document.createElement() method.
        // - Represents one row of seats in the bus layout.
        // - Holds seat elements for this row.
        let rowDiv = document.createElement("div");

        // Adds a CSS class to the row
        // - Uses classList.add(), a built-in JavaScript method, to add the “row” class to rowDiv.
        // - Applies styling to the row for proper layout display.
        // - Formats the row in the seat grid.
        rowDiv.classList.add("row");

        // Handles the first row (Door, empty spaces, Driver)
        // - A conditional statement checking if row equals 0.
        // - Builds a special first row with Door, three empty spaces, and Driver for Kenyan bus design.
        // - Sets up the unique layout of the bus entrance.
        if (row === 0) {
            // Adds the Door
            // - A user-defined variable named doorDiv, holding a new <div> element created with document.createElement().
            // - Represents the bus door in the first row.
            // - Shows the door in the seat layout.
            let doorDiv = document.createElement("div");

            // Styles the door
            // - Adds the “door” class to doorDiv using classList.add().
            // - Applies styling to mark the door visually.
            // - Makes the door distinct in the layout.
            doorDiv.classList.add("door");

            // Labels the door
            // - Sets doorDiv.textContent, a built-in JavaScript property, to “Door”.
            // - Displays the word “Door” in the layout.
            // - Identifies the door for the customer.
            doorDiv.textContent = "Door";

            // Adds the door to the row
            // - Uses appendChild(), a built-in JavaScript method, to add doorDiv to rowDiv.
            // - Places the door in the first row of the seat grid.
            // - Builds part of the first row’s layout.
            rowDiv.appendChild(doorDiv);

            // Adds three empty spaces
            // - A for loop iterating from 0 to 2 using a user-defined variable i.
            // - Creates empty space elements to fill gaps in the first row.
            // - Matches the Kenyan bus design with spaces before the Driver.
            for (let i = 0; i < 3; i++) {
                // Creates an empty space
                // - A user-defined variable named emptyDiv, holding a new <div> element created with document.createElement().
                // - Represents an empty space in the first row.
                // - Fills the layout gap.
                let emptyDiv = document.createElement("div");

                // Styles the empty space
                // - Adds the “empty-space” class to emptyDiv using classList.add().
                // - Applies styling to show the space as a gap.
                // - Formats the empty space visually.
                emptyDiv.classList.add("empty-space");

                // Adds the empty space to the row
                // - Uses appendChild() to add emptyDiv to rowDiv.
                // - Places the empty space in the first row.
                // - Builds part of the first row’s layout.
                rowDiv.appendChild(emptyDiv);
            }

            // Adds the Driver
            // - A user-defined variable named driverDiv, holding a new <div> element created with document.createElement().
            // - Represents the driver’s seat in the first row.
            // - Shows the driver’s position in the layout.
            let driverDiv = document.createElement("div");

            // Styles the driver’s seat
            // - Adds the “driver-seat” class to driverDiv using classList.add().
            // - Applies styling to mark the driver’s seat visually.
            // - Makes the driver’s seat distinct in the layout.
            driverDiv.classList.add("driver-seat");

            // Labels the driver’s seat
            // - Sets driverDiv.textContent to “Driver”.
            // - Displays the word “Driver” in the layout.
            // - Identifies the driver’s position for the customer.
            driverDiv.textContent = "Driver";

            // Adds the driver’s seat to the row
            // - Uses appendChild() to add driverDiv to rowDiv.
            // - Places the driver’s seat in the first row.
            // - Completes the first row’s layout.
            rowDiv.appendChild(driverDiv);
        } else {
            // Goes through each seat in the row
            // - A for loop iterating from 1 to seatsPerRow[row] using a user-defined variable seat.
            // - Creates seat elements for the current row based on the number of seats defined.
            // - Builds regular seat rows (rows 1–9) in the bus layout.
            for (let seat = 1; seat <= seatsPerRow[row]; seat++) {
                // Creates a seat identifier
                // - A user-defined variable named seatNumber, a string combining the row number and a letter (e.g., “1A”).
                // - Uses String.fromCharCode(64 + seat) to convert seat number to a letter (1=A, 2=B, etc.).
                // - Labels each seat uniquely for the bus layout.
                let seatNumber = `${row}${String.fromCharCode(64 + seat)}`;

                // Checks if the seat is booked
                // - A user-defined variable named isSeatBooked, a boolean from the built-in includes() method on bookedSeats.
                // - Determines if the seatNumber is in the bookedSeats array.
                // - Marks the seat as booked or available for display.
                let isSeatBooked = bookedSeats.includes(seatNumber);

                // Creates a seat element
                // - A user-defined variable named seatDiv, holding a new <div> element created with document.createElement().
                // - Represents one seat in the bus layout.
                // - Holds the seat’s visual and interactive elements.
                let seatDiv = document.createElement("div");

                // Styles the seat
                // - Uses classList.add() to add “seat” and either “booked” or “available” classes based on isSeatBooked.
                // - Applies styling to show if the seat is taken or free.
                // - Makes seats visually distinct for customers.
                seatDiv.classList.add("seat", isSeatBooked ? "booked" : "available");

                // Adds a seat identifier
                // - Uses setAttribute(), a built-in JavaScript method, to add a data-seat attribute with seatNumber.
                // - Stores the seat number in the element for use in selection logic.
                // - Links the seat element to its unique ID.
                seatDiv.setAttribute("data-seat", seatNumber);

                // Labels the seat
                // - Sets seatDiv.textContent to seatNumber.
                // - Displays the seat number, like “1A”, on the seat element.
                // - Shows customers which seat they’re selecting.
                seatDiv.textContent = seatNumber;

                // Sets up seat clicking
                // - Adds an event listener to seatDiv using addEventListener(), a built-in JavaScript method, for the 'click' event.
                // - Calls the user-defined selectSeat() function with seatDiv, seatNumber, and isSeatBooked when clicked.
                // - Lets customers select or deselect seats interactively.
                seatDiv.addEventListener("click", function () {
                    selectSeat(seatDiv, seatNumber, isSeatBooked);
                });

                // Adds the seat to the row
                // - Uses appendChild() to add seatDiv to rowDiv.
                // - Places the seat in the current row of the layout.
                // - Builds the row’s seat structure.
                rowDiv.appendChild(seatDiv);

                // Adds a walkway after the second seat
                // - A conditional statement checking if seat equals 2 and row is not the last row (totalRows - 1).
                // - Adds a walkway space between seats for rows 1–8, matching Kenyan bus design.
                // - Creates a visual gap in the seat layout.
                if (seat === 2 && row !== totalRows - 1) {
                    // Creates a walkway
                    // - A user-defined variable named walkwayDiv, holding a new <div> element created with document.createElement().
                    // - Represents a walkway space between seats.
                    // - Adds a gap for the bus aisle.
                    let walkwayDiv = document.createElement("div");

                    // Styles the walkway
                    // - Adds the “walkway” class to walkwayDiv using classList.add().
                    // - Applies styling to show the walkway as a gap.
                    // - Formats the aisle visually.
                    walkwayDiv.classList.add("walkway");

                    // Adds the walkway to the row
                    // - Uses appendChild() to add walkwayDiv to rowDiv.
                    // - Places the walkway after the second seat in the row.
                    // - Completes the row’s layout with an aisle.
                    rowDiv.appendChild(walkwayDiv);
                }
            }
        }

        // Adds the row to the layout
        // - Uses appendChild() to add rowDiv to seatLayout.
        // - Places the completed row in the seat grid.
        // - Builds the full bus seat layout for the customer.
        seatLayout.appendChild(rowDiv);
    }
}

// Tracks selected seats
// - A user-defined global variable named selectedSeats, an empty array.
// - Stores seat numbers (e.g., “1A”, “2B”) that the customer selects.
// - Manages multiple seat selections for booking.
let selectedSeats = [];

// Function to handle seat selection
// - A user-defined JavaScript function named selectSeat, with three inputs: seatElement (DOM element), seatNumber (string), isBooked (boolean).
// - Manages selecting or deselecting seats, updates the form, and controls the submit button.
// - Lets customers choose up to 5 seats and prepares the booking form.
function selectSeat(seatElement, seatNumber, isBooked) {
    // Checks if the seat is booked
    // - A conditional statement checking if isBooked is true.
    // - Shows an alert and stops if the seat is already taken.
    // - Prevents customers from selecting unavailable seats.
    if (isBooked) {
        // Shows an error message
        // - Calls alert() with “This seat is already booked.”.
        // - Tells the customer they can’t pick this seat.
        // - Keeps the selection process clear and user-friendly.
        alert("This seat is already booked.");
        return;
    }

    // Finds if the seat is already selected
    // - A user-defined constant named index, holding the result of the built-in indexOf() method on selectedSeats.
    // - Checks if seatNumber is in selectedSeats, returning its index or -1 if not found.
    // - Determines if the seat should be added or removed.
    const index = selectedSeats.indexOf(seatNumber);

    // Handles seat selection or deselection
    // - A conditional statement checking if index equals -1 (seat not selected).
    // - Adds the seat if not selected, or removes it if already selected, with a limit check.
    // - Updates the seat’s appearance and the form accordingly.
    if (index === -1) {
        // Checks the seat limit
        // - A conditional statement checking if selectedSeats.length is 5 or more.
        // - Shows an alert and stops if the customer tries to select more than 5 seats.
        // - Enforces a maximum of 5 seats per booking.
        if (selectedSeats.length >= 5) {
            // Shows an error message
            // - Calls alert() with “Cannot select more than 5 seats.”.
            // - Tells the customer they’ve reached the seat limit.
            // - Prevents adding more seats to the selection.
            alert("Cannot select more than 5 seats.");
            return;
        }

        // Adds the seat to the selection
        // - Uses the built-in push() method to add seatNumber to selectedSeats.
        // - Stores the selected seat in the array for booking.
        // - Tracks the customer’s choice.
        selectedSeats.push(seatNumber);

        // Marks the seat as selected
        // - Uses classList.add() to add the “selected” class to seatElement.
        // - Changes the seat’s appearance to show it’s selected.
        // - Updates the visual layout for the customer.
        seatElement.classList.add("selected");
    } else {
        // Removes the seat from the selection
        // - Uses the built-in splice() method to remove the seat at index from selectedSeats.
        // - Deletes the seat from the customer’s choices.
        // - Updates the selection list.
        selectedSeats.splice(index, 1);

        // Removes the selected appearance
        // - Uses classList.remove() to remove the “selected” class from seatElement.
        // - Changes the seat’s appearance back to available.
        // - Updates the visual layout to show the seat is free.
        seatElement.classList.remove("selected");
    }

    // Updates the form with selected seats
    // - Sets the value property of the input element with ID 'seatNumbers' to selectedSeats joined by commas using join(',').
    // - Stores selected seat numbers (e.g., “1A,2B”) in the form for booking.
    // - Prepares the form for submission with the customer’s choices.
    document.getElementById('seatNumbers').value = selectedSeats.join(',');

    // Finds the submit button
    // - A user-defined constant named button, holding the result of document.querySelector(), a built-in JavaScript method, targeting the button with type="submit".
    // - Identifies the form’s submit button for enabling or disabling.
    // - Controls the button’s state based on selections.
    const button = document.querySelector('button[type="submit"]');

    // Enables or disables the submit button
    // - Sets the disabled property of button to true if selectedSeats.length is 0, else false.
    // - Allows submission only if at least one seat is selected.
    // - Prevents proceeding to payment without seat choices.
    button.disabled = selectedSeats.length === 0;

    // Updates the button text
    // - Sets button.textContent to show “Proceed to Payment” with the number of selected seats.
    // - Displays the seat count, like “Proceed to Payment (2 seats)”, to the customer.
    // - Keeps the button label clear and informative.
    button.textContent = `Proceed to Payment (${selectedSeats.length} seats)`;
}

// Sets up form submission confirmation
// - Adds an event listener to the form with ID 'seatForm' using addEventListener(), a built-in JavaScript method.
// - Listens for the 'submit' event and runs a function to confirm seat selections before proceeding.
// - Ensures the customer confirms their seat choices before booking.
document.getElementById('seatForm').addEventListener('submit', function (event) {
    // Asks for confirmation
    // - Uses the built-in confirm() method to show a dialog with the number of selected seats and their numbers, like “You selected 2 seats: 1A, 2B. Proceed?”.
    // - Uses a ternary operator to add “s” for plural if selectedSeats.length > 1, and join(', ') to list seats.
    // - Stops submission if the customer cancels.
    if (!confirm(`You selected ${selectedSeats.length} seat${selectedSeats.length > 1 ? 's' : ''}: ${selectedSeats.join(', ')}. Proceed?`)) {
        // Stops the form submission
        // - Calls preventDefault(), a built-in JavaScript method of the event object, to cancel the form submission.
        // - Prevents booking if the customer doesn’t confirm.
        // - Keeps the customer on the seat selection page.
        event.preventDefault();
    }
});

// Loads booked seats when the page opens
// - Sets the built-in JavaScript window.onload property to the user-defined fetchBookedSeats() function.
// - Runs fetchBookedSeats when the page fully loads, triggering the 'load' event.
// - Fills the seat layout with booked seats as soon as the customer opens the seat selection page.
window.onload = fetchBookedSeats;