<?
	require ('../config/db.config.php');

	$table = "banks";

	$query = "SELECT * FROM $table";
	$query .= " ORDER BY name ";
	
	$result = mysql_query($query) or die(mysql_error());

	$respond = array();
	while ($row = mysql_fetch_assoc($result)) 
	{
		$respond[] = $row;
	};

	echo json_encode($respond);
?>