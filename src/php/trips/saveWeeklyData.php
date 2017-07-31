<?
	require ('../config/db.config.php');

  $params = json_decode(file_get_contents('php://input'),true);

  $data = $params['data'];

  foreach ($data as $el) {

    $driver_id = $el['driver_id'];
    $week_id = $el['week_id'];
    $asks = $el['asks'];
    $debt = $el['debt'];

    $query = "SELECT * FROM weekly_freelancers ";
    $query .= "   WHERE driver_id = $driver_id AND week_id = $week_id ";

    file_put_contents('save_weekly_data.sql', $query . "\n", FILE_APPEND);

    $result = mysql_query($query) or die(mysql_error());

    if (mysql_num_rows($result) > 0) {
      $query = "UPDATE weekly_freelancers SET ";
      $query .= " asks = $asks, debt = $debt ";
      
      $query .= " WHERE driver_id = $driver_id AND week_id = $week_id ";
  
      file_put_contents('save_weekly_data.sql', $query . "\n", FILE_APPEND);
      $result = mysql_query($query) or die(mysql_error());
    } else {
      $query = "INSERT INTO weekly_freelancers (week_id, driver_id, uber_bonus, asks, debt) VALUES ";
      $query .= " ($week_id, $driver_id, 0, $asks, $debt) ";
      
      file_put_contents('save_weekly_data.sql', $query . "\n", FILE_APPEND);
      $result = mysql_query($query) or die(mysql_error());
    }
  }

  // $driver_id = $params['driver_id'];
  // $week_id = $params['week_id'];
  // $asks = $params['asks'];
  // $debt = $params['debt'];

	// file_put_contents('save_weekly_data.sql', date("Y-m-d H:i:s") . "\n");

 //  $query = "UPDATE weekly_freelancers SET ";
 //  $query .= " asks = $asks, debt = $debt ";
  
 //  $query .= " WHERE driver_id = $driver_id AND week_id = $week_id ";
  

	// file_put_contents('save_weekly_data.sql', $query . "\n", FILE_APPEND);

	// $result = mysql_query($query) or die(mysql_error());

	echo json_encode();
?>