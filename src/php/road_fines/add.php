<?

	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

    $auto_id = $params['auto_id'];
    $fined_at = $params['fined_at'];
    $notes = $params['notes'];
    $fine_amount = $params['fine_amount'];
    $fine_number = $params['fine_number'];
    $fine_place = $params['fine_place'];
    $fixation_type = $params['fixation_type'];
    $driver_id = $params['driver_id'];
    $inputed_at = $params['inputed_at'];

	/* Таблица MySQL, в которой хранятся данные */
	$table = "road_fines";

	$query = "INSERT INTO $table (auto_id, fined_at, notes, fine_amount, fine_number, fixation_type, fine_place, driver_id, inputed_at) VALUES (";
	$query .="$auto_id,";
	$query .="'$fined_at',";
	$query .="'$notes',";
	$query .="$fine_amount,";
	$query .="$fine_number,";
	$query .="$fixation_type,";
	$query .="'$fine_place',";
	$query .="$driver_id,";
	$query .="'$inputed_at')";

	file_put_contents('../logs/road_fines.log', date("Y-m-d H:i:s") . ' ' .$query . PHP_EOL , FILE_APPEND);
	// echo $query;
	
	$result = mysql_query($query) or die(mysql_error());
	
?>