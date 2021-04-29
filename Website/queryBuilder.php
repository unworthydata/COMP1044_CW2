<?php
function buildQuery($columnNames, $getArray)
{
	$condition = "";
	foreach ($columnNames as $column) {
		if ($column['Type'] !== 'datetime'){
			if (isset($_GET["$column[Field]_condition"])) {
				$comparison = trim($getArray["$column[Field]_condition"]);

				if ( //it seems like this condition does not run but the WHERE condition is still added onto the SQL
					($column['Type'] == 'varchar(30)' ||
						$column['Type'] == 'varchar(50)' ||
						$column['Type'] == 'varchar(100)') ||
						$column['Type'] == 'varchar(255)' &&
					$comparison !== ""
				){
					$condition = $condition . $column['Field'] . " LIKE " . "'$comparison'" . " AND ";
				}
				else if ($column['Type'] == 'tinyint(1)')
					$condition = $condition . $column['Field'] . " = " . "TRUE AND ";
				else if ($column['Type'] == 'datetime') {
					$condition = $condition . $column['Field'] . " = " . "'$comparison' AND ";
				} else {
					if (isset($_GET["$column[Field]_quantity"])) {
						$operator = '=';
						switch ($comparison) {
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
						$condition = $condition . $column['Field'] . $operator . $getArray["$column[Field]_quantity"] . " AND ";
					}
				}
			}
	}
	$condition = substr($condition, 0, -5);
	return $condition;
}
}
