'use strict';

var API_SERVER = 'php/road_fines';

var current;

function RoadFineService($http) {

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
      // .post(API_SERVER + '/update.php', data, {
      //    transformRequest: angular.identity,
      //    headers: {'Content-Type': undefined}
      //  })
      .post(API_SERVER + '/remove.php', data)
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
    remove : remove,

  };
}

module.exports = RoadFineService;