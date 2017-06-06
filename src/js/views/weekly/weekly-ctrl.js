'use strict';

import { filter, isDate, assign, groupBy, omit, find, isEmpty, map } from 'lodash';
import { calcWeekStartAndEnd, datePlusDays, formattedToSave, daysBetween, treatAsUTC, daysFromToday } from '../../libs/date';

function WeeklyCtrl($scope, $state, TripService) {

    //console.log(tripList);

    calcDefaultReportDates();

    function calcDefaultReportDates(){
        var period = calcWeekStartAndEnd();

        $scope.start = period.start;
        $scope.end = period.end;
    }

    $scope.makeSummary = function(){
        if (isDate($scope.start) && isDate($scope.end)) {
            var period = {
                start: $scope.start,
                end: $scope.end
            };

            TripService.weekly(period)
                .then( function(result){
                    // console.log(result);
                    
                    // Freelancer 7/0
                    $scope.uber_free7_0 = filter(result, function(o){
                        return (o.group_id == 1 && o.mediator_id == 1) 
                    })

                    $scope.uber_free7_0 = map($scope.uber_free7_0, function(o){
                        o.total_netto = Number(o.sum_fare) + Number(o.sum_comission) + Number(o.sum_boost);
                        o.total_interest = Number(((o.total_netto - Number(o.sum_cash)) * 0.05).toFixed(2));
                        o.total_to_pay = Number(((o.total_netto - Number(o.sum_cash)) * 0.95).toFixed(2));
                        return o;
                    })

                    $scope.get_free7_0 = filter(result, function(o){
                        return (o.group_id == 1 && o.mediator_id == 2) 
                    })

                    $scope.get_free7_0 = map($scope.get_free7_0, function(o){
                        o.total = Number(o.sum_fare);
                        o.total_commission = Number((o.total * 0.177).toFixed(2));
                        o.total_interest = Number((o.total * 0.033).toFixed(2));
                        o.total_cash = Number(o.sum_cash);
                        o.total_to_pay = o.total - o.total_interest - o.total_cash;
                        return o;
                    })
                    
                    // Fixed
                    // $scope.fixed_get = filter(result, function(o){
                    //     return (o.group_id == 11 && o.mediator_id == 2) 
                    // })

                    // $scope.fixed_uber = filter(result, function(o){
                    //     return (o.group_id == 11 && o.mediator_id == 1) 
                    // })

                    // Parks
                    var park_get = filter(result, function(o){
                        return (o.mediator_id == 2 && o.is_park == 1) 
                    })

                    var park_uber = filter(result, function(o){
                        return (o.mediator_id == 1 && o.is_park == 1) 
                    })
                    makeParkSet(park_get, park_uber);

                    console.log("$scope.uber_free7_0");
                    console.log($scope.uber_free7_0);
                    console.log("$scope.gett_free7_0");
                    console.log($scope.get_free7_0);

                })

        } else {
            alert('check dates!!!');

        }

    }

    function makeParkSet(uberList, getList){
        var result_get = [],
            result_uber = [],
            result = [];

        var curObj;

        uberList.forEach(function(o){
            curObj = omit(o, []);

            var total = Number(o.sum_fare) + Number(o.sum_comission) + Number(o.sum_boost);
            var park_comis = Number(o.uber_park_comission)

            curObj.uber_total_netto = total;
            
            curObj.uber_total_interest = Number(((total - Number(o.sum_cash)) * park_comis).toFixed(2));
            curObj.uber_total_to_pay = Number(((total - Number(o.sum_cash)) * (1 - park_comis)).toFixed(2));

            // console.log('curObj.uber_total_interest');
            // console.log(curObj.uber_total_interest);

            result.push(curObj);
        })

        var existingRecord;
        
        getList.forEach(function(o){
            existingRecord = find(result, function(p){
                return p.driver_id == o.driver_id
            })

            if (! existingRecord) {
                curObj = omit(o, ['sum_fare', 'sum_result', 'sum_comission', 'sum_cash']);
                curObj.get_sum_fare = o.sum_fare;            
                curObj.get_sum_comission = o.sum_comission;            
                curObj.get_sum_result = o.sum_result;            
                curObj.get_sum_cash = o.sum_cash;            

                curObj.get_total = Number(o.sum_fare);
                curObj.get_total_commission = Number((curObj.get_total * 0.177).toFixed(2));
                curObj.get_total_interest = Number((curObj.get_total * 0.033).toFixed(2));
                curObj.get_total_cash = Number(o.sum_cash);
                curObj.get_total_to_pay = curObj.get_total - curObj.get_total_commission - curObj.get_total_interest - curObj.get_total_cash;

                result.push(curObj);
            } else {
                result.map( function(p){
                    if (p.driver_id == existingRecord.driver_id) {
                        assign(p, {
                            // get_sum_fare : o.sum_fare,            
                            // get_sum_comission : o.sum_comission,            
                            // get_sum_result : o.sum_result,            
                            // get_sum_cash : o.sum_cash            
                            get_total : Number(o.sum_fare),
                            get_total_commission : Number((curObj.get_total * 0.177).toFixed(2)),
                            get_total_interest : Number((curObj.get_total * 0.033).toFixed(2)),
                            get_total_cash : Number(o.sum_cash),
                            get_total_to_pay : curObj.get_total - curObj.get_total_commission - curObj.get_total_interest - curObj.get_total_cash,
                        })
                    } 

                    return p;
                })
            }
        })

        $scope.parks = groupBy(result, function(o){
            return o.group_name;
        });

        console.log('$scope.parks');
        console.log($scope.parks);
    }
}

module.exports = WeeklyCtrl; 