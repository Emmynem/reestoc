xnyder.controller('usersCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$routeParams,$window,storage,notify){

  $rootScope.pageTitle = "Users";

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
    $scope.loadUsers();
  };

  $scope.filterShow = false;

  $scope.filter_var = {};

  $scope.filterByDate = function () {
    $scope.filter_var.startdate = new Date();
    $scope.filter_var.startdate.setHours(0, 0, 0, 0);
    $scope.filter_var.enddate = new Date();
    $scope.filter_var.enddate.setHours(23, 59, 59, 999);
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

      $http.post('../../ng/server/get/get_users_filter.php', $scope.filter_data)
        .then(function (response) {
          if (response.data.engineMessage == 1) {
            $scope.allUsersStatus = undefined;
            $scope.allUsers = response.data.resultData;
            $scope.allUsersCount = $scope.allUsers.length;

            $scope.remove_loader();

            $scope.currentPage=1;
            $scope.numLimit=100;
            $scope.start = 0;
            $scope.$watch('allUsers',function(newVal){
              if(newVal){
               $scope.pages=Math.ceil($scope.allUsers.length/$scope.numLimit);

              }
            });
            $scope.hideNext=function(){
              if(($scope.start+ $scope.numLimit) < $scope.allUsers.length){
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
            $scope.allUsersStatus = "No data in range !";
            $scope.allUsersCount = 0;
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allUsersStatus = undefined;
            // }, 3000)
          }
          else {
            $scope.allUsersStatus = "Couldn't get data";
            $scope.allUsersCount = 0;
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allUsersStatus = undefined;
            // }, 3000)
          }
        }, function (error) {
          $scope.allUsersStatus = "Something's Wrong";
          $scope.allUsersCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allUsersStatus = undefined;
          // }, 3000)
        })
    }
  };

  $scope.loadUsers = function () {

    $http.get('../../ng/server/get/get_all_users.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allUsersStatus = undefined;
          $scope.allUsers = response.data.resultData;
          $scope.allUsersCount = $scope.allUsers.length;

          $scope.remove_loader();

          $scope.currentPage=1;
          $scope.numLimit=100;
          $scope.start = 0;
          $scope.$watch('allUsers',function(newVal){
            if(newVal){
             $scope.pages=Math.ceil($scope.allUsers.length/$scope.numLimit);

            }
          });
          $scope.hideNext=function(){
            if(($scope.start+ $scope.numLimit) < $scope.allUsers.length){
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
          $scope.allUsersStatus = response.data.engineErrorMessage;
          $scope.allUsersCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allUsersStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allUsersStatus = "Error Occured";
          $scope.allUsersCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allUsersStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allUsersStatus = "Something's Wrong";
        $scope.allUsersCount = 0;
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allUsersStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadUsers();

  $scope.user = {};

  $scope.edit_user_details = function(edit_user_unique_id, unique_id, access) {

    $scope.user.access = access;

    $scope.go_ahead = function () {
      $scope.shouldIShow = true;

      $scope.continue_action = function () {

        $scope.clickOnce = true;

        $scope.genericData = {
          "edit_user_unique_id":$scope.result_u,
          "user_unique_id":unique_id,
          "access":$scope.user.access
        }

        $http.post('../../ng/server/data/users/update_user_access.php', $scope.genericData)
          .then(function (response) {
            if (response.data.engineMessage == 1) {

              $scope.showSuccessStatus = true;
              $scope.actionStatus = "Action Completed";
              notify.do_notify($scope.result_u, "Edit Activity", "Updated user - ("+ unique_id +") access");

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

  $scope.delete_user = function (user_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "edit_user_unique_id":$scope.result_u,
        "user_unique_id":user_unique_id,
      }

      $http.post('../../ng/server/data/users/remove_user.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "User ("+ user_unique_id +") Removed");

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

  $scope.restore_user = function (user_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "edit_user_unique_id":$scope.result_u,
        "user_unique_id":user_unique_id,
      }

      $http.post('../../ng/server/data/users/restore_user.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Add Activity", "User ("+ user_unique_id +") Restored");

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
