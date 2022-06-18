xnyder.controller('newsletterCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$window,$sanitize,storage,notify){

  $rootScope.pageTitle = "Newsletter";

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

      $scope.filter_data = {
        "table":"newsletter",
        "startdate":$scope.filter_var.startdate,
        "enddate":$scope.filter_var.enddate
      }

      $http.post('server/filter.php', $scope.filter_data)
        .then(function (response) {
          if (response.data.engineMessage == 1) {
            $scope.allNewsletterStatus = undefined;
            $scope.allNewsletter = response.data.filteredData;

            $scope.remove_loader();

            $scope.currentPage=1;
            $scope.numLimit=50;
            $scope.start = 0;
            $scope.$watch('allNewsletter',function(newVal){
              if(newVal){
               $scope.pages=Math.ceil($scope.allNewsletter.length/$scope.numLimit);

              }
            });
            $scope.hideNext=function(){
              if(($scope.start+ $scope.numLimit) < $scope.allNewsletter.length){
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
            $scope.allNewsletterStatus = "No data in range !";
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allNewsletterStatus = undefined;
            // }, 3000)
          }
          else {
            $scope.allNewsletterStatus = "Couldn't get data";
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allNewsletterStatus = undefined;
            // }, 3000)
          }
        }, function (error) {
          $scope.allNewsletterStatus = "Something's Wrong";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allNewsletterStatus = undefined;
          // }, 3000)
        })
    }
  };

  $scope.loadNewsletter = function () {

    $http.get('server/get_newsletter.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allNewsletterStatus = undefined;
          $scope.allNewsletter = response.data.re_data;

          $scope.remove_loader();

          $scope.currentPage=1;
          $scope.numLimit=50;
          $scope.start = 0;
          $scope.$watch('allNewsletter',function(newVal){
            if(newVal){
             $scope.pages=Math.ceil($scope.allNewsletter.length/$scope.numLimit);

            }
          });
          $scope.hideNext=function(){
            if(($scope.start+ $scope.numLimit) < $scope.allNewsletter.length){
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
          $scope.allNewsletterStatus = "No emails found !";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allNewsletterStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allNewsletterStatus = "Error Occured";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allNewsletterStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allNewsletterStatus = "Something's Wrong";
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allNewsletterStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadNewsletter();

});
