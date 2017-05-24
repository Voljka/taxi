'use strict'

var controller = require('./park-ctrl');
var parkService = require('../../services/AutoparkService');

import { formattedToRu } from '../../libs/date';
import { numberSplitted } from '../../libs/number';

require('angular-flash-alert');

angular.module('parkModule', ['ngFlash'])
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
      if (!price)
        return "";
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
  .factory('AutoparkService', ['$http', parkService])

  .controller('ParkCtrl', ['$scope', '$state', 'autolist', 'AutoparkService', 'Flash', controller]);

module.exports = {
  template: require('./park.tpl'), 
  resolve: {
    autolist: ['AutoparkService', function (AutoparkService) {
     return AutoparkService.all()
       .then(function(data) {
         return data;
       })
    }],
  },  
  controller: 'ParkCtrl'
};