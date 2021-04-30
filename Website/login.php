<!-- the way this works is that you need to enter the correct info and only then you will be given access to the employee.php file
the employee.php file is one level above, so any malicious actors cannot access it without going through the login first -->

<?php
include 'connection.php';

// user is logged in
if (isset($_COOKIE['loggedIn']) && $_COOKIE['loggedIn'] == "true") {
	header("Location: employee.php");
	exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php
	if (isset($_POST['username'])) {
		$result = mysqli_query($conn, "SELECT * FROM accounts WHERE username = '$_POST[username]' AND password = '$_POST[password]'");
		$fetch = mysqli_fetch_all($result, MYSQLI_ASSOC);

		if (count($fetch) == 1) {
			// setcookie(name, value, expire, path, domain, secure, httponly);
			setcookie("loggedIn", "true", time() + 86400, "/"); // expires after 1 day (time() + 24 hrs * 3600 seconds an hr)
			header("Location: employee.php");
			exit();
		} else
			echo "<script>alert('Invalid username or password');</script>";
	}
	?>
	<title>Webflix - Login</title>
	<link rel="stylesheet" href="styles/normalize.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css" />
</head>

<body>
	<form action="login.php" method="POST">
		<fieldset>
			<label for="username">Username</label>
			<input type="text" name="username" id="username" placeholder="Enter your username">
			<label for="password">Password</label>
			<input type="password" name="password" id="password" placeholder="Enter your password">
			<input type="submit" value="Log in">
		</fieldset>
	</form>
</body>

</html>