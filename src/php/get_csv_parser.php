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
	$insert_query = "INSERT INTO trips (mediator_id, date_time, payment_type_id, fare, comission, notes, driver_fullname, mediator_trip_id, driver_phone) VALUES ";

    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {

        if ($row > 4) {
        	$insert_query .= "(". $GET . ',';
        	$insert_query .= '"' . transform_date($data[4]) . '",';

    		if ($data[7] > 0) {
    			$insert_query .= $TRIP_CASH . ',';
    	    	$insert_query .= correct_number($data[7]) . ',';
    		} else {
    			$insert_query .= $TRIP_CARD . ',';
	        	$insert_query .= correct_number($data[8]) . ',';
    		}

        	////////////////////////////
        	///////////////////////////
        	////////////// COMISSION !!!!!!!!!!!!!!!!!!!
        	$insert_query .= '0,';

        	$insert_query .= 'NULL,';

        	// echo mb_detect_encoding($data[1]);
        	// echo " " . $data[1] . PHP_EOL;
        	// echo " " . translate($data[1]) . PHP_EOL;

        	// echo mb_detect_encoding($data[1]) . PHP_EOL; 

        	if (mb_detect_encoding($data[1]) != "ASCII") {
        		$insert_query .= '"' . translate($data[1]) . '",';
        	} else {
        		$insert_query .= '"' . $data[1] . '",';
        	}
        	
            $insert_query .= $data[2] . ',';

        	////////////////////////////
        	///////////////////////////
        	////////////// PHONE !!!!!!!!!!!!!!!!!!!
        	$insert_query .= 0 ;

        	$insert_query .= "),";
        }

        $row++;

        if ($row % 100 == 0 ) {
		    $insert_query = substr($insert_query, 0, strlen($insert_query) - 1 );
		    echo $insert_query . PHP_EOL;

			$result = mysql_query($insert_query) or die(mysql_error());
			echo PHP_EOL . $result . PHP_EOL;

            $insert_query = "INSERT INTO trips (mediator_id, date_time, payment_type_id, fare, comission, notes, driver_fullname, mediator_trip_id, driver_phone) VALUES ";
        }
    }

    if ($row % 100 != 0 ) {
	    $insert_query = substr($insert_query, 0, strlen($insert_query) - 1 );
	    echo $insert_query . PHP_EOL;

		$result = mysql_query($insert_query) or die(mysql_error());
		echo PHP_EOL . $result . PHP_EOL;
    }
    
    fclose($handle);
}
?>