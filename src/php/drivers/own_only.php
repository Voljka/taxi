<?
	require ('../config/db.config.php');

	$table = "drivers";

    $OUR_1_1 = 2;
    $OUR_7_0 = 3;	

	$query = "SELECT $table.*, work_types.name group_name FROM $table";
	$query .= " LEFT JOIN work_types ON work_types.id = $table.work_type_id ";
	$query .= " WHERE $table.work_type_id IN ($OUR_7_0, $OUR_1_1) ";
	$query .= " ORDER BY $table.surname, $table.firstname, $table.patronymic ";
	
	$result = mysql_query($query) or die(mysql_error());

	$respond = array();
	while ($row = mysql_fetch_assoc($result)) 
	{
		$respond[] = $row;
	};

	echo json_encode($respond);
?>