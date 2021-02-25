<?php
	/**
	 * readyChat 2.2.0 release
	 * Software by DesignSkate
	 */
	 
	// Logged in checker
	if (!$user["apanel"]): header('location: ../index.php'); die(); else:

?>
	<div id="content">
		<div class="title">
			<img src="template/images/logo.png">
		</div>
		<div class="right_menu">
			<div class="title2">readyChat Credits</div>
			readyChat is developed by DesignSkate (<a href="http://designskate.com" target="_blank">DesignSkate.com</a>). Appropriate credit for icons used throughout the product
			can be found to the left hand side of this page.
	
			<div class="clear"></div>
			
			<div class="title2">Additional Terms</div>
			readyChat is property of DesignSkate. The front-end copyright notice (Chat Software by DesignSkate) may be removed or altered providing you do not claim the software was created by you.
			Copyright notices on the back-end of the system (administration panel) and in readyChat's source code may not be altered or removed for any reason.
			<br /><br />
			DesignSkate reserves the right to limit or terminate your client account (if activated on <a href="http://clients.designskate.com" target="_blank">clients.designskate.com</a>) if you do
			not follow the Additional Terms outlined above.
			
		</div>
		
		<div class="left_menu">
			<a href="http://designskate.com?ref=credits" target="_blank"><img src="template/icons/link_icon.png"> DesignSkate</a>
			<a href="https://www.iconfinder.com/iconsets/fatcow" target="_blank"><img src="template/icons/link_icon.png"> Farm-fresh icons</a>
			<a href="https://www.iconfinder.com/iconsets/silk2#readme" target="_blank"><img src="template/icons/link_icon.png"> Silk icons</a>
			<a href="https://www.iconfinder.com/iconsets/splashyIcons" target="_blank"><img src="template/icons/link_icon.png"> Splashyfish icons</a>
		</div>
	</div>

<?php endif; ?>