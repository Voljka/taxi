'use strict';

function ImportCtrl($scope, $state, ImportService, Upload, Flash) {

	$scope.files = [];
    Flash.clear();

    $scope.currentUberReport = 1;

    $scope.isLoading = false;

	if ($state.current.name == 'uber_load') {
		$scope.partner = "Uber";
	} else {
		$scope.partner = "Gett";
	}
    
    $scope.sendFile = function() {

      if ($scope.partner == "Uber" || ($scope.partner == "Gett" && $scope.form.file.$valid && $scope.file)){
        $scope.upload($scope.file);
      }
    };

    $scope.upload = function (file) {

        Flash.clear();
        
	 	var partner;
	 	if ($state.current.name == 'uber_load') {
			partner = "uber";
            var data = {
                is_current: $scope.currentUberReport,
            }

            $scope.isLoading = true;

            ImportService.uberCurrent(data)
            .then(function(respond){
                var id = Flash.create('danger', respond, 0, {class: 'custom-class', id: 'custom-id'}, true);
                $scope.isLoading = false;
            })

		} else {
			partner = "get";

            Upload.upload({
                url: 'php/import/'+ partner +'_csv_parser.php',
                data: {file: file} 
            }).then(function (resp) {
                console.log('Success ' + resp.config.data.file.name + 'uploaded');
                //alert(resp.data); 
                var id = Flash.create('danger', resp.data, 0, {class: 'custom-class', id: 'custom-id'}, true);

            }, function (resp) {
                console.log('Error status: ' + resp.status);
            }, function (evt) {
                var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                console.log('progress: ' + progressPercentage + '% ' + evt.config.data.file.name);
            });
		}
    };
}

module.exports = ImportCtrl; 