<?php
$row = 1;

$UBER = 1;
$TRIP_CASH = 1;
$MISC = 2;

require ('./config/db.config.php');

if (($handle = fopen("uber.csv", "r")) !== FALSE) {

    $query_drivers = "SELECT id, CONCAT(TRIM(firstname),' ',TRIM(surname)) fullname, phone, phone2 FROM drivers";

    $query_result = mysql_query($query_drivers) or die(mysql_error());        

    while ($row1 = mysql_fetch_assoc($query_result)) 
    {
        $ids[] = $row1['id'];
        $names[] = $row1['fullname'];
        $phones[] = $row1['phone'];
        $phones2[] = $row1['phone2'];
    };

    $query_uber_trips = "SELECT mediator_trip_id, payment_type_id, notes FROM trips WHERE mediator_id = 1";

    $query_result = mysql_query($query_uber_trips) or die(mysql_error());        

    while ($row2 = mysql_fetch_assoc($query_result)) 
    {
        $trip_ids[] = $row2['mediator_trip_id'];
        $payment_ids[] = $row2['payment_type_id'];
        $notes[] = $row2['notes'];
    };

	$insert_query = "INSERT INTO trips (mediator_id, date_time, mediator_trip_id, payment_type_id, fare, comission, notes, driver_fullname, driver_phone, driver_id) VALUES ";

    $bad_contacts = Array();
    $bad_contact_list = '';
    $bad_contats_count = 0;

    $already_exists_trips_count = 0;

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

        $query = '';
        if ($row > 1) {

            // check for a trip presence in db
            if (($data[3] == "Misc Payment" && array_search($data[5], $notes) > -1) || ($data[3] == "Trip" && array_search($data[6], $trip_ids) > -1)) {
                $already_exists_trips_count++;
            } else {
                // If driver exists in DB
                $driver_index = array_search($data[1], $phones);
                if (! $driver_index) {
                    $driver_index = array_search($data[1], $phones2);
                }

                if (! $driver_index) {
                    echo 'Driver does\'t related to any existing : ' . $data[0] . ' Phone: ' . $data[1] . '<br>';
                    // $driver_index = array_search($data[0], $names);
                    // if ($driver_index) {
                    //     echo '             But successfully found by fullname : ' . $data[0] . ' Phone: ' . $data[1] . '<br>';
                    // }

                    $contact = 'Fullname: ' . $data[0] . ' Phone: ' . $data[1];
                    $bad_contacts_count++;

                    //echo array_search($contact, $bad_contacts) . '<br>';

                    // if ( count($bad_contacts) == 0 || array_search($contact, $bad_contacts) == false) {
                    if ( array_search($contact, $bad_contacts) > -1 ) {
                    } else {
                        $bad_contacts[count($bad_contacts)] = 'Fullname: ' . $data[0] . ' Phone: ' . $data[1];
                        $bad_contact_list .= 'Fullname: ' . $data[0] . ' Phone: ' . $data[1] . '<br>';

                    }
                } else {
                    $query .= "(". $UBER . ',';
                    if ($data[3] == "Misc Payment") {
                        $query .= 'NULL,';
                        $query .= '"",';
                        $query .= $MISC . ',';
                        $query .= $data[16] . ',';
                        $query .= '0,';
                        $query .= '"' . $data[5] . '",';

                    } else {
                        $query .= '"' . $data[4] . '",';
                        $query .= '"' . $data[6] . '",';
                        $query .= $TRIP_CASH . ',';
                        $query .= $data[7] . ',';
                        $query .= $data[14] . ',';
                        $query .= 'NULL,';
                    }
                    $query .= '"' . $data[0] . '",';
                    $query .= $data[1] . ',' ;
                    $query .= $ids[$driver_index] ;
                    $query .= "),";

                    $insert_query .= $query;

                    echo 'Driver FOUND : ' . $data[0] . ' Phone: ' . $data[1] . '<br>';

                }
            }
        }
        $row++;

        if ($row % 100 == 0 ) {
		    $insert_query = substr($insert_query, 0, strlen($insert_query) - 1 );
		    echo $insert_query . PHP_EOL;

			$result = mysql_query($insert_query) or die(mysql_error());
			// echo PHP_EOL . $result . PHP_EOL;

			$insert_query = "INSERT INTO trips (mediator_id, date_time, mediator_trip_id, payment_type_id, fare, comission, notes, driver_fullname, driver_phone, driver_id) VALUES ";
        }
    }

    if ($row % 100 != 0 ) {
	    $insert_query = substr($insert_query, 0, strlen($insert_query) - 1 );
	    echo $insert_query . PHP_EOL;

		$result = mysql_query($insert_query) or die(mysql_error());
		// echo PHP_EOL . $result . PHP_EOL;
    }

    echo '<br>Bad Contacts: <br>';
    echo $bad_contact_list . '<br>';

    echo '<br> Skipped trips with bad contacts: '. $bad_contacts_count;
    echo '<br> Skipped existing trips count: '. $already_exists_trips_count;
    
    fclose($handle);
}
?>