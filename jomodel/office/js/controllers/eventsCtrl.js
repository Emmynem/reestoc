xnyder.controller('eventsCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$window,storage,notify){

  $rootScope.pageTitle = "Events";

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

  $scope.remove_loader = function () {

    $timeout(function () {
      $scope.show_loader = undefined;
    }, 2000)

  };

  $scope.filterShow = false;

  $scope.filterEventDateShow = false;

  $scope.filter_var = {};

  $scope.filter_event_date_var = {};

  $scope.new_date;

  $scope.get_today = function () {

    $scope.today = new Date();
    $scope.today.setHours(0, 0, 0, 0);

    if ($scope.today.getMonth() + 1 < 10) {
      // $scope.new_date = $scope.today.getFullYear() + "-0" + ($scope.today.getMonth() + 1) + "-" + $scope.today.getDate();

      if ($scope.today.getDate() + 1 < 10) {
        $scope.new_date = $scope.today.getFullYear() + "-0" + ($scope.today.getMonth() + 1) + "-0" + $scope.today.getDate();
      }
      else {
        $scope.new_date = $scope.today.getFullYear() + "-0" + ($scope.today.getMonth() + 1) + "-" + $scope.today.getDate();
      }

    }
    else {
      // $scope.new_date = $scope.today.getFullYear() + "-" + ($scope.today.getMonth() + 1) + "-" + $scope.today.getDate();

      if ($scope.today.getDate() + 1 < 10) {
        $scope.new_date = $scope.today.getFullYear() + "-" + ($scope.today.getMonth() + 1) + "-0" + $scope.today.getDate();
      }
      else {
        $scope.new_date = $scope.today.getFullYear() + "-" + ($scope.today.getMonth() + 1) + "-" + $scope.today.getDate();
      }
    }

  };

  $scope.get_today();

  $scope.filterByDate = function () {
    $scope.filter_var.startdate = new Date();
    $scope.filter_var.startdate.setHours(0, 0, 0, 0);
    $scope.filter_var.enddate = new Date();
    $scope.filter_var.enddate.setHours(23, 59, 59, 999);
    $scope.filterShow = true;
  };

  $scope.filterByEventDate = function () {
    $scope.filter_event_date_var.startdate = new Date();
    $scope.filter_event_date_var.startdate.setHours(0, 0, 0, 0);
    $scope.filter_event_date_var.enddate = new Date();
    $scope.filter_event_date_var.enddate.setHours(23, 59, 59, 999);
    $scope.filterEventDateShow = true;
  };

  $scope.filter = function () {

    if ($scope.filter_var.startdate > $scope.filter_var.enddate) {
      $scope.filterShow = true;
    }
    else {

      $scope.filterShow = false;
      $scope.filterEventDateShow = false;

      $scope.show_loader = true;

      $scope.filter_data = {
        "table":"events",
        "startdate":$scope.filter_var.startdate,
        "enddate":$scope.filter_var.enddate
      }

      $http.post('server/filter_events.php', $scope.filter_data)
        .then(function (response) {
          if (response.data.engineMessage == 1) {
            $scope.allEventsStatus = undefined;
            $scope.allEvents = response.data.filteredData;

            $scope.remove_loader();

            $scope.currentPage=1;
            $scope.numLimit=20;
            $scope.start = 0;
            $scope.$watch('allEvents',function(newVal){
              if(newVal){
               $scope.pages=Math.ceil($scope.allEvents.length/$scope.numLimit);

              }
            });
            $scope.hideNext=function(){
              if(($scope.start+ $scope.numLimit) < $scope.allEvents.length){
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
          else if (response.data.noData == 2) {
            $scope.allEventsStatus = "No data in range !";
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allEventsStatus = undefined;
            // }, 3000)
          }
          else {
            $scope.allEventsStatus = "Couldn't get data";
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allEventsStatus = undefined;
            // }, 3000)
          }
        }, function (error) {
          $scope.allEventsStatus = "Something's Wrong";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allEventsStatus = undefined;
          // }, 3000)
        })
    }
  };

  $scope.filter_event_date = function () {

    if ($scope.filter_event_date_var.startdate > $scope.filter_event_date_var.enddate) {
      $scope.filterEventDateShow = true;
    }
    else {

      $scope.filterEventDateShow = false;
      $scope.filterShow = false;

      $scope.show_loader = true;

      $scope.filter_data = {
        "table":"events",
        "startdate":$scope.filter_event_date_var.startdate,
        "enddate":$scope.filter_event_date_var.enddate
      }

      $http.post('server/filter_event_date.php', $scope.filter_data)
        .then(function (response) {
          if (response.data.engineMessage == 1) {
            $scope.allEventsStatus = undefined;
            $scope.allEvents = response.data.filteredData;

            $scope.remove_loader();

            $scope.currentPage=1;
            $scope.numLimit=20;
            $scope.start = 0;
            $scope.$watch('allEvents',function(newVal){
              if(newVal){
               $scope.pages=Math.ceil($scope.allEvents.length/$scope.numLimit);

              }
            });
            $scope.hideNext=function(){
              if(($scope.start+ $scope.numLimit) < $scope.allEvents.length){
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
          else if (response.data.noData == 2) {
            $scope.allEventsStatus = "No data in range !";
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allEventsStatus = undefined;
            // }, 3000)
          }
          else {
            $scope.allEventsStatus = "Couldn't get data";
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allEventsStatus = undefined;
            // }, 3000)
          }
        }, function (error) {
          $scope.allEventsStatus = "Something's Wrong";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allEventsStatus = undefined;
          // }, 3000)
        })
    }
  };

  $scope.loadEvents = function () {

    $http.get('server/get_events.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allEventsStatus = undefined;
          $scope.allEvents = response.data.re_data;

          $scope.remove_loader();

          $scope.currentPage=1;
          $scope.numLimit=20;
          $scope.start = 0;
          $scope.$watch('allEvents',function(newVal){
            if(newVal){
             $scope.pages=Math.ceil($scope.allEvents.length/$scope.numLimit);

            }
          });
          $scope.hideNext=function(){
            if(($scope.start+ $scope.numLimit) < $scope.allEvents.length){
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
        else if (response.data.noData == 2) {
          $scope.allEventsStatus = "No events found !";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allEventsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allEventsStatus = "Error Occured";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allEventsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allEventsStatus = "Something's Wrong";
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allEventsStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadEvents();

  $scope.view_event_description = function (event_unique_id, title) {

    // let str = title;
    // let new_str = str.replace(/ /g, '-');
    // re_title = new_str.toLowerCase();

    // storage.setEventID(event_unique_id);

    // $location.path($location.protocol() + "://" + $location.host() + ":" + $location.port() + "/courtiers/event/" + event_unique_id + "/" + re_title);
    // window.open($location.protocol() + "://" + $location.host() + ":" + $location.port() + "/royal/event/" + event_unique_id + "/" + re_title);
    // window.open("https://hellobeautifulworld.net/event/" + event_unique_id + "/" + re_title);

    // $location.path($location.protocol() + "://" + $location.host() + ":" + $location.port() + "/courtiers/event/" + title);
    window.open($location.protocol() + "://" + $location.host() + ":" + $location.port() + "/royal/event/" + title);
    // window.open("https://hellobeautifulworld.net/event/" + title);

  };

  $scope.view_ticket_categories = function (unique_id, display_title, event_date_end) {

    // modalOpen('ticketCategoryModal', 'medium');

    $scope.display_title = display_title;

    $scope.event_date_end = event_date_end;

    $scope.ticketCategoryData = {
      "table":"ticketing",
      "event_unique_id":unique_id
    }

    $http.post("server/get_ticket_category.php", $scope.ticketCategoryData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allTicketCategoryStatus = undefined;
          $scope.allTicketCategory = response.data.re_data;
        }
        else if (response.data.noData == 2) {
          $scope.allTicketCategoryStatus = "No ticket category !";
          // $timeout(function () {
          //   $scope.allTicketCategoryStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allTicketCategoryStatus = "Error Occured";
          // $timeout(function () {
          //   $scope.allTicketCategoryStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allTicketCategoryStatus = "Something's Wrong";
        // $timeout(function () {
        //   $scope.allTicketCategoryStatus = undefined;
        // }, 3000)
      })

  };

  $scope.edit_ticket_category = function (event_unique_id, unique_id) {

    $scope.para_1 = function () {
      var result = '';
      var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
      var charactersLength = characters.length;

      for (var i = 0; i < 200; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
      }
      return result;
    };

    $timeout(function () {
      $location.path("ticket-category/" + event_unique_id + "/" + $scope.para_1() + "/" + unique_id + "/" + $scope.para_1());
    }, 500)

  };

  $scope.ticketPrice = function (event_unique_id, total_no_of_tickets) {

    $scope.para_1 = function () {
      var result = '';
      var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
      var charactersLength = characters.length;

      for (var i = 0; i < 200; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
      }
      return result;
    };

    $location.path('ticketing/' + event_unique_id + "/" + total_no_of_tickets + "/" + $scope.para_1());

  };

  $scope.delete_ticket_category = function (event_unique_id, unique_id) {

    // modalOpen('deleteTicketCategoryModal', 'medium');

    $scope.shouldIShow = true;

    $scope.unique_id = unique_id;

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.deleted = 0;

      $scope.confirmData = {
        "table":"ticketing",
        "edit_user_unique_id":$scope.result_u,
        "event_unique_id":event_unique_id,
        "unique_id":unique_id,
        "status":$scope.deleted
      }

      $http.post('server/delete_ticket_category.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "Ticket Category Deleted");

            $timeout(function () {
              $scope.showSuccessStatus = undefined;
              $scope.actionStatus = undefined;
              // $route.reload();
              window.location.reload(true);
            }, 3000)

          }
          else if (response.data.noData == 2) {
            $scope.showStatus = true;
            $scope.actionStatus = "No Data Found";
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

  $scope.editEvent = function (unique_id) {

    $scope.para_1 = function () {
      var result = '';
      var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
      var charactersLength = characters.length;

      for (var i = 0; i < 200; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
      }
      return result;
    };

    $location.path("edit-event/" + unique_id + "/" + $scope.para_1());
  };

  $scope.see_comments = function (unique_id, post_title) {

    // modalOpen('commentsModal', 'medium');

    $scope.title = post_title;

    $scope.comment_count = null;

    $scope.commentsData = {
      "post_unique_id":unique_id
    }

    $http.post('server/get_comments.php', $scope.commentsData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allCommentsStatus = undefined;
          $scope.allComments = response.data.re_data;
          $scope.comment_count = response.data.comment_count;

          $scope.currentPageComment=1;
          $scope.numLimitComment=5;
          $scope.startComment = 0;
          $scope.$watch('allComments',function(newVal){
            if(newVal){
             $scope.pagesComment=Math.ceil($scope.allComments.length/$scope.numLimit);

            }
          });
          $scope.commenthideNext=function(){
            if(($scope.startComment+ $scope.numLimitComment) < $scope.allComments.length){
              return false;
            }
            else
            return true;
          };
          $scope.commenthidePrev=function(){
            if($scope.startComment===0){
              return true;
            }
            else
            return false;
          };
          $scope.commentnextPage=function(){
            $scope.currentPageComment++;
            $scope.startComment=$scope.startComment+ $scope.numLimitComment;
          };
          $scope.commentPrevPage=function(){
            if($scope.currentPageComment>1){
              $scope.currentPageComment--;
            }
            $scope.startComment=$scope.startComment - $scope.numLimitComment;
          };
        }
        else if (response.data.noData == 2) {
          $scope.allCommentsStatus = "No comments found !";
          // $timeout(function () {
          //   $scope.allCommentsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allCommentsStatus = "Error Occured";
          // $timeout(function () {
          //   $scope.allCommentsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allCommentsStatus = "Something's Wrong";
        // $timeout(function () {
        //   $scope.allCommentsStatus = undefined;
        // }, 3000)
      })

  };

  $scope.removeCommentsModal = function () {
    $scope.allComments = undefined;
    $scope.comment_count = undefined;
    // modalClose('commentsModal');
  };

  $scope.delete_event = function (unique_id) {


    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.deleted = 0;

      $scope.confirmData = {
        "table":"events",
        "edit_user_unique_id":$scope.result_u,
        "unique_id":unique_id,
        "status":$scope.deleted
      }

      $http.post('server/delete_post.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "Event deleted");

            $timeout(function () {
              $scope.showSuccessStatus = undefined;
              $scope.actionStatus = undefined;
              // $route.reload();
              window.location.reload(true);
            }, 3000)

          }
          else if (response.data.noData == 2) {
            $scope.showStatus = true;
            $scope.actionStatus = "No Data Found";
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

  $scope.restore_event = function (unique_id) {


    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.restored = 1;

      $scope.confirmData = {
        "table":"events",
        "edit_user_unique_id":$scope.result_u,
        "unique_id":unique_id,
        "status":$scope.restored
      }

      $http.post('server/restore_post.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Add Activity", "Event restored");

            $timeout(function () {
              $scope.showSuccessStatus = undefined;
              $scope.actionStatus = undefined;
              window.location.reload(true);
              // $route.reload();
            }, 3000)

          }
          else if (response.data.noData == 2) {
            $scope.showStatus = true;
            $scope.actionStatus = "No Data Found";
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

  $scope.undraft_event = function (unique_id) {


    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.undraft = 0;

      $scope.confirmData = {
        "table":"events",
        "edit_user_unique_id":$scope.result_u,
        "unique_id":unique_id,
        "status":$scope.undraft
      }

      $http.post('server/undraft_post.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Add Activity", "Event published from drafts");

            $timeout(function () {
              $scope.showSuccessStatus = undefined;
              $scope.actionStatus = undefined;
              // $route.reload();
              window.location.reload(true);
            }, 3000)

          }
          else if (response.data.noData == 2) {
            $scope.showStatus = true;
            $scope.actionStatus = "No Data Found";
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

  $scope.delete_comment = function (unique_id, post_title, post_unique_id) {


    $scope.shouldIShow = true;

    $scope.unique_id = unique_id;

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.deleted = 0;

      $scope.confirmData = {
        "table":"comments",
        "unique_id":unique_id,
        "status":$scope.deleted
      }

      $http.post('server/delete_comment.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "Event comment deleted");

            $timeout(function () {
              $scope.showSuccessStatus = undefined;
              $scope.actionStatus = undefined;
              // window.location.reload(true);
              // modalClose('deleteModal');
              // modalClose('commentsModal');
              $timeout(function () {
                $scope.see_comments(post_unique_id, post_title);
                $scope.clickOnce = false;
                $scope.shouldIShow = false;
              }, 500)
            }, 3000)

          }
          else if (response.data.noData == 2) {
            $scope.showStatus = true;
            $scope.actionStatus = "No Data Found";
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

  $scope.restore_comment = function (unique_id, post_title, post_unique_id) {


    $scope.shouldIShow = true;

    $scope.unique_id = unique_id;

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.restored = 1;

      $scope.confirmData = {
        "table":"comments",
        "unique_id":unique_id,
        "status":$scope.restored
      }

      $http.post('server/restore_comment.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Add Activity", "Event comment restored");

            $timeout(function () {
              $scope.showSuccessStatus = undefined;
              $scope.actionStatus = undefined;
              // window.location.reload(true);
              // modalClose('restoreModal');
              // modalClose('commentsModal');
              $timeout(function () {
                $scope.see_comments(post_unique_id, post_title);
                $scope.clickOnce = false;
                $scope.shouldIShow = false;
              }, 500)
            }, 3000)

          }
          else if (response.data.noData == 2) {
            $scope.showStatus = true;
            $scope.actionStatus = "No Data Found";
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
