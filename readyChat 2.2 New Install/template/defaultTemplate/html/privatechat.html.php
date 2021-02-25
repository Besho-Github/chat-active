<?php if (!defined('access')): die("403"); endif; ?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $GLOBALS["settings"]["site_title"]; ?></title>
	<link rel="stylesheet" type="text/css" href="template/<?php echo $GLOBALS["settings"]["template"]; ?>/css/readyChatPrivate.css" />
</head>
<body>
	<div id="privatechat_bar">
		<strong>Private Chat with <?php echo $GLOBALS["who"] . " " . $info; ?></strong>
		<div id="options" style="float:right;">
			<img id="sfx" style="cursor:pointer;" src="template/<?php echo $GLOBALS["settings"]["template"]; ?>/icons/sfx_on.png" title="Click to disable sound effects">
			<!--<img id="block" style="cursor:pointer; margin-left:10px;" src="template/<?php echo $GLOBALS["settings"]["template"]; ?>/icons/block.png" title="Block this user">-->
		</div>
	</div>
	<div id="container">
		<div id="chat_area" style="width:480px;">
			<ul id="messages" style="height:260px; border:1px solid #eaeaea; width:540px;">
				<li>
					You're now chatting privately with <strong><?php echo $GLOBALS["who"]; ?></strong>
					<div class="underline"></div>
				</li>
			</ul>
		</div>

		<div id="input_area" style="width:540px;">
			<form id="chatform" name="chatform" action="" method="post">
				<input type="text" name="content" id="content" maxlength="<?php echo $GLOBALS["settings"]["max_message"]; ?>"  style="width:413px;" autocomplete="off" />
				<label>
					<input type="hidden" name="name" id="name" value="<?php echo $GLOBALS["user"]["user_name"]; ?>" />
					<input type="hidden" name="token" value="<?php echo $post_key; ?>" />
				</label>
				<button type="submit">Send</button>
			</form>
		</div>
	</div>
</body>
<script type="text/javascript" src="library/jquery.js"></script>
<script type="text/javascript" src="library/3rdparty/emotify.js"></script>
<script type="text/javascript"><?php readyChatConfig("PrivateChat"); ?></script>
<script type="text/javascript" src="core/rc.emoticons.js"></script>
<script type="text/javascript" src="core/rc.language.js"></script>
<script type="text/javascript" src="core/private.js"></script>
</html>