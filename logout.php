<?php
session_start();
if(session_destroy()){
	setcookie("PHPSESSID", "", time() - 61200,"/");
	header('Location: https://server.com/assign2/login.php');
}
?>