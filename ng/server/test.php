<?php

  header('Content-Type: text/html; charset=utf-8');

  // require 'connect.php';
  include_once 'config/connect.php';
  include_once "objects/management_obj.php";
  include_once "objects/categories_obj.php";
  include_once "objects/sub_categories_obj.php";
  include_once "objects/mini_categories_obj.php";
  include_once "objects/products_obj.php";
  include_once "objects/cart_obj.php";
  include_once "objects/users_obj.php";
  include_once "objects/brands_obj.php";
  include_once "objects/offered_services_obj.php";
  include_once "objects/reviews_obj.php";
  include_once "objects/view_history_obj.php";
  include_once "objects/search_history_obj.php";
  include_once "objects/review_history_obj.php";
  include_once "objects/orders_obj.php";
  include_once "objects/coupons_obj.php";
  include_once "objects/order_history_obj.php";
  include_once "objects/disputes_obj.php";
  include_once "objects/shipments_obj.php";
  include_once "objects/coupon_history_obj.php";
  include_once "objects/functions.php";

  $database = new Database();
  $db = $database->getConnection();

  // initialize object
  $management = new Management($db);
  $category = new Category($db);
  $sub_category = new SubCategory($db);
  $mini_category = new MiniCategory($db);
  $product = new Products($db);
  $cart = new Cart($db);
  $user = new Users($db);
  $brand = new Brands($db);
  $offered_service = new OfferedServices($db);
  $review = new Reviews($db);
  $view_history = new ViewHistory($db);
  $search_history = new SearchHistory($db);
  $review_history = new ReviewHistory($db);
  $order = new Orders($db);
  $coupon = new Coupons($db);
  $order_history = new OrderHistory($db);
  $disputes = new Disputes($db);
  $shipments = new Shipments($db);
  $coupon_history = new CouponHistory($db);
  $functions = new Functions();
  // echo $func->active;

// add_new_management_user("dAXn9RrXd61LYHNSgI2T", "Emmanuel David", "emmanuel@gmail.com", 2, 1)
// ?edit_user_unique_id=dAXn9RrXd61LYHNSgI2T&fullname=Emmanuel%20David&email=emmanuel@gmail.com&role=2&access=1

  // $result = $management->add_new_management_user(isset($_GET['edit_user_unique_id']) ? $_GET['edit_user_unique_id'] : null, isset($_GET['fullname']) ? $_GET['fullname'] : null, isset($_GET['email']) ? $_GET['email'] : null,
  //  isset($_GET['role']) ? $_GET['role'] : null, isset($_GET['access']) ? $_GET['access'] : null);

  // ?user_unique_id=dAXn9RrXd61LYHNSgI2T
  // $result = $management->get_management_user_details(isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : null);

  // ?user_unique_id=dAXn9RrXd61LYHNSgI2T
  // $result = $management->get_edit_management_users(isset($_GET['edit_user_unique_id']) ? $_GET['edit_user_unique_id'] : null);

  // ?edit_user_unique_id=dAXn9RrXd61LYHNSgI2T&user_unique_id=dAXn9RrXd61LYHNSgI2T&role=3
  // $result = $management->update_management_user_role(isset($_GET['edit_user_unique_id']) ? $_GET['edit_user_unique_id'] : null, isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : null, isset($_GET['role']) ? $_GET['role'] : null);

  // ?edit_user_unique_id=dAXn9RrXd61LYHNSgI2T&user_unique_id=dAXn9RrXd61LYHNSgI2T
  // $result = $management->remove_management_user(isset($_GET['edit_user_unique_id']) ? $_GET['edit_user_unique_id'] : null, isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : null);

  // $result = $management->get_all_management_users();

  // $result = $category->get_all_categories();
  // $result = $category->get_user_categories(isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : null);

  // $result = $sub_category->get_all_sub_categories();
  // $result = $sub_category->get_user_sub_categories(isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : null);
  // $result = $sub_category->get_category_sub_categories(isset($_GET['category_unique_id']) ? $_GET['category_unique_id'] : null);
  // $result = $sub_category->get_null_category_sub_categories();

  // $result = $mini_category->get_all_mini_categories();
  // $result = $mini_category->get_user_mini_categories(isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : null);
  // $result = $mini_category->get_sub_category_mini_categories(isset($_GET['sub_category_unique_id']) ? $_GET['sub_category_unique_id'] : null);
  // $result = $mini_category->get_null_sub_category_mini_categories();

  // $result = $product->get_all_products();
  // $result = $product->get_product_details(isset($_GET['product_unique_id']) ? $_GET['product_unique_id'] : null, isset($_GET['stripped']) ? $_GET['stripped'] : null);
  // $result = $product->get_short_detail_of_products_for_users();
  // $result = $product->get_all_products_for_users();
  // $result = $product->get_product_details_for_users(isset($_GET['product_unique_id']) ? $_GET['product_unique_id'] : null, isset($_GET['stripped']) ? $_GET['stripped'] : null);
  // $result = $product->get_featured_category_short_detail_of_products_for_users(isset($_GET['category_unique_id']) ? $_GET['category_unique_id'] : null);
  // $result = $product->get_category_page_for_users(isset($_GET['category_unique_id']) ? $_GET['category_unique_id'] : null);
  // $result = $product->get_sub_category_page_for_users(isset($_GET['sub_category_unique_id']) ? $_GET['sub_category_unique_id'] : null);
  // $result = $product->get_mini_category_page_for_users(isset($_GET['mini_category_unique_id']) ? $_GET['mini_category_unique_id'] : null);
  // $result = $product->get_featured_sub_category_short_detail_of_products_for_users(isset($_GET['sub_category_unique_id']) ? $_GET['sub_category_unique_id'] : null);
  // $result = $product->get_featured_mini_category_short_detail_of_products_for_users(isset($_GET['mini_category_unique_id']) ? $_GET['mini_category_unique_id'] : null);
  // $result = $product->get_mini_category_short_detail_of_products_for_users(isset($_GET['mini_category_unique_id']) ? $_GET['mini_category_unique_id'] : null);
  // $result = $product->get_featured_brand_short_detail_of_products_for_users(isset($_GET['brand_unique_id']) ? $_GET['brand_unique_id'] : null);
  // $result = $product->get_view_history_short_detail_of_products_for_users();
  // $result = $product->get_recent_search_short_detail_of_products_for_users();
  // $result = $product->get_user_search_short_detail_of_products_for_users(isset($_GET['search_word']) ? $_GET['search_word'] : null);
  // $result = $product->get_user_category_search_short_detail_of_products_for_users(isset($_GET['search_word']) ? $_GET['search_word'] : null, isset($_GET['category_unique_id']) ? $_GET['category_unique_id'] : null);

  // $result = $user->get_all_users();
  // $result = $user->get_user_details(isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : null);

  // $result = $brand->get_all_brands();
  // $result = $brand->get_edit_management_brands(isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : null);
  // $result = $brand->get_brand_details(isset($_GET['brand_unique_id']) ? $_GET['brand_unique_id'] : null);

  $result = $offered_service->get_all_offered_services();

  // $result = $cart->get_all_cart();
  // $result = $cart->get_user_cart(isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : null);

  // $result = $review->get_all_reviews();
  // $result = $review->get_all_user_reviews(isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : null);
  // $result = $review->get_all_product_reviews(isset($_GET['product_unique_id']) ? $_GET['product_unique_id'] : null);

  // $result = $view_history->get_all_view_history();
  // $result = $view_history->get_user_view_history(isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : null);
  // $result = $view_history->get_product_view_history(isset($_GET['product_unique_id']) ? $_GET['product_unique_id'] : null);

  // $result = $search_history->get_all_search_history_with_products();
  // $result = $search_history->get_user_search_history_with_products(isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : null);
  // $result = $search_history->get_user_search_for_products(isset($_GET['search_word']) ? $_GET['search_word'] : null);

  // $result = $review_history->get_all_review_history();

  // $result = $order->get_all_orders();
  // $result = $order->get_user_orders(isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : null);
  // $result = $order->get_shipment_details(isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : null, isset($_GET['shipment_unique_id']) ? $_GET['shipment_unique_id'] : null);
  // $result = $order->get_shipment_orders(isset($_GET['shipment_unique_id']) ? $_GET['shipment_unique_id'] : null);

  // $result = $coupon->get_all_coupons();
  // $result = $coupon->get_user_coupons(isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : null);
  // $result = $coupon->get_product_coupons(isset($_GET['product_unique_id']) ? $_GET['product_unique_id'] : null);

  // $result = $order_history->get_all_order_history();
  // $result = $order_history->get_user_order_history(isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : null);

  // $result = $disputes->get_all_disputes();
  // $result = $disputes->get_user_disputes(isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : null);

  // $result = $shipments->get_all_shipments();
  // $result = $shipments->get_rider_shipments(isset($_GET['rider_unique_id']) ? $_GET['rider_unique_id'] : null);

  // $result = $coupon_history->get_all_coupon_history();
  // $result = $coupon_history->get_user_coupon_history(isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : null);

  $output = array('error' => false, 'success' => false);

  // $result = $functions->add_new_management_user_validation(isset($_GET['fullname']) ? $_GET['fullname'] : null, isset($_GET['email']) ? $_GET['email'] : null,
  //  isset($_GET['role']) ? $_GET['role'] : null, isset($_GET['access']) ? $_GET['access'] : null);

  $edit_user_unique_id = isset($_GET['edit_user_unique_id']) ? $_GET['edit_user_unique_id'] : null;
  $fullname = isset($_GET['fullname']) ? $_GET['fullname'] : null;
  $email = isset($_GET['email']) ? $_GET['email'] : null;
   $role = isset($_GET['role']) ? $_GET['role'] : null;
   $access = isset($_GET['access']) ? $_GET['access'] : null;

   if ($functions->isJson($result)) {
     echo $result;
   }
   else if (array_key_exists("success",$result)) {
     if ($result["success"] == true) {
       echo "Success : ".$result["message"];
     }
   }
   else if (array_key_exists("error",$result)) {
     if ($result["error"] == true) {
       echo "Error : ".$result["message"];
     }
     else {
       echo $result["message"];
     }
   }
   else {
     // print_r($result);
     // $the_result = json_encode($result);
     // $the_result_decoded = json_decode($the_result);
     echo json_encode($result);
     // print_r($_GET['cart_offered_service_unique_ids']);
     // $the_cart_ids = json_encode($_GET['cart_offered_service_unique_ids']);
     // echo $the_cart_ids;
   }

   $user_unique_id = "DSDJNDKJSDKJ";
   $product_unique_id = "";
   $price = "300";
   $total_count = "1212";
   $expiry_date = "2021-07-04 23:59:59";

   // $result = $functions->coupon_validation($user_unique_id, $product_unique_id, $price, $total_count, $expiry_date);
   // $result = $functions->validateInt($total_count);
   // $result = $functions->validateInt($price);

   // $converted_date = strtotime($functions->tomorrow);
   // $new_date = date('Y-m-d H:i:s', $converted_date);
   // echo $new_date;

   // echo $result;

   // if ($result["error"] == true) {
   //   echo "Error : ".$result["message"];
   // }
   // else {
   //   echo $result["message"];
   // }

   // if ($result["success"] == true) {
   //   echo "Success : ".$result["message"];
   // }
   // else if ($result["error"] == true) {
   //   echo "Error : ".$result["message"];
   // }
  // class genericClass {
  //   public $engineMessage = 0;
  //   public $noConnection = 0;
  //   public $alreadyExists = 0;
  // }
  //
  // $data = json_decode(file_get_contents("php://input"), true);
  //
  // if ($connected) {
  //
  //   try {
  //     $conn->beginTransaction();
  //
  //
  //
  //     $conn->commit();
  //   } catch (PDOException $e) {
  //     $conn->rollback();
  //     throw $e;
  //   }
  //
  //   echo json_encode($returnvalue);
  //
  // }
  // else {
  //   $returnvalue = new genericClass();
  //   $returnvalue->notConnected = 3;
  // }

?>
