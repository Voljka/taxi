'use strict';

var API_SERVER = 'php/payouts';

var current;

function PayoutService($http) {

  function perRange(data) {

    var url = API_SERVER + '/perRange.php?driver_id='+ data.driver_id+'&start='+data.start;

    return $http
      .get(url, { cache: false })
      .then(function (data) {
        return data.data;
      })
      .catch(function () {
        return undefined;
      });
  }

  function add(data) {

    return $http
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
      .post(API_SERVER + '/remove.php', {id: data.id})
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
    perRange   : perRange,
    current    : getCurrent,
    select     : select,
    add        : add,
    update     : update,
    remove     : remove,

  };
}

module.exports = PayoutService;