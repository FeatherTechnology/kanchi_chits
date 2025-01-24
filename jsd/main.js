
// Todays Date
$(function () {
	var interval = setInterval(function () {
		var momentNow = moment();
		$('#today-date').html(momentNow.format('DD') + ' ' + ' '
			+ momentNow.format('- dddd').substring(0, 12));
	}, 100);
});
$(function () {
	var interval = setInterval(function () {
		var momentNow = moment();
		$('#todays-date').html(momentNow.format('DD MMMM YYYY'));
	}, 100);
});
// Loading
$(function () {
	$("#loading-wrapper").fadeOut(3000);
});
// Textarea characters left
$(function () {
	$('#characterLeft').text('140 characters left');
	$('#message').keydown(function () {
		var max = 140;
		var len = $(this).val().length;
		if (len >= max) {
			$('#characterLeft').text('You have reached the limit');
			$('#characterLeft').addClass('red');
			$('#btnSubmit').addClass('disabled');
		}
		else {
			var ch = max - len;
			$('#characterLeft').text(ch + ' characters left');
			$('#btnSubmit').removeClass('disabled');
			$('#characterLeft').removeClass('red');
		}
	});
});
// Todo list
$('.todo-body').on('click', 'li.todo-list', function () {
	$(this).toggleClass('done');
});
// Tasks
(function ($) {
	var checkList = $('.task-checkbox'),
		toDoCheck = checkList.children('input[type="checkbox"]');
	toDoCheck.each(function (index, element) {
		var $this = $(element),
			taskItem = $this.closest('.task-block');
		$this.on('click', function (e) {
			taskItem.toggleClass('task-checked');
		});
	});
})(jQuery);
// Tasks Important Active
$('.task-actions').on('click', '.important', function () {
	$(this).toggleClass('active');
});
// Tasks Important Active
$('.task-actions').on('click', '.star', function () {
	$(this).toggleClass('active');
});
// Countdown
$(document).ready(function () {
	countdown();
	setInterval(countdown, 1000);
	function countdown() {
		var now = moment(), // get the current moment
			// May 28, 2013 @ 12:00AM
			then = moment([2020, 10, 7]),
			// get the difference from now to then in ms
			ms = then.diff(now, 'milliseconds', true);
		// If you need years, uncomment this line and make sure you add it to the concatonated phrase
		/*
		years = Math.floor(moment.duration(ms).asYears());
		then = then.subtract('years', years);
		*/
		// update the duration in ms
		ms = then.diff(now, 'milliseconds', true);
		// get the duration as months and round down
		// months = Math.floor(moment.duration(ms).asMonths());

		// // subtract months from the original moment (not sure why I had to offset by 1 day)
		// then = then.subtract('months', months).subtract('days', 1);
		// update the duration in ms
		ms = then.diff(now, 'milliseconds', true);
		days = Math.floor(moment.duration(ms).asDays());

		then = then.subtract(days, 'days');
		// update the duration in ms
		ms = then.diff(now, 'milliseconds', true);
		hours = Math.floor(moment.duration(ms).asHours());

		then = then.subtract(hours, 'hours');
		// update the duration in ms
		ms = then.diff(now, 'milliseconds', true);
		minutes = Math.floor(moment.duration(ms).asMinutes());

		then = then.subtract(minutes, 'minutes');
		// update the duration in ms
		ms = then.diff(now, 'milliseconds', true);
		seconds = Math.floor(moment.duration(ms).asSeconds());

		// concatonate the variables
		diff = '<div class="num">' + days + ' <span class="text"> Days Left</span></div>';
		$('#daysLeft').html(diff);
	}
});
// Bootstrap JS ***********
// Tooltip
$(function () {
	$('[data-toggle="tooltip"]').tooltip()
})
$(function () {
	$('[data-toggle="popover"]').popover()
})
// Custom Sidebar JS
jQuery(function ($) {
	// Dropdown menu
	$(".sidebar-dropdown > a").click(function () {
		$(".sidebar-submenu").slideUp(200);
		if ($(this).parent().hasClass("active")) {
			$(".sidebar-dropdown").removeClass("active");
			$(this).parent().removeClass("active");
		} else {
			$(".sidebar-dropdown").removeClass("active");
			$(this).next(".sidebar-submenu").slideDown(200);
			$(this).parent().addClass("active");
		}
	});
	//toggle sidebar
	$("#toggle-sidebar").click(function () {
		$(".page-wrapper").toggleClass("toggled");
	});
	// Pin sidebar on click
	$("#pin-sidebar").click(function () {
		if ($(".page-wrapper").hasClass("pinned")) {
			// unpin sidebar when hovered
			$(".page-wrapper").removeClass("pinned");
			$('.logo').css('visibility', 'visible');
			$("#sidebar").unbind("hover");

		} else {
			$(".page-wrapper").addClass("pinned");
			$('.logo').css('visibility', 'hidden');
			$("#sidebar").off('hover').hover(
				function () {
					// console.log("mouseenter");
					$(".page-wrapper").addClass("sidebar-hovered");
				},
				function () {
					// console.log("mouseout");
					$(".page-wrapper").removeClass("sidebar-hovered");
				}
			)
		}
	});
	// Pinned sidebar
	$(function () {
		$("#sidebar").hover(
			function () {
				// console.log("mouseenter");
				$(".page-wrapper").addClass("sidebar-hovered");
				$(".page-wrapper").hasClass("pinned") ? $('.logo').css('visibility', 'visible') : '';
			},
			function () {
				// console.log("mouseout");
				$(".page-wrapper").removeClass("sidebar-hovered");
				$(".page-wrapper").hasClass("pinned") ? $('.logo').css('visibility', 'hidden') : '';
			}
		)
	});
	// Toggle sidebar overlay
	$("#overlay").click(function () {
		$(".page-wrapper").toggleClass("toggled");
	});
	// Added by Srinu 
	$(function () {
		// When the window is resized, 
		$(window).resize(function () {
			// When the width and height meet your specific requirements or lower
			if ($(window).width() <= 768) {
				$(".page-wrapper").removeClass("pinned");
			}
		});
		// When the window is resized, 
		$(window).resize(function () {
			// When the width and height meet your specific requirements or lower
			if ($(window).width() >= 768) {
				$(".page-wrapper").removeClass("toggled");
			}
		});
	});
});
//this will set all alert messages with class 'alert' in slow fade out
setTimeout(function () {
	$('.alert').fadeOut('slow');
}, 2000);
//this will disable clicking outside of a modal in overall project
$('.modal').attr({
	'data-backdrop': "static",
	'data-keyboard': "false"
});
//this will disable auto complete in all input fields
$('input').attr('autocomplete', 'off');
// this will prevent normal window.alert messages to set it as swal
window.alert = function (message) {
	Swal.fire({
		text: message,
		target: 'body',
		toast: true,
		position: 'top-right',
		timer: 2000,
		showConfirmButton: true,
		confirmButtonColor: 'var(--primary-color)',
		timerProgressBar: true,
	})
	return false;
};
////////// Show Loader if ajax function is called inside anywhere in entire project  ////////
$(document).ajaxStart(function () {
	showOverlay();
});
$(document).ajaxStop(function () {
	hideOverlay();
});
showOverlay();
window.addEventListener('load', function () {
	hideOverlay();
});
// Function to add the overlay
function showOverlay() {
	var overlayDiv = document.createElement('div');
	overlayDiv.classList.add('overlay');
	document.body.appendChild(overlayDiv);

	var loaderDiv = document.createElement('div');
	loaderDiv.classList.add('loader');
	overlayDiv.appendChild(loaderDiv);

	var overlayText = document.createElement('span');
	overlayText.classList.add('overlay-text');
	overlayText.innerText = 'Please Wait';
	overlayDiv.appendChild(overlayText);
}
// Function to remove the overlay and clear the timer
function hideOverlay() {
	var overlayDiv = document.querySelector('.overlay');
	overlayDiv.remove();
}
//Initializing datatable by calling this function for every table.
function setdtable(table_id) {
	$(table_id).DataTable({
		'processing': true,
		'iDisplayLength': 10,
		"lengthMenu": [
			[10, 25, 50, -1],
			[10, 25, 50, "All"]
		],
		"createdRow": function (row, data, dataIndex) {
			$(row).find('td:first').html(dataIndex + 1);
		},
		"drawCallback": function (settings) {
			this.api().column(0).nodes().each(function (cell, i) {
				cell.innerHTML = i + 1;
			});
		},
		dom: 'lBfrtip',
		buttons: [{
			extend: 'excel',
		},
		{
			extend: 'colvis',
			collectionLayout: 'fixed four-column',
		}
		],
	}); // this will initialize all tables with dtable class in project as DataTable
};
///Append Row in Table TBody after getting response from ajax. ///common for all table//// Just send table id, response from ajax, Column in table. 
function appendDataToTable(tableSelector, response, columnMapping) {
	$(tableSelector).DataTable().destroy();
	let tableBody = $(tableSelector + ' tbody');
	tableBody.empty(); // Clear any existing rows in the table body

	// Iterate over the JSON data
	$.each(response, function (index, item) {
		// Create a new table row
		var row = $('<tr>');
		// Iterate over the column mapping
		$.each(columnMapping, function (key, value) {
			// Create a table cell with the data
			if (value === 'sno') {
				$('<td>').text(index + 1).appendTo(row); // Add serial number
			} else if (item.hasOwnProperty(value)) {

				if (value === 'action' || value === 'upload' || value === 'charts' || value === 'info' || value === 'grace_period') {
					// If the key is 'action' or 'upload', insert the HTML content directly
					$('<td>').html(item[value]).appendTo(row);
				} else {
					// Otherwise, insert the text content
					$('<td>').text(item[value]).appendTo(row);
				}
			}
		});
		// Append the row to the table body
		tableBody.append(row);
	});
}
/////////////////////////////////////////////////////

function serverSideTable(tableSelector, params, apiUrl) {
    $(tableSelector).DataTable().destroy(); // Destroy the previous table instance
    $(tableSelector).DataTable({
        'processing': true,          // Enable processing indicator
        'serverSide': true,          // Enable server-side processing
        'serverMethod': 'post',      // Use POST method for the request
        'ajax': {
            'url': apiUrl,           // URL for the AJAX request
            'data': function (data) { // Custom data sent to the server
                var search = $(tableSelector + '_search').val(); // Custom search input
                data.search = search;
                data.params = params;  // Additional params
            }
        },
        dom: 'lBfrtip',               // DOM layout for buttons and table controls
        buttons: [
            {
                extend: 'excel',
                title: "Branch List"
            },
            {
                extend: 'colvis',
                collectionLayout: 'fixed four-column',
            }
        ],
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        'order': [], // Allow default sorting (clicking on column header toggles between asc/desc)
        
        'drawCallback': function () {
            setDropdownScripts(); // Custom function for dropdowns (assuming you have one)

            let new_id = tableSelector.split("#").pop();
            $(tableSelector + '_filter input')
                .attr('id', new_id + '_search') // Add ID to the search input
                .addClass('custo-search'); // Add a custom class to search input
        }
    });
}









////////////////////////////////////////////////////



//Swal alert section *************************
function swalSuccess(title, text) {
	Swal.fire({
		icon: 'success',
		title: title,
		text: text,
		showConfirmButton: false,
		timerProgressBar: true,
		timer: 2000,
	})
}
function swalError(title, text) {
	Swal.fire({
		icon: 'error',
		title: title,
		text: text,
		confirmButtonColor: 'var(--primary-color)',
	})
}
function swalInfo(title, text) {
	Swal.fire({
		icon: 'info',
		title: title,
		text: text,
		confirmButtonColor: 'var(--primary-color)',
	})
}
function swalConfirm(title, text, functionname, idvalue, noCallback) {
	Swal.fire({
		title: title,
		text: text,
		icon: 'question',
		showCancelButton: true,
		confirmButtonColor: '#009688',
		cancelButtonColor: '#d33',
		cancelButtonText: 'No',
		confirmButtonText: 'Yes'
	}).then((result) => {
		if (result.isConfirmed) {
			functionname(idvalue);
		} else if (noCallback) {
			noCallback();
		}
	});
}
function checkMobileNo(mobileno, selector) {
	if (mobileno != '') {
		let mobileNoRegex = /[6-9]{1}[0-9]{9}$/;
		if (!(mobileNoRegex.test(mobileno))) {
			swalError('Warning', 'Enter valid Mobile Number.')
			$('#' + selector).val('')
		}
	}
}
function checkLandlineFormat(number, selector) {
	if (number != '') {
		let regex = /^\d{8,12}$/; // Landline number format (8 to 12 digits)
		if (!regex.test(number)) {
			swalError('Warning', 'Enter a valid landline number.');
			$('#' + selector).val('');
		}
	}
}
function validateEmail(emailInput, selector) {
	// Regular expression for email validation
	if (emailInput != '') {
		let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		if (!emailPattern.test(emailInput)) {
			swalError('Warning', 'Kindly enter valid Email address');
			$('#' + selector).val('');
		}
	}
}

function setDropdownScripts() {
	$('.dropdown').off().click(function (event) {
		event.preventDefault();
		$('.dropdown').not(this).removeClass('active');
		$(this).toggleClass('active');
	});

	$(document).click(function (event) {
		var target = $(event.target);
		if (!target.closest('.dropdown').length) {
			$('.dropdown').removeClass('active');
		}
	});
}

function checkInputFileSize(input, allowdsize, img) {
	if (input.files.length > 0) {
		const fileSize = input.files[0].size; // Get the size of the selected file
		const maxSize = allowdsize * 1024; // Maximum size in bytes (200 KB)
		if (fileSize > maxSize) {
			alert("Maximum File Size " + allowdsize + " KB. Please select a smaller file.");
			input.value = ''; // Clear the selected file
			img.attr('src', 'img/avatar.png');
		}
	}
}

function setCurrentDate(field_id) {
	const curDate = new Date();
	const curYear = curDate.getFullYear();
	const curMonth = curDate.getMonth() + 1;
	const curDay = curDate.getDate();
	$(field_id).val(`${curYear}-${curMonth < 10 ? '0' + curMonth : curMonth}-${curDay < 10 ? '0' + curDay : curDay}`);
}

function validateField(value, fieldId) {
	let response; // Declare the variable locally
	if (value === '' || value === null || value === undefined) {
		response = false;
		$('#' + fieldId).css('border', '1px solid #ff0000');
		swalError('Warning', 'Please fill out mandatory fields!');
	} else {
		response = true;
		$('#' + fieldId).css('border', '1px solid #cecece');
	}
	return response;
}
function validateMultiSelectField(fieldId, choicesInstance) {
	const selectedValues = choicesInstance.getValue(true);
	const choicesElement = $('#' + fieldId).closest('.choices'); // Targeting the Choices.js container

	if (selectedValues.length === 0) {
		choicesElement.find('.choices__inner').css('border', '1px solid #ff0000');
		swalError('Warning', 'Please fill out mandatory fields!');
		return false;
	} else {
		choicesElement.find('.choices__inner').css('border', '1px solid #cecece');
		return true;
	}
}

function moneyFormatIndia(num) {
    // Check if num is undefined or not a number
    if (num === undefined || num === null || isNaN(num)) {
        return '0'; // Return a default value if the input is invalid
    }
    
    // Convert num to a number type if it's a string
    num = Number(num);

    var isNegative = false;
    if (num < 0) {
        isNegative = true;
        num = Math.abs(num);
    }

    var explrestunits = "";
    if (num.toString().length > 3) {
        var lastthree = num.toString().substr(num.toString().length - 3);
        var restunits = num.toString().substr(0, num.toString().length - 3);
        restunits = (restunits.length % 2 === 1) ? "0" + restunits : restunits; // Add leading zero if necessary
        var expunit = restunits.match(/.{1,2}/g);
        for (var i = 0; i < expunit.length; i++) {
            if (i === 0) {
                explrestunits += parseInt(expunit[i]) + ",";
            } else {
                explrestunits += expunit[i] + ",";
            }
        }
        var thecash = explrestunits + lastthree;
    } else {
        var thecash = num.toString(); // Ensure it's a string
    }

    return isNegative ? "-" + thecash : thecash;
}
