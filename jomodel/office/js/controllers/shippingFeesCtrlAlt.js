xnyder.controller('shipping-feesCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$routeParams,$window,storage,notify,listLGA){

  $rootScope.pageTitle = "Shipping Fees";

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
    $scope.loadShippingFees();
  };

  $scope.lgas;
  $scope.change_lga = function (obj) {

    $scope.lgaList = listLGA.list();

    angular.forEach($scope.lgaList, function (value, key) {
      if (key == obj) {
        $scope.lgas = value;
      }
    })

  };

  $scope.shipping_fee = {};

  $scope.shipping_fee.add_country = "Nigeria";
  $scope.shipping_fee.add_state = "Rivers";
  $scope.change_lga($scope.shipping_fee.add_state);

  $scope.loadShippingFees = function () {

    $http.get('../../ng/server/get/get_all_shipping_fees.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allShippingFeesStatus = undefined;
          $scope.allShippingFees = response.data.resultData;
          $scope.allShippingFeesCount = $scope.allShippingFees.length;

          $scope.remove_loader();

          $scope.currentPage=1;
          $scope.numLimit=100;
          $scope.start = 0;
          $scope.$watch('allShippingFees',function(newVal){
            if(newVal){
             $scope.pages=Math.ceil($scope.allShippingFees.length/$scope.numLimit);

            }
          });
          $scope.hideNext=function(){
            if(($scope.start+ $scope.numLimit) < $scope.allShippingFees.length){
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
          $scope.allShippingFeesStatus = response.data.engineErrorMessage;
          $scope.allShippingFeesCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allShippingFeesStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allShippingFeesStatus = "Error Occured";
          $scope.allShippingFeesCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allShippingFeesStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allShippingFeesStatus = "Something's Wrong";
        $scope.allShippingFeesCount = 0;
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allShippingFeesStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadShippingFees();

  $scope.loadProducts = function () {

    $http.get('../../ng/server/get/get_all_products_for_shipping_fees.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allProductsStatus = undefined;
          $scope.allProducts = response.data.resultData;
          $scope.allProductsCount = $scope.allProducts.length;

          $scope.currentProductsPage=1;
          $scope.numLimitProducts=20;
          $scope.startProducts = 0;
          $scope.$watch('allProducts',function(newVal){
            if(newVal){
             $scope.pagesProducts=Math.ceil($scope.allProducts.length/$scope.numLimitProducts);

            }
          });
          $scope.hideNextProducts=function(){
            if(($scope.startProducts+ $scope.numLimitProducts) < $scope.allProducts.length){
              return false;
            }
            else
            return true;
          };
          $scope.hidePrevProducts=function(){
            if($scope.startProducts===0){
              return true;
            }
            else
            return false;
          };
          $scope.nextPageProducts=function(){
            $scope.currentProductsPage++;
            $scope.startProducts=$scope.startProducts+ $scope.numLimitProducts;
          };
          $scope.PrevPageProducts=function(){
            if($scope.currentProductsPage>1){
              $scope.currentProductsPage--;
            }
            $scope.startProducts=$scope.startProducts - $scope.numLimitProducts;
          };
        }
        else if (response.data.engineError == 2) {
          $scope.allProductsStatus = response.data.engineErrorMessage;
          $scope.allProductsCount = 0;
          // $timeout(function () {
          //   $scope.allProductsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allProductsStatus = "Error Occured";
          $scope.allProductsCount = 0;
          // $timeout(function () {
          //   $scope.allProductsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allProductsStatus = "Something's Wrong";
        $scope.allProductsCount = 0;
        // $timeout(function () {
        //   $scope.allProductsStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadProducts();

  $scope.selected_sub_products = [];

  $scope.toggleSelection = function toggleSelection(sub_product_unique_id) {
    var idx = $scope.selected_sub_products.indexOf(sub_product_unique_id);

    // Is currently selected
    if (idx > -1) {
      $scope.selected_sub_products.splice(idx, 1);
    }

    // Is newly selected
    else {
      $scope.selected_sub_products.push(sub_product_unique_id);
    }
  };

  $scope.save_shipping_fee = function () {

    if (!$scope.selected_sub_products.length) {
      $scope.show_selected_product_error = true;
    }
    else {
      $scope.show_selected_product_error = undefined;
      $scope.shouldIShow = true;
    }

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.genericData = {
        "user_unique_id":$scope.result_u,
        "sub_product_unique_ids":$scope.selected_sub_products,
        "city":$scope.shipping_fee.add_city,
        "state":$scope.shipping_fee.add_state,
        "country":$scope.shipping_fee.add_country,
        "price":$scope.shipping_fee.add_price
      }

      $http.post('../../ng/server/data/shipping_fees/add_new_selected_shipping_fee.php', $scope.genericData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Shipping Fee added successfully !";

            notify.do_notify($scope.result_u, "Add Activity", "Shipping Fee Added");

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

  $scope.edit_shipping_fee = function (shipping_fee_unique_id, sub_product_unique_id, city, state, country, price, sub_product_name, sub_product_size) {

    $scope.the_sub_product_name = sub_product_name;
    $scope.the_sub_product_size = sub_product_size;

    $scope.shipping_fee.edit_city = city;
    $scope.shipping_fee.edit_state = state;
    $scope.shipping_fee.edit_country = country;
    $scope.shipping_fee.edit_price = parseInt(price);

    $scope.go_ahead = function () {
      $scope.shouldIShow = true;
    };

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.genericData = {
        "user_unique_id":$scope.result_u,
        "shipping_fee_unique_id":shipping_fee_unique_id,
        "sub_product_unique_id":sub_product_unique_id,
        "city":$scope.shipping_fee.edit_city,
        "state":$scope.shipping_fee.edit_state,
        "country":$scope.shipping_fee.edit_country,
        "price":$scope.shipping_fee.edit_price
      }

      $http.post('../../ng/server/data/shipping_fees/update_shipping_fee.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {

          $scope.showSuccessStatus = true;
          $scope.actionStatus = "Changes Saved !";

          notify.do_notify($scope.result_u, "Edit Activity", "Shipping Fee (" + shipping_fee_unique_id + ") edited");

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

  $scope.delete_shipping_fee = function (shipping_fee_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "shipping_fee_unique_id":shipping_fee_unique_id
      }

      $http.post('../../ng/server/data/shipping_fees/remove_shipping_fee.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "Shipping fee (" + shipping_fee_unique_id + ") deleted");

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

  $scope.restore_shipping_fee = function (shipping_fee_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "shipping_fee_unique_id":shipping_fee_unique_id
      }

      $http.post('../../ng/server/data/shipping_fees/restore_shipping_fee.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Add Activity", "Shipping fee (" + shipping_fee_unique_id + ") restored");

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
