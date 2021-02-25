<?php if (!defined('access')): die("403"); endif; ?>
<!doctype html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $GLOBALS["settings"]["site_title"] . " - " . $profile["user_name"] . "'s Profile"; ?></title>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="template/<?php echo $GLOBALS["settings"]["template"]; ?>/css/readyChatProfiles.css" />
</head>
<body>
	<div class="wrapper">
		<?php if ($profiles): ?>
		<div id="profile_header">
			<span id="online_status"><?php echo $online; ?></span>
			<?php echo $profile["user_name"]; ?>'s Profile
			
			<div id="private_chat"><?php echo $private_chat; ?></div>
		</div>
		<div id="profile_container">
			<div id="profile_left">
				<div id="avatar"><img src="template/avatars/<?php echo $avaurl; ?>"></div>
				<div id="gender"><?php echo $gender; ?></div>
				<?php echo $edit_profile; ?>
			</div>
			<div id="profile_right">
				<div class="title">User Biography</div>
				<div id="bio">
					<?php echo $bio; ?>
				</div>
				<div class="title">User Location</div>
				<div id="location">
					<?php echo $location; ?>
				</div>
			</div>
		</div>
		<?php else: ?>
		<div id="profile_header"></div>
		<div id="profile_container">
			<div style="padding:20px; text-align:center;">
				Profiles have been disabled.
			</div>
		</div>		
		<?php endif; ?>
	</div>
	<div id="footer">
		<a href="http://designskate.com/readychat/" target="_blank">Chat Software</a> by DesignSkate
	</div>
</body>
<script type="text/javascript" src="library/jquery.js"></script>
<script type="text/javascript" src="core/rc.profile.js"></script>
</html>