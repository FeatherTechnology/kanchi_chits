$(document).ready(function () {
    //Add Group creation & Back.
    $(document).on('click', '#add_group, #back_btn', function () {
        swapTableAndCreation();
        $('#add_cus_map_modal').hide();
        $('#back_to_list').hide();
        getDateDropDown()
        getGroupCreationTable();
        $('#branch_name_edit').val('')
        $('#auction_modal_btn')
            .removeAttr('data-toggle')
            .removeAttr('data-target');
        // $('#add_cus_map')
        //     .removeAttr('data-toggle')
        //     .removeAttr('data-target');
        $('#reset_clear').show();
        $('#group_clear').show(); // Show reset button
        $('#submit_group_info').show();
        $('#submit_group_details').show();
        $('#submit_cus_map').show();
    });


    $('#grp_date,#start_month, #total_month').change(function () {
        getModalAttr()
    });

    document.getElementById('hours').addEventListener('input', function (e) {
        if (this.value.length > 2) this.value = this.value.slice(0, 2);
        if (this.value > 12) this.value = 12;
        if (this.value < 1 && this.value !== '') this.value = 1;
    });

    document.getElementById('minutes').addEventListener('input', function (e) {
        if (this.value.length > 2) this.value = this.value.slice(0, 2);
        if (this.value > 59) this.value = 59;
        if (this.value < 0) this.value = 0;
    });

    $('#start_month, #total_month').change(function () {
        let startMonth = $('#start_month').val();
        let totMonth = $('#total_month').val();
        if (totMonth == '') {
            $('#start_month').val('');
            swalError('Warning', 'Kindly fill the total month');
            return false;
        }
        if (totMonth != '' && startMonth != '') {
            var endDate = moment(startMonth, 'YYYY-MM').add(totMonth, 'months').subtract(1, 'month').format('YYYY-MM');//subract one month because by default its showing extra one month
            $('#end_month').val(endDate);
        }
    });


    $('#submit_group_info').click(function (event) {
        event.preventDefault();

        // Gather form data
        let grpInfoData = {
            'groupid': $('#groupid').val(),
            'group_id': $('#group_id').val(),
            'chit_value': $('#chit_value').val().replace(/,/g, ''),
            'grp_date': $('#grp_date').val(),
            'group_name': $('#group_name').val(),
            'commission': $('#commission').val(),
            'hours': $('#hours').val(),
            'minutes': $('#minutes').val(),
            'ampm': $('#ampm').val(),
            'total_members': $('#total_members').val(),
            'total_month': $('#total_month').val(),
            'start_month': $('#start_month').val(),
            'end_month': $('#end_month').val(),
            'branch': $('#branch').val(),
            'grace_period': $('#grace_period').val()
        };

        // Validate fields
        var fields = ['group_id', 'chit_value', 'grp_date', 'group_name', 'commission', 'hours', 'minutes', 'ampm', 'total_members', 'total_month', 'start_month', 'end_month', 'branch', 'grace_period'];
        var isValid = true;

        fields.forEach(function (field) {
            var fieldIsValid = validateField($('#' + field).val(), field);
            if (!fieldIsValid) {
                isValid = false;
            }
        });

        if (isValid) {
            // Perform AJAX POST request
            $.post('api/group_creation_files/submit_group_info.php', grpInfoData, function (response) {
                if (response.result == '4') {
                    swalError('Warning', 'Fill the Auction Details');
                } else if (response.result == '5') {
                    swalError('Warning', 'Remove the Customer Mapping Details');
                } else if (response.result == '1') {
                    swalSuccess('Success', 'Group Info Submitted Successfully');
                    $('#groupid').val(response.last_id);
                    $('#groupid').val('');
                    // Optionally, reset the form and update tables
                    $('#group_creation').trigger('reset');
                    getGroupCreationTable();
                    swapTableAndCreation();

                } else {
                    swalError('Error', 'Group Info Not Submitted');
                    $('#groupid').val('');
                    $('#group_creation').trigger('reset');

                }
            }, 'json').fail(function () {
                swalError('Error', 'Request failed. Please try again.');
            });
        } else {
            swalError('Warning', 'Kindly fill the mandatory fields');
        }
    });
    ////////////////////////////////////////////////////////////// Customer Mapping Start//////////////////////////////////////////////////////////////////
    // $('#add_cus_map').click(function(event){
    //     event.preventDefault(); // Prevent the default form submission
    // $('#joining_month').css('border', '1px solid #cecece');
    // $('#cus_name').css('border', '1px solid #cecece');
    // $('#add_cus_map_modal').show();

    // })
    // var counter = 1;
    // $(document).on('input', '.share_value', function () {
    //     // Get chit value (assumed to be a field with ID #chit_value)
    //     var chit_value = $('#chit_value').val().replace(/,/g, ''); // Remove commas if any
    //     console.log('Chit Value: ' + chit_value);

    //     // Get the current share value entered by the user
    //     var share_value = parseFloat($(this).val().replace(/,/g, ''));
    //     console.log('Entered Share Value: ' + share_value);

    //     // Validate if the share value is a number
    //     if (isNaN(share_value) || share_value <= 0) {
    //         $(this).val(''); // Clear the share value if invalid
    //         $(this).closest('.row').find('.share_percent').val(''); // Clear the share percentage field in the same row
    //         console.log('Invalid share value entered. Clearing percentage field.');
    //         return; // Exit if the share value is invalid
    //     }

    //     // Calculate share percentage
    //     var share_percent = (share_value / chit_value) * 100;
    //     console.log('Calculated Share Percentage: ' + share_percent);

    //     // Check if the total share percentage exceeds 100%
    //     var totalSharePercentage = 0;
    //     $('.share_value').each(function () {
    //         totalSharePercentage += (parseFloat($(this).val().replace(/,/g, '')) / chit_value) * 100;
    //     });
    //     console.log('Total Share Percentage: ' + totalSharePercentage);

    //     if (totalSharePercentage > 100) {
    //         $(this).val(''); // Clear the share value input
    //         $(this).closest('.row').find('.share_percent').val(''); // Clear the share percentage
    //         swalError('Warning', 'Total share percentage cannot exceed 100%.'); // Show alert after clearing fields
    //     } else {
    //         // Find the .share_percent input within the same row as .share_value
    //         var percentField = $(this).closest('.row').find('.share_percent');
    //         console.log('Found share percent field:', percentField);

    //         // Set the calculated share percentage in the share_percent field
    //         if (percentField.length) {
    //             console.log('Setting Share Percentage: ' + share_percent);
    //             percentField.val(share_percent); // Set the percentage with 2 decimal places
    //         } else {
    //             console.log('Share Percent field not found!');
    //         }
    //     }
    // });
    
    $(document).on('input', '.share_value', function () {
        // Store reference to the current element that triggered the event
        var $this = $(this);
    
        // Get chit value (assumed to be a field with ID #chit_value)
        var chit_value = $('#chit_value').val().replace(/,/g, ''); // Remove commas if any
        var group_id = $('#group_id').val();
        console.log('Chit Value: ' + chit_value);
    
        // Get the current share value entered by the user
        var share_value = parseFloat($this.val().replace(/,/g, ''));
        console.log('Entered Share Value: ' + share_value);
    
        // Validate if the share value is a number
        if (isNaN(share_value) || share_value <= 0) {
            $this.val(''); // Clear the share value if invalid
            $this.closest('.row').find('.share_percent').val(''); // Clear the share percentage field in the same row
            console.log('Invalid share value entered. Clearing percentage field.');
            return; // Exit if the share value is invalid
        }
    
        // Get the customer name (cus_name) from the row
        var cus_name = $this.closest('.row').find('.cus_name').val();
    
        // AJAX request to fetch chit limit and check share value
        $.ajax({
            url: 'api/group_creation_files/fetch_chit_limit.php', // Path to the PHP script
            type: 'POST',
            data: { cus_name: cus_name, group_id: group_id, new_share_value: share_value }, // Send cus_name and new_share_value to fetch chit limit
            dataType: 'json',
            success: function (response) {
                if (response.chit_limit) {
                    var chit_limit = parseFloat(response.chit_limit);
                    var share_value_sum = parseFloat(response.share_value_sum);
                    console.log('Chit Limit: ' + chit_limit);
                    console.log('Total Share Value: ' + share_value_sum);
    
                    // Compare chit limit with total share value
                    if (share_value_sum > chit_limit) {
                        $this.val(''); // Clear the share value input
                        $this.closest('.row').find('.share_percent').val(''); // Clear the share percentage
                        swalError('Warning', 'Share value exceeds chit limit:' +(chit_limit)); // Show warning
                    } else {
                        // Calculate share percentage
                        var share_percent = (share_value / chit_value) * 100;
    
                        // Check if the total share percentage exceeds 100%
                        var totalSharePercentage = 0;
                        $('.share_value').each(function () {
                            totalSharePercentage += (parseFloat($(this).val().replace(/,/g, '')) / chit_value) * 100;
                        });
    
                        if (totalSharePercentage > 100) {
                            $this.val(''); // Clear the share value input
                            $this.closest('.row').find('.share_percent').val(''); // Clear the share percentage
                            swalError('Warning', 'Total share percentage cannot exceed 100%.'); // Show alert after clearing fields
                        } else {
                            // Find the .share_percent input within the same row as .share_value
                            var percentField = $this.closest('.row').find('.share_percent');
                            console.log('Found share percent field:', percentField);
    
                            // Set the calculated share percentage in the share_percent field
                            if (percentField.length) {
                                console.log('Setting Share Percentage: ' + share_percent);
                                percentField.val(share_percent); // Set the percentage with 2 decimal places
                            } else {
                                console.log('Share Percent field not found!');
                            }
                        }
                    }
                } else {
                    swalError('Warning', response.error); // Show error if chit limit is not found
                    $this.val(''); // Clear the share value input
                    $this.closest('.row').find('.share_percent').val(''); // Clear the share percentage
                }
            },
            error: function () {
                swalError('Error', 'Unable to fetch chit limit.');
            }
        });
    });
    
    
    var counter = 1; // Initialize counter globally

    // $('#add_btn').on('click', function (e) {
    //     e.preventDefault();

    //     // Check the total share percentage before adding a new row
    //     var totalSharePercentage = 0;
    //     $('.share_value').each(function () {
    //         var chit_value = parseFloat($('#chit_value').val().replace(/,/g, '')); // Get chit value
    //         var share_value = parseFloat($(this).val().replace(/,/g, '')); // Get share value

    //         if (!isNaN(share_value) && share_value > 0) {
    //             var share_percent = (share_value / chit_value) * 100;
    //             totalSharePercentage += share_percent; // Add the share percentage to the total
    //         }
    //     });

    //     // Check if the total share percentage is >= 100
    //     if (totalSharePercentage >= 100) {
    //         swalError('Warning', 'Total share percentage is already 100%');
    //         return; // Prevent row addition if total share percentage is 100% or more
    //     }

    //     // Validate if all fields in the original row (#mapping-row) are filled
    //     var allFieldsFilled = true;
    //     $('#mapping-row').find('input, select').each(function () {
    //         if ($(this).val() === '') {
    //             allFieldsFilled = false; // If any field in the row is empty, set flag to false
    //             return false; // Exit loop
    //         }
    //     });
    //     var allRowsFilled = true;
    //     $('#mapping-container .row').each(function () {
    //         var allFieldsFilled = true;
    //         $(this).find('input, select').each(function () {
    //             if ($(this).val() === '') {
    //                 allFieldsFilled = false; // If any field in the row is empty, set flag to false
    //                 return false; // Exit loop
    //             }
    //         });
    //         if (!allFieldsFilled) {
    //             allRowsFilled = false; // Mark the overall flag as false if any row is incomplete
    //             return false; // Exit loop
    //         }
    //     });
    //     if (!allRowsFilled) {
    //         swalError('Warning', 'Please fill all fields in all rows before adding a new row.');
    //         return; // Prevent row addition
    //     }
    //     if (!allFieldsFilled) {
    //         swalError('Warning', 'Please fill all fields in the current row before adding a new one.');
    //         return; // Prevent row addition if current row is incomplete
    //     }

    //     // Disable all fields in the current (original) row before cloning it
    //     $('#mapping-row input, #mapping-row select').prop('readonly', true).prop('disabled', true);

    //     // Clone the current row and remove the add button from the cloned row
    //     var currentRow = $(this).closest('.row');
    //     var newRow = currentRow.clone();
    //     newRow.find('#add_btn').parent().remove(); // Remove the "+" button from the clone

    //     // Make "Customer Name" and "Share Value" editable in the new row
    //     newRow.find('input, select').each(function () {
    //         var fieldId = $(this).attr('id');

    //         if (fieldId !== 'cus_name' && fieldId !== 'share_value') {
    //             $(this).prop('readonly', true).prop('disabled', true); // Disable all other fields
    //         } else {
    //             $(this).prop('readonly', false).prop('disabled', false); // Allow "Customer Name" and "Share Value" to be editable
    //         }

    //         // Keep "Share Percentage" disabled
    //         if (fieldId === 'share_percent') {
    //             $(this).prop('readonly', true).prop('disabled', true); // Disable the Share Percentage field
    //         }
    //     });

    //     // Copy the "Auction Start From" value from the first row to the new row
    //     var firstRowAuctionStart = $('#joining_month').val(); // Get the value from the first row
    //     newRow.find('#joining_month').val(firstRowAuctionStart); // Set the value in the new row

    //     // Update the IDs of cloned fields to make them unique
    //     newRow.find('input, select').each(function () {
    //         var oldId = $(this).attr('id');
    //         if (oldId) {
    //             $(this).attr('id', oldId + '_' + counter); // Append the counter to the id to make it unique
    //         }

    //         // Clear text input fields except 'map_id'
    //         if ($(this).is('input[type="text"]') && oldId !== 'map_id') {
    //             $(this).val(''); // Clear text input
    //         }

    //         // Clear select fields except for "joining_month"
    //         if ($(this).is('select') && oldId !== 'joining_month') {
    //             $(this).val(''); // Clear select field
    //         }
    //     });

    //     // Increment the counter for the next clone
    //     counter++;

    //     // Append the new cloned row to the container
    //     $('#mapping-container').append(newRow);
    // });

    $('#add_btn').on('click', function (e) {
        e.preventDefault();
    
        // Check the total share percentage before adding a new row
        var totalSharePercentage = 0;
        $('.share_value').each(function () {
            var chit_value = parseFloat($('#chit_value').val().replace(/,/g, '')); // Get chit value
            var share_value = parseFloat($(this).val().replace(/,/g, '')); // Get share value
    
            if (!isNaN(share_value) && share_value > 0) {
                var share_percent = (share_value / chit_value) * 100;
                totalSharePercentage += share_percent; // Add the share percentage to the total
            }
        });
    
        // Check if the total share percentage is >= 100
        if (totalSharePercentage >= 100) {
            swalError('Warning', 'Total share percentage is already 100%');
            return; // Prevent row addition if total share percentage is 100% or more
        }
    
        // Validate if all fields in the original row (#mapping-row) are filled
        var allFieldsFilled = true;
        $('#mapping-row').find('input, select').each(function () {
            if ($(this).val() === '') {
                allFieldsFilled = false; // If any field in the row is empty, set flag to false
                return false; // Exit loop
            }
        });
    
        var allRowsFilled = true;
        $('#mapping-container .row').each(function () {
            var allFieldsFilled = true;
            $(this).find('input, select').each(function () {
                if ($(this).val() === '') {
                    allFieldsFilled = false; // If any field in the row is empty, set flag to false
                    return false; // Exit loop
                }
            });
            if (!allFieldsFilled) {
                allRowsFilled = false; // Mark the overall flag as false if any row is incomplete
                return false; // Exit loop
            }
        });
        if (!allRowsFilled) {
            swalError('Warning', 'Please fill all row entries before adding a new one.');
            return; // Prevent row addition
        }
        if (!allFieldsFilled) {
            swalError('Warning', 'Please fill all row entries before adding a new one');
            return; // Prevent row addition if current row is incomplete
        }
    
        // Disable all fields in the current (original) row before cloning it
        $('#mapping-row input, #mapping-row select').prop('readonly', true).prop('disabled', true);
    
        // Clone the current row
        var currentRow = $(this).closest('.row');
        var newRow = currentRow.clone();
    
        // Replace the "+" button with a "-" button
        newRow.find('#add_btn').replaceWith(`
                <div class="form-group">
                    <button class="btn btn-primary remove-row" id="sub_btn" style="width:55px;">-</button>
                </div>
        `);
    
        // Make "Customer Name" and "Share Value" editable in the new row
        newRow.find('input, select').each(function () {
            var fieldId = $(this).attr('id');
    
            if (fieldId !== 'cus_name' && fieldId !== 'share_value') {
                $(this).prop('readonly', true).prop('disabled', true); // Disable all other fields
            } else {
                $(this).prop('readonly', false).prop('disabled', false); // Allow "Customer Name" and "Share Value" to be editable
            }
    
            // Keep "Share Percentage" disabled
            if (fieldId === 'share_percent') {
                $(this).prop('readonly', true).prop('disabled', true); // Disable the Share Percentage field
            }
        });
    
        // Copy the "Auction Start From" value from the first row to the new row
        var firstRowAuctionStart = $('#joining_month').val(); // Get the value from the first row
        newRow.find('#joining_month').val(firstRowAuctionStart); // Set the value in the new row
    
        // Update the IDs of cloned fields to make them unique
        newRow.find('input, select').each(function () {
            var oldId = $(this).attr('id');
            if (oldId) {
                $(this).attr('id', oldId + '_' + counter); // Append the counter to the id to make it unique
            }
    
            // Clear text input fields except 'map_id'
            if ($(this).is('input[type="text"]') && oldId !== 'map_id') {
                $(this).val(''); // Clear text input
            }
    
            // Clear select fields except for "joining_month"
            if ($(this).is('select') && oldId !== 'joining_month') {
                $(this).val(''); // Clear select field
            }
        });
    
        // Increment the counter for the next clone
        counter++;
    
        // Append the new cloned row to the container
        $('#mapping-container').append(newRow);
    
        // Add functionality for removing rows
        $(document).on('click', '.remove-row', function (e) {
            e.preventDefault();
            $(this).closest('.row').remove(); // Remove the row when "-" button is clicked
            $(this).closest('.row').prop('readonly', false).prop('disabled', false);
        });
    });
    
    $('#add_cus_map').on('click', function () {
        let total_members = $('#total_members').val();
        let chit_value = $('#chit_value').val().replace(/,/g, '');
        let total_month = $('#total_month').val();
        if (total_members === '' || chit_value === '' || total_month === '') {
            swalError('Alert', 'Kindly Fill the Total Members,Chit Value and Total Month!')
            return;
        } else {
            $('#add_cus_map_modal').show();
            getAutoGenMappingId('');
            $('#group_creation_content').hide();
            $('#joining_month').css('border', '1px solid #cecece');
            $('#cus_name').css('border', '1px solid #cecece');
            // Call your existing functions
            $('#back_to_list').show();
            $('#back_btn').hide();
            $('#mapping-container').empty();
            $('#mapping-row input, #mapping-row select').val(''); // Clear all input and select values inside #mapping-row
            $('#mapping-row input, #mapping-row select').prop('readonly', false).prop('disabled', false);
            $('#mapping-row #map_id').prop('readonly', true); // Set the map_id field as readonly
            $('#mapping-row #share_percent').prop('readonly', true); // Set the map_id field as readonly
            getCusMapTable();
            getJoiningMonth();
        }
    });
    $(document).on('click', '#back_to_list', function (event) {
        event.preventDefault();

        // Hide back button
        $('#back_to_list').hide();
        // Show auction details content and back button
        $('.auction_detail_content, .back_btn').show();
        $('#group_creation_content').show();
        // Hide the modal
        $('#add_cus_map_modal').hide();
        $('#back_btn').show();

    });
    
    $('#submit_cus_map').click(function (event) {
        event.preventDefault(); // Prevent default form submission
    
        // Initialize arrays for storing values
        let cus_name = [];
        let share_value = [];
        let share_percent = [];
    
        let map_id = $('#map_id').val();
        let group_id = $('#group_id').val();
        let total_members = $('#total_members').val();
        let chit_value = $('#chit_value').val().replace(/,/g, ''); // Removing commas
        let joining_month = $('#joining_month').val();
    
        // Validation flag
        let isValid = true;
        let totalSharePercent = 0; // Initialize a variable to calculate the total share percent
    
        // Iterate through the #mapping-row (if applicable) and collect values
        $('#mapping-row').each(function () {
            let cus_id = $(this).find('.cus_name').val(); // Use class for customer ID
            let share_value_row = $(this).find('.share_value').val(); // Use class for share value
            let share_percent_row = $(this).find('.share_percent').val(); // Use class for share percentage
    
            // Check if all fields in #mapping-row are filled
            if (!cus_id || !share_value_row || !share_percent_row) {
                isValid = false; // Set isValid to false if any value is missing
                swalError('Warning', 'All fields in each row must be filled.');
                return false; // Stop processing and prevent submission
            }
    
            // Add values to arrays if valid
            cus_name.push(cus_id);
            share_value.push(share_value_row);
            share_percent.push(share_percent_row);
    
            // Accumulate the share percentage
            totalSharePercent += parseFloat(share_percent_row);
        });
    
        // Iterate through each row in #mapping-container to collect and validate values
        $('#mapping-container .row').each(function () {
            let cus_id = $(this).find('.cus_name').val(); // Use class for customer ID
            let share_value_row = $(this).find('.share_value').val(); // Use class for share value
            let share_percent_row = $(this).find('.share_percent').val(); // Use class for share percentage
    
            // Check if all fields are filled in the current row
            if (!cus_id || !share_value_row || !share_percent_row) {
                isValid = false; // Set isValid to false if any value is missing
                swalError('Warning', 'All fields in each row must be filled.');
                return false; // Stop processing and prevent submission
            }
    
            // Add values to arrays if valid
            cus_name.push(cus_id);
            share_value.push(share_value_row);
            share_percent.push(share_percent_row);
    
            // Accumulate the share percentage
            totalSharePercent += parseFloat(share_percent_row);
        });
    
        // Further validation outside the loop
        if (!joining_month) {
            validateField(joining_month, 'joining_month');
            isValid = false;
        }
    
        if (!cus_name.length) {
            validateField(cus_name, 'cus_name');
            isValid = false;
        }
    
        if (!share_value.length || !share_percent.length) {
            swalError('Warning', 'Share Value and Share Percentage are required.');
            isValid = false;
        }
    
        // Check if the sum of the share percentages is exactly 100
        if (totalSharePercent !== 100) {
            swalError('Warning', 'The Share Percent must be 100%. Currently, it is ' + totalSharePercent + '%.');
            isValid = false;
        }
    
        // Submit the form if everything is valid
        if (isValid && group_id !== '') {
            $.post('api/group_creation_files/submit_cus_mapping.php', {
                cus_name: cus_name, // Send the cus_name array
                map_id: map_id,
                group_id: group_id,
                total_members: total_members,
                chit_value: chit_value,
                joining_month: joining_month,
                share_value: share_value,
                share_percent: share_percent
            }, function (response) {
                let result = response.result;
    
                if (result === 1) {
                    swalSuccess('Success', 'Customer Added Successfully');
                    getCusMapTable(); // Refresh the table after success
                    getAutoGenMappingId('');
                    $('#mapping-container').empty();
                    $('#mapping-container .row, #mapping-container .row').val('');
                    $('#mapping-row input, #mapping-row select').val(''); // Clear all input and select values inside #mapping-row
                    $('#mapping-row input, #mapping-row select').prop('readonly', false).prop('disabled', false);
                    $('#mapping-row #map_id').prop('readonly', true); // Set the map_id field as readonly
                    $('#mapping-row #share_percent').prop('readonly', true); // Set the map_id field as readonly
                } else if (result === 2) {
                    swalError('Error', 'Customer Adding Failed');
                } else if (result === 3) {
                    swalError('Warning', response.message);
                }
            }, 'json');
        }
    });
    

    $(document).on('click', '.cusMapDeleteBtn', function () {
        var values = $(this).attr('value').split('-');
        var id = values[0]; // First value is the id
        var cus_map_id = values[1]; // Second value is the cus_mapping_id

        swalConfirm('Delete', 'Do you want to remove this customer mapping?', function () {
            removeCusMap(id, cus_map_id);
        });
    });
 
    ///////////////////////////////////////////////////////Customer Mapping End/////////////////////////////////////////
    //////////////////////////////////////////////////////////////auction Details/////////////////////////////////////////////////////////
    $('#submit_group_details').click(function (event) {
        event.preventDefault();

        let groupId = $('#group_id').val();
        let groupDate = $('#grp_date').val();
        let chitValue = parseFloat($('#chit_value').val().replace(/,/g, '')); // Parse chitValue as a float

        // Initialize a flag to check validation
        let isValid = true;

        // Collect table data
        let auctionDetails = [];
        $('#grp_details_table tbody tr').each(function () {
            let auctionMonth = $(this).find('.auction_month').text();
            let monthName = $(this).find('.month_name').text();
            let lowValue = parseFloat($(this).find('.low_value').val().replace(/,/g, '')); // Parse lowValue as a float
            let highValue = parseFloat($(this).find('.high_value').val().replace(/,/g, '')); // Parse highValue as a float

            // Validate that low_value and high_value are filled
            if (!lowValue || !highValue) {
                isValid = false;
                $(this).find('.low_value').css('border-color', !lowValue ? 'red' : '');
                $(this).find('.high_value').css('border-color', !highValue ? 'red' : '');
            } else {
                $(this).find('.low_value').css('border-color', '');
                $(this).find('.high_value').css('border-color', '');

                // Validate that high_value is less than or equal to chit_value
                if (highValue > chitValue) {
                    isValid = false;
                    $(this).find('.high_value').css('border-color', 'red');
                    swalError('Warning', 'High value cannot be greater than Chit Value.');
                }
            }

            auctionDetails.push({
                auction_month: auctionMonth,
                month_name: monthName,
                low_value: lowValue,
                high_value: highValue
            });
        });

        // Show an alert if any fields are invalid
        if (!isValid) {
            return; // Prevent further execution if validation fails
        }

        // Perform the min-max validation
        checkMinMaxValue('.low_value', '.high_value');

        // Check if any fields were marked as invalid (red border) by checkMinMaxValue
        if ($('#grp_details_table tbody tr').find('.low_value, .high_value').filter(function () { return $(this).css('border-color') === 'rgb(255, 0, 0)'; }).length > 0) {
            swalError('Warning', 'Low value cannot be greater than high value.');
            return; // Prevent form submission if any fields are invalid
        }

        // Send data to the PHP script if validation passes
        $.post('api/group_creation_files/submit_auction_details.php', {
            group_id: groupId,
            grp_date: groupDate,
            auction_details: auctionDetails
        }, function (response) {
            if (response.trim() === '1') { // Use .trim() to remove any extra whitespace
                populateAuctionDetailsTable(auctionDetails);
                swalSuccess('Success', 'Auction Details Submitted Successfully');
            } else {
                swalError('Warning', 'An error occurred while saving auction details.');
            }
        }).fail(function () {
            swalError('Error', 'Failed to communicate with the server.');
        });
    });


    ///////////////////////////////////////////////////////////////////auction details End//////////////////////////////////////////
    $(document).on('click', '.edit-group-creation', function () {
        let id = $(this).attr('value');
        $('#groupid').val(id);
        $('#add_cus_map_modal').hide();
        $('#back_to_list').hide();
        swapTableAndCreation();
        editGroupCreation(id)

    });

}); //document END////

$(function () {
    getGroupCreationTable();
    checkDashboardData();
});

function checkDashboardData() {
    let fromDashboard = localStorage.getItem('dashboardGrp');

    if (fromDashboard) { // Ensure fromDashboard is not null or empty
        console.log('Dashboard data found:', fromDashboard);

        // Find all <a> tags with the class 'edit-group-creation'
        let links = document.querySelectorAll('.edit-group-creation');

        links.forEach(link => {
            if (link.getAttribute('value') === fromDashboard) {
                console.log('Match found, triggering click for value:', fromDashboard);
                // link.click(); // Trigger click event
                $(link).trigger('click');
            }
        });
    } else {
        console.log('No matching data in localStorage.');
    }
}

function getGroupCreationTable() {
    serverSideTable('#group_creation_table', '', 'api/group_creation_files/get_grp_creation_list.php');

    $('#group_creation_table').on('init.dt', function () {
        checkDashboardData(); //Call function after the table loaded.
    });
}

function checkDashboardData() {
    let fromDashboard = localStorage.getItem('dashboardGrp');

    if (fromDashboard) { // Ensure fromDashboard is not null or empty
        // Find all <a> tags with the class 'edit-group-creation'
        let links = document.querySelectorAll('.edit-group-creation');

        links.forEach(link => {
            if (link.getAttribute('value') === fromDashboard) {
                $(link).trigger('click');
            }
        });
    }
}

function swapTableAndCreation() {
    if ($('.group_table_content').is(':visible')) {
        $('.group_table_content').hide();
        $('#add_group').hide();
        $('#group_creation_content').show();
        $('#back_btn').show();
        callGrpFunctions();

    } else {
        $('.group_table_content').show();
        $('#add_group').show();
        $('#group_creation_content').hide();
        $('#back_btn').hide();
        $('#customer_mapping').trigger('click');

        localStorage.setItem('dashboardGrp', '');
    }
}
function getAutoGenGroupId(id) {
    $.post('api/group_creation_files/get_autoGen_Group_id.php', { id }, function (response) {
        $('#group_id').val(response);
    }, 'json');
}
function getAutoGenMappingId(id) {
    $.post('api/group_creation_files/get_autoGen_mapping_id.php', { id }, function (response) {
        $('#map_id').val(response);
    }, 'json');
}
function callGrpFunctions() {
    getAutoGenGroupId('')
    // getDateDropDown()
    getBranchList();
    getCustomerList();
    getJoiningMonth()
}
function getBranchList() {
    $.post('api/common_files/get_branch_list.php', function (response) {
        let branchOptn = '';
        branchOptn = '<option value="">Select Branch</option>';
        response.forEach(val => {
            let selected = '';
            let editGId = $('#branch_name_edit').val();
            if (val.id == editGId) {
                selected = 'selected';
            }
            branchOptn += "<option value='" + val.id + "' " + selected + ">" + val.branch_name + "</option>";
        });
        $('#branch').empty().append(branchOptn);
    }, 'json');
}


function getCustomerList() {
    $.post('api/common_files/get_customer_list.php', function (response) {
        let cusOptn = '';
        cusOptn = '<option value="">Select Customer Name</option>';
        response.forEach(val => {
            cusOptn += '<option value="' + val.id + '">' + val.first_name + ' ' + val.last_name + ' - ' + val.place + ' - ' + val.cus_id + '</option>';
        });
        $('#cus_name').empty().append(cusOptn);
    }, 'json');
}
$('#total_month').on('input', function () {
    getJoiningMonth(); // Ensure correct function name is called
});
function getJoiningMonth() {
    let total_month = $('#total_month').val(); // Get the total month value
    let joiningMonthDropdown = $('#joining_month'); // Reference to the dropdown

    // Clear existing options
    joiningMonthDropdown.empty();

    // Add the default option
    joiningMonthDropdown.append('<option value="">Select Auction Start From</option>');

    // Check if total_month is a valid number
    if (total_month > 0) {
        // Loop through the months and add options
        for (let i = 1; i <= total_month; i++) {
            joiningMonthDropdown.append(`<option value="${i}">${i}</option>`);
        }
    }
}

function getCusMapTable() {
    let group_id = $('#group_id').val();
    $.post('api/group_creation_files/get_cus_map_details.php', { group_id }, function (response) {
        let cusMapColumn = [
            "sno",
            "map_id",
            "cus_id",
            "name",
            "place",
            "occ",
            "joining_month",
            "share_value",
            "share_percent",
            "action"
        ]
        appendDataToTable('#cus_mapping_table', response, cusMapColumn);
        setdtable('#cus_mapping_table');
    }, 'json');
}

function removeCusMap(id, cus_map_id) {
    $.post('api/group_creation_files/delete_cus_mapping.php', { id: id, cus_map_id: cus_map_id }, function (response) {
        if (response == 1) {
            swalSuccess('Success', 'Customer mapping removed successfully.');
            // Optionally, you can reload the table or perform other actions
            getCusMapTable();
        } else {
            swalError('Alert', 'Customer mapping removal failed.');
        }
    }, 'json');
}
function getModalAttr() {
    let grp_date = $('#grp_date').val();
    let start_month = $('#start_month').val();
    if (grp_date != '' && start_month != '') {
        $('#auction_modal_btn')
            .attr('data-toggle', 'modal')
            .attr('data-target', '#add_auction_modal');
    } else {
        $('#auction_modal_btn')
            .removeAttr('data-toggle')
            .removeAttr('data-target');
    }
}

function checkMinMaxValue(lowValueClass, highValueClass) {
    // Iterate over each row in the table
    $('#grp_details_table tbody tr').each(function () {
        let lowValue = $(this).find(lowValueClass).val().replace(/,/g, '');
        let highValue = $(this).find(highValueClass).val().replace(/,/g, '');

        if (lowValue && highValue && parseFloat(lowValue) > parseFloat(highValue)) {
            // Handle invalid case where lowValue is greater than highValue
            $(this).find(lowValueClass).css('border-color', 'red');
            $(this).find(highValueClass).css('border-color', 'red');
        } else {
            // Reset border color if valid
            $(this).find(lowValueClass).css('border-color', '');
            $(this).find(highValueClass).css('border-color', '');
        }
    });
}


function updateEndMonth() {
    let grp_date = $('#grp_date').val();
    let startMonth = $('#start_month').val();
    let totalMonths = parseInt($('#total_month').val(), 10);
    let groupId = $('#group_id').val(); // Assuming you have a field with id `group_id` for the group ID

    if (grp_date && startMonth && groupId) {
        $.post('api/group_creation_files/get_auction_details.php', {
            grp_date: grp_date,
            start_month: startMonth,
            total_month: totalMonths,
            group_id: groupId // Add group_id to the POST data
        }, function (response) {
            let result = JSON.parse(response);

            if (result.result === 1) {
                // Data found in auction_details
                populateAuctionDetailsTable(result.data);
            } else if (result.result === 0) {
                // Generate new rows based on start month and end month
                $('#end_month').val(result.end_month);
                populateAuctionDetailsTableWithInputs(totalMonths, startMonth, result.end_month);
            } else {
                swalError('Error', result.error_message || 'An error occurred.');
            }
        });
    } else {
        swalError('Warning', 'Kindly Select the Date and Start Month!');
    }
}

function populateAuctionDetailsTable(data) {
    let tableBody = $('#grp_details_table tbody');
    tableBody.empty();

    data.forEach((auction, index) => {
        let monthName = auction.month_name || auction.date; // Ensure month_name is correctly retrieved

        tableBody.append(`
            <tr>
                <td>${index + 1}</td> <!-- S.No. -->
                <td class="auction_month" style="display: none;">${auction.auction_month}</td>
                <td class="month_name">${monthName}</td>
                <td><input type="text" class="form-control low_value" value="${moneyFormatIndia(auction.low_value)}" placeholder="Enter Low Value"></td>
                <td><input type="text" class="form-control high_value" value="${moneyFormatIndia(auction.high_value)}" placeholder="Enter High Value"></td>
            </tr>
        `);
    });

    // Reapply the money format and validation check
    $('.low_value, .high_value').on('change blur', function () {
        let formattedValue = moneyFormatIndia(parseFloat($(this).val().replace(/,/g, '')) || 0);
        $(this).val(formattedValue);
        checkMinMaxValue('.low_value', '.high_value');
    });
}




function populateAuctionDetailsTableWithInputs(totalMonths, startMonth, endMonth) {
    let tableBody = $('#grp_details_table tbody');
    tableBody.empty();
    let startDate = new Date(startMonth + "-01");
    let endDate = new Date(endMonth + "-01");

    for (let i = 0; i < totalMonths; i++) {
        let monthYear = new Date(startDate.setMonth(startDate.getMonth() + (i === 0 ? 0 : 1)));
        if (monthYear > endDate) break;

        let monthName = monthYear.toLocaleString('default', { month: 'short', year: 'numeric' });
        let monthValue = i + 1; // Correctly calculate the month value based on loop iteration

        let formattedDate = `2-${monthName}`; // Format: 2-Aug-2024

        tableBody.append(`
            <tr>
                <td>${i + 1}</td>
                <td class="auction_month" style="display: none;">${monthValue}</td>
                <td class="month_name">${monthName}</td>
                <td><input type="number" class="form-control  low_value" placeholder="Enter Low Value"></td>
                <td><input type="number" class="form-control  high_value" placeholder="Enter High Value"></td>
            </tr>
        `);
    }
    $('.low_value, .high_value').change(function () {
        checkMinMaxValue('.low_value', '.high_value');
    });
    $('#grp_details_card').show();
}


function getDateDropDown(editDId) {
    let dateOption = '<option value="">Select Date</option>';
    for (let i = 1; i <= 31; i++) {
        let selected = '';
        if (i == editDId) {
            selected = 'selected';
        }
        dateOption += '<option value="' + i + '" ' + selected + '>' + i + '</option>';
    }
    $('#grp_date').empty().append(dateOption);
}

function hideSubmitButton(status) {
    if (status > 2) {
        // Hide the reset button and submit buttons
        $('#back_btn').show();
        $('#reset_clear').hide();
        $('#group_clear').hide(); // Hide reset button
        $('#submit_group_info').hide();
        $('#submit_group_details').hide();
        $('#submit_cus_map').hide();
        $('#add_btn').hide();

    } else {
        // Show the reset button and submit buttons
        $('#back_btn').show();
        $('#reset_clear').show();
        $('#group_clear').show(); // Show reset button
        $('#submit_group_info').show();
        $('#submit_group_details').show();
        $('#submit_cus_map').show();
        $('#add_btn').show();
    }
}

function editGroupCreation(id) {
    $.post('api/group_creation_files/group_creation_data.php', { id: id }, function (response) {
        // Populate form fields
        $('#group_creation').addClass('edit-mode');
        $('#groupid').val(id);
        $('#group_id').val(response[0].grp_id);
        $('#chit_value').val(moneyFormatIndia(response[0].chit_value));
        $('#group_name').val(response[0].grp_name);
        $('#commission').val(response[0].commission);
        $('#hours').val(response[0].hours);
        $('#minutes').val(response[0].minutes);
        $('#ampm').val(response[0].ampm);
        $('#total_members').val(response[0].total_members);
        $('#total_month').val(response[0].total_months);
        $('#start_month').val(response[0].start_month);
        $('#end_month').val(response[0].end_month);
        $('#branch_name_edit').val(response[0].branch);
        $('#grace_period').val(response[0].grace_period);

        let editDId = response[0].date;
        getDateDropDown(editDId);
        callGrpFunctions();

        setTimeout(() => {
            getAutoGenGroupId(id);

            $('#grp_date').trigger('change');
            $('#branch').trigger('change');

            $.post('api/group_creation_files/fetch_group_status.php', { group_id: response[0].grp_id }, function (statusResponse) {
                let status = parseInt(statusResponse, 10);
                hideSubmitButton(status);

                // Store original values for the specific group
                let originalValues = {};
                $('#group_creation').find('input, select, textarea').each(function () {
                    originalValues[$(this).attr('id')] = $(this).val();
                });

                // Flag to track if the form has changed
                let formChanged = false;

                // Attach the change event listener to all input, select, and textarea elements within the form
                $('#group_creation').on('keyup change paste', 'input, select, textarea', function () {
                    checkFormChange();
                });

                // Function to check if the form has changed
                function checkFormChange() {
                    formChanged = false;
                    $('#group_creation').find('input, select, textarea').each(function () {
                        let id = $(this).attr('id');
                        if ($(this).val() !== originalValues[id]) {
                            formChanged = true;
                            return false; // Exit loop if any change is detected
                        }
                    });
                    handleFormAction();
                }

                // Function to handle form submission or any other action
                function handleFormAction() {
                    if (formChanged && status <= 2) {
                        $('#back_btn').hide();
                    } else {
                        $('#back_btn').show();
                    }
                }

                // Clean up event listeners when leaving edit mode
                $('#submit_group_info').click(function () {
                    $('#group_creation').removeClass('edit-mode');
                    $('#group_creation').off('keyup change paste');
                });

            }, 'json');
        }, 1000);

        getModalAttr();
    }, 'json');
}

$('button[type="reset"],#back_btn').click(function (event) {
    // event.preventDefault();
    $('input').each(function () {
        var id = $(this).attr('id');
        if (id !== 'group_id' && id !== 'submit_cus_map') {
            $(this).val('');
        }
    });
    // Reset all select fields within the specific form
    $('#group_creation').find('select').each(function () {
        $(this).val($(this).find('option:first').val());

    });
    $('#group_creation input').css('border', '1px solid #cecece');
    $('#group_creation select').css('border', '1px solid #cecece');

});


