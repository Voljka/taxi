'use strict';

import { map, filter, isDate, assign, groupBy, omit, find, isEmpty, sumBy } from 'lodash';
import { datePlusDays, formattedToSave, daysBetween, treatAsUTC } from '../../libs/date';

function RoadFinesCtrl($scope, $state, autolist, finelist, driverlist, RoadFineService, Flash) {

  Flash.clear();

  $scope.autolist = autolist;
  $scope.fines = finelist.map(function(o){
    o.fine_amount = Number(o.fine_amount);
    o.fine_number = Number(o.fine_number);
    // o.fined_at = new Date(o.fined_at);
    o.fine_date = new Date(o.fined_at);
    o.fine_time = String(o.fined_at).substr(11,8);

    return o
  });
  $scope.cabdrivers = driverlist;

  var message, flashWindow;

  $scope.select = function(fine) {
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

  $scope.addRecord = function(){
    var newRecord = {
      fined_at: new Date(),
      auto_id: autolist[0].id,
      inputed_at: new Date(),
      fine_amount: 0.00,
      notes: '',
      fine_number: '',

      driver_id: driverlist[0].id,
      surname: driverlist[0].surname,
      firstname: driverlist[0].firstname,
      patronymic: driverlist[0].patronymic,

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
    record.editing = false;

    var data = {
      auto_id: record.auto_id,
      fined_at: treatAsUTC(record.fine_date),
      // inputed_at: formattedToSave( record.inputed_at ),
      inputed_at: record.inputed_at,
      fine_amount: record.fine_amount,
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

}
module.exports = RoadFinesCtrl; 