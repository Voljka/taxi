<?
	// require ($_SERVER['DOCUMENT_ROOT'].'/config/db.config.php');
	require ('../config/db.config.php');

    $id = $_POST['id'];
    $name = $_POST['name'];
    $daily = $_POST['daily'];

	/* Таблица MySQL, в которой хранятся данные */
	$table = "work_types";

	$query = "UPDATE $table SET name='$name', is_daily=$daily ";
	$query .= " WHERE id=$id ";

	// echo $query;
    file_put_contents('../logs/groups.log', date("Y-m-d H:i:s") . ' ' . $query . PHP_EOL , FILE_APPEND);

	// $result = mysql_query($query) or die(mysql_error());

?>