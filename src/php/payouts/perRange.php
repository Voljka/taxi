<?
	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

    // $driver_id = $params['driver_id'];
    // $start = $params['start'];
    // $end = $params['end'];

    $driver_id = $_GET['driver_id'];
    $start = $_GET['start'];
    $end = $_GET['end'];

    if (! $end) {
    	$end = $start;
    }

	$table = "payouts";

	$query = "SELECT id, report_date, payed_at, is_daily, amount, driver_id FROM $table ";

	$query .= " WHERE driver_id = $driver_id AND report_date >= '$start' AND report_date <= '$end' ";
	$query .= " ORDER BY $table.payed_at ";

	file_put_contents('payouts_daily.sql', $query);
	
	$result = mysql_query($query) or die(mysql_error());

	$respond = array();
	while ($row = mysql_fetch_assoc($result)) 
	{
		$respond[] = $row;
	};

	echo json_encode($respond);
?>