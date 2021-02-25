<?php
	/**
	 * readyChat 2.2.0 release
	 * Software by DesignSkate
	 */
	 
	// Logged in checker
	if (!$user["apanel"]): header('location: ../index.php'); die(); else:
	
	// Create stats
	$total_users = $mysqli->query("SELECT `user_id` FROM `users`");
	$users_online = $mysqli->query("SELECT user_id, active FROM `users` WHERE `active` = '1'");
	$guests_online = $mysqli->query("SELECT guest_id, active FROM `guests` WHERE `active` = '1'");
	$team_online = $mysqli->query("SELECT rank, active FROM `users` WHERE `rank` > 1 AND `active` = '1'");
	$team_total = $mysqli->query("SELECT rank, active FROM `users` WHERE `rank` > 1");
	$total_rooms = $mysqli->query("SELECT room_id FROM `rooms_permanent`");
	$admin = mysqli_fetch_array($mysqli->query("SELECT * FROM `admin`"));
	$lk_version = file_get_contents('./news/version.html', FILE_USE_INCLUDE_PATH);
	
	if (isset($_GET["action"]) && $_GET["action"] == "notes")
	{
		$notes = trim($mysqli->real_escape_string($_POST["notes"]));
		if ($notes != null)
		{
			$mysqli->query("UPDATE `admin` SET `notes` = '{$notes}'") or die($mysqli->error);
			header('location: index.php');
		}
	}
	
	if (isset($_GET["refresh"]) && $_GET["refresh"] == 1)
	{
		$mysqli->query("UPDATE `admin` SET `last_pull` = '0'");
		header('location: index.php?page=home');
	}
?>
	<div id="message_container">
		<div id="message_box">
			<div id="message_header">
				<div id="message_title">
					<img src="template/icons/admin_icon.png" style="float:left; margin-top:2px; margin-right:5px;">
					Update Available
				</div>
				<div id="top_error"></div>
				<a id="close" href="">Close</a>
			</div>
			
			<div id="message_area">
				An update to readyChat may be available from your CodeCanyon customer download page. If this is the case, we highly recommend you
				download and apply the update at your <strong>earliest convenience</strong> to resolve any software bugs and/or security concerns.
				<br /><br />
				Your Version: <strong><?php echo $GLOBALS["config"]["ver_num"]; ?></strong><br />
				Latest Version: <strong><?php echo $lk_version; ?></strong> (<a href="http://designskate.com/news/readychat_version.html" target="_blank">?</a>)
				<br /><br />
				<img src="template/icons/link_icon.png" style="float:left; margin-top:2px; margin-right:5px;"> 
				<a href="http://codecanyon.net/item/readychat/5780613" target="_blank" style="text-decoration:underline;">CodeCanyon Download Page</a>
			</div>
		</div>
	</div>
	
	<div id="content">
		<div class="title">
			<img src="template/images/logo.png">
		</div>
		<div class="right_menu">
			<div class="title2">Shared Admin Notes</div>
			
			<div class="account_options">
				<div class="account_header"></div>
				<div class="account_text">
					<form action="index.php?action=notes" method="post">
						<textarea name="notes" style="height:100px; width:658px; resize:none;" maxlength="500"><?php echo $admin["notes"]; ?></textarea>
						<div class="clear" style="margin-top:10px;"></div>
						<input type="submit" value="Save Notes">
					</form>
				</div>
			</div>			
			<div class="clear"></div>
			<div class="title2">
				readyChat News 
				<span style="float:right; font-size:11px; margin-top:5px;"><a href="?page=home&refresh=1">Refresh Now</a></span>
			</div>
			<div id="news">
			
			<?php
				/****
				* To prevent the constant loading of news from DesignSkate's server
				* which might slow down your site, this cache like system will check for
				* updated news every 2 days.
				****/
				
				if ($admin["last_pull"] > strtotime("-2 days") && $admin["last_pull"] != 0)
				{
					// load cached news
					include("news/news.html");
				}
				else
				{
					// 2 days past pull latest news
					$timeouts = stream_context_create(array(
						'http' => array(
							'timeout' => 2
						)
					));
					
					$news = @file_get_contents('http://designskate.com/news/readychat.html', 0, $timeouts);
					$version = @file_get_contents('http://designskate.com/news/readychat_version.html', 0, $timeouts);
					
					// update news
					if (!empty($news))
					{
						$file = fopen("news/news.html", 'w+');
						fwrite($file, $news);
						fclose($file);
						
						$mysqli->query("UPDATE `admin` SET `last_pull` = '{$time}'");
						echo $news;
					}
					else
					{
						// failed to retrieve latest news document
						include("news/news.html");
					}
					
					// update version
					if (!empty($version))
					{
						$file = fopen("news/version.html", 'w+');
						fwrite($file, $version);
						fclose($file);
					}
				}
				
				// Display last update
				if ($admin["last_pull"] == 0)
				{
					$last_pull = date("jS \of F Y h:i", $time);
				}
				else
				{
					$last_pull = date("jS \of F Y h:i", $admin["last_pull"]);
				}
				
				echo "<br /><br /><em><small>Last checked " . $last_pull . "</small></em>";
			?>		
			
			</div>		
		</div>
		
		<div class="left_menu">
			<a href="index.php"><img src="template/icons/home_icon.png"> Admin Home</a>
			<a href="index.php?page=rooms"><img src="template/icons/manage_icon.png"> Room Management</a>
			<a href="index.php?page=settings"><img src="template/icons/settings_icon.png"> Site Settings</a>
		</div>
		
		<div class="left_menu">
			<a href="#"><img src="template/icons/users_icon.png"> <strong><?php echo $total_users->num_rows; ?></strong> Users</a>
			<a href="#"><img src="template/icons/user_icon.png"> <strong><?php echo $team_total->num_rows; ?></strong> Staff Users</a>
			<a href="#"><img src="template/icons/online_icon.png"> <strong><?php echo $users_online->num_rows; ?></strong> Users Online</a>
			<a href="#"><img src="template/icons/online_icon.png"> <strong><?php echo $guests_online->num_rows; ?></strong> Guests Online</a>
			<a href="#"><img src="template/icons/chat_icon.png"> <strong><?php echo $total_rooms->num_rows; ?></strong> Chat Rooms</a>
		</div>
		
		<div class="left_menu">
			<div style="padding:10px;">
				<?php
					/*
						readyChat Version Information
					*/
					
					$lk_version = file_get_contents('./news/version.html', FILE_USE_INCLUDE_PATH);
					
					if ($lk_version == $GLOBALS["config"]["ver_num"])
					{
						echo "
							<img src=\"template/icons/tick_icon.png\" style=\"float:left; margin-top:1px; margin-right:5px;\"> 
							readyChat is up to date.
						";
					}
					else
					{
						echo "
							<img src=\"template/icons/admin_icon.png\" style=\"float:left; margin-top:1px; margin-right:5px;\"> 
							<div id=\"update\" style=\"text-decoration:underline; cursor:pointer;\">An update is available.</div>
						";
					}
				?>
			</div>
		</div>
	</div>

<?php endif; ?>