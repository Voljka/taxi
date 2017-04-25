<?

	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

    $fined_at = $params['fined_at'];
    $driver_id = $params['driver_id'];
    $notes = $params['notes'];
    $rule_type_id = $params['rule_type_id'];
    $total_sum = $params['total_sum'];
    $iteration_sum = $params['iteration_sum'];
    $min_daily_wage = $params['min_daily_wage'];
    $min_weekly_wage = $params['min_weekly_wage'];
    $is_total_income_as_fine = $params['is_total_income_as_fine'];

    $min_daily_wage = $min_daily_wage ? $min_daily_wage : "NULL";
    $min_weekly_wage = $min_weekly_wage ? $min_weekly_wage : "NULL";


	/* Таблица MySQL, в которой хранятся данные */
	$table = "admin_fines";

    $query = "SELECT id FROM admin_fines ";
    $query .= " WHERE driver_id = $driver_id AND fined_at = '$fined_at' ";

    $result = mysql_query($query) or die(mysql_error());

    if (mysql_num_rows($result) > 0) {
        $query = "UPDATE $table SET  ";
        $query .="fined_at = '$fined_at',";
        $query .="driver_id = $driver_id,";
        $query .="notes = '$notes',";
        $query .="rule_type_id = $rule_type_id,";
        $query .="total_sum = $total_sum,";
        $query .="iteration_sum = $iteration_sum,";
        $query .="min_daily_wage = $min_daily_wage,";
        $query .="min_weekly_wage = $min_weekly_wage,";
        $query .="is_total_income_as_fine = $is_total_income_as_fine ";
        $query .= " WHERE driver_id = $driver_id AND fined_at = '$fined_at' ";
        
        file_put_contents('../logs/debts.log', date("Y-m-d H:i:s") . ' ' .$query . PHP_EOL , FILE_APPEND);

        $result = mysql_query($query) or die(mysql_error());

    } else {
        $query = "INSERT INTO $table (fined_at, driver_id, notes, rule_type_id, total_sum, iteration_sum, min_daily_wage, min_weekly_wage, is_total_income_as_fine ) VALUES (";
        $query .="'$fined_at',";
        $query .="$driver_id,";
        $query .="'$notes',";
        $query .="$rule_type_id,";
        $query .="$total_sum,";
        $query .="$iteration_sum,";
        $query .="$min_daily_wage,";
        $query .="$min_weekly_wage,";
        $query .="$is_total_income_as_fine)";
        
        file_put_contents('../logs/debts.log', date("Y-m-d H:i:s") . ' ' .$query . PHP_EOL , FILE_APPEND);

        $result = mysql_query($query) or die(mysql_error());
    }
	
?>