<?php
	/**
	 * readyChat 2.2.0 release
	 * Software by DesignSkate
	 */
	 
	// Logged in checker
	if (!$user["apanel"]): header('location: ../index.php'); die(); else:
	
	if (isset($_GET["success"]))
	{
		$success = "<div class=\"success_head\">Room settings saved.</div>";
	}
	elseif (isset($_GET["create"]))
	{
		$success = "<div class=\"success_head\">Room created.</div>";
	}
	elseif (isset($_GET["delete"]) && !isset($_GET["sub"]))
	{
		$success = "<div class=\"success_head\">Room deleted.</div>";
	}
	else
	{
		$success = "";
	}
	
	if (isset($_GET["update"]))
	{
		if ($_POST["title"] == null || $_POST["desc"] == null || $_POST["limit"] == null)
		{
			$error = "<div class=\"error_head\">A required field was empty.</div>"; 
		}
		elseif (!is_numeric($_POST["limit"]))
		{
			$error = "<div class=\"error_head\">Room limit must be a number.</div>"; 
		}
		else
		{
			$error = "";
		}
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
				if (isset($_GET["sub"]) && $_GET["sub"] == "edit")
				{
					if (isset($_GET["edit"]) && is_numeric($_GET["edit"]))
					{
						$room_id = trim($mysqli->real_escape_string($_GET["edit"]));
						$query_room = $mysqli->query("SELECT * FROM `rooms_permanent` WHERE `room_id` = '{$room_id}'");
						if ($query_room->num_rows > 0)
						{
							$room = $query_room->fetch_array(MYSQLI_BOTH);
							
							if (isset($_GET["update"]) && $_GET["update"] == 1)
							{
								if (isset($_POST["token"]) && $_POST["token"] == md5($_SESSION["rooms_token"]))
								{
									$title = trim($mysqli->real_escape_string($_POST["title"]));
									$topic = trim($mysqli->real_escape_string($_POST["desc"]));
									$limit = trim($mysqli->real_escape_string($_POST["limit"]));
									$icon = trim($mysqli->real_escape_string($_POST["icon"]));
									$background = trim($mysqli->real_escape_string($_POST["background"]));
									$password = trim($mysqli->real_escape_string($_POST["password"]));
									$gaccess = trim($mysqli->real_escape_string($_POST["gaccess"]));
									
									if ($title == null || $topic == null || $limit == null || !is_numeric($limit))
									{
										echo "<div class=\"title2\">Editing Room: {$room["room_title"]}</div>";
										echo "
										<div class=\"editor\">
											<form action=\"index.php?page=rooms&sub=edit&edit={$room_id}&update=1\" method=\"post\">
												<strong>Room Title</strong><br />
												<span style=\"font-size:12px;\">A short title displayed on room listings.</span>
												<div class=\"clear\" style=\"margin-top:5px;\"></div>
												<input type=\"text\" maxlength=\"40\" name=\"title\" value=\"{$title}\">
												<div class=\"clear\"></div>
												
												<strong>Room Topic</strong><br />
												<span style=\"font-size:12px;\">The topic displayed at the top of the room.</span>
												<div class=\"clear\" style=\"margin-top:5px;\"></div>
												<input type=\"text\" maxlength=\"500\" name=\"desc\" value=\"{$topic}\">
												<div class=\"clear\"></div>
												
												<strong>Room Limit</strong><br />
												<span style=\"font-size:12px;\">The maximum users allowed in this room at one time.</span>
												<div class=\"clear\" style=\"margin-top:5px;\"></div>
												<input type=\"text\" maxlength=\"3\" name=\"limit\" value=\"{$limit}\">
												<div class=\"clear\"></div>
												
												<strong>Room Icon</strong><br />
												<span style=\"font-size:12px;\">Small icon (ideally 12x12) to sit beside the room name (not required)</span>
												<div class=\"clear\" style=\"margin-top:5px;\"></div>
												<input type=\"text\" maxlength=\"100\" name=\"icon\" value=\"{$icon}\">
												<div class=\"clear\"></div>
											
												<strong>Room Background</strong><br />
												<span style=\"font-size:12px;\">Full width/height background image for a specific room (not required)</span>
												<div class=\"clear\" style=\"margin-top:5px;\"></div>
												<input type=\"text\" maxlength=\"200\" name=\"background\" value=\"{$background}\">
												<div class=\"clear\"></div>
												
												<strong>Room Password</strong><br />
												<span style=\"font-size:12px;\">Enter a password below, or leave it blank to remove the room password.</span>
												<div class=\"clear\" style=\"margin-top:5px;\"></div>
												<input type=\"text\" maxlength=\"30\" name=\"password\" value=\"{$password}\" placeholder=\"No password set\">
												<div class=\"clear\"></div>
												
												<strong>Guest Access</strong><br />
												<span style=\"font-size:12px;\">Do you want to allow guests inside this room?</span>
												<div class=\"clear\" style=\"margin-top:5px;\"></div>
												<select name=\"gaccess\">";
												
												if ($gaccess)
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
												
												<input type=\"submit\" style=\"min-width:100px; border-radius:5px;\" value=\"Save Room Settings\">
											</form>
										</div>";								
									}
									else
									{
										// save room
										$mysqli->query("UPDATE `rooms_permanent` SET `room_title` = '{$title}', `room_desc` = '{$topic}', `room_limit` = '{$limit}', `room_limit` = '{$limit}', `room_icon` = '{$icon}', `room_password` = '{$password}', `guest_access` = '{$gaccess}', `room_background` = '{$background}' WHERE `room_id` = '{$room_id}'");
										
										// back to room settings
										header('location: index.php?page=rooms&sub=edit&edit=' . $room_id . '&success=1');
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
								if (!isset($_SESSION["rooms_token"]))
								{
									$_SESSION["rooms_token"] = sha1(rand(128, 5000));
								}
								
								echo "<div class=\"title2\">Editing Room: {$room["room_title"]}</div>";
								echo "
								<div class=\"editor\">
									<form action=\"index.php?page=rooms&sub=edit&edit={$room_id}&update=1\" method=\"post\">
										<strong>Room Title</strong><br />
										<span style=\"font-size:12px;\">A short title displayed on room listings.</span>
										<div class=\"clear\" style=\"margin-top:5px;\"></div>
										<input type=\"text\" maxlength=\"40\" name=\"title\" value=\"{$room["room_title"]}\">
										<div class=\"clear\"></div>
										
										<strong>Room Topic</strong><br />
										<span style=\"font-size:12px;\">The topic displayed at the top of the room.</span>
										<div class=\"clear\" style=\"margin-top:5px;\"></div>
										<input type=\"text\" maxlength=\"500\" name=\"desc\" value=\"{$room["room_desc"]}\">
										<div class=\"clear\"></div>
										
										<strong>Room Limit</strong><br />
										<span style=\"font-size:12px;\">The maximum users allowed in this room at one time.</span>
										<div class=\"clear\" style=\"margin-top:5px;\"></div>
										<input type=\"text\" maxlength=\"3\" name=\"limit\" value=\"{$room["room_limit"]}\">
										<div class=\"clear\"></div>
										
										<strong>Room Icon</strong><br />
										<span style=\"font-size:12px;\">Small icon (ideally 12x12) to sit beside the room name (not required)</span>
										<div class=\"clear\" style=\"margin-top:5px;\"></div>
										<input type=\"text\" maxlength=\"100\" name=\"icon\" value=\"{$room["room_icon"]}\">
										<div class=\"clear\"></div>
										
										<strong>Room Background</strong><br />
										<span style=\"font-size:12px;\">Full width/height background image for a specific room (not required)</span>
										<div class=\"clear\" style=\"margin-top:5px;\"></div>
										<input type=\"text\" maxlength=\"200\" name=\"background\" value=\"{$room["room_background"]}\">
										<div class=\"clear\"></div>
										
										<strong>Room Password</strong><br />
										<span style=\"font-size:12px;\">Enter a password below, or leave it blank to remove the room password.</span>
										<div class=\"clear\" style=\"margin-top:5px;\"></div>
										<input type=\"text\" maxlength=\"30\" name=\"password\" value=\"{$room["room_password"]}\" placeholder=\"No password set\">
										<div class=\"clear\"></div>
										
										<strong>Guest Access</strong><br />
										<span style=\"font-size:12px;\">Do you want to allow guests inside this room?</span>
										<div class=\"clear\" style=\"margin-top:5px;\"></div>
										<select name=\"gaccess\">";
										
										if ($room["guest_access"])
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
										
										<input type=\"hidden\" name=\"token\" value=\"" . md5($_SESSION["rooms_token"]) . "\">
										<input type=\"submit\" style=\"min-width:100px; border-radius:5px;\" value=\"Save Room Settings\">
									</form>
								</div>";
							}
						}
						else
						{
							echo "
							<div class=\"box\">
								<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
								The requested room does not exist - <a href=\"index.php?page=rooms\">Back to rooms</a>
							</div>";
						}
					}
					else
					{
						echo "
						<div class=\"box\" style=>
							<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
							The requested room does not exist - <a href=\"index.php?page=rooms\">Back to rooms</a>
						</div>";
					}
				}
				elseif (isset($_GET["sub"]) && $_GET["sub"] == "new")
				{
					if (isset($_GET["update"]) && $_GET["update"] == 1)
					{
						$title = trim($mysqli->real_escape_string($_POST["title"]));
						$topic = trim($mysqli->real_escape_string($_POST["desc"]));
						$limit = trim($mysqli->real_escape_string($_POST["limit"]));
						$icon = trim($mysqli->real_escape_string($_POST["icon"]));
						$background = trim($mysqli->real_escape_string($_POST["background"]));
						$password = trim($mysqli->real_escape_string($_POST["password"]));
						$gaccess = trim($mysqli->real_escape_string($_POST["gaccess"]));
						
						if ($title == null || $topic == null || $limit == null || !is_numeric($limit))
						{
							echo "<div class=\"title2\">Creating a new room</div>";
							echo "
							<div class=\"editor\">
								<form action=\"index.php?page=rooms&sub=new&update=1\" method=\"post\">
									<strong>Room Title</strong><br />
									<span style=\"font-size:12px;\">A short title displayed on room listings.</span>
									<div class=\"clear\" style=\"margin-top:5px;\"></div>
									<input type=\"text\" maxlength=\"40\" name=\"title\" value=\"{$title}\">
									<div class=\"clear\"></div>
									
									<strong>Room Topic</strong><br />
									<span style=\"font-size:12px;\">The topic displayed at the top of the room.</span>
									<div class=\"clear\" style=\"margin-top:5px;\"></div>
									<input type=\"text\" maxlength=\"500\" name=\"desc\" value=\"{$topic}\">
									<div class=\"clear\"></div>
									
									<strong>Room Limit</strong><br />
									<span style=\"font-size:12px;\">The maximum users allowed in this room at one time.</span>
									<div class=\"clear\" style=\"margin-top:5px;\"></div>
									<input type=\"text\" maxlength=\"3\" name=\"limit\" value=\"{$limit}\">
									<div class=\"clear\"></div>
									
									<strong>Room Icon</strong><br />
									<span style=\"font-size:12px;\">Small icon (ideally 12x12) to sit beside the room name (not required)</span>
									<div class=\"clear\" style=\"margin-top:5px;\"></div>
									<input type=\"text\" maxlength=\"100\" name=\"icon\" value=\"{$icon}\">
									<div class=\"clear\"></div>
									
									<strong>Room Background</strong><br />
									<span style=\"font-size:12px;\">Full width/height background image for a specific room (not required)</span>
									<div class=\"clear\" style=\"margin-top:5px;\"></div>
									<input type=\"text\" maxlength=\"200\" name=\"background\" value=\"{$background}\">
									<div class=\"clear\"></div>
									
									<strong>Room Password</strong><br />
									<span style=\"font-size:12px;\">Enter a password below, or leave it blank to remove the room password.</span>
									<div class=\"clear\" style=\"margin-top:5px;\"></div>
									<input type=\"text\" maxlength=\"30\" name=\"password\" value=\"{$password}\" placeholder=\"No password set\">
									<div class=\"clear\"></div>
									
									<strong>Guest Access</strong><br />
									<span style=\"font-size:12px;\">Do you want to allow guests inside this room?</span>
									<div class=\"clear\" style=\"margin-top:5px;\"></div>
									<select name=\"gaccess\">";
									
									if ($room["guest_access"])
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
									
									<input type=\"hidden\" name=\"token\" value=\"" . md5($_SESSION["rooms_token_2"]) . "\">
									<input type=\"submit\" style=\"min-width:100px; border-radius:5px;\" value=\"Save Room Settings\">
								</form>
							</div>";								
						}
						else
						{
							if (isset($_POST["token"]) && $_POST["token"] == md5($_SESSION["rooms_token_2"]))
							{
								// strip invalid content
								$strip_title = preg_replace('/[^A-Za-z0-9-.\/]/', '', $title);
								$final_title = "{$strip_title}.html";
								
								// Insert data to database
								$mysqli->query("INSERT INTO `rooms_permanent` (`room_title`, `room_desc`, `room_limit`, `room_icon`, `room_json`, `room_password`, `guest_access`, `room_background`)
																	   VALUES ('{$title}', '{$topic}', '{$limit}', '{$icon}', '{$final_title}', '{$password}', '{$gaccess}', '{$background}')") or die($mysqli->error);
																	   
								// back to room listings
								header('location: index.php?page=rooms&create=1');
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
					}
					else 
					{	
						// update token
						if (!isset($_SESSION["rooms_token_2"]))
						{
							$_SESSION["rooms_token_2"] = sha1(rand(128, 5000));
						}
						
						echo "<div class=\"title2\">Create a new room</div>";
						echo "
						<div class=\"editor\">
							<form action=\"index.php?page=rooms&sub=new&update=1\" method=\"post\">
								<strong>Room Title</strong><br />
								<span style=\"font-size:12px;\">A short title displayed on room listings.</span>
								<div class=\"clear\" style=\"margin-top:5px;\"></div>
								<input type=\"text\" maxlength=\"40\" name=\"title\">
								<div class=\"clear\"></div>
								
								<strong>Room Topic</strong><br />
								<span style=\"font-size:12px;\">The topic displayed at the top of the room.</span>
								<div class=\"clear\" style=\"margin-top:5px;\"></div>
								<input type=\"text\" maxlength=\"500\" name=\"desc\">
								<div class=\"clear\"></div>
								
								<strong>Room Limit</strong><br />
								<span style=\"font-size:12px;\">The maximum users allowed in this room at one time.</span>
								<div class=\"clear\" style=\"margin-top:5px;\"></div>
								<input type=\"text\" maxlength=\"3\" name=\"limit\">
								<div class=\"clear\"></div>
								
								<strong>Room Icon</strong><br />
								<span style=\"font-size:12px;\">Small icon (ideally 12x12) to sit beside the room name (not required)</span>
								<div class=\"clear\" style=\"margin-top:5px;\"></div>
								<input type=\"text\" maxlength=\"100\" name=\"icon\">
								<div class=\"clear\"></div>
								
								<strong>Room Background</strong><br />
								<span style=\"font-size:12px;\">Full width/height background image for a specific room (not required)</span>
								<div class=\"clear\" style=\"margin-top:5px;\"></div>
								<input type=\"text\" maxlength=\"200\" name=\"background\">
								<div class=\"clear\"></div>
								
								<strong>Room Password</strong><br />
								<span style=\"font-size:12px;\">Enter a password below, or leave it blank to remove the room password.</span>
								<div class=\"clear\" style=\"margin-top:5px;\"></div>
								<input type=\"text\" maxlength=\"30\" name=\"password\" placeholder=\"No password set\">
								<div class=\"clear\"></div>
								
								<strong>Guest Access</strong><br />
								<span style=\"font-size:12px;\">Do you want to allow guests inside this room?</span>
								<div class=\"clear\" style=\"margin-top:5px;\"></div>
								<select name=\"gaccess\">
									<option value=\"1\">Enable guest access.</option>
									<option value=\"0\">Disable guest access.</option>
								</select>										
								<div class=\"clear\"></div>
								
								<input type=\"hidden\" name=\"token\" value=\"" . md5($_SESSION["rooms_token_2"]) . "\">
								<input type=\"submit\" style=\"min-width:100px; border-radius:5px;\" value=\"Create Room\">
							</form>
						</div>";
					}
				}
				elseif (isset($_GET["sub"]) && $_GET["sub"] == "delete")
				{
					if (isset($_GET["delete"]) && is_numeric($_GET["delete"]))
					{
						$room_id = trim($mysqli->real_escape_string($_GET["delete"]));
						$query_room = $mysqli->query("SELECT * FROM `rooms_permanent` WHERE `room_id` = '{$room_id}'");
						if ($query_room->num_rows > 0)
						{
							$room = $query_room->fetch_array(MYSQLI_BOTH);
							if (isset($_GET["confirm"]))
							{
								if (isset($_SESSION["delete_key"]) && $_SESSION["delete_key"] == $_POST["deletekey"])
								{
									// update and disconnect users inside this room
									$mysqli->query("UPDATE `users` SET `reset` = '1' WHERE `user_room` = '{$room["room_id"]}'");
									
									// delete database entry
									$mysqli->query("DELETE FROM `rooms_permanent` WHERE `room_json` = '{$room["room_json"]}'");
									
									// Unset delete key
									unset($_SESSION["delete_key"]);
									
									// back to listings
									header('location: index.php?page=rooms&delete=1');
								}
								else
								{
									echo "
									<div class=\"box\">
										<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
										The authentication key was incorrect.
									</div>";								
								}
							}
							else
							{
								$_SESSION["delete_key"] = md5(rand(30, 529));
								
								echo "
								<div class=\"box\">
									<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Confirm this action</div>
									Are you sure you wish to delete {$room["room_title"]}?<br />
									<span style=\"font-size:11px;\">Users inside this room will be disconnected.</span>
									<div class=\"clear\"></div>
									<form action=\"index.php?page=rooms&sub=delete&delete={$room["room_id"]}&confirm=1\" method=\"post\">
										<input type=\"hidden\" name=\"deletekey\" value=\"{$_SESSION["delete_key"]}\">
										<input type=\"submit\" value=\"Delete Now\" style=\"padding:10px; cursor:pointer;\">
									</form>
								</div>";
							}
						}
						else
						{
							echo "
							<div class=\"box\">
								<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
								The requested room does not exist - <a href=\"index.php?page=rooms\">Back to rooms</a>
							</div>";
						}
					}
					else
					{
						echo "
						<div class=\"box\" style=>
							<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
							The requested room does not exist - <a href=\"index.php?page=rooms\">Back to rooms</a>
						</div>";
					}
				}
				elseif (isset($_GET["sub"]) && $_GET["sub"] == "maintenance")
				{
					if (isset($_GET["perform"]) && $_GET["perform"] == 1)
					{
						if (isset($_GET["run"]) && $_GET["run"] == 1)
						{
							// prune all rooms
							$query_chats = $mysqli->query("SELECT chat_id FROM `chat_messages`");
							if ($query_chats->num_rows > 0)
							{
								$mysqli->query("DELETE FROM `chat_messages`");
								$mysqli->query("ALTER TABLE `chat_messages` AUTO_INCREMENT = 1");
								
								echo "
								<div class=\"box\" style=>
									<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Task Completed</div>
									Your chat rooms were successfully pruned - <a href=\"index.php?page=rooms&sub=maintenance\">Back to maintenance</a>
								</div>";	
							}
							else
							{
								echo "
								<div class=\"box\" style=>
									<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
									There are no rooms to prune - <a href=\"index.php?page=rooms&sub=maintenance\">Back to maintenance</a>
								</div>";							
							}
						}
						else
						{
							echo "
							<div class=\"box\">
								<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Prune Chat Rooms</div>
								This task will clear the chat from all rooms on the website. This will not affect your users therefore shutting down your website is not required.
								<div class=\"clear\"></div>
								<div class=\"maintenance\">
									<img src=\"template/icons/tick_icon.png\"> <a href=\"index.php?page=rooms&sub=maintenance&perform=1&run=1\">Run Now</a>
								</div>
								<div class=\"maintenance\">
									<img src=\"template/icons/cross_icon.png\"> <a href=\"index.php?page=rooms&sub=maintenance\">Cancel</a>
								</div>								
							</div>";
						}
					}
					elseif (isset($_GET["perform"]) && $_GET["perform"] == 2)
					{
						if (isset($_GET["run"]) && $_GET["run"] == 1)
						{
							// kick all users
							$query_guests = $mysqli->query("SELECT guest_id FROM `guests`");
							$query_users = $mysqli->query("SELECT user_id, rank, active, kicked FROM `users` WHERE `active` = '1' AND `rank` <= '2'");
							if ($query_users->num_rows > 0 || $query_guests->num_rows > 0)
							{
								while($u = $query_users->fetch_assoc())
								{
									$mysqli->query("UPDATE `users` SET `kicked` = '1', `active` = '0' WHERE `user_id` = '{$u["user_id"]}'");
								}
								
								if ($query_guests->num_rows > 0)
								{
									while($g2u = $query_guests->fetch_assoc())
									{
										$mysqli->query("DELETE FROM `guests` WHERE `guest_id` = '{$g2u["guest_id"]}'");
									}
								}
								
								echo "
								<div class=\"box\" style=>
									<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Task Completed</div>
									All users were successfully kicked. - <a href=\"index.php?page=rooms&sub=maintenance\">Back to maintenance</a>
								</div>";	
							}
							else
							{
								echo "
								<div class=\"box\" style=>
									<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
									There are no users to kick - <a href=\"index.php?page=rooms&sub=maintenance\">Back to maintenance</a>
								</div>";							
							}
						}
						else
						{
							echo "
							<div class=\"box\">
								<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Kick All Users</div>
								This task will kick all users from your website and they will be required to login again. It is useful if you wish to close your chat room for maintenance.
								Don't forget to put your site into maintenance mode from the <a href=\"index.php?page=settings\">settings page</a>.
								<div class=\"clear\"></div>
								<div class=\"maintenance\">
									<img src=\"template/icons/tick_icon.png\"> <a href=\"index.php?page=rooms&sub=maintenance&perform=2&run=1\">Run Now</a>
								</div>
								<div class=\"maintenance\">
									<img src=\"template/icons/cross_icon.png\"> <a href=\"index.php?page=rooms&sub=maintenance\">Cancel</a>
								</div>								
							</div>";
						}
					}
					elseif (isset($_GET["perform"]) && $_GET["perform"] == 3)
					{
						if (isset($_GET["run"]) && $_GET["run"] == 1)
						{
							// send global announcement
							if (isset($_POST["content"]) && $_POST["content"] != null)
							{
								$content = trim($mysqli->real_escape_string($_POST["content"]));
								
								$query_rooms = $mysqli->query("SELECT room_json FROM `rooms_permanent`");
								if ($query_rooms->num_rows > 0)
								{
									$GLOBALS["mysqli"]->query("INSERT INTO `chat_messages` (chat_room, chat_user, chat_text, chat_time)
																					VALUES ('ALL', 'N/A_', '{$content}', '{$GLOBALS["config"]["micro"]}')");
								
									echo "
									<div class=\"box\" style=>
										<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Task Completed</div>
										Announcement successfully posted - <a href=\"index.php?page=rooms&sub=maintenance\">Back to maintenance</a>
									</div>";	
								}
								else
								{
									echo "
									<div class=\"box\" style=>
										<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
										There are no rooms to send an announcement to - <a href=\"index.php?page=rooms&sub=maintenance\">Back to maintenance</a>
									</div>";							
								}
							}
							else
							{
								echo "
								<div class=\"box\" style=>
									<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
									You tried to send a blank announcement - <a href=\"index.php?page=rooms&sub=maintenance&perform=3\">Back to Global Announcement</a>
								</div>";								
							}
						}
						else
						{
							echo "
							<div class=\"box\">
								<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Global Announcement</div>
								Enter the announcement text to send below. The text will be parsed as the chat is, therefore HTML is disabled. Links can be inserted by simply typing it, for example \"http://google.com\".
								<div class=\"clear\"></div>
								<form action=\"index.php?page=rooms&sub=maintenance&perform=3&run=1\" method=\"post\">
									<input type=\"text\" name=\"content\" style=\"width:420px; padding:10px;\">
									<input type=\"submit\" value=\"Send\" style=\"padding:10px; float:right; margin-right:1px;\">
								</form>
								<div class=\"clear\"></div>
								<div class=\"maintenance\">
									<img src=\"template/icons/cross_icon.png\"> <a href=\"index.php?page=rooms&sub=maintenance\">Cancel</a>
								</div>								
							</div>";
						}
					}
					elseif (isset($_GET["perform"]) && $_GET["perform"] == 4)
					{
						if (isset($_GET["run"]) && $_GET["run"] == 1)
						{
							// kick all guests
							$query_guests = $mysqli->query("SELECT guest_id FROM `guests`");
							if ($query_guests->num_rows > 0)
							{
								while($g2u = $query_guests->fetch_assoc())
								{
									$mysqli->query("DELETE FROM `guests` WHERE `guest_id` = '{$g2u["guest_id"]}'");
								}
								
								echo "
								<div class=\"box\" style=>
									<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Task Completed</div>
									All guests were successfully kicked. - <a href=\"index.php?page=rooms&sub=maintenance\">Back to maintenance</a>
								</div>";	
							}
							else
							{
								echo "
								<div class=\"box\" style=>
									<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
									There are no guests to kick - <a href=\"index.php?page=rooms&sub=maintenance\">Back to maintenance</a>
								</div>";							
							}
						}
						else
						{
							echo "
							<div class=\"box\">
								<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Kick All Guests</div>
								This task will kick all guest users from your website and they will be required to login again.
								<div class=\"clear\"></div>
								<div class=\"maintenance\">
									<img src=\"template/icons/tick_icon.png\"> <a href=\"index.php?page=rooms&sub=maintenance&perform=4&run=1\">Run Now</a>
								</div>
								<div class=\"maintenance\">
									<img src=\"template/icons/cross_icon.png\"> <a href=\"index.php?page=rooms&sub=maintenance\">Cancel</a>
								</div>								
							</div>";
						}
					}
					else
					{
						echo "
						<div class=\"box\">
							<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Chat Room Maintenance</div>
							Tasks performed here affect all rooms on the website. These tasks can be run whilst users are logged in without causing
							any problems.
							<div class=\"clear\"></div>
							<div class=\"maintenance\">
								<img src=\"template/icons/prune_icon.png\"> <a href=\"index.php?page=rooms&sub=maintenance&perform=1\">Prune chat rooms</a>
							</div>
							<div class=\"maintenance\">
								<img src=\"template/icons/delete_icon.png\"> <a href=\"index.php?page=rooms&sub=maintenance&perform=2\">Kick all users from the chat</a>
							</div>	
							<div class=\"maintenance\">
								<img src=\"template/icons/guests_icon.png\"> <a href=\"index.php?page=rooms&sub=maintenance&perform=4\">Kick all guests from the chat</a>
							</div>	
							<div class=\"maintenance\">
								<img src=\"template/icons/announce_icon.png\"> <a href=\"index.php?page=rooms&sub=maintenance&perform=3\">Send a global announcement</a>
							</div>								
						</div>";
					}
				}
				else
				{
					// list chat rooms
					echo "<div class=\"title2\">Chat Room Management</div>";
					
					$query_rooms = $mysqli->query("SELECT * FROM `rooms_permanent`");
					if ($query_rooms->num_rows > 0)
					{
						echo "
						<table class=\"forum\">
							<tr>
								<th style=\"width:40px; text-align:center;\">Icon</th>
								<th>Room Title</th>
								<th style=\"width:80px; text-align:center;\">Room Limit</th>
								<th style=\"width:80px;\"></th>
							</tr>
						";
						
						while($room = $query_rooms->fetch_assoc())
						{
							if ($room["room_icon"] == null)
							{
								$icon = "template/icons/empty_icon.png";
							}
							else
							{
								$icon = $room["room_icon"];
							}
							
							echo "
							<tr>
								<td style=\"text-align:center;\">
									<img src=\"{$icon}\" style=\"float:left; margin-top:1px; margin-left:8px;\" height=\"16px\">
								</td>
								<td>
									<span style=\"float:left;\">
										<a href=\"index.php?page=rooms&sub=edit&edit={$room["room_id"]}\">{$room["room_title"]}</a>
									</span>
								</td>
								<td style=\"text-align:center;\">
									{$room["room_limit"]}
								</td>
								<td style=\"text-align:center;\">
									<a href=\"index.php?page=rooms&sub=edit&edit={$room["room_id"]}\"><img src=\"template/icons/edit_icon.png\"></a>
									<a href=\"index.php?page=rooms&sub=delete&delete={$room["room_id"]}\"><img src=\"template/icons/delete_icon.png\"></a>
									<a href=\"index.php?page=history&room={$room["room_json"]}\" target=\"_blank\"><img src=\"template/icons/chat_icon.png\"></a>
								</td>
							</tr>";
							
							unset($icon);
						}
						
						echo "</table>";
					}
					else
					{
						echo "No rooms found.";
					}
				}
			?>
		</div>
		
		<div class="left_menu">
			<a href="index.php?page=rooms"><img src="template/icons/manage_icon.png"> Room Management</a>
			<a href="index.php?page=rooms&sub=new"><img src="template/icons/new_icon.png"> New Room</a>
			<a href="index.php?page=rooms&sub=maintenance"><img src="template/icons/maint_icon.png"> Global Maintenance</a>
			<a href="docs/rooms.html" target="_blank"><img src="template/icons/info_icon.png"> Rooms Documentation</a>
		</div>
	</div>

<?php endif; ?>