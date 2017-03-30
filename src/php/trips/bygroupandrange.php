<?

	require ('../config/db.config.php');

    $start_date = $_POST['start'];
    $end_date = $_POST['end'];
    $group_id = $_POST['group'];

	/* Таблица MySQL, в которой хранятся данные */
	$table = "trips";

	$start_date = substr($start_date, 0, 10) . ' 04:00:00';
	$end_date = substr($end_date, 0, 10) . ' 04:00:00';

	$query = "SELECT *, drivers.surname, drivers.firstname, drivers.patronymic, work_types.name group_name, work_types.is_daily FROM $table ";
	$query .= "LEFT JOIN drivers ON drivers.id = $table.driver_id ";
	$query .= "LEFT JOIN work_types ON work_types.id = drivers.work_type_id ";
    $query .="WHERE work_types.id = $group_id AND date_time BETWEEN '$start_date' AND '$end_date' ";
    $query .= "ORDER BY mediator_id, drivers.surname, drivers.firstname, drivers.patronymic, date_time ";

	// echo $query;

	$result = mysql_query($query) or die(mysql_error());

	$respond = array();
	while ($row = mysql_fetch_assoc($result)) 
	{
		$respond[] = $row;
	};

	echo json_encode($respond);
	// echo count($respond);
?>