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
					<a href="#" class="current"><img src="icons/tick.png" alt="Y" title="Completed"> Welcome</a>
					<a href="#"><img src="icons/cross.png" alt="N" title="Incomplete"> Check Permissions</a>
					<a href="#"><img src="icons/cross.png" alt="N" title="Incomplete"> Configure Settings</a>
					<a href="#"><img src="icons/cross.png" alt="N" title="Incomplete"> Install Tables</a>
					<a href="#"><img src="icons/cross.png" alt="N" title="Incomplete"> Create Your Account</a>
					<a href="#"><img src="icons/cross.png" alt="N" title="Incomplete"> Start Chatting</a>
				</div>
				<div id="content">
					<div class="title">readyChat Installer</div>
					Welcome to the readyChat installer. In just a few short moments, you'll be setting up your brand new
					chat website. We hope you find this process as simple as possible, if you're having trouble configuring the files please read our
					<a href="../documents/getting_started.html" target="_blank" style="color:#808080;">Getting Started</a> guide for a step by step guide.
					<br /><br />
					This installer will prompt you to configure files where necessary. You may also be prompted to modify folder permissions if readyChat
					relies on them to provide core features which cannot be disabled.
					<br /><br />
					<div id="next">
						<a href="index_2.php">Check Permissions</a>
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