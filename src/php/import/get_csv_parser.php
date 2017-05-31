<?php
header('Content-Type: text/html; charset=utf-8');

require ('../config/db.config.php');

function translate($str) {
	return iconv ('windows-1251', 'utf-8', $str);
}

function correct_number($num) {
	return intval( str_replace(',', '.', strval($num)) );
}

function is_shift_present($datetime, $driver_id){
	$res = false;
	global $shifts;

	file_put_contents('shift_recognising.sql', 'Looking: ' . $datetime . " " . "$driver_id" . "\n", FILE_APPEND);


	for ($i=0; $i < count($shifts); $i++) { 
		file_put_contents('shift_recognising.sql', 'Iteration: '.  $shifts[$i]['driver_id'] . " " . $shifts[$i]['start_time'] . " " . $shifts[$i]['finish_time'], FILE_APPEND);

		if ($shifts[$i]['driver_id'] == $driver_id && $datetime >= $shifts[$i]['start_time'] && (($datetime <= $shifts[$i]['finish_time']) || ! ($shifts[$i]['finish_time']) )) {
			$res = true;
			file_put_contents('shift_recognising.sql', 'Found! ' . "\n", FILE_APPEND);

			break;
		} else {
			file_put_contents('shift_recognising.sql', 'NOT Found! ' . "\n", FILE_APPEND);
		}

	}

	return $res;
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

file_put_contents('gett_import.sql', date("Y-m-d") . "\n");
file_put_contents('gett_completeness.sql', date("Y-m-d") . "\n");

$GET = 2;
$row = 1;
$TRIP_CASH = 1;
$TRIP_CARD = 3;

$OUR = array();
$OUR[] = 2;
$OUR[] = 3;
$OUR[] = 4;

$file = $_FILES['file'];

if (($handle = fopen($file['tmp_name'], "r")) !== FALSE) {

	 $query_drivers = "SELECT id, CONCAT(TRIM(surname),' ',TRIM(firstname),' ',TRIM(patronymic)) fullname, work_type_id FROM drivers";

	 $query_result = mysql_query($query_drivers) or die(mysql_error());        

	 while ($row1 = mysql_fetch_assoc($query_result)) 
	 {
		  $ids[] = $row1['id'];
		  $names[] = $row1['fullname'];
		  $group_ids[] = $row1['work_type_id'];
	 };

	 $query_get_trips = "SELECT mediator_trip_id FROM trips WHERE mediator_id = $GET";

	 $query_result = mysql_query($query_get_trips) or die(mysql_error());        

	 while ($row2 = mysql_fetch_assoc($query_result)) 
	 {
		  $trip_ids[] = $row2['mediator_trip_id'];
	 };

	 $query_shifts = "SELECT * FROM shifts ";
	 $query_shifts .= " ORDER BY shift_date DESC ";

	 $query_result = mysql_query($query_shifts) or die(mysql_error());        

	 $shifts = array();
	 while ($row3 = mysql_fetch_assoc($query_result)) 
	 {
		  $shifts[] = $row3;
	 };

	 $insert_query = "INSERT INTO trips (mediator_id, date_time, mediator_trip_id, payment_type_id, fare, boost_non_commissionable, cash, comission, notes, driver_fullname, driver_phone, driver_id, result) VALUES ";

	 $insert_corrections_query = "INSERT INTO corrections (mediator_id, trip_id,  driver_id, amount, notes, recognized_at) VALUES ";

	 $bad_contacts = Array();
	 $bad_contact_list = '';
	 $bad_contats_count = 0;
	 $successfull_loads_count = 0;

	 $unshifted_trips_list = '';
	 $unshifted_trips_count = 0;
	 $cancelled_trips = 0;

	 $already_exists_trips_count = 0;
	 $query = '';
	 file_put_contents('shift_recognising.sql', date("Y-m-d H;i;s"). "\n");

	 while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
		file_put_contents('gett_import.sql', $data[4] . "\n", FILE_APPEND);

		if ($row > 4) {
			if ($row == 5) {
				$first_date = $data[4];
			}

			if ($data[0] == "") {

			} else{
	
				 if ($data[12] == "Отмененная/Credit_card") {
					  $cancelled_trips++;
				 } else {
					  if (mb_detect_encoding($data[0]) != "UTF-8") {
						$extend_block = translate($data[0]);
					  } else {
						$extend_block = $data[0];
					  }

					  if ($extend_block == "Тип оплаты") {
						break;
					  } else {

							$last_date = $data[4];
							// check for a trip presence in db
							if ( array_search($data[2], $trip_ids) > -1 ) {
								 $already_exists_trips_count++;
							} else {

								 if (mb_detect_encoding($data[1]) != "UTF-8") {
									  $contact = translate($data[1]);
								 } else {
									  $contact = $data[1];
								 }

								 $driver_index = array_search($contact, $names);
								 if (! $driver_index) {

									  $bad_contacts_count++;

									  if ( array_search($contact, $bad_contacts) > -1 ) {
									  } else {
											$bad_contacts[count($bad_contacts)] = $contact;
											$bad_contact_list .= $contact . '<br>';
									  }

								 } else {
							 		// driver recognised and shifted
									// file_put_contents('shift_recognising.sql', $driver_index. " ". $group_ids[$driver_index] . " " . $names[$driver_index] . " " . (in_array($group_ids[$driver_index], $OUR) ? 'Our' : 'Freelancer') . " " . (is_shift_present($data[4], $ids[$driver_index]) ? "In" : "Out")  . "\n", FILE_APPEND);

								 	if (! (in_array($group_ids[$driver_index], $OUR)) || in_array($group_ids[$driver_index], $OUR) && is_shift_present($data[4], $ids[$driver_index]) ){

										  $successfull_loads_count++;

										  $query .= "(". $GET . ',';
										  $query .= '"' . $data[4] . '",';
										  $query .= '"' . $data[2] . '",';

										  $boost = 0;

										  if ($data[7] > 0) {
												$query .= $TRIP_CASH . ',';
												$query .= correct_number($data[8]) . ',';
												$query .= $boost . ",";
												$query .= correct_number($data[7]) . ',';
										  } else {
												$query .= $TRIP_CARD . ',';
												$query .= correct_number($data[8]) . ',';
												$query .= $boost . ",";
												$query .= correct_number($data[7]) . ',';
										  }

										  $query .= '0,';
										  $query .= 'NULL,';

										  if (mb_detect_encoding($data[1]) != "UTF-8") {
												$query .= '"' . translate($data[1]) . '",';
										  } else {
												$query .= '"' . $data[1] . '",';
										  }
										  
										  $query .= 0 . ',';
										  $query .= $ids[$driver_index]. ',' ;
										  $query .= 0 ;
										  $query .= "),";

							 		// driver recognised but not shifted
								 	} else {
								 		$unshifted_trips_list .= $data[4] . " " . $data[2] . " " . $names[$driver_index] . "<br>";

								 		$unshifted_trips_count++;

								 	}
								 }
							}               
					  }
				 }
			}
		}

		$row++;

		if ($row % 100 == 0 ) {
			if (strlen($query) > 0) {

				 $query = substr($query, 0, strlen($query) - 1 );
				 file_put_contents('gett_import.sql', $insert_query . $query . "\n", FILE_APPEND);

				 $result = mysql_query($insert_query . $query) or die(mysql_error());
			}
			$query = "";
		}
	 }

	 if ($row % 100 != 0 ) {
		if (strlen($query) > 0) {
			$query = substr($query, 0, strlen($query) - 1 );
			// echo $insert_query . $query . PHP_EOL;

			$result = mysql_query($insert_query . $query) or die(mysql_error());
			file_put_contents('gett_import.sql', $insert_query . $query . "\n", FILE_APPEND);

		}
	 }

	 // if ($unshifted_trips_count == 0 && $bad_contacts_count == 0){
		
		if (strval(substr($last_date, 11,2))> 12) {
			$end_date = substr($last_date, 0, 10);
		} else {
			$end_date = date("Y-m-d", strtotime($last_date) - 24*60*60);	 
		}

		if (strval(substr($first_date, 11,2))> 12) {
			$start_date = substr($first_date, 0, 10);
		} else {
			$start_date = date("Y-m-d", strtotime($first_date) - 24*60*60);	 
		}

		file_put_contents('gett_completeness.sql', 'firstdate = '. $first_date. "\n", FILE_APPEND);
		file_put_contents('gett_completeness.sql', 'lastdate = '. $last_date. "\n", FILE_APPEND);

		file_put_contents('gett_completeness.sql', 'startdate = '. $start_date. "\n", FILE_APPEND);
		file_put_contents('gett_completeness.sql', 'enddate = '. $end_date. "\n", FILE_APPEND);

		while ($start_date <= $end_date) {

		    $query = "SELECT import_for_date FROM gett_completed_imports ";
		    $query .= " WHERE import_for_date = '$start_date' ";

			$result = mysql_query($query) or die(mysql_error());

			if (mysql_num_rows($result) > 0) {

			} else {
				$query = "INSERT INTO gett_completed_imports ";
				$query .= "(import_for_date) ";
				$query .= "VALUES (";
				$query .= "'$start_date' ";
				$query .= ")";

				file_put_contents('gett_completeness.sql', $query . "\n", FILE_APPEND);
				$result = mysql_query($query) or die(mysql_error());
			}

			$start_date = date("Y-m-d", strtotime($start_date) + 24*60*60);	 
		}

	 // }

	 // echo '<br>'. $first_date .' <br>';
	 // echo '<br>'. $last_date .' <br>';
	 // echo '<br>'. $start_date .' <br>';
	 // echo '<br>'. $end_date .' <br>';

	 echo '<br>Нераспозанные водители: <br>';
	 echo $bad_contact_list . '<br>';
	 echo '<br>Поездки без введенной смены: <br>';
	 echo $unshifted_trips_list . '<br>';

	 file_put_contents('gett_import.sql', "\nBad Contacts: \n", FILE_APPEND);
	 file_put_contents('gett_import.sql', $bad_contact_list . "\n", FILE_APPEND);
	 file_put_contents('gett_import.sql', "\nUnshiffted trips: \n", FILE_APPEND);
	 file_put_contents('gett_import.sql', $unshifted_trips_list . "\n", FILE_APPEND);

	 file_put_contents('gett_import.sql', '<br> Successfully loaded: '. $successfull_loads_count . "\n", FILE_APPEND);
	 file_put_contents('gett_import.sql', '<br> Skipped trips with bad contacts: '. $bad_contacts_count . "\n", FILE_APPEND);
	 file_put_contents('gett_import.sql', '<br> Skipped existing trips count: '. $already_exists_trips_count . "\n", FILE_APPEND);
	 file_put_contents('gett_import.sql', '<br> Пропущено поездок по нашим водителям не содержащихся в сменах : '. $unshifted_trips_count . "\n", FILE_APPEND);

	 file_put_contents('gett_import.sql', '<br> Cancelled trips count: '. $cancelled_trips . "\n", FILE_APPEND);

	 echo '<br> Успешно загружено записей: '. $successfull_loads_count;
	 echo '<br> Пропущено поездок по нераспознанным водителям: '. $bad_contacts_count;
	 echo '<br> Пропущено поездок по нашим водителям, не содержащихся в сменах : '. $unshifted_trips_count;
	 echo '<br> Пропущено существующих поездок: '. $already_exists_trips_count;    
	 echo '<br> Отмененные поездки: '. $cancelled_trips;    
	 
	 fclose($handle);
} else {
	 file_put_contents('gett_import.sql', 'no handle!', FILE_APPEND);
}
?>