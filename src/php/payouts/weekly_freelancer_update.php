<?
	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

    $id = $params['id'];
    $paid_at = $params['paid_at'];
    $driver_id = $params['driver_id'];
    $amount = $params['amount'];
    $week_id = $params['week_id'];
    $payout_type_id = $params['payout_type_id'];


	/* Таблица MySQL, в которой хранятся данные */
	$table = "freelancers_payouts";

	$query = "UPDATE $table SET paid_at = '$paid_at', driver_id=$driver_id, amount=$amount, week_id=$week_id, payout_type_id = $payout_type_id ";
	$query .= " WHERE id=$id ";

	// echo $query;
    file_put_contents('../logs/freelancers_payouts.log', date("Y-m-d H:i:s") . ' ' . $query . PHP_EOL , FILE_APPEND);

	$result = mysql_query($query) or die(mysql_error());
    echo $result;

?>