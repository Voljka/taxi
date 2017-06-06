<?php
require_once('func.inc.php');

const URL_BASE_STATEMENTS="https://partners.uber.com/p3/money/statements/index";

$is_current = $_POST["is_current"];

// touch it!
GetURL(URL_BASE_STATEMENTS,$aRes);

if ($is_current == 1) {
	$URL_STATEMENTS="https://partners.uber.com/p3/money/statements/view/current";

} else {
	$report_id = getPreviousUberReportId($aRes);
	$URL_STATEMENTS="https://partners.uber.com/p3/money/statements/view/$report_id";
}

echo $URL_STATEMENTS;

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

?>