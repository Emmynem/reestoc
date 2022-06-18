xnyder.controller('shipmentsCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$routeParams,$window,storage,notify){

  $rootScope.pageTitle = "Shipments";

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
    $scope.loadShipments();
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

      $http.post('../../ng/server/get/get_shipments_filter.php', $scope.filter_data)
        .then(function (response) {
          if (response.data.engineMessage == 1) {
            $scope.allShipmentsStatus = undefined;
            $scope.allShipments = response.data.resultData;
            $scope.allShipmentsCount = $scope.allShipments.length;

            $scope.remove_loader();

            $scope.currentPage=1;
            $scope.numLimit=100;
            $scope.start = 0;
            $scope.$watch('allShipments',function(newVal){
              if(newVal){
               $scope.pages=Math.ceil($scope.allShipments.length/$scope.numLimit);

              }
            });
            $scope.hideNext=function(){
              if(($scope.start+ $scope.numLimit) < $scope.allShipments.length){
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
            $scope.allShipmentsStatus = "No data in range !";
            $scope.allShipmentsCount = 0;
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allShipmentsStatus = undefined;
            // }, 3000)
          }
          else {
            $scope.allShipmentsStatus = "Couldn't get data";
            $scope.allShipmentsCount = 0;
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allShipmentsStatus = undefined;
            // }, 3000)
          }
        }, function (error) {
          $scope.allShipmentsStatus = "Something's Wrong";
          $scope.allShipmentsCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allShipmentsStatus = undefined;
          // }, 3000)
        })
    }
  };

  $scope.loadShipments = function () {

    $http.get('../../ng/server/get/get_all_shipments.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allShipmentsStatus = undefined;
          $scope.allShipments = response.data.resultData;
          $scope.allShipmentsCount = $scope.allShipments.length;

          $scope.remove_loader();

          $scope.currentPage=1;
          $scope.numLimit=100;
          $scope.start = 0;
          $scope.$watch('allShipments',function(newVal){
            if(newVal){
             $scope.pages=Math.ceil($scope.allShipments.length/$scope.numLimit);

            }
          });
          $scope.hideNext=function(){
            if(($scope.start+ $scope.numLimit) < $scope.allShipments.length){
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
          $scope.allShipmentsStatus = response.data.engineErrorMessage;
          $scope.allShipmentsCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allShipmentsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allShipmentsStatus = "Error Occured";
          $scope.allShipmentsCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allShipmentsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allShipmentsStatus = "Something's Wrong";
        $scope.allShipmentsCount = 0;
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allShipmentsStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadShipments();

  $scope.view_shipment = function (unique_id, shipment_unique_id) {

    $scope.genericData = {
      "unique_id":unique_id,
      "shipment_unique_id":shipment_unique_id
    }

    $http.post('../../ng/server/get/get_management_shipment_details.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.shipmentDetails = null;
          $scope.shipmentDetailsStatus = undefined;
          $scope.shipmentDetails = response.data.resultData[0];

          $scope.the_latitude = $scope.shipmentDetails.latitude;
          $scope.the_longitude = $scope.shipmentDetails.longitude;
          $scope.url = "https://www.google.com/maps/d/u/0/embed?mid=1OWjIiXc7clGxGdbrHvjNOdY9ZLo&ll="+ $scope.the_latitude +"%2C"+ $scope.the_longitude +"&z=15";
          $timeout(function () {
            document.getElementById('this_mao_op').src = $scope.url;
          }, 500)

        }
        else if (response.data.engineError == 2) {
          $scope.shipmentDetailsStatus = response.data.engineErrorMessage;
          // $timeout(function () {
          //   $scope.shipmentDetailsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.shipmentDetailsStatus = "Error Occured";
          // $timeout(function () {
          //   $scope.shipmentDetailsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.shipmentDetailsStatus = "Something's Wrong";
        // $timeout(function () {
        //   $scope.shipmentDetailsStatus = undefined;
        // }, 3000)
      })

  };

  $scope.update_shipment_shipped = function (shipment_unique_id, user_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "shipment_unique_id":shipment_unique_id
      }

      $http.post('../../ng/server/data/shipments/update_shipment_start.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Edit Activity", "Updated shipment - ("+ shipment_unique_id +") started");

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

  $scope.complete_shipment = function (shipment_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "shipment_unique_id":shipment_unique_id
      }

      $http.post('../../ng/server/data/shipments/update_shipment_completed.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Edit Activity", "Updated shipment - ("+ shipment_unique_id +") completed");

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

  $scope.delete_shipment = function (shipment_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":$scope.result_u,
        "shipment_unique_id":shipment_unique_id
      }

      $http.post('../../ng/server/data/shipments/remove_shipment.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "Deleted shipment - ("+ shipment_unique_id +")");

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

  $scope.update_shipment_rider = function(unique_id, shipment_unique_id) {

    $scope.genericData = {
      "unique_id":unique_id,
      "shipment_unique_id":shipment_unique_id
    }

    $http.post('../../ng/server/get/get_management_shipment_details.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.shipmentRiderDetails = null;
          $scope.shipmentRiderDetailsStatus = undefined;
          $scope.shipmentRiderDetails = response.data.resultData[0];
          $scope.shipmentRiderDetailsCount = $scope.shipmentRiderDetails.length;

          $scope.the_rider_unique_id = $scope.shipmentRiderDetails.rider_unique_id;

          $http.post('../../ng/server/get/get_all_riders_available.php', $scope.genericData)
            .then(function (response) {
              if (response.data.engineMessage == 1) {
                $scope.allRiders = null;
                $scope.allRidersStatus = undefined;
                $scope.allRiders = response.data.resultData;
                $scope.allRidersCount = $scope.allRiders.length;
              }
              else if (response.data.engineError == 2) {
                $scope.allRidersStatus = response.data.engineErrorMessage;
                $scope.allRidersCount = 0;
                // $timeout(function () {
                //   $scope.allRidersStatus = undefined;
                // }, 3000)
              }
              else {
                $scope.allRidersStatus = "Error Occured";
                $scope.allRidersCount = 0;
                // $timeout(function () {
                //   $scope.allRidersStatus = undefined;
                // }, 3000)
              }
            }, function (error) {
              $scope.allRidersStatus = "Something's Wrong";
              $scope.allRidersCount = 0;
              // $timeout(function () {
              //   $scope.allRidersStatus = undefined;
              // }, 3000)
            })

          $scope.go_ahead = function () {
            $scope.shouldIShow = true;

            $scope.continue_action = function () {

              $scope.clickOnce = true;

              $scope.genericData = {
                "user_unique_id":$scope.result_u,
                "shipment_unique_id":shipment_unique_id,
                "rider_unique_id":$scope.the_rider_unique_id
              }

              $http.post('../../ng/server/data/shipments/update_shipment_rider.php', $scope.genericData)
                .then(function (response) {
                  if (response.data.engineMessage == 1) {

                    $scope.showSuccessStatus = true;
                    $scope.actionStatus = "Action Completed";
                    notify.do_notify($scope.result_u, "Edit Activity", "Updated shipment - ("+ shipment_unique_id +") rider changed (" + $scope.the_rider_unique_id + ")");

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
          };

          $scope.removeConfirmModal = function () {

            $scope.shouldIShow = undefined;

          };

        }
        else if (response.data.engineError == 2) {
          $scope.shipmentsAvailableStatus = response.data.engineErrorMessage;
          $scope.shipmentsAvailableCount = 0;
          // $timeout(function () {
          //   $scope.shipmentsAvailableStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.shipmentsAvailableStatus = "Error Occured";
          $scope.shipmentsAvailableCount = 0;
          // $timeout(function () {
          //   $scope.shipmentsAvailableStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.shipmentsAvailableStatus = "Something's Wrong";
        $scope.shipmentsAvailableCount = 0;
        // $timeout(function () {
        //   $scope.shipmentsAvailableStatus = undefined;
        // }, 3000)
      })

  };

  $scope.update_shipment_location = function(unique_id, shipment_unique_id) {

    $scope.genericData = {
      "unique_id":unique_id,
      "shipment_unique_id":shipment_unique_id
    }

    $http.post('../../ng/server/get/get_management_shipment_details.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.shipmentLocationDetails = null;
          $scope.shipmentLocationDetailsStatus = undefined;
          $scope.shipmentLocationDetails = response.data.resultData[0];
          $scope.shipmentLocationDetailsCount = $scope.shipmentLocationDetails.length;

          $scope.the_edit_current_location = $scope.shipmentLocationDetails.current_location;
          $scope.the_edit_latitude = parseFloat($scope.shipmentLocationDetails.latitude);
          $scope.the_edit_longitude = parseFloat($scope.shipmentLocationDetails.longitude);

          // $scope.url = "https://www.google.com/maps/d/u/0/embed?mid=1OWjIiXc7clGxGdbrHvjNOdY9ZLo&ll="+ $scope.the_latitude +"%2C"+ $scope.the_longitude +"&z=15";
          // $timeout(function () {
          //   document.getElementById('this_mao_op_oop').src = $scope.url;
          // }, 500)

          $scope.go_ahead = function () {
            $scope.shouldIShow = true;

            $scope.continue_action = function () {

              $scope.clickOnce = true;

              $scope.genericData = {
                "user_unique_id":$scope.result_u,
                "shipment_unique_id":shipment_unique_id,
                "current_location":$scope.the_edit_current_location,
                "latitude":$scope.the_edit_latitude,
                "longitude":$scope.the_edit_longitude
              }

              $http.post('../../ng/server/data/shipments/update_shipment_location.php', $scope.genericData)
                .then(function (response) {
                  if (response.data.engineMessage == 1) {

                    $scope.showSuccessStatus = true;
                    $scope.actionStatus = "Action Completed";
                    notify.do_notify($scope.result_u, "Edit Activity", "Updated shipment - ("+ shipment_unique_id +") location updated (" + $scope.the_edit_latitude  + "," + $scope.the_edit_longitude + ")");

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
          };

          $scope.removeConfirmModal = function () {

            $scope.shouldIShow = undefined;

          };

        }
        else if (response.data.engineError == 2) {
          $scope.shipmentsAvailableStatus = response.data.engineErrorMessage;
          $scope.shipmentsAvailableCount = 0;
          // $timeout(function () {
          //   $scope.shipmentsAvailableStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.shipmentsAvailableStatus = "Error Occured";
          $scope.shipmentsAvailableCount = 0;
          // $timeout(function () {
          //   $scope.shipmentsAvailableStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.shipmentsAvailableStatus = "Something's Wrong";
        $scope.shipmentsAvailableCount = 0;
        // $timeout(function () {
        //   $scope.shipmentsAvailableStatus = undefined;
        // }, 3000)
      })

  };

  $scope.update_shipment_location_by_rider = function(unique_id, shipment_unique_id) {

    $scope.genericData = {
      "unique_id":unique_id,
      "shipment_unique_id":shipment_unique_id
    }

    $http.post('../../ng/server/get/get_management_shipment_details.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.shipmentLocationDetails = null;
          $scope.shipmentLocationDetailsStatus = undefined;
          $scope.shipmentLocationDetails = response.data.resultData[0];
          $scope.shipmentLocationDetailsCount = $scope.shipmentLocationDetails.length;

          $scope.the_edit_current_location = $scope.shipmentLocationDetails.current_location;
          $scope.the_edit_latitude = parseFloat($scope.shipmentLocationDetails.latitude);
          $scope.the_edit_longitude = parseFloat($scope.shipmentLocationDetails.longitude);

          $scope.url = "https://www.google.com/maps/d/u/0/embed?mid=1OWjIiXc7clGxGdbrHvjNOdY9ZLo&ll="+ $scope.the_edit_latitude +"%2C"+ $scope.the_edit_longitude +"&z=15";
          $timeout(function () {
            document.getElementById('this_mao_op_oop_edit').src = $scope.url;
          }, 500)

          $scope.go_ahead = function () {
            $scope.shouldIShow = true;

            $scope.continue_action = function () {

              $scope.clickOnce = true;

              $scope.genericData = {
                "rider_unique_id":$scope.result_u,
                "shipment_unique_id":shipment_unique_id,
                "current_location":$scope.the_edit_current_location,
                "latitude":$scope.the_edit_latitude,
                "longitude":$scope.the_edit_longitude
              }

              $http.post('../../ng/server/data/shipments/update_rider_shipment_location.php', $scope.genericData)
                .then(function (response) {
                  if (response.data.engineMessage == 1) {

                    $scope.showSuccessStatus = true;
                    $scope.actionStatus = "Action Completed";
                    notify.do_notify($scope.result_u, "Edit Activity", "Updated shipment - ("+ shipment_unique_id +") location updated (" + $scope.the_edit_latitude  + "," + $scope.the_edit_longitude + ")");

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
          };

          $scope.removeConfirmModal = function () {

            $scope.shouldIShow = undefined;

          };

        }
        else if (response.data.engineError == 2) {
          $scope.shipmentsAvailableStatus = response.data.engineErrorMessage;
          $scope.shipmentsAvailableCount = 0;
          // $timeout(function () {
          //   $scope.shipmentsAvailableStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.shipmentsAvailableStatus = "Error Occured";
          $scope.shipmentsAvailableCount = 0;
          // $timeout(function () {
          //   $scope.shipmentsAvailableStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.shipmentsAvailableStatus = "Something's Wrong";
        $scope.shipmentsAvailableCount = 0;
        // $timeout(function () {
        //   $scope.shipmentsAvailableStatus = undefined;
        // }, 3000)
      })

  };

  $scope.create_shipment = function() {

    $http.post('../../ng/server/get/get_all_riders.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allRiders = null;
          $scope.allRidersStatus = undefined;
          $scope.allRiders = response.data.resultData;
          $scope.allRidersCount = $scope.allRiders.length;
        }
        else if (response.data.engineError == 2) {
          $scope.allRidersStatus = response.data.engineErrorMessage;
          $scope.allRidersCount = 0;
          // $timeout(function () {
          //   $scope.allRidersStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allRidersStatus = "Error Occured";
          $scope.allRidersCount = 0;
          // $timeout(function () {
          //   $scope.allRidersStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allRidersStatus = "Something's Wrong";
        $scope.allRidersCount = 0;
        // $timeout(function () {
        //   $scope.allRidersStatus = undefined;
        // }, 3000)
      })

    $scope.disable_location = false;

    $scope.the_latitude = 4.843785;
    $scope.the_longitude = 7.057911;
    $scope.url = "https://www.google.com/maps/d/u/0/embed?mid=1OWjIiXc7clGxGdbrHvjNOdY9ZLo&ll="+ $scope.the_latitude +"%2C"+ $scope.the_longitude +"&z=15";
    $timeout(function () {
      document.getElementById('this_mao_op_oop').src = $scope.url;
    }, 500)

    $scope.the_current_location = "Rumuibekwe 500102, Port Harcourt, Nigeria";

    $scope.go_ahead = function () {
      $scope.shouldIShow = true;

      $scope.continue_action = function () {

        $scope.clickOnce = true;

        $scope.genericData = {
          "user_unique_id":$scope.result_u,
          "rider_unique_id":$scope.the_rider_unique_id,
          "current_location":$scope.the_current_location,
          "longitude":$scope.the_longitude,
          "latitude":$scope.the_latitude
        }

        $http.post('../../ng/server/data/shipments/add_new_shipment.php', $scope.genericData)
          .then(function (response) {
            if (response.data.engineMessage == 1) {

              $scope.showSuccessStatus = true;
              $scope.actionStatus = "Action Completed";
              notify.do_notify($scope.result_u, "Add Activity", "Shipment created - rider (" + $scope.the_rider_unique_id + ")");

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
    };

    $scope.removeConfirmModal = function () {

      $scope.shouldIShow = undefined;

    };

  };

});
