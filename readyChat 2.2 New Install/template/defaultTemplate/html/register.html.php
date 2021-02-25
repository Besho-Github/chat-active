<?php if (!defined('access')): die("403"); endif; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $settings["site_title"]; ?></title>
	<link rel="stylesheet" type="text/css" href="template/<?php echo $GLOBALS["settings"]["template"]; ?>/css/readyChatLogin.css" />
</head>
<body>
	<?php
		echo $offline_notice;
		if (isset($_GET["n"]))
		{
			if ($_GET["n"] == 1)
			{
				$error = "- <span style=\"color:red; font-size:12px; font-weight:normal;\">A required field was empty</span>";
			}
			elseif ($_GET["n"] == 2)
			{
				$error = "- <span style=\"color:red; font-size:12px; font-weight:normal;\">Unable to complete registration. Please try again.</span>";
			}
			elseif ($_GET["n"] == 3)
			{
				$error = "- <span style=\"color:red; font-size:12px; font-weight:normal;\">That username has been taken, sorry.</span>";
			}
			elseif ($_GET["n"] == 4)
			{
				$error = "- <span style=\"color:red; font-size:12px; font-weight:normal;\">Please enter a valid email address.</span>";
			}
			elseif ($_GET["n"] == 5)
			{
				$error = "- <span style=\"color:red; font-size:12px; font-weight:normal;\">Usernames may contain letters and numbers only.</span>";
			}
			elseif ($_GET["n"] == 6)
			{
				$error = "- <span style=\"color:red; font-size:12px; font-weight:normal;\">The email address is already associated with another account.</span>";
			}
			elseif ($_GET["n"] == 7)
			{
				$error = "- <span style=\"color:red; font-size:12px; font-weight:normal;\">Username length should be 10 characters or less.</span>";
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
		
		if (!isset($_SESSION["regid"]))
		{
			$_SESSION["regid"] = md5(rand(1, 200)); 
		}
	?>	
	<div id="container">
		<div id="content">
			<div id="logo">
				<img src="template/<?php echo $GLOBALS["settings"]["template"]; ?>/images/logo.png">
			</div>
			<div id="welcome">
				Create your free account by filling out the form below or <a href="login.php">return to login</a>.
			</div>
			<div id="login" style="width:580px;">
				<div class="title">Register <?php echo $error; ?></div>
				<form action="register.php?action=register" method="post">
					Username<br />
					<span style="font-size:11px;">This will be your chat nickname.</span>
					<br />
					<input type="text" name="username" maxlength="30" placeholder="Create a username" value="<?php echo $username; ?>" <?php echo $field_off; ?>>
					<br /><br />
					Password<br />
					<span style="font-size:11px;">To prevent other users from using your nickname, create a password.</span>
					<br />
					<input type="password" name="password" maxlength="50" placeholder="Create a password" <?php echo $field_off; ?>>
					<br /><br />
					Email Address<br />
					<span style="font-size:11px;">Used in-case we need to contact you regarding your account.</span>
					<input type="text" name="email" maxlength="100" placeholder="Enter your email address" value="<?php echo $email; ?>" <?php echo $field_off; ?>>
					<br /><br />
					<input type="checkbox" name="terms" value="1" style="min-width:1px;" <?php echo $field_off; ?>> 
					I agree to the <a href="documents/terms.html" target="_blank">terms</a> and understand the <a href="documents/privacy.html" target="_blank">privacy policy</a>.
					<br /><br />
					<input type="submit" class="button" value="Register" <?php echo $field_off; ?>>
					<input type="hidden" name="regid" value="<?php echo $_SESSION["regid"]; ?>" <?php echo $field_off; ?>>
					<a href="login.php"><div class="button" style="margin-left:5px; margin-top:5px; min-width:80px; text-align:center;">Cancel</div></a>
				</form>
			</div>
		</div>
		<div id="footer">
			<a href="http://designskate.com/readychat/" target="_blank">Chat Software</a> by DesignSkate
		</div>
	</div>
</body>
</html>