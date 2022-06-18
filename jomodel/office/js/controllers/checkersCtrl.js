xnyder.controller('checkersCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$window,storage,notify){

  $rootScope.pageTitle = "Checkers";

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

  $scope.filterShow = false;

  $scope.filter_var = {};

  $scope.unlock_feature = false;

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
        "table":"checkers",
        "startdate":$scope.filter_var.startdate,
        "enddate":$scope.filter_var.enddate
      }

      $http.post('server/filter_checkers.php', $scope.filter_data)
        .then(function (response) {
          if (response.data.engineMessage == 1) {
            $scope.allCheckersStatus = undefined;
            $scope.allCheckers = response.data.filteredData;

            $scope.remove_loader();

            $scope.currentPage=1;
            $scope.numLimit=20;
            $scope.start = 0;
            $scope.$watch('allCheckers',function(newVal){
              if(newVal){
               $scope.pages=Math.ceil($scope.allCheckers.length/$scope.numLimit);

              }
            });
            $scope.hideNext=function(){
              if(($scope.start+ $scope.numLimit) < $scope.allCheckers.length){
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
          else if (response.data.noData == 2) {
            $scope.allCheckersStatus = "No data in range !";
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allCheckersStatus = undefined;
            // }, 3000)
          }
          else {
            $scope.allCheckersStatus = "Couldn't get data";
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allCheckersStatus = undefined;
            // }, 3000)
          }
        }, function (error) {
          $scope.allCheckersStatus = "Something's Wrong";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allCheckersStatus = undefined;
          // }, 3000)
        })
    }
  };

  $scope.loadCheckers = function () {

    $http.get('server/get_checkers.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allCheckersStatus = undefined;
          $scope.allCheckers = response.data.re_data;

          $scope.remove_loader();

          $scope.currentPage=1;
          $scope.numLimit=20;
          $scope.start = 0;
          $scope.$watch('allCheckers',function(newVal){
            if(newVal){
             $scope.pages=Math.ceil($scope.allCheckers.length/$scope.numLimit);

            }
          });
          $scope.hideNext=function(){
            if(($scope.start+ $scope.numLimit) < $scope.allCheckers.length){
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
        else if (response.data.noData == 2) {
          $scope.allCheckersStatus = "No checkers found !";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allCheckersStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allCheckersStatus = "Error Occured";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allCheckersStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allCheckersStatus = "Something's Wrong";
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allCheckersStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadCheckers();

  $scope.checker = {};

  $scope.edit_checker_details = function (user_unique_id, fullname, phone_number, access) {
    $scope.checker.fullname = fullname;
    $scope.checker.phone_number = phone_number;
    $scope.checker.access = access;

    // modalOpen("editCheckerDetailsModal", "medium");
    $scope.shouldIShow = false;

    $scope.saveCheckerDetails = function () {

      $scope.shouldIShow = true;

      $scope.continue_action = function () {

        $scope.clickOnce = true;

        $scope.editCheckerDetailsData = {
          "user_unique_id":user_unique_id,
          "edit_user_unique_id":$scope.result_u,
          "access":$scope.checker.access
        }

        $http.post('server/edit_checker_details.php', $scope.editCheckerDetailsData)
          .then(function (response) {
            if (response.data.engineMessage == 1) {
              $scope.showSuccessStatus = "Changes Saved !";

              notify.do_notify($scope.result_u, "Edit Activity", "Ticket Checker details edited");

              $timeout(function () {
                $scope.showStatus = undefined;
                $scope.showSuccessStatus = undefined;
                $rootScope.imageStatus = undefined;
                window.location.reload(true);
              }, 3000)
            }
            else if (response.data.noData == 2){
              $scope.showStatus = "No Checker Found";
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

      };

      $scope.removeConfirmModal = function () {

        $scope.shouldIShow = undefined;

      };

    };

  };

  $scope.edit_checker_password = function (user_unique_id) {

    modalOpen("editCheckerPasswordModal", "medium");
    $scope.shouldIShow = false;

    $scope.removeEditCheckerPasswordModal = function () {
      modalClose("editCheckerPasswordModal");
    };

    $scope.saveCheckerPassword = function () {

      if ($scope.checker.password != $scope.checker.confirmPassword) {
        $scope.things = true;
        $scope.passwordStatus = "Confirm Password Doesn't Match";
        $timeout(function () {
          $scope.passwordStatus = "";
        }, 3000);
      }
      else {

        $scope.things = undefined;

        $scope.shouldIShow = true;

        $scope.continue_action = function () {

          $scope.clickOnce = true;

          $scope.editCheckerPasswordData = {
            "user_unique_id":user_unique_id,
            "edit_user_unique_id":$scope.result_u,
            "password":$scope.checker.password
          }

          $http.post('server/edit_checker_password.php', $scope.editCheckerPasswordData)
          .then(function (response) {
            if (response.data.engineMessage == 1) {
              $scope.showSuccessStatus = "Changes Saved !";
              $timeout(function () {
                $scope.showStatus = undefined;
                $scope.showSuccessStatus = undefined;
                modalClose('editCheckerPasswordModal');
                $route.reload();
              }, 3000)
            }
            else if (response.data.noData == 2){
              $scope.showStatus = "No Checker Found";
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

        };

      }

      $scope.removeConfirmModal = function () {

        $scope.shouldIShow = undefined;

      };

    };

  };

  $scope.addChecker = function () {
    $location.path("add-checker");
  };

});
