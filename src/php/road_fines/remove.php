<?
	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

    $id = $params['id'];

	/* Таблица MySQL, в которой хранятся данные */
	$table = "road_fines";

	$query = "DELETE FROM $table ";
	$query .= " WHERE id=$id ";

	// echo $query;
    file_put_contents('../logs/road_fines.log', date("Y-m-d H:i:s") . ' ' . $query . PHP_EOL , FILE_APPEND);

	$result = mysql_query($query) or die(mysql_error());
    echo $result;

?>