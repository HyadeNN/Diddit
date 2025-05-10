<?php 

session_start();

if(isset($_SESSION['diddit_userid']))
{
	$_SESSION['diddit_userid'] = NULL;
	unset($_SESSION['diddit_userid']);

}

header("Location: login.php");
die;
