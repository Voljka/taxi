'use strict';

import { map, filter, isDate, assign, groupBy, omit, find, isEmpty, sumBy } from 'lodash';
import { datePlusDays, formattedToSave, formattedToSaveTime, daysBetween, treatAsUTC } from '../../libs/date';

function ParkCtrl($scope, $state, autolist, AutoParkService, Flash) {

  Flash.clear();

  function adjustList(list){
    $scope.autolist = list.map( function(o){

      o.is_rented = o.is_rented == 1 ? true : false;
      o.cost_weekly = Number(o.cost_weekly);
      o.cost_daily = Number(o.cost_daily);
      o.year_created = Number(o.year_created);

      o.license_deadline_input = new Date(o.license_deadline);
      o.osago_deadline_input = new Date(o.osago_deadline);

      return o;
    });
  }

  adjustList(autolist);

  var message, flashWindow;

  $scope.select = function(auto) {
    $scope.autolist = map($scope.autolist, function(c) {
      if (c.id === auto.id) {
        if (AutoParkService.current() == auto) {
          AutoParkService.select(undefined);
          c.selected = false;
          return c;
        } else {
          AutoParkService.select(auto);
          c.selected = true;
          return c;
        }
      } else {
        c.selected = false;
        return c;
      }
    })

    $scope.currentAuto = AutoParkService.current();
  }

  $scope.addRecord = function(){
    var newRecord = {
      model: '',
      state_number: '',
      is_rented: true,
      
      color: "",
      year_created: 2010,
      sts : "",
      license: "",
      license_deadline: new Date(),
      osago: "",
      osago_deadline: new Date(),

      editing: true,
      new: true,    
    }

    $scope.autolist.push(newRecord);
    $scope.currentAuto = newRecord;
  }

  $scope.removeRecord = function(){
    var data = {
      id: $scope.currentAuto.id,
    }
    
    AutoParkService.remove(data)
    .then(function(respond){
      console.log('auto removed');
      refreshList();
    })    
  }

  $scope.updateRecord = function(){
    $scope.currentAuto.editing = true;
    
  }

  $scope.saveAuto = function(record){
    if (validDates(record)){
      record.editing = false;

      var data = {
        model : record.model,
        state_number : record.state_number,
        is_rented : Number(record.is_rented),

        color: record.color,
        year_created: record.year_created,
        sts : record.sts,
        license: record.license,
        license_deadline: formattedToSaveTime( record.license_deadline_input).substr(0,10),
        osago: record.osago,
        osago_deadline:  formattedToSaveTime( record.osago_deadline_input).substr(0,10),
      }

      if (record.new) {

        AutoParkService.add(data)
        .then(function(respond){
          console.log('auto added');
          refreshList();
        })
      } else {

        data.id = record.id;

        AutoParkService.update(data)
        .then(function(respond){
          console.log('auto updated');
          refreshList();
        })
      }
    } else {
        message = "Неверный формат даты";
        flashWindow = Flash.create('danger', message, 0, {class: 'custom-class', id: 'custom-id'}, true);
      }
  }

  function validDates(record){
    return (isDate(record.license_deadline_input) && isDate(record.osago_deadline_input))
  }

  function refreshList(){

    AutoParkService.all()
    .then(function(list){
      adjustList(list);
    })
  }

  $scope.obj = {}

  $scope.setRentCost = function(){
    $scope.isShowingCosts = true;
    $scope.obj.lastWeekly = $scope.currentAuto.cost_weekly;
    $scope.obj.lastDaily = $scope.currentAuto.cost_daily;
    $scope.obj.newWeekly = $scope.currentAuto.cost_weekly;
    $scope.obj.newDaily = $scope.currentAuto.cost_daily;
    $scope.obj.start_date = new Date();
  }

  $scope.saveNewCosts = function(){

    if ($scope.obj.newDaily == $scope.obj.lastDaily && $scope.obj.newWeekly == $scope.obj.lastWeekly) {
      
      console.log('Rules stay the same. Do not do anything!');
      $scope.isShowingCosts = false;
    } else {
      var data = {
        last_rule_id: $scope.currentAuto.last_rule_id,
        new_started_at: formattedToSave( $scope.obj.start_date ),
        last_rule_finish: formattedToSave( new Date(datePlusDays(-1, $scope.obj.start_date))),
        new_daily: $scope.obj.newDaily,
        new_weekly: $scope.obj.newWeekly,
        auto_id: $scope.currentAuto.id,
      }

      AutoParkService.setCosts(data)
      .then(function(respond){
        $scope.isShowingCosts = false;
        refreshList();
      })
    }
  }

  $scope.closeCosts = function(){
    $scope.isShowingCosts = false;
  }

}
module.exports = ParkCtrl; 