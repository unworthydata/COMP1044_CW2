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

    <table>
      <?php
      // add logic to do headers
      // add logic to do content
        ?>
    </table>
  </body>
</html>
