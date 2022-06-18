xnyder.controller('sub-productsCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$routeParams,$window,storage,notify){

  $rootScope.pageTitle = "Sub Products";

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
    $scope.loadSubProducts();
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

  $scope.loadSubProducts = function () {

    $http.get('../../ng/server/get/get_all_sub_products.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allSubProductsStatus = undefined;
          $scope.allSubProducts = response.data.resultData;
          $scope.allSubProductsCount = $scope.allSubProducts.length;

          $scope.remove_loader();

          $scope.currentPage=1;
          $scope.numLimit=100;
          $scope.start = 0;
          $scope.$watch('allSubProducts',function(newVal){
            if(newVal){
             $scope.pages=Math.ceil($scope.allSubProducts.length/$scope.numLimit);

            }
          });
          $scope.hideNext=function(){
            if(($scope.start+ $scope.numLimit) < $scope.allSubProducts.length){
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
          $scope.allSubProductsStatus = response.data.engineErrorMessage;
          $scope.allSubProductsCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allSubProductsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allSubProductsStatus = "Error Occured";
          $scope.allSubProductsCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allSubProductsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allSubProductsStatus = "Something's Wrong";
        $scope.allSubProductsCount = 0;
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allSubProductsStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadSubProducts();

  $scope.loadProducts = function () {

    $http.get('../../ng/server/get/get_all_products_for_select.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allProductsStatus = undefined;
          $scope.allProducts = response.data.resultData;
        }
        else if (response.data.engineError == 2) {
          $scope.allProductsStatus = response.data.engineErrorMessage;
          // $timeout(function () {
          //   $scope.allProductsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allProductsStatus = "Error Occured";
          // $timeout(function () {
          //   $scope.allProductsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allProductsStatus = "Something's Wrong";
        // $timeout(function () {
        //   $scope.allProductsStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadProducts();

  $scope.sub_product = {};

  $scope.sub_product_images = {};

  $scope.save_sub_product = function () {

    $scope.shouldIShow = true;

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.sub_product.add_stock = 0;

      $scope.genericData = {
        "user_unique_id":$scope.result_u,
        "product_unique_id":$scope.sub_product.add_product_unique_id,
        "name":$scope.sub_product.add_name,
        "size":$scope.sub_product.add_size ? $scope.sub_product.add_size : null,
        "description":$scope.sub_product.add_description,
        "stock":$scope.sub_product.add_stock,
        "price":$scope.sub_product.add_price,
        "sales_price":$scope.sub_product.add_sales_price
      }

      $http.post('../../ng/server/data/sub_products/add_new_sub_product.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {

          $scope.showSuccessStatus = true;
          $scope.actionStatus = "Sub Product added successfully !";

          notify.do_notify($scope.result_u, "Add Activity", "Sub Product Added");

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

  $scope.edit_sub_product_details = function (sub_product_unique_id, stripped) {

    $scope.genericData = {
      "sub_product_unique_id":sub_product_unique_id ? sub_product_unique_id : null,
      "stripped":stripped ? stripped : null
    }

    $http.post('../../ng/server/get/get_sub_product_details.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.subProductDetailsStatus = undefined;
          $scope.subProductDetails = response.data.resultData;
          $scope.sub_product.edit_product_unique_id = $scope.subProductDetails[0].product_unique_id;
          $scope.sub_product_name = $scope.subProductDetails[0].name;
          $scope.sub_product.edit_name = $scope.subProductDetails[0].name;
          $scope.sub_product.edit_size = $scope.subProductDetails[0].size;
          $scope.sub_product.edit_description = $scope.subProductDetails[0].description;
          // $scope.sub_product.price = $scope.subProductDetails[0].price;
          // $scope.sub_product.sales_price = $scope.subProductDetails[0].sales_price;
          // $scope.sub_product.stock = $scope.subProductDetails[0].stock;

          $scope.go_ahead = function () {
            $scope.shouldIShow = true;
          };

          $scope.continue_action = function () {

            $scope.clickOnce = true;

            $scope.genericData = {
              "user_unique_id":$scope.result_u,
              "unique_id":sub_product_unique_id,
              "product_unique_id":$scope.sub_product.edit_product_unique_id,
              "name":$scope.sub_product.edit_name,
              "size":$scope.sub_product.edit_size,
              "description":$scope.sub_product.edit_description
            }

            $http.post('../../ng/server/data/sub_products/update_sub_product_details.php', $scope.genericData)
            .then(function (response) {
              if (response.data.engineMessage == 1) {

                $scope.showSuccessStatus = true;
                $scope.actionStatus = "Changes Saved !";

                notify.do_notify($scope.result_u, "Edit Activity", "Sub Product edited");

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
          $scope.subProductDetailsStatus = response.data.engineErrorMessage;
          // $timeout(function () {
          //   $scope.subProductDetailsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.subProductDetailsStatus = "Error Occured";
          // $timeout(function () {
          //   $scope.subProductDetailsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.subProductDetailsStatus = "Something's Wrong";
        // $timeout(function () {
        //   $scope.subProductDetailsStatus = undefined;
        // }, 3000)
      })

    $scope.removeConfirmModal = function () {

      $scope.shouldIShow = undefined;

    };
  };

  $scope.edit_sub_product_price_details = function (sub_product_unique_id, stripped) {

    $scope.genericData = {
      "sub_product_unique_id":sub_product_unique_id ? sub_product_unique_id : null,
      "stripped":stripped ? stripped : null
    }

    $http.post('../../ng/server/get/get_sub_product_details.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.subProductDetailsStatus = undefined;
          $scope.subProductDetails = response.data.resultData;
          $scope.sub_product.edit_product_unique_id = $scope.subProductDetails[0].product_unique_id;
          $scope.sub_product_name = $scope.subProductDetails[0].name;
          // $scope.sub_product.edit_size = $scope.subProductDetails[0].size;
          // $scope.sub_product.edit_description = $scope.subProductDetails[0].description;
          $scope.sub_product.edit_price = parseInt($scope.subProductDetails[0].price);
          $scope.sub_product.edit_sales_price = parseInt($scope.subProductDetails[0].sales_price);
          // $scope.sub_product.edit_stock = $scope.subProductDetails[0].stock;

          $scope.go_ahead = function () {
            $scope.shouldIShow = true;
          };

          $scope.continue_action = function () {

            $scope.clickOnce = true;

            $scope.genericData = {
              "user_unique_id":$scope.result_u,
              "unique_id":sub_product_unique_id,
              "product_unique_id":$scope.sub_product.edit_product_unique_id,
              "price":$scope.sub_product.edit_price,
              "sales_price":$scope.sub_product.edit_sales_price
            }

            $http.post('../../ng/server/data/sub_products/update_sub_product_prices.php', $scope.genericData)
            .then(function (response) {
              if (response.data.engineMessage == 1) {

                $scope.showSuccessStatus = true;
                $scope.actionStatus = "Changes Saved !";

                notify.do_notify($scope.result_u, "Edit Activity", "Sub Product Price edited");

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
          $scope.subProductDetailsStatus = response.data.engineErrorMessage;
          // $timeout(function () {
          //   $scope.subProductDetailsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.subProductDetailsStatus = "Error Occured";
          // $timeout(function () {
          //   $scope.subProductDetailsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.subProductDetailsStatus = "Something's Wrong";
        // $timeout(function () {
        //   $scope.subProductDetailsStatus = undefined;
        // }, 3000)
      })

    $scope.removeConfirmModal = function () {

      $scope.shouldIShow = undefined;

    };
  };

  $scope.edit_sub_product_stock_details = function (sub_product_unique_id, stripped) {

    $scope.genericData = {
      "sub_product_unique_id":sub_product_unique_id ? sub_product_unique_id : null,
      "stripped":stripped ? stripped : null
    }

    $http.post('../../ng/server/get/get_sub_product_details.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.subProductDetailsStatus = undefined;
          $scope.subProductDetails = response.data.resultData;
          $scope.sub_product.edit_product_unique_id = $scope.subProductDetails[0].product_unique_id;
          // $scope.sub_product.edit_name = $scope.subProductDetails[0].name;
          // $scope.sub_product.edit_size = $scope.subProductDetails[0].size;
          // $scope.sub_product.edit_description = $scope.subProductDetails[0].description;
          // $scope.sub_product.edit_price = $scope.subProductDetails[0].price;
          // $scope.sub_product.edit_sales_price = $scope.subProductDetails[0].sales_price;
          $scope.sub_product.edit_stock = $scope.subProductDetails[0].stock;

          $scope.go_ahead = function () {
            $scope.shouldIShow = true;
          };

          $scope.continue_action = function () {

            $scope.clickOnce = true;

            $scope.genericData = {
              "user_unique_id":$scope.result_u,
              "unique_id":sub_product_unique_id,
              "product_unique_id":$scope.sub_product.edit_product_unique_id,
              "stock":$scope.sub_product.edit_stock
            }

            $http.post('../../ng/server/data/sub_products/update_sub_product_stock.php', $scope.genericData)
            .then(function (response) {
              if (response.data.engineMessage == 1) {

                $scope.showSuccessStatus = true;
                $scope.actionStatus = "Changes Saved !";

                notify.do_notify($scope.result_u, "Edit Activity", "Sub Product Stock edited");

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
          $scope.subProductDetailsStatus = response.data.engineErrorMessage;
          // $timeout(function () {
          //   $scope.subProductDetailsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.subProductDetailsStatus = "Error Occured";
          // $timeout(function () {
          //   $scope.subProductDetailsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.subProductDetailsStatus = "Something's Wrong";
        // $timeout(function () {
        //   $scope.subProductDetailsStatus = undefined;
        // }, 3000)
      })

    $scope.removeConfirmModal = function () {

      $scope.shouldIShow = undefined;

    };
  };

  $scope.delete_sub_product = function (unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "unique_id":unique_id,
      }

      $http.post('../../ng/server/data/sub_products/remove_sub_product.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "Sub Product Removed");

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

  $scope.restore_sub_product = function (unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "unique_id":unique_id,
      }

      $http.post('../../ng/server/data/sub_products/restore_sub_product.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Add Activity", "Sub Product Restored");

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

  $scope.get_sub_product_images = function (sub_product_unique_id, name) {

    $scope.sub_product_name = name;
    $scope.the_sub_product_unique_id = sub_product_unique_id;

    $scope.genericData = {
      "sub_product_unique_id":sub_product_unique_id
    }

    $http.post('../../ng/server/get/get_sub_product_images.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.subProductImages = null;
          $scope.subProductImagesStatus = undefined;
          $scope.subProductImages = response.data.resultData;
          $scope.subProductImagesCount = $scope.subProductImages.length;
        }
        else if (response.data.engineError == 2) {
          $scope.subProductImagesStatus = response.data.engineErrorMessage;
          $scope.subProductImagesCount = 0;
          // $timeout(function () {
          //   $scope.subProductImagesStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.subProductImagesStatus = "Error Occured";
          // $timeout(function () {
          //   $scope.subProductImagesStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.subProductImagesStatus = "Something's Wrong";
        // $timeout(function () {
        //   $scope.subProductImagesStatus = undefined;
        // }, 3000)
      })

  };

  $scope.add_sub_product_image = function (sub_product_unique_id) {
    $location.path("/add-sub-product-image/" + sub_product_unique_id);
  };

  $scope.edit_sub_product_image = function (sub_product_unique_id, sub_product_image_unique_id) {
    $location.path("/edit-sub-product-image/" + sub_product_unique_id + "/" + sub_product_image_unique_id);
  };

  $scope.delete_sub_product_image = function (sub_product_unique_id, sub_product_image_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.shouldIShow = true;

    $scope.unique_id = sub_product_image_unique_id;

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "sub_product_unique_id":sub_product_unique_id,
        "sub_product_image_unique_id":sub_product_image_unique_id
      }

      $http.post('../../ng/server/data/sub_product_images/remove_sub_product_image.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "Sub Product Image Removed");

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
