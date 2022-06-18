xnyder.controller('signinCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$window,storage,notify,update_one_account_details){

  $rootScope.pageTitle = "Sign in";

  // -------------------------------- Don't touch this code --------------------------------

  $scope.para_1 = function () {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;

    for (var i = 0; i < 4; i++) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
  };

  $scope.para_2 = function () {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;

    for (var i = 0; i < 4; i++) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
  };

  $scope.para_3 = function () {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;

    for (var i = 0; i < 4; i++) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
  };

  $scope.para_4 = function () {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;

    for (var i = 0; i < 4; i++) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
  };

  // ----------------------------------------------------------------------------------------

  $scope.user = {};

  $scope.user_details = xnyderAuth.userDetails.basic();

  $scope.welcome_name = $scope.user_details.fullname != null ? $scope.user_details.fullname : "One account";

  $scope.signIn = function () {

    $scope.userDetails = {
      "username":$scope.user.username,
      "password":$scope.password
    }

      $http.post('server/login.php', $scope.userDetails)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.things = undefined;
          $scope.details = response.data.username;
          $scope.details_2 = response.data.user_unique_id;
          $scope.details_3 = response.data.fullname;
          $scope.details_4 = response.data.user_role;
          $scope.details_5 = response.data.email;
          switch (response.data.loginWho) {
            case 1:
              $scope.showSuccessStatus = true;
              $scope.loginStatus = "Welcome Master ...";
              notify.do_notify($scope.details_2, "Login Activity", "User logged in successfully.");
              // update_one_account_details.do_update_one_account_details($scope.details_2, email, $scope.details_4, fullname, gender, mobile);

              $timeout(function () {

                storage.setAil($scope.details_5);
                $scope.new_u_id = $scope.para_3() + $scope.details_2 + $scope.para_2();
                storage.setName($scope.details_3);
                storage.setRole($scope.details_4);
                storage.set_U_u($scope.details_2);
                storage.setUsername($scope.details);
                storage.setUserImage("img/avatars/user.svg");
                $route.reload();
                $location.path('/');
              }, 3000)

              break;
            case 2:
              $scope.showSuccessStatus = true;
              $scope.loginStatus = "Welcome Administrator ...";
              notify.do_notify($scope.details_2, "Login Activity", "User logged in successfully.");
              // update_one_account_details.do_update_one_account_details($scope.details_2, email, $scope.details_4, fullname, gender, mobile);

              $timeout(function () {

                storage.setAil($scope.details_5);
                $scope.new_u_id = $scope.para_3() + $scope.details_2 + $scope.para_2();
                storage.setName($scope.details_3);
                storage.setRole($scope.details_4);
                storage.set_U_u($scope.details_2);
                storage.setUsername($scope.details);
                storage.setUserImage("img/avatars/user.svg");
                $route.reload();
                $location.path('/');
              }, 3000)

              break;
            case 3:
              $scope.showSuccessStatus = true;
              $scope.loginStatus = "Welcome Sub Administrator ...";
              notify.do_notify($scope.details_2, "Login Activity", "User logged in successfully.");
              // update_one_account_details.do_update_one_account_details($scope.details_2, email, $scope.details_4, fullname, gender, mobile);

              $timeout(function () {

                storage.setAil($scope.details_5);
                $scope.new_u_id = $scope.para_3() + $scope.details_2 + $scope.para_2();
                storage.setName($scope.details_3);
                storage.setRole($scope.details_4);
                storage.set_U_u($scope.details_2);
                storage.setUsername($scope.details);
                storage.setUserImage("img/avatars/user.svg");
                $route.reload();
                $location.path('/');
              }, 3000)

              break;
            default:
              $scope.showSuccessStatus = true;
              $scope.loginStatus = "Welcome User ...";
              notify.do_notify($scope.details_2, "Login Activity", "User logged in successfully.");
              // update_one_account_details.do_update_one_account_details($scope.details_2, email, $scope.details_4, fullname, gender, mobile);

              $timeout(function () {

                storage.setAil($scope.details_5);
                $scope.new_u_id = $scope.para_3() + $scope.details_2 + $scope.para_2();
                storage.setName($scope.details_3);
                storage.setRole($scope.details_4);
                storage.set_U_u($scope.details_2);
                storage.setUsername($scope.details);
                storage.setUserImage("img/avatars/user.svg");
                $route.reload();
                $location.path('/');
              }, 3000)
          }
        }
        else if (response.data.notVerified == 3) {
          $scope.things = true;
          // $scope.showStatus = true;
          $scope.loginStatus = "Incorrect Password";
          $scope.passwordStatus = $scope.loginStatus;
          $timeout(function () {
            $scope.things = undefined;
            // $scope.showStatus = undefined;
            $scope.passwordStatus = "";
            $scope.loginStatus = "";
          }, 3000)
        }
        else if (response.data.emailDoesNotExist == 2) {
          $scope.things = undefined;
          $scope.showStatus = true;
          $scope.loginStatus = "User Doesn't Exist";
          $timeout(function () {
            $scope.showStatus = undefined;
            $scope.loginStatus = "";
          }, 3000)
        }
        else if (response.data.accessStatus == 2) {
          $scope.things = undefined;
          $scope.showStatus = true;
          $scope.loginStatus = "Account Suspended Can't Sign In";
          $timeout(function () {
            $scope.showStatus = undefined;
            $scope.loginStatus = "";
          }, 3000)
        }
        else if (response.data.accessStatus == 3) {
          $scope.things = undefined;
          $scope.showStatus = true;
          $scope.loginStatus = "Account Revoked";
          $timeout(function () {
            $scope.showStatus = undefined;
            $scope.loginStatus = "";
          }, 3000)
        }
        else {
          $scope.things = undefined;
          $scope.showStatus = true;
          $scope.loginStatus = "Can't Sign In";
          $timeout(function () {
            $scope.showStatus = undefined;
            $scope.loginStatus = "";
          }, 3000)
        }
      }, function (error) {
        $scope.things = undefined;
        $scope.showStatus = true;
        $scope.loginStatus = "Something's Wrong !";
        $timeout(function () {
          $scope.showStatus = undefined;
          $scope.loginStatus = "";
        }, 3000)
      })

  };

  $scope.signInNow = function () {

    $scope.loadingStatus = "Verifying signin ...";

    $timeout(function () {
      if (xnyderAuth.isLoggedIn()) {

        var firstname = $scope.user_details.firstname;
        var lastname = $scope.user_details.lastname;
        var fullname = $scope.user_details.fullname;
        var email = $scope.user_details.emailAddress;
        var gender = $scope.user_details.gender;
        var dob = $scope.user_details.dob;
        var mobile = $scope.user_details.mobile;
        var profileImage = $scope.user_details.profileImageWebp;
        var business_name = $scope.user_details.business_name;
        var account_type = $scope.user_details.account_type;

        $scope.userDetails = {
          "email":email,
        }

        $http.post('server/login_one_account_user.php', $scope.userDetails)
        .then(function (response) {
          if (response.data.engineMessage == 1) {
            $scope.things = undefined;
            $scope.details = response.data.username;
            $scope.details_2 = response.data.user_unique_id;
            $scope.details_3 = response.data.fullname;
            $scope.details_4 = response.data.user_role;
            $scope.details_5 = response.data.email;
            switch (response.data.loginWho) {
              case 1:
                $scope.showSuccessStatus = true;
                $scope.loadingStatus = undefined;
                $scope.loginStatus = "Welcome ...";
                notify.do_notify($scope.details_2, "Login Activity", "User logged in successfully.");
                // update_one_account_details.do_update_one_account_details($scope.details_2, email, $scope.details_4, fullname, gender, mobile);

                $timeout(function () {

                  storage.setAil($scope.details_5);
                  $scope.new_u_id = $scope.para_3() + $scope.details_2 + $scope.para_2();
                  storage.setName(fullname);
                  storage.setRole($scope.details_4);
                  storage.set_U_u($scope.details_2);
                  storage.setUsername($scope.details);
                  storage.setUserImage(profileImage);
                  $route.reload();
                  $location.path('/');
                }, 3000)

                break;
              case 2:
                $scope.showSuccessStatus = true;
                $scope.loadingStatus = undefined;
                $scope.loginStatus = "Welcome Admin ...";
                notify.do_notify($scope.details_2, "Login Activity", "User logged in successfully.");
                // update_one_account_details.do_update_one_account_details($scope.details_2, email, $scope.details_4, fullname, gender, mobile);

                $timeout(function () {

                  storage.setAil($scope.details_5);
                  $scope.new_u_id = $scope.para_3() + $scope.details_2 + $scope.para_2();
                  storage.setName(fullname);
                  storage.setRole($scope.details_4);
                  storage.set_U_u($scope.details_2);
                  storage.setUsername($scope.details);
                  storage.setUserImage(profileImage);
                  $route.reload();
                  $location.path('/');
                }, 3000)

                break;
              case 3:
                $scope.showSuccessStatus = true;
                $scope.loadingStatus = undefined;
                $scope.loginStatus = "Welcome Sub Administrator ...";
                notify.do_notify($scope.details_2, "Login Activity", "User logged in successfully.");
                // update_one_account_details.do_update_one_account_details($scope.details_2, email, $scope.details_4, fullname, gender, mobile);

                $timeout(function () {

                  storage.setAil($scope.details_5);
                  $scope.new_u_id = $scope.para_3() + $scope.details_2 + $scope.para_2();
                  storage.setName(fullname);
                  storage.setRole($scope.details_4);
                  storage.set_U_u($scope.details_2);
                  storage.setUsername($scope.details);
                  storage.setUserImage(profileImage);
                  $route.reload();
                  $location.path('/');
                }, 3000)

                break;
              default:
                $scope.showSuccessStatus = true;
                $scope.loadingStatus = undefined;
                $scope.loginStatus = "Welcome User ...";
                notify.do_notify($scope.details_2, "Login Activity", "User logged in successfully.");
                // update_one_account_details.do_update_one_account_details($scope.details_2, email, $scope.details_4, fullname, gender, mobile);

                $timeout(function () {

                  storage.setAil($scope.details_5);
                  $scope.new_u_id = $scope.para_3() + $scope.details_2 + $scope.para_2();
                  storage.setName(fullname);
                  storage.setRole($scope.details_4);
                  storage.set_U_u($scope.details_2);
                  storage.setUsername($scope.details);
                  storage.setUserImage(profileImage);
                  $route.reload();
                  $location.path('/');
                }, 3000)
            }
          }
          else if (response.data.notVerified == 3) {
            $scope.things = true;
            $scope.loadingStatus = undefined;
            // $scope.showStatus = true;
            $scope.loginStatus = "Incorrect Password";
            $scope.passwordStatus = $scope.loginStatus;
            $timeout(function () {
              $scope.things = undefined;
              // $scope.showStatus = undefined;
              $scope.passwordStatus = "";
              $scope.loginStatus = "";
            }, 3000)
          }
          else if (response.data.emailDoesNotExist == 2) {
            $scope.things = undefined;
            $scope.loadingStatus = undefined;
            $scope.showStatus = true;
            $scope.loginStatus = "User Doesn't Exist";
            $timeout(function () {
              $scope.showStatus = undefined;
              $scope.loginStatus = "";
            }, 3000)
          }
          else if (response.data.accessStatus == 2) {
            $scope.things = undefined;
            $scope.loadingStatus = undefined;
            $scope.showStatus = true;
            $scope.loginStatus = "Account Suspended Can't Sign In";
            $timeout(function () {
              $scope.showStatus = undefined;
              $scope.loginStatus = "";
            }, 3000)
          }
          else if (response.data.accessStatus == 3) {
            $scope.things = undefined;
            $scope.loadingStatus = undefined;
            $scope.showStatus = true;
            $scope.loginStatus = "Account Revoked";
            $timeout(function () {
              $scope.showStatus = undefined;
              $scope.loginStatus = "";
            }, 3000)
          }
          else {
            $scope.things = undefined;
            $scope.loadingStatus = undefined;
            $scope.showStatus = true;
            $scope.loginStatus = "Can't Sign In";
            $timeout(function () {
              $scope.showStatus = undefined;
              $scope.loginStatus = "";
            }, 3000)
          }
        }, function (error) {
          $scope.things = undefined;
          $scope.loadingStatus = undefined;
          $scope.showStatus = true;
          $scope.loginStatus = "Something's Wrong !";
          $timeout(function () {
            $scope.showStatus = undefined;
            $scope.loginStatus = "";
          }, 3000)
        })

      }
      else {
        $scope.things = undefined;
        $scope.loadingStatus = undefined;
        $scope.showStatus = true;
        $scope.loginStatus = "Login to One Account ...";
        $scope.welcome_name = "One account";
        $timeout(function () {
          $scope.showStatus = undefined;
          $scope.loginStatus = "";
        }, 5000)
      }
    }, 1000)

  };

  var platform_token = "v2Dh8V031iHJ24iS2hJ41KcnyUAfW0InrECY4gw2gRxY4x1gYYgnMyXwFMjBdLU21vDQYV2WFSbIkuSDHRhQ5nbso6EOXOLowpBOrx5YWV91QvfZrNzSFbBsQeyqg8aA73xpTo9MZiieEonfEyRMDu008tM0WGyPy2YAuUHtvA6VkaZJo7Lb39v0bApNbUaNn6GzedFZdnN5h4DsCdIpjE1HIt2gIgEa3TcFToji1B32kUklt5U7c1RhGRXORqt";
  // var redirectedUrl = "https://organize.xnyder.com/ibank/signin";
  var redirectedUrl = "http://localhost:8080/cerotics_store/jomodel/office/signin";
  var go_to_url = "https://www.myaccount.xnyder.com/auth/" + platform_token + "?request_url=" + redirectedUrl;

  $scope.signInAlt = function () {
    $window.open(go_to_url, "_self");
  };

});
