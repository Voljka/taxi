'use strict';

function ImportCtrl($scope, $state, ImportService, Upload, Flash) {

	$scope.files = [];
    Flash.clear();

    $scope.currentUberReport = 1;

    $scope.sendFile = function() {

      if ( $scope.form.file.$valid && $scope.file ){
        $scope.upload($scope.file);
      }
    };

    $scope.upload = function (file) {

        Flash.clear();
        
        var url = "get_csv_parser_corrections.php";

        Upload.upload({
            url: 'php/import/'+ url,
            data: {file: file} 
        }).then(function (resp) {
            console.log('Successfully ' + resp.config.data.file.name + 'uploaded');
            //alert(resp.data); 
            var id = Flash.create('danger', resp.data, 0, {class: 'custom-class', id: 'custom-id'}, true);

        }, function (resp) {
            console.log('Error status: ' + resp.status);
        }, function (evt) {
            var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
            console.log('progress: ' + progressPercentage + '% ' + evt.config.data.file.name);
        });
    };
}

module.exports = ImportCtrl; 