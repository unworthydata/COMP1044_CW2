<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Webflix - Employee Area</title>
  <link rel="stylesheet" href="styles/normalise.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
  <link rel="stylesheet" href="styles/employee.css">
</head>

<body>
  <form action="employee.php" method="POST">
    <fieldset>
      <label for="tableSelect">Select a table</label>
      <select class="list" name="tables" id="tableList">
        <?php //we're gonna connect to the database first to fetch stuff (we're connecting locally but when we got everything finalised and working we have to change this to the server)
       $conn = mysqli_connect('localhost', 'test', 'test123', 'entertainment'); //host, username, password, databasename
        if (!$conn) { //if the connection has failed (is false):
          echo 'Connection Error:' . mysqli_connect_error(); //concatenates the source of the error to the end of the echo
        }
        $query = ('SHOW TABLES FROM entertainment');
        $result = mysqli_query($conn, $query);
        $tablenames = mysqli_fetch_all($result, MYSQLI_ASSOC); //fetches a list of tables in database 'entertainment'
        mysqli_close($conn);
        mysqli_free_result($result);
        foreach ($tablenames as $table){
        echo "<option value=$table[Tables_in_entertainment]>$table[Tables_in_entertainment]</option>";
      }
        ?>
      </select>

      <label for="sortSelect">Sort by</label>
      <select class="list" name="columns" id="columnList">
        <?php
        // get the table names in a foreach loop (Use query 'SHOW COLUMNS FROM $table') (i think you meant column names so imma do that)
        $conn = mysqli_connect('localhost', 'test', 'test123', 'entertainment'); // we have to change this to the server
         if (!$conn) {
           echo 'Connection Error:' . mysqli_connect_error();
         }
         $query = ('SHOW COLUMNS FROM Customer'); //i don't know what variable you have to store the user's choice of table so using Customer as a placeholder for the sake of testing (it has a boolean columnname)
         $result = mysqli_query($conn, $query);
         $columnnames = mysqli_fetch_all($result, MYSQLI_ASSOC);
         mysqli_close($conn);
         mysqli_free_result($result);
         foreach($columnnames as $column){
           echo "<option value=$column[Field]>$column[Field]</option>";
         }
        ?>
      </select>
      <select class="list" name="sort" id="sortList">
        <option value="ascending">Ascending</option>
        <option value="descending">Descending</option>
      </select>
    </fieldset>
  </form>

  <div id="separator"></div>

  <!-- example table -->
  <table>
    <thead>
      <?php
      foreach($columnnames as $column){
        if ($column['Type'] == 'varchar(30)' or $column['Type'] == 'varchar(50)' or $column['Type'] == 'varchar(100)'){ //if we're storing strings any other way add them here
          echo "<th> <input type='text' placeholder= $column[Field] id='mame'> </th>";
        } elseif ($column['Type'] == 'tinyint(1)'){ //this is how we're storing booleans
          echo "<th> <div class='checkbox'> <input type='checkbox' id='manager'> </div> </th>";
        } else{
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
        }

      ?>

      <th>
        <button type="reset">Clear</button>
        <button type="submit">Start query</button>
      </th>

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
        foreach($columnnames as $column){
          if ($column['Type'] == 'varchar(30)' or $column['Type'] == 'varchar(50)' or $column['Type'] == 'varchar(100)'){ //if we're storing strings any other way add them here
            echo "<td> <input type='text' placeholder= $column[Field] id='mame'> </td>";

          } elseif ($column['Type'] == 'tinyint(1)'){ //this is how we're storing booleans
            echo "<td> <div class='checkbox'> <input type='checkbox' id='manager'> </div> </td>";

          } else{
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
        <?php
        foreach($columnnames as $column){
          if ($column['Type'] == 'varchar(30)' or $column['Type'] == 'varchar(50)' or $column['Type'] == 'varchar(100)'){ //if we're storing strings any other way add them here
            echo "<th> <input type='text' placeholder=$column[Field] id=$column[Field]> </th>";

          } elseif ($column['Type'] == 'tinyint(1)'){ //this is how we're storing booleans
            echo "<th> <div class='checkbox'> <input type='checkbox' id=$column[Field]> </div> </th>";

          } else{
            echo "<th class='numericQueryHeader'>
              <select class='operationList' name='operations'>
                <option value='equal'>==</option>
                <option value='notEqual'>&#8800;</option>
                <option value='lessThan'>&lt;</option>
                <option value='greaterThan'>&gt;</option>
                <option value='lessThanOrEq'>&le;</option>
                <option value='greaterThanOrEq'>&ge;</option>
              </select>
              <input class='numberField' type='number' placeholder=$column[Field] id=$column[Field]> </th>";
          }
        }

        ?>
        <th>
          <button type="reset">Clear</button>
          <button type="submit">Query</button>
        </th>
      </thead>

      <!-- for headers/column names -->
      <thead>
        <?php
        foreach ($columnnames as $column){
        echo "<th>";
        echo  "<td>$column[Field]</td>";
        echo "</th>";
      }
        ?>
      </thead>

      <tbody>

        <!-- for actual entries -->
        <?php
        $conn = mysqli_connect('localhost', 'test', 'test123', 'entertainment'); //needs to be updated to the server
        if (!$conn) {
          echo 'Connection Error:' . mysqli_connect_error();
        }

        $query = ('SELECT * FROM Customer'); //Customer here is a placeholder
        $result = mysqli_query($conn, $query);
        $fetch = mysqli_fetch_all($result, MYSQLI_ASSOC);
        // foreach entry
        foreach ($fetch as $entry){
          ?> <tr> <?php
          foreach ($columnnames as $column){
            ?><td><?php
              echo $fetch[json_encode($column['Field'])]; //for whatever reason it says that the index name is undefined even though it's meant to work??? have done literally everything i can to try and fix this and nothing works
            ?></td> <?php
          }
        }?>
                <td>
                <a href=''><img src='images/delete_icon.png' alt='delete_icon' class='icon'></a>
                <a href=''><img src='images/edit_icon.jpg' alt='edit_icon' class='icon'></a>
               </td>
          </tr>

      </tbody>

      <tfoot>
        <tr>
          <?php
          foreach ($columnnames as $column){
            if ($column['Type'] == 'varchar(30)' or $column['Type'] == 'varchar(50)' or $column['Type'] == 'varchar(100)'){ //if we're storing strings any other way add them here
              echo "<td> <input type='text' placeholder= $column[Field] id= $column[Field]> </td>";

            } elseif ($column['Type'] == 'tinyint(1)'){ //this is how we're storing booleans
              echo "<td> <div class='checkbox'> <input type='checkbox' id= $column[Field]> </div> </td>";

            } else{
              echo "<td> <input class='numberField' type='number' placeholder= $column[Field] id= $column[Field]> </td>";

            }
          }

          ?>
          <td><button type="submit">Insert record</button></td>
        </tr>
      </tfoot>
    </table>


</body>

<!-- notice that there are some repeated parts and there is
probably a way to extract that into some sort of function
in php (possibly into another php file and then #include at the top) -->

</html>
