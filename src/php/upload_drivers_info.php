<?php
$row = 1;
if (($handle = fopen("drivers.csv", "r")) !== FALSE) {

	$insert_query = "INSERT INTO drivers (`active`, `work_type_id`, `firstname`, `patronymic`, `surname`, `phone`, `phone2`, email, `card_number`, `beneficiar`, `bank_id`, `notes`, `rent`, `registration_date`) VALUES ";
 
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
         if ($row > 1) {
        	
        	// activeness
        	$insert_query .= "(". $data[2] . ',';
        	// work_type
        	$insert_query .= $data[1] . ',';
    		

    		//firstname
    		if ($data[10] == "_") {
        		$insert_query .= '"",';
        	} else {
        		$insert_query .= '"'. $data[10].'",';
        	}
    		//patronymic
    		if ($data[12] == "_") {
        		$insert_query .= '"",';
        	} else {
        		$insert_query .= '"'. $data[12].'",';
        	}
    		//lastname
    		if ($data[11] == "_") {
        		$insert_query .= '"",';
        	} else {
        		$insert_query .= '"'. $data[11].'",';
        	}

    		//phone
        	$insert_query .= substr($data[7],0,10) . ',';
    		//phone2
        	$insert_query .= substr($data[8],0,10) . ',';


    		//email
    		if ($data[13] == "_") {
        		$insert_query .= '"",';
        	} else {
        		$insert_query .= '"'. $data[13].'",';
        	}

    		//card number
    		if ($data[9] == "_") {
	        	$insert_query .= '0,';
	        } else {
	        	$insert_query .= $data[9] . ',';
	        }

    		

    		//beneficiar
    		if ($data[3] == "_") {
        		$insert_query .= '"",';
        	} else {
        		$insert_query .= '"'. $data[3].'",';
        	}

    		//bank id
        	$insert_query .= $data[5] . ',';

    		//notes
    		if ($data[14] == "_") {
        		$insert_query .= '"",';
        	} else {
        		$insert_query .= '"'. $data[14].'",';
        	}

    		//rent
        	$insert_query .= $data[4] . ',';

    		//registration date
    		if ($data[6] == "NULL") {
        		$insert_query .= 'NULL';
        	} else {
        		$insert_query .= '"'. $data[6].'"';
        	}

        	$insert_query .= "),";
        }

        $row++;

        if ($row % 100 == 0 ) {
		    $insert_query = substr($insert_query, 0, strlen($insert_query) - 1 );
		    echo $insert_query . '<br>';

			// $result = mysql_query($insert_query) or die(mysql_error());
			// echo PHP_EOL . $result  . '<br>';

			$insert_query = "INSERT INTO drivers (active, work_type_id, firstname, patronymic, surname, phone, phone2, email, card_number, beneficiar, bank_id, notes, rent, registration_date) VALUES ";
        }

	}
    
    if ($row % 100 != 0 ) {
	    $insert_query = substr($insert_query, 0, strlen($insert_query) - 1 );
	    echo $insert_query  . '<br>';

		// $result = mysql_query($insert_query) or die(mysql_error());
		// echo PHP_EOL . $result . PHP_EOL;
    }
   
   	fclose($handle);
}
?>