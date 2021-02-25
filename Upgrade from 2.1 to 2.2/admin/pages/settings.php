<?php
	/**
	 * readyChat 2.2.0 release
	 * Software by DesignSkate
	 */
	 
	// Logged in checker
	if (!$user["apanel"]): header('location: ../index.php'); die(); else:
	
	if (isset($_GET["success"]) && $_GET["success"] == 1)
	{
		$success = "<div class=\"success_head\">Settings updated.</div>";
	}
	else
	{
		$success = "";
	}
	
	if (isset($_GET["error"]) && $_GET["error"] == 1)
	{
		$error = "<div class=\"error_head\">Fields should not be blank.</div>";
	}
	elseif (isset($_GET["error"]) && $_GET["error"] == 2)
	{
		$error = "<div class=\"error_head\">Fields should be numeric.</div>";
	}
	else
	{
		$error = "";
	}
	
	echo $error;
	echo $success;
?>
	<div id="content">
		<div class="title">
			<img src="template/images/logo.png">
		</div>
		<div class="right_menu">

		<?php
		if (isset($_GET["sub"]) && $_GET["sub"] == "games")
		{
			if (isset($_GET["update"]) && $_GET["update"] == 1)
			{
				if (isset($_POST["token"]) && $_POST["token"] == md5($_SESSION["settings_token_games"]))
				{
					$games = trim($mysqli->real_escape_string($_POST["games"]));
	
					if (is_numeric($games))
					{
						// Update settings
						$mysqli->query("UPDATE `settings` SET `games` = '{$games}'");
						
						header('location: index.php?page=settings&sub=games&success=1');
					}
					else
					{
						header('location: index.php?page=settings&sub=games&error=2');
					}			
				}
				else
				{
					echo "
					<div class=\"box\">
						<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
						Invalid submission token receieved, please try again.
					</div>";					
				}
			}
			else
			{
				// update token
				if (!isset($_SESSION["settings_token_games"]))
				{
					$_SESSION["settings_token_games"] = sha1(rand(128, 5000));
				}
				
				echo "<div class=\"title2\">Flash Game Settings</div>";
				echo "
				<div class=\"editor\">
					<form action=\"index.php?page=settings&sub=games&update=1\" method=\"post\">
						<strong>Flash Games</strong><br />
						<span style=\"font-size:12px;\">Enable the ability for users to play flash games.</span>
						<div class=\"clear\" style=\"margin-top:5px;\"></div>
						<select name=\"games\">";
						
						if ($settings["games"])
						{
							echo "
								<option value=\"1\">Enable flash games.</option>
								<option value=\"0\">Disable flash games.</option>
							";
						}
						else
						{
							echo "
								<option value=\"0\">Disable flash games.</option>
								<option value=\"1\">Enable flash games.</option>
							";					
						}
						
						echo "
						</select>
						<div class=\"clear\"></div>
						
						<input type=\"hidden\" name=\"token\" value=\"" . md5($_SESSION["settings_token_games"]) . "\">
						<input type=\"submit\" style=\"min-width:100px; border-radius:5px;\" value=\"Save Settings\">
					</form>
				</div>";
			}
		}
		elseif (isset($_GET["sub"]) && $_GET["sub"] == "members")
		{
			if (isset($_GET["update"]) && $_GET["update"] == 1)
			{
				if (isset($_POST["token"]) && $_POST["token"] == md5($_SESSION["settings_token_1"]))
				{
					$idle = trim($mysqli->real_escape_string($_POST["idle"]));
					$e_kick = trim($mysqli->real_escape_string($_POST["exempt_kick"]));
					$register = trim($mysqli->real_escape_string($_POST["register"]));
					$private = trim($mysqli->real_escape_string($_POST["private"]));
		
					if ($idle == null || $e_kick == null)
					{
						header('location: index.php?page=settings&sub=members&error=1');
					}
					else
					{
						if (is_numeric($idle) && is_numeric($register) && is_numeric($private))
						{
							// Update settings
							$mysqli->query("UPDATE `settings` SET `kick_exempt` = '{$e_kick}', `idle_kick` = '{$idle}', `can_register` = '{$register}', `private_messages` = '{$private}'");
							
							header('location: index.php?page=settings&sub=members&success=1');
						}
						else
						{
							header('location: index.php?page=settings&sub=members&error=2');
						}
					}
				}
				else
				{
					echo "
					<div class=\"box\">
						<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
						Invalid submission token receieved, please try again.
					</div>";								
				}
			}
			else
			{
				// update token
				if (!isset($_SESSION["settings_token_1"]))
				{
					$_SESSION["settings_token_1"] = sha1(rand(128, 5000));
				}
				
				echo "<div class=\"title2\">Member Settings</div>";
				echo "
				<div class=\"editor\">
					<form action=\"index.php?page=settings&sub=members&update=1\" method=\"post\">
						<strong>Idle Kicker</strong><br />
						<span style=\"font-size:12px;\">How long (in minutes) before a user is disconnected due to be becoming idle.</span>
						<div class=\"clear\" style=\"margin-top:5px;\"></div>
						<input type=\"text\" maxlength=\"6\" name=\"idle\" value=\"{$settings["idle_kick"]}\">
						<div class=\"clear\"></div>
						
						<strong>Moderators are exempt from <em>Idle Kicker</em></strong><br />
						<span style=\"font-size:12px;\">Should moderators & administrators be exempt from the idle kicker?</span>
						<div class=\"clear\" style=\"margin-top:5px;\"></div>
						<select name=\"exempt_kick\">";
						
						if ($settings["kick_exempt"])
						{
							echo "
								<option value=\"1\">Moderators are exempt from the kick.</option>
								<option value=\"0\">Moderators are included in the kick.</option>
							";
						}
						else
						{
							echo "
								<option value=\"0\">Moderators are included in the kick.</option>
								<option value=\"1\">Moderators are exempt from the kick.</option>
							";					
						}
						
						echo "
						</select>
						<div class=\"clear\"></div>
						
						<strong>Open Registration</strong><br />
						<span style=\"font-size:12px;\">Allow new members to register?</span>
						<div class=\"clear\" style=\"margin-top:5px;\"></div>
						<select name=\"register\">";
						
						if ($settings["can_register"])
						{
							echo "
								<option value=\"1\">Allow new members to register.</option>
								<option value=\"0\">Do not allow new members to register.</option>
							";
						}
						else
						{
							echo "
								<option value=\"0\">Do not allow new members to register.</option>
								<option value=\"1\">Allow new members to register.</option>
							";					
						}
						
						echo "
						</select>
						<div class=\"clear\"></div>
						
						<strong>Private Messaging</strong><br />
						<span style=\"font-size:12px;\">Allow members to send each other private messages?</span>
						<div class=\"clear\" style=\"margin-top:5px;\"></div>
						<select name=\"private\">";
						
						if ($settings["private_messages"])
						{
							echo "
								<option value=\"1\">Enable Private Messages</option>
								<option value=\"0\">Disable Private Messages</option>
							";
						}
						else
						{
							echo "
								<option value=\"0\">Disable Private Messages</option>
								<option value=\"1\">Enable Private Messages</option>
							";					
						}
						
						echo "
						</select>
						<div class=\"clear\"></div>
						
						<input type=\"hidden\" name=\"token\" value=\"" . md5($_SESSION["settings_token_1"]) . "\">
						<input type=\"submit\" style=\"min-width:100px; border-radius:5px;\" value=\"Save Settings\">
					</form>
				</div>";
			}
		}
		elseif (isset($_GET["sub"]) && $_GET["sub"] == "guests")
		{
			if (isset($_GET["update"]) && $_GET["update"] == 1)
			{
				if (isset($_POST["token"]) && $_POST["token"] == md5($_SESSION["guests_token"]))
				{
					$aguests = trim($mysqli->real_escape_string($_POST["aguests"]));
					$gchat = trim($mysqli->real_escape_string($_POST["gchat"]));
					$garcade = trim($mysqli->real_escape_string($_POST["garcade"]));
		
					if (is_numeric($aguests) && is_numeric($gchat) && is_numeric($garcade))
					{
						// Update settings
						$mysqli->query("UPDATE `settings` SET `allow_guests` = '{$aguests}', `guest_chat` = '{$gchat}', `guest_arcade` = '{$garcade}'");
						
						header('location: index.php?page=settings&sub=guests&success=1');
					}
					else
					{
						header('location: index.php?page=settings&sub=guests&error=2');
					}
				}
				else
				{
					echo "
					<div class=\"box\">
						<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
						Invalid submission token receieved, please try again.
					</div>";								
				}
			}
			else
			{
				// update token
				if (!isset($_SESSION["guests_token"]))
				{
					$_SESSION["guests_token"] = sha1(rand(128, 5000));
				}
				
				echo "<div class=\"title2\">Guest Settings</div>";
				echo "
				<div class=\"editor\">
					<form action=\"index.php?page=settings&sub=guests&update=1\" method=\"post\">
						<strong>Enable Guests</strong><br />
						<span style=\"font-size:12px;\">If enabled, users will be able to access the chat without an account.</span>
						<div class=\"clear\" style=\"margin-top:5px;\"></div>
						<select name=\"aguests\">";
						
						if ($settings["allow_guests"])
						{
							echo "
								<option value=\"1\">Enable guest access.</option>
								<option value=\"0\">Disable guest access.</option>
							";
						}
						else
						{
							echo "
								<option value=\"0\">Disable guest access.</option>
								<option value=\"1\">Enable guest access.</option>
							";					
						}
						
						echo "
						</select>
						<div class=\"clear\"></div>
						
						<strong>Guest Chat</strong><br />
						<span style=\"font-size:12px;\">Should guests be able to chat or only read the conversation?</span>
						<div class=\"clear\" style=\"margin-top:5px;\"></div>
						<select name=\"gchat\">";
						
						if ($settings["guest_chat"])
						{
							echo "
								<option value=\"1\">Enable guest chat.</option>
								<option value=\"0\">Disable guest chat.</option>
							";
						}
						else
						{
							echo "
								<option value=\"0\">Disable guest chat.</option>
								<option value=\"1\">Enable guest chat.</option>
							";					
						}
						
						echo "
						</select>
						<div class=\"clear\"></div>
						
						<strong>Guest Arcade Access</strong><br />
						<span style=\"font-size:12px;\">Should guests be able to use the chat arcade?</span>
						<div class=\"clear\" style=\"margin-top:5px;\"></div>
						<select name=\"garcade\">";
						
						if ($settings["guest_arcade"])
						{
							echo "
								<option value=\"1\">Enable guest arcade.</option>
								<option value=\"0\">Disable guest arcade.</option>
							";
						}
						else
						{
							echo "
								<option value=\"0\">Disable guest arcade.</option>
								<option value=\"1\">Enable guest arcade.</option>
							";					
						}
						
						echo "
						</select>
						<div class=\"clear\"></div>
						
						<input type=\"hidden\" name=\"token\" value=\"" . md5($_SESSION["guests_token"]) . "\">
						<input type=\"submit\" style=\"min-width:100px; border-radius:5px;\" value=\"Save Settings\">
					</form>
				</div>";
			}
		}
		elseif (isset($_GET["sub"]) && $_GET["sub"] == "profiles")
		{
			if (isset($_GET["update"]) && $_GET["update"] == 1)
			{
				if (isset($_POST["token"]) && $_POST["token"] == md5($_SESSION["settings_token_2"]))
				{
					$profiles = trim($mysqli->real_escape_string($_POST["profiles"]));
					$embed = trim($mysqli->real_escape_string($_POST["embedded_profiles"]));
					$uploads = trim($mysqli->real_escape_string($_POST["uploads"]));
					$size = trim($mysqli->real_escape_string($_POST["size"]));
					
					if (is_numeric($profiles) && is_numeric($embed) && is_numeric($uploads) && is_numeric($size))
					{
						// Update settings
						$mysqli->query("UPDATE `settings` SET `allow_profiles` = '{$profiles}', `embedded_profiles` = '{$embed}', `allow_uploads` = '{$uploads}', `avatar_size` = '{$size}'");
						
						header('location: index.php?page=settings&sub=profiles&success=1');
					}
					else
					{
						header('location: index.php?page=settings&sub=profiles&error=2');
					}
				}
				else
				{
					echo "
					<div class=\"box\">
						<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
						Invalid submission token receieved, please try again.
					</div>";								
				}
			}
			else
			{
				// update token
				if (!isset($_SESSION["settings_token_2"]))
				{
					$_SESSION["settings_token_2"] = sha1(rand(128, 5000));
				}
				
				echo "<div class=\"title2\">Profile Settings</div>";
				echo "
				<div class=\"editor\">
					<form action=\"index.php?page=settings&sub=profiles&update=1\" method=\"post\">
						<strong>Enable Profiles</strong><br />
						<span style=\"font-size:12px;\">If enabled, users will be able to see member profiles.</span>
						<div class=\"clear\" style=\"margin-top:5px;\"></div>
						<select name=\"profiles\">";
						if ($settings["allow_profiles"])
						{
							echo "
								<option value=\"1\">Enable profiles.</option>
								<option value=\"0\">Disable profiles.</option>
							";
						}
						else
						{
							echo "
								<option value=\"0\">Disable profiles.</option>
								<option value=\"1\">Enable profiles.</option>
							";					
						}
						
						echo "
						</select>
						<div class=\"clear\"></div>
						
						<strong>Use Embedded Profiles</strong><br />
						<span style=\"font-size:12px;\">If enabled, profiles will be viewed in-chat instead of a new window.</span>
						<div class=\"clear\" style=\"margin-top:5px;\"></div>
						<select name=\"embedded_profiles\">";
						if ($settings["embedded_profiles"])
						{
							echo "
								<option value=\"1\">Enable embedded profiles.</option>
								<option value=\"0\">Disable embedded profiles.</option>
							";
						}
						else
						{
							echo "
								<option value=\"0\">Disable embedded profiles.</option>
								<option value=\"1\">Enable embedded profiles.</option>
							";					
						}
						
						echo "
						</select>
						<div class=\"clear\"></div>
						
						<strong>Allow Avatar Uploads</strong><br />
						<span style=\"font-size:12px;\">Please ensure `template/avatars/uploads` is writable before enabling.</span>
						<div class=\"clear\" style=\"margin-top:5px;\"></div>
						<select name=\"uploads\">";
						if ($settings["allow_uploads"])
						{
							echo "
								<option value=\"1\">Allow avatar uploads.</option>
								<option value=\"0\">Do not allow avatar uploads</option>
							";
						}
						else
						{
							echo "
								<option value=\"0\">Do not allow avatar uploads</option>
								<option value=\"1\">Allow avatar uploads.</option>
							";					
						}
						
						echo "
						</select>
						<div class=\"clear\"></div>
						
						<strong>Maximum Avatar Size</strong><br />
						<span style=\"font-size:12px;\">The maximum allowed avatar sizes in KB.</span>
						<div class=\"clear\" style=\"margin-top:5px;\"></div>
						<input type=\"text\" maxlength=\"40\" name=\"size\" value=\"{$settings["avatar_size"]}\">
						<div class=\"clear\"></div>
						
						<input type=\"hidden\" name=\"token\" value=\"" . md5($_SESSION["settings_token_2"]) . "\">
						<input type=\"submit\" style=\"min-width:100px; border-radius:5px;\" value=\"Save Settings\">
					</form>
				</div>";
			}
		}
		elseif (isset($_GET["sub"]) && $_GET["sub"] == "chat")
		{
			if (isset($_GET["update"]) && $_GET["update"] == 1)
			{
				if (isset($_POST["token"]) && $_POST["token"] == md5($_SESSION["settings_token_3"]))
				{
					$messages = trim($mysqli->real_escape_string($_POST["messages"]));
					$spam = trim($mysqli->real_escape_string($_POST["spam"]));
					$e_spam = trim($mysqli->real_escape_string($_POST["exempt_spam"]));
					$e_full = trim($mysqli->real_escape_string($_POST["exempt_full"]));
					$links = trim($mysqli->real_escape_string($_POST["links"]));
					$auto_poll = trim($mysqli->real_escape_string($_POST["auto_poll"]));
					$default_room = trim($mysqli->real_escape_string($_POST["default_room"]));
					
					if ($messages == null || $spam == null && $spam != 0 || $e_spam == null || $e_full == null || $links == null  || $default_room == "-1")
					{
						header('location: index.php?page=settings&sub=chat&error=1');
					}
					else
					{
						if (is_numeric($spam))
						{
							// Update settings
							$mysqli->query("UPDATE `settings` SET `spam` = '{$spam}', `spam_exempt` = '{$e_spam}', `full_exempt` = '{$e_full}', `allow_links` = '{$links}', `max_message` = '{$messages}', `auto_poll` = '{$auto_poll}', `default_room` = '{$default_room}'");
							
							header('location: index.php?page=settings&sub=chat&success=1');
						}
						else
						{
							header('location: index.php?page=settings&sub=chat&error=2');
						}
					}
				}
				else
				{
					echo "
					<div class=\"box\">
						<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
						Invalid submission token receieved, please try again.
					</div>";								
				}
			}
			else
			{
				// update token
				if (!isset($_SESSION["settings_token_3"]))
				{
					$_SESSION["settings_token_3"] = sha1(rand(128, 5000));
				}
				
				echo "<div class=\"title2\">Chat Settings</div>";
				echo "
				<div class=\"editor\">
					<form action=\"index.php?page=settings&sub=chat&update=1\" method=\"post\">
						<strong>Default room</strong><br />
						<span style=\"font-size:12px;\">Choose the default room members and/or guests should be placed in.</span>
						<div class=\"clear\" style=\"margin-top:5px;\"></div>
						<select name=\"default_room\">";
						
						$query_room = $mysqli->query("SELECT room_id, room_title FROM `rooms_permanent` WHERE `room_id` = '{$settings["default_room"]}'");
						if ($query_room->num_rows > 0)
						{
							$room = $query_room->fetch_assoc();
							echo "<option value=\"{$room["room_id"]}\">{$room["room_title"]}</option>";
						}
						else
						{
							echo "<option value=\"-1\">--</option>";
						}
						
						$query_rooms = $mysqli->query("SELECT room_id, room_title FROM `rooms_permanent` WHERE `room_id` != '{$settings["default_room"]}'");
						while($room = $query_rooms->fetch_array(MYSQLI_BOTH))
						{
							echo "<option value=\"{$room["room_id"]}\">{$room["room_title"]}</option>";
						}	
						
						echo "
						</select>
						<div class=\"clear\"></div>
						
						<strong>Maximum Message Length</strong><br />
						<span style=\"font-size:12px;\">How many characters should chat messages be limited to?</span>
						<div class=\"clear\" style=\"margin-top:5px;\"></div>
						<input type=\"text\" maxlength=\"4\" name=\"messages\" value=\"{$settings["max_message"]}\">
						<div class=\"clear\"></div>
						
						<strong>Auto Poll for new messages</strong><br />
						<span style=\"font-size:12px;\">This might make the chat appear to be snappier, though will increase bandwidth usage.</span>
						<div class=\"clear\" style=\"margin-top:5px;\"></div>
						<select name=\"auto_poll\">";
						
						if ($settings["auto_poll"])
						{
							echo "
								<option value=\"1\">Enable Auto Poll</option>
								<option value=\"0\">Disable Auto Poll</option>
							";
						}
						else
						{
							echo "
								<option value=\"0\">Disable Auto Poll</option>
								<option value=\"1\">Enable Auto Poll</option>
							";					
						}
						
						echo "
						</select>
						<div class=\"clear\"></div>
						
						<strong>Spam Prevention</strong><br />
						<span style=\"font-size:12px;\">How long (in seconds) before a user can send another message?</span>
						<div class=\"clear\" style=\"margin-top:5px;\"></div>
						<input type=\"text\" maxlength=\"6\" name=\"spam\" value=\"{$settings["spam"]}\">
						<div class=\"clear\"></div>
						
						<strong>Moderators are exempt from <em>Spam Prevention</em></strong><br />
						<span style=\"font-size:12px;\">Should moderators & administrators be exempt from the spam timer?</span>
						<div class=\"clear\" style=\"margin-top:5px;\"></div>
						<select name=\"exempt_spam\">";
						
						if ($settings["spam_exempt"])
						{
							echo "
								<option value=\"1\">Moderators are exempt from the timer.</option>
								<option value=\"0\">Moderators are included in the timer.</option>
							";
						}
						else
						{
							echo "
								<option value=\"0\">Moderators are included in the timer.</option>
								<option value=\"1\">Moderators are exempt from the timer.</option>
							";					
						}
						
						echo "
						</select>
						<div class=\"clear\"></div>
						
						<strong>Moderators can enter full rooms</strong><br />
						<span style=\"font-size:12px;\">Should moderators & administrators be able to enter full rooms?</span>
						<div class=\"clear\" style=\"margin-top:5px;\"></div>
						<select name=\"exempt_full\">";
						
						if ($settings["full_exempt"])
						{
							echo "
								<option value=\"1\">Moderators can enter full rooms.</option>
								<option value=\"0\">Moderators cannot enter full rooms.</option>
							";
						}
						else
						{
							echo "
								<option value=\"0\">Moderators cannot enter full rooms.</option>
								<option value=\"1\">Moderators can enter full rooms.</option>
							";					
						}
						
						echo "
						</select>
						<div class=\"clear\"></div>
						
						<strong>Allow clickable links</strong><br />
						<span style=\"font-size:12px;\">Should links automatically become clickable in-chat?</span>
						<div class=\"clear\" style=\"margin-top:5px;\"></div>
						<select name=\"links\">";
						
						if ($settings["allow_links"])
						{
							echo "
								<option value=\"1\">Enable clickable links.</option>
								<option value=\"0\">Disable clickable links.</option>
							";
						}
						else
						{
							echo "
								<option value=\"0\">Disable clickable links.</option>
								<option value=\"1\">Enable clickable links.</option>
							";					
						}
						
						echo "
						</select>
						<div class=\"clear\"></div>
						
						<input type=\"hidden\" name=\"token\" value=\"" . md5($_SESSION["settings_token_3"]) . "\">
						<input type=\"submit\" style=\"min-width:100px; border-radius:5px;\" value=\"Save Settings\">
					</form>
				</div>";
			}
		}
		elseif (isset($_GET["sub"]) && $_GET["sub"] == "colours")
		{
			if (isset($_GET["update"]) && $_GET["update"] == 1)
			{
				if (isset($_POST["token"]) && $_POST["token"] == md5($_SESSION["settings_token_7"]))
				{
					$member_hex = trim($mysqli->real_escape_string($_POST["member_hex"]));
					$guest_hex = trim($mysqli->real_escape_string($_POST["guest_hex"]));
					$admin_hex = trim($mysqli->real_escape_string($_POST["admin_hex"]));
					$mod_hex = trim($mysqli->real_escape_string($_POST["mod_hex"]));
					
					// Update settings
					$mysqli->query("UPDATE `settings` SET `member_hex` = '{$member_hex}', `guest_hex` = '{$guest_hex}', `admin_hex` = '{$admin_hex}', `mod_hex` = '{$mod_hex}'");
					
					header('location: index.php?page=settings&sub=colours&success=1');
				}
				else
				{
					echo "
					<div class=\"box\">
						<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
						Invalid submission token receieved, please try again.
					</div>";								
				}
			}
			else
			{
				// update token
				if (!isset($_SESSION["settings_token_7"]))
				{
					$_SESSION["settings_token_7"] = sha1(rand(128, 5000));
				}
				
				echo "<div class=\"title2\">Username Colours</div>";
				echo "
				<div class=\"editor\">
					<a href=\"http://html-color-codes.info/\" target=\"_blank\">Click here for a wide selection of HEX codes.</a>
					<div class=\"clear\"></div>
					
					<form action=\"index.php?page=settings&sub=colours&update=1\" method=\"post\">
						<strong>Member Colour</strong><br />
						<span style=\"font-size:12px;\">Enter a HEX colour code which should be assigned to members. Leave blank for default.</span>
						<div class=\"clear\" style=\"margin-top:5px;\"></div>
						<input type=\"text\" name=\"member_hex\" placeholder=\"#HEX\" value=\"{$settings["member_hex"]}\">
						<div class=\"clear\"></div>
						
						<strong>Guest Colour</strong><br />
						<span style=\"font-size:12px;\">Enter a HEX colour code which should be assigned to guests. Leave blank for default.</span>
						<div class=\"clear\" style=\"margin-top:5px;\"></div>
						<input type=\"text\" name=\"guest_hex\" placeholder=\"#HEX\" value=\"{$settings["guest_hex"]}\">
						<div class=\"clear\"></div>
						
						<strong>Admin Colour</strong><br />
						<span style=\"font-size:12px;\">Enter a HEX colour code which should be assigned to admins. Leave blank for default.</span>
						<div class=\"clear\" style=\"margin-top:5px;\"></div>
						<input type=\"text\" name=\"admin_hex\" placeholder=\"#HEX\" value=\"{$settings["admin_hex"]}\">
						<div class=\"clear\"></div>
						
						<strong>Moderator Colour</strong><br />
						<span style=\"font-size:12px;\">Enter a HEX colour code which should be assigned to moderators. Leave blank for default.</span>
						<div class=\"clear\" style=\"margin-top:5px;\"></div>
						<input type=\"text\" name=\"mod_hex\" placeholder=\"#HEX\" value=\"{$settings["mod_hex"]}\">
						<div class=\"clear\"></div>
						
						<input type=\"hidden\" name=\"token\" value=\"" . md5($_SESSION["settings_token_7"]) . "\">
						<input type=\"submit\" style=\"min-width:100px; border-radius:5px;\" value=\"Save Settings\">
					</form>
				</div>";
			}
		}
		else
		{
			if (isset($_GET["update"]) && $_GET["update"] == 1)
			{
				if (isset($_POST["token"]) && $_POST["token"] == md5($_SESSION["settings_token_4"]))
				{
					$title = trim($mysqli->real_escape_string($_POST["title"]));
					$news = trim($mysqli->real_escape_string($_POST["news"]));
					$banned = trim($mysqli->real_escape_string($_POST["bannedtxt"]));
					$offline = trim($mysqli->real_escape_string($_POST["offline"]));
					
					if ($title == null || $news == null || $banned == null)
					{
						header('location: index.php?page=settings&error=1');
					}
					else
					{
						// if offline was enabled, kick online users
						if ($offline)
						{
							$query_users = $mysqli->query("SELECT user_id, active, rank, apanel, kicked FROM `users` WHERE `active` = '1' AND `rank` < 2 AND `apanel` != 1");
							if ($query_users->num_rows > 0)
							{
								while($u2u = $query_users->fetch_assoc())
								{
									$mysqli->query("UPDATE `users` SET `active` = '0', `kicked` = '1' WHERE `user_id` = '{$u2u["user_id"]}'");
								}
							}
							
							$query_guests = $mysqli->query("SELECT guest_id, active FROM `guests`");
							if ($query_guests->num_rows > 0)
							{
								while($g2u = $query_guests->fetch_assoc())
								{
									$mysqli->query("DELETE FROM `guests` WHERE `guest_id` = '{$g2u["guest_id"]}'");
								}
							}
						}
					
						// Update settings
						$mysqli->query("UPDATE `settings` SET `site_title` = '{$title}', `login_news` = '{$news}', `banned_text` = '{$banned}', `offline_mode` = '{$offline}'");
						
						header('location: index.php?page=settings&success=1');
					}
				}
				else
				{
					echo "
					<div class=\"box\">
						<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
						Invalid submission token receieved, please try again.
					</div>";								
				}
			}
			else
			{
				// update token
				if (!isset($_SESSION["settings_token_4"]))
				{
					$_SESSION["settings_token_4"] = sha1(rand(128, 5000));
				}
				
				echo "<div class=\"title2\">General Settings</div>";
				echo "
				<div class=\"editor\">
					<form action=\"index.php?page=settings&update=1\" method=\"post\">
						<strong>Site Title</strong><br />
						<span style=\"font-size:12px;\">The title of your chat website.</span>
						<div class=\"clear\" style=\"margin-top:5px;\"></div>
						<input type=\"text\" maxlength=\"40\" name=\"title\" value=\"{$settings["site_title"]}\">
						<div class=\"clear\"></div>
						
						<strong>Login News</strong><br />
						<span style=\"font-size:12px;\">News displayed on the chat login page.</span>
						<div class=\"clear\" style=\"margin-top:5px;\"></div>
						<textarea name=\"news\">{$settings["login_news"]}</textarea>
						<div class=\"clear\"></div>
						
						<strong>Blacklist Message</strong><br />
						<span style=\"font-size:12px;\">If a blacklisted IP attempts to access the site, what message should they receive?</span>
						<div class=\"clear\" style=\"margin-top:5px;\"></div>
						<textarea name=\"bannedtxt\">{$settings["banned_text"]}</textarea>
						<div class=\"clear\"></div>
						
						<strong>Offline Mode</strong><br />
						<span style=\"font-size:12px;\">Allow only administrators to enter the chat (enabling will kick any online users).</span>
						<div class=\"clear\" style=\"margin-top:5px;\"></div>
						<select name=\"offline\">";
						
						if ($settings["offline_mode"])
						{
							echo "
								<option value=\"1\">Enable offline mode.</option>
								<option value=\"0\">Disable offline mode.</option>
							";
						}
						else
						{
							echo "
								<option value=\"0\">Disable offline mode.</option>
								<option value=\"1\">Enable offline mode.</option>
							";					
						}
						
						echo "
						</select>
						<div class=\"clear\"></div>
						
						<input type=\"hidden\" name=\"token\" value=\"" . md5($_SESSION["settings_token_4"]) . "\">
						<input type=\"submit\" style=\"min-width:100px; border-radius:5px;\" value=\"Save Settings\">
					</form>
				</div>";
			}
		}
		?>
	
		</div>
		
		<div class="left_menu">
			<a href="index.php?page=settings"><img src="template/icons/settings_icon.png"> General Settings</a>
			<a href="index.php?page=settings&sub=members"><img src="template/icons/users_icon.png"> Member Settings</a>
			<a href="index.php?page=settings&sub=guests"><img src="template/icons/guests_icon.png"> Guest Settings</a>
			<a href="index.php?page=settings&sub=profiles"><img src="template/icons/user_icon.png"> Profile Settings</a>
			<a href="index.php?page=settings&sub=chat"><img src="template/icons/chat_icon.png"> Chat Settings</a>
			<a href="index.php?page=settings&sub=colours"><img src="template/icons/colour.png"> Username Colours</a>
			<a href="index.php?page=settings&sub=games"><img src="template/icons/games_icon.png"> Flash Games Settings</a>
			<!--<a href="index.php?page=settings&sub=theme"><img src="template/icons/theme_icon.png"> Template Settings</a>
			<a href="index.php?page=settings&sub=db"><img src="template/icons/database_icon.png"> Database Tools</a>-->
		</div>
	</div>

<?php endif; ?>