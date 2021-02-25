<?php if (!file_exists("install.txt")){ die("Installer locked."); } ?>
<?php
	if ($_POST["user"] == null || $_POST["pass"] == null)
	{
		header('location: ./index_6.php?error=1');
	}
	else
	{
		include("../core/rc/database.inc.php");
		$mysqli = @new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
		
		$username = trim($mysqli->real_escape_string($_POST["user"]));
		$password = trim($mysqli->real_escape_string($_POST["pass"]));
		
		// ensure username consists of letters and numbers only
		if (preg_match("/^[a-zA-Z0-9]+$/", $username))
		{
			// encrypt password
			$epw = sha1(str_rot13($password . $keys["enc_1"]));
			$ip = $mysqli->real_escape_string($_SERVER['REMOTE_ADDR']);
			$time = time();
			
			// create the account
			$mysqli->query("INSERT INTO `users` (user_name, user_password, user_email, user_ip, user_room, user_joined, rank, apanel) 
										 VALUES ('{$username}', '{$epw}', 'admin@yoursite.com', '{$ip}', 'lobby.html', '{$time}', '3', '1')") or die($mysqli->error);
										 
			unlink("install.txt");		 
		}
		else
		{
			header('location: ./index_6.php?error=2');
		}
	}
?>
<!doctype html>
<html>
    <head>
	    <title>readyChat Installer</title>
		<link rel="stylesheet" type="text/css" href="css/installer.css" />
	</head>
	<body>
		<div id="topbar"></div>
		<div id="wrap">
			<div id="logo"><img src="images/logo.png"></div>
			<div id="container">
				<div id="menu">
					<a href="#"><img src="icons/tick.png" alt="Y" title="Completed"> Welcome</a>
					<a href="#"><img src="icons/tick.png" alt="Y" title="Completed"> Check Permissions</a>
					<a href="#"><img src="icons/tick.png" alt="Y" title="Completed"> Configure Settings</a>
					<a href="#"><img src="icons/tick.png" alt="Y" title="Completed"> Install Tables</a>
					<a href="#"><img src="icons/tick.png" alt="Y" title="Completed"> Create Your Account</a>
					<a href="#" class="current"><img src="icons/tick.png" alt="Y" title="Completed"> Start Chatting</a>
				</div>
				<div id="content">
					<div class="title">Installation Complete</div>

					readyChat has been installed successfully. If you encounter any problems with this installation, please contact us for a quick resolution, your satisfaction is important
					to us.
					<br /><br />
					<div class="box">
						Your Username: <strong><?php echo $username; ?></strong><br />
						Your Password: <strong><?php echo $password; ?></strong><br />
					</div>
					
					<br /><br />
					<div id="next">
						<a href="../login.php">Proceed to website</a>
					</div>

				</div>
			</div>
		</div>
		<div id="footer">
			<div style="float:left;">
				readyChat installer version 1.2.0 for ReadyChat 2.2.0
			</div>
			<div style="float:right;">
				<a href="../documents/getting_started.html" target="_blank" style="color:#808080;">Installation Support</a>
			</div>
		</div>
	</body>
</html>