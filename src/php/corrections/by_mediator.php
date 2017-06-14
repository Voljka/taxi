<?
	require ('../config/db.config.php');

  $mediator_id = $_GET['mediator_id'];

	$table = "corrections";

	$curdate = date("Y-m-d");

	$query = "SELECT $table.*, drivers.surname,  drivers.firstname, drivers.patronymic FROM $table ";
	$query .= " LEFT JOIN drivers ON $table.driver_id = drivers.id ";
	$query .= " WHERE mediator_id = $mediator_id ";
	$query .= " ORDER BY $table.recognized_at desc ";

	file_put_contents('corrections_by_mediator.sql', $query);
	
	$result = mysql_query($query) or die(mysql_error());

	$respond = array();
	while ($row = mysql_fetch_assoc($result)) 
	{
		$respond[] = $row;
	};

	echo json_encode($respond);
?>
