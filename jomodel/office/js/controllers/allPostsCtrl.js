xnyder.controller('all-postsCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$window,storage,notify){

  $rootScope.pageTitle = "All Posts";

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
    $scope.loadPosts();
  };

  $scope.filterShow = false;

//   let str = "This is a good user's news item";
// let new_str = str.replace(/ /g, '-');
// new_str.toLowerCase();

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
        "table":"blog_posts",
        "startdate":$scope.filter_var.startdate,
        "enddate":$scope.filter_var.enddate
      }

      $http.post('server/filter_posts.php', $scope.filter_data)
        .then(function (response) {
          if (response.data.engineMessage == 1) {
            $scope.allPostsStatus = undefined;
            $scope.allPosts = response.data.filteredData;

            $scope.remove_loader();

            $scope.currentPage=1;
            $scope.numLimit=20;
            $scope.start = 0;
            $scope.$watch('allPosts',function(newVal){
              if(newVal){
               $scope.pages=Math.ceil($scope.allPosts.length/$scope.numLimit);

              }
            });
            $scope.hideNext=function(){
              if(($scope.start+ $scope.numLimit) < $scope.allPosts.length){
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
            $scope.allPostsStatus = "No data in range !";
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allPostsStatus = undefined;
            // }, 3000)
          }
          else {
            $scope.allPostsStatus = "Couldn't get data";
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.allPostsStatus = undefined;
            // }, 3000)
          }
        }, function (error) {
          $scope.allPostsStatus = "Something's Wrong";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allPostsStatus = undefined;
          // }, 3000)
        })
    }
  };

  $scope.loadPosts = function () {

    $http.get('server/get_posts.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.allPostsStatus = undefined;
          $scope.allPosts = response.data.re_data;

          $scope.remove_loader();

          $scope.currentPage=1;
          $scope.numLimit=20;
          $scope.start = 0;
          $scope.$watch('allPosts',function(newVal){
            if(newVal){
             $scope.pages=Math.ceil($scope.allPosts.length/$scope.numLimit);

            }
          });
          $scope.hideNext=function(){
            if(($scope.start+ $scope.numLimit) < $scope.allPosts.length){
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
          $scope.allPostsStatus = "No posts found !";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allPostsStatus = undefined;
          // }, 3000)
        }
        else {
          $scope.allPostsStatus = "Error Occured";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.allPostsStatus = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.allPostsStatus = "Something's Wrong";
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.allPostsStatus = undefined;
        // }, 3000)
      })

  };

  $scope.loadPosts();

  $scope.view_post = function (unique_id, category_unique_id, title) {

    // let str = title;
    // let new_str = str.replace(/ /g, '-');
    // re_title = new_str.toLowerCase();

    storage.setPostID(unique_id);
    storage.setPostCat(category_unique_id);

    // $location.path($location.protocol() + "://" + $location.host() + ":" + $location.port() + "/courtiers/blog/view-post/" + unique_id + "/" + re_title);
    // window.open($location.protocol() + "://" + $location.host() + ":" + $location.port() + "/royal/blog/view-post/" + unique_id + "/" + re_title);
    // window.open("https://hellobeautifulworld.net/blog/view-post/" + unique_id + "/" + re_title);

    // $location.path($location.protocol() + "://" + $location.host() + ":" + $location.port() + "/courtiers/blog/view-post/" + title);
    window.open($location.protocol() + "://" + $location.host() + ":" + $location.port() + "/royal/blog/view-post/" + title);
    // window.open("https://hellobeautifulworld.net/blog/view-post/" + title);

  };

  $scope.edit_post = function (unique_id) {

    $scope.para_1 = function () {
      var result = '';
      var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
      var charactersLength = characters.length;

      for (var i = 0; i < 200; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
      }
      return result;
    };

    $location.path("edit-post/" + unique_id + "/" + $scope.para_1());
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

  // $scope.removeDeleteModal = function () {
  //   modalClose('deleteModal');
  // };
  //
  // $scope.removeRestoreModal = function () {
  //   modalClose('restoreModal');
  // };

  $scope.delete_post = function (unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.deleted = 0;

      $scope.confirmData = {
        "table":"blog_posts",
        "edit_user_unique_id":$scope.result_u,
        "unique_id":unique_id,
        "status":$scope.deleted
      }

      $http.post('server/delete_post.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Delete Activity", "Blog post deleted");

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

  $scope.restore_post = function (unique_id) {

    // modalOpen('restoreModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.restored = 1;

      $scope.confirmData = {
        "table":"blog_posts",
        "edit_user_unique_id":$scope.result_u,
        "unique_id":unique_id,
        "status":$scope.restored
      }

      $http.post('server/restore_post.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Add Activity", "Blog post restored");

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

  $scope.undraft_post = function (unique_id) {

    // modalOpen('deleteModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.undraft = 0;

      $scope.confirmData = {
        "table":"blog_posts",
        "edit_user_unique_id":$scope.result_u,
        "unique_id":unique_id,
        "status":$scope.undraft
      }

      $http.post('server/undraft_post.php', $scope.confirmData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {

            $scope.showSuccessStatus = true;
            $scope.actionStatus = "Action Completed";
            notify.do_notify($scope.result_u, "Add Activity", "Blog post published from drafts");

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

    // modalOpen('deleteModal', 'medium');

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
            notify.do_notify($scope.result_u, "Delete Activity", "Post comment deleted");

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

    // modalOpen('restoreModal', 'medium');

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
            notify.do_notify($scope.result_u, "Add Activity", "Post comment restored");

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
