<?php
session_start();
include 'inputFields.php';
include 'connection.php';
include 'queryBuilder.php';

if (isset($_GET['table']))
  $_SESSION['table'] = $_GET['table'];

if (isset($_GET['sortColumn']))
  $_SESSION['sortColumn'] = $_GET['sortColumn'];

if (isset($_GET['sortType']))
  $_SESSION['sortType'] = $_GET['sortType'];

if (isset($_POST['delete'])) {
  $result = mysqli_query($conn, "SHOW CREATE TABLE $_SESSION[table]");
  $fetch = mysqli_fetch_all($result, MYSQLI_ASSOC);

  // All the tables in our database have the ON DELETE constraint set to RESTRICT.
  // It follows that if a table has a foreign key, its rows can't be deleted.
  //
  // To check if a table has foreign key references we run the above query.
  // The MySQL query returns one row with two columns,
  // first column is labelled 'Table' and contains the table name,
  // while the second column is laelled 'Create Table' and 
  // contains the actual code written to create the table.
  //
  // (although the code is slightly altered after it passes through the 
  // compiler, it is mostly the same and will work for our current purpose)
  if (!strpos($fetch[0]['Create Table'], 'FOREIGN KEY')) {
    // for debugging:
    // echo "<br> DELETE: " . "DELETE FROM $_SESSION[table] WHERE $_POST[primaryKeyColumn] = $_POST[primaryKeyValue]";
    mysqli_query($conn, "DELETE FROM $_SESSION[table] WHERE $_POST[primaryKeyColumn] = $_POST[primaryKeyValue]");
  } else
    echo "<script type='text/javascript'> alert('Cannot delete the specified row, it is a child row with a foreign key reference'); </script>";

  unset($_POST['delete']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php
  // reload page after an insert to see changes (doesn't work)
  if (isset($_POST['afterInsert']))
    echo "<meta http-equiv='refresh' content='0; URL=employee.php' />";
  ?>
  <title>Webflix - Employee Portal</title>
  <link rel="stylesheet" href="styles/normalise.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
  <link rel="stylesheet" href="styles/employee.css">
</head>

<body>
  <form action="employee.php" method="GET">
    <fieldset>

      <label for="tableSelect">Select a table</label>
      <select class="list" name="table" id="tableSelect" onchange="submit();">
        <?php

        $result = mysqli_query($conn, "SHOW TABLES FROM $database");
        $tablenames = mysqli_fetch_all($result, MYSQLI_ASSOC);

        if (isset($_SESSION['table']))
          echo "<option value=$_SESSION[table]>$_SESSION[table]</option>";
        else
          echo "<option value='' disabled selected>Select your option</option>";


        foreach ($tablenames as $table)
          echo "<option value=$table[Tables_in_entertainment]>$table[Tables_in_entertainment]</option>";

        ?>
      </select>

      <label for="columnList">Sort by</label>
      <select class="list" name="sortColumn" id="columnList">
        <?php

        $result = mysqli_query($conn, "SHOW COLUMNS FROM $_SESSION[table]");
        $columnNames = mysqli_fetch_all($result, MYSQLI_ASSOC);

        if (isset($_SESSION['sortColumn']))
          echo "<option value=$_SESSION[sortColumn]>$_SESSION[sortColumn]</option>";
        else
          echo "<option value='' disabled selected>Select your option</option>";

        foreach ($columnNames as $column)
          echo "<option value=$column[Field]>$column[Field]</option>";
        ?>
      </select>

      <select class="list" name="sortType">
        <?php

        if (isset($_SESSION['sortType'])) {
          $sortType = $_SESSION['sortType'];
          echo "<option value=$sortType>$sortType</option>";

          if ($sortType == 'Ascending')
            echo "<option value='Descending'>Descending</option>";
          else
            echo "<option value='Ascending'>Ascending</option>";
        } else
          echo "<option value='Ascending'>Ascending</option>
                <option value='Descending'>Descending</option>";
        ?>
      </select>
      <input type="submit" value="Go">

      <div>
        <a class="button" href="#bottom" id="insertRef">Insert a new entry</a>
        <?php
        /*This will be some code that *might* scroll you to the bottom of the page which should be ran when the button is pressed
        <script>
        var element = document.getElementByID("separator")
        element.scrollTop = 9999999999 //just some big number that represents how my pixels we'll be scrolling down
      </script>*/ ?>
      </div>

    </fieldset>
  </form>

  <div id="separator"></div>
  <?php
  if (isset($_SESSION['table'])) { ?>
    <table>
      <!-- for querying -->
      <thead>
        <form action="employee.php" method="GET">
          <?php
          foreach ($columnNames as $column)
            queryInputs($column);
          ?>
          <th>
            <button name="query" type="submit" value="query">Query</button>
            <button name="clear" type="reset" value="clear" class="button button-outline">Clear</button>
          </th>

          <?php
          if (isset($_GET['query']))
            $condition = buildQuery($columnNames, $_GET);
          ?>
        </form>
      </thead>

      <!-- for headers/column names -->
      <thead>
        <?php
        $loop = 0;
        foreach ($columnNames as $column) {
          if ($loop == 0) { //the first column of every table should be the default we sort by (not the prettiest fix but hey it works)
            $_SESSION['sortColumn'] = $column['Field'];
            $loop += 1;
          }
          echo "<th>$column[Field]</th>";
        }
        ?>
      </thead>

      <!-- for actual entries -->
      <tbody>
        <?php
        $query = "SELECT * FROM $_SESSION[table] ";

        if (isset($_GET['query']) && $condition !== "") {
          // build query here
          $query .= "WHERE $condition";
        }

        if (isset($_SESSION['sortColumn'])) {
          if ($_SESSION['sortType'] == "Ascending")
            $query .= " ORDER BY $_SESSION[sortColumn] ASC";
          else
            $query .= " ORDER BY $_SESSION[sortColumn] DESC";
        }

        echo $query;
        $result = mysqli_query($conn, $query);

        $fetch = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $columnsResult = mysqli_query($conn, "SHOW COLUMNS FROM $_SESSION[table]");
        $columnNames = mysqli_fetch_all($columnsResult, MYSQLI_ASSOC);
        $primaryKeyColumn = $columnNames[0]['Field'];
        $primaryKeyValue = -1;

        foreach ($fetch as $entry) {
          echo "<tr>";
          $primaryKeyValue = $entry[$primaryKeyColumn];

          foreach ($columnNames as $column) {
            echo "<td>" . htmlspecialchars($entry[$column['Field']]) . "</td>";
          }
          echo "<td>

                <form action='update.php' method='POST' id='iconForm'>
                  <input type='hidden' name='primaryKeyColumn' value='$primaryKeyColumn'/>
                  <input type='hidden' name='primaryKeyValue' value='$primaryKeyValue'/>
                  <input type='hidden' name='table' value='$_SESSION[table]'/>
                <input type='image' src='images/update_icon.jpg' class='icon' name='update' value='update'/>
                </form>

                <form action='employee.php' method='POST' id='iconForm'>
                  <input type='hidden' name='primaryKeyColumn' value='$primaryKeyColumn'/>
                  <input type='hidden' name='primaryKeyValue' value='$primaryKeyValue'/>
                  <input type='hidden' name='delete' value='delete'/>
                  <input type='image' src='images/delete_icon.png' alt='delete' class='icon'/>
                </form>

              </td>
            </tr>";
        }
        ?>

      </tbody>

      <tfoot>
        <tr>
          <form action="employee.php" method="POST" name="afterInsert" id="insertForm">
            <?php
            // get last ID in table
            $result = mysqli_query($conn, "SELECT * FROM $_SESSION[table] ORDER BY $primaryKeyColumn DESC LIMIT 1");
            $fetch = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $lastPrimaryKeyValue = $fetch[0][$primaryKeyColumn];

            foreach ($columnNames as $column) {
              insertInputs($column, $primaryKeyColumn, $lastPrimaryKeyValue);
            }
            ?>

            <td><button type="submit" name="insert">Insert record</button></td>
          </form>

          <?php
          if (isset($_POST['insert'])) {
            $values = "(";

            foreach ($columnNames as $column) {
              if (
                isset($_POST["$column[Field]_insert"]) &&
                $_POST["$column[Field]_insert"] !== ""
              ) {

                $newField = $_POST["$column[Field]_insert"];

                if ($newField == "on")
                  $values .= 1;
                else if ($column['Type'] == 'int(11)')
                  $values .= "$newField";
                else if ($column['Type'] == 'datetime') {
                  $datetime = date('Y-m-d H:i:s');
                  $values .= "'$datetime'";
                } else
                  $values .= "'$newField'";
              } else
                $values .= "DEFAULT";

              $values .= ", ";
            }

            // remove trailing comma and space
            $values = substr($values, 0, -2) . ")";

            $insertQuery = "INSERT INTO $_SESSION[table] VALUES $values";

            // for debugging:
            // echo "<br> INSERT: " . $insertQuery;
            mysqli_query($conn, $insertQuery);
          }
          ?>

        </tr>
      </tfoot>
    </table>
    <a href="bottom"></a>
</body>

</html>

<?php
  }
  mysqli_free_result($result);
  mysqli_close($conn);
?>