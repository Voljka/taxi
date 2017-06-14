'use strict';

import { map, filter, isDate, assign, groupBy, omit, find, isEmpty, sumBy } from 'lodash';
import { formattedToSaveTime, calcWeekStartAndEnd, datePlusDays, formattedToSave, daysBetween, treatAsUTC } from '../../libs/date';
import { CALC_WAGE_BY_COMMON_RULE } from '../../constants/common';

function GettCorrectionsCtrl($scope, $state, driverlist, correctionlist, CorrectionService, Flash) {

    Flash.clear();

    var message, flashWindow;
    
    console.log(driverlist);
    console.log(correctionlist);

    function adjustData(){
      driverlist.map(function(o){
        o.driver_id = Number(o.driver_id);
      })

      correctionlist.map(function(o){
        o.amount = Number(o.amount);
      })
    }

    $scope.changeDriver = function(){

      var rec = $scope.currentCorrection;

      if ($scope.drivers.length == 0){
        rec.driver_id = undefined;
        rec.surname = "";
        rec.firstname = "";
        rec.patronymic = "";
      } else {
        if (! rec.driver_id){
          rec.driver_id = $scope.drivers[0].id;
        } 

        $scope.drivers.forEach(function(o){
          if (o.id == rec.driver_id) {
            rec.surname = o.surname;
            rec.firstname = o.firstname;
            rec.patronymic = o.patronymic;
          }
        })
      }
    }

    $scope.useGlobalFilter = function(){
      if (! $scope.corrections) {
        $scope.corrections = correctionlist;
      } else {
        $scope.corrections = filter( correctionlist, function(o) {
          var driver = o.surname.toLowerCase();
          return driver.indexOf($scope.filters.surnameGlobal.toLowerCase()) > -1
        }) 
      }
    }

    $scope.useLocalFilter = function(){
      if (! $scope.drivers || $scope.filters.surnameLocal == "" ) {
        $scope.drivers = driverlist;
      } else {
        $scope.drivers = filter( driverlist, function(o) {
          var driver = o.surname.toLowerCase();
          return driver.indexOf($scope.filters.surnameLocal.toLowerCase()) > -1
        }) 
        $scope.changeDriver();
      }
    }


    function updateList(){
    }

    $scope.addRecord = function(){

      var newRecord = {
        trip_id: "",
        mediator_id: 2,
        driver_id: $scope.drivers[0].id,
        
        amount: 0,
        notes: "",
        recognized_at: (formattedToSaveTime( new Date())).substr(0, 10),

        editing: true,
        new: true,    
      }

      console.log('newRecord');
      console.log(newRecord);

      $scope.corrections.push(newRecord);
      $scope.currentCorrection = newRecord;      

      $scope.filters.surnameLocal = "";
      $scope.useLocalFilter();

    }

    $scope.updateRecord = function(record){
      $scope.filters.surnameLocal = "";
      $scope.useLocalFilter();
      $scope.currentCorrection.editing = true;
    }

    $scope.removeRecord = function(){
    }

    $scope.saveCorrection = function(record){

      Flash.clear();

      var rec = $scope.currentCorrection;

      var resultOfDataValidation = isInputedDataValid(rec);

      if (resultOfDataValidation.length > 0){
        message = resultOfDataValidation;
        flashWindow = Flash.create('danger', message, 0, {class: 'custom-class', id: 'custom-id'}, true);
      } else {
        var data = {
          driver_id : rec.driver_id,
          trip_id : rec.trip_id,
          mediator_id : 2,
          recognized_at : rec.recognized_at,
          notes : rec.notes,
          amount : rec.amount,
        }

        if (rec.new){
          CorrectionService.add(data)
          .then(function(respond){
            console.log('correction saved')
            console.log(respond);

            CorrectionService.byMediatorId(2)
            .then(function(newData){
              correctionlist = newData;
              init();
            })
          })
        } else {
          data.id = rec.id;

          CorrectionService.update(data)
          .then(function(respond){
            console.log('correction updated')
            console.log(respond);

            CorrectionService.byMediatorId(2)
            .then(function(newData){
              correctionlist = newData;
              init();
            })
          })
        }
      }
    }

    function isInputedDataValid(record){
      var result = "";

      if (!record.amount || record.amount == 0 ){
        result += "Неверная сумма корректировки<br>";
      }

      if (record.trip_id.trim().length == 0){
        result += "Введите id поездки<br>";
      }

      return result;
    }

    $scope.select = function(record) {

      if (!$scope.currentCorrection || ! $scope.currentCorrection.editing) {

        $scope.corrections = map($scope.corrections, function(c) {
          if (c.id === record.id) {
            if (CorrectionService.current() == record) {
              CorrectionService.select(undefined);
              c.selected = false;
              return c;
            } else {
              CorrectionService.select(record);
              c.selected = true;
              return c;
            }
          } else {
            c.selected = false;
            return c;
          }
        })

        $scope.currentCorrection = CorrectionService.current();
      }
    }

    function init(){
      $scope.currentCorrection = undefined;
      adjustData();

      $scope.filters = {
        surnameGlobal: "",
        surnameLocal: "",
      }

      $scope.useGlobalFilter();
      $scope.useLocalFilter();
    }

    init();

}
module.exports = GettCorrectionsCtrl; 