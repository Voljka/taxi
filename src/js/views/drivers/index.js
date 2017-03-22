'use strict'

var controller = require('./driver-controller');
var driverService = require('../../services/DriverService');
require('angular-flash-alert');

import { ruPhoneFrom9 } from '../../libs/phones';

angular.module('driverModule', ['ngFlash'])
  .config(['$httpProvider', function($httpProvider) {
    $httpProvider.defaults.withCredentials = true;
  }])
  .config((FlashProvider) => {
      FlashProvider.setTimeout(5000);
      FlashProvider.setShowClose(true);
  })
  .filter('ruPhone', function(){
    return function(str){
      return ruPhoneFrom9(str)
    }
  })
  .run(function($rootScope) {
     $rootScope.$on('$stateChangeError', function() {
          console.error(arguments[5]);
     });
  })
  .factory('DriverService', ['$http', driverService])
  .controller('DriverCtrl', ['$scope', '$state', 'driverList', 'Flash', 'DriverService', controller]);

module.exports = {
  template: require('./drivers.tpl'), 
  resolve: {
    driverList: ['DriverService', function (DriverService) {
  		return DriverService.all()
  			.then(function(data) {
  				return data;
  			})
    }],
  },  
  controller: 'DriverCtrl'
};