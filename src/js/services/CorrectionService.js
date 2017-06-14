'use strict';

var API_SERVER = 'php/corrections';

var current;

function CorrectionService($http) {

  function byMediatorId(mediator_id) {
    return $http
      .get(API_SERVER + '/by_mediator.php?mediator_id=' + mediator_id, { cache: false })
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
    byMediatorId     : byMediatorId,
    current : getCurrent,
    select     : select,
    add        : add,
    update     : update,
    remove : remove,

  };
}

module.exports = CorrectionService;