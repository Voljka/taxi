<?

	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

    $name = $params['name'];

	/* Таблица MySQL, в которой хранятся данные */
	$table = "dispatchers";

	$query = "INSERT INTO $table (name) VALUES (";
    $query .="'$name')";

	file_put_contents('../logs/dispatchers.log', date("Y-m-d H:i:s") . ' ' .$query . PHP_EOL , FILE_APPEND);
	// echo $query;
	
	$result = mysql_query($query) or die(mysql_error());
	
?>