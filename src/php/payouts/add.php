<?

	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

    $report_date = $params['report_date'];
    $payed_at = $params['payed_at'];
    $driver_id = $params['driver_id'];
    $amount = $params['amount'];
    $is_daily = $params['is_daily'];

    $payed_at = date("Y-m-d H:i:s");

	/* Таблица MySQL, в которой хранятся данные */
	$table = "payouts";

	$query = "INSERT INTO $table (report_date, payed_at, driver_id, is_daily, amount ) VALUES (";
    $query .="'$report_date',";
    $query .="'$payed_at',";
	$query .="$driver_id,";
	$query .="$is_daily,";
    $query .="$amount)";

	file_put_contents('../logs/payouts.log', date("Y-m-d H:i:s") . ' ' .$query . PHP_EOL , FILE_APPEND);
	// echo $query;
	
	$result = mysql_query($query) or die(mysql_error());
	
?>