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
					<a href="#" class="current"><img src="icons/cross.png" alt="N" title="Incomplete"> Configure Settings</a>
					<a href="#"><img src="icons/cross.png" alt="N" title="Incomplete"> Install Tables</a>
					<a href="#"><img src="icons/cross.png" alt="N" title="Incomplete"> Create Your Account</a>
					<a href="#"><img src="icons/cross.png" alt="N" title="Incomplete"> Start Chatting</a>
				</div>
				<div id="content">
					<div class="title">Step 3 - Configure MySQL</div>

					<?php if (isset($_GET["error"])){ echo "<div class=\"error\">Error accessing the MySQL database.</div>"; } ?>
					
					<div class="box">
						<form action="index_4.php" method="post">
							Database Host<br />
							<span style="font-size:11px;">Commonly localhost, external MySQL servers may differ.</span>
							<div class="clear"></div>
							<input type="text" name="host" size="40" value="localhost">
							<br /><br />
							Database Username<br />
							<span style="font-size:11px;">The username you configured for your MySQL database.</span>
							<div class="clear"></div>
							<input type="text" name="user" size="40">
							<br /><br />
							Database Password<br />
							<span style="font-size:11px;">The password you configured for your MySQL database.</span>
							<div class="clear"></div>
							<input type="text" name="pass" size="40">
							<br /><br />
							Database Name<br />
							<span style="font-size:11px;">The name you configured for your MySQL database.</span>
							<div class="clear"></div>
							<input type="text" name="name" size="40">
							<br /><br />
							Password Key<br />
							<span style="font-size:11px;">A unique and random key to encrypt user passwords.</span>
							<div class="clear"></div>
							<input type="text" name="pass_key" size="40">
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