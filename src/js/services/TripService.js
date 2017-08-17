'use strict';

var API_SERVER = 'php/trips';

function TripService($http) {

  function weekly(range) {

    // return $http
    //   .post(API_SERVER + '/weeklybyrange.php', range)
    return $http
      .post(API_SERVER + '/weeklybyrange_new.php', range)
      .then(function (data) {
        return data.data;
      })
      .catch(function () {
        return undefined;
      });
  }

  function weekly_int(range) {

    console.log('range');
    console.log(range);

    return $http
      .post(API_SERVER + '/weekly_integrated_aggregators.php', range)
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

  function our1_1(range){

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

  function saveWeeklyData(data){

    console.log('Data in Service');
    console.log(data);
    
    return $http
      .post(API_SERVER + '/saveWeeklyData.php', data)
      .then(function (result) {
        return result.data;
      })
      .catch(function () {
        return undefined;
      });

  }

  return {
    weekly     : weekly,
    weekly_int     : weekly_int,
    daily     : daily,
    our1_1 : our1_1,
    closeDailyDay : closeDailyDay,
    saveDayDriverWithdrawals: saveDayDriverWithdrawals,
    saveWeeklyData: saveWeeklyData,

  };
}

module.exports = TripService;