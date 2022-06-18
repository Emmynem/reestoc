<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';

  class Cart{

      // database connection and table name
      private $conn;
      private $table_name = "carts";

      // object properties
      public $id;
      public $unique_id;
      public $user_unique_id;
      public $sub_product_unique_id;
      public $quantity;
      public $added_date;
      public $last_modified;
      public $status;

      private $functions;
      private $not_allowed_values;

      public $output = array('error' => false, 'success' => false);

      // constructor with $db as database connection
      public function __construct($db){
        $this->conn = $db;
        $this->functions = new Functions();
        $this->not_allowed_values = $this->functions->not_allowed_values;
      }

      function get_all_cart(){

        try {
          $this->conn->beginTransaction();

          $active = $this->functions->active;
          $date_added = $this->functions->today;

          $cart_array = array();

          $sql = "SELECT carts.id, carts.unique_id, carts.user_unique_id, carts.sub_product_unique_id, carts.quantity, carts.shipping_fee_unique_id, carts.added_date, carts.last_modified, carts.status, users.fullname as user_fullname,
          users.email as user_email, users.phone_number as user_phone_number, users.phone_number as user_phone_number, sub_products.name, sub_products.size, sub_products.stripped, sub_products.stock, sub_products.stock_remaining, sub_products.price, sub_products.sales_price, sub_products.favorites, shipping_fees.price as shipping_price FROM carts
          INNER JOIN users ON carts.user_unique_id = users.unique_id LEFT JOIN sub_products ON carts.sub_product_unique_id = sub_products.unique_id LEFT JOIN shipping_fees ON carts.shipping_fee_unique_id = shipping_fees.unique_id ORDER BY carts.added_date DESC";
          $query = $this->conn->prepare($sql);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_cart = array();
              $current_cart['id'] = $value['id'];
              $current_cart['unique_id'] = $value['unique_id'];
              $current_cart['user_unique_id'] = $value['user_unique_id'];
              $current_cart['sub_product_unique_id'] = $value['sub_product_unique_id'];
              $current_cart['quantity'] = $value['quantity'];
              $current_cart['user_fullname'] = $value['user_fullname'];
              $current_cart['user_email'] = $value['user_email'];
              $current_cart['user_phone_number'] = $value['user_phone_number'];
              $current_cart['added_date'] = $value['added_date'];
              $current_cart['last_modified'] = $value['last_modified'];
              $current_cart['status'] = $value['status'];
              $current_cart['name'] = $value['name'];
              $current_cart['size'] = $value['size'];
              $current_cart['stripped'] = $value['stripped'];
              $current_cart['stock'] = $value['stock'];
              $current_cart['stock_remaining'] = $value['stock_remaining'];
              $current_cart['price'] = $value['price'];
              $current_cart['sales_price'] = $value['sales_price'];
              $current_cart['favorites'] = $value['favorites'];
              $current_cart['shipping_price'] = $value['shipping_price'];

              $sub_product_id = $value['sub_product_unique_id'];
              $cart_unique_id = $value['unique_id'];
              $user_unique_id = $value['user_unique_id'];

              $current_shipping_price = (int)$value['shipping_price'];
              $current_cart_price = (int)$value['price'];
              $current_cart_sales_price = (int)$value['sales_price'];
              $current_cart_quantity = (int)$value['quantity'];

              $current_cart['full_shipping_price'] = $current_shipping_price * $current_cart_quantity;

              $sql2 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id LIMIT 1";
              $query2 = $this->conn->prepare($sql2);
              $query2->bindParam(":sub_product_unique_id", $sub_product_id);
              $query2->execute();

              $images_result = $query2->fetchAll();

              if ($query2->rowCount() > 0) {
                $current_cart_images = array();

                foreach ($images_result as $key => $image_value) {
                  $current_cart_images[] = $image_value['image'];
                }

                $current_cart['cart_product_images'] = $current_cart_images;
              }
              else{
                $current_cart['cart_product_images'] = null;
              }

              if ($current_cart_sales_price != 0) {
                $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND cart_unique_id=:cart_unique_id";
                $query7 = $this->conn->prepare($sql7);
                $query7->bindParam(":user_unique_id", $user_unique_id);
                $query7->bindParam(":cart_unique_id", $cart_unique_id);
                $query7->execute();

                if ($query7->rowCount() > 0) {
                  $the_cart_services_details = $query7->fetchAll();
                  $offered_services_price = 0;

                  foreach ($the_cart_services_details as $key => $value) {
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

                  $current_cart['total_cart_amount'] = (($current_cart_sales_price * $current_cart_quantity) + ($current_shipping_price * $current_cart_quantity)) + ($offered_services_price * $current_cart_quantity);

                }
                else {
                  $current_cart['total_cart_amount'] = ($current_cart_sales_price * $current_cart_quantity) + ($current_shipping_price * $current_cart_quantity);
                }
              }
              else {
                $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND cart_unique_id=:cart_unique_id";
                $query7 = $this->conn->prepare($sql7);
                $query7->bindParam(":user_unique_id", $user_unique_id);
                $query7->bindParam(":cart_unique_id", $cart_unique_id);
                $query7->execute();

                if ($query7->rowCount() > 0) {
                  $the_cart_services_details = $query7->fetchAll();
                  $offered_services_price = 0;

                  foreach ($the_cart_services_details as $key => $value) {
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

                  $current_cart['total_cart_amount'] = (($current_cart_price * $current_cart_quantity) + ($current_shipping_price * $current_cart_quantity)) + ($offered_services_price * $current_cart_quantity);

                }
                else {
                  $current_cart['total_cart_amount'] = ($current_cart_price * $current_cart_quantity) + ($current_shipping_price * $current_cart_quantity);
                }
              }

              $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND cart_unique_id=:cart_unique_id";
              $query7 = $this->conn->prepare($sql7);
              $query7->bindParam(":user_unique_id", $user_unique_id);
              $query7->bindParam(":cart_unique_id", $cart_unique_id);
              $query7->execute();

              if ($query7->rowCount() > 0) {
                $the_cart_services_details = $query7->fetchAll();

                $current_cart_offered_services = array();
                $current_cart_offered_services_price = array();
                $current_cart_offered_services_unique_id = array();

                $total_offered_services_amount = 0;

                foreach ($the_cart_services_details as $key => $value) {
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

                    $current_cart_offered_services[] = $the_offered_services_service;

                    $current_cart['cart_offered_services'] = $current_cart_offered_services;

                    $current_cart_offered_services_price[] = $the_offered_services_price;

                    $total_offered_services_amount += $the_offered_services_price;

                    $current_cart['cart_offered_services_price'] = $current_cart_offered_services_price;

                    $current_cart_offered_services_unique_id[] = $offered_service_unique_id;

                    $current_cart['cart_offered_services_unique_ids'] = $current_cart_offered_services_unique_id;

                  }
                  else{
                    $current_cart['cart_offered_services'] = null;
                    $current_cart['cart_offered_services_price'] = null;
                    $current_cart['cart_offered_services_unique_ids'] = null;
                  }
                }

                $current_cart['cart_offered_services_price_total_amount'] = $total_offered_services_amount * $current_cart_quantity;

              }
              else {
                $current_cart['cart_offered_services'] = null;
                $current_cart['cart_offered_services_price'] = null;
                $current_cart['cart_offered_services_unique_ids'] = null;
                $current_cart['cart_offered_services_price_total_amount'] = null;
              }

              $cart_array[] = $current_cart;
            }
            return $cart_array;
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

      function get_cart_filter($start_date, $end_date){

        if (!in_array($start_date,$this->not_allowed_values) && !in_array($end_date,$this->not_allowed_values)) {

          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;
            $date_added = $this->functions->today;

            $cart_array = array();

            $sql = "SELECT carts.id, carts.unique_id, carts.user_unique_id, carts.sub_product_unique_id, carts.quantity, carts.shipping_fee_unique_id, carts.added_date, carts.last_modified, carts.status, users.fullname as user_fullname,
            users.email as user_email, users.phone_number as user_phone_number, users.phone_number as user_phone_number, sub_products.name, sub_products.size, sub_products.stripped, sub_products.stock, sub_products.stock_remaining, sub_products.price, sub_products.sales_price, sub_products.favorites, shipping_fees.price as shipping_price FROM carts
            INNER JOIN users ON carts.user_unique_id = users.unique_id LEFT JOIN sub_products ON carts.sub_product_unique_id = sub_products.unique_id LEFT JOIN shipping_fees ON carts.shipping_fee_unique_id = shipping_fees.unique_id WHERE carts.added_date >:start_date AND (carts.added_date <:end_date OR carts.added_date=:end_date) ORDER BY carts.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":start_date", $start_date);
            $query->bindParam(":end_date", $end_date);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_cart = array();
                $current_cart['id'] = $value['id'];
                $current_cart['unique_id'] = $value['unique_id'];
                $current_cart['user_unique_id'] = $value['user_unique_id'];
                $current_cart['sub_product_unique_id'] = $value['sub_product_unique_id'];
                $current_cart['quantity'] = $value['quantity'];
                $current_cart['user_fullname'] = $value['user_fullname'];
                $current_cart['user_email'] = $value['user_email'];
                $current_cart['user_phone_number'] = $value['user_phone_number'];
                $current_cart['added_date'] = $value['added_date'];
                $current_cart['last_modified'] = $value['last_modified'];
                $current_cart['status'] = $value['status'];
                $current_cart['name'] = $value['name'];
                $current_cart['size'] = $value['size'];
                $current_cart['stripped'] = $value['stripped'];
                $current_cart['stock'] = $value['stock'];
                $current_cart['stock_remaining'] = $value['stock_remaining'];
                $current_cart['price'] = $value['price'];
                $current_cart['sales_price'] = $value['sales_price'];
                $current_cart['favorites'] = $value['favorites'];
                $current_cart['shipping_price'] = $value['shipping_price'];

                $sub_product_id = $value['sub_product_unique_id'];
                $cart_unique_id = $value['unique_id'];
                $user_unique_id = $value['user_unique_id'];

                $current_shipping_price = (int)$value['shipping_price'];
                $current_cart_price = (int)$value['price'];
                $current_cart_sales_price = (int)$value['sales_price'];
                $current_cart_quantity = (int)$value['quantity'];

                $current_cart['full_shipping_price'] = $current_shipping_price * $current_cart_quantity;

                $sql2 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id LIMIT 1";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":sub_product_unique_id", $sub_product_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_cart_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_cart_images[] = $image_value['image'];
                  }

                  $current_cart['cart_product_images'] = $current_cart_images;
                }
                else{
                  $current_cart['cart_product_images'] = null;
                }

                if ($current_cart_sales_price != 0) {
                  $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND cart_unique_id=:cart_unique_id";
                  $query7 = $this->conn->prepare($sql7);
                  $query7->bindParam(":user_unique_id", $user_unique_id);
                  $query7->bindParam(":cart_unique_id", $cart_unique_id);
                  $query7->execute();

                  if ($query7->rowCount() > 0) {
                    $the_cart_services_details = $query7->fetchAll();
                    $offered_services_price = 0;

                    foreach ($the_cart_services_details as $key => $value) {
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

                    $current_cart['total_cart_amount'] = (($current_cart_sales_price * $current_cart_quantity) + ($current_shipping_price * $current_cart_quantity)) + ($offered_services_price * $current_cart_quantity);

                  }
                  else {
                    $current_cart['total_cart_amount'] = ($current_cart_sales_price * $current_cart_quantity) + ($current_shipping_price * $current_cart_quantity);
                  }
                }
                else {
                  $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND cart_unique_id=:cart_unique_id";
                  $query7 = $this->conn->prepare($sql7);
                  $query7->bindParam(":user_unique_id", $user_unique_id);
                  $query7->bindParam(":cart_unique_id", $cart_unique_id);
                  $query7->execute();

                  if ($query7->rowCount() > 0) {
                    $the_cart_services_details = $query7->fetchAll();
                    $offered_services_price = 0;

                    foreach ($the_cart_services_details as $key => $value) {
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

                    $current_cart['total_cart_amount'] = (($current_cart_price * $current_cart_quantity) + ($current_shipping_price * $current_cart_quantity)) + ($offered_services_price * $current_cart_quantity);

                  }
                  else {
                    $current_cart['total_cart_amount'] = ($current_cart_price * $current_cart_quantity) + ($current_shipping_price * $current_cart_quantity);
                  }
                }

                $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND cart_unique_id=:cart_unique_id";
                $query7 = $this->conn->prepare($sql7);
                $query7->bindParam(":user_unique_id", $user_unique_id);
                $query7->bindParam(":cart_unique_id", $cart_unique_id);
                $query7->execute();

                if ($query7->rowCount() > 0) {
                  $the_cart_services_details = $query7->fetchAll();

                  $current_cart_offered_services = array();
                  $current_cart_offered_services_price = array();
                  $current_cart_offered_services_unique_id = array();

                  $total_offered_services_amount = 0;

                  foreach ($the_cart_services_details as $key => $value) {
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

                      $current_cart_offered_services[] = $the_offered_services_service;

                      $current_cart['cart_offered_services'] = $current_cart_offered_services;

                      $current_cart_offered_services_price[] = $the_offered_services_price;

                      $total_offered_services_amount += $the_offered_services_price;

                      $current_cart['cart_offered_services_price'] = $current_cart_offered_services_price;

                      $current_cart_offered_services_unique_id[] = $offered_service_unique_id;

                      $current_cart['cart_offered_services_unique_ids'] = $current_cart_offered_services_unique_id;

                    }
                    else{
                      $current_cart['cart_offered_services'] = null;
                      $current_cart['cart_offered_services_price'] = null;
                      $current_cart['cart_offered_services_unique_ids'] = null;
                    }
                  }

                  $current_cart['cart_offered_services_price_total_amount'] = $total_offered_services_amount * $current_cart_quantity;

                }
                else {
                  $current_cart['cart_offered_services'] = null;
                  $current_cart['cart_offered_services_price'] = null;
                  $current_cart['cart_offered_services_unique_ids'] = null;
                  $current_cart['cart_offered_services_price_total_amount'] = null;
                }

                $cart_array[] = $current_cart;
              }
              return $cart_array;
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

      function get_user_cart($user_unique_id){

        if (!in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $cart_array = array();
            $date_added = $this->functions->today;

            $active = $this->functions->active;

            $sql = "SELECT carts.id, carts.unique_id, carts.user_unique_id, carts.sub_product_unique_id, carts.quantity, carts.shipping_fee_unique_id, carts.added_date, carts.last_modified, carts.status, users.fullname as user_fullname,
            users.email as user_email, users.phone_number as user_phone_number, sub_products.name, sub_products.size, sub_products.stripped, sub_products.stock, sub_products.stock_remaining, sub_products.price, sub_products.sales_price, sub_products.favorites, shipping_fees.price as shipping_price FROM carts
            INNER JOIN users ON carts.user_unique_id = users.unique_id LEFT JOIN sub_products ON carts.sub_product_unique_id = sub_products.unique_id LEFT JOIN shipping_fees ON carts.shipping_fee_unique_id = shipping_fees.unique_id WHERE carts.user_unique_id =:user_unique_id AND carts.status=:status ORDER BY carts.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->bindParam(":status", $active);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_cart = array();
                $current_cart['id'] = $value['id'];
                $current_cart['unique_id'] = $value['unique_id'];
                $current_cart['user_unique_id'] = $value['user_unique_id'];
                $current_cart['sub_product_unique_id'] = $value['sub_product_unique_id'];
                $current_cart['quantity'] = $value['quantity'];
                $current_cart['user_fullname'] = $value['user_fullname'];
                $current_cart['user_email'] = $value['user_email'];
                $current_cart['user_phone_number'] = $value['user_phone_number'];
                $current_cart['added_date'] = $value['added_date'];
                $current_cart['last_modified'] = $value['last_modified'];
                $current_cart['status'] = $value['status'];
                $current_cart['name'] = $value['name'];
                $current_cart['size'] = $value['size'];
                $current_cart['stripped'] = $value['stripped'];
                $current_cart['stock'] = $value['stock'];
                $current_cart['stock_remaining'] = $value['stock_remaining'];
                $current_cart['price'] = $value['price'];
                $current_cart['sales_price'] = $value['sales_price'];
                $current_cart['favorites'] = $value['favorites'];
                $current_cart['shipping_price'] = $value['shipping_price'];

                $sub_product_id = $value['sub_product_unique_id'];
                $cart_unique_id = $value['unique_id'];

                $current_shipping_price = (int)$value['shipping_price'];
                $current_cart_price = (int)$value['price'];
                $current_cart_sales_price = (int)$value['sales_price'];
                $current_cart_quantity = (int)$value['quantity'];

                $current_cart['full_shipping_price'] = $current_shipping_price * $current_cart_quantity;

                $sql2 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id LIMIT 1";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":sub_product_unique_id", $sub_product_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_cart_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_cart_images[] = $image_value['image'];
                  }

                  $current_cart['cart_product_images'] = $current_cart_images;
                }
                else{
                  $current_cart['cart_product_images'] = null;
                }

                if ($current_cart_sales_price != 0) {
                  $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND cart_unique_id=:cart_unique_id";
                  $query7 = $this->conn->prepare($sql7);
                  $query7->bindParam(":user_unique_id", $user_unique_id);
                  $query7->bindParam(":cart_unique_id", $cart_unique_id);
                  $query7->execute();

                  if ($query7->rowCount() > 0) {
                    $the_cart_services_details = $query7->fetchAll();
                    $offered_services_price = 0;

                    foreach ($the_cart_services_details as $key => $value) {
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

                    $current_cart['total_cart_amount'] = (($current_cart_sales_price * $current_cart_quantity) + ($current_shipping_price * $current_cart_quantity)) + ($offered_services_price * $current_cart_quantity);

                  }
                  else {
                    $current_cart['total_cart_amount'] = ($current_cart_sales_price * $current_cart_quantity) + ($current_shipping_price * $current_cart_quantity);
                  }
                }
                else {
                  $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND cart_unique_id=:cart_unique_id";
                  $query7 = $this->conn->prepare($sql7);
                  $query7->bindParam(":user_unique_id", $user_unique_id);
                  $query7->bindParam(":cart_unique_id", $cart_unique_id);
                  $query7->execute();

                  if ($query7->rowCount() > 0) {
                    $the_cart_services_details = $query7->fetchAll();
                    $offered_services_price = 0;

                    foreach ($the_cart_services_details as $key => $value) {
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

                    $current_cart['total_cart_amount'] = (($current_cart_price * $current_cart_quantity) + ($current_shipping_price * $current_cart_quantity)) + ($offered_services_price * $current_cart_quantity);

                  }
                  else {
                    $current_cart['total_cart_amount'] = ($current_cart_price * $current_cart_quantity) + ($current_shipping_price * $current_cart_quantity);
                  }
                }

                $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND cart_unique_id=:cart_unique_id";
                $query7 = $this->conn->prepare($sql7);
                $query7->bindParam(":user_unique_id", $user_unique_id);
                $query7->bindParam(":cart_unique_id", $cart_unique_id);
                $query7->execute();

                if ($query7->rowCount() > 0) {
                  $the_cart_services_details = $query7->fetchAll();

                  $current_cart_offered_services = array();
                  $current_cart_offered_services_price = array();
                  $current_cart_offered_services_unique_id = array();

                  $total_offered_services_amount = 0;

                  foreach ($the_cart_services_details as $key => $value) {
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

                      $current_cart_offered_services[] = $the_offered_services_service;

                      $current_cart['cart_offered_services'] = $current_cart_offered_services;

                      $current_cart_offered_services_price[] = $the_offered_services_price;

                      $total_offered_services_amount += $the_offered_services_price;

                      $current_cart['cart_offered_services_price'] = $current_cart_offered_services_price;

                      $current_cart_offered_services_unique_id[] = $offered_service_unique_id;

                      $current_cart['cart_offered_services_unique_ids'] = $current_cart_offered_services_unique_id;

                    }
                    else{
                      $current_cart['cart_offered_services'] = null;
                      $current_cart['cart_offered_services_price'] = null;
                      $current_cart['cart_offered_services_unique_ids'] = null;
                    }
                  }

                  $current_cart['cart_offered_services_price_total_amount'] = $total_offered_services_amount * $current_cart_quantity;

                }
                else {
                  $current_cart['cart_offered_services'] = null;
                  $current_cart['cart_offered_services_price'] = null;
                  $current_cart['cart_offered_services_unique_ids'] = null;
                  $current_cart['cart_offered_services_price_total_amount'] = null;
                }

                $cart_array[] = $current_cart;
              }
              return $cart_array;
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

      function get_user_cart_for_users($user_unique_id){

        if (!in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $cart_array = array();
            $cart_unique_id_array = array();
            $cart_unique_id_alternate_array = array();
            $date_added = $this->functions->today;

            $active = $this->functions->active;

            $sql = "SELECT carts.unique_id, carts.sub_product_unique_id, carts.quantity, carts.shipping_fee_unique_id, sub_products.name, sub_products.size, sub_products.stripped, sub_products.stock, sub_products.price, sub_products.sales_price, shipping_fees.price as shipping_price FROM carts
            LEFT JOIN sub_products ON carts.sub_product_unique_id = sub_products.unique_id LEFT JOIN shipping_fees ON carts.shipping_fee_unique_id = shipping_fees.unique_id WHERE carts.user_unique_id =:user_unique_id AND carts.status=:status ORDER BY carts.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->bindParam(":status", $active);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_cart_unique_ids_alternate = array();
                $current_cart = array();
                $current_cart['unique_id'] = $value['unique_id'];
                $current_cart['sub_product_unique_id'] = $value['sub_product_unique_id'];
                $current_cart['quantity'] = $value['quantity'];
                $current_cart['name'] = $value['name'];
                $current_cart['size'] = $value['size'];
                $current_cart['stripped'] = $value['stripped'];
                $current_cart['stock'] = $value['stock'];
                $current_cart['price'] = $value['price'];
                $current_cart['sales_price'] = $value['sales_price'];
                $current_cart['shipping_price'] = $value['shipping_price'];

                $sub_product_id = $value['sub_product_unique_id'];
                $cart_unique_id = $value['unique_id'];

                $cart_unique_id_array[] = $cart_unique_id;
                $current_cart_unique_ids_alternate['cart_unique_id'] = $cart_unique_id;
                $current_cart_unique_ids_alternate['quantity'] = $current_cart['quantity'];

                $current_shipping_price = (int)$value['shipping_price'];
                $current_cart_price = (int)$value['price'];
                $current_cart_sales_price = (int)$value['sales_price'];
                $current_cart_quantity = (int)$value['quantity'];

                $current_cart['full_shipping_price'] = $current_shipping_price * $current_cart_quantity;

                $sql2 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id LIMIT 1";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":sub_product_unique_id", $sub_product_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_cart_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_cart_images[] = $image_value['image'];
                  }

                  $current_cart['cart_product_images'] = $current_cart_images;
                }
                else{
                  $current_cart['cart_product_images'] = null;
                }

                if ($current_cart_sales_price != 0) {
                  $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND cart_unique_id=:cart_unique_id";
                  $query7 = $this->conn->prepare($sql7);
                  $query7->bindParam(":user_unique_id", $user_unique_id);
                  $query7->bindParam(":cart_unique_id", $cart_unique_id);
                  $query7->execute();

                  if ($query7->rowCount() > 0) {
                    $the_cart_services_details = $query7->fetchAll();
                    $offered_services_price = 0;

                    foreach ($the_cart_services_details as $key => $value) {
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

                    $current_cart['total_cart_amount'] = (($current_cart_sales_price * $current_cart_quantity) + ($current_shipping_price * $current_cart_quantity)) + ($offered_services_price * $current_cart_quantity);

                  }
                  else {
                    $current_cart['total_cart_amount'] = ($current_cart_sales_price * $current_cart_quantity) + ($current_shipping_price * $current_cart_quantity);
                  }
                }
                else {
                  $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND cart_unique_id=:cart_unique_id";
                  $query7 = $this->conn->prepare($sql7);
                  $query7->bindParam(":user_unique_id", $user_unique_id);
                  $query7->bindParam(":cart_unique_id", $cart_unique_id);
                  $query7->execute();

                  if ($query7->rowCount() > 0) {
                    $the_cart_services_details = $query7->fetchAll();
                    $offered_services_price = 0;

                    foreach ($the_cart_services_details as $key => $value) {
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

                    $current_cart['total_cart_amount'] = (($current_cart_price * $current_cart_quantity) + ($current_shipping_price * $current_cart_quantity)) + ($offered_services_price * $current_cart_quantity);

                  }
                  else {
                    $current_cart['total_cart_amount'] = ($current_cart_price * $current_cart_quantity) + ($current_shipping_price * $current_cart_quantity);
                  }
                }

                $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND cart_unique_id=:cart_unique_id";
                $query7 = $this->conn->prepare($sql7);
                $query7->bindParam(":user_unique_id", $user_unique_id);
                $query7->bindParam(":cart_unique_id", $cart_unique_id);
                $query7->execute();

                if ($query7->rowCount() > 0) {
                  $the_cart_services_details = $query7->fetchAll();

                  $current_cart_offered_services = array();
                  $current_cart_offered_services_price = array();
                  $current_cart_offered_services_unique_id = array();

                  $total_offered_services_amount = 0;

                  foreach ($the_cart_services_details as $key => $value) {
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

                      $current_cart_offered_services['service'] = $the_offered_services_service;

                      $current_cart_offered_services['price'] = $the_offered_services_price;

                      $total_offered_services_amount += $the_offered_services_price;

                      // $current_cart['cart_offered_services_price'] = $current_cart_offered_services_price;

                      $current_cart_offered_services['unique_id'] = $offered_service_unique_id;

                      // $current_cart['cart_offered_services_unique_ids'] = $current_cart_offered_services_unique_id;

                      $current_cart['cart_offered_services'][$key] = $current_cart_offered_services;

                    }
                    else{
                      $current_cart['cart_offered_services'] = null;
                      // $current_cart['cart_offered_services_price'] = null;
                      // $current_cart['cart_offered_services_unique_ids'] = null;
                    }
                  }

                  $current_cart['cart_offered_services_price_total_amount'] = $total_offered_services_amount * $current_cart_quantity;

                }
                else {
                  $current_cart['cart_offered_services'] = null;
                  $current_cart['cart_offered_services_price'] = null;
                  $current_cart['cart_offered_services_unique_ids'] = null;
                  $current_cart['cart_offered_services_price_total_amount'] = null;
                }

                $cart_array[] = $current_cart;
                $cart_unique_id_alternate_array[] = $current_cart_unique_ids_alternate;
              }

              $full_array = array(
                array($cart_array),
                array($cart_unique_id_array),
                array($cart_unique_id_alternate_array)
              );
              return $full_array;
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

      function get_user_cart_details($user_unique_id, $cart_unique_id){

        if (!in_array($user_unique_id,$this->not_allowed_values) && !in_array($cart_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $cart_array = array();
            $date_added = $this->functions->today;

            $active = $this->functions->active;

            $sql = "SELECT carts.id, carts.unique_id, carts.user_unique_id, carts.sub_product_unique_id, carts.quantity, carts.shipping_fee_unique_id, carts.added_date, carts.last_modified, carts.status, users.fullname as user_fullname,
            users.email as user_email, users.phone_number as user_phone_number, sub_products.name, sub_products.size, sub_products.stripped, sub_products.stock, sub_products.stock_remaining, sub_products.price, sub_products.sales_price, sub_products.favorites, shipping_fees.price as shipping_price FROM carts
            INNER JOIN users ON carts.user_unique_id = users.unique_id LEFT JOIN sub_products ON carts.sub_product_unique_id = sub_products.unique_id LEFT JOIN shipping_fees ON carts.shipping_fee_unique_id = shipping_fees.unique_id WHERE carts.user_unique_id =:user_unique_id AND carts.unique_id =:cart_unique_id ORDER BY carts.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->bindParam(":cart_unique_id", $cart_unique_id);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_cart = array();
                $current_cart['id'] = $value['id'];
                $current_cart['unique_id'] = $value['unique_id'];
                $current_cart['user_unique_id'] = $value['user_unique_id'];
                $current_cart['sub_product_unique_id'] = $value['sub_product_unique_id'];
                $current_cart['quantity'] = $value['quantity'];
                $current_cart['user_fullname'] = $value['user_fullname'];
                $current_cart['user_email'] = $value['user_email'];
                $current_cart['user_phone_number'] = $value['user_phone_number'];
                $current_cart['added_date'] = $value['added_date'];
                $current_cart['last_modified'] = $value['last_modified'];
                $current_cart['status'] = $value['status'];
                $current_cart['name'] = $value['name'];
                $current_cart['size'] = $value['size'];
                $current_cart['stripped'] = $value['stripped'];
                $current_cart['stock'] = $value['stock'];
                $current_cart['stock_remaining'] = $value['stock_remaining'];
                $current_cart['price'] = $value['price'];
                $current_cart['sales_price'] = $value['sales_price'];
                $current_cart['favorites'] = $value['favorites'];
                $current_cart['shipping_price'] = $value['shipping_price'];

                $sub_product_id = $value['sub_product_unique_id'];
                $cart_unique_id = $value['unique_id'];

                $current_shipping_price = (int)$value['shipping_price'];
                $current_cart_price = (int)$value['price'];
                $current_cart_sales_price = (int)$value['sales_price'];
                $current_cart_quantity = (int)$value['quantity'];

                $current_cart['full_shipping_price'] = $current_shipping_price * $current_cart_quantity;

                $sql2 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id LIMIT 1";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":sub_product_unique_id", $sub_product_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_cart_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_cart_images[] = $image_value['image'];
                  }

                  $current_cart['cart_product_images'] = $current_cart_images;
                }
                else{
                  $current_cart['cart_product_images'] = null;
                }

                if ($current_cart_sales_price != 0) {
                  $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND cart_unique_id=:cart_unique_id";
                  $query7 = $this->conn->prepare($sql7);
                  $query7->bindParam(":user_unique_id", $user_unique_id);
                  $query7->bindParam(":cart_unique_id", $cart_unique_id);
                  $query7->execute();

                  if ($query7->rowCount() > 0) {
                    $the_cart_services_details = $query7->fetchAll();
                    $offered_services_price = 0;

                    foreach ($the_cart_services_details as $key => $value) {
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

                    $current_cart['total_cart_amount'] = (($current_cart_sales_price * $current_cart_quantity) + ($current_shipping_price * $current_cart_quantity)) + ($offered_services_price * $current_cart_quantity);

                  }
                  else {
                    $current_cart['total_cart_amount'] = ($current_cart_sales_price * $current_cart_quantity) + ($current_shipping_price * $current_cart_quantity);
                  }
                }
                else {
                  $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND cart_unique_id=:cart_unique_id";
                  $query7 = $this->conn->prepare($sql7);
                  $query7->bindParam(":user_unique_id", $user_unique_id);
                  $query7->bindParam(":cart_unique_id", $cart_unique_id);
                  $query7->execute();

                  if ($query7->rowCount() > 0) {
                    $the_cart_services_details = $query7->fetchAll();
                    $offered_services_price = 0;

                    foreach ($the_cart_services_details as $key => $value) {
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

                    $current_cart['total_cart_amount'] = (($current_cart_price * $current_cart_quantity) + ($current_shipping_price * $current_cart_quantity)) + ($offered_services_price * $current_cart_quantity);

                  }
                  else {
                    $current_cart['total_cart_amount'] = ($current_cart_price * $current_cart_quantity) + ($current_shipping_price * $current_cart_quantity);
                  }
                }

                $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND cart_unique_id=:cart_unique_id";
                $query7 = $this->conn->prepare($sql7);
                $query7->bindParam(":user_unique_id", $user_unique_id);
                $query7->bindParam(":cart_unique_id", $cart_unique_id);
                $query7->execute();

                if ($query7->rowCount() > 0) {
                  $the_cart_services_details = $query7->fetchAll();

                  $current_cart_offered_services = array();
                  $current_cart_offered_services_price = array();
                  $current_cart_offered_services_unique_id = array();

                  $total_offered_services_amount = 0;

                  foreach ($the_cart_services_details as $key => $value) {
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

                      $current_cart_offered_services[] = $the_offered_services_service;

                      $current_cart['cart_offered_services'] = $current_cart_offered_services;

                      $current_cart_offered_services_price[] = $the_offered_services_price;

                      $total_offered_services_amount += $the_offered_services_price;

                      $current_cart['cart_offered_services_price'] = $current_cart_offered_services_price;

                      $current_cart_offered_services_unique_id[] = $offered_service_unique_id;

                      $current_cart['cart_offered_services_unique_ids'] = $current_cart_offered_services_unique_id;

                    }
                    else{
                      $current_cart['cart_offered_services'] = null;
                      $current_cart['cart_offered_services_price'] = null;
                      $current_cart['cart_offered_services_unique_ids'] = null;
                    }
                  }

                  $current_cart['cart_offered_services_price_total_amount'] = $total_offered_services_amount * $current_cart_quantity;

                }
                else {
                  $current_cart['cart_offered_services'] = null;
                  $current_cart['cart_offered_services_price'] = null;
                  $current_cart['cart_offered_services_unique_ids'] = null;
                  $current_cart['cart_offered_services_price_total_amount'] = null;
                }

                $cart_array[] = $current_cart;
              }
              return $cart_array;
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

      function get_user_cart_details_for_users($user_unique_id, $cart_unique_id){

        if (!in_array($user_unique_id,$this->not_allowed_values) && !in_array($cart_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $cart_array = array();
            $date_added = $this->functions->today;

            $active = $this->functions->active;

            $sql = "SELECT carts.unique_id, carts.user_unique_id, carts.sub_product_unique_id, carts.quantity, carts.shipping_fee_unique_id, carts.last_modified, carts.status, sub_products.name, sub_products.size, sub_products.stripped, sub_products.stock, sub_products.stock_remaining, sub_products.price,
            sub_products.sales_price, sub_products.favorites, shipping_fees.price as shipping_price FROM carts LEFT JOIN sub_products ON carts.sub_product_unique_id = sub_products.unique_id LEFT JOIN shipping_fees ON carts.shipping_fee_unique_id = shipping_fees.unique_id
            WHERE carts.user_unique_id =:user_unique_id AND carts.unique_id =:cart_unique_id AND carts.status=:status ORDER BY carts.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->bindParam(":cart_unique_id", $cart_unique_id);
            $query->bindParam(":status", $active);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_cart = array();
                $current_cart['unique_id'] = $value['unique_id'];
                $current_cart['user_unique_id'] = $value['user_unique_id'];
                $current_cart['sub_product_unique_id'] = $value['sub_product_unique_id'];
                $current_cart['quantity'] = $value['quantity'];
                $current_cart['last_modified'] = $value['last_modified'];
                $current_cart['status'] = $value['status'];
                $current_cart['name'] = $value['name'];
                $current_cart['size'] = $value['size'];
                $current_cart['stripped'] = $value['stripped'];
                $current_cart['stock'] = $value['stock'];
                $current_cart['stock_remaining'] = $value['stock_remaining'];
                $current_cart['price'] = $value['price'];
                $current_cart['sales_price'] = $value['sales_price'];
                $current_cart['favorites'] = $value['favorites'];
                $current_cart['shipping_price'] = $value['shipping_price'];

                $sub_product_id = $value['sub_product_unique_id'];
                $cart_unique_id = $value['unique_id'];

                $current_shipping_price = (int)$value['shipping_price'];
                $current_cart_price = (int)$value['price'];
                $current_cart_sales_price = (int)$value['sales_price'];
                $current_cart_quantity = (int)$value['quantity'];

                $current_cart['full_shipping_price'] = $current_shipping_price * $current_cart_quantity;

                $sql2 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id LIMIT 1";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":sub_product_unique_id", $sub_product_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_cart_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_cart_images[] = $image_value['image'];
                  }

                  $current_cart['cart_product_images'] = $current_cart_images;
                }
                else{
                  $current_cart['cart_product_images'] = null;
                }

                if ($current_cart_sales_price != 0) {
                  $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND cart_unique_id=:cart_unique_id";
                  $query7 = $this->conn->prepare($sql7);
                  $query7->bindParam(":user_unique_id", $user_unique_id);
                  $query7->bindParam(":cart_unique_id", $cart_unique_id);
                  $query7->execute();

                  if ($query7->rowCount() > 0) {
                    $the_cart_services_details = $query7->fetchAll();
                    $offered_services_price = 0;

                    foreach ($the_cart_services_details as $key => $value) {
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

                    $current_cart['total_cart_amount'] = (($current_cart_sales_price * $current_cart_quantity) + ($current_shipping_price * $current_cart_quantity)) + ($offered_services_price * $current_cart_quantity);

                  }
                  else {
                    $current_cart['total_cart_amount'] = ($current_cart_sales_price * $current_cart_quantity) + ($current_shipping_price * $current_cart_quantity);
                  }
                }
                else {
                  $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND cart_unique_id=:cart_unique_id";
                  $query7 = $this->conn->prepare($sql7);
                  $query7->bindParam(":user_unique_id", $user_unique_id);
                  $query7->bindParam(":cart_unique_id", $cart_unique_id);
                  $query7->execute();

                  if ($query7->rowCount() > 0) {
                    $the_cart_services_details = $query7->fetchAll();
                    $offered_services_price = 0;

                    foreach ($the_cart_services_details as $key => $value) {
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

                    $current_cart['total_cart_amount'] = (($current_cart_price * $current_cart_quantity) + ($current_shipping_price * $current_cart_quantity)) + ($offered_services_price * $current_cart_quantity);

                  }
                  else {
                    $current_cart['total_cart_amount'] = ($current_cart_price * $current_cart_quantity) + ($current_shipping_price * $current_cart_quantity);
                  }
                }

                $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND cart_unique_id=:cart_unique_id";
                $query7 = $this->conn->prepare($sql7);
                $query7->bindParam(":user_unique_id", $user_unique_id);
                $query7->bindParam(":cart_unique_id", $cart_unique_id);
                $query7->execute();

                if ($query7->rowCount() > 0) {
                  $the_cart_services_details = $query7->fetchAll();

                  $current_cart_offered_services = array();
                  $current_cart_offered_services_price = array();
                  $current_cart_offered_services_unique_id = array();

                  $total_offered_services_amount = 0;

                  foreach ($the_cart_services_details as $key => $value) {
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

                      $current_cart_offered_services['service'] = $the_offered_services_service;

                      $current_cart_offered_services['price'] = $the_offered_services_price;

                      $total_offered_services_amount += $the_offered_services_price;

                      // $current_cart['cart_offered_services_price'] = $current_cart_offered_services_price;

                      $current_cart_offered_services['unique_id'] = $offered_service_unique_id;

                      // $current_cart['cart_offered_services_unique_ids'] = $current_cart_offered_services_unique_id;

                      $current_cart['cart_offered_services'][$key] = $current_cart_offered_services;

                    }
                    else{
                      $current_cart['cart_offered_services'] = null;
                      // $current_cart['cart_offered_services_price'] = null;
                      // $current_cart['cart_offered_services_unique_ids'] = null;
                    }
                  }

                  $current_cart['cart_offered_services_price_total_amount'] = $total_offered_services_amount * $current_cart_quantity;

                }
                else {
                  $current_cart['cart_offered_services'] = null;
                  $current_cart['cart_offered_services_price'] = null;
                  $current_cart['cart_offered_services_unique_ids'] = null;
                  $current_cart['cart_offered_services_price_total_amount'] = null;
                }

                $cart_array[] = $current_cart;
              }
              return $cart_array;
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
