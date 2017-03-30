'use strict';

var API_SERVER = 'php/trips';

// var current;

function TripService($http) {

  function weekly(range) {
    return $http
      .post(API_SERVER + '/weeklybyrange.php', range/*, {
         transformRequest: angular.identity,
         headers: {'Content-Type': undefined}
       }*/)
      .then(function (data) {
        return data.data;
      })
      .catch(function () {
        return undefined;
      });
  }

  function daily(range) {
    return $http
      .post(API_SERVER + '/dailybyrange.php', range/*, {
         transformRequest: angular.identity,
         headers: {'Content-Type': undefined}
       }*/)
      .then(function (data) {
        return data.data;
      })
      .catch(function () {
        return undefined;
      });
  }

  return {
    weekly     : weekly,
    daily     : daily,

  };
}

module.exports = TripService;