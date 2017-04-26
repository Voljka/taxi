<?
	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

	$table = "road_fines";

	$query = "SELECT $table.*, drivers.surname, drivers.firstname, drivers.patronymic, own_auto_park.model, own_auto_park.state_number, drivers.work_type_id group_id,  work_types.name group_name FROM $table ";

	$query .= " LEFT JOIN own_auto_park ON own_auto_park.id = $table.auto_id ";

	$query .= " LEFT JOIN shifts ON road_fines.auto_id = shifts.auto_id AND road_fines.fined_at BETWEEN (CONCAT(shifts.shift_date, ' 12:00:00')) AND CONCAT(DATE_ADD(shifts.shift_date, INTERVAL 1 DAY), ' 11:59:59') ";	

	$query .= " LEFT JOIN drivers ON drivers.id = shifts.driver_id ";
	$query .= " LEFT JOIN work_types ON work_types.id = drivers.work_type_id ";

	file_put_contents('all_autopark.sql', $query);
	
	$result = mysql_query($query) or die(mysql_error());

	$respond = array();
	while ($row = mysql_fetch_assoc($result)) 
	{
		$respond[] = $row;
	};

	echo json_encode($respond);
?>
