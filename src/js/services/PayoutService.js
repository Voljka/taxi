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

  function weekly_park(data) {

    var url = API_SERVER + '/weekly_park.php?group_id='+ data.group_id+'&week_id='+data.week_id;

    return $http
      .get(url, { cache: false })
      .then(function (data) {
        return data.data;
      })
      .catch(function () {
        return undefined;
      });
  }  

  function weekly_freelancer(data) {

    var url = API_SERVER + '/weekly_freelancer.php?driver_id='+ data.driver_id+'&week_id='+data.week_id;

    return $http
      .get(url, { cache: false })
      .then(function (data) {
        return data.data;
      })
      .catch(function () {
        return undefined;
      });
  }  

  function weekly_freelancer_add(data) {

    return $http
      .post(API_SERVER + '/weekly_freelancer_add.php', data)
      .then(function (data) {
        return data.data;
      })
      .catch(function () {
        return undefined;
      });
  }

  function weekly_freelancer_update(data) {
    return $http
      .post(API_SERVER + '/weekly_freelancer_update.php', data)
      .then(function (data) {
        return data.data;
      })
      .catch(function () {
        return undefined;
      });
  }

  function weekly_freelancer_remove(data) {
    return $http
      .post(API_SERVER + '/weekly_freelancer_remove.php', {id: data.id})
      .then(function (data) {
        return data.data;
      })
      .catch(function () {
        return undefined;
      });
  }

  function weekly_park_add(data) {

    return $http
      .post(API_SERVER + '/weekly_park_add.php', data)
      .then(function (data) {
        return data.data;
      })
      .catch(function () {
        return undefined;
      });
  }

  function weekly_park_update(data) {
    return $http
      .post(API_SERVER + '/weekly_park_update.php', data)
      .then(function (data) {
        return data.data;
      })
      .catch(function () {
        return undefined;
      });
  }

  function weekly_park_remove(data) {
    return $http
      .post(API_SERVER + '/weekly_park_remove.php', {id: data.id})
      .then(function (data) {
        return data.data;
      })
      .catch(function () {
        return undefined;
      });
  }


  return {
    perRange   : perRange,
    current    : getCurrent,
    select     : select,
    add        : add,
    update     : update,
    remove     : remove,
    weekly_freelancer : weekly_freelancer,
    weekly_park : weekly_park,
    weekly_freelancer_add : weekly_freelancer_add,
    weekly_freelancer_update : weekly_freelancer_update,
    weekly_freelancer_remove : weekly_freelancer_remove,
    weekly_park_add : weekly_park_add,
    weekly_park_update : weekly_park_update,
    weekly_park_remove : weekly_park_remove,
  };
}

module.exports = PayoutService;