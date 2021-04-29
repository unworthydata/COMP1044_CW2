<?php
function queryInputs($column)
{
	if ($column['Type'] == 'varchar(30)' or $column['Type'] == 'varchar(50)' or $column['Type'] == 'varchar(100)' or $column['Type'] == 'varchar(255)')
		echo "<th> <input type='text' placeholder='$column[Field]' name='$column[Field]_condition'> </th>";
	elseif ($column['Type'] == 'tinyint(1)')  //this is how we're storing booleans
		echo "<th> <input type='checkbox' name='$column[Field]_condition'> </th>";
	elseif ($column['Type'] == 'datetime')
		echo "<th> <input type='date' name='$column[Field]_condition'> </th>";
	else
		echo "<th class='numericQueryHeader'>
			  <select class='operationList' name='$column[Field]_condition'>
				<option value='' disabled selected></option>
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

function insertInputs($column)
{
	if ($column['Type'] == 'varchar(30)' or $column['Type'] == 'varchar(50)' or $column['Type'] == 'varchar(100)' or $column['Type'] == 'varchar(255)')
		echo "<td> <input type='text' placeholder='$column[Field]' name='$column[Field]_insert'> </td>";
	elseif ($column['Type'] == 'tinyint(1)')
		echo "<td> <input type='checkbox' name='$column[Field]_insert'> </td>";
	elseif ($column['Type'] == 'datetime')
		echo "<td> <input type='date' name='$column[Field]_insert'> </td>";
	else
		echo "<td> <input class='numberField' type='number' placeholder='$column[Field]' name='$column[Field]_insert'> </td>";
}

function updateInputs($column, $currentValue)
{
	echo "<fieldset>
			<label>$column[Field]</label>";
	if ($column['Type'] == 'varchar(30)' or $column['Type'] == 'varchar(50)' or $column['Type'] == 'varchar(100)' or $column['Type'] == 'varchar(255)')
		echo "<input type='text' value='$currentValue' name='$column[Field]_update'>";
	elseif ($column['Type'] == 'tinyint(1)')
		echo "<input type='checkbox' name='$column[Field]_update' value='$currentValue'>";
	elseif ($column['Type'] == 'datetime')
		echo "<input type='date' name='$column[Field]_condition' value='$currentValue'>";
	else
		echo "<input class='numberField' type='number' value='$currentValue' name='$column[Field]_update'>";
	echo "</fieldset>";
}
