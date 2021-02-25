<?php
	/*
		ReadyChat 2.2.0
		Configuration
	*/
	
	ob_start();
	error_reporting(0);
	
	# Access permissions
	define('access', true);
	
	# Start Sessions
	session_start();
	
	$config = array(
		"keys" => array(),
		"time" => time(),
		"micro" => round(microtime(true) * 1000),
		"ver_num" => "2.2.0"
	);
	
	$time = time();
	
	# Connect to the MySQL database
	require("database.inc.php");
	
	if (!isset($DB_HOST))
	{
		header('location: ./install/');
		exit();	
	} 
	
	$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
	
	# Connection Check / Installation Check
	if (mysqli_connect_errno() || !isset($installed)) 
	{
		if (!isset($installed))
		{
			header('location: ./install/');
			exit();
		}
		else
		{
			die("MySQL connection error");
		}
	}

	# Settings Array
	$settings = mysqli_fetch_array($mysqli->query("SELECT * FROM `settings`"));
	
	# User Sessions
	if (!isset($_SESSION["readyChatUser"]) || $_SESSION["readyChatUser"] == null)
	{
		$override = false;
		$uSession = null;
	}
	else
	{
		$uSession = 1;
		$override = false;
		$user = mysqli_fetch_array($mysqli->query("SELECT * FROM `users` WHERE `user_name` = '{$_SESSION["readyChatUser"]}'"));
		if (!isset($user["user_id"]))
		{
			unset($_SESSION["readyChatUser"]);
			header('location: login.php');
		}
	}
	
	# Guest Session Configuration
	if (!isset($_SESSION["readyChatGuest"]) || $_SESSION["readyChatGuest"] == null)
	{
		$override = false;
		$gSession = null;
	}
	else
	{
		$gSession = 1;
		$override = true;
		$guest = mysqli_fetch_array($mysqli->query("SELECT * FROM `guests` WHERE `guest_name` = '{$_SESSION["readyChatGuest"]}'"));
		
		if (!isset($guest["guest_id"]))
		{
			unset($_SESSION["readyChatGuest"]);
			header('location: login.php');
		}
	}
	
	# Website Timezone Configuration
	date_default_timezone_set('Europe/London');
?>
	