'use strict'

var controller = require('./weekly-int-ctrl');
var tripService = require('../../services/TripService');
var payoutService = require('../../services/PayoutService');

require('angular-flash-alert');

import { formattedToRu } from '../../libs/date';
import { numberSplitted, numberSplitted2 } from '../../libs/number';

angular.module('weeklyIntModule', ['ngFlash'])
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
  .filter('asPrice2', function(){
    return function(price){
      return numberSplitted2(Number(price));
    }
  })
  .filter('getObjName', function(){
    return function(obj){
      console.log(obj);
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

 .controller('WeeklyIntCtrl', ['$scope', '$state', 'TripService', 'PayoutService', 'Flash', controller]);

module.exports = {
  template: require('./weekly-int.tpl'), 
  // resolve: {
  //   tripList: ['TripService', function (TripService) {
  // 		return TripService.all()
  // 			.then(function(data) {
  // 				return data;
  // 			})
  //   }],
  // },  
  controller: 'WeeklyIntCtrl'
};