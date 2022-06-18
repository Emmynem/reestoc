xnyder.controller('product-sub-categoriesCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$routeParams,$window,storage,notify){

  $rootScope.pageTitle = "Product Sub Categories";

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
    $scope.loadProductSubCategories();
  };

  $scope.loadProductSubCategories = function () {

    $http.get('../../ng/server/get/get_all_sub_categories.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allProductSubCategoriesStatus = undefined;
          $scope.allProductSubCategories = response.data.resultData;
          $scope.allProductSubCategoriesCount = $scope.allProductSubCategories.length;

          $scope.remove_loader();

          $scope.currentPage=1;
          $scope.numLimit= 100;
          $scope.start = 0;
          $scope.$watch('allProductSubCategories',function(newVal){
            if(newVal){
             $scope.pages=Math.ceil($scope.allProductSubCategories.length/$scope.numLimit);

            }
          });
          $scope.hideNext=function(){
            if(($scope.start+ $scope.numLimit) < $scope.allProductSubCategories.length){
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
          $scope.allProductSubCategoriesStatus = response.data.engineErrorMessage;
          $scope.allProductSubCategoriesCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allProductSubCategoriesStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allProductSubCategoriesStatus = "Error Occured";
          $scope.allProductSubCategoriesCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allProductSubCategoriesStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allProductSubCategoriesStatus = "Something's Wrong";
        $scope.allProductSubCategoriesCount = 0;
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allProductSubCategoriesStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadProductSubCategories();

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

  $scope.product_sub_category = {};

  $scope.save_product_sub_category = function () {

    $scope.shouldIShow = true;

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.genericData = {
        "user_unique_id":$scope.result_u,
        "category_unique_id":$scope.product_sub_category.category_unique_id,
        "name":$scope.product_sub_category.name
      }

      $http.post('../../ng/server/data/sub_categories/add_new_sub_category.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {

          $scope.showSuccessStatus = true;
          $scope.actionStatus = "Product Sub Category added successfully !";

          notify.do_notify($scope.result_u, "Add Activity", "Product Sub Category Added");

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

  $scope.edit_product_sub_category = function (sub_category_unique_id, category_unique_id, name) {

    $scope.loadProductCategories();

    $scope.product_sub_category.name = name;
    $scope.product_sub_category.category_unique_id = category_unique_id;

    $scope.go_ahead = function () {
      $scope.shouldIShow = true;
    };

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.genericData = {
        "user_unique_id":$scope.result_u,
        "sub_category_unique_id":sub_category_unique_id,
        "category_unique_id":$scope.product_sub_category.category_unique_id,
        "name":$scope.product_sub_category.name
      }

      $http.post('../../ng/server/data/sub_categories/update_sub_category.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {

          $scope.showSuccessStatus = true;
          $scope.actionStatus = "Changes Saved !";

          notify.do_notify($scope.result_u, "Edit Activity", "Product Sub Category edited");

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

  $scope.reposition_product_sub_category = function (sub_category_unique_id, category_unique_id, name) {

    $scope.loadProductCategories();

    $scope.product_sub_category.category_unique_id = category_unique_id;

    $scope.sub_category_name = name;

    $scope.go_ahead = function () {
      $scope.shouldIShow = true;
    };

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.genericData = {
        "user_unique_id":$scope.result_u,
        "sub_category_unique_id":sub_category_unique_id,
        "category_unique_id":$scope.product_sub_category.category_unique_id
      }

      $http.post('../../ng/server/data/sub_categories/reposition_sub_category.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {

          $scope.showSuccessStatus = true;
          $scope.actionStatus = "Changes Saved !";

          notify.do_notify($scope.result_u, "Edit Activity", "Product Sub Category repositioned");

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

  $scope.delete_product_sub_category = function (sub_category_unique_id, category_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "edit_user_unique_id":$scope.result_u,
        "sub_category_unique_id":sub_category_unique_id,
        "category_unique_id":category_unique_id
      }

      $http.post('../../ng/server/data/sub_categories/remove_sub_category.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "Product Sub Category deleted");

            $timeout(function () {
              $scope.showSuccessStatus = undefined;
              $scope.actionStatus = undefined;
              // $route.reload();
              window.location.reload(true);
            }, 3000)

          }
          else if (response.data.noData == 2) {
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

  $scope.get_sub_category_image = function (sub_category_unique_id, name) {

    $scope.sub_category_name = name;
    $scope.the_sub_category_unique_id = sub_category_unique_id;

    $scope.genericData = {
      "sub_category_unique_id":sub_category_unique_id
    }

    $http.post('../../ng/server/get/get_sub_category_images.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.subCategoryImage = null;
          $scope.subCategoryImageStatus = undefined;
          $scope.subCategoryImage = response.data.resultData[0];
          $scope.subCategoryImageCount = $scope.subCategoryImage.length;
        }
        else if (response.data.engineError == 2) {
          $scope.subCategoryImageStatus = response.data.engineErrorMessage;
          $scope.subCategoryImageCount = 0;
          // $timeout(function () {
          //   $scope.subCategoryImageStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.subCategoryImageStatus = "Error Occured";
          // $timeout(function () {
          //   $scope.subCategoryImageStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.subCategoryImageStatus = "Something's Wrong";
        // $timeout(function () {
        //   $scope.subCategoryImageStatus = undefined;
        // }, 3000)
      })

  };

  $scope.add_sub_category_image = function (sub_category_unique_id) {
    $location.path("/add-sub-category-image/" + sub_category_unique_id);
  };

  $scope.edit_sub_category_image = function (sub_category_unique_id, sub_category_image_unique_id) {
    $location.path("/edit-sub-category-image/" + sub_category_unique_id + "/" + sub_category_image_unique_id);
  };

  $scope.delete_sub_category_image = function (sub_category_unique_id, sub_category_image_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.shouldIShow = true;

    $scope.unique_id = sub_category_image_unique_id;

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "sub_category_unique_id":sub_category_unique_id,
        "sub_category_image_unique_id":sub_category_image_unique_id
      }

      $http.post('../../ng/server/data/sub_category_images/remove_sub_category_image.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "Sub Category Image Removed");

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
