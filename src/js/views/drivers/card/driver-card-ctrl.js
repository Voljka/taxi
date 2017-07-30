'use strict';
var _ = require('lodash');

import { isDate } from 'lodash';

import { BANK_SBERBANK, BANK_CASH, GROUP_NOT_SPECIFIED } from '../../../constants/common';

function DriverCardCtrl($scope, $state, groupList, bankList, current, DriverService, BankService, GroupService, Flash) {

	var id;

	$scope.banks = bankList;
	$scope.groups = groupList;

	if ($state.current.name == 'driver_add') {
		$scope.surname = "";
		$scope.firstname = "";
		$scope.patronymic = "";

		$scope.rule_default_id = 1;

		$scope.mail = "";
		$scope.phone = "";
		$scope.phone2 = "";

		$scope.cardNumber = "";
		$scope.currentBank = BANK_SBERBANK;
		$scope.beneficiar = "";
		$scope.bankRate = 0;

		$scope.isCash = false;

		$scope.currentGroup = GROUP_NOT_SPECIFIED;
		$scope.firstDay = new Date();
		$scope.active = true;
		$scope.rent = false;

		$scope.notes = "";
	} else {
		$scope.surname = current.surname;
		$scope.firstname = current.firstname;
		$scope.patronymic = current.patronymic;

		$scope.mail = current.email;
		$scope.rule_default_id = current.rule_default_id ? current.rule_default_id : "1";
		$scope.phone = current.phone == 0 ? "" : current.phone;
		$scope.phone2 = current.phone2 == 0 ? "" : current.phone2;
		$scope.bankRate = Number(current.bank_rate);

		$scope.cardNumber = current.card_number == 0 ? "" : current.card_number;
		$scope.currentBank = current.bank_id;
		if (current.bank_id == BANK_CASH) {
			$scope.isCash = true;
		} else {
			$scope.isCash = false;
		}
		$scope.beneficiar = current.beneficiar;

		$scope.currentGroup = current.work_type_id;
		$scope.firstDay = current.registration_date ? new Date(current.registration_date) : "";
		$scope.active = current.active == 1 ? true : false;
		$scope.rent = current.rent == 1 ? true : false;

		$scope.notes = current.notes;
	}

	$scope.backToList = function(){
		$state.go('cabdrivers');
		id = undefined;
	}

	$scope.changeBank = function(){
		if ($scope.driverBank == BANK_CASH) {
			$scope.isCash = true;
		} else {
			$scope.isCash = false;
		}
	}
	
	$scope.save = function() {

		if (isAllDataValid()) {
			var data = {};

			data.firstname = $scope.firstname;
			data.surname = $scope.surname;
			data.patronymic = $scope.patronymic;

			data.email = $scope.mail;
			data.bank_rate = $scope.bankRate;
			data.phone = $scope.phone == "" ? 0 : Number($scope.phone);
			data.phone2 = $scope.phone2 == "" ? 0 : Number($scope.phone2);
			data.rule_default_id = $scope.rule_default_id;

			data.card_number = ($scope.driverBank == BANK_CASH || $scope.cardNumber == "") ? 0 : $scope.cardNumber;
			//data.bank_id = Number($scope.driverBank);
			data.bank_id = 99;
			data.beneficiar = ($scope.driverBank == BANK_CASH) ? "" : $scope.beneficiar;

			data.work_type_id = Number($scope.driverGroup);
			data.registration_date = $scope.firstDay;
			data.active = Number($scope.active);
			data.rent = Number($scope.rent);

			data.notes = $scope.notes;
			// console.log(data);

			if ($state.current.name == 'driver_add') {
				console.log('Saving new driver');
				console.log(data);
				
				DriverService.add(data)
					.then(function(newObject) {
						console.log(newObject);
						$scope.backToList();
					})
			} else {
				console.log('Updating driver info');
				data.id = Number(current.id);
				console.log(data);

				DriverService.update(data)
					.then(function(updatedObject) {
						console.log(updatedObject);
						$scope.backToList();
					})
			}

		}
	}

	
	function isAllDataValid(){

		var isValid = true,
			message = '';

		if ($scope.surname.trim().length < 3) {
			message += '<br>Неверное значение поля <strong>Фамилия</strong>';
			isValid = false;
		}

		if ($scope.firstname.trim().length < 3) {
			message += '<br>Неверное значение поля <strong>Имя</strong>';
			isValid = false;
		}

		if ($scope.patronymic.trim().length < 3) {
			message += '<br>Неверное значение поля <strong>Отчество</strong>';
			isValid = false;
		}

		if ($scope.phone === undefined) {
			message += '<br>Неверный <strong>Номер телефона 1</strong>';
			isValid = false;
		}

		if ($scope.phone2 === undefined) {
			message += '<br>Неверный <strong>Номер телефона 2</strong>';
			isValid = false;
		}

		console.log('value of card number = ' + $scope.cardNumber);
		if ($scope.cardNumber) {
			console.log('length of card number = ' + String($scope.cardNumber).length);
		}

		if ($scope.cardNumber === undefined || (String($scope.cardNumber).length != 18 && String($scope.cardNumber).length != 16 && String($scope.cardNumber).length != 0)) {
		// if ($scope.cardNumber === undefined) {
			message += '<br>Неверное значение поля <strong>Номер карты</strong>';
			isValid = false;
		}

		if ($scope.firstDay !== null && ! isDate($scope.firstDay)) {
			message += '<br>Неверное значение поля <strong>Дата выхода</strong>';
			isValid = false;
		}

		if (! isValid) {
		    id = Flash.create('danger', message, 0, {class: 'custom-class', id: 'custom-id'}, true);
		}

		return isValid;
	}
}

module.exports = DriverCardCtrl; 