xnyder.controller('offered-servicesCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$routeParams,$window,storage,notify){

  $rootScope.pageTitle = "Offered Services";

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

  $scope.refreshPage = function () {
  $scope.show_loader = true;
    $scope.loadOfferedServices();
  };

  $scope.loadOfferedServices = function () {

    $http.get('../../ng/server/get/get_all_offered_services.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allOfferedServicesStatus = undefined;
          $scope.allOfferedServices = response.data.resultData;
          $scope.allOfferedServicesCount = $scope.allOfferedServices.length;

          $scope.remove_loader();

          $scope.currentPage=1;
          $scope.numLimit=100;
          $scope.start = 0;
          $scope.$watch('allOfferedServices',function(newVal){
            if(newVal){
             $scope.pages=Math.ceil($scope.allOfferedServices.length/$scope.numLimit);

            }
          });
          $scope.hideNext=function(){
            if(($scope.start+ $scope.numLimit) < $scope.allOfferedServices.length){
              return false;
            }
            else
            return true;
          };
          $scope.hidePrev=function(){
            if($scope.start===0){
              return true;
            }
            else
            return false;
          };
          $scope.nextPage=function(){
            $scope.currentPage++;
            $scope.start=$scope.start+ $scope.numLimit;
          };
          $scope.PrevPage=function(){
            if($scope.currentPage>1){
              $scope.currentPage--;
            }
            $scope.start=$scope.start - $scope.numLimit;
          };
        }
        else if (response.data.engineError == 2) {
          $scope.allOfferedServicesStatus = response.data.engineErrorMessage;
          $scope.allOfferedServicesCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allOfferedServicesStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allOfferedServicesStatus = "Error Occured";
          $scope.allOfferedServicesCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allOfferedServicesStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allOfferedServicesStatus = "Something's Wrong";
        $scope.allOfferedServicesCount = 0;
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allOfferedServicesStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadOfferedServices();

  $scope.loadSubProducts = function () {

    $http.get('../../ng/server/get/get_all_sub_products_for_select.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allSubProductsStatus = undefined;
          $scope.allSubProducts = response.data.resultData;
          $scope.allSubProductsCount = $scope.allSubProducts.length;
        }
        else if (response.data.engineError == 2) {
          $scope.allSubProductsStatus = response.data.engineErrorMessage;
          $scope.allSubProductsCount = 0;
          // $timeout(function () {
          //   $scope.allSubProductsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allSubProductsStatus = "Error Occured";
          $scope.allSubProductsCount = 0;
          // $timeout(function () {
          //   $scope.allSubProductsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allSubProductsStatus = "Something's Wrong";
        $scope.allSubProductsCount = 0;
        // $timeout(function () {
        //   $scope.allSubProductsStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadSubProducts();

  $scope.loadOfferedServicesCategories = function (sub_product_unique_id) {

    $scope.genericData = {
      "sub_product_unique_id":sub_product_unique_id
    }

    $http.post('../../ng/server/get/get_all_offered_services_categories_for_select.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allOfferedServicesCategoriesStatus = undefined;
          $scope.allOfferedServicesCategories = response.data.resultData;
          $scope.allOfferedServicesCategoriesCount = $scope.allOfferedServicesCategories.length;
        }
        else if (response.data.engineError == 2) {
          $scope.allOfferedServicesCategoriesStatus = response.data.engineErrorMessage;
          $scope.allOfferedServicesCategoriesCount = 0;
          $scope.allOfferedServicesCategories = undefined;
          // $timeout(function () {
          //   $scope.allOfferedServicesCategoriesStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allOfferedServicesCategoriesStatus = "Error Occured";
          $scope.allOfferedServicesCategoriesCount = 0;
          $scope.allOfferedServicesCategories = undefined;
          // $timeout(function () {
          //   $scope.allOfferedServicesCategoriesStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allOfferedServicesCategoriesStatus = "Something's Wrong";
        $scope.allOfferedServicesCategoriesCount = 0;
        $scope.allOfferedServicesCategories = undefined;
        // $timeout(function () {
        //   $scope.allOfferedServicesCategoriesStatus = undefined;
        // }, 3000)
      })

    };

  // $scope.loadOfferedServicesCategories();

  $scope.offered_service = {};

  $scope.offered_service_with_image = {};

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

  $scope.save_offered_service = function () {

    $scope.shouldIShow = true;

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.genericData = {
        "user_unique_id":$scope.result_u,
        "sub_product_unique_id":$scope.offered_service.add_sub_product_unique_id,
        "offered_service_category_unique_id":$scope.offered_service.add_offered_service_category_unique_id,
        "service":$scope.offered_service.add_service,
        "details":$scope.offered_service.add_details,
        "price":$scope.offered_service.add_price
      }

      $http.post('../../ng/server/data/offered_services/add_new_service.php', $scope.genericData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Offered Service added successfully !";

            notify.do_notify($scope.result_u, "Add Activity", "Offered Service Added");

            $timeout(function () {
              $scope.showSuccessStatus = undefined;
              $scope.actionStatus = undefined;
              window.location.reload(true);
            }, 3000)

          }
          else if (response.data.engineError == 2){
            $scope.showStatus = true;
            $scope.actionStatus = response.data.engineErrorMessage;
            $timeout(function () {
              $scope.showStatus = undefined;
              $scope.actionStatus = undefined;
              $scope.clickOnce = false;
            }, 3000)
          }
          else {
            $scope.showStatus = true;
            $scope.actionStatus = "Error Occured";
            $timeout(function () {
              $scope.showStatus = undefined;
              $scope.actionStatus = undefined;
              $scope.clickOnce = false;
            }, 3000)
          }
        }, function (error) {
          $scope.showStatus = true;
          $scope.actionStatus = "Something's Wrong";
          $timeout(function () {
            $scope.showStatus = undefined;
            $scope.actionStatus = undefined;
            $scope.clickOnce = false;
          }, 3000)
        })

    };

    $scope.removeConfirmModal = function () {

      $scope.shouldIShow = undefined;

    };

  };

  $scope.save_offered_service_with_image = function () {

    $scope.shouldIShow = true;

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      if ($scope.count_files == 0) {
        $scope.showStatus = true;
        $scope.actionStatus = "Select Image";
        $timeout(function () {
          $scope.actionStatus = undefined;
          $scope.showStatus = false;
          $scope.clickOnce = false;
        }, 3000)
      }
      else if ($scope.count_files > 1) {
        $scope.showStatus = true;
        $scope.actionStatus = "Not more than 1 file allowed";
        $timeout(function () {
          $scope.actionStatus = undefined;
          $scope.showStatus = false;
          $scope.clickOnce = false;
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
        uploadForm.append('sub_product_unique_id', $scope.offered_service_with_image.add_sub_product_unique_id);
        uploadForm.append('offered_service_category_unique_id', $scope.offered_service_with_image.add_offered_service_category_unique_id);
        uploadForm.append('service', $scope.offered_service_with_image.add_service);
        uploadForm.append('details', $scope.offered_service_with_image.add_details);
        uploadForm.append('price', $scope.offered_service_with_image.add_price);
        uploadForm.append('upload_type', $scope.offered_service_with_image.upload_type);
        angular.forEach($scope.filez, function(file){
            uploadForm.append('file[]', file);
        });
        $http.post('../../ng/server/data/offered_services/add_new_service_with_image.php', uploadForm, {
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
            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Offered Service added successfully !";

            notify.do_notify($scope.result_u, "Add Activity", "Offered Service Added with Image");

            $scope.clickOnce = true;

            $timeout(function () {
              $scope.showSuccessStatus = undefined;
              $scope.actionStatus = undefined;
              window.location.reload(true);
            }, 1000);

          }
          else if(response.data.engineError == 2){
            $scope.showProgress = false;
            $scope.errorFileUpload = true;
            $rootScope.fileUploadStatusError = response.data.engineErrorMessage;
            $scope.showStatus = true;
            $scope.actionStatus = response.data.engineErrorMessage;
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
            $scope.showStatus = true;
            $scope.actionStatus = response.data.engineErrorMessage;
            $timeout(function(response){
              $rootScope.fileUploadStatusError = "";
              $scope.clickOnce = false;
              $scope.errorFileUpload = false;
              $scope.showStatus = undefined;
              $scope.actionStatus = undefined;
            },5000);
          }
          else{
            $scope.showProgress = false;
            $scope.errorFileUpload = true;
            $rootScope.fileUploadStatusError = "An Error Occured";
            $scope.showStatus = true;
            $scope.actionStatus = "An Error Occured";
            $timeout(function(response){
              $rootScope.fileUploadStatusError = "";
              $scope.clickOnce = false;
              $scope.errorFileUpload = false;
              $scope.showStatus = undefined;
              $scope.actionStatus = undefined;
            },5000);
          }
        }, function (error) {
          // console.log(response);
          $scope.showProgress = false;
          $scope.errorFileUpload = true;
          $rootScope.fileUploadStatusError = error.data.message;
          $scope.showStatus = true;
          $scope.actionStatus = $rootScope.fileUploadStatusError;
          $timeout(function(){
            $rootScope.fileUploadStatusError = "";
            $scope.clickOnce = false;
            $scope.errorFileUpload = false;
            $scope.showStatus = undefined;
            $scope.actionStatus = undefined;
          },5000);
        })

      }
    };

    $scope.removeConfirmModal = function () {

      $scope.shouldIShow = undefined;

    };

  };

  $scope.edit_offered_service = function (offered_service_unique_id, sub_product_unique_id, offered_service_category_unique_id, service, price, details) {

    $scope.offered_service.edit_sub_product_unique_id = sub_product_unique_id;
    $scope.offered_service.edit_offered_service_category_unique_id = offered_service_category_unique_id;
    $scope.offered_service.edit_service = service;
    $scope.offered_service.edit_price = parseInt(price);
    $scope.offered_service.edit_details = details;

    $scope.loadSubProducts();
    $scope.loadOfferedServicesCategories($scope.offered_service.edit_sub_product_unique_id);

    $scope.go_ahead = function () {
      $scope.shouldIShow = true;
    };

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.genericData = {
        "user_unique_id":$scope.result_u,
        "offered_service_unique_id":offered_service_unique_id,
        "sub_product_unique_id":$scope.offered_service.edit_sub_product_unique_id,
        "offered_service_category_unique_id":$scope.offered_service.edit_offered_service_category_unique_id,
        "service":$scope.offered_service.edit_service,
        "details":$scope.offered_service.edit_details,
        "price":$scope.offered_service.edit_price
      }

      $http.post('../../ng/server/data/offered_services/update_service.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {

          $scope.showSuccessStatus = true;
          $scope.actionStatus = "Changes Saved !";

          notify.do_notify($scope.result_u, "Edit Activity", "Offered Service (" + offered_service_unique_id + ") edited");

          $timeout(function () {
            $scope.showSuccessStatus = undefined;
            $scope.actionStatus = undefined;
            window.location.reload(true);
          }, 3000)

        }
        else if (response.data.engineError == 2){
          $scope.showStatus = true;
          $scope.actionStatus = response.data.engineErrorMessage;
          $timeout(function () {
            $scope.showStatus = undefined;
            $scope.actionStatus = undefined;
            $scope.clickOnce = false;
          }, 3000)
        }
        else {
          $scope.showStatus = true;
          $scope.actionStatus = "Error Occured";
          $timeout(function () {
            $scope.showStatus = undefined;
            $scope.actionStatus = undefined;
            $scope.clickOnce = false;
          }, 3000)
        }
      }, function (error) {
        $scope.showStatus = true;
        $scope.actionStatus = "Something's Wrong";
        $timeout(function () {
          $scope.showStatus = undefined;
          $scope.actionStatus = undefined;
          $scope.clickOnce = false;
        }, 3000)
      })

    };

    $scope.removeConfirmModal = function () {

      $scope.shouldIShow = undefined;

    };
  };

  $scope.edit_offered_service_image = function (offered_service_unique_id, sub_product_unique_id, offered_service_image_file) {

    $scope.offered_service_image_file = offered_service_image_file != null ? offered_service_image_file : null;

    $scope.loadOfferedServicesCategories(sub_product_unique_id);

    $scope.go_ahead = function () {
      $scope.shouldIShow = true;
    };

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      if ($scope.count_files == 0) {
        $scope.showStatus = true;
        $scope.actionStatus = "Select Image";
        $timeout(function () {
          $scope.actionStatus = undefined;
          $scope.showStatus = false;
          $scope.clickOnce = false;
        }, 3000)
      }
      else if ($scope.count_files > 1) {
        $scope.showStatus = true;
        $scope.actionStatus = "Not more than 1 file allowed";
        $timeout(function () {
          $scope.actionStatus = undefined;
          $scope.showStatus = false;
          $scope.clickOnce = false;
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
        uploadForm.append('sub_product_unique_id', sub_product_unique_id);
        uploadForm.append('offered_service_unique_id', offered_service_unique_id);
        uploadForm.append('upload_type', $scope.offered_service_with_image.edit_upload_type);
        angular.forEach($scope.filez, function(file){
            uploadForm.append('file[]', file);
        });
        $http.post('../../ng/server/data/offered_services/update_service_image.php', uploadForm, {
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
            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Changes Saved !";

            notify.do_notify($scope.result_u, "Edit Activity", "Offered Service (" + offered_service_unique_id + ") Image edited");

            $scope.clickOnce = true;

            $timeout(function () {
              $scope.showSuccessStatus = undefined;
              $scope.actionStatus = undefined;
              window.location.reload(true);
            }, 1000);

          }
          else if(response.data.engineError == 2){
            $scope.showProgress = false;
            $scope.errorFileUpload = true;
            $rootScope.fileUploadStatusError = response.data.engineErrorMessage;
            $scope.showStatus = true;
            $scope.actionStatus = response.data.engineErrorMessage;
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
            $scope.showStatus = true;
            $scope.actionStatus = response.data.engineErrorMessage;
            $timeout(function(response){
              $rootScope.fileUploadStatusError = "";
              $scope.clickOnce = false;
              $scope.errorFileUpload = false;
              $scope.showStatus = undefined;
              $scope.actionStatus = undefined;
            },5000);
          }
          else{
            $scope.showProgress = false;
            $scope.errorFileUpload = true;
            $rootScope.fileUploadStatusError = "An Error Occured";
            $scope.showStatus = true;
            $scope.actionStatus = "An Error Occured";
            $timeout(function(response){
              $rootScope.fileUploadStatusError = "";
              $scope.clickOnce = false;
              $scope.errorFileUpload = false;
              $scope.showStatus = undefined;
              $scope.actionStatus = undefined;
            },5000);
          }
        }, function (error) {
          // console.log(response);
          $scope.showProgress = false;
          $scope.errorFileUpload = true;
          $rootScope.fileUploadStatusError = error.data.message;
          $scope.showStatus = true;
          $scope.actionStatus = $rootScope.fileUploadStatusError;
          $timeout(function(){
            $rootScope.fileUploadStatusError = "";
            $scope.clickOnce = false;
            $scope.errorFileUpload = false;
            $scope.showStatus = undefined;
            $scope.actionStatus = undefined;
          },5000);
        })

      }
    };

    $scope.removeConfirmModal = function () {

      $scope.shouldIShow = undefined;

    };

  };

  $scope.delete_offered_service = function (offered_service_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "offered_service_unique_id":offered_service_unique_id
      }

      $http.post('../../ng/server/data/offered_services/remove_service_with_image.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "Offered Service deleted");

            $timeout(function () {
              $scope.showSuccessStatus = undefined;
              $scope.actionStatus = undefined;
              // $route.reload();
              window.location.reload(true);
            }, 3000)

          }
          else if (response.data.noData == 2) {
            $scope.showStatus = true;
            $scope.actionStatus = response.data.engineErrorMessage;
            $timeout(function () {
              $scope.showStatus = undefined;
              $scope.actionStatus = undefined;
            }, 3000)
          }
          else {
            $scope.showStatus = true;
            $scope.actionStatus = "Error Occured";
            $timeout(function () {
              $scope.showStatus = undefined;
              $scope.actionStatus = undefined;
            }, 3000)
          }
        }, function (error) {
          $scope.showStatus = true;
          $scope.actionStatus = "Something's Wrong";
          $timeout(function () {
            $scope.showStatus = undefined;
            $scope.actionStatus = undefined;
          }, 3000)
        })

    };

  };

});
