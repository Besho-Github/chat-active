<?php if (!file_exists("install.txt")){ die("Installer locked."); } ?>
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
					<a href="#" class="current"><img src="icons/cross.png" alt="N" title="Incomplete"> Create Your Account</a>
					<a href="#"><img src="icons/cross.png" alt="N" title="Incomplete"> Start Chatting</a>
				</div>
				<div id="content">
					<div class="title">Step 5 - Create Your Account</div>

					<?php 
						if (isset($_GET["error"]) && $_GET["error"] == 1){ echo "<div class=\"error\">Please do not leave any fields blank.</div>"; } 
						if (isset($_GET["error"]) && $_GET["error"] == 2){ echo "<div class=\"error\">Usernames can only contain letters and numbers.</div>"; } 
					?>
					
					<div class="box">
						<form action="index_7.php" method="post" autocomplete="off">
							Admin Username<br />
							<span style="font-size:11px;">Your main admin username.</span>
							<div class="clear"></div>
							<input type="text" name="user" size="35" style="width:300px;">
							<br /><br />
							Admin Password<br />
							<span style="font-size:11px;">Your admin password.</span>
							<div class="clear"></div>
							<input type="password" name="pass" size="40" style="width:300px;">
							<br /><br />
							<input type="submit" class="submit" value="Continue">
						</form>
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