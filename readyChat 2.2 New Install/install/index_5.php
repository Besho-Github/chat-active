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
					<a href="#" class="current"><img src="icons/cross.png" alt="N" title="Incomplete"> Install Tables</a>
					<a href="#"><img src="icons/cross.png" alt="N" title="Incomplete"> Create Your Account</a>
					<a href="#"><img src="icons/cross.png" alt="N" title="Incomplete"> Start Chatting</a>
				</div>
				<div id="content">
					<div class="title">Step 4 - Install Tables</div>
					
					<?php
						include("../core/rc/database.inc.php");
						$mysqli = @new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
						
						// install admin table
						$mysqli->query("CREATE TABLE IF NOT EXISTS `admin` (
						  `notes` varchar(1000) NOT NULL,
						  `last_pull` int(20) NOT NULL DEFAULT '0'
						) ENGINE=InnoDB DEFAULT CHARSET=latin1");
						
						// default data
						$mysqli->query("INSERT INTO `admin` (`notes`, `last_pull`) VALUES
						('You can share notes with administrators here.', 0)");		

						// install blacklist
						$mysqli->query("CREATE TABLE IF NOT EXISTS `blacklist` (
						  `blacklist_id` int(4) NOT NULL AUTO_INCREMENT,
						  `blacklist_ip` varchar(30) NOT NULL,
						  PRIMARY KEY (`blacklist_id`)
						) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");
						
						// install rooms
						$mysqli->query("CREATE TABLE IF NOT EXISTS `rooms_permanent` (
						  `room_id` int(3) NOT NULL AUTO_INCREMENT,
						  `room_title` varchar(40) NOT NULL,
						  `room_desc` varchar(500) NOT NULL,
						  `room_limit` int(3) NOT NULL DEFAULT '25',
						  `room_icon` varchar(100) NOT NULL,
						  `room_json` varchar(100) NOT NULL,
						  `room_password` varchar(30) NOT NULL DEFAULT '0',
						  `guest_access` int(1) NOT NULL DEFAULT '1',
						  `room_background` varchar(200) NOT NULL,
						  PRIMARY KEY (`room_id`)
						) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2") or die($mysqli->error);
						
						// default data
						$mysqli->query("INSERT INTO `rooms_permanent` (`room_id`, `room_title`, `room_desc`, `room_limit`, `room_icon`, `room_json`, `room_password`) VALUES
						(1, 'Lobby', 'Welcome to the lobby! Here you can meet and greet your fellow chatters in the default room. Be kind to newcomers and show them the ropes!', 50, '', 'lobby.html', '')") or die($mysqli->error);
						
						// create settings
						$mysqli->query("CREATE TABLE IF NOT EXISTS `settings` (
						  `site_title` varchar(100) NOT NULL,
						  `can_login` int(1) NOT NULL DEFAULT '1',
						  `can_register` int(1) NOT NULL DEFAULT '1',
						  `idle_kick` int(6) NOT NULL DEFAULT '3',
						  `spam` int(6) NOT NULL DEFAULT '1',
						  `spam_exempt` int(1) NOT NULL DEFAULT '1',
						  `kick_exempt` int(1) NOT NULL DEFAULT '0',
						  `full_exempt` int(1) NOT NULL DEFAULT '1',
						  `allow_links` int(1) NOT NULL DEFAULT '1',
						  `login_news` varchar(2000) NOT NULL DEFAULT 'Welcome to our chat room.',
						  `banned_text` varchar(2000) NOT NULL,
						  `offline_mode` int(1) NOT NULL DEFAULT '0',
						  `offline_message` varchar(300) NOT NULL DEFAULT 'Chat is temporarily unavailable due to maintenance.',
						  `allow_profiles` int(1) NOT NULL DEFAULT '1',
						  `max_message` int(4) NOT NULL DEFAULT '300',
						  `auto_poll` int(1) NOT NULL DEFAULT '0',
						  `embedded_profiles` int(1) NOT NULL DEFAULT '1',
						  `avatar_size` int(4) NOT NULL DEFAULT '0',
						  `allow_uploads` int(1) NOT NULL DEFAULT '0',
						  `games` int(1) NOT NULL DEFAULT '0',
						  `allow_guests` int(1) NOT NULL DEFAULT '1',
						  `guest_chat` int(1) NOT NULL DEFAULT '1',
						  `guest_arcade` int(1) NOT NULL DEFAULT '1',
						  `private_messages` int(1) NOT NULL DEFAULT '1',
						  `default_room` int(3) NOT NULL DEFAULT '1',
						  `member_hex` varchar(20) NOT NULL,
						  `guest_hex` varchar(20) NOT NULL,
						  `admin_hex` varchar(20) NOT NULL,
						  `mod_hex` varchar(20) NOT NULL,
						  `template` varchar(100) NOT NULL DEFAULT 'defaultTemplate',
						   UNIQUE KEY `site_title` (`site_title`)
						) ENGINE=InnoDB DEFAULT CHARSET=latin1") or die($mysqli->error);
						
						// default data
						$mysqli->query("INSERT INTO `settings` (`site_title`, `can_login`, `can_register`, `idle_kick`, `spam`, `spam_exempt`, `kick_exempt`, `full_exempt`, `allow_links`, `login_news`, `banned_text`, `offline_mode`, `offline_message`, `allow_profiles`, `max_message`, `auto_poll`, `template`) VALUES
						('readyChat', 1, 1, 10, 1, 0, 0, 1, 1, 'Your new chat room is ready!\r\n', 'You are banned from this website due to violating our rules or because the moderator felt your actions were unacceptable. Your account may be reinstated at the discretion of the website administrator.', 0, 'Chat is temporarily unavailable due to maintenance.', 1, 300, 0, 'defaultTemplate')") or die($mysqli->error);
					
						// create users
						$mysqli->query("CREATE TABLE IF NOT EXISTS `users` (
						  `user_id` int(4) NOT NULL AUTO_INCREMENT,
						  `warned` int(1) NOT NULL DEFAULT '0',
						  `user_name` varchar(35) NOT NULL,
						  `user_password` varchar(300) NOT NULL,
						  `user_email` varchar(100) NOT NULL,
						  `user_joined` int(20) NOT NULL,
						  `user_room` varchar(100) NOT NULL,
						  `last_active` int(20) NOT NULL DEFAULT '0',
						  `last_msg` int(20) NOT NULL DEFAULT '0',
						  `active` int(1) NOT NULL DEFAULT '0',
						  `rank` int(1) NOT NULL DEFAULT '1',
						  `apanel` int(1) NOT NULL DEFAULT '0',
						  `kicked` int(1) NOT NULL DEFAULT '0',
						  `banned` int(1) NOT NULL DEFAULT '0',
						  `reset` int(1) NOT NULL DEFAULT '0',
						  `user_ip` varchar(30) NOT NULL,
						  `warning_text` varchar(300) NOT NULL DEFAULT 'Never Warned',
						  `profile_age` int(3) NOT NULL DEFAULT '0',
						  `profile_sex` int(1) NOT NULL DEFAULT '0',
						  `profile_bio` varchar(300) NOT NULL DEFAULT '0',
						  `profile_avatar` varchar(100) NOT NULL DEFAULT 'no_avatar.jpg',
						  `last_poll` varchar(25) NOT NULL DEFAULT '0',
						  `private_poll` varchar(25) NOT NULL DEFAULT '0',
						  `profile_location` varchar(100) NOT NULL DEFAULT 'Unknown',
						  PRIMARY KEY (`user_id`)
						) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1") or die($mysqli->error);
						
						// create guests
						$mysqli->query("CREATE TABLE IF NOT EXISTS `guests` (
						  `guest_id` int(4) NOT NULL AUTO_INCREMENT,
						  `guest_name` varchar(35) NOT NULL,
						  `guest_room` varchar(100) NOT NULL,
						  `last_msg` int(20) NOT NULL DEFAULT '0',
						  `last_active` int(20) NOT NULL DEFAULT '0',
						  `active` int(1) NOT NULL DEFAULT '1',
						  `guest_ip` varchar(30) NOT NULL,
						  `kicked` int(1) NOT NULL DEFAULT '0',
						  `banned` int(1) NOT NULL DEFAULT '0',
						  `warned` int(1) NOT NULL DEFAULT '0',
						  `warning_text` varchar(500) NOT NULL DEFAULT 'Never Warned',
						  `last_poll` varchar(25) NOT NULL DEFAULT '0',
						  PRIMARY KEY (`guest_id`)
						) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1") or die($mysqli->error);
						
						// create private chats
						$mysqli->query("CREATE TABLE IF NOT EXISTS `private_chats` (
						  `im_id` int(11) NOT NULL AUTO_INCREMENT,
						  `im_to` int(4) NOT NULL,
						  `im_from` int(4) NOT NULL,
						  `im_time` varchar(25) NOT NULL,
						  `im_msg` varchar(800) NOT NULL,
						  `im_status` int(1) NOT NULL DEFAULT '1',
						  PRIMARY KEY (`im_id`)
						) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1") or die($mysqli->error);
						
						// create main chat table
						$mysqli->query("CREATE TABLE IF NOT EXISTS `chat_messages` (
						  `chat_id` int(5) NOT NULL AUTO_INCREMENT,
						  `chat_room` varchar(100) NOT NULL,
						  `chat_user` varchar(100) NOT NULL,
						  `chat_text` varchar(1000) NOT NULL,
						  `chat_time` varchar(30) NOT NULL,
						  `chat_guest` int(1) NOT NULL DEFAULT '0',
						  PRIMARY KEY (`chat_id`)
						) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1") or die($mysqli->error);
						
						// flash games
						$mysqli->query("CREATE TABLE IF NOT EXISTS `flash_games` (
						  `game_id` int(4) NOT NULL AUTO_INCREMENT,
						  `game_title` varchar(35) NOT NULL,
						  `game_swf` varchar(300) NOT NULL,
						  `game_icon` varchar(300) NOT NULL,
						  `width` int(3) NOT NULL,
						  `height` int(3) NOT NULL,
						  `plays` int(4) NOT NULL DEFAULT '0',
						  PRIMARY KEY (`game_id`)
						) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1") or die($mysqli->error);
					?>
					
					readyChat's tables have been installed.
					
					<br /><br />
					<div id="next">
						<a href="index_6.php">Create Your Account</a>
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