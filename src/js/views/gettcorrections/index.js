'use strict'

var controller = require('./gett-corrections-ctrl');
var driverService = require('../../services/DriverService');
var correctionService = require('../../services/CorrectionService');

import { calcWeekStartAndEnd, formattedToRu } from '../../libs/date';
import { numberSplitted } from '../../libs/number';

require('angular-flash-alert');

angular.module('gettCorrectionsModule', ['ngFlash'])
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
  .filter('asPriceOrNull', function(){
    return function(price){
      return numberSplitted(Number(price));
    }
  })
  .factory('CorrectionService', ['$http', correctionService])
  .factory('DriverService', ['$http', driverService])
  .controller('GettCorrectionsCtrl', ['$scope', '$state', 'driverlist', 'correctionlist', 'CorrectionService', 'Flash', controller]);

module.exports = {
  template: require('./gett-corrections.tpl'), 
  resolve: {
    driverlist: ['DriverService', function (DriverService) {
      return DriverService.all()
       .then(function(data) {
         return data;
       })
    }],

    correctionlist: ['CorrectionService', function (CorrectionService) {
      var GETT = 2;
      return CorrectionService.byMediatorId(GETT)
       .then(function(data) {
         return data;
       })
    }],
  },  
  controller: 'GettCorrectionsCtrl'
};