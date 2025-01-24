$(document).ready(function () {
    $(document).on('click', '.addcompanyBtn, .backBtn', function () {
        swapTableAndCreation();
    });

    $('#state').change(function () {
        getDistrictList($(this).val());
    });

    $('#district').change(function () {
        getTalukList($(this).val());
    });

    $('#submit_company_creation').click(function () {
        event.preventDefault();
        //Validation
        let company_name = $('#company_name').val(); let address = $('#address').val(); let state = $('#state').val(); let district = $('#district').val(); let taluk = $('#taluk').val(); let place = $('#place').val(); let pincode = $('#pincode').val(); let website = $('#website').val(); let mailid = $('#mailid').val(); let mobile = $('#mobile').val(); let whatsapp = $('#whatsapp').val(); let landline_code = $('#landline_code').val(); let landline = $('#landline').val(); let companyid = $('#companyid').val();
        var data = ['company_name', 'address', 'state','place', 'district', 'taluk', 'pincode']
        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });
        if (isValid) {
            /////////////////////////// submit page AJAX /////////////////////////////////////
            $.post('api/company_creation_files/submit_company_creation.php', { company_name, address, state, district, taluk, place, pincode, website, mailid, mobile, whatsapp, landline_code, landline, companyid }, function (response) {
                if (response == '1') {
                    swalSuccess('Success', 'Company Added Successfully!');
                } else {
                    swalSuccess('Success', 'Company Updated Successfully!')
                }

                $('#company_creation').trigger('reset');
                getCompanyTable();
                swapTableAndCreation();//to change to div to table content.

            });
            /////////////////////////// submit page AJAX END/////////////////////////////////////
        }
    });

    ///////////////////////////////////// EDIT Screen START   /////////////////////////////////////
    $(document).on('click', '.companyActionBtn', function () {
        var id = $(this).attr('value'); // Get value attribute
        $.post('api/company_creation_files/get_company_creation_data.php', { id }, function (response) {
            $('#companyid').val(id);
            $('#company_name').val(response[0].company_name);
            $('#address').val(response[0].address);
            $('#state').val(response[0].state);

            getDistrictList(response[0].state)
            getTalukList(response[0].district)

            $('#place').val(response[0].place);
            $('#pincode').val(response[0].pincode);
            $('#website').val(response[0].website);
            $('#mailid').val(response[0].mailid);
            $('#mobile').val(response[0].mobile);
            $('#whatsapp').val(response[0].whatsapp);
            $('#landline_code').val(response[0].landline_code);
            $('#landline').val(response[0].landline);

            setTimeout(() => {
                $('#district').val(response[0].district);
                $('#taluk').val(response[0].taluk);
            }, 1000);

            swapTableAndCreation();//to change to div to table content.
        }, 'json');
    })
    ///////////////////////////////////// EDIT Screen END  /////////////////////////////////////

    $('#mobile, #whatsapp').change(function () {       
        checkMobileNo($(this).val(), $(this).attr('id'));
    });

    $('#landline').change(function () {       
        checkLandlineFormat($(this).val(), $(this).attr('id'));
    });
    
    $('#mailid').on('change', function () {
        validateEmail($(this).val(), $(this).attr('id'));
    });

});//Document END.

//OnLoad/////
$(function () {
    getCompanyTable();
    getStateList();
});

function getCompanyTable() {
    $.post('api/company_creation_files/company_creation_list.php', function (response) {
        var columnMapping = [
            'sno',
            'company_name',
            'place',
            'district_name',
            'mobile',
            'action'
        ];
        appendDataToTable('#company_creation_table', response, columnMapping);
        setdtable('#company_creation_table');

        if (response.length > 0) {
            $('#add_company').hide();
        } else {
            $('#add_company').show();
        }
    }, 'json')
}

function swapTableAndCreation() {
    if ($('.company_table_content').is(':visible')) {
        $('.company_table_content').hide();
        $('.addcompanyBtn').hide();
        $('#company_creation_content').show();
        $('.backBtn').show();
    } else {
        $('.company_table_content').show();
        $('#company_creation_content').hide();
        $('.backBtn').hide();
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
$('button[type="reset"],  .backBtn').click(function () {
    event.preventDefault();
    $('#company_creation input').css('border', '1px solid #cecece');
    $('#company_creation select').css('border', '1px solid #cecece');
});