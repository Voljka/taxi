var angular = require('angular');
require('angular-route');
require('angular-ui-router');

var driversTemplate = require('./js/views/drivers');
var driverCardTemplate = require('./js/views/drivers/card');
var importTemplate = require('./js/views/import');
// var weeklyTemplate = require('./js/views/weekly');
var weeklyTemplate = require('./js/views/weekly_int');
var dailyTemplate = require('./js/views/daily');
var shiftTemplate = require('./js/views/shifts');
var roadfineTemplate = require('./js/views/roadfines');
var autoparkTemplate = require('./js/views/park');
var gettCorrectionsTemplate = require('./js/views/gettcorrections');

var app = angular
	.module('taxiApp', [
		'ui.router', 
		'ngRoute', 
		'driverModule',
		'driverCardModule',
		'importModule',
		'weeklyIntModule',
		// 'weeklyModule',
		'dailyModule',
		'shiftModule',
		'roadFinesModule',
		'parkModule',
		'gettCorrectionsModule',
	])

	.controller('MainCtrl', function($scope) {
		$scope.temporal_variable = 'Ok';
	})

	.config(function($stateProvider, $urlRouterProvider) {
    
    	$urlRouterProvider.otherwise('/cabdrivers');
    
    	$stateProvider

	        .state('cabdrivers', {
	            url: '/cabdrivers',
	            views: {
	            	'content': driversTemplate
	            }
	        })
	        .state('driver_add', {
	            url: '/driver/add',
	            views: {
	            	'content': driverCardTemplate
	            }
	        })
	        .state('driver_modify', {
	            url: '/driver/modify',
	            views: {
	            	'content': driverCardTemplate
	            }
	        })
	        .state('get_load', {
	            url: '/get_data_load',
	            views: {
	            	'content': importTemplate
	            }
	        })
	        .state('uber_load', {
	            url: '/uber_data_load',
	            views: {
	            	'content': importTemplate
	            }
	        })

	        .state('daily', {
	            url: '/daily_summary',
	            views: {
	            	// 'content': {
	            	// 	template: '<h1>Daily Report</h1>'
	            	// }
	            	'content': dailyTemplate
	            }
	        })
	        .state('weekly', {
	            url: '/weekly_summary',
	            views: {
	            	'content': weeklyTemplate
	            }
	        })

	        .state('shifts', {
	            url: '/shifts',
	            views: {
	            	'content': shiftTemplate
	            }
	        })
	        .state('roadfines', {
	            url: '/roadfines',
	            views: {
	            	'content': roadfineTemplate
	            }
	        })
	        .state('autopark', {
	            url: '/autopark',
	            views: {
	            	'content': autoparkTemplate
	            }
	        })
	        .state('gett_corrections', {
	            url: '/gett_corrections',
	            views: {
	            	'content': gettCorrectionsTemplate
	            }
	        })

})