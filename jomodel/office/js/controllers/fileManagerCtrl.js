xnyder.controller('file-managerCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$window,storage,notify){

  $rootScope.pageTitle = "File Manager";

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
    $scope.loadFiles();
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
        "table":"files",
        "startdate":$scope.filter_var.startdate,
        "enddate":$scope.filter_var.enddate
      }

      $http.post('server/filter_files.php', $scope.filter_data)
        .then(function (response) {
          if (response.data.engineMessage == 1) {
            $scope.allFilesStatus = undefined;
            $scope.allFiles = response.data.filteredData;

            $scope.remove_loader();

            $scope.currentPage=1;
            $scope.numLimit=20;
            $scope.start = 0;
            $scope.$watch('allFiles',function(newVal){
              if(newVal){
               $scope.pages=Math.ceil($scope.allFiles.length/$scope.numLimit);

              }
            });
            $scope.hideNext=function(){
              if(($scope.start+ $scope.numLimit) < $scope.allFiles.length){
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
            $scope.allFilesStatus = "No data in range !";
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allFilesStatus = undefined;
            // }, 3000)
          }
          else {
            $scope.allFilesStatus = "Couldn't get data !";
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allFilesStatus = undefined;
            // }, 3000)
          }
        }, function (error) {
          $scope.allFilesStatus = "Something's Wrong!";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allFilesStatus = undefined;
          // }, 3000)
        })
    }
  };

  $scope.loadFiles = function () {

    $http.get('server/get_files.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allFilesStatus = undefined;
          $scope.allFiles = response.data.re_data;

          $scope.remove_loader();

          $scope.currentPage=1;
          $scope.numLimit=20;
          $scope.start = 0;
          $scope.$watch('allFiles',function(newVal){
            if(newVal){
             $scope.pages=Math.ceil($scope.allFiles.length/$scope.numLimit);

            }
          });
          $scope.hideNext=function(){
            if(($scope.start+ $scope.numLimit) < $scope.allFiles.length){
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
          $scope.allFilesStatus = "No files found !";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allFilesStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allFilesStatus = "Error Occured";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allFilesStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allFilesStatus = "Something's Wrong";
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allFilesStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadFiles();

  $rootScope.added_image = true;

  $scope.form = [];
  $scope.files = [];

  $scope.uploadedFile = function(element) {
    $scope.currentFile = element.files[0];
    var reader = new FileReader();


    reader.onload = function(event) {
      // $scope.image_source = event.target.result
      $scope.$apply(function($scope) {
        $scope.files = element.files;
      });
    }
                reader.readAsDataURL(element.files[0]);
  };

  $scope.upload = function(){

    $scope.shouldIShow = true;

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.form.file = $scope.files[0];

      $http({
        method  : 'POST',
        url     : 'server/uploadfile.php',
        processData: false,
        transformRequest: function (data) {
            var formData = new FormData();
            formData.append('file', $scope.form.file);
            return formData;
        },
        data : $scope.form,
        headers: {
               'Content-Type': undefined
        },
        uploadEventHandlers: {
          progress: function (e) {
            if (e.lengthComputable) {
                  $scope.showProgress = true;
                  $scope.progressBar = (e.loaded / e.total) * 100;
                  $scope.progressCounter = $scope.progressBar.toFixed(2) + '% ';
            }
          }
        }
      })
      .then(function(response){

        if(response.data.invalidFormat == 3){
          $scope.showProgress = false;
          $scope.errorFileUpload = true;
          $rootScope.fileUploadStatusError = "Only PDFs, TXTs, Word Docs, Excel, Powerpoint, Zip and Rar files allowed";
          $timeout(function(response){
            $rootScope.fileUploadStatusError = "";
            $scope.clickOnce = false;
            $scope.errorFileUpload = false;
          },5000);
        }
        else if(response.data.fileLarge == 2){
          $scope.showProgress = false;
          $scope.errorFileUpload = true;
          $rootScope.fileUploadStatusError = "File too large, must be less than 100mb ...";
          $timeout(function(response){
            $rootScope.fileUploadStatusError = "";
            $scope.clickOnce = false;
            $scope.errorFileUpload = false;
          },5000);
        }
        else if (response.data.return_file == null) {
          $scope.showProgress = false;
          $scope.errorFileUpload = true;
          $rootScope.fileUploadStatusError = "No file selected ...";
          $timeout(function(response){
            $rootScope.fileUploadStatusError = "";
            $scope.clickOnce = false;
            $scope.errorFileUpload = false;
          },3000);
        }
        else {

          if(response.data.engineMessage == 1){
            // $scope.showProgress = false;
            $scope.successFileUpload = true;
            $rootScope.myfile_file = response.data.return_file;
            $rootScope.myfile_name = response.data.return_filename;
            $rootScope.myfile_size = response.data.return_filesize;
            $rootScope.fileUploadStatusSuccess = "File uploaded successfully ...";

            $timeout(function(response){
              if ($rootScope.myfile_file == null || $rootScope.myfile_file == undefined || $rootScope.myfile_file == "") {
                $scope.showProgress = false;
                $rootScope.fileUploadStatusError = "No file available !";
                $scope.showStatus = "Check Form";
                $timeout(function () {
                  $rootScope.fileUploadStatusError = "";
                  $scope.showStatus = undefined;
                  $scope.clickOnce = false;
                }, 3000)
              }
              else {

                $scope.clickOnce = true;

                $scope.uploadMagazineData = {
                  "user_unique_id":$scope.result_u,
                  "save_as_name":$scope.save_as_name,
                  "file_name":$rootScope.myfile_name,
                  "file_extension":$rootScope.myfile_file,
                  "file_size":$rootScope.myfile_size
                }

                $http.post('server/add_file_upload.php', $scope.uploadMagazineData)
                  .then(function (response) {
                    if (response.data.engineMessage == 1) {
                      $scope.showSuccessStatus = "File Saved";

                      notify.do_notify($scope.result_u, "Add Activity", "File uploaded in file manager");

                      $timeout(function () {
                        $scope.showProgress = false;
                        $scope.showStatus = undefined;
                        $scope.showSuccessStatus = undefined;
                        $rootScope.fileUploadStatusError = undefined;
                        $rootScope.fileUploadStatusSuccess = undefined;
                        window.location.reload(true);
                      }, 3000)
                    }
                    else {
                      $scope.showProgress = false;
                      $scope.showStatus = "Error Occured";
                      $timeout(function () {
                        $scope.clickOnce = false;
                        $scope.showStatus = undefined;
                        $scope.showSuccessStatus = undefined;
                      }, 3000)
                    }
                  }, function (error) {
                    $scope.showProgress = false;
                    $scope.showStatus = "Something's Wrong";
                    $timeout(function () {
                      $scope.clickOnce = false;
                      $scope.showStatus = undefined;
                      $scope.showSuccessStatus = undefined;
                    }, 3000)
                  })

              }
            },3000);
          }
          else{
            $scope.errorFileUpload = true;
            $rootScope.fileUploadStatusError = "An error occured on magazine";
            $scope.clickOnce = false;
          }
        }

      }, function (error) {
        console.error("Error occured");
        $scope.clickOnce = false;
      })

    };

    $scope.removeConfirmModal = function () {

      $scope.shouldIShow = undefined;

    };

  };

  $scope.showPreview = function (file) {

    $scope.analysis_preview = "../" + file;

    notify.do_notify($scope.result_u, "Add Activity", "Previewed file from file manager");

    $window.open($scope.analysis_preview, "_blank");
  };

  $scope.add_download = function (file_name, file_extension) {

    $scope.continue_action = function () {

      $scope.showSuccessStatus = "Download started ...";

      notify.do_notify($scope.result_u, "Add Activity", "Downloaded file from file manager");

      var link = document.createElement('a');
      link.href = "../" + file_extension;
      link.download = file_name;
      link.dispatchEvent(new MouseEvent('click'));

      $timeout(function () {
        $scope.showSuccessStatus = undefined;
        // window.location.reload(true);
      }, 4000)

    };

  };

  $scope.deleteFile = function (unique_id, file_extension) {

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.deleted = 0;

      $scope.confirmData = {
        "unique_id":unique_id,
        "file":file_extension
      }

      $http.post('server/delete_file.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "File deleted from file manager");

            $timeout(function () {
              $scope.showSuccessStatus = undefined;
              // $route.reload();
              window.location.reload(true);
            }, 3000)

          }
          else if (response.data.noData == 2) {
            $scope.showStatus = "No Data Found";
            $timeout(function () {
              $scope.showStatus = undefined;
            }, 3000)
          }
          else {
            $scope.showStatus = "Error Occured";
            $timeout(function () {
              $scope.showStatus = undefined;
            }, 3000)
          }
        }, function (error) {
          $scope.showStatus = "Something's Wrong";
          $timeout(function () {
            $scope.showStatus = undefined;
          }, 3000)
        })

    };

  };

});
