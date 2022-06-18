xnyder.controller('shopCtrl',function($scope,$rootScope,$http,$timeout,$location,$route,$window,storage,notify){

  $rootScope.pageTitle = "Shop";

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

  $scope.options = {
    height:250,
    toolbar: [

      ['edit',['undo','redo']],
      ['style', ['bold', 'italic', 'underline', 'clear']],
      ['insert', ['link']],
      ['view', ['fullscreen']]

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

      $scope.filter_data = {
        "table":"shop",
        "startdate":$scope.filter_var.startdate,
        "enddate":$scope.filter_var.enddate
      }

      $http.post('server/filter_shop.php', $scope.filter_data)
        .then(function (response) {
          if (response.data.engineMessage == 1) {
            $scope.if_product_exists = undefined;
            $scope.allProducts = response.data.filteredData;
            $scope.total_views = response.data.total_views;

            $scope.remove_loader();

            $scope.currentPage=1;
            $scope.numLimit=10;
            $scope.start = 0;
            $scope.$watch('allProducts',function(newVal){
              if(newVal){
               $scope.pages=Math.ceil($scope.allProducts.length/$scope.numLimit);

              }
            });
            $scope.hideNext=function(){
              if(($scope.start+ $scope.numLimit) < $scope.allProducts.length){
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
            $scope.if_product_exists = "No data in range !";
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.if_product_exists = undefined;
            // }, 3000)
          }
          else {
            $scope.if_product_exists = "Couldn't get data !";
            $scope.remove_loader();
            // $timeout(function () {
            //   $scope.if_product_exists = undefined;
            // }, 3000)
          }
        }, function (error) {
          $scope.if_product_exists = "Something's Wrong!";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.if_product_exists = undefined;
          // }, 3000)
        })
    }
  };

  $scope.load = function () {


    $http.get('server/get_shop.php')
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          $scope.if_product_exists = undefined;
          $scope.allProducts = response.data.re_data;
          $scope.total_views = response.data.total_views;

          $scope.remove_loader();

          $scope.currentPage=1;
          $scope.numLimit=10;
          $scope.start = 0;
          $scope.$watch('allProducts',function(newVal){
            if(newVal){
             $scope.pages=Math.ceil($scope.allProducts.length/$scope.numLimit);

            }
          });
          $scope.hideNext=function(){
            if(($scope.start+ $scope.numLimit) < $scope.allProducts.length){
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
          $scope.if_product_exists = "No products found !";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.if_product_exists = undefined;
          // }, 3000)
        }
        else {
          $scope.if_product_exists = "Couldn't fetch products !";
          $scope.remove_loader();
          // $timeout(function () {
          //   $scope.if_product_exists = undefined;
          // }, 3000)
        }
      }, function (error) {
        $scope.if_product_exists = "Something's Wrong!";
        $scope.remove_loader();
        // $timeout(function () {
        //   $scope.if_product_exists = undefined;
        // }, 3000)
      })

  };

  $scope.load();

  $scope.product = {};

  $scope.edit_product = function (unique_id) {

    $scope.para_1 = function () {
      var result = '';
      var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
      var charactersLength = characters.length;

      for (var i = 0; i < 200; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
      }
      return result;
    };

    $location.path("edit-product/" + unique_id + "/" + $scope.para_1());
  };

  $scope.viewProductDescription = function (product_name, product_description) {

    $scope.the_product_name = product_name;
    $scope.the_product_description = product_description;

  };

  $scope.showLink = function (title, unique_id) {

    $scope.addViewData = {
      "unique_id":unique_id
    }

    $http.post('server/add_product_view.php', $scope.addViewData)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          console.log("ye view");
        }
        else {
          console.log("ney view");
        }
      }, function (error) {
        console.log("big ney view");
      })

    // let str = title;
    // let new_str = str.replace(/ /g, '-');
    // let new_str = str.replace(/[ +#(),]/g, '-');
    // re_title = new_str.toLowerCase();

    // $location.path($location.protocol() + "://" + $location.host() + ":" + $location.port() + "/royal/blog/view-post/" + unique_id + "/" + title);
    // window.open($location.protocol() + "://" + $location.host() + ":" + $location.port() + "/royal/shop/item/" + unique_id + "/" + title);
    // window.open("https://hellobeautifulworld.net/shop/item/" + unique_id + "/" + title);

    // $location.path($location.protocol() + "://" + $location.host() + ":" + $location.port() + "/royal/blog/view-post/" + title);
    window.open($location.protocol() + "://" + $location.host() + ":" + $location.port() + "/royal/shop/item/" + title);
    // window.open("https://hellobeautifulworld.net/shop/item/" + title);

  };

  $scope.deleteProduct = function (unique_id, product_image) {

    // modalOpen('deleteProductModal', 'medium');

    $scope.continue_action = function () {

      $scope.clickOnce = true;

      $scope.deleteData = {
        "unique_id":unique_id,
        "product_image":product_image
      }

      $http.post('server/delete_shop.php', $scope.deleteData)
        .then(function (response) {
          if (response.data.engineMessage == 1) {
            $scope.showSuccessStatus = "Product Deleted";
            notify.do_notify($scope.result_u, "Delete Activity", "Product Deleted");
            $timeout(function () {
              $scope.showStatus = undefined;
              $scope.showSuccessStatus = undefined;
              $rootScope.imageStatus = undefined;
              window.location.reload(true);
              $scope.load();
            }, 3000)
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

  };

});
