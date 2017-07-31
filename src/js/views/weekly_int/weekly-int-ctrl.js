'use strict';

import { filter, isDate, assign, groupBy, omit, find, isEmpty, map, uniqBy, sumBy } from 'lodash';
import { calcWeekStartAndEnd, datePlusDays, formattedToSave, daysBetween, treatAsUTC, daysFromToday } from '../../libs/date';

function WeeklyIntCtrl($scope, $state, TripService, PayoutService, Flash) {

    calcDefaultReportDates();

    const PARK_PAYOUTS = 1, DRIVER_PAYOUTS = 2;
    const PARK_PAYOUT = 13, DRIVER_PAYOUT = 12;
    var payoutWindowMode, currentWeekId, currentParkId, currentDriverId;


    $scope.currentDriver = undefined;

    function calcDefaultReportDates(){
        var period = calcWeekStartAndEnd();

        $scope.start = period.start;
        $scope.end = period.end;
    }


    function bankCardBellView(str){
        var result = '';
        while (str.length > 4) {
            result += str.substr(0,4) + " ";
            str = str.substr(4, str.length - 4);
        }

        if (str.length > 0) {
            result += str;
        }

        return result;
    }

    var previousPark = [];
    var selectedParkDriverObj, selectedFreelancerDriverObj;

    $scope.selectParkRow = function(o){
        if (o[0].group_id != 110){
            $scope.selectedPark = true;
        }
    }

    function adjustData(data){
        data.forEach(function(o){
            o.uber_sum_fare = o.uber_sum_fare ? Number(o.uber_sum_fare) : 0;
            o.uber_sum_result = o.uber_sum_result ? Number(o.uber_sum_result) : 0;
            o.uber_sum_cash = o.uber_sum_cash ? Number(o.uber_sum_cash) : 0;
            o.uber_correction = o.uber_correction ? Number(o.uber_correction) : 0;
            o.uber_sum_boost = o.uber_sum_boost ? Number(o.uber_sum_boost) : 0;
            o.uber_sum_comission = o.uber_sum_comission ? Number(o.uber_sum_comission) : 0;

            o.gett_sum_fare = o.gett_sum_fare ? Number(o.gett_sum_fare) : 0;
            o.gett_sum_result = o.gett_sum_result ? Number(o.gett_sum_result) : 0;
            o.gett_sum_cash = o.gett_sum_cash ? Number(o.gett_sum_cash) : 0;
            o.gett_correction = o.gett_correction ? Number(o.gett_correction) : 0;
            o.gett_sum_boost = o.gett_sum_boost ? Number(o.gett_sum_boost) : 0;
            o.gett_sum_comission = o.gett_sum_comission ? Number(o.gett_sum_comission) : 0;

            o.wheely_sum_fare = o.wheely_sum_fare ? Number(o.wheely_sum_fare) : 0;
            o.wheely_sum_boost = o.wheely_sum_boost ? Number(o.wheely_sum_boost) : 0;
            o.wheely_sum_comission = o.wheely_sum_comission ? Number(o.wheely_sum_comission) : 0;
            o.wheely_sum_fines = o.wheely_sum_fines ? Number(o.wheely_sum_fines) : 0;

            o.asks = o.asks ? Number(o.asks) : 0;
            o.debt = o.debt ? Number(o.debt) : 0;

            o.uber_park_comission = Number(o.uber_park_comission);
            o.wheely_park_comission = Number(o.wheely_park_comission);
            o.bank_rate = Number(o.bank_rate);
            o.uber_bonus = o.uber_bonus ? Number(o.uber_bonus) : 0;

            o.park_paid = o.park_paid ? Number(o.park_paid) : 0;
            o.payed_to_driver = o.freelancer_paid ? Number(o.freelancer_paid) : 0;

            //o.yandex_asks = o.yandex_asks ? Number(o.yandex_asks) : 0;
            o.yandex_asks = o.asks;

            o.yandex_paid_freelancers = o.yandex_paid_freelancers ? Number(o.yandex_paid_freelancers) : 0;
            o.yandex_paid_autoparks = o.yandex_paid_autoparks ? Number(o.yandex_paid_autoparks) : 0;

            o.yandex_paid = o.yandex_paid_freelancers + o.yandex_paid_autoparks;

            o.card_number = bankCardBellView(String(o.card_number));
        })

        return data;
    }

    function recalcDriverTotals(obj){

        obj.yandex_residual = Number((obj.yandex_asks * (1 - 0.03)).toFixed(2)) - obj.yandex_paid;

        obj.wheely_total = obj.wheely_sum_fare - obj.wheely_sum_comission - obj.wheely_sum_fines + obj.wheely_sum_boost;
        if (obj.is_park == 1){
            obj.wheely_interest = Number((obj.wheely_total * obj.wheely_park_comission).toFixed(2));
        } else{
            obj.wheely_interest = Number((obj.wheely_total * 0.05).toFixed(2));
        }
        obj.wheely_to_pay = obj.wheely_total - obj.wheely_interest;

        obj.total_payable = obj.uber_total_to_pay + obj.gett_total_to_pay + obj.yandex_residual + obj.wheely_to_pay;
        obj.total_without_payback = obj.total_payable;

        if (obj.is_park == 1){
            if (obj.is_own_park == 1){
                obj.payback = 0;
            } else {
                if (obj.group_id == 110 || obj.group_id == 109){
                    obj.payback = 0;
                } else {
                    obj.payback = Number(((obj.uber_total_netto) * 0.02).toFixed(2)) + Number((obj.gett_total * 0.02).toFixed(2)) + Number((obj.wheely_total * 0.02).toFixed(2));
                }
            }
        } else {
            obj.payback = 0;
        }

        obj.total_payable += obj.payback;

        obj.bank_comission = Number((obj.total_payable * obj.bank_rate).toFixed(2));
        obj.total_to_pay = Number((obj.total_payable * (1 - obj.bank_rate/100)).toFixed(2));
        obj.residual_to_pay = obj.total_to_pay - obj.payed_to_driver - obj.debt;

    }

    $scope.sumBy = sumBy;


    $scope.selectFreeDriver = function(o){
        if (o.selected){
            o.selected = false;
            $scope.selectedFreelancer = false;
            selectedFreelancerDriverObj = false;
        } else {
            $scope.free7_0.map(function(p){
                p.selected = false;
                return p;
            })

            o.selected = true;
            $scope.selectedFreelancer = true;

            selectedFreelancerDriverObj = o;
        }
    }

    $scope.saveFreelancerData = function(){
        var o = selectedFreelancerDriverObj;

        var data = {
            data: [{
                driver_id: o.driver_id,
                week_id: o.week_id,
                asks: o.yandex_asks,
                debt: o.debt,
            }]
        }

        saveWeeklyData(data)
        .then(function(result){
            // console.log('weekly data for freelancer successfully saved!')

        })

    }

    $scope.saveParkDriverData = function(park){
        var o = selectedParkDriverObj;

        var data = [];
        park.forEach(function(o){
            data.push({
                driver_id: o.driver_id,
                week_id: o.week_id,
                asks: o.yandex_asks,
                debt: o.debt,
            });

        })
        
        saveWeeklyData({data: data})
        .then(function(result){
            // console.log('weekly data for park driver successfully saved!')
           
        })
    }

    function saveWeeklyData(data){

        return TripService.saveWeeklyData(data);

    }

    function adjustPayouts(payouts){
        payouts = payouts.map(function(o){
            o.amount = Number(o.amount);
            return o;
        })
        return payouts;
    }



    $scope.showPayoutsToPark = function(park){
        payoutWindowMode = PARK_PAYOUTS;

        var data = {
            group_id: park[0].group_id,
            week_id: park[0].week_id,
            bank_rate: park[0].bank_rate,

        }

        currentParkId = park[0].group_id;

        if (data.group_id == 9){
            $scope.totalCharged = sumBy(park,'total_to_pay') + ($scope.rafael_payback * (1 - data.bank_rate));
        } else {
            $scope.totalCharged = sumBy(park,'total_to_pay');
        }

        $scope.totalCharged = $scope.totalCharged - sumBy(park,'debt');

        updateParkPayouts(data);
    }

    function updateParkPayouts(data){
        return PayoutService.weekly_park(data)
            .then(function(payouts){
                console.log('payouts');
                console.log(payouts);

                payouts = adjustPayouts(payouts);

                $scope.payouts = payouts;
                $scope.totalPayouts = sumBy(payouts, 'amount');

                $scope.residualToPay = Number(($scope.totalCharged - $scope.totalPayouts).toFixed(2));

                $scope.isShowingPayouts = true;
                
            })
    }



    $scope.showPayoutsToDriver = function(isPark){

        payoutWindowMode = DRIVER_PAYOUTS;

        var driver;
        if (isPark){
            driver = selectedParkDriverObj;
            $scope.currentDriver = selectedParkDriverObj;
        } else {
            driver = selectedFreelancerDriverObj;
            $scope.currentDriver = selectedFreelancerDriverObj;
        }

        var data = {
            driver_id: driver.driver_id,
            week_id: driver.week_id,
            total_to_pay: driver.total_to_pay,
        }

        $scope.totalToPay = driver.total_to_pay;

        currentDriverId = driver.driver_id;

        updateDriverPayouts(data);
    }

    function updateDriverPayouts(data){
        return PayoutService.weekly_freelancer(data)
            .then(function(payouts){
                console.log('payouts');
                console.log(payouts);

                payouts = adjustPayouts(payouts);

                $scope.payouts = payouts;

                $scope.totalPayouts = sumBy(payouts, 'amount');

                $scope.totalCharged = $scope.totalToPay;
                $scope.residualToPay = Number(($scope.totalToPay - $scope.totalPayouts).toFixed(2));

                $scope.isShowingPayouts = true;
            })
    }

    $scope.closePayouts = function(){
        $scope.isShowingPayouts = false;
        $scope.makeSummary();
    }

    $scope.selectParkDriver = function(o, ar){
        if (o.selected){
            o.selected = false;
            $scope.selectedParkDriver = false;
            $scope.withPaybacks = false;
            selectedParkDriverObj = false;

        } else {
            if (ar == previousPark) {
                ar.map(function(p){
                    p.selected = false;
                    return p;
                })

                o.selected = true;
            } else {
                previousPark.map(function(p){
                    p.selected = false;
                    return p;
                })
                o.selected = true;

                previousPark = ar;
            }
            $scope.selectedParkDriver = true;
            if (o.is_own_park == 0){
                $scope.withPaybacks = true;
            } else {
                $scope.withPaybacks = false;
            }
            selectedParkDriverObj = o;
        }
    }

    $scope.recalcDriverTotals = recalcDriverTotals;

    $scope.makeSummary = function(){
        if (isDate($scope.start) && isDate($scope.end)) {
            var period = {
                // start: formattedToSave( $scope.start ),
                // end: formattedToSave( $scope.end ),
                start: $scope.start,
                end: $scope.end,
            };

            console.log(period);

            TripService.weekly_int(period)
                .then( function(result){

                    result = adjustData(result);

                    currentWeekId = result[0].week_id;

                    // console.log(result);
                    var free7_0 = filter(result, function(o){
                        return o.is_park == 0;
                    })

                    $scope.free7_0 = calcFreelanceData(free7_0);

                    console.log('$scope.free7_0');
                    console.log($scope.free7_0);

                    var parks = filter(result, function(o){
                        return o.is_park == 1;
                    })

                    parks = calcParkData(parks);

                    var res = 0;

                    parks.forEach(function(o){
                        if (o.group_id == 109){
                            // console.log(o.surname + " : " + Number(( (o.uber_total_netto + o.gett_total + o.wheely_total) * 0.02).toFixed(2)));
                            res += Number(( (o.uber_total_netto + o.gett_total + o.wheely_total) * 0.02).toFixed(2));
                        } 
                    })

                    $scope.rafael_payback = res;

                    // console.log('$scope.rafael_payback');
                    // console.log($scope.rafael_payback);                         

                    $scope.parks = groupBy(parks, function(o){
                        return o.group_name;
                    });

                    console.log('$scope.parks');
                    console.log($scope.parks);
               
                })
        } else {
            alert('check dates!!!');

        }
    }

    function calcFreelanceData(dataset){


        var result = map(dataset, function(o){
            o.uber_total = Number(o.uber_sum_fare) + Number(o.uber_sum_boost) + Number(o.uber_bonus)  + o.uber_correction;
            o.uber_total_netto = Number(o.uber_sum_fare) + Number(o.uber_sum_comission) + Number(o.uber_sum_boost) + Number(o.uber_bonus)  + o.uber_correction;
            o.uber_total_interest = Number(((o.uber_total_netto) * 0.05).toFixed(2));
            o.uber_total_to_pay = Number(((o.uber_total_netto - o.uber_total_interest - Number(o.uber_sum_cash)) ).toFixed(2));

            o.gett_total = Number(o.gett_sum_fare);
            o.gett_total_commission = Number((o.gett_total * 0.177).toFixed(2));
            o.gett_total_interest = Number((o.gett_total * 0.033).toFixed(2));
            o.gett_total_cash = Number(o.gett_sum_cash);
            o.gett_total_to_pay = o.gett_total - o.gett_total_commission - o.gett_total_interest - o.gett_total_cash + o.gett_correction;

            recalcDriverTotals(o);
            return o;
        })

        return result;
    }


    function calcParkData(dataset){

        var result = map(dataset, function(o){

            o.uber_total = o.uber_sum_fare + o.uber_sum_comission + o.uber_sum_boost + o.uber_bonus + o.uber_correction;
            o.uber_total_netto = o.uber_sum_fare + o.uber_sum_comission + o.uber_sum_boost + o.uber_bonus + o.uber_correction;
            
            if (o.is_own_park == 1) {
                o.uber_total_interest = 0;
            } else {
                o.uber_total_interest = Number(((o.uber_total_netto - o.uber_sum_cash) * o.uber_park_comission).toFixed(2));
            }
            o.uber_total_to_pay = Number(((o.uber_total_netto - o.uber_sum_cash) * (1 - o.uber_park_comission)).toFixed(2));

            o.gett_total = o.gett_sum_fare;
            o.gett_total_commission = Number((o.gett_total * 0.177).toFixed(2));
            if (o.is_own_park == 1) {
                o.gett_total_interest = 0;
            } else {
                o.gett_total_interest = Number((o.gett_total * 0.033).toFixed(2));
            }

            o.gett_total_cash = o.gett_sum_cash;
            o.gett_total_to_pay = o.gett_total - o.gett_total_commission - o.gett_total_interest - o.gett_total_cash + o.gett_correction;

            recalcDriverTotals(o);
            return o;
        })

        return result;
    }

    $scope.freelancersToXLS = function(){
        // tableToExcel('table-freelancers', 'Freelancers');
    }

    $scope.parkToXLS = function(table){
        // console.log(table);
        tableToExcel(table, 'Calc');


    }

    // var tableToExcel1 = (function() {
    //     var uri = 'data:application/vnd.ms-excel;base64,'
    //         , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
    //         , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    //         , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
    //     return function(table, name) {
    //         if (!table.nodeType) table = document.getElementById(table)
    //         var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
    //         window.location.href = uri + base64(format(template, ctx))
    //     }
    // })()

    var tableToExcel = (function() {
      var uri = 'data:application/vnd.ms-excel;base64,'
        , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><?xml version="1.0" encoding="UTF-8" standalone="yes"?><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
        , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
        , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
      return function(table, name) {
          if (!table.nodeType) table = document.getElementById(table)
          var ctx = { worksheet: name || 'Worksheet', table: table.innerHTML }
        window.location.href = uri + base64(format(template, ctx))
      }
    })()    

    var message, flashWindow;

    $scope.checkPayoutEnter = function(keyEvent, payout) {
         if (keyEvent.which == 13){
            console.log('existing payouts', 'charged');
            console.log(sumBy($scope.payouts, 'amount'), $scope.totalCharged);

             if (sumBy($scope.payouts, 'amount') > ($scope.totalCharged)) {
                message = '<strong>Выплаты не могут превышать сумму к выплате!</strong>';
                flashWindow = Flash.create('danger', message, 5000, {class: 'custom-class', id: 'custom-id'}, true);
             } else {
                payout.editing = false;
                $scope.isPayoutEditing = false;
                payout.selected = false;

                // !!!!!!!!!! Update on server
                if (payoutWindowMode == PARK_PAYOUTS){
                    if (payout.new) {
                       PayoutService.weekly_park_add(payout)
                          .then(function(data){
                            console.log('park payout added!');
                            updateParkPayouts(payout);
                          })
                    } else {
                       PayoutService.weekly_park_update(payout)
                          .then(function(data){
                             console.log('park payout updated!');
                             updateParkPayouts(payout);
                          })
                    }
                } else {

                    if (payout.new) {
                       PayoutService.weekly_freelancer_add(payout)
                          .then(function(data){
                             console.log('freelancer payout added!');
                             updateDriverPayouts(payout);
                          })
                    } else {
                       PayoutService.weekly_freelancer_update(payout)
                          .then(function(data){
                             console.log('freelancer payout updated!');
                             updateDriverPayouts(payout);
                          })
                    }
                }
             }
         } 
    }    

    $scope.addPayout = function() {
      var newPayout = {
         amount: $scope.residualToPay,
         paid_at: formattedToSave( new Date()),
         editing: true,
         selected: false,
         new: true,
         id: getCounter(),
         week_id: currentWeekId,
      }

      if (payoutWindowMode == PARK_PAYOUTS){
        newPayout.group_id = currentParkId;
        newPayout.payout_type_id = PARK_PAYOUT;
      } else {
        newPayout.driver_id = currentDriverId;
        newPayout.payout_type_id = DRIVER_PAYOUT;
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

      if (payoutWindowMode == PARK_PAYOUTS){
         PayoutService.weekly_park_remove(payout)
         .then(function(data){
            console.log('park payout removed!');

            updateParkPayouts(payout);
         })
      } else {
         PayoutService.weekly_freelancer_remove(payout)
         .then(function(data){
            console.log('freelancer payout removed!');

            updateDriverPayouts(payout);
         })
      }
    }

    function recalcPayouts(){

    }

    var counter = 10000000000;

    function getCounter(){
      return (counter++);
    }

}

module.exports = WeeklyIntCtrl; 