<?php
header('Content-Type: text/html; charset=utf-8');

require ('../config/db.config.php');

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
		  $res .= '0' . substr($str, 11,7);
	 } else {
		  $res .= substr($str, 11,8);
	 }
	 $res .= ':00';

	 return $res;
}

function getXLS($xls){
  include_once 'phpexcel/Classes/PHPExcel/IOFactory.php';
  $objPHPExcel = PHPExcel_IOFactory::load($xls);
  $objPHPExcel->setActiveSheetIndex(0);
  $aSheet = $objPHPExcel->getActiveSheet();

  //этот массив будет содержать массивы содержащие в себе значения ячеек каждой строки
  $array = array();
  //получим итератор строки и пройдемся по нему циклом
  foreach($aSheet->getRowIterator() as $row){
    //получим итератор ячеек текущей строки
    $cellIterator = $row->getCellIterator();
    //пройдемся циклом по ячейкам строки
    //этот массив будет содержать значения каждой отдельной строки
    $item = array();
    foreach($cellIterator as $cell){
      //заносим значения ячеек одной строки в отдельный массив
      // array_push($item, iconv('utf-8', 'cp1251', $cell->getCalculatedValue()));
      array_push($item, $cell->getCalculatedValue());
    }
    //заносим массив со значениями ячеек отдельной строки в "общий массв строк"
    array_push($array, $item);
  }
  return $array;
}

function find_driverId_by_fullname($fullname){
	global $ids, $names;

	$result = 0;

	$driver_index = array_search($fullname, $names);

	if ($driver_index > -1){
		$result = $ids[$driver_index];
	}

	return $result;
}
 
file_put_contents('wheely_import.sql', date("Y-m-d") . "\n");

$WHEELY = 3;
$row = 1;
$TRIP_CASH = 1;
$TRIP_CARD = 3;

$file = $_FILES['file'];

// $xls = getXLS($$file['tmp_name']); 
$xls = getXLS("wheely_sample.xlsx"); 

if (count($xls) > 1) {

	 $query_drivers = "SELECT drivers.id, CONCAT(TRIM(firstname),' ',TRIM(surname)) fullname
	 										FROM drivers 
	 										LEFT JOIN work_types ON work_types.id = drivers.work_type_id
	 										WHERE drivers.work_type_id = 2 OR work_types.is_park = 1";

	 $query_result = mysql_query($query_drivers) or die(mysql_error());        

	 while ($row1 = mysql_fetch_assoc($query_result)) 
	 {
		  $ids[] = $row1['id'];
		  $names[] = $row1['fullname'];
	 };

	 $query_get_trips = "SELECT mediator_trip_id FROM trips WHERE mediator_id = $WHEELY";

	 $query_result = mysql_query($query_get_trips) or die(mysql_error());        

	 while ($row2 = mysql_fetch_assoc($query_result)) 
	 {
		  $trip_ids[] = $row2['mediator_trip_id'];
	 };

	 $insert_query = "INSERT INTO trips_tmp (mediator_id, date_time, mediator_trip_id, payment_type_id, fare, boost_non_commissionable, cash, comission, notes, driver_fullname, driver_phone, driver_id, result) VALUES ";

	 $bad_contacts = Array();
	 $bad_contact_list = '';
	 $bad_contats_count = 0;
	 $successfull_loads_count = 0;

	 $cancelled_trips = 0;

	 $already_exists_trips_count = 0;
	 $query = '';

	 // Find correct columns
	 $cind = 0;

	 $head = $xls[0];

	 foreach ($head as $value) {

	 		if ($value == "Водитель") {
	 			$c_driver = $cind;
	 		}

	 		if ($value == "Номер") {
	 			$c_trip = $cind;
	 		}

	 		if ($value == "Завершен") {
	 			$c_time = $cind;
	 		}

	 		if ($value == "Стоимость по тарифу") {
	 			$c_fare = $cind;
	 		}

	 		if ($value == "Агентское вознаграждение") {
	 			$c_agent = $cind;
	 		}

	 		if ($value == "Лицензионное вознаграждение") {
	 			$c_lic = $cind;
	 		}

	 		if ($value == "Чаевые") {
	 			$c_tea = $cind;
	 		}

	 		if ($value == "Парковка") {
	 			$c_parking = $cind;
	 		}

	 		if ($value == "Штраф") {
	 			$c_fine = $cind;
	 		}

	 		$cind++;
	 }

	 print_r($head);
	 echo $insert_query . "<br>";

	 $rind = 0;

	 foreach ($xls as $row) {

	 	if ($rind == 0){
	 		$rind = 1;
	 	}	else {
			if (! isset($c_fine)) {
				$fine = 0;
			} else {
				$fine = $row[$c_fine];
			}

		  if ( array_search($row[$c_trip], $trip_ids) > -1 ) {
		  	$already_exists_trips_count++;
		  } else {

				$driverId = find_driverId_by_fullname($row[$c_driver]);

				echo $row[$c_driver] . " ".  $driverId . "<br>";

				if ($driverId > 0) {
					$time = substr($row[$c_time], 6,4) . "-".  substr($row[$c_time], 3,2) . "-".  substr($row[$c_time], 0,2);
					$time .= " ". substr($row[$c_time], 11,2) . ":".  substr($row[$c_time], 14,2) . ":00";

			 		$query .= " ($WHEELY, '" . $time ."', " . $row[$c_trip] . ", $TRIP_CARD, " . $row[$c_fare] . ", " . ($row[$c_tea] + $row[$c_parking]) . ", 0, " . ($row[$c_agent] + $row[$c_lic]) . ", '', '" . $row[$c_driver] . "', 9999999999," . $driverId. ", " . $fine . ")," ;
			 		echo " ($WHEELY, '" . $time ."', " . $row[$c_trip] . ", $TRIP_CARD, " . $row[$c_fare] . ", " . ($row[$c_tea] + $row[$c_parking]) . ", 0, " . ($row[$c_agent] + $row[$c_lic]) . ", '', '" . $row[$c_driver] . "', 9999999999," . $driverId. ", " . $fine . "),";

			 		$successfull_loads_count++;

				} else {
				  if ( array_search($row[$c_driver], $bad_contacts) > -1 ) {
				  } else {
						$bad_contats_count++;
						$bad_contact_list .= $row[$c_driver] . "<br>";
						$bad_contacts[] = $row[$c_driver];
				  }
				}
			}
	 	}
	 }

	 echo $insert_query. $query . " \n";

	 file_put_contents('wheely_import.sql', "\n" . $insert_query. $query . " \n", FILE_APPEND);

	if (strlen($query) > 0) {
		$query = substr($query, 0, strlen($query) - 1 );
		$result = mysql_query($insert_query . $query) or die(mysql_error());
	}

	 echo '<br>Нераспозанные водители: <br>';
	 echo $bad_contact_list . '<br>';

	 file_put_contents('wheely_import.sql', "\nBad Contacts: \n", FILE_APPEND);
	 file_put_contents('wheely_import.sql', $bad_contact_list . "\n", FILE_APPEND);

	 echo '<br> Успешно загружено записей: '. $successfull_loads_count;
	 echo '<br> Пропущено поездок по нераспознанным водителям: '. $bad_contacts_count;
	 echo '<br> Пропущено существующих поездок: '. $already_exists_trips_count;    
	 echo '<br> Отмененные поездки: '. $cancelled_trips;    
	 
} else {
	 file_put_contents('wheely_import.sql', 'File has no data!', FILE_APPEND);
}
?>