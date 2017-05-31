'use strict';

import { map, filter, isDate, assign, groupBy, omit, find, isEmpty, sumBy } from 'lodash';
import { datePlusDays, formattedToSave, daysBetween, treatAsUTC } from '../../libs/date';
import { CALC_WAGE_BY_COMMON_RULE } from '../../constants/common';

function DailyCtrl($scope, $state, data, TripService, PayoutService, DebtService, Flash) {

    Flash.clear();

    $scope.payouts = [];
    $scope.totalPayouts = [];
    $scope.residualToPay = 0;

    var message, flashWindow;

    var counter = 10000000000;

    function getCounter(){
      return (counter++);
    }

    function fuelByTotal (total) {
        var res;

        switch (true) {
            case (total < 7500) : 
                res = 1200;
                break;
            case (total < 8000) : 
                res = 1250;
                break;
            case (total < 8500) : 
                res = 1300;
                break;
            case (total < 9000) : 
                res = 1400;
                break;
            case (total < 9500) : 
                res = 1500;
                break;
            case (total < 11000) : 
                res = 1650;
                break;
            case (total < 12000) : 
                res = 1850;
                break;
            case (total < 14000) : 
                res = 2200;
                break;
            // case (total < 16000) : 
            //     res = 2500;
            //     break;
            default : 
                res = 2500;
                break;
        }

        return res;
    }

    $scope.hasSelectedRow = false;
    
    var tripList = data.data;
    $scope.lastReport = data.last;

    function adjustData(){

       map(tripList, function(obj) {
           return map(obj, function(o){

               o.total_payouts = Number(o.total_amount);
               o.uber_bonus = o.uber_bonus ? Number(o.uber_bonus) : 0;
               o.uber_bonus_part = o.uber_bonus_part ? Number(o.uber_bonus_part) : 0;

               o.referal_bonus = o.referal_bonus ? Number(o.referal_bonus) : 0;
               o.prepay = o.prepay ? Number(o.prepay) : 0;

               o.paid_by_cash = Number(o.paid_by_cash);
               o.admin_outcomes = Number(o.admin_outcomes);
               o.company_fines = Number(o.company_fines);
               o.gett_month = Number(o.gett_month);

               o.uber_sum_comission = o.uber_sum_comission ? Number(o.uber_sum_comission) : 0;
               o.uber_sum_fare = Number(o.uber_sum_fare);
               o.uber_sum_result = Number(o.uber_sum_result);
               o.uber_sum_cash = Number(o.uber_sum_cash);
               o.uber_sum_boost = Number(o.uber_sum_boost);

               o.uber_correction_comission = o.uber_correction_comission ? Number(o.uber_correction_comission) : 0;
               o.uber_correction_fare = o.uber_correction_fare ? Number(o.uber_correction_fare) : 0;
               o.uber_correction_result = o.uber_correction_result ? Number(o.uber_correction_result) : 0;
               o.uber_correction_cash = o.uber_correction_cash ? Number(o.uber_correction_cash) : 0;

               o.deferred_debt = Number(o.deferred_debt);

               o.gett_sum_comission = o.gett_sum_comission ? Number(o.gett_sum_comission) : 0;
               o.gett_sum_fare = Number(o.gett_sum_fare);
               o.gett_sum_result = Number(o.gett_sum_result);
               o.gett_sum_cash = Number(o.gett_sum_cash);
               o.gett_sum_boost = Number(o.gett_sum_boost);

               o.gett_correction_comission = o.gett_correction_comission ? Number(o.gett_correction_comission) : 0;
               o.gett_correction_fare = o.gett_correction_fare ? Number(o.gett_correction_fare) : 0;
               o.gett_correction_result = o.gett_correction_result ? Number(o.gett_correction_result) : 0;
               o.gett_correction_cash = o.gett_correction_cash ? Number(o.gett_correction_cash) : 0;

               o.yandex_cash = o.yandex_cash ? Number(o.yandex_cash) : 0; 
               o.yandex_non_cash = o.yandex_non_cash ? Number(o.yandex_non_cash) : 0;

               o.rbt_total = o.rbt_total ? Number(o.rbt_total) : 0; 
               o.rbt_comission = o.rbt_comission ? Number(o.rbt_comission) : 0;

               o.malyutka_total = o.malyutka_total ? Number(o.malyutka_total) : 0; 
               o.malyutka_netto = o.malyutka_total * 0.8; 

               o.from_hand_amount = Number(o.from_hand_amount);
               o.fine_planned_payment = o.fine_planned_payment ? Number(o.fine_planned_payment) : 0;

               o.uber_total = o.uber_sum_fare  + o.uber_sum_boost;
               o.uber_total_netto = o.uber_sum_fare + o.uber_sum_comission + o.uber_sum_boost; 

               o.gett_total = o.gett_sum_fare + o.gett_sum_comission;
               o.gett_total_netto = o.gett_total * 0.8;
               o.gett_left_on_account = o.gett_sum_fare + o.gett_sum_comission - o.gett_sum_cash;

               o.yandex_total = o.yandex_non_cash + o.yandex_cash;
               o.yandex_total_netto = Number(((o.yandex_non_cash + o.yandex_cash) *0.8).toFixed(2));

               o.covered_company_deficit = o.covered_company_deficit ? Number(o.covered_company_deficit) : 0;

               if (! o.rule_default_id) {
                  o.rule_default_id = String(CALC_WAGE_BY_COMMON_RULE);
               }

               o.covered = o.payed_by_company ? Number(o.payed_by_company) : 0;

               o.franchise = o.fuel_expenses ? Number(o.fran_from_income) : Number(o.franchise_planned_payment);
               o.fine = o.fuel_expenses ? Number(o.fine_from_income) + Number(o.fine_from_franchise) : Number(o.fine_planned_payment);
               o.rental = o.fuel_expenses ? Number(o.rent_from_income) + Number(o.rent_from_franchise) : (o.is_rented ? Number(o.rental_daily_cost) : 0);

               var common_wage_rules = o.rule_default_id == CALC_WAGE_BY_COMMON_RULE;

               if (o.fuel_expenses) {
                   if (o.uniq_is60_40 == 1) {
                       console.log(o.id, '60/40'); 
                       o.driver_part = 0.6;
                       o.rule_default_id = "2";

                   } else {
                       if (o.uniq_is50_50 == 1) {
                           o.driver_part = 0.5;
                           o.rule_default_id = "3";
                             console.log(o.id, '50/50'); 
                       } else {
                           if (o.uniq_is40_60 == 1) {
                               o.driver_part = 0.4;
                               o.rule_default_id = "4";
                             console.log(o.id, '40/60'); 
                           } else {
                             if (o.is_bonus == 1 && o.is_manual_bonus_day == 0) {
                                 o.rule_default_id = "5";
                                console.log(o.id, 'auto bonus rule'); 
                             } else {
                               if (o.is_bonus == 1 && o.is_manual_bonus_day == 1) {
                                   o.rule_default_id = "6";
                                  console.log(o.id, 'manual bonus rule'); 
                               } 
                              
                             }

                           }
                       } 
                   }
               } else {
                   if (common_wage_rules || ! o.rule_default_id) {
                       o.driver_part = 0.6;
                   }
               }   

               recalcWage(o);

               return o;
           })
       })

    }

    adjustData();

    $scope.isShowingPayouts = false;

    console.log(tripList);
    
    $scope.currentDriver = undefined;
    $scope.shiftDate = undefined;
    $scope.isShowingDetails = false;

    $scope.dailyList = tripList;

    $scope.removeDebt = function(for_date, driver){
      driver.debt = 0;
      driver.debt_manually_annulled = true;

      recalcWage(driver);

    }

    $scope.removeFine = function(for_date, driver){
      driver.fine = 0;
      recalcWage(driver);

    }

    $scope.removeFranchise = function(for_date, driver){
      driver.franchise = 0;
      recalcWage(driver);

    }

    $scope.editFuel = function(for_date, driver){
      driver.editingFuel = true;

    }

    $scope.editAdminFines = function(for_date, driver){
      driver.editingAdmFines = true;

    }

    $scope.editGettMonth = function(for_date, driver){
      driver.editingGettMonth = true;

    }

    $scope.editAdmExp = function(for_date, driver){
      driver.editingAdmExp = true;

    }

    $scope.editReferalBonus = function(for_date, driver){
      driver.editingReferalBonus = true;

    }

    $scope.editDebt = function(for_date, driver){
      driver.editingDebt = true;

    }

    $scope.editUberBonus = function(for_date, driver){
      driver.editingUberBonus = true;

    }

    $scope.editUberBonusPart = function(for_date, driver){
      driver.editingUberBonusPart = true;

    }

    $scope.editCover = function(for_date, driver){
      driver.editingCover = true;
      recalcWage(driver);

    }

   $scope.checkEnter = function(keyEvent, driver) {
     if (keyEvent.which == 13){
      driver.editingFuel = false;
      driver.editingDebt = false;
      driver.editingUberBonus = false;
      driver.editingUberBonusPart = false;
      driver.editingReferalBonus = false;
      driver.editingAdmFines = false;
      driver.editingGettMonth = false;
      driver.editingAdmExp = false;

      driver.debt_manually_entered = true;
      driver.manually_entered = true;
      driver.editingCover = false;
      recalcWage(driver);
     } 
   }    


   $scope.selectDriver = function(for_date, driver){
      

      deselectAll(driver);

      if (! driver.selected) {
         $scope.currentDriver = driver;
         $scope.shiftDate = for_date;

         if (daysBetween($scope.lastReport, for_date).toFixed(0) < 2 && driver.uber_completeness && driver.gett_completeness && driver.finish_time) {
            $scope.rowAllowedForSaving = true;
         } else {
            // $scope.rowAllowedForSaving = false;
            $scope.rowAllowedForSaving = true;
         }
   
         if (driver.report_date) {
            $scope.dayAllowedForSaving = false;
         } else {
            $scope.dayAllowedForSaving = true;
         }
      }
      else {
         $scope.rowAllowedForSaving = false;
         $scope.currentDriver = undefined;
         $scope.shiftDate = undefined;
      }

      driver.selected = ! driver.selected;
      $scope.hasSelectedRow = driver.selected;
   }

   $scope.rowForEditing = function(reportDate){
      return daysBetween(reportDate, $scope.lastReport)

   }

   $scope.daysBetween = daysBetween;

   function deselectAll(driver){
      map($scope.dailyList, function(obj) {
         return map(obj, function(o) {
            if (o == driver) {
            } else {
               o.selected = false;
            }
            return o;
         }) 
      })
   }

   $scope.showDetails = function(){
      $scope.isShowingDetails = true;

   }

   $scope.closeDetails = function(){
      $scope.isShowingDetails = false;

   }


   function recalcWage(driver){
      driver.yandex_total = driver.yandex_non_cash + driver.yandex_cash;
      driver.yandex_total_netto = Number(((driver.yandex_non_cash + driver.yandex_cash) *0.8).toFixed(2));

      driver.rbt_total_netto = driver.rbt_total - driver.rbt_comission; 
      driver.rbt_dispatcher_wage = driver.rbt_total * 0.1;

      driver.malyutka_total_netto = driver.malyutka_total * 0.8; 
      driver.malyutka_dispatcher_wage = driver.malyutka_total * 0.1;

      driver.total_cash = driver.from_hand_amount + driver.uber_sum_cash + driver.gett_sum_cash + driver.yandex_cash + driver.rbt_total + driver.malyutka_total;
      driver.total = driver.from_hand_amount + driver.uber_total + driver.gett_total + driver.yandex_total + driver.rbt_total_netto + driver.malyutka_total;
      // driver.total_netto = Number((driver.total * 0.8 + driver.from_hand_amount * 0.2 + driver.rbt_total_netto * 0.2).toFixed(2));
      driver.total_netto = driver.uber_total_netto + driver.gett_total_netto + driver.yandex_total_netto + driver.rbt_total_netto + driver.malyutka_total_netto + driver.from_hand_amount;

      if (driver.manually_entered) {
        // driver
      } else {
        driver.fuel = driver.fuel_expenses ? Number(driver.fuel_expenses) : fuelByTotal(driver.total);
      }

      var common_wage_rules = driver.rule_default_id == CALC_WAGE_BY_COMMON_RULE;

      var wage_before_debt; 

      if (driver.fuel_expenses) {
         if (driver.debt_manually_entered) {
            // driver.debt = 0;
         } else {
            driver.debt = Number(driver.debt_from_income) + Number(driver.debt_from_franchise);
         }
         
         if (driver.is_bonus == 1) {
            driver.total_netto = Number((driver.uber_total * 0.765 + driver.gett_total_netto + 
                                  driver.yandex_total_netto + driver.rbt_total_netto + 
                                  driver.malyutka_total_netto + driver.from_hand_amount).toFixed(2));
            wage_before_debt = Number(driver.total_netto - 1700).toFixed(2);

         } else { 
           wage_before_debt = Number(((driver.total_netto - driver.fuel) * driver.driver_part).toFixed(2));
         }
      } else {

         if (driver.is_bonus_day == 1) {
            driver.total_netto = Number((driver.uber_total * 0.765 + driver.gett_total_netto + 
                                  driver.yandex_total_netto + driver.rbt_total_netto + 
                                  driver.malyutka_total_netto + driver.from_hand_amount).toFixed(2));
            wage_before_debt = Number(driver.total_netto - 1700).toFixed(2);
         } else {
           if (common_wage_rules) {
               driver.driver_part = 0.6;
               if (driver.total < 7000) {
                  driver.rule_default_id = "4";
                  wage_before_debt = Number(((driver.total_netto - driver.fuel) * (1 - driver.driver_part)).toFixed(2));
               } else {
                  driver.rule_default_id = "2";
                  wage_before_debt = Number(((driver.total_netto - driver.fuel) * driver.driver_part).toFixed(2));
               }

           } else {
              var kef;
              if (driver.rule_default_id == "4") 
                kef = 0.4;
              if (driver.rule_default_id == "3") 
                kef = 0.5;
              if (driver.rule_default_id == "2") 
                kef = 0.6;
              
              wage_before_debt = Number(((driver.total_netto - driver.fuel) * kef).toFixed(2));
           }
         }

         if (! driver.debt_planned_payment || driver.debt_planned_payment == 0) {
            driver.debt = 0;

         } else {

            if (driver.debt_manually_entered) {
            } else {

               if (driver.debt_min_wage) {
                  if ((wage_before_debt - driver.debt_min_wage) >= driver.debt_residual) {

                     driver.debt = driver.debt_residual;
                  } else {
                     if (wage_before_debt > 0) {
                        driver.debt = wage_before_debt - driver.debt_min_wage;
                     }
                  }
               } else {
                  if (driver.debt_all_as_debt == 1) {
                     if (wage_before_debt >= driver.debt_residual) {
                        driver.debt = driver.debt_residual;
                     } else {
                        if (wage_before_debt > 0) {
                           driver.debt = wage_before_debt - driver.debt_residual;
                        }
                     }
                  } else {
                     driver.debt = driver.debt_planned_payment ? driver.debt_planned_payment : 0;
                  }
               }
            }
         }

      }

      driver.debt = Number(driver.debt);

      driver.rental_for_show = (driver.rule_default_id=="5" || driver.rule_default_id=="6") ? 1700 : driver.rental;

      driver.wage = wage_before_debt - driver.fine - driver.debt - driver.franchise + 
                    driver.uber_bonus_part  + driver.referal_bonus 
                    - driver.prepay +
                    driver.gett_correction_fare + driver.uber_correction_fare -
                    driver.company_fines;

      driver.income = Number((driver.total_netto - driver.fuel - driver.rental + driver.uber_bonus - driver.uber_bonus_part - (driver.wage < 0 ? 0 : driver.wage) - driver.rbt_dispatcher_wage - driver.malyutka_dispatcher_wage - driver.gett_month - driver.admin_outcomes).toFixed(2));
      // driver.income_deficit = driver.income < 0 ? driver.income : 0;
      driver.income_deficit = 0;
      // if (driver.is_bonus_day == 1 || driver.is_bonus == 1) {
      //   driver.left_to_pay = driver.wage;
      // } else {
      //   driver.left_to_pay = (driver.wage - driver.total_payouts + driver.covered + driver.income_deficit + driver.deferred_debt);
      // }
      driver.left_to_pay = (driver.wage - driver.total_payouts + driver.covered + driver.income_deficit + driver.deferred_debt + driver.paid_by_cash);

      // console.log(driver.driver_id, wage_before_debt, driver.total_netto, driver.fuel, driver.driver_part, driver.uber_total);

   }


   $scope.recalcWage = recalcWage;

   $scope.changeRule = function(driver){
      if (driver.rule_default_id == CALC_WAGE_BY_COMMON_RULE) {
         driver.driver_part = 0.6;
         driver.is_bonus_day = 0;
         driver.is_bonus = 0;
         driver.is_manual_bonus_day = 0;
      } 

      if (driver.rule_default_id == 2) {
         driver.driver_part = 0.6;
         driver.is_bonus_day = 0;
         driver.is_bonus = 0;
         driver.is_manual_bonus_day = 0;
      } 

      if (driver.rule_default_id == 3) {
         driver.driver_part = 0.5;
         driver.is_bonus_day = 0;
         driver.is_bonus = 0;
         driver.is_manual_bonus_day = 0;
      } 

      if (driver.rule_default_id == 4) {
         driver.driver_part = 0.4;
         driver.is_bonus_day = 0;
         driver.is_bonus = 0;
         driver.is_manual_bonus_day = 0;
      } 

      if (driver.rule_default_id == 5) {
         driver.is_bonus_day = 1;
         driver.is_bonus = 1;
         driver.is_manual_bonus_day = 0;
      } 

      if (driver.rule_default_id == 6) {
         driver.is_bonus_day = 1;
         driver.is_bonus = 1;
         driver.is_manual_bonus_day = 1;
      } 

      recalcWage(driver);
   }

   $scope.sumBy = sumBy;


   $scope.saveDayCalc = function(){

      var tripsPerDate = tripList[$scope.shiftDate];

      var validTrips = filter(tripsPerDate, function(obj){
         return obj.fuel_expenses
      })

      if (tripsPerDate.length > validTrips.length) {
         // alert('Not all rows saved!');

         message = '<strong>Not all rows saved!</strong>';
         flashWindow = Flash.create('danger', message, 5000, {class: 'custom-class', id: 'custom-id'}, true);

      } else {
         var data = { reportDate : $scope.shiftDate }
         TripService.closeDailyDay(data)
            .then( function(){
               console.log('saved');
               
               TripService.our1_1()
                .then(function(data) {
                      tripList = data.data;
                      
                      $scope.lastReport = data.last;
                      $scope.dailyList = tripList;
                      
                      adjustData();
                })
            })
      }
   }

   $scope.showPayouts = function(){

      var data = {
         start: $scope.shiftDate,
         driver_id: $scope.currentDriver.id,
      }

      PayoutService.perRange(data)
         .then(function(data){
            console.log('Payouts');
            console.log(data);
            data = map(data, function(o){
               o.amount = Number(o.amount);
               return o;
            })
            
            $scope.payouts = data;
            calcPayoutTotal();

            $scope.isShowingPayouts = true;
         })
   }

   function calcPayoutTotal(){
      $scope.totalPayouts = sumBy($scope.payouts, 'amount');
      var d = $scope.currentDriver;

      var wage = d.wage + d.covered;

      if (d.income < 0)
        wage += d.income;
      if (d.wage + d.covered + d.income < 0) {
        $scope.totalCharged = 0;
      } else {
        $scope.totalCharged = wage;
      }

      $scope.residualToPay = $scope.currentDriver.left_to_pay - $scope.totalPayouts;

   }

   $scope.selectPayout = function(payout) {
      $scope.payouts = map($scope.payouts, function(c) {
         if (c.id === payout.id) {
            if (PayoutService.current() == payout) {
               PayoutService.select(undefined);
               c.selected = false;
               return c;
            } else {
               PayoutService.select(payout);
               c.selected = true;
               return c;
            }
         } else {
            c.selected = false;
            return c;
         }
      })

      $scope.currentPayout = PayoutService.current();
   }

   $scope.addPayout = function() {
      var newPayout = {
         amount: Number($scope.residualToPay.toFixed(2)),
         driver_id: $scope.currentDriver.id,
         report_date: $scope.shiftDate,
         payed_at: formattedToSave( new Date()),
         editing: true,
         selected: false,
         new: true,
         id: getCounter(),
         is_daily: 1,
      }

      $scope.isPayoutEditing = true;

      $scope.payouts.push(newPayout);
   }

   $scope.updatePayout = function(payout) {
      payout.editing = true;
      $scope.isPayoutEditing = true;
      payout.selected = false;
   }

   $scope.deletePayout = function(payout) {
      $scope.payouts = filter($scope.payouts, function(o){
         return (o.id != payout.id);
      })
      calcPayoutTotal();

      // !!!!!!!!!!! Delete from server
      PayoutService.remove(payout)
         .then(function(data){
            console.log('payout removed!');

            $scope.showPayouts();
         })
   }

  $scope.checkPayoutEnter = function(keyEvent, payout) {
     if (keyEvent.which == 13){
        console.log('existing paouts', 'wage + covered');
        console.log(sumBy($scope.payouts, 'amount'), ($scope.currentDriver.wage + $scope.currentDriver.covered));

         if (sumBy($scope.payouts, 'amount') > ($scope.currentDriver.wage + $scope.currentDriver.covered)) {
            message = '<strong>Сумма выплат не может превышать зарплату водителя за день!</strong>';
            flashWindow = Flash.create('danger', message, 5000, {class: 'custom-class', id: 'custom-id'}, true);
         } else {
            payout.editing = false;
            $scope.isPayoutEditing = false;
            payout.selected = false;


            calcPayoutTotal();
            // !!!!!!!!!! Update on server
            if (payout.new) {
               PayoutService.add(payout)
                  .then(function(data){
                     console.log('payout added!');
                     $scope.showPayouts();
                  })
            } else {
               PayoutService.update(payout)
                  .then(function(data){
                     console.log('payout updated!');
                     $scope.showPayouts();
                  })
            }
         }
     } 
   }    

   $scope.closePayouts = function(){
      $scope.isShowingPayouts = false;

      // !!! Refresh data in report
      refreshReport();
   }

   function refreshReport(){
      TripService.our1_1()
       .then(function(data) {
          $scope.hasSelectedRow = false;
          
          tripList = data.data;
          $scope.lastReport = data.last;
          adjustData();

          $scope.isShowingPayouts = false;

          $scope.currentDriver = undefined;
          $scope.shiftDate = undefined;
          $scope.isShowingDetails = false;

          $scope.dailyList = tripList;
       })
   } 

   $scope.saveDriverCalc = function(){

      var d = $scope.currentDriver;

      var totalDeficit = 0;
      
      totalDeficit = d.wage + d.covered;

      var deficitAfterAdjustments = Number((totalDeficit + d.deferred_debt).toFixed(2));

      if (deficitAfterAdjustments < 0) {
         $scope.newDebt = {
            additionAmount: deficitAfterAdjustments,
         } 

         $scope.newDebt.debtLeftToPay = (d.debt_residual) ? (d.debt_residual - d.debt) : 0;

         $scope.obj = {
            newDebtIterationSum: 0,
            paidByCash: -deficitAfterAdjustments,
         }

         // console.log($scope.obj, $scope.newDebt.additionAmount, $scope.obj.paidByCash + $scope.newDebt.additionAmount);

         $scope.obj.debtMinWage = 1000;

         if (d.last_debt) {
            if (d.last_debt.min_daily_wage > 0) {
               $scope.obj.debtRule = 2;
            } else {
               if (d.last_debt.is_total_income_as_fine == 1) {
                  $scope.obj.debtRule = 3;
               } else {
                  if (d.last_debt.is_total_income_as_fine != 1 && ! d.last_debt.min_daily_wage) {
                     $scope.obj.debtRule = 1;
                  } else {
                     alert('!!! Neither of cases !!!');
                  }
               }
            }
         } else {
            $scope.obj.debtRule = 3;
         }

         $scope.isShowingDebtWindow = true;

      } else {

         var data = {
            driver_id : d.id,
            debt : d.debt,
            fine : d.fine,
            franchise : d.franchise,
            fuel : d.fuel,
            rent : d.rental,
            ya_cash : d.yandex_cash,
            ya_non_cash : d.yandex_non_cash,
            is_bonus : (d.is_bonus == 1 || d.is_bonus_day == 1) ? 1 : 0,
            uber_bonus: d.uber_bonus,
            uber_bonus_part: d.uber_bonus_part,
            referal_bonus: d.referal_bonus,
            is_manual_bonus_day : (Number(d.rule_default_id) == 6) ? 1 : 0,
            hand : d.from_hand_amount,
            rbt_total : d.rbt_total,
            malyutka_total : d.malyutka_total,
            rbt_comission : d.rbt_comission,
            uber_correction: d.uber_correction_fare,
            gett_correction: d.gett_correction_fare,
            admin_outcomes: d.admin_outcomes,
            company_fines: d.company_fines,
            gett_month: d.gett_month,
            paid_by_cash: d.paid_by_cash,
            total: d.total,
            // wage_rule: d.rule_default_id,
            dated: $scope.shiftDate,
            group: d.work_type_id,
            covered: d.covered,
            cover_note: '',
            deferred_debt: d.deferred_debt ? d.deferred_debt : 0, ////////// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            covered_company_deficit: d.covered_company_deficit ? d.covered_company_deficit : 0, ////////// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
         }

          data.wage_rule = d.rule_default_id;

          console.log('data 2 presave');
          console.log(data);

         saveDriverDay(data);
      }
   }

   function saveDriverDay(data) {
      TripService.saveDayDriverWithdrawals(data)
         .then( function(){
            console.log('saved');
            
            TripService.our1_1()
               .then(function(data) {
                  tripList = data.data;
                  
                  $scope.lastReport = data.last;
                  $scope.dailyList = tripList;
                  
                  adjustData();

                  var tripsPerDate = tripList[$scope.shiftDate];

                  var validTrips = filter(tripsPerDate, function(obj){
                     return obj.fuel_expenses
                  })

                  if (tripsPerDate.length == validTrips.length) {
                     $scope.dayAllowedForSaving = false;
                  }
             })
         })
   }

   $scope.closeDebtWindow = function(){
      $scope.isShowingDebtWindow = false;
   }

   $scope.saveNewDebtRule = function(){

      var d = $scope.currentDriver;

      $scope.obj.paidByCash = isNaN($scope.obj.paidByCash) ? 0 : $scope.obj.paidByCash;
      d.paid_by_cash = $scope.obj.paidByCash;

      // Если разница в з/п полностью покрыта наличными
      if ($scope.obj.paidByCash + $scope.newDebt.additionAmount == 0){
        saveDriverDay(makeDriverDayDataForSaving());
        $scope.isShowingDebtWindow = false;

        refreshReport();         
      } else {
        $scope.obj.newDebtIterationSum = isNaN($scope.obj.newDebtIterationSum) ? 0 : $scope.obj.newDebtIterationSum;

        // Check iteration sum
        if ($scope.obj.debtRule == 1 && $scope.obj.newDebtIterationSum > (-$scope.newDebt.additionAmount - $scope.obj.paidByCash) ){
           message = '<strong>Сумма ежесменной выплаты не может быть больше суммы долга!</strong>';
           flashWindow = Flash.create('danger', message, 5000, {class: 'custom-class', id: 'custom-id'}, true);

        } else {

          d.deferred_debt = -$scope.newDebt.additionAmount - $scope.obj.paidByCash;
          // Save drive day
          saveDriverDay(makeDriverDayDataForSaving());

          // add new debt
          var sumOfNewDebt = d.deferred_debt + $scope.newDebt.debtLeftToPay;
          addDebt(makeNewDebtRules(sumOfNewDebt))
          .then(function(){
            if (d.last_debt) {
               closeDebt($scope.newDebt.debtLeftToPay)
            }

            $scope.isShowingDebtWindow = false;

            refreshReport();         
          })
        }
      }
   }


   function closeDebt(residual){
      var data = {
         amount: residual,
         shift: $scope.shiftDate,
         driver_id: $scope.currentDriver.id,
      }

      DebtService.close(data)
         .then(function(respond){
            console.log('dedt closed');
            console.log(respond);
         })

   }

   function addDebt(rules){
      return DebtService.add(rules)
         .then(function(respond){
            console.log('dedt added');
            console.log(respond);
         })
   }

   function makeNewDebtRules(amount){

      var d = $scope.currentDriver;

      var rule = {
         //fined_at: formattedToSave(new Date()),
         fined_at: $scope.shiftDate,
         notes: '',
         driver_id: d.id,
         rule_type_id: 1,
         total_sum: amount,
      }

      // iterated 
      if ($scope.obj.debtRule == 1 ){
         rule.iteration_sum = $scope.obj.newDebtIterationSum;
         rule.is_total_income_as_fine = 0;
      }

      // min wage
      if ($scope.obj.debtRule == 2 ){
         rule.iteration_sum = 0;
         rule.is_total_income_as_fine = 0;
         rule.min_daily_wage = $scope.obj.debtMinWage;
         // rule.iteration_count = 1;
         
      }

      // whole wage
      if ($scope.obj.debtRule == 3 ){
         rule.iteration_sum = 0;
         rule.is_total_income_as_fine = 1;
         
      }

      console.log('new debt rule');
      console.log(rule);

      return rule;
   }

   function makeDriverDayDataForSaving(){
      var d = $scope.currentDriver;

      var data = {
         driver_id : d.id,
         debt : d.debt,
         fine : d.fine,
         franchise : d.franchise,
         fuel : d.fuel,
         rent : d.rental,
         ya_cash : d.yandex_cash,
         ya_non_cash : d.yandex_non_cash,
         rbt_total : d.rbt_total,
         malyutka_total : d.malyutka_total,
         rbt_comission : d.rbt_comission,
         total: d.total,
         is_bonus : (d.is_bonus == 1 || d.is_bonus_day == 1) ? 1 : 0,
         uber_bonus: d.uber_bonus,
         uber_bonus_part: d.uber_bonus_part,
         referal_bonus: d.referal_bonus,
         is_manual_bonus_day : (Number(d.rule_default_id) == 6) ? 1 : 0,
         uber_correction: d.uber_correction_fare,
         gett_correction: d.gett_correction_fare,
         admin_outcomes: d.admin_outcomes,
         company_fines: d.company_fines,
         gett_month: d.gett_month,
         paid_by_cash: d.paid_by_cash,
         hand : d.from_hand_amount,
         dated: $scope.shiftDate,
         group: d.work_type_id,
         covered: d.covered,
         cover_note: '',
         deferred_debt: d.deferred_debt ? d.deferred_debt : 0, ////////// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
         covered_company_deficit: d.covered_company_deficit ? d.covered_company_deficit : 0, ////////// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
      }
      console.log('data 1 presave');
      console.log(data);

      data.wage_rule = d.rule_default_id;

      return data;
   }

  $scope.total_topay = function(data){
    var result = 0;

    data.forEach(function(o){
      var topay = o.wage - o.total_payouts + o.covered + o.income_deficit + o.deferred_debt;
      if (topay > 0)
        result += topay;
    })

    return result;
  }

  $scope.total_income = function(data){
    var result = 0;

    data.forEach(function(o){
      var income = o.income + o.covered_company_deficit;
      if (income > 0)
        result += income;
    })

    return result;
  }

}
module.exports = DailyCtrl; 