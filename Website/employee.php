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
        <?php
        // get the table names in a foreach loop (Use query 'SHOW TABLES')
        // something like " foreach table "
        // for example and testing I set $table to Film
        $table = 'Film';
        echo "<option value=$table>$table</option>";
        ?>
      </select>

      <label for="sortSelect">Sort by</label>
      <select class="list" name="columns" id="columnList">
        <?php
        // get the table names in a foreach loop (Use query 'SHOW COLUMNS FROM $table')
        // something like " foreach column in table "
        // for example and testing I set $column to film_id
        $column = 'film_id';
        echo "<option value=$column>$column</option>";
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

      // foreach column
      // if text column then
      echo "<th> <input type='text' placeholder='Name' id='mame'> </th>";

      // else if boolean
      echo "<th> <div class='checkbox'> <input type='checkbox' id='manager'> </div> </th>";

      // else 
      echo "<th class='numericQueryHeader'>
        <select class='operationList' name='operations'>
          <option value='equal'>==</option>
          <option value='notEqual'>&#8800;</option>
          <option value='lessThan'>&lt;</option>
          <option value='greaterThan'>&gt;</option>
          <option value='lessThanOrEq'>&le;</option>
          <option value='greaterThanOrEq'>&ge;</option>
        </select>
        <input class='numberField' type='number' placeholder='Age' id='age'> </th>";
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

        // foreach column
        // if text column then
        echo "<td> <input type='text' placeholder='Name' id='mame'> </td>";

        // else if boolean
        echo "<td> <div class='checkbox'> <input type='checkbox' id='manager'> </div> </td>";

        // else 
        echo "<td> <input class='numberField' type='number' placeholder='Age' id='age'> </td>";
        ?>
        <td><button type="submit">Insert record</button></td>
      </tr>
    </tfoot>
  </table>

  <!-- table with functionality outline -->
  <!-- note that it is wrapped with a hidden div, remove that div tag before and after table so you can see it-->
  <div hidden>
    <table>
      <!-- for querying -->
      <thead>
        <?php
        // foreach column
        // if text column then
        echo "<th> <input type='text' placeholder=$column id=$column> </th>";
        // else if boolean
        echo "<th> <div class='checkbox'> <input type='checkbox' id=$column> </div> </th>";
        // else
        echo "<th class='numericQueryHeader'>
          <select class='operationList' name='operations'>
            <option value='equal'>==</option>
            <option value='notEqual'>&#8800;</option>
            <option value='lessThan'>&lt;</option>
            <option value='greaterThan'>&gt;</option>
            <option value='lessThanOrEq'>&le;</option>
            <option value='greaterThanOrEq'>&ge;</option>
          </select>
          <input class='numberField' type='number' placeholder=$column id=$column> </th>";
        ?>
        <th>
          <button type="reset">Clear</button>
          <button type="submit">Query</button>
        </th>
      </thead>

      <!-- for headers/column names -->
      <thead>
        <?php
        // get the table names in a foreach loop (Use query 'SHOW COLUMNS FROM $table')
        // something like " foreach ($fetch as $table) "

        // foreach entry 
        echo "<th>";
        // foreach column/attribute in an entry
        echo  "<td>$columnName</td>";

        echo "</th>";
        ?>
      </thead>

      <tbody>
        <!-- for actual entries -->
        <?php
        // foreach entry 
        echo "<tr>";
        // foreach column/attribute in an entry
        echo  "<td>$attributeValue</td>";
        echo  "<td>
                <a href=''><img src='images/delete_icon.png' alt='delete_icon' class='icon'></a>
                <a href=''><img src='images/edit_icon.jpg' alt='edit_icon' class='icon'></a>
               </td>";
        echo  "</tr>";
        ?>
      </tbody>

      <tfoot>
        <tr>
          <?php

          // foreach column
          // if text column then
          echo "<td> <input type='text' placeholder='Name' id='mame'> </td>";

          // else if boolean
          echo "<td> <div class='checkbox'> <input type='checkbox' id='manager'> </div> </td>";

          // else 
          echo "<td> <input class='numberField' type='number' placeholder='Age' id='age'> </td>";
          ?>
          <td><button type="submit">Insert record</button></td>
        </tr>
      </tfoot>
    </table>
  </div>

</body>

<!-- notice that there are some repeated parts and there is 
probably a way to extract that into some sort of function 
in php (possibly into another php file and then #include at the top) -->

</html>