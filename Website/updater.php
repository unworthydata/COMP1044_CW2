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
			$update .= "$column[Field] = $newEntry , ";
		}
	}

	$dateTime = date('Y-m-d H:i:s');
	$update .= "last_update = '$dateTime' ";

	$update .= "WHERE $_SESSION[primaryKeyColumn] = $_SESSION[primaryKeyValue]";

	// for debugging
	//echo "<br> UPDATE: " . $update;
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
	<?php
	if (isset($_POST['update']))
		echo "<script type='text/javascript'>
				alert('Successfully updated entry! Redirecting...');
			</script>
			<meta http-equiv='refresh' content='0; URL=employee.php' />";
	?>
	<title>Webflix - Employee Portal</title>
	<link rel="stylesheet" href="styles/normalise.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
	<link rel="stylesheet" href="styles/styles.css">
</head>

<form action="update.php" method="POST" style="max-width: 300px;">
	<?php
	$result = mysqli_query($conn, "SELECT * FROM $_SESSION[table] WHERE $_SESSION[primaryKeyColumn] = $_SESSION[primaryKeyValue]");
	$fetch = mysqli_fetch_all($result, MYSQLI_ASSOC);

	foreach ($columnNames as $column) {
		if ($column['Field'] == $_SESSION['primaryKeyColumn']) {
			echo "<label>$column[Field]</label>";
			echo "<input class='numberField' type='number' value='$_SESSION[primaryKeyValue]' readonly>";
		} else
			updateInputs($column, $fetch[0][$column['Field']]);
	}
	?>

	<button type="submit" name="update" value="update">Update</button>
</form>

<a href="employee.php" class="button">Go back</a>
