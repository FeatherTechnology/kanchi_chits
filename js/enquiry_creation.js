$(document).ready(function () {
  // addenquirey button click
  $(document).on("click", "#add_enquiry, #back_btn", function () {
    swaptables();
     

    
    
  });

  // chitinfo submit btton click
  $("#submit_enquiry_creation").click(function (event) {
    event.preventDefault();
    let chitvalue = $("#chitvalue").val();
    let chitmonth = $("#chitmonth").val();
    let enquiryid = $("#enquiryid").val();
    var data = ["chitvalue", "chitmonth"];

    var isValid = true;
    data.forEach(function (entry) {
      var fieldIsValid = validateField($("#" + entry).val(), entry);
      if (!fieldIsValid) {
        isValid = false;
      }
    });
    if (isValid) {
      $.post(
        "api/enquiry_creation_files/submit_enquiry_creation.php",
        { chitvalue, chitmonth, enquiryid },
        function (response) {
          if (response.result == "1") {
            swalSuccess("Success", "Enquiry Update Successfully!");
          } else if (response.result == "2") {
            swalSuccess("Success", "Enquiry Added Successfully!");
            $("#enquiryid").val(response.lastid);
            $("#basicinfocard").show();
            getcustomerlist(response.lastid);
          } else if (response.result == "0") {
            swalError("Alert", "Process Failed");
          }

          getenquirycreationlist();
          // $('#basicinfocard').show()
        },
        "json"
      );
    }
  });

  // addcustomer creation
  $("#addcustomer").click(function (event) {
    event.preventDefault();

    let name = $("#name").val();
    let customerstatus = $("#customerstatus").val();
    let enquiryid = $("#enquiryid").val();
    let mobilenumber = $("#mobilenumber").val();
    let place = $("#place").val();
    let remarks = $("#remarks").val();
    let customerlistid = $("#customerid").val();
    var data = ["name", "customerstatus", "mobilenumber", "place", "remarks"];
    var isValid = true;
    data.forEach(function (entry) {
      var fieldIsValid = validateField($("#" + entry).val(), entry);
      if (!fieldIsValid) {
        isValid = false;
      }
    });
    if (isValid) {
      $.post(
        "api/enquiry_creation_files/addcustomer.php",
        {
          name,
          customerstatus,
          mobilenumber,
          place,
          remarks,
          enquiryid,
          customerlistid,
        },
        function (response) {
          if (response.result == "1") {
            swalSuccess("Success", "Customer Update Successfully!");
            $("#name").val("");
            $("#customerstatus").val("");
            $("#mobilenumber").val("");
            $("#place").val("");
            $("#remarks").val("");
            $("#customerid").val("");

          } else if (response.result == "2") {
            swalSuccess("Success", "Customer Added Successfully!");
            $("#name").val("");
            $("#customerstatus").val("");
            $("#mobilenumber").val("");
            $("#place").val("");
            $("#remarks").val("");
          } else if (response.result == "0") {
            swalError("Alert", "Process Failed");
          }
        },
        "json"
      );
    }
    getcustomerlist(enquiryid);
  });

  $(document).on("click", ".enquiryActionBtn", function () {
    swaptables();
    let id = $(this).attr("value");
    $("#enquiryid").val(id);
    editchittable(id);
    $("#basicinfocard").show();
    getcustomerlist(id);
  });

  $(document).on("click", ".enquiryDeleteBtn", function () {
    var id = $(this).attr("value");
    swalConfirm(
      "Delete",
      "Do you want to Delete this Enquiry Creation?",
      deletenquiry,
      id
    );
    return;
  });

  $(document).on("click", ".customerActionBtn", function () {
    let id = $(this).attr("value");
    $("#customerid").val(id);
    customeredit(id);
  });


  $(document).on("click", ".customerDeleteBtn", function () {
    var id = $(this).attr("value");
    swalConfirm("Delete","Do you want to Delete this Customer?",deletcustomer,id);
    return;
  });



});








$("#mobilenumber").change(function () {

  checkMobileNo($(this).val(), $(this).attr("id"));
});
$(function () {
  getenquirycreationlist();
});

function getenquirycreationlist() {
  serverSideTable(
    "#enquiry_create",
    "",
    "api/enquiry_creation_files/enquiry_creation_list.php"
  );
}
// customerlistfile
function getcustomerlist(enquiryid) {
  serverSideTable(
    "#customer_create",
    enquiryid,
    "api/enquiry_creation_files/customerlist.php"
  );
}

function swaptables() {
  if ($(".enquiry_table_content").is(":visible")) {
    $(".enquiry_table_content").hide();
    $("#add_enquiry").hide();
    $("#enquiry_creation_content").show();
    $("#back_btn").show();

    $("#name").val("");
    $("#customerstatus").val("");
    $("#mobilenumber").val("");
    $("#place").val("");
    $("#remarks").val("");
    $("#customerid").val("0");
    $("#enquiryid").val("0");
    $("#chitvalue").val("");
    $("#chitmonth").val("");
    $("#basicinfocard").hide();
    
  } else {
    $(".enquiry_table_content").show();
    $("#add_enquiry").show();
    $("#enquiry_creation_content").hide();
    $("#back_btn").hide();
  }

}
function editchittable(id) {
  $.post(
    "api/enquiry_creation_files/enquiryedit.php",
    { id: id },
    function (response) {
      $("#enquiryid").val(id);
      $("#chitvalue").val(response[0].chit_value);
      $("#chitmonth").val(response[0].total_month);
    },
    "json"
  );
}
function deletenquiry(id) {
  $.post(
    "api/enquiry_creation_files/deletenquiry.php",
    { id },
    function (response) {
      console.log(response);
      if (response == 0) {
        swalError("Alert", "Process Failed");
      } else if (response == 1) {
        swalSuccess("Success", "Enquiry Creation Deleted Successfully!");
        getenquirycreationlist();
      }
    }
  );
}

function customeredit(id){
    $.post(
        "api/enquiry_creation_files/customeredit.php",
        { id: id },
        function (response) {
          $("#customerid").val(id);
          $("#name").val(response[0].cus_name);
          $("#customerstatus").val(response[0].cus_status);
          $("#mobilenumber").val(response[0].mobile_number);
          $("#place").val(response[0].place);
          $("#remarks").val(response[0].remarks);
        },
        "json"
      );
    
}
function deletcustomer(id) {
    $.post(
      "api/enquiry_creation_files/customerdelet.php",
      { id },
      function (response) {
        let enquiryid=$("#enquiryid").val();
        if (response == 0) {
          swalError("Alert", "Process Failed");
        } else if (response == 1) {
          swalSuccess("Success", "Customer Deleted Successfully!");
          getcustomerlist(enquiryid);
         
          
        }
      }
    );
  }
  
  $('button[type="reset"], #back_btn').click(function () {
    event.preventDefault();
    $('textarea').css('border', '1px solid #cecece');
    $('input').css('border', '1px solid #cecece');
    $('select').css('border', '1px solid #cecece');
});

  


