xnyder.controller('add-checkerCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$window,storage,notify){

  $rootScope.pageTitle = "Add New Checker";

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

  $scope.show_loader = true;

  $scope.remove_loader = function () {

    $timeout(function () {
      $scope.show_loader = undefined;
    }, 2000)

  };

  $scope.remove_loader();

  $scope.unlock_feature = false;

  $scope.checker = {};

  $scope.save_checker = function () {

    if ($scope.checker.password != $scope.checker.confirmPassword) {
      $scope.things = true;
      $scope.passwordStatus = "Confirm Password Doesn't Match";
      $timeout(function () {
        $scope.passwordStatus = "";
      }, 3000);
    }
    else {
      $scope.things = undefined;
      modalOpen('confirmationModal');

      $scope.removeConfirmModal = function () {
        modalClose('confirmationModal');
      };

      $scope.continue_action = function () {

        $scope.clickOnce = true;

        $scope.checkerData = {
          "edit_user_unique_id":$scope.result_u,
          "username":$scope.checker.username,
          "fullname":$scope.checker.fullname,
          "email":$scope.checker.email,
          "gender":$scope.checker.gender,
          "phone_number":$scope.checker.phone_number,
          "password":$scope.checker.password
        }

        $http.post('server/add_checker.php', $scope.checkerData)
          .then(function (response) {
            if (response.data.engineMessage == 1) {

              $scope.showSuccessStatus = true;
              $scope.actionStatus = "Checker Saved !";

              $timeout(function () {
                $scope.showSuccessStatus = undefined;
                $scope.actionStatus = undefined;
                $route.reload();
                $location.path('checkers');
              }, 3000)

            }
            else if (response.data.checkerAlreadyExists == 2) {
              $scope.showStatus = true;
              $scope.actionStatus = "Email / Username already exists";
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

    }

  };

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
        $scope.usersModalStatus = "Email not found in one account !";
        $timeout(function () {
          $scope.usersModalStatus = undefined;
          $scope.if_emails_exists = undefined;
        }, 3000);
      }

    }, 1000);

  };

  $scope.addChecker = function (obj) {

    $scope.getUserData = {
      "email":obj
    }

    $http.post("server/search_for_checker.php", $scope.getUserData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.show_add_form = true;

          $scope.user_details = xnyderAuth.getRequiredDetails(obj);

          if ($scope.user_details != undefined || $scope.user_details == "Not found") {


            $scope.addCheckerStatus = undefined;

            $scope.checker.firstname = $scope.user_details.firstname;
            $scope.checker.lastname = $scope.user_details.lastname;
            $scope.checker.email = $scope.user_details.emailAddress;
            $scope.checker.gender = $scope.user_details.gender;
            $scope.checker.phone = $scope.user_details.mobile;

            $scope.if_emails_exists = undefined;

          }
          else {
            $scope.addCheckerStatus = "Email not found in one account !";
            $scope.show_add_form = false;
            $scope.usersModalStatus = undefined;
            $scope.if_emails_exists = undefined;
            $timeout(function () {
              $route.reload();
            }, 3000);
          }

        }
        else if (response.data.alreadyExists == 2){
          $scope.show_add_form = false;
          $scope.addCheckerStatus = "Checker Already Exists !";
          $timeout(function () {
            $route.reload();
          }, 3000);
        }
        else {
          $scope.show_add_form = false;
          $scope.addCheckerStatus = "Couldn't look for user !";
          $timeout(function () {
            $route.reload();
          }, 3000);
        }
      }, function (error) {
        $scope.addCheckerStatus = "Something's Wrong !";
        $timeout(function () {
          $route.reload();
        }, 3000);
      })
  };

  $scope.saveChecker = function(){

    $scope.shouldIShow = true;

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.checkerData = {
        "edit_user_unique_id":$scope.result_u,
        "username":$scope.checker.username,
        "fullname":$scope.checker.firstname + ' ' + $scope.checker.lastname,
        "email":$scope.checker.email,
        "gender":$scope.checker.gender,
        "phone_number":$scope.checker.phone
      }

      $http.post('server/add_checker.php', $scope.checkerData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = "Ticket Checker Added !";

            notify.do_notify($scope.result_u, "Add Activity", "Ticket Checker added");

            $timeout(function () {
              $scope.showSuccessStatus = undefined;
              $scope.showStatus = undefined;
              $scope.clickOnce = false;
              $location.path('checkers');
            }, 3000);
          }
          else if (response.data.userAlreadyExists == 2){
            $scope.showStatus = "Email / Username already exists";

            $timeout(function () {
              $scope.showSuccessStatus = undefined;
              $scope.showStatus = undefined;
              $scope.clickOnce = false;
            }, 3000);
          }
          else {
            $scope.showStatus = "Couldn't add checker !";

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
