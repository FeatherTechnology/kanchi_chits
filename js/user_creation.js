//Branch Name Multi select initialization
const branch_name = new Choices('#branch_name', {
    removeItemButton: true,
    noChoicesText: 'Select Branch Name',
    allowHTML: true
});

$(document).ready(function () {

    $('.add_user_btn, .back_to_userList_btn').click(function () {
        swapTableAndCreation();
    });

    /////////////////////////////////////////////////////////// Role Modal START ///////////////////////////////////////////////////////////////////////
    $('#submit_role').click(function (event) {
        event.preventDefault();
        let role = $('#add_role').val(); let id = $('#role_id').val();
        var data = ['add_role']
        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });
        if (role != '') {
            if (isValid) {
                $.post('api/user_creation_files/submit_role.php', { role, id }, function (response) {
                    if (response == '0') {
                        swalError('Warning', 'Role Already Exists!');
                    } else if (response == '1') {
                        swalSuccess('Success', 'Role Updated Successfully!');
                    } else if (response == '2') {
                        swalSuccess('Success', 'Role Added Successfully!');
                    }

                    getRoleTable();
                }, 'json');
                clearRole(); //To Clear All Fields in Role creation.
            }
        }
    });

    $(document).on('click', '.roleActionBtn', function () {
        var id = $(this).attr('value'); // Get value attribute
        $.post('api/user_creation_files/get_role_data.php', { id }, function (response) {
            $('#role_id').val(id);
            $('#add_role').val(response[0].role);
        }, 'json');
    });

    $(document).on('click', '.roleDeleteBtn', function () {
        var id = $(this).attr('value'); // Get value attribute
        swalConfirm('Delete', 'Do you want to Delete the Role?', deleteRole, id);
        return;
    });
    /////////////////////////////////////////////////////////// Role Modal END ///////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////// Place Modal START ///////////////////////////////////////////////////////////////////////
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
     /////////////////////////////////////////////////////////// Designation Modal START ///////////////////////////////////////////////////////////////////////
     $('#submit_occ').click(function (event) {
        event.preventDefault();
        let designation = $('#add_occ').val(); let id = $('#add_occ_id').val();
        var data = ['add_occ']
        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });
        if (designation != '') {
            if (isValid) {
                $.post('api/user_creation_files/submit_occupation.php', { designation, id }, function (response) {
                    if (response == '0') {
                        swalError('Warning', 'Designation Already Exists!');
                    } else if (response == '1') {
                        swalSuccess('Success', 'Designation Updated Successfully!');
                    } else if (response == '2') {
                        swalSuccess('Success', 'Designation Added Successfully!');
                    }

                    getDesignationTable();
                }, 'json');
                clearDesignation(); //To Clear All Fields in Occupation creation.
            }
        }
    });

    $(document).on('click', '.occActionBtn', function () {
        var id = $(this).attr('value'); // Get value attribute
        $.post('api/user_creation_files/get_occupation_data.php', { id }, function (response) {
            $('#add_occ_id').val(id);
            $('#add_occ').val(response[0].designation);
        }, 'json');
    });

    $(document).on('click', '.occDeleteBtn', function () {
        var id = $(this).attr('value'); // Get value attribute
        swalConfirm('Delete', 'Do you want to Delete the Designation?', deleteDesignation, id);
        return;
    });
    /////////////////////////////////////////////////////////// Designation Modal END ///////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////// User Creation START ///////////////////////////////////////////////////////////////////////
    $('#submit_user_creation').click(function (event) {
        event.preventDefault();

        // Collect selected submenu IDs
        let selectedSubmenuIds = [];
        $('input[type="checkbox"]:checked').each(function () {
            if ($(this).hasClass('submenu-checkbox')) {
                selectedSubmenuIds.push($(this).val());
            }
        });

        let userFormData = {
            name: $('#name').val(),
            user_code: $('#user_id').val(),
            role: $('#role').val(),
            place: $('#place').val(),
            designation: $('#designation').val(),
            address: $('#address').val(),
            email: $('#email').val(),
            mobile_no: $('#mobile_no').val(),
            user_name: $('#user_name').val(),
            password: $('#password').val(),
            confirm_password: $('#confirm_password').val(),
            branch_name: $('#branch_name').val(),
            occ_detail:$("#occ_detail").val(),
            collection_access: $('#collection_access').val(),
            submenus: selectedSubmenuIds,
            id: $('#user_creation_id').val()
        }
        var data = ['name', 'user_id','role', 'user_name', 'password', 'confirm_password', 'place','designation','collection_access']

        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });
        let isBranchNameValid = validateMultiSelectField('branch_name', branch_name);

        // if (isFormDataValid(userFormData)) {
        if (isValid && isBranchNameValid) {
            if (selectedSubmenuIds.length > 0) {
                $.post('api/user_creation_files/submit_user_creation.php', userFormData, function (response) {
                    if (response.status == '') {
                        swalError('Error', 'Creation Failed.');
                    } else if (response.status == '1') {
                        swalSuccess('Success', 'User Updated Successfully!');
                    } else if (response.status == '2') {
                        swalSuccess('Success', 'User Added Successfully!');
                    } else if (response.status == '3') {
                        swalError('Warning', 'User Name Already Created.');
                    }

                    if (response.status != '' && response.status != '3') {
                        swapTableAndCreation();
                    }
                    let sessionId = $('#session_user_id').val();
                    if (response.last_id == sessionId) {
                        getLeftbarMenuList(); //After Submit/Update Leftbar want to refresh to view the changes.
                    }
                }, 'json');
            }
            
        }
        // }
    });

    $(document).on('click', '.userActionBtn', function () {
        var id = $(this).attr('value'); // Get value attribute
        $.post('api/user_creation_files/user_creation_data.php', { id }, function (response) {
            $('#user_creation_id').val(id);
            swapTableAndCreation();//to change to div to table content.
            $('#name').val(response[0].name);
            $('#user_id').val(response[0].user_code);
            $('#address').val(response[0].address);
            $('#email').val(response[0].email);
            $('#mobile_no').val(response[0].mobile);
            $('#user_name').val(response[0].user_name);
            $('#password').val(response[0].password);
            $('#confirm_password').val(response[0].password);
            $('#occ_detail').val(response[0].occ_detail);
            $('#collection_access').val(response[0].collection_access);

            setTimeout(() => {
                getUserID(id)
                getRoleDropdown(response[0].role);
                getPlaceDropdown(response[0].place);
                getOccupationDropdown(response[0].designation);
                getBranchName(response[0].branch);
            }, 1000);

        }, 'json');
    });

    $(document).on('click', '.userDeleteBtn', function () {
        var id = $(this).attr('value'); // Get value attribute
        swalConfirm('Delete', 'Do you want to Delete the User?', deleteUser, id);
        return;
    });
    /////////////////////////////////////////////////////////// User Creation END ///////////////////////////////////////////////////////////////////////

    $('#email').on('change', function () {
        validateEmail($(this).val(), $(this).attr('id'));
    });

    $('#mobile_no').change(function () {
        checkMobileNo($(this).val(), $(this).attr('id'));
    });

    $('#password, #confirm_password').keyup(function () {
        const password = $('#password').val();
        const confirmPassword = $('#confirm_password').val();
        if (password != '' && confirmPassword != '') {
            if (password != confirmPassword) {
                $('#confirm_password').css("border", "1px solid red");
            } else {
                $('#confirm_password').css("border", "");

            }
        }
    });

    $('#password, #confirm_password').change(function () {
        const password = $('#password').val();
        const confirmPassword = $('#confirm_password').val();
        if (password != '' && confirmPassword != '') {
            if (password != confirmPassword) {
                $('#confirm_password').val('');
            }
        }
    });

    // Handle menu checkbox events
    $(document).on('change', '.main-menu input[type="checkbox"]', function () {
        const menuId = $(this).attr('id');
        const submenus = $(`#${menuId}-submenus input[type="checkbox"]`);
        submenus.prop('disabled', !this.checked);
        if (!this.checked) {
            submenus.prop('checked', false);
        }
    });

    $('button[type="reset"]').click(function (event) {
        event.preventDefault();
        $('input').each(function () {
            var id = $(this).attr('id');
            if (id !== 'company_name' && id != 'user_id' && id != 'user_creation_id' && id != 'session_user_id') {
                $(this).val('');
            }
        });

        $('select').each(function () {
            $(this).val($(this).find('option:first').val());
        });

        $('textarea').each(function () {
            $(this).val(''); // Reset textarea fields
        });

        // Reset Choices.js multiselect
        branch_name.removeActiveItems();

        // Uncheck and reset all checkboxes
        $('input[type="checkbox"]').prop('checked', false);
        $('#name').css('border', '1px solid #cecece');
        $('#user_id').css('border', '1px solid #cecece');
        $('#role').css('border', '1px solid #cecece');
        $('#place').css('border', '1px solid #cecece');
        $('#designation').css('border', '1px solid #cecece');
        $('#user_name').css('border', '1px solid #cecece');
        $('#password').css('border', '1px solid #cecece');
        $('#confirm_password').css('border', '1px solid #cecece');
        $('#collection_access').css('border', '1px solid #cecece');
        $('#branch_name').closest('.choices').find('.choices__inner').css('border', '1px solid #cecece');
    });

  

}); //Document END.

//ON Load
$(function () {
    getUserCreationTable();
    getSessionValue();
});

function getSessionValue() {
    $.post('api/base_api/getSessionData.php', function (response) {
        $('#session_user_id').val(response);
    }, 'json');
}
function swapTableAndCreation() {
    if ($('.user_creation_table_content').is(':visible')) {
        $('.user_creation_table_content').hide();
        $('.add_user_btn').hide();
        $('#user_creation_content').show();
        $('.back_to_userList_btn').show();
        let userid = $('#user_creation_id').val();

        getRoleDropdown('');
        getPlaceDropdown('');
        getOccupationDropdown('');
        getUserID('');
        getCompanyName();
        getBranchName('');
        getMenuSubMenuList(userid);
    } else {
        $('.user_creation_table_content').show();
        $('.add_user_btn').show();
        $('#user_creation_content').hide();
        $('.back_to_userList_btn').hide();
        getUserCreationTable();
        $('#reset_btn').trigger('click');
        $('#user_creation_id').val('0');
    }
}

function getUserCreationTable() {
    $.post('api/user_creation_files/user_creation_list.php', function (response) {
        let userColumn = [
            'sno',
            'name',
            'user_name',
            'role',
            'designation',
            'branch_names',
            'action'
        ];
        appendDataToTable('#user_creation_table', response, userColumn);
        setdtable('#user_creation_table');
    }, 'json');
}

function getMenuSubMenuList(userId) {
    $.post('api/user_creation_files/get_menu_submenu_list.php', function (response) {
        $('#dynamic-menus').empty();
        // Group submenus by main menu
        var grouped = {};
        response.forEach(function (item) {
            if (!grouped[item.main_menu_link]) {
                grouped[item.main_menu_link] = {
                    main_menu: item.main_menu,
                    submenus: []
                };
            }
            if (item.sub_menu) {
                grouped[item.main_menu_link].submenus.push({
                    sub_menu: item.sub_menu,
                    sub_menu_link: item.sub_menu_link,
                    sub_menu_id: item.sub_menu_id
                });
            }
        });

        // Iterate over grouped data to generate HTML
        var tabindex = 18;
        for (var mainMenuLink in grouped) {
            var menu = grouped[mainMenuLink];
            const menuHtml = `
                <div class="custom-control custom-checkbox main-menu">
                    <input type="checkbox" value="Yes" name="${mainMenuLink}-mainmenu" id="${mainMenuLink}-mainmenu" tabindex="${tabindex}">&nbsp;&nbsp;
                    <label class="custom-control-label" for="${mainMenuLink}-mainmenu">
                        <h5>${menu.main_menu}</h5>
                    </label>
                </div> 
                </br>
                <div class="row" id="${mainMenuLink}-mainmenu-submenus">
                    <!-- Submenus will be appended here -->
                </div>
                <hr>
            `;
            $('#dynamic-menus').append(menuHtml);
            tabindex++;
            menu.submenus.forEach(function (submenu) {
                const submenuHtml = `
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" value="${submenu.sub_menu_id}" class=" submenu-checkbox" name="${submenu.sub_menu_link}" id="${submenu.sub_menu_link}" tabindex="${tabindex}" disabled>&nbsp;&nbsp;
                            <label class="custom-control-label" for="${submenu.sub_menu_link}">${submenu.sub_menu}</label>
                        </div>
                    </div>
                `;
                $(`#${mainMenuLink}-mainmenu-submenus`).append(submenuHtml);
                tabindex++;
            });
        }

        // Fetch user permissions and set checkbox states
        $.post('api/user_creation_files/get_user_permissions.php', { user_id: userId }, function (userPermissions) {
            // Set main menu checkboxes
            userPermissions.forEach(function (permission) {
                $(`#${permission.main_menu_link}-mainmenu`).prop('checked', true);
                $(`#${permission.main_menu_link}-mainmenu`).trigger('change'); // Trigger change event to enable submenus

                $(`#${permission.sub_menu_link}`).prop('checked', true);
            });
        }, 'json');

    }, 'json');
}

function getUserID(id) {
    $.post('api/user_creation_files/get_user_id.php', { id }, function (response) {
        $('#user_id').val(response);
    }, 'json')
}

function clearRole() {
    $('#add_role').val('');
    $('#role_id').val('0');
    $('#add_role').css('border', '1px solid #cecece');
}

function getRoleTable() {
    $.post('api/user_creation_files/get_role_list.php', function (response) {
        let loanCategoryColumn = [
            "sno",
            "role",
            "action"
        ]
        appendDataToTable('#role_table', response, loanCategoryColumn);
        setdtable('#role_table');
    }, 'json');
}

function getRoleDropdown(role_name_id) {
    $.post('api/user_creation_files/get_role_list.php', function (response) {
        let appendLineNameOption = '';
        appendLineNameOption += '<option value="">Select Role</option>';
        $.each(response, function (index, val) {
            let selected = '';
            if (val.id == role_name_id) {
                selected = 'selected';
            }
            appendLineNameOption += '<option value="' + val.id + '" ' + selected + '>' + val.role + '</option>';
        });
        $('#role').empty().append(appendLineNameOption);

        clearRole();
    }, 'json');
}

function deleteRole(id) {
    $.post('api/user_creation_files/delete_role.php', { id }, function (response) {
        if (response == '1') {
            swalSuccess('Success', 'Role Deleted Successfully.');
            getRoleTable();
        } else if (response == '0') {
            swalError('Access Denied', 'Used in User Creation');
        } else {
            swalError('Error', 'Role Delete Failed.');
        }
    }, 'json');
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

function clearDesignation() {
    $('#add_occ').val('');
    $('#add_occ_id').val('0');
    $('#add_occ').css('border', '1px solid #cecece');
}

function getDesignationTable() {
    $.post('api/user_creation_files/get_occupation_list.php', function (response) {
        let placeList = [
            "sno",
            "designation",
            "action"
        ]
        appendDataToTable('#occ_modal_table', response, placeList);
        setdtable('#occ_modal_table');
    }, 'json');
}

function deleteDesignation(id) {
    $.post('api/user_creation_files/delete_occupation.php', { id }, function (response) {
        if (response == '1') {
            swalSuccess('Success', 'Designation Deleted Successfully.');
            getDesignationTable();
        } else if (response == '0') {
            swalError('Access Denied', 'Used in User Creation');
        } else {
            swalError('Error', 'Designation Delete Failed.');
        }
    }, 'json');
}

function getOccupationDropdown(occ_name_id) {
    $.post('api/user_creation_files/get_occupation_list.php', function (response) {
        let appendOccNameOption = '';
        appendOccNameOption += '<option value="">Select Designation</option>';
        $.each(response, function (index, val) {
            let selected = '';
            if (val.id == occ_name_id) {
                selected = 'selected';
            }
            appendOccNameOption += '<option value="' + val.id + '" ' + selected + '>' + val.designation + '</option>';
        });
        $('#designation').empty().append(appendOccNameOption);

        clearDesignation()
    }, 'json');
}

function getCompanyName() {
    $.ajax({
        url: 'api/user_creation_files/get_company_name.php',
        type: 'POST',
        dataType: 'json',
        cache: false,
        success: function (response) {
            $('#company_name').val(response['company_name']);
        },
        error: function (xhr, status, error) {
            swalError('Error', status + error);
        }
    });
}

function getBranchName(branch_edit_it) {
    $.post('api/common_files/get_branch_list.php', function (response) {
        branch_name.clearStore();
        $.each(response, function (index, val) {
            let selected = '';
            if (branch_edit_it.includes(val.id)) {
                selected = 'selected';
            }
            let items = [
                {
                    value: val.id,
                    label: val.branch_name,
                    selected: selected
                }
            ];
            branch_name.setChoices(items);
            branch_name.init();
        });
    }, 'json');
}

function deleteUser(id) {
    $.post('api/user_creation_files/delete_user.php', { id }, function (response) {
        if (response == '0') {
            swalSuccess('Success', 'User Deleted Successfully.');
            getUserCreationTable();
            setTimeout(() => {
                let userSessionId = $('#session_user_id').val();
                if (userSessionId == id) {
                    location.href = 'logout.php';
                }
            }, 2500);
        } else if (response == '1') {
            swalError('Error', 'User Delete Failed.');
        }
    }, 'json');
}