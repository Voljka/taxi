<?
	// require ($_SERVER['DOCUMENT_ROOT'].'/config/db.config.php');
	require ('../config/db.config.php');

    $id = $_POST['id'];
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

	/* Таблица MySQL, в которой хранятся данные */
	$table = "drivers";

	$query = "UPDATE $table SET active=$active, firstname='$firstname', patronymic='$patronymic', surname='$surname', phone=$phone, phone2=$phone2, email='$email', card_number=$card_number, beneficiar='$beneficiar', bank_id=$bank_id, notes='$notes', rent=$rent, registration_date='$registration_date' ";
	$query .= " WHERE id=$id ";

	// echo $query;
    file_put_contents('../logs/drivers.log', date("Y-m-d H:i:s") . ' ' . $query . PHP_EOL , FILE_APPEND);

	// $result = mysql_query($query) or die(mysql_error());

?>