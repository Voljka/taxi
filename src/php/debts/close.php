<?
	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

    $driver_id = $params['driver_id'];
    $shift = $params['shift'];
    $amount = $params['amount'];

    $DEBT_FROM_INCOME = 2;
	// Debt
	$query = "INSERT INTO driver_withdrawals ";
	$query .= "(withdrawal_type_id, driver_id, payed_at, amount, is_debt_recalc) ";
	$query .= "VALUES (";

	$query .= "$DEBT_FROM_INCOME, $driver_id, '$shift', $amount, 1 ";

	$query .= ")";

	file_put_contents('debts.log', $query, FILE_APPEND);
	$result = mysql_query($query) or die(mysql_error());

	echo $result;

?>