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

	function getWeekRange($curdate){

		$cur_day_of_the_week = date("w", strtotime($curdate));
		$curdate = date("Y-m-d", strtotime($curdate));

		$range = array();
		$range['start'] = date("Y-m-d", strtotime($curdate) - ($cur_day_of_the_week - 1)*24*60*60);
		$range['finish'] = date("Y-m-d", strtotime($range["start"]) + 6.5*24*60*60);

		return $range;
	}

	$UBER = 1;
	$GETT = 2;
	$WHEELY = 3;

  $params = json_decode(file_get_contents('php://input'),true);

  $start_date = substr($params['start'], 0,10);
  $end_date = substr($params['end'], 0,10);

  // $start_date = "2017-07-17";
  // $end_date = "2017-07-23";

  $week_range = getWeekRange($start_date);
  $week_start = $week_range['start'];
	$week_finish = $week_range['finish'];

	file_put_contents('weekly_get_correction_data.sql', date("Y-m-d H;i:s"). "\n");

	/* Таблица MySQL, в которой хранятся данные */
	$table = "trips";

	$uber_start_date = substr($start_date, 0, 10) . ' 04:00:00';
	$uber_end_date = date("Y-m-d", strtotime($end_date) + 24*60*60) . ' 03:59:59';

	// $uber_end_date = substr($end_date, 0, 10) . ' 03:59:59';

	$start_date = substr($start_date, 0, 10) . ' 00:00:00';
	$end_date = substr($end_date, 0, 10) . ' 23:59:59';

	$query = "SELECT drivers.surname, drivers.firstname, drivers.patronymic, drivers.bank_rate, drivers.card_number, drivers.beneficiar, work_types.id group_id, work_types.name group_name, work_types.is_daily, work_types.is_park, work_types.is_own_park, work_types.uber_park_comission, work_types.wheely_park_comission, drivers.id driver_id, uber_data.sum_fare uber_sum_fare, uber_data.sum_comission uber_sum_comission, uber_data.sum_cash uber_sum_cash, uber_data.sum_result uber_sum_result, uber_data.sum_boost uber_sum_boost, gett_data.sum_fare gett_sum_fare, gett_data.sum_comission gett_sum_comission, gett_data.sum_cash gett_sum_cash, gett_data.sum_result gett_sum_result, gett_data.sum_boost gett_sum_boost, weeks.id week_id, weekly_freelancers.uber_bonus, weekly_freelancers.yandex_cash, weekly_freelancers.yandex_non_cash, yandex_asks1.asks_amount yandex_asks, freelancers_payouts1.payed_amount yandex_paid_freelancers, autoparks_payouts1.payed_amount yandex_paid_autoparks, wheely_data.sum_fare wheely_sum_fare, wheely_data.sum_comission wheely_sum_comission, wheely_data.sum_fines wheely_sum_fines, wheely_data.sum_boost wheely_sum_boost  FROM drivers ";

	$query .= " LEFT JOIN work_types ON work_types.id = drivers.work_type_id ";

	$query .= " LEFT JOIN weeks ON weeks.start_date = '$week_start' AND weeks.end_date = '$week_finish' ";

	$query .= " LEFT JOIN ( SELECT SUM(amount) asks_amount, driver_id FROM yandex_asks 
	 													LEFT JOIN weeks ON weeks.start_date = '$week_start' AND weeks.end_date = '$week_finish' 
														WHERE yandex_asks.week_id = weeks.id
														GROUP BY yandex_asks.driver_id) yandex_asks1 

									ON drivers.id = yandex_asks1.driver_id ";

	$query .= " LEFT JOIN ( SELECT SUM(amount) payed_amount, driver_id FROM freelancers_payouts 
	 													LEFT JOIN weeks ON weeks.start_date = '$week_start' AND weeks.end_date = '$week_finish' 
														WHERE freelancers_payouts.week_id = weeks.id
														GROUP BY freelancers_payouts.driver_id) freelancers_payouts1 

									ON drivers.id = freelancers_payouts1.driver_id ";

	$query .= " LEFT JOIN ( SELECT SUM(amount) payed_amount, driver_id FROM autoparks_payouts 
	 													LEFT JOIN weeks ON weeks.start_date = '$week_start' AND weeks.end_date = '$week_finish' 
														WHERE autoparks_payouts.week_id = weeks.id
														GROUP BY autoparks_payouts.driver_id) autoparks_payouts1 

									ON drivers.id = autoparks_payouts1.driver_id ";
	
	$query .= " LEFT JOIN weekly_freelancers ON drivers.id = weekly_freelancers.driver_id AND weekly_freelancers.week_id = weeks.id ";

	$query .= " LEFT JOIN (SELECT SUM(fare) sum_fare, SUM(comission) sum_comission, SUM(cash) sum_cash, SUM(result) sum_result, SUM(boost_non_commissionable) sum_boost, driver_id, mediator_id, date_time FROM $table ";
    $query .="WHERE mediator_id=$UBER AND date_time BETWEEN '$uber_start_date' AND '$uber_end_date' ";
  $query .= 'GROUP BY driver_id) uber_data ON uber_data.driver_id = drivers.id ';

	$query .= " LEFT JOIN (SELECT SUM(fare) sum_fare, SUM(comission) sum_comission, SUM(cash) sum_cash, SUM(result) sum_result, SUM(boost_non_commissionable) sum_boost, driver_id, mediator_id, date_time FROM $table ";
  $query .="WHERE mediator_id=$GETT AND date_time BETWEEN '$start_date' AND '$end_date' ";
  $query .= 'GROUP BY driver_id) gett_data ON gett_data.driver_id = drivers.id ';

	$query .= " LEFT JOIN (SELECT SUM(fare) sum_fare, SUM(comission) sum_comission, SUM(result) sum_fines, SUM(boost_non_commissionable) sum_boost, driver_id, mediator_id, date_time FROM $table ";
  $query .="WHERE mediator_id=$WHEELY AND date_time BETWEEN '$start_date' AND '$end_date' ";
  $query .= 'GROUP BY driver_id) wheely_data ON wheely_data.driver_id = drivers.id ';

  $query .= " WHERE  work_types.is_daily = 0 AND (uber_data.sum_fare > 0 OR gett_data.sum_fare > 0 OR wheely_data.sum_fare > 0) ";

 
	file_put_contents('../logs/trip_weekly_select.log', date('Y:m-d H:i:s') . PHP_EOL . $query . PHP_EOL);

	$result = mysql_query($query) or die(mysql_error());

	$respond = array();
	while ($row = mysql_fetch_assoc($result)) 
	{
		$ubc = get_correction_data($UBER, $start, $end, $row['driver_id']);
		$row['uber_correction'] = $ubc['sum_fare'] ? $ubc['sum_fare'] : 0;

		$gtc = get_correction_data($GETT, $start, $end, $row['driver_id']);
		$row['gett_correction'] = $gtc['sum_fare'] ? $ubc['sum_fare'] : 0;

		$respond[] = $row;
	};

	echo json_encode($respond);
?>