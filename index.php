<?php
//this screen is only for login page*****

//below code is for redirecting user to dashboard if already logged in, even directly changes url
session_start();
$userid  = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "";
if ($userid != "") {
	echo "<script>location.href='home.php'</script>";
}

include "include/common/dashboardhead.php";
?>
<script>
    localStorage.setItem('currentPage', 'dashboard');
</script>

<body class="authentication">

	<form id="loginform" name="loginform" action="" method="post">
		<div class="login-screen">
			<div class="login-box">
				<div class=" logo_div">
					<img src="./img/bg_none_eng_logo.png" alt="" class="img"><br>
				</div>
				<div>
				
				</div>
				<span class="text-danger" id="cinnocheck">
				</span>
				<h5 class="login_text">Welcome back,<br />Please Login to your Account.</h5>
				<div class="form-group mt-4">
					<label for="lusername">User Name</label>
					<input type="text" name="lusername" id="lusername" tabindex="1" class="form-control" value="" placeholder="Enter Email" style="padding: 10px;border-radius:6px;" />
					<span id="usernamecheck" class="text-danger" style="display:none">Enter Email</span>
				</div>
				<div class="form-group mt-4">
					<label for="lpassword">Password</label>
					<input type="password" name="lpassword" id="lpassword" tabindex="2" class="form-control" value="" placeholder="Enter Password" style="padding: 10px;border-radius:6px;" />
					<span id="passwordcheck" class="text-danger" style="display:none">Enter Password</span>
				</div>

				<div class="actions" style="padding-top: 40px;">
					<button type="submit" id="lbutton" tabindex="6" name="lbutton" class="form-control btn btn-primary" style="font-size: 1rem;font-weight: bolder;color: white;padding: 10px;border-radius:6px;">Login</button>
				</div>

			</div>
		</div>
	</form>

</body>



<?php include "include/common/dashboardfooter.php" ?>
<script src="jsd/index.js"></script>