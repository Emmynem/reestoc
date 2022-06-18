xnyder.controller('galleryCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$window,storage,notify,ngClipboard){

  $rootScope.pageTitle = "Gallery";

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

      $scope.show_loader = true;

      $scope.filter_data = {
        "table":"gallery",
        "startdate":$scope.filter_var.startdate,
        "enddate":$scope.filter_var.enddate
      }

      $http.post('server/filter_gallery.php', $scope.filter_data)
        .then(function (response) {
          if (response.data.engineMessage == 1) {
            $scope.if_gallery_exists = undefined;
            $scope.allImages = response.data.filteredData;

            $scope.remove_loader();

            $scope.currentPage=1;
            $scope.numLimit=15;
            $scope.start = 0;
            $scope.$watch('allImages',function(newVal){
              if(newVal){
               $scope.pages=Math.ceil($scope.allImages.length/$scope.numLimit);

              }
            });
            $scope.hideNext=function(){
              if(($scope.start+ $scope.numLimit) < $scope.allImages.length){
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
            $scope.if_gallery_exists = "No data in range !";
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.if_gallery_exists = undefined;
            // }, 3000)
          }
          else {
            $scope.if_gallery_exists = "Couldn't get data !";
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.if_gallery_exists = undefined;
            // }, 3000)
          }
        }, function (error) {
          $scope.if_gallery_exists = "Something's Wrong!";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.if_gallery_exists = undefined;
          // }, 3000)
        })
    }
  };

  $scope.load = function () {


    $http.get('server/get_gallery.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.if_gallery_exists = undefined;
          $scope.allImages = response.data.re_data;

          $scope.remove_loader();

          $scope.currentPage=1;
          $scope.numLimit=15;
          $scope.start = 0;
          $scope.$watch('allImages',function(newVal){
            if(newVal){
             $scope.pages=Math.ceil($scope.allImages.length/$scope.numLimit);

            }
          });
          $scope.hideNext=function(){
            if(($scope.start+ $scope.numLimit) < $scope.allImages.length){
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
          $scope.if_gallery_exists = "No images found !";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.if_gallery_exists = undefined;
          // }, 3000)
        }
        else {
          $scope.if_gallery_exists = "Couldn't fetch images !";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.if_gallery_exists = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.if_gallery_exists = "Something's Wrong!";
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.if_gallery_exists = undefined;
        // }, 3000)
      })

  };

  $scope.load();

  $rootScope.added_image = true;

  $scope.form = [];
  $scope.files = [];

  // $scope.uploadedFile = function(element) {
  //   $scope.currentFile = element.files[0];
  //   var reader = new FileReader();
  //
  //
  //   reader.onload = function(event) {
  //     $scope.image_source = event.target.result
  //     $scope.$apply(function($scope) {
  //       $scope.files = element.files;
  //     });
  //   }
  //               reader.readAsDataURL(element.files[0]);
  // };

  var uploadForm = new FormData();

  $scope.count_files = 0;

  $scope.uploadedFile = function(element) {
    $scope.count_files = 0;
    uploadForm.set('file[]', undefined);
    angular.forEach(element.files, function(file){
        uploadForm.append('file[]', file);
        $scope.count_files += 1;
    });
  };

  $scope.saveUpload = function () {

    if ($scope.count_files > 20) {
      $scope.showStatus = "Not more than 20 files at a time";
      $timeout(function () {
        $scope.showStatus = undefined;
      }, 3000)
    }
    else {

      $scope.showStatus = undefined;
      $scope.shouldIShow = true;

      $scope.continue_action = function () {

        $scope.showProgress = false;
        $rootScope.fileUploadStatusError = undefined;
        $rootScope.fileUploadStatusSuccess = undefined;
        $scope.errorFileUpload = false;
        $scope.successFileUpload = false;
        $scope.showSuccessStatus = undefined;
        $scope.showStatus = undefined;

        if ($scope.count_files > 20) {
          $scope.showStatus = "Not more than 20 files at a time";
          $timeout(function () {
            $scope.showStatus = undefined;
          }, 3000)
        }
        else {

          // var uploadForm = new FormData();
          uploadForm.append('user_unique_id', $scope.result_u);
          uploadForm.append('edit_user_unique_id', $scope.result_u);
          // angular.forEach($scope.filez, function(file){
          //     uploadForm.append('file[]', file);
          // });
          $http.post('server/uploadgalleryimage.php', uploadForm, {
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
                $rootScope.fileUploadStatusError = undefined;

                $scope.successFileUpload = true;
                $rootScope.fileUploadStatusSuccess = response.data.message;
                $scope.notification_message = "Gallery " + $rootScope.fileUploadStatusSuccess;

                $scope.clickOnce = true;

                $scope.showSuccessStatus = "Image Saved";

                notify.do_notify($scope.result_u, "Add Activity", $scope.notification_message);

                $timeout(function () {
                  $scope.showProgress = false;
                  $scope.showStatus = undefined;
                  $scope.showSuccessStatus = undefined;
                  $rootScope.fileUploadStatusError = undefined;
                  $rootScope.fileUploadStatusSuccess = undefined;
                  window.location.reload(true);
                }, 2000)
              }
          }, function (error) {
            // console.log(response);
            $scope.showProgress = false;
            $scope.errorFileUpload = true;
            $rootScope.fileUploadStatusError = error.data.message;
            $timeout(function(response){
              $rootScope.fileUploadStatusError = "";
              $scope.clickOnce = false;
              $scope.errorFileUpload = false;
            },5000);
          })

          // for(var pair of uploadForm.entries()) {
          //   console.log(pair[0]+ ', '+ pair[1]);
          // }

        }

      };

      $scope.removeConfirmModal = function () {
        $scope.shouldIShow = undefined;
      };

    }

  };

  $scope.showPreview = function (image) {
    $scope.analysis_preview = "../" + image;

    $window.open($scope.analysis_preview, "_blank");
  };

  $scope.copied = false;

  $scope.copy_image_link = function (link, unique_id) {

    $scope.unique_id = unique_id;

    $scope.link = window.location.protocol + "//" + window.location.hostname + "/" + link;
    // let raw_link = link;
    // let split_raw = raw_link.split("office/");
    // let splitted_raw = split_raw[1];
    // $scope.link = link;

    ngClipboard.toClipboard($scope.link);
    $scope.copied = true;
    $scope.copied_image = "Copied";
    $timeout(function() {
      $scope.copied = false;
      $scope.copied_image = undefined;
    }, 5000)

  };

  $scope.deleteImage = function (unique_id) {

    // modalOpen('deleteImageModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.deleteData = {
        "unique_id":unique_id
      }

      $http.post('server/delete_gallery_image.php', $scope.deleteData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {
            $scope.showSuccessStatus = "Image Deleted";
            notify.do_notify($scope.result_u, "Delete Activity", "Gallery image deleted");
            $timeout(function () {
              $scope.showStatus = undefined;
              $scope.showSuccessStatus = undefined;
              $rootScope.imageStatus = undefined;
              // modalClose('deleteImageModal');
              window.location.reload(true);
              $scope.load();
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
