<?
	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

  
    $report_date = $params['reportDate'];
    $curdate = date("Y-m-d");
  
    $query = "INSERT INTO daily_reports (report_date, saved_at) ";
    $query .= " VALUES ('$report_date',";
    $query .= " '$curdate')";
    
  	file_put_contents('close_daily_day.sql', $query);

	$result = mysql_query($query) or die(mysql_error());

	echo json_encode();
?>