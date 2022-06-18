xnyder.controller('add-eventCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$window,$sanitize,storage,notify,strip_text){

  $rootScope.pageTitle = "Add Event";

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

  $scope.options = {
    height:250,
    toolbar: [

      ['edit',['undo','redo']],
      ['style', ['bold', 'italic', 'underline', 'clear']],
      ['insert', ['link', 'picture']],
      ['view', ['fullscreen']]

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

  $rootScope.added_image = true;

  $scope.form = [];
  $scope.files = [];

  $scope.uploadedFile = function(element) {
    $scope.currentFile = element.files[0];
    var reader = new FileReader();


    reader.onload = function(event) {
      $scope.image_source = event.target.result
      $scope.$apply(function($scope) {
        $scope.files = element.files;
      });
    }
                reader.readAsDataURL(element.files[0]);
  };

  $scope.event = {};

  $scope.event.total_no_of_tickets = 0;

  $scope.today = new Date();
  $scope.today.setHours(0, 0, 0, 0);

  $scope.event.event_date_start = new Date();
  $scope.event.event_date_start.setHours(0, 0, 0, 0);

  $scope.event.event_date_end = new Date();
  $scope.event.event_date_end.setHours(0, 0, 0, 0);

    $scope.saveEvent = function () {

      if ($scope.event.event_date_start > $scope.event.event_date_end) {

      }
      else {

        $scope.shouldIShow = true;

        $scope.continue_action = function () {

          $scope.clickOnce = true;

          $scope.form.image = $scope.files[0];

          $scope.showProgress = false;
          $rootScope.fileUploadStatusError = undefined;
          $rootScope.fileUploadStatusSuccess = undefined;
          $scope.errorFileUpload = false;
          $scope.successFileUpload = false;
          $scope.showSuccessStatus = undefined;
          $scope.showStatus = undefined;

          var uploadForm = new FormData();
          uploadForm.append("file", $scope.form.image);
          $http.post('server/uploadeventimage.php', uploadForm, {
              transformRequest:angular.identity,
              headers: {'Content-Type':undefined, 'Process-Data': false},
              uploadEventHandlers: {
                progress: function (e) {
                  if (e.lengthComputable) {
                    $scope.showProgress = true;
                    $scope.progressBar = (e.loaded / e.total) * 100;
                    $scope.progressCounter = $scope.progressBar.toFixed(2) + '%';
                  }
                }
              }
          })
          .then(function(response){
              // console.log(response);
              if(response.data.error){
                  $scope.showProgress = false;
                  $scope.errorFileUpload = true;
                  $rootScope.fileUploadStatusError = response.data.message;
                  $timeout(function(response){
                    $rootScope.fileUploadStatusError = "";
                    $scope.clickOnce = false;
                    $scope.errorFileUpload = false;
                  },5000);
              }
              else{

                $scope.successFileUpload = true;
                $rootScope.eventimage = response.data.return_file;
                $rootScope.fileUploadStatusSuccess = response.data.message;

                $scope.clickOnce = true;

                $scope.new_date_start;

                $scope.new_date_end;

                if ($scope.event.event_date_start.getMonth() + 1 < 10) {
                  $scope.new_date_start = $scope.event.event_date_start.getFullYear() + "-0" + ($scope.event.event_date_start.getMonth() + 1) + "-" + $scope.event.event_date_start.getDate();
                }
                else {
                  $scope.new_date_start = $scope.event.event_date_start.getFullYear() + "-" + ($scope.event.event_date_start.getMonth() + 1) + "-" + $scope.event.event_date_start.getDate();
                }

                $scope.new_time_start = $scope.event.event_time_start.getHours() + ":" + $scope.event.event_time_start.getMinutes() + ":" + "00";

                if ($scope.event.event_date_end.getMonth() + 1 < 10) {
                  $scope.new_date_end = $scope.event.event_date_end.getFullYear() + "-0" + ($scope.event.event_date_end.getMonth() + 1) + "-" + $scope.event.event_date_end.getDate();
                }
                else {
                  $scope.new_date_end = $scope.event.event_date_end.getFullYear() + "-" + ($scope.event.event_date_end.getMonth() + 1) + "-" + $scope.event.event_date_end.getDate();
                }

                $scope.new_time_end = $scope.event.event_time_end.getHours() + ":" + $scope.event.event_time_end.getMinutes() + ":" + "00";

                $scope.eventData = {
                  "user_unique_id":$scope.result_u,
                  "edit_user_unique_id":$scope.result_u,
                  "display_title":$scope.event.display_title,
                  "stripped":strip_text.get_stripped($scope.event.display_title),
                  "event_name":$scope.event.event_name,
                  "event_date_start":$scope.new_date_start,
                  "event_time_start":$scope.new_time_start,
                  "event_date_end":$scope.new_date_end,
                  "event_time_end":$scope.new_time_end,
                  "event_location":$scope.event.event_location,
                  "event_categories":$scope.event.event_categories,
                  "event_tags":$scope.event.event_tags,
                  "event_venue":$scope.event.event_venue,
                  "event_organizers":$scope.event.event_organizers,
                  "event_image":$rootScope.eventimage,
                  "total_no_of_tickets":$scope.event.total_no_of_tickets,
                  "tickets_left":$scope.event.total_no_of_tickets
                }

                $http.post('server/add_event.php', $scope.eventData)
                  .then(function (response) {
                    if (response.data.engineMessage == 1) {

                      $scope.showSuccessStatus = true;
                      $scope.actionStatus = "Event Added !";

                      notify.do_notify($scope.result_u, "Add Activity", "Event Added");

                      $timeout(function () {
                        $scope.showSuccessStatus = undefined;
                        $scope.actionStatus = undefined;
                        $scope.showProgress = false;
                        $rootScope.fileUploadStatusError = undefined;
                        $rootScope.fileUploadStatusSuccess = undefined;
                        window.location.reload(true);
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

              }
          }, function (error) {
            // console.log(response);
            $scope.showProgress = false;
            $scope.errorFileUpload = true;
            $rootScope.fileUploadStatusError = response.message;
            $timeout(function(response){
              $rootScope.fileUploadStatusError = "";
              $scope.clickOnce = false;
              $scope.errorFileUpload = false;
            },5000);
          })

        };

        $scope.removeConfirmModal = function () {
          $scope.shouldIShow = undefined;
        };

      }

    };

    $scope.saveDraftEvent = function () {

      if ($scope.event.event_date_start > $scope.event.event_date_end) {

      }
      else {

        $scope.shouldIShow = true;

        $scope.continue_action = function () {

          $scope.clickOnce = true;

          $scope.form.image = $scope.files[0];

          $scope.showProgress = false;
          $rootScope.fileUploadStatusError = undefined;
          $rootScope.fileUploadStatusSuccess = undefined;
          $scope.errorFileUpload = false;
          $scope.successFileUpload = false;
          $scope.showSuccessStatus = undefined;
          $scope.showStatus = undefined;

          var uploadForm = new FormData();
          uploadForm.append("file", $scope.form.image);
          $http.post('server/uploadeventimage.php', uploadForm, {
              transformRequest:angular.identity,
              headers: {'Content-Type':undefined, 'Process-Data': false},
              uploadEventHandlers: {
                progress: function (e) {
                  if (e.lengthComputable) {
                    $scope.showProgress = true;
                    $scope.progressBar = (e.loaded / e.total) * 100;
                    $scope.progressCounter = $scope.progressBar.toFixed(2) + '%';
                  }
                }
              }
          })
          .then(function(response){
              // console.log(response);
              if(response.data.error){
                  $scope.showProgress = false;
                  $scope.errorFileUpload = true;
                  $rootScope.fileUploadStatusError = response.data.message;
                  $timeout(function(response){
                    $rootScope.fileUploadStatusError = "";
                    $scope.clickOnce = false;
                    $scope.errorFileUpload = false;
                  },5000);
              }
              else{

                $scope.successFileUpload = true;
                $rootScope.eventimage = response.data.return_file;
                $rootScope.fileUploadStatusSuccess = response.data.message;

                $scope.clickOnce = true;

                $scope.new_date_start;

                $scope.new_date_end;

                if ($scope.event.event_date_start.getMonth() + 1 < 10) {
                  $scope.new_date_start = $scope.event.event_date_start.getFullYear() + "-0" + ($scope.event.event_date_start.getMonth() + 1) + "-" + $scope.event.event_date_start.getDate();
                }
                else {
                  $scope.new_date_start = $scope.event.event_date_start.getFullYear() + "-" + ($scope.event.event_date_start.getMonth() + 1) + "-" + $scope.event.event_date_start.getDate();
                }

                $scope.new_time_start = $scope.event.event_time_start.getHours() + ":" + $scope.event.event_time_start.getMinutes() + ":" + "00";

                if ($scope.event.event_date_end.getMonth() + 1 < 10) {
                  $scope.new_date_end = $scope.event.event_date_end.getFullYear() + "-0" + ($scope.event.event_date_end.getMonth() + 1) + "-" + $scope.event.event_date_end.getDate();
                }
                else {
                  $scope.new_date_end = $scope.event.event_date_end.getFullYear() + "-" + ($scope.event.event_date_end.getMonth() + 1) + "-" + $scope.event.event_date_end.getDate();
                }

                $scope.new_time_end = $scope.event.event_time_end.getHours() + ":" + $scope.event.event_time_end.getMinutes() + ":" + "00";

                $scope.eventData = {
                  "user_unique_id":$scope.result_u,
                  "edit_user_unique_id":$scope.result_u,
                  "display_title":$scope.event.display_title,
                  "stripped":strip_text.get_stripped($scope.event.display_title),
                  "event_name":$scope.event.event_name,
                  "event_date_start":$scope.new_date_start,
                  "event_time_start":$scope.new_time_start,
                  "event_date_end":$scope.new_date_end,
                  "event_time_end":$scope.new_time_end,
                  "event_location":$scope.event.event_location,
                  "event_categories":$scope.event.event_categories,
                  "event_tags":$scope.event.event_tags,
                  "event_venue":$scope.event.event_venue,
                  "event_organizers":$scope.event.event_organizers,
                  "event_image":$rootScope.eventimage,
                  "total_no_of_tickets":$scope.event.total_no_of_tickets,
                  "tickets_left":$scope.event.total_no_of_tickets
                }

                $http.post('server/add_draft_event.php', $scope.eventData)
                  .then(function (response) {
                    if (response.data.engineMessage == 1) {

                      $scope.showSuccessStatus = true;
                      $scope.actionStatus = "Event Drafted !";

                      notify.do_notify($scope.result_u, "Add Activity", "Event Drafted");

                      $timeout(function () {
                        $scope.showSuccessStatus = undefined;
                        $scope.actionStatus = undefined;
                        $scope.showProgress = false;
                        $rootScope.fileUploadStatusError = undefined;
                        $rootScope.fileUploadStatusSuccess = undefined;
                        window.location.reload(true);
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

              }
          }, function (error) {
            // console.log(response);
            $scope.showProgress = false;
            $scope.errorFileUpload = true;
            $rootScope.fileUploadStatusError = response.message;
            $timeout(function(response){
              $rootScope.fileUploadStatusError = "";
              $scope.clickOnce = false;
              $scope.errorFileUpload = false;
            },5000);
          })

        };

        $scope.removeConfirmModal = function () {
          $scope.shouldIShow = undefined;
        };

      }

    };

});
