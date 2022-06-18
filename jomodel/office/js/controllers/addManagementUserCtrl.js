xnyder.controller('add-management-userCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$routeParams,$window,storage,notify){

  $rootScope.pageTitle = "Add Management User";

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

  $scope.management_user = {};

  $scope.loadDetails = function (email) {

    $scope.if_emails_exists = undefined;

    $scope.show_search_loader = true;

    $timeout(function () {

      $scope.user_details = xnyderAuth.getRequiredDetails(email);

      if ($scope.user_details != undefined || $scope.user_details == "Not found") {
        $scope.if_emails_exists = false;

        $scope.show_search_loader = false;

        $scope.email = {};

        $scope.email.fullname = $scope.user_details.fullname;
        $scope.email.email = $scope.user_details.emailAddress;
        $scope.user_image = $scope.user_details.profileImageWebp;

      }
      else {
        $scope.show_search_loader = false;

        $scope.if_emails_exists = true;
        $scope.managementUsersModalStatus = "Email not found in one account !";
        $timeout(function () {
          $scope.managementUsersModalStatus = undefined;
          $scope.if_emails_exists = undefined;
        }, 3000);
      }

    }, 1000);

  };

  $scope.addManagementUser = function (obj) {

    $scope.getUserData = {
      "email":obj,
      "phone_number":obj
    }

    $http.post("../../ng/server/data/management/search_for_management_user.php", $scope.getUserData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.show_add_form = true;

          $scope.user_details = xnyderAuth.getRequiredDetails(obj);

          if ($scope.user_details != undefined || $scope.user_details == "Not found") {

            $scope.addUserStatus = undefined;

            $scope.management_user.firstname = $scope.user_details.firstname;
            $scope.management_user.lastname = $scope.user_details.lastname;
            $scope.management_user.email = $scope.user_details.emailAddress;
            // $scope.management_user.gender = $scope.user_details.gender;
            $scope.management_user.phone = $scope.user_details.mobile;

            $scope.if_emails_exists = undefined;

          }
          else {
            $scope.addUserStatus = "Email not found in one account !";
            $scope.show_add_form = false;
            $scope.managementUsersModalStatus = undefined;
            $scope.if_emails_exists = undefined;
            $timeout(function () {
              $route.reload();
            }, 3000);
          }

        }
        else if (response.data.alreadyExists == 2){
          $scope.show_add_form = false;
          $scope.addUserStatus = "Management User Already Exists !";
          $timeout(function () {
            $route.reload();
          }, 3000);
        }
        else {
          $scope.show_add_form = false;
          $scope.addUserStatus = "Couldn't look for user !";
          $timeout(function () {
            $route.reload();
          }, 3000);
        }
      }, function (error) {
        $scope.addUserStatus = "Something's Wrong !";
        $timeout(function () {
          $route.reload();
        }, 3000);
      })
  };

  $scope.saveManagementUser = function(){

    $scope.shouldIShow = true;

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.getUserData = {
        "edit_user_unique_id":$scope.result_u,
        "fullname":$scope.management_user.firstname + ' ' + $scope.management_user.lastname,
        "email":$scope.management_user.email,
        "phone_number":$scope.management_user.phone,
        "role":$scope.management_user.role,
        "access":$scope.management_user.access
      }

      $http.post("../../ng/server/data/management/add_new_management_user.php", $scope.getUserData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = "Management User !";

            notify.do_notify($scope.result_u, "Add Activity", "Management User added");

            $timeout(function () {
              $scope.showSuccessStatus = undefined;
              $scope.showStatus = undefined;
              $scope.clickOnce = false;
              $location.path('management-users');
            }, 2000);
          }
          else if (response.data.engineError == 2){
            $scope.showStatus = response.data.engineErrorMessage;

            $timeout(function () {
              $scope.showSuccessStatus = undefined;
              $scope.showStatus = undefined;
              $scope.clickOnce = false;
            }, 3000);
          }
          else {
            $scope.showStatus = "Couldn't add management user !";

            $timeout(function () {
              $scope.showSuccessStatus = undefined;
              $scope.showStatus = undefined;
              $scope.clickOnce = false;
            }, 3000);
          }
        }, function (error) {
          $scope.showStatus = "Something's Wrong !";

          $timeout(function () {
            $scope.showSuccessStatus = undefined;
            $scope.showStatus = undefined;
            $scope.clickOnce = false;
          }, 3000);
        })

    };

    $scope.removeConfirmModal = function () {

      $scope.shouldIShow = undefined;

    };

  };

});
