xnyder.controller('enquiriesCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$window,storage,notify){

  $rootScope.pageTitle = "Enquiries";

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
    $scope.loadEnquiries();
  };

  $scope.filterShow = false;

  $scope.filterEnquiryShow = false;

  $scope.filter_var = {};

  $scope.enquiry_data = {};

  $scope.filterByDate = function () {
    $scope.filter_var.startdate = new Date();
    $scope.filter_var.startdate.setHours(0, 0, 0, 0);
    $scope.filter_var.enddate = new Date();
    $scope.filter_var.enddate.setHours(23, 59, 59, 999);
    $scope.filterShow = true;
  };

  $scope.filterByEnquiryStatus = function () {
    $scope.filterEnquiryShow = true;
  };

  $scope.filter = function () {

    if ($scope.filter_var.startdate > $scope.filter_var.enddate) {
      $scope.filterShow = true;
    }
    else {

      $scope.filterShow = false;

      $scope.show_loader = true;

      $scope.filter_data = {
        "table":"enquiries",
        "startdate":$scope.filter_var.startdate,
        "enddate":$scope.filter_var.enddate
      }

      $http.post('server/filter.php', $scope.filter_data)
        .then(function (response) {
          if (response.data.engineMessage == 1) {
            $scope.allEnquiriesStatus = undefined;
            $scope.allEnquiries = response.data.filteredData;

            $scope.remove_loader();

            $scope.currentPage=1;
            $scope.numLimit=50;
            $scope.start = 0;
            $scope.$watch('allEnquiries',function(newVal){
              if(newVal){
               $scope.pages=Math.ceil($scope.allEnquiries.length/$scope.numLimit);

              }
            });
            $scope.hideNext=function(){
              if(($scope.start+ $scope.numLimit) < $scope.allEnquiries.length){
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
            $scope.allEnquiriesStatus = "No data in range !";
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allEnquiriesStatus = undefined;
            // }, 3000)
          }
          else {
            $scope.allEnquiriesStatus = "Couldn't get data";
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allEnquiriesStatus = undefined;
            // }, 3000)
          }
        }, function (error) {
          $scope.allEnquiriesStatus = "Something's Wrong";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allEnquiriesStatus = undefined;
          // }, 3000)
        })
    }
  };

  $scope.filterEnquiryStatus = function () {

    $scope.filterEnquiryShow = false;

    $scope.show_loader = true;

    $scope.filter_data = {
      "table":"enquiries",
      "enquiry_status":$scope.enquiry_data.status
    }

    $http.post('server/filter_enquiry.php', $scope.filter_data)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allEnquiriesStatus = undefined;
          $scope.allEnquiries = response.data.filteredData;

          $scope.remove_loader();

          $scope.currentPage=1;
          $scope.numLimit=20;
          $scope.start = 0;
          $scope.$watch('allEnquiries',function(newVal){
            if(newVal){
             $scope.pages=Math.ceil($scope.allEnquiries.length/$scope.numLimit);

            }
          });
          $scope.hideNext=function(){
            if(($scope.start+ $scope.numLimit) < $scope.allEnquiries.length){
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
          $scope.allEnquiriesStatus = "No data in range !";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allEnquiriesStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allEnquiriesStatus = "Couldn't get data";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allEnquiriesStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allEnquiriesStatus = "Something's Wrong";
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allEnquiriesStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadEnquiries = function () {

    $http.get('server/get_enquiries.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allEnquiriesStatus = undefined;
          $scope.allEnquiries = response.data.re_data;

          $scope.remove_loader();

          $scope.currentPage=1;
          $scope.numLimit=20;
          $scope.start = 0;
          $scope.$watch('allEnquiries',function(newVal){
            if(newVal){
             $scope.pages=Math.ceil($scope.allEnquiries.length/$scope.numLimit);

            }
          });
          $scope.hideNext=function(){
            if(($scope.start+ $scope.numLimit) < $scope.allEnquiries.length){
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
          $scope.allEnquiriesStatus = "No enquiries found !";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allEnquiriesStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allEnquiriesStatus = "Error Occured";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allEnquiriesStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allEnquiriesStatus = "Something's Wrong";
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allEnquiriesStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadEnquiries();

  $scope.view_enquiry = function (unique_id) {

    // modalOpen('viewEnquiryModal', 'large');

    $scope.enquiryData = {
      "table":"enquiries",
      "unique_id":unique_id
    }

    $http.post("server/get_an_enquiry.php", $scope.enquiryData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.showStatus = undefined;
          $scope.enquiry_details = response.data.re_data;
        }
        else if (response.data.noData == 2) {
          $scope.showStatus = "Couldn't get details !";
          // $timeout(function () {
          //   $scope.showStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.showStatus = "Error Occured";
          // $timeout(function () {
          //   $scope.showStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.showStatus = "Something's Wrong";
        // $timeout(function () {
        //   $scope.showStatus = undefined;
        // }, 3000)
      })

  };

  $scope.complete_enquiry = function (unique_id) {

    // modalOpen('completeEnquiryModal');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      // $scope.showLoadingStatus = "Sending email ...";

      $scope.completed = "Completed";

      $scope.confirmData = {
        "table":"enquiries",
        "unique_id":unique_id,
        "enquiry_status":$scope.completed
      }

      $http.post('server/complete_enquiry.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {
            $scope.user_details = response.data.re_data;

            $scope.email_subject = "Reply On : " + $scope.user_details.subject;

            $scope.sender_username = $scope.loggedInName + " from Hello Beautiful World";

            $scope.requestData = {
              "fullname":$scope.user_details.name,
              "email":$scope.user_details.email,
              "subject":$scope.email_subject,
              "username":$scope.sender_username
            }

             $http.post('server/auto_email_reply.php', $scope.requestData)
              .then(function success(response) {
                if (response.data.engineMessage == 1) {

                  // $scope.showSuccessStatus = true;
                  // $scope.actionStatus = "Action Completed";
                  //
                  // $timeout(function () {
                  //   $scope.showSuccessStatus = undefined;
                  //   $scope.showLoadingStatus = undefined;
                  //   $scope.actionStatus = undefined;
                  //   window.location.reload(true);
                  // }, 3000)

                  // $scope.showSuccessStatus = true;
                  // $scope.confirmStatus = "Request Sent";
                  // $timeout(function() {
                  //   $scope.showSuccessStatus = undefined;
                  //   $scope.confirmStatus = "";
                  //   window.location.reload(true);
                  // }, 3000)
                }
                else if (response.data.error == 2) {
                  // $scope.showStatus = true;
                  // $scope.resetStatus = "Error Occured";
                  // $timeout(function() {
                  //   $scope.showStatus = undefined;
                  //   $scope.resetStatus = "";
                  //   window.location.reload(true);
                  // }, 3000)

                  // $scope.showLoadingStatus = undefined;
                  // $scope.showStatus = true;
                  // $scope.actionStatus = "Not sent";
                  // $timeout(function () {
                  //   $scope.showStatus = undefined;
                  //   $scope.actionStatus = undefined;
                  // }, 3000)
                }
              }, function error(response) {
                // $scope.showStatus = true;
                // $scope.resetStatus = "Something's Wrong";
                // $timeout(function() {
                //   $scope.showStatus = undefined;
                //   $scope.resetStatus = "";
                //   window.location.reload(true);
                // }, 3000)

                // $scope.showLoadingStatus = undefined;
                // $scope.showStatus = true;
                // $scope.actionStatus = "Error occured";
                // $timeout(function () {
                //   $scope.showStatus = undefined;
                //   $scope.actionStatus = undefined;
                // }, 3000)
              })

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";

            $timeout(function () {
              $scope.showSuccessStatus = undefined;
              $scope.actionStatus = undefined;
              window.location.reload(true);
            }, 3000)

          }
          else if (response.data.noData == 2) {
            $scope.showStatus = true;
            $scope.actionStatus = "No Data Found";
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
