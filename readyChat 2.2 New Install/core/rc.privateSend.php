<?php
	/*
		ReadyChat 2.2.0
		Private Chat
	*/
	 
    require_once("rc/config.inc.php");
	require_once("rc/functions.inc.php");
	
	if (!isset($_SESSION["readyChatUser"])){ header('location: login.php'); }
	if (!isset($_POST["token"])){ die("Invalid params."); }

	if ($_POST["token"] == md5($_SESSION["post_key"]))
	{
		if (isset($_POST['content']))
		{
			$cid = rcClean($_POST["cid"]);
			if (trim(htmlspecialchars(strip_tags($_POST["content"]))) == null || trim($cid) == null)
			{
				echo 2;
			}
			else
			{
				if ($GLOBALS["user"]["last_msg"] + $GLOBALS["settings"]["spam"] < time() || $GLOBALS["user"]["rank"] > 1 && $GLOBALS["settings"]["spam_exempt"] == 1 || $GLOBALS["user"]["last_msg"] == 0)
				{
					$query_recip = $GLOBALS["mysqli"]->query("SELECT user_id FROM `users` WHERE `user_id` = '{$cid}'");
					if ($query_recip->num_rows > 0)
					{
						if ($cid != $user["user_id"])
						{
							$content = rcClean($_POST["content"]);
							
							$GLOBALS["mysqli"]->query("INSERT INTO `private_chats` (im_to, im_from, im_time, im_msg) 
																		    VALUES ('{$cid}', '{$GLOBALS["user"]["user_id"]}', '{$GLOBALS["config"]["micro"]}', '{$content}')");
																			
							$GLOBALS["mysqli"]->query("UPDATE `users` SET `last_msg` = '" . time() . "', `last_active` = '" . time() . "' WHERE `user_id` = '{$GLOBALS["user"]["user_id"]}'");
							
							echo "O.K";
						}
						else
						{
							echo 3;
						}
					}
					else
					{
						echo 3;
					}
				}
				else
				{
					echo 3;
				}
			}
		}
		else
		{
			echo 4;
		}
	}
	else
	{
		echo 5;
	}
?>