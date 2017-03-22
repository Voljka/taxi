<?

	require ('../config/db.config.php');

    $active = $_POST['active'];
    $work_type_id = $_POST['work_type_id'];
    $firtsname = $_POST['firstname'];
    $patronymic = $_POST['patronymic'];
    $surname = $_POST['surname'];
    $phone = $_POST['phone'];
    $phone2 = $_POST['phone2'];
    $email = $_POST['email'];
    $card_number = $_POST['card_number'];
    $beneficiar = $_POST['beneficiar'];
    $bank_id = $_POST['bank_id'];
    $notes = $_POST['notes'];
    $rent = $_POST['rent'];
    $registration_date = $_POST['registration_date'];

	//$curdate = date("Y-m-d");

	/* Таблица MySQL, в которой хранятся данные */
	$table = "drivers";

	$query = "INSERT INTO $table (active, work_type_id, firstname, patronymic, surname, phone, phone2, email, card_number, beneficiar, bank_id, notes, rent, registration_date) VALUES (";
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
    $query .="'registration_date')";

	file_put_contents('../logs/drivers.log', date("Y-m-d H:i:s") . ' ' .$query . PHP_EOL , FILE_APPEND);
	// echo $query;
	
	// $result = mysql_query($query) or die(mysql_error());
	
?>