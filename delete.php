<?php

/*
Subject Code and Section: BTI320B
 Student Name: Eldon Lai
 Date Submitted:  Oct 14/2014
Student Declaration
I/we declare that the attached assignment is my/our own work in accordance with Seneca Academic Policy. No part of this assignment has been copied manually or electronically from any other source (including web sites) or distributed to other students.
Name Eldon Lai
Student ID 053931127
*/

	if (!isset($_SERVER['HTTPS']) || !$_SERVER['HTTPS']) {
		// request is not using SSL, redirect to https, or fail
		header('Location: https://server.com/assign2/delete.php');
	}

	$lines = file('/home/int322/secret/topsecret');
	$dbserver = trim($lines[0]);
	$uid = trim($lines[1]);
	$pw = trim($lines[2]);
	$dbname = trim($lines[3]);
	
	//Connect to the mysql server and get back our link_identifier
 	$link = mysqli_connect($dbserver, $uid, $pw, $dbname)
        	or die('Could not connect: ' . mysqli_error($link));
		
	session_start();// Starting Session
	// Storing Session

	if(!isset($_SESSION['username'])){
		mysqli_close($link); // Closing Connection
		header('Location: https://server.com/assign2/login.php'); // Redirecting To Home Page
	} else {
		$user_check=$_SESSION['username'];
		// SQL Query To Fetch Complete Information Of User
		$ses_sql=mysqli_query($link, 'select username from user where username="'. $_SESSION['username'] . '"')  or die('query failed'. mysqli_error($link));
		$row = mysqli_fetch_assoc($ses_sql);
		$login_session =$row['username'];
	
		if(!isset($login_session)){
			mysqli_close($link); // Closing Connection
			header('Location: https://server.com/assign2/login.php'); // Redirecting To Home Page
		} 
	}

include 'a1.lib';
$a2 = new a2();

if(isset($_GET["var"])){
	$command = $_GET["var"];
	$id = $_GET["id"];


$link = $a2->openDB();

// triggers flag for whether or not the item is deleted
if($command=="delete"){
	$sql_query = 'UPDATE inventory SET deleted="n" WHERE id="' . $id . '";';
} else {
	$sql_query = 'UPDATE inventory SET deleted="y" WHERE id="' . $id . '";';
}

//sends the flag to database
$result = mysqli_query($link, $sql_query) or die('query failed'. mysqli_error($link));

mysqli_close($link);
header("location: view.php");
}
?>