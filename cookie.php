<?php

	if(isset($_GET)){

		setcookie('sort',  $_GET['sort'], time()+60*60*24*30);
	
	}
	
	header("location: https://server.com/assign2/view.php");

?>