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
					<a href="#" class="current"><img src="icons/cross.png" alt="Y" title="Completed"> Check Permissions</a>
					<a href="#"><img src="icons/cross.png" alt="N" title="Incomplete"> Configure Settings</a>
					<a href="#"><img src="icons/cross.png" alt="N" title="Incomplete"> Install Tables</a>
					<a href="#"><img src="icons/cross.png" alt="N" title="Incomplete"> Create Your Account</a>
					<a href="#"><img src="icons/cross.png" alt="N" title="Incomplete"> Start Chatting</a>
				</div>
				<div id="content">
					<div class="title">Step 2 - Check Permissions</div>

					<?php
					error_reporting(0);
					$s = array();
					
					if (function_exists('mysqli_connect')) 
					{
						echo "<div class='item'><strong>MySQLi Support</strong> <span style='color:green;'>is OK!</span></div>";
						$s[2] = true;
					}
					else
					{
						echo "<div class='item'><strong>MySQLi Support</strong> <span style='color:red;'>The MySQLi extension must be enabled.</span></div>";
					}
					
					if(is_dir("../admin/news/") && is_writable("../admin/news/"))
					{
						echo "<div class='item'><strong>../admin/news/</strong> <span style='color:green;'>is OK!</span></div>";
						$s[3] = true;
					}
					else
					{
						echo "<div class='item'><strong>../admin/news/</strong> <span style='color:red;'>must be writable.</span></div>";
					}
					
					if(is_dir("../core/") && is_writable("../core/"))
					{
						echo "<div class='item'><strong>../core/</strong> <span style='color:green;'>is OK!</span></div>";
						$s[4] = true;
					}
					else
					{
						echo "<div class='item'><strong>../core/</strong> <span style='color:red;'>must be writable.</span></div>";
					}
					
					if(is_dir("../template/avatars/uploads/") && is_writable("../template/avatars/uploads/"))
					{
						echo "<div class='item'><strong>../template/avatars/uploads/</strong> <span style='color:green;'>is OK!</span></div>";
						$s[5] = true;
					}
					else
					{
						echo "<div class='item'><strong>../template/avatars/uploads/</strong> <span style='color:red;'>must be writable.</span></div>";
					}
					
					?>
					
					<br />
					<div id="next">
						<?php if ($s[2] && $s[3] && $s[4] && $s[5]): echo "<a href=\"index_3.php\">Configure Settings</a>"; else: echo "Please correct the above errors to continue."; endif; ?>
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