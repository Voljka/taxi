'use strict';

import { filter, isDate, assign, groupBy, omit, find, isEmpty } from 'lodash';

function WeeklyCtrl($scope, $state, TripService) {

    //console.log(tripList);
    $scope.start = new Date("2017-03-13");
    $scope.end = new Date("2017-03-19");

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

                    $scope.get_free7_0 = filter(result, function(o){
                        return (o.group_id == 1 && o.mediator_id == 2) 
                    })
                    
                    // Fixed
                    $scope.fixed_get = filter(result, function(o){
                        return (o.group_id == 11 && o.mediator_id == 2) 
                    })

                    $scope.fixed_uber = filter(result, function(o){
                        return (o.group_id == 11 && o.mediator_id == 1) 
                    })

                    // Parks
                    var park_get = filter(result, function(o){
                        return (o.mediator_id == 2 && o.is_park == 1) 
                    })

                    var park_uber = filter(result, function(o){
                        return (o.mediator_id == 1 && o.is_park == 1) 
                    })
                    makeParkSet(park_get, park_uber);

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
            curObj = omit(o, ['sum_fare', 'sum_result', 'sum_comission', 'sum_cash']);
            curObj.uber_sum_fare = o.sum_fare;            
            curObj.uber_sum_comission = o.sum_comission;            
            curObj.uber_sum_result = o.sum_result;            
            curObj.uber_sum_cash = o.sum_cash;            
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
                result.push(curObj);
            } else {
                result.map( function(p){
                    if (p.driver_id == existingRecord.driver_id) {
                        assign(p, {
                            get_sum_fare : o.sum_fare,            
                            get_sum_comission : o.sum_comission,            
                            get_sum_result : o.sum_result,            
                            get_sum_cash : o.sum_cash            
                        })
                    } 

                    return p;
                })
            }
        })

        $scope.parks = groupBy(result, function(o){
            return o.group_name;
        });

        // console.log($scope.parks);
    }
}

module.exports = WeeklyCtrl; 