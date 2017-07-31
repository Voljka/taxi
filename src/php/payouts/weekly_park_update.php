<?
	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

    $id = $params['id'];
    $paid_at = $params['paid_at'];
    $park_id = $params['group_id'];
    $amount = $params['amount'];
    $week_id = $params['week_id'];
    $payout_type_id = $params['payout_type_id'];


	/* Таблица MySQL, в которой хранятся данные */
	$table = "autoparks_payouts";

	$query = "UPDATE $table SET paid_at = '$paid_at', park_id=$park_id, amount=$amount, week_id=$week_id, payout_type_id = $payout_type_id ";
	$query .= " WHERE id=$id ";

	// echo $query;
    file_put_contents('../logs/autoparks_payouts.log', date("Y-m-d H:i:s") . ' ' . $query . PHP_EOL , FILE_APPEND);

	$result = mysql_query($query) or die(mysql_error());
    echo $result;

?>