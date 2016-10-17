<?php

session_start();

	$lines = file('/home/int322/secret/topsecret');
	$dbserver = trim($lines[0]);
	$uid = trim($lines[1]);
	$pw = trim($lines[2]);
	$dbname = trim($lines[3]);
	
	//Connect to the mysql server and get back our link_identifier
 	$link = mysqli_connect($dbserver, $uid, $pw, $dbname)
        	or die('Could not connect: ' . mysqli_error($link));

if(!isset($_SESSION['username'])){
		mysqli_close($link); // Closing Connection
} else {
		$user_check=$_SESSION['username'];
		// SQL Query To Fetch Complete Information Of User
		$ses_sql=mysqli_query($link, 'select username from users where username="'. $_SESSION['username'] . '"')  or die('query failed'. mysqli_error($link));
		$row = mysqli_fetch_assoc($ses_sql);
		$login_session =$row['username'];
	
	if(isset($login_session)){
		header('Location: https://server.com/assign2/view.php');
	}
}


$error="";
if($_POST){
	
	$lines = file('/home/int322/secret/topsecret');
	$dbserver = trim($lines[0]);
	$uid = trim($lines[1]);
	$pw = trim($lines[2]);
	$dbname = trim($lines[3]);
	
	if(isset($_POST['forgot'])){
	?>
		<html>
		<body>
			<?php echo $error; ?>
			<br/>
			<form method="post" action="https://server.com/assign2/login.php">
				Email:<input type="text" name=email >
				<br/>
				<input value = "submit"  type="submit">
			</form>
		</body>
		</html>
	<?php
	} else  if (isset($_POST['email'])){
	
		//Connect to the mysql server and get back our link_identifier
		$link = mysqli_connect($dbserver, $uid, $pw, $dbname)
			or die('Could not connect: ' . mysqli_error($link));
	
		$sql_query = 'Select * From users where username="'. $_POST['email'] . '"';
		$result = mysqli_query($link, $sql_query) or die('query failed'. mysqli_error($link));
		$data = mysqli_fetch_array($result);
		$rows = mysqli_num_rows($result);

		if($rows >=1){
			$msg = "User Name:" . $_POST['email'] . " Password Hint: " . $data['passwordHint'] ;
			mail(
			"int322@localhost", // E-Mail address
			"Forgot Password", // Subject
			$msg, // Message
			"From: Example2 <example2@domain.tld>\r\nReply-to: Example3 <example3@domain.tld>"  // Additional Headers
			);
			
		}

		header("location: https://server.com/assign2/login.php");
		
		mysqli_close($link);
		
	} else {
	
		//Connect to the mysql server and get back our link_identifier
		$link = mysqli_connect($dbserver, $uid, $pw, $dbname)
			or die('Could not connect: ' . mysqli_error($link));
			
		$sql_query = 'Select * From users where username="'. $_POST['userName'] . '"';
		$result = mysqli_query($link, $sql_query) or die('query failed'. mysqli_error($link));
		$enPass = mysqli_fetch_assoc($result);

		$sql_query = 'Select * From users where password="' . crypt($_POST['pass'], $enPass['password']) . '" AND username="'. $_POST['userName'] . '"';
		//$sql_query = 'Select * From users where password="' . $_POST['pass'] . '" AND username="'. $_POST['userName'] . '"';
		$result = mysqli_query($link, $sql_query) or die('query failed'. mysqli_error($link));
		$rows = mysqli_num_rows($result);
		
		if($rows ==1){
			$_SESSION['username'] = $_POST['userName'];
			$_SESSION['role']  = $enPass['role'];
			header("location: https://server.com/assign2/view.php");
		} else {
			$error = "Invalid username or password";
			?>
			<html>
			<body>
				<?php echo $error; ?>
				<br/>
				<form method="post" action="https://server.com/assign2/login.php">
					User Name:<input type="text" name=userName >
					<br/>
					Password: <input type="password" name=pass >
					<br />
					<input value = "Login" type="submit">
				</form>
				<br/>
				<form method="post" action="https://server.com/assign2/login.php">
					<input type="hidden" name="forgot" value="Yes"> 
					<input value="Forgot your password?"type="submit">
				</form>
			</body>
			</html>
			<?php
		}

		mysqli_close($link);
	
	}

}else {
	?>
		<html>
		<body>
			<?php echo $error; ?>
			<br/>
			<form method="post" action="https://server.com/assign2/login.php">
				User Name:<input type="text" name=userName >
				<br/>
				Password: <input type="password" name=pass >
				<br />
				<input value = "Login" type="submit">
			</form>
			<br/>
			<form method="post" action="https://server.com/assign2/login.php">
				 <input type="hidden" name="forgot" value="Yes"> 
				<input value="Forgot your password?"type="submit">
			</form>
		</body>
		</html>
	<?php
} 
?>

