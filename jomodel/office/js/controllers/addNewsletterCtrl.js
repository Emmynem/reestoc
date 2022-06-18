xnyder.controller('add-newsletterCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$window,$sanitize,storage,notify){

  $rootScope.pageTitle = "Send Emails";

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

  $scope.remove_loader();

  $scope.options = {
    height:300,
    toolbar: [

      ['edit',['undo','redo']],
      ['style', ['bold', 'italic', 'underline', 'clear']],
      ['font', ['strikethrough', 'superscript', 'subscript']],
      ['fontsize', ['fontsize']],
      ['para', ['ul', 'ol']],
      ['insert', ['link', 'picture','video']],
      ['view', ['fullscreen', 'codeview']]

    ],
    callbacks: {
      onImageUpload: function(files) {
        var data = new FormData();
        data.append("file", files[0]);
        $.ajax({
          data: data,
          type: "POST",
          url: "server/summernoteimg.php",
          cache: false,
          contentType: false,
          processData: false,
          success: function(url) {
            $('.summernote').summernote('editor.insertImage', url);
          },
          error:function(data) {
            console.log(data);
          }
        });
      }
    }
  };

  $scope.bulk_mail = {};

  $scope.sendEmailNow = function () {

    $scope.shouldIShow = true;

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.emailData = {
        "fullname":"No Reply",
        "username":$scope.bulk_mail.display_name,
        "email":"email",
        "subject":$scope.bulk_mail.subject,
        "message":$scope.bulk_mail.email_details
      }

      $http.get('server/get_newsletter_emails.php')
        .then(function (response) {
          if (response.data.engineMessage == 1) {
            $scope.allNewsletterEmailStatus = undefined;
            $scope.allNewsletterEmail = response.data.re_data;

            $scope.newArr = [];
            angular.forEach($scope.allNewsletterEmail, function(item, index) {
                $scope.newArr.push(item[0]);
            });

            $scope.allNewsletterEmails = [...new Set($scope.newArr)];

            $scope.showProgressStatus = "Sending mail, please be patient ...";

            $scope.counter = 0;

            $scope.timer = (3 * $scope.allNewsletterEmails.length) * 1000;

            $scope.timer_simplified = function (time) {

              var the_time = time / 1000;

              if (the_time >= 60 && the_time < 3600) {
                var minute = the_time / 60;
                var new_minute = Math.floor(minute);
                var divided = the_time % 60;

                return new_minute + " min(s) " + divided + " sec(s) remaining ...";
              }
              else if(the_time >= 3600){
                var hour = (the_time / 60) / 60;
                var minute = (the_time / 60) % 60;
                var new_hour = Math.floor(hour);
                var new_minute = Math.floor(minute);
                var divided = the_time % 60;

                return new_hour + " hour(s) " + new_minute + " min(s) " + divided + " sec(s) remaining ...";
              }
              else {
                return the_time + " secs remaining ...";
              }

            };

            $scope.send_each_mail = function () {

              $scope.new_time = $scope.timer_simplified($scope.timer);

              // console.log("Time is : " + $scope.new_time);
              $scope.timeRemaining = "" + $scope.new_time;
              $timeout(function () {

                $scope.emailData.email = $scope.allNewsletterEmails[$scope.counter];
                $scope.timer = $scope.timer - 3000;
                console.log($scope.emailData);

                if ($scope.timer == 0) {
                  // console.log("Time is : " + "0 secs remaining");
                  $scope.timeRemaining = "0 secs remaining";

                }

                $scope.counter++;

                if ($scope.counter < $scope.allNewsletterEmails.length) {
                  $scope.send_each_mail();
                }
                else {
                  $scope.shouldIShow = false;
                  $scope.showProgressStatus = undefined;
                  $scope.showStatus = undefined;
                  $scope.timeRemaining = undefined;
                  $scope.showSuccessStatus = "Emails sent successfully";
                  notify.do_notify($scope.result_u, "Add Activity", "Newsletter Sent");

                  $timeout(function () {
                    $scope.bulk_mail = null;
                    $scope.emailData = null;
                    $scope.shouldIShow = undefined;
                    $scope.clickOnce = false;
                    $scope.showSuccessStatus = undefined;
                    // modalClose('sendEmailsModal');
                  }, 3000)
                }

              }, 3000)

            };

            $scope.send_each_mail();

          }
          else {
            $scope.showStatus = "Error Occured";
            $timeout(function () {
              $scope.showStatus = undefined;
              $scope.showSuccessStatus = undefined;
            }, 3000)
          }
        }, function (error) {
          $scope.showStatus = "Something's Wrong";
          $timeout(function () {
            $scope.showStatus = undefined;
            $scope.showSuccessStatus = undefined;
          }, 3000)
        })

    };

    $scope.removeConfirmModal = function () {

      $scope.shouldIShow = undefined;
      $scope.clickOnce = false;

    };

  };

});
