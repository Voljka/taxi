'use strict';

var API_SERVER = 'php/drivers';
// var REPORT_API = 'php/reports';

var current;

function DriverService($http) {

  function all() {
    return $http
      .get(API_SERVER + '/all.php', { cache: false })
      .then(function (data) {
        return data.data;
      })
      .catch(function () {
        return undefined;
      });
  }

  function own_only() {
    return $http
      .get(API_SERVER + '/own_only.php', { cache: false })
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

  function getCurrent(){
    return current;
  }

  function select(selectedObject) {
    current = selectedObject;
  }

  return {
    all     : all,
    current : getCurrent,
    select     : select,
    add        : add,
    update     : update,
    own_only : own_only,

  };
}

module.exports = DriverService;