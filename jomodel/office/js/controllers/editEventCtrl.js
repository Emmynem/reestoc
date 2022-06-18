xnyder.controller('edit-eventCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$routeParams,$window,$sanitize,storage,notify,strip_text){

  $rootScope.pageTitle = "Edit Event";

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

  $scope.event_unique_id = $routeParams.unique_id;

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

  $scope.event = {};

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

  $scope.get_event_details = function () {

    $scope.enquiryData = {
      "table":"events",
      "unique_id":$scope.event_unique_id
    }

    $http.post("server/get_an_event.php", $scope.enquiryData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.showStatus = undefined;
          $scope.event_details = response.data.re_data;

          $scope.event.display_title = $scope.event_details.display_title;
          $scope.event.event_name = $scope.event_details.event_name;
          $scope.new_event_date_start = new Date($scope.event_details.event_date_start);
          $scope.event.event_date_start = $scope.new_event_date_start;
          $scope.new_event_date_end = new Date($scope.event_details.event_date_end);
          $scope.event.event_date_end = $scope.new_event_date_end;
          $scope.event_date = new Date($scope.event_details.added_date);
          $scope.new_event_time_start = new Date($scope.event_details.event_date_start + " " + $scope.event_details.event_time_start);
          $scope.event.event_time_start = $scope.new_event_time_start;
          $scope.new_event_time_end = new Date($scope.event_details.event_date_end + " " + $scope.event_details.event_time_end);
          $scope.event.event_time_end = $scope.new_event_time_end;
          $scope.event.event_location = $scope.event_details.event_location;
          $scope.event.event_categories = $scope.event_details.event_categories;
          $scope.event.event_tags = $scope.event_details.event_tags;
          $scope.event.event_venue = $scope.event_details.event_venue;
          $scope.event.event_organizers = $scope.event_details.event_organizers;
          $scope.event.old_event_image = $scope.event_details.event_image;
          $scope.drafted = $scope.event_details.drafted;

          $scope.remove_loader();

          $scope.editEventSuccessStatus = "Event loaded ...";
          $timeout(function () {
            $scope.editEventSuccessStatus = undefined;
          }, 3000)

        }
        else if (response.data.noData == 2) {
          $scope.showStatus = true;
          $scope.actionStatus = "Couldn't get details !";
          $scope.remove_loader();
          $scope.editEventStatus = $scope.actionStatus;
          $timeout(function () {
            $scope.showStatus = undefined;
            $scope.actionStatus = undefined;
            $scope.editEventStatus = undefined;
            $scope.clickOnce = false;
          }, 3000)
        }
        else {
          $scope.showStatus = true;
          $scope.actionStatus = "Error Occured !";
          $scope.remove_loader();
          $scope.editEventStatus = $scope.actionStatus;
          $timeout(function () {
            $scope.showStatus = undefined;
            $scope.actionStatus = undefined;
            $scope.editEventStatus = undefined;
            $scope.clickOnce = false;
          }, 3000)
        }
      }, function (error) {
        $scope.showStatus = true;
        $scope.actionStatus = "Something's Wrong !";
        $scope.remove_loader();
        $scope.editEventStatus = $scope.actionStatus;
        $timeout(function () {
          $scope.showStatus = undefined;
          $scope.actionStatus = undefined;
          $scope.editEventStatus = undefined;
          $scope.clickOnce = false;
        }, 3000)
      })

  };

  $scope.get_event_details();

    $scope.save_changes = function () {

      if ($scope.event.event_date_start > $scope.event.event_date_end) {

      }
      else {

        $scope.shouldIShow = true;

        $scope.continue_action = function () {

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

          $scope.editEventData = {
            "edit_user_unique_id":$scope.result_u,
            "unique_id":$scope.event_unique_id,
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
            "event_image":$scope.event.old_event_image
          }

          $scope.form.image = $scope.files[0];

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

            if(response.data.error){

              if (response.data.message == 'Upload Failed. File empty!') {

                $scope.showProgress = false;

                $http.post('server/edit_event.php', $scope.editEventData)
                  .then(function (response) {
                    if (response.data.engineMessage == 1) {

                      $scope.showSuccessStatus = true;
                      $scope.actionStatus = "Changes Saved !";
                      $scope.editEventSuccessStatus = $scope.actionStatus;

                      notify.do_notify($scope.result_u, "Edit Activity", "Event edited and published");

                      $timeout(function () {
                        $scope.showSuccessStatus = undefined;
                        $scope.actionStatus = undefined;
                        $scope.editEventSuccessStatus = undefined;
                        $scope.showProgress = false;
                        $rootScope.fileUploadStatusError = undefined;
                        $rootScope.fileUploadStatusSuccess = undefined;

                        $location.path("events");
                      }, 3000)

                    }
                    else if (response.data.noData == 2) {
                      $scope.showStatus = true;
                      $scope.actionStatus = "No data found";
                      $scope.editEventStatus = $scope.actionStatus;
                      $timeout(function () {
                        $scope.showStatus = undefined;
                        $scope.actionStatus = undefined;
                        $scope.editEventStatus = undefined;
                        $scope.clickOnce = false;
                      }, 3000)
                    }
                    else {
                      $scope.showStatus = true;
                      $scope.actionStatus = "Error Occured";
                      $scope.editEventStatus = $scope.actionStatus;
                      $timeout(function () {
                        $scope.showStatus = undefined;
                        $scope.actionStatus = undefined;
                        $scope.editEventStatus = undefined;
                        $scope.clickOnce = false;
                      }, 3000)
                    }
                  }, function (error) {
                    $scope.showStatus = true;
                    $scope.actionStatus = "Something's Wrong";
                    $scope.editEventStatus = $scope.actionStatus;
                    $timeout(function () {
                      $scope.showStatus = undefined;
                      $scope.actionStatus = undefined;
                      $scope.editEventStatus = undefined;
                      $scope.clickOnce = false;
                    }, 3000)
                  })

              }
              else {
                $scope.showProgress = false;
                $scope.errorFileUpload = true;
                $rootScope.fileUploadStatusError = response.data.message;
                $timeout(function(response){
                  $rootScope.fileUploadStatusError = "";
                  $scope.clickOnce = false;
                  $scope.errorFileUpload = false;
                },5000);
              }
            }
            else{
              $scope.successFileUpload = true;
              $rootScope.edit_event_image = response.data.return_file;
              $rootScope.fileUploadStatusSuccess = response.data.message;

              $scope.editEventData.event_image = $rootScope.edit_event_image;

              $scope.clickOnce = true;

              $http.post('server/edit_event.php', $scope.editEventData)
                .then(function (response) {
                  if (response.data.engineMessage == 1) {
                    $scope.showSuccessStatus = true;
                    $scope.actionStatus = "Changes Saved !";
                    $scope.editEventSuccessStatus = $scope.actionStatus;

                    notify.do_notify($scope.result_u, "Edit Activity", "Event edited and published");

                    $scope.delete_this = {
                      "old_image":$scope.event.old_event_image
                    }
                    $http.post('server/delete_old_bg_image.php', $scope.delete_this)
                      .then(function (response) {
                        if (response.data.engineMessage == 1) {
                          // console.log("Old Image deleted");
                        }
                      },function (error) {
                        console.log("A fatal error occured");
                      })

                      $timeout(function () {
                        $scope.showSuccessStatus = undefined;
                        $scope.actionStatus = undefined;
                        $scope.editEventSuccessStatus = undefined;
                        $scope.showProgress = false;
                        $rootScope.fileUploadStatusError = undefined;
                        $rootScope.fileUploadStatusSuccess = undefined;

                        $location.path("events");
                      }, 3000)
                  }
                  else if (response.data.noData == 2) {
                    $scope.showStatus = true;
                    $scope.actionStatus = "No data found";
                    $scope.editEventStatus = $scope.actionStatus;
                    $timeout(function () {
                      $scope.showStatus = undefined;
                      $scope.actionStatus = undefined;
                      $scope.editEventStatus = undefined;
                      $scope.clickOnce = false;
                    }, 3000)
                  }
                  else {
                    $scope.showStatus = true;
                    $scope.actionStatus = "Error Occured";
                    $scope.editEventStatus = $scope.actionStatus;
                    $timeout(function () {
                      $scope.showStatus = undefined;
                      $scope.actionStatus = undefined;
                      $scope.editEventStatus = undefined;
                      $scope.clickOnce = false;
                    }, 3000)
                  }
                }, function (error) {
                  $scope.showStatus = true;
                  $scope.actionStatus = "Something's Wrong";
                  $scope.editEventStatus = $scope.actionStatus;
                  $timeout(function () {
                    $scope.showStatus = undefined;
                    $scope.actionStatus = undefined;
                    $scope.editEventStatus = undefined;
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

    $scope.save_draft_changes = function () {

      if ($scope.event.event_date_start > $scope.event.event_date_end) {

      }
      else {

        $scope.shouldIShow = true;

        $scope.continue_action = function () {

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

          $scope.editEventData = {
            "edit_user_unique_id":$scope.result_u,
            "unique_id":$scope.event_unique_id,
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
            "event_image":$scope.event.old_event_image
          }

          $scope.form.image = $scope.files[0];

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

            if(response.data.error){

              if (response.data.message == 'Upload Failed. File empty!') {

                $scope.showProgress = false;

                $http.post('server/edit_event.php', $scope.editEventData)
                  .then(function (response) {
                    if (response.data.engineMessage == 1) {

                      $scope.showSuccessStatus = true;
                      $scope.actionStatus = "Changes Saved !";
                      $scope.editEventSuccessStatus = $scope.actionStatus;

                      notify.do_notify($scope.result_u, "Edit Activity", "Event edited and drafted");

                      $timeout(function () {
                        $scope.showSuccessStatus = undefined;
                        $scope.actionStatus = undefined;
                        $scope.editEventSuccessStatus = undefined;
                        $scope.showProgress = false;
                        $rootScope.fileUploadStatusError = undefined;
                        $rootScope.fileUploadStatusSuccess = undefined;

                        $location.path("events");
                      }, 3000)

                    }
                    else if (response.data.noData == 2) {
                      $scope.showStatus = true;
                      $scope.actionStatus = "No data found";
                      $scope.editEventStatus = $scope.actionStatus;
                      $timeout(function () {
                        $scope.showStatus = undefined;
                        $scope.actionStatus = undefined;
                        $scope.editEventStatus = undefined;
                        $scope.clickOnce = false;
                      }, 3000)
                    }
                    else {
                      $scope.showStatus = true;
                      $scope.actionStatus = "Error Occured";
                      $scope.editEventStatus = $scope.actionStatus;
                      $timeout(function () {
                        $scope.showStatus = undefined;
                        $scope.actionStatus = undefined;
                        $scope.editEventStatus = undefined;
                        $scope.clickOnce = false;
                      }, 3000)
                    }
                  }, function (error) {
                    $scope.showStatus = true;
                    $scope.actionStatus = "Something's Wrong";
                    $scope.editEventStatus = $scope.actionStatus;
                    $timeout(function () {
                      $scope.showStatus = undefined;
                      $scope.actionStatus = undefined;
                      $scope.editEventStatus = undefined;
                      $scope.clickOnce = false;
                    }, 3000)
                  })

              }
              else {
                $scope.showProgress = false;
                $scope.errorFileUpload = true;
                $rootScope.fileUploadStatusError = response.data.message;
                $timeout(function(response){
                  $rootScope.fileUploadStatusError = "";
                  $scope.clickOnce = false;
                  $scope.errorFileUpload = false;
                },5000);
              }
            }
            else{
              $scope.successFileUpload = true;
              $rootScope.edit_event_image = response.data.return_file;
              $rootScope.fileUploadStatusSuccess = response.data.message;

              $scope.editEventData.event_image = $rootScope.edit_event_image;

              $scope.clickOnce = true;

              $http.post('server/edit_event.php', $scope.editEventData)
                .then(function (response) {
                  if (response.data.engineMessage == 1) {
                    $scope.showSuccessStatus = true;
                    $scope.actionStatus = "Changes Saved !";
                    $scope.editEventSuccessStatus = $scope.actionStatus;

                    notify.do_notify($scope.result_u, "Edit Activity", "Event edited and drafted");

                    $scope.delete_this = {
                      "old_image":$scope.event.old_event_image
                    }
                    $http.post('server/delete_old_bg_image.php', $scope.delete_this)
                      .then(function (response) {
                        if (response.data.engineMessage == 1) {
                          // console.log("Old Image deleted");
                        }
                      },function (error) {
                        console.log("A fatal error occured");
                      })

                      $timeout(function () {
                        $scope.showSuccessStatus = undefined;
                        $scope.actionStatus = undefined;
                        $scope.editEventSuccessStatus = undefined;
                        $scope.showProgress = false;
                        $rootScope.fileUploadStatusError = undefined;
                        $rootScope.fileUploadStatusSuccess = undefined;

                        $location.path("events");
                      }, 3000)
                  }
                  else if (response.data.noData == 2) {
                    $scope.showStatus = true;
                    $scope.actionStatus = "No data found";
                    $scope.editEventStatus = $scope.actionStatus;
                    $timeout(function () {
                      $scope.showStatus = undefined;
                      $scope.actionStatus = undefined;
                      $scope.editEventStatus = undefined;
                      $scope.clickOnce = false;
                    }, 3000)
                  }
                  else {
                    $scope.showStatus = true;
                    $scope.actionStatus = "Error Occured";
                    $scope.editEventStatus = $scope.actionStatus;
                    $timeout(function () {
                      $scope.showStatus = undefined;
                      $scope.actionStatus = undefined;
                      $scope.editEventStatus = undefined;
                      $scope.clickOnce = false;
                    }, 3000)
                  }
                }, function (error) {
                  $scope.showStatus = true;
                  $scope.actionStatus = "Something's Wrong";
                  $scope.editEventStatus = $scope.actionStatus;
                  $timeout(function () {
                    $scope.showStatus = undefined;
                    $scope.actionStatus = undefined;
                    $scope.editEventStatus = undefined;
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
