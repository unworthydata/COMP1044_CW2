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

if (isset($_POST['delete']))
  $_SESSION['delete'] = $_POST['delete'];

if (isset($_POST['update']))
  echo "update message";
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
        foreach ($columnNames as $column){
        if ($loop == 0){ //the first column of every table should be the default we sort by (not the prettiest fix but hey it works)
          $_SESSION[sortColumn] = $column['Field'];
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
                <form action='employee.php' method='POST' id='iconForm'>
                  <input type='hidden' name='primaryKeyColumn' value='$primaryKeyColumn'/>
                  <input type='hidden' name='primaryKeyValue' value='$primaryKeyValue'/>
                  <input type='hidden' name='delete' value='delete'/>
                  <input type='image' src='images/delete_icon.png' alt='delete' class='icon'/>
                </form>

                <form action='update.php' method='POST' id='iconForm'>
                  <input type='hidden' name='primaryKeyColumn' value='$primaryKeyColumn'/>
                  <input type='hidden' name='primaryKeyValue' value='$primaryKeyValue'/>
                  <input type='hidden' name='table' value='$_SESSION[table]'/>
                <input type='image' src='images/update_icon.jpg' class='icon' name='update' value='update'/>
                </form>

              </td>
            </tr>";
        }

        if (isset($_POST["delete"])){
          echo "DELETE FROM $_SESSION[table] WHERE $primaryKeyColumn = $primaryKeyValue";
          //mysqli_query($conn, "DELETE FROM $_SESSION[table] WHERE $primaryKeyColumn = $primaryKeyValue");
        }
        ?>

      </tbody>

      <tfoot>
        <tr>
          <form action="employee.php" method="POST" id="insertForm">
            <?php
            foreach ($columnNames as $column)
              insertInputs($column);
            ?>

            <td><button type="submit" name="insert">Insert record</button></td>
          </form>

          <?php
          if (isset($_POST['insert'])) {
            $newEntry = "(";
            foreach ($columnNames as $column) {
              if (!$column['Field'] == 'active'){ //if it's not the checkbox since $active_insert is not a variable that exists since it's not a textbox
                $value = $_POST["$column[Field]_insert"];
                if (isset($value) && $value == "on")
                  $newEntry .= '1';
                else if ($value !== "")
                  $newEntry .= $value;
                else
                  $newEntry .= "DEFAULT";
                }else{ //if it is the checkbox
                  //idk how to refer to the checbox here but you would do that manually since you know that ONLY the SMALLINT values go here
                }
              $newEntry .= ", ";
            }
            $newEntry = substr($newEntry, 0, -2) . ")";

            $insertQuery = "INSERT INTO $_SESSION[table] VALUES $newEntry";
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
