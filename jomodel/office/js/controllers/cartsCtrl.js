xnyder.controller('cartsCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$routeParams,$window,storage,notify){

  $rootScope.pageTitle = "Carts";

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
    $scope.loadCarts();
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
        "start_date":$scope.filter_var.startdate,
        "end_date":$scope.filter_var.enddate
      }

      $http.post('../../ng/server/get/get_cart_filter.php', $scope.filter_data)
        .then(function (response) {
          if (response.data.engineMessage == 1) {
            $scope.allCartsStatus = undefined;
            $scope.allCarts = response.data.resultData;
            $scope.allCartsCount = $scope.allCarts.length;

            $scope.remove_loader();

            $scope.currentPage=1;
            $scope.numLimit=100;
            $scope.start = 0;
            $scope.$watch('allCarts',function(newVal){
              if(newVal){
               $scope.pages=Math.ceil($scope.allCarts.length/$scope.numLimit);

              }
            });
            $scope.hideNext=function(){
              if(($scope.start+ $scope.numLimit) < $scope.allCarts.length){
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
            $scope.allCartsStatus = "No data in range !";
            $scope.allCartsCount = 0;
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allCartsStatus = undefined;
            // }, 3000)
          }
          else {
            $scope.allCartsStatus = "Couldn't get data";
            $scope.allCartsCount = 0;
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allCartsStatus = undefined;
            // }, 3000)
          }
        }, function (error) {
          $scope.allCartsStatus = "Something's Wrong";
          $scope.allCartsCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allCartsStatus = undefined;
          // }, 3000)
        })
    }
  };

  $scope.loadCarts = function () {

    $http.get('../../ng/server/get/get_all_cart.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allCartsStatus = undefined;
          $scope.allCarts = response.data.resultData;
          $scope.allCartsCount = $scope.allCarts.length;

          $scope.remove_loader();

          $scope.currentPage=1;
          $scope.numLimit=100;
          $scope.start = 0;
          $scope.$watch('allCarts',function(newVal){
            if(newVal){
             $scope.pages=Math.ceil($scope.allCarts.length/$scope.numLimit);

            }
          });
          $scope.hideNext=function(){
            if(($scope.start+ $scope.numLimit) < $scope.allCarts.length){
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
          $scope.allCartsStatus = response.data.engineErrorMessage;
          $scope.allCartsCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allCartsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allCartsStatus = "Error Occured";
          $scope.allCartsCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allCartsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allCartsStatus = "Something's Wrong";
        $scope.allCartsCount = 0;
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allCartsStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadCarts();

  $scope.view_cart = function (user_unique_id, cart_unique_id) {

    $scope.genericData = {
      "user_unique_id":user_unique_id,
      "cart_unique_id":cart_unique_id
    }

    $http.post('../../ng/server/get/get_user_cart_details.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.cartDetails = null;
          $scope.cartDetailsStatus = undefined;
          $scope.cartDetails = response.data.resultData[0];
        }
        else if (response.data.engineError == 2) {
          $scope.cartDetailsStatus = response.data.engineErrorMessage;
          $scope.cartDetailsCount = 0;
          // $timeout(function () {
          //   $scope.cartDetailsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.cartDetailsStatus = "Error Occured";
          // $timeout(function () {
          //   $scope.cartDetailsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.cartDetailsStatus = "Something's Wrong";
        // $timeout(function () {
        //   $scope.cartDetailsStatus = undefined;
        // }, 3000)
      })

  };

  $scope.get_brand_image = function (brand_unique_id, name) {

    $scope.brand_name = name;
    $scope.the_brand_unique_id = brand_unique_id;

    $scope.genericData = {
      "brand_unique_id":brand_unique_id
    }

    $http.post('../../ng/server/get/get_brand_images.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.brandImage = null;
          $scope.brandImageStatus = undefined;
          $scope.brandImage = response.data.resultData[0];
          $scope.brandImageCount = $scope.brandImage.length;
        }
        else if (response.data.engineError == 2) {
          $scope.brandImageStatus = response.data.engineErrorMessage;
          $scope.brandImageCount = 0;
          // $timeout(function () {
          //   $scope.brandImageStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.brandImageStatus = "Error Occured";
          // $timeout(function () {
          //   $scope.brandImageStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.brandImageStatus = "Something's Wrong";
        // $timeout(function () {
        //   $scope.brandImageStatus = undefined;
        // }, 3000)
      })

  };

  $scope.delete_brand = function (brand_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "brand_unique_id":brand_unique_id
      }

      $http.post('../../ng/server/data/brands/remove_brand.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "Brand deleted");

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

  $scope.restore_brand = function (brand_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "brand_unique_id":brand_unique_id
      }

      $http.post('../../ng/server/data/brands/restore_brand.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "Brand restored");

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

  $scope.add_brand_image = function (brand_unique_id) {
    $location.path("/add-brand-image/" + brand_unique_id);
  };

  $scope.edit_brand_image = function (brand_unique_id, brand_image_unique_id) {
    $location.path("/edit-brand-image/" + brand_unique_id + "/" + brand_image_unique_id);
  };

  $scope.delete_brand_image = function (brand_unique_id, brand_image_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.shouldIShow = true;

    $scope.unique_id = brand_image_unique_id;

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "brand_unique_id":brand_unique_id,
        "brand_image_unique_id":brand_image_unique_id
      }

      $http.post('../../ng/server/data/brand_images/remove_brand_image.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "Brand Image Removed");

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
