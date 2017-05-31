'use strict';
var _ = require('lodash');

function DriverCtrl($scope, $state, driverList, Flash, DriverService) {

	$scope.drivers = driverList;
	$scope.currentDriver = undefined;
	Flash.clear();

	filterObjects($scope.drivers); 

	$scope.select = function(driver) {
		$scope.drivers = _.map($scope.drivers, function(c) {
			if (c.id === driver.id) {
				if (DriverService.current() == driver) {
					DriverService.select(undefined);
					c.selected = false;
					return c;
				} else {
					DriverService.select(driver);
					c.selected = true;
					return c;
				}
			} else {
				c.selected = false;
				return c;
			}
		})

		$scope.currentDriver = DriverService.current();
	}

	$scope.calcRetirement = function(){
		var data = { 
			driver_id: $scope.currentDriver.id,
		};

		DriverService.calcRetirement(data)
		.then(function(respond){
			respond.franchise_paid = Number(respond.franchise_paid);
			respond.debt_paid = Number(respond.debt_paid);
			respond.fine_paid = Number(respond.fine_paid);
			respond.fine_charged = Number(respond.road_fines_charged);
			respond.debt_charged = Number(respond.debts_charged) - respond.debt_paid;

			respond.franchise_balance = respond.franchise_paid;
			respond.fine_balance = respond.fine_paid - respond.fine_charged;
			respond.debt_balance = respond.debt_paid - respond.debt_charged;

			respond.total_charged = respond.fine_charged + respond.debt_charged;
			respond.total_paid = respond.fine_paid + respond.debt_paid + respond.franchise_paid;
			respond.total_balance = respond.total_paid - respond.total_charged;

			$scope.rd = respond;

			$scope.isShowingRetirement = true;
		})
	}

	$scope.closeRetirement = function(){
		$scope.isShowingRetirement = false;
	}

	$scope.useFilter = function(){
		filterObjects();
	}

	$scope.add = function() {
		$state.go('driver_add');
	}

	$scope.edit = function() {
		$state.go('driver_modify');
	}

	$scope.delete = function() {
		DriverService.delete();
	}

	function filterObjects() {
		
		if (! $scope.filteredObjects) {
			$scope.filteredObjects = $scope.drivers;
		} else {
			$scope.filteredObjects = _.filter( $scope.drivers, function(o) {
				var driver = o.surname.toLowerCase();
				return driver.indexOf($scope.textFilter.toLowerCase()) > -1
			}) 
		}
	}

}

module.exports = DriverCtrl; 
