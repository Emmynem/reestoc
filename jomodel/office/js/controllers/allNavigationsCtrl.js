xnyder.controller('all-navigationsCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$window,storage,notify){

  $rootScope.pageTitle = "All Navigations";

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

  $scope.loadNavigations = function () {

    $http.get('../../ng/server/get/get_all_management_navigations.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allNavigationsFullStatus = undefined;
          $scope.allNavigationsFull = response.data.resultData;

          $scope.remove_loader();

          $scope.currentPage=1;
          $scope.numLimit=20;
          $scope.start = 0;
          $scope.$watch('allNavigationsFull',function(newVal){
            if(newVal){
             $scope.pages=Math.ceil($scope.allNavigationsFull.length/$scope.numLimit);

            }
          });
          $scope.hideNext=function(){
            if(($scope.start+ $scope.numLimit) < $scope.allNavigationsFull.length){
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
          $scope.allNavigationsFullStatus = response.data.engineErrorMessage;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allNavigationsFullStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allNavigationsFullStatus = "Error Occured";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allNavigationsFullStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allNavigationsFullStatus = "Something's Wrong";
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allNavigationsFullStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadNavigations();

  $scope.navigation = {};

  $scope.edit_navigation = function (navigation_unique_id, navigation_title, navigation_link, navigation_icon) {

    $scope.navigation.nav_title = navigation_title;
    $scope.navigation.nav_link = navigation_link;
    $scope.navigation.nav_icon = navigation_icon;

    $scope.go_ahead = function () {
      $scope.shouldIShow = true;
    };

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.navigationData = {
        "user_unique_id":$scope.result_u,
        "navigation_unique_id":navigation_unique_id,
        "nav_title":$scope.navigation.nav_title,
        "nav_link":$scope.navigation.nav_link,
        "nav_icon":$scope.navigation.nav_icon
      }

      $http.post('../../ng/server/data/management_navigation/update_nav_link.php', $scope.navigationData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {

          $scope.showSuccessStatus = true;
          $scope.actionStatus = "Changes Saved !";

          notify.do_notify($scope.result_u, "Edit Activity", "Navigation Link edited");

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
  }

  $scope.delete_navigation = function (navigation_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "navigation_unique_id":navigation_unique_id
      }

      $http.post('../../ng/server/data/management_navigation/remove_nav_link.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "Navigation Link removed");

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

  $scope.restore_navigation = function (navigation_unique_id) {

    // modalOpen('restoreModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "navigation_unique_id":navigation_unique_id
      }

      $http.post('../../ng/server/data/management_navigation/restore_nav_link.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Add Activity", "Navigation Link restored");

            $timeout(function () {
              $scope.showSuccessStatus = undefined;
              $scope.actionStatus = undefined;
              window.location.reload(true);
              // $route.reload();
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
