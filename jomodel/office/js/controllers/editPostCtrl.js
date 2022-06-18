xnyder.controller('edit-postCtrl',function($scope,$rootScope,$routeParams,$http,$timeout,$location,$route,$window,$sanitize,storage,notify,strip_text){

  $rootScope.pageTitle = "Edit Post";

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

  $scope.post_route_unique_id = $routeParams.unique_id;

  $scope.options = {
    height:500,
    toolbar: [

      ['edit',['undo','redo']],
      ['style', ['bold', 'italic', 'underline', 'clear']],
      ['font', ['strikethrough', 'superscript', 'subscript']],
      ['fontsize', ['fontsize']],
      ['para', ['ul', 'ol']],
      ['insert', ['link', 'picture', 'video']],
      ['view', ['fullscreen', 'codeview']]

    ],
    callbacks: {
      onImageUpload:
      function(image) {
        editor = $(this);
        uploadImageContent(image[0], editor);
      }
    }
  };

  $scope.post = {};

  $scope.list_categories = function () {

    $http.get("server/get_categories.php")
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allCategoriesStatus = undefined;
          $scope.allCategories = response.data.re_data;
        }
        else if (response.data.noData == 2) {
          $scope.allCategoriesStatus = "No category found !";
          // $timeout(function () {
          //   $scope.allCategoriesStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allCategoriesStatus = "Error Occured";
          // $timeout(function () {
          //   $scope.allCategoriesStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allCategoriesStatus = "Something's Wrong";
        // $timeout(function () {
        //   $scope.allCategoriesStatus = undefined;
        // }, 3000)
      })

  };

  $scope.list_categories();

  $scope.get_post_details = function () {

    $scope.postData = {
      "unique_id":$scope.post_route_unique_id
    }

    $http.post("server/get_post_details.php", $scope.postData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.editPostStatus = undefined;
          $scope.details = response.data.re_data;
          $scope.post.post_title = $scope.details.post_title;
          $scope.post.category_unique_id = $scope.details.category_unique_id;
          $scope.post.author = $scope.details.author_name;
          $scope.post.post_details = $scope.details.post_details;
          $scope.post.old_bgimage = $scope.details.bg_image;
          $scope.post.file = $scope.details.file;
          $scope.post.file_size = $scope.details.file_size;
          $scope.drafted = $scope.details.drafted;

          $scope.editPostSuccessStatus = "Post loaded ...";
          $scope.remove_loader();
          $timeout(function () {
            $scope.editPostSuccessStatus = undefined;
          }, 3000)
        }
        else if (response.data.noData == 2) {
          $scope.showStatus = true;
          $scope.remove_loader();
          $scope.actionStatus = "Couldn't get details !";
          $scope.editPostStatus = $scope.actionStatus;
          $timeout(function () {
            $scope.showStatus = undefined;
            $scope.actionStatus = undefined;
            $scope.editPostStatus = undefined;
            $scope.clickOnce = false;
          }, 3000)
        }
        else {
          $scope.showStatus = true;
          $scope.remove_loader();
          $scope.actionStatus = "Error Occured !";
          $scope.editPostStatus = $scope.actionStatus;
          $timeout(function () {
            $scope.showStatus = undefined;
            $scope.actionStatus = undefined;
            $scope.editPostStatus = undefined;
            $scope.clickOnce = false;
          }, 3000)
        }
      }, function (error) {
        $scope.showStatus = true;
        $scope.remove_loader();
        $scope.actionStatus = "Something's Wrong !";
        $scope.editPostStatus = $scope.actionStatus;
        $timeout(function () {
          $scope.showStatus = undefined;
          $scope.actionStatus = undefined;
          $scope.editPostStatus = undefined;
          $scope.clickOnce = false;
        }, 3000)
      })

  };

  $scope.get_post_details();

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

    $scope.edit_post = function () {

      $scope.shouldIShow = true;

      $scope.continue_action = function () {

        $scope.clickOnce = true;

        $scope.editPostData = {
          "edit_user_unique_id":$scope.result_u,
          "unique_id":$scope.post_route_unique_id,
          "author_name":$scope.loggedInName,
          "post_title":$scope.post.post_title,
          "stripped":$scope.post.post_title,
          "category_unique_id":$scope.post.category_unique_id,
          "bg_image":$scope.post.old_bgimage,
          "file":$scope.post.file,
          "file_size":$scope.post.file_size,
          "post_details":$scope.post.post_details
        }

        $scope.form.image = $scope.files[0];

        var uploadForm = new FormData();
        uploadForm.append("file", $scope.form.image);
        $http.post('server/uploadimage.php', uploadForm, {
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

              $http.post('server/edit_post.php', $scope.editPostData)
                .then(function (response) {
                  if (response.data.engineMessage == 1) {

                    $scope.showSuccessStatus = true;
                    $scope.actionStatus = "Changes Saved !";
                    notify.do_notify($scope.result_u, "Edit Activity", "Blog post edited and published");

                    $timeout(function () {
                      $scope.showSuccessStatus = undefined;
                      $scope.actionStatus = undefined;
                      $scope.showProgress = false;
                      $rootScope.fileUploadStatusError = undefined;
                      $rootScope.fileUploadStatusSuccess = undefined;
                      // window.location.reload(true);
                      $location.path('all-posts');
                    }, 3000)

                  }
                  else if (response.data.noData == 2) {
                    $scope.showStatus = true;
                    $scope.actionStatus = "No Data Found";
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
            $rootScope.edit_bgimage = response.data.image;
            $rootScope.file = response.data.file;
            $rootScope.file_size = response.data.file_size;
            $rootScope.fileUploadStatusSuccess = response.data.message;

            $scope.editPostData.bg_image = $rootScope.edit_bgimage;
            $scope.editPostData.file = $rootScope.file;
            $scope.editPostData.file_size = $rootScope.file_size;

            $scope.clickOnce = true;

            $http.post('server/edit_post.php', $scope.editPostData)
              .then(function (response) {
                if (response.data.engineMessage == 1) {

                  $scope.showSuccessStatus = true;
                  $scope.actionStatus = "Changes Saved !";
                  notify.do_notify($scope.result_u, "Edit Activity", "Blog post edited and published");

                  $scope.delete_this = {
                    // "unique_id":$scope.post_route_unique_id
                    "old_image":$scope.post.file
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
                    $scope.showProgress = false;
                    $rootScope.fileUploadStatusError = undefined;
                    $rootScope.fileUploadStatusSuccess = undefined;
                    // window.location.reload(true);
                    $location.path('all-posts');
                  }, 3000)

                }
                else if (response.data.noData == 2) {
                  $scope.showStatus = true;
                  $scope.actionStatus = "No Data Found";
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

    };

    $scope.edit_draft_post = function () {

      $scope.shouldIShow = true;

      $scope.continue_action = function () {

        $scope.clickOnce = true;

        $scope.editPostData = {
          "edit_user_unique_id":$scope.result_u,
          "unique_id":$scope.post_route_unique_id,
          "author_name":$scope.loggedInName,
          "post_title":$scope.post.post_title,
          "stripped":$scope.post.post_title,
          "category_unique_id":$scope.post.category_unique_id,
          "bg_image":$scope.post.old_bgimage,
          "file":$scope.post.file,
          "file_size":$scope.post.file_size,
          "post_details":$scope.post.post_details
        }

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
        $http.post('server/uploadimage.php', uploadForm, {
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

              if (response.data.message == 'Upload Failed. File empty!') {

                $scope.showProgress = false;

                $http.post('server/edit_post.php', $scope.editPostData)
                  .then(function (response) {
                    if (response.data.engineMessage == 1) {

                      $scope.showSuccessStatus = true;
                      $scope.actionStatus = "Changes Saved !";
                      notify.do_notify($scope.result_u, "Edit Activity", "Blog post edited and drafted");

                      $timeout(function () {
                        $scope.showSuccessStatus = undefined;
                        $scope.actionStatus = undefined;
                        $scope.showProgress = false;
                        $rootScope.fileUploadStatusError = undefined;
                        $rootScope.fileUploadStatusSuccess = undefined;
                        // window.location.reload(true);
                        $location.path('all-posts');
                      }, 3000)

                    }
                    else if (response.data.noData == 2) {
                      $scope.showStatus = true;
                      $scope.actionStatus = "No Data Found";
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
              $rootScope.edit_bgimage = response.data.image;
              $rootScope.file = response.data.file;
              $rootScope.file_size = response.data.file_size;
              $rootScope.fileUploadStatusSuccess = response.data.message;

              $scope.editPostData.bg_image = $rootScope.edit_bgimage;
              $scope.editPostData.file = $rootScope.file;
              $scope.editPostData.file_size = $rootScope.file_size;

              $scope.clickOnce = true;

              $http.post('server/edit_post.php', $scope.editPostData)
                .then(function (response) {
                  if (response.data.engineMessage == 1) {

                    $scope.showSuccessStatus = true;
                    $scope.actionStatus = "Changes Saved !";
                    notify.do_notify($scope.result_u, "Edit Activity", "Blog post edited and drafted");

                    $scope.delete_this = {
                      // "unique_id":$scope.post_route_unique_id
                      "old_image":$scope.post.old_bgimage
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
                      $scope.showProgress = false;
                      $rootScope.fileUploadStatusError = undefined;
                      $rootScope.fileUploadStatusSuccess = undefined;
                      // window.location.reload(true);
                      $location.path('all-posts');
                    }, 3000)

                  }
                  else if (response.data.noData == 2) {
                    $scope.showStatus = true;
                    $scope.actionStatus = "No Data Found";
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

    };

});
