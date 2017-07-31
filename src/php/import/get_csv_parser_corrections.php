<?php
header('Content-Type: text/html; charset=utf-8');

require ('../config/db.config.php');

function translate($str) {
	return iconv ('windows-1251', 'utf-8', $str);
}

function correct_number($num) {
	return intval( str_replace(',', '.', strval($num)) );
}

function transform_date($str){
	 $res = substr($str, 6,4) . '-';
	 $res .= substr($str, 3,2) . '-';
	 $res .= substr($str, 0,2) . ' ';
	 
	 if (substr($str, 12,1) == ":") {
		  $res .= '0' . substr($str, 11,7);
	 } else {
		  $res .= substr($str, 11,8);
	 }
	 $res .= ':00';

	 return $res;
}

function is_correction_presents($trip, $driver){
	global $cor_trip_ids, $cor_trip_driver_ids;

	$result = false;
	for ($i=0; $i < count($cor_trip_ids); $i++) { 
		if ($cor_trip_ids[$i] == $trip && $cor_trip_driver_ids[$i] == $driver) {
			$result = true;
			break;
		}
	}

	return $result;
}

file_put_contents('gett_import_corrections.sql', date("Y-m-d H:i:s") . "\n");

$GET = 2;
$row = 1;

$file = $_FILES['file']['tmp_name'];
// $file = "gett_corrections.csv";

if (($handle = fopen($file, "r")) !== FALSE) {

	 // make array of the drivers
	 $query_drivers = "SELECT id, CONCAT(TRIM(surname),' ',TRIM(firstname),' ',TRIM(patronymic)) fullname, work_type_id FROM drivers";

	 $query_result = mysql_query($query_drivers) or die(mysql_error());        

	 while ($row1 = mysql_fetch_assoc($query_result)) 
	 {
		  $ids[] = $row1['id'];
		  $names[] = $row1['fullname'];
		  $group_ids[] = $row1['work_type_id'];
	 };

	 // make array of the presented Gett trips
	 $query_get_trips = "SELECT mediator_trip_id, driver_id FROM trips WHERE mediator_id = $GET";

	 $query_result = mysql_query($query_get_trips) or die(mysql_error());        

	 while ($row2 = mysql_fetch_assoc($query_result)) 
	 {
		  $trip_ids[] = $row2['mediator_trip_id'];
		  $trip_driver_ids[] = $row2['driver_id'];
	 };
	 
	 // make array of the presented Gett corrections 
	 $query_get_cors = "SELECT trip_id, driver_id FROM corrections WHERE mediator_id = $GET";

	 $query_result = mysql_query($query_get_cors) or die(mysql_error());        

	 while ($row2 = mysql_fetch_assoc($query_result)) 
	 {
		  $cor_trip_ids[] = $row2['trip_id'];
		  $cor_trip_driver_ids[] = $row2['driver_id'];
	 };

	 $insert_corrections_query = "INSERT INTO corrections (mediator_id, trip_id,  driver_id, amount, notes, recognized_at) VALUES ";

	 $bad_contacts = Array();
	 $bad_contact_list = '';
	 $bad_contats_count = 0;
	 $successfull_loads_count = 0;

	 $already_exists_trips_count = 0;
	 $query = '';

	 while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
		file_put_contents('gett_import_corrections.sql', $data[4] . "\n", FILE_APPEND);

		if ($row > 1) {

			if ($data[0] == "") {

			} else {
				$cur_order = $data[2];

				$trip_index = array_search($cur_order, $trip_ids);

				// If trip present in DB
				if ($trip_index > -1){
					$correction_driver_id = $trip_driver_ids[$trip_index];

					// if correction already saved
					if (is_correction_presents($cur_order, $correction_driver_id)){
						$already_exists_trips_count++;
					// If correction is new one
					} else {
						$successfull_loads_count++;

						$query .= "($GET, '$cur_order', $correction_driver_id, ". $data[4] . ", '" . translate($data[3]). "', '" .date("Y-m-d"). "'),";
					}
				// If trip absent in DB
				} else {
				  $bad_contacts_count++;

				  if ( array_search($cur_order, $bad_contacts) > -1 ) {
				  } else {
						$bad_contacts[count($bad_contacts)] = $cur_order;
						$bad_contact_list .= $cur_order . '<br>';
				  }
				}
			}
		}

		$row++;

	 }

	if (strlen($query) > 0) {
		$query = substr($query, 0, strlen($query) - 1 );
		// echo $insert_query . $query . PHP_EOL;

		file_put_contents('gett_import_corrections.sql', $insert_corrections_query . $query . "\n", FILE_APPEND);
		$result = mysql_query($insert_corrections_query . $query) or die(mysql_error());
	}

	 echo '<br>Нераспозанные номера заказов : <br>';
	 echo $bad_contact_list . '<br>';
	 echo '<br> Успешно загружено записей: '. $successfull_loads_count;
	 echo '<br> Пропущено поездок с ошибочными заказами : '. $bad_contacts_count;
	 echo '<br> Пропущено существующих корректировок: '. $already_exists_trips_count;    

	 file_put_contents('gett_import_corrections.sql', "\nНераспозанные номера заказов: \n", FILE_APPEND);
	 file_put_contents('gett_import_corrections.sql', $bad_contact_list . "\n", FILE_APPEND);

	 file_put_contents('gett_import_corrections.sql', '<br> Загружено : '. $successfull_loads_count . "\n", FILE_APPEND);
	 file_put_contents('gett_import_corrections.sql', '<br> Пропущено ошибочных : '. $bad_contacts_count . "\n", FILE_APPEND);
	 file_put_contents('gett_import_corrections.sql', '<br> Пропущено существующих: '. $already_exists_trips_count . "\n", FILE_APPEND);
	 
	 fclose($handle);
} else {
	 file_put_contents('gett_import_corrections.sql', 'no file handle!', FILE_APPEND);
}
?>