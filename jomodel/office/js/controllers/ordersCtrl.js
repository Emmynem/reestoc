xnyder.controller('ordersCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$routeParams,$window,storage,notify){

  $rootScope.pageTitle = "Orders";

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
    $scope.loadOrders();
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

      $http.post('../../ng/server/get/get_orders_filter.php', $scope.filter_data)
        .then(function (response) {
          if (response.data.engineMessage == 1) {
            $scope.allOrdersStatus = undefined;
            $scope.allOrders = response.data.resultData;
            $scope.allOrdersCount = $scope.allOrders.length;

            $scope.remove_loader();

            $scope.currentPage=1;
            $scope.numLimit=100;
            $scope.start = 0;
            $scope.$watch('allOrders',function(newVal){
              if(newVal){
               $scope.pages=Math.ceil($scope.allOrders.length/$scope.numLimit);

              }
            });
            $scope.hideNext=function(){
              if(($scope.start+ $scope.numLimit) < $scope.allOrders.length){
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
            $scope.allOrdersStatus = "No data in range !";
            $scope.allOrdersCount = 0;
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allOrdersStatus = undefined;
            // }, 3000)
          }
          else {
            $scope.allOrdersStatus = "Couldn't get data";
            $scope.allOrdersCount = 0;
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allOrdersStatus = undefined;
            // }, 3000)
          }
        }, function (error) {
          $scope.allOrdersStatus = "Something's Wrong";
          $scope.allOrdersCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allOrdersStatus = undefined;
          // }, 3000)
        })
    }
  };

  $scope.loadOrders = function () {

    $http.get('../../ng/server/get/get_all_orders.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allOrdersStatus = undefined;
          $scope.allOrders = response.data.resultData;
          $scope.allOrdersCount = $scope.allOrders.length;

          $scope.remove_loader();

          $scope.currentPage=1;
          $scope.numLimit=100;
          $scope.start = 0;
          $scope.$watch('allOrders',function(newVal){
            if(newVal){
             $scope.pages=Math.ceil($scope.allOrders.length/$scope.numLimit);

            }
          });
          $scope.hideNext=function(){
            if(($scope.start+ $scope.numLimit) < $scope.allOrders.length){
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
          $scope.allOrdersStatus = response.data.engineErrorMessage;
          $scope.allOrdersCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allOrdersStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allOrdersStatus = "Error Occured";
          $scope.allOrdersCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allOrdersStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allOrdersStatus = "Something's Wrong";
        $scope.allOrdersCount = 0;
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allOrdersStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadOrders();

  $scope.view_order = function (user_unique_id, order_unique_id) {

    $scope.genericData = {
      "user_unique_id":user_unique_id,
      "order_unique_id":order_unique_id
    }

    $http.post('../../ng/server/get/get_user_order_details.php', $scope.genericData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.orderDetails = null;
          $scope.orderDetailsStatus = undefined;
          $scope.orderDetails = response.data.resultData[0];

          $scope.genericData2 = {
            "user_unique_id":user_unique_id
          }

          $http.post('../../ng/server/get/get_user_addresses_for_users.php', $scope.genericData2)
            .then(function (response) {
              if (response.data.engineMessage == 1) {
                $scope.addressDetails = null;
                $scope.addressDetailsStatus = undefined;
                $scope.addressDetails = response.data.resultData;
                $scope.addressDetailsCount = $scope.addressDetails.length;
              }
              else if (response.data.engineError == 2) {
                $scope.addressDetailsStatus = response.data.engineErrorMessage;
                $scope.addressDetailsCount = 0;
                // $timeout(function () {
                //   $scope.addressDetailsStatus = undefined;
                // }, 3000)
              }
              else {
                $scope.addressDetailsStatus = "Error Occured";
                $scope.addressDetailsCount = 0;
                // $timeout(function () {
                //   $scope.addressDetailsStatus = undefined;
                // }, 3000)
              }
            }, function (error) {
              $scope.addressDetailsStatus = "Something's Wrong";
              $scope.addressDetailsCount = 0;
              // $timeout(function () {
              //   $scope.addressDetailsStatus = undefined;
              // }, 3000)
            })

          $http.post('../../ng/server/get/get_user_single_order_history.php', $scope.genericData)
            .then(function (response) {
              if (response.data.engineMessage == 1) {
                $scope.orderHistoryDetails = null;
                $scope.orderHistoryDetailsStatus = undefined;
                $scope.orderHistoryDetails = response.data.resultData;
                $scope.orderHistoryDetailsCount = $scope.orderHistoryDetails.length;
              }
              else if (response.data.engineError == 2) {
                $scope.orderHistoryDetailsStatus = response.data.engineErrorMessage;
                $scope.orderHistoryDetailsCount = 0;
                // $timeout(function () {
                //   $scope.orderHistoryDetailsStatus = undefined;
                // }, 3000)
              }
              else {
                $scope.orderHistoryDetailsStatus = "Error Occured";
                $scope.orderHistoryDetailsCount = 0;
                // $timeout(function () {
                //   $scope.orderHistoryDetailsStatus = undefined;
                // }, 3000)
              }
            }, function (error) {
              $scope.orderHistoryDetailsStatus = "Something's Wrong";
              $scope.orderHistoryDetailsCount = 0;
              // $timeout(function () {
              //   $scope.orderHistoryDetailsStatus = undefined;
              // }, 3000)
            })

          $scope.genericData3 = {
            "user_unique_id":user_unique_id,
            "shipment_unique_id":$scope.orderDetails.shipment_unique_id
          }

          $http.post('../../ng/server/get/get_shipment_details.php', $scope.genericData3)
            .then(function (response) {
              if (response.data.engineMessage == 1) {
                $scope.orderShipmentDetails = null;
                $scope.orderShipmentDetailsStatus = undefined;
                $scope.orderShipmentDetails = response.data.resultData;
                $scope.orderShipmentDetailsCount = $scope.orderShipmentDetails.length;

                $scope.the_latitude = $scope.orderShipmentDetails.latitude;
                $scope.the_longitude = $scope.orderShipmentDetails.longitude;
                $scope.url = "https://www.google.com/maps/d/u/0/embed?mid=1OWjIiXc7clGxGdbrHvjNOdY9ZLo&ll="+ $scope.the_latitude +"%2C"+ $scope.the_longitude +"&z=15";
                $timeout(function () {
                  document.getElementById('this_mao_op').src = $scope.url;
                }, 500)

              }
              else if (response.data.engineError == 2) {
                $scope.orderShipmentDetailsStatus = response.data.engineErrorMessage;
                $scope.orderShipmentDetailsCount = 0;
                // $timeout(function () {
                //   $scope.orderShipmentDetailsStatus = undefined;
                // }, 3000)
              }
              else {
                $scope.orderShipmentDetailsStatus = "Error Occured";
                $scope.orderShipmentDetailsCount = 0;
                // $timeout(function () {
                //   $scope.orderShipmentDetailsStatus = undefined;
                // }, 3000)
              }
            }, function (error) {
              $scope.orderShipmentDetailsStatus = "Something's Wrong";
              $scope.orderShipmentDetailsCount = 0;
              // $timeout(function () {
              //   $scope.orderShipmentDetailsStatus = undefined;
              // }, 3000)
            })

          $scope.genericData4 = {
            "user_unique_id":user_unique_id,
            "order_unique_id":order_unique_id,
            "tracker_unique_id":$scope.orderDetails.tracker_unique_id
          }

          $http.post('../../ng/server/get/get_single_completed_order_details.php', $scope.genericData4)
            .then(function (response) {
              if (response.data.engineMessage == 1) {
                $scope.orderCompletedDetails = null;
                $scope.orderCompletedDetailsStatus = undefined;
                $scope.orderCompletedDetails = response.data.resultData;
                $scope.orderCompletedDetailsCount = $scope.orderCompletedDetails.length;
              }
              else if (response.data.engineError == 2) {
                $scope.orderCompletedDetailsStatus = response.data.engineErrorMessage;
                $scope.orderCompletedDetailsCount = 0;
                // $timeout(function () {
                //   $scope.orderCompletedDetailsStatus = undefined;
                // }, 3000)
              }
              else {
                $scope.orderCompletedDetailsStatus = "Error Occured";
                $scope.orderCompletedDetailsCount = 0;
                // $timeout(function () {
                //   $scope.orderCompletedDetailsStatus = undefined;
                // }, 3000)
              }
            }, function (error) {
              $scope.orderCompletedDetailsStatus = "Something's Wrong";
              $scope.orderCompletedDetailsCount = 0;
              // $timeout(function () {
              //   $scope.orderCompletedDetailsStatus = undefined;
              // }, 3000)
            })

        }
        else if (response.data.engineError == 2) {
          $scope.orderDetailsStatus = response.data.engineErrorMessage;
          // $timeout(function () {
          //   $scope.orderDetailsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.orderDetailsStatus = "Error Occured";
          // $timeout(function () {
          //   $scope.orderDetailsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.orderDetailsStatus = "Something's Wrong";
        // $timeout(function () {
        //   $scope.orderDetailsStatus = undefined;
        // }, 3000)
      })

  };

  $scope.update_order_paid = function (order_unique_id, user_unique_id, tracker_unique_id, sub_product_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":user_unique_id,
        "order_unique_id":order_unique_id,
        "tracker_unique_id":tracker_unique_id,
        "sub_product_unique_id":sub_product_unique_id
      }

      $http.post('../../ng/server/data/orders/update_order_paid.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Edit Activity", "Updated order - ("+ tracker_unique_id +") paid");

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

  $scope.update_order_not_paid = function (order_unique_id, user_unique_id, tracker_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.option = "Unpaid";
    $scope.message = "Order is " + $scope.option;

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "management_user_unique_id":$scope.result_u,
        "user_unique_id":user_unique_id,
        "order_unique_id":order_unique_id,
        "tracker_unique_id":tracker_unique_id,
        "option":$scope.option,
        "message":$scope.message
      }

      $http.post('../../ng/server/data/orders/update_order_not_paid.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Edit Activity", "Updated order - ("+ order_unique_id +") unpaid");

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

  $scope.complete_order = function (order_unique_id, user_unique_id, tracker_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "user_unique_id":user_unique_id,
        "order_unique_id":order_unique_id,
        "tracker_unique_id":tracker_unique_id
      }

      $http.post('../../ng/server/data/orders/update_order_completed.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Edit Activity", "Updated order - ("+ tracker_unique_id +") completed");

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

  $scope.get_shipment_details = function(order_unique_id, user_unique_id, tracker_unique_id) {

    $http.get('../../ng/server/get/get_all_available_shipments.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.shipmentsAvailable = null;
          $scope.shipmentsAvailableStatus = undefined;
          $scope.shipmentsAvailable = response.data.resultData;
          $scope.shipmentsAvailableCount = $scope.shipmentsAvailable.length;

          $scope.change_map = function () {
            $scope.show_map_now = true;
            // document.getElementById('this_mao').src = "";
            $scope.shipment_values_arr = $scope.the_shipment_unique_id.split(",");
            $scope.the_latitude = $scope.shipment_values_arr[1];
            $scope.the_longitude = $scope.shipment_values_arr[2];
            $scope.url = "https://www.google.com/maps/d/u/0/embed?mid=1OWjIiXc7clGxGdbrHvjNOdY9ZLo&ll="+ $scope.the_latitude +"%2C"+ $scope.the_longitude +"&z=15";
            document.getElementById('this_mao').src = $scope.url;
          };

          $scope.go_ahead = function () {
            $scope.shouldIShow = true;

            $scope.shipment_values_arr = $scope.the_shipment_unique_id.split(",");
            $scope.the_shipment_unique_id_alt = $scope.shipment_values_arr[0];

            $scope.continue_action = function () {

              $scope.clickOnce = true;

              $scope.genericData = {
                "user_unique_id":user_unique_id,
                "order_unique_id":order_unique_id,
                "tracker_unique_id":tracker_unique_id,
                "shipment_unique_id":$scope.the_shipment_unique_id_alt
              }

              $http.post('../../ng/server/data/orders/update_order_shipped.php', $scope.genericData)
                .then(function (response) {
                  if (response.data.engineMessage == 1) {

                    $scope.showSuccessStatus = true;
                    $scope.actionStatus = "Action Completed";
                    notify.do_notify($scope.result_u, "Edit Activity", "Updated order - ("+ tracker_unique_id +") shipped (" + $scope.the_shipment_unique_id_alt + ")");

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

});
