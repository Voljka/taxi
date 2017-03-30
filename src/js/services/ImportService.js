'use strict';

var API_SERVER = 'php/import';
function ImportService($http){

  function uberDataUpload(data) {

    return $http
      .post(API_SERVER + '/uber_csv_parser.php', data, {
         transformRequest: angular.identity,
         headers: {'Content-Type': undefined}
       })
      .then(function (data) {
        return data.data;
      })
      .catch(function () {
        return undefined;
      });
  }

  function getDataUpload(data) {

    return $http
      .post(API_SERVER + '/get_csv_parser.php', data, {
         transformRequest: angular.identity,
         headers: {'Content-Type': undefined}
       })
      .then(function (data) {
        return data.data;
      })
      .catch(function () {
        return undefined;
      });
  }

  function test(files) {

    return $http({
      method: 'POST',
      url: API_SERVER + '/test.php',
      headers: { 'Content-Type': false },
      transformRequest: function (data) {
          var formData = new FormData();
          //need to convert our json object to a string version of json otherwise
          // the browser will do a 'toString()' on the object which will result 
          // in the value '[Object object]' on the server.
          // formData.append("model", angular.toJson(data.model));
          //now add all of the assigned files
          for (var i = 0; i < data.files; i++) {
              //add each file to the form data and iteratively name them
              formData.append("file", data.files[i]);
          }
          return formData;
      },
      //Create an object that contains the model and files which will be transformed
      // in the above transformRequest method
      data: { files: files }      
    })
    .then(function (data) {
      console.log(data.data);
      return data.data;
    })
    .catch(function (err) {
      console.log(err);

      return undefined;
    });
  }

  return {
    uploadUber :  uberDataUpload,
    uploadGet  :  getDataUpload,
    test : test,

  };
}

module.exports = ImportService;