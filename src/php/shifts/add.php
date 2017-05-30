<?

	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

    $auto_id = $params['auto_id'];
    $dispatcher_id = $params['dispatcher_id'];
    if (! $params['finish_time']) {
	    $finish_time = "NULL";
    } else {
	    $finish_time = "'" . $params['finish_time'] . "'";
    }
    $start_time = $params['start_time'];
    $km = $params['km'];
    $driver_id = $params['driver_id'];
    if (! $params['uber_driver_id']) {
    	$uber_driver_id = 'NULL';
    } else {
    	$uber_driver_id = $params['uber_driver_id'];

    }
    if (! $params['yandex_driver_id']) {
    	$yandex_driver_id = 'NULL';
    } else {
    	$yandex_driver_id = $params['yandex_driver_id'];
    	
    }
    $shift_date = $params['shift_date'];
    $prepay = $params['prepay'];

	/* Таблица MySQL, в которой хранятся данные */
	$table = "shifts";

	$query = "INSERT INTO $table (auto_id, dispatcher_id, driver_id, uber_driver_id, yandex_driver_id, start_time, finish_time, km, prepay, shift_date) VALUES (";
    $query .="$auto_id,";
	$query .="$dispatcher_id,";
	$query .="$driver_id,";
	$query .="$uber_driver_id,";
	$query .="$yandex_driver_id,";
	$query .="'$start_time',";
	$query .="$finish_time,";
    $query .="$km,";
    $query .="$prepay,";
    $query .="'$shift_date')";

	file_put_contents('../logs/shifts.log', date("Y-m-d H:i:s") . ' ' .$query . PHP_EOL , FILE_APPEND);
	// echo $query;
	
	$result = mysql_query($query) or die(mysql_error());
	
?>