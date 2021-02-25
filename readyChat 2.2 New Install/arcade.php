<?php
	/*
		ReadyChat 2.2.0
		Arcade
	*/
	 
	require_once("core/rc/config.inc.php");
	require_once("core/rc/functions.inc.php");
	
	if (!$override && !isset($_SESSION["readyChatUser"]) || isset($_SESSION["readyChatUser"]) && $user["active"] == 0 || !$settings["games"]){ die("You can't do that right now."); }
	
	if ($GLOBALS["gSession"] != null && !$GLOBALS["settings"]["guest_arcade"])
	{
		echo "Please create an account to use the arcade.";
	}
	else
	{
		$query_games = $GLOBALS["mysqli"]->query("SELECT * FROM `flash_games`");
		if ($query_games->num_rows > 0)
		{
			if (isset($_GET["gid"]) && is_numeric($_GET["gid"]))
			{
				$gid = trim($GLOBALS["mysqli"]->real_escape_string($_GET["gid"]));
				$query_gid = $GLOBALS["mysqli"]->query("SELECT * FROM `flash_games` WHERE `game_id` = '{$gid}'");
				
				if ($query_gid->num_rows > 0)
				{	
					$game = $query_gid->fetch_assoc();
					
					echo "
					<html>
					<head>
					<title>{$settings["site_title"]} - {$game["game_title"]}</title>
					</head>
					<body>";
				
					$plays = $game["plays"] + 1;
					echo "<embed width=\"{$game["width"]}\" height=\"{$game["height"]}\" src=\"{$game["game_swf"]}\" type=\"application/x-shockwave-flash\"></embed>";
					$GLOBALS["mysqli"]->query("UPDATE `flash_games` SET `plays` = '{$plays}' WHERE `game_id` = '{$game["game_id"]}'");
					
					echo "
					</body>
					</html>";
				}
				else
				{
					echo "
					<html>
					<head>
					<title>{$GLOBALS["settings"]["site_title"]} - Arcade Error</title>
					</head>
					<body>
						The requested game does not exist.
					</body>
					</html>";
				}
			}
			else
			{
				echo "<div id=\"games\">";
				
				while($game = $query_games->fetch_assoc())
				{
					$width = $game["width"] + 15;
					$height = $game["height"] + 40;
					
					echo "
					<div class=\"game\">
						<div class=\"game_img\"><img src=\"{$game["game_icon"]}\"></div>
						<div class=\"game_title\">{$game["game_title"]}</div>
						<div class=\"game_desc\">
							<div class=\"play\"><a href=\"#play\" onclick=\"url('{$game["game_title"]}', 'arcade.php?gid={$game["game_id"]}', '{$width}', '{$height}')\">Play</a></div>						
						</div>
					</div>";

					unset($width, $height);
				}
				
				echo "</div>";
			}
		}
		else
		{
			echo "There are no games to play.";
		}
	}
	
?> 