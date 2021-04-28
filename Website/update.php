<?php
include 'connection.php';
include 'inputFields.php';
session_start();

if (isset($_POST['table'])) {
	$_SESSION['table'] = $_POST['table'];
	$_SESSION['primaryKeyColumn'] = $_POST['primaryKeyColumn'];
	$_SESSION['primaryKeyValue'] = $_POST['primaryKeyValue'];
}

$columnsResult = mysqli_query($conn, "SHOW COLUMNS FROM $_SESSION[table]");
$columnNames = mysqli_fetch_all($columnsResult, MYSQLI_ASSOC);

if (isset($_POST['update'])) {
	$update = "UPDATE $_SESSION[table] SET ";
	foreach ($columnNames as $column) {
		if (
			isset($_POST["$column[Field]_update"]) &&
			$column['Field'] !== $_SESSION['primaryKeyColumn']
		) {
			$value = $_POST["$column[Field]_update"];

			if ($value !== "") {
				if ($value == "on")
					$newEntry = 1;
				else if ($column['Type'] == 'int(11)')
					$newEntry = $value;
				else
					$newEntry = "'$value'";
			}
		} else
			$newEntry = "$column[Field]";

		$update .= "$column[Field] = $newEntry , ";
	}
	$update = substr($update, 0, -2);
	$update .= "WHERE $_SESSION[primaryKeyColumn] = $_SESSION[primaryKeyValue]";

	mysqli_query($conn, $update);
}

$columnsResult = mysqli_query($conn, "SHOW COLUMNS FROM $_SESSION[table]");
$columnNames = mysqli_fetch_all($columnsResult, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Webflix - Employee Portal</title>
	<link rel="stylesheet" href="styles/normalise.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
</head>

<form action="update.php" method="POST" style="max-width: 30vw;">
	<?php
	$result = mysqli_query($conn, "SELECT * FROM $_SESSION[table] WHERE $_SESSION[primaryKeyColumn] = $_SESSION[primaryKeyValue]");
	$fetch = mysqli_fetch_all($result, MYSQLI_ASSOC);

	foreach ($columnNames as $column)
		updateInputs($column, $fetch[0][$column['Field']]);
	?>

	<button type="submit" name="update" value="update">Update</button>
</form>

<form action="employee.php">
	<button>Go back</button>
</form>