<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';

  class Orders{

      // database connection and table name
      private $conn;
      private $table_name = "orders";

      // object properties
      public $id;
      public $unique_id;
      public $user_unique_id;
      public $sub_product_unique_id;
      public $tracker_unique_id;
      public $shipment_unique_id;
      public $quantity;
      public $checked_out;
      public $paid;
      public $shipped;
      public $disputed;
      public $delivery_status;
      public $added_date;
      public $last_modified;
      public $status;

      private $functions;
      private $not_allowed_values;
      private $shipment_table_values;
      private $user_addresses_table_values;

      public $output = array('error' => false, 'success' => false);

      // constructor with $db as database connection
      public function __construct($db){
          $this->conn = $db;
          $this->functions = new Functions();
          $this->not_allowed_values = $this->functions->not_allowed_values;
          $this->shipment_table_values = $this->functions->shipment_table_values;
          $this->user_addresses_table_values = $this->functions->user_addresses_table_values;
      }

      function get_all_orders(){

        try {
          $this->conn->beginTransaction();

          $active = $this->functions->active;
          $date_added = $this->functions->today;

          $order_array = array();

          $sql = "SELECT orders.id, orders.unique_id, orders.user_unique_id, orders.sub_product_unique_id, orders.tracker_unique_id, orders.shipment_unique_id, orders.coupon_unique_id, orders.shipping_fee_unique_id, orders.pickup_location, orders.quantity, orders.payment_method,
          orders.checked_out, orders.paid, orders.shipped, orders.disputed, orders.delivery_status, orders.added_date, orders.last_modified, orders.status,
          users.fullname as user_fullname, users.email as user_email, users.phone_number as user_phone_number, sub_products.name, sub_products.size, sub_products.stripped,
          sub_products.stock, sub_products.stock_remaining, sub_products.price, sub_products.sales_price, sub_products.favorites, products.mini_category_unique_id FROM orders INNER JOIN users ON orders.user_unique_id = users.unique_id LEFT JOIN sub_products ON orders.sub_product_unique_id = sub_products.unique_id
          LEFT JOIN products ON sub_products.product_unique_id = products.unique_id ORDER BY orders.added_date DESC";
          $query = $this->conn->prepare($sql);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_order = array();
              $current_order['id'] = $value['id'];
              $current_order['unique_id'] = $value['unique_id'];
              $current_order['user_unique_id'] = $value['user_unique_id'];
              $current_order['sub_product_unique_id'] = $value['sub_product_unique_id'];
              $current_order['tracker_unique_id'] = $value['tracker_unique_id'];
              $current_order['shipment_unique_id'] = $value['shipment_unique_id'];
              $current_order['quantity'] = $value['quantity'];
              $current_order['payment_method'] = $value['payment_method'];
              $current_order['checked_out'] = $value['checked_out'];
              $current_order['paid'] = $value['paid'];
              $current_order['shipped'] = $value['shipped'];
              $current_order['disputed'] = $value['disputed'];
              $current_order['delivery_status'] = $value['delivery_status'];
              $current_order['added_date'] = $value['added_date'];
              $current_order['last_modified'] = $value['last_modified'];
              $current_order['status'] = $value['status'];
              $current_order['user_fullname'] = $value['user_fullname'];
              $current_order['user_email'] = $value['user_email'];
              $current_order['user_phone_number'] = $value['user_phone_number'];
              $current_order['name'] = $value['name'];
              $current_order['size'] = $value['size'];
              $current_order['stripped'] = $value['stripped'];
              $current_order['stock'] = $value['stock'];
              $current_order['stock_remaining'] = $value['stock_remaining'];
              $current_order['price'] = $value['price'];
              $current_order['sales_price'] = $value['sales_price'];
              $current_order['favorites'] = $value['favorites'];

              $sub_product_id = $value['sub_product_unique_id'];
              $order_unique_id = $value['unique_id'];
              $user_unique_id = $value['user_unique_id'];
              $tracker_unique_id = $value['tracker_unique_id'];
              $shipping_fee_unique_id = $value['shipping_fee_unique_id'];
              $mini_category_unique_id = $value['mini_category_unique_id'];

              $current_order_price = (int)$value['price'];
              $current_order_sales_price = (int)$value['sales_price'];
              $current_order_quantity = (int)$value['quantity'];

              $sql2 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id LIMIT 1";
              $query2 = $this->conn->prepare($sql2);
              $query2->bindParam(":sub_product_unique_id", $sub_product_id);
              $query2->execute();

              $images_result = $query2->fetchAll();

              if ($query2->rowCount() > 0) {
                $current_order_images = array();

                foreach ($images_result as $key => $image_value) {
                  $current_order_images[] = $image_value['image'];
                }

                $current_order['order_product_images'] = $current_order_images;
              }
              else{
                $current_order['order_product_images'] = null;
              }

              if ($current_order_sales_price != 0) {
                $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND order_unique_id=:order_unique_id";
                $query7 = $this->conn->prepare($sql7);
                $query7->bindParam(":user_unique_id", $user_unique_id);
                $query7->bindParam(":order_unique_id", $order_unique_id);
                $query7->execute();

                if ($query7->rowCount() > 0) {
                  $the_order_services_details = $query7->fetchAll();
                  $offered_services_price = 0;

                  foreach ($the_order_services_details as $key => $value) {
                    $offered_service_unique_id = $value["offered_service_unique_id"];
                    $sql8 = "SELECT price FROM offered_services WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id AND status=:status";
                    $query8 = $this->conn->prepare($sql8);
                    $query8->bindParam(":unique_id", $offered_service_unique_id);
                    $query8->bindParam(":sub_product_unique_id", $sub_product_id);
                    $query8->bindParam(":status", $active);
                    $query8->execute();

                    $the_offered_services_price_details = $query8->fetch();
                    $the_offered_services_price = (int)$the_offered_services_price_details[0];
                    if ($query8->rowCount() > 0) {
                      $offered_services_price += $the_offered_services_price;
                    }
                    else{
                      $offered_services_price = 0;
                    }
                  }

                  $current_order['total_order_amount'] = ($current_order_sales_price * $current_order_quantity) + ($offered_services_price * $current_order_quantity);

                }
                else {
                  $current_order['total_order_amount'] = $current_order_sales_price * $current_order_quantity;
                }
              }
              else {
                $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND order_unique_id=:order_unique_id";
                $query7 = $this->conn->prepare($sql7);
                $query7->bindParam(":user_unique_id", $user_unique_id);
                $query7->bindParam(":order_unique_id", $order_unique_id);
                $query7->execute();

                if ($query7->rowCount() > 0) {
                  $the_order_services_details = $query7->fetchAll();
                  $offered_services_price = 0;

                  foreach ($the_order_services_details as $key => $value) {
                    $offered_service_unique_id = $value["offered_service_unique_id"];

                    $sql8 = "SELECT price FROM offered_services WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id AND status=:status";
                    $query8 = $this->conn->prepare($sql8);
                    $query8->bindParam(":unique_id", $offered_service_unique_id);
                    $query8->bindParam(":sub_product_unique_id", $sub_product_id);
                    $query8->bindParam(":status", $active);
                    $query8->execute();

                    $the_offered_services_price_details = $query8->fetch();
                    $the_offered_services_price = (int)$the_offered_services_price_details[0];
                    if ($query8->rowCount() > 0) {
                      $offered_services_price += $the_offered_services_price;
                    }
                    else{
                      $offered_services_price = 0;
                    }

                  }

                  $current_order['total_order_amount'] = ($current_order_price * $current_order_quantity) + ($offered_services_price * $current_order_quantity);

                }
                else {
                  $current_order['total_order_amount'] = $current_order_price * $current_order_quantity;
                }
              }

              $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND order_unique_id=:order_unique_id";
              $query7 = $this->conn->prepare($sql7);
              $query7->bindParam(":user_unique_id", $user_unique_id);
              $query7->bindParam(":order_unique_id", $order_unique_id);
              $query7->execute();

              if ($query7->rowCount() > 0) {
                $the_order_services_details = $query7->fetchAll();

                $current_order_offered_services = array();
                $current_order_offered_services_price = array();
                $current_order_offered_services_unique_id = array();

                $total_offered_services_amount = 0;

                foreach ($the_order_services_details as $key => $value) {
                  $offered_service_unique_id = $value["offered_service_unique_id"];
                  $sql8 = "SELECT price, service FROM offered_services WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id AND status=:status";
                  $query8 = $this->conn->prepare($sql8);
                  $query8->bindParam(":unique_id", $offered_service_unique_id);
                  $query8->bindParam(":sub_product_unique_id", $sub_product_id);
                  $query8->bindParam(":status", $active);
                  $query8->execute();

                  $the_offered_services_details = $query8->fetch();
                  $the_offered_services_price = (int)$the_offered_services_details[0];
                  $the_offered_services_service = $the_offered_services_details[1];

                  if ($query8->rowCount() > 0) {

                    $current_order_offered_services[] = $the_offered_services_service;

                    $current_order['order_offered_services'] = $current_order_offered_services;

                    $current_order_offered_services_price[] = $the_offered_services_price;

                    $total_offered_services_amount += $the_offered_services_price;

                    $current_order['order_offered_services_price'] = $current_order_offered_services_price;

                    $current_order_offered_services_unique_id[] = $offered_service_unique_id;

                    $current_order['order_offered_services_unique_ids'] = $current_order_offered_services_unique_id;

                  }
                  else{
                    $current_order['order_offered_services'] = null;
                    $current_order['order_offered_services_price'] = null;
                    $current_order['order_offered_services_unique_ids'] = null;
                  }
                }

                $current_order['order_offered_services_price_total_amount'] = $total_offered_services_amount * $current_order_quantity;

              }
              else {
                $current_order['order_offered_services'] = null;
                $current_order['order_offered_services_price'] = null;
                $current_order['order_offered_services_unique_ids'] = null;
                $current_order['order_offered_services_price_total_amount'] = null;
              }

              $the_total_order_amount = $current_order['total_order_amount'];

              $sqlOrderCoupon = "SELECT coupon_unique_id FROM order_coupons WHERE tracker_unique_id=:tracker_unique_id";
              $queryOrderCoupon = $this->conn->prepare($sqlOrderCoupon);
              $queryOrderCoupon->bindParam(":tracker_unique_id", $tracker_unique_id);
              $queryOrderCoupon->execute();

              $the_order_coupon_details = $queryOrderCoupon->rowCount() > 0 ? $queryOrderCoupon->fetch() : null;
              $the_coupon_unique_id = $the_order_coupon_details != null ? $the_order_coupon_details[0] : null;

              $sql3 = "SELECT percentage, current_count, name, unique_id, user_unique_id, sub_product_unique_id, code, mini_category_unique_id FROM coupons WHERE unique_id=:unique_id AND (user_unique_id=:user_unique_id OR sub_product_unique_id=:sub_product_unique_id OR mini_category_unique_id=:mini_category_unique_id) AND expiry_date >:today";
              $query3 = $this->conn->prepare($sql3);
              $query3->bindParam(":unique_id", $the_coupon_unique_id);
              $query3->bindParam(":user_unique_id", $user_unique_id);
              $query3->bindParam(":sub_product_unique_id", $sub_product_id);
              $query3->bindParam(":mini_category_unique_id", $mini_category_unique_id);
              $query3->bindParam(":today", $date_added);
              $query3->execute();

              if ($query3->rowCount() > 0) {
                $the_coupon_price_details = $query3->fetch();
                $the_coupon_percentage = (int)$the_coupon_price_details[0];
                $the_coupon_price = $current_order_sales_price != 0 ? (($current_order_sales_price * $the_coupon_percentage) / 100) * $current_order_quantity : (($current_order_price * $the_coupon_percentage) / 100) * $current_order_quantity;
                $the_coupon_count = (int)$the_coupon_price_details[1];
                $the_coupon_name = $the_coupon_price_details[2];
                $the_coupon_unique_id = $the_coupon_price_details[3];
                $the_coupon_user_unique_id = $the_coupon_price_details[4];
                $the_coupon_sub_product_unique_id = $the_coupon_price_details[5];
                $the_coupon_code = $the_coupon_price_details[6];
                $the_coupon_mini_category_unique_id = $the_coupon_price_details[7];

                if ($the_coupon_count != 0) {
                  if (($key + 1) <= $the_coupon_count && ($the_coupon_user_unique_id == $user_unique_id || $the_coupon_sub_product_unique_id == $sub_product_id || $the_coupon_mini_category_unique_id == $mini_category_unique_id)) {
                    $current_order['total_order_amount'] = $the_total_order_amount - $the_coupon_price;
                    $current_order['coupon_name'] = $the_coupon_name;
                    $current_order['coupon_code'] = $the_coupon_code;
                    $current_order['coupon_price'] = $the_coupon_price;
                    $current_order['coupon_status'] = "Available";
                  }
                  else {
                    $current_order['total_order_amount'] = $the_total_order_amount;
                    $current_order['coupon_name'] = $the_coupon_name;
                    $current_order['coupon_code'] = $the_coupon_code;
                    $current_order['coupon_price'] = $the_coupon_price;
                    $current_order['coupon_status'] = "Not available";
                  }
                }
                else {
                  $current_order['total_order_amount'] = $the_total_order_amount;
                  $current_order['coupon_name'] = $the_coupon_name;
                  $current_order['coupon_code'] = $the_coupon_code;
                  $current_order['coupon_price'] = $the_coupon_price;
                  $current_order['coupon_status'] = "Not available";
                }
              }
              else {
                $current_order['total_order_amount'] = $the_total_order_amount;
                $current_order['coupon_name'] = null;
                $current_order['coupon_code'] = null;
                $current_order['coupon_price'] = null;
                $current_order['coupon_status'] = "Not available";
              }

              $the_total_order_amount_with_coupon = $current_order['total_order_amount'];

              $sql4 = "SELECT price, city, state, country FROM shipping_fees WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id";
              $query4 = $this->conn->prepare($sql4);
              $query4->bindParam(":unique_id", $shipping_fee_unique_id);
              $query4->bindParam(":sub_product_unique_id", $sub_product_id);
              $query4->execute();

              if ($query4->rowCount() > 0) {
                $the_shipping_fee_price_details = $query4->fetch();
                $the_shipping_fee_price = (int)$the_shipping_fee_price_details[0];
                $the_shipping_fee_city = $the_shipping_fee_price_details[1];
                $the_shipping_fee_state = $the_shipping_fee_price_details[2];
                $the_shipping_fee_country = $the_shipping_fee_price_details[3];

                $current_order['total_order_amount'] = $the_total_order_amount_with_coupon + ($the_shipping_fee_price * $current_order_quantity);
                $current_order['shipping_to_city'] = $the_shipping_fee_city;
                $current_order['shipping_to_state'] = $the_shipping_fee_state;
                $current_order['shipping_to_country'] = $the_shipping_fee_country;
                $current_order['shipping_fee_price'] = $the_shipping_fee_price != 0 ? ($the_shipping_fee_price * $current_order_quantity) : 0;

              }
              else {
                $current_order['total_order_amount'] = $the_total_order_amount_with_coupon;
                $current_order['shipping_to_city'] = null;
                $current_order['shipping_to_state'] = null;
                $current_order['shipping_to_country'] = null;
                $current_order['shipping_fee_price'] = null;
              }

              $order_array[] = $current_order;
            }
            return $order_array;
          }
          else {
            $output['error'] = true;
            $output['message'] = "Empty";
            return $output;
          }

          $this->conn->commit();
        } catch (PDOException $e) {
          $this->conn->rollback();
          throw $e;
        }

      }

      function get_order_filter($start_date, $end_date){

        if (!in_array($start_date,$this->not_allowed_values) && !in_array($end_date,$this->not_allowed_values)) {

          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;
            $date_added = $this->functions->today;

            $order_array = array();

            $sql = "SELECT orders.id, orders.unique_id, orders.user_unique_id, orders.sub_product_unique_id, orders.tracker_unique_id, orders.shipment_unique_id, orders.coupon_unique_id, orders.shipping_fee_unique_id, orders.pickup_location, orders.quantity, orders.payment_method,
            orders.checked_out, orders.paid, orders.shipped, orders.disputed, orders.delivery_status, orders.added_date, orders.last_modified, orders.status,
            users.fullname as user_fullname, users.email as user_email, users.phone_number as user_phone_number, sub_products.name, sub_products.size, sub_products.stripped,
            sub_products.stock, sub_products.stock_remaining, sub_products.price, sub_products.sales_price, sub_products.favorites, products.mini_category_unique_id FROM orders INNER JOIN users ON orders.user_unique_id = users.unique_id LEFT JOIN sub_products ON orders.sub_product_unique_id = sub_products.unique_id
            LEFT JOIN products ON sub_products.product_unique_id = products.unique_id WHERE orders.added_date >:start_date AND (orders.added_date <:end_date OR orders.added_date=:end_date) ORDER BY orders.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":start_date", $start_date);
            $query->bindParam(":end_date", $end_date);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_order = array();
                $current_order['id'] = $value['id'];
                $current_order['unique_id'] = $value['unique_id'];
                $current_order['user_unique_id'] = $value['user_unique_id'];
                $current_order['sub_product_unique_id'] = $value['sub_product_unique_id'];
                $current_order['tracker_unique_id'] = $value['tracker_unique_id'];
                $current_order['shipment_unique_id'] = $value['shipment_unique_id'];
                $current_order['quantity'] = $value['quantity'];
                $current_order['payment_method'] = $value['payment_method'];
                $current_order['checked_out'] = $value['checked_out'];
                $current_order['paid'] = $value['paid'];
                $current_order['shipped'] = $value['shipped'];
                $current_order['disputed'] = $value['disputed'];
                $current_order['delivery_status'] = $value['delivery_status'];
                $current_order['added_date'] = $value['added_date'];
                $current_order['last_modified'] = $value['last_modified'];
                $current_order['status'] = $value['status'];
                $current_order['user_fullname'] = $value['user_fullname'];
                $current_order['user_email'] = $value['user_email'];
                $current_order['user_phone_number'] = $value['user_phone_number'];
                $current_order['name'] = $value['name'];
                $current_order['size'] = $value['size'];
                $current_order['stripped'] = $value['stripped'];
                $current_order['stock'] = $value['stock'];
                $current_order['stock_remaining'] = $value['stock_remaining'];
                $current_order['price'] = $value['price'];
                $current_order['sales_price'] = $value['sales_price'];
                $current_order['favorites'] = $value['favorites'];

                $sub_product_id = $value['sub_product_unique_id'];
                $order_unique_id = $value['unique_id'];
                $user_unique_id = $value['user_unique_id'];
                $tracker_unique_id = $value['tracker_unique_id'];
                $shipping_fee_unique_id = $value['shipping_fee_unique_id'];
                $mini_category_unique_id = $value['mini_category_unique_id'];

                $current_order_price = (int)$value['price'];
                $current_order_sales_price = (int)$value['sales_price'];
                $current_order_quantity = (int)$value['quantity'];

                $sql2 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id LIMIT 1";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":sub_product_unique_id", $sub_product_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_order_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_order_images[] = $image_value['image'];
                  }

                  $current_order['order_product_images'] = $current_order_images;
                }
                else{
                  $current_order['order_product_images'] = null;
                }

                if ($current_order_sales_price != 0) {
                  $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND order_unique_id=:order_unique_id";
                  $query7 = $this->conn->prepare($sql7);
                  $query7->bindParam(":user_unique_id", $user_unique_id);
                  $query7->bindParam(":order_unique_id", $order_unique_id);
                  $query7->execute();

                  if ($query7->rowCount() > 0) {
                    $the_order_services_details = $query7->fetchAll();
                    $offered_services_price = 0;

                    foreach ($the_order_services_details as $key => $value) {
                      $offered_service_unique_id = $value["offered_service_unique_id"];
                      $sql8 = "SELECT price FROM offered_services WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id AND status=:status";
                      $query8 = $this->conn->prepare($sql8);
                      $query8->bindParam(":unique_id", $offered_service_unique_id);
                      $query8->bindParam(":sub_product_unique_id", $sub_product_id);
                      $query8->bindParam(":status", $active);
                      $query8->execute();

                      $the_offered_services_price_details = $query8->fetch();
                      $the_offered_services_price = (int)$the_offered_services_price_details[0];
                      if ($query8->rowCount() > 0) {
                        $offered_services_price += $the_offered_services_price;
                      }
                      else{
                        $offered_services_price = 0;
                      }
                    }

                    $current_order['total_order_amount'] = ($current_order_sales_price * $current_order_quantity) + ($offered_services_price * $current_order_quantity);

                  }
                  else {
                    $current_order['total_order_amount'] = $current_order_sales_price * $current_order_quantity;
                  }
                }
                else {
                  $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND order_unique_id=:order_unique_id";
                  $query7 = $this->conn->prepare($sql7);
                  $query7->bindParam(":user_unique_id", $user_unique_id);
                  $query7->bindParam(":order_unique_id", $order_unique_id);
                  $query7->execute();

                  if ($query7->rowCount() > 0) {
                    $the_order_services_details = $query7->fetchAll();
                    $offered_services_price = 0;

                    foreach ($the_order_services_details as $key => $value) {
                      $offered_service_unique_id = $value["offered_service_unique_id"];

                      $sql8 = "SELECT price FROM offered_services WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id AND status=:status";
                      $query8 = $this->conn->prepare($sql8);
                      $query8->bindParam(":unique_id", $offered_service_unique_id);
                      $query8->bindParam(":sub_product_unique_id", $sub_product_id);
                      $query8->bindParam(":status", $active);
                      $query8->execute();

                      $the_offered_services_price_details = $query8->fetch();
                      $the_offered_services_price = (int)$the_offered_services_price_details[0];
                      if ($query8->rowCount() > 0) {
                        $offered_services_price += $the_offered_services_price;
                      }
                      else{
                        $offered_services_price = 0;
                      }

                    }

                    $current_order['total_order_amount'] = ($current_order_price * $current_order_quantity) + ($offered_services_price * $current_order_quantity);

                  }
                  else {
                    $current_order['total_order_amount'] = $current_order_price * $current_order_quantity;
                  }
                }

                $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND order_unique_id=:order_unique_id";
                $query7 = $this->conn->prepare($sql7);
                $query7->bindParam(":user_unique_id", $user_unique_id);
                $query7->bindParam(":order_unique_id", $order_unique_id);
                $query7->execute();

                if ($query7->rowCount() > 0) {
                  $the_order_services_details = $query7->fetchAll();

                  $current_order_offered_services = array();
                  $current_order_offered_services_price = array();
                  $current_order_offered_services_unique_id = array();

                  $total_offered_services_amount = 0;

                  foreach ($the_order_services_details as $key => $value) {
                    $offered_service_unique_id = $value["offered_service_unique_id"];
                    $sql8 = "SELECT price, service FROM offered_services WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id AND status=:status";
                    $query8 = $this->conn->prepare($sql8);
                    $query8->bindParam(":unique_id", $offered_service_unique_id);
                    $query8->bindParam(":sub_product_unique_id", $sub_product_id);
                    $query8->bindParam(":status", $active);
                    $query8->execute();

                    $the_offered_services_details = $query8->fetch();
                    $the_offered_services_price = (int)$the_offered_services_details[0];
                    $the_offered_services_service = $the_offered_services_details[1];

                    if ($query8->rowCount() > 0) {

                      $current_order_offered_services[] = $the_offered_services_service;

                      $current_order['order_offered_services'] = $current_order_offered_services;

                      $current_order_offered_services_price[] = $the_offered_services_price;

                      $total_offered_services_amount += $the_offered_services_price;

                      $current_order['order_offered_services_price'] = $current_order_offered_services_price;

                      $current_order_offered_services_unique_id[] = $offered_service_unique_id;

                      $current_order['order_offered_services_unique_ids'] = $current_order_offered_services_unique_id;

                    }
                    else{
                      $current_order['order_offered_services'] = null;
                      $current_order['order_offered_services_price'] = null;
                      $current_order['order_offered_services_unique_ids'] = null;
                    }
                  }

                  $current_order['order_offered_services_price_total_amount'] = $total_offered_services_amount * $current_order_quantity;

                }
                else {
                  $current_order['order_offered_services'] = null;
                  $current_order['order_offered_services_price'] = null;
                  $current_order['order_offered_services_unique_ids'] = null;
                  $current_order['order_offered_services_price_total_amount'] = null;
                }

                $the_total_order_amount = $current_order['total_order_amount'];

                $sqlOrderCoupon = "SELECT coupon_unique_id FROM order_coupons WHERE tracker_unique_id=:tracker_unique_id";
                $queryOrderCoupon = $this->conn->prepare($sqlOrderCoupon);
                $queryOrderCoupon->bindParam(":tracker_unique_id", $tracker_unique_id);
                $queryOrderCoupon->execute();

                $the_order_coupon_details = $queryOrderCoupon->rowCount() > 0 ? $queryOrderCoupon->fetch() : null;
                $the_coupon_unique_id = $the_order_coupon_details != null ? $the_order_coupon_details[0] : null;

                $sql3 = "SELECT percentage, current_count, name, unique_id, user_unique_id, sub_product_unique_id, code, mini_category_unique_id FROM coupons WHERE unique_id=:unique_id AND (user_unique_id=:user_unique_id OR sub_product_unique_id=:sub_product_unique_id OR mini_category_unique_id=:mini_category_unique_id) AND expiry_date >:today";
                $query3 = $this->conn->prepare($sql3);
                $query3->bindParam(":unique_id", $the_coupon_unique_id);
                $query3->bindParam(":user_unique_id", $user_unique_id);
                $query3->bindParam(":sub_product_unique_id", $sub_product_id);
                $query3->bindParam(":mini_category_unique_id", $mini_category_unique_id);
                $query3->bindParam(":today", $date_added);
                $query3->execute();

                if ($query3->rowCount() > 0) {
                  $the_coupon_price_details = $query3->fetch();
                  $the_coupon_percentage = (int)$the_coupon_price_details[0];
                  $the_coupon_price = $current_order_sales_price != 0 ? (($current_order_sales_price * $the_coupon_percentage) / 100) * $current_order_quantity : (($current_order_price * $the_coupon_percentage) / 100) * $current_order_quantity;
                  $the_coupon_count = (int)$the_coupon_price_details[1];
                  $the_coupon_name = $the_coupon_price_details[2];
                  $the_coupon_unique_id = $the_coupon_price_details[3];
                  $the_coupon_user_unique_id = $the_coupon_price_details[4];
                  $the_coupon_sub_product_unique_id = $the_coupon_price_details[5];
                  $the_coupon_code = $the_coupon_price_details[6];
                  $the_coupon_mini_category_unique_id = $the_coupon_price_details[7];

                  if ($the_coupon_count != 0) {
                    if (($key + 1) <= $the_coupon_count && ($the_coupon_user_unique_id == $user_unique_id || $the_coupon_sub_product_unique_id == $sub_product_id || $the_coupon_mini_category_unique_id == $mini_category_unique_id)) {
                      $current_order['total_order_amount'] = $the_total_order_amount - $the_coupon_price;
                      $current_order['coupon_name'] = $the_coupon_name;
                      $current_order['coupon_code'] = $the_coupon_code;
                      $current_order['coupon_price'] = $the_coupon_price;
                      $current_order['coupon_status'] = "Available";
                    }
                    else {
                      $current_order['total_order_amount'] = $the_total_order_amount;
                      $current_order['coupon_name'] = $the_coupon_name;
                      $current_order['coupon_code'] = $the_coupon_code;
                      $current_order['coupon_price'] = $the_coupon_price;
                      $current_order['coupon_status'] = "Not available";
                    }
                  }
                  else {
                    $current_order['total_order_amount'] = $the_total_order_amount;
                    $current_order['coupon_name'] = $the_coupon_name;
                    $current_order['coupon_code'] = $the_coupon_code;
                    $current_order['coupon_price'] = $the_coupon_price;
                    $current_order['coupon_status'] = "Not available";
                  }
                }
                else {
                  $current_order['total_order_amount'] = $the_total_order_amount;
                  $current_order['coupon_name'] = null;
                  $current_order['coupon_code'] = null;
                  $current_order['coupon_price'] = null;
                  $current_order['coupon_status'] = "Not available";
                }

                $the_total_order_amount_with_coupon = $current_order['total_order_amount'];

                $sql4 = "SELECT price, city, state, country FROM shipping_fees WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id";
                $query4 = $this->conn->prepare($sql4);
                $query4->bindParam(":unique_id", $shipping_fee_unique_id);
                $query4->bindParam(":sub_product_unique_id", $sub_product_id);
                $query4->execute();

                if ($query4->rowCount() > 0) {
                  $the_shipping_fee_price_details = $query4->fetch();
                  $the_shipping_fee_price = (int)$the_shipping_fee_price_details[0];
                  $the_shipping_fee_city = $the_shipping_fee_price_details[1];
                  $the_shipping_fee_state = $the_shipping_fee_price_details[2];
                  $the_shipping_fee_country = $the_shipping_fee_price_details[3];

                  $current_order['total_order_amount'] = $the_total_order_amount_with_coupon + ($the_shipping_fee_price * $current_order_quantity);
                  $current_order['shipping_to_city'] = $the_shipping_fee_city;
                  $current_order['shipping_to_state'] = $the_shipping_fee_state;
                  $current_order['shipping_to_country'] = $the_shipping_fee_country;
                  $current_order['shipping_fee_price'] = $the_shipping_fee_price != 0 ? ($the_shipping_fee_price * $current_order_quantity) : 0;

                }
                else {
                  $current_order['total_order_amount'] = $the_total_order_amount_with_coupon;
                  $current_order['shipping_to_city'] = null;
                  $current_order['shipping_to_state'] = null;
                  $current_order['shipping_to_country'] = null;
                  $current_order['shipping_fee_price'] = null;
                }

                $order_array[] = $current_order;
              }
              return $order_array;
            }
            else {
              $output['error'] = true;
              $output['message'] = "Empty";
              return $output;
            }

            $this->conn->commit();
          } catch (PDOException $e) {
            $this->conn->rollback();
            throw $e;
          }

        }
        else {
          $output['error'] = true;
          $output['message'] = "All fields are required";
          return $output;
        }

      }

      function get_user_orders($user_unique_id){

        if (!in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;
            $date_added = $this->functions->today;

            $order_array = array();

            $sql = "SELECT orders.id, orders.unique_id, orders.user_unique_id, orders.sub_product_unique_id, orders.tracker_unique_id, orders.shipment_unique_id, orders.coupon_unique_id, orders.shipping_fee_unique_id, orders.pickup_location, orders.quantity, orders.payment_method,
            orders.checked_out, orders.paid, orders.shipped, orders.disputed, orders.delivery_status, orders.added_date, orders.last_modified, orders.status,
            users.fullname as user_fullname, users.email as user_email, users.phone_number as user_phone_number, sub_products.name, sub_products.size, sub_products.stripped,
            sub_products.stock, sub_products.stock_remaining, sub_products.price, sub_products.sales_price, sub_products.favorites, products.mini_category_unique_id FROM orders INNER JOIN users ON orders.user_unique_id = users.unique_id LEFT JOIN sub_products ON orders.sub_product_unique_id = sub_products.unique_id
            LEFT JOIN products ON sub_products.product_unique_id = products.unique_id WHERE orders.user_unique_id =:user_unique_id ORDER BY orders.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_order = array();
                $current_order['id'] = $value['id'];
                $current_order['unique_id'] = $value['unique_id'];
                $current_order['user_unique_id'] = $value['user_unique_id'];
                $current_order['sub_product_unique_id'] = $value['sub_product_unique_id'];
                $current_order['tracker_unique_id'] = $value['tracker_unique_id'];
                $current_order['shipment_unique_id'] = $value['shipment_unique_id'];
                $current_order['quantity'] = $value['quantity'];
                $current_order['payment_method'] = $value['payment_method'];
                $current_order['checked_out'] = $value['checked_out'];
                $current_order['paid'] = $value['paid'];
                $current_order['shipped'] = $value['shipped'];
                $current_order['disputed'] = $value['disputed'];
                $current_order['delivery_status'] = $value['delivery_status'];
                $current_order['added_date'] = $value['added_date'];
                $current_order['last_modified'] = $value['last_modified'];
                $current_order['status'] = $value['status'];
                $current_order['user_fullname'] = $value['user_fullname'];
                $current_order['user_email'] = $value['user_email'];
                $current_order['user_phone_number'] = $value['user_phone_number'];
                $current_order['name'] = $value['name'];
                $current_order['size'] = $value['size'];
                $current_order['stripped'] = $value['stripped'];
                $current_order['stock'] = $value['stock'];
                $current_order['stock_remaining'] = $value['stock_remaining'];
                $current_order['price'] = $value['price'];
                $current_order['sales_price'] = $value['sales_price'];
                $current_order['favorites'] = $value['favorites'];

                $sub_product_id = $value['sub_product_unique_id'];
                $order_unique_id = $value['unique_id'];
                $tracker_unique_id = $value['tracker_unique_id'];
                $shipping_fee_unique_id = $value['shipping_fee_unique_id'];
                $mini_category_unique_id = $value['mini_category_unique_id'];

                $current_order_price = (int)$value['price'];
                $current_order_sales_price = (int)$value['sales_price'];
                $current_order_quantity = (int)$value['quantity'];

                $sql2 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id LIMIT 1";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":sub_product_unique_id", $sub_product_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_order_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_order_images[] = $image_value['image'];
                  }

                  $current_order['order_product_images'] = $current_order_images;
                }
                else{
                  $current_order['order_product_images'] = null;
                }

                if ($current_order_sales_price != 0) {
                  $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND order_unique_id=:order_unique_id";
                  $query7 = $this->conn->prepare($sql7);
                  $query7->bindParam(":user_unique_id", $user_unique_id);
                  $query7->bindParam(":order_unique_id", $order_unique_id);
                  $query7->execute();

                  if ($query7->rowCount() > 0) {
                    $the_order_services_details = $query7->fetchAll();
                    $offered_services_price = 0;

                    foreach ($the_order_services_details as $key => $value) {
                      $offered_service_unique_id = $value["offered_service_unique_id"];
                      $sql8 = "SELECT price FROM offered_services WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id AND status=:status";
                      $query8 = $this->conn->prepare($sql8);
                      $query8->bindParam(":unique_id", $offered_service_unique_id);
                      $query8->bindParam(":sub_product_unique_id", $sub_product_id);
                      $query8->bindParam(":status", $active);
                      $query8->execute();

                      $the_offered_services_price_details = $query8->fetch();
                      $the_offered_services_price = (int)$the_offered_services_price_details[0];
                      if ($query8->rowCount() > 0) {
                        $offered_services_price += $the_offered_services_price;
                      }
                      else{
                        $offered_services_price = 0;
                      }
                    }

                    $current_order['total_order_amount'] = ($current_order_sales_price * $current_order_quantity) + ($offered_services_price * $current_order_quantity);

                  }
                  else {
                    $current_order['total_order_amount'] = $current_order_sales_price * $current_order_quantity;
                  }
                }
                else {
                  $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND order_unique_id=:order_unique_id";
                  $query7 = $this->conn->prepare($sql7);
                  $query7->bindParam(":user_unique_id", $user_unique_id);
                  $query7->bindParam(":order_unique_id", $order_unique_id);
                  $query7->execute();

                  if ($query7->rowCount() > 0) {
                    $the_order_services_details = $query7->fetchAll();
                    $offered_services_price = 0;

                    foreach ($the_order_services_details as $key => $value) {
                      $offered_service_unique_id = $value["offered_service_unique_id"];

                      $sql8 = "SELECT price FROM offered_services WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id AND status=:status";
                      $query8 = $this->conn->prepare($sql8);
                      $query8->bindParam(":unique_id", $offered_service_unique_id);
                      $query8->bindParam(":sub_product_unique_id", $sub_product_id);
                      $query8->bindParam(":status", $active);
                      $query8->execute();

                      $the_offered_services_price_details = $query8->fetch();
                      $the_offered_services_price = (int)$the_offered_services_price_details[0];
                      if ($query8->rowCount() > 0) {
                        $offered_services_price += $the_offered_services_price;
                      }
                      else{
                        $offered_services_price = 0;
                      }

                    }

                    $current_order['total_order_amount'] = ($current_order_price * $current_order_quantity) + ($offered_services_price * $current_order_quantity);

                  }
                  else {
                    $current_order['total_order_amount'] = $current_order_price * $current_order_quantity;
                  }
                }

                $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND order_unique_id=:order_unique_id";
                $query7 = $this->conn->prepare($sql7);
                $query7->bindParam(":user_unique_id", $user_unique_id);
                $query7->bindParam(":order_unique_id", $order_unique_id);
                $query7->execute();

                if ($query7->rowCount() > 0) {
                  $the_order_services_details = $query7->fetchAll();

                  $current_order_offered_services = array();
                  $current_order_offered_services_price = array();
                  $current_order_offered_services_unique_id = array();

                  $total_offered_services_amount = 0;

                  foreach ($the_order_services_details as $key => $value) {
                    $offered_service_unique_id = $value["offered_service_unique_id"];
                    $sql8 = "SELECT price, service FROM offered_services WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id AND status=:status";
                    $query8 = $this->conn->prepare($sql8);
                    $query8->bindParam(":unique_id", $offered_service_unique_id);
                    $query8->bindParam(":sub_product_unique_id", $sub_product_id);
                    $query8->bindParam(":status", $active);
                    $query8->execute();

                    $the_offered_services_details = $query8->fetch();
                    $the_offered_services_price = (int)$the_offered_services_details[0];
                    $the_offered_services_service = $the_offered_services_details[1];

                    if ($query8->rowCount() > 0) {

                      $current_order_offered_services[] = $the_offered_services_service;

                      $current_order['order_offered_services'] = $current_order_offered_services;

                      $current_order_offered_services_price[] = $the_offered_services_price;

                      $total_offered_services_amount += $the_offered_services_price;

                      $current_order['order_offered_services_price'] = $current_order_offered_services_price;

                      $current_order_offered_services_unique_id[] = $offered_service_unique_id;

                      $current_order['order_offered_services_unique_ids'] = $current_order_offered_services_unique_id;

                    }
                    else{
                      $current_order['order_offered_services'] = null;
                      $current_order['order_offered_services_price'] = null;
                      $current_order['order_offered_services_unique_ids'] = null;
                    }
                  }

                  $current_order['order_offered_services_price_total_amount'] = $total_offered_services_amount * $current_order_quantity;

                }
                else {
                  $current_order['order_offered_services'] = null;
                  $current_order['order_offered_services_price'] = null;
                  $current_order['order_offered_services_unique_ids'] = null;
                  $current_order['order_offered_services_price_total_amount'] = null;
                }

                $the_total_order_amount = $current_order['total_order_amount'];

                $sqlOrderCoupon = "SELECT coupon_unique_id FROM order_coupons WHERE tracker_unique_id=:tracker_unique_id";
                $queryOrderCoupon = $this->conn->prepare($sqlOrderCoupon);
                $queryOrderCoupon->bindParam(":tracker_unique_id", $tracker_unique_id);
                $queryOrderCoupon->execute();

                $the_order_coupon_details = $queryOrderCoupon->rowCount() > 0 ? $queryOrderCoupon->fetch() : null;
                $the_coupon_unique_id = $the_order_coupon_details != null ? $the_order_coupon_details[0] : null;

                $sql3 = "SELECT percentage, current_count, name, unique_id, user_unique_id, sub_product_unique_id, code, mini_category_unique_id FROM coupons WHERE unique_id=:unique_id AND (user_unique_id=:user_unique_id OR sub_product_unique_id=:sub_product_unique_id OR mini_category_unique_id=:mini_category_unique_id) AND expiry_date >:today";
                $query3 = $this->conn->prepare($sql3);
                $query3->bindParam(":unique_id", $the_coupon_unique_id);
                $query3->bindParam(":user_unique_id", $user_unique_id);
                $query3->bindParam(":sub_product_unique_id", $sub_product_id);
                $query3->bindParam(":mini_category_unique_id", $mini_category_unique_id);
                $query3->bindParam(":today", $date_added);
                $query3->execute();

                if ($query3->rowCount() > 0) {
                  $the_coupon_price_details = $query3->fetch();
                  $the_coupon_percentage = (int)$the_coupon_price_details[0];
                  $the_coupon_price = $current_order_sales_price != 0 ? (($current_order_sales_price * $the_coupon_percentage) / 100) * $current_order_quantity : (($current_order_price * $the_coupon_percentage) / 100) * $current_order_quantity;
                  $the_coupon_count = (int)$the_coupon_price_details[1];
                  $the_coupon_name = $the_coupon_price_details[2];
                  $the_coupon_unique_id = $the_coupon_price_details[3];
                  $the_coupon_user_unique_id = $the_coupon_price_details[4];
                  $the_coupon_sub_product_unique_id = $the_coupon_price_details[5];
                  $the_coupon_code = $the_coupon_price_details[6];
                  $the_coupon_mini_category_unique_id = $the_coupon_price_details[7];

                  if ($the_coupon_count != 0) {
                    if (($key + 1) <= $the_coupon_count && ($the_coupon_user_unique_id == $user_unique_id || $the_coupon_sub_product_unique_id == $sub_product_id || $the_coupon_mini_category_unique_id == $mini_category_unique_id)) {
                      $current_order['total_order_amount'] = $the_total_order_amount - $the_coupon_price;
                      $current_order['coupon_name'] = $the_coupon_name;
                      $current_order['coupon_code'] = $the_coupon_code;
                      $current_order['coupon_price'] = $the_coupon_price;
                      $current_order['coupon_status'] = "Available";
                    }
                    else {
                      $current_order['total_order_amount'] = $the_total_order_amount;
                      $current_order['coupon_name'] = $the_coupon_name;
                      $current_order['coupon_code'] = $the_coupon_code;
                      $current_order['coupon_price'] = $the_coupon_price;
                      $current_order['coupon_status'] = "Not available";
                    }
                  }
                  else {
                    $current_order['total_order_amount'] = $the_total_order_amount;
                    $current_order['coupon_name'] = $the_coupon_name;
                    $current_order['coupon_code'] = $the_coupon_code;
                    $current_order['coupon_price'] = $the_coupon_price;
                    $current_order['coupon_status'] = "Not available";
                  }
                }
                else {
                  $current_order['total_order_amount'] = $the_total_order_amount;
                  $current_order['coupon_name'] = null;
                  $current_order['coupon_code'] = null;
                  $current_order['coupon_price'] = null;
                  $current_order['coupon_status'] = "Not available";
                }

                $the_total_order_amount_with_coupon = $current_order['total_order_amount'];

                $sql4 = "SELECT price, city, state, country FROM shipping_fees WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id";
                $query4 = $this->conn->prepare($sql4);
                $query4->bindParam(":unique_id", $shipping_fee_unique_id);
                $query4->bindParam(":sub_product_unique_id", $sub_product_id);
                $query4->execute();

                if ($query4->rowCount() > 0) {
                  $the_shipping_fee_price_details = $query4->fetch();
                  $the_shipping_fee_price = (int)$the_shipping_fee_price_details[0];
                  $the_shipping_fee_city = $the_shipping_fee_price_details[1];
                  $the_shipping_fee_state = $the_shipping_fee_price_details[2];
                  $the_shipping_fee_country = $the_shipping_fee_price_details[3];

                  $current_order['total_order_amount'] = $the_total_order_amount_with_coupon + ($the_shipping_fee_price * $current_order_quantity);
                  $current_order['shipping_to_city'] = $the_shipping_fee_city;
                  $current_order['shipping_to_state'] = $the_shipping_fee_state;
                  $current_order['shipping_to_country'] = $the_shipping_fee_country;
                  $current_order['shipping_fee_price'] = $the_shipping_fee_price != 0 ? ($the_shipping_fee_price * $current_order_quantity) : 0;

                }
                else {
                  $current_order['total_order_amount'] = $the_total_order_amount_with_coupon;
                  $current_order['shipping_to_city'] = null;
                  $current_order['shipping_to_state'] = null;
                  $current_order['shipping_to_country'] = null;
                  $current_order['shipping_fee_price'] = null;
                }

                $order_array[] = $current_order;
              }
              return $order_array;
            }
            else {
              $output['error'] = true;
              $output['message'] = "Empty";
              return $output;
            }

            $this->conn->commit();
          } catch (PDOException $e) {
            $this->conn->rollback();
            throw $e;
            $output['error'] = true;
            $output['message'] = "Critical error occured";
            return $output;
          }
        }
        else {
          $output['error'] = true;
          $output['message'] = "All fields are required";
          return $output;
        }

      }

      function get_user_orders_for_users($user_unique_id){

        if (!in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;
            $date_added = $this->functions->today;

            $order_array = array();

            $sql = "SELECT orders.unique_id, orders.user_unique_id, orders.sub_product_unique_id, orders.tracker_unique_id, orders.shipment_unique_id, orders.coupon_unique_id, orders.shipping_fee_unique_id, orders.pickup_location, orders.quantity, orders.payment_method,
            orders.checked_out, orders.paid, orders.shipped, orders.disputed, orders.delivery_status, orders.added_date, orders.last_modified, orders.status,
            sub_products.name, sub_products.size, sub_products.stripped, sub_products.stock, sub_products.stock_remaining, sub_products.price, sub_products.sales_price, sub_products.favorites, products.mini_category_unique_id FROM orders
            LEFT JOIN sub_products ON orders.sub_product_unique_id = sub_products.unique_id
            LEFT JOIN products ON sub_products.product_unique_id = products.unique_id WHERE orders.user_unique_id =:user_unique_id ORDER BY orders.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_order = array();
                $current_order['unique_id'] = $value['unique_id'];
                $current_order['user_unique_id'] = $value['user_unique_id'];
                $current_order['sub_product_unique_id'] = $value['sub_product_unique_id'];
                $current_order['tracker_unique_id'] = $value['tracker_unique_id'];
                $current_order['shipment_unique_id'] = $value['shipment_unique_id'];
                $current_order['quantity'] = $value['quantity'];
                $current_order['payment_method'] = $value['payment_method'];
                $current_order['checked_out'] = $value['checked_out'];
                $current_order['paid'] = $value['paid'];
                $current_order['shipped'] = $value['shipped'];
                $current_order['disputed'] = $value['disputed'];
                $current_order['delivery_status'] = $value['delivery_status'];
                $current_order['added_date'] = $value['added_date'];
                $current_order['last_modified'] = $value['last_modified'];
                $current_order['status'] = $value['status'];
                $current_order['name'] = $value['name'];
                $current_order['size'] = $value['size'];
                $current_order['stripped'] = $value['stripped'];
                $current_order['stock'] = $value['stock'];
                $current_order['stock_remaining'] = $value['stock_remaining'];
                $current_order['price'] = $value['price'];
                $current_order['sales_price'] = $value['sales_price'];
                $current_order['favorites'] = $value['favorites'];

                $sub_product_id = $value['sub_product_unique_id'];
                $order_unique_id = $value['unique_id'];
                $tracker_unique_id = $value['tracker_unique_id'];
                $shipping_fee_unique_id = $value['shipping_fee_unique_id'];
                $mini_category_unique_id = $value['mini_category_unique_id'];

                $current_order_price = (int)$value['price'];
                $current_order_sales_price = (int)$value['sales_price'];
                $current_order_quantity = (int)$value['quantity'];

                $sql2 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id LIMIT 1";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":sub_product_unique_id", $sub_product_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_order_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_order_images[] = $image_value['image'];
                  }

                  $current_order['order_product_images'] = $current_order_images;
                }
                else{
                  $current_order['order_product_images'] = null;
                }

                if ($current_order_sales_price != 0) {
                  $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND order_unique_id=:order_unique_id";
                  $query7 = $this->conn->prepare($sql7);
                  $query7->bindParam(":user_unique_id", $user_unique_id);
                  $query7->bindParam(":order_unique_id", $order_unique_id);
                  $query7->execute();

                  if ($query7->rowCount() > 0) {
                    $the_order_services_details = $query7->fetchAll();
                    $offered_services_price = 0;

                    foreach ($the_order_services_details as $key => $value) {
                      $offered_service_unique_id = $value["offered_service_unique_id"];
                      $sql8 = "SELECT price FROM offered_services WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id AND status=:status";
                      $query8 = $this->conn->prepare($sql8);
                      $query8->bindParam(":unique_id", $offered_service_unique_id);
                      $query8->bindParam(":sub_product_unique_id", $sub_product_id);
                      $query8->bindParam(":status", $active);
                      $query8->execute();

                      $the_offered_services_price_details = $query8->fetch();
                      $the_offered_services_price = (int)$the_offered_services_price_details[0];
                      if ($query8->rowCount() > 0) {
                        $offered_services_price += $the_offered_services_price;
                      }
                      else{
                        $offered_services_price = 0;
                      }
                    }

                    $current_order['total_order_amount'] = ($current_order_sales_price * $current_order_quantity) + ($offered_services_price * $current_order_quantity);

                  }
                  else {
                    $current_order['total_order_amount'] = $current_order_sales_price * $current_order_quantity;
                  }
                }
                else {
                  $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND order_unique_id=:order_unique_id";
                  $query7 = $this->conn->prepare($sql7);
                  $query7->bindParam(":user_unique_id", $user_unique_id);
                  $query7->bindParam(":order_unique_id", $order_unique_id);
                  $query7->execute();

                  if ($query7->rowCount() > 0) {
                    $the_order_services_details = $query7->fetchAll();
                    $offered_services_price = 0;

                    foreach ($the_order_services_details as $key => $value) {
                      $offered_service_unique_id = $value["offered_service_unique_id"];

                      $sql8 = "SELECT price FROM offered_services WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id AND status=:status";
                      $query8 = $this->conn->prepare($sql8);
                      $query8->bindParam(":unique_id", $offered_service_unique_id);
                      $query8->bindParam(":sub_product_unique_id", $sub_product_id);
                      $query8->bindParam(":status", $active);
                      $query8->execute();

                      $the_offered_services_price_details = $query8->fetch();
                      $the_offered_services_price = (int)$the_offered_services_price_details[0];
                      if ($query8->rowCount() > 0) {
                        $offered_services_price += $the_offered_services_price;
                      }
                      else{
                        $offered_services_price = 0;
                      }

                    }

                    $current_order['total_order_amount'] = ($current_order_price * $current_order_quantity) + ($offered_services_price * $current_order_quantity);

                  }
                  else {
                    $current_order['total_order_amount'] = $current_order_price * $current_order_quantity;
                  }
                }

                $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND order_unique_id=:order_unique_id";
                $query7 = $this->conn->prepare($sql7);
                $query7->bindParam(":user_unique_id", $user_unique_id);
                $query7->bindParam(":order_unique_id", $order_unique_id);
                $query7->execute();

                if ($query7->rowCount() > 0) {
                  $the_order_services_details = $query7->fetchAll();

                  $current_order_offered_services = array();
                  $current_order_offered_services_price = array();
                  $current_order_offered_services_unique_id = array();

                  $total_offered_services_amount = 0;

                  foreach ($the_order_services_details as $key => $value) {
                    $offered_service_unique_id = $value["offered_service_unique_id"];
                    $sql8 = "SELECT price, service FROM offered_services WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id AND status=:status";
                    $query8 = $this->conn->prepare($sql8);
                    $query8->bindParam(":unique_id", $offered_service_unique_id);
                    $query8->bindParam(":sub_product_unique_id", $sub_product_id);
                    $query8->bindParam(":status", $active);
                    $query8->execute();

                    $the_offered_services_details = $query8->fetch();
                    $the_offered_services_price = (int)$the_offered_services_details[0];
                    $the_offered_services_service = $the_offered_services_details[1];

                    if ($query8->rowCount() > 0) {

                      $current_order_offered_services['service'] = $the_offered_services_service;

                      $current_order_offered_services['price'] = $the_offered_services_price;

                      $total_offered_services_amount += $the_offered_services_price;

                      // $current_order['order_offered_services_price'] = $current_order_offered_services_price;

                      $current_order_offered_services['unique_id'] = $offered_service_unique_id;

                      // $current_order['order_offered_services_unique_ids'] = $current_order_offered_services_unique_id;

                      $current_order['order_offered_services'][$key] = $current_order_offered_services;

                    }
                    else{
                      $current_order['order_offered_services'] = null;
                      // $current_order['order_offered_services_price'] = null;
                      // $current_order['order_offered_services_unique_ids'] = null;
                    }
                  }

                  $current_order['order_offered_services_price_total_amount'] = $total_offered_services_amount * $current_order_quantity;

                }
                else {
                  $current_order['order_offered_services'] = null;
                  // $current_order['order_offered_services_price'] = null;
                  // $current_order['order_offered_services_unique_ids'] = null;
                  $current_order['order_offered_services_price_total_amount'] = null;
                }

                $the_total_order_amount = $current_order['total_order_amount'];

                $sqlOrderCoupon = "SELECT coupon_unique_id FROM order_coupons WHERE tracker_unique_id=:tracker_unique_id";
                $queryOrderCoupon = $this->conn->prepare($sqlOrderCoupon);
                $queryOrderCoupon->bindParam(":tracker_unique_id", $tracker_unique_id);
                $queryOrderCoupon->execute();

                $the_order_coupon_details = $queryOrderCoupon->rowCount() > 0 ? $queryOrderCoupon->fetch() : null;
                $the_coupon_unique_id = $the_order_coupon_details != null ? $the_order_coupon_details[0] : null;

                $sql3 = "SELECT percentage, current_count, name, unique_id, user_unique_id, sub_product_unique_id, code, mini_category_unique_id FROM coupons WHERE unique_id=:unique_id AND (user_unique_id=:user_unique_id OR sub_product_unique_id=:sub_product_unique_id OR mini_category_unique_id=:mini_category_unique_id) AND expiry_date >:today";
                $query3 = $this->conn->prepare($sql3);
                $query3->bindParam(":unique_id", $the_coupon_unique_id);
                $query3->bindParam(":user_unique_id", $user_unique_id);
                $query3->bindParam(":sub_product_unique_id", $sub_product_id);
                $query3->bindParam(":mini_category_unique_id", $mini_category_unique_id);
                $query3->bindParam(":today", $date_added);
                $query3->execute();

                if ($query3->rowCount() > 0) {
                  $the_coupon_price_details = $query3->fetch();
                  $the_coupon_percentage = (int)$the_coupon_price_details[0];
                  $the_coupon_price = $current_order_sales_price != 0 ? (($current_order_sales_price * $the_coupon_percentage) / 100) * $current_order_quantity : (($current_order_price * $the_coupon_percentage) / 100) * $current_order_quantity;
                  $the_coupon_count = (int)$the_coupon_price_details[1];
                  $the_coupon_name = $the_coupon_price_details[2];
                  $the_coupon_unique_id = $the_coupon_price_details[3];
                  $the_coupon_user_unique_id = $the_coupon_price_details[4];
                  $the_coupon_sub_product_unique_id = $the_coupon_price_details[5];
                  $the_coupon_code = $the_coupon_price_details[6];
                  $the_coupon_mini_category_unique_id = $the_coupon_price_details[7];

                  if ($the_coupon_count != 0) {
                    if (($key + 1) <= $the_coupon_count && ($the_coupon_user_unique_id == $user_unique_id || $the_coupon_sub_product_unique_id == $sub_product_id || $the_coupon_mini_category_unique_id == $mini_category_unique_id)) {
                      $current_order['total_order_amount'] = $the_total_order_amount - $the_coupon_price;
                      $current_order['coupon_name'] = $the_coupon_name;
                      $current_order['coupon_code'] = $the_coupon_code;
                      $current_order['coupon_price'] = $the_coupon_price;
                      $current_order['coupon_status'] = "Available";
                    }
                    else {
                      $current_order['total_order_amount'] = $the_total_order_amount;
                      $current_order['coupon_name'] = $the_coupon_name;
                      $current_order['coupon_code'] = $the_coupon_code;
                      $current_order['coupon_price'] = $the_coupon_price;
                      $current_order['coupon_status'] = "Not available";
                    }
                  }
                  else {
                    $current_order['total_order_amount'] = $the_total_order_amount;
                    $current_order['coupon_name'] = $the_coupon_name;
                    $current_order['coupon_code'] = $the_coupon_code;
                    $current_order['coupon_price'] = $the_coupon_price;
                    $current_order['coupon_status'] = "Not available";
                  }
                }
                else {
                  $current_order['total_order_amount'] = $the_total_order_amount;
                  $current_order['coupon_name'] = null;
                  $current_order['coupon_code'] = null;
                  $current_order['coupon_price'] = null;
                  $current_order['coupon_status'] = "Not available";
                }

                $the_total_order_amount_with_coupon = $current_order['total_order_amount'];

                $sql4 = "SELECT price, city, state, country FROM shipping_fees WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id";
                $query4 = $this->conn->prepare($sql4);
                $query4->bindParam(":unique_id", $shipping_fee_unique_id);
                $query4->bindParam(":sub_product_unique_id", $sub_product_id);
                $query4->execute();

                if ($query4->rowCount() > 0) {
                  $the_shipping_fee_price_details = $query4->fetch();
                  $the_shipping_fee_price = (int)$the_shipping_fee_price_details[0];
                  $the_shipping_fee_city = $the_shipping_fee_price_details[1];
                  $the_shipping_fee_state = $the_shipping_fee_price_details[2];
                  $the_shipping_fee_country = $the_shipping_fee_price_details[3];

                  $current_order['total_order_amount'] = $the_total_order_amount_with_coupon + ($the_shipping_fee_price * $current_order_quantity);
                  $current_order['shipping_to_city'] = $the_shipping_fee_city;
                  $current_order['shipping_to_state'] = $the_shipping_fee_state;
                  $current_order['shipping_to_country'] = $the_shipping_fee_country;
                  $current_order['shipping_fee_price'] = $the_shipping_fee_price != 0 ? ($the_shipping_fee_price * $current_order_quantity) : 0;

                }
                else {
                  $current_order['total_order_amount'] = $the_total_order_amount_with_coupon;
                  $current_order['shipping_to_city'] = null;
                  $current_order['shipping_to_state'] = null;
                  $current_order['shipping_to_country'] = null;
                  $current_order['shipping_fee_price'] = null;
                }

                $order_array[] = $current_order;
              }
              return $order_array;
            }
            else {
              $output['error'] = true;
              $output['message'] = "Empty";
              return $output;
            }

            $this->conn->commit();
          } catch (PDOException $e) {
            $this->conn->rollback();
            throw $e;
            $output['error'] = true;
            $output['message'] = "Critical error occured";
            return $output;
          }
        }
        else {
          $output['error'] = true;
          $output['message'] = "All fields are required";
          return $output;
        }

      }

      function get_user_orders_for_users_limit($user_unique_id, $limit){

        if (!in_array($user_unique_id,$this->not_allowed_values) && !in_array($limit,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;
            $date_added = $this->functions->today;

            $order_array = array();

            $sql = "SELECT orders.unique_id, orders.user_unique_id, orders.sub_product_unique_id, orders.tracker_unique_id, orders.shipment_unique_id, orders.coupon_unique_id, orders.shipping_fee_unique_id, orders.pickup_location, orders.quantity, orders.payment_method,
            orders.checked_out, orders.paid, orders.shipped, orders.disputed, orders.delivery_status, orders.added_date, orders.last_modified, orders.status,
            sub_products.name, sub_products.size, sub_products.stripped, sub_products.stock, sub_products.stock_remaining, sub_products.price, sub_products.sales_price, sub_products.favorites, products.mini_category_unique_id FROM orders
            LEFT JOIN sub_products ON orders.sub_product_unique_id = sub_products.unique_id
            LEFT JOIN products ON sub_products.product_unique_id = products.unique_id WHERE orders.user_unique_id =:user_unique_id ORDER BY orders.added_date DESC LIMIT ".$limit;
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_order = array();
                $current_order['unique_id'] = $value['unique_id'];
                $current_order['user_unique_id'] = $value['user_unique_id'];
                $current_order['sub_product_unique_id'] = $value['sub_product_unique_id'];
                $current_order['tracker_unique_id'] = $value['tracker_unique_id'];
                $current_order['shipment_unique_id'] = $value['shipment_unique_id'];
                $current_order['quantity'] = $value['quantity'];
                $current_order['payment_method'] = $value['payment_method'];
                $current_order['checked_out'] = $value['checked_out'];
                $current_order['paid'] = $value['paid'];
                $current_order['shipped'] = $value['shipped'];
                $current_order['disputed'] = $value['disputed'];
                $current_order['delivery_status'] = $value['delivery_status'];
                $current_order['added_date'] = $value['added_date'];
                $current_order['last_modified'] = $value['last_modified'];
                $current_order['status'] = $value['status'];
                $current_order['name'] = $value['name'];
                $current_order['size'] = $value['size'];
                $current_order['stripped'] = $value['stripped'];
                $current_order['stock'] = $value['stock'];
                $current_order['stock_remaining'] = $value['stock_remaining'];
                $current_order['price'] = $value['price'];
                $current_order['sales_price'] = $value['sales_price'];
                $current_order['favorites'] = $value['favorites'];

                $sub_product_id = $value['sub_product_unique_id'];
                $order_unique_id = $value['unique_id'];
                $tracker_unique_id = $value['tracker_unique_id'];
                $shipping_fee_unique_id = $value['shipping_fee_unique_id'];
                $mini_category_unique_id = $value['mini_category_unique_id'];

                $current_order_price = (int)$value['price'];
                $current_order_sales_price = (int)$value['sales_price'];
                $current_order_quantity = (int)$value['quantity'];

                $sql2 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id LIMIT 1";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":sub_product_unique_id", $sub_product_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_order_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_order_images[] = $image_value['image'];
                  }

                  $current_order['order_product_images'] = $current_order_images;
                }
                else{
                  $current_order['order_product_images'] = null;
                }

                if ($current_order_sales_price != 0) {
                  $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND order_unique_id=:order_unique_id";
                  $query7 = $this->conn->prepare($sql7);
                  $query7->bindParam(":user_unique_id", $user_unique_id);
                  $query7->bindParam(":order_unique_id", $order_unique_id);
                  $query7->execute();

                  if ($query7->rowCount() > 0) {
                    $the_order_services_details = $query7->fetchAll();
                    $offered_services_price = 0;

                    foreach ($the_order_services_details as $key => $value) {
                      $offered_service_unique_id = $value["offered_service_unique_id"];
                      $sql8 = "SELECT price FROM offered_services WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id AND status=:status";
                      $query8 = $this->conn->prepare($sql8);
                      $query8->bindParam(":unique_id", $offered_service_unique_id);
                      $query8->bindParam(":sub_product_unique_id", $sub_product_id);
                      $query8->bindParam(":status", $active);
                      $query8->execute();

                      $the_offered_services_price_details = $query8->fetch();
                      $the_offered_services_price = (int)$the_offered_services_price_details[0];
                      if ($query8->rowCount() > 0) {
                        $offered_services_price += $the_offered_services_price;
                      }
                      else{
                        $offered_services_price = 0;
                      }
                    }

                    $current_order['total_order_amount'] = ($current_order_sales_price * $current_order_quantity) + ($offered_services_price * $current_order_quantity);

                  }
                  else {
                    $current_order['total_order_amount'] = $current_order_sales_price * $current_order_quantity;
                  }
                }
                else {
                  $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND order_unique_id=:order_unique_id";
                  $query7 = $this->conn->prepare($sql7);
                  $query7->bindParam(":user_unique_id", $user_unique_id);
                  $query7->bindParam(":order_unique_id", $order_unique_id);
                  $query7->execute();

                  if ($query7->rowCount() > 0) {
                    $the_order_services_details = $query7->fetchAll();
                    $offered_services_price = 0;

                    foreach ($the_order_services_details as $key => $value) {
                      $offered_service_unique_id = $value["offered_service_unique_id"];

                      $sql8 = "SELECT price FROM offered_services WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id AND status=:status";
                      $query8 = $this->conn->prepare($sql8);
                      $query8->bindParam(":unique_id", $offered_service_unique_id);
                      $query8->bindParam(":sub_product_unique_id", $sub_product_id);
                      $query8->bindParam(":status", $active);
                      $query8->execute();

                      $the_offered_services_price_details = $query8->fetch();
                      $the_offered_services_price = (int)$the_offered_services_price_details[0];
                      if ($query8->rowCount() > 0) {
                        $offered_services_price += $the_offered_services_price;
                      }
                      else{
                        $offered_services_price = 0;
                      }

                    }

                    $current_order['total_order_amount'] = ($current_order_price * $current_order_quantity) + ($offered_services_price * $current_order_quantity);

                  }
                  else {
                    $current_order['total_order_amount'] = $current_order_price * $current_order_quantity;
                  }
                }

                $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND order_unique_id=:order_unique_id";
                $query7 = $this->conn->prepare($sql7);
                $query7->bindParam(":user_unique_id", $user_unique_id);
                $query7->bindParam(":order_unique_id", $order_unique_id);
                $query7->execute();

                if ($query7->rowCount() > 0) {
                  $the_order_services_details = $query7->fetchAll();

                  $current_order_offered_services = array();
                  $current_order_offered_services_price = array();
                  $current_order_offered_services_unique_id = array();

                  $total_offered_services_amount = 0;

                  foreach ($the_order_services_details as $key => $value) {
                    $offered_service_unique_id = $value["offered_service_unique_id"];
                    $sql8 = "SELECT price, service FROM offered_services WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id AND status=:status";
                    $query8 = $this->conn->prepare($sql8);
                    $query8->bindParam(":unique_id", $offered_service_unique_id);
                    $query8->bindParam(":sub_product_unique_id", $sub_product_id);
                    $query8->bindParam(":status", $active);
                    $query8->execute();

                    $the_offered_services_details = $query8->fetch();
                    $the_offered_services_price = (int)$the_offered_services_details[0];
                    $the_offered_services_service = $the_offered_services_details[1];

                    if ($query8->rowCount() > 0) {

                      $current_order_offered_services['service'] = $the_offered_services_service;

                      $current_order_offered_services['price'] = $the_offered_services_price;

                      $total_offered_services_amount += $the_offered_services_price;

                      // $current_order['order_offered_services_price'] = $current_order_offered_services_price;

                      $current_order_offered_services['unique_id'] = $offered_service_unique_id;

                      // $current_order['order_offered_services_unique_ids'] = $current_order_offered_services_unique_id;

                      $current_order['order_offered_services'][$key] = $current_order_offered_services;

                    }
                    else{
                      $current_order['order_offered_services'] = null;
                      // $current_order['order_offered_services_price'] = null;
                      // $current_order['order_offered_services_unique_ids'] = null;
                    }
                  }

                  $current_order['order_offered_services_price_total_amount'] = $total_offered_services_amount * $current_order_quantity;

                }
                else {
                  $current_order['order_offered_services'] = null;
                  // $current_order['order_offered_services_price'] = null;
                  // $current_order['order_offered_services_unique_ids'] = null;
                  $current_order['order_offered_services_price_total_amount'] = null;
                }

                $the_total_order_amount = $current_order['total_order_amount'];

                $sqlOrderCoupon = "SELECT coupon_unique_id FROM order_coupons WHERE tracker_unique_id=:tracker_unique_id";
                $queryOrderCoupon = $this->conn->prepare($sqlOrderCoupon);
                $queryOrderCoupon->bindParam(":tracker_unique_id", $tracker_unique_id);
                $queryOrderCoupon->execute();

                $the_order_coupon_details = $queryOrderCoupon->rowCount() > 0 ? $queryOrderCoupon->fetch() : null;
                $the_coupon_unique_id = $the_order_coupon_details != null ? $the_order_coupon_details[0] : null;

                $sql3 = "SELECT percentage, current_count, name, unique_id, user_unique_id, sub_product_unique_id, code, mini_category_unique_id FROM coupons WHERE unique_id=:unique_id AND (user_unique_id=:user_unique_id OR sub_product_unique_id=:sub_product_unique_id OR mini_category_unique_id=:mini_category_unique_id) AND expiry_date >:today";
                $query3 = $this->conn->prepare($sql3);
                $query3->bindParam(":unique_id", $the_coupon_unique_id);
                $query3->bindParam(":user_unique_id", $user_unique_id);
                $query3->bindParam(":sub_product_unique_id", $sub_product_id);
                $query3->bindParam(":mini_category_unique_id", $mini_category_unique_id);
                $query3->bindParam(":today", $date_added);
                $query3->execute();

                if ($query3->rowCount() > 0) {
                  $the_coupon_price_details = $query3->fetch();
                  $the_coupon_percentage = (int)$the_coupon_price_details[0];
                  $the_coupon_price = $current_order_sales_price != 0 ? (($current_order_sales_price * $the_coupon_percentage) / 100) * $current_order_quantity : (($current_order_price * $the_coupon_percentage) / 100) * $current_order_quantity;
                  $the_coupon_count = (int)$the_coupon_price_details[1];
                  $the_coupon_name = $the_coupon_price_details[2];
                  $the_coupon_unique_id = $the_coupon_price_details[3];
                  $the_coupon_user_unique_id = $the_coupon_price_details[4];
                  $the_coupon_sub_product_unique_id = $the_coupon_price_details[5];
                  $the_coupon_code = $the_coupon_price_details[6];
                  $the_coupon_mini_category_unique_id = $the_coupon_price_details[7];

                  if ($the_coupon_count != 0) {
                    if (($key + 1) <= $the_coupon_count && ($the_coupon_user_unique_id == $user_unique_id || $the_coupon_sub_product_unique_id == $sub_product_id || $the_coupon_mini_category_unique_id == $mini_category_unique_id)) {
                      $current_order['total_order_amount'] = $the_total_order_amount - $the_coupon_price;
                      $current_order['coupon_name'] = $the_coupon_name;
                      $current_order['coupon_code'] = $the_coupon_code;
                      $current_order['coupon_price'] = $the_coupon_price;
                      $current_order['coupon_status'] = "Available";
                    }
                    else {
                      $current_order['total_order_amount'] = $the_total_order_amount;
                      $current_order['coupon_name'] = $the_coupon_name;
                      $current_order['coupon_code'] = $the_coupon_code;
                      $current_order['coupon_price'] = $the_coupon_price;
                      $current_order['coupon_status'] = "Not available";
                    }
                  }
                  else {
                    $current_order['total_order_amount'] = $the_total_order_amount;
                    $current_order['coupon_name'] = $the_coupon_name;
                    $current_order['coupon_code'] = $the_coupon_code;
                    $current_order['coupon_price'] = $the_coupon_price;
                    $current_order['coupon_status'] = "Not available";
                  }
                }
                else {
                  $current_order['total_order_amount'] = $the_total_order_amount;
                  $current_order['coupon_name'] = null;
                  $current_order['coupon_code'] = null;
                  $current_order['coupon_price'] = null;
                  $current_order['coupon_status'] = "Not available";
                }

                $the_total_order_amount_with_coupon = $current_order['total_order_amount'];

                $sql4 = "SELECT price, city, state, country FROM shipping_fees WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id";
                $query4 = $this->conn->prepare($sql4);
                $query4->bindParam(":unique_id", $shipping_fee_unique_id);
                $query4->bindParam(":sub_product_unique_id", $sub_product_id);
                $query4->execute();

                if ($query4->rowCount() > 0) {
                  $the_shipping_fee_price_details = $query4->fetch();
                  $the_shipping_fee_price = (int)$the_shipping_fee_price_details[0];
                  $the_shipping_fee_city = $the_shipping_fee_price_details[1];
                  $the_shipping_fee_state = $the_shipping_fee_price_details[2];
                  $the_shipping_fee_country = $the_shipping_fee_price_details[3];

                  $current_order['total_order_amount'] = $the_total_order_amount_with_coupon + ($the_shipping_fee_price * $current_order_quantity);
                  $current_order['shipping_to_city'] = $the_shipping_fee_city;
                  $current_order['shipping_to_state'] = $the_shipping_fee_state;
                  $current_order['shipping_to_country'] = $the_shipping_fee_country;
                  $current_order['shipping_fee_price'] = $the_shipping_fee_price != 0 ? ($the_shipping_fee_price * $current_order_quantity) : 0;

                }
                else {
                  $current_order['total_order_amount'] = $the_total_order_amount_with_coupon;
                  $current_order['shipping_to_city'] = null;
                  $current_order['shipping_to_state'] = null;
                  $current_order['shipping_to_country'] = null;
                  $current_order['shipping_fee_price'] = null;
                }

                $order_array[] = $current_order;
              }
              return $order_array;
            }
            else {
              $output['error'] = true;
              $output['message'] = "Empty";
              return $output;
            }

            $this->conn->commit();
          } catch (PDOException $e) {
            $this->conn->rollback();
            throw $e;
            $output['error'] = true;
            $output['message'] = "Critical error occured";
            return $output;
          }
        }
        else {
          $output['error'] = true;
          $output['message'] = "All fields are required";
          return $output;
        }

      }

      function get_user_order_details($user_unique_id, $order_unique_id){

        if (!in_array($user_unique_id,$this->not_allowed_values) && !in_array($order_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;
            $date_added = $this->functions->today;

            $order_array = array();

            $sql = "SELECT orders.id, orders.unique_id, orders.user_unique_id, orders.sub_product_unique_id, orders.tracker_unique_id, orders.shipment_unique_id, orders.coupon_unique_id, orders.shipping_fee_unique_id, orders.pickup_location, orders.quantity, orders.payment_method,
            orders.checked_out, orders.paid, orders.shipped, orders.disputed, orders.delivery_status, orders.added_date, orders.last_modified, orders.status,
            users.fullname as user_fullname, users.email as user_email, users.phone_number as user_phone_number, sub_products.name, sub_products.size, sub_products.stripped,
            sub_products.stock, sub_products.stock_remaining, sub_products.price, sub_products.sales_price, sub_products.favorites, products.mini_category_unique_id FROM orders INNER JOIN users ON orders.user_unique_id = users.unique_id LEFT JOIN sub_products ON orders.sub_product_unique_id = sub_products.unique_id
            LEFT JOIN products ON sub_products.product_unique_id = products.unique_id WHERE orders.user_unique_id =:user_unique_id AND orders.unique_id =:order_unique_id ORDER BY orders.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->bindParam(":order_unique_id", $order_unique_id);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_order = array();
                $current_order['id'] = $value['id'];
                $current_order['unique_id'] = $value['unique_id'];
                $current_order['user_unique_id'] = $value['user_unique_id'];
                $current_order['sub_product_unique_id'] = $value['sub_product_unique_id'];
                $current_order['tracker_unique_id'] = $value['tracker_unique_id'];
                $current_order['shipment_unique_id'] = $value['shipment_unique_id'];
                $current_order['pickup_location'] = $value['pickup_location'];
                $current_order['quantity'] = $value['quantity'];
                $current_order['payment_method'] = $value['payment_method'];
                $current_order['checked_out'] = $value['checked_out'];
                $current_order['paid'] = $value['paid'];
                $current_order['shipped'] = $value['shipped'];
                $current_order['disputed'] = $value['disputed'];
                $current_order['delivery_status'] = $value['delivery_status'];
                $current_order['added_date'] = $value['added_date'];
                $current_order['last_modified'] = $value['last_modified'];
                $current_order['status'] = $value['status'];
                $current_order['user_fullname'] = $value['user_fullname'];
                $current_order['user_email'] = $value['user_email'];
                $current_order['user_phone_number'] = $value['user_phone_number'];
                $current_order['name'] = $value['name'];
                $current_order['size'] = $value['size'];
                $current_order['stripped'] = $value['stripped'];
                $current_order['stock'] = $value['stock'];
                $current_order['stock_remaining'] = $value['stock_remaining'];
                $current_order['price'] = $value['price'];
                $current_order['sales_price'] = $value['sales_price'];
                $current_order['favorites'] = $value['favorites'];

                $sub_product_id = $value['sub_product_unique_id'];
                $order_unique_id = $value['unique_id'];
                $tracker_unique_id = $value['tracker_unique_id'];
                $shipping_fee_unique_id = $value['shipping_fee_unique_id'];
                $mini_category_unique_id = $value['mini_category_unique_id'];

                $current_order_price = (int)$value['price'];
                $current_order_sales_price = (int)$value['sales_price'];
                $current_order_quantity = (int)$value['quantity'];

                $sql2 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id LIMIT 1";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":sub_product_unique_id", $sub_product_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_order_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_order_images[] = $image_value['image'];
                  }

                  $current_order['order_product_images'] = $current_order_images;
                }
                else{
                  $current_order['order_product_images'] = null;
                }

                if ($current_order_sales_price != 0) {
                  $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND order_unique_id=:order_unique_id";
                  $query7 = $this->conn->prepare($sql7);
                  $query7->bindParam(":user_unique_id", $user_unique_id);
                  $query7->bindParam(":order_unique_id", $order_unique_id);
                  $query7->execute();

                  if ($query7->rowCount() > 0) {
                    $the_order_services_details = $query7->fetchAll();
                    $offered_services_price = 0;

                    foreach ($the_order_services_details as $key => $value) {
                      $offered_service_unique_id = $value["offered_service_unique_id"];
                      $sql8 = "SELECT price FROM offered_services WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id AND status=:status";
                      $query8 = $this->conn->prepare($sql8);
                      $query8->bindParam(":unique_id", $offered_service_unique_id);
                      $query8->bindParam(":sub_product_unique_id", $sub_product_id);
                      $query8->bindParam(":status", $active);
                      $query8->execute();

                      $the_offered_services_price_details = $query8->fetch();
                      $the_offered_services_price = (int)$the_offered_services_price_details[0];
                      if ($query8->rowCount() > 0) {
                        $offered_services_price += $the_offered_services_price;
                      }
                      else{
                        $offered_services_price = 0;
                      }
                    }

                    $current_order['total_order_amount'] = ($current_order_sales_price * $current_order_quantity) + ($offered_services_price * $current_order_quantity);

                  }
                  else {
                    $current_order['total_order_amount'] = $current_order_sales_price * $current_order_quantity;
                  }
                }
                else {
                  $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND order_unique_id=:order_unique_id";
                  $query7 = $this->conn->prepare($sql7);
                  $query7->bindParam(":user_unique_id", $user_unique_id);
                  $query7->bindParam(":order_unique_id", $order_unique_id);
                  $query7->execute();

                  if ($query7->rowCount() > 0) {
                    $the_order_services_details = $query7->fetchAll();
                    $offered_services_price = 0;

                    foreach ($the_order_services_details as $key => $value) {
                      $offered_service_unique_id = $value["offered_service_unique_id"];

                      $sql8 = "SELECT price FROM offered_services WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id AND status=:status";
                      $query8 = $this->conn->prepare($sql8);
                      $query8->bindParam(":unique_id", $offered_service_unique_id);
                      $query8->bindParam(":sub_product_unique_id", $sub_product_id);
                      $query8->bindParam(":status", $active);
                      $query8->execute();

                      $the_offered_services_price_details = $query8->fetch();
                      $the_offered_services_price = (int)$the_offered_services_price_details[0];
                      if ($query8->rowCount() > 0) {
                        $offered_services_price += $the_offered_services_price;
                      }
                      else{
                        $offered_services_price = 0;
                      }

                    }

                    $current_order['total_order_amount'] = ($current_order_price * $current_order_quantity) + ($offered_services_price * $current_order_quantity);

                  }
                  else {
                    $current_order['total_order_amount'] = $current_order_price * $current_order_quantity;
                  }
                }

                $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND order_unique_id=:order_unique_id";
                $query7 = $this->conn->prepare($sql7);
                $query7->bindParam(":user_unique_id", $user_unique_id);
                $query7->bindParam(":order_unique_id", $order_unique_id);
                $query7->execute();

                if ($query7->rowCount() > 0) {
                  $the_order_services_details = $query7->fetchAll();

                  $current_order_offered_services = array();
                  $current_order_offered_services_price = array();
                  $current_order_offered_services_unique_id = array();

                  $total_offered_services_amount = 0;

                  foreach ($the_order_services_details as $key => $value) {
                    $offered_service_unique_id = $value["offered_service_unique_id"];
                    $sql8 = "SELECT price, service FROM offered_services WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id AND status=:status";
                    $query8 = $this->conn->prepare($sql8);
                    $query8->bindParam(":unique_id", $offered_service_unique_id);
                    $query8->bindParam(":sub_product_unique_id", $sub_product_id);
                    $query8->bindParam(":status", $active);
                    $query8->execute();

                    $the_offered_services_details = $query8->fetch();
                    $the_offered_services_price = (int)$the_offered_services_details[0];
                    $the_offered_services_service = $the_offered_services_details[1];

                    if ($query8->rowCount() > 0) {

                      $current_order_offered_services[] = $the_offered_services_service;

                      $current_order['order_offered_services'] = $current_order_offered_services;

                      $current_order_offered_services_price[] = $the_offered_services_price;

                      $total_offered_services_amount += $the_offered_services_price;

                      $current_order['order_offered_services_price'] = $current_order_offered_services_price;

                      $current_order_offered_services_unique_id[] = $offered_service_unique_id;

                      $current_order['order_offered_services_unique_ids'] = $current_order_offered_services_unique_id;

                    }
                    else{
                      $current_order['order_offered_services'] = null;
                      $current_order['order_offered_services_price'] = null;
                      $current_order['order_offered_services_unique_ids'] = null;
                    }
                  }

                  $current_order['order_offered_services_price_total_amount'] = $total_offered_services_amount * $current_order_quantity;

                }
                else {
                  $current_order['order_offered_services'] = null;
                  $current_order['order_offered_services_price'] = null;
                  $current_order['order_offered_services_unique_ids'] = null;
                  $current_order['order_offered_services_price_total_amount'] = null;
                }

                $the_total_order_amount = $current_order['total_order_amount'];

                $sqlOrderCoupon = "SELECT coupon_unique_id FROM order_coupons WHERE tracker_unique_id=:tracker_unique_id";
                $queryOrderCoupon = $this->conn->prepare($sqlOrderCoupon);
                $queryOrderCoupon->bindParam(":tracker_unique_id", $tracker_unique_id);
                $queryOrderCoupon->execute();

                $the_order_coupon_details = $queryOrderCoupon->rowCount() > 0 ? $queryOrderCoupon->fetch() : null;
                $the_coupon_unique_id = $the_order_coupon_details != null ? $the_order_coupon_details[0] : null;

                $sql3 = "SELECT percentage, current_count, name, unique_id, user_unique_id, sub_product_unique_id, code, mini_category_unique_id FROM coupons WHERE unique_id=:unique_id AND (user_unique_id=:user_unique_id OR sub_product_unique_id=:sub_product_unique_id OR mini_category_unique_id=:mini_category_unique_id) AND expiry_date >:today";
                $query3 = $this->conn->prepare($sql3);
                $query3->bindParam(":unique_id", $the_coupon_unique_id);
                $query3->bindParam(":user_unique_id", $user_unique_id);
                $query3->bindParam(":sub_product_unique_id", $sub_product_id);
                $query3->bindParam(":mini_category_unique_id", $mini_category_unique_id);
                $query3->bindParam(":today", $date_added);
                $query3->execute();

                if ($query3->rowCount() > 0) {
                  $the_coupon_price_details = $query3->fetch();
                  $the_coupon_percentage = (int)$the_coupon_price_details[0];
                  $the_coupon_price = $current_order_sales_price != 0 ? (($current_order_sales_price * $the_coupon_percentage) / 100) * $current_order_quantity : (($current_order_price * $the_coupon_percentage) / 100) * $current_order_quantity;
                  $the_coupon_count = (int)$the_coupon_price_details[1];
                  $the_coupon_name = $the_coupon_price_details[2];
                  $the_coupon_unique_id = $the_coupon_price_details[3];
                  $the_coupon_user_unique_id = $the_coupon_price_details[4];
                  $the_coupon_sub_product_unique_id = $the_coupon_price_details[5];
                  $the_coupon_code = $the_coupon_price_details[6];
                  $the_coupon_mini_category_unique_id = $the_coupon_price_details[7];

                  if ($the_coupon_count != 0) {
                    if (($key + 1) <= $the_coupon_count && ($the_coupon_user_unique_id == $user_unique_id || $the_coupon_sub_product_unique_id == $sub_product_id || $the_coupon_mini_category_unique_id == $mini_category_unique_id)) {
                      $current_order['total_order_amount'] = $the_total_order_amount - $the_coupon_price;
                      $current_order['coupon_name'] = $the_coupon_name;
                      $current_order['coupon_code'] = $the_coupon_code;
                      $current_order['coupon_price'] = $the_coupon_price;
                      $current_order['coupon_status'] = "Available";
                    }
                    else {
                      $current_order['total_order_amount'] = $the_total_order_amount;
                      $current_order['coupon_name'] = $the_coupon_name;
                      $current_order['coupon_code'] = $the_coupon_code;
                      $current_order['coupon_price'] = $the_coupon_price;
                      $current_order['coupon_status'] = "Not available";
                    }
                  }
                  else {
                    $current_order['total_order_amount'] = $the_total_order_amount;
                    $current_order['coupon_name'] = $the_coupon_name;
                    $current_order['coupon_code'] = $the_coupon_code;
                    $current_order['coupon_price'] = $the_coupon_price;
                    $current_order['coupon_status'] = "Not available";
                  }
                }
                else {
                  $current_order['total_order_amount'] = $the_total_order_amount;
                  $current_order['coupon_name'] = null;
                  $current_order['coupon_code'] = null;
                  $current_order['coupon_price'] = null;
                  $current_order['coupon_status'] = "Not available";
                }

                $the_total_order_amount_with_coupon = $current_order['total_order_amount'];

                $sql4 = "SELECT price, city, state, country FROM shipping_fees WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id";
                $query4 = $this->conn->prepare($sql4);
                $query4->bindParam(":unique_id", $shipping_fee_unique_id);
                $query4->bindParam(":sub_product_unique_id", $sub_product_id);
                $query4->execute();

                if ($query4->rowCount() > 0) {
                  $the_shipping_fee_price_details = $query4->fetch();
                  $the_shipping_fee_price = (int)$the_shipping_fee_price_details[0];
                  $the_shipping_fee_city = $the_shipping_fee_price_details[1];
                  $the_shipping_fee_state = $the_shipping_fee_price_details[2];
                  $the_shipping_fee_country = $the_shipping_fee_price_details[3];

                  $current_order['total_order_amount'] = $the_total_order_amount_with_coupon + ($the_shipping_fee_price * $current_order_quantity);
                  $current_order['shipping_to_city'] = $the_shipping_fee_city;
                  $current_order['shipping_to_state'] = $the_shipping_fee_state;
                  $current_order['shipping_to_country'] = $the_shipping_fee_country;
                  $current_order['shipping_fee_price'] = $the_shipping_fee_price != 0 ? ($the_shipping_fee_price * $current_order_quantity) : 0;

                }
                else {
                  $current_order['total_order_amount'] = $the_total_order_amount_with_coupon;
                  $current_order['shipping_to_city'] = null;
                  $current_order['shipping_to_state'] = null;
                  $current_order['shipping_to_country'] = null;
                  $current_order['shipping_fee_price'] = null;
                }

                $order_array[] = $current_order;
              }
              return $order_array;
            }
            else {
              $output['error'] = true;
              $output['message'] = "Empty";
              return $output;
            }

            $this->conn->commit();
          } catch (PDOException $e) {
            $this->conn->rollback();
            throw $e;
            $output['error'] = true;
            $output['message'] = "Critical error occured";
            return $output;
          }
        }
        else {
          $output['error'] = true;
          $output['message'] = "All fields are required";
          return $output;
        }

      }

      function get_user_order_details_for_users($user_unique_id, $order_unique_id){

        if (!in_array($user_unique_id,$this->not_allowed_values) && !in_array($order_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;
            $date_added = $this->functions->today;

            $order_array = array();

            $sql = "SELECT orders.unique_id, orders.user_unique_id, orders.sub_product_unique_id, orders.tracker_unique_id, orders.shipment_unique_id, orders.coupon_unique_id, orders.shipping_fee_unique_id, orders.pickup_location, orders.quantity, orders.payment_method,
            orders.checked_out, orders.paid, orders.shipped, orders.disputed, orders.delivery_status, orders.added_date, orders.last_modified, orders.status,
            sub_products.name, sub_products.size, sub_products.stripped, sub_products.stock, sub_products.stock_remaining, sub_products.price, sub_products.sales_price, sub_products.favorites, products.mini_category_unique_id FROM orders
            LEFT JOIN sub_products ON orders.sub_product_unique_id = sub_products.unique_id
            LEFT JOIN products ON sub_products.product_unique_id = products.unique_id WHERE orders.user_unique_id =:user_unique_id AND orders.unique_id =:order_unique_id ORDER BY orders.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->bindParam(":order_unique_id", $order_unique_id);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_order = array();
                $current_order['unique_id'] = $value['unique_id'];
                $current_order['user_unique_id'] = $value['user_unique_id'];
                $current_order['sub_product_unique_id'] = $value['sub_product_unique_id'];
                $current_order['tracker_unique_id'] = $value['tracker_unique_id'];
                $current_order['shipment_unique_id'] = $value['shipment_unique_id'];
                $current_order['pickup_location'] = $value['pickup_location'];
                $current_order['quantity'] = $value['quantity'];
                $current_order['payment_method'] = $value['payment_method'];
                $current_order['checked_out'] = $value['checked_out'];
                $current_order['paid'] = $value['paid'];
                $current_order['shipped'] = $value['shipped'];
                $current_order['disputed'] = $value['disputed'];
                $current_order['delivery_status'] = $value['delivery_status'];
                $current_order['added_date'] = $value['added_date'];
                $current_order['last_modified'] = $value['last_modified'];
                $current_order['status'] = $value['status'];
                $current_order['name'] = $value['name'];
                $current_order['size'] = $value['size'];
                $current_order['stripped'] = $value['stripped'];
                $current_order['stock'] = $value['stock'];
                $current_order['stock_remaining'] = $value['stock_remaining'];
                $current_order['price'] = $value['price'];
                $current_order['sales_price'] = $value['sales_price'];
                $current_order['favorites'] = $value['favorites'];

                $sub_product_id = $value['sub_product_unique_id'];
                $order_unique_id = $value['unique_id'];
                $tracker_unique_id = $value['tracker_unique_id'];
                $shipping_fee_unique_id = $value['shipping_fee_unique_id'];
                $mini_category_unique_id = $value['mini_category_unique_id'];

                $current_order_price = (int)$value['price'];
                $current_order_sales_price = (int)$value['sales_price'];
                $current_order_quantity = (int)$value['quantity'];

                $sql2 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id LIMIT 1";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":sub_product_unique_id", $sub_product_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_order_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_order_images[] = $image_value['image'];
                  }

                  $current_order['order_product_images'] = $current_order_images;
                }
                else{
                  $current_order['order_product_images'] = null;
                }

                if ($current_order_sales_price != 0) {
                  $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND order_unique_id=:order_unique_id";
                  $query7 = $this->conn->prepare($sql7);
                  $query7->bindParam(":user_unique_id", $user_unique_id);
                  $query7->bindParam(":order_unique_id", $order_unique_id);
                  $query7->execute();

                  if ($query7->rowCount() > 0) {
                    $the_order_services_details = $query7->fetchAll();
                    $offered_services_price = 0;

                    foreach ($the_order_services_details as $key => $value) {
                      $offered_service_unique_id = $value["offered_service_unique_id"];
                      $sql8 = "SELECT price FROM offered_services WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id AND status=:status";
                      $query8 = $this->conn->prepare($sql8);
                      $query8->bindParam(":unique_id", $offered_service_unique_id);
                      $query8->bindParam(":sub_product_unique_id", $sub_product_id);
                      $query8->bindParam(":status", $active);
                      $query8->execute();

                      $the_offered_services_price_details = $query8->fetch();
                      $the_offered_services_price = (int)$the_offered_services_price_details[0];
                      if ($query8->rowCount() > 0) {
                        $offered_services_price += $the_offered_services_price;
                      }
                      else{
                        $offered_services_price = 0;
                      }
                    }

                    $current_order['total_order_amount'] = ($current_order_sales_price * $current_order_quantity) + ($offered_services_price * $current_order_quantity);

                  }
                  else {
                    $current_order['total_order_amount'] = $current_order_sales_price * $current_order_quantity;
                  }
                }
                else {
                  $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND order_unique_id=:order_unique_id";
                  $query7 = $this->conn->prepare($sql7);
                  $query7->bindParam(":user_unique_id", $user_unique_id);
                  $query7->bindParam(":order_unique_id", $order_unique_id);
                  $query7->execute();

                  if ($query7->rowCount() > 0) {
                    $the_order_services_details = $query7->fetchAll();
                    $offered_services_price = 0;

                    foreach ($the_order_services_details as $key => $value) {
                      $offered_service_unique_id = $value["offered_service_unique_id"];

                      $sql8 = "SELECT price FROM offered_services WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id AND status=:status";
                      $query8 = $this->conn->prepare($sql8);
                      $query8->bindParam(":unique_id", $offered_service_unique_id);
                      $query8->bindParam(":sub_product_unique_id", $sub_product_id);
                      $query8->bindParam(":status", $active);
                      $query8->execute();

                      $the_offered_services_price_details = $query8->fetch();
                      $the_offered_services_price = (int)$the_offered_services_price_details[0];
                      if ($query8->rowCount() > 0) {
                        $offered_services_price += $the_offered_services_price;
                      }
                      else{
                        $offered_services_price = 0;
                      }

                    }

                    $current_order['total_order_amount'] = ($current_order_price * $current_order_quantity) + ($offered_services_price * $current_order_quantity);

                  }
                  else {
                    $current_order['total_order_amount'] = $current_order_price * $current_order_quantity;
                  }
                }

                $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND order_unique_id=:order_unique_id";
                $query7 = $this->conn->prepare($sql7);
                $query7->bindParam(":user_unique_id", $user_unique_id);
                $query7->bindParam(":order_unique_id", $order_unique_id);
                $query7->execute();

                if ($query7->rowCount() > 0) {
                  $the_order_services_details = $query7->fetchAll();

                  $current_order_offered_services = array();
                  $current_order_offered_services_price = array();
                  $current_order_offered_services_unique_id = array();

                  $total_offered_services_amount = 0;

                  foreach ($the_order_services_details as $key => $value) {
                    $offered_service_unique_id = $value["offered_service_unique_id"];
                    $sql8 = "SELECT price, service FROM offered_services WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id AND status=:status";
                    $query8 = $this->conn->prepare($sql8);
                    $query8->bindParam(":unique_id", $offered_service_unique_id);
                    $query8->bindParam(":sub_product_unique_id", $sub_product_id);
                    $query8->bindParam(":status", $active);
                    $query8->execute();

                    $the_offered_services_details = $query8->fetch();
                    $the_offered_services_price = (int)$the_offered_services_details[0];
                    $the_offered_services_service = $the_offered_services_details[1];

                    if ($query8->rowCount() > 0) {

                      $current_order_offered_services['service'] = $the_offered_services_service;

                      $current_order_offered_services['price'] = $the_offered_services_price;

                      $total_offered_services_amount += $the_offered_services_price;

                      // $current_order['order_offered_services_price'] = $current_order_offered_services_price;

                      $current_order_offered_services['unique_id'] = $offered_service_unique_id;

                      // $current_order['order_offered_services_unique_ids'] = $current_order_offered_services_unique_id;

                      $current_order['order_offered_services'][$key] = $current_order_offered_services;

                    }
                    else{
                      $current_order['order_offered_services'] = null;
                      // $current_order['order_offered_services_price'] = null;
                      // $current_order['order_offered_services_unique_ids'] = null;
                    }
                  }

                  $current_order['order_offered_services_price_total_amount'] = $total_offered_services_amount * $current_order_quantity;

                }
                else {
                  $current_order['order_offered_services'] = null;
                  // $current_order['order_offered_services_price'] = null;
                  // $current_order['order_offered_services_unique_ids'] = null;
                  $current_order['order_offered_services_price_total_amount'] = null;
                }

                $the_total_order_amount = $current_order['total_order_amount'];

                $sqlOrderCoupon = "SELECT coupon_unique_id FROM order_coupons WHERE tracker_unique_id=:tracker_unique_id";
                $queryOrderCoupon = $this->conn->prepare($sqlOrderCoupon);
                $queryOrderCoupon->bindParam(":tracker_unique_id", $tracker_unique_id);
                $queryOrderCoupon->execute();

                $the_order_coupon_details = $queryOrderCoupon->rowCount() > 0 ? $queryOrderCoupon->fetch() : null;
                $the_coupon_unique_id = $the_order_coupon_details != null ? $the_order_coupon_details[0] : null;

                $sql3 = "SELECT percentage, current_count, name, unique_id, user_unique_id, sub_product_unique_id, code, mini_category_unique_id FROM coupons WHERE unique_id=:unique_id AND (user_unique_id=:user_unique_id OR sub_product_unique_id=:sub_product_unique_id OR mini_category_unique_id=:mini_category_unique_id) AND expiry_date >:today";
                $query3 = $this->conn->prepare($sql3);
                $query3->bindParam(":unique_id", $the_coupon_unique_id);
                $query3->bindParam(":user_unique_id", $user_unique_id);
                $query3->bindParam(":sub_product_unique_id", $sub_product_id);
                $query3->bindParam(":mini_category_unique_id", $mini_category_unique_id);
                $query3->bindParam(":today", $date_added);
                $query3->execute();

                if ($query3->rowCount() > 0) {
                  $the_coupon_price_details = $query3->fetch();
                  $the_coupon_percentage = (int)$the_coupon_price_details[0];
                  $the_coupon_price = $current_order_sales_price != 0 ? (($current_order_sales_price * $the_coupon_percentage) / 100) * $current_order_quantity : (($current_order_price * $the_coupon_percentage) / 100) * $current_order_quantity;
                  $the_coupon_count = (int)$the_coupon_price_details[1];
                  $the_coupon_name = $the_coupon_price_details[2];
                  $the_coupon_unique_id = $the_coupon_price_details[3];
                  $the_coupon_user_unique_id = $the_coupon_price_details[4];
                  $the_coupon_sub_product_unique_id = $the_coupon_price_details[5];
                  $the_coupon_code = $the_coupon_price_details[6];
                  $the_coupon_mini_category_unique_id = $the_coupon_price_details[7];

                  if ($the_coupon_count != 0) {
                    if (($key + 1) <= $the_coupon_count && ($the_coupon_user_unique_id == $user_unique_id || $the_coupon_sub_product_unique_id == $sub_product_id || $the_coupon_mini_category_unique_id == $mini_category_unique_id)) {
                      $current_order['total_order_amount'] = $the_total_order_amount - $the_coupon_price;
                      $current_order['coupon_name'] = $the_coupon_name;
                      $current_order['coupon_code'] = $the_coupon_code;
                      $current_order['coupon_price'] = $the_coupon_price;
                      $current_order['coupon_status'] = "Available";
                    }
                    else {
                      $current_order['total_order_amount'] = $the_total_order_amount;
                      $current_order['coupon_name'] = $the_coupon_name;
                      $current_order['coupon_code'] = $the_coupon_code;
                      $current_order['coupon_price'] = $the_coupon_price;
                      $current_order['coupon_status'] = "Not available";
                    }
                  }
                  else {
                    $current_order['total_order_amount'] = $the_total_order_amount;
                    $current_order['coupon_name'] = $the_coupon_name;
                    $current_order['coupon_code'] = $the_coupon_code;
                    $current_order['coupon_price'] = $the_coupon_price;
                    $current_order['coupon_status'] = "Not available";
                  }
                }
                else {
                  $current_order['total_order_amount'] = $the_total_order_amount;
                  $current_order['coupon_name'] = null;
                  $current_order['coupon_code'] = null;
                  $current_order['coupon_price'] = null;
                  $current_order['coupon_status'] = "Not available";
                }

                $the_total_order_amount_with_coupon = $current_order['total_order_amount'];

                $sql4 = "SELECT price, city, state, country FROM shipping_fees WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id";
                $query4 = $this->conn->prepare($sql4);
                $query4->bindParam(":unique_id", $shipping_fee_unique_id);
                $query4->bindParam(":sub_product_unique_id", $sub_product_id);
                $query4->execute();

                if ($query4->rowCount() > 0) {
                  $the_shipping_fee_price_details = $query4->fetch();
                  $the_shipping_fee_price = (int)$the_shipping_fee_price_details[0];
                  $the_shipping_fee_city = $the_shipping_fee_price_details[1];
                  $the_shipping_fee_state = $the_shipping_fee_price_details[2];
                  $the_shipping_fee_country = $the_shipping_fee_price_details[3];

                  $current_order['total_order_amount'] = $the_total_order_amount_with_coupon + ($the_shipping_fee_price * $current_order_quantity);
                  $current_order['shipping_to_city'] = $the_shipping_fee_city;
                  $current_order['shipping_to_state'] = $the_shipping_fee_state;
                  $current_order['shipping_to_country'] = $the_shipping_fee_country;
                  $current_order['shipping_fee_price'] = $the_shipping_fee_price != 0 ? ($the_shipping_fee_price * $current_order_quantity) : 0;

                }
                else {
                  $current_order['total_order_amount'] = $the_total_order_amount_with_coupon;
                  $current_order['shipping_to_city'] = null;
                  $current_order['shipping_to_state'] = null;
                  $current_order['shipping_to_country'] = null;
                  $current_order['shipping_fee_price'] = null;
                }

                $order_array[] = $current_order;
              }
              return $order_array;
            }
            else {
              $output['error'] = true;
              $output['message'] = "Empty";
              return $output;
            }

            $this->conn->commit();
          } catch (PDOException $e) {
            $this->conn->rollback();
            throw $e;
            $output['error'] = true;
            $output['message'] = "Critical error occured";
            return $output;
          }
        }
        else {
          $output['error'] = true;
          $output['message'] = "All fields are required";
          return $output;
        }

      }

      function get_shipment_orders($shipment_unique_id){

        if (!in_array($shipment_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;

            $order_array = array();

            $sql = "SELECT orders.id, orders.unique_id, orders.user_unique_id, orders.sub_product_unique_id, orders.tracker_unique_id, orders.shipment_unique_id, orders.coupon_unique_id, orders.shipping_fee_unique_id, orders.pickup_location, orders.quantity, orders.payment_method,
            orders.checked_out, orders.paid, orders.shipped, orders.disputed, orders.delivery_status, orders.added_date, orders.last_modified, orders.status,
            users.fullname as user_fullname, users.email as user_email, users.phone_number as user_phone_number, sub_products.name, sub_products.size, sub_products.stripped,
            sub_products.stock, sub_products.stock_remaining, sub_products.price, sub_products.sales_price, sub_products.favorites, products.mini_category_unique_id FROM orders INNER JOIN users ON orders.user_unique_id = users.unique_id LEFT JOIN sub_products ON orders.sub_product_unique_id = sub_products.unique_id
            LEFT JOIN products ON sub_products.product_unique_id = products.unique_id WHERE orders.shipment_unique_id =:shipment_unique_id ORDER BY orders.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":shipment_unique_id", $shipment_unique_id);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_order = array();
                $current_order['id'] = $value['id'];
                $current_order['unique_id'] = $value['unique_id'];
                $current_order['user_unique_id'] = $value['user_unique_id'];
                $current_order['sub_product_unique_id'] = $value['sub_product_unique_id'];
                $current_order['tracker_unique_id'] = $value['tracker_unique_id'];
                $current_order['shipment_unique_id'] = $value['shipment_unique_id'];
                $current_order['quantity'] = $value['quantity'];
                $current_order['payment_method'] = $value['payment_method'];
                $current_order['checked_out'] = $value['checked_out'];
                $current_order['paid'] = $value['paid'];
                $current_order['shipped'] = $value['shipped'];
                $current_order['disputed'] = $value['disputed'];
                $current_order['delivery_status'] = $value['delivery_status'];
                $current_order['added_date'] = $value['added_date'];
                $current_order['last_modified'] = $value['last_modified'];
                $current_order['status'] = $value['status'];
                $current_order['user_fullname'] = $value['user_fullname'];
                $current_order['user_email'] = $value['user_email'];
                $current_order['user_phone_number'] = $value['user_phone_number'];
                $current_order['name'] = $value['name'];
                $current_order['size'] = $value['size'];
                $current_order['stripped'] = $value['stripped'];
                $current_order['stock'] = $value['stock'];
                $current_order['stock_remaining'] = $value['stock_remaining'];
                $current_order['price'] = $value['price'];
                $current_order['sales_price'] = $value['sales_price'];
                $current_order['favorites'] = $value['favorites'];

                $sub_product_id = $value['sub_product_unique_id'];
                $order_unique_id = $value['unique_id'];
                $user_unique_id = $value['user_unique_id'];
                $tracker_unique_id = $value['tracker_unique_id'];
                $shipping_fee_unique_id = $value['shipping_fee_unique_id'];
                $mini_category_unique_id = $value['mini_category_unique_id'];

                $current_order_price = (int)$value['price'];
                $current_order_sales_price = (int)$value['sales_price'];
                $current_order_quantity = (int)$value['quantity'];

                $sql2 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id LIMIT 1";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":sub_product_unique_id", $sub_product_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_order_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_order_images[] = $image_value['image'];
                  }

                  $current_order['order_product_images'] = $current_order_images;
                }
                else{
                  $current_order['order_product_images'] = null;
                }

                if ($current_order_sales_price != 0) {
                  $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND order_unique_id=:order_unique_id";
                  $query7 = $this->conn->prepare($sql7);
                  $query7->bindParam(":user_unique_id", $user_unique_id);
                  $query7->bindParam(":order_unique_id", $order_unique_id);
                  $query7->execute();

                  if ($query7->rowCount() > 0) {
                    $the_order_services_details = $query7->fetchAll();
                    $offered_services_price = 0;

                    foreach ($the_order_services_details as $key => $value) {
                      $offered_service_unique_id = $value["offered_service_unique_id"];
                      $sql8 = "SELECT price FROM offered_services WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id AND status=:status";
                      $query8 = $this->conn->prepare($sql8);
                      $query8->bindParam(":unique_id", $offered_service_unique_id);
                      $query8->bindParam(":sub_product_unique_id", $sub_product_id);
                      $query8->bindParam(":status", $active);
                      $query8->execute();

                      $the_offered_services_price_details = $query8->fetch();
                      $the_offered_services_price = (int)$the_offered_services_price_details[0];
                      if ($query8->rowCount() > 0) {
                        $offered_services_price += $the_offered_services_price;
                      }
                      else{
                        $offered_services_price = 0;
                      }
                    }

                    $current_order['total_order_amount'] = ($current_order_sales_price * $current_order_quantity) + ($offered_services_price * $current_order_quantity);

                  }
                  else {
                    $current_order['total_order_amount'] = $current_order_sales_price * $current_order_quantity;
                  }
                }
                else {
                  $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND order_unique_id=:order_unique_id";
                  $query7 = $this->conn->prepare($sql7);
                  $query7->bindParam(":user_unique_id", $user_unique_id);
                  $query7->bindParam(":order_unique_id", $order_unique_id);
                  $query7->execute();

                  if ($query7->rowCount() > 0) {
                    $the_order_services_details = $query7->fetchAll();
                    $offered_services_price = 0;

                    foreach ($the_order_services_details as $key => $value) {
                      $offered_service_unique_id = $value["offered_service_unique_id"];

                      $sql8 = "SELECT price FROM offered_services WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id AND status=:status";
                      $query8 = $this->conn->prepare($sql8);
                      $query8->bindParam(":unique_id", $offered_service_unique_id);
                      $query8->bindParam(":sub_product_unique_id", $sub_product_id);
                      $query8->bindParam(":status", $active);
                      $query8->execute();

                      $the_offered_services_price_details = $query8->fetch();
                      $the_offered_services_price = (int)$the_offered_services_price_details[0];
                      if ($query8->rowCount() > 0) {
                        $offered_services_price += $the_offered_services_price;
                      }
                      else{
                        $offered_services_price = 0;
                      }

                    }

                    $current_order['total_order_amount'] = ($current_order_price * $current_order_quantity) + ($offered_services_price * $current_order_quantity);

                  }
                  else {
                    $current_order['total_order_amount'] = $current_order_price * $current_order_quantity;
                  }
                }

                $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND order_unique_id=:order_unique_id";
                $query7 = $this->conn->prepare($sql7);
                $query7->bindParam(":user_unique_id", $user_unique_id);
                $query7->bindParam(":order_unique_id", $order_unique_id);
                $query7->execute();

                if ($query7->rowCount() > 0) {
                  $the_order_services_details = $query7->fetchAll();

                  $current_order_offered_services = array();
                  $current_order_offered_services_price = array();
                  $current_order_offered_services_unique_id = array();

                  $total_offered_services_amount = 0;

                  foreach ($the_order_services_details as $key => $value) {
                    $offered_service_unique_id = $value["offered_service_unique_id"];
                    $sql8 = "SELECT price, service FROM offered_services WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id AND status=:status";
                    $query8 = $this->conn->prepare($sql8);
                    $query8->bindParam(":unique_id", $offered_service_unique_id);
                    $query8->bindParam(":sub_product_unique_id", $sub_product_id);
                    $query8->bindParam(":status", $active);
                    $query8->execute();

                    $the_offered_services_details = $query8->fetch();
                    $the_offered_services_price = (int)$the_offered_services_details[0];
                    $the_offered_services_service = $the_offered_services_details[1];

                    if ($query8->rowCount() > 0) {

                      $current_order_offered_services['service'] = $the_offered_services_service;

                      $current_order_offered_services['price'] = $the_offered_services_price;

                      $total_offered_services_amount += $the_offered_services_price;

                      // $current_order['order_offered_services_price'] = $current_order_offered_services_price;

                      $current_order_offered_services['unique_id'] = $offered_service_unique_id;

                      // $current_order['order_offered_services_unique_ids'] = $current_order_offered_services_unique_id;

                      $current_order['order_offered_services'][$key] = $current_order_offered_services;

                    }
                    else{
                      $current_order['order_offered_services'] = null;
                      // $current_order['order_offered_services_price'] = null;
                      // $current_order['order_offered_services_unique_ids'] = null;
                    }
                  }

                  $current_order['order_offered_services_price_total_amount'] = $total_offered_services_amount * $current_order_quantity;

                }
                else {
                  $current_order['order_offered_services'] = null;
                  // $current_order['order_offered_services_price'] = null;
                  // $current_order['order_offered_services_unique_ids'] = null;
                  $current_order['order_offered_services_price_total_amount'] = null;
                }

                $the_total_order_amount = $current_order['total_order_amount'];

                $sqlOrderCoupon = "SELECT coupon_unique_id FROM order_coupons WHERE tracker_unique_id=:tracker_unique_id";
                $queryOrderCoupon = $this->conn->prepare($sqlOrderCoupon);
                $queryOrderCoupon->bindParam(":tracker_unique_id", $tracker_unique_id);
                $queryOrderCoupon->execute();

                $the_order_coupon_details = $queryOrderCoupon->rowCount() > 0 ? $queryOrderCoupon->fetch() : null;
                $the_coupon_unique_id = $the_order_coupon_details != null ? $the_order_coupon_details[0] : null;

                $sql3 = "SELECT percentage, current_count, name, unique_id, user_unique_id, sub_product_unique_id, code, mini_category_unique_id FROM coupons WHERE unique_id=:unique_id AND (user_unique_id=:user_unique_id OR sub_product_unique_id=:sub_product_unique_id OR mini_category_unique_id=:mini_category_unique_id) AND expiry_date >:today";
                $query3 = $this->conn->prepare($sql3);
                $query3->bindParam(":unique_id", $the_coupon_unique_id);
                $query3->bindParam(":user_unique_id", $user_unique_id);
                $query3->bindParam(":sub_product_unique_id", $sub_product_id);
                $query3->bindParam(":mini_category_unique_id", $mini_category_unique_id);
                $query3->bindParam(":today", $date_added);
                $query3->execute();

                if ($query3->rowCount() > 0) {
                  $the_coupon_price_details = $query3->fetch();
                  $the_coupon_percentage = (int)$the_coupon_price_details[0];
                  $the_coupon_price = $current_order_sales_price != 0 ? (($current_order_sales_price * $the_coupon_percentage) / 100) * $current_order_quantity : (($current_order_price * $the_coupon_percentage) / 100) * $current_order_quantity;
                  $the_coupon_count = (int)$the_coupon_price_details[1];
                  $the_coupon_name = $the_coupon_price_details[2];
                  $the_coupon_unique_id = $the_coupon_price_details[3];
                  $the_coupon_user_unique_id = $the_coupon_price_details[4];
                  $the_coupon_sub_product_unique_id = $the_coupon_price_details[5];
                  $the_coupon_code = $the_coupon_price_details[6];
                  $the_coupon_mini_category_unique_id = $the_coupon_price_details[7];

                  if ($the_coupon_count != 0) {
                    if (($key + 1) <= $the_coupon_count && ($the_coupon_user_unique_id == $user_unique_id || $the_coupon_sub_product_unique_id == $sub_product_id || $the_coupon_mini_category_unique_id == $mini_category_unique_id)) {
                      $current_order['total_order_amount'] = $the_total_order_amount - $the_coupon_price;
                      $current_order['coupon_name'] = $the_coupon_name;
                      $current_order['coupon_code'] = $the_coupon_code;
                      $current_order['coupon_price'] = $the_coupon_price;
                      $current_order['coupon_status'] = "Available";
                    }
                    else {
                      $current_order['total_order_amount'] = $the_total_order_amount;
                      $current_order['coupon_name'] = $the_coupon_name;
                      $current_order['coupon_code'] = $the_coupon_code;
                      $current_order['coupon_price'] = $the_coupon_price;
                      $current_order['coupon_status'] = "Not available";
                    }
                  }
                  else {
                    $current_order['total_order_amount'] = $the_total_order_amount;
                    $current_order['coupon_name'] = $the_coupon_name;
                    $current_order['coupon_code'] = $the_coupon_code;
                    $current_order['coupon_price'] = $the_coupon_price;
                    $current_order['coupon_status'] = "Not available";
                  }
                }
                else {
                  $current_order['total_order_amount'] = $the_total_order_amount;
                  $current_order['coupon_name'] = null;
                  $current_order['coupon_code'] = null;
                  $current_order['coupon_price'] = null;
                  $current_order['coupon_status'] = "Not available";
                }

                $the_total_order_amount_with_coupon = $current_order['total_order_amount'];

                $sql4 = "SELECT price, city, state, country FROM shipping_fees WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id";
                $query4 = $this->conn->prepare($sql4);
                $query4->bindParam(":unique_id", $shipping_fee_unique_id);
                $query4->bindParam(":sub_product_unique_id", $sub_product_id);
                $query4->execute();

                if ($query4->rowCount() > 0) {
                  $the_shipping_fee_price_details = $query4->fetch();
                  $the_shipping_fee_price = (int)$the_shipping_fee_price_details[0];
                  $the_shipping_fee_city = $the_shipping_fee_price_details[1];
                  $the_shipping_fee_state = $the_shipping_fee_price_details[2];
                  $the_shipping_fee_country = $the_shipping_fee_price_details[3];

                  $current_order['total_order_amount'] = $the_total_order_amount_with_coupon + ($the_shipping_fee_price * $current_order_quantity);
                  $current_order['shipping_to_city'] = $the_shipping_fee_city;
                  $current_order['shipping_to_state'] = $the_shipping_fee_state;
                  $current_order['shipping_to_country'] = $the_shipping_fee_country;
                  $current_order['shipping_fee_price'] = $the_shipping_fee_price != 0 ? ($the_shipping_fee_price * $current_order_quantity) : 0;

                }
                else {
                  $current_order['total_order_amount'] = $the_total_order_amount_with_coupon;
                  $current_order['shipping_to_city'] = null;
                  $current_order['shipping_to_state'] = null;
                  $current_order['shipping_to_country'] = null;
                  $current_order['shipping_fee_price'] = null;
                }

                $order_array[] = $current_order;
              }
              return $order_array;
            }
            else {
              $output['error'] = true;
              $output['message'] = "Empty";
              return $output;
            }

            $this->conn->commit();
          } catch (PDOException $e) {
            $this->conn->rollback();
            throw $e;
            $output['error'] = true;
            $output['message'] = "Critical error occured";
            return $output;
          }
        }
        else {
          $output['error'] = true;
          $output['message'] = "All fields are required";
          return $output;
        }

      }

      function get_shipment_details($user_unique_id, $shipment_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values) && !in_array($shipment_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $shipment_array = array();

            $date_added = $this->functions->today;
            $active = $this->functions->active;
            $null = $this->functions->null;
            $shipment_table_values = $this->functions->shipment_table_values;
            $user_addresses_table_values = $this->functions->user_addresses_table_values;

            $sql = "SELECT shipments.id, shipments.unique_id, shipments.shipment_unique_id, shipments.rider_unique_id, shipments.current_location, shipments.longitude, shipments.latitude,
            shipments.delivery_status, shipments.added_date, shipments.last_modified, shipments.status, riders.fullname as rider_fullname, riders.email as rider_email, riders.phone_number as rider_phone_number FROM shipments
            INNER JOIN riders ON shipments.rider_unique_id = riders.unique_id WHERE shipments.shipment_unique_id=:shipment_unique_id ORDER BY shipments.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":shipment_unique_id", $shipment_unique_id);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {

              foreach ($result as $key => $value) {

                $current_shipment = array();
                $current_shipment['id'] = $value['id'];
                $current_shipment['unique_id'] = $value['unique_id'];
                $current_shipment['shipment_unique_id'] = $value['shipment_unique_id'];
                $current_shipment['rider_unique_id'] = $value['rider_unique_id'];
                $current_shipment['current_location'] = $value['current_location'];
                $current_shipment['longitude'] = $value['longitude'];
                $current_shipment['latitude'] = $value['latitude'];
                $current_shipment['delivery_status'] = $value['delivery_status'];
                $current_shipment['added_date'] = $value['added_date'];
                $current_shipment['last_modified'] = $value['last_modified'];
                $current_shipment['status'] = $value['status'];
                $current_shipment['rider_fullname'] = $value['rider_fullname'];
                $current_shipment['rider_email'] = $value['rider_email'];
                $current_shipment['rider_phone_number'] = $value['rider_phone_number'];

                $sql2 = "SELECT firstname as address_first_name, lastname as address_last_name, address, additional_information, city, state, country, default_status FROM users_addresses WHERE user_unique_id=:user_unique_id ORDER BY FIELD (default_status, 'Yes') DESC";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":user_unique_id", $user_unique_id);
                $query2->execute();

                $result2 = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_user_address = array();

                  foreach ($result2 as $key => $value) {

                    $current_user_address_value = array();

                    $current_user_address_value['address_first_name'] = $value['address_first_name'];
                    $current_user_address_value['address_last_name'] = $value['address_last_name'];
                    $current_user_address_value['address'] = $value['address'];
                    $current_user_address_value['additional_information'] = $value['additional_information'];
                    $current_user_address_value['city'] = $value['city'];
                    $current_user_address_value['state'] = $value['state'];
                    $current_user_address_value['country'] = $value['country'];
                    $current_user_address_value['default_status'] = $value['default_status'];

                    $current_user_address[] = $current_user_address_value;

                  }
                  $current_shipment['users_addresses'] = $current_user_address;
                }
                else {
                  $current_shipment['users_addresses'] = null;
                }

                $shipment_array = $current_shipment;

              }

              return $shipment_array;
            }
            else {
              $output['error'] = true;
              $output['message'] = "Empty";
              return $output;
            }

            $this->conn->commit();
          } catch (PDOException $e) {
            $this->conn->rollback();
            throw $e;
            $output['error'] = true;
            $output['message'] = "Critical error occured";
            return $output;
          }
        }
        else {
          $output['error'] = true;
          $output['message'] = "All fields are required";
          return $output;
        }

      }

      function get_shipment_details_for_users($user_unique_id, $shipment_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values) && !in_array($shipment_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $shipment_array = array();

            $date_added = $this->functions->today;
            $active = $this->functions->active;
            $null = $this->functions->null;
            $shipment_table_values = $this->functions->shipment_table_values;
            $user_addresses_table_values = $this->functions->user_addresses_table_values;

            $sql = "SELECT shipments.unique_id, shipments.shipment_unique_id, shipments.rider_unique_id, shipments.current_location, shipments.longitude, shipments.latitude,
            shipments.delivery_status, shipments.last_modified, shipments.status, riders.fullname as rider_fullname, riders.email as rider_email, riders.phone_number as rider_phone_number FROM shipments
            INNER JOIN riders ON shipments.rider_unique_id = riders.unique_id WHERE shipments.shipment_unique_id=:shipment_unique_id ORDER BY shipments.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":shipment_unique_id", $shipment_unique_id);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {

              foreach ($result as $key => $value) {

                $current_shipment = array();
                $current_shipment['unique_id'] = $value['unique_id'];
                $current_shipment['shipment_unique_id'] = $value['shipment_unique_id'];
                $current_shipment['rider_unique_id'] = $value['rider_unique_id'];
                $current_shipment['current_location'] = $value['current_location'];
                $current_shipment['longitude'] = $value['longitude'];
                $current_shipment['latitude'] = $value['latitude'];
                $current_shipment['delivery_status'] = $value['delivery_status'];
                $current_shipment['last_modified'] = $value['last_modified'];
                $current_shipment['status'] = $value['status'];
                $current_shipment['rider_fullname'] = $value['rider_fullname'];
                $current_shipment['rider_email'] = $value['rider_email'];
                $current_shipment['rider_phone_number'] = $value['rider_phone_number'];

                $sql2 = "SELECT firstname as address_first_name, lastname as address_last_name, address, additional_information, city, state, country, default_status FROM users_addresses WHERE user_unique_id=:user_unique_id ORDER BY FIELD (default_status, 'Yes') DESC";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":user_unique_id", $user_unique_id);
                $query2->execute();

                $result2 = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_user_address = array();

                  foreach ($result2 as $key => $value) {

                    $current_user_address_value = array();

                    $current_user_address_value['address_first_name'] = $value['address_first_name'];
                    $current_user_address_value['address_last_name'] = $value['address_last_name'];
                    $current_user_address_value['address'] = $value['address'];
                    $current_user_address_value['additional_information'] = $value['additional_information'];
                    $current_user_address_value['city'] = $value['city'];
                    $current_user_address_value['state'] = $value['state'];
                    $current_user_address_value['country'] = $value['country'];
                    $current_user_address_value['default_status'] = $value['default_status'];

                    $current_user_address[] = $current_user_address_value;

                  }
                  $current_shipment['users_addresses'] = $current_user_address;
                }
                else {
                  $current_shipment['users_addresses'] = null;
                }

                $shipment_array = $current_shipment;

              }

              return $shipment_array;
            }
            else {
              $output['error'] = true;
              $output['message'] = "Empty";
              return $output;
            }

            $this->conn->commit();
          } catch (PDOException $e) {
            $this->conn->rollback();
            throw $e;
            $output['error'] = true;
            $output['message'] = "Critical error occured";
            return $output;
          }
        }
        else {
          $output['error'] = true;
          $output['message'] = "All fields are required";
          return $output;
        }

      }

  }

?>
