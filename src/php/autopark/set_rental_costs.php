<?

	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

    $last_rule_id = $params['last_rule_id'];
    $last_rule_finish = $params['last_rule_finish'];
    $new_daily = $params['new_daily'];
    $new_weekly = $params['new_weekly'];
    $new_started_at = $params['new_started_at'];
    $auto_id = $params['auto_id'];

	/* Таблица MySQL, в которой хранятся данные */
	$table = "auto_rental_costs";

	if ($last_rule_id) {
		$query = "UPDATE $table SET ended_at='$last_rule_finish' ";
		$query .= " WHERE id=$last_rule_id ";
	
		file_put_contents('../logs/autopark.log', date("Y-m-d H:i:s") . ' ' .$query . PHP_EOL , FILE_APPEND);

		$result = mysql_query($query) or die(mysql_error());
	} 

	$query = "INSERT INTO $table (cost_weekly, cost_daily, auto_id, started_at, ended_at) VALUES (";
	$query .="$new_weekly,";
	$query .="$new_daily,";
	$query .="$auto_id,";
    $query .="'$new_started_at',";
    $query .="NULL)";

	file_put_contents('../logs/autopark.log', date("Y-m-d H:i:s") . ' ' .$query . PHP_EOL , FILE_APPEND);
	// echo $query;
	
	$result = mysql_query($query) or die(mysql_error());
	
?>