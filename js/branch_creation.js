$(document).ready(function () {
    $(document).on('click', '#add_branch, #back_btn', function () {
        swapTableAndCreation();
    });

    $('#state').change(function () {
        getDistrictList($(this).val());
    });
    $('#district').change(function () {
        getTalukList($(this).val());
    });


    $('#submit_branch_creation').click(function () {
        event.preventDefault();
        //Validation
        let company_name = $('#company_name').val(); let branch_code = $('#branch_code').val(); let branch_name = $('#branch_name').val(); let address = $('#address').val(); let state = $('#state').val(); let district = $('#district').val(); let taluk = $('#taluk').val(); let place = $('#place').val(); let pincode = $('#pincode').val(); let email_id = $('#email_id').val(); let mobile_number = $('#mobile_number').val(); let whatsapp = $('#whatsapp').val(); let landline = $('#landline').val(); let landline_code = $('#landline_code').val(); let branchid = $('#branchid').val();
        var data = ['company_name', 'branch_code', 'branch_name','place','state', 'district', 'taluk', 'pincode']
        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });
        if (isValid) {
            $.post('api/branch_creation/submit_branch_creation.php', { company_name, branch_code, branch_name, address, state, district, taluk, place, pincode, email_id, mobile_number, whatsapp, landline, landline_code, branchid }, function (response) {
                if (response == '1') {
                    swalSuccess('Success', 'Branch Added Successfully!');
                } else {
                    swalSuccess('Success', 'Branch Updated Successfully!')
                }
                $('#branchid').val('');
                $('#branch_creation').trigger('reset');
                getBranchTable();
                swapTableAndCreation();//to change to div to table content.

            });

        }
    });

    $(document).on('click', '.branchActionBtn', function () {
        var id = $(this).attr('value'); // Get value attribute
        $.post('api/branch_creation/get_branch_creation_data.php', {id}, function (response) {
            swapTableAndCreation();
            $('#branchid').val(id);
            $('#company_name').val(response[0].company_name);
            $('#branch_name').val(response[0].branch_name);
            $('#address').val(response[0].address);
            getDistrictList(response[0].state);
            getTalukList(response[0].district);
            $('#place').val(response[0].place);
            $('#pincode').val(response[0].pincode);
            $('#email_id').val(response[0].email_id);
            $('#mobile_number').val(response[0].mobile_number);
            $('#whatsapp').val(response[0].whatsapp);
            $('#landline_code').val(response[0].landline_code);
            $('#landline').val(response[0].landline);
            setTimeout(() => {
                $('#state').val(response[0].state);
                $('#district').val(response[0].district);
                $('#taluk').val(response[0].taluk);
                $('#branch_code').val(response[0].branch_code);
            }, 1000);

        }, 'json');
    });

    $(document).on('click', '.branchDeleteBtn', function () {
        var id = $(this).attr('value');
        swalConfirm('Delete', 'Do you want to Delete the Branch Name?', getBranchDelete, id);
        return;
    });
    $('#mobile_number, #whatsapp').change(function () {
        checkMobileNo($(this).val(), $(this).attr('id'));
    });
    $('#landline').change(function () {
        checkLandlineFormat($(this).val(), $(this).attr('id'));
    });
    $('#email_id').on('change', function () {
        validateEmail($(this).val(), $(this).attr('id'));
    });

}) //Document END///


$(function () {
    getBranchTable()


});

function getBranchTable() {
    serverSideTable('#branch_create', '', 'api/branch_creation/branch_creation_list.php');
}
function swapTableAndCreation() {
    if ($('.branch_table_content').is(':visible')) {
        $('.branch_table_content').hide();
        $('#add_branch').hide();
        $('#branch_creation_content').show();
        $('#back_btn').show();

        getCompanyName();
        getStateList();

    } else {
        $('.branch_table_content').show();
        $('#add_branch').show(); // Show the Add Branch button
        $('#branch_creation_content').hide();
        $('#back_btn').hide();
    }
}

function getStateList() {
    $.post('api/common_files/get_state_list.php', function (response) {
        let appendStateOption = '';
        appendStateOption += "<option value=''>Select State</option>";
        $.each(response, function (index, val) {
            appendStateOption += "<option value='" + val.id + "'>" + val.state_name + "</option>";
        });
        $('#state').empty().append(appendStateOption);
    }, 'json');
}
function getDistrictList(state_id) {
    $.post('api/common_files/get_district_list.php', { state_id }, function (response) {
        let appendDistrictOption = '';
        appendDistrictOption += "<option value=''>Select District</option>";
        $.each(response, function (index, val) {
            appendDistrictOption += "<option value='" + val.id + "'>" + val.district_name + "</option>";
        });
        $('#district').empty().append(appendDistrictOption);
    }, 'json');
}

function getTalukList(district_id) {
    $.post('api/common_files/get_taluk_list.php', { district_id }, function (response) {
        let appendTalukOption = '';
        appendTalukOption += "<option value=''>Select Taluk</option>";
        $.each(response, function (index, val) {
            appendTalukOption += "<option value='" + val.id + "'>" + val.taluk_name + "</option>";
        });
        $('#taluk').empty().append(appendTalukOption);
    }, 'json');
}
function getCompanyName() {
    $.ajax({
        url: 'api/branch_creation/getCompanyName.php',
        type: 'POST',
        data: {},
        dataType: 'json',
        cache: false,
        success: function (response) {
            $('#company_name').val(response['company_name']);
        },
        error: function (xhr, status, error) {
            swalError('Error', status + error);
        }
    }).then(function () {
        getBranchCode();
    })
}

function getBranchCode() {
    var company_name = $('#company_name').val();
    $.ajax({
        url: "api/branch_creation/getBranchCode.php",
        type: 'POST',
        data: { company_name },
        dataType: 'json',
        cache: false,
        success: function (response) {

            if (response.hasOwnProperty('branch_code')) {
                $('#branch_code').val(response['branch_code']);
            } else {

                console.error('Unexpected response format:', response);
            }
        },
        error: function (xhr, status, error) {

            console.error('AJAX Error:', status, error);
        }
    });
}
$('button[type="reset"], #back_btn').click(function () {
    event.preventDefault();
    $('input').each(function () {
        var id = $(this).attr('id');
        if (id !== 'company_name' && id !== 'branch_code') {
            $(this).val('');
        }
    });
    $('textarea').val('');

    $('select').each(function () {
        $(this).val($(this).find('option:first').val());

    });
    $('input').css('border', '1px solid #cecece');
    $('select').css('border', '1px solid #cecece');
});

function getBranchDelete(id) {
    $.post('api/branch_creation/delete_branch_creation.php', { id }, function (response) {
        if (response == '1') {
            swalSuccess('Success', 'Branch Deleted Successfully!');
            getBranchTable();
        } else if (response == '2') {
            swalError('Access Denied', 'Used in User Creation');
        }  else {
            swalError('Error', 'Failed to Delete Branch');
        }
    }, 'json');
}

