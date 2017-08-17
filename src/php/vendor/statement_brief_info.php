<?php
require_once('func.inc.php');

function getPreviousUberReportId($aRes){
	$data=preg_replace("|([^\{]+)(.*)|is", "\${2}", $aRes[URL_BASE_STATEMENTS]["HTML"]);

	$report_list_start = strripos($data, 'window.__JSON_GLOBALS_["data"] ');
	$report_array_start = strripos($data, '[{', $report_list_start);
	$report_array_finish = strripos($data, '}]', $report_list_start);

	$report_array = substr($data, $report_array_start, $report_array_finish - $report_array_start + 2);

	$report_array = json_decode($report_array, true);

	$cur_day_of_the_week = date("w");

	$day_correction = $cur_day_of_the_week - 2 + 7;
	$curdate = date("Y-m-d");
	$start_date = date("Y-m-d H:i:s", strtotime($curdate) - ($day_correction)*24*60*60 + 4*60*60);
	// $start_date = date("Y-m-d H:i:s", strtotime($curdate) - 2*($day_correction)*24*60*60 + 4*60*60);

	$calculated_time = strtotime($start_date);

	$report_id = 'NOT FOUND';

	foreach ($report_array as $report) {
		if ($report['starting_at'] == $calculated_time) {
			$report_id = $report['uuid'];
			break;
		}
	}

	return $report_id;
}

function getWeekDates($is_last){
	$cur_day_of_the_week = date("w");
	$curdate = date("Y-m-d");

	if (! $is_last){
		$curdate =	date("Y-m-d", strtotime($curdate) - 7*24*60*60);
	}

	$range = array();
	$range["start"] = date("Y-m-d", strtotime($curdate) - ($cur_day_of_the_week - 1)*24*60*60);
	$range["finish"] = date("Y-m-d", strtotime($range["start"]) + 6.5*24*60*60);

	return $range;
}

$params = json_decode(file_get_contents('php://input'),true);
$is_current = $params["is_current"];

const URL_BASE_STATEMENTS="https://partners.uber.com/p3/money/statements/index";

GetURL(URL_BASE_STATEMENTS,$aRes);

// touch it!

if ($is_current == 1) {
	$URL_STATEMENTS="https://partners.uber.com/p3/money/statements/view/current";

} else {
	$report_id = getPreviousUberReportId($aRes);
	$URL_STATEMENTS="https://partners.uber.com/p3/money/statements/view/$report_id";
}

GetURL($URL_STATEMENTS,$aRes);


$aRes[$URL_STATEMENTS]["HTML"]=preg_replace("|([^\{]+)(.*)|is", "\${2}", $aRes[$URL_STATEMENTS]["HTML"]);

// print_r($aRes[$URL_STATEMENTS]["HTML"]);

$aRes = json_decode($aRes[$URL_STATEMENTS]["HTML"], true);

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
		if ($corrections[$i]['driver_id'] == $driver_id && $note == $corrections[$i]['notes'] && (floatval($amount) == floatval($corrections[$i]['amount'])) ) {
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
// $rangeStartAt = $_POST['periodStart'];
// $rangeEndAt = $_POST['periodEnd'];

$current_week = getWeekDates();
$week_start = $current_week["start"];
$week_finish = $current_week["finish"];
$query_week = "SELECT * FROM weeks WHERE start_date ='$week_start' AND end_date = '$week_finish'";
file_put_contents('weeks.log', date("Y-m-s H:i:s") . "\n" . $query_week);
$query_result = mysql_query($query_week);

if (mysql_affected_rows() < 1){
	$query_week = "INSERT INTO weeks (start_date, end_date) VALUES('$week_start','$week_finish') ";
	file_put_contents('weeks.log', "\nNEW Week: \n" . $query_week . "\n", FILE_APPEND);
	$query_result = mysql_query($query_week);
	$week_id = mysql_insert_id();
} else {
	while ($row = mysql_fetch_assoc($query_result)) 
	{
	  $week_id = $row['id'];
	};
}

file_put_contents('weeks.log', "\n Week id: \n" . $week_id . "\n", FILE_APPEND);

file_put_contents('weeks_params.log', date("Y-m-s H:i:s") . "\n");
$bonuses = array();

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

file_put_contents('uber_import_brief.sql', date("Y-m-d H:i:s") . "\n");
file_put_contents('uber_import_brief_adjust.sql', date("Y-m-d H:i:s") . "\n");

$unshifted_trips_list = '';
$unshifted_trips_count = 0;
$new_corrections_count = 0;

////////////// Loop by drivers in the report

$week_datetime = date("Y-m-d", strtotime($week_start) + 24*60*60) . " 00:00:00";
$week_date = date("Y-m-d", strtotime($week_start) + 24*60*60);

// $week_datetime = date("Y-m-d", strtotime($week_start) - 6*24*60*60) . " 00:00:00";
// $week_date = date("Y-m-d", strtotime($week_start) - 6*24*60*60);

foreach ($aRes['body']['drivers'] as $k=>$aD) {

	if (!array_key_exists("trip_earnings",$aD)){
	}
	else {
		$driver_phone = $aD['contact_number'];
		$driver_name = $aD['last_name'] . ' ' . $aD['first_name'];

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
	  	$di = $aD['trip_earnings']['totals'];
			file_put_contents('uber_import_brief.sql', $aD['last_name'] . ": \n", FILE_APPEND);
			
			$fare = (array_key_exists('fare', $di) ? $di['fare'] : 0);
			$fee = (array_key_exists('uber_fee_inc_vat', $di) ? $di['uber_fee_inc_vat'] : 0);
			$cash = (array_key_exists('cash_collected', $di) ? $di['cash_collected'] : 0);
			$boost = (array_key_exists('earnings_boost_non_commissionable', $di) ? $di['earnings_boost_non_commissionable'] : 0);
			$surge = (array_key_exists('surge', $di) ? $di['surge'] : 0);
			$misc = (array_key_exists('misc', $di) ? $di['misc'] : 0);
			$cancellation = (array_key_exists('cancellation', $di) ? $di['cancellation'] : 0);
			$adjust = (array_key_exists('fare_adjustment_delta', $di) ? $di['fare_adjustment_delta'] : 0);
			$driver_id = $ids[$driver_index];

			$full_fare = floatval($fare) + floatval($surge) + floatval($misc) + floatval($cancellation);

			$query .= "( $UBER, '$week_datetime', 'TOTAL', 1, $full_fare, $boost, $fee, $cash, '', '$driver_name', $driver_phone, $driver_id),";
			// file_put_contents('uber_import_brief.sql', $query . " \n\n", FILE_APPEND);

			if (floatval($adjust) != 0) {
				$query2 .= "( $UBER, 'TOTAL', $driver_id, $adjust, '', '$week_date'),";
				file_put_contents('uber_import_brief_adjust.sql', $driver_name. ' : '. $query2 . "\n" , FILE_APPEND);
			}
	  }
	}
}

if (strlen($query) > 0) {
    $query = substr($query, 0, strlen($query) - 1 );
		file_put_contents('uber_import_brief.sql', "\n$insert_query $query \n\n", FILE_APPEND);

		$query_remove = "DELETE FROM trips WHERE date_time='$week_datetime'";
    $result = mysql_query($query_remove) or die(mysql_error());

    $result1 = mysql_query($insert_query. " " . $query) or die(mysql_error());
}

if (strlen($query2) > 0) {
    $query2 = substr($query2, 0, strlen($query2) - 1 );
		file_put_contents('uber_import_brief.sql', "\n$insert_corrections_query $query2 \n", FILE_APPEND);

		$query_remove = "DELETE FROM corrections WHERE recognized_at='$week_date'";
    $result = mysql_query($query_remove) or die(mysql_error());

    $result1 = mysql_query($insert_corrections_query. " " . $query2) or die(mysql_error());
}

file_put_contents('uber_import_brief.sql', "\nBad Contacts: \n", FILE_APPEND);
file_put_contents('uber_import_brief.sql', $bad_contact_list . "\n", FILE_APPEND);

if ($bad_contacts_count > 0) {
	echo '<br>Нераспознанные водители: <br>';
	echo $bad_contact_list . '<br>';
} else {
	echo 'Все водители успешно распозаны и данные загружены';
}

?>