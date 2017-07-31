<?
	require ('../config/db.config.php');

  $params = json_decode(file_get_contents('php://input'),true);

  $id = $params['id'];

	/* Таблица MySQL, в которой хранятся данные */
	$table = "autoparks_payouts";

	$query = "DELETE FROM $table ";
	$query .= " WHERE id=$id ";

	// echo $query;
    file_put_contents('../logs/autoparks_payouts.log', date("Y-m-d H:i:s") . ' ' . $query . PHP_EOL , FILE_APPEND);

	$result = mysql_query($query) or die(mysql_error());
    echo $result;

?>