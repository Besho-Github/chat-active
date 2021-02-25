<?php if (!defined('access')): die("403"); endif; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $GLOBALS["settings"]["site_title"]; ?></title>
	<link rel="stylesheet" type="text/css" href="template/<?php echo $GLOBALS["settings"]["template"]; ?>/css/readyChatLogin.css" />
	<script type="text/javascript">
		var guestLoginEnabled = false;
		
		function guestLogin()
		{
			if (guestLoginEnabled)
			{
				 document.getElementById('guestMode').disabled = false;
				 guestLoginEnabled = false;
			}
			else
			{
				document.getElementById('guestMode').disabled = true;
				guestLoginEnabled = true;
			}
		}
	</script>
</head>
<body>
	<?php
		echo $offline_notice; 
		if (isset($_GET["e"]))
		{
			if ($_GET["e"] == 1)
			{
				$error = "- <span style=\"color:red; font-size:12px; font-weight:normal;\">A required field was empty</span>";
			}
			elseif ($_GET["e"] == 2)
			{
				$error = "- <span style=\"color:red; font-size:12px; font-weight:normal;\">The username doesn't exist.</span>";
			}
			elseif ($_GET["e"] == 3)
			{
				$error = "- <span style=\"color:red; font-size:12px; font-weight:normal;\">The password entered was incorrect.</span>";
			}
			elseif ($_GET["e"] == 4)
			{
				$error = "- <span style=\"color:red; font-size:12px; font-weight:normal;\">You are banned from the chat.</span>";
			}
			elseif ($_GET["e"] == 5)
			{
				$error = "- <span style=\"color:red; font-size:12px; font-weight:normal;\">The chat is in offline mode.</span>";
			}
			elseif ($_GET["e"] == 6)
			{
				$error = "- <span style=\"color:red; font-size:12px; font-weight:normal;\">There is already a guest with this name.</span>";
			}
			elseif ($_GET["e"] == 7)
			{
				$error = "- <span style=\"color:red; font-size:12px; font-weight:normal;\">Usernames may have letters and numbers.</span>";
			}
			elseif ($_GET["e"] == 8)
			{
				$error = "- <span style=\"color:red; font-size:12px; font-weight:normal;\">Username length less than 10 characters.</span>";
			}
			else
			{
				$error = "";
			}
		}
		else
		{
			$error = "";
		}
	?>	
	<div id="container">
		<div id="content">
			<div id="logo">
				<img src="template/<?php echo $GLOBALS["settings"]["template"]; ?>/images/logo.png">
			</div>
			<div id="welcome">
				Welcome to <?php echo $settings["site_title"]; ?>. To begin chatting, please login below or <a href="register.php">create an account</a>.
			</div>
			<div id="login">
				<div class="title">Login <?php echo $error; ?></div>
				<form action="login.php?action=login" method="post">
					Username<br />
					<input type="text" name="username">
					<br /><br />
					Password<br />
					<input type="password" name="password" id="guestMode" >
					<br /><br />
					<?php if ($settings["allow_guests"]): ?>
					<input id="guestCheck" onclick="guestLogin();" type="checkbox" name="guest" value="1" style="float:left; min-width:1px; margin-top:2px; margin-right:5px;"> Login as guest
					<br /><br />
					<?php endif; ?>
					<input type="submit" class="button" value="Login">
				</form>
			</div>
			<div id="news">
				<div class="title">News</div>
				<div class="box">
					<?php echo $settings["login_news"]; ?>
				</div>
				<br />
				<div class="title">Users Online</div>
				<div class="box">
					<?php echo $show_online; ?>
				</div>
			</div>
		</div>
		<div id="footer">
			<a href="http://designskate.com/readychat/" target="_blank">Chat Software</a> by DesignSkate
		</div>
	</div>
</body>
</html>