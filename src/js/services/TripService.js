'use strict';

var API_SERVER = 'php/trips';

function TripService($http) {

  function weekly(range) {
    return $http
      .post(API_SERVER + '/weeklybyrange.php', range)
      .then(function (data) {
        return data.data;
      })
      .catch(function () {
        return undefined;
      });
  }

  function daily(range) {
    return $http
      .post(API_SERVER + '/dailybyrange.php', range)
      .then(function (data) {
        return data.data;
      })
      .catch(function () {
        return undefined;
      });
  }

  function our1_1(){

    var range = {
      start: "2017-04-24",
      end: "2017-05-01"
    }

    return $http
      .post(API_SERVER + '/our1_1.php', range)
      .then(function (data) {
        return data.data;
      })
      .catch(function () {
        return undefined;
      });
  }

  function closeDailyDay(data){
    return $http
      .post(API_SERVER + '/closeDay.php', data)
      .then(function (result) {
        return result.data;
      })
      .catch(function () {
        return undefined;
      });

  }

  function saveDayDriverWithdrawals(data){
    return $http
      .post(API_SERVER + '/saveDailyIndividuals.php', data)
      .then(function (result) {
        return result.data;
      })
      .catch(function () {
        return undefined;
      });

  }

  return {
    weekly     : weekly,
    daily     : daily,
    our1_1 : our1_1,
    closeDailyDay : closeDailyDay,
    saveDayDriverWithdrawals: saveDayDriverWithdrawals,

  };
}

module.exports = TripService;