<?php
  //connecting to the database
  $conn = mysqli_connect('localhost', 'test', 'test123', 'entertainment'); /*host, username, password, databasename*/

  //checking the connection to make sure it worked
  if(!$conn){ //if the connection has failed (is false):
    echo 'Connection Error:' . mysqli_connect_error(); //concatenates the source of the error to the end of the echo
  }


  //after the user's inputs have been validated we need to add the data to the database

//this code stops people from typing code into the database and having it be read as code
  //$data = mysqli_real_escape_string($conn, $data)
  //do this everytime for every element you're inputting into the database (every column essentially)

//now we have to write the SQL code itself to perform the insert the values
//$sql = "INSERT INTO table(columnname1, columnname2, columnname3) VALUES('$data1', 'data2', 'data3')";

//to save into the database and check
/*if(mysqli_query($conn, $sql)){
  the code has ran and you can redirect to a new page (probably one that displays all the columns so the user can see their column has been added)
} else {
 echo 'Error:' . mysqli_error($conn);
}*/
 ?>
