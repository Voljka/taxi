<?
	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

    $id = $params['id'];
    $auto_id = $params['auto_id'];
    $fined_at = $params['fined_at'];
    $fine_place = $params['fine_place'];
    $fixation_type = $params['fixation_type'];
    $driver_id = $params['driver_id'];
    $notes = $params['notes'];
    $fine_amount = $params['fine_amount'];
    $fine_number = $params['fine_number'];
    $inputed_at = $params['inputed_at'];

	/* Таблица MySQL, в которой хранятся данные */
	$table = "road_fines";

	$query = "UPDATE $table SET fined_at='$fined_at', auto_id=$auto_id, notes = '$notes', fine_amount = $fine_amount, fine_number = $fine_number, inputed_at = '$inputed_at', fixation_type = $fixation_type, $fine_place = '$fine_place', driver_id = $driver_id ";
	$query .= " WHERE id=$id ";

	// echo $query;
    file_put_contents('../logs/road_fines.log', date("Y-m-d H:i:s") . ' ' . $query . PHP_EOL , FILE_APPEND);

	$result = mysql_query($query) or die(mysql_error());
    echo $result;

?>