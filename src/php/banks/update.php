<?
	// require ($_SERVER['DOCUMENT_ROOT'].'/config/db.config.php');
	require ('../config/db.config.php');

    $id = $_POST['id'];
    $name = $_POST['name'];

	/* Таблица MySQL, в которой хранятся данные */
	$table = "banks";

	$query = "UPDATE $table SET name='$name'";
	$query .= " WHERE id=$id ";

	// echo $query;
    file_put_contents('../logs/banks.log', date("Y-m-d H:i:s") . ' ' . $query . PHP_EOL , FILE_APPEND);

	// $result = mysql_query($query) or die(mysql_error());

?>