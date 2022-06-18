xnyder.controller('default-pickup-locationsCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$routeParams,$window,storage,notify,listLGA){

  $rootScope.pageTitle = "Pickup Locations";

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
    $scope.loadDefaultPickupLocations();
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

  $scope.default_pickup_location = {};

  $scope.default_pickup_location.add_country = "Nigeria";
  $scope.default_pickup_location.add_state = "Rivers";

  $scope.change_lga($scope.default_pickup_location.add_state);

  $scope.loadDefaultPickupLocations = function () {

    $http.get('../../ng/server/get/get_all_default_pickup_locations.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allDefaultPickupLocationsStatus = undefined;
          $scope.allDefaultPickupLocations = response.data.resultData;
          $scope.allDefaultPickupLocationsCount = $scope.allDefaultPickupLocations.length;

          $scope.remove_loader();

          $scope.currentPage=1;
          $scope.numLimit=100;
          $scope.start = 0;
          $scope.$watch('allDefaultPickupLocations',function(newVal){
            if(newVal){
             $scope.pages=Math.ceil($scope.allDefaultPickupLocations.length/$scope.numLimit);

            }
          });
          $scope.hideNext=function(){
            if(($scope.start+ $scope.numLimit) < $scope.allDefaultPickupLocations.length){
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
          $scope.allDefaultPickupLocationsStatus = response.data.engineErrorMessage;
          $scope.allDefaultPickupLocationsCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allDefaultPickupLocationsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allDefaultPickupLocationsStatus = "Error Occured";
          $scope.allDefaultPickupLocationsCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allDefaultPickupLocationsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allDefaultPickupLocationsStatus = "Something's Wrong";
        $scope.allDefaultPickupLocationsCount = 0;
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allDefaultPickupLocationsStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadDefaultPickupLocations();

  $scope.save_default_pickup_location = function () {

    $scope.shouldIShow = true;

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.genericData = {
        "user_unique_id":$scope.result_u,
        "firstname":$scope.default_pickup_location.add_firstname,
        "lastname":$scope.default_pickup_location.add_lastname,
        "city":$scope.default_pickup_location.add_city,
        "state":$scope.default_pickup_location.add_state,
        "country":$scope.default_pickup_location.add_country,
        "address":$scope.default_pickup_location.add_address,
        "additional_information":$scope.default_pickup_location.add_additional_information != undefined ? $scope.default_pickup_location.add_additional_information : null
      }

      $http.post('../../ng/server/data/default_pickup_locations/add_new_default_pickup_location.php', $scope.genericData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Default Pickup Location added successfully !";

            notify.do_notify($scope.result_u, "Add Activity", "Default Pickup Location Added");

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

  $scope.edit_default_pickup_location = function (default_pickup_location_unique_id, firstname, lastname, address, additional_information, city, state, country) {

    $scope.default_pickup_location.edit_firstname = firstname;
    $scope.default_pickup_location.edit_lastname = lastname;
    $scope.default_pickup_location.edit_address = address;
    $scope.default_pickup_location.edit_additional_information = additional_information;
    $scope.default_pickup_location.edit_city = city;
    $scope.default_pickup_location.edit_state = state;
    $scope.default_pickup_location.edit_country = country;

    $scope.change_lga($scope.default_pickup_location.edit_state);

    $scope.go_ahead = function () {
      $scope.shouldIShow = true;
    };

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.genericData = {
        "user_unique_id":$scope.result_u,
        "default_pickup_location_unique_id":default_pickup_location_unique_id,
        "firstname":$scope.default_pickup_location.edit_firstname,
        "lastname":$scope.default_pickup_location.edit_lastname,
        "city":$scope.default_pickup_location.edit_city,
        "state":$scope.default_pickup_location.edit_state,
        "country":$scope.default_pickup_location.edit_country,
        "address":$scope.default_pickup_location.edit_address,
        "additional_information":$scope.default_pickup_location.edit_additional_information != undefined ? $scope.default_pickup_location.edit_additional_information : null
      }

      $http.post('../../ng/server/data/default_pickup_locations/update_default_pickup_location.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {

          $scope.showSuccessStatus = true;
          $scope.actionStatus = "Changes Saved !";

          notify.do_notify($scope.result_u, "Edit Activity", "Default Pickup Location (" + default_pickup_location_unique_id + ") edited");

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

  $scope.delete_default_pickup_location = function (default_pickup_location_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "default_pickup_location_unique_id":default_pickup_location_unique_id
      }

      $http.post('../../ng/server/data/default_pickup_locations/remove_default_pickup_location.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "Default Pickup Location (" + default_pickup_location_unique_id + ") deleted");

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

  $scope.restore_default_pickup_location = function (default_pickup_location_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "default_pickup_location_unique_id":default_pickup_location_unique_id
      }

      $http.post('../../ng/server/data/default_pickup_locations/restore_default_pickup_location.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Add Activity", "Default Pickup Location (" + default_pickup_location_unique_id + ") restored");

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
