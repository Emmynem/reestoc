var xnyder = angular.module('access',["ngRoute","ngCookies","ngMessages", "ngClipboard","ngSanitize","summernote"]);

// Routing starts
xnyder.config(function($routeProvider,$locationProvider){
 $routeProvider
 .when('/',{
   templateUrl:'dashboard.html',
   controller:'dashboardCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/dashboard',{
   templateUrl:'dashboard.html',
   controller:'dashboardCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/categories',{
   templateUrl:'categories.html',
   controller:'categoriesCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/add-navigation',{
   templateUrl:'add-navigation.html',
   controller:'add-navigationCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/all-navigations',{
   templateUrl:'all-navigations.html',
   controller:'all-navigationsCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/product-categories',{
   templateUrl:'product-categories.html',
   controller:'product-categoriesCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/product-mini-categories',{
   templateUrl:'product-mini-categories.html',
   controller:'product-mini-categoriesCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/add-mini-category-image/:mini_category_unique_id',{
   templateUrl:'add-mini-category-image.html',
   controller:'add-mini-category-imageCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/edit-mini-category-image/:mini_category_unique_id/:mini_category_image_unique_id',{
   templateUrl:'edit-mini-category-image.html',
   controller:'edit-mini-category-imageCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/product-sub-categories',{
   templateUrl:'product-sub-categories.html',
   controller:'product-sub-categoriesCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/add-sub-category-image/:sub_category_unique_id',{
   templateUrl:'add-sub-category-image.html',
   controller:'add-sub-category-imageCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/edit-sub-category-image/:sub_category_unique_id/:sub_category_image_unique_id',{
   templateUrl:'edit-sub-category-image.html',
   controller:'edit-sub-category-imageCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/products',{
   templateUrl:'products.html',
   controller:'productsCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/sub-products',{
   templateUrl:'sub-products.html',
   controller:'sub-productsCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/add-product-image/:product_unique_id',{
   templateUrl:'add-product-image.html',
   controller:'add-product-imageCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/edit-product-image/:product_unique_id/:product_image_unique_id',{
   templateUrl:'edit-product-image.html',
   controller:'edit-product-imageCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/add-sub-product-image/:sub_product_unique_id',{
   templateUrl:'add-sub-product-image.html',
   controller:'add-sub-product-imageCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/edit-sub-product-image/:sub_product_unique_id/:sub_product_image_unique_id',{
   templateUrl:'edit-sub-product-image.html',
   controller:'edit-sub-product-imageCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/brands',{
   templateUrl:'brands.html',
   controller:'brandsCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/add-brand-image/:brand_unique_id',{
   templateUrl:'add-brand-image.html',
   controller:'add-brand-imageCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/edit-brand-image/:brand_unique_id/:brand_image_unique_id',{
   templateUrl:'edit-brand-image.html',
   controller:'edit-brand-imageCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/carts',{
   templateUrl:'carts.html',
   controller:'cartsCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/orders',{
   templateUrl:'orders.html',
   controller:'ordersCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/shipments',{
   templateUrl:'shipments.html',
   controller:'shipmentsCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/add-rider',{
   templateUrl:'add-rider.html',
   controller:'add-riderCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/riders',{
   templateUrl:'riders.html',
   controller:'ridersCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/offered-services-categories',{
   templateUrl:'offered-services-categories.html',
   controller:'offered-services-categoriesCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/offered-services',{
   templateUrl:'offered-services.html',
   controller:'offered-servicesCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/shipping-fees',{
   templateUrl:'shipping-fees.html',
   controller:'shipping-feesCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/add-management-user',{
   templateUrl:'add-management-user.html',
   controller:'add-management-userCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/management-users',{
   templateUrl:'management-users.html',
   controller:'management-usersCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/add-agent',{
   templateUrl:'add-agent.html',
   controller:'add-agentCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/agents',{
   templateUrl:'agents.html',
   controller:'agentsCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/add-user',{
   templateUrl:'add-user.html',
   controller:'add-userCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/users',{
   templateUrl:'users.html',
   controller:'usersCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/coupons',{
   templateUrl:'coupons.html',
   controller:'couponsCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/flash-deals',{
   templateUrl:'flash-deals.html',
   controller:'flash-dealsCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/pop-up-deals',{
   templateUrl:'pop-up-deals.html',
   controller:'pop-up-dealsCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/sharing',{
   templateUrl:'sharing.html',
   controller:'sharingCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/add-sharing-image/:sharing_unique_id',{
   templateUrl:'add-sharing-image.html',
   controller:'add-sharing-imageCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/edit-sharing-image/:sharing_unique_id/:sharing_image_unique_id',{
   templateUrl:'edit-sharing-image.html',
   controller:'edit-sharing-imageCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/pickup-locations',{
   templateUrl:'default-pickup-locations.html',
   controller:'default-pickup-locationsCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/add-post',{
   templateUrl:'add-post.html',
   controller:'add-postCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/edit-post/:unique_id/:extra',{
   templateUrl:'edit-post.html',
   controller:'edit-postCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/ticket-category/:event_unique_id/:extra/:unique_id/:extra_2',{
   templateUrl:'edit-ticket-category.html',
   controller:'edit-ticket-categoryCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/all-posts',{
   templateUrl:'all-posts.html',
   controller:'all-postsCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/blog-images',{
   templateUrl:'images.html',
   controller:'imagesCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/checkers',{
   templateUrl:'checkers.html',
   controller:'checkersCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/add-checker',{
   templateUrl:'add-checker.html',
   controller:'add-checkerCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/enquiries',{
   templateUrl:'enquiries.html',
   controller:'enquiriesCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/newsletter',{
   templateUrl:'newsletter.html',
   controller:'newsletterCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/add-newsletter',{
   templateUrl:'add-newsletter.html',
   controller:'add-newsletterCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/events',{
   templateUrl:'events.html',
   controller:'eventsCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/add-event',{
   templateUrl:'add-event.html',
   controller:'add-eventCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/edit-event/:unique_id/:extra',{
   templateUrl:'edit-event.html',
   controller:'edit-eventCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/file-manager',{
   templateUrl:'file-manager.html',
   controller:'file-managerCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/shop',{
   templateUrl:'shop.html',
   controller:'shopCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/add-product',{
   templateUrl:'add-product.html',
   controller:'add-productCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/edit-product/:unique_id/:extra',{
   templateUrl:'edit-product.html',
   controller:'edit-productCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/ticketing/:event_unique_id/:total_no_of_tickets/:extra',{
   templateUrl:'ticketing.html',
   controller:'ticketingCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/account',{
   templateUrl:'account.html',
   controller:'accountCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/gallery',{
   templateUrl:'gallery.html',
   controller:'galleryCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/profile',{
   templateUrl:'profile.html',
   controller:'profileCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/notifications',{
   templateUrl:'notifications.html',
   controller:'notificationsCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/reset',{
   templateUrl:'reset.html',
   controller:'resetCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.getAil() != undefined && storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/settings',{
   templateUrl:'settings.html',
   controller:'settingsCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() === null || storage.getAuth() === null){
         $location.path('/signin');
       }
     }
   }
 })
 .when('/signin',{
   templateUrl:'signin.html',
   controller:'signinCtrl',
   resolve:{
     "check":function(storage,$location){
       if(storage.get_U_u() != null && storage.getAuth() !== null){
         $location.path('/overview');
       }
     }
   }
 })
 .otherwise({
   redirectTo:'/'
 })
 $locationProvider.hashPrefix('');
 $locationProvider.html5Mode(true);
});
 // Routing ends

 // creating storage service for userdata starts
xnyder.service('storage',function($cookies,$window,$http,$location){
     var u_u = "Reestoc-Management_UJKDJNKD";
     var ail = "Reestoc-Management_MSLSKDJK";
     var ame = "Reestoc-Management_NDKNDSDJ";
     var role = "Reestoc-Management_RSKDNKSNDS";
     var username = "Reestoc-Management_USERLJDNJO";
     var post_id = "Reestoc-Management_POST_ID";
     var post_cat = "Reestoc-Management_POST_CAT";
     var user_image = "Reestoc-Management_USER_IMAGE";
     this.setAil = function(obj){
       $window.localStorage.setItem(ail,obj);
     }
     this.getAil = function(){
       return $window.localStorage.getItem(ail);
     }
     this.setName = function(obj){
       $window.localStorage.setItem(ame,obj);
     }
     this.getName = function(){
       return $window.localStorage.getItem(ame);
     }
     this.set_U_u = function (obj) {
       $window.localStorage.setItem(u_u,obj);
     }
     this.get_U_u = function (obj) {
       return $window.localStorage.getItem(u_u);
     }
     this.setRole = function(obj){
       $window.localStorage.setItem(role,obj);
     }
     this.getRole = function(){
       return $window.localStorage.getItem(role);
     }
     this.setUsername = function(obj){
       $window.localStorage.setItem(username,obj);
     }
     this.getUsername = function(){
       return $window.localStorage.getItem(username);
     }
     this.setPostID = function(obj){
       $window.localStorage.setItem(post_id,obj);
     }
     this.getPostID = function(){
       return $window.localStorage.getItem(post_id);
     }
     this.setPostCat = function(obj){
       $window.localStorage.setItem(post_cat,obj);
     }
     this.getPostCat = function(){
       return $window.localStorage.getItem(post_cat);
     }
     this.setUserImage = function(obj){
       $window.localStorage.setItem(user_image,obj);
     }
     this.getUserImage = function(){
       return $window.localStorage.getItem(user_image);
     }

     this.getAuth = function () {
       if (xnyderAuth.isLoggedIn() && ($window.localStorage.getItem(ail) == xnyderAuth.userDetails.emailAddress())) {
         return "Logged In";
       }
       else {
         return null;
       }
     }

     this.showProducts = function () {
       xnyderAuth.grid();
     }

     this.exit = function(){
       $window.localStorage.removeItem(ail);
       $window.localStorage.removeItem(u_u);
       $window.localStorage.removeItem(ame);
       $window.localStorage.removeItem(role);
       $window.localStorage.removeItem(username);
       $window.localStorage.removeItem(post_id);
       $window.localStorage.removeItem(post_cat);
       $window.localStorage.removeItem(user_image);
       $location.path('/signin');
     }
   });

xnyder.service('notify', function ($http) {
 this.do_notify = function (user_unique_id, type, action) {
   var message;

   var notify_data = {
     "user_unique_id":user_unique_id,
     "type":type,
     "action":action
   }

   $http.post('server/notify.php', notify_data)
     .then(function (response) {
       if (response.data.engineMessage == 1) {
         return message = "success";
       }
       else {
         return message = "error";
       }
     }, function (error) {
       return message = "error";
     })

 };
});

xnyder.service('update_one_account_details', function ($http) {
  this.do_update_one_account_details = function (unique_id, email, user_role, fullname, gender, phone_number) {
    var message;

    var one_account_data = {
      "user_unique_id":unique_id,
      "email":email,
      "user_role":user_role,
      "fullname":fullname,
      "gender":gender,
      "phone_number":phone_number
    }

    $http.post('server/update_one_account_user_details.php', one_account_data)
      .then(function (response) {
        if (response.data.engineMessage == 1) {
          return message = "success";
        }
        else {
          return message = "error";
        }
      }, function (error) {
        return message = "error";
      })

  }
});

xnyder.service('strip_text', function () {
  this.get_stripped = function (text) {
    let str = text;
    let new_str = str.replace(/-/g, '');
    let new_str_2 = new_str.replace(/  /g, '-');
    let new_str_3 = new_str_2.replace(/[ +#(),*!"<>~`;:?]/g, '-');
    let new_str_4 = new_str_3.replace(/-----/g, '-');
    let new_str_5 = new_str_4.replace(/----/g, '-');
    let new_str_6 = new_str_5.replace(/---/g, '-');
    let new_str_7 = new_str_6.replace(/--/g, '-');
    let re_text = new_str_7.toLowerCase();
    return re_text;
  }
});

xnyder.service('listLGA', function () {
  this.list = function () {
    var lgaList = {
      Abia: [
        "Aba North",
        "Aba South",
        "Arochukwu",
        "Bende",
        "Ikwuano",
        "Isiala Ngwa North",
        "Isiala Ngwa South",
        "Isuikwuato",
        "Obi Ngwa",
        "Ohafia",
        "Osisioma",
        "Ugwunagbo",
        "Ukwa East",
        "Ukwa West",
        "Umuahia North",
        "Umuahia South",
        "Umu Nneochi"
      ],
      Adamawa: [
        "Demsa",
        "Fufure",
        "Ganye",
        "Gayuk",
        "Gombi",
        "Grie",
        "Hong",
        "Jada",
        "Larmurde",
        "Madagali",
        "Maiha",
        "Mayo Belwa",
        "Michika",
        "Mubi North",
        "Mubi South",
        "Numan",
        "Shelleng",
        "Song",
        "Toungo",
        "Yola North",
        "Yola South"
      ],
      "Akwa Ibom": [
        "Abak",
        "Eastern Obolo",
        "Eket",
        "Esit Eket",
        "Essien Udim",
        "Etim Ekpo",
        "Etinan",
        "Ibeno",
        "Ibesikpo Asutan",
        "Ibiono-Ibom",
        "Ika",
        "Ikono",
        "Ikot Abasi",
        "Ikot Ekpene",
        "Ini",
        "Itu",
        "Mbo",
        "Mkpat-Enin",
        "Nsit-Atai",
        "Nsit-Ibom",
        "Nsit-Ubium",
        "Obot Akara",
        "Okobo",
        "Onna",
        "Oron",
        "Oruk Anam",
        "Udung-Uko",
        "Ukanafun",
        "Uruan",
        "Urue-Offong Oruko",
        "Uyo"
      ],
      Anambra: [
        "Aguata",
        "Anambra East",
        "Anambra West",
        "Anaocha",
        "Awka North",
        "Awka South",
        "Ayamelum",
        "Dunukofia",
        "Ekwusigo",
        "Idemili North",
        "Idemili South",
        "Ihiala",
        "Njikoka",
        "Nnewi North",
        "Nnewi South",
        "Ogbaru",
        "Onitsha North",
        "Onitsha South",
        "Orumba North",
        "Orumba South",
        "Oyi"
      ],
      Bauchi: [
        "Alkaleri",
        "Bauchi",
        "Bogoro",
        "Damban",
        "Darazo",
        "Dass",
        "Gamawa",
        "Ganjuwa",
        "Giade",
        "Itas-Gadau",
        "Jama are",
        "Katagum",
        "Kirfi",
        "Misau",
        "Ningi",
        "Shira",
        "Tafawa Balewa",
        " Toro",
        " Warji",
        " Zaki"
      ],
      Bayelsa: [
        "Brass",
        "Ekeremor",
        "Kolokuma Opokuma",
        "Nembe",
        "Ogbia",
        "Sagbama",
        "Southern Ijaw",
        "Yenagoa"
      ],
      Benue: [
        "Agatu",
        "Apa",
        "Ado",
        "Buruku",
        "Gboko",
        "Guma",
        "Gwer East",
        "Gwer West",
        "Katsina-Ala",
        "Konshisha",
        "Kwande",
        "Logo",
        "Makurdi",
        "Obi",
        "Ogbadibo",
        "Ohimini",
        "Oju",
        "Okpokwu",
        "Oturkpo",
        "Tarka",
        "Ukum",
        "Ushongo",
        "Vandeikya"
      ],
      Borno: [
        "Abadam",
        "Askira-Uba",
        "Bama",
        "Bayo",
        "Biu",
        "Chibok",
        "Damboa",
        "Dikwa",
        "Gubio",
        "Guzamala",
        "Gwoza",
        "Hawul",
        "Jere",
        "Kaga",
        "Kala-Balge",
        "Konduga",
        "Kukawa",
        "Kwaya Kusar",
        "Mafa",
        "Magumeri",
        "Maiduguri",
        "Marte",
        "Mobbar",
        "Monguno",
        "Ngala",
        "Nganzai",
        "Shani"
      ],
      "Cross River": [
        "Abi",
        "Akamkpa",
        "Akpabuyo",
        "Bakassi",
        "Bekwarra",
        "Biase",
        "Boki",
        "Calabar Municipal",
        "Calabar South",
        "Etung",
        "Ikom",
        "Obanliku",
        "Obubra",
        "Obudu",
        "Odukpani",
        "Ogoja",
        "Yakuur",
        "Yala"
      ],
      Delta: [
        "Aniocha North",
        "Aniocha South",
        "Bomadi",
        "Burutu",
        "Ethiope East",
        "Ethiope West",
        "Ika North East",
        "Ika South",
        "Isoko North",
        "Isoko South",
        "Ndokwa East",
        "Ndokwa West",
        "Okpe",
        "Oshimili North",
        "Oshimili South",
        "Patani",
        "Sapele",
        "Udu",
        "Ughelli North",
        "Ughelli South",
        "Ukwuani",
        "Uvwie",
        "Warri North",
        "Warri South",
        "Warri South West"
      ],
      Ebonyi: [
        "Abakaliki",
        "Afikpo North",
        "Afikpo South",
        "Ebonyi",
        "Ezza North",
        "Ezza South",
        "Ikwo",
        "Ishielu",
        "Ivo",
        "Izzi",
        "Ohaozara",
        "Ohaukwu",
        "Onicha"
      ],
      Edo: [
        "Akoko-Edo",
        "Egor",
        "Esan Central",
        "Esan North-East",
        "Esan South-East",
        "Esan West",
        "Etsako Central",
        "Etsako East",
        "Etsako West",
        "Igueben",
        "Ikpoba Okha",
        "Orhionmwon",
        "Oredo",
        "Ovia North-East",
        "Ovia South-West",
        "Owan East",
        "Owan West",
        "Uhunmwonde"
      ],
      Ekiti: [
        "Ado Ekiti",
        "Efon",
        "Ekiti East",
        "Ekiti South-West",
        "Ekiti West",
        "Emure",
        "Gbonyin",
        "Ido Osi",
        "Ijero",
        "Ikere",
        "Ikole",
        "Ilejemeje",
        "Irepodun-Ifelodun",
        "Ise-Orun",
        "Moba",
        "Oye"
      ],
      Rivers: [
        "ABHONEMA",
        "AHOADA EAST",
        "AHOADA WEST",
        "AIRPORT ROAD - PORT HARCOURT",
        "BONNY ISLAND",
        "ELELE",
        "ELEME-AGBONCHIA",
        "ELEME-AKPAJO",
        "ELEME-ALESA",
        "ELEME-ALETO",
        "ELEME-ALODE",
        "ELEME-EBUBU",
        "ELEME-EKPORO",
        "ELEME-ETEO",
        "ELEME-NCHIA",
        "ELEME-ODIDO",
        "ELEME-OGALE",
        "ELEME-ONNE",
        "EMOUHA",
        "ETCHE",
        "ISIOKPO",
        "OKRIKA",
        "OKRIKA-OKRIKA TOWN(KIRIKE)",
        "OMOKU",
        "OYIGBO-AFAM",
        "OYIGBO-EGBERU",
        "OYIGBO-IZUOMA",
        "OYIGBO-KOMKOM",
        "OYIGBO-MIRINWANYI",
        "OYIGBO-NDOKI",
        "OYIGBO-OBEAMA",
        "OYIGBO-UMUAGBAI",
        "PHC-NEW GRA -PHASE 1 2 3 4",
        "PHC-RIVER STATE UNIVERSITY",
        "PHC-WOJI Central Location",
        "PORTHARCOURT-ABONNEMA TOWN",
        "PORTHARCOURT-ABONNEMA WHARF",
        "PORTHARCOURT-ABULOMA",
        "PORTHARCOURT-ADA GEORGE",
        "PORTHARCOURT-AGIP",
        "PORTHARCOURT-AIRFORCE BASE",
        "PORTHARCOURT-AKPAJO",
        "PORTHARCOURT-ALAKAHIA-UPTH",
        "PORTHARCOURT-ALUU",
        "PORTHARCOURT-AMADI AMA",
        "PORTHARCOURT-AYA-OGOLOGO",
        "PORTHARCOURT-AZUBIE",
        "PORTHARCOURT-BORI CAMP",
        "PORTHARCOURT-BOROKIRI",
        "PORTHARCOURT-CHOBA",
        "PORTHARCOURT-CHOBA-UNIPORT",
        "PORTHARCOURT-CHURCHHILL",
        "PORTHARCOURT-D/LINE",
        "PORTHARCOURT-DARICK POLO",
        "PORTHARCOURT-DIOBU",
        "PORTHARCOURT-EAGLE ISLAND",
        "PORTHARCOURT-EGBELU",
        "PORTHARCOURT-ELEKAHIA",
        "PORTHARCOURT-ELELEWON",
        "PORTHARCOURT-ELIBOLO",
        "PORTHARCOURT-ELIGBAM",
        "PORTHARCOURT-ELIMGBU",
        "PORTHARCOURT-ELINPARAWON",
        "PORTHARCOURT-ELIOHANI",
        "PORTHARCOURT-ELIOZU",
        "PORTHARCOURT-ENEKA",
        "PORTHARCOURT-IGWURUTA",
        "PORTHARCOURT-INTELS KM 16",
        "PORTHARCOURT-MGBUOBA",
        "PORTHARCOURT-MILE 1",
        "PORTHARCOURT-MILE 2",
        "PORTHARCOURT-MILE 3",
        "PORTHARCOURT-MILE 4",
        "PORTHARCOURT-MILE 5",
        "PORTHARCOURT-MGBUOSIMINI",
        "PORTHARCOURT-NEW GRA-TOMBIA",
        "PORTHARCOURT-NKPOGU",
        "PORTHARCOURT-NKPOLU",
        "PORTHARCOURT-OGBATAI",
        "PORTHARCOURT-OGBOGORO",
        "PORTHARCOURT-OGBUNABALI",
        "PORTHARCOURT-OGINIGBA",
        "PORTHARCOURT-OKPORO ROAD",
        "PORTHARCOURT-OKURU",
        "PORTHARCOURT-OLD GRA",
        "PORTHARCOURT-ORAZI",
        "PORTHARCOURT-OZUBOKO",
        "PORTHARCOURT-PETER ODILLI ROAD",
        "PORTHARCOURT-RECLAMATION",
        "PORTHARCOURT-RUKPOKWU",
        "PORTHARCOURT-RUMEME",
        "PORTHARCOURT-RUMUAGHAOLU",
        "PORTHARCOURT-RUMUAKPAKOLOSI",
        "PORTHARCOURT-RUMUEPIRIKOM",
        "PORTHARCOURT-RUMUEVOLU",
        "PORTHARCOURT-RUMUIBEKWE",
        "PORTHARCOURT-RUMUIGBO",
        "PORTHARCOURT-RUMUKALAGBOR",
        "PORTHARCOURT-RUMUKRUSHI",
        "PORTHARCOURT-RUMUMASI",
        "PORTHARCOURT-RUMUODUMAYA",
        "PORTHARCOURT-RUMUOGBA",
        "PORTHARCOURT-RUMUOKE",
        "PORTHARCOURT-RUMUOKORO",
        "PORTHARCOURT-RUMUOKWUTA",
        "PORTHARCOURT-RUMUOLA",
        "PORTHARCOURT-RUMUOLUMENI",
        "PORTHARCOURT-RUMUOROSI",
        "PORTHARCOURT-RUMUOWAH",
        "PORTHARCOURT-STADIUM ROAD",
        "PORTHARCOURT-TOWN",
        "PORTHARCOURT-TRANS AMADI",
        "PORTHARCOURT-WOJI ILLOM",
        "PORTHARCOURT-WOJI ROAD",
        "PORTHARCOURT-WOJI YKC",
        "PORTHARCOURT-WOJI-ELIJIJI",
        "RUMUAGHOLU",
        "RUMUODUOMAYA",
        "Obio-Akpor",
        "Ogu–Bolo",
        "Tai",
        "Gokana",
        "Khana",
        "Opobo–Nkoro",
        "Andoni",
        "Bonny",
        "Degema",
        "Asari-Toru",
        "Akuku-Toru",
        "Abua–Odual",
        "Ogba–Egbema–Ndoni",
        "Ikwerre",
        "Omuma"
      ],
      Enugu: [
        "Aninri",
        "Awgu",
        "Enugu East",
        "Enugu North",
        "Enugu South",
        "Ezeagu",
        "Igbo Etiti",
        "Igbo Eze North",
        "Igbo Eze South",
        "Isi Uzo",
        "Nkanu East",
        "Nkanu West",
        "Nsukka",
        "Oji River",
        "Udenu",
        "Udi",
        "Uzo Uwani"
      ],
      Abuja: [
        "Abaji",
        "Bwari",
        "Gwagwalada",
        "Kuje",
        "Kwali",
        "Municipal Area Council"
      ],
      Gombe: [
        "Akko",
        "Balanga",
        "Billiri",
        "Dukku",
        "Funakaye",
        "Gombe",
        "Kaltungo",
        "Kwami",
        "Nafada",
        "Shongom",
        "Yamaltu-Deba"
      ],
      Imo: [
        "Aboh Mbaise",
        "Ahiazu Mbaise",
        "Ehime Mbano",
        "Ezinihitte",
        "Ideato North",
        "Ideato South",
        "Ihitte-Uboma",
        "Ikeduru",
        "Isiala Mbano",
        "Isu",
        "Mbaitoli",
        "Ngor Okpala",
        "Njaba",
        "Nkwerre",
        "Nwangele",
        "Obowo",
        "Oguta",
        "Ohaji-Egbema",
        "Okigwe",
        "Orlu",
        "Orsu",
        "Oru East",
        "Oru West",
        "Owerri Municipal",
        "Owerri North",
        "Owerri West",
        "Unuimo"
      ],
      Jigawa: [
        "Auyo",
        "Babura",
        "Biriniwa",
        "Birnin Kudu",
        "Buji",
        "Dutse",
        "Gagarawa",
        "Garki",
        "Gumel",
        "Guri",
        "Gwaram",
        "Gwiwa",
        "Hadejia",
        "Jahun",
        "Kafin Hausa",
        "Kazaure",
        "Kiri Kasama",
        "Kiyawa",
        "Kaugama",
        "Maigatari",
        "Malam Madori",
        "Miga",
        "Ringim",
        "Roni",
        "Sule Tankarkar",
        "Taura",
        "Yankwashi"
      ],
      Kaduna: [
        "Birnin Gwari",
        "Chikun",
        "Giwa",
        "Igabi",
        "Ikara",
        "Jaba",
        "Jema a",
        "Kachia",
        "Kaduna North",
        "Kaduna South",
        "Kagarko",
        "Kajuru",
        "Kaura",
        "Kauru",
        "Kubau",
        "Kudan",
        "Lere",
        "Makarfi",
        "Sabon Gari",
        "Sanga",
        "Soba",
        "Zangon Kataf",
        "Zaria"
      ],
      Kano: [
        "Ajingi",
        "Albasu",
        "Bagwai",
        "Bebeji",
        "Bichi",
        "Bunkure",
        "Dala",
        "Dambatta",
        "Dawakin Kudu",
        "Dawakin Tofa",
        "Doguwa",
        "Fagge",
        "Gabasawa",
        "Garko",
        "Garun Mallam",
        "Gaya",
        "Gezawa",
        "Gwale",
        "Gwarzo",
        "Kabo",
        "Kano Municipal",
        "Karaye",
        "Kibiya",
        "Kiru",
        "Kumbotso",
        "Kunchi",
        "Kura",
        "Madobi",
        "Makoda",
        "Minjibir",
        "Nasarawa",
        "Rano",
        "Rimin Gado",
        "Rogo",
        "Shanono",
        "Sumaila",
        "Takai",
        "Tarauni",
        "Tofa",
        "Tsanyawa",
        "Tudun Wada",
        "Ungogo",
        "Warawa",
        "Wudil"
      ],
      Katsina: [
        "Bakori",
        "Batagarawa",
        "Batsari",
        "Baure",
        "Bindawa",
        "Charanchi",
        "Dandume",
        "Danja",
        "Dan Musa",
        "Daura",
        "Dutsi",
        "Dutsin Ma",
        "Faskari",
        "Funtua",
        "Ingawa",
        "Jibia",
        "Kafur",
        "Kaita",
        "Kankara",
        "Kankia",
        "Katsina",
        "Kurfi",
        "Kusada",
        "Mai Adua",
        "Malumfashi",
        "Mani",
        "Mashi",
        "Matazu",
        "Musawa",
        "Rimi",
        "Sabuwa",
        "Safana",
        "Sandamu",
        "Zango"
      ],
      Kebbi: [
        "Aleiro",
        "Arewa Dandi",
        "Argungu",
        "Augie",
        "Bagudo",
        "Birnin Kebbi",
        "Bunza",
        "Dandi",
        "Fakai",
        "Gwandu",
        "Jega",
        "Kalgo",
        "Koko Besse",
        "Maiyama",
        "Ngaski",
        "Sakaba",
        "Shanga",
        "Suru",
        "Wasagu Danko",
        "Yauri",
        "Zuru"
      ],
      Kogi: [
        "Adavi",
        "Ajaokuta",
        "Ankpa",
        "Bassa",
        "Dekina",
        "Ibaji",
        "Idah",
        "Igalamela Odolu",
        "Ijumu",
        "Kabba Bunu",
        "Kogi",
        "Lokoja",
        "Mopa Muro",
        "Ofu",
        "Ogori Magongo",
        "Okehi",
        "Okene",
        "Olamaboro",
        "Omala",
        "Yagba East",
        "Yagba West"
      ],
      Kwara: [
        "Asa",
        "Baruten",
        "Edu",
        "Ekiti",
        "Ifelodun",
        "Ilorin East",
        "Ilorin South",
        "Ilorin West",
        "Irepodun",
        "Isin",
        "Kaiama",
        "Moro",
        "Offa",
        "Oke Ero",
        "Oyun",
        "Pategi"
      ],
      Lagos: [
        "Agege",
        "Ajeromi-Ifelodun",
        "Alimosho",
        "Amuwo-Odofin",
        "Apapa",
        "Badagry",
        "Epe",
        "Eti Osa",
        "Ibeju-Lekki",
        "Ifako-Ijaiye",
        "Ikeja",
        "Ikorodu",
        "Kosofe",
        "Lagos Island",
        "Lagos Mainland",
        "Mushin",
        "Ojo",
        "Oshodi-Isolo",
        "Shomolu",
        "Surulere"
      ],
      Nassarawa: [
        "Akwanga",
        "Awe",
        "Doma",
        "Karu",
        "Keana",
        "Keffi",
        "Kokona",
        "Lafia",
        "Nasarawa",
        "Nasarawa Egon",
        "Obi",
        "Toto",
        "Wamba"
      ],
      Niger: [
        "Agaie",
        "Agwara",
        "Bida",
        "Borgu",
        "Bosso",
        "Chanchaga",
        "Edati",
        "Gbako",
        "Gurara",
        "Katcha",
        "Kontagora",
        "Lapai",
        "Lavun",
        "Magama",
        "Mariga",
        "Mashegu",
        "Mokwa",
        "Moya",
        "Paikoro",
        "Rafi",
        "Rijau",
        "Shiroro",
        "Suleja",
        "Tafa",
        "Wushishi"
      ],
      Ogun: [
        "Abeokuta North",
        "Abeokuta South",
        "Ado-Odo Ota",
        "Egbado North",
        "Egbado South",
        "Ewekoro",
        "Ifo",
        "Ijebu East",
        "Ijebu North",
        "Ijebu North East",
        "Ijebu Ode",
        "Ikenne",
        "Imeko Afon",
        "Ipokia",
        "Obafemi Owode",
        "Odeda",
        "Odogbolu",
        "Ogun Waterside",
        "Remo North",
        "Shagamu"
      ],
      Ondo: [
        "Akoko North-East",
        "Akoko North-West",
        "Akoko South-West",
        "Akoko South-East",
        "Akure North",
        "Akure South",
        "Ese Odo",
        "Idanre",
        "Ifedore",
        "Ilaje",
        "Ile Oluji-Okeigbo",
        "Irele",
        "Odigbo",
        "Okitipupa",
        "Ondo East",
        "Ondo West",
        "Ose",
        "Owo"
      ],
      Osun: [
        "Atakunmosa East",
        "Atakunmosa West",
        "Aiyedaade",
        "Aiyedire",
        "Boluwaduro",
        "Boripe",
        "Ede North",
        "Ede South",
        "Ife Central",
        "Ife East",
        "Ife North",
        "Ife South",
        "Egbedore",
        "Ejigbo",
        "Ifedayo",
        "Ifelodun",
        "Ila",
        "Ilesa East",
        "Ilesa West",
        "Irepodun",
        "Irewole",
        "Isokan",
        "Iwo",
        "Obokun",
        "Odo Otin",
        "Ola Oluwa",
        "Olorunda",
        "Oriade",
        "Orolu",
        "Osogbo"
      ],
      Oyo: [
        "Afijio",
        "Akinyele",
        "Atiba",
        "Atisbo",
        "Egbeda",
        "Ibadan North",
        "Ibadan North-East",
        "Ibadan North-West",
        "Ibadan South-East",
        "Ibadan South-West",
        "Ibarapa Central",
        "Ibarapa East",
        "Ibarapa North",
        "Ido",
        "Irepo",
        "Iseyin",
        "Itesiwaju",
        "Iwajowa",
        "Kajola",
        "Lagelu",
        "Ogbomosho North",
        "Ogbomosho South",
        "Ogo Oluwa",
        "Olorunsogo",
        "Oluyole",
        "Ona Ara",
        "Orelope",
        "Ori Ire",
        "Oyo",
        "Oyo East",
        "Saki East",
        "Saki West",
        "Surulere"
      ],
      Plateau: [
        "Bokkos",
        "Barkin Ladi",
        "Bassa",
        "Jos East",
        "Jos North",
        "Jos South",
        "Kanam",
        "Kanke",
        "Langtang South",
        "Langtang North",
        "Mangu",
        "Mikang",
        "Pankshin",
        "Qua an Pan",
        "Riyom",
        "Shendam",
        "Wase"
      ],
      Sokoto: [
        "Binji",
        "Bodinga",
        "Dange Shuni",
        "Gada",
        "Goronyo",
        "Gudu",
        "Gwadabawa",
        "Illela",
        "Isa",
        "Kebbe",
        "Kware",
        "Rabah",
        "Sabon Birni",
        "Shagari",
        "Silame",
        "Sokoto North",
        "Sokoto South",
        "Tambuwal",
        "Tangaza",
        "Tureta",
        "Wamako",
        "Wurno",
        "Yabo"
      ],
      Taraba: [
        "Ardo Kola",
        "Bali",
        "Donga",
        "Gashaka",
        "Gassol",
        "Ibi",
        "Jalingo",
        "Karim Lamido",
        "Kumi",
        "Lau",
        "Sardauna",
        "Takum",
        "Ussa",
        "Wukari",
        "Yorro",
        "Zing"
      ],
      Yobe: [
        "Bade",
        "Bursari",
        "Damaturu",
        "Fika",
        "Fune",
        "Geidam",
        "Gujba",
        "Gulani",
        "Jakusko",
        "Karasuwa",
        "Machina",
        "Nangere",
        "Nguru",
        "Potiskum",
        "Tarmuwa",
        "Yunusari",
        "Yusufari"
      ],
      Zamfara: [
        "Anka",
        "Bakura",
        "Birnin Magaji Kiyaw",
        "Bukkuyum",
        "Bungudu",
        "Gummi",
        "Gusau",
        "Kaura Namoda",
        "Maradun",
        "Maru",
        "Shinkafi",
        "Talata Mafara",
        "Chafe",
        "Zurmi"
      ]
    }

    return lgaList;
  }

});

xnyder.run(function($rootScope,$anchorScroll){
    $rootScope.$on("$locationChangeSuccess", function(){
        $anchorScroll();
    })
});

xnyder.directive('fileModel', ['$parse', function ($parse) {
  //for file upload
    return {
    restrict: 'A',
    link: function(scope, element, attrs) {
        var model = $parse(attrs.fileModel);
        var modelSetter = model.assign;

        element.bind('change', function(){
            scope.$apply(function(){
                modelSetter(scope, element[0].files[0]);
            });
        });
    }
   };
}]);

xnyder.filter('fil_date', function () {
  return function (date) {
    let raw_date = date;
    let split_raw = raw_date != undefined ? raw_date.split(" ") : null;
    let splitted_raw = split_raw != undefined ? split_raw[0] : null;

    return splitted_raw;
  }
});

xnyder.filter('fil_time', function () {
  return function (time) {
    let raw_time = time;
    let split_raw = raw_time != undefined ? raw_time.split(" ") : null;
    let splitted_raw = split_raw != undefined ? split_raw[1] : null;

    return splitted_raw;
  }
});

xnyder.filter('fil_fmt_time', function () {
  return function (time) {
    let raw_time = time;
    let split_raw = raw_time != undefined ? raw_time.split(":") : null;
    let splitted_raw = split_raw != undefined ? split_raw[0] : null;
    let splitted_raw_2 = split_raw != undefined ? split_raw[1] : null;

    let new_time_morning = splitted_raw + ":" + splitted_raw_2 + " AM";
    // let new_time_later = splitted_raw + "." + splitted_raw_2 + "PM";

    if (splitted_raw > 0 && splitted_raw <= 11) {
      return new_time_morning;
    }
    else {

      let new_time_later;

      switch (splitted_raw) {
        case "00":
          splitted_raw = 12;
          new_time_later = splitted_raw + ":" + splitted_raw_2 + " AM";
          return new_time_later;
        break;
        case "13":
          splitted_raw = 1;
          new_time_later = splitted_raw + ":" + splitted_raw_2 + " PM";
          return new_time_later;
        break;
        case "14":
          splitted_raw = 2;
          new_time_later = splitted_raw + ":" + splitted_raw_2 + " PM";
          return new_time_later;
        break;
        case "15":
          splitted_raw = 3;
          new_time_later = splitted_raw + ":" + splitted_raw_2 + " PM";
          return new_time_later;
        break;
        case "16":
          splitted_raw = 4;
          new_time_later = splitted_raw + ":" + splitted_raw_2 + " PM";
          return new_time_later;
        break;
        case "17":
          splitted_raw = 5;
          new_time_later = splitted_raw + ":" + splitted_raw_2 + " PM";
          return new_time_later;
        break;
        case "18":
          splitted_raw = 6;
          new_time_later = splitted_raw + ":" + splitted_raw_2 + " PM";
          return new_time_later;
        break;
        case "19":
          splitted_raw = 7;
          new_time_later = splitted_raw + ":" + splitted_raw_2 + " PM";
          return new_time_later;
        break;
        case "20":
          splitted_raw = 8;
          new_time_later = splitted_raw + ":" + splitted_raw_2 + " PM";
          return new_time_later;
        break;
        case "21":
          splitted_raw = 9;
          new_time_later = splitted_raw + ":" + splitted_raw_2 + " PM";
          return new_time_later;
        break;
        case "22":
          splitted_raw = 10;
          new_time_later = splitted_raw + ":" + splitted_raw_2 + " PM";
          return new_time_later;
        break;
        case "23":
          splitted_raw = 11;
          new_time_later = splitted_raw + ":" + splitted_raw_2 + " PM";
          return new_time_later;
        break;
        case "24":
          splitted_raw = 12;
          new_time_later = splitted_raw + ":" + splitted_raw_2 + " AM";
          return new_time_later;
        break;
        default:
          splitted_raw = 12;
          new_time_later = splitted_raw + ":" + splitted_raw_2 + " PM";
          return new_time_later;
      }
    }

  }
});

xnyder.filter('fil_id', function () {
  return function (unique_id) {
    let raw_unique_id = unique_id;
    let split_raw = raw_unique_id.slice(0,5);
    let splitted_raw = new String(split_raw) + "*****";

    return splitted_raw;
  }
});

xnyder.filter('bytes', function () {
	return function (bytes, precision) {
		if (isNaN(parseFloat(bytes)) || !isFinite(bytes)) return '0 bytes' ;
		if (typeof precision === 'undefined') precision = 1 ;
		var units = ['bytes', 'kB', 'MB', 'GB', 'TB', 'PB'],
			number = Math.floor(Math.log(bytes) / Math.log(1024));
			return (bytes / Math.pow(1024, Math.floor(number))).toFixed(precision) + " " + units[number];
	}
});

xnyder.filter('downloads', function () {
	return function (downloads) {
		if (isNaN(downloads))
			downloads = 0;

		if (downloads < 1000)
			return downloads + ' ';

		downloads /= 1000;

		if (downloads < 1000)
			return downloads.toFixed(1) + ' k';

		downloads /= 1000;

		if (downloads < 1000)
			return downloads.toFixed(1) + ' m';

		downloads /= 1000;

		if (downloads < 1000)
			return downloads.toFixed(1) + ' b';

		downloads /= 1000;

		return downloads.toFixed(1) + ' tn';
	};
});

xnyder.filter('phone', function () {
  return function (phone) {
    let raw_phone = phone;
    let first_part = raw_phone.substring(0,4);
    let second_part = raw_phone.substring(8,11);
    let full_phone = first_part + "xxxx" + second_part;

    return full_phone;
  }
})

xnyder.filter('notifications', function () {
	return function (notifications) {
		if (isNaN(notifications))
			notifications = 0;

		if (notifications < 1000)
			return notifications + ' ';

		notifications /= 1000;

		if (notifications < 1000)
			return notifications.toFixed(0) + 'k';

		notifications /= 1000;

		if (notifications < 1000)
			return notifications.toFixed(0) + 'm';

		notifications /= 1000;

		if (notifications < 1000)
			return notifications.toFixed(0) + 'b';

		notifications /= 1000;

		return notifications.toFixed(0) + 'tn';
	};
});

 // Controller Starts
