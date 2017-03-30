<?php
require_once('func.inc.php');

const URL_STATEMENTS="https://partners.uber.com/p3/money/statements/view/current";

//const URL_STATEMENTS="https://partners.uber.com/p3/money/statements/view/87bd6e22-008d-3503-e7b2-a3d001b31be0";

// touch it!
GetURL('https://partners.uber.com/p3/money/statements/index',$aRes);

GetURL(URL_STATEMENTS, $aRes);

$aRes[URL_STATEMENTS]["HTML"]=preg_replace("|([^\{]+)(.*)|is", "\${2}", $aRes[URL_STATEMENTS]["HTML"]);

// print_r($aRes[URL_STATEMENTS]["HTML"]) . '<br>';

$aRes = json_decode($aRes[URL_STATEMENTS]["HTML"], true);

if (!array_key_exists('drivers', $aRes['body'])) {
	fnLog("Trips: no data found.");
	die();
}

//////////////// get Parameters from request 
$rangeStartAt = $_POST['periodStart'];
$rangeEndAt = $_POST['periodEnd'];

$rangeStartAt = "2017-03-27T00:00:00";
$rangeEndAt = "2017-04-02T23:59:59";

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

$query_uber_trips = "SELECT mediator_trip_id, payment_type_id, notes FROM trips WHERE mediator_id = 1 AND date_time BETWEEN '$rangeStartAt' AND '$rangeEndAt'";

echo $query_uber_trips . '<br>';

$query_result = mysql_query($query_uber_trips) or die(mysql_error());        

while ($row2 = mysql_fetch_assoc($query_result)) 
{
    $trip_ids[] = $row2['mediator_trip_id'];
    $payment_ids[] = $row2['payment_type_id'];
    $notes[] = $row2['notes'];
};

echo count($trip_ids);

$query = '';

$insert_query = "INSERT INTO trips (mediator_id, date_time, mediator_trip_id, payment_type_id, fare, comission, cash, notes, driver_fullname, driver_phone, driver_id) VALUES ";

////////////// Loop by drivers in the report

foreach ($aRes['body']['drivers'] as $k=>$aD) {

	if (!array_key_exists("trip_earnings",$aD)){
		var_dump($aD);
		
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

            $contact = 'Fullname: ' . $driver_name . ' Phone: ' . $driver_phone;
            $bad_contacts_count++;

            if ( array_search($contact, $bad_contacts) > -1 ) {
            } else {
                $bad_contacts[count($bad_contacts)] = $contact;
                $bad_contact_list .= $contact . '<br>';

            }
        } else {
        	////////// Loop By trips of the driver
			foreach($aD["trip_earnings"]['trips'] as $trip_id=>$aTrip){

	            $existing_trip_index = array_search($trip_id, $trip_ids);
	            $is_trip_to_write = ! ($existing_trip_index > -1);

	            $is_trip_a_recalc = array_key_exists('line_items', $aTrip) && $aTrip['line_items'][0]['key'] == "driver_statement.trip_earnings_detail.line_item.adjustment";

	            if ($is_trip_a_recalc && $is_trip_to_write) {
	            	if ($payment_ids[$existing_trip_index] == $TRIP_MISC && $notes[$existing_trip_index] == $aTrip['line_items'][0]['data']['message']) {
	            		$is_trip_to_write = false;
	            	}

	            }

	            if (! $is_trip_to_write) {
	                $already_exists_trips_count++;
	            } else {
		            $successfull_loads_count++;
		            $query .= "(". $UBER . ',';

		            //print_r( $aTrip['line_items'][0]['data'] ) . '<br>';
		            if (array_key_exists('line_items', $aTrip) && $is_trip_a_recalc) {
			            $query .= '"' . $rangeStartAt . '",';
			            $query .= '"' . $trip_id . '",';
			            $query .= $TRIP_MISC . ',';
			            $query .= $aTrip['line_items'][0]['data']['amount'] . ',';
			            $query .= $aTrip['uber_fee'] . ',';
			            $query .= $aTrip['cash_collected'] . ',';
			            $query .= '"' . $aTrip['line_items'][0]['data']['message'] . '",';
		            } else {
			            $query .= '"' . $aTrip['date'] . '",';
			            $query .= '"' . $trip_id . '",';
			            $query .= $TRIP_CASH . ',';
			            $query .= $aTrip['fare'] . ',';
			            $query .= $aTrip['uber_fee'] . ',';
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

    $result = mysql_query($insert_query . $query) or die(mysql_error());
}

echo '<br><br><br><br><br>';

echo '<br>Bad Contacts: <br>';
echo $bad_contact_list . '<br>';

echo '<br> Successfully loaded: '. $successfull_loads_count;
echo '<br> Skipped drivers with bad contacts: '. $bad_contacts_count;
echo '<br> Skipped existing trips count: '. $already_exists_trips_count;


?>