<?

	require ('../config/db.config.php');


	function get_correction_data($mediator_id, $start, $end, $driver_id){
		$query1 = "SELECT 
						driver_id, 
						SUM(amount) sum_fare, 
						0 sum_comission, 
						0 sum_cash, 
						0 sum_result ";
		$query1 .= "FROM corrections	";
		$query1 .= "WHERE corrections.mediator_id=$mediator_id AND corrections.driver_id= $driver_id AND recognized_at >= '$start' AND recognized_at <= '$end'  ";
		$query1 .= "	GROUP BY corrections.driver_id ";

		file_put_contents('weekly_get_correction_data.sql', $query1 . "\n", FILE_APPEND);

		$result = mysql_query($query1) or die(mysql_error());
		$result_set = array();
		
		while ($row = mysql_fetch_assoc($result)) 
		{
			$result_set['sum_fare'] = $row['sum_fare'];
			$result_set['sum_result'] = $row['sum_result'];
			$result_set['sum_comission'] = $row['sum_comission'];
			$result_set['sum_cash'] = $row['sum_cash'];
		};

		return $result_set;
	}

	$UBER = 1;
	$GETT = 2;

  $params = json_decode(file_get_contents('php://input'),true);

  $start_date = $params['start'];
  $end_date = $params['end'];

	file_put_contents('weekly_get_correction_data.sql', date("Y-m-d H;i:s"). "\n");

	/* Таблица MySQL, в которой хранятся данные */
	$table = "trips";

	$uber_start_date = substr($start_date, 0, 10) . ' 04:00:00';
	$uber_end_date = date("Y-m-d", strtotime($end_date) + 24*60*60) . ' 03:59:59';

	// $uber_end_date = substr($end_date, 0, 10) . ' 03:59:59';

	$start_date = substr($start_date, 0, 10) . ' 00:00:00';
	$end_date = substr($end_date, 0, 10) . ' 23:59:59';

	$query = "SELECT * FROM ( SELECT SUM(fare) sum_fare, SUM(comission) sum_comission, SUM(cash) sum_cash, SUM(result) sum_result, SUM(boost_non_commissionable) sum_boost, driver_id, mediator_id, drivers.surname, drivers.firstname, drivers.patronymic, drivers.bank_rate, work_types.id group_id, work_types.name group_name, work_types.is_daily, work_types.is_park, work_types.uber_park_comission, date_time FROM $table ";
	$query .= "LEFT JOIN drivers ON drivers.id = $table.driver_id ";
	$query .= "LEFT JOIN work_types ON work_types.id = drivers.work_type_id ";
    $query .="WHERE mediator_id=$UBER AND work_types.is_daily = 0 AND date_time BETWEEN '$uber_start_date' AND '$uber_end_date' ";
  $query .= 'GROUP BY mediator_id, drivers.work_type_id, driver_id ';
  // $query .= "ORDER BY mediator_id, drivers.surname, drivers.firstname, drivers.patronymic, date_time ";

  $query .= " UNION ";
	$query .= "SELECT SUM(fare) sum_fare, SUM(comission) sum_comission, SUM(cash) sum_cash, SUM(result) sum_result, SUM(boost_non_commissionable) sum_boost, driver_id, mediator_id, drivers.surname, drivers.firstname, drivers.patronymic, drivers.bank_rate, work_types.id group_id, work_types.name group_name, work_types.is_daily, work_types.is_park, work_types.uber_park_comission, date_time FROM $table ";
	$query .= "LEFT JOIN drivers ON drivers.id = $table.driver_id ";
	$query .= "LEFT JOIN work_types ON work_types.id = drivers.work_type_id ";
    $query .="WHERE mediator_id=$GETT AND  work_types.is_daily = 0 AND date_time BETWEEN '$start_date' AND '$end_date' ";
    $query .= 'GROUP BY mediator_id, drivers.work_type_id, driver_id) a ';
    $query .= "ORDER BY mediator_id, surname, firstname, patronymic, date_time ";

	file_put_contents('../logs/trip_weekly_select.log', date('Y:m-d H:i:s') . PHP_EOL . $query . PHP_EOL);

	$result = mysql_query($query) or die(mysql_error());

	$respond = array();
	while ($row = mysql_fetch_assoc($result)) 
	{
		// if ($row['mediator_id'] == "1") {
		// 	$ubc = get_correction_data($UBER, $start_date, $end_date, $row['driver_id']);
		// 	$row['sum_correction'] = $ubc['sum_fare'] ? $ubc['sum_fare'] : 0;
		// };

		if ($row['mediator_id'] == 1) {
			$start = $uber_start_date;
			$end = $uber_end_date;
		} else {
			$start = $start_date;
			$end = $end_date;
		}

		$ubc = get_correction_data($row['mediator_id'], $start, $end, $row['driver_id']);
		$row['sum_correction'] = $ubc['sum_fare'] ? $ubc['sum_fare'] : 0;

		// $ubc = get_correction_data($UBER, $start_date, $end_date, $row['driver_id']);
		// $row['sum_correction'] = $ubc['sum_fare'] ? $ubc['sum_fare'] : 0;

		$respond[] = $row;
	};

	echo json_encode($respond);
?>