<?php if (!defined('access')): die("403"); endif; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $GLOBALS["settings"]["site_title"]; ?></title>
	<link rel="stylesheet" type="text/css" href="template/<?php echo $GLOBALS["settings"]["template"]; ?>/css/readyChatLogin.css" />
</head>
<body>
	<div id="container">
		<div id="content">
			<div id="logo">
				<img src="template/<?php echo $GLOBALS["settings"]["template"]; ?>/images/logo.png">
			</div>
			<div id="news_content" style="font-size:13px;">
				<?php echo $GLOBALS["settings"]["banned_text"]; ?>
			</div>
		</div>
		<div id="footer">
			<a href="http://designskate.com/readychat/" target="_blank">Chat Software</a> by DesignSkate
		</div>
	</div>
</body>
</html>