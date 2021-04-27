<!DOCTYPE html>
<html lang="en">

<?php

// $sortType holds either "Ascending" or "Descnding" (you can do if ($sortType == "Ascending") then sort ascending else sort descending)
// $column holds the column name
// so if the sortType is set, then the user wants to sort

if (isset($_GET['sortType'])) {
  // then sort
}



?>

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
      <select class="list" name="tables" id="tableSelect" onchange="submit();">
        <?php //we're gonna connect to the database first to fetch stuff (we're connecting locally but when we got everything finalised and working we have to change this to the server)
        $conn = mysqli_connect('localhost', 'root', '', 'entertainment'); //host, username, password, databasename
        if (!$conn)  //if the connection has failed (is false):
          echo 'Connection Error:' . mysqli_connect_error(); //concatenates the source of the error to the end of the echo

        $query = ('SHOW TABLES FROM entertainment');
        $result = mysqli_query($conn, $query);
        $tablenames = mysqli_fetch_all($result, MYSQLI_ASSOC); //fetches a list of tables in database 'entertainment'

        mysqli_close($conn);
        mysqli_free_result($result);

        // this is to say "if this field is set (i.e. the user already selected a value),
        // set that value as the display value in the list), otherwiwse display placeholder value
        if (isset($_GET['tables']))
          echo "<option value=$_GET[tables]>$_GET[tables]</option>";
        else
          echo "<option value='' disabled selected>Select your option</option>";


        foreach ($tablenames as $table)
          echo "<option value=$table[Tables_in_entertainment]>$table[Tables_in_entertainment]</option>";

        ?>
      </select>

      <label for="columnList">Sort by</label>
      <select class="list" name="column" id="columnList">
        <?php
        // get the table names in a foreach loop (Use query 'SHOW column FROM $table') (i think you meant column names so imma do that)
        $conn = mysqli_connect('localhost', 'root', '', 'entertainment'); // we have to change this to the server
        if (!$conn)
          echo 'Connection Error:' . mysqli_connect_error();


        $table = $_GET['tables'];
        $query = ("SHOW COLUMNS FROM $table");
        $result = mysqli_query($conn, $query);
        $columnnames = mysqli_fetch_all($result, MYSQLI_ASSOC);

        mysqli_close($conn);
        mysqli_free_result($result);

        // this is to say "if this field is set (the user already selected a value),
        // keep that value as the display value in the list)
        if (isset($_GET['column']))
          echo "<option value=$_GET[column]>$_GET[column]</option>";
        else
          echo "<option value='' disabled selected>Select your option</option>";

        foreach ($columnnames as $column)
          echo "<option value=$column[Field]>$column[Field]</option>";
        ?>
      </select>

      <select class="list" name="sortType">
        <?php
        // this is to say "if this field is set (the user already selected a value),
        // keep that value as the display value in the list)
        if (isset($_GET['sortType'])) {
          $sortType = $_GET['sortType'];
          echo "<option value=$sortType>$sortType</option>";

          if ($sortType == 'Ascending')
            echo "<option value='Descending'>Descending</option>";
          else
            echo "<option value='Ascending'>Ascending</option>";
        } else {
          echo "<option value='Ascending'>Ascending</option>
                <option value='Descending'>Descending</option>";
        }

        ?>
      </select>

      <input type="submit" value="Go">

    </fieldset>
  </form>

  <div id="separator"></div>

  <!-- example table -->
  <table>
    <thead>
      <form action="employee.php" method="GET">
        <?php
        foreach ($columnnames as $column) {
          if ($column['Type'] == 'varchar(30)' or $column['Type'] == 'varchar(50)' or $column['Type'] == 'varchar(100)')  //if we're storing strings any other way add them here
            echo "<th> <input type='text' placeholder= $column[Field] id='name'> </th>";
          elseif ($column['Type'] == 'tinyint(1)')  //this is how we're storing booleans
            echo "<th> <div class='checkbox'> <input type='checkbox' id='manager'> </div> </th>";
          else
            echo "<th class='numericQueryHeader'>
            <select class='operationList' name='operations'>
              <option value='equal'>==</option>
              <option value='notEqual'>&#8800;</option>
              <option value='lessThan'>&lt;</option>
              <option value='greaterThan'>&gt;</option>
              <option value='lessThanOrEq'>&le;</option>
              <option value='greaterThanOrEq'>&ge;</option>
            </select>
            <input class='numberField' type='number' placeholder= $column[Field] id='age'> </th>";
        }

        ?>

        <th>
          <button type="reset">Clear</button>
          <button type="submit">Start query</button>
        </th>
      </form>
    </thead>
    <thead>
      <th>Name</th>
      <th>Manager?</th>
      <th>Age</th>
      <th></th>
    </thead>

    <tbody>
      <tr>
        <td>Tywin Lannister</td>
        <td>Yes</td>
        <td>50</td>
        <td>
          <a href=""><img src="images/delete_icon.png" alt="delete_icon" class="icon"></a>
          <a href=""><img src="images/edit_icon.jpg" alt="edit_icon" class="icon"></a>
        </td>
      </tr>
      <tr>
        <td>Robert Langdon</td>
        <td>Yes</td>
        <td>36</td>
        <td>
          <a href=""><img src="images/delete_icon.png" alt="delete_icon" class="icon"></a>
          <a href=""><img src="images/edit_icon.jpg" alt="edit_icon" class="icon"></a>
        </td>
      </tr>
      <tr>
        <td>Robb Stark</td>
        <td>No</td>
        <td>23</td>
        <td>
          <a href=""><img src="images/delete_icon.png" alt="delete_icon" class="icon"></a>
          <a href=""><img src="images/edit_icon.jpg" alt="edit_icon" class="icon"></a>
        </td>
      </tr>
    </tbody>

    <tfoot>
      <tr>
        <?php
        foreach ($columnnames as $column) {
          if ($column['Type'] == 'varchar(30)' or $column['Type'] == 'varchar(50)' or $column['Type'] == 'varchar(100)') { //if we're storing strings any other way add them here
            echo "<td> <input type='text' placeholder= $column[Field] id='mame'> </td>";
          } elseif ($column['Type'] == 'tinyint(1)') { //this is how we're storing booleans
            echo "<td> <div class='checkbox'> <input type='checkbox' id='manager'> </div> </td>";
          } else {
            echo "<td> <input class='numberField' type='number' placeholder= $column[Field] id='age'> </td>";
          }
        }

        ?>
        <td><button type="submit">Insert record</button></td>
      </tr>
    </tfoot>
  </table>

  <!-- table with functionality outline -->
  <!-- note that it is wrapped with a hidden div, remove that div tag before and after table so you can see it-->

  <table>
    <!-- for querying -->
    <thead>
      <form action="employee.php" method="GET">
        <?php
        foreach ($columnnames as $column) {
          if ($column['Type'] == 'varchar(30)' or $column['Type'] == 'varchar(50)' or $column['Type'] == 'varchar(100)')
            // the code says that a query entry would be labelled as columnName_condition, for example film_id_condition
            echo "<th> <input type='text' placeholder='$column[Field]' name='$column[Field]_condition'> </th>";
          elseif ($column['Type'] == 'tinyint(1)')  //this is how we're storing booleans
            echo "<th> <input type='checkbox' name='$column[Field]_condition'> </th>";
          else
            // in this case, the comparison operator (==, >, <, etc...) would be called columnName_condition, for example salary_condition.
            // The value being compared against would be called columnName_quantity, for example, a query of (salary >= 9), would have
            // $salary_condition = ">=" and $salary_quantity = 9
            echo "<th class='numericQueryHeader'>
                  <select class='operationList' name='$column[Field]_condition'>
                    <option value='equal'>==</option>
                    <option value='notEqual'>&#8800;</option>
                    <option value='lessThan'>&lt;</option>
                    <option value='greaterThan'>&gt;</option>
                    <option value='lessThanOrEqual'>&le;</option>
                    <option value='greaterThanOrEqual'>&ge;</option>
                  </select>
                  <input class='numberField' type='number' placeholder='$column[Field]' name='$column[Field]_quantity'>
                </th>";
        }

        ?>
        <th>
          <button name="clear" type="reset" value="clear">Clear</button>
          <button name="query" type="submit" value="query">Query</button>
        </th>

        <?php
        // if the query button was clicked, go through all the columns and build a query (for example I used a text/VARCHAR column)
        if (isset($_GET['query'])) {
          $query = "SELECT * FROM $table WHERE ";
          foreach ($columnnames as $column) {
            if (isset($_GET["$column[Field]_condition"])) {
              if ($column['Type'] == 'varchar(30)' or $column['Type'] == 'varchar(50)' or $column['Type'] == 'varchar(100)')
                $query = $query . $column['Field'] . " LIKE " . $_GET["$column[Field]_condition"] . " AND ";
              else if ($column['Type'] == 'tinyint(1)')
                // this can be confusing, but it means (example column name isManager) "... isManager = TRUE AND "
                $query = $query . $column['Field'] . " = " . "TRUE AND ";
              else {
                // since we can't use equal sign in the URL (it violates the rules because it would break the url)
                // we use a simple switch statement to set the comparison operator
                $operator = '=';
                switch ($_GET["$column[Field]_condition"]) {
                  case 'equal':
                    break;
                  case 'notEqual':
                    $operator = '!=';
                    break;
                  case 'lessThan':
                    $operator = '<';
                    break;
                  case 'greaterThan':
                    $operator = '>';
                    break;
                  case 'lessThanOrEqual':
                    $operator = '<=';
                    break;
                  case 'greaterThanOrEqual':
                    $operator = '>=';
                    break;
                }
                // says (example column salary) "... salary > 9 AND "
                $query = $query . $column['Field'] . $operator . $_GET["$column[Field]_quantity"] . " AND ";
              }
            }

            // here we remove the last AND (last 5 characters)
            substr($query, 0, 5);
            echo $query;
          }

          // now run the query and get the new table (basically set new )
        }
        ?>
      </form>
    </thead>

    <!-- for headers/column names -->
    <thead>
      <?php
      foreach ($columnnames as $column)
        echo "<th>$column[Field]</th>";
      ?>
    </thead>

    <tbody>

      <!-- for actual entries -->
      <?php
      $conn = mysqli_connect('localhost', 'root', '', 'entertainment'); //needs to be updated to the server
      if (!$conn) {
        echo 'Connection Error:' . mysqli_connect_error();
      }

      $query = ("SELECT * FROM $table");
      $result = mysqli_query($conn, $query);
      $fetch = mysqli_fetch_all($result, MYSQLI_ASSOC);

      // foreach entry
      foreach ($fetch as $entry) {
        echo "<tr>";

        echo "</tr>";
      }
      ?>

    </tbody>

    <tfoot>
      <tr>
        <form action="employee.php" method="POST">
          <?php

          foreach ($columnnames as $column) {
            if ($column['Type'] == 'varchar(30)' or $column['Type'] == 'varchar(50)' or $column['Type'] == 'varchar(100)')  //if we're storing strings any other way add them here
              echo "<td> <input type='text' placeholder='$column[Field]' id='$column[Field]'> </td>";
            elseif ($column['Type'] == 'tinyint(1)')  //this is how we're storing booleans
              echo "<td> <input type='checkbox' id='$column[Field]'> </td>";
            else
              echo "<td> <input class='numberField' type='number' placeholder='$column[Field]' id='$column[Field]'> </td>";
          }

          ?>
          <td><button type="submit">Insert record</button></td>
        </form>

      </tr>
    </tfoot>
  </table>
</body>

<!-- notice that there are some repeated parts and there is
probably a way to extract that into some sort of function
in php (possibly into another php file and then #include at the top) -->

</html>