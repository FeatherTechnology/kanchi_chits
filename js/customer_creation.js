$(document).ready(function () {
    $(document).on('click', '#add_customer, #back_btn', function () {
        $('#cus_id').val('');
        swapTableAndCreation();
        setTimeout(() => {
            getSourceTable()
        getGuarantorInfoTable()
        getFamilyInfoTable()
        getPlaceDropdown(place_name_id);
        }, 1000);
        $('#imgshow').attr('src', 'img/avatar.png');
        $('.toRefresh').hide();
        $('.knowData').val('');
        $('#custom_name_edit').val('')


    });
    $(document).on('click', '#back_btn', function () {
        getCustomerEntryTable();
    });

    $('#reference_type').change(function () {
        var referenceType = $(this).val();
        // Hide all fields initially
        $('#cus_name_container').hide();
        $('#name_container').hide();
        $('#mobile_container').hide();
        $('#declaration_container').hide();
        $('#cus_id_container').hide();

        if (referenceType == '1') {
            // Promotion selected
            $('#declaration_container').show();
        } else if (referenceType == '2') {
            // Customer selected
            $('#cus_name_container').show();
            $('#cus_id_container').show();
            $('#declaration_container').show();
            getExistingRefCustomer()
            // getCustomerName(cusId);
        } else if (referenceType == '3') {
            // Well Known Person selected
            $('#name_container').show();
            $('#mobile_container').show();
            $('#declaration_container').show();
        }
    });

    $('#gua_family').on('change', function () {
        // const famRelationship = $(this).find('option:selected').val(); // Get the text of the selected option
        const guarantorId = $(this).val();

        if (guarantorId) {
            getGrelationshipName(guarantorId);
        } else {
            $('#guarantor1_name').val('');
        }
       // updateFieldsVisibility(famRelationship); // Pass the fam_relationship to the function
    });

    $('#cus_name').on('change', function () {
        // Get the text of the selected option
        const cusId = $(this).val();

        if (cusId) {
            getCustomerName(cusId);
        } else {
            $('#ref_cus_id').val('');
        }
    });
    $('#pic').change(function () {
        let pic = $('#pic')[0];
        let img = $('#imgshow');
        img.attr('src', URL.createObjectURL(pic.files[0]));
        checkInputFileSize(this, 200, img)
    })

    $('#gu_pic').change(function () {
        let pic = $('#gu_pic')[0];
        let img = $('#gur_imgshow');
        img.attr('src', URL.createObjectURL(pic.files[0]));
        checkInputFileSize(this, 200, img)
    })
    // Function to format Aadhaar number input
    $('input[data-type="adhaar-number"]').keyup(function () {
        var value = $(this).val();
        value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join(" ");
        $(this).val(value);
    });

    $('input[data-type="adhaar-number"]').change(function () {
        let len = $(this).val().length;
        if (len < 14) {
            $(this).val('');
            swalError('Warning', 'Kindly Enter Valid Aadhaar Number');
        }
    });
    $('#dob').on('change', function () {
        var dob = new Date($(this).val());
        var today = new Date();
        var age = today.getFullYear() - dob.getFullYear();
        var m = today.getMonth() - dob.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
            age--;
        }
        $('#age').val(age);
    });
    ////////////////////////////////////////////////////////// Place Modal START ///////////////////////////////////////////////////////////////////////
    $('#submit_place').click(function (event) {
        event.preventDefault();
        let place = $('#add_place').val(); let id = $('#add_place_id').val();
        var data = ['add_place']
        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });
        if (place != '') {
            if (isValid) {
                $.post('api/user_creation_files/submit_place.php', { place, id }, function (response) {
                    if (response == '0') {
                        swalError('Warning', 'Place Already Exists!');
                    } else if (response == '1') {
                        swalSuccess('Success', 'Place Updated Successfully!');
                    } else if (response == '2') {
                        swalSuccess('Success', 'Place Added Successfully!');
                    }

                    getPlaceTable();
                }, 'json');
                clearPlace(); //To Clear All Fields in Place creation.
            }
        }
    });

    $(document).on('click', '.placeActionBtn', function () {
        var id = $(this).attr('value'); // Get value attribute
        $.post('api/user_creation_files/get_place_data.php', { id }, function (response) {
            $('#add_place_id').val(id);
            $('#add_place').val(response[0].place);
        }, 'json');
    });

    $(document).on('click', '.placeDeleteBtn', function () {
        var id = $(this).attr('value'); // Get value attribute
        swalConfirm('Delete', 'Do you want to Delete the Place?', deletePlace, id);
        return;
    });
    /////////////////////////////////////////////////////////// Place Modal END ///////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////Source Start/////////////////////////////////////////////////////////////////////////

    $('#add_src').click(function (event) {
        event.preventDefault();
        // Validation
        let cus_id = $('#cus_id').val()
        let occupation = $('#occupation').val();
        let occ_detail = $('#occ_detail').val();
        let occ_place = $('#occ_place').val();
        let source = $('#source').val();
        let income = $('#income').val();
        var data = ['occupation', 'occ_detail','occ_place', 'source', 'income']

        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });

        if (isValid) {
            $.post('api/customer_creation_files/submit_source_info.php', { cus_id, occupation, occ_detail,occ_place, source, income }, function (response) {
                getSourceTable();
                totalIncome()
            });
            $('#occupation').val('');
            $('#occ_detail').val('');
            $('#occ_place').val('');
            $('#source').val('');
            $('#income').val('');
        }
    });


    $(document).on('click', '.sourceDeleteBtn', function () {
        var id = $(this).attr('value'); // Get value attribute
        $.post('api/customer_creation_files/delete_source.php', { id }, function (response) {
            if (response == '0') {
                // swalSuccess('Success', 'Document Need Deleted Successfully.');

                getSourceTable();
                totalIncome()
            } else {
                // swalError('Error', 'Document Need Delete Failed.');
            }
        }, 'json');
    })

    ///////////////////////////////////////////////////////////////////Source End/////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////Family modal Start ///////////////////////////////////////////////////////////////////
    $('#submit_family').click(function (event) {
        event.preventDefault();
        // Validation
        let cus_id = $('#cus_id').val();// Remove spaces from cus_id
        let fam_name = $('#fam_name').val();
        let fam_relationship = $('#fam_relationship').val();
        let fam_age = $('#fam_age').val();
        let fam_live = $('#fam_live').val();
        let fam_occupation = $('#fam_occupation').val();
        let fam_aadhar = $('#fam_aadhar').val().replace(/\s/g, '');
        let fam_mobile = $('#fam_mobile').val();
        let family_id = $('#family_id').val();

        var data = ['fam_name', 'fam_relationship','fam_mobile']

        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });

        if (isValid) {
            $.post('api/customer_creation_files/submit_family_info.php', { cus_id, fam_name, fam_relationship, fam_age, fam_live, fam_occupation, fam_aadhar, fam_mobile, family_id }, function (response) {
                if (response == '1') {
                    swalSuccess('Success', 'Family Info Added Successfully!');
                } else {
                    swalSuccess('Success', 'Family Info Updated Successfully!');
                }
                // Refresh the family table
                getFamilyTable();
            });
        }
    });
    $(document).on('click', '.familyActionBtn', function () {
        var id = $(this).attr('value'); // Get value attribute
        $.post('api/customer_creation_files/family_creation_data.php', { id: id }, function (response) {
            $('#family_id').val(id);
            $('#fam_name').val(response[0].fam_name);
            $('#fam_relationship').val(response[0].fam_relationship);
            $('#fam_age').val(response[0].fam_age);
            $('#fam_live').val(response[0].fam_live);
            $('#fam_occupation').val(response[0].fam_occupation);
            $('#fam_aadhar').val(response[0].fam_aadhar);
            $('#fam_mobile').val(response[0].fam_mobile);
        }, 'json');
    });

    $(document).on('click', '.familyDeleteBtn', function () {
        var id = $(this).attr('value');
        swalConfirm('Delete', 'Do you want to Delete the Family Details?', getFamilyDelete, id);
        return;
    });
    $('#clear_fam_form').click(function (event) {
        event.preventDefault()
        $('#family_id').val('');
        $('#family_form select').each(function () {
            $(this).val($(this).find('option:first').val());
    
        });
        $('#family_form textarea').val('');
        $('#family_form input').val('');
       
    })
    //////////////////////////////////////////////////////////////////////Family Modal end //////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////Guarantor Modal Start/////////////////////////////////////////////////////////
    $('#submit_guarantor').click(function (event) {
        event.preventDefault();

        let cus_id = $('#cus_id').val();
        let gua_name = $('#gua_name').val();
        let gua_family = $('#gua_family').val();
        let gua_name_val = $('#gua_family :selected').text();
        let fam_name = $("#guarantor1_name").val();
        let other_name = $("#other_name").val();
        let existing_cus = $('#existing_cus').val();
        let existing_cus_name = $('#existing_cus :selected').text();
        let details = $('#details').val();
        let gu_pic = $('#gu_pic')[0].files[0];
        let gur_pic = $('#gur_pic').val();
        let guarantor_id = $('#guarantor_id').val();

        let relationship_type;
        let existing_cus_id = ''; // Initialize
        let family_id = ''; // Initialize
        let guarantor_relationship = '';
        let guarantor_name = '';

        if (gua_name === '2') {
            relationship_type = 1;
            existing_cus_id = existing_cus;
            guarantor_relationship = 'Existing Customer'; // Set fixed text
            guarantor_name = existing_cus_name;
        } else if (gua_name === '3') {
            relationship_type = 2;
            guarantor_relationship = 'Other';
            guarantor_name = other_name;
        } else {
            relationship_type = 3;
            family_id = gua_family;
            guarantor_relationship = fam_name;
            guarantor_name = gua_name_val;
        }

        let guaDetail = new FormData();
        guaDetail.append('cus_id', cus_id);
        guaDetail.append('relationship_type', relationship_type);
        guaDetail.append('guarantor_name', guarantor_name); // Store correct guarantor name here
        guaDetail.append('guarantor_relationship', guarantor_relationship);
        guaDetail.append('existing_cus_id', existing_cus_id);
        guaDetail.append('family_id', family_id);
        guaDetail.append('details', details);
        guaDetail.append('gu_pic', gu_pic);
        guaDetail.append('gur_pic', gur_pic);
        guaDetail.append('guarantor_id', guarantor_id);
        guaDetail.append('gua_name', gua_name);
        guaDetail.append('gua_family', gua_family);
        guaDetail.append('guarantor1_name', guarantor1_name);
        guaDetail.append('existing_cus', existing_cus);
        guaDetail.append('other_name', other_name);


        if (isFormDataValid(guaDetail)) {
            $.ajax({
                url: 'api/customer_creation_files/submit_guarantor.php',
                type: 'post',
                data: guaDetail,
                contentType: false,
                processData: false,
                cache: false,
                success: function (response) {
                    response = JSON.parse(response);
                    if (response === 'Success') {
                        if (guarantor_id === '') {
                            swalSuccess('Success', 'Guarantor Info Added Successfully!');
                        } else {
                            swalSuccess('Success', 'Guarantor Info Updated Successfully!');
                        }
                    } else {
                        swalError('Error', 'Error in table');
                    }
                    getGuarantorTable();
                }
            });
        }
    });

    $(document).on('click', '.guaranatorActionBtn', function () {
        let id = $(this).attr('value'); // Get value attribute

        $.post('api/customer_creation_files/guarantor_creation_data.php', { id: id }, function (response) {
            if (response && response.length > 0) {
                // Assuming response is an array with one object
                var data = response[0];
                $('#guarantor_id').val(id);
                $('#cus_id').val(data.cus_id);
                $('#details').val(data.details);
                //  $('#gur_pic').val(data.gu_pic);
                $('#existing_cus').val(data.existing_cus);
                let relationship_type = data.relationship_type;
                let guarantor_relationship = data.guarantor_relationship
                let gua_name_val = data.guarantor_name;
                let existing_cus_id = data.existing_cus_id;
                let family_id = data.family_id;
                let gu_pic = data.gu_pic;
                let guarantor_name = data.guarantor_name;

                // Hide all containers initially
                $('#fam_name_container,#name1_container, #existing_cus_container, #details_container, #name2_container').hide();

                if (relationship_type === '1') {
                    $('#gua_name').val('2');
                    getExistingCustomer(); // Fetch and populate existing customers
                    setTimeout(() => {
                        $('#customer_name_edit').val(existing_cus_id);
                        $('#existing_cus').val(existing_cus_id).trigger('change'); // Update and trigger change
                    }, 1000);
                    $('#existing_cus_container').show();
                } else if (relationship_type === '2') {
                    $('#gua_name').val('3');
                    $('#other_name').val(guarantor_name);
                    $('#name2_container').show();
                    $('#details_container').show();
                } else {
                    $('#gua_name').val('1');
                    setTimeout(()=>{
                        $('#gua_name_edit').val(family_id);
                        $('#gua_family').val(family_id).trigger('change');
                    },1000);
                    $('#guarantor1_name').val(guarantor_relationship);
                    $('#name1_container').show();
                    $('#fam_name_container').show();
                    getGuarantorFamily()
                }
                let paths = "uploads/customer_creation/gu_pic/";
                if (data.gu_pic) {
                    $('#gur_pic').val(data.gu_pic);
                    $('#gur_imgshow').attr('src', paths + data.gu_pic);
                } else {
                    $('#gur_imgshow').attr('src', 'img/avatar.png');
                }
            } else {
                console.error('No data found for ID:', id);
            }
        }, 'json');
    });


    $(document).on('click', '.guarantorDeleteBtn', function () {
        var id = $(this).attr('value');
        swalConfirm('Delete', 'Do you want to Delete the Guarantor Details?', getGuaDelete, id);
        return;
    });

    $('#clear_gua_form').click(function (event) {
        event.preventDefault()
        $('#guarantor_id').val('');
        $('#guarantor_form select').each(function () {
            $(this).val($(this).find('option:first').val());
    
        });
        $('#guarantor_form textarea').val('');
        $('#guarantor_form input').val('');
       
    })
    ///////////////////////////////////////////////////////////////////////Guarantor Modal End////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////Customer creation Start/////////////////////////////////////////////////////////

    $('#submit_cus_creation').click(function (event) {
        event.preventDefault();

        // Validation
        let source_create = $('#source_create').DataTable().rows().count();
        let famInfoRowCount = $('#fam_info_table').DataTable().rows().count();
        let guar_info_table = $('#guar_info_table').DataTable().rows().count();
        let reference_type = $('#reference_type').val();
        let cus_name = $('#cus_name').val();
        let name = $('#name').val();
        let ref_cus_id = $('#ref_cus_id').val();
        let mobile = $('#mobile').val();
        let declaration = $('#declaration').val();
        let cus_id = $('#cus_id').val();
        let aadhar_number = $('#aadhar_number').val().replace(/\s/g, '');
        let first_name = $("#first_name").val();
        let last_name = $('#last_name').val();
        let dob = $('#dob').val();
        let age = $('#age').val();
        let place = $('#place').val();
        let mobile1 = $('#mobile1').val();
        let mobile2 = $('#mobile2').val();
        let whatsapp = $('#whatsapp').val();
        let address = $('#address').val();
        let native_address = $('#native_address').val();
        let pic = $('#pic')[0].files[0];
        let per_pic = $('#per_pic').val();
        let tot_income = $('#tot_income').val().replace(/,/g, '');
        let chit_limit = $('#chit_limit').val().replace(/,/g, '');
        let reference = $('#reference').val();
        let customer_id = $('#customer_id').val();

        let cusDetail = new FormData();
        cusDetail.append('cus_id', cus_id);
        cusDetail.append('reference_type', reference_type);
        cusDetail.append('cus_name', cus_name);
        cusDetail.append('ref_cus_id', ref_cus_id);
        cusDetail.append('name', name);
        cusDetail.append('mobile', mobile);
        cusDetail.append('declaration', declaration);
        cusDetail.append('aadhar_number', aadhar_number);
        cusDetail.append('first_name', first_name);
        cusDetail.append('last_name', last_name);
        cusDetail.append('dob', dob);
        cusDetail.append('age', age);
        cusDetail.append('place', place);
        cusDetail.append('mobile1', mobile1);
        cusDetail.append('mobile2', mobile2);
        cusDetail.append('whatsapp', whatsapp);
        cusDetail.append('address', address);
        cusDetail.append('native_address', native_address);
        cusDetail.append('pic', pic);
        cusDetail.append('per_pic', per_pic);
        cusDetail.append('tot_income', tot_income);
        cusDetail.append('chit_limit', chit_limit);
        cusDetail.append('reference', reference);
        cusDetail.append('customer_id', customer_id);

        var data = ['cus_id', 'first_name', 'last_name', 'aadhar_number', 'mobile1','place','address', 'chit_limit', 'reference']

        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });
       
        if (isValid) {
            if (source_create === 0 || famInfoRowCount === 0 || guar_info_table === 0) {
                swalError('Warning', 'Please Fill out Source Info ,Family Info and Guarantor Info!');
                return false;
            }
            $.ajax({
                url: 'api/customer_creation_files/submit_customer.php',
                type: 'post',
                data: cusDetail,
                contentType: false,
                processData: false,
                cache: false,
                success: function (response) {
                    response = JSON.parse(response);
                    if (response == '1') {
                        swalSuccess('Success', 'Customer Info Added Successfully!');
                    } else {
                        swalSuccess('Success', 'Customer Info Updated Successfully!');
                    }
                    $('#customer_id').val('');
                    $('#customer_creation').trigger('reset');
                    getCustomerEntryTable();
                    swapTableAndCreation()

                }
            });
        }
    });

    $(document).on('click', '.customerActionBtn', function () {
        let id = $(this).attr('value');
        $('#customer_id').val(id);

        swapTableAndCreation();
        editCustomerCreation(id)

    });

    $(document).on('click', '.customerDeleteBtn', function () {
        var id = $(this).attr('value');
        swalConfirm('Delete', 'Do you want to Delete the Customer Details?', getCustomerDelete, id);
        return;
    });
    //////////////////////////////////////////////////////////////////////////Customer creation end///////////////////////////////////////////////////////
    $('input[name="mobile_whatsapp"]').on('change', function () {
        let selectedValue = $(this).val();
        let mobileNumber;

        if (selectedValue === 'mobile1') {
            mobileNumber = $('#mobile1').val();
        } else if (selectedValue === 'mobile2') {
            mobileNumber = $('#mobile2').val();
        }

        $('#whatsapp').val(mobileNumber);
    });


    $('#mobile,#mobile1,#mobile2,#fam_mobile,#whatsapp').change(function () {
        checkMobileNo($(this).val(), $(this).attr('id'));
    });
})

$(function () {
    getCustomerEntryTable();
});

function swapTableAndCreation() {
    if ($('.customer_table_content').is(':visible')) {
        $('.customer_table_content').hide();
        $('#add_customer').hide();
        $('#customer_creation_content').show();
        $('#back_btn').show();
        // let customer_id = $('#customer_id').val();
        getAutoGenCusId('');
    } else {
        $('.customer_table_content').show();
        $('#add_customer').show();
        $('#customer_creation_content').hide();
        $('#back_btn').hide();
    }
}
function clearPlace() {
    $('#add_place').val('');
    $('#add_place_id').val('0');
    $('#add_place').css('border', '1px solid #cecece');
}

function getPlaceTable() {
    $.post('api/user_creation_files/get_place_list.php', function (response) {
        let placeList = [
            "sno",
            "place",
            "action"
        ]
        appendDataToTable('#place_modal_table', response, placeList);
        setdtable('#place_modal_table');
    }, 'json');
}

function deletePlace(id) {
    $.post('api/user_creation_files/delete_place.php', { id }, function (response) {
        if (response == '1') {
            swalSuccess('Success', 'Place Deleted Successfully.');
            getPlaceTable();
        } else if (response == '0') {
            swalError('Access Denied', 'Used in User Creation');
        } else {
            swalError('Error', 'Place Delete Failed.');
        }
    }, 'json');
}

function getPlaceDropdown(place_name_id) {
    $.post('api/user_creation_files/get_place_list.php', function (response) {
        let appendPlaceNameOption = '';
        appendPlaceNameOption += '<option value="">Select Place</option>';
        $.each(response, function (index, val) {
            let selected = '';
            if (val.id == place_name_id) {
                selected = 'selected';
            }
            appendPlaceNameOption += '<option value="' + val.id + '" ' + selected + '>' + val.place + '</option>';
        });
        $('#place').empty().append(appendPlaceNameOption);

        clearPlace()
    }, 'json');
}
function getFamilyInfoTable() {
    let cus_id = $('#cus_id').val()

    $.post('api/customer_creation_files/family_creation_list.php', { cus_id }, function (response) {
        var columnMapping = [
            'sno',
            'fam_name',
            'fam_relationship',
            'fam_age',
            'fam_live',
            'fam_occupation',
            'fam_aadhar',
            'fam_mobile',
        ];
        appendDataToTable('#fam_info_table', response, columnMapping);
        setdtable('#fam_info_table');
    }, 'json')
}

function getFamilyTable() {
    let cus_id = $('#cus_id').val()
    $.post('api/customer_creation_files/family_creation_list.php', { cus_id: cus_id }, function (response) {
        var columnMapping = [
            'sno',
            'fam_name',
            'fam_relationship',
            'fam_age',
            'fam_live',
            'fam_occupation',
            'fam_aadhar',
            'fam_mobile',
            'action'
        ];
        appendDataToTable('#family_creation_table', response, columnMapping);
        setdtable('#family_creation_table');
        $('#family_form input').val('');
        $('#family_form input').css('border', '1px solid #cecece');
        $('#family_form select').css('border', '1px solid #cecece');
        $('#fam_relationship').val('');
        $('#fam_live').val('');
        getGuarantorFamily()
    }, 'json')
}

function getFamilyDelete(id) {
    let cus_id = $('#cus_id').val()
    let customer_id = $('#customer_id').val();
    $.post('api/customer_creation_files/delete_family_creation.php', { id, cus_id, customer_id }, function (response) {
        if (response == '0') {
            swalError('Warning', 'Have to maintain atleast one Family Info');
        } else if (response == '1') {
            swalSuccess('Success', 'Family Info Deleted Successfully!');
            getFamilyTable();
        } else if (response == '2') {
            swalError('Access Denied', 'Family Member Already Used');
        } else {
            swalError('Warning', 'Error occur While Delete Family Info.');
        }
    }, 'json');
}


function getGuarantorFamily() {
    let cus_id = $('#cus_id').val();
    $.post('api/customer_creation_files/get_guarantor_relationship.php', { cus_id }, function (response) {
        let appendGuarantorOption = '';
        appendGuarantorOption += "<option value=''>Select Familiy Member</option>";
        $.each(response, function (index, val) {
            let selected = '';
            let editGId = $('#gua_name_edit').val();
            if (val.id == editGId) {
                selected = 'selected';
            }
            appendGuarantorOption += "<option value='" + val.id + "' " + selected + ">" + val.fam_name + "</option>";
        });
        $('#gua_family').empty().append(appendGuarantorOption);
    }, 'json');
}
function getExistingRefCustomer() {
    $.post('api/customer_creation_files/get_existing_customer.php', function (response) {
        let appendCusOption = '';
        appendCusOption += "<option value=''>Select Existing Customer</option>";
        $.each(response, function (index, val) {
            let selected = '';
            let editGId = $('#custom_name_edit').val();
            if (val.id == editGId) {
                selected = 'selected';
            }
            appendCusOption += "<option value='" + val.id + "' " + selected + ">" + val.full_name + "</option>";
        });

        $('#cus_name').empty().append(appendCusOption);
    }, 'json');
}


function getExistingCustomer() {
    $.post('api/customer_creation_files/get_existing_customer.php', function (response) {
        let appendCustomerOption = '';
        appendCustomerOption += "<option value=''>Select Existing Customer</option>";
        $.each(response, function (index, val) {
            let selected = '';
            let editGId = $('#customer_name_edit').val();
            if (val.id == editGId) {
                selected = 'selected';
            }
            appendCustomerOption  += "<option value='" + val.id + "' " + selected + ">" + val.full_name + "</option>";
        });

        $('#existing_cus').empty().append(appendCustomerOption);
    }, 'json');
}
function isFormDataValid(formData) {
    // Reset border styles for all fields
    $('#gua_name').css('border', '1px solid #cecece');
    $('#guarantor1_name').css('border', '1px solid #cecece');
    $('#existing_cus').css('border', '1px solid #cecece');
    $('#details').css('border', '1px solid #cecece');
    if (formData.get('gua_name') == '' || formData.get('gua_name') == undefined || formData.get('gua_name') == null) { //if Empty
        if (!validateField(formData['gua_name'], 'gua_name')) {
            return false;
        }
    }
    else if (formData.get('gua_name') == '2') { // Existing Customer
        if (!validateField(formData.get('existing_cus'), 'existing_cus')) {
            return false;
        }
    } else if (formData.get('gua_name') == '3') { // Others
        if (!validateField(formData.get('other_name'), 'other_name') || !validateField(formData.get('details'), 'details')) {
            return false;
        }
    } else { // Default case
        if (!validateField(formData.get('gua_family'), 'gua_family') ||!validateField(formData.get('guarantor1_name'), 'guarantor1_name')) {
            return false;
        }
    }

    return true;
}

function getAutoGenCusId(id) {
    $.post('api/customer_creation_files/get_autoGen_cus_id.php', { id }, function (response) {
        $('#cus_id').val(response);
    }, 'json');
}
function getSourceTable() {
    let cus_id = $('#cus_id').val()
    $.post('api/customer_creation_files/source_list.php', { cus_id }, function (response) {
        let sourceColumn = [
            "sno",
            "occupation",
            "occ_detail",
            "occ_place",
            "source",
            "income",
            "action"
        ]
        appendDataToTable('#source_create', response, sourceColumn);
        setdtable('#source_create');
    }, 'json');
}


function getGrelationshipName(guarantorId) {
    $.ajax({
        url: 'api/customer_creation_files/getGrelationship.php',
        type: 'POST',
        data: { guarantor_id: guarantorId },
        dataType: 'json',
        cache: false,
        success: function (response) {
            $('#guarantor1_name').val(response.guarantor_name);
        },
        error: function (xhr, status, error) {
            console.error('AJAX error: ' + status, error);
            // Optionally handle errors here, such as displaying an error message to the user
        }
    });
}
function getCustomerName(cusId) {
    $.ajax({
        url: 'api/customer_creation_files/getCustomerId.php',
        type: 'POST',
        data: { cus_id: cusId },
        dataType: 'json',
        cache: false,
        success: function (response) {
            $('#ref_cus_id').val(response.ref_cus_id);
        },
        error: function (xhr, status, error) {
            console.error('AJAX error: ' + status, error);
            // Optionally handle errors here, such as displaying an error message to the user
        }
    });
}
$('#gua_name').on('change', function () {
    let gua_name= $(this).val();
    updateFieldsVisibility(gua_name);
    console.log(gua_name);
});
function updateFieldsVisibility(gua_name) {
    // Hide all containers initially
    let containers = ['#fam_name_container', '#name1_container', '#existing_cus_container', '#details_container', '#name2_container'];
    $(containers.join(',')).hide();  // This hides all containers
    // Show containers based on the selected value of gua_name
    if (gua_name === '2') {
        $('#existing_cus_container').show();
        getExistingCustomer();  // Call the function for existing customer
    } else if (gua_name === '3') {
        $('#name2_container').show();
        $('#details_container').show();
    } else if (gua_name === '1') {
        $('#name1_container').show();
        $('#fam_name_container').show();
        getGuarantorFamily(); 
    }
}

function getGuarantorTable() {
    let cus_id = $('#cus_id').val()
    $.post('api/customer_creation_files/get_guarantor_list.php', { cus_id }, function (response) {
        let GList = [
            "sno",
            "guarantor_name",
            "guarantor_relationship",
            "action"
        ]
        appendDataToTable('#guarantor_creation_table', response, GList);
        setdtable('#guarantor_creation_table');
        $('#guarantor_form input').val('');
        $('#guarantor_form input').css('border', '1px solid #cecece');
        $('#guarantor_form select').css('border', '1px solid #cecece');
        $('#gua_name').val('');
        $('#gua_family').val('');
        $('#existing_customer').val('');
        $('#guarantor1_name').val('');
        $('#details').val('');
        $('#name1_container').hide();
        $('#fam_name_container').hide();
        $('#existing_cus_container').hide();
        $('#details_container').hide()
        $('#name2_container').hide()
        $('#gur_imgshow').attr('src', 'img/avatar.png');
    }, 'json');
}
function getGuarantorInfoTable() {
    let cus_id = $('#cus_id').val()
    $.post('api/customer_creation_files/get_guarantor_list.php', { cus_id }, function (response) {
        let GList = [
            "sno",
            "guarantor_name",
            "guarantor_relationship",
        ]
        appendDataToTable('#guar_info_table', response, GList);
        setdtable('#guar_info_table');
    }, 'json');
}
function getGuaDelete(id) {
    let cus_id = $('#cus_id').val().trim();
    let customer_id = $('#customer_id').val();

    $.post('api/customer_creation_files/delete_guarantor_creation.php', { id, cus_id, customer_id }, function (response) {
        if (response === '0') {
            swalError('Warning', 'You must maintain at least one Guarantor Info.');
        } else if (response === '1') {
            swalSuccess('Success', 'Guarantor Info Deleted Successfully!');
            getGuarantorTable(); // Refresh table after deletion
        } else {
            swalError('Error', 'Failed to Delete Guarantor.');
        }
    }, 'json').fail(function (jqXHR, textStatus, errorThrown) {
        swalError('Error', 'An unexpected error occurred: ' + errorThrown);
    });
}
function totalIncome() {
    let cus_id = $('#cus_id').val();
    $.post('api/customer_creation_files/total_income.php', { cus_id: cus_id }, function (response) {
        // Check if the response is valid and has the total_income property
        let formattedIncome = moneyFormatIndia(response.total_income);
        $('#tot_income').val(formattedIncome);

    }, 'json');
}
function getCustomerEntryTable() {
    serverSideTable('#customer_create', '', 'api/customer_creation_files/customer_creation_list.php');
    // setDropdownScripts();   
}

function editCustomerCreation(id) {
    $.post('api/customer_creation_files/customer_creation_data.php', { id: id }, function (response) {
        $('#customer_id').val(id);
        $('#reference_type').val(response[0].reference_type);
        $('#name').val(response[0].name);
        $('#custom_name_edit').val(response[0].cus_name);
        $('ref_cus_id').val(response[0].ref_cus_id);
        $('#mobile').val(response[0].mobile);
        $('#declaration').val(response[0].declaration);
        $('#cus_id').val(response[0].cus_id);
        $('#aadhar_number').val(response[0].aadhar_number);
        $('#first_name').val(response[0].first_name);
        $('#last_name').val(response[0].last_name);
        $('#dob').val(response[0].dob);
        $('#age').val(response[0].age);
        $('#mobile1').val(response[0].mobile1);
        $('#mobile2').val(response[0].mobile2);
        $('#whatsapp').val(response[0].whatsapp);
        $('#address').val(response[0].address);
        $('#native_address').val(response[0].native_address);
        $('#tot_income').val(moneyFormatIndia(response[0].tot_income));
        $('#chit_limit').val(moneyFormatIndia(response[0].chit_limit));
        $('#reference').val(response[0].reference);
        if (response[0].whatsapp === response[0].mobile1) {
            $('#mobile1_radio').prop('checked', true);
            $('#selected_mobile_radio').val('mobile1');
        } else if (response[0].whatsapp === response[0].mobile2) {
            $('#mobile2_radio').prop('checked', true);
            $('#selected_mobile_radio').val('mobile2');
        }
        // getExistingRefCustomer()
        setTimeout(() => {
            getAutoGenCusId(id)
            getPlaceDropdown(response[0].place);
            $('#cus_name').trigger('change');
        }, 1000);
            getFamilyInfoTable()
            getGuarantorInfoTable()
            getSourceTable()
        if (response[0].reference_type == '1') {
            $('#declaration_container').show();
            $('#cus_name_container').hide();
            $('#cus_id_container').hide();
            $('#mobile_container').hide();
        } else if (response[0].reference_type == '2') {
            $('#cus_name_container').show();
            $('#cus_id_container').show();
            $('#declaration_container').show();
            $('#mobile_container').hide();
            getExistingRefCustomer()
        }
        else {
            $('#name_container').show();
            $('#mobile_container').show();
            $('#declaration_container').show();
        }
        let path = "uploads/customer_creation/cus_pic/";
        $('#per_pic').val(response[0].pic);
        var img = $('#imgshow');
        img.attr('src', path + response[0].pic);
    }, 'json');

}
function getCustomerDelete(id) {
    $.post('api/customer_creation_files/delete_customer_creation.php', { id }, function (response) {
        if (response === 0) {
            swalError('Access Denied', 'Used in Group Creation Screen');
        } else if (response === 1) {
            swalSuccess('Success', 'Customer Creation Deleted Successfully');
            getCustomerEntryTable();
        } else {
            swalError('Error', 'Failed to delete customer: ' + response);
        }
    }, 'json');
}
$('button[type="reset"],#back_btn').click(function (event) {
    // event.preventDefault();
    $('input').each(function () {
        var id = $(this).attr('id');
        if (id !== 'cus_id' && id !== 'add_src') {
            $(this).val('');
        }
    });
    // Clear all textarea fields within the specific form
    $('#customer_creation').find('textarea').val('');

    //clear all upload inputs within the form.
    $('#customer_creation').find('input[type="file"]').val('');

    // Reset all select fields within the specific form
    $('#customer_creation').find('select').each(function () {
        $(this).val($(this).find('option:first').val());

    });
    $('#customer_creation').find('input[type="radio"]').prop('checked', false);
    //Reset all  images within the form
    $('#imgshow').attr('src', 'img/avatar.png');
    $('#customer_creation input').css('border', '1px solid #cecece');
    $('#customer_creation select').css('border', '1px solid #cecece');
    $('#customer_creation textarea').css('border', '1px solid #cecece');
    $('#cus_name_container').hide();
    $('#name_container').hide();
    $('#mobile_container').hide();
    $('#declaration_container').hide();
    $('#cus_id_container').hide();

});

