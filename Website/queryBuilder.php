<?php
function buildQuery($columnNames, $getArray)
{
	$condition = "";
	foreach ($columnNames as $column) {
		if (isset($_GET["$column[Field]_condition"])) {
			$comparison = trim($getArray["$column[Field]_condition"]);

			if (
				$comparison !== '' &&
				($column['Type'] == 'varchar(30)' ||
					$column['Type'] == 'varchar(50)' ||
					$column['Type'] == 'varchar(100)' ||
					$column['Type'] == 'varchar(255)')
			) {
				$condition = $condition . $column['Field'] . " REGEXP " . "'$comparison'" . " AND ";
			} else if ($column['Type'] == 'tinyint(1)')
				$condition = $condition . $column['Field'] . " = " . "TRUE AND ";
			else if ($column['Type'] == 'datetime' && $comparison !== '') {
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
	if ($condition !== '') {
		$condition = substr($condition, 0, -5); //if you do this while $condition is '' it no longer becomes '' according to PHP
	}
	return $condition;
}
