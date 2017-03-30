'use strict'

require('ng-file-upload');

var controller = require('./import-ctrl');
var importService = require('../../services/ImportService');
require('angular-flash-alert');

angular.module('importModule', ['ngFlash', 'ngFileUpload'])
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
  // .directive('fileUpload', function () {
  //     return {
  //         scope: true,        //create a new scope
  //         link: function (scope, el, attrs) {
  //             el.bind('change', function (event) {
  //                 var files = event.target.files;
  //                 //iterate files since 'multiple' may be specified on the element
  //                 for (var i = 0;i<files.length;i++) {
  //                     //emit event upward
  //                     scope.$emit("fileSelected", { file: files[i] });
  //                 }                                       
  //             });
  //         }
  //     };
  // })
  .factory('ImportService', ['$http', importService])
  .controller('ImportCtrl', ['$scope', '$state', 'ImportService', 'Upload', 'Flash', controller]);

module.exports = {
  template: require('./import.tpl'), 
  resolve: {
    // driverList: ['DriverService', function (DriverService) {
  		// return DriverService.all()
  		// 	.then(function(data) {
  		// 		return data;
  		// 	})
    // }],
  },  
  controller: 'ImportCtrl'
};