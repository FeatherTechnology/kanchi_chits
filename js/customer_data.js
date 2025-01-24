$(document).ready(function () {

    $(document).on('click', '#back_btn', function () {
        swapTableAndCreation();
    });

    $('input[name=customer_data_type]').click(function () {
        let customerType = $(this).val();
        if (customerType == 'cus_profile') {
            $('#customer_creation').show(); $('#customer_summary').hide(); $('#curr_closed').hide(); $('#doc_curr_closed').hide(); $('#customer_document').hide();

        } else if (customerType == 'cus_summary') {
            $('#customer_creation').hide(); $('#curr_closed').show(); $('#customer_summary').show(); $('#doc_curr_closed').hide(); $('#customer_document').hide();

        }
        else if (customerType == 'cus_doc') {
            $('#customer_creation').hide(); $('#curr_closed').hide(); $('#customer_summary').hide(); $('#customer_document').show(); $('#doc_curr_closed').show();

        }
    })
    $('input[name=group_type]').click(function () {
        let groupType = $(this).val();
        if (groupType == 'cus_current') {
            $('#customer_summary').show();
            $('.group_close').hide();
            $('.group_current').show();
        } else if (groupType == 'cus_closed') {
            $('.group_close').show();
            $('.group_current').hide();
        }
    });
    $('input[name=docc_type]').click(function () {
        let doccType = $(this).val();
        if (doccType == 'doc_clo') {
            $('#customer_document').show();
            $('.doc_current').show();
            $('.group_close').hide();
            $('.group_current').hide();
            // let id = $('#customer_id').val();
            // viewCustomerGroups(id);
        } else if (doccType == 'doc_cur') {
            $('.doc_current').show();
            $('.group_close').hide();
            $('.group_current').hide();
        }
    });
    $(document).on('click', '#document_current', function () {
        $('#customer_summary').show()
        $('.doc_current').show();
        let id = $('#customer_id').val();
        viewCustomerDoc(id);
        $('#sub_noc').hide();
        $('#member_card').hide();
    })
    $(document).on('click', '#document_closed', function () {
        $('.doc_current').show();
        let id = $('#customer_id').val();
        viewCustomerClosedDoc(id);
        $('#sub_noc').show();
        $('#member_card').show();

    })
    $(document).on('click', '#customer_profile', function () {

        $('#customer_creation').show(); $('#customer_summary').hide(); $('#curr_closed').hide();
        $('#customer_document').hide(); $('#doc_curr_closed').hide();
    })
    $(document).on('click', '#customer_sum', function () {
        $('#customer_creation').hide(); $('#customer_summary').show(); $('#curr_closed').show();
        $('#customer_summary').show()
        $('.group_current').show()
        $('.group_close').hide()
        let id = $('#customer_id').val();
        viewCustomerGroups(id);
        $('#customer_document').hide(); $('#doc_curr_closed').hide();
    })
    $(document).on('click', '#customer_doc', function () {
        $('#customer_creation').hide(); $('#curr_closed').hide(); $('#customer_summary').hide(); $('#customer_document').show(); $('#doc_curr_closed').show();
        $('.group_close').hide();
        $('.group_current').hide();
        let id = $('#customer_id').val();
        viewCustomerDoc(id);
    })
    $(document).on('click', '.customerActionBtn', function () {
        let id = $(this).attr('value');
        $('#customer_id').val(id);
        $('#customer_creation').show();
        $('#customer_summary').hide();
        $('#customer_document').hide();
        $('#curr_closed').hide();
        $('#doc_curr_closed').hide();
        swapTableAndCreation();
        editCustomerCreation(id)
         //viewCustomerGroups(id);
        $('#cus_id').val('');
        $('#imgshow').attr('src', 'img/avatar.png');
        $('.toRefresh').hide();
        $('.knowData').val('');
        $('#custom_name_edit').val('')

    });
    $(document).on('click', '#back_to_list', function () {
        $('#noc_summary').hide();
        $('#doc_curr_closed').show();
        $('#customer_document').show();
        $('#back_btn').show();
        $('#back_to_list').hide();
        $('#main_radio').show();
        $('#noc_member').css('border', '1px solid #cecece');
        $('#noc_relation').css('border', '1px solid #cecece');
    });
    $(document).on('click', '.documentActionBtn', function (event) {
        event.preventDefault();  // Prevent default action
        let value = $(this).attr('value');  // Get the value from the button
        let parts = value.split('_');  // Split by underscore to get cus_id and grp_id
        let cus_id = parts[0];  // Get cus_id
        let grp_id = parts[1];  // Get grp_id
        $('#submit_noc').attr('cus_id', cus_id);
        $('#submit_noc').attr('grp_id', grp_id);
        // Call the document list function with cus_id and grp_id
        documentList(cus_id, grp_id);
        getGuarantorRelationship(cus_id);
        setTimeout(() => {
            setSubmittedDisabled();
        }, 1000);
        // Show/Hide elements as per requirement
        $('#noc_summary').show();
        $('#doc_curr_closed').hide();
        $('#customer_document').hide();
        $('#back_to_list').show();
        $('#customer_creation').hide();
        $('#curr_closed').hide();
        $('#customer_summary').hide();
        $('#back_btn').hide();
        $('#main_radio').hide();

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
        const guarantorId = $(this).val();

        if (guarantorId) {
            getGrelationshipName(guarantorId);
        } else {
            $('#guarantor1_name').val('');
        }
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
        var data = ['occupation', 'occ_detail', 'occ_place', 'source', 'income']

        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });

        if (isValid) {
            $.post('api/customer_creation_files/submit_source_info.php', { cus_id, occupation, occ_detail, occ_place, source, income }, function (response) {
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

        var data = ['fam_name', 'fam_relationship', 'fam_mobile']

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
                    setTimeout(() => {
                        $('#gua_name_edit').val(family_id);
                        $('#gua_family').val(family_id).trigger('change');
                    }, 1000);
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
    ///////////////////////////////////////////////////////////////////Document info START ////////////////////////////////////////////////////////////////////////////
    $('#doc_holder_name').on('change', function () {
        const guarantorId = $(this).val();
        if (guarantorId && guarantorId !== 'null') {
            // Fetch the guarantor relationship if a valid ID is selected
            getDocrelationshipName(guarantorId);
        } else {
            // Set default relationship as 'Customer' if no valid ID is selected
            $('#doc_relationship').val('Customer');
        }
    });
    $('#group_name').on('change', function () {
        const guarantorId = $(this).val();
        if (guarantorId && guarantorId !== 'null') {
            // Fetch the guarantor relationship if a valid ID is selected
            getDocGroupName(guarantorId);

        } else {
            // Set default relationship as 'Customer' if no valid ID is selected
            $('#grp_id').val('Customer');
        }
    });
    $('#submit_doc_info').click(function (event) {
        event.preventDefault();
        let doc_name = $('#doc_name').val();
        let doc_type = $('#doc_type').val();
        let doc_holder_name = $('#doc_holder_name').val();
        let grp_id = $('#grp_id').val();
        let group_name = $('#group_name').val();
        let group_month = $('#group_month').val();
        let doc_relationship = $('#doc_relationship').val();
        let remarks = $('#remarks').val();
        let doc_upload = $('#doc_upload')[0].files[0];
        let doc_upload_edit = $('#doc_upload_edit').val();
        let doc_info_id = $('#doc_info_id').val();
        let cus_id = $('#cus_id').val();
        var data = ['doc_name', 'doc_type', 'doc_holder_name', 'doc_relationship', 'group_month', 'group_name']
        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });
        if (doc_upload === undefined && doc_upload_edit === '') {
            let isUploadValid = validateField('', 'doc_upload');
            let isHiddenValid = validateField('', 'doc_upload_edit');
            if (!isUploadValid || !isHiddenValid) {
                isValid = false;
            }
            else {
                $('#doc_upload').css('border', '1px solid #cecece');
                $('#doc_upload_edit').css('border', '1px solid #cecece');
            }
        }
        else {
            $('#doc_upload').css('border', '1px solid #cecece');
            $('#doc_upload_edit').css('border', '1px solid #cecece');
        }
        if (isValid) {
            let docInfo = new FormData();
            docInfo.append('doc_name', doc_name);
            docInfo.append('doc_type', doc_type);
            docInfo.append('doc_holder_name', doc_holder_name);
            docInfo.append('grp_id', grp_id);
            docInfo.append('group_month', group_month);
            docInfo.append('group_name', group_name);
            docInfo.append('doc_relationship', doc_relationship);
            docInfo.append('remarks', remarks);
            docInfo.append('doc_upload', doc_upload);
            docInfo.append('doc_upload_edit', doc_upload_edit);
            docInfo.append('cus_id', cus_id);
            docInfo.append('id', doc_info_id);

            $.ajax({
                url: 'api/customer_data_files/submit_updated_info.php',
                type: 'post',
                data: docInfo,
                contentType: false,
                processData: false,
                cache: false,
                success: function (response) {
                    if (response == '1') {
                        swalSuccess('Success', 'Document Info Updated Successfully')
                    } else if (response == '2') {
                        swalSuccess('Success', 'Document Info Added Successfully')
                    } else {
                        swalError('Alert', 'Failed')
                    }
                    getDocCreationTable();
                  //  $('#clear_doc_form').trigger('click');
                    $('#doc_info_id').val('');
                    $('#doc_info_form select').each(function () {
                        $(this).val($(this).find('option:first').val());
                
                    });
                }
            });
        }
    });

    $(document).on('click', '.docActionBtn', function () {
        let id = $(this).attr('value');
        
        $.post('api/settlement_files/doc_info_data.php', { id }, function (response) {
            if (response[0].status == 4 || response[0].status == 5) {
                // Show a SweetAlert warning and prevent editing
                swalError('Access Denied', 'Group is closed.');
                return; // Stop further execution
            }
            
            // Populate fields if status is not 4
            $('#doc_name').val(response[0].doc_name);
            $('#doc_type').val(response[0].doc_type);
            $('#doc_holder_name').val(response[0].holder_name);
            $('#grp_id').val(response[0].group_id);
            $('#group_name').val(response[0].grp_name);
    
            $('#group_month_edit').val(response[0].auction_id);
            
            getGroupMonth(); // Load related data for group month
    
            $('#doc_relationship').val(response[0].relationship);
            $('#remarks').val(response[0].remarks);
            $('#doc_upload_edit').val(response[0].upload);
            $('#doc_info_id').val(response[0].id);
            
        }, 'json');
    });
   $(document).on('click', '.docDeleteBtn', function () {
    let id = $(this).attr('value');
    
    // Check the group status first before allowing delete
    $.post('api/settlement_files/doc_info_data.php', { id }, function (response) {
        if (response[0].status == 4 || response[0].status == 5) {
            // If the group status is 4, show a warning and prevent deletion
            swalError('Access Denied', 'Group is closed.');
        } else {
            // If the group status is not 4, proceed with delete confirmation
            swalConfirm('Delete', 'Are you sure you want to delete this document?', deleteDocInfo, id);
        }
    }, 'json');
});

    $('#clear_doc_form').click(function (event) {
        event.preventDefault()
        $('#doc_info_id').val('');
        $('#doc_upload_edit').val('');
        $('#doc_info_form select').each(function () {
            $(this).val($(this).find('option:first').val());
        });
        $('#doc_info_form textarea').val('');
        $('#doc_info_form input').val('');
        $('#doc_info_form input').css('border', '1px solid #cecece');
        $('#doc_info_form select').css('border', '1px solid #cecece');
    })
    ///////////////////////////////////////////////////////////////////Document info END ////////////////////////////////////////////////////////////////////////////
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

        var data = ['cus_id', 'first_name', 'last_name', 'aadhar_number', 'mobile1', 'place', 'address', 'chit_limit', 'reference']

        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });
        if (pic === undefined && per_pic === '') {
            let isUploadValid = validateField('', 'pic');
            let isHiddenValid = validateField('', 'per_pic');
            if (!isUploadValid || !isHiddenValid) {
                isValid = false;
            }
            else {
                $('#pic').css('border', '1px solid #cecece');
                $('#per_pic').css('border', '1px solid #cecece');
            }
        }
        else {
            $('#pic').css('border', '1px solid #cecece');
            $('#per_pic').css('border', '1px solid #cecece');
        }
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
    $(document).on('click', '#customer_current', function () {
        $('#customer_summary').show()
        $('.group_close').hide()
        $('.group_current').show()
        let id = $('#customer_id').val();
        viewCustomerGroups(id);
    })
    $(document).on('click', '#customer_closed', function () {
        $('.group_close').show();
        $('.group_current').hide()
        let id = $('#customer_id').val();
        viewCustomerClosedGroups(id);

    })
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
        })
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
    //////////////////////////////////////////NoC Start///////////////////////////////////////////////
    setCurrentDate('#date_of_noc');
    $('#noc_member').on('change', function () {
        const guarantorId = $(this).val();
        if (guarantorId && guarantorId !== 'null') {
            // Fetch the guarantor relationship if a valid ID is selected
            getnocrelationshipName(guarantorId);
        } else {
            // Set default relationship as 'Customer' if no valid ID is selected
            $('#noc_relation').val('Customer');
        }
        setTimeout(() => {
            setValuesInTables();
        }, 1000);
    });
    $(document).on('click', '.noc_doc_info_chkbx', function () {
        setValuesInTables();
        removeValuesInTables();
    });

    $('#submit_noc').click(function (event) {
        event.preventDefault();

        let cus_id = $(this).attr('cus_id');  // Retrieve cus_id from button attribute
        let grp_id = $(this).attr('grp_id');  // Retrieve grp_id from button attribute

        let docId = [];
        $('.noc_doc_info_chkbx').each(function () {
            if ($(this).is(':checked') && $(this).attr('data-id') == '0') {
                docId.push($(this).val());
            }
        });

        if (docId.length === 0) {
            swalError('Warning', 'Kindly check at least one checkbox');
            $('#noc_member').val('');
            $('#noc_relation').val('');
            return;
        }

        let doc_list_cnt = $('#noc_document_list_table').DataTable().rows().count();
        let date_of_noc = $('#date_of_noc').val();
        let noc_member = $('#noc_member').val();
        let noc_relation = $('#noc_relation').val();

        // Validation
        var data = ['date_of_noc', 'noc_member', 'noc_relation'];
        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });

        if (isValid) {
            let nocData = {
                'docId': docId,
                'date_of_noc': date_of_noc,
                'noc_member': noc_member,
                'noc_relation': noc_relation,
                'cus_id': cus_id,  // Pass cus_id here
                'grp_id': grp_id,  // Pass grp_id here
                'doc_list_cnt': doc_list_cnt
            };

            $.post('api/customer_data_files/submit_noc.php', nocData, function (response) {
                if (response == '1') {
                    swalSuccess('Success', 'NOC submitted successfully.');
                    documentList(cus_id, grp_id);  // Use cus_id and grp_id after submission
                    $('#noc_relation').val('');
                    $('#noc_member').val('');
                    setTimeout(() => {
                        setSubmittedDisabled();
                    }, 1000);
                } else {
                    swalError('Error', 'NOC submission failed.');
                }
            }, 'json');
        }
    });


    /////////////////////////////////////////NoC END//////////////////////////////////////////////
    //////////////////////////////////////////////Document End/////////////////////////////////////////////////
});
$(function () {
    getCustomerEntryTable();
});
function swapTableAndCreation() {
    if ($('.customer_table_content').is(':visible')) {
        $('.customer_table_content').hide();
        $('#customer_data_content').show();
        $('#back_btn').show();
        // $('#customer_summary').hide();
        // $('#curr_closed').hide();
    } else {
        $('.customer_table_content').show();
        $('#back_btn').hide();
        $('#customer_data_content').hide();
        $('#customer_profile').trigger('click');
        $('#customer_current').trigger('click');
        $('#document_current').trigger('click');
    }
}

function getCustomerEntryTable() {
    serverSideTable('#customer_create', '', 'api/customer_data_files/customer_profile_list.php');
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
            appendCustomerOption += "<option value='" + val.id + "' " + selected + ">" + val.full_name + "</option>";
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
        if (!validateField(formData.get('gua_family'), 'gua_family') || !validateField(formData.get('guarantor1_name'), 'guarantor1_name')) {
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
    let gua_name = $(this).val();
    updateFieldsVisibility(gua_name);
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
        let formattedIncome = moneyFormatIndia(response.total_income);
        $('#tot_income').val(formattedIncome);

    }, 'json');
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
        getDocInfoTable();
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
function viewCustomerGroups(id) {
    $.post('api/customer_data_files/group_current_data.php', { id: id }, function (response) {
        // Iterate through the response to round off chit_amount

        let cashList = [
            "sno",
            "grp_id",
            "grp_name",
            "chit_value",
            "grp_status",
            "collection_status",
            "grace_period",
            "status",
            "settle_status",
            "charts",

        ];
        appendDataToTable('#group_list_table', response, cashList);
        setdtable('#group_list_table');
        setDropdownScripts();
    }, 'json');
}
function viewCustomerClosedGroups(id) {
    $.post('api/customer_data_files/group_closed_data.php', { id: id }, function (response) {
        // Iterate through the response to round off chit_amount

        let cashList = [
            "sno",
            "grp_id",
            "grp_name",
            "chit_value",
            "grp_status",
            "collection_status",
            "status",
            "settle_status",
            "charts",

        ];
        appendDataToTable('#group_close_table', response, cashList);
        setdtable('#group_close_table');
        setDropdownScripts();
    }, 'json');
}
function viewCustomerDoc(id) {
    $.post('api/customer_data_files/get_current_document.php', { id: id }, function (response) {
        // Iterate through the response to round off chit_amount
        let cashList = [
            "sno",
            "grp_id",
            "grp_name",
            "chit_value",
            "document_count",
            "action",

        ];
        appendDataToTable('#doc_cur_table', response, cashList);
        setdtable('#doc_cur_table');
    }, 'json');
}
function viewCustomerClosedDoc(id) {
    $.post('api/customer_data_files/get_closed_document.php', { id: id }, function (response) {
        // Iterate through the response to round off chit_amount
        let cashList = [
            "sno",
            "grp_id",
            "grp_name",
            "chit_value",
            "document_count",
            "action",

        ];
        appendDataToTable('#doc_cur_table', response, cashList);
        setdtable('#doc_cur_table');
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
    return new Promise(function(resolve, reject){
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
                tbody.empty(); // Clear existing rows
    
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
                // Resolve the Promise when AJAX completes successfully
                resolve(response);
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error: ' + status + error);
                // Reject the Promise if there is an error
                reject(new Error('Failed to fetch data: ' + error));
            }
        });
    });
}
function closeChartsModal() {
    $('#due_chart_model').modal('hide');
    $('#commitment_chart_model').modal('hide');
}
function documentList(cus_id, grp_id) {
    $.post('api/customer_data_files/noc_document_info_list.php', { cus_id, grp_id }, function (response) {
        let nocDocInfoColumns = [
            'sno',
            'doc_name',
            'doc_type',
            'auction_month',
            'guarantor_name',
            'upload',
            'date_of_noc',
            'noc_member_name',
            'noc_relationship',
            'action'
        ];
        appendDataToTable('#noc_document_list_table', response, nocDocInfoColumns);
        setdtable('#noc_document_list_table');
    }, 'json');
}
function getGuarantorRelationship(cus_id) {
    $.post('api/customer_data_files/noc_guarantor_name.php', { cus_id: cus_id }, function (response) {
        let appendGurantorOption = "<option value=''>Select Name</option>";
        $.each(response, function (index, val) {
            let selected = '';
            let editGId = $('#gua_name_edit').val(); // Existing guarantor ID (if any)
            if (val.type === 'Guarantor' && val.id == editGId) {
                selected = 'selected';
            }

            // Display type of the person (Guarantor or Customer)
            appendGurantorOption += "<option value='" + val.id + "' " + selected + ">" + val.name + "</option>";
        });

        $('#noc_member').empty().append(appendGurantorOption);
        // Clear the relationship field
        $('#noc_relation').val('');
    }, 'json');
}
function getnocrelationshipName(guarantorId) {
    $.ajax({
        url: 'api/settlement_files/gua_name.php',
        type: 'POST',
        data: { id: guarantorId },
        dataType: 'json',
        cache: false,
        success: function (response) {
            $('#noc_relation').val(response.guarantor_relationship || 'Customer');
        },
        error: function (xhr, status, error) {
            console.error('Error fetching guarantor relationship:', error);
            $('#noc_relation').val('Customer');
        }
    });
}
function setSubmittedDisabled() {
    $('.noc_doc_info_chkbx').each(function () {
        if ($(this).attr('data-id') == '1') {
            $(this).closest('tr').addClass('disabled-row');
            $(this).attr('checked', true).attr('disabled', true);
        }
    });
    var doc_checkDisabled = $('.noc_doc_info_chkbx:disabled').length === $('.noc_doc_info_chkbx').length;
    if (doc_checkDisabled) {
        $('#submit_noc').hide();
    } else {
        $('#submit_noc').show();
    }

}
function setValuesInTables() {
    let member = $('#noc_member').val();
    let date = $('#date_of_noc').val();
    let formattedDate = (member != '') ? formatDate(date) : '';
    let name = (member != '') ? $('#noc_member').find(":selected").text() : '';
    let relationship = (member != '') ? $('#noc_relation').val() : '';
    let checked = false;
    $('.noc_doc_info_chkbx').each(function () {
        if ($(this).is(':checked') && $(this).attr('data-id') == '0') {
            checked = true;
            $(this).closest('tr').find('td:nth-child(9)').text(relationship);
            $(this).closest('tr').find('td:nth-child(8)').text(name);
            $(this).closest('tr').find('td:nth-child(7)').text(formattedDate);
        }
    });
}
function removeValuesInTables() {
    $('.noc_doc_info_chkbx').each(function () {
        if (!$(this).is(':checked')) {
            $(this).closest('tr').find('td:nth-child(9)').text('');
            $(this).closest('tr').find('td:nth-child(8)').text('');
            $(this).closest('tr').find('td:nth-child(7)').text('');
        }
    });

}

function formatDate(inputDate) {
    // Split the input date into year, month, and day components
    let parts = inputDate.split('-');
    // Rearrange them in dd-mm-yyyy format
    return parts[2] + '-' + parts[1] + '-' + parts[0];
}
function getDocrelationshipName(guarantorId) {
    $.ajax({
        url: 'api/settlement_files/gua_name.php',
        type: 'POST',
        data: { id: guarantorId },
        dataType: 'json',
        cache: false,
        success: function (response) {
            $('#doc_relationship').val(response.guarantor_relationship || 'Customer');
        },
        error: function (xhr, status, error) {
            console.error('Error fetching guarantor relationship:', error);
            $('#doc_relationship').val('Customer');
        }
    });
}
function getDocGroupName(guarantorId) {
    $.ajax({
        url: 'api/customer_data_files/getGroup_name.php',
        type: 'POST',
        data: { id: guarantorId },
        dataType: 'json',
        cache: false,
        success: function (response) {
            $('#grp_id').val(response.grp_id);
            getGroupMonth();
        },
    });
}

function getDocGuarantor() {
    let cus_id = $('#cus_id').val();
    $.post('api/settlement_files/get_document_guarantor.php', { cus_id: cus_id }, function (response) {
        let appendGuarantorOption = "<option value=''>Select Name</option>";
        $.each(response, function (index, val) {
            let selected = '';
            appendGuarantorOption += "<option value='" + val.id + "' " + selected + ">" + val.name + "</option>";
        });

        $('#doc_holder_name').empty().append(appendGuarantorOption);
        $('#doc_relationship').val('');
    }, 'json');
}
function getGroupName() {
    let cus_id = $('#cus_id').val();
    $.post('api/customer_data_files/get_document_group.php', { cus_id: cus_id }, function (response) {
        let appendGroupOption = "<option value=''>Select Group Name</option>";
        $.each(response, function (index, val) {
            let selected = '';
            appendGroupOption += "<option value='" + val.grp_name + "' " + selected + ">" + val.grp_name + "</option>";
        });

        $('#group_name').empty().append(appendGroupOption);
    }, 'json');
}

function getGroupMonth() {
    let cus_id = $('#cus_id').val();
    let grp_id = $('#grp_id').val();
    $.post('api/customer_data_files/get_document_month.php', { cus_id, grp_id }, function (response) {
        let appendGroupOption = "<option value=''>Select Auction Month</option>";
        let editId = $('#group_month_edit').val(); // Fetch the current value of group_month_edit for comparison

        // Iterate over the response to append options
        $.each(response, function (index, val) {
            let selected = '';

            // Compare the current option id with the editId and select it if they match
            if (val.id == editId) {
                selected = 'selected';
            }

            appendGroupOption += "<option value='" + val.id + "' " + selected + ">" + val.auction_month + "</option>";
        });

        // Populate the group_month dropdown
        $('#group_month').empty().append(appendGroupOption);
    }, 'json');
}

function deleteDocInfo(id) {
    $.post('api/settlement_files/delete_doc_info.php', { id }, function (response) {
        if (response == '1') {
            swalSuccess('success', 'Doc Info Deleted Successfully');
            getDocCreationTable();
        } else if (response == '2') {
            swalError('Access Denied', 'Used in NOC Summary');
        } else {
            swalError('Alert', 'Delete Failed')
        }
    }, 'json');
}

// function refreshDocModal() {
//     $('#clear_doc_form').trigger('click');
// }
function getDocCreationTable() {
    let cus_id = $('#cus_id').val();
    $.post('api/customer_data_files/document_info_list.php', { cus_id }, function (response) {
        let docInfoColumn = [
            "sno",
            "grp_name",
            "group_id",
            "auction_month",
            "doc_name",
            "doc_type",
            "guarantor_name",
            "relationship",
            "remarks",
            "upload",
            "action"
        ]
        appendDataToTable('#doc_creation_table', response, docInfoColumn);
        setdtable('#doc_creation_table')
        $('#doc_info_form input').val('');
        $('#doc_info_form textarea').val('');
        $('#doc_info_form input').css('border', '1px solid #cecece');
        $('#doc_info_form select').css('border', '1px solid #cecece');
    }, 'json');
}
function getDocInfoTable() {
    let cus_id = $('#cus_id').val();
    $.post('api/customer_data_files/document_info_list.php', { cus_id }, function (response) {
        let docColumn = [
            "sno",
            "grp_name",
            "group_id",
            "auction_month",
            "doc_name",
            "doc_type",
            "guarantor_name",
            "relationship",
            "remarks",
            "upload"
        ]
        appendDataToTable('#document_info', response, docColumn);
        setdtable('#document_info')
    }, 'json');
}