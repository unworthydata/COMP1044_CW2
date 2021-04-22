

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Employee Portal - Webflix</title>
    <link rel="stylesheet" href="styles/normalize.css" />
    <link rel="stylesheet" href="styles/styles.css" />
    <link rel="stylesheet" href="styles/employee.css" />
  </head>
  <body>
    <h1>Employee Portal</h1>
    <div class="tableSelection">
      <label for="tables">Choose a table:</label>
      <select id="tables" name="tables">
      <!-- Add logic here to select tables (so that it is dynamic and changes with the SQL databse) -->
        <?php
        $tableName = 'actors';
        echo "<option value='$tableName'>$tableName</option>";
        ?>
      </select>
    </div>

    <div></div>

    <table>
      <thead>
        <th>Country</th>
        <th>OrderID</th>
        <th>Order Amount</th>
      </thead>
      <tr>
        <td>USA</td>
        <td>1000</td>
        <td>$1,300</td>
      </tr>
      <tr>
        <td>USA</td>
        <td>1001</td>
        <td>$700</td>
      </tr>
      <tr>
        <td>CA</td>
        <td>1002</td>
        <td>$2,000</td>
      </tr>
      <tr>
        <td>CA</td>
        <td>1003</td>
        <td>$1,000</td>
      </tr>
      <tfoot>
        <td>Total</td>
        <td></td>
        <td>$5,000</td>
      </tfoot>
    </table>


  <<?php  //connecting to the database
  $conn = mysqli_connect('localhost', 'test', 'test123', 'entertainment'); /*host, username, password, databasename*/

  //checking the connection to make sure it worked
  if(!$conn){ //if the connection has failed (is false):
    echo 'Connection Error:' . mysqli_connect_error(); //concatenates the source of the error to the end of the echo
  }

  $query = 'SELECT * FROM Film'; //query
  $result = mysqli_query($conn, $query); //result of the query
  $fetch = mysqli_fetch_all($result, MYSQLI_ASSOC);
  mysqli_free_result($result); //we don't need result anymore so we free that from memory
  mysqli_close($conn); //we don't need the connection anymore since we got our array so we close the connection to the database
?>


    <table>
      <thead>
        <th>Film ID</th>
        <th>Title</th>
        <th>Description</th>
        <th>Release Year</th>
        <th>Language ID</th>
        <th>Rental Duration</th>
        <th>Rental Rate</th>
        <th>Length</th>
        <th>Replacement Cost</th>
        <th>Rating</th>
        <th>Special Features</th>
        <th>Last Updated</th>
      </thead>
      <?php
      foreach($fetch as $Film){ ?>
      <tr>
        <td><?php echo $Film['film_id'];?></td>
        <td><?php echo $Film['title'];?></td>
        <td><?php echo $Film['description'];?></td>
        <td><?php echo $Film['release_year'];?></td>
        <td><?php echo $Film['language_id'];?></td>
        <td><?php echo $Film['rental_duration'];?></td>
        <td><?php echo $Film['rental_rate'];?></td>
        <td><?php echo $Film['length'];?></td>
        <td><?php echo $Film['replacement_cost'];?></td>
        <td><?php echo $Film['rating'];?></td>
        <td><?php echo $Film['special_features'];?></td>
        <td><?php echo $Film['last_update'];?></td>
      </tr>
<?php  }
/*THIS SNIPPET OF CODE IS NOT RUNNABLE FOR MULTIPLE REASONS:
1. YOU NEED TO CREATE A NEW USER IN PHPMYADMIN WITH THE SAME DETAILS AS THE ONE IN THE 3RD LINE (THIS WON'T BE A PROBLEM ONCE WE GET AN ACTUAL SERVER GOING AND WILL MAKE IT EASY TO SET PERMISSIONS FOR USERS)
2. YOU NEED TO GO INTO YOUR XAMPP DIRECTORY AND FIND THE 'HTDOCS' FOLDER (C:\xampp\htdocs) AND CREATE A NEW FOLDER AND PLACE THIS FILE INTO IT. THE FOLDER NAME BE THE URL FOR ACTUALLY LOADING THE PHP (localhost/foldername/test.php)
*/  ?>

    </table>
  </body>
</html>
