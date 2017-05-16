'use strict';

function ImportCtrl($scope, $state, ImportService, Upload, Flash) {

	$scope.files = [];
    Flash.clear();

	if ($state.current.name == 'uber_load') {
        $scope.isLoading = true;

		$scope.partner = "Uber";
        ImportService.uberCurrent()
            .then(function(respond){
                var id = Flash.create('danger', respond, 0, {class: 'custom-class', id: 'custom-id'}, true);
                $scope.isLoading = false;
            })
	} else {
		$scope.partner = "Gett";
        $scope.isLoading = false;
	}
    
    // $scope.$on("fileSelected", function (event, args) {
    //     $scope.$apply(function () {            
    //         //add the file object to the scope's files collection
    //         $scope.files.push(args.file);
    //     });

    // });

    $scope.sendFile = function() {
      if ($scope.form.file.$valid && $scope.file) {
        $scope.upload($scope.file);
      }
    };

    $scope.upload = function (file) {

	 	var partner;
	 	if ($state.current.name == 'uber_load') {
			partner = "uber";
		} else {
			partner = "get";
		}
		   	
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
    };

}

module.exports = ImportCtrl; 