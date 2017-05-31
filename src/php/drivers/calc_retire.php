<?
	require ('../config/db.config.php');

    // $params = json_decode(file_get_contents('php://input'),true);

    // $driver_id = $params['driver_id'];
    $driver_id = $_GET['driver_id'];

    // $driver_id = 483;
    $FINE_FROM_INCOME = 6;
    $DEBT_FROM_INCOME = 2;
    $FRANCHISE_FROM_INCOME = 1;

    file_put_contents('retire.sql', date("Y-m-d H:i:s") . "\n");

	// Road fines
	$query = "SELECT SUM(fine_amount) charged_fines FROM road_fines";
	$query .= " WHERE driver_id = $driver_id ";
	$query .= " GROUP BY driver_id ";
	
    file_put_contents('retire.sql', $query . "\n", FILE_APPEND);
	$result = mysql_query($query) or die(mysql_error());

	$respond['road_fines_charged'] = 0;
	while ($row = mysql_fetch_assoc($result)) 
	{
		$respond['road_fines_charged'] = $row['charged_fines'];
	};

	// Admin fines
	$query = "SELECT SUM(total_sum) charged_debts FROM admin_fines";
	$query .= " WHERE driver_id = $driver_id ";
	$query .= " GROUP BY driver_id ";
	
    file_put_contents('retire.sql', $query . "\n", FILE_APPEND);
	$result = mysql_query($query) or die(mysql_error());

	$respond['debts_charged'] = 0;
	while ($row = mysql_fetch_assoc($result)) 
	{
		$respond['debts_charged'] = $row['charged_debts'];
	};

	// Franchise payed
	$query = "SELECT SUM(amount) paid_franchise FROM driver_withdrawals ";
	$query .= " WHERE driver_id = $driver_id AND withdrawal_type_id = $FRANCHISE_FROM_INCOME ";
	$query .= " GROUP BY driver_id ";
	
    file_put_contents('retire.sql', $query . "\n", FILE_APPEND);
	$result = mysql_query($query) or die(mysql_error());

	$respond['franchise_paid'] = 0;
	while ($row = mysql_fetch_assoc($result)) 
	{
		$respond['franchise_paid'] = $row['paid_franchise'];
	};

	// Debts payed
	$query = "SELECT SUM(amount) paid_debt FROM driver_withdrawals ";
	$query .= " WHERE driver_id = $driver_id AND withdrawal_type_id = $DEBT_FROM_INCOME ";
	$query .= " GROUP BY driver_id ";
	
    file_put_contents('retire.sql', $query . "\n", FILE_APPEND);
	$result = mysql_query($query) or die(mysql_error());

	$respond['debt_paid'] = 0;
	while ($row = mysql_fetch_assoc($result)) 
	{
		$respond['debt_paid'] = $row['paid_debt'];
	};

	// Fines payed
	$query = "SELECT SUM(amount) paid_fines FROM driver_withdrawals ";
	$query .= " WHERE driver_id = $driver_id AND withdrawal_type_id = $FINE_FROM_INCOME ";
	$query .= " GROUP BY driver_id ";
	
    file_put_contents('retire.sql', $query . "\n", FILE_APPEND);
	$result = mysql_query($query) or die(mysql_error());

	$respond['fine_paid'] = 0;
	while ($row = mysql_fetch_assoc($result)) 
	{
		$respond['fine_paid'] = $row['paid_fines'];
	};

	echo json_encode($respond);
?>