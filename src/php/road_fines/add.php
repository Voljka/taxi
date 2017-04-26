<?

	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

    $auto_id = $params['auto_id'];
    $fined_at = $params['fined_at'];
    $notes = $params['notes'];
    $fine_amount = $params['fine_amount'];
    $fine_number = $params['fine_number'];
    $inputed_at = $params['inputed_at'];

	/* Таблица MySQL, в которой хранятся данные */
	$table = "road_fines";

	$query = "INSERT INTO $table (auto_id, fined_at, notes, fine_amount, fine_number, inputed_at) VALUES (";
	$query .="$auto_id,";
	$query .="'$fined_at',";
	$query .="'$notes',";
	$query .="$fine_amount,";
	$query .="$fine_number,";
	$query .="'$inputed_at')";

	file_put_contents('../logs/road_fines.log', date("Y-m-d H:i:s") . ' ' .$query . PHP_EOL , FILE_APPEND);
	// echo $query;
	
	$result = mysql_query($query) or die(mysql_error());
	
?>