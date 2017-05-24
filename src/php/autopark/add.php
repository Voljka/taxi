<?

	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

    $model = $params['model'];
    $state_number = $params['state_number'];
    $is_rented = $params['is_rented'];
    $color = $params['color'];
    $sts = $params['sts'];
    $license = $params['license'];
    $year_created = $params['year_created'];

	/* Таблица MySQL, в которой хранятся данные */
	$table = "own_auto_park";

	$query = "INSERT INTO $table (model, state_number, color, sts, license, year_created, is_rented) VALUES (";
	$query .="'$model',";
    $query .="'$state_number',";
    $query .="'$color',";
    $query .="'$sts',";
    $query .="'$license',";
    $query .="$year_created,";
    $query .="$is_rented)";

	file_put_contents('../logs/autopark.log', date("Y-m-d H:i:s") . ' ' .$query . PHP_EOL , FILE_APPEND);
	// echo $query;
	
	$result = mysql_query($query) or die(mysql_error());
	
?>