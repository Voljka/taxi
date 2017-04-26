<?

	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

    $auto_id = $params['auto_id'];
    $dispatcher_id = $params['dispatcher_id'];
    $driver_id = $params['driver_id'];
    $shift_date = $params['shift_date'];

	/* Таблица MySQL, в которой хранятся данные */
	$table = "shifts";

	$query = "INSERT INTO $table (auto_id, dispatcher_id, driver_id, shift_date) VALUES (";
    $query .="$auto_id,";
	$query .="$dispatcher_id,";
	$query .="$driver_id,";
    $query .="'$shift_date')";

	file_put_contents('../logs/shifts.log', date("Y-m-d H:i:s") . ' ' .$query . PHP_EOL , FILE_APPEND);
	// echo $query;
	
	$result = mysql_query($query) or die(mysql_error());
	
?>