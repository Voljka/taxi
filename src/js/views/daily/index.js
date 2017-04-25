'use strict'

var controller = require('./daily-ctrl');
var tripService = require('../../services/TripService');
var payoutService = require('../../services/PayoutService');
var debtService = require('../../services/DebtService');

import { formattedToRu } from '../../libs/date';
import { numberSplitted } from '../../libs/number';

require('angular-flash-alert');

angular.module('dailyModule', ['ngFlash'])
  .config(['$httpProvider', function($httpProvider) {
    $httpProvider.defaults.withCredentials = true;
  }])
  .run(function($rootScope) {
     $rootScope.$on('$stateChangeError', function() {
          console.error(arguments[5]);
     });
  })
  .filter('formatRu', function(){
    return function(datetime){
      return formattedToRu(new Date(datetime.substr(0,10)));
    }
  })
  .filter('asPrice', function(){
    return function(price){
      return numberSplitted(Number(price));
    }
  })
  .filter('getObjName', function(){
    return function(obj){
      // console.log(obj);
      return Object.keys(obj)[0];
    }
  })
  .filter('getObjArray', function(){
    return function(obj){
      return Object.values(obj)[0];
    }
  })
  .factory('TripService', ['$http', tripService])
  .factory('PayoutService', ['$http', payoutService])
  .factory('DebtService', ['$http', debtService])
  .controller('DailyCtrl', ['$scope', '$state', 'tripList','TripService','PayoutService', 'DebtService', 'Flash', controller]);

module.exports = {
  template: require('./daily.tpl'), 
  resolve: {
    tripList: ['TripService', function (TripService) {
     return TripService.our1_1()
       .then(function(data) {
         return data;
       })
    }],
  },  
  controller: 'DailyCtrl'
};