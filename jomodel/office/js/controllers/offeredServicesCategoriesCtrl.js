xnyder.controller('offered-services-categoriesCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$routeParams,$window,storage,notify){

  $rootScope.pageTitle = "Offered Services Categories";

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
    $scope.loadOfferedServicesCategories();
  };

  $scope.loadOfferedServicesCategories = function () {

    $http.get('../../ng/server/get/get_all_offered_services_categories.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allOfferedServicesCategoriesStatus = undefined;
          $scope.allOfferedServicesCategories = response.data.resultData;
          $scope.allOfferedServicesCategoriesCount = $scope.allOfferedServicesCategories.length;

          $scope.remove_loader();

          $scope.currentPage=1;
          $scope.numLimit=100;
          $scope.start = 0;
          $scope.$watch('allOfferedServicesCategories',function(newVal){
            if(newVal){
             $scope.pages=Math.ceil($scope.allOfferedServicesCategories.length/$scope.numLimit);

            }
          });
          $scope.hideNext=function(){
            if(($scope.start+ $scope.numLimit) < $scope.allOfferedServicesCategories.length){
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
          $scope.allOfferedServicesCategoriesStatus = response.data.engineErrorMessage;
          $scope.allOfferedServicesCategoriesCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allOfferedServicesCategoriesStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allOfferedServicesCategoriesStatus = "Error Occured";
          $scope.allOfferedServicesCategoriesCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allOfferedServicesCategoriesStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allOfferedServicesCategoriesStatus = "Something's Wrong";
        $scope.allOfferedServicesCategoriesCount = 0;
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allOfferedServicesCategoriesStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadOfferedServicesCategories();

  $scope.loadSubProducts = function () {

    $http.get('../../ng/server/get/get_all_sub_products_for_select.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allSubProductsStatus = undefined;
          $scope.allSubProducts = response.data.resultData;
          $scope.allSubProductsCount = $scope.allSubProducts.length;
        }
        else if (response.data.engineError == 2) {
          $scope.allSubProductsStatus = response.data.engineErrorMessage;
          $scope.allSubProductsCount = 0;
          // $timeout(function () {
          //   $scope.allSubProductsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allSubProductsStatus = "Error Occured";
          $scope.allSubProductsCount = 0;
          // $timeout(function () {
          //   $scope.allSubProductsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allSubProductsStatus = "Something's Wrong";
        $scope.allSubProductsCount = 0;
        // $timeout(function () {
        //   $scope.allSubProductsStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadSubProducts();

  $scope.offered_service_category = {};

  $scope.save_offered_service_category = function () {

    $scope.shouldIShow = true;

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.genericData = {
        "user_unique_id":$scope.result_u,
        "sub_product_unique_id":$scope.offered_service_category.sub_product_unique_id,
        "service_category":$scope.offered_service_category.service_category
      }

      $http.post('../../ng/server/data/offered_services_category/add_new_service_category.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {

          $scope.showSuccessStatus = true;
          $scope.actionStatus = "Offered Service Category added successfully !";

          notify.do_notify($scope.result_u, "Add Activity", "Offered Service Category Added");

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

  $scope.edit_offered_service_category = function (offered_service_category_unique_id, sub_product_unique_id, service_category) {

    $scope.offered_service_category.edit_service_category = service_category;
    $scope.offered_service_category.edit_sub_product_unique_id = sub_product_unique_id;

    $scope.go_ahead = function () {
      $scope.shouldIShow = true;
    };

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.genericData = {
        "user_unique_id":$scope.result_u,
        "offered_service_category_unique_id":offered_service_category_unique_id,
        "sub_product_unique_id":$scope.offered_service_category.edit_sub_product_unique_id,
        "service_category":$scope.offered_service_category.edit_service_category
      }

      $http.post('../../ng/server/data/offered_services_category/update_service_category.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {

          $scope.showSuccessStatus = true;
          $scope.actionStatus = "Changes Saved !";

          notify.do_notify($scope.result_u, "Edit Activity", "Offered Service Category edited");

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

  $scope.delete_offered_service_category = function (offered_service_category_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "offered_service_category_unique_id":offered_service_category_unique_id
      }

      $http.post('../../ng/server/data/offered_services_category/remove_service_category.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "Offered Service Category deleted");

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

  $scope.restore_offered_service_category = function (offered_service_category_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "offered_service_category_unique_id":offered_service_category_unique_id
      }

      $http.post('../../ng/server/data/offered_services_category/restore_service_category.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Add Activity", "Offered Service Category restored");

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

});
