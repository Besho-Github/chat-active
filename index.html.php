<?php if (!defined('access')): die("403"); endif; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<title><?php echo $GLOBALS["settings"]["site_title"]; ?></title>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="template/<?php echo $GLOBALS["settings"]["template"]; ?>/css/readyChatMain.css" />
	<style>
		input:disabled {
			background:#f8f8f8;
		}
	</style>
</head>
<body>
	<div class="progress-indicator"><img src="template/<?php echo $GLOBALS["settings"]["template"]; ?>/images/loading.gif" title="" alt=""></div>

	<!-- Background Dimmer -->
	<div id="dimmer"></div>

	<div id="message_container">
		<div id="message_box">
			<div id="message_header">
				<div id="message_title">Alert!</div>
				<div id="top_error"></div>
				<a id="close" href="">Continue</a>
			</div>
			
			<!-- Room requires a password -->
			<div id="message_area">This is a message box</div>
			<div id="box_entry">
				<input id="rpw" type="text" name="pwinput" placeholder="Enter Room Password">
				<div class="room" id="rtj" room_link="none">Enter Room</div>
			</div>
		</div>
	</div>

	<!-- Content Box -->
	<div id="contentbox_container">
		<div id="contentbox">
			<div id="contentbox_header">
				<div id="content_title">Default Title</div>
				<a id="close_profile" href="">Continue</a>
				<?php if ($GLOBALS["settings"]["private_messages"]): ?><div id="private_chat"></div><?php endif; ?>
			</div>
			<div id="contentbox_html"></div>
		</div>
	</div>

	<!-- Smiley Box -->
	<div id="smiley_container">
		<div id="smiley_box">
			<div id="message_header">
				<div id="message_title">Insert a smiley</div>
				<a id="close_smilies" href="">Continue</a>
			</div>
			<div id="smiley_area"></div>
		</div>
	</div>
	
	<div id="wrapper">
		<div id="top">
			<img src="template/<?php echo $GLOBALS["settings"]["template"]; ?>/images/logo.png">
		</div>
		<div id="upperPanel">
			<ul id="messages">
				<div id="topic_text"><img src="template/<?php echo $GLOBALS["settings"]["template"]; ?>/images/loading_small_2.gif"></div>
			</ul>
			
			<div id="user_list">
				
			</div>
		</div>
		<div id="bottomPanel">
			<div id="chatform">
				<form name="chatform" action="" method="post">
					<?php
						/*
							If this user is a guest and the administrator has disabled guest chat, show a message in the input box!
						*/
						
						if (!$settings["guest_chat"] && $gSession)
						{
							$gc = "placeholder=\"Become a registered member to chat!\" DISABLED";
						}
						else
						{
							$gc = null;
						}
					?>
					
					<!-- Input Text Area -->
					<div id="input">
						<input type="text" name="content" maxlength="<?php echo $settings["max_message"]; ?>" autocomplete="off" placeholder="Type your message here..."<?php echo $gc; ?> />
					</div>
					
					<?php
						/*
							If this user has moderator/admin permissions, they require a secondary authentication key
							to prevent CSRF.
						*/
						
						if (!$gSession && $user["rank"] > 1)
						{
							echo "<input type=\"hidden\" name=\"admin_token\" value=\"" . md5($_SESSION["admin_key"]) . "\" />";				
						}
					?>
					
					<!-- Authentication Token -->
					<input type="hidden" name="token" value="<?php echo $post_key; ?>" />
					
					<?php 
						/*
							The following values are place holders to improve the feel of speed.
							Values cannot be changed to manipulate the chat system.
						*/
						
						if (!$gSession)
						{
							echo "<input type=\"hidden\" name=\"name\" id=\"name\" value=\"{$GLOBALS["user"]["user_name"]}\" />";
						}
						else
						{
							echo "<input type=\"hidden\" name=\"name\" id=\"name\" value=\"(Guest) {$GLOBALS["guest"]["guest_name"]}\" />";
						}
					?>
					
					<button id="submit" type="submit">Send</button>
				</form>
			</div>
		</div>
	</div>
</body>
<!-- ReadyChat Required JavaScript -->
<script type="text/javascript" src="library/jquery.js"></script>
<script type="text/javascript" src="library/3rdparty/emotify.js"></script>
<script type="text/javascript"><?php readyChatConfig("JavaScript"); ?></script>
<script type="text/javascript" src="core/rc.emoticons.js"></script>
<script type="text/javascript" src="core/rc.language.js"></script>
<script type="text/javascript" src="core/readyChat.js"></script>
</html>