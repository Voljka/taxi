<?
	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

    $id = $params['id'];
    $auto_id = $params['auto_id'];
    $dispatcher_id = $params['dispatcher_id'];
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

    if (! $params['finish_time']) {
        $finish_time = "NULL";
    } else {
        $finish_time = "'" . $params['finish_time'] . "'";
    }
    $start_time = $params['start_time'];
    $km = $params['km'];
    $shift_date = $params['shift_date'];
    $prepay = $params['prepay'];

	/* Таблица MySQL, в которой хранятся данные */
	$table = "shifts";

	$query = "UPDATE $table SET shift_date='$shift_date', auto_id = $auto_id, driver_id=$driver_id, uber_driver_id=$uber_driver_id, yandex_driver_id=$yandex_driver_id, dispatcher_id=$dispatcher_id, km=$km, start_time='$start_time', finish_time=$finish_time, prepay = $prepay ";
	$query .= " WHERE id=$id ";

	// echo $query;
    file_put_contents('../logs/shifts.log', date("Y-m-d H:i:s") . ' ' . $query . PHP_EOL , FILE_APPEND);

	$result = mysql_query($query) or die(mysql_error());
    echo $result;

?>