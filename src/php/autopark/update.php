<?
	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

    $id = $params['id'];
    $model = $params['model'];
    $state_number = $params['state_number'];
    $is_rented = $params['is_rented'];

	/* Таблица MySQL, в которой хранятся данные */
	$table = "own_auto_park";

	$query = "UPDATE $table SET model='$model', state_number = '$state_number', is_rented = $is_rented ";
	$query .= " WHERE id=$id ";

	// echo $query;
    file_put_contents('../logs/autopark.log', date("Y-m-d H:i:s") . ' ' . $query . PHP_EOL , FILE_APPEND);

	$result = mysql_query($query) or die(mysql_error());
    echo $result;

?>