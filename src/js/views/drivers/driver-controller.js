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
