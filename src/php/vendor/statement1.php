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

print_r($aRes[URL_STATEMENTS]["HTML"]);

$aRes = json_decode($aRes[URL_STATEMENTS]["HTML"], true);

if (!array_key_exists('drivers', $aRes['body'])) {
	fnLog("Trips: no data found.");
	die();
}

function in_array_except($element, $array, $exception_list){

	// file_put_contents( 'uber_strict_parser.sql',"Element: ". $element. " Trips Count: ". count($array). "Exceptions Count: ". count($exception_list). "\n", FILE_APPEND);

	$index = -1;
	for ($j=0; $j < count($array) ; $j++ ) { 

		if ($array[$j] == $element){
			// file_put_contents( 'uber_strict_parser.sql',"!!!!At least element is found!!!!". "\n", FILE_APPEND);
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
	
	file_put_contents( 'uber_strict_parser.sql', "PARAMS: trip = ". $trip . " exceptions " . $exceptions .  "\n", FILE_APPEND);

	//$cur_index = array_search($trip, $trip_ids);
	$cur_index = in_array_except($trip, $trip_ids, $exceptions);

	file_put_contents( 'uber_strict_parser.sql', "trip = ". $trip . " trip type " . $trip_type . " trip notes = " . $trip_note  . ' cur_index =  ' . $cur_index. "\n", FILE_APPEND);

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

		// $cur_index = array_search($trip, $trip_ids);
		file_put_contents( 'uber_strict_parser.sql', "PARAMS 2 : trip = ". $trip . " exceptions " . $exceptions .  "\n", FILE_APPEND);
		$cur_index = in_array_except($trip, $trip_ids, $exceptions);

		file_put_contents( 'uber_strict_parser.sql', "Second!!!! trip = ". $trip . " trip type " . $trip_type . " trip notes = " . $trip_note . ' cur_index =  ' . $cur_index . "\n", FILE_APPEND);
	}


	return $presense;
}

//////////////// get Parameters from request 
$rangeStartAt = $_POST['periodStart'];
$rangeEndAt = $_POST['periodEnd'];

// $rangeStartAt = "2017-05-04T00:00:00";
// $rangeEndAt = "2017-05-10T23:59:59";

$rangeStartAt = "2017-05-08 00:00:00";
$rangeEndAt = "2017-05-15 11:59:59";
// $adjustmentsAt = date('Y:m:d') . ' 08:00:00';
$adjustmentsAt = '2017-05-14' . ' 16:00:00';


/// setup variables
$row = 1;

$UBER = 1;
$TRIP_CASH = 1;
$TRIP_MISC = 2;
$MISC = 2;

$bad_contacts = Array();
$bad_contact_list = '';
$bad_contats_count = 0;
$successfull_loads_count = 0;

$already_exists_trips_count = 0;

// get driver list from DB and existing trips 
require('../config/db.config.php');

$query_drivers = "SELECT id, CONCAT(TRIM(firstname),' ',TRIM(surname)) fullname, phone, phone2 FROM drivers";

$query_result = mysql_query($query_drivers) or die(mysql_error());        

while ($row1 = mysql_fetch_assoc($query_result)) 
{
    $ids[] = $row1['id'];
    $phones[] = $row1['phone'];
    $phones2[] = $row1['phone2'];
};

$query_uber_trips = "SELECT mediator_trip_id, payment_type_id, notes FROM trips WHERE mediator_id = 1 ";

// $query_uber_trips = "SELECT mediator_trip_id, payment_type_id, notes FROM trips WHERE mediator_id = 1 AND date_time BETWEEN '$rangeStartAt' AND '$rangeEndAt'";

$query_result = mysql_query($query_uber_trips) or die(mysql_error());        

while ($row2 = mysql_fetch_assoc($query_result)) 
{
    $trip_ids[] = $row2['mediator_trip_id'];
    $payment_ids[] = $row2['payment_type_id'];
    $notes[] = $row2['notes'];
};

$query = '';

$insert_query = "INSERT INTO trips (mediator_id, date_time, mediator_trip_id, payment_type_id, fare, comission, cash, notes, driver_fullname, driver_phone, driver_id) VALUES ";

file_put_contents('uber_strict_parser.sql', date("Y-m-d") . "\n");

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
			            $successfull_loads_count++;
			            $query .= "(". $UBER . ',';

			            //print_r( $aTrip['line_items'][0]['data'] ) . '<br>';
			            if (array_key_exists('line_items', $aTrip) && $is_trip_a_recalc) {
				            $query .= '"' . $adjustmentsAt . '",';
				            $query .= '"' . $trip_id . '",';
				            $query .= $TRIP_MISC . ',';
				            // $query .= $aTrip['line_items'][0]['data']['amount'] . ',';
				            $query .= $aTrip['fare_adjustment_delta'] . ',';
				            $query .=  '0,';
				            // $query .= $aTrip['cash_collected'] . ',';
				            $query .=  '0,';
				            $query .= '"' . $aTrip['line_items'][0]['data']['message'] . '",';
			            } else {
				            // $query .= '"' . $aTrip['date'] . '",';
				            $query .= '"' . date('Y-m-d H:i:s', strtotime($aTrip['date'])) . '",';
				            $query .= '"' . $trip_id . '",';
				            $query .= $TRIP_CASH . ',';
				            $query .= $aTrip['fare'] . ',';
				            $query .= ($aTrip['uber_fee_inc_vat'] ? $aTrip['uber_fee_inc_vat'] : 0) . ',';
				            $query .= $aTrip['cash_collected'] . ',';
				            $query .= 'NULL,';
			            }

			            //// non-obligable fields. Remove them!!!!!!!
			            $query .= '"' . $driver_name . '",';
			            $query .= $driver_phone . ',' ;
			            /////////////////////////////////////////////

			            $query .= $ids[$driver_index] ;
			            $query .= "),";
	            }
			}
	    }
	}
}

if (strlen($query) > 0) {
    $query = substr($query, 0, strlen($query) - 1 );
    // echo $insert_query . $query . PHP_EOL;
	file_put_contents('uber_strict_parser.sql', "\n$insert_query $query \n", FILE_APPEND);

    // $result = mysql_query($insert_query . $query) or die(mysql_error());
}

echo '<br><br><br><br><br>';

echo '<br>Нераспознанные водители: <br>';
echo $bad_contact_list . '<br>';

echo '<br> Успешно загружено записей : '. $successfull_loads_count;
echo '<br> Пропущено водителей: '. $bad_contacts_count;
echo '<br> Пропущены уже существующие записи: '. $already_exists_trips_count;

file_put_contents('uber_strict_parser.sql', "\nBad Contacts: \n", FILE_APPEND);
file_put_contents('uber_strict_parser.sql', $bad_contact_list . "\n", FILE_APPEND);
file_put_contents('uber_strict_parser.sql', '<br> Successfully loaded: '. $successfull_loads_count . "\n", FILE_APPEND);
file_put_contents('uber_strict_parser.sql', '<br> Skipped trips with bad contacts: '. $bad_contacts_count . "\n", FILE_APPEND);
file_put_contents('uber_strict_parser.sql', '<br> Skipped existing trips count: '. $already_exists_trips_count . "\n", FILE_APPEND);
// file_put_contents('uber_strict_parser.sql', '<br> Cancelled trips count: '. $cancelled_trips . "\n", FILE_APPEND);

if ($bad_contacts_count == 0 && $successfull_loads_count > 0) {
// if ($successfull_loads_count > 0) {
	$query = "INSERT INTO uber_completed_imports (`import_for_date`) VALUES ";

	$start_date = date("Y-m-d", strtotime($rangeStartAt));
	$end_date = date("Y-m-d", strtotime($rangeEndAt));

	$next_date = $start_date;

	while ($next_date <= $end_date) {
		$query .= "('$next_date'),";

		$next_date = date("Y-m-d", strtotime($next_date) + 24*60*60);
	};

    $query = substr($query, 0, strlen($query) - 1 );

    $result = mysql_query($query) or die(mysql_error());
}

?>