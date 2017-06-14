<?
	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

    $id = $params['id'];
    $mediator_id = $params['mediator_id'];
    $trip_id = $params['trip_id'];
    $driver_id = $params['driver_id'];
    $amount = $params['amount'];
    $notes = $params['notes'];
    $recognized_at = $params['recognized_at'];

	/* Таблица MySQL, в которой хранятся данные */
	$table = "corrections";

	$query = "UPDATE $table SET mediator_id=$mediator_id, trip_id = '$trip_id', driver_id = $driver_id, amount = $amount, notes = '$notes', recognized_at = '$recognized_at' ";
	$query .= " WHERE id=$id ";

	// echo $query;
    file_put_contents('../logs/corrections.log', date("Y-m-d H:i:s") . ' ' . $query . PHP_EOL , FILE_APPEND);

	$result = mysql_query($query) or die(mysql_error());
    echo $result;

?>