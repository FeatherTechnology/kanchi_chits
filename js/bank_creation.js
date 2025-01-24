const underBranchChoices = new Choices('#under_branch', {
    removeItemButton: true,
    noChoicesText: 'No branches available',
    allowHTML: true,
});

$(document).ready(function () {
    $(document).on('click', '#add_bank, #back_btn', function () {
        swapTableAndCreation();
        getUnderBranchDropdown();
    });

    $('#submit_bank_creation').click(function (event) {
        event.preventDefault();
        //Validation
        let qr_code = $('#qr_code')[0].files[0]; let inserted_qr_code = $('#inserted_qr_code').val(); let bank_name = $('#bank_name').val(); let bank_short_name = $('#bank_short_name').val(); let account_number = $('#account_number').val(); let ifsc_code = $('#ifsc_code').val(); let branch_name = $('#branch_name').val(); let gpay = $('#gpay').val(); let under_branch = $('#under_branch').val(); let status = $('#status').val(); let bank_id = $('#bank_id').val();
        var data = ['bank_name', 'bank_short_name', 'account_number', 'ifsc_code', 'branch_name']

        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });
        let isMultiSelectValid = validateMultiSelectField('under_branch', underBranchChoices);
        if (isValid && isMultiSelectValid) {
            let bankDetail = new FormData();
            bankDetail.append('bank_name', bank_name)
            bankDetail.append('bank_short_name', bank_short_name)
            bankDetail.append('account_number', account_number)
            bankDetail.append('ifsc_code', ifsc_code)
            bankDetail.append('branch_name', branch_name)
            bankDetail.append('qr_code', qr_code)
            bankDetail.append('inserted_qr_code', inserted_qr_code)
            bankDetail.append('gpay', gpay)
            bankDetail.append('under_branch', under_branch)
            bankDetail.append('status', status)
            bankDetail.append('bank_id', bank_id)
            $.ajax({
                url: 'api/bank_creation_files/submit_bank_creation.php',
                type: 'post',
                data: bankDetail,
                contentType: false,
                processData: false,
                cache: false,
                success: function (response) {
                    if (response = 'Success') {
                        if (bank_id == '') {
                            swalSuccess('Success', 'Bank Added Successfully!');
                        } else {
                            swalSuccess('Success', 'Bank Updated Successfully!')
                        }
                    } else {
                        swalError('Error', 'Error in table');
                    }
                    $('#bank_id').val('');
                    $('#branch_name2').val('');
                    $('#bank_creation').trigger('reset');
                    getBankTable();
                    swapTableAndCreation();//to change to div to table content.
                }
            });
        }
    });
    $(document).on('click', '.bankActionBtn', function () {
        var id = $(this).attr('data-value'); // Use data-value attribute
        $.post('api/bank_creation_files/get_bank_creation_data.php', { id: id }, function (response) {
            if (response && response.length > 0) {
                swapTableAndCreation();
                $('#bank_id').val(id);
                $('#bank_name').val(response[0].bank_name);
                $('#bank_short_name').val(response[0].bank_short_name);
                $('#account_number').val(response[0].account_number);
                $('#ifsc_code').val(response[0].ifsc_code);
                $('#branch_name').val(response[0].branch_name);
                $('#inserted_qr_code').val(response[0].qr_code);
                $('#gpay').val(response[0].gpay);
                $('#branch_name2').val(response[0].under_branch);
                
                // Display the QR code file name and set up the click event
                if (response[0].qr_code) {
                    var qrCodeFileName = response[0].qr_code.split('/').pop(); // Get the file name
                    $('#qr_code_filename').html('<a href="uploads/bank_creation/qr_code/' + response[0].qr_code + '" target="_blank">' + qrCodeFileName + '</a>');
                } else {
                    $('#qr_code_filename').html('');
                }
            }
            getUnderBranchDropdown();
        }, 'json');
    });
    

    $(document).on('click', '.bankDeleteBtn', function () {
        var id = $(this).attr('data-value');
        swalConfirm('Delete', 'Do you want to Delete the Bank Details?', getBankDelete, id);
        return;
    });

    // Handle click event on the action buttons
    $(document).on('click', '.bankActiveBtn', function () {
        var id = $(this).data('value');
        swalConfirm('Status', 'Do you want to change the Status?', getStatusChange, id);
        return;

    })
    $('#gpay').change(function () {
        checkMobileNo($(this).val(), $(this).attr('id'));
    });

})

$(function () {
    getBankTable();

});

function getBankTable() {
    $.post('api/bank_creation_files/bank_creation_list.php', function (response) {
        var columnMapping = [
            'sno',
            'bank_name',
            'account_number',
            'branch_name',
            'status',
            'action'
        ];
        appendDataToTable('#bank_create', response, columnMapping);
        setdtable('#bank_create');
    }, 'json')

}

function swapTableAndCreation() {
    if ($('.bank_table_content').is(':visible')) {
        $('.bank_table_content').hide();
        $('#add_bank').hide();
        $('#bank_creation_content').show();
        $('#back_btn').show();

    } else {
        $('.bank_table_content').show();
        $('#add_bank').show();
        $('#bank_creation_content').hide();
        $('#back_btn').hide();
    }
}


function getUnderBranchDropdown() {
    let branch_id = $('#branch_name').val();
    let branch_name2 = $('#branch_name2').val();
    $.post('api/bank_creation_files/get_branch_name_dropdown.php', { branch_id }, function (response) {
        underBranchChoices.clearStore();
        $.each(response, function (index, val) {
            let selected = '';
            if (branch_name2.includes(val.id)) {
                selected = 'selected';
            }
            let items = [
                {
                    value: val.id,
                    label: val.branch_name,
                    selected: selected,
                }
            ];
            underBranchChoices.setChoices(items); // Add choices

        });
    }, 'json');
}

function getBankDelete(id) {
    $.post('api/bank_creation_files/delete_bank_creation.php', { id }, function (response) {
        if (response === '1') {
            swalSuccess('Success', 'Bank Deleted Successfully!');
            getBankTable();
        } else {
            swalError('Error', 'Failed to Delete Bank: ' + response);
        }
    }, 'json');
}
function getStatusChange(id) {
    var row = $(this).closest('tr');
    var statusCell = row.find('td:nth-child(5)');
    $.post('api/bank_creation_files/bank_status.php', { id }, function (response) {
        if (response.success) {
            var newStatus = response.new_status == '0' ? 'Inactive' : 'Active';
            statusCell.text(newStatus);
            getBankTable();
        }
    }, 'json');
}

$('#back_btn').click(function (event) {
    // event.preventDefault();
    $('input, textarea').val('');
    $('#qr_code_filename').html('');
    underBranchChoices.clearInput();
    getUnderBranchDropdown();
    $('#bank_name').css('border', '1px solid #cecece');
    $('#bank_short_name').css('border', '1px solid #cecece');
    $('#account_number').css('border', '1px solid #cecece');
    $('#ifsc_code').css('border', '1px solid #cecece');
    $('#branch_name').css('border', '1px solid #cecece');
    $('#under_branch').closest('.choices').find('.choices__inner').css('border', '1px solid #cecece');
});

