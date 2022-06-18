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

  $scope.refreshSharingPage = function () {
  $scope.show_loader = true;
    $scope.loadSharingShippingFees();
  };

  $scope.refreshPickupLocationPage = function () {
  $scope.show_loader = true;
    $scope.loadPickupLocationShippingFees();
  };

  $scope.refreshPickupLocationSharingPage = function () {
  $scope.show_loader = true;
    $scope.loadPickupLocationSharingShippingFees();
  };

  $scope.lgas;

  $scope.show_sharing_locations = function () {

    $scope.loadSharingItems();

    if ($scope.sharing_shipping_fee.add_country == "Nigeria") {
      $scope.allLocations = Object.entries(listLGA.list());

      if ($scope.allLocations != undefined || $scope.allLocations != null) {

        $scope.allLocationsStatus = undefined;
        $scope.currentLocationsPage=1;
        $scope.numLimitLocations=10;
        $scope.startLocations = 0;
        $scope.$watch('allLocations',function(newVal){
          if(newVal){
           $scope.pagesLocations=Math.ceil($scope.allLocations.length/$scope.numLimitLocations);
          }
        });
        $scope.hideNextLocations=function(){
          if(($scope.startLocations+ $scope.numLimitLocations) < $scope.allLocations.length){
            return false;
          }
          else
          return true;
        };
        $scope.hidePrevLocations=function(){
          if($scope.startLocations===0){
            return true;
          }
          else
          return false;
        };
        $scope.nextPageLocations=function(){
          $scope.currentLocationsPage++;
          $scope.startLocations=$scope.startLocations+ $scope.numLimitLocations;
        };
        $scope.PrevPageLocations=function(){
          if($scope.currentLocationsPage>1){
            $scope.currentLocationsPage--;
          }
          $scope.startLocations=$scope.startLocations - $scope.numLimitLocations;
        };
      }
      else {
        $scope.allLocationsStatus = "No location found";
      }
    }
    else {
      $scope.allLocationsStatus = "No location found";
    }

  };

  $scope.change_lga = function (obj) {

    $scope.lgaList = listLGA.list();

    angular.forEach($scope.lgaList, function (value, key) {
      if (key == obj) {
        $scope.lgas = value;
      }
    })

  };

  $scope.shipping_fee = {};
  $scope.pickup_location_shipping_fee = {};
  $scope.sharing_shipping_fee = {};
  $scope.pickup_location_sharing_shipping_fee = {};

  $scope.shipping_fee.add_country = "Nigeria";
  $scope.shipping_fee.add_state = "Rivers";

  $scope.sharing_shipping_fee.add_country = "Nigeria";
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

  $scope.loadSharingShippingFees = function () {

    $http.get('../../ng/server/get/get_all_sharing_shipping_fees.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allSharingShippingFeesStatus = undefined;
          $scope.allSharingShippingFees = response.data.resultData;
          $scope.allSharingShippingFeesCount = $scope.allSharingShippingFees.length;

          $scope.remove_loader();

          $scope.currentPageSharing=1;
          $scope.numLimitSharing=100;
          $scope.startSharing = 0;
          $scope.$watch('allSharingShippingFees',function(newVal){
            if(newVal){
             $scope.pagesSharing=Math.ceil($scope.allSharingShippingFees.length/$scope.numLimitSharing);

            }
          });
          $scope.hideNextSharing=function(){
            if(($scope.startSharing+ $scope.numLimitSharing) < $scope.allSharingShippingFees.length){
              return false;
            }
            else
            return true;
          };
          $scope.hidePrevSharing=function(){
            if($scope.startSharing===0){
              return true;
            }
            else
            return false;
          };
          $scope.nextPageSharing=function(){
            $scope.currentPageSharing++;
            $scope.startSharing=$scope.startSharing+ $scope.numLimitSharing;
          };
          $scope.PrevPageSharing=function(){
            if($scope.currentPageSharing>1){
              $scope.currentPageSharing--;
            }
            $scope.startSharing=$scope.startSharing - $scope.numLimitSharing;
          };
        }
        else if (response.data.engineError == 2) {
          $scope.allSharingShippingFeesStatus = response.data.engineErrorMessage;
          $scope.allSharingShippingFeesCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allSharingShippingFeesStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allSharingShippingFeesStatus = "Error Occured";
          $scope.allSharingShippingFeesCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allSharingShippingFeesStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allSharingShippingFeesStatus = "Something's Wrong";
        $scope.allSharingShippingFeesCount = 0;
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allSharingShippingFeesStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadSharingShippingFees();

  $scope.loadPickupLocationShippingFees = function () {

    $http.get('../../ng/server/get/get_all_pickup_location_shipping_fees.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allPickupLocationShippingFeesStatus = undefined;
          $scope.allPickupLocationShippingFees = response.data.resultData;
          $scope.allPickupLocationShippingFeesCount = $scope.allPickupLocationShippingFees.length;

          $scope.remove_loader();

          $scope.currentPagePickupLocation=1;
          $scope.numLimitPickupLocation=100;
          $scope.startPickupLocation = 0;
          $scope.$watch('allPickupLocationShippingFees',function(newVal){
            if(newVal){
             $scope.pagesPickupLocation=Math.ceil($scope.allPickupLocationShippingFees.length/$scope.numLimitPickupLocation);

            }
          });
          $scope.hideNextPickupLocation=function(){
            if(($scope.startPickupLocation+ $scope.numLimitPickupLocation) < $scope.allPickupLocationShippingFees.length){
              return false;
            }
            else
            return true;
          };
          $scope.hidePrevPickupLocation=function(){
            if($scope.startPickupLocation===0){
              return true;
            }
            else
            return false;
          };
          $scope.nextPagePickupLocation=function(){
            $scope.currentPagePickupLocation++;
            $scope.startPickupLocation=$scope.startPickupLocation+ $scope.numLimitPickupLocation;
          };
          $scope.PrevPagePickupLocation=function(){
            if($scope.currentPagePickupLocation>1){
              $scope.currentPagePickupLocation--;
            }
            $scope.startPickupLocation=$scope.startPickupLocation - $scope.numLimitPickupLocation;
          };
        }
        else if (response.data.engineError == 2) {
          $scope.allPickupLocationShippingFeesStatus = response.data.engineErrorMessage;
          $scope.allPickupLocationShippingFeesCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allPickupLocationShippingFeesStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allPickupLocationShippingFeesStatus = "Error Occured";
          $scope.allPickupLocationShippingFeesCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allPickupLocationShippingFeesStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allPickupLocationShippingFeesStatus = "Something's Wrong";
        $scope.allPickupLocationShippingFeesCount = 0;
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allPickupLocationShippingFeesStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadPickupLocationShippingFees();

  $scope.loadPickupLocationSharingShippingFees = function () {

    $http.get('../../ng/server/get/get_all_pickup_location_sharing_shipping_fees.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allPickupLocationSharingShippingFeesStatus = undefined;
          $scope.allPickupLocationSharingShippingFees = response.data.resultData;
          $scope.allPickupLocationSharingShippingFeesCount = $scope.allPickupLocationSharingShippingFees.length;

          $scope.remove_loader();

          $scope.currentPagePickupLocationSharing=1;
          $scope.numLimitPickupLocationSharing=100;
          $scope.startPickupLocationSharing = 0;
          $scope.$watch('allPickupLocationSharingShippingFees',function(newVal){
            if(newVal){
             $scope.pagesPickupLocationSharing=Math.ceil($scope.allPickupLocationSharingShippingFees.length/$scope.numLimitPickupLocationSharing);

            }
          });
          $scope.hideNextPickupLocationSharing=function(){
            if(($scope.startPickupLocationSharing+ $scope.numLimitPickupLocationSharing) < $scope.allPickupLocationSharingShippingFees.length){
              return false;
            }
            else
            return true;
          };
          $scope.hidePrevPickupLocationSharing=function(){
            if($scope.startPickupLocationSharing===0){
              return true;
            }
            else
            return false;
          };
          $scope.nextPagePickupLocationSharing=function(){
            $scope.currentPagePickupLocationSharing++;
            $scope.startPickupLocationSharing=$scope.startPickupLocationSharing+ $scope.numLimitPickupLocationSharing;
          };
          $scope.PrevPagePickupLocationSharing=function(){
            if($scope.currentPagePickupLocationSharing>1){
              $scope.currentPagePickupLocationSharing--;
            }
            $scope.startPickupLocationSharing=$scope.startPickupLocationSharing - $scope.numLimitPickupLocationSharing;
          };
        }
        else if (response.data.engineError == 2) {
          $scope.allPickupLocationSharingShippingFeesStatus = response.data.engineErrorMessage;
          $scope.allPickupLocationSharingShippingFeesCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allPickupLocationSharingShippingFeesStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allPickupLocationSharingShippingFeesStatus = "Error Occured";
          $scope.allPickupLocationSharingShippingFeesCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allPickupLocationSharingShippingFeesStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allPickupLocationSharingShippingFeesStatus = "Something's Wrong";
        $scope.allPickupLocationSharingShippingFeesCount = 0;
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allPickupLocationSharingShippingFeesStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadPickupLocationSharingShippingFees();

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

  $scope.loadDefaultLocations = function () {

    $http.get('../../ng/server/get/get_all_default_pickup_locations_for_shipping_fees.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allDefaultLocationsStatus = undefined;
          $scope.allDefaultLocations = response.data.resultData;
          $scope.allDefaultLocationsCount = $scope.allDefaultLocations.length;

          $scope.currentDefaultLocationsPage=1;
          $scope.numLimitDefaultLocations=5;
          $scope.startDefaultLocations = 0;
          $scope.$watch('allDefaultLocations',function(newVal){
            if(newVal){
             $scope.pagesDefaultLocations=Math.ceil($scope.allDefaultLocations.length/$scope.numLimitDefaultLocations);

            }
          });
          $scope.hideNextDefaultLocations=function(){
            if(($scope.startDefaultLocations+ $scope.numLimitDefaultLocations) < $scope.allDefaultLocations.length){
              return false;
            }
            else
            return true;
          };
          $scope.hidePrevDefaultLocations=function(){
            if($scope.startDefaultLocations===0){
              return true;
            }
            else
            return false;
          };
          $scope.nextPageDefaultLocations=function(){
            $scope.currentDefaultLocationsPage++;
            $scope.startDefaultLocations=$scope.startDefaultLocations+ $scope.numLimitDefaultLocations;
          };
          $scope.PrevPageDefaultLocations=function(){
            if($scope.currentDefaultLocationsPage>1){
              $scope.currentDefaultLocationsPage--;
            }
            $scope.startDefaultLocations=$scope.startDefaultLocations - $scope.numLimitDefaultLocations;
          };
        }
        else if (response.data.engineError == 2) {
          $scope.allDefaultLocationsStatus = response.data.engineErrorMessage;
          $scope.allDefaultLocationsCount = 0;
          // $timeout(function () {
          //   $scope.allDefaultLocationsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allDefaultLocationsStatus = "Error Occured";
          $scope.allDefaultLocationsCount = 0;
          // $timeout(function () {
          //   $scope.allDefaultLocationsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allDefaultLocationsStatus = "Something's Wrong";
        $scope.allDefaultLocationsCount = 0;
        // $timeout(function () {
        //   $scope.allDefaultLocationsStatus = undefined;
        // }, 3000)
      })

  };

  // $scope.loadDefaultLocations();

  $scope.loadSharingItems = function () {

    $scope.allSharingItems = null;

    $http.get('../../ng/server/get/get_all_sharing_items_for_shipping_fees.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allSharingItemsStatus = undefined;
          $scope.allSharingItems = response.data.resultData;
          $scope.allSharingItemsCount = $scope.allSharingItems.length;

        }
        else if (response.data.engineError == 2) {
          $scope.allSharingItemsStatus = response.data.engineErrorMessage;
          $scope.allSharingItemsCount = 0;
          // $timeout(function () {
          //   $scope.allSharingItemsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allSharingItemsStatus = "Error Occured";
          $scope.allSharingItemsCount = 0;
          // $timeout(function () {
          //   $scope.allSharingItemsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allSharingItemsStatus = "Something's Wrong";
        $scope.allSharingItemsCount = 0;
        // $timeout(function () {
        //   $scope.allSharingItemsStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadSharingItems();

  $scope.loadPickupLocationsSharingItems = function () {

    $scope.loadDefaultLocations();

    $scope.allPickupLocationsSharingItems = null;

    $http.get('../../ng/server/get/get_all_sharing_items_for_shipping_fees.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allPickupLocationsSharingItemsStatus = undefined;
          $scope.allPickupLocationsSharingItems = response.data.resultData;
          $scope.allPickupLocationsSharingItemsCount = $scope.allPickupLocationsSharingItems.length;

          $scope.currentPickupLocationsSharingItemsPage=1;
          $scope.numLimitPickupLocationsSharingItems=5;
          $scope.startPickupLocationsSharingItems = 0;
          $scope.$watch('allPickupLocationsSharingItems',function(newVal){
            if(newVal){
             $scope.pagesPickupLocationsSharingItems=Math.ceil($scope.allPickupLocationsSharingItems.length/$scope.numLimitPickupLocationsSharingItems);

            }
          });
          $scope.hideNextPickupLocationsSharingItems=function(){
            if(($scope.startPickupLocationsSharingItems+ $scope.numLimitPickupLocationsSharingItems) < $scope.allPickupLocationsSharingItems.length){
              return false;
            }
            else
            return true;
          };
          $scope.hidePrevPickupLocationsSharingItems=function(){
            if($scope.startPickupLocationsSharingItems===0){
              return true;
            }
            else
            return false;
          };
          $scope.nextPagePickupLocationsSharingItems=function(){
            $scope.currentPickupLocationsSharingItemsPage++;
            $scope.startPickupLocationsSharingItems=$scope.startPickupLocationsSharingItems+ $scope.numLimitPickupLocationsSharingItems;
          };
          $scope.PrevPagePickupLocationsSharingItems=function(){
            if($scope.currentPickupLocationsSharingItemsPage>1){
              $scope.currentPickupLocationsSharingItemsPage--;
            }
            $scope.startPickupLocationsSharingItems=$scope.startPickupLocationsSharingItems - $scope.numLimitPickupLocationsSharingItems;
          };
        }
        else if (response.data.engineError == 2) {
          $scope.allPickupLocationsSharingItemsStatus = response.data.engineErrorMessage;
          $scope.allPickupLocationsSharingItemsCount = 0;
          // $timeout(function () {
          //   $scope.allPickupLocationsSharingItemsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allPickupLocationsSharingItemsStatus = "Error Occured";
          $scope.allPickupLocationsSharingItemsCount = 0;
          // $timeout(function () {
          //   $scope.allPickupLocationsSharingItemsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allPickupLocationsSharingItemsStatus = "Something's Wrong";
        $scope.allPickupLocationsSharingItemsCount = 0;
        // $timeout(function () {
        //   $scope.allPickupLocationsSharingItemsStatus = undefined;
        // }, 3000)
      })

  };

  $scope.selected_sub_products = [];

  $scope.selected_sub_products_pickup_locations = [];

  $scope.selected_sharing_locations = [];

  $scope.selected_sharing_states = [];

  $scope.selected_sharing_items = [];

  $scope.selected_sharing_pickup_locations = [];

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

  $scope.toggleSubProductPickupLocationsSelection = function toggleSubProductPickupLocationsSelection(default_pickup_location_unique_id) {
    var idx = $scope.selected_sub_products_pickup_locations.indexOf(default_pickup_location_unique_id);

    // Is currently selected
    if (idx > -1) {
      $scope.selected_sub_products_pickup_locations.splice(idx, 1);
    }

    // Is newly selected
    else {
      $scope.selected_sub_products_pickup_locations.push(default_pickup_location_unique_id);
    }
  };

  $scope.toggleSharingSelection = function toggleSharingSelection(state, city) {
    // function to get the index of a value
    function getIndexOfLocations(arr, k) {
      for (var i = 0; i < arr.length; i++) {
        var index = arr[i].indexOf(k);
        if (index > -1) {
          return [i, index];
        }
      }
    }

    var idx = getIndexOfLocations($scope.selected_sharing_locations, city);
    // Is currently selected
    if (idx != undefined) {
      $scope.selected_sharing_locations.splice(idx[0], idx[1]);
    }
    // Is newly selected
    else {
      $scope.selected_sharing_locations.push([state, city]);
    }
  };

  $scope.toggleSharingStateSelection = function toggleSharingStateSelection(cities, state) {
    // function to get the index of a value
    function getIndexOfLocations(arr, k) {
      for (var i = 0; i < arr.length; i++) {
        var index = arr[i].indexOf(k);
        if (index > -1) {
          return [i, index];
        }
      }
    }

    var idx = $scope.selected_sharing_states.indexOf(state);

    // Is currently selected
    if (idx > -1) {
      $scope.selected_sharing_states.splice(idx, 1);
      angular.forEach(cities, function (valuex, key) {
        var id_name = state + valuex;
        document.getElementById(id_name).checked = false;

        var idx_2 = getIndexOfLocations($scope.selected_sharing_locations, valuex);

        // Is currently selected
        if (idx_2 != undefined) {
          $scope.selected_sharing_locations.splice(idx_2[0], idx_2[1]);
        }
        // Is newly selected
        else {
          if (idx_2 != undefined) {
            $scope.selected_sharing_locations.splice(idx_2[0], idx_2[1]);
          }
          else {

          }

        }

      })
    }

    // Is newly selected
    else {
      $scope.selected_sharing_states.push(state);
      angular.forEach(cities, function (valuex, key) {
        var id_name = state + valuex;
        document.getElementById(id_name).checked = true;

        var idx_2 = getIndexOfLocations($scope.selected_sharing_locations, valuex);

        // Is currently selected
        if (idx_2 != undefined) {
          // $scope.selected_sharing_locations.splice(idx_2[0], idx_2[1]);
        }
        // Is newly selected
        else {
          $scope.selected_sharing_locations.push([state, valuex]);
        }
      })
    }
  };

  $scope.toggleSharingItemsSelection = function toggleSharingItemsSelection(sharing_unique_id) {
    var idx = $scope.selected_sharing_items.indexOf(sharing_unique_id);

    // Is currently selected
    if (idx > -1) {
      $scope.selected_sharing_items.splice(idx, 1);
    }

    // Is newly selected
    else {
      $scope.selected_sharing_items.push(sharing_unique_id);
    }
  };

  $scope.toggleSharingPickupLocationsSelection = function toggleSharingPickupLocationsSelection(default_pickup_location_unique_id) {
    var idx = $scope.selected_sharing_pickup_locations.indexOf(default_pickup_location_unique_id);

    // Is currently selected
    if (idx > -1) {
      $scope.selected_sharing_pickup_locations.splice(idx, 1);
    }

    // Is newly selected
    else {
      $scope.selected_sharing_pickup_locations.push(default_pickup_location_unique_id);
    }
  };

  // ------------------- Shipping fee for sub products

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

  // -------------------- End here

  // ------------------- Shipping fee for sharing items

  $scope.save_sharing_shipping_fee = function () {

    if (!$scope.selected_sharing_locations.length) {
      $scope.show_selected_location_error = true;
    }
    else {
      $scope.show_selected_location_error = undefined;
      $scope.shouldIShow = true;
    }

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.genericData = {
        "user_unique_id":$scope.result_u,
        "sharing_unique_id":$scope.sharing_shipping_fee.add_sharing_unique_id,
        "sharing_locations":$scope.selected_sharing_locations,
        "country":$scope.sharing_shipping_fee.add_country,
        "price":$scope.sharing_shipping_fee.add_price
      }

      $http.post('../../ng/server/data/sharing_shipping_fees/add_new_selected_sharing_shipping_fee.php', $scope.genericData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Sharing Shipping Fee added successfully !";

            notify.do_notify($scope.result_u, "Add Activity", "Sharing Shipping Fee Added");

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

  $scope.edit_sharing_shipping_fee = function (sharing_shipping_fee_unique_id, sharing_unique_id, city, state, country, price, sharing_item_name) {

    $scope.the_sharing_item_name = sharing_item_name;

    $scope.sharing_shipping_fee.edit_state = state;
    $scope.change_lga($scope.sharing_shipping_fee.edit_state);
    $scope.sharing_shipping_fee.edit_city = city;
    $scope.sharing_shipping_fee.edit_country = country;
    $scope.sharing_shipping_fee.edit_price = parseInt(price);

    $scope.go_ahead = function () {
      $scope.shouldIShow = true;
    };

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.genericData = {
        "user_unique_id":$scope.result_u,
        "sharing_shipping_fee_unique_id":sharing_shipping_fee_unique_id,
        "sharing_unique_id":sharing_unique_id,
        "city":$scope.sharing_shipping_fee.edit_city,
        "state":$scope.sharing_shipping_fee.edit_state,
        "country":$scope.sharing_shipping_fee.edit_country,
        "price":$scope.sharing_shipping_fee.edit_price
      }

      $http.post('../../ng/server/data/sharing_shipping_fees/update_sharing_shipping_fee.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {

          $scope.showSuccessStatus = true;
          $scope.actionStatus = "Changes Saved !";

          notify.do_notify($scope.result_u, "Edit Activity", "Sharing Shipping Fee (" + sharing_shipping_fee_unique_id + ") edited");

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

  $scope.delete_sharing_shipping_fee = function (sharing_shipping_fee_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "sharing_shipping_fee_unique_id":sharing_shipping_fee_unique_id
      }

      $http.post('../../ng/server/data/sharing_shipping_fees/remove_sharing_shipping_fee.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "Sharing Shipping fee (" + sharing_shipping_fee_unique_id + ") deleted");

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

  $scope.restore_sharing_shipping_fee = function (sharing_shipping_fee_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "sharing_shipping_fee_unique_id":sharing_shipping_fee_unique_id
      }

      $http.post('../../ng/server/data/sharing_shipping_fees/restore_sharing_shipping_fee.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Add Activity", "Sharing Shipping fee (" + sharing_shipping_fee_unique_id + ") restored");

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

  // -------------------- End here

  // ------------------- Pickup Location Shipping fee for sub products

  $scope.save_pickup_location_shipping_fee = function () {

    if (!$scope.selected_sub_products_pickup_locations.length) {
      $scope.show_selected_product_pickup_locations_error = true;
    }
    else if (!$scope.selected_sub_products.length) {
      $scope.show_selected_product_error = true;
    }
    else {
      $scope.show_selected_product_pickup_locations_error = undefined;
      $scope.show_selected_product_error = undefined;
      $scope.shouldIShow = true;
    }

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.genericData = {
        "user_unique_id":$scope.result_u,
        "sub_product_unique_ids":$scope.selected_sub_products,
        "default_pickup_location_unique_ids":$scope.selected_sub_products_pickup_locations,
        "price":$scope.pickup_location_shipping_fee.add_price
      }

      $http.post('../../ng/server/data/shipping_fees/add_new_selected_pickup_location_shipping_fee.php', $scope.genericData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Pickup Location(s) Shipping Fees added successfully !";

            notify.do_notify($scope.result_u, "Add Activity", "Pickup Location(s) Shipping Fees Added");

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

  $scope.edit_pickup_location_shipping_fee = function (pickup_location_unique_id, default_pickup_location_unique_id, sub_product_unique_id, address_first_name, address_last_name, address, city, state, country, price, sub_product_name, sub_product_size) {

    $scope.the_sub_product_name = sub_product_name;
    $scope.the_sub_product_size = sub_product_size;

    $scope.the_unique_id = default_pickup_location_unique_id;
    $scope.the_address_first_name = address_first_name;
    $scope.the_address_last_name = address_last_name;
    $scope.the_address = address;
    $scope.the_city = city;
    $scope.the_state = state;
    $scope.the_country = country;
    $scope.pickup_location_shipping_fee.edit_price = parseInt(price);

    $scope.go_ahead = function () {
      $scope.shouldIShow = true;
    };

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.genericData = {
        "user_unique_id":$scope.result_u,
        "pickup_location_unique_id":pickup_location_unique_id,
        "default_pickup_location_unique_id":default_pickup_location_unique_id,
        "sub_product_unique_id":sub_product_unique_id,
        "price":$scope.pickup_location_shipping_fee.edit_price
      }

      $http.post('../../ng/server/data/shipping_fees/update_pickup_location_shipping_fee.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {

          $scope.showSuccessStatus = true;
          $scope.actionStatus = "Changes Saved !";

          notify.do_notify($scope.result_u, "Edit Activity", "Pickup Location Shipping Fee (" + pickup_location_unique_id + ") edited");

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

  $scope.delete_pickup_location_shipping_fee = function (pickup_location_unique_id, sub_product_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "pickup_location_unique_id":pickup_location_unique_id,
        "sub_product_unique_id":sub_product_unique_id
      }

      $http.post('../../ng/server/data/shipping_fees/remove_pickup_location_shipping_fee.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "Pickup Location Shipping fee (" + pickup_location_unique_id + ") deleted");

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

  $scope.restore_pickup_location_shipping_fee = function (pickup_location_unique_id, sub_product_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "pickup_location_unique_id":pickup_location_unique_id,
        "sub_product_unique_id":sub_product_unique_id
      }

      $http.post('../../ng/server/data/shipping_fees/restore_pickup_location_shipping_fee.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Add Activity", "Pickup Location Shipping fee (" + pickup_location_unique_id + ") restored");

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

  // -------------------- End here

  // ------------------- Pickup Location Shipping fee for sharing items

  $scope.save_pickup_location_sharing_shipping_fee = function () {

    if (!$scope.selected_sharing_pickup_locations.length) {
      $scope.show_selected_sharing_pickup_locations_error = true;
    }
    else if (!$scope.selected_sharing_items.length) {
      $scope.show_selected_sharing_items_error = true;
    }
    else {
      $scope.show_selected_sharing_pickup_locations_error = undefined;
      $scope.show_selected_sharing_items_error = undefined;
      $scope.shouldIShow = true;
    }

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.genericData = {
        "user_unique_id":$scope.result_u,
        "sharing_unique_ids":$scope.selected_sharing_items,
        "default_pickup_location_unique_ids":$scope.selected_sharing_pickup_locations,
        "price":$scope.pickup_location_sharing_shipping_fee.add_price
      }

      $http.post('../../ng/server/data/sharing_shipping_fees/add_new_selected_pickup_location_sharing_shipping_fee.php', $scope.genericData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Sharing Item(s) Pickup Location(s) Shipping Fee(s) added successfully !";

            notify.do_notify($scope.result_u, "Add Activity", "Sharing Item(s) Pickup Location(s) Shipping Fee(s) Added");

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

  $scope.edit_pickup_location_sharing_shipping_fee = function (pickup_location_unique_id, default_pickup_location_unique_id, sharing_unique_id, address_first_name, address_last_name, address, city, state, country, price, sharing_item_name) {

    $scope.the_sharing_item_name = sharing_item_name;

    $scope.the_unique_id = default_pickup_location_unique_id;
    $scope.the_address_first_name = address_first_name;
    $scope.the_address_last_name = address_last_name;
    $scope.the_address = address;
    $scope.the_city = city;
    $scope.the_state = state;
    $scope.the_country = country;
    $scope.pickup_location_sharing_shipping_fee.edit_price = parseInt(price);

    $scope.go_ahead = function () {
      $scope.shouldIShow = true;
    };

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.genericData = {
        "user_unique_id":$scope.result_u,
        "pickup_location_unique_id":pickup_location_unique_id,
        "default_pickup_location_unique_id":default_pickup_location_unique_id,
        "sharing_unique_id":sharing_unique_id,
        "price":$scope.pickup_location_sharing_shipping_fee.edit_price
      }

      $http.post('../../ng/server/data/sharing_shipping_fees/update_pickup_location_sharing_shipping_fee.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {

          $scope.showSuccessStatus = true;
          $scope.actionStatus = "Changes Saved !";

          notify.do_notify($scope.result_u, "Edit Activity", "Sharing Item Pickup Location Shipping Fee (" + pickup_location_unique_id + ") edited");

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

  $scope.delete_pickup_location_sharing_shipping_fee = function (pickup_location_unique_id, sharing_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "pickup_location_unique_id":pickup_location_unique_id,
        "sharing_unique_id":sharing_unique_id
      }

      $http.post('../../ng/server/data/sharing_shipping_fees/remove_pickup_location_sharing_shipping_fee.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "Sharing Item Pickup Location Shipping fee (" + pickup_location_unique_id + ") deleted");

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

  $scope.restore_pickup_location_sharing_shipping_fee = function (pickup_location_unique_id, sharing_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "pickup_location_unique_id":pickup_location_unique_id,
        "sharing_unique_id":sharing_unique_id
      }

      $http.post('../../ng/server/data/sharing_shipping_fees/restore_pickup_location_sharing_shipping_fee.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Add Activity", "Sharing Item Pickup Location Shipping fee (" + pickup_location_unique_id + ") restored");

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

  // -------------------- End here

});
