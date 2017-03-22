<?

	require ('../config/db.config.php');

    $name = $_POST['name'];
    $daily = $_POST['daily'];

	/* Таблица MySQL, в которой хранятся данные */
	$table = "work_types";

	$query = "INSERT INTO $table (name, is_daily) VALUES (";
    $query .="'$name',";
    $query .="$daily)";

	file_put_contents('../logs/groups.log', date("Y-m-d H:i:s") . ' ' .$query . PHP_EOL , FILE_APPEND);
	// echo $query;
	
	// $result = mysql_query($query) or die(mysql_error());
	
?>