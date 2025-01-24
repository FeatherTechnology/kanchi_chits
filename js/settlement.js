$(document).ready(function () {
    $(document).on('click', '.back_btn', function () {
        $('.settlement_table_content').show();
        $('#settlement_content,.back_btn').hide();
        $('#settle_type_container, #bank_container, #cash_container,#cash_denom,#deno_upload_cont, #cheque_no_container, #cheque_val_container, #cheque_remark_container, #transaction_id_container, #transaction_val_container, #transaction_remark_container, #balance_remark_container').hide();
        $('#payment_type').val('');
        $('#settle_type').val('');
        $('#den_upload').val(''); // This won't work, see below for the workaround
        $('#den_upload_edit').val('');
        $('#settlement_screen input').val('')
        $('#settlement_screen select').val('');
        resetValidation()
        getDocInfoTable('')
        getCashAck('');
    });

    // Initial Hide of all optional fields
    $('#settle_type_container, #bank_container, #cash_container,#cash_denom, #deno_upload_cont,#cheque_no_container, #cheque_val_container, #cheque_remark_container, #transaction_id_container, #transaction_val_container, #transaction_remark_container, #balance_remark_container').hide();

    // Payment Type Change
    $('#payment_type').change(function () {
        let paymentType = $(this).val();
        // fetchSettlementData($('#groupid').val()); // Fetch data based on group ID
        updateSettleAmount();
        $('#settle_type').val('');
        resetValidation()
        if (paymentType == "1") { // Split Payment
            $('#settle_type_container').show();
            getBankName()
            $('#bank_container, #cash_container,#deno_upload_cont,#cash_denom, #cheque_no_container, #cheque_val_container, #cheque_remark_container, #transaction_id_container, #transaction_val_container, #transaction_remark_container, #balance_remark_container').hide();
            // Calculate balance amount whenever the cash, cheque, or transaction values are entered
            $('#settle_cash, #cheque_val, #transaction_val').on('input', function () {
                calculateBalance();
            });
        } else if (paymentType == "2") { // Single Payment
            $('#settle_type_container').show();
            $('#bank_container, #cash_container,#cash_denom,deno_upload_cont,#cheque_no_container, #cheque_val_container, #cheque_remark_container, #transaction_id_container, #transaction_val_container, #transaction_remark_container, #balance_remark_container').hide();
        } else {
            $('#settle_type_container, #bank_container, #cash_container, #cash_denom,#deno_upload_cont,#cheque_no_container, #cheque_val_container, #cheque_remark_container, #transaction_id_container, #transaction_val_container, #transaction_remark_container, #balance_remark_container').hide();
        }
    });
    // Event listener for quantity input changes
    $('#denominationTableBody').on('keyup', 'input[type="number"]', function () {
        lastQuantityInput = $(this); // Store the last quantity input
        updateTotalValue(); // Call the function to update total value
    });

    $('#settle_type').change(function () {
        let settleType = $(this).val();
        updateSettleAmount();
        let paymentType = $('#payment_type').val();

        if (paymentType == '2') {  // Handling for Payment Type 2
            if (settleType == "1") { // Cash
                resetValidation();
                $('#cash_container').show();
                $('#cash_denom').show();
                $('#deno_upload_cont').show();
                $('#bank_container, #cheque_no_container, #cheque_val_container, #cheque_remark_container, #transaction_id_container, #transaction_val_container, #transaction_remark_container').hide();
                $('#settle_cash').prop('readonly', true);
                $('#transaction_val').val('');
                $('#cheque_val').val('');
            } else if (settleType == "2") { // Cheque
                resetValidation();
                getBankName();
                $('#bank_container, #cheque_no_container, #cheque_val_container, #cheque_remark_container').show();
                $('#cash_container, #cash_denom,#deno_upload_cont,#transaction_id_container, #transaction_val_container, #transaction_remark_container').hide();
                $('#cheque_val').prop('readonly', true);
                $('#settle_cash').val('');
                $('#transaction_val').val('');
            } else if (settleType == "3") { // Bank Transfer
                resetValidation();
                getBankName();
                $('#bank_container, #transaction_id_container, #transaction_val_container, #transaction_remark_container').show();
                $('#cash_container, #cash_denom,#deno_upload_cont,#cheque_no_container, #cheque_val_container, #cheque_remark_container').hide();
                $('#transaction_val').prop('readonly', true);
                $('#cheque_val').val('');
                $('#settle_cash').val('');
            } else {
                $('#cash_container, #cash_denom,#deno_upload_cont,#bank_container, #cheque_no_container, #cheque_val_container, #cheque_remark_container, #transaction_id_container, #transaction_val_container, #transaction_remark_container').hide();
            }
        } else if (paymentType == '1') {  // Handling for Payment Type 1 (assuming it's another value, modify if needed)
            if (settleType == "1") { // Cash
                resetValidation();
                $('#add_grup')
                    .removeAttr('data-toggle')
                    .removeAttr('data-target');
                $('#cash_container').show();
                $('#deno_upload_cont').show();
                $('#cash_denom').show();
                $('#balance_remark_container').show();
                $('#settle_cash').prop('readonly', false);
                $('#balance_amount').val('');
                $('#bank_container, #cheque_no_container, #cheque_val_container, #cheque_remark_container, #transaction_id_container, #transaction_val_container, #transaction_remark_container').hide();
            } else if (settleType == "2") { // Cheque
                resetValidation();
                getBankName();
                $('#bank_container, #cheque_no_container, #cheque_val_container, #cheque_remark_container').show();
                $('#balance_remark_container').show();
                $('#cheque_val').prop('readonly', false);
                $('#cash_container, #cash_denom,#deno_upload_cont,#transaction_id_container, #transaction_val_container, #transaction_remark_container').hide();
                $('#balance_amount').val('');
            } else if (settleType == "3") { // Bank Transfer
                resetValidation();
                getBankName();
                $('#bank_container, #transaction_id_container, #transaction_val_container, #transaction_remark_container').show();
                $('#balance_remark_container').show();
                $('#transaction_val').prop('readonly', false);
                $('#cash_container, #cash_denom,#deno_upload_cont,#cheque_no_container, #cheque_val_container, #cheque_remark_container').hide();
                $('#balance_amount').val('');
            } else {
                $('#cash_container, #cash_denom,#deno_upload_cont,#bank_container, #cheque_no_container, #cheque_val_container, #cheque_remark_container, #transaction_id_container, #transaction_val_container, #transaction_remark_container').hide();
            }
        }
        let openingHandCash = 0, openingBankCash = 0, closingHandCash = 0, closingBankCash = 0;

        // Fetch opening balance
        $.ajax({
            url: 'api/accounts_files/accounts/opening_balance.php',
            method: 'POST',
            dataType: 'json',
            success: function (response) {
                if (response && response.length > 0) {
                    openingHandCash = response[0].hand_cash;
                    openingBankCash = response[0].bank_cash;
                    // Now fetch the closing balance
                    fetchClosingBalance();
                }
            },
        });

        // Fetch closing balance
        function fetchClosingBalance() {
            $.ajax({
                url: 'api/accounts_files/accounts/closing_balance.php',
                method: 'POST',
                dataType: 'json',
                success: function (response) {
                    if (response && response.length > 0) {
                        closingHandCash = response[0].hand_cash;
                        closingBankCash = response[0].bank_cash;
                        // Now perform the combined calculation and validation
                        performValidation();
                    }
                },
            });
        }

        // Perform validation using combined opening and closing balances
        // Perform validation using combined opening and closing balances
        function performValidation() {
            // Add opening and closing balances
            let totalHandCash = openingHandCash + closingHandCash;
            let totalBankCash = openingBankCash + closingBankCash;
            let cheque_val = parseFloat($('#cheque_val').val().replace(/,/g, '')) || 0;
            let transaction_val = parseFloat($('#transaction_val').val().replace(/,/g, '')) || 0;
            let totalBankCashAmount = cheque_val + transaction_val;

            // Validate closing balance for Split Payment
            let submitButtonDisabled = false;

            if (paymentType === '1') {
                let settle_cash = parseFloat($('#settle_cash').val().replace(/,/g, '')) || 0;
                if (settle_cash > totalHandCash) {
                    swalError('Warning', `Hand cash (₹${moneyFormatIndia(settle_cash)}) exceeds the available total balance (₹${moneyFormatIndia(totalHandCash)}).`);
                    submitButtonDisabled = true;

                }

                if (totalBankCashAmount > totalBankCash) {
                    swalError('Warning', `Bank cash (₹${totalBankCashAmount}) exceeds the available total balance (₹${totalBankCash}).`);
                    submitButtonDisabled = true;
                }
            } else if (paymentType === '2') { // Single Payment
                if (settleType === '1') { // Settle with Cash
                    let settle_cash = parseFloat($('#settle_cash').val().replace(/,/g, '')) || 0;
                    if (settle_cash > totalHandCash) {
                        swalError('Warning', `Hand cash (₹${moneyFormatIndia(settle_cash)}) exceeds the available total balance (₹${moneyFormatIndia(totalHandCash)}).`);
                        submitButtonDisabled = true;
                    }
                } else if (settleType >= '2') { // Settle with Bank
                    if (totalBankCashAmount > totalBankCash) {
                        swalError('Warning', `Bank cash (₹${moneyFormatIndia(totalBankCashAmount)}) exceeds the available total balance (₹${moneyFormatIndia(totalBankCash)}).`);
                        submitButtonDisabled = true;
                    }
                }
            }

            $('#submit_settle_info').attr('disabled', submitButtonDisabled);
        }
    });

    $(document).on('click', '.settleListBtn', function (event) {
        event.preventDefault();
        $('.settlement_table_content').hide();
        $('#settlement_content,.back_btn').show();
        let id = $(this).attr('value');
        $('#groupid').val(id);
        editGroupCreation(id)
        getCustomerName(id)
        $('#groupid').val(id);
        $('#add_grup')
            .removeAttr('data-toggle')
            .removeAttr('data-target');
        const currentDate = new Date();
        $('#settle_date').val(formatDate(currentDate));
        getDocInfoTable('')
        getCashAck('');
        $('#submit_settle_info').attr('disabled', false);
    })

    // $('#gua_name').on('change', function () {
    //     const guarantorId = $(this).val();
    //     if (guarantorId === '-1') {
    //         // If "Customer" is selected
    //         $('#gua_relationship').val('Customer');
    //     } else if (guarantorId) {
    //         // Fetch the guarantor relationship if a valid ID is selected
    //         getGrelationshipName(guarantorId);
    //     } else {
    //         // Clear the relationship field if no valid ID is selected
    //         $('#gua_relationship').val('');
    //     }
    // });
    $('#customer_name').on('change', function () {
        const customerId = $(this).val();
        if (customerId && customerId !== 'null') {
            // Fetch the guarantor relationship if a valid ID is selected
            editCustomerCreation(customerId)
            getGuarantorRelationship(customerId)
            fetchSettlementData(customerId)
            //checkBalance()
            setTimeout(function () {
                getDocInfoTable();
                getCashAck();
                fetchSettlementData(customerId)
                //  getDenomImage();
            }, 1000);
        } else {
            // Set default relationship as 'Customer' if no valid ID is selected
            $('#gua_relationship').val('Customer');
        }
    });
    $('#gua_name').on('change', function () {
        const guarantorId = $(this).val();
        if (guarantorId && guarantorId !== 'null') {
            // Fetch the guarantor relationship if a valid ID is selected
            getGrelationshipName(guarantorId);
        } else {
            // Set default relationship as 'Customer' if no valid ID is selected
            $('#gua_relationship').val('Customer');
        }
    });
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

    $('#settle_cash, #cheque_val, #transaction_val').on('input', function () {
        // Remove commas first, then parse to float
        let settle_balance = parseFloat($('#settle_balance').val().replace(/,/g, '')) || 0; // Convert to float, default to 0 if empty
        let payment_type = $('#payment_type').val();
        let settle_type = $('#settle_type').val();
        let settle_cash = parseFloat($('#settle_cash').val().replace(/,/g, '')) || 0; // Convert to float, default to 0 if empty
        let cheque_val = parseFloat($('#cheque_val').val().replace(/,/g, '')) || 0; // Convert to float, default to 0 if empty
        let transaction_val = parseFloat($('#transaction_val').val().replace(/,/g, '')) || 0; // Convert to float, default to 0 if empty
        getModalAttr()
        if (payment_type == '1') { // Split Payment
            var totalAmount = settle_cash + cheque_val + transaction_val;

            // Compare totalAmount with settle_balance
            if (totalAmount > settle_balance) {
                swalError('Warning', 'The entered amount exceeds the settlement balance.');
                $('#balance_amount').val(0);
                $('#settle_cash').val('');
                $('#cheque_val').val('');
                $('#transaction_val').val('');
            }
        }

        // Variables for balances
        let openingHandCash = 0, openingBankCash = 0, closingHandCash = 0, closingBankCash = 0;

        // Fetch opening balance
        $.ajax({
            url: 'api/accounts_files/accounts/opening_balance.php',
            method: 'POST',
            dataType: 'json',
            success: function (response) {
                if (response && response.length > 0) {
                    openingHandCash = response[0].hand_cash;
                    openingBankCash = response[0].bank_cash;
                    // Now fetch the closing balance
                    fetchClosingBalance();
                }
            },
        });

        // Fetch closing balance
        function fetchClosingBalance() {
            $.ajax({
                url: 'api/accounts_files/accounts/closing_balance.php',
                method: 'POST',
                dataType: 'json',
                success: function (response) {
                    if (response && response.length > 0) {
                        closingHandCash = response[0].hand_cash;
                        closingBankCash = response[0].bank_cash;
                        // Now perform the combined calculation and validation
                        performValidation();
                    }
                },
            });
        }

        // Perform validation using combined opening and closing balances
        // Perform validation using combined opening and closing balances
        function performValidation() {
            // Add opening and closing balances
            let totalHandCash = openingHandCash + closingHandCash;
            let totalBankCash = openingBankCash + closingBankCash;
            let cheque_val = parseFloat($('#cheque_val').val().replace(/,/g, '')) || 0;
            let transaction_val = parseFloat($('#transaction_val').val().replace(/,/g, '')) || 0;
            let totalBankCashAmount = cheque_val + transaction_val;

            // Validate closing balance for Split Payment
            let submitButtonDisabled = false;

            if (payment_type === '1') {
                let settle_cash = parseFloat($('#settle_cash').val().replace(/,/g, '')) || 0;
                if (settle_cash > totalHandCash) {
                    swalError('Warning', `Hand cash (₹${moneyFormatIndia(settle_cash)}) exceeds the available total balance (₹${moneyFormatIndia(totalHandCash)}).`);
                    $('#settle_cash').val('');
                    submitButtonDisabled = true;
                }


                if (totalBankCashAmount > totalBankCash) {
                    swalError('Warning', `Bank cash (₹${moneyFormatIndia(totalBankCashAmount)}) exceeds the available total balance (₹${moneyFormatIndia(totalBankCash)}).`);
                    submitButtonDisabled = true;
                    $('#transaction_val').val('');
                    $('#cheque_val').val('');
                }
            } else if (payment_type === '2') { // Single Payment
                if (settle_type === '1') { // Settle with Cash
                    let settle_cash = parseFloat($('#settle_cash').val().replace(/,/g, '')) || 0;
                    if (settle_cash > totalHandCash) {
                        swalError('Warning', `Hand cash (₹${moneyFormatIndia(settle_cash)}) exceeds the available total balance (₹${moneyFormatIndia(totalHandCash)}).`);
                        submitButtonDisabled = true;
                    }
                } else if (settle_type === '2') { // Settle with Bank
                    if (totalBankCashAmount > totalBankCash) {
                        swalError('Warning', `Bank cash (₹${moneyFormatIndia(totalBankCashAmount)}) exceeds the available total balance (₹${moneyFormatIndia(totalBankCash)}).`);
                        submitButtonDisabled = true;
                    }
                }
            }

            $('#submit_settle_info').attr('disabled', submitButtonDisabled);
        }

    });

    $('#submit_settle_info').click(function (event) {
        event.preventDefault();

        // Create a FormData object to hold the form data
        let settleInfo = new FormData();

        // Append all your form fields to the FormData object
        settleInfo.append('auction_id', $('#groupid').val());
        settleInfo.append('group_id', $('#group_id').val());
        settleInfo.append('cus_id', $('#cus_id').val());
        settleInfo.append('settle_date', $('#settle_date').val());
        settleInfo.append('settle_amount', $('#settle_amount').val().replace(/,/g, ''));
        settleInfo.append('set_amount', $('#set_amount').val());
        settleInfo.append('settle_balance', parseFloat($('#settle_balance').val().replace(/,/g, '')) || 0);
        settleInfo.append('payment_type', $('#payment_type').val());
        settleInfo.append('settle_type', $('#settle_type').val());
        settleInfo.append('bank_name', $('#bank_name').val());
        settleInfo.append('settle_cash', parseFloat($('#settle_cash').val().replace(/,/g, '')) || 0);
        settleInfo.append('cheque_no', $('#cheque_no').val());
        settleInfo.append('cheque_val', parseFloat($('#cheque_val').val().replace(/,/g, '')) || 0);
        settleInfo.append('cheque_remark', $('#cheque_remark').val());
        settleInfo.append('transaction_id', $('#transaction_id').val());
        settleInfo.append('transaction_val', parseFloat($('#transaction_val').val().replace(/,/g, '')) || 0);
        settleInfo.append('transaction_remark', $('#transaction_remark').val());
        settleInfo.append('balance_amount', $('#balance_amount').val().replace(/,/g, ''));
        settleInfo.append('gua_name', $('#gua_name').val());
        settleInfo.append('gua_relationship', $('#gua_relationship').val());

        // Append the file from the file input
        let fileInput = $('#den_upload')[0].files[0];
        if (fileInput) {
            settleInfo.append('den_upload', fileInput);
        }

        settleInfo.append('den_upload_edit', $('#den_upload_edit').val());

        // Get the settle type
        let settleType = $('#settle_type').val();

        // Validation for the file upload based on settle_type
        let isUploadValid = true;
        if (settleType == '1') { // Assuming '1' means you want to validate den_upload
            if (!fileInput && !$('#den_upload_edit').val()) {
                isUploadValid = validateField('', 'den_upload_edit');
                if (!isUploadValid) {
                    $('#den_upload').css('border', '1px solid red'); // Highlight invalid field
                }
            } else {
                $('#den_upload').css('border', '1px solid #cecece');
                $('#den_upload_edit').css('border', '1px solid #cecece');
            }
        } else {
            // If settle_type is not 1, clear any previous highlights
            $('#den_upload').css('border', '1px solid #cecece');
            $('#den_upload_edit').css('border', '1px solid #cecece');
        }

        // Validate the form data
        let isValid = isFormDataValid(settleInfo) && isUploadValid;

        // Check if the form is valid before submission
        if (isValid) {
            $.ajax({
                url: 'api/settlement_files/submit_settlement_info.php',
                type: 'POST',
                data: settleInfo,
                processData: false, // Prevent jQuery from automatically processing the data
                contentType: false, // Prevent jQuery from setting content type
                success: function (response) {
                    if (response == '1') {
                        swalSuccess('Success', 'Settlement Successfully');
                        $('.settlement_table_content').show();
                        $('#settlement_content, .back_btn').hide();
                        getSettlementTable();
                        $('#groupid').val('');
                        $('#settlement_screen').trigger('reset');
                    } else {
                        swalError('Warning', 'Settlement Failed.');
                    }
                },
                error: function (xhr, status, error) {
                    swalError('Error', 'An error occurred while submitting the settlement info: ' + error);
                }
            });
        } else {
            swalError('Warning', 'Please fill the all the fields.');
        }
    });



    ///////////////////////////////////////////////////////////////////Document info START ////////////////////////////////////////////////////////////////////////////

    $('#submit_doc_info').click(function (event) {
        event.preventDefault();
        let doc_name = $('#doc_name').val();
        let doc_type = $('#doc_type').val();
        let doc_holder_name = $('#doc_holder_name').val();
        let doc_relationship = $('#doc_relationship').val();
        let remarks = $('#remarks').val();
        let doc_upload = $('#doc_upload')[0].files[0];
        let doc_upload_edit = $('#doc_upload_edit').val();
        let doc_info_id = $('#doc_info_id').val();
        let cus_id = $('#cus_id').val();
        let auction_id = $('#groupid').val();
        var data = ['doc_name', 'doc_type', 'doc_holder_name', 'doc_relationship']

        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });
        if (isValid) {
            let docInfo = new FormData();
            docInfo.append('doc_name', doc_name);
            docInfo.append('doc_type', doc_type);
            docInfo.append('doc_holder_name', doc_holder_name);
            docInfo.append('doc_relationship', doc_relationship);
            docInfo.append('remarks', remarks);
            docInfo.append('doc_upload', doc_upload);
            docInfo.append('doc_upload_edit', doc_upload_edit);
            docInfo.append('cus_id', cus_id);
            docInfo.append('groupid', auction_id);
            docInfo.append('id', doc_info_id);

            $.ajax({
                url: 'api/settlement_files/submit_document_info.php',
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
                    groupData()
                    getDocCreationTable();
                    $('#doc_info_form input:not(#grp_id):not(#grp_name):not(#auction_month)').val('');
                    //$('#clear_doc_form').trigger('click');
                    $('#doc_info_id').val('');
                    $('#doc_upload_edit').val('');
                }
            });
        }
    });

    $(document).on('click', '.docActionBtn', function () {
        let id = $(this).attr('value');
        $.post('api/settlement_files/doc_info_data.php', { id }, function (response) {
            $('#doc_name').val(response[0].doc_name);
            $('#doc_type').val(response[0].doc_type);
            $('#doc_holder_name').val(response[0].holder_name);
            $('#doc_relationship').val(response[0].relationship);
            $('#remarks').val(response[0].remarks);
            $('#doc_upload_edit').val(response[0].upload);
            $('#doc_info_id').val(response[0].id);
        }, 'json');
    });

    $(document).on('click', '.docDeleteBtn', function () {
        let id = $(this).attr('value');
        swalConfirm('Delete', 'Are you sure you want to delete this document?', deleteDocInfo, id);
    });

    $('#clear_doc_form').click(function (event) {
        event.preventDefault();
        $('#doc_info_form input:not(#grp_id):not(#grp_name):not(#auction_month)').val('');
        $('#doc_info_id').val('');
        $('#doc_upload_edit').val('');
        $('#doc_info_form input').css('border', '1px solid #cecece');
        $('#doc_info_form select').css('border', '1px solid #cecece');
    })
    ///////////////////////////////////////////////////////////////////Document info END ////////////////////////////////////////////////////////////////////////////

    ////////////////////////Document End/////////////////////////////////////////////

    ///////////////////////Auction Info START //////////////////////////////
    $('#auction_info').click(function(){
        event.preventDefault();
        $('#add_Calculation_modal').modal('show');

        let auctionData = {
            'group_id' : $('#group_id').val(),
            'date' : $('#auction_date').val()
        }
    
        $.ajax({
            url: 'api/auction_files/fetch_calculation_data.php',
            type: 'POST',
            data: auctionData,
            dataType: 'json',
            success: function (response) {
                $('#calc_group_name').val(response.group_name);
                $('#calc_auction_month').val(response.auction_month);
    
                // Format the date in dd-mm-yyyy format
                $('#cal_date').val(formatDate(new Date(response.cal_date)));
    
                $('#calc_chit_value').val(moneyFormatIndia(response.chit_value));
                $('#calc_auction_value').val(moneyFormatIndia(response.auction_value));
                $('#calc_commission').val(moneyFormatIndia(response.commission));
                $('#calc_total_value').val(moneyFormatIndia(response.total_value));
                $('#calc_chit_amount').val(moneyFormatIndia(Math.round(response.chit_amount)));
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', status, error);
            }
        });
    });

    $(document).on('click', '#print_cal', function () {

        const chitValue = $('#calc_chit_value').val().replace(/,/g, ''); // Ensure no extra commas
        const commission = $('#calc_commission').val().replace(/,/g, '');
        const auctionValue = $('#calc_auction_value').val().replace(/,/g, '');
        const chitAmount = $('#calc_chit_amount').val().replace(/,/g, '');
        const totalValue = $('#calc_total_value').val().replace(/,/g, '');

        const formattedChitValue = moneyFormatIndia(chitValue);
        const formattedCommission = moneyFormatIndia(commission);
        const formattedAuctionValue = moneyFormatIndia(auctionValue);
        const formattedChitAmount = moneyFormatIndia(chitAmount);
        const formattedTotalValue = moneyFormatIndia(totalValue);

        // Create the HTML content with formatted values
        const content = `
        <div id="print_content" style="text-align: center;">
            <h2 style="margin-bottom: 20px; display: flex; align-items: center; justify-content: center;">
                <img src="img/bg_none_eng_logo.png" style="margin-right: 10px;" class="img">
               
            </h2>
            <table style="margin: 0 auto; border-collapse: collapse; width: 25%;">
                <tr>
                    <td><strong>Group Name</strong></td>
                    <td>${$('#calc_group_name').val()}</td>
                </tr>
                <tr>
                    <td><strong>Auction Month</strong></td>
                    <td>${$('#calc_auction_month').val()}</td>
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

    ///////////////////////Auction Info END //////////////////////////////

});
$(function () {
    getSettlementTable();
});

function getSettlementTable() {
    serverSideTable('#settlement_list_table', '', 'api/settlement_files/settlement_list.php');

}
function editGroupCreation(id) {
    $.post('api/settlement_files/settle_group_data.php', { id: id }, function (response) {
        if (response && response.length > 0) {
            let data = response[0];
            $('#groupid').val(id);
            $('#group_id').val(data.group_id);
            $('#group_name').val(data.grp_name);
            $('#chit_value').val(moneyFormatIndia(data.chit_value));
            $('#commission').val(moneyFormatIndia(data.commission));
            $('#total_members').val(data.total_members);
            $('#total_month').val(data.total_months);
            $('#start_month').val(data.start_month);
            $('#end_month').val(data.end_month);
            $('#grp_month').val(data.auction_month);
            $('#auction_date').val(data.date);
        }
    }, 'json');
}
function groupData() {
    let group_id = $('#group_id').val();
    $('#grp_id').val(group_id);
    let group_name = $('#group_name').val();
    $('#grp_name').val(group_name);
    let grp_month = $('#grp_month').val();
    $('#auction_month').val(grp_month);
}
function editCustomerCreation(id) {
    let auction_id = $('#groupid').val();
    $.post('api/settlement_files/settle_customer_data.php', { id: id, auction_id: auction_id }, function (response) {
        if (response.length > 0) {
            $('#cus_id').val(response[0].cus_id);
            $('#map_id').val(response[0].map_id);
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
    }, 'json');
}
function getGrelationshipName(guarantorId) {
    $.ajax({
        url: 'api/settlement_files/gua_name.php',
        type: 'POST',
        data: { id: guarantorId },
        dataType: 'json',
        cache: false,
        success: function (response) {
            $('#gua_relationship').val(response.guarantor_relationship || 'Customer');
        },
        error: function (xhr, status, error) {
            console.error('Error fetching guarantor relationship:', error);
            $('#gua_relationship').val('Customer');
        }
    });
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

function getGuarantorRelationship(id) {
    let auction_id = $('#groupid').val();
    $.post('api/settlement_files/get_guarantor_name.php', { id: id, auction_id: auction_id }, function (response) {
        let appendGuarantorOption = "<option value=''>Select Name</option>";
        $.each(response, function (index, val) {
            let selected = '';
            let editGId = $('#gua_name_edit').val(); // Existing guarantor ID (if any)
            if (val.type === 'Guarantor' && val.id == editGId) {
                selected = 'selected';
            }

            // Display type of the person (Guarantor or Customer)
            appendGuarantorOption += "<option value='" + val.id + "' " + selected + ">" + val.name + "</option>";
        });

        $('#gua_name').empty().append(appendGuarantorOption);
        // Clear the relationship field
        $('#gua_relationship').val('');
    }, 'json');
}
function getDocGuarantor() {
    let cus_id = $('#cus_id').val(); // Corrected: added $
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

function refreshDocModal() {
    $('#clear_doc_form').trigger('click');
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
// function setSettlementFields(data) {

//     // Set Settlement Amount and Balance
//     // $('#settle_amount').val(settlementAmount);
//     $('#settle_amount').val(moneyFormatIndia(settlement_amount));
//     checkBalance();
//     // Update the UI based on payment and settlement types
//     updateSettleAmount();
// }
function formatDate(date) {
    let day = date.getDate();
    let month = date.getMonth() + 1; // Months are zero-based
    let year = date.getFullYear();

    // Add leading zeros if day or month is less than 10
    if (day < 10) day = '0' + day;
    if (month < 10) month = '0' + month;

    return day + '-' + month + '-' + year;
}

// Set Settlement Date to Current Date in dd-mm-yyyy format

function fetchSettlementData(id) {
    let auction_id = $('#groupid').val();
    $.post('api/settlement_files/get_settlement_amount.php', { id: id, auction_id: auction_id }, function (response) {
        if (response.length > 0) {
            // Assuming `response` is an array and we need the first object's `settlement_amount`
            let settlement_amount = response[0].settlement_amount;
            let set_amount = response[0].settle_amount;
            $('#settle_amount').val(moneyFormatIndia(settlement_amount));
            $('#set_amount').val(set_amount);
            checkBalance()

        } else {
            // Clear fields if no data found
            $('#settle_date').val('');
            $('#settle_amount').val('');
            $('#settle_balance').val('');
        }
    }, 'json');
}

function getDocCreationTable() {
    let cus_id = $('#cus_id').val();
    let auction_id = $('#groupid').val();
    $.post('api/settlement_files/doc_info_list.php', { cus_id, auction_id }, function (response) {
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
        $('#doc_info_form input:not(#grp_id):not(#grp_name):not(#auction_month)').val('');
        $('#doc_info_form textarea').val('');
        $('#doc_info_form input').css('border', '1px solid #cecece');
        $('#doc_info_form select').css('border', '1px solid #cecece');
        $('#doc_info_form select').each(function () {
            $(this).val($(this).find('option:first').val());
        });
    }, 'json');
}
function getDocInfoTable() {
    let cus_id = $('#cus_id').val();
    let auction_id = $('#groupid').val();
    $.post('api/settlement_files/doc_info_list.php', { cus_id, auction_id }, function (response) {
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
function updateSettleAmount() {
    const paymentType = $('#payment_type').val();
    const settleType = $('#settle_type').val();

    // Fetch the settle balance value
    const settleBalance = $('#settle_balance').val();

    // Hide all containers initially
    $('#cash_container, #cash_denom,#deno_upload_cont,#cheque_no_container, #cheque_val_container, #cheque_remark_container, #transaction_id_container, #transaction_val_container, #transaction_remark_container, #bank_container').hide();

    // Show relevant containers based on payment type and settlement type
    if (paymentType == "2") { // Single Payment
        $('#cheque_no').val('');
        $('#cheque_remark').val('');
        $('#transaction_id').val('');
        $('#cheque_no').val('');
        $('#transaction_remark').val('');
        if (settleType == "1") { // Cash
            $('#settle_cash').val(settleBalance);
            $('#cash_container').show(); // Show the cash container
            $('#deno_upload_cont').show(); // Show the cash container
            $('#cash_denom').show(); // Show the cash container
            getModalAttr()
        } else if (settleType == "2") { // Cheque
            $('#cheque_val').val(settleBalance);
            $('#cheque_no_container, #cheque_val_container, #cheque_remark_container, #bank_container').show(); // Show cheque containers
        } else if (settleType == "3") { // Bank Transfer
            $('#transaction_val').val(settleBalance);
            $('#transaction_id_container, #transaction_val_container, #transaction_remark_container, #bank_container').show(); // Show transaction containers
        }
    } else if (paymentType == "1") { // Split Payment
        $('#bank_container, #cash_container, #cash_denom,#deno_upload_cont,#cheque_no_container, #cheque_val_container, #cheque_remark_container, #transaction_id_container, #transaction_val_container, #transaction_remark_container, #balance_remark_container').hide();
        $('#settle_type_container').show();
        $('#settle_cash').val('');
        $('#cheque_val').val('');
        $('#transaction_val').val('');
        $('#cheque_no').val('');
        $('#cheque_remark').val('');
        $('#transaction_id').val('');
        $('#cheque_no').val('');
        $('#transaction_remark').val('');
    } else {
        $('#settle_type_container, #bank_container, #cash_container,#deno_upload_cont, #cash_denom,#cheque_no_container, #cheque_val_container, #cheque_remark_container, #transaction_id_container, #transaction_val_container, #transaction_remark_container, #balance_remark_container').hide();
    }
}

function calculateBalance() {
    // Get the settlement balance and remove commas, then parse it as a float
    let settlementBalance = parseFloat($('#settle_balance').val().replace(/,/g, '')) || 0;
    let cashVal = parseFloat($('#settle_cash').val()) || 0;
    let chequeVal = parseFloat($('#cheque_val').val()) || 0;
    let transactionVal = parseFloat($('#transaction_val').val()) || 0;

    // Calculate the remaining balance
    let remainingBalance = settlementBalance - (cashVal + chequeVal + transactionVal);

    // Format the remaining balance using the moneyFormatIndia function
    $('#balance_amount').val(moneyFormatIndia(remainingBalance));
}

function isFormDataValid(settleInfo) {
    let isValid = true;

    // Validate gua_name field
    if (!validateField(settleInfo.get('gua_name'), 'gua_name')) {
        isValid = false;
    }

    // Validate payment type
    if (!validateField(settleInfo.get('payment_type'), 'payment_type')) {
        isValid = false;
    }

    // Split Payment
    if (settleInfo.get('payment_type') == "1") {
        // Validate settle type
        if (!validateField(settleInfo.get('settle_type'), 'settle_type')) {
            isValid = false;
        }

        // Validate specific fields based on settle_type
        if (settleInfo.get('settle_type') == "1") { // Cash
            if (!validateField(settleInfo.get('settle_cash'), 'settle_cash')) {
                isValid = false;
            }
        } else if (settleInfo.get('settle_type') == "2") { // Cheque
            if (!validateField(settleInfo.get('cheque_no'), 'cheque_no') ||
                !validateField(settleInfo.get('cheque_val'), 'cheque_val') ||
                !validateField(settleInfo.get('bank_name'), 'bank_name')) {
                isValid = false;
            }
        } else if (settleInfo.get('settle_type') == "3") { // Transaction
            if (!validateField(settleInfo.get('transaction_id'), 'transaction_id') ||
                !validateField(settleInfo.get('transaction_val'), 'transaction_val') ||
                !validateField(settleInfo.get('bank_name'), 'bank_name')) {
                isValid = false;
            }
        }

        // Ensure that at least one payment method is filled
        let isCashFilled = settleInfo.get('settle_cash') > 0;
        let isChequeFilled = settleInfo.get('cheque_val') > 0;
        let isTransactionFilled = settleInfo.get('transaction_val') > 0;

        if (!(isCashFilled || isChequeFilled || isTransactionFilled)) {
            isValid = false;
            $('#settle_cash, #cheque_val, #transaction_val').css('border', '1px solid #ff0000');
        } else {
            resetFieldBorders(['settle_cash', 'cheque_val', 'transaction_val']);
        }
    } else if (settleInfo.get('payment_type') == "2") { // Single Payment
        if (!validateField(settleInfo.get('settle_type'), 'settle_type')) {
            isValid = false;
        }

        if (settleInfo.get('settle_type') == "1") { // Cash
            if (!validateField(settleInfo.get('settle_cash'), 'settle_cash')) {
                isValid = false;
            }
        } else if (settleInfo.get('settle_type') == "2") { // Cheque
            if (!validateField(settleInfo.get('cheque_no'), 'cheque_no') ||
                !validateField(settleInfo.get('cheque_val'), 'cheque_val') ||
                !validateField(settleInfo.get('bank_name'), 'bank_name')) {
                isValid = false;
            }
        } else if (settleInfo.get('settle_type') == "3") { // Transaction
            if (!validateField(settleInfo.get('transaction_id'), 'transaction_id') ||
                !validateField(settleInfo.get('transaction_val'), 'transaction_val') ||
                !validateField(settleInfo.get('bank_name'), 'bank_name')) {
                isValid = false;
            }
        }
    }


    return isValid;
}


function resetFieldBorders(fields) {
    fields.forEach(field => {
        document.getElementById(field).style.border = '1px solid #cecece';
    });
}

function resetValidation() {
    const fieldsToReset = [
        'settle_type', 'settle_cash', 'cheque_no', 'bank_name',
        'cheque_val', 'cheque_remark', 'transaction_id', 'transaction_val',
        'transaction_remark', 'payment_type', 'gua_name',
    ];

    fieldsToReset.forEach(fieldId => {
        $('#' + fieldId).css('border', '1px solid #cecece');
    });
}

function getCashAck() {
    let auction_id = $('#groupid').val();
    let cus_id = $('#cus_id').val();
    $.post('api/settlement_files/get_cashack_list.php', { auction_id, cus_id }, function (response) {
        let tableBody = $('#guarantor_table tbody');
        tableBody.empty(); // Clear existing rows

        // Check if response is an array and has elements
        if (Array.isArray(response) && response.length > 0) {
            response.forEach((row, index) => {
                // Append new rows to the table
                tableBody.append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>${row.settle_date}</td>
                        <td>${row.balance_amount}</td>
                        <td>${row.guarantor_name}</td>
                        <td>${row.guarantor_relationship}</td>
                        <td>${row.upload}</td>
                    </tr>
                `);
            });
        } else {
            // Optionally, display a message if no data is available
            tableBody.append(`
                <tr>
                    <td colspan="5">No data available</td>
                </tr>
            `);
        }
    }, 'json');
}


function checkBalance() {
    let auction_id = $('#groupid').val();
    let cus_id = $('#cus_id').val();
    $.ajax({
        url: 'api/settlement_files/get_balance_amount.php',
        type: 'POST',
        data: { "auction_id": auction_id, 'cus_id': cus_id },
        dataType: 'json',
        success: function (response) {
            if (response && response.balance_amount !== undefined) {
                // Check if balance amount is zero
                let balanceAmount = response.balance_amount;
                if (balanceAmount === 'null' || balanceAmount === null) {
                    // Set balance to settlement amount if balance is zero
                    $('#settle_balance').val($('#settle_amount').val());
                } else {
                    $('#settle_balance').val(moneyFormatIndia(balanceAmount));
                }

            } else {
                console.error('Balance amount not found in response');
            }
        },
        error: function (xhr, status, error) {
            console.error('AJAX Error:', status, error);
        }
    });
}
function getModalAttr() {
    let settleCash = parseFloat($('#settle_cash').val()) || 0; // Get settle cash amount
    if (settleCash != '') {
        $('#add_grup')
            .attr('data-toggle', 'modal')
            .attr('data-target', '#denomination');
    } else {
        $('#add_grup')
            .removeAttr('data-toggle')
            .removeAttr('data-target');
    }
}

function calDenomination() {
    let settleCashInput = $('#settle_cash').val().replace(/,/g, ''); // Get settle cash input value as a string
    if (settleCashInput === '') {
        swalError('Alert', 'Kindly Fill the Cash!');
        return;
    }
    let settleCash = parseFloat(settleCashInput) || 0;
    let group_id = $('#group_id').val();
    $('#grop_id').val(group_id);
    let grp_month = $('#grp_month').val();
    $('#auc_month').val(grp_month);
    let date = $('#auction_date').val();
    let com = $('#commission').val();
    $('#cht_com').val(com);
    let cht_value = $('#chit_value').val();
    $('#cht_value').val(cht_value);
    $('#set_val').val(moneyFormatIndia(settleCash));
    $.post('api/auction_files/fetch_calculation_data.php', { group_id: group_id, date: date }, function (response) {
        if (response) {
            // Since response is an object, access its properties directly
            let dateParts = response.cal_date.split('-');
            let formattedDate = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];
            $('#auct_date').val(formattedDate);
            $('#denon_name').val(response.group_name);
            $('#total_val').val(moneyFormatIndia(response.total_value));
            $('#act_val').val(moneyFormatIndia(response.auction_value));
        } else {
            console.error('No data found');
        }
    }, 'json');

}

let lastQuantityInput = null; // Variable to keep track of the last quantity input that was modified
function updateTotalValue() {
    let totalAmount = 0;
    let settleCash = parseFloat($('#settle_cash').val().replace(/,/g, '')) || 0;

    // Loop through each row in the denomination table
    $('#denominationTableBody tr').each(function () {
        const $row = $(this);

        // Parse the denomination
        const denomination = parseFloat($row.find('td:first').text());

        // Check if denomination is valid
        if (isNaN(denomination)) {
            return; // Skip this row if denomination is not valid
        }

        // Get the quantity input value, defaulting to 0 if not a valid number
        let quantity = parseFloat($row.find('input[type="number"]').val()) || 0;

        // Calculate the total value for this row
        const totalValue = denomination * quantity;

        // Set the calculated total value in the corresponding text input
        $row.find('input[type="text"]').val(totalValue);

        // Add to the overall total amount
        totalAmount += totalValue;
    });

    // Update the total amount display
    $('#totalAmount').val(totalAmount);

    // Validate against settle_cash input
    if (totalAmount > settleCash) {
        // Check if the last modified input exceeds the settle cash
        if (lastQuantityInput) {
            const $lastRow = lastQuantityInput.closest('tr'); // Get the row of the last input
            const denomination = parseFloat($lastRow.find('td:first').text());
            const lastQuantity = parseFloat(lastQuantityInput.val()) || 0;
            const lastTotalValue = denomination * lastQuantity;
            swalError('Warning', `Please enter a value less than the settlement amount (₹${moneyFormatIndia(settleCash)}).`);
            // Reset the last quantity and total value
            $lastRow.find('input[type="number"]').val(''); // Reset quantity to 0
            $lastRow.find('input[type="text"]').val(0); // Reset total value to 0
            totalAmount -= lastTotalValue; // Adjust total amount
        }

        // // Update the total amount display after adjustments
        $('#totalAmount').val(totalAmount);

        // Show alert message to user
        // swalError('Warning', `Total value (₹${moneyFormatIndia(totalAmount)}) exceeds settle cash amount (₹${moneyFormatIndia(settleCash)})!`);
    }
}

function resetDenominationTable() {
    // Reset all number inputs and total value to 0
    $('#denominationTableBody tr').each(function () {
        $(this).find('input[type="number"]').val(''); // Reset quantity inputs
        $(this).find('input[type="text"]').val(0);    // Reset total value inputs
    });
    $('#totalAmount').val(0); // Reset total amount
}

function printDenomination() {
    let totalAmount = parseFloat($('#totalAmount').val().replace(/,/g, ''));

    if (totalAmount === 0 || isNaN(totalAmount)) {
        swalError('Warning', 'Please fill in the denomination values before printing.');
        return;
    }

    const chitValue = $('#cht_value').val().replace(/,/g, '');
    const commission = $('#cht_com').val().replace(/,/g, '');
    const auctionValue = $('#act_val').val().replace(/,/g, '');
    const totalValue = $('#total_val').val().replace(/,/g, '');
    const setVal = $('#set_val').val().replace(/,/g, '');

    const formattedChitValue = moneyFormatIndia(chitValue);
    const formattedCommission = moneyFormatIndia(commission);
    const formattedAuctionValue = moneyFormatIndia(auctionValue);
    const formattedTotalValue = moneyFormatIndia(totalValue);
    const formattedSetlValue = moneyFormatIndia(setVal);

    let content = ` 
        <div id="print_content" style="text-align: center;">
            <h2 style="margin-bottom: 10px; display: flex; align-items: center; justify-content: center;">
                <img src="img/bg_none_eng_logo.png" class="img1" style=" height: 80px;">           
            </h2>
            <h3 style="margin-bottom: 10px; display: flex; align-items: center; justify-content: center;">
                Cash Denomination
            </h3>
            <table style="margin: 0 auto; border-collapse: collapse; width:65%; font-size: 16px;">
                <tr>
                    <td style="padding-bottom: 7px;"><strong>Group Name</strong></td>
                    <td style="padding-bottom: 7px;">${$('#denon_name').val()}</td>
                </tr>
                <tr>
                    <td style="padding-bottom: 7px;"><strong>Auction Month</strong></td>
                    <td style="padding-bottom: 7px;">${$('#auc_month').val()}</td>
                </tr>
                <tr>
                    <td style="padding-bottom: 7px;"><strong>Date</strong></td>
                    <td style="padding-bottom: 7px;">${$('#auct_date').val()}</td>
                </tr>
                <tr>
                    <td style="padding-bottom: 7px;"><strong>Chit Value</strong></td>
                    <td style="padding-bottom: 7px;">${formattedChitValue}</td>
                </tr>
                <tr>
                    <td style="padding-bottom: 7px;"><strong>Auction Value</strong></td>
                    <td style="padding-bottom: 7px;">${formattedAuctionValue}</td>
                </tr>
                <tr>
                    <td style="padding-bottom: 7px;"><strong>Commission</strong></td>
                    <td style="padding-bottom: 7px;">${formattedCommission}</td>
                </tr>
                <tr>
                    <td style="padding-bottom: 7px;"><strong>Total Amount</strong></td>
                    <td style="padding-bottom: 7px;">${formattedTotalValue}</td>
                </tr>
                <tr>
                    <td style="padding-bottom: 7px;"><strong>Settlement Amount</strong></td>
                    <td style="padding-bottom: 7px;">${formattedSetlValue}</td>
                </tr>
            </table>
        </div>
        <br />
        <div style="text-align: center;">
            <h4>Denomination Table</h4>
            <table style="margin: 0 auto; border-collapse: collapse; width: 85%; font-size: 16px;">
                <thead>
                    <tr>
                        <th style="border: 1px solid black; padding: 7px;">Amount</th>
                        <th style="border: 1px solid black; padding: 7px;">Quantity</th>
                        <th style="border: 1px solid black; padding: 7px;">Total Value</th>
                    </tr>
                </thead>
                <tbody>
    `;

    $('#denominationTableBody tr').each(function () {
        let amount = $(this).find('td:first').text();
        let quantity = $(this).find('input[type="number"]').val();
        let totalValue = $(this).find('input[type="text"]').val();

        const formattedQuantity = quantity ? quantity : '';
        const formattedTotalValue = totalValue ? moneyFormatIndia(totalValue) : '';

        if ($(this).find('td').eq(0).attr('colspan') === '2') {
            content += `
            <tr>
              <td colspan="2" style="border: 1px solid black; padding: 7px; text-align: right;"><strong>Total</strong></td>
              <td style="border: 1px solid black; padding: 7px;">${moneyFormatIndia($('#totalAmount').val().replace(/,/g, ''))}</td>
            </tr>
          `;
        } else {
            content += `
            <tr>
              <td style="border: 1px solid black; padding: 7px;">${amount}</td>
              <td style="border: 1px solid black; padding: 7px;">${formattedQuantity}</td>
              <td style="border: 1px solid black; padding: 7px;">${formattedTotalValue}</td>
            </tr>
          `;
        }
    });

    content += `
                </tbody>
            </table>
        </div>
        <br />
        <div style="display: flex; justify-content: space-between; margin-top: 80px;">
            <div>
                <h5 style="font-size: 16px;">Manager's Signature</h5>
            </div>
            <div>
                <h5 style="font-size: 16px;">Customer's Signature</h5>
            </div>
        </div>
    `;

    const printWindow = window.open('', '_blank');

    printWindow.document.write('<html><head><title>Print Denomination</title>');
    printWindow.document.write('<style>body{font-family: Arial, sans-serif; margin: 10px;} .table { width: 100%; border-collapse: collapse; } .table, .table th, .table td { border: 1px solid black; } .table th, .table td { padding: 7px; text-align: left; } .text-right { text-align: right; }</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(content);
    printWindow.document.write('</body></html>');

    setTimeout(() => {
        printWindow.document.close();
        printWindow.print();

        printWindow.onafterprint = function () {
            printWindow.close();
        };
    }, 1000);
}

// function getDenomImage() {
//     let auction_id = $('#groupid').val();
//     let cus_id = $('#cus_id').val();
//     $.post('api/settlement_files/get_denomination_list.php', { auction_id, cus_id }, function (response) {
//         // Show the container if there's a response
//         if (response) {
//             $('#deno_upload_cont').show();
//             $('#denom_data').html(response); // Insert the response links into the span
//         } else {
//             $('#deno_upload_cont').hide(); // Hide if there's no response
//         }
//     });
// }

function getCustomerName(id) {
    $.post('api/settlement_files/get_settle_customer.php', { id: id }, function (response) {
        let appendCusOption = '';
        appendCusOption += "<option value=''>Select Customer Name</option>";
        let isSharePercent100 = false; // Flag for share percent check
        let selectedCusName = ''; // Store customer name if share percent is 100
        let selectedCusId = ''; // Store customer ID if share percent is 100

        $.each(response, function (index, val) {
            let editGId = $('#custom_name_edit').val();
            if (val.share_percent == 100) {
                isSharePercent100 = true;
                selectedCusName = val.cus_name;
                selectedCusId = val.id;
            } else {
                let selected = (val.id == editGId) ? 'selected' : '';
                appendCusOption += "<option value='" + val.id + "' " + selected + ">" + val.cus_name + "</option>";
            }
        });

        // If share percent is 100, disable the dropdown and show the customer name
        if (isSharePercent100) {
            $('#customer_name').attr('disabled', true).html("<option value=''>" + selectedCusName + "</option>");

            // Manually trigger the onchange functions with the selected customer ID
            if (selectedCusId) {
                editCustomerCreation(selectedCusId);
                getGuarantorRelationship(selectedCusId);
                fetchSettlementData(selectedCusId);

                setTimeout(function () {
                    getDocInfoTable();
                    getCashAck();
                    fetchSettlementData(selectedCusId);
                }, 1000);
            }
        } else {
            $('#customer_name').attr('disabled', false).empty().append(appendCusOption);
        }
    }, 'json');
}

function closeChartsModal() {
    $('#add_Calculation_modal').modal('hide');
}