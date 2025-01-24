$(document).ready(function(){

    $('#from_date').change(function () {
        let from_date = $('#from_date').val();
        let to_date = $('#to_date').val();
        if (from_date > to_date) {
            $('#to_date').val('');
        }
        $('#to_date').attr('min', from_date);
    });
    
    const toggleButtons = $(".toggle-button");
    toggleButtons.removeClass('active'); //initially make all buttons unchecked
    toggleButtons.on("click", function () {
        // Reset active class for all buttons
        toggleButtons.removeClass("active");
        // Add active class to the clicked button
        $(this).addClass("active");

        let chosenOpt = $(this).val();
        if (chosenOpt == 'Today') {
            clearAllContents();
            getOpeningBal('today', '', '', '')
            getBalSheetDetails('today', '', '', '');
            getNetBenefitDetails('today', '', '', '');
        }
    });

    $('#submitDaywise').click(function () {
        let from_date = $('#from_date').val(); let to_date = $('#to_date').val();
        if (from_date != '' && to_date != '') {
            clearAllContents();
            getOpeningBal('day', from_date, to_date, '')
            getBalSheetDetails('day', from_date, to_date, '');
            getNetBenefitDetails('day', from_date, to_date, '');

            $('.close').trigger('click');//it will close modal
        } else {
            swalError('Please Fill Dates!', 'error');
            event.preventDefault();
        }
    })

    $('#submitMonthwise').click(function () {
        let for_month = $('#for_month').val()
        if (for_month != '') {
            clearAllContents();
            getOpeningBal('month', '', '', for_month)
            getBalSheetDetails('month', '', '', for_month);
            getNetBenefitDetails('month', '', '', for_month);

            $('.close').trigger('click');//it will close modal
        } else {
            swalError('Please Choose Month!', 'error');
            event.preventDefault();
        }
    });

}); ////// Document END.

$(function(){
    getUserNames();
});

function getUserNames() {
    //get user name only who has access of cash tally
    $.post('api/common_files/user_list.php', function(response) {
        $('#by_user').empty();
        $('#by_user').append("<option value=''>Select User</option>")
        $.each(response, function (index, val) {
            $('#by_user').append("<option value='" + val['id'] + "'>" + val['name'] + "</option> ");
        })
    }, 'json')
}

function getOpeningBal(type, from_date, to_date, month){
    var user_id = $('#by_user').val();
    if (type == 'today'){ 
        var args = { 'type': 'today', 'user_id': user_id }; 
    } else if (type == 'day'){ 
        var args = { 'type': 'day', 'from_date': from_date, 'to_date': to_date, 'user_id': user_id }; 
    } else if (type == 'month'){ 
        var args = { 'type': 'month', 'month': month, 'user_id': user_id }; 
    }
    $.post('api/accounts_files/balance_sheet_files/bs_opening_bal.php', args, function(response){
        if(response.length > 0){
            $('#balance_sheet_table tbody tr:first td:nth-child(2)').text(moneyFormatIndia(response[0]['opening_balance']));
        }
    },'json');
}

function getBalSheetDetails(type, from_date, to_date, month){
    var user_id = $('#by_user').val();
    if (type == 'today'){ 
        var args = { 'type': 'today', 'user_id': user_id }; 
    } else if (type == 'day'){ 
        var args = { 'type': 'day', 'from_date': from_date, 'to_date': to_date, 'user_id': user_id }; 
    } else if (type == 'month'){ 
        var args = { 'type': 'month', 'month': month, 'user_id': user_id }; 
    }

    $.post('api/accounts_files/balance_sheet_files/balance_sheet_details.php', args, function(response){
        $('#balance_sheet_table tbody tr:nth-child(3) td:nth-child(2)').text(moneyFormatIndia(response[0]['due']));

        $('#balance_sheet_table tbody tr:nth-child(5) td:nth-child(2)').text(moneyFormatIndia(response[0]['invcr']));
        $('#balance_sheet_table tbody tr:nth-child(6) td:nth-child(2)').text(moneyFormatIndia(response[0]['depcr']));
        $('#balance_sheet_table tbody tr:nth-child(7) td:nth-child(2)').text(moneyFormatIndia(response[0]['elcr']));
        $('#balance_sheet_table tbody tr:nth-child(8) td:nth-child(2)').text(moneyFormatIndia(response[0]['exccr']));
        $('#balance_sheet_table tbody tr:nth-child(9) td:nth-child(2)').text(moneyFormatIndia(response[0]['contracr']));
        $('#balance_sheet_table tbody tr:nth-child(10) td:nth-child(2)').text(moneyFormatIndia(response[0]['oicr']));

        $('#balance_sheet_table tbody tr:nth-child(5) td:nth-child(3)').text(moneyFormatIndia(response[0]['invdr']));
        $('#balance_sheet_table tbody tr:nth-child(6) td:nth-child(3)').text(moneyFormatIndia(response[0]['depdr']));
        $('#balance_sheet_table tbody tr:nth-child(7) td:nth-child(3)').text(moneyFormatIndia(response[0]['eldr']));
        $('#balance_sheet_table tbody tr:nth-child(8) td:nth-child(3)').text(moneyFormatIndia(response[0]['excdr']));
        $('#balance_sheet_table tbody tr:nth-child(9) td:nth-child(3)').text(moneyFormatIndia(response[0]['contradr']));

        $('#balance_sheet_table tbody tr:nth-child(12) td:nth-child(3)').text(moneyFormatIndia(response[0]['sttledr']));
        $('#balance_sheet_table tbody tr:nth-child(13) td:nth-child(3)').text(moneyFormatIndia(response[0]['expdr']));
        
    },'json').then(function(){
        setTimeout(() => {
            getBalSheetTotal();
        }, 1000);
    });
}

function getBalSheetTotal() {
    var credit_total = 0;
    var debit_total = 0;
    $('#balance_sheet_table tbody tr').not('tr:nth-child(14)').each(function () {
        var credit = $(this).find('td:nth-child(2)').text().replace(/,/g,''); // credit amount
        var debit = $(this).find('td:nth-child(3)').text().replace(/,/g,''); // debit amount
        credit_total += parseInt(credit) || 0;
        debit_total += parseInt(debit) || 0;
    });
    
    let close = credit_total - debit_total;
    debit_total = debit_total + close;
    credit_total = moneyFormatIndia(credit_total);
    debit_total = moneyFormatIndia(debit_total);

    $('#balance_sheet_table tbody tr:nth-child(14) td:nth-child(3)').text(moneyFormatIndia(close));
    $('#balance_sheet_table tbody tr:nth-child(16) td:nth-child(2)').text(credit_total).css('font-weight','bold');
    $('#balance_sheet_table tbody tr:nth-child(16) td:nth-child(3)').text(debit_total).css('font-weight','bold');
}

function getNetBenefitDetails(type, from_date, to_date, month){
    var user_id = $('#by_user').val();
    if (type == 'today'){ 
        var args = { 'type': 'today', 'user_id': user_id }; 
    } else if (type == 'day'){ 
        var args = { 'type': 'day', 'from_date': from_date, 'to_date': to_date, 'user_id': user_id }; 
    } else if (type == 'month'){ 
        var args = { 'type': 'month', 'month': month, 'user_id': user_id }; 
    }
    $.post('api/accounts_files/balance_sheet_files/net_benefit_details.php', args, function(response){
        $('#net_benefit_table tbody tr:nth-child(2) td:nth-child(2)').text(moneyFormatIndia(response[0]['benefit']));
        $('#net_benefit_table tbody tr:nth-child(3) td:nth-child(2)').text(moneyFormatIndia(response[0]['oicr']));

        $('#net_benefit_table tbody tr:nth-child(5) td:nth-child(3)').text(moneyFormatIndia(response[0]['expdr']));
        
    },'json').then(function(){
        setTimeout(() => {
            getNetBenefitTotal();
        }, 1000);
    });
}

function getNetBenefitTotal() {
    let credit_total = 0;
    let debit_total = 0;
    $('#net_benefit_table tbody tr').each(function () {
        let credit = $(this).find('td:nth-child(2)').text().replace(/,/g,''); // credit amount
        let debit = $(this).find('td:nth-child(3)').text().replace(/,/g,''); // debit amount
        credit_total += parseInt(credit) || 0;
        debit_total += parseInt(debit) || 0;
    });
    
    let benefit = credit_total - debit_total;
    credit_total = moneyFormatIndia(credit_total);
    debit_total = moneyFormatIndia(debit_total);
    benefit_total = moneyFormatIndia(benefit);

    $('#net_benefit_table tbody tr:nth-child(7) td:nth-child(2)').text(credit_total).css('font-weight','bold');
    $('#net_benefit_table tbody tr:nth-child(7) td:nth-child(3)').text(debit_total).css('font-weight','bold');
    $('#net_benefit_table tbody tr:nth-child(8) td:nth-child(2)').text(benefit_total).css('font-weight','bold');
}

// to clear all contents
function clearAllContents() {
    $('#balance_sheet_table').find('tbody tr').each(function () {
        $(this).find('td:nth-child(2)').text('')
        $(this).find('td:nth-child(3)').text('')
    });
    $('#net_benefit_table').find('tbody tr').each(function () {
        $(this).find('td:nth-child(2)').text('')
        $(this).find('td:nth-child(3)').text('')
    });
}