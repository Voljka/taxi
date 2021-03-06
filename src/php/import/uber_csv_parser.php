<?php

$file = $_FILES['file'];
$row = 1;

$UBER = 1;
$TRIP_CASH = 1;
$MISC = 2;

require ('../config/db.config.php');

file_put_contents('gett_import.sql', date("Y-m-d"));

// echo $file['tmp_name'];
if (($handle = fopen($file['tmp_name'], "r")) !== FALSE) {

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

	$insert_query = "INSERT INTO trips (mediator_id, date_time, mediator_trip_id, payment_type_id, fare, cash, comission, notes, driver_fullname, driver_phone, driver_id, result) VALUES ";

    $bad_contacts = Array();
    $bad_contact_list = '';
    $bad_contats_count = 0;
    $successfull_loads_count = 0;

    $already_exists_trips_count = 0;
    $query = '';

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

        //$query = '';
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

                    $contact = 'Fullname: ' . $data[0] . ' Phone: ' . $data[1];
                    $bad_contacts_count++;

                    if ( array_search($contact, $bad_contacts) > -1 ) {
                    } else {
                        $bad_contacts[count($bad_contacts)] = 'Fullname: ' . $data[0] . ' Phone: ' . $data[1];
                        $bad_contact_list .= 'Fullname: ' . $data[0] . ' Phone: ' . $data[1] . '<br>';

                    }
                } else {
                    $successfull_loads_count++;
                    $query .= "(". $UBER . ',';
                    if ($data[3] == "Misc Payment") {
                        $query .= 'NULL,';
                        $query .= '"",';
                        $query .= $MISC . ',';
                        $query .= $data[16] . ',';
                        // Cash
                        $query .= '0,';
                        // Comission
                        $query .= '0,';
                        $query .= '"' . $data[5] . '",';

                    } else {
                        $query .= '"' . $data[4] . '",';
                        $query .= '"' . $data[6] . '",';
                        $query .= $TRIP_CASH . ',';
                        $query .= $data[7] . ',';
                        // Cash
                        $query .= '0,';
                        // comission
                        $query .= $data[14] . ',';
                        $query .= 'NULL,';
                    }
                    $query .= '"' . $data[0] . '",';
                    $query .= $data[1] . ',' ;
                    $query .= $ids[$driver_index] . ',' ;
                    $query .= $data[16] ;
                    $query .= "),";

                }
            }
        }
        $row++;

        if ($row % 100 == 0 ) {
            if (strlen($query) > 0) {
                $query = substr($query, 0, strlen($query) - 1 );
                // echo $insert_query . $query . PHP_EOL;

                file_put_contents('uber_import.sql', $insert_query . $query . "\n", FILE_APPEND);

                $result = mysql_query($insert_query . $query) or die(mysql_error());

            }
            $query = "";
        }
    }

    if ($row % 100 != 0 ) {
            if (strlen($query) > 0) {
                $query = substr($query, 0, strlen($query) - 1 );
                // echo $insert_query . $query . PHP_EOL;
                file_put_contents('uber_import.sql', $insert_query . $query . "\n", FILE_APPEND);

                $result = mysql_query($insert_query . $query) or die(mysql_error());

            }
    }

    echo '<br>Bad Contacts: <br>';
    echo $bad_contact_list . '<br>';

    echo '<br> Successfully loaded: '. $successfull_loads_count;
    echo '<br> Skipped trips with bad contacts: '. $bad_contacts_count;
    echo '<br> Skipped existing trips count: '. $already_exists_trips_count;
    
    fclose($handle);
}	
?>
