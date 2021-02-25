<?php
	/**
	 * readyChat 2.2.0 release
	 * Software by DesignSkate
	 */
	 
	require_once("../core/rc/config.inc.php");
	
	// Logged in checker
	if (!$user["apanel"]): header('location: ../index.php'); die(); else:
	
	if ($settings["offline_mode"])
	{
		$offline_notice = "<div id=\"offline\">Chat is currently in offline mode - <a href=\"?page=settings\">disable</a></div>";
	}
	else
	{
		$offline_notice = "";
	}
	
	// Pages
	$home = ""; $rooms = ""; $users = ""; $games = ""; $settingsp = "";
	if (isset($_GET["page"]))
	{
		if ($_GET["page"] == "home")
		{
			$home = " class=\"active\"";
		}
		elseif ($_GET["page"] == "rooms")
		{
			$rooms = " class=\"active\"";
		}
		elseif ($_GET["page"] == "users")
		{
			$users = " class=\"active\"";
		}
		elseif ($_GET["page"] == "games")
		{
			$games = " class=\"active\"";
		}
		elseif ($_GET["page"] == "settings")
		{
			$settingsp = " class=\"active\"";
		}
	}
	else
	{
		$home = "class=\"active\"";
	}
?> 
<!doctype html>
<html>
    <head>
	    <title>readyChat Admin</title>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="template/style.css" />
	</head>
<body>
	<?php echo $offline_notice; ?>
	<div id="dimmer"></div>
	<div id="menu">
		<div class="wrap">
			<span style="float:left;">
				<a href="index.php?page=home"<?php echo $home; ?>>Admin Home</a>
				<a href="index.php?page=rooms"<?php echo $rooms; ?>>Room Management</a>
				<a href="index.php?page=users"<?php echo $users; ?>>User Management</a>
				<a href="index.php?page=games"<?php echo $games; ?>>Flash Games</a>
				<a href="index.php?page=settings"<?php echo $settingsp; ?>>Site Settings</a>
			</span>
			<span style="float:right;">
				<a href="../index.php" target="_blank">Return to site</a>
			</span>
		</div>
	</div>
	<div class="wrap">

		<?php 
			// Include admin panel pages
			if (isset($_GET["page"])):
				if ($_GET["page"] == "home"): include("pages/dashboard.php"); endif;
				if ($_GET["page"] == "rooms"): include("pages/rooms.php"); endif;
				if ($_GET["page"] == "settings"): include("pages/settings.php"); endif;
				if ($_GET["page"] == "users"): include("pages/users.php"); endif;
				if ($_GET["page"] == "games"): include("pages/games.php"); endif;
				if ($_GET["page"] == "history"): include("pages/history.php"); endif;
				
				/******
				* The following page should not be removed under any circumstances
				******/
				if ($_GET["page"] == "credits"): include("pages/credits.php"); endif;
				
			else:
				include("pages/dashboard.php");
			endif;
		?>		
		
	</div>
	<div class="wrap">
		<!--
			REMOVING THE FOLLOWING IS PROHIBITED.
			readyChat's copyright notice must remain intact on the administration side of the system.
			This software (readyChat) is property of DesignSkate.
		-->
		<div id="footer">
			<span style="float:left;">Powered by readyChat version <?php echo $GLOBALS["config"]["ver_num"]; ?></span>
			<span style="float:right;"><a href="index.php?page=credits">Credits</a></span>
		</div>
	</div>
</body>
<script type="text/javascript" src="../library/jquery.js"></script>
<script type="text/javascript" src="adminjs.js"></script>
</html>
<?php endif; ?>
