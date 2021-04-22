<?php
  //connecting to the database
  $conn = mysqli_connect('localhost', 'test', 'test123', 'entertainment'); /*host, username, password, databasename*/

  //checking the connection to make sure it worked
  if(!$conn){ //if the connection has failed (is false):
    echo 'Connection Error:' . mysqli_connect_error(); //concatenates the source of the error to the end of the echo
  }

  //An SQL query in PHP is written as a string variable representing the SQL Code
  //For example: $query = 'SELECT * FROM actor'; is a full runnable piece of code

  //Making the query is done as so:
  //$result = mysqli_query($conn, $query);

  //fetching the resulting rows as a 2D array is done as so:
  //$fetch = mysqli_fetch_all($result, MYSQLI_ASSOC);
  //this results in an array of results from the query

  //we do not need $result in memory anymore so we can free it
  //mysqli_free_result($result);

  //we then close the connection since we don't need it anymore
  //mysqli_close($conn);



  //SAMPLE OUTPUT
  $query = 'SELECT * FROM Film';
  $result = mysqli_query($conn, $query);
  $fetch = mysqli_fetch_all($result, MYSQLI_ASSOC);
  print_r($fetch); //displaying it on the webpage (You can use this array in HTML to display variable in a table)
  //you can loop through the array using php: <?php foreach($Film as $fetch){ and then write HtML in there

  mysqli_free_result($result);


/*THIS SNIPPET OF CODE IS NOT RUNNABLE FOR MULTIPLE REASONS:
1. YOU NEED TO CREATE A NEW USER IN PHPMYADMIN WITH THE SAME DETAILS AS THE ONE IN THE 3RD LINE (THIS WON'T BE A PROBLEM ONCE WE GET AN ACTUAL SERVER GOING AND WILL MAKE IT EASY TO SET PERMISSIONS FOR USERS)
2. YOU NEED TO GO INTO YOUR XAMPP DIRECTORY AND FIND THE 'HTDOCS' FOLDER (C:\xampp\htdocs) AND CREATE A NEW FOLDER AND PLACE THIS FILE INTO IT. THE FOLDER NAME BE THE URL FOR ACTUALLY LOADING THE PHP (localhost/foldername/test.php)
*/
  ?>
