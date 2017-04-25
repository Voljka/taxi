'use strict';

var API_SERVER = 'php/debts/';

function DebtService($http) {

  function close(data){
    return $http
      .post(API_SERVER + '/close.php', data)
      .then(function (result) {
        return result.data;
      })
      .catch(function () {
        return undefined;
      });

  }

  function add(data){
    return $http
      .post(API_SERVER + '/add.php', data)
      .then(function (result) {
        return result.data;
      })
      .catch(function () {
        return undefined;
      });

  }

  return {
    add     : add,
    close   : close,
  };
}

module.exports = DebtService;