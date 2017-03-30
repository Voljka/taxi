<?php
header('Content-Type: text/html; charset=utf-8');

require ('./config/db.config.php');

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
        $res .= '0' . substr($str, 11,4);
    } else {
        $res .= substr($str, 11,5);
    }
    $res .= ':00';

    return $res;
}

$GET = 2;
$row = 1;
$TRIP_CASH = 1;
$TRIP_CARD = 3;

if (($handle = fopen("get.csv", "r")) !== FALSE) {

    $query_drivers = "SELECT id, CONCAT(TRIM(surname),' ',TRIM(firstname),' ',TRIM(patronymic)) fullname FROM drivers";

    $query_result = mysql_query($query_drivers) or die(mysql_error());        

    while ($row1 = mysql_fetch_assoc($query_result)) 
    {
        $ids[] = $row1['id'];
        $names[] = $row1['fullname'];
    };

    $query_get_trips = "SELECT mediator_trip_id FROM trips WHERE mediator_id = $GET";

    $query_result = mysql_query($query_get_trips) or die(mysql_error());        

    while ($row2 = mysql_fetch_assoc($query_result)) 
    {
        $trip_ids[] = $row2['mediator_trip_id'];
    };

    $insert_query = "INSERT INTO trips (mediator_id, date_time, mediator_trip_id, payment_type_id, fare, comission, notes, driver_fullname, driver_phone, driver_id) VALUES ";

    $bad_contacts = Array();
    $bad_contact_list = '';
    $bad_contats_count = 0;
    $successfull_loads_count = 0;

    $already_exists_trips_count = 0;
    $query = '';

    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {

        if ($row > 4) {

            // check for a trip presence in db
            if ( array_search($data[2], $trip_ids) > -1 ) {
                $already_exists_trips_count++;
            } else {

                // If driver exists in DB
                if (mb_detect_encoding($data[1]) != "ASCII") {
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
                    $successfull_loads_count++;

                	$query .= "(". $GET . ',';
                	$query .= '"' . transform_date($data[4]) . '",';
                    $query .= '"' . $data[2] . '",';

            		if ($data[7] > 0) {
            			$query .= $TRIP_CASH . ',';
            	    	$query .= correct_number($data[7]) . ',';
            		} else {
            			$query .= $TRIP_CARD . ',';
        	        	$query .= correct_number($data[8]) . ',';
            		}

                	////////////////////////////
                	///////////////////////////
                	////////////// COMISSION !!!!!!!!!!!!!!!!!!!
                	$query .= '0,';

                	$query .= 'NULL,';

                	if (mb_detect_encoding($data[1]) != "ASCII") {
                		$query .= '"' . translate($data[1]) . '",';
                	} else {
                		$query .= '"' . $data[1] . '",';
                	}
                	
                	$query .= 0 . ',';
                    $query .= $ids[$driver_index] ;
                	$query .= "),";
                }
            }
        }

        $row++;

        if ($row % 100 == 0 ) {
            if (strlen($query) > 0) {

                $query = substr($query, 0, strlen($query) - 1 );
                echo $insert_query . $query . PHP_EOL;

                $result = mysql_query($insert_query . $query) or die(mysql_error());

            }
            $query = "";
        }
    }

    if ($row % 100 != 0 ) {
        if (strlen($query) > 0) {
            $query = substr($query, 0, strlen($query) - 1 );
            echo $insert_query . $query . PHP_EOL;

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