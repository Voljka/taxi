<?
	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

    $id = $params['id'];
    $active = $params['active'];
    $work_type_id = $params['work_type_id'];
    $firstname = $params['firstname'];
    $patronymic = $params['patronymic'];
    $surname = $params['surname'];
    $phone = $params['phone'];
    $phone2 = $params['phone2'];
    $bank_rate = $params['bank_rate'];
    $bank_interest = $params['bank_fixed'];
    $email = $params['email'];
    if ($params['rule_default_id'] == 1){
        $rule_default_id = "NULL";
    } else {
        $rule_default_id = $params['rule_default_id'];
    }
    $card_number = $params['card_number'];
    $beneficiar = $params['beneficiar'];
    $bank_id = $params['bank_id'];
    $notes = $params['notes'];
    $rent = $params['rent'];
    $registration_date = $params['registration_date'] == '' ? 'NULL' : "'". $params['registration_date'] . "'";


	/* Таблица MySQL, в которой хранятся данные */
	$table = "drivers";

	$query = "UPDATE $table SET active=$active, firstname='$firstname', patronymic='$patronymic', surname='$surname', phone=$phone, phone2=$phone2, email='$email', card_number=$card_number, beneficiar='$beneficiar', bank_id=$bank_id, notes='$notes', rent=$rent, work_type_id=$work_type_id, registration_date=$registration_date, rule_default_id = $rule_default_id, bank_rate=$bank_rate, bank_interest = $bank_interest ";
	$query .= " WHERE id=$id ";

	// echo $query;
    file_put_contents('../logs/drivers.log', date("Y-m-d H:i:s") . ' ' . $query . PHP_EOL , FILE_APPEND);

	$result = mysql_query($query) or die(mysql_error());
    echo $result;

?>