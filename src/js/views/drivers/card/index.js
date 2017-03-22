'use strict'

require('angular-input-masks');
require('angular-flash-alert');

var controller = require('./driver-card-ctrl');
var driverService = require('../../../services/DriverService');
var bankService = require('../../../services/BankService');
var groupService = require('../../../services/GroupService');

angular.module('driverCardModule', ['ui.utils.masks', 'ngFlash'])
  .config(['$httpProvider', function($httpProvider) {
    $httpProvider.defaults.withCredentials = true;
  }])
  .factory('DriverService', ['$http', driverService])
  .factory('BankService', ['$http', bankService])
  .factory('GroupService', ['$http', groupService])
  .controller('DriverCardCtrl', ['$scope', '$state', 'groupList', 'bankList', 'current', 'DriverService', 'BankService', 'GroupService', 'Flash', controller])

module.exports = {
  template: require('./driver-card.tpl'), 
  resolve: {
    current: ['DriverService', function (DriverService) {
     	return DriverService.current();
    }],
    bankList: ['BankService', function (BankService) {
    return BankService.all()
      .then(function(data) {
        return data;
      })
    }],
    groupList: ['GroupService', function (GroupService) {
    return GroupService.all()
      .then(function(data) {
        return data;
      })
    }],
  },
  controller: 'DriverCardCtrl'
};