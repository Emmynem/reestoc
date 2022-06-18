xnyder.controller('settingsCtrl',function($scope,$rootScope,$http,$timeout,$location,$routeParams,$route,storage,notify){

  $scope.loggedInName = "Admin";

  $rootScope.pageTitle = "Settings";

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

  $scope.logout = function () {

    $scope.logoutStatus = "Logging Out ...";

    notify.do_notify($scope.result_u, "Logout Activity", "User logged out successfully.");

    $timeout(function () {
      $scope.logoutStatus = undefined;
      storage.exit();
    }, 2000);
  };

  $scope.user = {};

  $scope.update_password = function () {

    if ($scope.user.password != $scope.user.confirmPassword) {
      $scope.things = true;
      $scope.passwordStatus = "Confirm Password Doesn't Match";
      $timeout(function () {
        $scope.passwordStatus = "";
      }, 3000);
    }
    else {

      $scope.things = undefined;

      $scope.clickOnce = true;

      $scope.verifyPasswordData = {
        "user_unique_id":$scope.result_u,
        "oldPassword":$scope.user.oldPassword
      }

      $http.post('server/verify_p.php', $scope.verifyPasswordData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {

          $scope.editUserPasswordData = {
            "user_unique_id":$scope.result_u,
            "edit_user_unique_id":$scope.result_u,
            "password":$scope.user.password
          }

          $http.post('server/edit_user_p.php', $scope.editUserPasswordData)
          .then(function (response) {
            if (response.data.engineMessage == 1) {
              $scope.showSuccessStatus = "Changes Saved !";
              notify.do_notify($scope.result_u, "Edit Activity", "User password updated");
              $timeout(function () {
                $scope.showStatus = undefined;
                $scope.showSuccessStatus = undefined;
                $route.reload();
              }, 3000)
            }
            else if (response.data.noData == 2){
              $scope.showStatus = "No User Found";
              $timeout(function () {
                $scope.showStatus = undefined;
                $scope.showSuccessStatus = undefined;
                $scope.clickOnce = false;
              }, 3000)
            }
            else {
              $scope.showStatus = "Error Occured";
              $timeout(function () {
                $scope.showStatus = undefined;
                $scope.showSuccessStatus = undefined;
                $scope.clickOnce = false;
              }, 3000)
            }
          }, function (error) {
            $scope.showStatus = "Something's Wrong";
            $timeout(function () {
              $scope.showStatus = undefined;
              $scope.showSuccessStatus = undefined;
              $scope.clickOnce = false;
            }, 3000)
          })

        }
        else if (response.data.notVerified == 3){
          $scope.showStatus = "Old password doesn't match !";
          $scope.things = true;
          $scope.passwordStatus = $scope.showStatus;
          $timeout(function () {
            $scope.showStatus = undefined;
            $scope.showSuccessStatus = undefined;
            $scope.passwordStatus = "";
            $scope.things = undefined;
            $scope.clickOnce = false;
          }, 3000)
        }
        else if (response.data.noData == 2){
          $scope.showStatus = "No User Found !";
          $timeout(function () {
            $scope.showStatus = undefined;
            $scope.showSuccessStatus = undefined;
            $scope.clickOnce = false;
          }, 3000)
        }
        else {
          $scope.showStatus = "Error Occured";
          $timeout(function () {
            $scope.showStatus = undefined;
            $scope.showSuccessStatus = undefined;
            $scope.clickOnce = false;
          }, 3000)
        }
      }, function (error) {
        $scope.showStatus = "Something's Wrong";
        $timeout(function () {
          $scope.showStatus = undefined;
          $scope.showSuccessStatus = undefined;
          $scope.clickOnce = false;
        }, 3000)
      })

    }

  };

});
