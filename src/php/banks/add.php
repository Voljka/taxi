<?

	require ('../config/db.config.php');

    $name = $_POST['name'];

	/* Таблица MySQL, в которой хранятся данные */
	$table = "banks";

	$query = "INSERT INTO $table (name) VALUES (";
    $query .="'name')";

	file_put_contents('../logs/banks.log', date("Y-m-d H:i:s") . ' ' .$query . PHP_EOL , FILE_APPEND);
	// echo $query;
	
	// $result = mysql_query($query) or die(mysql_error());
	
?>