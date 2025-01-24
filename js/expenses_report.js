$(document).ready(function () {
    // When the 'from_date' changes, set 'to_date' minimum value and reset it if necessary
    $('#from_date').change(function () {
        let from_date = $('#from_date').val();
        let to_date = $('#to_date').val();
        
        // If 'from_date' is greater than 'to_date', reset 'to_date'
        if (from_date > to_date) {
            $('#to_date').val('');
        }

        // Set the minimum 'to_date' to the selected 'from_date'
        $('#to_date').attr('min', from_date);
    });

    // When the 'expenses_report_btn' button is clicked
    $('#expenses_report_btn').click(function (event) {
        event.preventDefault();

        // Get the 'from_date' and 'to_date' values
        let from_date = $('#from_date').val();
        let to_date = $('#to_date').val();
        
        // Create the data object
        let data = {
            'from_date': from_date,
            'to_date': to_date
        };

        // Check if both dates are filled
        if (from_date !== '' && to_date !== '') {
            // Call the server-side table function with the data and API endpoint
            serverSideTable('#accounts_expenses_table', data, 'api/report_files/get_expenses_report.php');
        } else {
            // Show error message if dates are not filled
            swalError('Please Fill Dates!', 'Both From and To dates are required.');
        }
    });
});
