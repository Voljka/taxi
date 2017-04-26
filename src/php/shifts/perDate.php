<?
	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

    $shift_date = $_GET['shift_date'];

    $OUR_1_1 = 2;
    $OUR_7_0 = 3;

	$table = "shifts";

	$query = "SELECT $table.*, own_auto_park.model, own_auto_park.state_number, drivers.surname, drivers.firstname, drivers.patronymic,drivers.work_type_id group_id, work_types.name group_name, dispatchers.name dispatcher_name FROM $table ";

	$query .= " LEFT JOIN drivers ON drivers.id = shifts.driver_id ";
	$query .= " LEFT JOIN work_types ON work_types.id = drivers.work_type_id ";
	$query .= " LEFT JOIN own_auto_park ON own_auto_park.id = shifts.auto_id ";
	$query .= " LEFT JOIN dispatchers ON dispatchers.id = shifts.dispatcher_id ";

	$query .= " WHERE shift_date = '$shift_date' AND drivers.work_type_id IN ($OUR_1_1, $OUR_7_0) ";
	$query .= " ORDER BY drivers.surname, drivers.firstname, drivers.patronymic ";

	file_put_contents('shift_per_date.sql', $query);
	
	$result = mysql_query($query) or die(mysql_error());

	$respond = array();
	while ($row = mysql_fetch_assoc($result)) 
	{
		$respond[] = $row;
	};

	echo json_encode($respond);
?>