<?

	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

    $mediator_id = $params['mediator_id'];
    $trip_id = $params['trip_id'];
    $driver_id = $params['driver_id'];
    $amount = $params['amount'];
    $notes = $params['notes'];
    $recognized_at = $params['recognized_at'];

	/* Таблица MySQL, в которой хранятся данные */
	$table = "corrections";

	$query = "INSERT INTO $table (mediator_id, trip_id, driver_id, amount, notes, recognized_at) VALUES (";
	$query .="$mediator_id,";
    $query .="'$trip_id',";
    $query .="$driver_id,";
    $query .="$amount,";
    $query .="'$notes',";
    $query .="'$recognized_at')";

	file_put_contents('../logs/corrections.log', date("Y-m-d H:i:s") . ' ' .$query . PHP_EOL , FILE_APPEND);
	// echo $query;
	
	$result = mysql_query($query) or die(mysql_error());
	
?>