xnyder.controller('accountCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$window,storage,notify){

  $rootScope.pageTitle = "Accounting";

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

  $scope.filterShow = false;

//   let str = "This is a good user's news item";
// let new_str = str.replace(/ /g, '-');
// new_str.toLowerCase();

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
        "table":"account",
        "startdate":$scope.filter_var.startdate,
        "enddate":$scope.filter_var.enddate
      }

      $http.post('server/filter_acc.php', $scope.filter_data)
        .then(function (response) {
          if (response.data.engineMessage == 1) {
            $scope.allAccountsStatus = undefined;
            $scope.allAccounts = response.data.filteredData;
            $scope.plusSum = response.data.plusSum;
            $scope.minusSum = response.data.minusSum;
            $scope.remainingSum = $scope.plusSum - $scope.minusSum;
            $scope.totalCount = response.data.totalCount;

            $scope.remove_loader();

            $scope.currentPage=1;
            $scope.numLimit=20;
            $scope.start = 0;
            $scope.$watch('allAccounts',function(newVal){
              if(newVal){
               $scope.pages=Math.ceil($scope.allAccounts.length/$scope.numLimit);

              }
            });
            $scope.hideNext=function(){
              if(($scope.start+ $scope.numLimit) < $scope.allAccounts.length){
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
            $scope.allAccountsStatus = "No data in range !";
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allAccountsStatus = undefined;
            // }, 3000)
          }
          else {
            $scope.allAccountsStatus = "Couldn't get data";
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allAccountsStatus = undefined;
            // }, 3000)
          }
        }, function (error) {
          $scope.allAccountsStatus = "Something's Wrong";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allAccountsStatus = undefined;
          // }, 3000)
        })
    }
  };

  $scope.loadAccount = function () {

    $http.get('server/get_account.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allAccountsStatus = undefined;
          $scope.allAccounts = response.data.re_data;
          $scope.plusSum = response.data.plusSum;
          $scope.minusSum = response.data.minusSum;
          $scope.remainingSum = $scope.plusSum - $scope.minusSum;
          $scope.totalCount = response.data.totalCount;

          $scope.remove_loader();

          $scope.currentPage=1;
          $scope.numLimit=20;
          $scope.start = 0;
          $scope.$watch('allAccounts',function(newVal){
            if(newVal){
             $scope.pages=Math.ceil($scope.allAccounts.length/$scope.numLimit);

            }
          });
          $scope.hideNext=function(){
            if(($scope.start+ $scope.numLimit) < $scope.allAccounts.length){
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
          $scope.allAccountsStatus = "No records found !";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allAccountsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allAccountsStatus = "Error Occured";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allAccountsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allAccountsStatus = "Something's Wrong";
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allAccountsStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadAccount();

  $scope.account = {};

  $scope.show_item = false;

  $scope.hide_item = function () {
    if ($scope.account.type == "Income") {
      $scope.show_item = false;
    }
    else {
      $scope.show_item = true;
    }
  };

  $scope.saveRecord = function () {

    $scope.shouldIShow = true;

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.accountData = {
        "user_unique_id":$scope.result_u,
        "edit_user_unique_id":$scope.result_u,
        "item":$scope.account.item,
        "acc_type":$scope.account.type,
        "description":$scope.account.description,
        "amount":$scope.account.amount
      }

      $http.post("server/add_account.php", $scope.accountData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = $scope.account.type + " Record Saved !";
            $scope.for_notification = $scope.account.type + " record saved";

            notify.do_notify($scope.result_u, "Add Activity", $scope.for_notification);

            $timeout(function () {
              $scope.showSuccessStatus = undefined;
              $scope.showStatus = undefined;
              $scope.clickOnce = false;
              window.location.reload(true);
            }, 3000);
          }
          else {
            $scope.showStatus = "Couldn't record !";

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

  $scope.deleteRecord = function (unique_id, acc_type) {

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.deleted = 0;

      $scope.deleteData = {
        "unique_id":unique_id,
        "user_unique_id":$scope.result_u,
        "acc_type":acc_type,
        "status":$scope.deleted
      }

      $http.post('server/delete_account.php', $scope.deleteData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = acc_type + " Record Deleted !";
            $scope.for_notification = acc_type + " record deleted";

            notify.do_notify($scope.result_u, "Delete Activity", $scope.for_notification);

            $timeout(function () {
              $scope.showSuccessStatus = undefined;
              $scope.showStatus = undefined;
              $scope.clickOnce = false;
              window.location.reload(true);
            }, 3000);
          }
          else if (response.data.noData == 2) {
            $scope.showStatus = "No data found";
            $timeout(function () {
              $scope.showStatus = undefined;
              $scope.showSuccessStatus = undefined;
            }, 3000)
          }
          else {
            $scope.showStatus = "Error Occured";
            $timeout(function () {
              $scope.showStatus = undefined;
              $scope.showSuccessStatus = undefined;
            }, 3000)
          }
        }, function (error) {
          $scope.showStatus = "Something's Wrong";
          $timeout(function () {
            $scope.showStatus = undefined;
            $scope.showSuccessStatus = undefined;
          }, 3000)
        })

    };

  };

});
