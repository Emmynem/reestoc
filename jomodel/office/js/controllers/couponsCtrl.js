xnyder.controller('couponsCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$routeParams,$window,storage,notify){

  $rootScope.pageTitle = "Coupons";

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
    $scope.loadCoupons();
  };

  // Gets full date and time of the now, format "2021-09-20 22:00:00"
  $scope.get_today = function () {

    $scope.today = new Date();

    $scope.year = $scope.today.getFullYear();
    $scope.month = $scope.today.getMonth() + 1 < 10 ? "-0" + ($scope.today.getMonth() + 1) : "-" + ($scope.today.getMonth() + 1);
    $scope.date = $scope.today.getDate() < 10 ? "-0" + $scope.today.getDate() : "-" + $scope.today.getDate();

    $scope.hours = $scope.today.getHours() < 10 ? "0" + $scope.today.getHours() : $scope.today.getHours();
    $scope.minutes = $scope.today.getMinutes() < 10 ? "0" + $scope.today.getMinutes() : $scope.today.getMinutes();
    $scope.seconds = $scope.today.getSeconds() < 10 ? "0" + $scope.today.getSeconds() : $scope.today.getSeconds();

    $scope.full_date = $scope.year + $scope.month + $scope.date;
    $scope.full_time = $scope.hours + ":" + $scope.minutes + ":" + $scope.seconds;

    $scope.minimum_date = $scope.full_date + "T" + $scope.hours + ":" + $scope.minutes;

    $scope.full_date_and_time = $scope.full_date + " " + $scope.full_time;

    $scope.todays_date = $scope.full_date_and_time;

  };

  $scope.get_today();

  $scope.filterShow = false;
  $scope.filterExpiryDateShow = false;

  $scope.filter_var = {};
  $scope.filter_expiry_date_var = {};

  $scope.filterByDate = function () {
    $scope.filter_var.startdate = new Date();
    $scope.filter_var.startdate.setHours(0, 0, 0, 0);
    $scope.filter_var.enddate = new Date();
    $scope.filter_var.enddate.setHours(23, 59, 0, 0);
    $scope.filterShow = true;
  };

  $scope.filterByExpiryDate = function () {
    $scope.filter_expiry_date_var.startdate = new Date();
    $scope.filter_expiry_date_var.startdate.setHours(0, 0, 0, 0);
    $scope.filter_expiry_date_var.enddate = new Date();
    $scope.filter_expiry_date_var.enddate.setHours(23, 59, 0, 0);
    $scope.filterExpiryDateShow = true;
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

      $http.post('../../ng/server/get/get_coupons_filter.php', $scope.filter_data)
        .then(function (response) {
          if (response.data.engineMessage == 1) {
            $scope.allCouponsStatus = undefined;
            $scope.allCoupons = response.data.resultData;
            $scope.allCouponsCount = $scope.allCoupons.length;

            $scope.remove_loader();

            $scope.currentPage=1;
            $scope.numLimit=100;
            $scope.start = 0;
            $scope.$watch('allCoupons',function(newVal){
              if(newVal){
               $scope.pages=Math.ceil($scope.allCoupons.length/$scope.numLimit);

              }
            });
            $scope.hideNext=function(){
              if(($scope.start+ $scope.numLimit) < $scope.allCoupons.length){
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
            $scope.allCouponsStatus = "No data in range !";
            $scope.allCouponsCount = 0;
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allCouponsStatus = undefined;
            // }, 3000)
          }
          else {
            $scope.allCouponsStatus = "Couldn't get data";
            $scope.allCouponsCount = 0;
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allCouponsStatus = undefined;
            // }, 3000)
          }
        }, function (error) {
          $scope.allCouponsStatus = "Something's Wrong";
          $scope.allCouponsCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allCouponsStatus = undefined;
          // }, 3000)
        })
    }
  };

  $scope.filter_expiry_date = function () {

    if ($scope.filter_expiry_date_var.startdate > $scope.filter_expiry_date_var.enddate) {
      $scope.filterExpiryDateShow = true;
    }
    else {

      $scope.filterExpiryDateShow = false;

      $scope.show_loader = true;

      $scope.filter_expiry_date_data = {
        "start_date":$scope.filter_expiry_date_var.startdate,
        "end_date":$scope.filter_expiry_date_var.enddate
      }

      $http.post('../../ng/server/get/get_coupons_expiry_date_filter.php', $scope.filter_expiry_date_data)
        .then(function (response) {
          if (response.data.engineMessage == 1) {
            $scope.allCouponsStatus = undefined;
            $scope.allCoupons = response.data.resultData;
            $scope.allCouponsCount = $scope.allCoupons.length;

            $scope.remove_loader();

            $scope.currentPage=1;
            $scope.numLimit=100;
            $scope.start = 0;
            $scope.$watch('allCoupons',function(newVal){
              if(newVal){
               $scope.pages=Math.ceil($scope.allCoupons.length/$scope.numLimit);

              }
            });
            $scope.hideNext=function(){
              if(($scope.start+ $scope.numLimit) < $scope.allCoupons.length){
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
            $scope.allCouponsStatus = "No data in range !";
            $scope.allCouponsCount = 0;
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allCouponsStatus = undefined;
            // }, 3000)
          }
          else {
            $scope.allCouponsStatus = "Couldn't get data";
            $scope.allCouponsCount = 0;
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allCouponsStatus = undefined;
            // }, 3000)
          }
        }, function (error) {
          $scope.allCouponsStatus = "Something's Wrong";
          $scope.allCouponsCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allCouponsStatus = undefined;
          // }, 3000)
        })
    }
  };

  $scope.loadCoupons = function () {

    $http.get('../../ng/server/get/get_all_coupons.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allCouponsStatus = undefined;
          $scope.allCoupons = response.data.resultData;
          $scope.allCouponsCount = $scope.allCoupons.length;

          $scope.remove_loader();

          $scope.currentPage=1;
          $scope.numLimit=100;
          $scope.start = 0;
          $scope.$watch('allCoupons',function(newVal){
            if(newVal){
             $scope.pages=Math.ceil($scope.allCoupons.length/$scope.numLimit);

            }
          });
          $scope.hideNext=function(){
            if(($scope.start+ $scope.numLimit) < $scope.allCoupons.length){
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
          $scope.allCouponsStatus = response.data.engineErrorMessage;
          $scope.allCouponsCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allCouponsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allCouponsStatus = "Error Occured";
          $scope.allCouponsCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allCouponsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allCouponsStatus = "Something's Wrong";
        $scope.allCouponsCount = 0;
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allCouponsStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadCoupons();

  $scope.coupon = {};

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

  $scope.loadProducts = function () {

    $http.get('../../ng/server/get/get_all_products_for_coupons.php')
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

  $scope.loadUsers = function () {

    $http.get('../../ng/server/get/get_all_users_for_coupons.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allUsersStatus = undefined;
          $scope.allUsers = response.data.resultData;
          $scope.allUsersCount = $scope.allUsers.length;

          $scope.currentUsersPage=1;
          $scope.numLimitUsers=20;
          $scope.startUsers = 0;
          $scope.$watch('allUsers',function(newVal){
            if(newVal){
             $scope.pagesUsers=Math.ceil($scope.allUsers.length/$scope.numLimitUsers);

            }
          });
          $scope.hideNextUsers=function(){
            if(($scope.startUsers+ $scope.numLimitUsers) < $scope.allUsers.length){
              return false;
            }
            else
            return true;
          };
          $scope.hidePrevUsers=function(){
            if($scope.startUsers===0){
              return true;
            }
            else
            return false;
          };
          $scope.nextPageUsers=function(){
            $scope.currentUsersPage++;
            $scope.startUsers=$scope.startUsers+ $scope.numLimitUsers;
          };
          $scope.PrevPageUsers=function(){
            if($scope.currentUsersPage>1){
              $scope.currentUsersPage--;
            }
            $scope.startUsers=$scope.startUsers - $scope.numLimitUsers;
          };
        }
        else if (response.data.engineError == 2) {
          $scope.allUsersStatus = response.data.engineErrorMessage;
          $scope.allUsersCount = 0;
          // $timeout(function () {
          //   $scope.allUsersStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allUsersStatus = "Error Occured";
          $scope.allUsersCount = 0;
          // $timeout(function () {
          //   $scope.allUsersStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allUsersStatus = "Something's Wrong";
        $scope.allUsersCount = 0;
        // $timeout(function () {
        //   $scope.allUsersStatus = undefined;
        // }, 3000)
      })

  };

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

  $scope.selected_users = [];

  $scope.toggleUsersSelection = function toggleUsersSelection(user_unique_id) {
    var idx = $scope.selected_users.indexOf(user_unique_id);

    // Is currently selected
    if (idx > -1) {
      $scope.selected_users.splice(idx, 1);
    }

    // Is newly selected
    else {
      $scope.selected_users.push(user_unique_id);
    }
  };

  $scope.change_coupon_type = function (add_type) {

    switch (add_type) {
      case 'Category':
        $scope.loadProductCategories();
        $scope.coupon.coupon_percentage = 1;

        break;
      case 'Event':
        $scope.coupon.coupon_percentage = 1;
        break;
      case 'SubProduct':
        $scope.loadProducts();
        $scope.coupon.coupon_percentage = 1;
        break;
      case 'Users':
        $scope.loadUsers();
        $scope.coupon.coupon_percentage = 1;
        break;
      default:

    }

  };

  $scope.split_expiry_date = function (expiry_date) {
    $scope.today = new Date(expiry_date);

    $scope.year = $scope.today.getFullYear();
    $scope.month = $scope.today.getMonth() + 1 < 10 ? "-0" + ($scope.today.getMonth() + 1) : "-" + ($scope.today.getMonth() + 1);
    $scope.date = $scope.today.getDate() < 10 ? "-0" + $scope.today.getDate() : "-" + $scope.today.getDate();

    $scope.hours = $scope.today.getHours() < 10 ? "0" + $scope.today.getHours() : $scope.today.getHours();
    $scope.minutes = $scope.today.getMinutes() < 10 ? "0" + $scope.today.getMinutes() : $scope.today.getMinutes();
    $scope.seconds = $scope.today.getSeconds() < 10 ? "0" + $scope.today.getSeconds() : $scope.today.getSeconds();

    $scope.full_date = $scope.year + $scope.month + $scope.date;
    $scope.full_time = $scope.hours + ":" + $scope.minutes + ":" + $scope.seconds;

    $scope.full_date_and_time = $scope.full_date + " " + $scope.full_time;

    $scope.coupon.coupon_expiry_date_alt = $scope.full_date_and_time;

  };

  $scope.save_category_coupon = function () {

    $scope.shouldIShow = true;

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.genericData = {
        "management_user_unique_id":$scope.result_u,
        "category_unique_id":$scope.coupon.category_unique_id,
        "sub_category_unique_id":$scope.coupon.sub_category_unique_id,
        "mini_category_unique_id":$scope.coupon.mini_category_unique_id,
        "code":$scope.coupon.coupon_code,
        "name":$scope.coupon.coupon_name,
        "percentage":$scope.coupon.coupon_percentage,
        "total_coupons":$scope.coupon.coupon_total_count,
        "expiry_date":$scope.coupon.coupon_expiry_date_alt
      }

      $http.post('../../ng/server/data/coupons/add_new_category_coupon.php', $scope.genericData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessCategoryStatus = true;
            $scope.actionCategoryStatus = "Category Coupon added successfully !";

            notify.do_notify($scope.result_u, "Add Activity", "Category Coupon (" + $scope.coupon.coupon_code.toUpperCase() + ") Added");

            $timeout(function () {
              $scope.showSuccessCategoryStatus = undefined;
              $scope.actionCategoryStatus = undefined;
              window.location.reload(true);
            }, 3000)

          }
          else if (response.data.engineError == 2){
            $scope.showCategoryStatus = true;
            $scope.actionCategoryStatus = response.data.engineErrorMessage;
            $timeout(function () {
              $scope.showCategoryStatus = undefined;
              $scope.actionCategoryStatus = undefined;
              $scope.clickOnce = false;
            }, 3000)
          }
          else {
            $scope.showCategoryStatus = true;
            $scope.actionCategoryStatus = "Error Occured";
            $timeout(function () {
              $scope.showCategoryStatus = undefined;
              $scope.actionCategoryStatus = undefined;
              $scope.clickOnce = false;
            }, 3000)
          }
        }, function (error) {
          $scope.showCategoryStatus = true;
          $scope.actionCategoryStatus = "Something's Wrong";
          $timeout(function () {
            $scope.showCategoryStatus = undefined;
            $scope.actionCategoryStatus = undefined;
            $scope.clickOnce = false;
          }, 3000)
        })

    };

    $scope.removeConfirmModal = function () {

      $scope.shouldIShow = undefined;

    };

  };

  $scope.save_event_coupon = function () {

    $scope.shouldIShow = true;

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.genericData = {
        "management_user_unique_id":$scope.result_u,
        "code":$scope.coupon.coupon_code,
        "name":$scope.coupon.coupon_name,
        "percentage":$scope.coupon.coupon_percentage,
        "expiry_date":$scope.coupon.coupon_expiry_date_alt
      }

      $http.post('../../ng/server/data/coupons/add_new_event_coupon.php', $scope.genericData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessEventStatus = true;
            $scope.actionEventStatus = "Event Coupon added successfully !";

            notify.do_notify($scope.result_u, "Add Activity", "Event Coupon (" + $scope.coupon.coupon_code.toUpperCase() + ") Added");

            $timeout(function () {
              $scope.showSuccessEventStatus = undefined;
              $scope.actionEventStatus = undefined;
              window.location.reload(true);
            }, 3000)

          }
          else if (response.data.engineError == 2){
            $scope.showEventStatus = true;
            $scope.actionEventStatus = response.data.engineErrorMessage;
            $timeout(function () {
              $scope.showEventStatus = undefined;
              $scope.actionEventStatus = undefined;
              $scope.clickOnce = false;
            }, 3000)
          }
          else {
            $scope.showEventStatus = true;
            $scope.actionEventStatus = "Error Occured";
            $timeout(function () {
              $scope.showEventStatus = undefined;
              $scope.actionEventStatus = undefined;
              $scope.clickOnce = false;
            }, 3000)
          }
        }, function (error) {
          $scope.showEventStatus = true;
          $scope.actionEventStatus = "Something's Wrong";
          $timeout(function () {
            $scope.showEventStatus = undefined;
            $scope.actionEventStatus = undefined;
            $scope.clickOnce = false;
          }, 3000)
        })

    };

    $scope.removeConfirmModal = function () {

      $scope.shouldIShow = undefined;

    };

  };

  $scope.save_sub_products_coupon = function () {

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
        "management_user_unique_id":$scope.result_u,
        "sub_product_unique_ids":$scope.selected_sub_products,
        "code":$scope.coupon.coupon_code,
        "name":$scope.coupon.coupon_name,
        "percentage":$scope.coupon.coupon_percentage,
        "total_coupons":$scope.coupon.coupon_total_count,
        "expiry_date":$scope.coupon.coupon_expiry_date_alt
      }

      $http.post('../../ng/server/data/coupons/add_new_sub_products_coupon.php', $scope.genericData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessSubProductStatus = true;
            $scope.actionSubProductStatus = "Sub Product(s) Coupon added successfully !";

            notify.do_notify($scope.result_u, "Add Activity", "Sub Product(s) Coupon (" + $scope.coupon.coupon_code.toUpperCase() + ") Added");

            $timeout(function () {
              $scope.showSuccessSubProductStatus = undefined;
              $scope.actionSubProductStatus = undefined;
              window.location.reload(true);
            }, 3000)

          }
          else if (response.data.engineError == 2){
            $scope.showSubProductStatus = true;
            $scope.actionSubProductStatus = response.data.engineErrorMessage;
            $timeout(function () {
              $scope.showSubProductStatus = undefined;
              $scope.actionSubProductStatus = undefined;
              $scope.clickOnce = false;
            }, 3000)
          }
          else {
            $scope.showSubProductStatus = true;
            $scope.actionSubProductStatus = "Error Occured";
            $timeout(function () {
              $scope.showSubProductStatus = undefined;
              $scope.actionSubProductStatus = undefined;
              $scope.clickOnce = false;
            }, 3000)
          }
        }, function (error) {
          $scope.showSubProductStatus = true;
          $scope.actionSubProductStatus = "Something's Wrong";
          $timeout(function () {
            $scope.showSubProductStatus = undefined;
            $scope.actionSubProductStatus = undefined;
            $scope.clickOnce = false;
          }, 3000)
        })

    };

    $scope.removeConfirmModal = function () {

      $scope.shouldIShow = undefined;

    };

  };

  $scope.save_users_coupon = function () {

    if (!$scope.selected_users.length) {
      $scope.show_selected_user_error = true;
    }
    else {
      $scope.show_selected_user_error = undefined;
      $scope.shouldIShow = true;
    }

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.genericData = {
        "management_user_unique_id":$scope.result_u,
        "user_unique_ids":$scope.selected_users,
        "code":$scope.coupon.coupon_code,
        "name":$scope.coupon.coupon_name,
        "percentage":$scope.coupon.coupon_percentage,
        "total_coupons":$scope.coupon.coupon_total_count,
        "expiry_date":$scope.coupon.coupon_expiry_date_alt
      }

      $http.post('../../ng/server/data/coupons/add_new_users_coupon.php', $scope.genericData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessUsersStatus = true;
            $scope.actionUsersStatus = "Users(s) Personalized Coupon added successfully !";

            notify.do_notify($scope.result_u, "Add Activity", "User(s) Personalized Coupon (" + $scope.coupon.coupon_code.toUpperCase() + ") Added");

            $timeout(function () {
              $scope.showSuccessUsersStatus = undefined;
              $scope.actionUsersStatus = undefined;
              window.location.reload(true);
            }, 3000)

          }
          else if (response.data.engineError == 2){
            $scope.showUsersStatus = true;
            $scope.actionUsersStatus = response.data.engineErrorMessage;
            $timeout(function () {
              $scope.showUsersStatus = undefined;
              $scope.actionUsersStatus = undefined;
              $scope.clickOnce = false;
            }, 3000)
          }
          else {
            $scope.showUsersStatus = true;
            $scope.actionUsersStatus = "Error Occured";
            $timeout(function () {
              $scope.showUsersStatus = undefined;
              $scope.actionUsersStatus = undefined;
              $scope.clickOnce = false;
            }, 3000)
          }
        }, function (error) {
          $scope.showUsersStatus = true;
          $scope.actionUsersStatus = "Something's Wrong";
          $timeout(function () {
            $scope.showUsersStatus = undefined;
            $scope.actionUsersStatus = undefined;
            $scope.clickOnce = false;
          }, 3000)
        })

    };

    $scope.removeConfirmModal = function () {

      $scope.shouldIShow = undefined;

    };

  };

  $scope.delete_coupon = function (coupon_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "coupon_unique_id":coupon_unique_id,
      }

      $http.post('../../ng/server/data/coupons/remove_coupon.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "Coupon Removed");

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

  $scope.delete_code_coupon = function (code) {

    // modalOpen('deleteModal', 'medium');

    $scope.the_code = code;

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "code":code,
      }

      $http.post('../../ng/server/data/coupons/remove_multiple_coupon.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "All (" + code + ") Coupons Removed");

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

});
