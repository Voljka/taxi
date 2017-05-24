'use strict';

var API_SERVER = 'php/shifts';

var current;

function ShiftService($http) {

  function perDate(data) {
    return $http
      .get(API_SERVER + '/perDate.php?shift_date='+data.shift_date, { cache: false })
      .then(function (data) {
        return data.data;
      })
      .catch(function () {
        return undefined;
      });
  }

  function add(data) {

    return $http
      // .post(API_SERVER + '/add.php', data, {
      //    transformRequest: angular.identity,
      //    headers: {'Content-Type': undefined}
      //  })
      .post(API_SERVER + '/add.php', data)
      .then(function (data) {
        return data.data;
      })
      .catch(function () {
        return undefined;
      });
  }

  function update(data) {
    return $http
      // .post(API_SERVER + '/update.php', data, {
      //    transformRequest: angular.identity,
      //    headers: {'Content-Type': undefined}
      //  })
      .post(API_SERVER + '/update.php', data)
      .then(function (data) {
        return data.data;
      })
      .catch(function () {
        return undefined;
      });
  }

  function remove(data) {
    return $http
      .post(API_SERVER + '/removeCharging.php', data)
      .then(function (data) {
        return data.data;
      })
      .catch(function () {
        return undefined;
      });
  }

  function getCurrent(){
    return current;
  }

  function select(selectedObject) {
    current = selectedObject;
  }

  function lastShift(data){
    return $http
      .get(API_SERVER + '/lastShiftBeforeDate.php?shift_date='+data.shift_date+'&driver_id='+data.driver_id, { cache: false })
      .then(function (data) {
        return data.data;
      })
      .catch(function () {
        return undefined;
      });
  }

  function driverPerTimeAndAuto(data){
    return $http
      .get(API_SERVER + '/driverPerTimeAndAuto.php?shift_date='+data.shift_date+'&auto_id='+data.auto_id, { cache: false })
      .then(function (data) {
        return data.data;
      })
      .catch(function () {
        return undefined;
      });
  }

  return {
    perDate     : perDate,
    current : getCurrent,
    select     : select,
    add        : add,
    update     : update,
    remove : remove,
    lastShift : lastShift,
    driverPerTimeAndAuto: driverPerTimeAndAuto,

  };
}

module.exports = ShiftService;