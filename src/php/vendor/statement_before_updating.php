<?php
require_once('func.inc.php');

const URL_STATEMENTS="https://partners.uber.com/p3/money/statements/view/current";

// const URL_STATEMENTS="https://partners.uber.com/p3/money/statements/view/e498c661-71eb-dced-1cc4-7251714317d0";
// echo "In<br>";

// touch it!
GetURL('https://partners.uber.com/p3/money/statements/index',$aRes);

// print_r($aRes);

GetURL(URL_STATEMENTS, $aRes);

$aRes[URL_STATEMENTS]["HTML"]=preg_replace("|([^\{]+)(.*)|is", "\${2}", $aRes[URL_STATEMENTS]["HTML"]);

// print_r($aRes[URL_STATEMENTS]["HTML"]);

$aRes = json_decode($aRes[URL_STATEMENTS]["HTML"], true);

if (!array_key_exists('drivers', $aRes['body'])) {
	fnLog("Trips: no data found.");
	die();
}

function in_array_except($element, $array, $exception_list){

	$index = -1;
	for ($j=0; $j < count($array) ; $j++ ) { 

		if ($array[$j] == $element){
			if (count($exception_list) == 0 || ! in_array($j, $exception_list) ){
				$index = $j;
				break;
			}
		}
	}

	return $index;
}

function find_by_3_columns($trip, $trip_type, $trip_note){

	global $trip_ids, $payment_ids, $notes, $MISC;
	$presense = -1;
	$last_index = undefined;
	$exceptions = array(); 
	
	$cur_index = in_array_except($trip, $trip_ids, $exceptions);

	while ($cur_index > -1){

		$exceptions[] = $cur_index;

		if ($trip_type == $MISC) {
			if ($payment_ids[$cur_index] == $trip_type && $notes[$cur_index] == $trip_note){
				$presense = $cur_index;
				break;
			}
		} else {
			if ($payment_ids[$cur_index] == $trip_type){
				$presense = $cur_index;
				break;
			}
		}

		$cur_index = in_array_except($trip, $trip_ids, $exceptions);
	}

	return $presense;
}

function is_correction_presents($driver_id, $note, $amount){
	$res = false;
	global $corrections;

	for ($i=0; $i < count($corrections) ; $i++) { 
		if ($corrections[$i]['driver_id'] == $driver_id && $note == $corrections[$i]['notes'] && ($amount == $corrections[$i]['amount']) ) {
			$res = true;
			break;
		}

	}

	return $res;
}

function is_shift_present($datetime, $driver_id){
	$res = false;
	global $shifts;

	for ($i=0; $i < count($shifts); $i++) { 

		if ($shifts[$i]['driver_id'] == $driver_id && $datetime >= $shifts[$i]['start_time'] && (! $shifts[$i]['finish_time'] || $datetime <= $shifts[$i]['finish_time']) ) {
			$res = true;
			break;
		}

	}

	return $res;
}

//////////////// get Parameters from request 
$rangeStartAt = $_POST['periodStart'];
$rangeEndAt = $_POST['periodEnd'];


/// setup variables
$row = 1;

$UBER = 1;
$TRIP_CASH = 1;
$TRIP_MISC = 2;
$MISC = 2;

$OUR = array();
$OUR[] = 2;
$OUR[] = 3;
$OUR[] = 4;

$bad_contacts = Array();
$bad_contact_list = '';
$bad_contats_count = 0;
$successfull_loads_count = 0;

$already_exists_trips_count = 0;
$already_exists_corrections_count = 0;

// get driver list from DB and existing trips 
require('../config/db.config.php');

$query_drivers = "SELECT id, CONCAT(TRIM(firstname),' ',TRIM(surname)) fullname, phone, phone2, work_type_id FROM drivers";

$query_result = mysql_query($query_drivers) or die(mysql_error());        

while ($row1 = mysql_fetch_assoc($query_result)) 
{
    $ids[] = $row1['id'];
    $phones[] = $row1['phone'];
    $phones2[] = $row1['phone2'];
    $names[] = $row1['fullname'];
    $group_ids[] = $row1['work_type_id'];
};

$query_shifts = "SELECT * FROM shifts ";
$query_shifts .= " ORDER BY shift_date DESC ";

$query_result = mysql_query($query_shifts) or die(mysql_error());        

$shifts = array();
while ($row3 = mysql_fetch_assoc($query_result)) 
{
  $shifts[] = $row3;
};

$query_corrections = "SELECT * FROM corrections";

$query_result = mysql_query($query_corrections) or die(mysql_error());        

while ($row1 = mysql_fetch_assoc($query_result)) 
{
    $corrections[] = $row1;
};

$query_uber_trips = "SELECT mediator_trip_id, payment_type_id, notes FROM trips WHERE mediator_id = $UBER ";

$query_result = mysql_query($query_uber_trips) or die(mysql_error());        

while ($row2 = mysql_fetch_assoc($query_result)) 
{
    $trip_ids[] = $row2['mediator_trip_id'];
    $payment_ids[] = $row2['payment_type_id'];
    $notes[] = $row2['notes'];
};

$query = '';
$query2 = '';

$insert_query = "INSERT INTO trips (mediator_id, date_time, mediator_trip_id, payment_type_id, fare, boost_non_commissionable, comission, cash, notes, driver_fullname, driver_phone, driver_id) VALUES ";

$insert_corrections_query = "INSERT INTO corrections (mediator_id, trip_id, driver_id, amount, notes, recognized_at) VALUES ";

file_put_contents('uber_strict_parser.sql', date("Y-m-d") . "\n");


$unshifted_trips_list = '';
$unshifted_trips_count = 0;
$new_corrections_count = 0;

$last_date = "1900-01-01";
$first_date = "2100-12-31";

////////////// Loop by drivers in the report

foreach ($aRes['body']['drivers'] as $k=>$aD) {

	if (!array_key_exists("trip_earnings",$aD)){
		// var_dump($aD);
		
	}
	else {

		$driver_phone = $aD['contact_number'];
		$driver_name = $aD['last_name'] . ' ' . $aD['first_name'];

        // If driver exists in DB
        $driver_index = array_search($driver_phone, $phones);
        if (! $driver_index) {
            $driver_index = array_search($driver_phone, $phones2);
        }

        if (! $driver_index) {

            $contact = 'ФИО: ' . $driver_name . ' Тел: ' . $driver_phone;
            $bad_contacts_count++;

            if ( array_search($contact, $bad_contacts) > -1 ) {
            } else {
                $bad_contacts[count($bad_contacts)] = $contact;
                $bad_contact_list .= $contact . '<br>';

            }
        } else {
        	////////// Loop By trips of the driver
			foreach($aD["trip_earnings"]['trips'] as $trip_id=>$aTrip){

	            $is_trip_a_recalc = array_key_exists('line_items', $aTrip) && $aTrip['line_items'][0]['key'] == "driver_statement.trip_earnings_detail.line_item.adjustment";

	            $existing_trip_index = find_by_3_columns($trip_id, ($is_trip_a_recalc ? 2 : 1), $aTrip['line_items'][0]['data']['message']);

	            if ($existing_trip_index > -1) {
	            	$is_trip_to_write = false;
	            } else {

		            if ($is_trip_a_recalc){
	            		if ($aTrip['fare_adjustment_delta']){
	            			$is_trip_to_write = true;
	            		} else {
	            			// print_r($aTrip);
		            		$is_trip_to_write = false;
	            		}
		            } else {
			            $is_trip_to_write = true;
		            }
	            }

	            if (! $is_trip_to_write) {
	                $already_exists_trips_count++;
	            } else {

	            	// If driver is not out or is our and shift preents
	            	if (! (in_array($group_ids[$driver_index], $OUR)) || in_array($group_ids[$driver_index], $OUR) && is_shift_present( date('Y-m-d H:i:s', strtotime($aTrip['date']) ), $ids[$driver_index])) {

			            $successfull_loads_count++;

			            if (array_key_exists('line_items', $aTrip) && $is_trip_a_recalc) {
			            	if (! is_correction_presents($ids[$driver_index], $aTrip['line_items'][0]['data']['message'], $aTrip['fare_adjustment_delta'])){
					            $query2 .= "(";
					            $query2 .= $UBER . ',';
					            $query2 .= "'$trip_id',";
					            $query2 .= $ids[$driver_index] . ',';
					            $query2 .= $aTrip['fare_adjustment_delta'] . ',';
					            $query2 .= '"' . $aTrip['line_items'][0]['data']['message'] . '",';
					            $query2 .= "'".date("Y-m-d")."'";
					            $query2 .= "),";

					            $new_corrections_count++;
			            	} else {
			            		$already_exists_corrections_count++;
			            	}
			            } else {
			            	$curdate = date('Y-m-d H:i:s', strtotime($aTrip['date']));
			            	
			            	if ($first_date > $curdate )
			            		$first_date = $curdate ;	
			            	if ($last_date < $curdate)
			            		$last_date = $curdate;	

				            $query .= "(". $UBER . ',';
				            $query .= '"' . date('Y-m-d H:i:s', strtotime($aTrip['date'])) . '",';
				            $query .= '"' . $trip_id . '",';
				            $query .= $TRIP_CASH . ',';
				            $query .= $aTrip['fare'] . ',';

				            if (array_key_exists('earnings_boost_non_commissionable', $aTrip)){
				            	$boost = $aTrip['earnings_boost_non_commissionable'];
				            } else {
				            	$boost = 0;

				            }
				            $query .= $boost . ',';

				            $query .= ($aTrip['uber_fee_inc_vat'] ? $aTrip['uber_fee_inc_vat'] : 0) . ',';
				            $query .= $aTrip['cash_collected'] . ',';
				            $query .= 'NULL,';
				            //// non-obligable fields. Remove them!!!!!!!
				            $query .= '"' . $driver_name . '",';
				            $query .= $driver_phone . ',' ;
				            /////////////////////////////////////////////

				            $query .= $ids[$driver_index] ;
				            $query .= "),";
			            }

			        // Driver hasnt a shift
			        } else {
				 		$unshifted_trips_list .= date('Y-m-d H:i:s', strtotime($aTrip['date'])) . " " . $trip_id . " " . $names[$driver_index]. " " . $group_ids[$driver_index]. "<br>";

				 		$unshifted_trips_count++;
			        }
	            }
			}
	    }
	}
}

file_put_contents('uber_strict_parser.sql', "Length of the base query =". strlen($query) ." \n", FILE_APPEND);

if (strlen($query) > 0) {
    $query = substr($query, 0, strlen($query) - 1 );
	file_put_contents('uber_strict_parser.sql', "\n$insert_query $query \n", FILE_APPEND);

    $result1 = mysql_query($insert_query. " " . $query) or die(mysql_error());
	file_put_contents('uber_strict_parser.sql', "Result of base query = $result1 \n", FILE_APPEND);
}

file_put_contents('uber_strict_parser.sql', "Length of the corrections query =". strlen($query2) ." \n", FILE_APPEND);

if (strlen($query2) > 0) {
    $query2 = substr($query2, 0, strlen($query2) - 1 );
	file_put_contents('uber_strict_parser.sql', "\n$insert_corrections_query $query2 \n", FILE_APPEND);

    $result2 = mysql_query($insert_corrections_query. " " .$query2) or die(mysql_error());
	file_put_contents('uber_strict_parser.sql', "Result of corrections query = $result2 \n", FILE_APPEND);

}

echo '<br><br><br><br><br>';

echo '<br>Нераспознанные водители: <br>';
echo $bad_contact_list . '<br>';
echo '<br>Поездки без введенной смены: <br>';
echo $unshifted_trips_list . '<br>';


echo '<br> Успешно загружено записей : '. $successfull_loads_count;
echo '<br> Пропущено водителей: '. $bad_contacts_count;
echo '<br> Пропущено поездок по нашим водителям, не содержащихся в сменах : '. $unshifted_trips_count;

echo '<br> Пропущены уже существующие записи: '. $already_exists_trips_count;
echo '<br> Новых корректировок: '. $new_corrections_count;
echo '<br> Пропущено существующих корректировок: '. $already_exists_corrections_count;

file_put_contents('uber_strict_parser.sql', "\nBad Contacts: \n", FILE_APPEND);
file_put_contents('uber_strict_parser.sql', $bad_contact_list . "\n", FILE_APPEND);
file_put_contents('uber_strict_parser.sql', '<br> Successfully loaded: '. $successfull_loads_count . "\n", FILE_APPEND);
file_put_contents('uber_strict_parser.sql', '<br> Skipped trips with bad contacts: '. $bad_contacts_count . "\n", FILE_APPEND);
file_put_contents('uber_strict_parser.sql', '<br> Пропущено поездок по нашим водителям не содержащихся в сменах : '. $unshifted_trips_count . "\n", FILE_APPEND);

file_put_contents('uber_strict_parser.sql', '<br> Skipped existing trips count: '. $already_exists_trips_count . "\n", FILE_APPEND);
file_put_contents('uber_strict_parser.sql', '<br> Новых корректировок: '. $new_corrections_count . "\n", FILE_APPEND);
file_put_contents('uber_strict_parser.sql', '<br> Пропущено существующих корректировок: '. $already_exists_corrections_count . "\n", FILE_APPEND);


// if ($bad_contacts_count == 0 && $unshifted_trips_count == 0 && $successfull_loads_count > 0) {
		file_put_contents('uber_completeness.sql', date("Y-m-d H:i:s"). "\n");

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

		file_put_contents('uber_completeness.sql', 'firstdate = '. $first_date. "\n", FILE_APPEND);
		file_put_contents('uber_completeness.sql', 'lastdate = '. $last_date. "\n", FILE_APPEND);

		file_put_contents('uber_completeness.sql', 'startdate = '. $start_date. "\n", FILE_APPEND);
		file_put_contents('uber_completeness.sql', 'enddate = '. $end_date. "\n", FILE_APPEND);

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

				file_put_contents('uber_completeness.sql', $query . "\n", FILE_APPEND);
				$result = mysql_query($query) or die(mysql_error());
			}

			$start_date = date("Y-m-d", strtotime($start_date) + 24*60*60);	 
		}
// }

?>