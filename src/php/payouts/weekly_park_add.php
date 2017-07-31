<?

	require ('../config/db.config.php');

  $params = json_decode(file_get_contents('php://input'),true);

  $paid_at = $params['paid_at'];
  $park_id = $params['group_id'];
  $amount = $params['amount'];
  $week_id = $params['week_id'];
  $payout_type_id = $params['payout_type_id'];

    // $payed_at = date("Y-m-d H:i:s");

	/* Таблица MySQL, в которой хранятся данные */
	$table = "autoparks_payouts";

	$query = "INSERT INTO $table (paid_at, park_id, week_id, amount, payout_type_id ) VALUES (";
  $query .="'$paid_at',";
	$query .="$park_id,";
	$query .="$week_id,";
	$query .="$amount,";
  $query .="$payout_type_id)";

	file_put_contents('../logs/autoparks_payouts.log', date("Y-m-d H:i:s") . ' ' .$query . PHP_EOL , FILE_APPEND);
	// echo $query;
	
	$result = mysql_query($query) or die(mysql_error());
	
?>