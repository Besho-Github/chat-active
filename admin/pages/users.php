<?php
	/**
	 * readyChat 2.2.0 release
	 * Software by DesignSkate
	 */
	 
	// Logged in checker
	if (!$user["apanel"]): header('location: ../index.php'); die(); else:
	
	if (isset($_GET["success"]) && $_GET["success"] == 1)
	{
		$success = "<div class=\"success_head\">User successfully saved.</div>";
	}
	elseif (isset($_GET["success"]) && $_GET["success"] == 2)
	{
		$success = "<div class=\"success_head\">User disconnected.</div>";
	}
	elseif (isset($_GET["success"]) && $_GET["success"] == 3)
	{
		$success = "<div class=\"success_head\">Guest successfully kicked.</div>";
	}
	elseif (isset($_GET["success"]) && $_GET["success"] == 4)
	{
		$success = "<div class=\"success_head\">Guest successfully banned.</div>";
	}
	elseif (isset($_GET["banned"]))
	{
		$success = "<div class=\"success_head\">User banned.</div>";
	}
	elseif (isset($_GET["deleted"]))
	{
		$success = "<div class=\"success_head\">User deleted.</div>";
	}
	elseif (isset($_GET["removed"]) && $_GET["sub"] == "blacklist")
	{
		$success = "<div class=\"success_head\">Blacklist entry deleted.</div>";
	}
	elseif (isset($_GET["created"]) && $_GET["sub"] == "blacklist")
	{
		$success = "<div class=\"success_head\">Blacklist entry created.</div>";
	}
	else
	{
		$success = "";
	}
	
	if (isset($_GET["error"]) && $_GET["error"] == 1)
	{
		$error = "<div class=\"error_head\">That guest ID isn't online.</div>";
	}
	else
	{
		$error = "";
	}
	
	echo $success, $error;
?>
	<div id="content">
		<div class="title">
			<img src="template/images/logo.png">
		</div>
		<div class="right_menu">
		
			<?php
				if (isset($_GET["sub"]) && $_GET["sub"] == "create")
				{
					if (isset($_GET["create"]) && $_GET["create"] == 1)
					{
						if (isset($_POST["token"]) && $_POST["token"] == md5($_SESSION["users_token"]))
						{
							if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["email"]) && isset($_POST["rank"]))
							{
								$username = $mysqli->real_escape_string($_POST["username"]);
								$password = $mysqli->real_escape_string($_POST["password"]);
								$email = $mysqli->real_escape_string($_POST["email"]);
								$rank = $mysqli->real_escape_string($_POST["rank"]);
								
								if (isset($_POST["apanel"]))
								{
									$apanel = $mysqli->real_escape_string($_POST["apanel"]);
								}
								else
								{
									$apanel = 0;
								}
								
								if (isset($_POST["banned"]))
								{
									$banned = $mysqli->real_escape_string($_POST["banned"]);
								}
								else
								{
									$banned = 0;
								}
								
								$query_users = $GLOBALS["mysqli"]->query("SELECT `user_name` FROM `users` WHERE `user_name` = '{$username}'");
								if ($query_users->num_rows < 1)
								{
									$query_email = $GLOBALS["mysqli"]->query("SELECT `user_email` FROM `users` WHERE `user_email` = '{$email}'");
									if ($query_email->num_rows < 1)
									{
										if (filter_var($email, FILTER_VALIDATE_EMAIL))
										{
											if (preg_match("/^[a-zA-Z0-9]+$/", $username))
											{
												if (strlen($username) <= 10)
												{
													$epw = sha1(str_rot13($password . $keys["enc_1"]));
													$ip = "n/a";
													
													$GLOBALS["mysqli"]->query("INSERT INTO `users` (user_name, user_password, user_email, user_ip, user_room, user_joined, banned, apanel, rank) 
																				 VALUES ('{$username}', '{$epw}', '{$email}', '{$ip}', '1', '{$time}', '{$banned}', '{$apanel}', '{$rank}')") or die($GLOBALS["mysqli"]->error);
													
													header('location: index.php?page=users&sub=create&success=1');
												}
												else
												{
													echo "
														<div class=\"box\">
														<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
														Username should be 10 characters long or less - <a href=\"javascript:history.go(-1)\">Go Back</a> and correct this.
													</div>";	
												}
											}
											else
											{
												echo "
													<div class=\"box\">
													<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
													Usernames should contain no special characters, a-z, A-Z and numbers only - <a href=\"javascript:history.go(-1)\">Go Back</a> and correct this.
												</div>";	
											}
										}
										else
										{
											echo "
												<div class=\"box\">
												<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
												Email format invalid - <a href=\"javascript:history.go(-1)\">Go Back</a> and correct this.
											</div>";	
										}
									}
									else
									{
										echo "
											<div class=\"box\">
											<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
											The email is already in use - <a href=\"javascript:history.go(-1)\">Go Back</a> and correct this.
										</div>";						
									}
								}
								else
								{
									echo "
										<div class=\"box\">
										<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
										The username is already in use - <a href=\"javascript:history.go(-1)\">Go Back</a> and correct this.
									</div>";	
								}
							}
							else
							{
								echo "
									<div class=\"box\">
									<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
									Required field was empty - <a href=\"javascript:history.go(-1)\">Go Back</a> and correct this.
								</div>";								
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
						if (!isset($_SESSION["users_token"]))
						{
							$_SESSION["users_token"] = sha1(rand(128, 5000));
						}
						
						echo "
						<div class=\"title2\" style=\"overflow:auto;\">
							<span style=\"float:left;\">Creating New Account</span>
						</div>
						<div class=\"editor\">
							<form action=\"index.php?page=users&sub=create&create=1\" method=\"post\">
								<strong>Username</strong><br />
								<span style=\"font-size:12px;\">The name the user will login with.</span>
								<div class=\"clear\" style=\"margin-top:5px;\"></div>
								<input type=\"text\" name=\"username\">
								<div class=\"clear\"></div>
								
								<strong>Email Address</strong><br />
								<span style=\"font-size:12px;\">The user's email address.</span>
								<div class=\"clear\" style=\"margin-top:5px;\"></div>
								<input type=\"text\" name=\"email\">
								<div class=\"clear\"></div>
								
								<strong>User Rank</strong><br />
								<span style=\"font-size:12px;\">Set this user's chat room rank - see documentation for rank information.</span>
								<div class=\"clear\" style=\"margin-top:5px;\"></div>
								<select name=\"rank\">
									<option value=\"1\">User</option>
									<option value=\"2\">Moderator</option>
									<option value=\"3\">Administrator</option>
								</select>
								<div class=\"clear\"></div>
								
								<strong>Password</strong><br />
								<span style=\"font-size:12px;\">The password the user will login with.</span>
								<div class=\"clear\" style=\"margin-top:5px;\"></div>
								<input type=\"text\" name=\"password\">
								<div class=\"clear\"></div>
								
								<strong>Admin Panel Access</strong><br />
								<span style=\"font-size:12px;\">Select whether or not this user can access the admin panel.</span>
								<div class=\"clear\" style=\"margin-top:5px;\"></div>
								<div class=\"maintenance\">
									<input type=\"checkbox\" value=\"1\" name=\"apanel\" style=\"min-width:1px; float:left; margin-right:10px;\"> Can access admin panel.
								</div>
								<div class=\"clear\"></div>
								
								<strong>Account Banned</strong><br />
								<span style=\"font-size:12px;\">Select whether or not this user is banned from the chat (user will be kicked if online).</span>
								<div class=\"clear\" style=\"margin-top:5px;\"></div>
								<div class=\"maintenance\">
									<input type=\"checkbox\" value=\"1\" name=\"banned\" style=\"min-width:1px; float:left; margin-right:10px;\"> Account is banned.
								</div>
								<div class=\"clear\"></div>
								
								<input type=\"hidden\" name=\"token\" value=\"" . md5($_SESSION["users_token"]) . "\">
								<input type=\"submit\" style=\"min-width:100px; border-radius:5px;\" value=\"Create User\">
							</form>
						</div>";
					}
				}
				elseif (isset($_GET["sub"]) && $_GET["sub"] == "edit")
				{
					if (isset($_GET["edit"]) && is_numeric($_GET["edit"]))
					{
						$user_id = trim($mysqli->real_escape_string($_GET["edit"]));
						$query_user = $mysqli->query("SELECT * FROM `users` WHERE `user_id` = '{$user_id}'");
						if ($query_user->num_rows > 0)
						{
							$quser = $query_user->fetch_array(MYSQLI_BOTH);
							
							if (isset($_GET["disconnect"]) && $quser["active"])
							{
								// disconnect
								$mysqli->query("UPDATE `users` SET `reset` = '1', `active` = '0' WHERE `user_id` = '{$quser["user_id"]}'");
								header('location: index.php?page=users&sub=edit&edit=' . $quser["user_id"] . '&success=2');
							}
							elseif (isset($_GET["removeavatar"]) && $_GET["removeavatar"] == 1)
							{
								// delete avatar
								if (file_exists("../template/avatars/uploads/{$quser["user_id"]}.jpg"))
								{
									unlink("../template/avatars/uploads/{$quser["user_id"]}.jpg");
								}
								
								// set no avatar
								$mysqli->query("UPDATE `users` SET `profile_avatar` = 'no_avatar.jpg' WHERE `user_id` = '{$quser["user_id"]}'");
								
								// redirect
								header('location: index.php?page=users&sub=edit&edit=' . $quser["user_id"] . '&success=1');						
							}
							elseif (isset($_GET["update"]) && $_GET["update"] == 1)
							{
								if (isset($_POST["token"]) && $_POST["token"] == md5($_SESSION["users_token"]))
								{
									if (!isset($_POST["banned"])){ $_POST["banned"] = 0; }
									if (!isset($_POST["apanel"])){ $_POST["apanel"] = 0; }
									
									$rank = trim($mysqli->real_escape_string($_POST["rank"]));
									$apanel = trim($mysqli->real_escape_string($_POST["apanel"]));
									$banned = trim($mysqli->real_escape_string($_POST["banned"]));
									$email = trim($mysqli->real_escape_string($_POST["email"]));
									
									if (isset($_POST["gender"]))
									{
										$gender = trim($mysqli->real_escape_string($_POST["gender"]));
									}
									else
									{
										$gender = null;
									}
									
									if (isset($_POST["bio"]))
									{
										$bio = trim($mysqli->real_escape_string($_POST["bio"]));
									}
									else
									{
										$bio = null;
									}	
									
									if (isset($_POST["avatar"]))
									{
										$avatar = trim($mysqli->real_escape_string($_POST["avatar"]));
									}
									else
									{
										$avatar = null;
									}	
									
									if ($banned == 1 && $quser["user_id"] == $user["user_id"] || $banned == 1 && $quser["user_id"] == 1)
									{
										echo "
										<div class=\"box\">
											<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
											You cannot ban this account - <a href=\"javascript:history.go(-1)\">Go Back</a> and correct this.
										</div>";								
									}
									elseif ($apanel == 0 && $quser["user_id"] == $user["user_id"] || $apanel == 0 && $quser["user_id"] == 1)
									{
										echo "
										<div class=\"box\">
											<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
											You cannot remove admin panel access for this account - <a href=\"javascript:history.go(-1)\">Go Back</a> and correct this.
										</div>";								
									}
									elseif ($rank < 3 && $quser["user_id"] == 1)
									{
										echo "
										<div class=\"box\">
											<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
											You cannot change the rank for this account - <a href=\"javascript:history.go(-1)\">Go Back</a> and correct this.
										</div>";								
									}
									else
									{		
										if ($rank == null || $email == null)
										{
											echo "
											<div class=\"box\">
												<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
												A required field was blank - <a href=\"javascript:history.go(-1)\">Go Back</a> and correct this.
											</div>";
										}
										else
										{
											if (filter_var($email, FILTER_VALIDATE_EMAIL))
											{
												if (isset($_POST["password"]) && $_POST["password"] != null)
												{
													// update password
													$password = trim($mysqli->real_escape_string($_POST["password"]));
													
													// re-encrypt
													$epw = sha1(str_rot13($password . $keys["enc_1"]));
													
													// save
													$mysqli->query("UPDATE `users` SET `user_password` = '{$epw}' WHERE `user_id` = '{$quser["user_id"]}'");
													$pw = "&pw=1";
												}
												
												// save user account
												$mysqli->query("UPDATE `users` SET `rank` = '{$rank}', `apanel` = '{$apanel}', `banned` = '{$banned}', `user_email` = '{$email}', `profile_sex` = '{$gender}', `profile_bio` = '{$bio}', `profile_avatar` = '{$avatar}' WHERE `user_id` = '{$quser["user_id"]}'");
												
												// remove token
												unset($_SESSION["users_token"]);
												
												// redirect
												header('location: index.php?page=users&sub=edit&edit=' . $quser["user_id"] . '&success=1');
											}
											else
											{
												echo "
												<div class=\"box\">
													<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
													The email format appears to be incorrect - <a href=\"javascript:history.go(-1)\">Go Back</a> and correct this.
												</div>";
											}
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
								if (!isset($_SESSION["users_token"]))
								{
									$_SESSION["users_token"] = sha1(rand(128, 5000));
								}
								
								// init admin panel
								if ($quser["apanel"] == 1)
								{
									$apanel = "CHECKED";
								}
								else
								{
									$apanel = "";
								}
								
								// init banned
								if ($quser["banned"] == 1)
								{
									$isbanned = "CHECKED";
								}
								else
								{
									$isbanned = "";
								}
								
								// init disconnect
								if ($quser["active"] == 1)
								{
									$disconnect = "
									<img src=\"template/icons/disconnect_icon.png\" style=\"float:left; width:12px; margin-right:4px; margin-top:4px;\">
									<a href=\"?page=users&sub=edit&edit={$quser["user_id"]}&disconnect=1\" style=\"text-decoration:none;\">Disconnect User</a>";
								}
								else
								{
									$disconnect = "";
								}
								
								echo "
								<div class=\"title2\" style=\"overflow:auto;\">
									<span style=\"float:left;\">Editing User: {$quser["user_name"]}</span>
								</div>
								<div class=\"editor\">
									<form action=\"index.php?page=users&sub=edit&edit={$quser["user_id"]}&update=1\" method=\"post\">
										<strong>Username</strong><br />
										<span style=\"font-size:12px;\">Cannot be changed, available for reference only.</span>
										<div class=\"clear\" style=\"margin-top:5px;\"></div>
										<input type=\"text\" DISABLED value=\"{$quser["user_name"]}\">
										<div class=\"clear\"></div>
										
										<strong>Email Address</strong><br />
										<span style=\"font-size:12px;\">The user's email address.</span>
										<div class=\"clear\" style=\"margin-top:5px;\"></div>
										<input type=\"text\" name=\"email\" value=\"{$quser["user_email"]}\">
										<div class=\"clear\"></div>
										
										<strong>User Rank</strong><br />
										<span style=\"font-size:12px;\">Set this user's chat room rank - see documentation for rank information.</span>
										<div class=\"clear\" style=\"margin-top:5px;\"></div>
										<select name=\"rank\">";
										
										if ($quser["rank"] == 1)
										{
											echo "
											<option value=\"1\">User</option>
											<option value=\"2\">Moderator</option>
											<option value=\"3\">Administrator</option>";
										}
										elseif ($quser["rank"] == 2)
										{
											echo "
											<option value=\"2\">Moderator</option>
											<option value=\"1\">User</option>
											<option value=\"3\">Administrator</option>";
										}
										elseif ($quser["rank"] == 3)
										{
											echo "
											<option value=\"3\">Administrator</option>
											<option value=\"2\">Moderator</option>
											<option value=\"1\">User</option>";
										}
										
										echo "
										</select>
										<div class=\"clear\"></div>
										
										<strong>New Password</strong><br />
										<span style=\"font-size:12px;\">Enter a new password or leave it blank for no change.</span>
										<div class=\"clear\" style=\"margin-top:5px;\"></div>
										<input type=\"text\" name=\"password\" placeholder=\"Enter a new password here...\">
										<div class=\"clear\"></div>
										
										<strong>Admin Panel Access</strong><br />
										<span style=\"font-size:12px;\">Select whether or not this user can access the admin panel.</span>
										<div class=\"clear\" style=\"margin-top:5px;\"></div>
										<div class=\"maintenance\">
											<input type=\"checkbox\" value=\"1\" $apanel name=\"apanel\" style=\"min-width:1px; float:left; margin-right:10px;\"> Can access admin panel.
										</div>
										<div class=\"clear\"></div>
										
										<strong>Account Banned</strong><br />
										<span style=\"font-size:12px;\">Select whether or not this user is banned from the chat (user will be kicked if online).</span>
										<div class=\"clear\" style=\"margin-top:5px;\"></div>
										<div class=\"maintenance\">
											<input type=\"checkbox\" value=\"1\" $isbanned name=\"banned\" style=\"min-width:1px; float:left; margin-right:10px;\"> Account is banned.
										</div>
										<div class=\"clear\"></div>
										
										<strong>Profile Gender</strong><br />
										<span style=\"font-size:12px;\">The user's profile gender</span>
										<div class=\"clear\" style=\"margin-top:5px;\"></div>
										<select name=\"gender\">";
										
										if ($quser["profile_sex"] == 1)
										{
											echo "
											<option value=\"1\">Male</option>
											<option value=\"2\">Female</option>
											<option value=\"3\">Undisclosed</option>";
										}
										elseif ($quser["profile_sex"] == 2)
										{
											echo "
											<option value=\"2\">Female</option>
											<option value=\"1\">Male</option>
											<option value=\"3\">Undisclosed</option>";
										}
										else
										{
											echo "
											<option value=\"3\">Undisclosed</option>
											<option value=\"1\">Male</option>
											<option value=\"2\">Female</option>";
										}
										
										echo "</select>
										<div class=\"clear\"></div>
										
										<strong>Profile Bio</strong><br />
										<span style=\"font-size:12px;\">Update the user's profile bio</span>
										<div class=\"clear\" style=\"margin-top:5px;\"></div>
										<textarea name=\"bio\">" . nl2br($quser["profile_bio"]) . "</textarea>
										<div class=\"clear\"></div>
										
										<strong>Profile Avatar</strong><br />
										<span style=\"font-size:12px;\">`example.png` will try to load template/avatars/example.png</span>
										<div class=\"clear\" style=\"margin-top:5px;\"></div>
										<input type=\"text\" name=\"avatar\" value=\"{$quser["profile_avatar"]}\">
										<div class=\"clear\"></div>
										
										<input type=\"hidden\" name=\"token\" value=\"" . md5($_SESSION["users_token"]) . "\">
										<input type=\"submit\" style=\"min-width:100px; border-radius:5px;\" value=\"Save User\">
									</form>
								</div>";
							}
						}
						else
						{
							echo "
							<div class=\"box\">
								<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
								The requested account does not exist - <a href=\"index.php?page=users\">Back to users</a>
							</div>";
						}
					}
					else
					{
						echo "
						<div class=\"box\">
							<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
							The requested account does not exist - <a href=\"index.php?page=users\">Back to users</a>
						</div>";
					}
				}
				elseif (isset($_GET["sub"]) && $_GET["sub"] == "delete")
				{
					if (isset($_GET["delete"]) && is_numeric($_GET["delete"]))
					{
						$user_id = trim($mysqli->real_escape_string($_GET["delete"]));
						$query_user = $mysqli->query("SELECT user_id, rank, user_name, user_ip FROM `users` WHERE `user_id` = '{$user_id}'");
						if ($user_id != 1)
						{
							if ($query_user->num_rows > 0)
							{
								$quser = $query_user->fetch_array(MYSQLI_BOTH);
								if (isset($_GET["confirm"]))
								{
									if (isset($_SESSION["delete_key"]) && $_SESSION["delete_key"] == $_POST["deletekey"])
									{
										if (isset($_POST["deleteip"]))
										{
											// delete accounts with the same IP
											$query_ips = $mysqli->query("SELECT user_id, user_ip FROM `users` WHERE `user_ip` = '{$quser["user_ip"]}' AND `user_id` != {$quser["user_id"]}");
											if ($query_ips->num_rows > 0)
											{
												while($ip = $query_ips->fetch_assoc())
												{
													$mysqli->query("DELETE FROM `users` WHERE `user_ip` = '{$ip["user_ip"]}'");
												}
											}
											
											//echo "Deleted IPs";
										}
										if (isset($_POST["blip"]))
										{
											// blacklist the IP address
											$query_blacklist = $mysqli->query("SELECT * FROM `blacklist` WHERE `blacklist_ip` = '{$quser["user_ip"]}'");
											if ($query_blacklist->num_rows < 1)
											{
												// blacklist ip
												$mysqli->query("INSERT INTO `blacklist` (`blacklist_ip`) VALUES ('{$quser["user_ip"]}')") or die($mysqli->error);
											}
											
											//echo "Blacklisted";
										}
										
										// delete the account
										$mysqli->query("DELETE FROM `users` WHERE `user_id` = '{$quser["user_id"]}'");
										
										// redirect
										header('location: index.php?page=users&deleted=1');
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
										Are you sure you wish to delete <strong>{$quser["user_name"]}</strong>?<br />
										<span style=\"font-size:11px;\">This user will be permanently deleted from the website and will be required to re-register.</span>
										<div class=\"clear\"></div>
									
										<form action=\"index.php?page=users&sub=delete&delete={$quser["user_id"]}&confirm=1\" method=\"post\">
											<div class=\"maintenance\">
												<input type=\"checkbox\" name=\"deleteip\" value=\"1\" style=\"min-width:1px; float:left; margin-right:10px;\"> Remove accounts with same I.P
											</div>
											<div class=\"maintenance\">
												<input type=\"checkbox\" name=\"blip\" value=\"1\" style=\"min-width:1px; float:left; margin-right:10px;\"> Add I.P address to blacklist
											</div>
											<div class=\"clear\"></div>
											
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
									The requested account does not exist - <a href=\"index.php?page=users\">Back to users</a>
								</div>";
							}
						}
						else
						{
							echo "
							<div class=\"box\">
								<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
								You cannot delete this account - <a href=\"index.php?page=users\">Back to users</a>
							</div>";					
						}
					}
				}
				elseif (isset($_GET["sub"]) && $_GET["sub"] == "blacklist")
				{
					if (isset($_GET["new"]) && $_GET["new"] == 1)
					{
						if (isset($_GET["create"]))
						{
							if (isset($_POST["ip"]) && trim($_POST["ip"]) != null)
							{
								$ip = trim($mysqli->real_escape_string($_POST["ip"]));
								if (filter_var($ip, FILTER_VALIDATE_IP))
								{
									// check if it exists
									$query_blacklist = $mysqli->query("SELECT * FROM `blacklist` WHERE `blacklist_ip` = '{$ip}'");
									if ($query_blacklist->num_rows > 0)
									{
										echo "
										<div class=\"box\">
											<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
											That I.P address is already blacklisted - <a href=\"index.php?page=users&sub=blacklist&new=1\">Back to blacklist</a>
										</div>";									
									}
									else
									{
										// insert to blacklist
										$mysqli->query("INSERT INTO `blacklist` (`blacklist_ip`) VALUES ('$ip')");
										
										// redirect
										header('location: index.php?page=users&sub=blacklist&created=1');
									}
								}
								else
								{
									echo "
									<div class=\"box\">
										<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
										The content entered does not appear to be a valid I.P address - <a href=\"index.php?page=users&sub=blacklist&new=1\">Back to blacklist</a>
									</div>";									
								}
							}
							else
							{
								echo "
								<div class=\"box\">
									<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
									You cannot create a blank entry - <a href=\"index.php?page=users&sub=blacklist&new=1\">Back to blacklist</a>
								</div>";									
							}
						}
						else
						{
							echo "
							<div class=\"title2\">
								Adding a new blacklist entry
							</div>
							<div class=\"editor\">
								<form action=\"index.php?page=users&sub=blacklist&new=1&create=1\" method=\"post\">
									<strong>I.P Address</strong><br />
									<span style=\"font-size:12px;\">Ranges are not supported, please enter the full I.P address.</span>
									<div class=\"clear\" style=\"margin-top:5px;\"></div>
									<input type=\"text\" name=\"ip\">
									<div class=\"clear\"></div>		
									<input type=\"submit\" style=\"min-width:100px; border-radius:5px;\" value=\"Create Entry\">
								</form>
							</div>";
						}
					}
					elseif (isset($_GET["delete"]) && is_numeric($_GET["delete"]))
					{
						$delete = trim($mysqli->real_escape_string($_GET["delete"]));
						$query_ips = $mysqli->query("SELECT * FROM `blacklist` WHERE `blacklist_id` = '{$delete}'");
						if ($query_ips->num_rows > 0)
						{
							$mysqli->query("DELETE FROM `blacklist` WHERE `blacklist_id` = '{$delete}'");
							
							// redirect
							header('location: index.php?page=users&sub=blacklist&removed=1');
						}
						else
						{
							echo "
							<div class=\"box\">
								<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
								The IP you're trying to delete doesn't exist - <a href=\"index.php?page=users&sub=blacklist\">Back to blacklist</a>
							</div>";							
						}
					}
					else
					{
						// list ips
						echo "
						<div class=\"title2\" style=\"overflow:auto;\">
							<span style=\"float:left;\">I.P Blacklist</span>
							<span style=\"float:right; font-size:13px; margin-top:4px;\">
								<img src=\"template/icons/new_icon.png\" style=\"float:left; margin-right:5px; margin-top:3px; width:12px;\"> 
								<a href=\"?page=users&sub=blacklist&new=1\" style=\"text-decoration:none;\">Add I.P to blacklist</a>
							</span>
						</div>";
						
						// pagination
						$table = "blacklist";
						$list = "?page=users&sub=blacklist";
						$extra = "";
						$limit = 10;

						include("../core/paginate.php");
						
						$query_ips = $mysqli->query("SELECT * FROM `$table` ORDER BY abs(`blacklist_id`) DESC LIMIT $start, $limit");
						if ($query_ips->num_rows > 0)
						{
							echo "
							<table class=\"forum\">
								<tr>
									<th>Blacklisted I.P Address</th>
									<th style=\"width:60px;\"></th>
								</tr>
							";
							
							while($blacklist = $query_ips->fetch_assoc())
							{
								echo "
								<tr>
									<td>
										{$blacklist["blacklist_ip"]}
									</td>
									<td style=\"text-align:center;\">
										<a href=\"index.php?page=users&sub=blacklist&delete={$blacklist["blacklist_id"]}\"><img src=\"template/icons/delete_icon.png\"></a>
									</td>
								</tr>";
							}
							
							echo "</table>";
							echo $paginate;
						}
						else
						{
							echo "No blacklist entries found! <a href=\"index.php?page=users&sub=blacklist&new=1\">Add a new entry</a>.";
						}
					}
				}
				elseif (isset($_GET["search"]))
				{
					// search
					$search = trim($mysqli->real_escape_string($_POST["search"]));
					if ($search != null && $search != "%")
					{
						// pagination
						$table = "users";
						$list = "?page=users";
						$extra = "";
						$limit = 10;

						include("../core/paginate.php");
						
						echo "
						<div class=\"title2\" style=\"overflow:auto; float:left;\">
							Search: {$search}
						</div>
						<span style=\"float:right; margin-top:-10px;\">
							<form action=\"index.php?page=users&search=1\" method=\"post\">
								<input type=\"text\" name=\"search\" placeholder=\"Search for a user...\">
								<input type=\"submit\" value=\"Search\">
							</form>
						</span>
						";
						
						$search_users = $mysqli->query("SELECT user_id, user_name, rank, active FROM `$table` WHERE user_name LIKE '{$search}%' ORDER BY abs(`user_id`) ASC LIMIT $start, $limit");
						if ($search_users->num_rows > 0)
						{	
							echo "
							<table class=\"forum\">
								<tr>
									<th style=\"width:40px;\"></th>
									<th>User Name</th>
									<th style=\"width:60px; text-align:center;\">Active</th>
									<th style=\"width:60px;\"></th>
								</tr>
							";
							
							while($quser = $search_users->fetch_assoc())
							{
								if ($quser["rank"] == 2)
								{
									$rank = "<img src=\"template/icons/moderator_icon.png\" title=\"Moderator\">";
								}
								
								if ($quser["rank"] == 3)
								{
									$rank = "<img src=\"template/icons/admin_icon.png\" title=\"Administrator\">";
								}
								
								if ($quser["rank"] == 1)
								{
									$rank = "<img src=\"template/icons/user_icon.png\" title=\"User\">";
								}
								
								if ($quser["active"] == 1)
								{
									$active = "<img src=\"template/icons/online_icon.png\" title=\"Online\">";
								}
								else
								{
									$active = "<img src=\"template/icons/offline_icon.png\" title=\"Offline\">";
								}
								
								echo "
								<tr>
									<td style=\"text-align:center;\">
										{$rank}
									</td>
									<td>
										<a href=\"index.php?page=users&sub=edit&edit={$quser["user_id"]}\">{$quser["user_name"]}</a>
									</td>
									<td style=\"text-align:center;\">
										{$active}
									</td>
									<td style=\"text-align:center;\">
										<a href=\"index.php?page=users&sub=edit&edit={$quser["user_id"]}\"><img src=\"template/icons/edit_icon.png\"></a>
										<a href=\"index.php?page=users&sub=delete&delete={$quser["user_id"]}\"><img src=\"template/icons/delete_icon.png\"></a>
									</td>
								</tr>";
								
								unset($rank, $active);
							}
							
							echo "</table>";
							echo $paginate;						
						}
						else
						{
							echo "
							<div class=\"box\" style=\"margin-top:40px;\">
								<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
								No users were found - <a href=\"index.php?page=users\">Back to users</a>
							</div>";							
						}
					}
					else
					{
						echo "
						<div class=\"box\">
							<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
							Your search entry was empty - <a href=\"index.php?page=users\">Back to users</a>
						</div>";								
					}
				}
				elseif (isset($_GET["sub"]) && $_GET["sub"] == "guests")
				{
					if (isset($_GET["kick"]))
					{
						$gid = $mysqli->real_escape_string(trim($_GET["kick"]));
						$query_guest = $mysqli->query("SELECT guest_id FROM `guests` WHERE `guest_id` = '{$gid}'");
						if ($query_guest->num_rows > 0)
						{
							// Kick Guest
							$mysqli->query("UPDATE `guests` SET `active` = '0', `kicked` = '1' WHERE `guest_id` = '{$gid}'");
							header('location: index.php?page=users&sub=guests&success=3');
						}
						else
						{
							header('location: index.php?page=users&sub=guests&error=1');
						}	
					}
					elseif (isset($_GET["ban"]))
					{
						$gid = $mysqli->real_escape_string(trim($_GET["ban"]));
						$query_guest = $mysqli->query("SELECT guest_id, guest_ip FROM `guests` WHERE `guest_id` = '{$gid}'");
						if ($query_guest->num_rows > 0)
						{
							// Ban Guest
							$guest = $query_guest->fetch_array();
							$mysqli->query("INSERT INTO `blacklist` (blacklist_ip) VALUES ('{$guest["guest_ip"]}')");
							$mysqli->query("UPDATE `guests` SET `active` = '0', `banned` = '1' WHERE `guest_id` = '{$gid}'");
							header('location: index.php?page=users&sub=guests&success=4');
						}
						else
						{
							header('location: index.php?page=users&sub=guests&error=1');
						}						
					}
					else
					{
						// list users
						echo "
						<div class=\"title2\" style=\"overflow:auto; float:left;\">
							Guest Management {$heading}
						</div>
						";
					
						// pagination
						$table = "guests";
						$list = "?page=users&sub=guests";
						$extra = "WHERE `active` = '1'";
						$limit = 10;

						include("../core/paginate.php");
						
						$query_users = $mysqli->query("SELECT guest_id, guest_name, guest_ip, active FROM `$table` $extra ORDER BY abs(`guest_id`) ASC LIMIT $start, $limit");
						if ($query_users->num_rows > 0)
						{
							echo "
							<table class=\"forum\">
								<tr>
									<th style=\"width:40px;\"></th>
									<th>Guest Name</th>
									<th style=\"width:80px;\"></th>
								</tr>
							";
							
							while($quser = $query_users->fetch_assoc())
							{
								echo "
								<tr>
									<td style=\"text-align:center;\">
										<img src=\"../template/avatars/no_avatar.jpg\" style=\"width:32px; height:32px;\">
									</td>
									<td>
										{$quser["guest_name"]} ({$quser["guest_ip"]})
									</td>
									<td style=\"text-align:center;\">
										<a href=\"index.php?page=users&sub=guests&kick={$quser["guest_id"]}\"><img src=\"template/icons/offline_icon.png\" title=\"Kick Guest\"></a>
										<a href=\"index.php?page=users&sub=guests&ban={$quser["guest_id"]}\"><img src=\"template/icons/delete_icon.png\" title=\"Ban Guest\"></a>
									</td>
								</tr>";
								
								unset($rank, $active);
							}
							
							echo "</table>";
						}
						else
						{
							echo "<br /><br />No guests found.";
						}
					}
				}
				else
				{
					if (isset($_GET["sub"]) && $_GET["sub"] == "moderators")
					{
						$extra = "WHERE `rank` > 1";
						$heading = "(Moderators)";
					}
					else
					{
						$extra = "";
						$heading = "";
					}
					
					// list users
					echo "
					<div class=\"title2\" style=\"overflow:auto; float:left;\">
						User Management {$heading}
					</div>
					<span style=\"float:right; margin-top:-10px;\">
						<form action=\"index.php?page=users&search=1\" method=\"post\">
							<input type=\"text\" name=\"search\" placeholder=\"Search for a user...\">
							<input type=\"submit\" value=\"Search\">
						</form>
					</span>
					";
				
					// pagination
					$table = "users";
					$list = "?page=users";
					$limit = 10;

					include("../core/paginate.php");
					
					$query_users = $mysqli->query("SELECT user_id, user_name, rank, active, profile_avatar FROM `$table` $extra ORDER BY abs(`user_id`) ASC LIMIT $start, $limit");
					if ($query_users->num_rows > 0)
					{
						echo "
						<table class=\"forum\">
							<tr>
								<th style=\"width:40px;\"></th>
								<th>User Name</th>
								<th style=\"width:60px; text-align:center;\">Active</th>
								<th style=\"width:80px;\"></th>
							</tr>
						";
						
						while($quser = $query_users->fetch_assoc())
						{
							if (file_exists("../template/avatars/{$quser["profile_avatar"]}"))
							{
								$avaurl = $quser["profile_avatar"];
							}
							else
							{
								$avaurl = "no_avatar.jpg";
							}
							
							if ($quser["rank"] == 2)
							{
								$rank = "<img src=\"template/icons/moderator_icon.png\" title=\"Moderator\">";
							}
							
							if ($quser["rank"] == 3)
							{
								$rank = "<img src=\"template/icons/admin_icon.png\" title=\"Administrator\">";
							}
							
							if ($quser["rank"] == 1)
							{
								$rank = "<img src=\"template/icons/user_icon.png\" title=\"User\">";
							}
							
							if ($quser["active"] == 1)
							{
								$active = "<img src=\"template/icons/online_icon.png\" title=\"Online\">";
							}
							else
							{
								$active = "<img src=\"template/icons/offline_icon.png\" title=\"Offline\">";
							}
							
							echo "
							<tr>
								<td style=\"text-align:center;\">
									<img src=\"../template/avatars/{$avaurl}\" style=\"width:32px; height:32px;\">
								</td>
								<td>
									<a href=\"index.php?page=users&sub=edit&edit={$quser["user_id"]}\">{$quser["user_name"]}</a>
								</td>
								<td style=\"text-align:center;\">
									{$active}
								</td>
								<td style=\"text-align:center;\">
									<a href=\"index.php?page=users&sub=edit&edit={$quser["user_id"]}\"><img src=\"template/icons/edit_icon.png\" title=\"Edit Account\"></a>
									<a href=\"index.php?page=users&sub=delete&delete={$quser["user_id"]}\"><img src=\"template/icons/delete_icon.png\" title=\"Delete Account\"></a>
									<a href=\"index.php?page=history&user={$quser["user_id"]}\" target=\"_blank\"><img src=\"template/icons/chat_icon.png\" title=\"Chat History\"></a>
								</td>
							</tr>";
							
							unset($rank, $active);
						}
						
						echo "</table>";
						
						echo $paginate;
					}
					else
					{
						echo "No users found.";
					}
				}
			?>
		</div>
		
		<?php 
		if (isset($_GET["sub"]) && $_GET["sub"] == "edit")
		{
			echo "
			<div class=\"left_menu\">
				<a href=\"index.php?page=users&sub=edit&edit={$_GET["edit"]}&disconnect=1\"><img src=\"template/icons/disconnect_icon.png\"> Disconnect User</a>
				<a href=\"index.php?page=users&sub=delete&delete={$_GET["edit"]}\"><img src=\"template/icons/delete_icon.png\"> Delete Account</a>
				<a href=\"index.php?page=users&sub=edit&edit={$_GET["edit"]}&removeavatar=1\"><img src=\"template/icons/avatar_icon.png\"> Delete Avatar</a>
				<a href=\"index.php?page=history&user={$_GET["edit"]}\" target=\"_blank\"><img src=\"template/icons/chat_icon.png\"> Chat History</a>
				<a href=\"../profile.php?uid={$_GET["edit"]}\" target=\"_blank\"><img src=\"template/icons/user_icon.png\"> View Profile</a>
				<a href=\"index.php?page=users\"><img src=\"template/icons/manage_icon.png\"> User Management</a>
			</div>";
		}
		else
		{
		?>
		<div class="left_menu">
			<a href="index.php?page=users"><img src="template/icons/manage_icon.png"> User Management</a>
			<a href="index.php?page=users&sub=create"><img src="template/icons/new_icon.png"> Create New User</a>
			<a href="index.php?page=users&sub=moderators"><img src="template/icons/moderator_icon.png"> Chat Moderators</a>
			<a href="index.php?page=users&sub=guests"><img src="template/icons/online_icon.png"> Guest Manager</a>
			<a href="index.php?page=users&sub=blacklist"><img src="template/icons/blacklist_icon.png"> I.P Blacklist</a>
			<a href="docs/users.html" target="_blank"><img src="template/icons/info_icon.png"> Users Documentation</a>
		</div>
		<?php 
		}
		?>
	</div>

<?php endif; ?>