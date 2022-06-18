xnyder.controller('resetCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$window,storage,notify){

  $rootScope.pageTitle = "Reset password";

  $scope.reset = {};

  $scope.reset_email_password = function () {

    $scope.resetData = {
      "email":$scope.reset.email
    }

    $http.post('server/check_email.php', $scope.resetData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $rootScope.fullname = response.data.userName;

          $http.post('server/update_email_password.php', $scope.resetData)
            .then(function (response) {
              if (response.data.engineMessage == 1) {
                $scope.new_password = response.data.new_password;

                $scope.subject = "Password Reset";

                $scope.description = "<h2>Hello " + $rootScope.fullname + ",</h2><br> Your new password is " + "<h3 style='color: blue'> " + $scope.new_password + " </h3>" + " copy and paste it in the login portal.<br>You can login into your account now.<br><h3>Thanks for choosing <a href='https://www.hellobeautifulworld.net'>Hello Beautiful World</a></h3>";

                $scope.requestData = {
                   "fullname":$rootScope.fullname,
                   "email":$scope.reset.email,
                   "subject":$scope.subject,
                   "message":$scope.description
                 }

                 $http.post('server/mysendadminmail.php', $scope.requestData)
                  .then(function success(response) {
                    if (response.data.engineMessage == 1) {
                      $scope.showSuccessStatus = true;
                      $scope.confirmStatus = "Request Sent";
                      $timeout(function() {
                        $scope.showSuccessStatus = undefined;
                        $scope.confirmStatus = "";
                        $route.reload();
                      }, 3000)
                    }
                    else if (response.data.error == 2) {
                      $scope.showStatus = true;
                      $scope.resetStatus = "Error Occured";
                      $timeout(function() {
                        $scope.showStatus = undefined;
                        $scope.resetStatus = "";
                        $route.reload();
                      }, 3000)
                    }
                  }, function error(response) {
                    $scope.showStatus = true;
                    $scope.resetStatus = "Something's Wrong";
                    $timeout(function() {
                      $scope.showStatus = undefined;
                      $scope.resetStatus = "";
                      $route.reload();
                    }, 3000)
                  })

                $scope.showSuccessStatus = true;
                $scope.resetStatus = "Email Sent";
                $timeout(function() {
                  $scope.showSuccessStatus = undefined;
                  $scope.resetStatus = "";
                  $route.reload();
                  $location.path('/signin');
                }, 3000)

              }
              else {
                $scope.showStatus = true;
                $scope.resetStatus = "An Error Occured";
                $timeout(function() {
                  $scope.showStatus = undefined;
                  $scope.resetStatus = "";
                }, 3000)
              }
            }, function (error) {
              $scope.showStatus = true;
              $scope.resetStatus = "Something's Wrong";
              $timeout(function() {
                $scope.showStatus = undefined;
                $scope.resetStatus = "";
              }, 3000)
            })

        }
        else if (response.data.noEmail == 2) {
          $scope.showStatus = true;
          $scope.resetStatus = "Email Doesn't Exist";
          $timeout(function() {
            $scope.showStatus = undefined;
            $scope.resetStatus = "";
          }, 3000)
        }
        else {
          $scope.showStatus = true;
          $scope.resetStatus = "An Error Occured";
          $timeout(function() {
            $scope.showStatus = undefined;
            $scope.resetStatus = "";
          }, 3000)
        }
      }, function (error) {
        $scope.showStatus = true;
        $scope.resetStatus = "Something's Wrong";
        $timeout(function() {
          $scope.showStatus = undefined;
          $scope.resetStatus = "";
        }, 3000)
      })

  };

});
