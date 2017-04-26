'use strict'

var controller = require('./roadfine-ctrl');
var parkService = require('../../services/AutoparkService');
var roadFineService = require('../../services/RoadFineService');
// var shiftService = require('../../services/ShiftService');
// var dispatcherService = require('../../services/DispatcherService');
var driverService = require('../../services/DriverService');

import { formattedToRu } from '../../libs/date';
import { numberSplitted } from '../../libs/number';

require('angular-flash-alert');

angular.module('roadFinesModule', ['ngFlash'])
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
  // .factory('ShiftService', ['$http', shiftService])
  .factory('DriverService', ['$http', driverService])
  .factory('RoadFineService', ['$http', roadFineService])
  .factory('AutoparkService', ['$http', parkService])
  // .factory('DispatcherService', ['$http', dispatcherService])
  .controller('RoadFinesCtrl', ['$scope', '$state', 'autolist', 'finelist', 'driverlist', 'RoadFineService', 'Flash', controller]);

module.exports = {
  template: require('./roadfines.tpl'), 
  resolve: {
    autolist: ['AutoparkService', function (AutoparkService) {
     return AutoparkService.all()
       .then(function(data) {
         return data;
       })
    }],
    driverlist: ['DriverService', function (DriverService) {
     return DriverService.own_only()
       .then(function(data) {
         return data;
       })
    }],
    finelist: ['RoadFineService', function (RoadFineService) {
     return RoadFineService.all()
       .then(function(data) {
         return data;
       })
    }],
  },  
  controller: 'RoadFinesCtrl'
};