<?php
	/**
	 * readyChat 2.2.0 release
	 * Software by DesignSkate
	 */
	 
	if (!$user["apanel"]): header('location: ../index.php'); die(); else:
	 
	if (isset($_GET["success"]))
	{
		if ($_GET["success"] == 1)
		{
			$success = "<div class=\"success_head\">The avatar has been kicked.</div>";
		}
	}
	else
	{
		$success = "";
	}
	
	if (isset($_GET["error"]))
	{
		if ($_GET["error"] == 1)
		{
			$error = "<div class=\"error_head\">Unable to find the requested avatar.</div>";
		}
	}
	else
	{
		$error = null;
	}
	
	echo $success, $error;
	
	if (isset($_GET["room"]))
	{
		$query_room = $mysqli->query("SELECT room_id, room_json, room_title FROM `rooms_permanent` WHERE `room_json` = '" . $mysqli->real_escape_string($_GET["room"]) . "'");
		if ($query_room->num_rows > 0)
		{
			$show = true;
			$history_of = $query_room->fetch_assoc();
		}
		else
		{
			$show = false;
		}	
	}
	else
	{
		if (isset($_GET["user"]))
		{
			$query_user = $mysqli->query("SELECT user_id, user_name FROM `users` WHERE `user_id` = '" . $mysqli->real_escape_string($_GET["user"]) . "'");
			if ($query_user->num_rows > 0)
			{
				$show = true;
				$history_of = $query_user->fetch_assoc();
			}
			else
			{
				$show = false;
			}
		}
		else
		{
			$show = false;
		}
	}
?>
	<div id="content">
		<div class="title">
			<img src="template/images/logo.png">
		</div>
		<div class="right_menu" style="float:left; width:920px;">
		
			<?php
				if ($show)
				{
					if (isset($_GET["room"]))
					{		
						$table = "chat_messages";
						$list = "?page=history&room={$history_of["room_json"]}";
						$extra = "WHERE `chat_room` = '{$history_of["room_json"]}'";
						$limit = 10;

						include("../core/paginate.php");

						$query_chats = $mysqli->query("SELECT * FROM `$table` $extra ORDER BY abs(`chat_id`) ASC LIMIT $start, $limit");
						
						echo "
						<div class=\"title2\">
							Chat Room History in {$history_of["room_title"]}
						</div>";
					}
					
					if (isset($_GET["user"]) && !isset($_GET["room"]))
					{
						$table = "chat_messages";
						$list = "?page=history&user={$history_of["user_id"]}";
						$extra = "WHERE `chat_user` = '{$history_of["user_name"]}'";
						$limit = 10;

						include("../core/paginate.php");

						$query_chats = $mysqli->query("SELECT * FROM `$table` $extra ORDER BY abs(`chat_id`) ASC LIMIT $start, $limit");	

						echo "
						<div class=\"title2\">
							Chat Room History from {$history_of["user_name"]}
						</div>";						
					}
					
					if ($query_chats->num_rows > 0)
					{
						echo "
						<table class=\"forum\"\">
							<tr>
								<th style=\"width:100px;\">Chat ID</th>
								<th style=\"width:160px;\">Username</th>
								<th>Message</th>
								<th style=\"width:260px;\">Time</th>
								<th style=\"width:42px;\">Member</th>
							</tr>
						";
						
						while($chat = $query_chats->fetch_assoc())
						{
							if (!$chat["chat_guest"])
							{
								$guest = "<img src=\"template/icons/tick_icon.png\" title=\"Guest Account\">";
							}
							else
							{	
								$guest = "<img src=\"template/icons/cross_icon.png\" title=\"Registered Account\">";
							}
							
							echo "
							<tr>
								<td>{$chat["chat_id"]}</td>
								<td>{$chat["chat_user"]}</td>
								<td>" . $chat["chat_text"] . "</td>
								<td>" . date("l jS F \@ g:i a", $chat["chat_time"] / 1000) . "</td>
								<td style=\"text-align:center;\">{$guest}</td>
							</tr>";
							
							unset($guest);
						}
						
						echo "</table>";
						echo $paginate;
					}
					else
					{
						echo "No chat history to display.";
					}
				}
				else
				{
					echo "
					<div class=\"title2\">
						Chat Room History
					</div>";

					echo "No history to display.";
				}
			?>
		</div>
	</div>
<?php endif; ?>