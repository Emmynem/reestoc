xnyder.controller('edit-productCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$routeParams,$window,$sanitize,storage,notify,strip_text){

  $rootScope.pageTitle = "Edit Product";

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

  $scope.unique_id = $routeParams.unique_id;

  $scope.options = {
    height:300,
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

  $scope.product = {};

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

  $scope.edit_product = function (unique_id) {

    // modalOpen('editProductModal', 'medium');

    $scope.productData = {
      "unique_id":unique_id
    }

    $http.post("server/get_shop_details.php", $scope.productData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.editProductStatus = undefined;
          $scope.details = response.data.re_data;
          $scope.product.product_name = $scope.details.product_name;
          $scope.product.product_price = parseInt($scope.details.product_price);
          $scope.product.product_url = $scope.details.product_url;
          $scope.product.product_description = $scope.details.product_description;
          $scope.product.old_product_image = $scope.details.product_image;

          $scope.remove_loader();

          $scope.editProductSuccessStatus = "Product loaded ...";
          $timeout(function () {
            $scope.editProductSuccessStatus = undefined;
          }, 3000)

        }
        else if (response.data.noData == 2) {
          $scope.showStatus = true;
          $scope.actionStatus = "Couldn't get details !";
          $scope.editProductStatus = $scope.actionStatus;
          $timeout(function () {
            $scope.showStatus = undefined;
            $scope.actionStatus = undefined;
            $scope.editProductStatus = undefined;
            $scope.clickOnce = false;
          }, 3000)
        }
        else {
          $scope.showStatus = true;
          $scope.actionStatus = "Error Occured !";
          $scope.editProductStatus = $scope.actionStatus;
          $timeout(function () {
            $scope.showStatus = undefined;
            $scope.actionStatus = undefined;
            $scope.editProductStatus = undefined;
            $scope.clickOnce = false;
          }, 3000)
        }
      }, function (error) {
        $scope.showStatus = true;
        $scope.actionStatus = "Something's Wrong !";
        $scope.editProductStatus = $scope.actionStatus;
        $timeout(function () {
          $scope.showStatus = undefined;
          $scope.actionStatus = undefined;
          $scope.editProductStatus = undefined;
          $scope.clickOnce = false;
        }, 3000)
      })

  };

  $scope.edit_product($scope.unique_id);

  $scope.saveUploadChanges = function () {

    $scope.shouldIShow = true;

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.editProductData = {

        "edit_user_unique_id":$scope.result_u,
        "unique_id":$scope.unique_id,
        "product_name":$scope.product.product_name,
        "stripped":strip_text.get_stripped($scope.product.product_name),
        "product_image":$scope.product.old_product_image,
        "product_price":$scope.product.product_price,
        "product_url":$scope.product.product_url,
        "product_description":$scope.product.product_description
      }

      $scope.form.image = $scope.files[0];

      var uploadForm = new FormData();
      uploadForm.append("file", $scope.form.image);
      $http.post('server/uploadproductimage.php', uploadForm, {
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

            $http.post('server/edit_shop.php', $scope.editProductData)
              .then(function (response) {
                if (response.data.engineMessage == 1) {

                  $scope.showSuccessStatus = true;
                  $scope.actionStatus = "Changes Saved !";

                  notify.do_notify($scope.result_u, "Edit Activity", "Product Edited");

                  $timeout(function () {
                    $scope.showSuccessStatus = undefined;
                    $scope.actionStatus = undefined;
                    $scope.showProgress = false;
                    $rootScope.fileUploadStatusError = undefined;
                    $rootScope.fileUploadStatusSuccess = undefined;
                    $route.reload();
                    $location.path('shop');
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
          $rootScope.edit_product_image = response.data.return_file;
          $rootScope.fileUploadStatusSuccess = response.data.message;

          $scope.editProductData.product_image = $rootScope.edit_product_image;

          $scope.clickOnce = true;

          $http.post('server/edit_shop.php', $scope.editProductData)
            .then(function (response) {
              if (response.data.engineMessage == 1) {

                $scope.showSuccessStatus = true;
                $scope.actionStatus = "Changes Saved !";
                notify.do_notify($scope.result_u, "Edit Activity", "Product Edited");

                $scope.delete_this = {
                  // "unique_id":$scope.post_route_unique_id
                  "old_image":$scope.product.old_product_image
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
                  $route.reload();
                  $location.path('shop');
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
      $scope.shouldIShow = false;
    };

  };

});
