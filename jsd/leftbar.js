$(document).ready(function () {
    // Define the mapping of current_page values to current_module values
    const moduleMapping = {
        'dashboard':'dashboard',
        'company_creation': 'master',
        'branch_creation': 'master',
        'customer_creation': 'master',
        'group_creation': 'master',
        'bank_creation': 'admin',
        'user_creation': 'admin',
        'auction': 'auction',
        'settlement':'settlement',
        'collection':'collection',
        'accounts':'accounts',
        'balance_sheet':'accounts',
        'customer_data':'customer_data',
        'group_summary':'group_summary',
        'enquiry_creation':'enquiry',
        'expenses_report':'reports',
        'other_transaction_report':'reports',
        'bulk_upload':'bulk_upload'
    };

    const current_page = localStorage.getItem('currentPage');
    // Assign the current_module based on the current_page value
    const current_module = moduleMapping[current_page] || 'dashboard';

    // Call the function with the current module
    getLeftbarMenuList(current_module);

})

// Wrap the $.ajax() function in a promise and use async/await
async function getLeftbarMenuList(current_module) {
    try {
        const response = await $.ajax({
            url: 'api/base_api/menulist.php',
            method: 'POST',
            dataType: 'json'
        });

        if (response.length !== 0) {
            // Call the function with the response
           await createSidebarMenu(response);
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
            await toggleSidebarSubmenus(current_module);
        }
    } catch (error) {
        console.error('Error fetching menu list:', error);
    }
}

// Function to create the sidebar menu
function createSidebarMenu(response) {
    $('.sidebar-menu').empty();
    var sidebar = $('<ul></ul>');

    // Group submenus by main menu
    var grouped = {};
    response.forEach(function (item) {
        if (!grouped[item.main_menu]) {
            grouped[item.main_menu] = [];
        }
        grouped[item.main_menu].push(item);
    });
    // Create main menu items
    for (var mainMenu in grouped) {
        var mainMenuLi = $('<li class="sidebar-dropdown ' + grouped[mainMenu][0].main_menu_link + '"></li>');
        var mainMenuLink = $('<a href="javascript:void(0)"></a>').appendTo(mainMenuLi);
        mainMenuLink.append('<i class="icon-' + grouped[mainMenu][0].main_menu_icon + '"></i>');
        mainMenuLink.append('<span class="menu-text">' + mainMenu + '</span>');

        var submenuDiv = $('<div class="sidebar-submenu"></div>').appendTo(mainMenuLi);
        var submenuUl = $('<ul></ul>').appendTo(submenuDiv);

        // Create submenu items
        grouped[mainMenu].forEach(function (subItem) {
            var subLi = $('<li></li>').appendTo(submenuUl);
            var subLink = $('<a href="' + subItem.sub_menu_link + '" class="clickevent"></a>').appendTo(subLi);
            subLink.append('<i class="icon-' + subItem.sub_menu_icon + '"></i>');
            subLink.append(subItem.sub_menu);

        });

        sidebar.append(mainMenuLi);
    }

    // Append the sidebar to the DOM
    $('.sidebar-menu').append(sidebar);

    $('.clickevent').each(function () {
        $(this).on('click', function (event) {
            event.preventDefault();
            setlocalvariable(this);
        });
    });
}

function setlocalvariable(element) {
    var hrefValue = $(element).attr('href');
    localStorage.setItem('currentPage', hrefValue);
    window.location.href = 'home.php';
}

function toggleSidebarSubmenus(current_module) {
    // Find all elements with the class 'sidebar-submenu'
    var sidebarSubmenus = document.querySelectorAll('.sidebar-submenu');

    // Loop through each submenu
    sidebarSubmenus.forEach(function (submenu) {
        // Check if the parent <li> has the class that matches the current module
        var parentLi = submenu.closest('li');
        if (parentLi && parentLi.classList.contains(current_module)) {
            // If it matches, show the submenu
            // submenu.style.display = 'block';
            let mainmenu = submenu.closest('.sidebar-dropdown');
            mainmenu.classList.add('active');
        } else {
            // If it doesn't match, hide the submenu
            // submenu.style.display = 'none';
        }
    });

    var sidebarLinks = document.querySelectorAll('.page-wrapper .sidebar-wrapper .sidebar-menu .sidebar-dropdown .sidebar-submenu ul li a');
    let current_page = localStorage.getItem('currentPage');

    sidebarLinks.forEach(function (link) {
        var href = link.getAttribute('href');
        if (href === current_page) {

            // Assuming 'href' is the variable that contains the element with href="company_creation"
            var selectedLink = document.querySelector('a[href="' + href + '"]');

            // To change the background color of the "Master" link, we need to navigate up to the parent <a> element
            var mainLink = selectedLink.closest('.sidebar-dropdown').querySelector('a');

            // Set the background color of the 'Master' link
            mainLink.style.backgroundColor = 'rgba(0, 0, 0, 0.2)';

            link.style.backgroundColor = 'rgba(0, 0, 0, 0.2)';
        }
    });
    if (current_page == 'dashboard') {
        $('.dashboard').css('backgroundColor', 'rgba(0, 0, 0, 0.2)');
    }
}