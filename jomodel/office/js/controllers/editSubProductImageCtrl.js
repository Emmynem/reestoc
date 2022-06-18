xnyder.controller('edit-sub-product-imageCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$routeParams,$window,storage,notify){

  $rootScope.pageTitle = "Edit Sub Product Image";

  $scope.loggedInName = storage.getName();
  $scope.loggedInUserImage = storage.getUserImage();
  $scope.loggedInEmail = storage.getAil();
  $scope.loggedInUsername = storage.getUsername();

  $scope.result_u = storage.get_U_u();
  $scope.main_role = storage.getRole();
  $scope.this_year = new Date().getFullYear();
  $scope.current_nav = $location.path();
  $scope.current_nav_split = $scope.current_nav.slice(1);

  $scope.doLogout = function () { notify.do_notify($scope.result_u, "Logout Activity", "User logged out successfully."); storage.exit(); };

  $scope.get_notifications = function () {

    $scope.start = 0;
    $scope.numLimit = 5;

    $scope.notifications_data = {
      "user_unique_id":$scope.result_u,
      "user_role":$scope.main_role,
      "start":$scope.start,
      "numLimit":$scope.numLimit
    }

    $http.post('server/get_notifications.php', $scope.notifications_data)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allNotifications = response.data.re_data;
          $scope.allNotificationsCount = response.data.totalCount > 99 && response.data.totalCount < 1000 ? 99 : response.data.totalCount;
        }
        else if (response.data.noData == 2){
          $scope.allNotifications = "no data";
        }
        else {
          $scope.allNotifications = "error";
        }
      }, function (error) {
        $scope.allNotifications = "error";
      })
  };

  $scope.get_notifications();

  $scope.get_navigation = function () {

    $http.post('server/get_navigation.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allNavigations = response.data.re_data;
        }
        else if (response.data.noData == 2){
          $scope.allNavigations = "No navigation";
        }
        else {
          $scope.allNavigations = "error";
        }
      }, function (error) {
        $scope.allNavigations = "error";
      })
  };

  $scope.get_navigation();

  $scope.show_loader = true;

  $scope.remove_loader = function () {

    $timeout(function () {
      $scope.show_loader = undefined;
    }, 2000)

  };

  $scope.remove_loader();

  $scope.the_sub_product_unique_id = $routeParams.sub_product_unique_id;
  $scope.the_sub_product_image_unique_id = $routeParams.sub_product_image_unique_id;

  $scope.sub_product_images = {};

  var uploadForm = new FormData();

  $scope.count_files = 0;

  $scope.uploadedFile = function(element) {
    $scope.count_files = 0;
    uploadForm.set('file[]', undefined);
    angular.forEach(element.files, function(file){
        uploadForm.append('file[]', file);
        $scope.count_files += 1;
    });

    if ($scope.count_files == 1) {
      $scope.currentFile = element.files[0];
      var reader = new FileReader();


      reader.onload = function(event) {
        $scope.image_source = event.target.result
        $scope.$apply(function($scope) {
          $scope.files = element.files;
        });
      }
      reader.readAsDataURL(element.files[0]);

      $scope.show_preview = true;
    }
    else {
      $scope.show_preview = false;
      $scope.image_source = "";
    }
  };

  $scope.get_sub_product_image_details = function (unique_id, sub_product_unique_id) {

    $scope.genericData = {
      "unique_id":unique_id,
      "sub_product_unique_id":sub_product_unique_id
    }

    $http.post('../../ng/server/get/get_sub_product_image_details.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.subProductImageDetails = null;
          $scope.subProductImageDetailsStatus = undefined;
          $scope.subProductImageDetails = response.data.resultData;
          $scope.sub_product_name = $scope.subProductImageDetails[0].sub_product_name;
          $scope.sub_product_file = $scope.subProductImageDetails[0].file;
        }
        else if (response.data.engineError == 2) {
          $scope.subProductImageDetailsStatus = response.data.engineErrorMessage;
          // $timeout(function () {
          //   $scope.subProductImageDetailsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.subProductImageDetailsStatus = "Error Occured";
          // $timeout(function () {
          //   $scope.subProductImageDetailsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.subProductImageDetailsStatus = "Something's Wrong";
        // $timeout(function () {
        //   $scope.subProductImageDetailsStatus = undefined;
        // }, 3000)
      })

  };

  $scope.get_sub_product_image_details($scope.the_sub_product_image_unique_id, $scope.the_sub_product_unique_id);

  $scope.edit_sub_product_image = function(){

    $scope.shouldIShow = true;

    $scope.continue_action = function () {
      if ($scope.count_files > 1) {
        $scope.errorFileUpload = true;
        $scope.fileUploadStatusError = "Not more than 1 file at a time";
        $timeout(function () {
          $scope.fileUploadStatusError = undefined;
          $scope.errorFileUpload = false;
        }, 3000)
      }
      else {
        $scope.showProgress = false;
        $rootScope.fileUploadStatusError = undefined;
        $rootScope.fileUploadStatusSuccess = undefined;
        $scope.errorFileUpload = false;
        $scope.successFileUpload = false;
        $scope.showSuccessStatus = undefined;
        $scope.showStatus = undefined;

        // var uploadForm = new FormData();
        uploadForm.append('user_unique_id', $scope.result_u);
        uploadForm.append('upload_type', $scope.sub_product_images.upload_type);
        uploadForm.append('sub_product_unique_id', $scope.the_sub_product_unique_id);
        uploadForm.append('sub_product_image_unique_id', $scope.the_sub_product_image_unique_id);
        angular.forEach($scope.filez, function(file){
            uploadForm.append('file[]', file);
        });
        $http.post('../../ng/server/data/sub_product_images/edit_sub_product_image_select_type.php', uploadForm, {
          transformRequest:angular.identity,
          headers: {'Content-Type':undefined, 'Process-Data': false},
          uploadEventHandlers: {
            progress: function (e) {
              if (e.lengthComputable) {
                $scope.showProgress = true;
                $scope.progressBar = (e.loaded / e.total) * 100;
                $scope.progressCounter = $scope.progressBar.toFixed(2) + '%';
              }
            }
          }
        })
        .then(function(response){
          if (response.data.engineMessage == 1) {
            $rootScope.fileUploadStatusError = undefined;

            $scope.showProgress = false;
            $scope.successFileUpload = true;
            $rootScope.fileUploadStatusSuccess = response.data.resultData;

            notify.do_notify($scope.result_u, "Edit Activity", "Product Image edited");

            $scope.clickOnce = true;

            $scope.showSuccessStatus = "Files Uploaded as ";

            $timeout(function () {
              $location.path("sub-products");
            }, 1000);

          }
          else if(response.data.engineError == 2){
            $scope.showProgress = false;
            $scope.errorFileUpload = true;
            $rootScope.fileUploadStatusError = response.data.engineErrorMessage;
            $rootScope.showStatus = response.data.engineErrorMessage;
            $timeout(function(response){
              $rootScope.fileUploadStatusError = "";
              $scope.clickOnce = false;
              $scope.errorFileUpload = false;
            },5000);
          }
          else if(response.data.engineError == 3){
            $scope.showProgress = false;
            $scope.errorFileUpload = true;
            $rootScope.fileUploadStatusError = response.data.engineErrorMessage;
            $rootScope.showStatus = response.data.engineErrorMessage;
            $timeout(function(response){
              $rootScope.fileUploadStatusError = "";
              $scope.clickOnce = false;
              $scope.errorFileUpload = false;
            },5000);
          }
          else{
            $scope.showProgress = false;
            $scope.errorFileUpload = true;
            $rootScope.fileUploadStatusError = "An Error Occured";
            $timeout(function(response){
              $rootScope.fileUploadStatusError = "";
              $scope.clickOnce = false;
              $scope.errorFileUpload = false;
            },5000);
          }
        }, function (error) {
          // console.log(response);
          $scope.showProgress = false;
          $scope.errorFileUpload = true;
          $rootScope.fileUploadStatusError = error.data.message;
          $rootScope.showStatus = $rootScope.fileUploadStatusError;
          $timeout(function(){
            $rootScope.fileUploadStatusError = "";
            $scope.clickOnce = false;
            $scope.errorFileUpload = false;
          },5000);
        })

      }
    };

    $scope.removeConfirmModal = function () {

      $scope.shouldIShow = undefined;

    };

  };

});
