<?
	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

    $shift_date = $_GET['shift_date'];
    $driver_id = $_GET['driver_id'];

	$table = "shifts";

	$query = "SELECT $table.* FROM $table ";

	$query .= " LEFT JOIN drivers ON drivers.id = shifts.driver_id ";

	$query .= " WHERE shift_date < '$shift_date' AND driver_id = $driver_id ";
	$query .= " ORDER BY shift_date ";

	file_put_contents('last_shift.sql', date("Y-m-d") . "\n" . $query);
	
	$result = mysql_query($query) or die(mysql_error());

	$respond = array();

	while ($row = mysql_fetch_assoc($result)) 
	{
		$respond[] = $row;
		break;
	};

	echo json_encode($respond);
?>