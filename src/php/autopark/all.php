<?
	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

	$table = "own_auto_park";

	$curdate = date("Y-m-d");

	$query = "SELECT $table.*, auto_rental_costs.cost_daily, auto_rental_costs.cost_weekly, auto_rental_costs.id last_rule_id FROM $table ";
	$query .= " LEFT JOIN auto_rental_costs ON $table.id = auto_rental_costs.auto_id AND auto_rental_costs.started_at <='". $curdate ."' AND (auto_rental_costs.ended_at IS NULL OR auto_rental_costs.ended_at >='". $curdate. "') ";

	file_put_contents('all_autopark.sql', $query);
	
	$result = mysql_query($query) or die(mysql_error());

	$respond = array();
	while ($row = mysql_fetch_assoc($result)) 
	{
		$respond[] = $row;
	};

	echo json_encode($respond);
?>
