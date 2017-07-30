<?
	require ('../config/db.config.php');

  $params = json_decode(file_get_contents('php://input'),true);

  $driver_id = $params['driver_id'];
  $week_id = $params['week_id'];
  $yandex_cash = $params['yandex_cash'];
  $yandex_non_cash = $params['yandex_non_cash'];

	file_put_contents('save_weekly_data.sql', date("Y-m-d H:i:s") . "\n");

  $query = "UPDATE weekly_freelancers SET ";
  $query .= " yandex_cash = $yandex_cash,";
  $query .= " yandex_non_cash = $yandex_non_cash ";
  $query .= " WHERE driver_id = $driver_id AND week_id = $week_id ";
  

	file_put_contents('save_weekly_data.sql', $query . "\n", FILE_APPEND);

	$result = mysql_query($query) or die(mysql_error());

	echo json_encode();
?>