xnyder.controller('edit-ticket-categoryCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$routeParams,$window,storage,notify){

  $rootScope.pageTitle = "Edit Ticket Category";

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

  $scope.show_loader = true;

  $scope.unlock_feature = false;

  $scope.remove_loader = function () {

    $timeout(function () {
      $scope.show_loader = undefined;
    }, 2000)

  };

  $scope.event_unique_id = $routeParams.event_unique_id;

  $scope.unique_id = $routeParams.unique_id;

  $scope.ticket = {};

  $scope.ticket_details = {};

  $scope.get_event_details = function () {

    $scope.enquiryData = {
      "table":"events",
      "event_unique_id":$scope.event_unique_id,
      "unique_id":$scope.unique_id
    }

    $http.post("server/get_an_event_alt_2.php", $scope.enquiryData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.showStatus = undefined;
          $scope.event_details = response.data.re_data;

          $scope.ticket_details.display_title = $scope.event_details.display_title;
          $scope.ticket_details.total_no_of_tickets = $scope.event_details.total_no_of_tickets;
          $scope.ticket_details.tickets_left = $scope.ticket_details.total_no_of_tickets - response.data.total_remaining_sum;
          $scope.ticket_details.tickets_left_for_this_category = response.data.remaining_sum;

          $scope.edit_ticket_details = response.data.ticket_details;

          $scope.ticket.ticket_name = $scope.edit_ticket_details.ticket_name;
          $scope.ticket.ticket_description = $scope.edit_ticket_details.ticket_description;
          $scope.ticket.price = parseInt($scope.edit_ticket_details.price);
          $scope.ticket.total_no_of_ticketing = parseInt($scope.edit_ticket_details.total_no_of_ticketing);

          $scope.remove_loader();

          $scope.editTicketingSuccessStatus = "Event loaded ...";
          $timeout(function () {
            $scope.editTicketingSuccessStatus = undefined;
          }, 4000)

        }
        else if (response.data.noData == 2) {
          $scope.showStatus = true;
          $scope.actionStatus = "Couldn't get details !";
          $scope.editTicketingStatus = $scope.actionStatus;
          $scope.remove_loader();
          $timeout(function () {
            // $scope.showStatus = undefined;
            // $scope.actionStatus = undefined;
            // $scope.editTicketingStatus = undefined;
            // $scope.clickOnce = false;
          }, 3000)
        }
        else {
          $scope.showStatus = true;
          $scope.actionStatus = "Error Occured !";
          $scope.editTicketingStatus = $scope.actionStatus;
          $scope.remove_loader();
          $timeout(function () {
            // $scope.showStatus = undefined;
            // $scope.actionStatus = undefined;
            // $scope.editTicketingStatus = undefined;
            // $scope.clickOnce = false;
          }, 3000)
        }
      }, function (error) {
        $scope.showStatus = true;
        $scope.actionStatus = "Something's Wrong !";
        $scope.editTicketingStatus = $scope.actionStatus;
        $scope.remove_loader();
        $timeout(function () {
          // $scope.showStatus = undefined;
          // $scope.actionStatus = undefined;
          // $scope.editTicketingStatus = undefined;
          // $scope.clickOnce = false;
        }, 3000)
      })

  };

  $scope.get_event_details();

  $scope.saveTicketingChanges = function () {

    if ($scope.ticket.total_no_of_ticketing > $scope.ticket_details.tickets_left) {
      $scope.showInvalidError = true;
    }
    else {

      $scope.showInvalidError = undefined;

      $scope.shouldIShow = true;

      $scope.continue_action = function () {

        $scope.clickOnce = true;

        $scope.ticketData = {
          "edit_user_unique_id":$scope.result_u,
          "event_unique_id":$scope.event_unique_id,
          "unique_id":$scope.unique_id,
          "ticket_name":$scope.ticket.ticket_name,
          "ticket_description":$scope.ticket.ticket_description,
          "price":$scope.ticket.price,
          "total_no_of_ticketing":$scope.ticket.total_no_of_ticketing
        }

        $http.post('server/edit_ticket_category.php', $scope.ticketData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Changes Saved !";
            // $scope.editTicketingSuccessStatus = $scope.actionStatus;
            notify.do_notify($scope.result_u, "Edit Activity", "Ticket category edited");

            $timeout(function () {
              $scope.showSuccessStatus = undefined;
              $scope.actionStatus = undefined;
              $scope.editTicketingSuccessStatus = undefined;
              $route.reload();
              $location.path('events');
            }, 3000)

          }
          else if (response.data.noData == 2) {
            $scope.showStatus = true;
            $scope.actionStatus = "No details found";
            // $scope.editTicketingStatus = $scope.actionStatus;
            $timeout(function () {
              $scope.showStatus = undefined;
              $scope.actionStatus = undefined;
              $scope.editTicketingStatus = undefined;
              $scope.clickOnce = false;
            }, 3000)
          }
          else {
            $scope.showStatus = true;
            $scope.actionStatus = "Error Occured";
            // $scope.editTicketingStatus = $scope.actionStatus;
            $timeout(function () {
              $scope.showStatus = undefined;
              $scope.actionStatus = undefined;
              $scope.editTicketingStatus = undefined;
              $scope.clickOnce = false;
            }, 3000)
          }
        }, function (error) {
          $scope.showStatus = true;
          $scope.actionStatus = "Something's Wrong";
          $scope.editTicketingStatus = $scope.actionStatus;
          $timeout(function () {
            $scope.showStatus = undefined;
            $scope.actionStatus = undefined;
            $scope.editTicketingStatus = undefined;
            $scope.clickOnce = false;
          }, 3000)
        })

      };

      $scope.removeConfirmModal = function () {

        $scope.shouldIShow = undefined;

      };

    }

  };

});
