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
		header('Location: https://server.com/assign2/add.php');
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

$itemNameErr ="";
$descriptionErr ="";
$supplierCodeErr ="";
$costErr ="";
$sellingPriceErr ="";
$numberOnHandErr="";
$costErr ="";
$reorderPointErr ="";
$phoneNumberErr ="";
$dataValid = true;

// If submit with POST
if ($_POST) { 
        // Test for nothing entered in field
	// Tests fields for errors
	
	if (preg_match("/^[\s]*$/", $_POST['itemName'])) {
		$itemNameErr = "Error - you must fill in a first name";
		$dataValid = false;
	} else if (!preg_match("/^[\s]*[a-zA-Z\s:;\-,'0-9]*[\s]*$/", $_POST['itemName'])){
		$itemNameErr = "letters, spaces, colon, semi-colon, dash, comma, apostrophe and numeric character (0-9) only - cannot be blank";
		$dataValid = false;
	}
	
	if (preg_match("/^[\s]*$/", $_POST['description'])) {
		$descriptionErr = "Error - you must fill in a description";
		$dataValid = false;		
	} else if (!preg_match("/^[a-zA-Z\s\d\'.,\-]*$/", $_POST['description'])){
		$descriptionErr = "letters, digits, periods, commas, apostrophes, dashes and spaces only (may contain newlines since this is a multiline textarea) - cannot be blank";
		$dataValid = false;
	}
	
	if (preg_match("/^[\s]*$/", $_POST['supplierCode'])){
		$supplierCodeErr = "Error - you must fill in the supplier code";
		$dataValid = false;		
	}else if (!preg_match("/^[\s]*[A-Z][A-Z][A-Z][\d][\d][\d][\d]*[\s]*$/", $_POST['supplierCode'])){
		$supplierCodeErr = "supplier code begins with three uppercase letters and is followed by three or more digits - cannot be blank
";
		$dataValid = false;
	}
	
	if (preg_match("/^[\s]*$/", $_POST['cost'])){
		$costErr = "Error - you must fill in a cost";
		$dataValid = false;		
	}else if (!preg_match("/[\s]*[\d]+[\.][\d][\d][\s]*$/", $_POST['cost'])){
		$costErr = "monetary amounts only i.e. one or more digits, then a period, then two digits - cannot be blank";
		$dataValid = false;
	}
	
	if (preg_match("/^[\s]*$/", $_POST['sellingPrice'])) {
		$sellingPriceErr = "Error - you must fill in a selling price";
		$dataValid = false;		
	}else if (!preg_match("/^[\s]*[\d]+[\.][\d][\d][\s]*$/", $_POST['sellingPrice'])){
		$sellingPriceErr = "monetary amounts only i.e. one or more digits, then a period, then two digits - cannot be blank";
		$dataValid = false;
	}
	
	if (preg_match("/^[\s]*$/", $_POST['numberOnHand'])) {
		$numberOnHandErr = "Error - you must fill in a number on hand";
		$dataValid = false;		
	}else if (!preg_match("/^[\s]*[\d]+[\s]*$/", $_POST['numberOnHand'])){
		$numberOnHandErr = "digits only- cannot be blank";
		$dataValid = false;
	}
	
	if (preg_match("/^[\s]*$/", $_POST['reorderPoint'])){
		$reorderPointErr = "Error - you must fill in a reorder point";
		$dataValid = false;		
	}else if (!preg_match("/^[\s]*[\d]+[\s]*$/", $_POST['reorderPoint'])){
		$reorderPointErr = "digits only- cannot be blank";
		$dataValid = false;
	}
}

// If the submit button was pressed and something was entered in both fields, process data
// (we just print a mesg)
if ($_POST && $dataValid) { 
	
	$link = $a2->openDB();
	
	//stores form data in values
	$itemName = trim($_POST['itemName']);
	$description = trim($_POST['description']);
	$supplierCode = trim($_POST['supplierCode']);
	$cost = trim($_POST['cost']);
	$sellingPrice = trim($_POST['sellingPrice']);
	$numberOnHand = trim($_POST['numberOnHand']);
	$reorderPoint = trim($_POST['reorderPoint']);

	if (isset($_POST["onBackOrder"])){
		$onBackOrder = "y";
	}else {
		$onBackOrder = "n";
	}

	if(isset($_GET['id'])){
		$sql_query = 'UPDATE inventory set itemName="' . mysqli_real_escape_string($link,$itemName) . '", description="' . mysqli_real_escape_string($link,$description) . '", supplierCode="' . mysqli_real_escape_string($link,$supplierCode) . '", cost="' . mysqli_real_escape_string($link,$cost) . '", price="' . mysqli_real_escape_string($link,$sellingPrice ). '", onHand="' . mysqli_real_escape_string($link,$numberOnHand) . '", reorderPoint="' . mysqli_real_escape_string($link,$reorderPoint) . '", backOrder="' . mysqli_real_escape_string($link,$onBackOrder) . '"' . 'WHERE id="' . mysqli_real_escape_string($link,$_GET['id']) . '"' ;
	} else {
		$sql_query = 'INSERT INTO inventory set itemName="' . mysqli_real_escape_string($link,$itemName) . '", description="' . mysqli_real_escape_string($link,$description) . '", supplierCode="' . mysqli_real_escape_string($link,$supplierCode) . '", cost="' . mysqli_real_escape_string($link,$cost) . '", price="' . mysqli_real_escape_string($link,$sellingPrice) . '", onHand="' . mysqli_real_escape_string($link,$numberOnHand) . '", reorderPoint="' . mysqli_real_escape_string($link,$reorderPoint) . '", backOrder="' . mysqli_real_escape_string($link,$onBackOrder ) . '"';
	}
	
	//Run our sql query
	$result = mysqli_query($link, $sql_query) or die('query failed'. mysqli_error($link));
  
	//Close the MySQL Link
	mysqli_close($link);

	header("location: view.php");
	
	exit();

// If no submit or data is invalid, print form, repopulating fields and printing err mesgs
} else { 

if(isset($_GET['id'])){
	$link = $a2->openDB();
	$sql_query2= 'SELECT * from inventory WHERE id="' . $_GET['id'] . '"'; 
	$result2 = mysqli_query($link, $sql_query2) or die('query failed'. mysqli_error($link));
	$edit = mysqli_fetch_assoc($result2);
	mysqli_close($link);
}
?>

<html>

<title>
	Add Inventory
</title>

<body>

<header>
	<center>
		<?php $a2->name();?>
	</center>
</header>

<?php
	$a2->menu("", $_SESSION['username'], $_SESSION['role']);
?>

<form method="post" action="add.php<?php if(isset($_GET['id'])){ echo "?id=" . $_GET['id']; }?> ">
<table>
	<?php	
		if(isset($_GET['id'])){
		?> 
			<tr> 
				<td>		
					ID:
				</td>
				<td>
					<input readonly="readonly" type="text" name=id value="<?php echo $_GET['id']; ?>">
				</td>
			</tr>
		<?php
		} 
	?>
	<tr> 
		<td>		
			Item name:
		</td>
		<td>
			<input  type="text" name=itemName value="<?php if (isset($_POST['itemName'])) echo $_POST['itemName']; ?><?php if (isset($edit['itemName'])) echo $edit['itemName']; ?>"><font color = 'red'><?php echo $itemNameErr;?></font>
		</td>
	</tr>
	<tr>
		<td>
			Description:  
		</td>
		<td>
		<textarea name="description" value=""><?php if (isset($_POST['description'])) echo $_POST['description']; ?><?php if (isset($edit['description'])) echo $edit['description']; ?></textarea><font color = 'red'><?php echo $descriptionErr;?> </font>
		</td>
	</tr>
	<tr>
		<td>
		Supplier Code:
		</td>
		<td>
		<input type="text" name=supplierCode value="<?php if (isset($_POST['supplierCode'])) echo $_POST['supplierCode']; ?><?php if (isset($edit['supplierCode'])) echo $edit['supplierCode']; ?>"><font color = 'red'><?php echo $supplierCodeErr; ?></font>
		</td>
	</tr>
	<tr>
		<td>
		Cost: 
		</td>
		<td>
		<input type="text" name=cost value="<?php if (isset($_POST['cost'])) echo $_POST['cost']; ?><?php if (isset($edit['cost'])) echo $edit['cost']; ?>"><font color = 'red'><?php echo $costErr; ?></font>
		</td>
	</tr>
	<tr>
		<td>
		Selling price: 
		</td>
		<td>
		<input type="text" name=sellingPrice value="<?php if (isset($_POST['sellingPrice'])) echo $_POST['sellingPrice']; ?><?php if (isset($edit['price'])) echo $edit['price']; ?>"><font color = 'red'><?php echo $sellingPriceErr; ?></font>
		</td>
	</tr>
	<tr>
		<td>
		Number on hand:
		</td>
		<td>
		<input type="text" name=numberOnHand value="<?php if (isset($_POST['numberOnHand'])) echo $_POST['numberOnHand']; ?><?php if (isset($edit['onHand'])) echo $edit['onHand']; ?>"><font color = 'red'><?php echo $numberOnHandErr; ?></font>
		</td>
	</tr>
	<tr>
		<td>
		Reorder Point:
		</td>
		<td>
		<input type="text" name=reorderPoint value="<?php if (isset($_POST['reorderPoint'])) echo $_POST['reorderPoint']; ?><?php if (isset($edit['reorderPoint'])) echo $edit['reorderPoint']; ?>"><font color = 'red'><?php echo $reorderPointErr; ?> </font>
		</td>
	</tr>
	<tr>
		<td>
		On Back Order:
		</td>
		<td>
		<input type="checkbox"  name="onBackOrder" <?php if (isset($_POST['onBackOrder']))echo "CHECKED"; ?><?php if (isset($edit['backOrder']))echo "CHECKED"; ?>>
		</td>
	</tr>
	<tr>
		<td>
		<input type="submit">
		</td>
	</tr>
</table>
</form>
<?php
}
?>
</body>

<footer>
	<?php $a2->footer(); ?>
</footer>

</html>