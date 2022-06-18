xnyder.controller('ridersCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$routeParams,$window,storage,notify){

  $rootScope.pageTitle = "Riders";

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
    $scope.loadRiders();
  };

  $scope.loadRiders = function () {

    $http.get('../../ng/server/get/get_all_riders.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allRidersStatus = undefined;
          $scope.allRiders = response.data.resultData;
          $scope.allRidersCount = $scope.allRiders.length;

          $scope.remove_loader();

          $scope.currentPage=1;
          $scope.numLimit=100;
          $scope.start = 0;
          $scope.$watch('allRiders',function(newVal){
            if(newVal){
             $scope.pages=Math.ceil($scope.allRiders.length/$scope.numLimit);

            }
          });
          $scope.hideNext=function(){
            if(($scope.start+ $scope.numLimit) < $scope.allRiders.length){
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
          $scope.allRidersStatus = response.data.engineErrorMessage;
          $scope.allRidersCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allRidersStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allRidersStatus = "Error Occured";
          $scope.allRidersCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allRidersStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allRidersStatus = "Something's Wrong";
        $scope.allRidersCount = 0;
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allRidersStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadRiders();

  $scope.rider = {};

  $scope.edit_rider_details = function(edit_user_unique_id, unique_id, role, access) {

    $scope.rider.role = role;
    $scope.rider.access = access;

    $scope.go_ahead = function () {
      $scope.shouldIShow = true;

      $scope.continue_action = function () {

        $scope.clickOnce = true;

        $scope.genericData = {
          "edit_user_unique_id":$scope.result_u,
          "rider_unique_id":unique_id,
          "role":$scope.rider.role,
          "access":$scope.rider.access
        }

        $http.post('../../ng/server/data/riders/update_rider_details.php', $scope.genericData)
          .then(function (response) {
            if (response.data.engineMessage == 1) {

              $scope.showSuccessStatus = true;
              $scope.actionStatus = "Action Completed";
              notify.do_notify($scope.result_u, "Edit Activity", "Updated rider - ("+ unique_id +") details");

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

    };

    $scope.removeConfirmModal = function () {

      $scope.shouldIShow = undefined;

    };

  };

  $scope.delete_rider = function (rider_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "edit_user_unique_id":$scope.result_u,
        "rider_unique_id":rider_unique_id,
      }

      $http.post('../../ng/server/data/riders/remove_rider.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "Rider ("+ rider_unique_id +") Removed");

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

  $scope.restore_rider = function (rider_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "edit_user_unique_id":$scope.result_u,
        "rider_unique_id":rider_unique_id,
      }

      $http.post('../../ng/server/data/riders/restore_rider.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Add Activity", "Rider ("+ rider_unique_id +") Restored");

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
