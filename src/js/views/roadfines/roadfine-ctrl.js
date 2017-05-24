'use strict';

import { map, filter, isDate, assign, groupBy, omit, find, isEmpty, sumBy } from 'lodash';
import { datePlusDays, formattedToSave, formattedToSaveTime, daysBetween, treatAsUTC } from '../../libs/date';
import { VIDEOFIXATION,FIXATED_BY_POLICE } from '../../constants/common';

function RoadFinesCtrl($scope, $state, autolist, finelist, driverlist, RoadFineService, ShiftService, Flash) {

  Flash.clear();

  $scope.autolist = autolist;
  $scope.fines = finelist.map(function(o){
    o.fine_amount = Number(o.fine_amount);
    o.fine_number = Number(o.fine_number);
    o.fine_date = new Date(o.fined_at);
    o.fine_time = String(o.fined_at).substr(11,8);

    return o
  });
  $scope.cabdrivers = driverlist;

  var message, flashWindow;

  $scope.isDate = isDate;

  $scope.select = function(fine) {
    if (! $scope.currentFine || ! $scope.currentFine.editing) {

      $scope.fines = map($scope.fines, function(c) {
        if (c.id === fine.id) {
          if (RoadFineService.current() == fine) {
            RoadFineService.select(undefined);
            c.selected = false;
            return c;
          } else {
            RoadFineService.select(fine);
            c.selected = true;
            return c;
          }
        } else {
          c.selected = false;
          return c;
        }
      })

      $scope.currentFine = RoadFineService.current();
    }
  }

  $scope.addRecord = function(){
    var newRecord = {
      fine_date: new Date(formattedToSaveTime( new Date()).substr(0,10) + ' 12:00:00') ,
      auto_id: autolist[0].id,
      inputed_at: new Date(),
      fine_amount: 0.00,
      notes: '',
      fine_number: '',
      fine_place: '',
      fixation_type: String(VIDEOFIXATION),

      driver_id: undefined,
      surname: "",
      firstname: "",
      patronymic: "",

      model: autolist[0].model,
      state_number: autolist[0].state_number,

      group_id: driverlist[0].work_type_id,
      group_name: driverlist[0].group_name,

      editing: true,
      new: true,    
    }

    $scope.fines.push(newRecord);
    $scope.currentFine = newRecord;
  }

  $scope.removeRecord = function(){
    var data = {
      id: $scope.currentFine.id,
    }
    
    RoadFineService.remove(data)
    .then(function(respond){
      console.log('road fine removed');
      refreshList();
    })    
  }

  $scope.updateRecord = function(){
    $scope.currentFine.editing = true;
    
  }

  $scope.saveFine = function(record){

    if (! record.driver_id){
      message = "Перед сохранением штрафа необходимо привязать его к водителю!";
      flashWindow = Flash.create('danger', message, 5000, {class: 'custom-class', id: 'custom-id'}, true);
    } else {
      record.editing = false;

      var data = {
        auto_id: record.auto_id,
        fined_at: treatAsUTC(record.fine_date),
        // inputed_at: formattedToSave( record.inputed_at ),
        inputed_at: record.inputed_at,
        fine_amount: record.fine_amount,
        fixation_type: record.fixation_type,
        fine_place: record.fine_place,
        driver_id: record.driver_id,
        fine_number : record.fine_number,
        notes : record.notes,
      }

      console.log()

      if (record.new) {

        RoadFineService.add(data)
        .then(function(respond){
          console.log('fine added');
          refreshList();
        })
      } else {

        data.id = record.id;

        RoadFineService.update(data)
        .then(function(respond){
          console.log('fine updated');
          refreshList();
        })
      }
    }
  }

  function refreshList(){

    RoadFineService.all()
    .then(function(list){
      $scope.fines = list.map(function(o){
        o.fine_amount = Number(o.fine_amount);
        o.fine_number = Number(o.fine_number);
        // o.fined_at = new Date(o.fined_at);
        o.fine_date = new Date(o.fined_at);
        o.fine_time = String(o.fined_at).substr(11,8);

        return o
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

    console.log(record);
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
    console.log(record);
  }

  $scope.joinDriver = function(record){
    var data = {
      auto_id : record.auto_id,
      shift_date : formattedToSaveTime( record.fine_date ),
    }

    console.log(record);

    ShiftService.driverPerTimeAndAuto(data)
      .then(function(driver){

        if (driver.length == 0) {
          record.group_id = undefined;
          record.group_name = undefined;
          record.driver_id = undefined;
          record.surname = undefined;
          record.firstname = undefined;
          record.patronymic = undefined;

          message = "В данное время ни один из водителей не был на указанном авто !";
          flashWindow = Flash.create('danger', message, 5000, {class: 'custom-class', id: 'custom-id'}, true);
        } else {

          record.group_id = driver[0].work_type_id;
          record.group_name = driver[0].group_name;
          record.driver_id = driver[0].driver_id;
          record.surname = driver[0].surname;
          record.firstname = driver[0].firstname;
          record.patronymic = driver[0].patronymic;
        }
      })
  }

}
module.exports = RoadFinesCtrl; 