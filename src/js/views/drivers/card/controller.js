'use strict';
var _ = require('lodash');
import { toSafeString, toUnsafeString } from '../../../libs/strings';

function ConsumerCardCtrl($scope, $state, workersList, regionsList, tttypeList, current, MainService) {

	if ($state.current.name == 'consumer_add') {
		$scope.submitCaption = "Добавить";
		$scope.name = "";
		$scope.representatives = "";
		$scope.mail = "";
		$scope.place = "";
		$scope.person = "";
		$scope.notes = "";
		$scope.vip = false;
		$scope.currentRegion = regionsList[0].id;
		$scope.currentManager = workersList[0].id;
		$scope.currentType = tttypeList[0].id;
	} else {

		current.name = toUnsafeString(current.name) //.replace(/&#34;/g, '\"').replace(/&#39;/g, '\'');

		$scope.submitCaption = "Сохранить";
		$scope.name = toUnsafeString( current.name );
		$scope.representatives = current.representatives;
		$scope.mail = current.mail;
		$scope.place = toUnsafeString( current.place );
		$scope.person = current.person;
		$scope.notes = toUnsafeString( current.notes );
		$scope.vip = (current.is_vip == 1);
		$scope.currentRegion = current.region;
		$scope.currentManager = current.worker;
		$scope.currentType = current.tt_type;
	}

	$scope.workers = workersList;
	$scope.regions = regionsList;
	$scope.tttypeList = tttypeList;

	$scope.backToList = function(){
		$state.go('consumers');
	}

	$scope.save = function() {
		var formData = new FormData();

		// check mail format

		formData.append('name', toSafeString($scope.name));
		formData.append('representatives', $scope.representatives);
		formData.append('notes', toSafeString($scope.notes));
		formData.append('mail', $scope.mail);
		formData.append('place', toSafeString($scope.place));
		formData.append('person', $scope.person);
		formData.append('worker', $scope.managerList);
		formData.append('tt_type', $scope.typeList);
		formData.append('region', $scope.regionList);
		formData.append('is_vip', $scope.vip);
		formData.append('payment_option', current ? current.payment_option : 5);
		formData.append('route_order_id', ($state.current.name == 'consumer_add') ? 999999 : current.route_order_id);

		if ($state.current.name == 'consumer_add') {
			console.log('Saving new consumer');
			MainService.add(formData)
				.then(function(newObject) {
					console.log(newObject);
					$scope.backToList();
				})
		} else {
			console.log('Updating consumer');
			formData.append('id', current.id);
			MainService.update(formData)
				.then(function(updatedObject) {
					console.log(updatedObject);
					$scope.backToList();
				})
		}
	}
}

module.exports = ConsumerCardCtrl; 