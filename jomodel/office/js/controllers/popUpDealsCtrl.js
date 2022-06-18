xnyder.controller('pop-up-dealsCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$routeParams,$window,storage,notify){

  $rootScope.pageTitle = "Pop Up Deals";

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
    $scope.loadPopUpDeals();
  };

  $scope.filterShow = false;

  $scope.filter_var = {};

  $scope.filterByDate = function () {
    $scope.filter_var.startdate = new Date();
    $scope.filter_var.startdate.setHours(0, 0, 0, 0);
    $scope.filter_var.enddate = new Date();
    $scope.filter_var.enddate.setHours(23, 59, 0, 0);
    $scope.filterShow = true;
  };

  $scope.filter = function () {

    if ($scope.filter_var.startdate > $scope.filter_var.enddate) {
      $scope.filterShow = true;
    }
    else {

      $scope.filterShow = false;

      $scope.show_loader = true;

      $scope.filter_data = {
        "start_date":$scope.filter_var.startdate,
        "end_date":$scope.filter_var.enddate
      }

      $http.post('../../ng/server/get/get_pop_up_deals_filter.php', $scope.filter_data)
        .then(function (response) {
          if (response.data.engineMessage == 1) {
            $scope.allPopUpDealsStatus = undefined;
            $scope.allPopUpDeals = response.data.resultData;
            $scope.allPopUpDealsCount = $scope.allPopUpDeals.length;

            $scope.remove_loader();

            $scope.currentPage=1;
            $scope.numLimit=100;
            $scope.start = 0;
            $scope.$watch('allPopUpDeals',function(newVal){
              if(newVal){
               $scope.pages=Math.ceil($scope.allPopUpDeals.length/$scope.numLimit);

              }
            });
            $scope.hideNext=function(){
              if(($scope.start+ $scope.numLimit) < $scope.allPopUpDeals.length){
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
            $scope.allPopUpDealsStatus = "No data in range !";
            $scope.allPopUpDealsCount = 0;
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allPopUpDealsStatus = undefined;
            // }, 3000)
          }
          else {
            $scope.allPopUpDealsStatus = "Couldn't get data";
            $scope.allPopUpDealsCount = 0;
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allPopUpDealsStatus = undefined;
            // }, 3000)
          }
        }, function (error) {
          $scope.allPopUpDealsStatus = "Something's Wrong";
          $scope.allPopUpDealsCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allPopUpDealsStatus = undefined;
          // }, 3000)
        })
    }
  };

  $scope.loadPopUpDeals = function () {

    $http.get('../../ng/server/get/get_all_pop_up_deals.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allPopUpDealsStatus = undefined;
          $scope.allPopUpDeals = response.data.resultData;
          $scope.allPopUpDealsCount = $scope.allPopUpDeals.length;

          $scope.remove_loader();

          $scope.currentPage=1;
          $scope.numLimit=100;
          $scope.start = 0;
          $scope.$watch('allPopUpDeals',function(newVal){
            if(newVal){
             $scope.pages=Math.ceil($scope.allPopUpDeals.length/$scope.numLimit);

            }
          });
          $scope.hideNext=function(){
            if(($scope.start+ $scope.numLimit) < $scope.allPopUpDeals.length){
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
          $scope.allPopUpDealsStatus = response.data.engineErrorMessage;
          $scope.allPopUpDealsCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allPopUpDealsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allPopUpDealsStatus = "Error Occured";
          $scope.allPopUpDealsCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allPopUpDealsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allPopUpDealsStatus = "Something's Wrong";
        $scope.allPopUpDealsCount = 0;
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allPopUpDealsStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadPopUpDeals();

  $scope.pop_up_deals = {};

  $scope.pop_up_deals.main_url = "reestoc.com";
  $scope.pop_up_deals.url_path = "";

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

  $scope.upload_pop_up_deal = function(){

    $scope.shouldIShow = true;

    $scope.continue_action = function () {

      $scope.pop_up_deals.url = $scope.pop_up_deals.main_url + $scope.pop_up_deals.url_path;

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
        uploadForm.append('url', $scope.pop_up_deals.url);
        angular.forEach($scope.filez, function(file){
            uploadForm.append('file[]', file);
        });
        $http.post('../../ng/server/data/pop_up_deals/add_new_pop_up_deal.php', uploadForm, {
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
            $scope.actionStatus = "Pop Up Deal added !";

            notify.do_notify($scope.result_u, "Add Activity", "Pop Up Deal added");

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

  $scope.get_pop_up_deal_image = function (url, image, file) {
    $scope.pop_up_deal_url = url;
    $scope.pop_up_deal_image = image;
    $scope.pop_up_deal_file = file;
  };

  $scope.delete_pop_up_deal = function (pop_up_deal_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "pop_up_deal_unique_id":pop_up_deal_unique_id,
      }

      $http.post('../../ng/server/data/pop_up_deals/remove_pop_up_deal.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "Pop Up Deal Removed");

            $timeout(function () {
              $scope.showSuccessStatus = undefined;
              $scope.actionStatus = undefined;
              // $route.reload();
              window.location.reload(true);
            }, 3000)

          }
          else if (response.data.engineError == 2) {
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
