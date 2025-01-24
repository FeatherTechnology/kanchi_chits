const cus_name = new Choices('#cus_name', {
    removeItemButton: true,
    noChoicesText: 'Select Customer Name',
    allowHTML: true,
    shouldSort: false // Disable default sorting
});

$(document).ready(function () {
    $(document).on('click', '.add_loan, .back_btn', function () {
        //swapTableAndCreation();
        $('.auction_table_content').show();
        $('.auction_detail_content,.back_btn').hide();

        $('.today').show();
        $('.this_month').show();
        // getAuctionTodayTable()
        // getAuctionMonthTable()
        getAuctionTable();
        $('#submit_cus_map').prop('disabled', false);
        $('#pageHeaderName').text(` - Auction`);

        localStorage.setItem('dashboardAuc', '');
    });
    $(document).on('click', '.today', function () {
        getAuctionTodayTable()
    });
    $(document).on('click', '.this_month', function () {
        getAuctionMonthTable()
    });
    $(document).on('click', '#back_to_list', function (event) {
        event.preventDefault();
     //   auctionStarted = false;
        // Hide back button
        $('#back_to_list').hide();

        // Show auction details content and back button
        $('.auction_detail_content, .back_btn').show();

        // Hide the modal
        $('#add_cus_map_modal').hide();
        // $('#cus_mapping_table tbody').empty();
        $('.auction_close').addClass('d-none');
       $('#auction_round1').addClass('d-none');
        $('#auction_round2').addClass('d-none');
        $('#auction_round3').addClass('d-none');
        $('#submit_cus_map').prop('disabled', false);
        // Fetch the values from the input fields
        var groupId = $('#group_id').val(); // Get the Group ID
        var groupName = $('#grp_name').val(); // Get the Group Name
        var chitValue = $('#ch_value').val(); // Get the Chit Value (make sure this input exists)
        const formattedChitValue = moneyFormatIndia(chitValue.toString());
        // Update the page header with the fetched values
        $('#pageHeaderName').text(` - Auction - Group ID: ${groupId}, Group Name: ${groupName}, Chit Value: ${formattedChitValue}`);
    });
    /////////////////////////////////////////////////////////////////////////////////// Action Round Button///////////////////////////////
    $('#auction_round1').on('click', function (e) {
        e.preventDefault();
        let isValid = true; // Flag to track if all fields are valid

       
            $(this).removeClass('btn-primary').addClass('btn-success'); // Use Bootstrap class for green color

            // Enable auction_round2
            $('#auction_round2').prop('disabled', false).removeClass('d-none'); // Remove d-none class to show the button
    });

    $('#auction_round2').on('click', function (e) {
        // Change the background color to green
        e.preventDefault();
        $(this).removeClass('btn-primary').addClass('btn-success'); // Use Bootstrap class for green color

        // Enable auction_round2
        $('#auction_round3').prop('disabled', false).removeClass('d-none'); // Remove d-none class to show the button
    });
    let allowCusNameClick = true;
    //  let allowDeleteClick = true;

    $('#auction_round3').on('click', function (e) {
        e.preventDefault(); // Prevent default action
        $('.delete-icon').hide(); // Hide delete icons
        $(this).removeClass('btn-primary').addClass('btn-success'); // Change button color
        $('#submit_cus_map').prop('disabled', true); // Disable submit button
        $('.auction_close').prop('disabled', false).removeClass('d-none'); // Show auction close button

        // Disable the click event for customer name column
        allowCusNameClick = false;
    });
    //////////////////////////////////////////////////////////// Action Round Button ///////////////////////////////////
    $(document).on('click', '.auctionListBtn', function (event) {
        event.preventDefault();

        let groupId = $(this).attr('data-grpid'); // First part is group ID
        let groupName = $(this).attr('data-grpname'); // Second part is group name
        let chitValue = $(this).attr('data-chitval'); // Third part is chit value

        if (chitValue !== undefined && chitValue !== null) {
            // Convert chitValue to a string before formatting
            const formattedChitValue = moneyFormatIndia(chitValue.toString());

            // Update the page header with the dynamic values
            $('#pageHeaderName').text(` - Auction - Group ID: ${groupId}, Group Name: ${groupName}, Chit Value: ${formattedChitValue}`);
        } else {
            console.error('Chit value is undefined or null');
            // Handle the case where chitValue is not provided
        }

        // Hide and show the necessary sections
        $('.auction_table_content').hide();
        $('.auction_detail_content,.back_btn').show();
        $('#back_to_list').hide();
        $('.today').hide();
        $('.this_month').hide();
        $('#add_cus_map_modal').hide();
        //  getModalAttr();
        // Fetch auction details based on the selected group ID
        fetchAuctionDetails(groupId);
        switchGroup(groupId)
    });


    ///////////////////////////////////////////////////////Auction Modal Start/////////////////////////////////////////////////////////////////  
    $(document).on('click', '.auctionBtn', function (event) {
        event.preventDefault();
    
        var uniqueMonth = $(this).data('value');
        var [groupId, auction_month] = uniqueMonth.split('_');
    
        // Fetch the auction details and validate date
        $.post('api/auction_files/validate_auction_date.php', { group_id: groupId, auction_month: auction_month }, function (response) {
    
            if (response.is_valid && response.auction_detail) {
                var auctionDetail = response.auction_detail;
                var auctionDate = auctionDetail.date; // Fetch auction date from response
                
                // Get current date and format both auctionDate and currentDate to YYYY-MM-DD
                var currentDate = new Date();
                var currentDateString = currentDate.toISOString().split('T')[0]; // Extract YYYY-MM-DD part of current date
                var auctionDateString = new Date(auctionDate).toISOString().split('T')[0]; // Convert auction date to YYYY-MM-DD
    
                // Compare only the date parts
                if (auctionDateString < currentDateString) {
                    // Auction date has passed, show warning
                    swalError('Expired Auction', 'Please Reschedule the Auction Date.');
                    return; // Exit, no further action
                } else {
                    // Continue with showing the modal and auction details
    
                    $('#pageHeaderName').text(` - Auction`);
                    $('#add_cus_map_modal').show();
                    $('.auction_detail_content').hide();
                    $('#back_to_list').show();
                    $('.back_btn').hide();
    
                    var [date, id, low_value, high_value, group_name, chit_value, branch_name] = [
                        auctionDetail.date,
                        auctionDetail.id,
                        auctionDetail.low_value,
                        auctionDetail.high_value,
                        auctionDetail.group_name,
                        auctionDetail.chit_value,
                        auctionDetail.branch_name,
                    ];
    
                    // Convert date to dd-mm-yyyy format
                    var formattedDate = formatDate(date);
    
                    // Set attributes for submission
                    $('#submit_cus_map').attr('data-group_id', groupId);
                    $('#submit_cus_map').attr('data-id', id);
                    $('#submit_cus_map').attr('data-date', formattedDate);
                    $('#submit_cus_map').attr('data-high_value', high_value);
                    $('#submit_cus_map').attr('data-low_value', low_value);
    
                    // Set the values in the modal form fields
                    $('#group_id').val(groupId);
                    $('#grp_name').val(group_name);
                    $('#branch_name').val(branch_name);
                    $('#ch_value').val(chit_value);
                    var moneyChitVal = moneyFormatIndia(chit_value);
                    $('#chit_val').val(moneyChitVal);
                    $('#auction_date').val(formattedDate);
                    var auctionTime = `${auctionDetail.hours}:${auctionDetail.minutes < 10 ? '0' + auctionDetail.minutes : auctionDetail.minutes} ${auctionDetail.ampm}`;
                    $('#auction_time').val(auctionTime);
                    $('#grp_month').val(auction_month);
                    $('#low_value').val(moneyFormatIndia(low_value));
                    $('#high_value').val(moneyFormatIndia(high_value));
    
                    // Set attributes for auction close button
                    $('.auction_close').attr('data-group_id', groupId);
                    $('.auction_close').attr('data-date', formattedDate);
                    $('.auction_close').attr('data-id', id);
    
                    // Fetch customer names based on groupId
                    getCusName(groupId, auction_month);
                    resetRoundButtons();
                  //  $('#auction_round1').prop('disabled', true);
                    $('.auction_close').prop('disabled', true);
                    $('#auction_start').prop('disabled', false);
                    if ($('#cus_mapping_table tbody tr').length > 0) {
                        $('.auction_close').removeClass('d-none'); // Show button if rows exist
                        $('#auction_round1').removeClass('d-none');
                        $('#auction_round2').removeClass('d-none');
                        $('#auction_round3').removeClass('d-none');
                    } else {
                        $('.auction_close').addClass('d-none'); // Hide button if no rows
                        $('#auction_round1').addClass('d-none');
                        $('#auction_round2').addClass('d-none');
                        $('#auction_round3').addClass('d-none');
                    }
                }
            }
        }, 'json');
    
        // Function to format date to dd-mm-yyyy
        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            var dateParts = dateString.split('-'); // Assuming input format is 'yyyy-mm-dd'
            return `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`; // Return as 'dd-mm-yyyy'
        }
    });

    let auctionStarted = false;
    $(document).on('click', '#auction_start', function (event) {
        event.preventDefault();
        let group_id = $('#group_id').val();
        let auction_month = $('#grp_month').val();
    
        $.post('api/auction_files/validate_auction_start.php', { group_id: group_id, auction_month: auction_month }, function (response) {
            if (response.is_valid) {
                swalSuccess('Success', "Auction Can Start Now");
    
                // Set the auctionStarted flag to true
                auctionStarted = true;
    
                // Enable all existing input fields by removing the readonly attribute
                // Now including "Company" rows as well
                $('#cus_mapping_table tbody input.form-control').prop('readonly', false); // Make all rows editable
                
                // Disable the auction start button once the auction is started
                $('#auction_start').prop('disabled', true);
    
                // Enable auction rounds
                $('#auction_round1').prop('disabled', false);
    
                // Enable auction close button
                $('.auction_close').prop('disabled', false);
    
            } else {
                var auctionDate = response.auction_detail ? response.auction_detail.date : 'N/A';
                var formattedAuctionDate = formatDate(auctionDate); // Format the auction date
    
                var auctionTime = response.auction_detail
                    ? `${String(response.auction_detail.hours).padStart(2, '0')}:${String(response.auction_detail.minutes).padStart(2, '0')} ${response.auction_detail.ampm.toUpperCase()}`
                    : 'N/A';
    
    
                swalError('Please Wait', `The auction will open after ${auctionTime} on ${formattedAuctionDate}`);
            }
        }, 'json');
    });
    
    // Object to store customer mapping data for each group
var groupCustomerData = {};

// Function to handle adding customers to the table
$('#submit_cus_map').on('click', function (e) {
    e.preventDefault();

    var selectedCustomers = $('#cus_name').val(); // Get the selected customer IDs
    var groupId = $(this).attr('data-group_id'); // Get the group ID
  //  var auctionStarted = false; // Auction not started initially

    if (!groupCustomerData[groupId]) {
        groupCustomerData[groupId] = []; // Initialize array for this group if it doesn't exist
    }

    if (selectedCustomers && selectedCustomers.length > 0) {
        selectedCustomers.forEach(function (cusId) {
            var cusName = $('#cus_name option[value="' + cusId + '"]').text().trim();
            var uniqueIdentifier = cusId + '_' + new Date().getTime();

            // Check if this customer already exists in the current group data
            var existingCustomer = groupCustomerData[groupId].find(c => c.cusId === cusId);

            // Append the selected customer to the table if not already added
            if (!existingCustomer) {
                $('#cus_mapping_table tbody').append(`
                    <tr data-cus-id="${cusId}" data-unique-id="${uniqueIdentifier}">
                        <td>${$('#cus_mapping_table tbody tr').length + 1}</td>
                        <td class="cus-name-column">${cusName}</td>
                        <td class="value-column">
                            <div class="input-container">
                                <input type="number" name="cus_value[]" class="form-control" data-cus-id="${cusId}" value="" placeholder="Enter value" readonly>
                                <i class="icon-delete delete-icon" style="width:25px;"></i>
                            </div>
                        </td>
                    </tr>
                `);
                if (auctionStarted) {
                                    $(`#cus_mapping_table tbody tr[data-unique-id="${uniqueIdentifier}"] input.form-control`).prop('readonly', false);
                                }
                // Add this customer to the groupCustomerData array for the current group
                groupCustomerData[groupId].push({ cusId: cusId, cusName: cusName, values: [] });
            }
        });

        // Reset the selection
        cus_name.removeActiveItems();
        getCusName(groupId);

        if ($('#cus_mapping_table tbody tr').length > 0) {
            $('.auction_close').removeClass('d-none');
            $('#auction_round1').removeClass('d-none');
            $('#auction_round2').removeClass('d-none');
            $('#auction_round3').removeClass('d-none');
        }

        updateDeleteIcon();
        resetRoundButtons();
    } else {
        swalError('Warning', 'Please select at least one customer before submitting.');
    }
});

$(document).on('click', '#cus_mapping_table tbody tr .cus-name-column', function () {
    if (!allowCusNameClick) {
        return; // Exit if the click event is disabled
    }
    
    var $row = $(this).closest('tr');
    var cusId = $row.data('cus-id');
    var groupId = $('#group_id').val();
    // Find the value column in the current row
    var $valueColumn = $row.find('.value-column');
    
    // Check if the last input in the row is empty
    var $lastInput = $valueColumn.find('input:last');
    if ($lastInput.length > 0 && $lastInput.val() === '') {
        swalError('Input Required', 'Please fill in the previous value before adding a new one.');
        return; // Exit the function
    }
    
    // Generate a unique ID for the new input
    var uniqueIdentifier = cusId + '_' + new Date().getTime();
    
    // Append a new input field after the existing one
    $valueColumn.append(`
        <div class="input-container mt-2">
            <input type="number" name="cus_value[]" class="form-control" data-cus-id="${cusId}" value="" placeholder="Enter value">
            <i class="icon-delete delete-icon" style="width:25px;"></i>
        </div>
    `);
    
    var customer = groupCustomerData[groupId].find(c => c.cusId === cusId);
    if (customer) {
        var inputIndex = $(this).closest('tr').index();
        customer.values[inputIndex] = value; // Update the value for the corresponding input
    }
    
    // Reset the border and update delete icon
    $row.find('input[name="cus_value[]"]').css('border', '');
    updateDeleteIcon();
    resetRoundButtons();
});

function switchGroup(groupId) {
    // Clear the current table
    $('#cus_mapping_table tbody').empty();

    // Check if there is data for the selected group
    if (groupCustomerData[groupId] && groupCustomerData[groupId].length > 0) {
        groupCustomerData[groupId].forEach(function (customer, index) {
            var uniqueIdentifier = customer.cusId + '_' + new Date().getTime();

            // Append each saved customer with their saved values or an empty input if no values exist
          
            $('#cus_mapping_table tbody').append(`
                <tr data-cus-id="${customer.cusId}" data-unique-id="${uniqueIdentifier}">
                    <td>${index + 1}</td>
                    <td class="cus-name-column">${customer.cusName}</td>
                    <td class="value-column">
                        ${customer.values.length > 0 ? customer.values.map((value, idx) => `
                            <div class="input-container mt-2">
                                <input type="number" name="cus_value[]" class="form-control" data-cus-id="${customer.cusId}" value="${value}" placeholder="Enter value" readonly>
                                <i class="icon-delete delete-icon" style="width:25px;"></i>
                            </div>
                        `).join('') : `
                            <div class="input-container mt-2">
                                <input type="number" name="cus_value[]" class="form-control" data-cus-id="${customer.cusId}" value="" placeholder="Enter value" readonly>
                                <i class="icon-delete delete-icon" style="width:25px;"></i>
                            </div>
                        `}
                    </td>
                </tr>
            `);
            if (auctionStarted) {
                $(`#cus_mapping_table tbody tr[data-unique-id="${uniqueIdentifier}"] input.form-control`).prop('readonly', false);
            }
        });

        updateDeleteIcon();
        resetRoundButtons();
    }
}


// Function to update the delete icons
function updateDeleteIcon() {
    $('#cus_mapping_table tbody tr').each(function () {
        $(this).find('.delete-icon').remove();
        if (!$(this).hasClass('company-row')) {
            $(this).find('.value-column .input-container:last').append('<i class="icon-delete delete-icon" style="width:25px;"></i>');
        }
    });
}

    $(document).on('click', '.icon-delete', function () {
        // if (!allowDeleteClick) {
        //     return; // Exit if the click event is disabled
        // }
        var $inputContainer = $(this).closest('.input-container'); // Find the closest input container
        var $row = $(this).closest('tr'); // Find the row for the clicked delete icon
        var cusId = $row.attr('data-cus-id');
        var groupId = $('#group_id').val();
        // Update the groupCustomerData array by removing the customer
        if (groupCustomerData[groupId]) {
            groupCustomerData[groupId] = groupCustomerData[groupId].filter(
                (customer) => customer.cusId !== cusId
            );
        }

        // Remove the specific input container
        $inputContainer.remove();

        // Check if the row has any input containers left
        if ($row.find('.input-container').length === 0) {
            // If no input containers are left, remove the entire row
            $row.remove();

        }

        // After deletion, update the row numbers for all rows
        $('#cus_mapping_table tbody tr').each(function (index) {
            $(this).find('td:first').text(index + 1); // Update row numbers
        });
        if ($('#cus_mapping_table tbody tr').length > 0) {
            $('.auction_close').removeClass('d-none'); // Show button if rows exist
            $('#auction_round1').removeClass('d-none');
            $('#auction_round2').removeClass('d-none');
            $('#auction_round3').removeClass('d-none');
        } else {
            $('.auction_close').addClass('d-none'); // Hide button if no rows
            $('#auction_round1').addClass('d-none');
            $('#auction_round2').addClass('d-none');
            $('#auction_round3').addClass('d-none');
        }
        // After deletion, ensure the delete icon is updated in the new last input container of each row
        updateDeleteIcon();
        resetRoundButtons();
    });


    let hasCompany = false;
    function resetRoundButtons() {
        let round1Value = 0;
        let round2Value = 0;
    
        // Iterate through each row to calculate the total values for both customer and company
        $('#cus_mapping_table tbody tr').each(function () {
            const cusValue = parseFloat($(this).find('input[name="cus_value[]"]').val()) || 0;
    
            // Add the value to the corresponding round value
            if (cusValue > 0) {
                round1Value += cusValue; // Assuming the first button is based on total values
            }
        });
    
        // Update the round buttons based on the collected values
        if (round1Value > 0) {
            $('#auction_round1').removeClass('btn-success').addClass('btn-primary'); // Reset to original color
            $('#auction_round2').removeClass('btn-success').addClass('btn-primary'); // Reset to original color
            $('#auction_round3').removeClass('btn-success').addClass('btn-primary'); // Reset to original color
            $('#auction_round2').prop('disabled', true); // Disable and hide button 2
            $('#auction_round3').prop('disabled', true); // Disable and hide button 3
        } else {
            $('#auction_round1').removeClass('btn-success').addClass('btn-primary'); // Reset to original color
            $('#auction_round2').removeClass('btn-success').addClass('btn-primary'); // Reset to original color
            $('#auction_round3').removeClass('btn-success').addClass('btn-primary'); // Reset to original color
            $('#auction_round2').prop('disabled', true); // Disable and hide button 2
            $('#auction_round3').prop('disabled', true); // Disable and hide button 3
        }
    }
    
    $(document).on('change', '#cus_mapping_table tbody input[type="number"]', function () {
        var inputValue = parseFloat($(this).val());
        var submitBtn = $('#submit_cus_map');
        var lowValue = parseFloat(submitBtn.attr('data-low_value'));
        var highValue = parseFloat(submitBtn.attr('data-high_value')); // Use a high number if highValue is NaN
    
        // Format lowValue and highValue using moneyFormatIndia
        var formattedLowValue = moneyFormatIndia(lowValue);
        var formattedHighValue = moneyFormatIndia(highValue);
    
        // Check if the input value is within the specified range
        if (inputValue < lowValue || inputValue > highValue) {
            swalError('Warning', `Please enter a value between ${formattedLowValue} and ${formattedHighValue}.`);
            $(this).val(''); // Clear the invalid value
            return;
        }
    
        var isValid = true;
        var isUnique = true; // Flag for uniqueness check
        var hasPreviousValues = false; // Flag to check if there are previous values
    
        // Skip validation if the row belongs to "Company"
        var isCompanyRow = $(this).closest('tr').hasClass('company-row');
        if (isCompanyRow) {
            return; // Exit validation for "Company" row
        }
    
        // Validate against all previous input values in the table
        $('#cus_mapping_table tbody input[type="number"]').each(function () {
            var prevValue = parseFloat($(this).val());
    
            // Skip the current input field value from comparison and also skip "Company" rows
            if (this !== event.target && !isNaN(prevValue) && !$(this).closest('tr').hasClass('company-row')) {
                hasPreviousValues = true; // Set flag since we found a previous value
    
                // Check for uniqueness
                if (inputValue === prevValue) {
                    isUnique = false; // Found a duplicate value
                }
    
                // Check if the current input value is less than or equal to any previous value
                if (inputValue <= prevValue) {
                    isValid = false; // Input is not valid
                }
            }
        });
    
        // Show warning if the value must be greater than all previous values (only if previous values exist)
        if (hasPreviousValues && !isValid) {
            swalError('Warning', 'Please enter a higher bid value than the previous one.');
            $(this).val(''); // Clear the invalid value
            return;
        }
    
        // Show warning if the value is not unique
        if (!isUnique) {
            swalError('Warning', 'The bid value must be unique for each entry.');
            $(this).val(''); // Clear the invalid value
            return;
        }
    
        // If all validations pass, make the input readonly
        $(this).prop('readonly', true);
    
        // Call the function to highlight the highest value
        highlightHighestValue();
    
        // Limit to two inputs per customer
        manageCustomerInputs($(this));
    })

    // Function to highlight the highest value
    function highlightHighestValue() {
        var highestValue = -Infinity;
        var highestInput = null;

        // Iterate over each input to find the highest value
        $('#cus_mapping_table tbody input[type="number"]').each(function () {
            var value = parseFloat($(this).val());
            if (!isNaN(value) && value > highestValue) {
                highestValue = value;
                highestInput = $(this); // Track the input with the highest value
            }
        });

        // Reset the background color of all inputs
        $('#cus_mapping_table tbody input[type="number"]').css('background-color', '');

        // Apply a color highlight to the input with the highest value
        if (highestInput) {
            highestInput.css('background-color', '#D4EDDA'); // Use light green color
        }
    }

    // Function to manage inputs for each customer
    function manageCustomerInputs($input) {
        var cusId = $input.data('cus-id'); // Get the customer ID from the input field

        // Find all input fields for this customer
        var $inputs = $('#cus_mapping_table tbody input[type="number"][data-cus-id="' + cusId + '"]');

        // If there are more than 2 inputs, remove the oldest one
        if ($inputs.length > 2) {
            $inputs.first().parent().remove(); // Remove the oldest input field
        }
    }
   

    $(document).on('click', '.auction_close', function (e) {
        e.preventDefault();
        let group_id = $(this).attr('data-group_id');
        let date = $(this).attr('data-date');
        let id = $(this).attr('data-id');
    
        // Collect table data
        let tableData = [];
        let overallMaxValue = -Infinity; // Initialize to the lowest possible value
    
        $('#cus_mapping_table tbody tr').each(function () {
            let cusId = $(this).data('cus-id');
            let values = $(this).find('input[name="cus_value[]"]').map(function () {
                return $(this).val(); // Collect all values for this customer/company
            }).get(); // Get values as an array
    
            // Convert values to numbers and filter valid entries
            values = values.map(Number).filter(value => !isNaN(value) && value !== ''); // Filter out invalid values
    
            // Add customer/company values to the table data and calculate the max value
            values.forEach(value => {
                tableData.push({
                    cus_id: cusId, // Customer or Company ID
                    value: value, // Individual value
                    group_id: group_id,
                    date: date,
                    id: id
                });
                // Update overall maximum value for both customer and company values
                overallMaxValue = Math.max(overallMaxValue, value);
            });
        });
    
        // Format the max value for display
        let maxValue = moneyFormatIndia(overallMaxValue);
    
        // Use swalConfirm to show confirmation alert
        swalConfirm(
            'Do you want to close the auction?',
            `The Final value will be ${maxValue}`, // Use the highest value (overallMaxValue)
            closeAuction,
            { group_id: group_id, date: date, id: id, tableData: tableData }
        );
    });
    
    function closeAuction(data) {
        $.ajax({
            url: 'api/auction_files/insert_auction_list.php',
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({ data: data.tableData }), // Send all entries for each customer/company
            success: function (response) {
                if (response.success) {
                    swalSuccess('Success', "Auction Completed successfully");
                    $('#add_cus_map_modal').hide();
                    $('#add_Calculation_modal').modal('show');
                    calculationModal(data.group_id, data.date);
                    fetchAuctionDetails(data.group_id);
                }
            },
        });
    }
    
    $(document).on('keyup', '#cus_mapping_table tbody input[type="number"]', function () {
        // Call the function to reset round buttons when the value changes
        resetRoundButtons();
    });
    
    //////////////////////////////////////////////////////////Auction Modal End//////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////PostPone Modal Start//////////////////////////////////////////////////////////////
    $(document).on('click', '.postponeBtn', function () {
        var dataValue = $(this).data('value');
        var parts = dataValue.split('_');
        var groupId = parts[0];
        var auctionMonth = parts[1];

        $('#mapping_id').val(groupId);
        $('#date_name_edit').val(auctionMonth);

        $('#add_pos_modal').modal('show');
        populateDates();
    });

    $('#submit_postpone').click(function (event) {
        event.preventDefault();
        var selectedDate = $('#grp_date').val();
        var groupId = $('#mapping_id').val();
        var auctionMonth = $('#date_name_edit').val();


        if (selectedDate === "") {
            swalError('Error', 'Please Select a Date');
            $('#grp_date').css('border-color', 'red'); // Highlight the input field with a red border
            return;
        }

        // Remove the red border if a valid date is selected
        $('#grp_date').css('border-color', '');

        // Convert selectedDate to the correct format (YYYY-MM-DD)
        var today = new Date();
        var year = today.getFullYear();
        var month = String(today.getMonth() + 1).padStart(2, '0');
        var newDate = year + '-' + month + '-' + String(selectedDate).padStart(2, '0');

        // Send the data to the server using AJAX
        $.ajax({
            url: 'api/auction_files/update_auction_date.php',
            method: 'POST',
            data: {
                group_id: groupId,
                new_date: newDate,
                auction_month: auctionMonth
            },
            success: function (response) {
                swalSuccess('Success', "Auction Reschedule Successfully");
                $('#add_pos_modal').modal('hide');
                fetchAuctionDetails(groupId)
            },

        });
    });
    /////////////////////////////////////////////////////////////////////////////////PostPone Modal end//////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////View Modal Start///////////////////////////////////////
    
    $(document).on('click', '.viewBtn', function (event) {
        event.preventDefault();
        $('#add_view_modal').modal('show');
    
        var uniqueValue = $(this).data('value');
        var [groupId, date] = uniqueValue.split('_');
    
        // Fetch data from server
        $.post('api/auction_files/auction_close_view.php', {
            group_id: groupId,
            date: date
        }, function (response) {
            if (response.status === 'success') {
                var data = response.data;
                var tableBody = $('#view_table tbody');
                tableBody.empty(); // Clear any existing rows
    
                // Find the maximum numeric value among all rows, including the company
                var maxValue = Math.max(...data.map(row => parseFloat(row.value)));
    
                // Populate table with data
                $.each(data, function (index, row) {
                    var formattedValue = moneyFormatIndia(row.value);
                    var isMaxValue = parseFloat(row.value) === maxValue;
    
                    // Add a class 'highlight-row' if it has the maximum value
                    var rowHtml = `<tr class="${isMaxValue ? 'highlight-row' : ''}">
                        <td>${index + 1}</td>
                        <td>${row.customer_name}</td>
                        <td>${formattedValue}</td>
                    </tr>`;
                    tableBody.append(rowHtml);
                });
            } else {
                alert('Error: ' + response.message); // Show error message
            }
        }, 'json');
    });
    


    ////////////////////////////////////////////////////////////////View Modal End////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////CalCulation Modal Start///////////////////////////////////////////////////////
    $(document).on('click', '.calculateBtn', function (event) {
        event.preventDefault();
        $('#add_Calculation_modal').modal('show');
        let uniqueValue = $(this).attr('data-value'); // This gets the unique value for fetching data
        calculation(uniqueValue);

    });
    $(document).on('click', '#print_cal', function () {

        const chitValue = $('#chit_value').val().replace(/,/g, ''); // Ensure no extra commas
        const commission = $('#Commission').val().replace(/,/g, '');
        const auctionValue = $('#auction_value').val().replace(/,/g, '');
        const chitAmount = $('#chit_amount').val().replace(/,/g, '');
        const totalValue = $('#total_value').val().replace(/,/g, '');


        const formattedChitValue = moneyFormatIndia(chitValue);
        const formattedCommission = moneyFormatIndia(commission);
        const formattedAuctionValue = moneyFormatIndia(auctionValue);
        const formattedChitAmount = moneyFormatIndia(chitAmount);
        const formattedTotalValue = moneyFormatIndia(totalValue);

        // Create the HTML content with formatted values
        const content = `
        <div id="print_content" style="text-align: center;">
            <h2 style="margin-bottom: 20px; display: flex; align-items: center; justify-content: center;">
                <img src="img/pothigai_logo.jpg" style="margin-right: 10px;" class="img">
               
            </h2>
            <table style="margin: 0 auto; border-collapse: collapse; width: 25%;">
                <tr>
                    <td><strong>Group Name</strong></td>
                    <td>${$('#group_name').val()}</td>
                </tr>
                <tr>
                    <td><strong>Auction Month</strong></td>
                    <td>${$('#auction_month').val()}</td>
                </tr>
                <tr>
                    <td><strong>Date</strong></td>
                    <td>${$('#cal_date').val()}</td>
                </tr>
                <tr>
                    <td><strong>Chit Value</strong></td>
                    <td>${formattedChitValue}</td>
                </tr>
                <tr>
                    <td><strong>Auction Value</strong></td>
                    <td>${formattedAuctionValue}</td>
                </tr>
                <tr>
                    <td><strong>Commission</strong></td>
                    <td>${formattedCommission}</td>
                </tr>
                <tr>
                    <td><strong>Total Value</strong></td>
                    <td>${formattedTotalValue}</td>
                </tr>
                <tr>
                    <td><strong>Chit Amount</strong></td>
                    <td>${formattedChitAmount}</td>
                </tr>
            </table>
        </div>
    `;

        const tempDiv = $('<div>').html(content).css({
            position: 'absolute',
            top: '-500px',
            left: '-500px',
            width: '800px',  // Adjust width
            height: 'auto',  // Adjust height or set a specific height like '600px'
            padding: '20px', // Optional: add padding for better layout in the image
            backgroundColor: '#fff', // Ensure background is white (or any color you prefer)
            textAlign: 'center', // Center-aligns the content
            fontFamily: 'Arial, sans-serif' // Optional: for better font rendering
        }).appendTo('body');

        html2canvas(tempDiv[0], {
            scale: 2,  // Increase the scale factor to improve the resolution
            width: tempDiv.outerWidth(), // Set canvas width to the width of the div
            height: tempDiv.outerHeight() // Set canvas height to the height of the div
        }).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const link = document.createElement('a');
            link.href = imgData;
            link.download = 'calculation_details.png';
            link.click();
            tempDiv.remove();
        }).catch(err => {
            console.error('Error generating image:', err);
        });
    });

    ////////////////////////////////////////////////////////////////////CalCulation Modal End///////////////////////////////////////////////
});

$(function () {
    getAuctionTable();
});

function getAuctionTable() {
    let params = { 'type': '' };
    serverSideTable('#auction_list_table', params, 'api/auction_files/auction_list.php');

    $('#auction_list_table').on('init.dt', function () {
        checkDashboardData(); //call function after the table loaded.
    });
}

function checkDashboardData() {
    let fromDashboard = localStorage.getItem('dashboardAuc');
    if (fromDashboard) {
        let links = document.querySelectorAll('.auctionListBtn');

        links.forEach(link => {
            if (link.getAttribute('data-grpid') == fromDashboard) {
                $(link).trigger('click');
            }
        });
    }
}

function getAuctionMonthTable() {
    let params = { 'type': 'month' };
    serverSideTable('#auction_list_table', params, 'api/auction_files/auction_list.php');
}
function getAuctionTodayTable() {
    let params = { 'type': 'today' };
    serverSideTable('#auction_list_table', params, 'api/auction_files/auction_list.php');
}
function fetchAuctionDetails(groupId) {
    $.ajax({
        url: 'api/auction_files/view_auction_list.php', // Update this with the correct path to your PHP script
        type: 'POST',
        dataType: 'json',
        data: {
            group_id: groupId
        },
        success: function (response) {
            if (response.length > 0) {
                var tbody = $('#auction_table tbody');
                tbody.empty(); // Clear existing rows

                $.each(response, function (index, item) {
                    var auctionMonth = item.auction_month;
                    var date = item.date;

                    // Format the values using moneyFormatIndia
                    var lowValue = moneyFormatIndia(item.low_value);
                    var highValue = moneyFormatIndia(item.high_value);
                    var auctionValue = item.auction_value ? moneyFormatIndia(item.auction_value) : ''; // Show '—' if null

                    var cusName = item.cus_name;
                    var action = item.action;

                    var row = '<tr>' +
                        '<td>' + auctionMonth + '</td>' +
                        '<td>' + date + '</td>' +
                        '<td>' + lowValue + '</td>' +
                        '<td>' + highValue + '</td>' +
                        '<td>' + cusName + '</td>' +
                        '<td>' + auctionValue + '</td>' +
                        '<td>' + action + '</td>' +
                        '</tr>';

                    tbody.append(row);
                });

                $('.auction_detail_content').show(); // Show the card after data is loaded
                $('#add_cus_map_modal').hide(); // Show the card after data is loaded

            }
        },
        error: function (xhr, status, error) {
            console.error('AJAX Error: ' + status + error);
        }
    });
}


function closeChartsModal() {
    //  $('#add_cus_map_modal').modal('hide');
    //     cus_name.removeActiveItems();
    //   $('#cus_name').closest('.choices').find('.choices__inner').css('border', '1px solid #cecece');
    $('#grp_date').css('border-color', '');

    $('#add_pos_modal').modal('hide');
    $('#add_view_modal').modal('hide');
    $('#add_Calculation_modal').modal('hide');
}


function getCusName(groupId, auction_month) {
    $.post('api/auction_files/get_customerName_list.php', {
        group_id: groupId,
        auction_month: auction_month
    }, function (response) {
        cus_name.clearStore();

        // Initialize an array with the "Company" option
        let items = [{
            value: -1,
            label: 'Company',
            selected: false
        }];

        // Map the response to the desired format, handling empty values
        response.forEach(function (val) {
            let cusName = val.cus_name ? val.cus_name : ''; // Handle empty cus_name
            let place = val.place ? ' - ' + val.place : ''; // Only add hyphen if place is not empty
            let cusId = val.cus_id ? ' - ' + val.cus_id : ''; // Only add hyphen if cus_id is not empty

            let label = cusName + place + cusId; // Concatenate the values

            // Trim any trailing hyphens or extra spaces
            label = label.replace(/\s*-\s*$/, '');

            items.push({
                value: val.id,
                label: label.trim(),
                selected: false
            });
        });

        // Sort the items alphabetically, excluding the first item (Company)
        const companyOption = items.shift(); // Remove Company option
        items.sort((a, b) => a.label.localeCompare(b.label)); // Sort alphabetically
        items.unshift(companyOption); // Add Company back at the top

        // Set the choices with the new items array
        cus_name.setChoices(items, 'value', 'label', true);
    }, 'json');
}

function populateDates() {
    var $grpDateSelect = $('#grp_date');
    var today = new Date();
    var currentDay = today.getDate();
    var lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0).getDate();

    // Clear any existing options
    $grpDateSelect.empty().append('<option value="">Select Date</option>');

    // Populate the dropdown with dates starting from today to the end of the month
    for (var day = currentDay; day <= lastDay; day++) {
        $grpDateSelect.append($('<option>', {
            value: day,
            text: day
        }));
    }
}
function calculation(uniqueValue) {
    const [group_id, date] = uniqueValue.split('_');

    $.ajax({
        url: 'api/auction_files/fetch_calculation_data.php',
        type: 'POST',
        data: {
            group_id: group_id,
            date: date
        },
        dataType: 'json',
        success: function (response) {
            $('#group_name').val(response.group_name);
            $('#auction_month').val(response.auction_month);

            // Format the date in dd-mm-yyyy format
            $('#cal_date').val(formatDate(response.cal_date));

            $('#chit_value').val(moneyFormatIndia(response.chit_value));
            $('#auction_value').val(moneyFormatIndia(response.auction_value));
            $('#Commission').val(moneyFormatIndia(response.commission));
            $('#total_value').val(moneyFormatIndia(response.total_value));
            let roundedAmount = Math.round(response.chit_amount);
            let formattedAmount = moneyFormatIndia(roundedAmount);
            $('#chit_amount').val(formattedAmount);
        },
        error: function (xhr, status, error) {
            console.error('AJAX Error:', status, error);
        }
    });
}

function formatDate(dateString) {
    const [year, month, day] = dateString.split('-'); // Assuming date is in yyyy-mm-dd format
    return `${day}-${month}-${year}`;
}

function calculationModal(groupId, date) {
    // Split the uniqueValue into parts

    $.ajax({
        url: 'api/auction_files/close_fetch_data.php', // URL to the server-side script that fetches the data
        type: 'POST',
        data: {
            group_id: groupId, // Send the group_id part
            date: date // Send the date part
        },
        dataType: 'json',
        success: function (response) {
            // Assuming the server returns a JSON object with the relevant data
            $('#group_name').val(response.group_name);
            $('#auction_month').val(response.auction_month);
            $('#cal_date').val(formatDate(response.cal_date));
            $('#chit_value').val(moneyFormatIndia(response.chit_value));
            $('#auction_value').val(moneyFormatIndia(response.auction_value));
            $('#Commission').val(moneyFormatIndia(response.commission));
            $('#total_value').val(moneyFormatIndia(response.total_value));
            let roundAmount = Math.round(response.chit_amount);
            let formattAmount = moneyFormatIndia(roundAmount);
            $('#chit_amount').val(formattAmount);
        },
        error: function (xhr, status, error) {
            console.error('AJAX Error:', status, error);
        }
    });
}
