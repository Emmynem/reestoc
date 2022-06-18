xnyder.controller('productsCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$routeParams,$window,storage,notify){

  $rootScope.pageTitle = "Products";

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
    $scope.loadProducts();
  };

  $scope.options = {
    height:250,
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

  $scope.loadProducts = function () {

    $http.get('../../ng/server/get/get_all_products.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allProductsStatus = undefined;
          $scope.allProducts = response.data.resultData;
          $scope.allProductsCount = $scope.allProducts.length;

          $scope.remove_loader();

          $scope.currentPage=1;
          $scope.numLimit=100;
          $scope.start = 0;
          $scope.$watch('allProducts',function(newVal){
            if(newVal){
             $scope.pages=Math.ceil($scope.allProducts.length/$scope.numLimit);

            }
          });
          $scope.hideNext=function(){
            if(($scope.start+ $scope.numLimit) < $scope.allProducts.length){
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
          $scope.allProductsStatus = response.data.engineErrorMessage;
          $scope.allProductsCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allProductsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allProductsStatus = "Error Occured";
          $scope.allProductsCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allProductsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allProductsStatus = "Something's Wrong";
        $scope.allProductsCount = 0;
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allProductsStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadProducts();

  $scope.loadProductMiniCategories = function (sub_category_unique_id) {

    $scope.allProductMiniCategories = null;

    $scope.genericData = {
      "sub_category_unique_id":sub_category_unique_id
    }

    $http.post('../../ng/server/get/get_sub_category_mini_categories.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allProductMiniCategoriesStatus = undefined;
          $scope.allProductMiniCategories = response.data.resultData;
        }
        else if (response.data.engineError == 2) {
          $scope.allProductMiniCategoriesStatus = response.data.engineErrorMessage;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allProductMiniCategoriesStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allProductMiniCategoriesStatus = "Error Occured";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allProductMiniCategoriesStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allProductMiniCategoriesStatus = "Something's Wrong";
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allProductMiniCategoriesStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadProductSubCategories = function (category_unique_id) {

    $scope.allProductSubCategories = null;

    $scope.genericData = {
      "category_unique_id":category_unique_id
    }

    $http.post('../../ng/server/get/get_category_sub_categories.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allProductSubCategoriesStatus = undefined;
          $scope.allProductSubCategories = response.data.resultData;
        }
        else if (response.data.engineError == 2) {
          $scope.allProductSubCategoriesStatus = response.data.engineErrorMessage;
          // $timeout(function () {
          //   $scope.allProductSubCategoriesStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allProductSubCategoriesStatus = "Error Occured";
          // $timeout(function () {
          //   $scope.allProductSubCategoriesStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allProductSubCategoriesStatus = "Something's Wrong";
        // $timeout(function () {
        //   $scope.allProductSubCategoriesStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadProductAddMiniCategories = function (sub_category_unique_id) {

    $scope.allProductAddMiniCategories = undefined;

    $scope.genericData = {
      "sub_category_unique_id":sub_category_unique_id
    }

    $http.post('../../ng/server/get/get_sub_category_mini_categories.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allProductAddMiniCategories = null;
          $scope.allProductAddMiniCategoriesStatus = undefined;
          $scope.allProductAddMiniCategories = response.data.resultData;
        }
        else if (response.data.engineError == 2) {
          $scope.allProductAddMiniCategoriesStatus = response.data.engineErrorMessage;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allProductAddMiniCategoriesStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allProductAddMiniCategoriesStatus = "Error Occured";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allProductAddMiniCategoriesStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allProductAddMiniCategoriesStatus = "Something's Wrong";
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allProductAddMiniCategoriesStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadProductAddSubCategories = function (category_unique_id) {

    $scope.allProductAddSubCategories = undefined;

    $scope.genericData = {
      "category_unique_id":category_unique_id
    }

    $http.post('../../ng/server/get/get_category_sub_categories.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allProductAddSubCategories = null;
          $scope.allProductAddSubCategoriesStatus = undefined;
          $scope.allProductAddSubCategories = response.data.resultData;
        }
        else if (response.data.engineError == 2) {
          $scope.allProductAddSubCategoriesStatus = response.data.engineErrorMessage;
          // $timeout(function () {
          //   $scope.allProductAddSubCategoriesStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allProductAddSubCategoriesStatus = "Error Occured";
          // $timeout(function () {
          //   $scope.allProductAddSubCategoriesStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allProductAddSubCategoriesStatus = "Something's Wrong";
        // $timeout(function () {
        //   $scope.allProductAddSubCategoriesStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadProductCategories = function () {

    $http.get('../../ng/server/get/get_all_categories.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allProductCategoriesStatus = undefined;
          $scope.allProductCategories = response.data.resultData;
        }
        else if (response.data.engineError == 2) {
          $scope.allProductCategoriesStatus = response.data.engineErrorMessage;
          // $timeout(function () {
          //   $scope.allProductCategoriesStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allProductCategoriesStatus = "Error Occured";
          // $timeout(function () {
          //   $scope.allProductCategoriesStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allProductCategoriesStatus = "Something's Wrong";
        // $timeout(function () {
        //   $scope.allProductCategoriesStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadProductCategories();

  $scope.loadBrands = function () {

    $http.get('../../ng/server/get/get_all_brands_for_select.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allBrandsStatus = undefined;
          $scope.allBrands = response.data.resultData;
        }
        else if (response.data.engineError == 2) {
          $scope.allBrandsStatus = response.data.engineErrorMessage;
          // $timeout(function () {
          //   $scope.allBrandsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allBrandsStatus = "Error Occured";
          // $timeout(function () {
          //   $scope.allBrandsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allBrandsStatus = "Something's Wrong";
        // $timeout(function () {
        //   $scope.allBrandsStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadBrands();

  $scope.product = {};

  $scope.product_images = {};

  $scope.save_product = function () {

    $scope.shouldIShow = true;

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.genericData = {
        "user_unique_id":$scope.result_u,
        "category_unique_id":$scope.product.add_category_unique_id,
        "sub_category_unique_id":$scope.product.add_sub_category_unique_id,
        "mini_category_unique_id":$scope.product.add_mini_category_unique_id,
        "name":$scope.product.add_name,
        "brand_unique_id":$scope.product.add_brand_unique_id ? $scope.product.add_brand_unique_id : null,
        "description":$scope.product.add_description
      }

      $http.post('../../ng/server/data/products/add_new_product.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {

          $scope.showSuccessStatus = true;
          $scope.actionStatus = "Product added successfully !";

          notify.do_notify($scope.result_u, "Add Activity", "Product Added");

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

  $scope.edit_product_details = function (product_unique_id, stripped) {

    $scope.genericData = {
      "product_unique_id":product_unique_id ? product_unique_id : null,
      "stripped":stripped ? stripped : null
    }

    $http.post('../../ng/server/get/get_product_details.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.productDetailsStatus = undefined;
          $scope.productDetails = response.data.resultData;
          $scope.product.name = $scope.productDetails[0].name;
          $scope.product.brand_unique_id = $scope.productDetails[0].brand_unique_id;
          $scope.product.description = $scope.productDetails[0].description;

          $scope.go_ahead = function () {
            $scope.shouldIShow = true;
          };

          $scope.continue_action = function () {

            $scope.clickOnce = true;

            $scope.genericData = {
              "user_unique_id":$scope.result_u,
              "unique_id":product_unique_id,
              "name":$scope.product.name,
              "brand_unique_id":$scope.product.brand_unique_id,
              "description":$scope.product.description
            }

            $http.post('../../ng/server/data/products/update_product_details.php', $scope.genericData)
            .then(function (response) {
              if (response.data.engineMessage == 1) {

                $scope.showSuccessStatus = true;
                $scope.actionStatus = "Changes Saved !";

                notify.do_notify($scope.result_u, "Edit Activity", "Product edited");

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
          $scope.productDetailsStatus = response.data.engineErrorMessage;
          // $timeout(function () {
          //   $scope.productDetailsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.productDetailsStatus = "Error Occured";
          // $timeout(function () {
          //   $scope.productDetailsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.productDetailsStatus = "Something's Wrong";
        // $timeout(function () {
        //   $scope.productDetailsStatus = undefined;
        // }, 3000)
      })

    $scope.removeConfirmModal = function () {

      $scope.shouldIShow = undefined;

    };
  };

  $scope.edit_product_categories = function (product_unique_id, stripped, mini_category_unique_id, sub_category_unique_id, category_unique_id, name) {

    $scope.product_name = name;

    $scope.genericData = {
      "product_unique_id":product_unique_id ? product_unique_id : null,
      "stripped":stripped ? stripped : null
    }

    $http.post('../../ng/server/get/get_product_details.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.productDetailsStatus = undefined;
          $scope.productDetails = response.data.resultData;
          $scope.product.edit_mini_category_unique_id = $scope.productDetails[0].mini_category_unique_id;
          $scope.product.edit_sub_category_unique_id = $scope.productDetails[0].sub_category_unique_id;
          $scope.product.edit_category_unique_id = $scope.productDetails[0].category_unique_id;

          $scope.loadProductCategories();
          $scope.loadProductSubCategories($scope.product.edit_category_unique_id);
          $scope.loadProductMiniCategories($scope.product.edit_sub_category_unique_id);

          $scope.go_ahead = function () {
            $scope.shouldIShow = true;
          };

          $scope.continue_action = function () {

            $scope.clickOnce = true;

            $scope.genericData = {
              "user_unique_id":$scope.result_u,
              "unique_id":product_unique_id,
              "mini_category_unique_id":$scope.product.edit_mini_category_unique_id,
              "sub_category_unique_id":$scope.product.edit_sub_category_unique_id,
              "category_unique_id":$scope.product.edit_category_unique_id
            }

            $http.post('../../ng/server/data/products/update_product_categories.php', $scope.genericData)
            .then(function (response) {
              if (response.data.engineMessage == 1) {

                $scope.showSuccessStatus = true;
                $scope.actionStatus = "Changes Saved !";

                notify.do_notify($scope.result_u, "Edit Activity", "Product Categories edited");

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
          $scope.productDetailsStatus = response.data.engineErrorMessage;
          // $timeout(function () {
          //   $scope.productDetailsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.productDetailsStatus = "Error Occured";
          // $timeout(function () {
          //   $scope.productDetailsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.productDetailsStatus = "Something's Wrong";
        // $timeout(function () {
        //   $scope.productDetailsStatus = undefined;
        // }, 3000)
      })

    $scope.removeConfirmModal = function () {

      $scope.shouldIShow = undefined;

    };
  };

  $scope.delete_product = function (unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "unique_id":unique_id,
      }

      $http.post('../../ng/server/data/products/remove_product.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "Product Removed");

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

  $scope.restore_product = function (unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "unique_id":unique_id,
      }

      $http.post('../../ng/server/data/products/restore_product.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Add Activity", "Product Restored");

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

  $scope.get_product_images = function (product_unique_id, name) {

    $scope.product_name = name;
    $scope.the_product_unique_id = product_unique_id;

    $scope.genericData = {
      "product_unique_id":product_unique_id
    }

    $http.post('../../ng/server/get/get_product_images.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.productImages = null;
          $scope.productImagesStatus = undefined;
          $scope.productImages = response.data.resultData;
          $scope.productImagesCount = $scope.productImages.length;
        }
        else if (response.data.engineError == 2) {
          $scope.productImagesStatus = response.data.engineErrorMessage;
          $scope.productImagesCount = 0;
          // $timeout(function () {
          //   $scope.productImagesStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.productImagesStatus = "Error Occured";
          // $timeout(function () {
          //   $scope.productImagesStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.productImagesStatus = "Something's Wrong";
        // $timeout(function () {
        //   $scope.productImagesStatus = undefined;
        // }, 3000)
      })

  };

  $scope.upload_product_images = function(){

    if ($scope.count_files > 10) {
      $scope.errorFileUpload = true;
      $scope.fileUploadStatusError = "Not more than 10 files at a time";
      $timeout(function () {
        $scope.fileUploadStatusError = undefined;
        $scope.errorFileUpload = false;
      }, 3000)
    }
    else {
      $scope.showProgress = false;
      $rootScope.fileUploadStatusError = undefined;
      $rootScope.fileUploadStatusSuccess = undefined;
      $scope.errorFileUpload = false;
      $scope.successFileUpload = false;
      $scope.showSuccessStatus = undefined;
      $scope.showStatus = undefined;

      var uploadForm = new FormData();
      uploadForm.append('user_unique_id', $scope.result_u);
      uploadForm.append('upload_type', $scope.product_images.upload_type);
      uploadForm.append('product_unique_id', $scope.product_images.product_unique_id);
      angular.forEach($scope.filez, function(file){
          uploadForm.append('file[]', file);
      });
      $http.post('../../ng/server/data/product_images/add_product_image_select_type.php', uploadForm, {
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
        if (response.data.engineMessage == 1) {
          $rootScope.fileUploadStatusError = undefined;

          $scope.showProgress = false;
          $scope.successFileUpload = true;
          $rootScope.fileUploadStatusSuccess = response.data.resultData;

          $scope.clickOnce = true;

          $scope.showSuccessStatus = "Files Uploaded as ";

        }
        else if(response.data.engineError == 2){
          $scope.showProgress = false;
          $scope.errorFileUpload = true;
          $rootScope.fileUploadStatusError = response.data.engineErrorMessage;
          $rootScope.showStatus = response.data.engineErrorMessage;
          $timeout(function(response){
            $rootScope.fileUploadStatusError = "";
            $scope.clickOnce = false;
            $scope.errorFileUpload = false;
          },5000);
        }
        else if(response.data.engineError == 3){
          $scope.showProgress = false;
          $scope.errorFileUpload = true;
          $rootScope.fileUploadStatusError = response.data.engineErrorMessage;
          $rootScope.showStatus = response.data.engineErrorMessage;
          $timeout(function(response){
            $rootScope.fileUploadStatusError = "";
            $scope.clickOnce = false;
            $scope.errorFileUpload = false;
          },5000);
        }
        else{
          $scope.showProgress = false;
          $scope.errorFileUpload = true;
          $rootScope.fileUploadStatusError = "An Error Occured";
          $timeout(function(response){
            $rootScope.fileUploadStatusError = "";
            $scope.clickOnce = false;
            $scope.errorFileUpload = false;
          },5000);
        }
      }, function (error) {
        // console.log(response);
        $scope.showProgress = false;
        $scope.errorFileUpload = true;
        $rootScope.fileUploadStatusError = error.data.message;
        $rootScope.showStatus = $rootScope.fileUploadStatusError;
        $timeout(function(){
          $rootScope.fileUploadStatusError = "";
          $scope.clickOnce = false;
          $scope.errorFileUpload = false;
        },5000);
      })

    }

  };

  // $scope.edit_product_image = function(product_image_unique_id){
  //
  //   if ($scope.count_files > 1) {
  //     $scope.errorFileUpload = true;
  //     $scope.fileUploadStatusError = "Not more than 1 file at a time";
  //     $timeout(function () {
  //       $scope.fileUploadStatusError = undefined;
  //       $scope.errorFileUpload = false;
  //     }, 3000)
  //   }
  //   else {
  //     $scope.showProgress = false;
  //     $rootScope.fileUploadStatusError = undefined;
  //     $rootScope.fileUploadStatusSuccess = undefined;
  //     $scope.errorFileUpload = false;
  //     $scope.successFileUpload = false;
  //     $scope.showSuccessStatus = undefined;
  //     $scope.showStatus = undefined;
  //
  //     var uploadForm = new FormData();
  //     uploadForm.append('user_unique_id', $scope.result_u);
  //     uploadForm.append('upload_type', $scope.product_images.upload_type);
  //     uploadForm.append('product_unique_id', $scope.product_images.product_unique_id);
  //     uploadForm.append('product_image_unique_id', product_image_unique_id);
  //     angular.forEach($scope.filez, function(file){
  //         uploadForm.append('file[]', file);
  //     });
  //     $http.post('../../ng/server/data/product_images/edit_product_image_select_type.php', uploadForm, {
  //       transformRequest:angular.identity,
  //       headers: {'Content-Type':undefined, 'Process-Data': false},
  //       uploadEventHandlers: {
  //         progress: function (e) {
  //           if (e.lengthComputable) {
  //             $scope.showProgress = true;
  //             $scope.progressBar = (e.loaded / e.total) * 100;
  //             $scope.progressCounter = $scope.progressBar.toFixed(2) + '%';
  //           }
  //         }
  //       }
  //     })
  //     .then(function(response){
  //       if (response.data.engineMessage == 1) {
  //         $rootScope.fileUploadStatusError = undefined;
  //
  //         $scope.showProgress = false;
  //         $scope.successFileUpload = true;
  //         $rootScope.fileUploadStatusSuccess = response.data.resultData;
  //
  //         $scope.clickOnce = true;
  //
  //         $scope.showSuccessStatus = "Files Uploaded as ";
  //
  //       }
  //       else if(response.data.engineError == 2){
  //         $scope.showProgress = false;
  //         $scope.errorFileUpload = true;
  //         $rootScope.fileUploadStatusError = response.data.engineErrorMessage;
  //         $rootScope.showStatus = response.data.engineErrorMessage;
  //         $timeout(function(response){
  //           $rootScope.fileUploadStatusError = "";
  //           $scope.clickOnce = false;
  //           $scope.errorFileUpload = false;
  //         },5000);
  //       }
  //       else if(response.data.engineError == 3){
  //         $scope.showProgress = false;
  //         $scope.errorFileUpload = true;
  //         $rootScope.fileUploadStatusError = response.data.engineErrorMessage;
  //         $rootScope.showStatus = response.data.engineErrorMessage;
  //         $timeout(function(response){
  //           $rootScope.fileUploadStatusError = "";
  //           $scope.clickOnce = false;
  //           $scope.errorFileUpload = false;
  //         },5000);
  //       }
  //       else{
  //         $scope.showProgress = false;
  //         $scope.errorFileUpload = true;
  //         $rootScope.fileUploadStatusError = "An Error Occured";
  //         $timeout(function(response){
  //           $rootScope.fileUploadStatusError = "";
  //           $scope.clickOnce = false;
  //           $scope.errorFileUpload = false;
  //         },5000);
  //       }
  //     }, function (error) {
  //       // console.log(response);
  //       $scope.showProgress = false;
  //       $scope.errorFileUpload = true;
  //       $rootScope.fileUploadStatusError = error.data.message;
  //       $rootScope.showStatus = $rootScope.fileUploadStatusError;
  //       $timeout(function(){
  //         $rootScope.fileUploadStatusError = "";
  //         $scope.clickOnce = false;
  //         $scope.errorFileUpload = false;
  //       },5000);
  //     })
  //
  //   }
  //
  // };

  $scope.add_product_image = function (product_unique_id) {
    $location.path("/add-product-image/" + product_unique_id);
  };

  $scope.edit_product_image = function (product_unique_id, product_image_unique_id) {
    $location.path("/edit-product-image/" + product_unique_id + "/" + product_image_unique_id);
  };

  $scope.delete_product_image = function (product_unique_id, product_image_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.shouldIShow = true;

    $scope.unique_id = product_image_unique_id;

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "product_unique_id":product_unique_id,
        "product_image_unique_id":product_image_unique_id
      }

      $http.post('../../ng/server/data/product_images/remove_product_image.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "Product Image Removed");

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
