$(document).ready(function () {
    $(document).on('click', '#back_to_coll_list', function () {
        $('#collection_list').show();
        getCollectionTable();
        $('#coll_main_container,#back_to_coll_list').hide();

    });
    $(document).on('click', '#back_to_pay_list', function (event) {
        event.preventDefault();
        $('.colls-cntnr,#back_to_coll_list').show();
        $('.coll_details,#back_to_pay_list').hide();
        $('#transaction_container').hide();

    })


    $(document).on('click', '.collectionListBtn', function (event) {
        event.preventDefault();
        $('#collection_list').hide();
        $('#coll_main_container,#back_to_coll_list').show();
        let id = $(this).attr('value');
        // editGroupCreation(id)
        viewCustomerGroups(id);
        editCustomerCreation(id)
    })
    $('#coll_mode').change(function () {
        var coll_mode = $(this).val();
        $('#transaction_container').hide();
        $('#bank_container').hide();
        if (coll_mode == '2') {

            getBankName()
            $('#bank_container').show();
            $('#transaction_container').show();
        }
    });
    /////////////////////////////////////////////////////Pay Start//////////////////////////////////////////////////////////
    $(document).on('click', '.add_pay', function (event) {
        event.preventDefault();

        // Hide and show the appropriate sections
        $('.colls-cntnr, #back_to_coll_list').hide();
        $('.coll_details, #back_to_pay_list').show();
        collectDate();

        let dataValue = $(this).data('value');
        let dataParts = dataValue.split('_');
        let groupId = dataParts[0];
        let customerId = dataParts[1];
        let auctionId = dataParts[2];
        let cusMappingID = dataParts[3]; // Extract cus_mapping_id from data attribute
        let cusId = dataParts[4];
        let share_id = dataParts[5];

        $.ajax({
            url: 'api/collection_files/fetch_pay_details.php',
            type: 'POST',
            data: {
                group_id: groupId,
                cus_mapping_id: cusMappingID,
                share_id:share_id
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    // Check if the necessary fields are present in the response
                    if (typeof response.chit_amount !== 'undefined' && typeof response.payable_amnt !== 'undefined') {
                        // Round off chit_amount and payable_amnt
                        let roundedChitAmount = Math.round(response.chit_amount || 0);
                        let roundedPayableAmnt = Math.round(response.payable_amnt || 0);

                        // Populate the form fields with the fetched and rounded data
                        $('#group_name').val(response.group_name);
                        $('#auction_month').val(response.auction_month);
                        $('#date').val(response.date);
                        $('#chit_value').val(moneyFormatIndia(response.chit_value));
                        $('#chit_amt').val(moneyFormatIndia(roundedChitAmount));
                        $('#pending_amt').val(moneyFormatIndia(response.pending_amt || 0));
                        $('#payable_amnt').val(moneyFormatIndia(roundedPayableAmnt));
                        $('#coll_mode').each(function () {
                            $(this).val($(this).find('option:first').val());
                        });
                        $('#bank_name').each(function () {
                            $(this).val($(this).find('option:first').val());
                        });
                        $('#collection_amount').val('');
                        $('#transaction_id').val('');
                        $('input').css('border', '1px solid #cecece');
                        $('select').css('border', '1px solid #cecece');
                        $('#transaction_container').hide();
                        $('#bank_container').hide();
                    } else {
                        console.error('Required data fields are missing in the response.');
                        swalError('Warning', 'Failed to retrieve the required payment details.');
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', error);
                swalError('Error', 'An error occurred while fetching payment details.');
            }
        });

        $('#submit_collection').unbind('click').click(function (event) {
            event.preventDefault();
            $(this).attr('disabled', true);
            let collectionDate = $('#collection_date').val();
            let collectionAmount = $('#collection_amount').val(); // Parse as float for numerical comparison
            let coll_mode = $('#coll_mode').val();
            let transaction_id = $('#transaction_id').val();
            let bank_name = $('#bank_name').val();
            let pendingAmount = Math.round(parseFloat($('#pending_amt').val().replace(/,/g, '')));
            let payableAmount = Math.round(parseFloat($('#payable_amnt').val().replace(/,/g, ''))); // Round off payable amount
            let chitAmount = Math.round(parseFloat($('#chit_amt').val().replace(/,/g, ''))); // Round off chit amount
            let chit_value = $('#chit_value').val().replace(/,/g, ''); // Round off chit amount

            let isValid = true; 
            // Validate the collection amount field
            if (!collectionAmount || parseFloat(collectionAmount) <= 0) {
                isValid = false;
                swalError('Warning', 'Collection amount cannot be empty or zero.');
                $('#collection_amount').css('border-color', 'red');
            } else {
                $('#collection_amount').css('border-color', ''); // Reset border if valid
                collectionAmount = parseFloat(collectionAmount); // Now safely parse it as a float
            }
            // Validate the collection mode field
            if (!validateField(coll_mode, 'coll_mode')) {
                isValid = false;
            }

            if (coll_mode === '2') {
                // Check if transaction_id is empty
                if (!validateField(transaction_id, 'transaction_id')) {
                    isValid = false;
                }
                
                // Check if bank_name is empty (assuming bank_name is required for coll_mode '2')
                if (!validateField(bank_name, 'bank_name')) {
                    isValid = false;
                }
            }
            // Check if collection amount is less than or equal to payable amount
            if (collectionAmount > payableAmount) {
                isValid = false;
                swalError('Warning', 'Collection amount cannot be greater than payable amount.');
            }

            if (isValid) {
                // Send the data to the server using AJAX
                $.ajax({
                    url: 'api/collection_files/submit_collection.php',
                    method: 'POST',
                    data: {
                        group_id: groupId,
                        cus_id: customerId,
                        auction_id: auctionId,
                        cus_mapping_id: cusMappingID, // Pass cus_mapping_id
                        share_id: share_id, // Pass cus_mapping_id
                        auction_month: $('#auction_month').val(),
                        chit_value: chit_value,
                        chit_amount: chitAmount, // Use rounded chit amount
                        pending_amt: pendingAmount,
                        payable_amnt: payableAmount, // Use rounded payable amount
                        collection_amount: collectionAmount,
                        collection_date: collectionDate,
                        coll_mode: coll_mode,
                        transaction_id: transaction_id,
                        bank_name: bank_name,
                    },
                    success: function (response) {
                        $('#submit_collection').attr('disabled', false);

                        response = JSON.parse(response);
                        if (response.result == 1) {
                            swalSuccess('Success', "Collected Successfully");
                            // Optionally clear the form fields
                            $('#collection_amount').val('');
                            viewCustomerGroups(cusId);
                            $('.colls-cntnr,#back_to_coll_list').show();
                            $('.coll_details, #back_to_pay_list').hide();
            
                            // Use the coll_id from the response to print the collection
                            setTimeout(function () {
                                printCollection(response.coll_id); // Pass the collection ID here
                            }, 1000);
                        } else {
                            swalError('Warning', 'Failed to save the collection details');
                        }
                    },
                });
            }else{
                $('#submit_collection').attr('disabled', false);
            }
            
        });
    });
    function printCollection(coll_id) { 
        Swal.fire({
            title: 'Print',
            text: 'Do you want to print this collection?',
            imageUrl: 'img/printer.png',
            imageWidth: 300,
            imageHeight: 210,
            imageAlt: 'Custom image',
            showCancelButton: true,
            confirmButtonColor: '#009688',
            cancelButtonColor: '#d33',
            cancelButtonText: 'No',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'api/collection_files/print_collection.php',
                    data: { 'coll_id': coll_id },
                    type: 'POST',
                    dataType: 'json', // Ensure the response is treated as JSON
                    cache: false,
                    success: function (response) {
                        if (Array.isArray(response) && response.length > 0) {
                            let rows = response.map(row => `
                                <tr>
                                    <td><strong>Group ID</strong></td>
                                    <td>${row.group_id || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <td><strong>Group Name</strong></td>
                                    <td>${row.group_name || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <td><strong>Customer Name</strong></td>
                                    <td>${row.cus_name || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <td><strong>Auction Month</strong></td>
                                    <td>${row.auction_month || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <td><strong>Chit Amount</strong></td>
                                    <td>${moneyFormatIndia(row.chit_amount) || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <td><strong>Payable</strong></td>
                                    <td>${moneyFormatIndia(row.payable) || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <td><strong>Collection Date</strong></td>
                                    <td>${row.collection_date || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <td><strong>Collection Amount</strong></td>
                                    <td>${moneyFormatIndia(row.collection_amount) || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <td><strong>Pending</strong></td>
                                    <td>${moneyFormatIndia(row.pending) || 'N/A'}</td>
                                </tr>
                            `).join('');
                            
                            // HTML Content with consistent alignment and styling
                            const content = `
                            <div id="print_content" style="text-align: center; font-size: 13px;">
                                <h2 style="margin-bottom: 20px;">
                                    <img src="img/bg_none_eng_logo.png" style="width: 125px; height: 90px;" />
                                </h2>
                                <table style="margin: 0 auto; border-collapse: collapse; width: 90%; text-align: left; border: none;">
                                    ${rows}
                                </table>
                            </div>
                            `;
    
                            // Create a new window for printing
                            const printWindow = window.open('', '_blank');
                            printWindow.document.write(`
                                <html>
                                <head>
                                    <title>Print Collection Details</title>
                                    <style>
                                        body {
                                            font-family: Arial, sans-serif;
                                            margin: 0;
                                            padding: 0;
                                            text-align: center;
                                        }
                                        table {
                                            width: 90%;
                                            margin: 0 auto;
                                            border-collapse: collapse;
                                            table-layout: fixed; /* Ensures equal column widths */
                                            border: none;
                                        }
                                        td {
                                            padding: 4px;
                                            border: none;
                                            font-size: 13px;
                                            word-wrap: break-word;
                                        }
                                        .label {
                                            font-weight: bold;
                                            text-align: right;
                                            width: 40%;
                                        }
                                        h2 img {
                                            display: block;
                                            margin: 0 auto;
                                        }
                                    </style>
                                </head>
                                <body>
                                    ${content}
                                </body>
                                </html>
                            `);
                            printWindow.document.close();
    
                            // Trigger the print dialog
                            setTimeout(() => {
                                printWindow.focus();
                                printWindow.print(); 
                                printWindow.close();
                            }, 1000);
                        } else {
                            console.error('No valid data found:', response);
                            swalError('Error', 'No data found for printing.');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching data:', error);
                        swalError('Error', 'Failed to load collection data.');
                    }
                });
            } else {
                // Show collection container and return to collection list if canceled
                $('.colls-cntnr, #back_to_coll_list').show(); 
            }
        });
    }
    
    
    

    ////////////////////////////////////////////////Pay End/////////////////////////////////////////////////
    ////////////////////////////////////////////////////////Commitement  Start////////////////////////////////////////////
    $(document).on('click', '.add_commitment', function (event) {
        event.preventDefault();

        // Show the modal
        $('#add_commitment_modal').modal('show');
        $('#label').css('border', '1px solid #cecece');
        $('#commitment_date').css('border', '1px solid #cecece');
        $('#remark').css('border', '1px solid #cecece');
        // Pre-fill the modal or attach necessary data if required
        let dataValue = $(this).data('value');
        let dataParts = dataValue.split('_');
        let groupId = dataParts[0];
        let cusMappingID = dataParts[1];
        let share_id = dataParts[2];
        getCommitmentInfoTable(cusMappingID,groupId,share_id);
        commitDate();
        // Unbind any existing click event to prevent multiple submissions
        $('#add_commit').off('click').on('click', function (event) {
            event.preventDefault();

            // Validation
            let label = $('#label').val();
            let remark = $('#remark').val();
            let commitment_date = $('#commitment_date').val();

            var isValid = true;

            // Validate each field
            if (!validateField(label, 'label')) {
                isValid = false;
            }
            if (!validateField(remark, 'remark')) {
                isValid = false;
            }
            if (!validateField(commitment_date, 'commitment_date')) {
                isValid = false;
            }

            // If all fields are valid, proceed with the AJAX call
            if (isValid) {
                $.post('api/collection_files/submit_commitement.php', {
                    group_id: groupId,
                    cus_mapping_id: cusMappingID, // Pass cus_mapping_id
                    share_id:share_id, // Pass cus_mapping_id
                    label: label,
                    remark: remark,
                    commitment_date:commitment_date
                }, function (response) {
                    if (response == '1') {
                        swalSuccess('Success', 'Commitment Added Successfully!');
                        $('#label').val('');
                        $('#remark').val('');
                        $('#commitment_date').val('');
                        getCommitmentInfoTable(cusMappingID, groupId,share_id);
                    } else {
                        swalError('Warning', 'Commitment Not Added!');
                    }
                });
            }
        });

        $(document).on('click', '.commitDeleteBtn', function () {
            var id = $(this).attr('value');
            swalConfirm('Delete', 'Do you want to Delete the Commitment Details?', function () {
                getCommitDelete(id, cusMappingID, groupId, share_id); // Pass cusMappingID to delete function
            });
        });
    });


    ///////////////////////////////////////////////////////Commitement  End/////////////////////////////////////////////////
    ///////////////////////////////////////////////////////Due Start/////////////////////////////////////////////
    $(document).on('click', '.add_due', function (event) {
        event.preventDefault();
        $('#due_chart_model').modal('show');
        let dataValue = $(this).data('value');
        let dataParts = dataValue.split('_');
        let groupId = dataParts[0];
        let cusMappingID = dataParts[1];
        let auction_month = dataParts[2];
        let share_id = dataParts[3];
        var tbody = $('#due_chart_table tbody');
        tbody.empty(); // Clear existing rows
        getDueChart(groupId, cusMappingID, auction_month,share_id).then(function(response){
            $('.print_due_coll').click(function () {
                // Fetch the data from the server and create a table with it
                const coll_id = $(this).attr('id');
                $.ajax({
                    url: 'api/collection_files/print_collection.php', // Update with the correct path to your PHP script
                    type: 'POST',
                    data: {
                        coll_id: coll_id,
                    },
                    dataType: 'json',
                    success: function (response) {
                        // Create the HTML content with formatted values
                        let rows = response.map(row => `
                            <tr>
                                <td><strong>Group ID</strong></td>
                                <td>${row.group_id}</td>
                            </tr>
                            <tr>
                                <td><strong>Group Name</strong></td>
                                <td>${row.group_name}</td>
                            </tr>
                            <tr>
                                <td><strong>Customer Name</strong></td>
                                <td>${row.cus_name}</td>
                            </tr>
                            <tr>
                                <td><strong>Auction Month</strong></td>
                                <td>${row.auction_month}</td>
                            </tr>
                            <tr>
                                <td><strong>Chit Amount</strong></td>
                                <td>${moneyFormatIndia(row.chit_amount)}</td>
                            </tr>
                            <tr>
                                <td><strong>Payable</strong></td>
                                <td>${moneyFormatIndia(row.payable)}</td>
                            </tr>
                            <tr>
                                <td><strong>Collection Date</strong></td>
                                <td>${row.collection_date}</td>
                            </tr>
                            <tr>
                                <td><strong>Collection Amount</strong></td>
                                <td>${moneyFormatIndia(row.collection_amount)}</td>
                            </tr>
                            <tr>
                                <td><strong>Pending</strong></td>
                                <td>${moneyFormatIndia(row.pending)}</td>
                            </tr>
                        `).join('');
                        
                        // HTML Content with consistent alignment and styling
                        const content = `
                        <div id="print_content" style="text-align: center; font-size: 13px;">
                            <h2 style="margin-bottom: 20px;">
                                <img src="img/bg_none_eng_logo.png" style="width: 125px; height: 90px;" />
                            </h2>
                            <table style="margin: 0 auto; border-collapse: collapse; width: 90%; text-align: left; border: none;">
                                ${rows}
                            </table>
                        </div>
                        `;

                        // Create a temporary iframe to hold the content for printing
                        const printWindow = window.open('', '_blank');
                        printWindow.document.write(`
                            <html>
                            <head>
                                <title>Print Collection Details</title>
                                <style>
                                    body {
                                        font-family: Arial, sans-serif;
                                        margin: 0;
                                        padding: 0;
                                        text-align: center;
                                    }
                                    table {
                                        width: 90%;
                                        margin: 0 auto;
                                        border-collapse: collapse;
                                        table-layout: fixed; /* Ensures equal column widths */
                                        border: none;
                                    }
                                    td {
                                        padding: 4px;
                                        border: none;
                                        font-size: 13px;
                                        word-wrap: break-word;
                                    }
                                    .label {
                                        font-weight: bold;
                                        text-align: right;
                                        width: 40%;
                                    }
                                    h2 img {
                                        display: block;
                                        margin: 0 auto;
                                    }
                                </style>
                            </head>
                            <body>
                                ${content}
                            </body>
                            </html>
                        `);
                        printWindow.document.close();

                        // Trigger the print dialog
                        setTimeout(() => {
                            printWindow.focus();
                            printWindow.print(); 
                            printWindow.close();  
                        }, 1000);
                    },
                });
            });
        });
    });
    ////////////////////////////////////////////////////////Due End////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////Commitement Chart Start////////////////////////////////////////////
    $(document).on('click', '.commitment_chart', function (event) {
        event.preventDefault();
        $('#commitment_chart_model').modal('show');
        let dataValue = $(this).data('value');
        let dataParts = dataValue.split('_');
        let groupId = dataParts[0];
        let cusMappingID = dataParts[1];
        let share_id = dataParts[2];
        getCommitmentChartTable(groupId,cusMappingID,share_id)

    });
    ///////////////////////////////////////////////////////Commitement Chart End/////////////////////////////////////////////////
    /////////////////////////////////////Document End//////////////////////////////////////////////////////////////////    
})
function closeChartsModal() {
    $('#due_chart_model').modal('hide');
    $('#commitment_chart_model').modal('hide');
    $('#add_commitment_modal').modal('hide');
    $('#label').val('');
    $('#remark').val('');
    $('#commitment_date').val('');
}
$(function () {
    getCollectionTable();
});

function getCollectionTable() {
    serverSideTable('#collection_list_table', '', 'api/collection_files/collection_list.php');
}
function editCustomerCreation(id) {
    $.post('api/collection_files/collection_customer_data.php', { id: id }, function (response) {
        if (Array.isArray(response) && response.length > 0) {
            $('#group_id').val(id);
            $('#cus_id').val(response[0].cus_id);
            $('#cus_name').val(response[0].cus_name); // Full name in a single field
            $('#place').val(response[0].place);
            $('#mobile1').val(response[0].mobile1);
            $('#occupation').val(response[0].occupations); // Assuming you have a field for occupations
            $('#referred_by').val(response[0].reference_type); // Assuming you have a field for reference_type

            // Handle image path if necessary
            let path = "uploads/customer_creation/cus_pic/";
            $('#per_pic').val(response[0].pic);
            var img = $('#imgshow');
            img.attr('src', path + response[0].pic);
        } else {
            alert("No data found for this customer.");
        }
    }, 'json').fail(function (jqXHR, textStatus, errorThrown) {
        console.error("AJAX request failed:", textStatus, errorThrown);
    });
}
function viewCustomerGroups(id) {
    let params = { 'id': id };
    serverSideTable('#group_list_table', params, 'api/collection_files/collection_group_data.php');
    // setDropdownScripts();
}
function collectDate() {
    var today = new Date();
    var day = String(today.getDate()).padStart(2, '0');
    var month = String(today.getMonth() + 1).padStart(2, '0'); // January is 0
    var year = today.getFullYear();

    var currentDate = day + '-' + month + '-' + year;
    $('#collection_date').val(currentDate);
}
function commitDate() {
    var today = new Date();
    var day = String(today.getDate()).padStart(2, '0');
    var month = String(today.getMonth() + 1).padStart(2, '0'); // January is 0
    var year = today.getFullYear();

    var currentDate = day + '-' + month + '-' + year;
    $('#comm_date').val(currentDate);
}
function getCommitmentInfoTable(cusMappingID, groupId ,share_id) {
    $.post('api/collection_files/commitment_info_data.php', { cus_mapping_id: cusMappingID, group_id: groupId,share_id:share_id }, function (response) {
        var columnMapping = [
            'sno',
            'created_on',
            'label',
            'commitment_date',
            'remark',
            'action'

        ];
        appendDataToTable('#commit_form_table', response, columnMapping);
        setdtable('#commit_form_table');
    }, 'json')
}
function getCommitDelete(id, cusMappingID, groupId, share_id) {
    $.post('api/collection_files/delete_commitment.php', { id: id }, function (response) {
        if (response === '1') {
            swalSuccess('Success', 'Commitment Info Deleted Successfully!');
            getCommitmentInfoTable(cusMappingID, groupId, share_id); // Refresh the table after deletion
        } else {
            swalError('Error', 'Failed to Delete Commitment: ' + response);
        }
    }, 'json');
}

function getCommitmentChartTable(groupId ,cusMappingID,share_id) {
    $.post('api/collection_files/commitment_chart_data.php', {group_id: groupId ,cus_mapping_id: cusMappingID,share_id:share_id}, function (response) {
        var columnMapping = [
            'sno',
            'created_on',
            'label',
            'commitment_date',
            'remark',
        ];
        appendDataToTable('#commitment_chart_table', response, columnMapping);
        setdtable('#commitment_chart_table');
    }, 'json')
}
function getDueChart(groupId, cusMappingID, auction_month,share_id) {
    return new Promise(function (resolve, reject){
        $.ajax({
            url: 'api/collection_files/due_chart_data.php', // Update this with the correct path to your PHP script
            type: 'POST',
            dataType: 'json',
            data: {
                group_id: groupId,
                cus_mapping_id: cusMappingID,
                auction_month: auction_month,
                share_id: share_id
            },
            success: function (response) {
                var tbody = $('#due_chart_table tbody');
    
                // Track whether we have added any rows
                var hasRows = false;
    
                $.each(response, function (index, item) {
                    var auctionMonth = item.auction_month;
                    var auctionDate = item.auction_date;
    
                    // Format the values using moneyFormatIndia
                    var chitAmount = item.chit_share ? moneyFormatIndia(Math.round(item.chit_share)) : '';
                  //  var payable = item.payable ? moneyFormatIndia(item.payable) : '';
                    var collectionDate = item.collection_date ? item.collection_date : '';
                    var collectionAmount = item.collection_amount ? moneyFormatIndia(item.collection_amount) : '';
                    //  var pending = item.pending;
                    var pending = item.pending !== null && item.pending !== undefined ? moneyFormatIndia(item.pending) : '';
                  var initialPayableAmount = item.initial_payable_amount ? moneyFormatIndia(item.initial_payable_amount) : '';
                    var action = item.action ? item.action : '';
    
                    var row = '<tr>' +
                        '<td>' + auctionMonth + '</td>' +
                        '<td>' + auctionDate + '</td>' +
                        '<td>' + chitAmount + '</td>' +
                        '<td>' + initialPayableAmount + '</td>' +
                        '<td>' + collectionDate + '</td>' +
                        '<td>' + collectionAmount + '</td>' +
                        '<td>' + pending + '</td>' +
                        '<td>' + action + '</td>' +
                        '</tr>';
    
                    tbody.append(row);
                    hasRows = true;
                });
    
                // If no data was found in the response
                if (!hasRows) {
                    // Display a row with auction_month and a 'No data available' message
                    var noDataRow = '<tr>' +
                        '<td>' + auction_month + '</td>' +
                        '<td>' + '' + '</td>' +
                        '<td>' + '' + '</td>' +
                        '<td>' + '' + '</td>' +
                        '<td>' + '' + '</td>' +
                        '<td>' + '' + '</td>' +
                        '<td>' + '' + '</td>' +
                        '<td>' + '' + '</td>' +
                        '</tr>';
                    tbody.append(noDataRow);
                }
                //Resolve the pormise when ajax completes successfully
                resolve(response);
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error: ' + status + error);
                // Reject the promise if there is an error.
                reject(new Error('Failed to fetch data:' + error));
            }
        });
    });
}
function getBankName() {
    $.post('api/settlement_files/get_bank_name.php', function (response) {
        let appendBankOption = "<option value=''>Select Bank Name</option>";
        $.each(response, function (index, val) {
            let selected = '';
            let editGId = $('#bank_name_edit').val(); // Existing guarantor ID (if any)
            if (val.id == editGId) {
                selected = 'selected';
            }
            appendBankOption += "<option value='" + val.id + "' " + selected + ">" + val.bank_name + "</option>";
        });
        $('#bank_name').empty().append(appendBankOption);
    }, 'json');
}
