<?
	require ('../config/db.config.php');

	function calc_current_debt_payment(){
		return $res;
	}

	function calc_current_franchise_payment(){
		return $res;
	}

	function calc_current_fine_payment(){
		return $res;
	}

	function get_debt_until_date($finish_date, $driver){
		global $debts;
		$res = array();
		$total_debt = 0;
		$current_debt = undefined;

		// print_r($debts);

		for ($p = 0; $p < count($debts); $p++){
			if ($debts[$p]['fined_at'] <= $finish_date && $debts[$p]['driver_id'] == $driver) {
					$total_debt += $debts[$p]['total_sum'];
					$current_debt = $debts[$p];
			}
		}

		$res['current_debt'] = $current_debt;
		$res['total_debts'] = $total_debt;

		return $res;
	}

	function get_fines_until_date($finish_date, $driver){
		global $fines;
		$res = array();
		$total_fines = 0;

		// print_r($debts);

		for ($p = 0; $p < count($fines); $p++){
			if ($fines[$p]['inputed_at'] <= $finish_date && $fines[$p]['driver_id'] == $driver) {
					$total_fines += $fines[$p]['fine_amount'];
			}
		}

		$res = $total_fines;

		return $res;
	}

	function get_debts_payed_until_date($finish_date, $driver){
		global $debts_payed;
		$res = 0;

		for ($p = 0; $p < count($debts_payed); $p++){
			if ($debts_payed[$p]['payed_at'] < $finish_date && $debts_payed[$p]['driver_id'] == $driver) {
				$res += $debts_payed[$p]['amount'];
			}
		}

		return $res;
	}

	function get_fines_payed_until_date($finish_date, $driver){
		global $fines_payed;
		$res = 0;

		for ($p = 0; $p < count($fines_payed); $p++){
			if ($fines_payed[$p]['payed_at'] <= $finish_date && $fines_payed[$p]['driver_id'] == $driver) {
				$res += $fines_payed[$p]['amount'];
			}
		}

		return $res;
	}

	function get_payed_franchise_until_date($finish_date, $driver){
		global $franchises_payed, $franchises_withdrawals;
		
		$fr_payments = 0;
		$fr_withdrawals = 0;

		for ($p = 0; $p < count($franchises_payed); $p++){
			if ($franchises_payed[$p]['payed_at'] <= $finish_date && $franchises_payed[$p]['driver_id'] == $driver) {
				$fr_payments += $franchises_payed[$p]['amount'];
			}
		}

		for ($p = 0; $p < count($franchises_withdrawals); $p++){
			if ($franchises_withdrawals[$p]['payed_at'] <= $finish_date && $franchises_withdrawals[$p]['driver_id'] == $driver) {
				$fr_payments += $franchises_withdrawals[$p]['amount'];
			}
		}

		$plan = ($fr_payments - $fr_withdrawals);

		return $plan;
	}

	function get_daily_franchise($for_date){
		global $franchise_rules;

		for ($p = 0; $p < count($franchise_rules); $p++){

			if ($franchise_rules[$p]['started_at'] <= $for_date && (!$franchise_rules[$p]['ended_at'] || $franchise_rules[$p]['ended_at'] >= $for_date)) {
				$res = $franchise_rules[$p];
			}
		}

		return $res;
	}

	file_put_contents('tmp.res', "");
	file_put_contents('111.res', "");

    $params = json_decode(file_get_contents('php://input'),true);

    $start_date = $params['start'];
    $end_date = $params['end'];

    $OUR1_1 = 2;

    $MISC_PAYMENT = 2;

    $UBER = 1;
    $GETT = 2;

    // withdrawal types
    $FINE_FROM_INCOME = 6;
    $FINE_FROM_FRANCHISE = 4;

    $DEBT_FROM_INCOME = 2;
    $DEBT_FROM_FRANCHISE = 7;

    $RENT_FROM_INCOME = 3;
    $RENT_FROM_FRANCHISE = 5;

    $FRANCHISE_FROM_INCOME = 1;

    $EXPENSES_COVERED_BY_COMPANY = 1;

	// get array of franchise rules
	$query = "SELECT * FROM franchise_rules";
	$result = mysql_query($query) or die(mysql_error());
	
	$franchise_rules = array();

	while ($row = mysql_fetch_assoc($result)) 
	{
		$franchise_rules[] = $row;
	};

	// get array of fines
	$query = "SELECT road_fines.*, shifts.driver_id FROM road_fines ";

	$query .= " LEFT JOIN shifts ON road_fines.auto_id = shifts.auto_id AND road_fines.fined_at BETWEEN (CONCAT(shifts.shift_date, ' 12:00:00')) AND CONCAT(DATE_ADD(shifts.shift_date, INTERVAL 1 DAY), ' 11:59:59') ";

	$query .= " WHERE inputed_at <= '$end_date'";
	$result = mysql_query($query) or die(mysql_error());
	$fines = array();
	
	while ($row = mysql_fetch_assoc($result)) 
	{
		$fines[] = $row;
	};

	// get array of fine payments
	$query = "SELECT * FROM driver_withdrawals ";
	$query .= " WHERE withdrawal_type_id IN ($FINE_FROM_FRANCHISE, $FINE_FROM_INCOME) AND payed_at <='$end_date' ";

	$result = mysql_query($query) or die(mysql_error());
	$fines_payed = array();
	
	while ($row = mysql_fetch_assoc($result)) 
	{
		$fines_payed[] = $row;
	};

	// get array of debts
	$query = "SELECT * FROM admin_fines ";

	$query .= " LEFT JOIN admin_fine_rule_types ON admin_fines.rule_type_id = admin_fine_rule_types.id ";

	$query .= " WHERE fined_at <= '$end_date'";

//	file_put_contents("select_our1_1.sql", $query);
	$result = mysql_query($query) or die(mysql_error());

	$debts = array();
	
	while ($row = mysql_fetch_assoc($result)) 
	{
		$debts[] = $row;
	};

	file_put_contents('debt_request.sql', $query . "\n");

	// get array of debts payments
	$query = "SELECT * FROM driver_withdrawals ";
	$query .= " WHERE withdrawal_type_id IN ($DEBT_FROM_FRANCHISE, $DEBT_FROM_INCOME) AND payed_at <='$end_date' ";

	$result = mysql_query($query) or die(mysql_error());
	$debts_payed = array();
	
	while ($row = mysql_fetch_assoc($result)) 
	{
		$debts_payed[] = $row;
	};
	file_put_contents('debt_request.sql', $query . "\n", FILE_APPEND);

	// get array of franchise payments
	$query = "SELECT * FROM driver_withdrawals ";
	$query .= " WHERE withdrawal_type_id IN ($FRANCHISE_FROM_INCOME) AND payed_at <='$end_date' ";

	$result = mysql_query($query) or die(mysql_error());
	$franchises_payed = array();
	
	while ($row = mysql_fetch_assoc($result)) 
	{
		$franchises_payed[] = $row;
	};

	// get array of franchise wtthdrawals
	$query = "SELECT * FROM driver_withdrawals ";
	$query .= " WHERE withdrawal_type_id IN ($DEBT_FROM_FRANCHISE, $FINE_FROM_FRANCHISE, $RENT_FROM_FRANCHISE) AND payed_at <='$end_date' ";

	$result = mysql_query($query) or die(mysql_error());
	$franchises_withdrawals = array();
	
	while ($row = mysql_fetch_assoc($result)) 
	{
		$franchises_withdrawals[] = $row;
	};

	/* Таблица MySQL, в которой хранятся данные */
	$table = "trips";

	$current_start_date = substr($start_date, 0, 10) . ' 12:00:00';
	$current_end_date = date("Y-m-d", strtotime($start_date) + 24*60*60) . ' 11:59:59';

	$totalRespond = array();

	$debt_payments_calced = array();
	$franchise_payments_calced = array();
	$fines_payments_calced = array();

	// Loop by selected date range
	for ($j = 1; $j < 8; $j++) {
		$franchise_daily_rules = get_daily_franchise(substr($current_start_date,0,10));
		// print_r($franchise_daily_rules);

		$query = "SELECT drivers.id, drivers.work_type_id, drivers.surname, drivers.card_number, banks.name bank_name, drivers.beneficiar, drivers.firstname, drivers.patronymic, work_types.name group_name, own_auto_park.state_number, own_auto_park.is_rented, auto_rental_costs.cost_daily rental_daily_cost, daily_reports.report_date, yandex_daily_data.cash yandex_cash, yandex_daily_data.non_cash yandex_non_cash, daily_individual_cases.is_60_40 uniq_is60_40, daily_individual_cases.is_50_50 uniq_is50_50, daily_individual_cases.is_40_60 uniq_is40_60, daily_individual_cases.fuel_expenses, daily_individual_cases.covered_company_deficit, daily_individual_cases.deferred_debt, fine_fran.fine_from_franchise, fine_inc.fine_from_income, debt_fran.debt_from_franchise, debt_inc.debt_from_income, fran_inc.fran_from_income, rent_fran.rent_from_franchise, rent_inc.rent_from_income, from_hand_trips_total.amount from_hand_amount, uber_completed_imports.import_for_date uber_completeness, gett_completed_imports.import_for_date gett_completeness, drivers.rule_default_id, rule_defaults.name rule_default_name, rule_defaults.driver_part, company_expenses.amount payed_by_company, payouts1.total_amount, gett_data.gett_sum_fare, gett_data.gett_sum_comission, gett_data.gett_sum_cash, gett_data.gett_sum_result, uber_data.uber_sum_fare, uber_data.uber_sum_comission, uber_data.uber_sum_cash, uber_data.uber_sum_result, gett_corrections.gett_correction_fare, gett_corrections.gett_correction_comission, gett_corrections.gett_correction_cash, gett_corrections.gett_correction_result, uber_corrections.uber_correction_fare, uber_corrections.uber_correction_comission, uber_corrections.uber_correction_cash, uber_corrections.uber_correction_result, rbt_data.total_brutto rbt_total, rbt_data.comission rbt_comission ";

		$query .= " FROM drivers ";

		// Uber adjustments
		$query .= "LEFT JOIN (
						SELECT 
							driver_id, SUM(trips.fare) uber_correction_fare, SUM(trips.comission) uber_correction_comission, 
								SUM(trips.cash) uber_correction_cash, 
								SUM(trips.result) uber_correction_result ";
		$query .= "FROM trips	";
		$query .= "	LEFT JOIN drivers ON drivers.id = trips.driver_id 
					LEFT JOIN work_types ON work_types.id = drivers.work_type_id ";
		$query .= "WHERE trips.payment_type_id=$MISC_PAYMENT AND trips.mediator_id=$UBER AND work_types.id = $OUR1_1 AND trips.driver_id= drivers.id AND drivers.active = 1 AND date_time BETWEEN '$current_start_date' AND '$current_end_date' ";

		$query .= "	GROUP BY trips.driver_id 
					) AS uber_corrections ";
		$query .= "ON uber_corrections.driver_id = drivers.id ";

		// Gett adjustments
		$query .= "LEFT JOIN (
						SELECT 
							driver_id, SUM(trips.fare) gett_correction_fare, SUM(trips.comission) gett_correction_comission, 
								SUM(trips.cash) gett_correction_cash, 
								SUM(trips.result) gett_correction_result ";
		$query .= "FROM trips	";
		$query .= "	LEFT JOIN drivers ON drivers.id = trips.driver_id 
					LEFT JOIN work_types ON work_types.id = drivers.work_type_id ";
		$query .= "WHERE trips.payment_type_id=$MISC_PAYMENT AND trips.mediator_id=$GETT AND work_types.id = $OUR1_1 AND trips.driver_id= drivers.id AND drivers.active = 1 AND date_time BETWEEN '$current_start_date' AND '$current_end_date' ";

		$query .= "	GROUP BY trips.driver_id 
					) AS gett_corrections ";
		$query .= "ON gett_corrections.driver_id = drivers.id ";

		// Gett total data
		$query .= "LEFT JOIN (
						SELECT 
							driver_id, SUM(trips.fare) gett_sum_fare, SUM(trips.comission) gett_sum_comission, 
								SUM(trips.cash) gett_sum_cash, 
								SUM(trips.result) gett_sum_result ";
		$query .= "FROM trips	";
		$query .= "	LEFT JOIN drivers ON drivers.id = trips.driver_id 
					LEFT JOIN work_types ON work_types.id = drivers.work_type_id ";
		$query .= "WHERE trips.payment_type_id<>$MISC_PAYMENT AND trips.mediator_id=$GETT AND trips.driver_id= drivers.id AND work_types.id = $OUR1_1 AND drivers.active = 1 AND date_time BETWEEN '$current_start_date' AND '$current_end_date' ";

		$query .= "	GROUP BY trips.driver_id 
					) AS gett_data ";
		$query .= "ON gett_data.driver_id = drivers.id ";

		// Uber total data
		$query .= "LEFT JOIN (
						SELECT 
							driver_id, SUM(trips.fare) uber_sum_fare, SUM(trips.comission) uber_sum_comission, 
								SUM(trips.cash) uber_sum_cash, 
								SUM(trips.result) uber_sum_result ";
		$query .= "FROM trips	";
		$query .= "	LEFT JOIN drivers ON drivers.id = trips.driver_id 
					LEFT JOIN work_types ON work_types.id = drivers.work_type_id ";
		$query .= "WHERE trips.payment_type_id<>$MISC_PAYMENT AND trips.mediator_id=$UBER AND trips.driver_id= drivers.id AND work_types.id = $OUR1_1 AND drivers.active = 1 AND date_time BETWEEN '$current_start_date' AND '$current_end_date' ";

		$query .= "	GROUP BY trips.driver_id 
					) AS uber_data ";
		$query .= "ON uber_data.driver_id = drivers.id ";

		// Default Rule
		$query .= "LEFT JOIN rule_defaults ON drivers.rule_default_id = rule_defaults.id ";
		
		// Shift
		$query .= "LEFT JOIN shifts ON shifts.driver_id = drivers.id ";

		// Auto 
		$query .= "LEFT JOIN own_auto_park ON shifts.auto_id = own_auto_park.id AND shifts.shift_date='" .substr($current_start_date,0,10) ."' ";

		// Rental cost
		$query .= "LEFT JOIN auto_rental_costs ON shifts.auto_id = auto_rental_costs.auto_id AND auto_rental_costs.started_at <='". substr($current_start_date,0,10) ."' AND (auto_rental_costs.ended_at IS NULL OR auto_rental_costs.ended_at >='". substr($current_start_date,0,10) ."') ";

		// Group
		$query .= "LEFT JOIN work_types ON work_types.id = drivers.work_type_id ";

		// Fines payed from franchise account
		$query .= "LEFT JOIN (";
		$query .= 		"SELECT driver_id, SUM(amount) fine_from_franchise  ";
		$query .=			"FROM `driver_withdrawals` ";
		$query .= 		"WHERE payed_at ='". substr($current_start_date,0,10) ."' AND withdrawal_type_id = $FINE_FROM_FRANCHISE ";
		$query .= 		" GROUP BY driver_id, payed_at "; ////////
		$query .= 	") AS fine_fran ";
		$query .= 	"ON fine_fran.driver_id = drivers.id ";

		// Fines payed from day income
		$query .= "LEFT JOIN (";
		$query .= 		"SELECT driver_id, SUM(amount) fine_from_income  ";
		$query .=			"FROM `driver_withdrawals` ";
		$query .= 		"WHERE payed_at ='". substr($current_start_date,0,10) ."' AND withdrawal_type_id = $FINE_FROM_INCOME ";
		$query .= 		" GROUP BY driver_id, payed_at "; ////////
		$query .= 	") AS fine_inc ";

		$query .= 	"ON fine_inc.driver_id = drivers.id ";

		// Debt payed from franchise account
		$query .= "LEFT JOIN (";
		$query .= 		"SELECT driver_id, SUM(amount) debt_from_franchise  ";
		$query .=			"FROM `driver_withdrawals` ";
		$query .= 		"WHERE payed_at ='". substr($current_start_date,0,10) ."' AND withdrawal_type_id = $DEBT_FROM_FRANCHISE ";
		$query .= 		" GROUP BY driver_id, payed_at "; ////////
		$query .= 	") AS debt_fran ";
		$query .= 	"ON debt_fran.driver_id = drivers.id ";

		// Debt payed from day income
		$query .= "LEFT JOIN (";
		$query .= 		"SELECT driver_id, SUM(amount) debt_from_income  ";
		$query .=			"FROM `driver_withdrawals` ";
		$query .= 		"WHERE payed_at ='". substr($current_start_date,0,10) ."' AND withdrawal_type_id = $DEBT_FROM_INCOME ";
		$query .= 		" GROUP BY driver_id, payed_at "; ////////
		$query .= 	") AS debt_inc ";
		$query .= 	"ON debt_inc.driver_id = drivers.id ";

		// Franchise payed from day income
		$query .= "LEFT JOIN (";
		$query .= 		"SELECT driver_id, SUM(amount) fran_from_income  ";
		$query .=			"FROM `driver_withdrawals` ";
		$query .= 		"WHERE payed_at ='". substr($current_start_date,0,10) ."' AND withdrawal_type_id = $FRANCHISE_FROM_INCOME";
		$query .= 		" GROUP BY driver_id, payed_at "; ////////
		$query .= 	") AS fran_inc ";
		$query .= 	"ON fran_inc.driver_id = drivers.id ";

		// Rental payed from franchise account
		$query .= "LEFT JOIN (";
		$query .= 		"SELECT driver_id, SUM(amount) rent_from_franchise  ";
		$query .=			"FROM `driver_withdrawals` ";
		$query .= 		"WHERE payed_at ='". substr($current_start_date,0,10) ."' AND withdrawal_type_id = $RENT_FROM_FRANCHISE ";
		$query .= 		" GROUP BY driver_id, payed_at "; ////////
		$query .= 	") AS rent_fran ";
		$query .= 	"ON rent_fran.driver_id = drivers.id ";

		// Rental payed from day income
		$query .= "LEFT JOIN (";
		$query .= 		"SELECT driver_id, SUM(amount) rent_from_income  ";
		$query .=			"FROM `driver_withdrawals` ";
		$query .= 		"WHERE payed_at ='". substr($current_start_date,0,10) ."' AND withdrawal_type_id = $RENT_FROM_INCOME ";
		$query .= 		" GROUP BY driver_id, payed_at "; ////////
		$query .= 	") AS rent_inc ";
		$query .= 	"ON rent_inc.driver_id = drivers.id ";

		// Yandex Taximeter data
		$query .= "LEFT JOIN yandex_daily_data ON yandex_daily_data.trip_date='" . substr($current_start_date,0,10) . "' AND yandex_daily_data.driver_id = drivers.id ";

		// RBT data
		$query .= "LEFT JOIN rbt_data ON rbt_data.shift_date='" . substr($current_start_date,0,10) . "' AND rbt_data.driver_id = drivers.id ";

		// Company expenses for a driver (for complete reports)
		$query .= "LEFT JOIN company_expenses ON company_expenses.charged_at='" . substr($current_start_date,0,10) . "' AND company_expenses.driver_id IS NOT NULL AND company_expenses.driver_id = drivers.id AND company_expenses.expense_type_id=$EXPENSES_COVERED_BY_COMPANY ";

		// Amount of the trips from hand
		$query .= "LEFT JOIN from_hand_trips_total ON from_hand_trips_total.trip_date='" . substr($current_start_date,0,10) . "' AND from_hand_trips_total.driver_id = drivers.id ";

		// Individual options 
		$query .= "LEFT JOIN daily_individual_cases ON daily_individual_cases.report_date='" . substr($current_start_date,0,10) . "' AND daily_individual_cases.driver_id = drivers.id AND daily_individual_cases.group_id=$OUR1_1 ";

		// Info about daily report
		$query .= "LEFT JOIN daily_reports ON daily_reports.report_date='" . substr($current_start_date,0,10) . "' ";

		// Uber completed report status
		$query .= "LEFT JOIN uber_completed_imports ON uber_completed_imports.import_for_date='" . substr($current_start_date,0,10) . "' ";

		// Uber completed report status
		$query .= "LEFT JOIN gett_completed_imports ON gett_completed_imports.import_for_date='" . substr($current_start_date,0,10) . "' ";

		// Payouts
		$query .= "LEFT JOIN ( SELECT driver_id, report_date, SUM(amount) total_amount FROM payouts GROUP BY report_date, driver_id) AS payouts1 ON payouts1.report_date='" . substr($current_start_date,0,10) . "' AND payouts1.driver_id = drivers.id ";

		// Bank 
		$query .= "LEFT JOIN banks ON drivers.bank_id=banks.id ";


	    $query .= " WHERE uber_data.uber_sum_fare IS NOT NULL OR gett_data.gett_sum_fare IS NOT NULL OR gett_corrections.gett_correction_fare IS NOT NULL OR uber_corrections.uber_correction_fare IS NOT NULL OR yandex_daily_data.cash IS NOT NULL OR yandex_daily_data.non_cash IS NOT NULL OR from_hand_trips_total.amount IS NOT NULL ";	    
	    $query .= " GROUP BY drivers.id ";
	    $query .= " ORDER BY drivers.surname, drivers.firstname, drivers.patronymic";

		file_put_contents("select_our1_1.sql", $query);

		$result = mysql_query($query) or die(mysql_error());

		$respond = array();
		
		// file_put_contents('111.res', substr($current_start_date,0,10) . "\n", FILE_APPEND);
		while ($row = mysql_fetch_assoc($result)) 
		{
			// if ($row['uber_completeness'] && $row['gett_completeness']) {
			// file_put_contents('111.res', $row['id'] . ' ' . floatval($row['fuel_expenses']) , FILE_APPEND);
			if (floatval($row['fuel_expenses']) > 0) {
				// file_put_contents('111.res', $row['id'] . ' out' . "\n", FILE_APPEND);

			} else {
				// file_put_contents('111.res', $row['id'] . ' in' . "\n", FILE_APPEND);
			// Autocalc debt
				$driver = $row['id'];
				file_put_contents('tmp.res', "\n $driver_id = " . $driver . ' DATE : ' . substr($current_start_date,0,10) .  "\n", FILE_APPEND);
				$debt_info = get_debt_until_date(substr($current_start_date,0,10), $driver);
				$driver_debts = $debt_info['total_debts'];
				$driver_last_debt = $debt_info['current_debt'];

				$debt_already_payed = get_debts_payed_until_date(substr($current_start_date,0,10), $driver);

				$already_calced_debt = $debt_payments_calced[$driver] ? $debt_payments_calced[$driver] : 0;

				file_put_contents('tmp.res', '$driver_debts = ' . $driver_debts . "\n", FILE_APPEND);
				file_put_contents('tmp.res', '$debt_already_payed = ' . $debt_already_payed . "\n", FILE_APPEND);
				file_put_contents('tmp.res', '$already_calced_debt = ' . $already_calced_debt . "\n", FILE_APPEND);

				if ($driver_debts > ($debt_already_payed + $already_calced_debt)) {
					$residual_payment = $driver_debts - $debt_already_payed - $already_calced_debt;
					file_put_contents('tmp.res', '$residual_payment = ' . $residual_payment . "\n", FILE_APPEND);

					$row['debt_planned_payment'] = ($residual_payment > $driver_last_debt['iteration_sum']) ? $driver_last_debt['iteration_sum'] : $residual_payment;
					$row['debt_min_wage'] = $driver_last_debt['min_daily_wage'];
					$row['debt_residual'] = $residual_payment;
					$row['debt_all_as_debt'] = $driver_last_debt['is_total_income_as_fine'];

					$row['last_debt'] = $driver_last_debt;
					$debt_payments_calced[$driver] = $debt_payments_calced[$driver] ? $debt_payments_calced[$driver] + $row['debt_planned_payment'] : $row['debt_planned_payment'];
				}

			// Autocalc fine
				$already_payed_fines = get_fines_payed_until_date(substr($current_start_date,0,10), $driver);
				$fines_charged = get_fines_until_date(substr($current_start_date,0,10), $driver);
				$already_calced_fines = $fines_payments_calced[$driver] ? $fines_payments_calced[$driver] : 0;

				file_put_contents('tmp.res', '$already_payed_fines = ' . $already_payed_fines, FILE_APPEND);
				file_put_contents('tmp.res', '$already_calced_fines = ' . $already_calced_fines, FILE_APPEND);

				if ($fines_charged > ($already_payed_fines + $already_calced_fines)) {
					$residual_payment = $fines_charged - $already_payed_fines - $already_calced_fines;

					$row['fine_planned_payment'] = $residual_payment;
					$fines_payments_calced[$driver] = $fines_payments_calced[$driver] ? $fines_payments_calced[$driver] + $row['fine_planned_payment'] : $row['fine_planned_payment'];
				}

			// Autocalc franchise
				$already_payed_franchise = get_payed_franchise_until_date(substr($current_start_date,0,10), $driver);
				$already_calced_franchise = $franchise_payments_calced[$driver] ? $franchise_payments_calced[$driver] : 0;

				file_put_contents('tmp.res', '$already_payed_franchise = ' . $already_payed_franchise  . "\n", FILE_APPEND);
				file_put_contents('tmp.res', '$already_calced_franchise = ' . $already_calced_franchise  . "\n", FILE_APPEND);
				file_put_contents('tmp.res', "franchise_daily_rules['daily_total_amount'] = " . $franchise_daily_rules['daily_total_amount'] . "\n", FILE_APPEND);

				if (($already_payed_franchise + $already_calced_franchise) < $franchise_daily_rules['daily_total_amount']) {

						$residual_payment = $franchise_daily_rules['daily_total_amount'] - $already_payed_franchise - $already_calced_franchise;
						file_put_contents('tmp.res', '$residual_franchise = ' . $residual_payment . "\n", FILE_APPEND);

						$row['franchise_planned_payment'] = ($residual_payment > $franchise_daily_rules['sum_for_daily']) ? $franchise_daily_rules['sum_for_daily'] : $residual_payment;
						$franchise_payments_calced[$driver] = $franchise_payments_calced[$driver] ? $franchise_payments_calced[$driver] + $row['franchise_planned_payment'] : $row['franchise_planned_payment'];
				} else {
					$row['franchise_planned_payment'] = 0;
				}
			}

			$respond[] = $row;
		};

		$totalRespond[substr($current_start_date,0,10)] = $respond;

		$current_start_date = date("Y-m-d", strtotime($start_date) + $j*24*60*60) . ' 12:00:00';
		$current_end_date = date("Y-m-d", strtotime($start_date) + ($j + 1)*24*60*60) . ' 11:59:59';

		if (substr($current_start_date, 0, 10) == $end_date) {
			break;
		}
	}

	$query = "SELECT MAX(report_date) last_daily_report_date FROM daily_reports";
	$result = mysql_query($query) or die(mysql_error());

	while ($row = mysql_fetch_assoc($result)) 
	{
		$last = $row['last_daily_report_date'];
	}

	$full_respond['data'] = $totalRespond;
	$full_respond['last'] = $last;

	//echo json_encode($totalRespond);
	echo json_encode($full_respond);
?>