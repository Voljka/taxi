<?
	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

    $id = $params['id'];
    $report_date = $params['report_date'];
    $payed_at = $params['payed_at'];
    $driver_id = $params['driver_id'];
    $amount = $params['amount'];
    $is_daily = $params['is_daily'];


	/* Таблица MySQL, в которой хранятся данные */
	$table = "payouts";

	$query = "UPDATE $table SET report_date='$report_date', payed_at = '$payed_at', driver_id=driver_id, amount=$amount, is_daily=$is_daily ";
	$query .= " WHERE id=$id ";

	// echo $query;
    file_put_contents('../logs/payouts.log', date("Y-m-d H:i:s") . ' ' . $query . PHP_EOL , FILE_APPEND);

	$result = mysql_query($query) or die(mysql_error());
    echo $result;

?>