<?
	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

    $id = $params['id'];
    $auto_id = $params['auto_id'];
    $dispatcher_id = $params['dispatcher_id'];
    $driver_id = $params['driver_id'];
    $shift_date = $params['shift_date'];

	/* Таблица MySQL, в которой хранятся данные */
	$table = "shifts";

	$query = "UPDATE $table SET shift_date='$shift_date', auto_id = $auto_id, driver_id=$driver_id, dispatcher_id=$dispatcher_id ";
	$query .= " WHERE id=$id ";

	// echo $query;
    file_put_contents('../logs/shifts.log', date("Y-m-d H:i:s") . ' ' . $query . PHP_EOL , FILE_APPEND);

	$result = mysql_query($query) or die(mysql_error());
    echo $result;

?>