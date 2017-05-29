'use strict';

import { map, filter, isDate, assign, groupBy, omit, find, isEmpty, sumBy } from 'lodash';
import { datePlusDays, formattedToSave, formattedToSaveTime, daysBetween, treatAsUTC } from '../../libs/date';

function ShiftCtrl($scope, $state, autolist, dispatcherlist, driverlist, ShiftService, Flash) {

  $scope.shifts = [];
  $scope.yandexdrivers = [];
  $scope.uberdrivers = [];
  Flash.clear();

  $scope.shiftDate = new Date();

  $scope.autolist = autolist;
  $scope.dispatcherlist = dispatcherlist;

  useFilter();

  var message, flashWindow;


  $scope.showShift = function(){
    refreshShift();

  }

  $scope.select = function(driver) {
    $scope.shifts = map($scope.shifts, function(c) {
      if (c.id === driver.id) {
        if (ShiftService.current() == driver) {
          ShiftService.select(undefined);
          c.selected = false;
          return c;
        } else {
          ShiftService.select(driver);
          c.selected = true;
          return c;
        }
      } else {
        c.selected = false;
        return c;
      }
    })

    $scope.currentShift = ShiftService.current();
  }

  $scope.addRecord = function(){
    var newRecord = {
      dispatcher_id: dispatcherlist[0].id,
      driver_id: driverlist[0].id,
      uber_driver_id: "999999",
      uber_surname: '',
      uber_firstname: '',
      uber_patronymic: '',
      yandex_driver_id: "999999",
      yandex_surname: '',
      yandex_firstname: '',
      yandex_patronymic: '',
      surname: driverlist[0].surname,
      firstname: driverlist[0].firstname,
      patronymic: driverlist[0].patronymic,
      auto_id: autolist[0].id,
      model: autolist[0].model,
      state_number: autolist[0].state_number,
      group_id: driverlist[0].work_type_id,
      group_name: driverlist[0].group_name,
      editing: true,
      shift_date: $scope.shiftDate,
      start_time: new Date(formattedToSave( $scope.shiftDate ) + ' 12:00:00'),
      finish_time: undefined,
      km: 0,
      new: true,
    }

    setUberAndYandexDriver(newRecord);
    makeUberAndYandexDriverLists(newRecord.driver_id);

    $scope.shifts.push(newRecord);
    $scope.currentShift = newRecord;
  }

  function makeUberAndYandexDriverLists(exceptDriverId){
    $scope.yandexdrivers = filter(driverlist, function(o){
      return !( o.id == exceptDriverId );
    })

    $scope.uberdrivers = filter(driverlist, function(o){
      return !( o.id == exceptDriverId );
    })

  }

  function setUberAndYandexDriver(shift){

    console.log('setUberAndYandexDriver');

    console.log('shift');
    console.log(shift);

    console.log(shift.uber_driver_id);

    if (shift.uber_driver_id && shift.uber_driver_id != "999999") {
      var uber_driver = find(driverlist, function(p){
        return (Number(p.id) == Number(shift.uber_driver_id))
      })
      console.log('Found uber_driver');
      console.log(uber_driver);

      shift.uber_surname = uber_driver.surname;
      shift.uber_firstname = uber_driver.firstname;
      shift.uber_patronymic = uber_driver.patronymic;

    } else {
      shift.uber_driver_id = "999999";
      shift.uber_surname = '';
      shift.uber_firstname = '';
      shift.uber_patronymic = '';
    }

    if (shift.yandex_driver_id && shift.yandex_driver_id != "999999") {
      var yandex_driver = find(driverlist, function(p){
        return (Number(p.id) == Number(shift.yandex_driver_id))
      })
      console.log('Found yandex_driver');
      console.log(yandex_driver);

      shift.yandex_surname = yandex_driver.surname;
      shift.yandex_firstname = yandex_driver.firstname;
      shift.yandex_patronymic = yandex_driver.patronymic;

    } else {
      shift.yandex_driver_id = "999999";
      shift.yandex_surname = '';
      shift.yandex_firstname = '';
      shift.yandex_patronymic = '';
    }
  }

  $scope.removeRecord = function(){
    var data = {
      id: $scope.currentShift.id,
    }
    
    ShiftService.remove(data)
    .then(function(respond){
      console.log('shift removed');
      refreshShift();
    })    
  }

  $scope.updateRecord = function(){
 
   makeUberAndYandexDriverLists($scope.currentShift);
   $scope.currentShift.editing = true;
  }

  $scope.saveShift = function(record){

    var isPresent = checkPresense(record);
    var isValidClose = checkKmAndFinishDatePresenceSimultanuously(record);

    if (isPresent.length > 0) {
      message = isPresent;
      flashWindow = Flash.create('danger', message, 0, {class: 'custom-class', id: 'custom-id'}, true);
    } else {
      if (isValidClose.length > 0) {
        message = isValidClose;
        flashWindow = Flash.create('danger', message, 0, {class: 'custom-class', id: 'custom-id'}, true);
      } else {


        checkLastShiftCloseness(record)
          .then(function(respond){
            
            if (respond.length ==0 || respond.finish_time){
              var data = {
                auto_id: record.auto_id,
                driver_id: record.driver_id,
                uber_driver_id: (record.uber_driver_id == "999999") ? undefined : record.uber_driver_id,
                yandex_driver_id: (record.yandex_driver_id == "999999") ? undefined : record.yandex_driver_id,
                driver_id: record.driver_id,
                dispatcher_id: record.dispatcher_id,
                start_time: formattedToSaveTime(record.start_time),
                finish_time: record.finish_time ? formattedToSaveTime(record.finish_time) : undefined,
                km: record.km,
                shift_date : formattedToSaveTime($scope.shiftDate).substr(0,10),
              }

              if (record.new) {

                ShiftService.add(data)
                .then(function(respond){
                  if (flashWindow)
                    flashWindow.clear();
                  record.editing = false;
                  console.log('shift added');
                  refreshShift();
                })
              } else {

                data.id = record.id;

                ShiftService.update(data)
                .then(function(respond){
                  record.editing = false;
                  if (flashWindow)
                    flashWindow.clear();
                  console.log('shift updated');
                  refreshShift();
                })

              }
            } else {
              message = "У данного водителя не закрыта предыдущая смена. Закройте ее перед тем, добавлять сведения о новой  ";
              flashWindow = Flash.create('danger', message, 0, {class: 'custom-class', id: 'custom-id'}, true);
            }
          })
      }
    }
  }

  function checkLastShiftCloseness(record){
    var curRecord = {
      shift_date: formattedToSave( record.shift_date ),
      driver_id: record.driver_id,
    }

    return ShiftService.lastShift(curRecord);
  }

  function checkKmAndFinishDatePresenceSimultanuously(record){
    var result = "";

    if (! isDate(record.start_time)) {
      result += "Неверный формат времени получения авто!";
    }

    if (record.finish_time && record.start_time >= record.finish_time ) {
      result += "Авто не может быть сдано раньше чем получено!";
    }

    if (isDate(record.finish_time) && record.km == 0) {
      result += "Не указаны показания счетчика при сдаче авто!";
    } else {
      if (record.km > 0 && ! isDate(record.finish_time)){
        result += "Неверно указано время сдачи авто!";
      }
    }
    return result;
  }

  function checkPresense(record){

    var result = '';

    $scope.shifts.forEach(function(o){
      
      if ((record.new && record.new == o.new) || o.id == record.id) {
      } else {

        if (o.auto_id == record.auto_id) {
          result += 'Auto ' + record.state_number + ' already presents in the shift! <br>';
        }

        if (o.driver_id == record.driver_id) {
          result += 'Driver ' + record.surname + ' ' + record.firstname + ' '+ record.patronymic + ' ' + ' already presents in the shift! <br>';
        }
      }
    }) 

    return result;   
  }

  function refreshShift(){
    var data = {
      shift_date: formattedToSaveTime($scope.shiftDate).substr(0,10),
    };

    console.log(data);

    ShiftService.perDate(data)
    .then(function(list){

      $scope.shifts = list.map(function(o){
        o.start_time = new Date( o.start_time);

        setUberAndYandexDriver(o);

        o.km = Number(o.km);
        o.finish_time = o.finish_time ? new Date(o.finish_time) : undefined ;
        return o;
      });

    })
  }

  $scope.changeAuto = function(record){
    $scope.autolist.forEach(function(o){
      if (o.id == record.auto_id) {
        record.model = o.model;
        record.state_number = o.state_number;
      }
    })
  }

  $scope.changeDriver = function(record){
    $scope.cabdrivers.forEach(function(o){
      if (o.id == record.driver_id) {
        record.group_id = o.work_type_id;
        record.group_name = o.group_name;
        record.surname = o.surname;
        record.firstname = o.firstname;
        record.patronymic = o.patronymic;
      }
    })

    if (record.driver_id == record.yandex_driver_id || record.driver_id == record.uber_driver_id){
      setUberAndYandexDriver($scope.currentShift);
      makeUberAndYandexDriverLists(record.driver_id);
    }

  }

  $scope.changeUberDriver = function(record){
    $scope.cabdrivers.forEach(function(o){
      if (o.id == record.uber_driver_id) {
        record.uber_surname = o.surname;
        record.uber_firstname = o.firstname;
        record.uber_patronymic = o.patronymic;
      }
    })
  }

  $scope.changeYandexDriver = function(record){
    $scope.cabdrivers.forEach(function(o){
      if (o.id == record.yandex_driver_id) {
        record.yandex_surname = o.surname;
        record.yandex_firstname = o.firstname;
        record.yandex_patronymic = o.patronymic;
      }
    })
  }

  function useFilter(){
    
    if (! $scope.cabdrivers) {
      $scope.cabdrivers = driverlist;
    } else {
      $scope.cabdrivers = _.filter( driverlist, function(o) {
        var driver = o.surname.toLowerCase();
        return driver.indexOf($scope.surnameFilter.toLowerCase()) > -1
      }) 
    }
  }

}
module.exports = ShiftCtrl; 