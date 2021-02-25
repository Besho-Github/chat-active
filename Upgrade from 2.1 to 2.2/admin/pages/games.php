<?php
	/**
	 * readyChat 2.2.0 release
	 * Software by DesignSkate
	 */
	 
	// Logged in checker
	if (!$user["apanel"]): header('location: ../index.php'); die(); else:
	
	if (isset($_GET["success"]) && $_GET["success"] == 1)
	{
		$success = "<div class=\"success_head\">Game Saved.</div>";
	}
	elseif (isset($_GET["deleted"]))
	{
		$success = "<div class=\"success_head\">Game deleted.</div>";
	}
	else
	{
		$success = "";
	}
	
	echo $success;
	
	if (!$settings["games"])
	{
		$enabled = "<div class=\"info_head\">Games are not enabled, <a href=\"index.php?page=settings&sub=games\">click here</a> to enable this feature.</div>";
	}
	else
	{
		$enabled = null;
	}
	
	echo $enabled;
?>
	<div id="content">
		<div class="title">
			<img src="template/images/logo.png">
		</div>
		<div class="right_menu">
		
			<?php
				if (isset($_GET["sub"]) && $_GET["sub"] == "new")
				{
					if (isset($_GET["submit"]) && $_GET["submit"] == 1)
					{
						if (isset($_POST["token"]) && $_POST["token"] == md5($_SESSION["games_token"]))
						{
							$title = trim($mysqli->real_escape_string($_POST["title"]));
							$swf = trim($mysqli->real_escape_string($_POST["swf"]));
							$icon = trim($mysqli->real_escape_string($_POST["icon"]));
							$width = trim($mysqli->real_escape_string($_POST["width"]));
							$height = trim($mysqli->real_escape_string($_POST["height"]));
							
							if ($title == null || $swf == null || $icon == null || $width == null || $height == null)
							{
								echo "
								<div class=\"box\">
									<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
									Please do not leave any fields blank! <a href=\"javascript:history.go(-1)\">Go Back</a> and correct this.
								</div>";										
							}
							else
							{
								// save game
								$mysqli->query("INSERT INTO `flash_games` (game_title, game_swf, game_icon, width, height) VALUES ('{$title}', '{$swf}', '{$icon}', '{$width}', '{$height}')");
								
								// redirect
								header('location: index.php?page=games&success=1');
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
						if (!isset($_SESSION["games_token"]))
						{
							$_SESSION["games_token"] = sha1(rand(128, 5000));
						}
						
						echo "
						<div class=\"title2\" style=\"overflow:auto;\">
							<span style=\"float:left;\">Add New Game</span>
						</div>
						<div class=\"editor\">
							<form action=\"index.php?page=games&sub=new&submit=1\" method=\"post\">
								<strong>Game Title</strong><br />
								<span style=\"font-size:12px;\">The game title as it appears on games menu.</span>
								<div class=\"clear\" style=\"margin-top:5px;\"></div>
								<input type=\"text\" name=\"title\" maxlength=\"35\">
								<div class=\"clear\"></div>
								
								<strong>Game SWF</strong><br />
								<span style=\"font-size:12px;\">The location of the game's SWF file (can be external)</span>
								<div class=\"clear\" style=\"margin-top:5px;\"></div>
								<input type=\"text\" name=\"swf\" maxlength=\"300\">
								<div class=\"clear\"></div>
								
								<strong>Game Icon</strong><br />
								<span style=\"font-size:12px;\">The location of the game's icon file (can be external)</span>
								<div class=\"clear\" style=\"margin-top:5px;\"></div>
								<input type=\"text\" name=\"icon\" maxlength=\"300\">
								<div class=\"clear\"></div>
								
								<strong>Game Width</strong><br />
								<span style=\"font-size:12px;\">The width of the game (in pixels)</span>
								<div class=\"clear\" style=\"margin-top:5px;\"></div>
								<input type=\"text\" name=\"width\" maxlength=\"4\">
								<div class=\"clear\"></div>
								
								<strong>Game Height</strong><br />
								<span style=\"font-size:12px;\">The height of the game (in pixels)</span>
								<div class=\"clear\" style=\"margin-top:5px;\"></div>
								<input type=\"text\" name=\"height\" maxlength=\"4\">
								<div class=\"clear\"></div>
								
								<input type=\"hidden\" name=\"token\" value=\"" . md5($_SESSION["games_token"]) . "\">
								<input type=\"submit\" style=\"min-width:100px; border-radius:5px;\" value=\"Add Game\">
							</form>
						</div>";
					}	
				}
				elseif (isset($_GET["sub"]) && $_GET["sub"] == "edit")
				{
					if (isset($_GET["edit"]) && is_numeric($_GET["edit"]))
					{
						$game_id = trim($mysqli->real_escape_string($_GET["edit"]));
						$query_game = $mysqli->query("SELECT * FROM `flash_games` WHERE `game_id` = '{$game_id}'");
						if ($query_game->num_rows > 0)
						{
							$qgame = $query_game->fetch_array(MYSQLI_BOTH);
							
							if (isset($_GET["update"]) && $_GET["update"] == 1)
							{
								if (isset($_POST["token"]) && $_POST["token"] == md5($_SESSION["games_token"]))
								{
									$title = trim($mysqli->real_escape_string($_POST["title"]));
									$swf = trim($mysqli->real_escape_string($_POST["swf"]));
									$icon = trim($mysqli->real_escape_string($_POST["icon"]));
									$width = trim($mysqli->real_escape_string($_POST["width"]));
									$height = trim($mysqli->real_escape_string($_POST["height"]));
									
									if ($title == null || $swf == null || $icon == null || $width == null || $height == null)
									{
										echo "
										<div class=\"box\">
											<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
											Please do not leave any fields blank! <a href=\"javascript:history.go(-1)\">Go Back</a> and correct this.
										</div>";										
									}
									else
									{
										// save game
										$mysqli->query("UPDATE `flash_games` SET `game_title` = '{$title}', `game_swf` = '{$swf}', `game_icon` = '{$icon}', `width` = '{$width}', `height` = '{$height}' WHERE `game_id` = '{$qgame["game_id"]}'");
										
										// redirect
										header('location: index.php?page=games&sub=edit&edit=' . $qgame["game_id"] . '&success=1');
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
								if (!isset($_SESSION["games_token"]))
								{
									$_SESSION["games_token"] = sha1(rand(128, 5000));
								}
								
								echo "
								<div class=\"title2\" style=\"overflow:auto;\">
									<span style=\"float:left;\">Editing Game: {$qgame["game_title"]}</span>
								</div>
								<div class=\"editor\">
									<form action=\"index.php?page=games&sub=edit&edit={$qgame["game_id"]}&update=1\" method=\"post\">
										<strong>Game Title</strong><br />
										<span style=\"font-size:12px;\">The game title as it appears on games menu.</span>
										<div class=\"clear\" style=\"margin-top:5px;\"></div>
										<input type=\"text\" name=\"title\" maxlength=\"35\" value=\"{$qgame["game_title"]}\">
										<div class=\"clear\"></div>
										
										<strong>Game SWF</strong><br />
										<span style=\"font-size:12px;\">The location of the game's SWF file (can be external)</span>
										<div class=\"clear\" style=\"margin-top:5px;\"></div>
										<input type=\"text\" name=\"swf\" maxlength=\"300\" value=\"{$qgame["game_swf"]}\">
										<div class=\"clear\"></div>
										
										<strong>Game Icon</strong><br />
										<span style=\"font-size:12px;\">The location of the game's icon file (can be external)</span>
										<div class=\"clear\" style=\"margin-top:5px;\"></div>
										<input type=\"text\" name=\"icon\" maxlength=\"300\" value=\"{$qgame["game_icon"]}\">
										<div class=\"clear\"></div>
										
										<strong>Game Width</strong><br />
										<span style=\"font-size:12px;\">The width of the game (in pixels)</span>
										<div class=\"clear\" style=\"margin-top:5px;\"></div>
										<input type=\"text\" name=\"width\" maxlength=\"4\" value=\"{$qgame["width"]}\">
										<div class=\"clear\"></div>
										
										<strong>Game Height</strong><br />
										<span style=\"font-size:12px;\">The height of the game (in pixels)</span>
										<div class=\"clear\" style=\"margin-top:5px;\"></div>
										<input type=\"text\" name=\"height\" maxlength=\"4\" value=\"{$qgame["height"]}\">
										<div class=\"clear\"></div>
										
										<input type=\"hidden\" name=\"token\" value=\"" . md5($_SESSION["games_token"]) . "\">
										<input type=\"submit\" style=\"min-width:100px; border-radius:5px;\" value=\"Save Game\">
									</form>
								</div>";
							}
						}
						else
						{
							echo "
							<div class=\"box\">
								<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
								The requested game does not exist - <a href=\"index.php?page=games\">Back to games</a>
							</div>";
						}
					}
					else
					{
						echo "
						<div class=\"box\">
							<div class=\"title\" style=\"margin-top:0px; margin-bottom:10px;\">Error</div>
							The requested game does not exist - <a href=\"index.php?page=games\">Back to games</a>
						</div>";
					}
				}
				elseif (isset($_GET["sub"]) && $_GET["sub"] == "delete")
				{
					if (isset($_GET["delete"]) && is_numeric($_GET["delete"]))
					{
						$game_id = trim($mysqli->real_escape_string($_GET["delete"]));
						$query_games = $mysqli->query("SELECT * FROM `flash_games` WHERE `game_id` = '{$game_id}'");
						if ($query_games->num_rows > 0)
						{
							$qgame = $query_games->fetch_array(MYSQLI_BOTH);
							if (isset($_GET["confirm"]))
							{
								if (isset($_SESSION["delete_key"]) && $_SESSION["delete_key"] == $_POST["deletekey"])
								{
									// delete the game
									$mysqli->query("DELETE FROM `flash_games` WHERE `game_id` = '{$qgame["game_id"]}'");
									
									// redirect
									header('location: index.php?page=games&deleted=1');
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
									Are you sure you wish to delete <strong>{$qgame["game_title"]}</strong>?<br />
									<span style=\"font-size:11px;\">This game will be permanently deleted from the website.</span>
									<div class=\"clear\"></div>
								
									<form action=\"index.php?page=games&sub=delete&delete={$qgame["game_id"]}&confirm=1\" method=\"post\">
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
								The requested game does not exist - <a href=\"index.php?page=games\">Back to Games</a>
							</div>";
						}
					}
				}
				else
				{
					// list users
					echo "
					<div class=\"title2\">
						Flash Games
					</div>";
				
					// pagination
					$table = "flash_games";
					$list = "?page=games";
					$limit = 10;
					$extra = "";

					include("../core/paginate.php");
					
					$query_games = $mysqli->query("SELECT * FROM `$table` $extra ORDER BY abs(`game_id`) ASC LIMIT $start, $limit");
					if ($query_games->num_rows > 0)
					{
						echo "
						<table class=\"forum\">
							<tr>
								<th style=\"width:40px;\"></th>
								<th>Game Title</th>
								<th style=\"width:100px; text-align:center;\">Times Played</th>
								<th style=\"width:60px;\"></th>
							</tr>
						";
						
						while($game = $query_games->fetch_assoc())
						{
							echo "
							<tr>
								<td style=\"text-align:center;\">
									<img src=\"{$game["game_icon"]}\" width=\"60px\" style=\"border-radius:3px; float:left; margin-top:3px;\">
								</td>
								<td>
									<a href=\"index.php?page=games&sub=edit&edit={$game["game_id"]}\">{$game["game_title"]}</a>
								</td>
								<td style=\"text-align:center;\">
									{$game["plays"]}
								</td>
								<td style=\"text-align:center;\">
									<a href=\"index.php?page=games&sub=edit&edit={$game["game_id"]}\"><img src=\"template/icons/edit_icon.png\"></a>
									<a href=\"index.php?page=games&sub=delete&delete={$game["game_id"]}\"><img src=\"template/icons/delete_icon.png\"></a>
								</td>
							</tr>";
						}
						
						echo "</table>";
						echo $paginate;
					}
					else
					{
						echo "No games found.";
					}
				}
			?>
		</div>
		
		<div class="left_menu">
			<a href="index.php?page=games"><img src="template/icons/manage_icon.png"> Games Management</a>
			<a href="index.php?page=games&sub=new"><img src="template/icons/new_icon.png"> Add Game</a>
			<a href="docs/games.html" target="_blank"><img src="template/icons/info_icon.png"> Games Documentation</a>
		</div>
	</div>

<?php endif; ?>