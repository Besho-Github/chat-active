<?php if (!file_exists("install.txt")){ die("Installer locked."); } ?>
<?php
	# Database Connection

	if (isset($_POST["host"]))
	{
		$host = $_POST["host"];
	}
	else
	{
		$host = null;
	}
	
	if (isset($_POST["user"]))
	{
		$user = $_POST["user"];
	}
	else
	{
		$user = null;
	}
	
	if (isset($_POST["pass"]))
	{
		$pass = $_POST["pass"];
	}
	else
	{
		$pass = null;
	}
	
	if (isset($_POST["name"]))
	{
		$name = $_POST["name"];
	}
	else
	{
		$name = null;
	}
	
	$mysqli = @new mysqli($host, $user, $pass, $name);
	
	if (mysqli_connect_errno()) 
	{
		header('location: ./index_3.php?error=1');
		exit();
	}
	else
	{
		$config_file = '<?php
	/**
	 * ReadyChat 2.2.0 release
	 * Software by DesignSkate
	 */
	 
	//
	// Please configure the following MySQL settings
	$DB_HOST = "' . $_POST["host"] . '";
	$DB_USER = "' . $_POST["user"] . '";
	$DB_PASS = "' . $_POST["pass"] . '";
	$DB_NAME = "' . $_POST["name"] . '";
	
	//
	// The following encryption keys should not be modified post installation
	$keys["enc_1"] = "' . $_POST["pass_key"] . '";
	$installed = true;
?>';
		
		$write_config = fopen("../core/rc/database.inc.php", "w+");
		fwrite($write_config, $config_file);
		fclose($write_config);
	
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
					<a href="#" class="current"><img src="icons/tick.png" alt="Y" title="Completed"> Configure Settings</a>
					<a href="#"><img src="icons/cross.png" alt="N" title="Incomplete"> Install Tables</a>
					<a href="#"><img src="icons/cross.png" alt="N" title="Incomplete"> Create Your Account</a>
					<a href="#"><img src="icons/cross.png" alt="N" title="Incomplete"> Start Chatting</a>
				</div>
				<div id="content">
					<div class="title">Step 3 - Configure MySQL</div>
					Successfully connected to the MySQL database. We're ready to install the tables now! Click continue to install the tables.
					<br /><br />
					<div id="next">
						<a href="index_5.php">Install Tables</a>
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