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
		header('Location: https://server.com/assign2/view.php');
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
		//session_start();
		$user_check=$_SESSION['username'];
		// SQL Query To Fetch Complete Information Of User
		$ses_sql=mysqli_query($link, 'select username from users where username="'. $_SESSION['username'] . '"')  or die('query failed'. mysqli_error($link));
		$row = mysqli_fetch_assoc($ses_sql);
		$login_session =$row['username'];
	
		if(!isset($login_session)){
			mysqli_close($link); // Closing Connection
			header('Location: https://server.com/assign2/login.php'); // Redirecting To Home Page
		} 
	}

	include 'a1.lib';
	$a2 = new a2();

	$numRows = "1";

	if (isset($_POST['desc']) || isset($_SESSION['desc']) ) {
		$link = $a2->openDB();
		// Get all records now in DB\
		
		if (isset($_POST['desc'])){
			$_SESSION['desc'] = $_POST['desc'];
		}
		
		if(isset($_COOKIE['sort'])){
			$sql_query = "SELECT * from inventory WHERE lower(description) LIKE lower('%" . mysqli_real_escape_string($link, $_SESSION['desc'])."%') order by " . $_COOKIE['sort'];
		} else {
			$sql_query = "SELECT * from inventory WHERE lower(description) LIKE lower('%" . mysqli_real_escape_string($link, $_SESSION['desc']) ."%');";
		}
		
		//Run our sql query
		$result = mysqli_query($link, $sql_query) or die('query failed'. mysqli_error($link));
		$numRows = $result->num_rows;
		
	}else {
		$link = $a2->openDB();
		// Get all records now in DB
		
		if(isset($_COOKIE['sort'])){
			$sql_query = "SELECT * from inventory order by " . $_COOKIE['sort'];
		} else {
			$sql_query = "SELECT * from inventory";
		}
		
		//Run our sql query
		$result = mysqli_query($link, $sql_query) or die('query failed'. mysqli_error($link));
	
	}
 
	//iterate through result printing each record
?>
<html>

<title>
	View Inventory
</title>

<body>

<header>
	<center>
		<?php $a2->name();?>
	</center>
</header>

<?php 

	if($numRows ==0){	echo "0 results found";}

	$menuValue = "";
	
	if (isset($_SESSION['desc'])) {
		$menuValue =  $_SESSION['desc'];
	}

	$a2->menu($menuValue, $_SESSION['username'], $_SESSION['role']);
?>

<table border="1" width="100%">
	<tr>
		<th> <a href = "cookie.php?sort=id" > id </a></th>
		<th><a href = "cookie.php?sort=itemName" > Item Name</a></th>
		<th><a href = "cookie.php?sort=description" > Description</a></th>
		<th><a href = "cookie.php?sort=supplierCode" > Supplier Code</a></th>
		<th><a href = "cookie.php?sort=cost" > Cost</a></th>
		<th><a href = "cookie.php?sort=price" > Price</a></th>
		<th><a href = "cookie.php?sort=onHand" > On Hand</a></th>
		<th><a href = "cookie.php?sort=reorderPoint" > Reorder Point</a></th>
		<th><a href = "cookie.php?sort=backOrder" > Back Order</a></th>
		<th><a href = "cookie.php?sort=deleted" >  Delete/Restore </a></th>
	</tr>
<?php
 		while($row = mysqli_fetch_assoc($result)){
?>

		<tr>
		<td><center><a href ="https://server.com/assign2/add.php?id=<?php print $row['id']; ?>"><?php print $row['id']; ?> </a> </center></td>
		<td><center><?php print $row['itemName']; ?></center></td>
		<td><center><?php print $row['description']; ?></center></td>
		<td><center><?php print $row['supplierCode']; ?></center></td>
		<td><center><?php print $row['cost']; ?></center></td>
		<td><center><?php print $row['price']; ?></center></td>
		<td><center><?php print $row['onHand']; ?></center></td>
		<td><center><?php print $row['reorderPoint']; ?></center></td>
		<td><center><?php print $row['backOrder']; ?></center></td>
		<td><center><a href="delete.php?var=<?php if ($row['deleted'] == "y") print 'delete'; else print 'restore'; ?>&id=<?php print $row['id']; ?>"><?php if ($row['deleted'] == "y") print 'Delete'; else print 'Restore'; ?></a></center></td>
		</tr>
<?php
 		}

?>
</table>
</body>

<footer>
	<?php $a2->footer(); ?>
</footer>

</html>
<?php
	// Free resultset (optional)
	mysqli_free_result($result);
  
	//Close the MySQL Link
	mysqli_close($link);

	exit();
?>