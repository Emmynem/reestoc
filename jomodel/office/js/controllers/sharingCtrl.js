xnyder.controller('sharingCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$routeParams,$window,storage,notify){

  $rootScope.pageTitle = "Sharing";

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
    $scope.loadSharing();
  };

  // Gets full date and time of the now, format "2021-09-20 22:00:00"
  $scope.get_today = function () {

    $scope.today = new Date();

    $scope.year = $scope.today.getFullYear();
    $scope.month = $scope.today.getMonth() + 1 < 10 ? "-0" + ($scope.today.getMonth() + 1) : "-" + ($scope.today.getMonth() + 1);
    $scope.date = $scope.today.getDate() < 10 ? "-0" + $scope.today.getDate() : "-" + $scope.today.getDate();

    $scope.hours = $scope.today.getHours() < 10 ? "0" + $scope.today.getHours() : $scope.today.getHours();
    $scope.minutes = $scope.today.getMinutes() < 10 ? "0" + $scope.today.getMinutes() : $scope.today.getMinutes();
    $scope.seconds = $scope.today.getSeconds() < 10 ? "0" + $scope.today.getSeconds() : $scope.today.getSeconds();

    $scope.full_date = $scope.year + $scope.month + $scope.date;
    $scope.full_time = $scope.hours + ":" + $scope.minutes + ":" + $scope.seconds;

    $scope.minimum_date = $scope.full_date + "T" + $scope.hours + ":" + $scope.minutes;

    $scope.full_date_and_time = $scope.full_date + " " + $scope.full_time;

    $scope.todays_date = $scope.full_date_and_time;

  };

  $scope.get_today();

  $scope.filterShow = false;
  $scope.filterExpiryDateShow = false;

  $scope.filter_var = {};
  $scope.filter_expiry_date_var = {};

  $scope.filterByDate = function () {
    $scope.filter_var.startdate = new Date();
    $scope.filter_var.startdate.setHours(0, 0, 0, 0);
    $scope.filter_var.enddate = new Date();
    $scope.filter_var.enddate.setHours(23, 59, 0, 0);
    $scope.filterShow = true;
  };

  $scope.filterByExpiryDate = function () {
    $scope.filter_expiry_date_var.startdate = new Date();
    $scope.filter_expiry_date_var.startdate.setHours(0, 0, 0, 0);
    $scope.filter_expiry_date_var.enddate = new Date();
    $scope.filter_expiry_date_var.enddate.setHours(23, 59, 0, 0);
    $scope.filterExpiryDateShow = true;
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

      $http.post('../../ng/server/get/get_sharing_filter.php', $scope.filter_data)
        .then(function (response) {
          if (response.data.engineMessage == 1) {
            $scope.allSharingStatus = undefined;
            $scope.allSharing = response.data.resultData;
            $scope.allSharingCount = $scope.allSharing.length;

            $scope.remove_loader();

            $scope.currentPage=1;
            $scope.numLimit=100;
            $scope.start = 0;
            $scope.$watch('allSharing',function(newVal){
              if(newVal){
               $scope.pages=Math.ceil($scope.allSharing.length/$scope.numLimit);

              }
            });
            $scope.hideNext=function(){
              if(($scope.start+ $scope.numLimit) < $scope.allSharing.length){
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
            $scope.allSharingStatus = "No data in range !";
            $scope.allSharingCount = 0;
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allSharingStatus = undefined;
            // }, 3000)
          }
          else {
            $scope.allSharingStatus = "Couldn't get data";
            $scope.allSharingCount = 0;
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allSharingStatus = undefined;
            // }, 3000)
          }
        }, function (error) {
          $scope.allSharingStatus = "Something's Wrong";
          $scope.allSharingCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allSharingStatus = undefined;
          // }, 3000)
        })
    }
  };

  $scope.filter_expiry_date = function () {

    if ($scope.filter_expiry_date_var.startdate > $scope.filter_expiry_date_var.enddate) {
      $scope.filterExpiryDateShow = true;
    }
    else {

      $scope.filterExpiryDateShow = false;

      $scope.show_loader = true;

      $scope.filter_expiry_date_data = {
        "start_date":$scope.filter_expiry_date_var.startdate,
        "end_date":$scope.filter_expiry_date_var.enddate
      }

      $http.post('../../ng/server/get/get_sharing_expiry_date_filter.php', $scope.filter_expiry_date_data)
        .then(function (response) {
          if (response.data.engineMessage == 1) {
            $scope.allSharingStatus = undefined;
            $scope.allSharing = response.data.resultData;
            $scope.allSharingCount = $scope.allSharing.length;

            $scope.remove_loader();

            $scope.currentPage=1;
            $scope.numLimit=100;
            $scope.start = 0;
            $scope.$watch('allSharing',function(newVal){
              if(newVal){
               $scope.pages=Math.ceil($scope.allSharing.length/$scope.numLimit);

              }
            });
            $scope.hideNext=function(){
              if(($scope.start+ $scope.numLimit) < $scope.allSharing.length){
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
            $scope.allSharingStatus = "No data in range !";
            $scope.allSharingCount = 0;
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allSharingStatus = undefined;
            // }, 3000)
          }
          else {
            $scope.allSharingStatus = "Couldn't get data";
            $scope.allSharingCount = 0;
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allSharingStatus = undefined;
            // }, 3000)
          }
        }, function (error) {
          $scope.allSharingStatus = "Something's Wrong";
          $scope.allSharingCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allSharingStatus = undefined;
          // }, 3000)
        })
    }
  };

  $scope.options = {
    height:500,
    toolbar: [

      ['edit',['undo','redo']],
      ['style', ['bold', 'italic', 'underline', 'clear']],
      ['font', ['strikethrough', 'superscript', 'subscript']],
      ['fontsize', ['fontsize']],
      ['para', ['ul', 'ol']],
      ['insert', ['link', 'picture']],
      ['view', ['fullscreen', 'codeview']]

    ],
    callbacks: {
      onImageUpload: function(files) {
        var data = new FormData();
        data.append("file", files[0]);
        $.ajax({
          data: data,
          type: "POST",
          url: "server/summernoteimg.php",
          cache: false,
          contentType: false,
          processData: false,
          success: function(url) {
            $('.summernote').summernote('editor.insertImage', url);
          },
          error:function(data) {
            console.log(data);
          }
        });
      }
    }
  };

  $scope.loadSharing = function () {

    $http.get('../../ng/server/get/get_all_sharing.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allSharingStatus = undefined;
          $scope.allSharing = response.data.resultData;
          $scope.allSharingCount = $scope.allSharing.length;

          $scope.remove_loader();

          $scope.currentPage=1;
          $scope.numLimit=100;
          $scope.start = 0;
          $scope.$watch('allSharing',function(newVal){
            if(newVal){
             $scope.pages=Math.ceil($scope.allSharing.length/$scope.numLimit);

            }
          });
          $scope.hideNext=function(){
            if(($scope.start+ $scope.numLimit) < $scope.allSharing.length){
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
          $scope.allSharingStatus = response.data.engineErrorMessage;
          $scope.allSharingCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allSharingStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allSharingStatus = "Error Occured";
          $scope.allSharingCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allSharingStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allSharingStatus = "Something's Wrong";
        $scope.allSharingCount = 0;
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allSharingStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadSharing();

  $scope.sharing = {};

  $scope.sharing_images = {};

  $scope.split_expiry_date = function (expiry_date) {
    $scope.today = new Date(expiry_date);

    $scope.year = $scope.today.getFullYear();
    $scope.month = $scope.today.getMonth() + 1 < 10 ? "-0" + ($scope.today.getMonth() + 1) : "-" + ($scope.today.getMonth() + 1);
    $scope.date = $scope.today.getDate() < 10 ? "-0" + $scope.today.getDate() : "-" + $scope.today.getDate();

    $scope.hours = $scope.today.getHours() < 10 ? "0" + $scope.today.getHours() : $scope.today.getHours();
    $scope.minutes = $scope.today.getMinutes() < 10 ? "0" + $scope.today.getMinutes() : $scope.today.getMinutes();
    $scope.seconds = $scope.today.getSeconds() < 10 ? "0" + $scope.today.getSeconds() : $scope.today.getSeconds();

    $scope.full_date = $scope.year + $scope.month + $scope.date;
    $scope.full_time = $scope.hours + ":" + $scope.minutes + ":" + $scope.seconds;

    $scope.full_date_and_time = $scope.full_date + " " + $scope.full_time;

    $scope.sharing.sharing_expiry_date_alt = $scope.full_date_and_time;

  };

  $scope.split_starting_date = function (starting_date) {
    $scope.today = new Date(starting_date);

    $scope.year = $scope.today.getFullYear();
    $scope.month = $scope.today.getMonth() + 1 < 10 ? "-0" + ($scope.today.getMonth() + 1) : "-" + ($scope.today.getMonth() + 1);
    $scope.date = $scope.today.getDate() < 10 ? "-0" + $scope.today.getDate() : "-" + $scope.today.getDate();

    $scope.hours = $scope.today.getHours() < 10 ? "0" + $scope.today.getHours() : $scope.today.getHours();
    $scope.minutes = $scope.today.getMinutes() < 10 ? "0" + $scope.today.getMinutes() : $scope.today.getMinutes();
    $scope.seconds = $scope.today.getSeconds() < 10 ? "0" + $scope.today.getSeconds() : $scope.today.getSeconds();

    $scope.full_date = $scope.year + $scope.month + $scope.date;
    $scope.full_time = $scope.hours + ":" + $scope.minutes + ":" + $scope.seconds;

    $scope.full_date_and_time = $scope.full_date + " " + $scope.full_time;

    $scope.sharing.sharing_starting_date_alt = $scope.full_date_and_time;

  };

  $scope.change_add_split_price = function () {
    $scope.sharing.add_split_price = Math.round($scope.sharing.add_total_price / $scope.sharing.add_total_no_of_persons);
  };

  $scope.change_edit_split_price = function () {
    $scope.sharing.edit_split_price = Math.round($scope.sharing.edit_total_price / $scope.sharing.edit_total_no_of_persons);
  };

  $scope.sharing.add_expiration_alt = false;
  $scope.sharing.add_expiration = 0;
  $scope.sharing.edit_expiration_alt = false;

  $scope.change_add_expiration = function () {
    if ($scope.sharing.add_expiration_alt == false) {
      $scope.sharing.add_expiration_alt = true;
      $scope.sharing.add_expiration = 1;
    }
    else {
      $scope.sharing.add_expiration_alt = false;
      $scope.sharing.add_expiration = 0;
    }
  };

  $scope.change_edit_expiration = function () {
    if ($scope.sharing.edit_expiration_alt == false) {
      $scope.sharing.edit_expiration_alt = true;
      $scope.sharing.edit_expiration = 1;
    }
    else {
      $scope.sharing.edit_expiration_alt = false;
      $scope.sharing.edit_expiration = 0;
    }
  };

  $scope.save_sharing = function () {

    $scope.shouldIShow = true;

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.genericData = {
        "user_unique_id":$scope.result_u,
        "name":$scope.sharing.add_name,
        "total_price":$scope.sharing.add_total_price,
        "split_price":$scope.sharing.add_split_price,
        "total_no_of_persons":$scope.sharing.add_total_no_of_persons,
        "expiration":$scope.sharing.add_expiration,
        "starting_date":$scope.sharing.sharing_starting_date_alt,
        "expiry_date":$scope.sharing.add_expiration == 1 ? $scope.sharing.sharing_expiry_date_alt : null,
        "description":$scope.sharing.add_description
      }

      $http.post('../../ng/server/data/sharing_items/add_new_sharing_item.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {

          $scope.showSuccessStatus = true;
          $scope.actionStatus = "Sharing Item added successfully !";

          notify.do_notify($scope.result_u, "Add Activity", "Sharing Item Added");

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

  };

  $scope.edit_sharing_details = function (sharing_unique_id, stripped) {

    $scope.genericData = {
      "sharing_unique_id":sharing_unique_id ? sharing_unique_id : null,
      "stripped":stripped ? stripped : null
    }

    $http.post('../../ng/server/get/get_sharing_details.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.sharingDetailsStatus = undefined;
          $scope.sharingDetails = response.data.resultData;
          $scope.sharing_name = $scope.sharingDetails[0].name;
          $scope.sharing.edit_name = $scope.sharingDetails[0].name;
          $scope.sharing.edit_total_price = parseInt($scope.sharingDetails[0].total_price);
          $scope.sharing.edit_split_price = parseInt($scope.sharingDetails[0].split_price);
          $scope.sharing.edit_total_no_of_persons = parseInt($scope.sharingDetails[0].total_no_of_persons);
          $scope.sharing.edit_expiration = parseInt($scope.sharingDetails[0].expiration);
          $scope.sharing.edit_starting_date = new Date($scope.sharingDetails[0].starting_date);
          $scope.sharing.edit_expiry_date = $scope.sharing.edit_expiration == 1 ? new Date($scope.sharingDetails[0].expiry_date) : null;
          $scope.sharing.edit_description = $scope.sharingDetails[0].description;

          $scope.sharing.edit_expiration_alt = $scope.sharing.edit_expiration == 1 ? true : false;

          $scope.split_starting_date($scope.sharing.edit_starting_date);
          $scope.split_expiry_date($scope.sharing.edit_expiry_date);

          $scope.go_ahead = function () {
            $scope.shouldIShow = true;
          };

          $scope.continue_action = function () {

            $scope.clickOnce = true;

            $scope.genericData = {
              "user_unique_id":$scope.result_u,
              "sharing_unique_id":sharing_unique_id,
              "name":$scope.sharing.edit_name,
              "total_price":$scope.sharing.edit_total_price,
              "split_price":$scope.sharing.edit_split_price,
              "total_no_of_persons":$scope.sharing.edit_total_no_of_persons,
              "expiration":$scope.sharing.edit_expiration,
              "starting_date":$scope.sharing.sharing_starting_date_alt,
              "expiry_date":$scope.sharing.edit_expiration == 1 ? $scope.sharing.sharing_expiry_date_alt : null,
              "description":$scope.sharing.edit_description
            }

            $http.post('../../ng/server/data/sharing_items/update_sharing_item_details.php', $scope.genericData)
            .then(function (response) {
              if (response.data.engineMessage == 1) {

                $scope.showSuccessStatus = true;
                $scope.actionStatus = "Changes Saved !";

                notify.do_notify($scope.result_u, "Edit Activity", "Sharing item edited");

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

        }
        else if (response.data.engineError == 2) {
          $scope.sharingDetailsStatus = response.data.engineErrorMessage;
          // $timeout(function () {
          //   $scope.sharingDetailsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.sharingDetailsStatus = "Error Occured";
          // $timeout(function () {
          //   $scope.sharingDetailsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.sharingDetailsStatus = "Something's Wrong";
        // $timeout(function () {
        //   $scope.sharingDetailsStatus = undefined;
        // }, 3000)
      })

    $scope.removeConfirmModal = function () {

      $scope.shouldIShow = undefined;

    };
  };

  $scope.delete_sharing = function (unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "unique_id":unique_id,
      }

      $http.post('../../ng/server/data/sharing_items/remove_sharing_item.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "Sharing Removed");

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

  $scope.restore_sharing = function (unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "unique_id":unique_id,
      }

      $http.post('../../ng/server/data/sharing_items/restore_sharing_item.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Add Activity", "Sharing Restored");

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

  $scope.get_sharing_images = function (sharing_unique_id, name) {

    $scope.sharing_name = name;
    $scope.the_sharing_unique_id = sharing_unique_id;

    $scope.genericData = {
      "sharing_unique_id":sharing_unique_id
    }

    $http.post('../../ng/server/get/get_sharing_images.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.sharingImages = null;
          $scope.sharingImagesStatus = undefined;
          $scope.sharingImages = response.data.resultData;
          $scope.sharingImagesCount = $scope.sharingImages.length;
        }
        else if (response.data.engineError == 2) {
          $scope.sharingImagesStatus = response.data.engineErrorMessage;
          $scope.sharingImagesCount = 0;
          // $timeout(function () {
          //   $scope.sharingImagesStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.sharingImagesStatus = "Error Occured";
          // $timeout(function () {
          //   $scope.sharingImagesStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.sharingImagesStatus = "Something's Wrong";
        // $timeout(function () {
        //   $scope.sharingImagesStatus = undefined;
        // }, 3000)
      })

  };

  $scope.get_sharing_users = function (sharing_unique_id, name) {

    $scope.sharing_name = name;
    $scope.the_sharing_unique_id = sharing_unique_id;

    $scope.genericData = {
      "sharing_unique_id":sharing_unique_id
    }

    $http.post('../../ng/server/get/get_sharing_users.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.sharingUsers = null;
          $scope.sharingUsersStatus = undefined;
          $scope.sharingUsers = response.data.resultData;
          $scope.sharingUsersCount = response.data.users_count;
          $scope.sharingPaidUsersCount = response.data.paid_users_count;
          $scope.sharingDisableButton = response.data.disable_button;
        }
        else if (response.data.engineError == 2) {
          $scope.sharingUsersStatus = response.data.engineErrorMessage;
          // $timeout(function () {
          //   $scope.sharingUsersStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.sharingUsersStatus = "Error Occured";
          // $timeout(function () {
          //   $scope.sharingUsersStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.sharingUsersStatus = "Something's Wrong";
        // $timeout(function () {
        //   $scope.sharingUsersStatus = undefined;
        // }, 3000)
      })

  };

  $scope.get_sharing_history = function (sharing_unique_id, name) {

    $scope.sharing_name = name;
    $scope.the_sharing_unique_id = sharing_unique_id;

    $scope.genericData = {
      "sharing_unique_id":sharing_unique_id
    }

    $http.post('../../ng/server/get/get_sharing_history.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.sharingHistory = null;
          $scope.sharingHistoryStatus = undefined;
          $scope.sharingHistory = response.data.resultData;
        }
        else if (response.data.engineError == 2) {
          $scope.sharingHistoryStatus = response.data.engineErrorMessage;
          // $timeout(function () {
          //   $scope.sharingHistoryStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.sharingHistoryStatus = "Error Occured";
          // $timeout(function () {
          //   $scope.sharingHistoryStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.sharingHistoryStatus = "Something's Wrong";
        // $timeout(function () {
        //   $scope.sharingHistoryStatus = undefined;
        // }, 3000)
      })

  };

  $scope.update_sharing_user_paid = function (sharing_user_unique_id, user_unique_id, sharing_unique_id, sharing_item_name, amount) {

    $scope.shouldIShow = true;

    $scope.unique_id = sharing_user_unique_id;

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "management_user_unique_id":$scope.result_u,
        "user_unique_id":user_unique_id,
        "sharing_user_unique_id":sharing_user_unique_id,
        "sharing_unique_id":sharing_unique_id,
        "sharing_item_name":sharing_item_name,
        "amount":amount
      }

      $http.post('../../ng/server/data/sharing_users/update_user_sharing_item_paid.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Edit Activity", "Updated sharing item (" + sharing_unique_id + ") user - ("+ user_unique_id +") paid");

            $timeout(function () {
              $scope.showSuccessStatus = undefined;
              $scope.actionStatus = undefined;
              // $route.reload();
              // window.location.reload(true);
              $scope.clickOnce = false;
              $scope.removeConfirmModal();
              $scope.get_sharing_users(sharing_unique_id, sharing_item_name);
              $scope.loadSharing();
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

    $scope.removeConfirmModal = function () {

      $scope.shouldIShow = undefined;

    };

  };

  $scope.add_sharing_image = function (sharing_unique_id) {
    $location.path("/add-sharing-image/" + sharing_unique_id);
  };

  $scope.edit_sharing_image = function (sharing_unique_id, sharing_image_unique_id) {
    $location.path("/edit-sharing-image/" + sharing_unique_id + "/" + sharing_image_unique_id);
  };

  $scope.delete_sharing_image = function (sharing_unique_id, sharing_image_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.shouldIShow = true;

    $scope.unique_id = sharing_image_unique_id;

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "sharing_unique_id":sharing_unique_id,
        "sharing_image_unique_id":sharing_image_unique_id
      }

      $http.post('../../ng/server/data/sharing_images/remove_sharing_image.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "Sharing Item Image Removed");

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

    $scope.removeConfirmModal = function () {

      $scope.shouldIShow = undefined;

    };

  };

});
