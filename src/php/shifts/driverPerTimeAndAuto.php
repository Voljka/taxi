<?
	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

    $shift_date = $_GET['shift_date'];
    $auto_id = $_GET['auto_id'];

    $OUR_1_1 = 2;
    $OUR_7_0 = 3;

	$table = "shifts";

	$query = "SELECT $table.*, drivers.surname, drivers.firstname, drivers.patronymic, work_types.name group_name, drivers.work_type_id FROM $table ";

	$query .= " LEFT JOIN drivers ON drivers.id = shifts.driver_id ";
	$query .= " LEFT JOIN work_types ON work_types.id = drivers.work_type_id ";

	$query .= " WHERE auto_id = $auto_id AND '$shift_date' BETWEEN shifts.start_time AND shifts.finish_time  ";

	file_put_contents('driver_per_date_and_auto.sql', date("Y-m-d H:i:s") . "\n" . $query);
	
	$result = mysql_query($query) or die(mysql_error());

	$respond = array();
	while ($row = mysql_fetch_assoc($result)) 
	{
		$respond[] = $row;
	};

	echo json_encode($respond);
?>