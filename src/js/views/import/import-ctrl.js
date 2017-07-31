'use strict';

function ImportCtrl($scope, $state, ImportService, Upload, Flash) {

	$scope.files = [];
    Flash.clear();

    $scope.currentUberReport = 1;

    $scope.isLoading = false;

	if ($state.current.name == 'uber_load') {
		$scope.partner = "Uber";
	} else {
        if ($state.current.name == 'wheely_load') {
            $scope.partner = "Wheely";
            $scope.ext = ".xlsx";
        } else {
            $scope.partner = "Gett";
            $scope.ext = ".csv";
        }
	}
    
    $scope.sendFile = function() {

      if ($scope.partner == "Uber" || ($scope.partner == "Gett" && 
            $scope.form.file.$valid && $scope.file) || 
            ($scope.partner == "Wheely" && $scope.form.file.$valid && $scope.file)){
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

            var url;

            if ($state.current.name == 'wheely_load') {
                url = "wheely_xls_parser.php";
            } else {
                url = "get_csv_parser.php";
            }

            Upload.upload({
                url: 'php/import/'+ url,
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