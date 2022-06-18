xnyder.controller('agentsCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$routeParams,$window,storage,notify){

  $rootScope.pageTitle = "Agents";

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
    $scope.loadAgents();
  };

  $scope.loadAgents = function () {

    $http.get('../../ng/server/get/get_all_agents.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allAgentsStatus = undefined;
          $scope.allAgents = response.data.resultData;
          $scope.allAgentsCount = $scope.allAgents.length;

          $scope.remove_loader();

          $scope.currentPage=1;
          $scope.numLimit=100;
          $scope.start = 0;
          $scope.$watch('allAgents',function(newVal){
            if(newVal){
             $scope.pages=Math.ceil($scope.allAgents.length/$scope.numLimit);

            }
          });
          $scope.hideNext=function(){
            if(($scope.start+ $scope.numLimit) < $scope.allAgents.length){
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
          $scope.allAgentsStatus = response.data.engineErrorMessage;
          $scope.allAgentsCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allAgentsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allAgentsStatus = "Error Occured";
          $scope.allAgentsCount = 0;
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allAgentsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allAgentsStatus = "Something's Wrong";
        $scope.allAgentsCount = 0;
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allAgentsStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadAgents();

  $scope.agent = {};

  $scope.edit_agent_details = function(edit_user_unique_id, unique_id, role, access) {

    $scope.agent.role = role;
    $scope.agent.access = access;

    $scope.go_ahead = function () {
      $scope.shouldIShow = true;

      $scope.continue_action = function () {

        $scope.clickOnce = true;

        $scope.genericData = {
          "edit_user_unique_id":$scope.result_u,
          "agent_unique_id":unique_id,
          "role":$scope.agent.role,
          "access":$scope.agent.access
        }

        $http.post('../../ng/server/data/agents/update_agent_details.php', $scope.genericData)
          .then(function (response) {
            if (response.data.engineMessage == 1) {

              $scope.showSuccessStatus = true;
              $scope.actionStatus = "Action Completed";
              notify.do_notify($scope.result_u, "Edit Activity", "Updated agent - ("+ unique_id +") details");

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

  $scope.delete_agent = function (agent_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "edit_user_unique_id":$scope.result_u,
        "agent_unique_id":agent_unique_id,
      }

      $http.post('../../ng/server/data/agents/remove_agent.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "Agent ("+ agent_unique_id +") Removed");

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

  $scope.restore_agent = function (agent_unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.confirmData = {
        "edit_user_unique_id":$scope.result_u,
        "agent_unique_id":agent_unique_id,
      }

      $http.post('../../ng/server/data/agents/restore_agent.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Add Activity", "Agent ("+ agent_unique_id +") Restored");

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
