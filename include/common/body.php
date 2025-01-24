<!-- Page wrapper start -->
<div class="page-wrapper">
    <?php include "include/common/leftbar.php"; ?>

    <!-- Page content start  -->
    <div class="page-content">

        <!-- Header start -->
        <header class="header">
            <div class="toggle-btns">
                <a id="toggle-sidebar" href="#">
                    <i class="icon-list"></i>
                </a>
                <a id="pin-sidebar" href="#">
                    <i class="icon-list"></i>
                </a>
            </div>
            <div class="header-items">
                <!-- Custom search start -->
                <ul class="header-actions">
                    <li class="dropdown"></li>
                    <li class="dropdown">
                        <div class="custom-search">
                            <input type="text" id="search_input_" class="search-query" placeholder="Search here ..." onkeypress="if (event.key === 'Enter') {$('#search_screens').trigger('click');}">
                            <i id="search_screens" class="icon-search" style="color:white"></i>
                        </div>
                        <div class="dropdown-menu dropdown-menu-right lrg" aria-labelledby="notifications" style="left: -52px;top: 43px;">
                            <div class="dropdown-menu-header">
                                Results
                            </div>
                            <div class="customScroll5 quickscard">
                                <ul class="header-notifications" id='search_ul'></ul>
                            </div>
                        </div>
                    </li>
                </ul>
                <!-- Custom search end -->

                <!-- Header actions start -->
                <ul class="header-actions">
                    <li class="dropdown"></li>
                    <li class="dropdown">
                        <a href="#" id="notifications" data-toggle="dropdown" aria-haspopup="true">
                            <i class="icon-bell"></i>
                            <span class="count-label"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right lrg" aria-labelledby="notifications" style="left: -238px;">
                            <div class="dropdown-menu-header">
                                Notifications
                            </div>
                            <div class="customScroll5 quickscard">
                                <ul class="header-notifications"></ul>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a href="#" id="userSettings" class="user-settings" data-toggle="dropdown" aria-haspopup="true">
                            <span class="user-name show-username"></span>
                            <span class="avatar">
                                <img src="img/av1.png" alt="avatar">
                                <span class="status online"></span>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userSettings">
                            <div class="header-profile-actions">
                                <div class="header-user-profile">
                                    <div class="header-user">
                                        <img src="img/av1.png" alt="Admin Template">
                                    </div>
                                    <h5 class="show-username"></h5>
                                    <p class="show-username"></p>
                                </div>
                                <a href="#"><i class="icon-user1"></i> My Profile</a>
                                <a href="logout.php"><i class="icon-log-out1"></i> Sign Out</a>
                            </div>
                        </div>
                    </li>
                </ul>
                <!-- Header actions end -->
            </div>
        </header>

        <br><br>
        <div class="page-header">
            <div style="background-color:var(--primary-color); width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
                Kanchi Chits<span id="pageHeaderName"></span>
            </div>
        </div><br>
        <div class="main-container" id="main-container"></div>

    </div>
</div>