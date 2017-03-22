<?
	require ('../config/db.config.php');

	$table = "drivers";

	$query = "SELECT $table.*, banks.name bank_name, work_types.name type_name FROM $table";
	$query .= " LEFT JOIN banks ON banks.id = $table.bank_id ";
	$query .= " LEFT JOIN work_types ON work_types.id = $table.work_type_id ";
	$query .= " ORDER BY $table.surname, $table.firstname, $table.patronymic ";
	
	$result = mysql_query($query) or die(mysql_error());

	$respond = array();
	while ($row = mysql_fetch_assoc($result)) 
	{
		$respond[] = $row;
	};

	echo json_encode($respond);
?>