'use strict'

var controller = require('./weekly-int-ctrl');
var tripService = require('../../services/TripService');

import { formattedToRu } from '../../libs/date';
import { numberSplitted } from '../../libs/number';

angular.module('weeklyIntModule', [])
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
  .controller('WeeklyIntCtrl', ['$scope', '$state', 'TripService', controller]);

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