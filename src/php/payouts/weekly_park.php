<?
	require ('../config/db.config.php');

  $params = json_decode(file_get_contents('php://input'),true);

  // $driver_id = $params['driver_id'];
  // $start = $params['start'];
  // $end = $params['end'];

  $park_id = $_GET['group_id'];
  $week_id = $_GET['week_id'];

	$table = "autoparks_payouts";

	$query = "SELECT * FROM $table WHERE week_id=$week_id AND park_id=$park_id ";

	$query .= " ORDER BY $table.paid_at ";

	file_put_contents('weekly_park_payouts_list.sql', $query);
	
	$result = mysql_query($query) or die(mysql_error());

	$respond = array();
	while ($row = mysql_fetch_assoc($result)) 
	{
		$respond[] = $row;
	};

	echo json_encode($respond);
?>