// File: admin_reports.js
// Manages fetching and displaying report data for admin_reports.html in the bus booking system.

// Attaches event listener for page load
// - Built-in event listener using document.addEventListener() with 'DOMContentLoaded' event.
// - Executes anonymous function when HTML is fully loaded.
// - Initializes report data fetching for admin dashboard.
document.addEventListener('DOMContentLoaded', function () {
    // Triggers report fetching
    // - Built-in function call to user-defined fetchAllReports.
    // - Starts loading all report data for tables and charts.
    // - Runs on page load to populate admin_reports.html.
    fetchAllReports();
});

// Defines function to set date range
// - User-defined function named setDateRange, with no inputs.
// - Updates start and end date inputs based on selected period.
// - Enables admins to filter reports by predefined time ranges.
function setDateRange() {
    // Declares period variable
    // - User-defined constant named period using document.getElementById(), a built-in method.
    // - Gets value of dropdown with ID 'period' (e.g., 'last_month', 'q1').
    // - Determines the time range for date filtering.
    const period = document.getElementById('period').value;
    
    // Declares start date input variable
    // - User-defined constant named startDateInput using document.getElementById().
    // - Targets input element with ID 'startDate' in admin_reports.html.
    // - Prepares to set the start date for report filtering.
    const startDateInput = document.getElementById('startDate');
    
    // Declares end date input variable
    // - User-defined constant named endDateInput using document.getElementById().
    // - Targets input element with ID 'endDate' in admin_reports.html.
    // - Prepares to set the end date for report filtering.
    const endDateInput = document.getElementById('endDate');
    
    // Declares current date variable
    // - User-defined constant named today using new Date(), a built-in API.
    // - Stores current date for calculating time ranges.
    // - Provides reference for setting date inputs.
    const today = new Date();
    
    // Evaluates selected period
    // - Built-in if-else block checking period value.
    // - Sets startDateInput and endDateInput with formatted dates (dd-mm-yyyy).
    // - Matches period to predefined ranges for report filtering.
    if (period === 'last_month') {
        // Declares start date for last month
        // - User-defined constant named start using new Date().
        // - Sets to first day of previous month using today.getFullYear() and today.getMonth() - 1.
        // - Defines start of last month for filtering.
        const start = new Date(today.getFullYear(), today.getMonth() - 1, 1);
        
        // Declares end date for last month
        // - User-defined constant named end using new Date().
        // - Sets to last day of previous month using today.getMonth() and day 0.
        // - Defines end of last month for filtering.
        const end = new Date(today.getFullYear(), today.getMonth(), 0);
        
        // Sets start date input
        // - Built-in assignment to startDateInput.value using template literal.
        // - Formats date as dd-mm-yyyy with padStart(), a built-in method, for leading zeros.
        // - Updates start date field for last month (e.g., 01-06-2025).
        startDateInput.value = `${String(start.getDate()).padStart(2, '0')}-${String(start.getMonth() + 1).padStart(2, '0')}-${start.getFullYear()}`;
        
        // Sets end date input
        // - Built-in assignment to endDateInput.value using template literal.
        // - Formats date as dd-mm-yyyy with padStart() for leading zeros.
        // - Updates end date field for last month (e.g., 30-06-2025).
        endDateInput.value = `${String(end.getDate()).padStart(2, '0')}-${String(end.getMonth() + 1).padStart(2, '0')}-${end.getFullYear()}`;
    } else if (period === 'last_year') {
        // Sets last year’s start date
        // - Built-in assignment to startDateInput.value using template literal.
        // - Sets to January 1 of previous year using today.getFullYear() - 1.
        // - Formats as 01-01-yyyy (e.g., 01-01-2024).
        startDateInput.value = `01-01-${today.getFullYear() - 1}`;
        
        // Sets last year’s end date
        // - Built-in assignment to endDateInput.value using template literal.
        // - Sets to December 31 of previous year using today.getFullYear() - 1.
        // - Formats as 31-12-yyyy (e.g., 31-12-2024).
        endDateInput.value = `31-12-${today.getFullYear() - 1}`;
    } else if (period === 'q1') {
        // Sets Q1 start date
        // - Built-in assignment to startDateInput.value using template literal.
        // - Sets to January 1 of current year using today.getFullYear().
        // - Formats as 01-01-yyyy (e.g., 01-01-2025).
        startDateInput.value = `01-01-${today.getFullYear()}`;
        
        // Sets Q1 end date
        // - Built-in assignment to endDateInput.value using template literal.
        // - Sets to March 31 of current year using today.getFullYear().
        // - Formats as 31-03-yyyy (e.g., 31-03-2025).
        endDateInput.value = `31-03-${today.getFullYear()}`;
    } else if (period === 'q2') {
        // Sets Q2 start date
        // - Built-in assignment to startDateInput.value using template literal.
        // - Sets to April 1 of current year using today.getFullYear().
        // - Formats as 01-04-yyyy (e.g., 01-04-2025).
        startDateInput.value = `01-04-${today.getFullYear()}`;
        
        // Sets Q2 end date
        // - Built-in assignment to endDateInput.value using template literal.
        // - Sets to June 30 of current year using today.getFullYear().
        // - Formats as 30-06-yyyy (e.g., 30-06-2025).
        endDateInput.value = `30-06-${today.getFullYear()}`;
    } else if (period === 'q3') {
        // Sets Q3 start date
        // - Built-in assignment to startDateInput.value using template literal.
        // - Sets to July 1 of current year using today.getFullYear().
        // - Formats as 01-07-yyyy (e.g., 01-07-2025).
        startDateInput.value = `01-07-${today.getFullYear()}`;
        
        // Sets Q3 end date
        // - Built-in assignment to endDateInput.value using template literal.
        // - Sets to September 30 of current year using today.getFullYear().
        // - Formats as 30-09-yyyy (e.g., 30-09-2025).
        endDateInput.value = `30-09-${today.getFullYear()}`;
    } else if (period === 'q4') {
        // Sets Q4 start date
        // - Built-in assignment to startDateInput.value using template literal.
        // - Sets to October 1 of current year using today.getFullYear().
        // - Formats as 01-10-yyyy (e.g., 01-10-2025).
        startDateInput.value = `01-10-${today.getFullYear()}`;
        
        // Sets Q4 end date
        // - Built-in assignment to endDateInput.value using template literal.
        // - Sets to December 31 of current year using today.getFullYear().
        // - Formats as 31-12-yyyy (e.g., 31-12-2025).
        endDateInput.value = `31-12-${today.getFullYear()}`;
    } else {
        // Clears date inputs
        // - Built-in assignments to startDateInput.value and endDateInput.value.
        // - Sets both to empty strings for invalid or no period.
        // - Allows admins to enter custom dates.
        startDateInput.value = '';
        endDateInput.value = '';
    }
}

// Defines function to format date input
// - User-defined function named formatDate, with one input: input (date input element).
// - Ensures date inputs follow dd-mm-yyyy format with hyphens.
// - Helps admins enter valid dates for report filtering.
function formatDate(input) {
    // Declares formatted value variable
    // - User-defined let variable named value, initialized as empty string.
    // - Stores processed input characters for formatting.
    // - Builds the final formatted date string.
    let value = '';
    
    // Iterates through input characters
    // - Built-in for loop using let variable i to iterate over input.value.
    // - Processes each character to filter valid ones.
    // - Ensures only digits and hyphens are included.
    for (let i = 0; i < input.value.length; i++) {
        // Declares character variable
        // - User-defined let variable named char using input.value[i].
        // - Holds current character for validation.
        // - Prepares to check if character is valid.
        let char = input.value[i];
        
        // Validates character
        // - Built-in conditional checking if char is a digit (0-9) or hyphen at positions 2 or 5.
        // - Adds valid characters to value string.
        // - Maintains dd-mm-yyyy format.
        if (char >= '0' && char <= '9' || (char === '-' && (i === 2 || i === 5))) {
            value += char;
        }
    }
    
    // Adds hyphen after day
    // - Built-in conditional checking value.length > 2 and no hyphen at position 2.
    // - Inserts hyphen after day using substring(), a built-in method.
    // - Ensures format like 12- for day entry.
    if (value.length > 2 && value[2] !== '-' && value.length <= 10) {
        value = value.substring(0, 2) + '-' + value.substring(2);
    }
    
    // Adds hyphen after month
    // - Built-in conditional checking value.length > 5 and no hyphen at position 5.
    // - Inserts hyphen after month using substring().
    // - Ensures format like 12-06- for month entry.
    if (value.length > 5 && value[5] !== '-' && value.length <= 10) {
        value = value.substring(0, 5) + '-' + value.substring(5);
    }
    
    // Limits input length
    // - Built-in conditional checking if value.length > 10.
    // - Truncates value to 10 characters using substring().
    // - Prevents invalid date lengths beyond dd-mm-yyyy.
    if (value.length > 10) {
        value = value.substring(0, 10);
    }
    
    // Updates input field
    // - Built-in assignment to input.value with formatted value.
    // - Displays formatted date in the input field.
    // - Ensures user sees correct dd-mm-yyyy format.
    input.value = value;
}

// Defines function to fetch all reports
// - User-defined function named fetchAllReports, with no inputs.
// - Coordinates fetching of multiple reports using date filters.
// - Populates tables and charts in admin_reports.html.
function fetchAllReports() {
    // Declares start date input variable
    // - User-defined constant named startDateInput using document.getElementById().
    // - Targets input element with ID 'startDate'.
    // - Prepares to read start date for filtering.
    const startDateInput = document.getElementById('startDate');
    
    // Declares end date input variable
    // - User-defined constant named endDateInput using document.getElementById().
    // - Targets input element with ID 'endDate'.
    // - Prepares to read end date for filtering.
    const endDateInput = document.getElementById('endDate');
    
    // Declares start date variable
    // - User-defined let variable named startDate using startDateInput.value.
    // - Stores user-entered start date.
    // - Prepares date for server formatting.
    let startDate = startDateInput.value;
    
    // Declares end date variable
    // - User-defined let variable named endDate using endDateInput.value.
    // - Stores user-entered end date.
    // - Prepares date for server formatting.
    let endDate = endDateInput.value;

    // Reformats start date
    // - Built-in conditional checking startDate length and hyphen positions.
    // - Converts dd-mm-yyyy to yyyy-mm-dd for server compatibility.
    // - Prepares start date for API requests.
    if (startDate && startDate.length === 10 && startDate[2] === '-' && startDate[5] === '-') {
        // Declares day component
        // - User-defined constant named day using substring(), a built-in method.
        // - Extracts day from startDate (positions 0-2).
        // - Prepares day for reformatting.
        const day = startDate.substring(0, 2);
        // Declares month component
        // - User-defined constant named month using substring().
        // - Extracts month from startDate (positions 3-5).
        // - Prepares month for reformatting.
        const month = startDate.substring(3, 5);
        // Declares year component
        // - User-defined constant named year using substring().
        // - Extracts year from startDate (positions 6-10).
        // - Prepares year for reformatting.
        const year = startDate.substring(6, 10);
        
        // Updates start date
        // - Built-in assignment to startDate using template literal.
        // - Combines year, month, day as yyyy-mm-dd (e.g., 2025-06-01).
        // - Ensures server-compatible date format.
        startDate = `${year}-${month}-${day}`;
    }
    
    // Reformats end date
    // - Built-in conditional checking endDate length and hyphen positions.
    // - Converts dd-mm-yyyy to yyyy-mm-dd for server compatibility.
    // - Prepares end date for API requests.
    if (endDate && endDate.length === 10 && endDate[2] === '-' && endDate[5] === '-') {
        // Declares day component
        // - User-defined constant named day using substring().
        // - Extracts day from endDate (positions 0-2).
        // - Prepares day for reformatting.
        const day = endDate.substring(0, 2);
        // Declares month component
        // - User-defined constant named month using substring().
        // - Extracts month from endDate (positions 3-5).
        // - Prepares month for reformatting.
        const month = endDate.substring(3, 5);
        // Declares year component
        // - User-defined constant named year using substring().
        // - Extracts year from endDate (positions 6-10).
        // - Prepares year for reformatting.
        const year = endDate.substring(6, 10);
        
        // Updates end date
        // - Built-in assignment to endDate using template literal.
        // - Combines year, month, day as yyyy-mm-dd (e.g., 2025-06-30).
        // - Ensures server-compatible date format.
        endDate = `${year}-${month}-${day}`;
    }

    // Triggers report fetching
    // - Built-in function calls to user-defined report functions.
    // - Passes startDate and endDate to each function.
    // - Initiates data retrieval for all reports.
    fetchSystemUsageReport(startDate, endDate);
    fetchRevenueReports(startDate, endDate);
    fetchUserActivityReport(startDate, endDate);
    fetchBookingSummaryReport(startDate, endDate);
    fetchMaintenanceReport(startDate, endDate);
    fetchBookingStatusReport(startDate, endDate);
    fetchBusUtilizationReport(startDate, endDate);
    fetchRoutePopularityReport(startDate, endDate);
    fetchDriversActivityReport(startDate, endDate);
}

// Defines async function for system usage report
// - User-defined async function named fetchSystemUsageReport, with two inputs: startDate and endDate (date strings).
// - Fetches system usage data and updates table in admin_reports.html.
// - Displays metrics like total bookings and active users.
async function fetchSystemUsageReport(startDate, endDate) {
    // Starts error handling block
    // - Built-in try-catch block to handle network or data issues.
    // - Ensures robust error handling during data fetching.
    // - Prevents crashes in report display.
    try {
        // Declares URL variable
        // - User-defined let variable named url with base path '../../../php/admin/fetchSystemUsageReport.php'.
        // - Points to server endpoint for system usage data.
        // - Prepares to append date filters.
        let url = '../../../php/admin/fetchSystemUsageReport.php';
        
        // Adds date filters
        // - Built-in conditional checking for startDate and endDate.
        // - Appends query parameters using encodeURIComponent(), a built-in method.
        // - Filters report data by date range.
        if (startDate && endDate) {
            url += `?start=${encodeURIComponent(startDate)}&end=${encodeURIComponent(endDate)}`;
        }
        
        // Sends HTTP request
        // - User-defined constant named response using fetch(), a built-in async method.
        // - Sends GET request to constructed url.
        // - Retrieves system usage data from server.
        const response = await fetch(url);
        
        // Declares data variable
        // - User-defined constant named data using response.json(), a built-in method.
        // - Parses JSON response into JavaScript object.
        // - Contains system usage metrics like TotalBookings.
        const data = await response.json();
        
        // Logs data for debugging
        // - Built-in console.log() with data object.
        // - Outputs system usage data to developer tools.
        // - Helps verify response content.
        console.log('System Usage Data:', data);
        
        // Declares table body variable
        // - User-defined constant named tableBody using document.getElementById().
        // - Targets element with ID 'systemUsageTableBody'.
        // - Prepares table for data display.
        const tableBody = document.getElementById('systemUsageTableBody');
        
        // Updates table content
        // - Built-in assignment to tableBody.innerHTML using ternary operator.
        // - Displays table row if response_status is 'success' and TotalBookings exists, else shows no-data message.
        // - Shows TotalBookings, ActiveCustomers, ActiveStaff, ActiveBuses.
        tableBody.innerHTML = data.response_status === 'success' && data.TotalBookings !== undefined ? `
            <tr>
                <td>${data.TotalBookings}</td>
                <td>${data.ActiveCustomers}</td>
                <td>${data.ActiveStaff}</td>
                <td>${data.ActiveBuses}</td>
            </tr>
        ` : '<tr><td colspan="4">No data available</td></tr>';
    } catch (error) {
        // Logs error
        // - Built-in console.error() with error object.
        // - Outputs fetch issues to developer tools.
        // - Helps diagnose network or JSON parsing problems.
        console.error('Error fetching system usage report:', error);
    }
}

// Defines async function for revenue reports
// - User-defined async function named fetchRevenueReports, with two inputs: startDate and endDate (date strings).
// - Fetches multiple revenue datasets and updates tables and charts.
// - Displays financial and operational insights for admins.
async function fetchRevenueReports(startDate, endDate) {
    // Starts error handling block
    // - Built-in try-catch block to handle network or data issues.
    // - Ensures robust error handling during data fetching.
    // - Prevents crashes in report display.
    try {
        // Declares URL variable
        // - User-defined let variable named url with base path '../../../php/admin/fetchRevenueReports.php'.
        // - Points to server endpoint for revenue data.
        // - Prepares to append date filters.
        let url = '../../../php/admin/fetchRevenueReports.php';
        
        // Adds date filters
        // - Built-in conditional checking for startDate and endDate.
        // - Appends query parameters using encodeURIComponent().
        // - Filters report data by date range.
        if (startDate && endDate) {
            url += `?start=${encodeURIComponent(startDate)}&end=${encodeURIComponent(endDate)}`;
        }
        
        // Sends HTTP request
        // - User-defined constant named response using fetch().
        // - Sends GET request to constructed url.
        // - Retrieves revenue data from server.
        const response = await fetch(url);
        
        // Declares data variable
        // - User-defined constant named data using response.json().
        // - Parses JSON response into JavaScript object.
        // - Contains datasets like summary, byRoute, bookingStatus.
        const data = await response.json();
        
        // Logs drivers activity for debugging
        // - Built-in console.log() with data.driversActivity.
        // - Outputs staff activity data to developer tools.
        // - Helps verify response content.
        console.log('Staff Activity Data:', data.driversActivity);
        
        // Declares revenue summary table variable
        // - User-defined constant named summaryBody using document.getElementById().
        // - Targets element with ID 'revenueSummaryTableBody'.
        // - Prepares table for revenue summary data.
        const summaryBody = document.getElementById('revenueSummaryTableBody');
        
        // Updates revenue summary table
        // - Built-in assignment to summaryBody.innerHTML using ternary operator.
        // - Maps data.summary to table rows if exists and non-empty, else shows no-data message.
        // - Displays PaymentDate and TotalRevenue.
        summaryBody.innerHTML = data.summary && data.summary.length > 0 ? 
            data.summary.map(row => `
                <tr>
                    <td>${row.PaymentDate}</td>
                    <td>${row.TotalRevenue}</td>
                </tr>
            `).join('') : '<tr><td colspan="2">No data available</td></tr>';
        
        // Declares revenue by route table variable
        // - User-defined constant named routeBody using document.getElementById().
        // - Targets element with ID 'revenueByRouteTableBody'.
        // - Prepares table for revenue by route data.
        const routeBody = document.getElementById('revenueByRouteTableBody');
        
        // Updates revenue by route table
        // - Built-in assignment to routeBody.innerHTML using ternary operator.
        // - Maps data.byRoute to table rows if exists and non-empty, else shows no-data message.
        // - Displays RouteName, StartLocation, Destination, TotalRevenue.
        routeBody.innerHTML = data.byRoute && data.byRoute.length > 0 ? 
            data.byRoute.map(row => `
                <tr>
                    <td>${row.RouteName}</td>
                    <td>${row.StartLocation}</td>
                    <td>${row.Destination}</td>
                    <td>${row.TotalRevenue}</td>
                </tr>
            `).join('') : '<tr><td colspan="4">No data available</td></tr>';
        
        // Declares revenue by payment mode table variable
        // - User-defined constant named modeBody using document.getElementById().
        // - Targets element with ID 'revenueByPaymentModeTableBody'.
        // - Prepares table for payment mode data.
        const modeBody = document.getElementById('revenueByPaymentModeTableBody');
        
        // Updates revenue by payment mode table
        // - Built-in assignment to modeBody.innerHTML using ternary operator.
        // - Maps data.byPaymentMode to table rows if exists and non-empty, else shows no-data message.
        // - Displays PaymentMode and TotalRevenue.
        modeBody.innerHTML = data.byPaymentMode && data.byPaymentMode.length > 0 ? 
            data.byPaymentMode.map(row => `
                <tr>
                    <td>${row.PaymentMode}</td>
                    <td>${row.TotalRevenue}</td>
                </tr>
            `).join('') : '<tr><td colspan="2">No data available</td></tr>';
        
        // Declares revenue by nationality table variable
        // - User-defined constant named nationalityBody using document.getElementById().
        // - Targets element with ID 'revenueByNationalityTableBody'.
        // - Prepares table for nationality data.
        const nationalityBody = document.getElementById('revenueByNationalityTableBody');
        
        // Updates revenue by nationality table
        // - Built-in assignment to nationalityBody.innerHTML using ternary operator.
        // - Maps data.byNationality to table rows if exists and non-empty, else shows no-data message.
        // - Displays Nationality and TotalRevenue.
        nationalityBody.innerHTML = data.byNationality && data.byNationality.length > 0 ? 
            data.byNationality.map(row => `
                <tr>
                    <td>${row.Nationality}</td>
                    <td>${row.TotalRevenue}</td>
                </tr>
            `).join('') : '<tr><td colspan="2">No data available</td></tr>';
        
        // Declares booking status table variable
        // - User-defined constant named bookingStatusBody using document.getElementById().
        // - Targets element with ID 'bookingStatusTableBody'.
        // - Prepares table for booking status data.
        const bookingStatusBody = document.getElementById('bookingStatusTableBody');
        
        // Updates booking status table
        // - Built-in assignment to bookingStatusBody.innerHTML using ternary operator.
        // - Maps data.bookingStatus to table rows if exists and non-empty, else shows no-data message.
        // - Displays Status and Count.
        bookingStatusBody.innerHTML = data.bookingStatus && data.bookingStatus.length > 0 ? 
            data.bookingStatus.map(row => `
                <tr>
                    <td>${row.Status}</td>
                    <td>${row.Count}</td>
                </tr>
            `).join('') : '<tr><td colspan="2">No data available</td></tr>';
        
        // Declares booking status chart variable
        // - User-defined constant named bookingStatusChart using document.getElementById().
        // - Targets element with ID 'bookingStatusChart'.
        // - Prepares container for pie chart display.
        const bookingStatusChart = document.getElementById('bookingStatusChart');
        
        // Updates booking status chart
        // - Built-in conditional checking for data.bookingStatus and non-empty array.
        // - Generates pie chart if data exists, else shows no-data message.
        // - Uses CSS gradients for visual representation.
        if (data.bookingStatus && data.bookingStatus.length > 0) {
            // Declares chart colors
            // - User-defined constant named colors with array of hex codes.
            // - Defines colors for pie chart segments.
            // - Alternates between green and red for visibility.
            const colors = ['#4CAF50', '#FF5733'];
            
            // Declares total count
            // - User-defined constant named totalCount using reduce(), a built-in method.
            // - Sums parseInt(row.Count) from bookingStatus, defaults to 1 to avoid division by zero.
            // - Calculates total for percentage computation.
            const totalCount = data.bookingStatus.reduce((sum, row) => sum + parseInt(row.Count), 0) || 1;
            
            // Declares gradient array
            // - User-defined let variable named gradient, initialized as empty array.
            // - Stores CSS gradient definitions for pie chart.
            // - Builds segments dynamically.
            let gradient = [];
            
            // Declares current angle
            // - User-defined let variable named currentAngle, initialized to 0.
            // - Tracks starting angle for each pie segment.
            // - Increments for continuous segments.
            let currentAngle = 0;
            
            // Iterates through booking statuses
            // - Built-in forEach on data.bookingStatus with row and index parameters.
            // - Calculates angles for pie chart segments.
            // - Builds gradient for visual display.
            data.bookingStatus.forEach((row, index) => {
                // Calculates percentage
                // - User-defined constant named percentage using row.Count and totalCount.
                // - Divides count by total and multiplies by 100.
                // - Determines segment size in pie chart.
                const percentage = (row.Count / totalCount) * 100;
                
                // Calculates angle
                // - User-defined constant named angle using percentage.
                // - Converts percentage to degrees by multiplying by 360/100.
                // - Defines segment size for pie chart.
                const angle = (percentage / 100) * 360;
                
                // Adds gradient segment
                // - Built-in push to gradient array with template literal.
                // - Uses colors[index % colors.length] for cycling colors.
                // - Defines color and angle range for segment.
                gradient.push(`${colors[index % colors.length]} ${currentAngle}deg ${currentAngle + angle}deg`);
                
                // Updates current angle
                // - Built-in assignment adding angle to currentAngle.
                // - Sets start for next segment.
                // - Ensures continuous pie chart.
                currentAngle += angle;
            });
            
            // Updates chart HTML
            // - Built-in assignment to bookingStatusChart.innerHTML using template literal.
            // - Creates pie chart with CSS gradient and labels with colors.
            // - Displays booking status distribution visually.
            bookingStatusChart.innerHTML = `
                <div class="pie-chart" style="--colors: ${gradient.join(', ')};"></div>
                <div class="pie-labels">
                    ${data.bookingStatus.map((row, index) => `
                        <div class="pie-label">
                            <span class="pie-label-color" style="background-color: ${colors[index % colors.length]};"></span>
                            ${row.Status}: ${row.Count}
                        </div>
                    `).join('')}
                </div>
            `;
        } else {
            // Sets no-data message
            // - Built-in assignment to bookingStatusChart.innerHTML.
            // - Displays message if no booking status data exists.
            // - Informs admins of missing data.
            bookingStatusChart.innerHTML = '<p>No booking status data available</p>';
        }
        
        // Declares bus utilization table variable
        // - User-defined constant named busUtilizationBody using document.getElementById().
        // - Targets element with ID 'busUtilizationTableBody'.
        // - Prepares table for bus utilization data.
        const busUtilizationBody = document.getElementById('busUtilizationTableBody');
        
        // Updates bus utilization table
        // - Built-in assignment to busUtilizationBody.innerHTML using ternary operator.
        // - Maps data.busUtilization to table rows if exists and non-empty, else shows no-data message.
        // - Displays BusNumber, TripsScheduled, SeatsBooked.
        busUtilizationBody.innerHTML = data.busUtilization && data.busUtilization.length > 0 ? 
            data.busUtilization.map(row => `
                <tr>
                    <td>${row.BusNumber}</td>
                    <td>${row.TripsScheduled}</td>
                    <td>${row.SeatsBooked}</td>
                </tr>
            `).join('') : '<tr><td colspan="3">No data available</td></tr>';
        
        // Declares route popularity table variable
        // - User-defined constant named routePopularityBody using document.getElementById().
        // - Targets element with ID 'routePopularityTableBody'.
        // - Prepares table for route popularity data.
        const routePopularityBody = document.getElementById('routePopularityTableBody');
        
        // Updates route popularity table
        // - Built-in assignment to routePopularityBody.innerHTML using ternary operator.
        // - Maps data.routePopularity to table rows if exists and non-empty, else shows no-data message.
        // - Displays RouteName, StartLocation, Destination, Bookings.
        routePopularityBody.innerHTML = data.routePopularity && data.routePopularity.length > 0 ? 
            data.routePopularity.map(row => `
                <tr>
                    <td>${row.RouteName}</td>
                    <td>${row.StartLocation}</td>
                    <td>${row.Destination}</td>
                    <td>${row.Bookings}</td>
                </tr>
            `).join('') : '<tr><td colspan="4">No data available</td></tr>';
        
        // Declares route popularity chart variable
        // - User-defined constant named routePopularityChart using document.getElementById().
        // - Targets element with ID 'routePopularityChart'.
        // - Prepares container for pie chart display.
        const routePopularityChart = document.getElementById('routePopularityChart');
        
        // Updates route popularity chart
        // - Built-in conditional checking for data.routePopularity and non-empty array.
        // - Generates pie chart if data exists, else shows no-data message.
        // - Uses CSS gradients for visual representation.
        if (data.routePopularity && data.routePopularity.length > 0) {
            // Declares chart colors
            // - User-defined constant named colors with array of hex codes.
            // - Defines colors for pie chart segments.
            // - Uses multiple colors for variety.
            const colors = ['#4CAF50', '#FF5733', '#33C4FF', '#FFC107', '#800080'];
            
            // Declares total bookings
            // - User-defined constant named totalBookings using reduce().
            // - Sums parseInt(row.Bookings) from routePopularity, defaults to 1.
            // - Calculates total for percentage computation.
            const totalBookings = data.routePopularity.reduce((sum, row) => sum + parseInt(row.Bookings), 0) || 1;
            
            // Declares gradient array
            // - User-defined let variable named gradient, initialized as empty array.
            // - Stores CSS gradient definitions for pie chart.
            // - Builds segments dynamically.
            let gradient = [];
            
            // Declares current angle
            // - User-defined let variable named currentAngle, initialized to 0.
            // - Tracks starting angle for each pie segment.
            // - Increments for continuous segments.
            let currentAngle = 0;
            
            // Iterates through routes
            // - Built-in forEach on data.routePopularity with row and index parameters.
            // - Calculates angles for pie chart segments.
            // - Builds gradient for visual display.
            data.routePopularity.forEach((row, index) => {
                // Calculates percentage
                // - User-defined constant named percentage using row.Bookings and totalBookings.
                // - Divides bookings by total and multiplies by 100.
                // - Determines segment size in pie chart.
                const percentage = (row.Bookings / totalBookings) * 100;
                
                // Calculates angle
                // - User-defined constant named angle using percentage.
                // - Converts percentage to degrees by multiplying by 360/100.
                // - Defines segment size for pie chart.
                const angle = (percentage / 100) * 360;
                
                // Adds gradient segment
                // - Built-in push to gradient array with template literal.
                // - Uses colors[index % colors.length] for cycling colors.
                // - Defines color and angle range for segment.
                gradient.push(`${colors[index % colors.length]} ${currentAngle}deg ${currentAngle + angle}deg`);
                
                // Updates current angle
                // - Built-in assignment adding angle to currentAngle.
                // - Sets start for next segment.
                // - Ensures continuous pie chart.
                currentAngle += angle;
            });
            
            // Updates chart HTML
            // - Built-in assignment to routePopularityChart.innerHTML using template literal.
            // - Creates pie chart with CSS gradient and labels with colors.
            // - Displays route popularity distribution visually.
            routePopularityChart.innerHTML = `
                <div class="pie-chart" style="--colors: ${gradient.join(', ')};"></div>
                <div class="pie-labels">
                    ${data.routePopularity.map((row, index) => `
                        <div class="pie-label">
                            <span class="pie-label-color" style="background-color: ${colors[index % colors.length]};"></span>
                            ${row.RouteName}: ${row.Bookings}
                        </div>
                    `).join('')}
                </div>
            `;
        } else {
            // Sets no-data message
            // - Built-in assignment to routePopularityChart.innerHTML.
            // - Displays message if no route popularity data exists.
            // - Informs admins of missing data.
            routePopularityChart.innerHTML = '<p>No route popularity data available</p>';
        }
        
        // Declares drivers activity table variable
        // - User-defined constant named driversActivityBody using document.getElementById().
        // - Targets element with ID 'driversActivityTableBody'.
        // - Prepares table for driver activity data.
        const driversActivityBody = document.getElementById('driversActivityTableBody');
        
        // Ensures array data
        // - User-defined constant named staffActivityData using Array.isArray(), a built-in method.
        // - Returns data.driversActivity if array, else empty array.
        // - Prevents errors from non-array data.
        const staffActivityData = Array.isArray(data.driversActivity) ? data.driversActivity : [];
        
        // Updates drivers activity table
        // - Built-in assignment to driversActivityBody.innerHTML using ternary operator.
        // - Maps staffActivityData to table rows if non-empty, else shows no-data message.
        // - Displays StaffName, Role, TripsAssigned with defaults for missing data.
        driversActivityBody.innerHTML = staffActivityData.length > 0 ? 
            staffActivityData.map(row => `
                <tr>
                    <td>${row.StaffName || 'N/A'}</td>
                    <td>${row.Role || 'N/A'}</td>
                    <td>${row.TripsAssigned || 0}</td>
                </tr>
            `).join('') : '<tr><td colspan="3">No staff activity data available</td></tr>';
    } catch (error) {
        // Logs error
        // - Built-in console.error() with error object.
        // - Outputs fetch issues to developer tools.
        // - Helps diagnose network or JSON parsing problems.
        console.error('Error fetching revenue reports:', error);
    }
}

// Defines async function for user activity report
// - User-defined async function named fetchUserActivityReport, with two inputs: startDate and endDate (date strings).
// - Fetches user activity data and updates table in admin_reports.html.
// - Displays user actions like logins or bookings.
async function fetchUserActivityReport(startDate, endDate) {
    // Starts error handling block
    // - Built-in try-catch block to handle network or data issues.
    // - Ensures robust error handling during data fetching.
    // - Prevents crashes in report display.
    try {
        // Declares URL variable
        // - User-defined let variable named url with base path '../../../php/admin/fetchUserActivityReport.php'.
        // - Points to server endpoint for user activity data.
        // - Prepares to append date filters.
        let url = '../../../php/admin/fetchUserActivityReport.php';
        
        // Adds date filters
        // - Built-in conditional checking for startDate and endDate.
        // - Appends query parameters using encodeURIComponent().
        // - Filters report data by date range.
        if (startDate && endDate) {
            url += `?start=${encodeURIComponent(startDate)}&end=${encodeURIComponent(endDate)}`;
        }
        
        // Sends HTTP request
        // - User-defined constant named response using fetch().
        // - Sends GET request to constructed url.
        // - Retrieves user activity data from server.
        const response = await fetch(url);
        
        // Declares data variable
        // - User-defined constant named data using response.json().
        // - Parses JSON response into JavaScript object.
        // - Contains user activity details like ActivityID, Description.
        const data = await response.json();
        
        // Logs data for debugging
        // - Built-in console.log() with data object.
        // - Outputs user activity data to developer tools.
        // - Helps verify response content.
        console.log('User Activity Data:', data);
        
        // Declares table body variable
        // - User-defined constant named tableBody using document.getElementById().
        // - Targets element with ID 'userActivityTableBody'.
        // - Prepares table for data display.
        const tableBody = document.getElementById('userActivityTableBody');
        
        // Updates table content
        // - Built-in assignment to tableBody.innerHTML using ternary operator.
        // - Maps data.data to table rows if response_status is 'success' and data is non-empty array, else shows no-data message.
        // - Displays ActivityID, Description, Date, Time, WhoDidIt, Role.
        tableBody.innerHTML = data.response_status === 'success' && Array.isArray(data.data) && data.data.length > 0 ? 
            data.data.map(row => `
                <tr>
                    <td>${row.ActivityID}</td>
                    <td>${row.Description}</td>
                    <td>${row.Date}</td>
                    <td>${row.Time}</td>
                    <td>${row.WhoDidIt}</td>
                    <td>${row.Role}</td>
                </tr>
            `).join('') : '<tr><td colspan="6">No data available</td></tr>';
    } catch (error) {
        // Logs error
        // - Built-in console.error() with error object.
        // - Outputs fetch issues to developer tools.
        // - Helps diagnose network or JSON parsing problems.
        console.error('Error fetching user activity report:', error);
    }
}

// Defines async function for booking summary report
// - User-defined async function named fetchBookingSummaryReport, with two inputs: startDate and endDate (date strings).
// - Fetches booking summary data and updates table in admin_reports.html.
// - Displays overview of bookings like IDs and dates.
async function fetchBookingSummaryReport(startDate, endDate) {
    // Starts error handling block
    // - Built-in try-catch block to handle network or data issues.
    // - Ensures robust error handling during data fetching.
    // - Prevents crashes in report display.
    try {
        // Declares URL variable
        // - User-defined let variable named url with base path '../../../php/admin/fetchBookingSummaryReport.php'.
        // - Points to server endpoint for booking summary data.
        // - Prepares to append date filters.
        let url = '../../../php/admin/fetchBookingSummaryReport.php';
        
        // Adds date filters
        // - Built-in conditional checking for startDate and endDate.
        // - Appends query parameters using encodeURIComponent().
        // - Filters report data by date range.
        if (startDate && endDate) {
            url += `?start=${encodeURIComponent(startDate)}&end=${encodeURIComponent(endDate)}`;
        }
        
        // Sends HTTP request
        // - User-defined constant named response using fetch().
        // - Sends GET request to constructed url.
        // - Retrieves booking summary data from server.
        const response = await fetch(url);
        
        // Declares data variable
        // - User-defined constant named data using response.json().
        // - Parses JSON response into JavaScript object.
        // - Contains booking details like BookingID, CustomerID.
        const data = await response.json();
        
        // Logs data for debugging
        // - Built-in console.log() with data object.
        // - Outputs booking summary data to developer tools.
        // - Helps verify response content.
        console.log('Booking Summary Data:', data);
        
        // Declares table body variable
        // - User-defined constant named tableBody using document.getElementById().
        // - Targets element with ID 'bookingSummaryTableBody'.
        // - Prepares table for data display.
        const tableBody = document.getElementById('bookingSummaryTableBody');
        
        // Updates table content
        // - Built-in assignment to tableBody.innerHTML using ternary operator.
        // - Maps data.data to table rows if response_status is 'success' and data is non-empty array, else shows no-data message.
        // - Displays BookingID, CustomerID, ScheduleID, BookingDate, TravelDate.
        tableBody.innerHTML = data.response_status === 'success' && Array.isArray(data.data) && data.data.length > 0 ? 
            data.data.map(row => `
                <tr>
                    <td>${row.BookingID}</td>
                    <td>${row.CustomerID}</td>
                    <td>${row.ScheduleID}</td>
                    <td>${row.BookingDate}</td>
                    <td>${row.TravelDate}</td>
                </tr>
            `).join('') : '<tr><td colspan="5">No data available</td></tr>';
    } catch (error) {
        // Logs error
        // - Built-in console.error() with error object.
        // - Outputs fetch issues to developer tools.
        // - Helps diagnose network or JSON parsing problems.
        console.error('Error fetching booking summary report:', error);
    }
}

// Defines async function for booking status report
// - User-defined async function named fetchBookingStatusReport, with two inputs: startDate and endDate (date strings).
// - Takes two parameters: startDate and endDate (date strings).
// - Fetches maintenance data and updates a table in the DOM.
async function fetchMaintenanceReport(startDate, endDate) {
    // Fetch maintenance data
    // - A try-catch block for handling asynchronous operations.
    // - Contains a fetch request and response handling.
    // - Manages server communication and error cases.
    try {
        // Local variable to hold the API URL
        // - A let variable storing the base URL for the report.
        // - Points to a PHP endpoint for maintenance data.
        // - Appends date parameters if provided.
        let url = '../../../php/admin/fetchMaintenanceReport.php';
        
        // Add date filters to URL
        // - A conditional block checking for startDate and endDate.
        // - Appends query parameters using encodeURIComponent().
        // - Filters the report data by date range.
        if (startDate && endDate) {
            url += `?start=${encodeURIComponent(startDate)}&end=${encodeURIComponent(endDate)}`;
        }
        
        // Fetch request
        // - An asynchronous HTTP request using the fetch API.
        // - Sends a GET request to the constructed URL.
        // - Retrieves maintenance data from the server.
        const response = await fetch(url);
        
        // Local variable to hold parsed response data
        // - A constant storing the JSON-parsed response.
        // - Fetched via response.json().
        // - Contains the maintenance report data.
        const data = await response.json();
        
        // Log data for debugging
        // - A console log statement outputting the data.
        // - Displays maintenance data for debugging.
        // - Helps verify the response content.
        console.log('Maintenance Data:', data);
        
        // Local variable to reference the table body
        // - A constant targeting a DOM element with ID 'maintenanceReportTableBody'.
        // - Fetched via getElementById().
        // - Represents the table body for maintenance data.
        const tableBody = document.getElementById('maintenanceReportTableBody');
        
        // Update table content
        // - DOM manipulation setting innerHTML.
        // - Checks response_status, data array, and length to ensure valid data.
        // - Maps data to table rows or shows a no-data message.
        tableBody.innerHTML = data.response_status === 'success' && Array.isArray(data.data) && data.data.length > 0 ? 
            data.data.map(row => `
                <tr>
                    <td>${row.MaintenanceID}</td>
                    <td>${row.BusID}</td>
                    <td>${row.ServiceDone}</td>
                    <td>${row.ServiceDate}</td>
                    <td>${row.Cost}</td>
                    <td>${row.LSD}</td>
                    <td>${row.NSD}</td>
                    <td>${row.TechnicianID}</td>
                </tr>
            `).join('') : '<tr><td colspan="8">No data available</td></tr>';
    } catch (error) {
        // Error handling
        // - A catch block for fetch-related errors.
        // - Logs the error to the console for debugging.
        // - Ensures the application doesn’t crash on failure.
        console.error('Error fetching maintenance report:', error);
    }
}

// Async function to fetch booking status report
// - A user-defined async function named fetchBookingStatusReport.
// - Takes two parameters: startDate and endDate (date strings).
// - Reuses data from fetchRevenueReports to update a table and chart.
// - Displays booking status counts like Confirmed or Cancelled.
async function fetchBookingStatusReport(startDate, endDate) {
    // Triggers revenue reports
    // - Built-in await expression calling user-defined fetchRevenueReports.
    // - Passes startDate and endDate to fetch data.
    // - Updates booking status table and chart via revenue data.
    await fetchRevenueReports(startDate, endDate);
}

// Defines async function for bus utilization report
// - User-defined async function named fetchBusUtilizationReport, with two inputs: startDate and endDate (date strings).
// - Reuses data from fetchRevenueReports to update table.
// - Displays bus usage metrics like trips and seats booked.
async function fetchBusUtilizationReport(startDate, endDate) {
    // Triggers revenue reports
    // - Built-in await expression calling user-defined fetchRevenueReports.
    // - Passes startDate and endDate to fetch data.
    // - Updates bus utilization table via revenue data.
    await fetchRevenueReports(startDate, endDate);
}

// Defines async function for route popularity report
// - User-defined async function named fetchRoutePopularityReport, with two inputs: startDate and endDate (date strings).
// - Reuses data from fetchRevenueReports to update table and chart.
// - Displays popular routes based on booking counts.
async function fetchRoutePopularityReport(startDate, endDate) {
    // Triggers revenue reports
    // - Built-in await expression calling user-defined fetchRevenueReports.
    // - Passes startDate and endDate to fetch data.
    // - Updates route popularity table and chart via revenue data.
    await fetchRevenueReports(startDate, endDate);
}

// Defines async function for drivers activity report
// - User-defined async function named fetchDriversActivityReport, with two inputs: startDate and endDate (date strings).
// - Reuses data from fetchRevenueReports to update table.
// - Displays driver and staff activity like trips assigned.
async function fetchDriversActivityReport(startDate, endDate) {
    // Triggers revenue reports
    // - Built-in await expression calling user-defined fetchRevenueReports.
    // - Passes startDate and endDate to fetch data.
    // - Updates drivers activity table via revenue data.
    await fetchRevenueReports(startDate, endDate);
}