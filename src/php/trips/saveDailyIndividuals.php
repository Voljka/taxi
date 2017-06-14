<?
	require ('../config/db.config.php');

    $params = json_decode(file_get_contents('php://input'),true);

    $RULE_60_40 = 2;
    $RULE_50_50 = 3;
    $RULE_40_60 = 4;
    $RULE_DEFAULT = 1;
    $MANUAL_BONUS = 6;
    $AUTO_BONUS = 5;

    $UBER = 1;
    $GETT = 2;

    $FINE_FROM_INCOME = 4;
    $DEBT_FROM_INCOME = 2;
    $RENT_FROM_INCOME = 3;
    $FRANCHISE_FROM_INCOME = 1;
    $CORRECTION_FROM_INCOME = 11;

    $COVERED_DEFICIT_BY_COMPANY = 1;

    $driver_id = $params['driver_id'];
    $debt = $params['debt'];
    $fine = $params['fine'];
    $fuel = $params['fuel'];
    $franchise = $params['franchise'];
    $rent = $params['rent'];
    $ya_cash = $params['ya_cash'];
    $ya_non_cash = $params['ya_non_cash'];
    $rbt_total = $params['rbt_total'];
    $referal_bonus = $params['referal_bonus'];
    $malyutka_total = $params['malyutka_total'];
    $rbt_comission = $params['rbt_comission'];
    $hand = $params['hand'];
    $is_bonus = $params['is_bonus'];

    $paid_by_cash = $params['paid_by_cash'];
    $company_fines = $params['company_fines'];
    $gett_month = $params['gett_month'];
    $admin_outcomes = $params['admin_outcomes'];

    $uber_bonus = $params['uber_bonus'];
    $uber_bonus_part = $params['uber_bonus_part'];

    $uber_correction = - $params['uber_correction'];
    $gett_correction = - $params['gett_correction'];

    $is_manual_bonus_day = $params['is_manual_bonus_day'];
    $total = $params['total'];
    $wage_rule = $params['wage_rule'];
    $shift = $params['dated'];
    $group_id = $params['group'];
    $covered = $params['covered'];
    $cover_note = $params['cover_note'];
    $deferred_debt = $params['deferred_debt'];
    $covered_company_deficit = $params['covered_company_deficit'];

	file_put_contents('save_daily_inds.sql', $wage_rule . "\n");

    $query = "SELECT id FROM daily_individual_cases ";
    $query .= " WHERE driver_id = $driver_id AND report_date = '$shift' ";

	$result = mysql_query($query) or die(mysql_error());

	if (mysql_num_rows($result) > 0) {
	    $query = "UPDATE daily_individual_cases SET ";
	    $query .= " report_date = '$shift',";
	    $query .= " driver_id = $driver_id, ";
	    $query .= " group_id = $group_id, ";
	    $query .= " total = $total, ";
	    $query .= " is_bonus = $is_bonus, ";
	    $query .= " referal_bonus = $referal_bonus, ";
	    $query .= " uber_bonus = $uber_bonus, ";
	    $query .= " paid_by_cash = $paid_by_cash, ";
	    $query .= " admin_outcomes = $admin_outcomes, ";
	    $query .= " company_fines = $company_fines, ";
	    $query .= " gett_month = $gett_month, ";
	    $query .= " uber_bonus_part = $uber_bonus_part, ";
	    $query .= " is_manual_bonus_day = $is_manual_bonus_day, ";
	    $query .= " deferred_debt = $deferred_debt, ";
	    $query .= " covered_company_deficit = $covered_company_deficit, ";
	    $query .= " fuel_expenses = $fuel, ";
	    
	    if ($wage_rule == $RULE_60_40 ) {
	    	$query .= "is_60_40 = 1, ";
	    	$query .= "is_50_50 = NULL, ";
	    	$query .= "is_40_60 = NULL ";
	    } else if ($wage_rule == $RULE_50_50 ) {
	    	$query .= "is_60_40 = NULL, ";
	    	$query .= "is_50_50 = 1, ";
	    	$query .= "is_40_60 = NULL ";
	    } else if ($wage_rule == $RULE_40_60 ) {
	    	$query .= "is_60_40 = NULL, ";
	    	$query .= "is_50_50 = NULL, ";
	    	$query .= "is_40_60 = 1 ";
	    } else if ($wage_rule == $RULE_DEFAULT ) {
	    	$query .= "is_60_40 = NULL, ";
	    	$query .= "is_50_50 = NULL, ";
	    	$query .= "is_40_60 = NULL ";
	    } else if ($wage_rule == $MANUAL_BONUS ) {
	    	$query .= "is_60_40 = NULL, ";
	    	$query .= "is_50_50 = NULL, ";
	    	$query .= "is_40_60 = NULL ";
	    } else if ($wage_rule == $AUTO_BONUS ) {
	    	$query .= "is_60_40 = NULL, ";
	    	$query .= "is_50_50 = NULL, ";
	    	$query .= "is_40_60 = NULL ";
	    };

	    $query .= " WHERE driver_id = $driver_id AND report_date = '$shift' ";

		file_put_contents('save_daily_inds.sql', $query . "\n", FILE_APPEND);

		$result = mysql_query($query) or die(mysql_error());

	} else {
		$query = "INSERT INTO daily_individual_cases ";
		$query .= "(driver_id, total, group_id, report_date, is_bonus, referal_bonus, uber_bonus, gett_month, admin_outcomes, company_fines, paid_by_cash, uber_bonus_part, is_manual_bonus_day, fuel_expenses, deferred_debt, covered_company_deficit, is_60_40, is_50_50, is_40_60) ";
		$query .= "VALUES (";

		$query .= "$driver_id,";
		$query .= "$total,";
		$query .= "$group_id,";
		$query .= "'$shift',";
		$query .= "$is_bonus,";
		$query .= "$referal_bonus,";
		$query .= "$uber_bonus,";
		$query .= "$gett_month,";
		$query .= "$admin_outcomes,";
		$query .= "$company_fines,";
		$query .= "$paid_by_cash,";
		$query .= "$uber_bonus_part,";
		$query .= "$is_manual_bonus_day,";
		$query .= "$fuel,";
		$query .= "$deferred_debt,";
		$query .= "$covered_company_deficit,";

	    if ($wage_rule == $RULE_60_40 ) {
	    	$query .= " 1, NULL, NULL ";
	    } else if ($wage_rule == $RULE_50_50 ) {
	    	$query .= " NULL, 1, NULL ";
	    } else if ($wage_rule == $RULE_40_60 ) {
	    	$query .= " NULL, NULL, 1 ";
	    } else if ($wage_rule == $RULE_DEFAULT ) {
	    	$query .= " NULL, NULL, NULL ";
	    } else if ($wage_rule == $MANUAL_BONUS ) {
	    	$query .= " NULL, NULL, NULL ";
	    } else if ($wage_rule == $AUTO_BONUS ) {
	    	$query .= " NULL, NULL, NULL ";
	    };

		$query .= ")";

		file_put_contents('save_daily_inds.sql', $query . "\n", FILE_APPEND);
		$result = mysql_query($query) or die(mysql_error());

		if ($is_bonus == 1 && $is_manual_bonus_day == 0) {
		    $query = "SELECT id FROM last_bonus_days ";
		    $query .= " WHERE driver_id = $driver_id ";

			$result = mysql_query($query) or die(mysql_error());		
			if (mysql_num_rows($result) > 0) {
				$query = "UPDATE last_bonus_days SET last_bonus_day='$shift' ";
				$query = " WHERE driver_id = $driver_id ";
				file_put_contents('save_daily_inds.sql', $query . "\n", FILE_APPEND);
			} else {
				$query = "INSERT INTO last_bonus_days (driver_id, last_bonus_day) ";
				$query = " VALUES ($driver_id, '$shift')";
				file_put_contents('save_daily_inds.sql', $query . "\n", FILE_APPEND);
			}
			$result = mysql_query($query) or die(mysql_error());
		}
	}

	// Rent
    $query = "SELECT id FROM driver_withdrawals ";
    $query .= " WHERE driver_id = $driver_id AND payed_at = '$shift' AND withdrawal_type_id = $RENT_FROM_INCOME";

	$result = mysql_query($query) or die(mysql_error());

	if (mysql_num_rows($result) > 0) {
	    $query = "UPDATE driver_withdrawals SET ";

	    $query .= " withdrawal_type_id = $RENT_FROM_INCOME,";
	    $query .= " driver_id = $driver_id,";
	    $query .= " payed_at = '$shift',";
	    $query .= " amount = $rent";

	    $query .= " WHERE driver_id = $driver_id AND payed_at = '$shift' ";

		file_put_contents('save_daily_inds.sql', $query . "\n", FILE_APPEND);
		$result = mysql_query($query) or die(mysql_error());
		
	} else {
		if ($rent > 0) {
			$query = "INSERT INTO driver_withdrawals ";
			$query .= "(withdrawal_type_id, driver_id, payed_at, amount) ";
			$query .= "VALUES (";

			$query .= "$RENT_FROM_INCOME, $driver_id, '$shift', $rent ";

			$query .= ")";

			file_put_contents('save_daily_inds.sql', $query . "\n", FILE_APPEND);
			$result = mysql_query($query) or die(mysql_error());
		}
	}

	// Uber correction
	if ($uber_correction < 0){
	    $query = "SELECT id FROM driver_withdrawals ";
	    $query .= " WHERE driver_id = $driver_id AND mediator_id = $UBER AND payed_at = '$shift' AND withdrawal_type_id = $CORRECTION_FROM_INCOME";

		$result = mysql_query($query) or die(mysql_error());

		if (mysql_num_rows($result) > 0) {
		    $query = "UPDATE driver_withdrawals SET ";

		    $query .= " withdrawal_type_id = $CORRECTION_FROM_INCOME,";
		    $query .= " driver_id = $driver_id,";
		    $query .= " mediator_id = $UBER,";
		    $query .= " payed_at = '$shift',";
		    $query .= " amount = $uber_correction";

		    $query .= " WHERE driver_id = $driver_id AND payed_at = '$shift' AND mediator_id = $UBER ";

			file_put_contents('save_daily_inds.sql', $query . "\n", FILE_APPEND);
			$result = mysql_query($query) or die(mysql_error());
			
		} else {
			if ($rent > 0) {
				$query = "INSERT INTO driver_withdrawals ";
				$query .= "(withdrawal_type_id, driver_id, payed_at, mediator_id, amount) ";
				$query .= "VALUES (";

				$query .= "$CORRECTION_FROM_INCOME, $driver_id, '$shift', $UBER , $uber_correction ";

				$query .= ")";

				file_put_contents('save_daily_inds.sql', $query . "\n", FILE_APPEND);
				$result = mysql_query($query) or die(mysql_error());
			}
		}
	}

	// Gett correction
	if ($gett_correction < 0){

	    $query = "SELECT id FROM driver_withdrawals ";
	    $query .= " WHERE driver_id = $driver_id AND mediator_id = $GETT AND payed_at = '$shift' AND withdrawal_type_id = $CORRECTION_FROM_INCOME";

		$result = mysql_query($query) or die(mysql_error());

		if (mysql_num_rows($result) > 0) {
		    $query = "UPDATE driver_withdrawals SET ";

		    $query .= " withdrawal_type_id = $CORRECTION_FROM_INCOME,";
		    $query .= " driver_id = $driver_id,";
		    $query .= " mediator_id = $GETT,";
		    $query .= " payed_at = '$shift',";
		    $query .= " amount = $gett_correction";

		    $query .= " WHERE driver_id = $driver_id AND payed_at = '$shift' AND mediator_id = $GETT ";

			file_put_contents('save_daily_inds.sql', $query . "\n", FILE_APPEND);
			$result = mysql_query($query) or die(mysql_error());
			
		} else {
			if ($rent > 0) {
				$query = "INSERT INTO driver_withdrawals ";
				$query .= "(withdrawal_type_id, driver_id, payed_at, mediator_id, amount) ";
				$query .= "VALUES (";

				$query .= "$CORRECTION_FROM_INCOME, $driver_id, '$shift', $GETT , $gett_correction ";

				$query .= ")";

				file_put_contents('save_daily_inds.sql', $query . "\n", FILE_APPEND);
				$result = mysql_query($query) or die(mysql_error());
			}
		}
	}

	// Fine
    $query = "SELECT id FROM driver_withdrawals ";
    $query .= " WHERE driver_id = $driver_id AND payed_at = '$shift' AND withdrawal_type_id = $FINE_FROM_INCOME";

	$result = mysql_query($query) or die(mysql_error());

	if (mysql_num_rows($result) > 0) {

	    $query = "UPDATE driver_withdrawals SET ";
	    $query .= " withdrawal_type_id = $FINE_FROM_INCOME,";
	    $query .= " driver_id = $driver_id,";
	    $query .= " payed_at = '$shift',";
	    $query .= " amount = $fine";

	    $query .= " WHERE driver_id = $driver_id AND payed_at = '$shift' ";


		file_put_contents('save_daily_inds.sql', $query . "\n", FILE_APPEND);
		$result = mysql_query($query) or die(mysql_error());

	} else {
		if ($fine > 0) {
			$query = "INSERT INTO driver_withdrawals ";
			$query .= "(withdrawal_type_id, driver_id, payed_at, amount) ";
			$query .= "VALUES (";

			$query .= "$FINE_FROM_INCOME, $driver_id, '$shift', $fine ";

			$query .= ")";

			file_put_contents('save_daily_inds.sql', $query . "\n", FILE_APPEND);
			$result = mysql_query($query) or die(mysql_error());
		}
	}

	// Debt
    $query = "SELECT id FROM driver_withdrawals ";
    $query .= " WHERE driver_id = $driver_id AND payed_at = '$shift' AND withdrawal_type_id = $DEBT_FROM_INCOME";

	$result = mysql_query($query) or die(mysql_error());

	if (mysql_num_rows($result) > 0) {
	    $query = "UPDATE driver_withdrawals SET ";
	    $query .= " withdrawal_type_id = $DEBT_FROM_INCOME,";
	    $query .= " driver_id = $driver_id,";
	    $query .= " payed_at = '$shift',";
	    $query .= " amount = $debt";
	    $query .= " WHERE driver_id = $driver_id AND payed_at = '$shift' ";

			file_put_contents('save_daily_inds.sql', $query . "\n", FILE_APPEND);
		$result = mysql_query($query) or die(mysql_error());

	} else {
		if ($debt > 0) {
			$query = "INSERT INTO driver_withdrawals ";
			$query .= "(withdrawal_type_id, driver_id, payed_at, amount) ";
			$query .= "VALUES (";

			$query .= "$DEBT_FROM_INCOME, $driver_id, '$shift', $debt ";

			$query .= ")";

			file_put_contents('save_daily_inds.sql', $query . "\n", FILE_APPEND);
			$result = mysql_query($query) or die(mysql_error());
		}
	}

	// Franchise
    $query = "SELECT id FROM driver_withdrawals ";
    $query .= " WHERE driver_id = $driver_id AND payed_at = '$shift' AND withdrawal_type_id = $FRANCHISE_FROM_INCOME";

	$result = mysql_query($query) or die(mysql_error());

	if (mysql_num_rows($result) > 0) {
	    $query = "UPDATE driver_withdrawals SET ";
	    $query .= " withdrawal_type_id = $FRANCHISE_FROM_INCOME,";
	    $query .= " driver_id = $driver_id,";
	    $query .= " payed_at = '$shift',";
	    $query .= " amount = $franchise";
	    $query .= " WHERE driver_id = $driver_id AND payed_at = '$shift' ";

			file_put_contents('save_daily_inds.sql', $query . "\n", FILE_APPEND);
		$result = mysql_query($query) or die(mysql_error());

	} else {
		if ($franchise > 0) {
			$query = "INSERT INTO driver_withdrawals ";
			$query .= "(withdrawal_type_id, driver_id, payed_at, amount) ";
			$query .= "VALUES (";

			$query .= "$FRANCHISE_FROM_INCOME, $driver_id, '$shift', $franchise ";

			$query .= ")";

			file_put_contents('save_daily_inds.sql', $query . "\n", FILE_APPEND);
			$result = mysql_query($query) or die(mysql_error());
		}
	}

	// yandex
    $query = "SELECT id FROM yandex_daily_data ";
    $query .= " WHERE driver_id = $driver_id AND trip_date = '$shift' ";

	$result = mysql_query($query) or die(mysql_error());

	if (mysql_num_rows($result) > 0) {
	    $query = "UPDATE yandex_daily_data SET ";
	    $query .= " driver_id = $driver_id,";
	    $query .= " trip_date = '$shift',";
	    $query .= " cash = $ya_cash,";
	    $query .= " non_cash = $ya_non_cash";

	    $query .= " WHERE driver_id = $driver_id AND trip_date = '$shift' ";

			file_put_contents('save_daily_inds.sql', $query . "\n", FILE_APPEND);
		$result = mysql_query($query) or die(mysql_error());

	} else {
		$query = "INSERT INTO yandex_daily_data ";
		$query .= "(driver_id, trip_date, cash, non_cash) ";
		$query .= "VALUES (";

		$query .= "$driver_id, '$shift', $ya_cash, $ya_non_cash ";

		$query .= ")";

		file_put_contents('save_daily_inds.sql', $query . "\n", FILE_APPEND);
		$result = mysql_query($query) or die(mysql_error());
	}

	// rbt
    $query = "SELECT id FROM rbt_data ";
    $query .= " WHERE driver_id = $driver_id AND shift_date = '$shift' ";

	$result = mysql_query($query) or die(mysql_error());

	if (mysql_num_rows($result) > 0) {
	    $query = "UPDATE rbt_data SET ";
	    $query .= " driver_id = $driver_id,";
	    $query .= " shift_date = '$shift',";
	    $query .= " total_brutto = $rbt_total,";
	    $query .= " comission = $rbt_comission ";

	    $query .= " WHERE driver_id = $driver_id AND shift_date = '$shift' ";

			file_put_contents('save_daily_inds.sql', $query . "\n", FILE_APPEND);
		$result = mysql_query($query) or die(mysql_error());

	} else {
		$query = "INSERT INTO rbt_data ";
		$query .= "(driver_id, shift_date, total_brutto, comission) ";
		$query .= "VALUES (";

		$query .= "$driver_id, '$shift', $rbt_total, $rbt_comission ";

		$query .= ")";

		file_put_contents('save_daily_inds.sql', $query . "\n", FILE_APPEND);
		$result = mysql_query($query) or die(mysql_error());
	}

	// malyutka
    $query = "SELECT id FROM malyutka ";
    $query .= " WHERE driver_id = $driver_id AND shift_date = '$shift' ";

	$result = mysql_query($query) or die(mysql_error());

	if (mysql_num_rows($result) > 0) {
	    $query = "UPDATE malyutka SET ";
	    $query .= " driver_id = $driver_id,";
	    $query .= " shift_date = '$shift',";
	    $query .= " total_brutto = $malyutka_total";

	    $query .= " WHERE driver_id = $driver_id AND shift_date = '$shift' ";

			file_put_contents('save_daily_inds.sql', $query . "\n", FILE_APPEND);
		$result = mysql_query($query) or die(mysql_error());

	} else {
		$query = "INSERT INTO malyutka ";
		$query .= "(driver_id, shift_date, total_brutto) ";
		$query .= "VALUES (";

		$query .= "$driver_id, '$shift', $malyutka_total ";

		$query .= ")";

		file_put_contents('save_daily_inds.sql', $query . "\n", FILE_APPEND);
		$result = mysql_query($query) or die(mysql_error());
	}


	// hand
    $query = "SELECT id FROM from_hand_trips_total ";
    $query .= " WHERE driver_id = $driver_id AND trip_date = '$shift' ";

	$result = mysql_query($query) or die(mysql_error());

	if (mysql_num_rows($result) > 0) {
	    $query = "UPDATE from_hand_trips_total SET ";
	    $query .= " driver_id = $driver_id,";
	    $query .= " trip_date = '$shift',";
	    $query .= " amount = $hand";

	    $query .= " WHERE driver_id = $driver_id AND trip_date = '$shift' ";


			file_put_contents('save_daily_inds.sql', $query . "\n", FILE_APPEND);
		$result = mysql_query($query) or die(mysql_error());

	} else {
		$query = "INSERT INTO from_hand_trips_total ";
		$query .= "(driver_id, trip_date, amount) ";
		$query .= "VALUES (";

		$query .= "$driver_id, '$shift', $hand ";

		$query .= ")";

		file_put_contents('save_daily_inds.sql', $query . "\n", FILE_APPEND);
		$result = mysql_query($query) or die(mysql_error());
	}

	// covered
    $query = "SELECT id FROM company_expenses ";
    $query .= " WHERE driver_id = $driver_id AND charged_at = '$shift' AND expense_type_id = $COVERED_DEFICIT_BY_COMPANY";

	$result = mysql_query($query) or die(mysql_error());

	if (mysql_num_rows($result) > 0) {

	    $query = "UPDATE company_expenses SET ";
	    $query .= " driver_id = $driver_id,";
	    $query .= " expense_type_id = $COVERED_DEFICIT_BY_COMPANY,";
	    $query .= " charged_at = '$shift',";
	    $query .= " note = '$cover_note',";
	    $query .= " amount = $covered";

	    $query .= " WHERE driver_id = $driver_id AND charged_at = '$shift' AND expense_type_id = $COVERED_DEFICIT_BY_COMPANY";


			file_put_contents('save_daily_inds.sql', $query . "\n", FILE_APPEND);
		$result = mysql_query($query) or die(mysql_error());
	} else {

		$query = "INSERT INTO company_expenses ";
		$query .= "(driver_id, charged_at, amount, expense_type_id, note) ";
		$query .= "VALUES (";

		$query .= "$driver_id, '$shift', $covered, $COVERED_DEFICIT_BY_COMPANY, '$cover_note' ";

		$query .= ")";

		file_put_contents('save_daily_inds.sql', $query . "\n", FILE_APPEND);
		$result = mysql_query($query) or die(mysql_error());
	}


	echo json_encode();
?>