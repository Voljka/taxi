<?

	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

    $active = $params['active'];
    $work_type_id = $params['work_type_id'];
    $firstname = $params['firstname'];
    $patronymic = $params['patronymic'];
    $surname = $params['surname'];
    $bank_rate = $params['bank_rate'];
    $bank_interest = $params['bank_fixed'];
    $phone = $params['phone'];
    $phone2 = $params['phone2'];
    $email = $params['email'];
    $card_number = $params['card_number'];
    if ($params['rule_default_id'] == 1){
        $rule_default_id = "NULL";
    } else {
        $rule_default_id = $params['rule_default_id'];
    }
    
    $beneficiar = $params['beneficiar'];
    $bank_id = $params['bank_id'];
    $notes = $params['notes'];
    $rent = $params['rent'];
    $registration_date = $params['registration_date'] == '' ? 'NULL' : "'". $params['registration_date'] . "'";

	/* Таблица MySQL, в которой хранятся данные */
	$table = "drivers";

	$query = "INSERT INTO $table (active, work_type_id, firstname, patronymic, surname, phone, phone2, email, card_number, beneficiar, bank_id, notes, rent, bank_rate, bank_interest, rule_default_id, registration_date) VALUES (";
    $query .="$active,";
    $query .="$work_type_id,";
    $query .="'$firstname',";
    $query .="'$patronymic',";
    $query .="'$surname',";
    $query .="$phone,";
    $query .="$phone2,";
	$query .="'$email',";
	$query .="card_number,";
	$query .="'beneficiar',";
	$query .="bank_id,";
	$query .="'notes',";
    $query .="$rent,";
    $query .="$bank_rate,";
    $query .="$bank_interest,";
    $query .="$rule_default_id,";
    $query .="registration_date)";

	file_put_contents('../logs/drivers.log', date("Y-m-d H:i:s") . ' ' .$query . PHP_EOL , FILE_APPEND);
	// echo $query;
	
	$result = mysql_query($query) or die(mysql_error());
	
?>