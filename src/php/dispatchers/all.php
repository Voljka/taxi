<?
	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

	$table = "dispatchers";

	$query = "SELECT * FROM $table ";

	$query .= " ORDER BY name ";

	file_put_contents('all_dispatchers.sql', $query);
	
	$result = mysql_query($query) or die(mysql_error());

	$respond = array();
	while ($row = mysql_fetch_assoc($result)) 
	{
		$respond[] = $row;
	};

	echo json_encode($respond);
?>
