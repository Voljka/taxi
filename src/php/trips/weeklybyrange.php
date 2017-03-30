<?

	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

    // $start_date = $_POST['start'];
    // $end_date = $_POST['end'];
    $start_date = $params['start'];
    $end_date = $params['end'];

	/* Таблица MySQL, в которой хранятся данные */
	$table = "trips";

	$start_date = substr($start_date, 0, 10) . ' 00:00:00';
	$end_date = substr($end_date, 0, 10) . ' 23:59:59';

	// $query = "SELECT *, drivers.surname, drivers.firstname, drivers.patronymic, work_types.name group_name, work_types.is_daily FROM $table ";
	// $query .= "LEFT JOIN drivers ON drivers.id = $table.driver_id ";
	// $query .= "LEFT JOIN work_types ON work_types.id = drivers.work_type_id ";
 //    $query .="WHERE work_types.is_daily = 0 AND date_time BETWEEN '$start_date' AND '$end_date' ";
 //    $query .= "ORDER BY mediator_id, drivers.surname, drivers.firstname, drivers.patronymic, date_time ";


	$query = "SELECT SUM(fare) sum_fare, SUM(comission) sum_comission, SUM(cash) sum_cash, SUM(result) sum_result, driver_id, mediator_id, drivers.surname, drivers.firstname, drivers.patronymic, work_types.id group_id, work_types.name group_name, work_types.is_daily, work_types.is_park FROM $table ";
	$query .= "LEFT JOIN drivers ON drivers.id = $table.driver_id ";
	$query .= "LEFT JOIN work_types ON work_types.id = drivers.work_type_id ";
    $query .="WHERE work_types.is_daily = 0 AND date_time BETWEEN '$start_date' AND '$end_date' ";
    $query .= 'GROUP BY mediator_id, drivers.work_type_id, driver_id ';
    $query .= "ORDER BY mediator_id, drivers.surname, drivers.firstname, drivers.patronymic, date_time ";

	file_put_contents('../logs/trip_weekly_select.log', date('Y:m-d H:i:s') . PHP_EOL . $query . PHP_EOL, FILE_APPEND);

	$result = mysql_query($query) or die(mysql_error());

	$respond = array();
	while ($row = mysql_fetch_assoc($result)) 
	{
		$respond[] = $row;
	};

	echo json_encode($respond);
	// echo count($respond);
?>