'use strict'

require('ng-file-upload');

var controller = require('./gett-corrections-auto-ctrl');
var importService = require('../../services/ImportService');
require('angular-flash-alert');

angular.module('gettCorrectionsAutoModule', ['ngFlash', 'ngFileUpload'])
  .config(['$httpProvider', function($httpProvider) {
    $httpProvider.defaults.withCredentials = true;
  }])
  .config((FlashProvider) => {
      FlashProvider.setTimeout(5000);
      FlashProvider.setShowClose(true);
  })
  .run(function($rootScope) {
     $rootScope.$on('$stateChangeError', function() {
          console.error(arguments[5]);
     });
  })
  .factory('ImportService', ['$http', importService])
  .controller('GettCorrectionsAutoCtrl', ['$scope', '$state', 'ImportService', 'Upload', 'Flash', controller]);

module.exports = {
  template: require('./gett-corrections-auto.tpl'), 
  resolve: {
  },  
  controller: 'GettCorrectionsAutoCtrl'
};