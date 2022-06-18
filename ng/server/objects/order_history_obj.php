<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';

  class OrderHistory{

      // database connection and table name
      private $conn;
      private $table_name = "order_history";

      // object properties
      public $id;
      public $unique_id;
      public $user_unique_id;
      public $order_unique_id;
      public $price;
      public $completion;
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

      function get_all_order_history(){

        try {
          $this->conn->beginTransaction();

          $order_history_array = array();

          $sql = "SELECT order_history.id, order_history.unique_id, order_history.user_unique_id, order_history.order_unique_id, order_history.price, order_history.completion, order_history.added_date, order_history.last_modified, order_history.status,
          users.fullname as user_fullname, users.email as user_email FROM order_history INNER JOIN users ON order_history.user_unique_id = users.unique_id ORDER BY order_history.added_date DESC";
          $query = $this->conn->prepare($sql);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_order_history = array();
              $current_order_history['id'] = $value['id'];
              $current_order_history['unique_id'] = $value['unique_id'];
              $current_order_history['user_unique_id'] = $value['user_unique_id'];
              $current_order_history['order_unique_id'] = $value['order_unique_id'];
              $current_order_history['price'] = $value['price'];
              $current_order_history['completion'] = $value['completion'];
              $current_order_history['added_date'] = $value['added_date'];
              $current_order_history['last_modified'] = $value['last_modified'];
              $current_order_history['status'] = $value['status'];
              $current_order_history['user_fullname'] = $value['user_fullname'];
              $current_order_history['user_email'] = $value['user_email'];

              $order_unique_id = $value['order_unique_id'];

              $sql3 = "SELECT orders.sub_product_unique_id, products.name, products.size, products.stripped, products.stock, products.stock_remaining, products.price, products.sales_price, products.favorites FROM orders LEFT JOIN sub_products ON orders.sub_product_unique_id = products.unique_id WHERE orders.unique_id=:unique_id";
              $query3 = $this->conn->prepare($sql3);
              $query3->bindParam(":unique_id", $order_unique_id);
              $query3->execute();

              $result3 = $query3->fetchAll();

              if ($query3->rowCount() > 0) {
                foreach ($result3 as $key => $value3) {

                  $current_order_history['sub_product_unique_id'] = $value3['sub_product_unique_id'];
                  $current_order_history['name'] = $value3['name'];
                  $current_order_history['size'] = $value3['size'];
                  $current_order_history['stripped'] = $value3['stripped'];
                  $current_order_history['stock'] = $value3['stock'];
                  $current_order_history['stock_remaining'] = $value3['stock_remaining'];
                  $current_order_history['sub_product_price'] = $value3['price'];
                  $current_order_history['sub_product_sales_price'] = $value3['sales_price'];
                  $current_order_history['favorites'] = $value3['favorites'];

                  $sub_product_id = $value3['sub_product_unique_id'];

                  $sql2 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id LIMIT 1";
                  $query2 = $this->conn->prepare($sql2);
                  $query2->bindParam(":sub_product_unique_id", $sub_product_id);
                  $query2->execute();

                  $images_result = $query2->fetchAll();

                  if ($query2->rowCount() > 0) {
                    $current_order_history_images = array();

                    foreach ($images_result as $key => $image_value) {
                      $current_order_history_images[] = $image_value['image'];
                    }

                    $current_order_history['sub_product_images'] = $current_order_history_images;
                  }
                  else{
                    $current_order_history['sub_product_images'] = null;
                  }

                }
              }
              else {
                $current_order_history['sub_product_unique_id'] = null;
                $current_order_history['name'] = null;
                $current_order_history['size'] = null;
                $current_order_history['stripped'] = null;
                $current_order_history['stock'] = null;
                $current_order_history['stock_remaining'] = null;
                $current_order_history['sub_product_price'] = null;
                $current_order_history['sub_product_sales_price'] = null;
                $current_order_history['favorites'] = null;
                $current_order_history['sub_product_images'] = null;
              }

              $order_history_array[] = $current_order_history;
            }
            return $order_history_array;
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

      function get_user_order_history($user_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $order_history_array = array();

            $sql = "SELECT order_history.id, order_history.unique_id, order_history.user_unique_id, order_history.order_unique_id, order_history.price, order_history.completion, order_history.added_date, order_history.last_modified, order_history.status,
            users.fullname as user_fullname, users.email as user_email FROM order_history INNER JOIN users ON order_history.user_unique_id = users.unique_id WHERE order_history.user_unique_id=:user_unique_id ORDER BY order_history.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_order_history = array();
                $current_order_history['id'] = $value['id'];
                $current_order_history['unique_id'] = $value['unique_id'];
                $current_order_history['user_unique_id'] = $value['user_unique_id'];
                $current_order_history['order_unique_id'] = $value['order_unique_id'];
                $current_order_history['price'] = $value['price'];
                $current_order_history['completion'] = $value['completion'];
                $current_order_history['added_date'] = $value['added_date'];
                $current_order_history['last_modified'] = $value['last_modified'];
                $current_order_history['status'] = $value['status'];
                $current_order_history['user_fullname'] = $value['user_fullname'];
                $current_order_history['user_email'] = $value['user_email'];

                $order_unique_id = $value['order_unique_id'];

                $sql3 = "SELECT orders.sub_product_unique_id, sub_products.name, sub_products.size, sub_products.stripped, sub_products.stock, sub_products.stock_remaining, sub_products.price, sub_products.sales_price, sub_products.favorites FROM orders LEFT JOIN sub_products ON orders.sub_product_unique_id = sub_products.unique_id WHERE orders.unique_id=:unique_id";
                $query3 = $this->conn->prepare($sql3);
                $query3->bindParam(":unique_id", $order_unique_id);
                $query3->execute();

                $result3 = $query3->fetchAll();

                if ($query3->rowCount() > 0) {
                  foreach ($result3 as $key => $value3) {

                    $current_order_history['sub_product_unique_id'] = $value3['sub_product_unique_id'];
                    $current_order_history['name'] = $value3['name'];
                    $current_order_history['size'] = $value3['size'];
                    $current_order_history['stripped'] = $value3['stripped'];
                    $current_order_history['stock'] = $value3['stock'];
                    $current_order_history['stock_remaining'] = $value3['stock_remaining'];
                    $current_order_history['sub_product_price'] = $value3['price'];
                    $current_order_history['sub_product_sales_price'] = $value3['sales_price'];
                    $current_order_history['favorites'] = $value3['favorites'];

                    $sub_product_id = $value3['sub_product_unique_id'];

                    $sql2 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id LIMIT 1";
                    $query2 = $this->conn->prepare($sql2);
                    $query2->bindParam(":sub_product_unique_id", $sub_product_id);
                    $query2->execute();

                    $images_result = $query2->fetchAll();

                    if ($query2->rowCount() > 0) {
                      $current_order_history_images = array();

                      foreach ($images_result as $key => $image_value) {
                        $current_order_history_images[] = $image_value['image'];
                      }

                      $current_order_history['sub_product_images'] = $current_order_history_images;
                    }
                    else{
                      $current_order_history['sub_product_images'] = null;
                    }

                  }
                }
                else {
                  $current_order_history['sub_product_unique_id'] = null;
                  $current_order_history['name'] = null;
                  $current_order_history['size'] = null;
                  $current_order_history['stripped'] = null;
                  $current_order_history['stock'] = null;
                  $current_order_history['stock_remaining'] = null;
                  $current_order_history['sub_product_price'] = null;
                  $current_order_history['sub_product_sales_price'] = null;
                  $current_order_history['favorites'] = null;
                  $current_order_history['sub_product_images'] = null;
                }

                $order_history_array[] = $current_order_history;
              }
              return $order_history_array;
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

      function get_user_single_order_history($user_unique_id, $order_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values) && !in_array($order_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $order_history_array = array();

            $sql = "SELECT order_history.id, order_history.unique_id, order_history.user_unique_id, order_history.order_unique_id, order_history.price, order_history.completion, order_history.added_date, order_history.last_modified, order_history.status,
            users.fullname as user_fullname, users.email as user_email FROM order_history INNER JOIN users ON order_history.user_unique_id = users.unique_id WHERE order_history.user_unique_id=:user_unique_id AND order_history.order_unique_id=:order_unique_id ORDER BY order_history.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->bindParam(":order_unique_id", $order_unique_id);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_order_history = array();
                $current_order_history['id'] = $value['id'];
                $current_order_history['unique_id'] = $value['unique_id'];
                $current_order_history['user_unique_id'] = $value['user_unique_id'];
                $current_order_history['order_unique_id'] = $value['order_unique_id'];
                $current_order_history['price'] = $value['price'];
                $current_order_history['completion'] = $value['completion'];
                $current_order_history['added_date'] = $value['added_date'];
                $current_order_history['last_modified'] = $value['last_modified'];
                $current_order_history['status'] = $value['status'];
                $current_order_history['user_fullname'] = $value['user_fullname'];
                $current_order_history['user_email'] = $value['user_email'];

                $order_history_array[] = $current_order_history;
              }
              return $order_history_array;
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

      function get_user_single_order_history_for_users($user_unique_id, $order_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values) && !in_array($order_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;

            $order_history_array = array();

            $sql = "SELECT order_history.user_unique_id, order_history.order_unique_id, order_history.price, order_history.completion, order_history.added_date, order_history.last_modified
            FROM order_history WHERE order_history.user_unique_id=:user_unique_id AND order_history.order_unique_id=:order_unique_id AND order_history.status=:status ORDER BY order_history.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->bindParam(":order_unique_id", $order_unique_id);
            $query->bindParam(":status", $active);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_order_history = array();
                $current_order_history['user_unique_id'] = $value['user_unique_id'];
                $current_order_history['order_unique_id'] = $value['order_unique_id'];
                $current_order_history['price'] = $value['price'];
                $current_order_history['completion'] = $value['completion'];
                $current_order_history['added_date'] = $value['added_date'];
                $current_order_history['last_modified'] = $value['last_modified'];

                $order_history_array[] = $current_order_history;
              }
              return $order_history_array;
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

      function get_all_completed_orders(){

        try {
          $this->conn->beginTransaction();

          $sql = "SELECT orders_completed.unique_id, orders_completed.user_unique_id, orders_completed.order_unique_id, orders_completed.tracker_unique_id, orders_completed.quantity, orders_completed.payment_method, orders_completed.sub_product_name, orders_completed.sub_product_size, orders_completed.rider_fullname, orders_completed.rider_phone_number,
            orders_completed.coupon_name, orders_completed.coupon_code, orders_completed.coupon_percentage, orders_completed.coupon_price, orders_completed.user_address_fullname, orders_completed.user_full_address, orders_completed.city, orders_completed.state, orders_completed.country, orders_completed.offered_services, orders_completed.offered_services_prices, orders_completed.offered_services_total_amount,
            orders_completed.shipping_fee_price, orders_completed.total_price, orders_completed.added_date, orders_completed.last_modified, orders_completed.status FROM orders_completed ORDER BY orders_completed.added_date DESC";
          $query = $this->conn->prepare($sql);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            return $result;
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

      function get_user_completed_orders($user_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT orders_completed.unique_id, orders_completed.user_unique_id, orders_completed.order_unique_id, orders_completed.tracker_unique_id, orders_completed.quantity, orders_completed.payment_method, orders_completed.sub_product_name, orders_completed.sub_product_size, orders_completed.rider_fullname, orders_completed.rider_phone_number,
              orders_completed.coupon_name, orders_completed.coupon_code, orders_completed.coupon_percentage, orders_completed.coupon_price, orders_completed.user_address_fullname, orders_completed.user_full_address, orders_completed.city, orders_completed.state, orders_completed.country, orders_completed.offered_services, orders_completed.offered_services_prices, orders_completed.offered_services_total_amount,
              orders_completed.shipping_fee_price, orders_completed.total_price, orders_completed.added_date, orders_completed.last_modified, orders_completed.status FROM orders_completed WHERE orders_completed.user_unique_id=:user_unique_id ORDER BY orders_completed.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              return $result;
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

      function get_single_completed_order_details($user_unique_id, $order_unique_id, $tracker_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values) && !in_array($order_unique_id,$this->not_allowed_values) && !in_array($tracker_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT orders_completed.unique_id, orders_completed.user_unique_id, orders_completed.order_unique_id, orders_completed.tracker_unique_id, orders_completed.quantity, orders_completed.payment_method, orders_completed.sub_product_name, orders_completed.sub_product_size, orders_completed.rider_fullname, orders_completed.rider_phone_number,
              orders_completed.coupon_name, orders_completed.coupon_code, orders_completed.coupon_percentage, orders_completed.coupon_price, orders_completed.user_address_fullname, orders_completed.user_full_address, orders_completed.city, orders_completed.state, orders_completed.country, orders_completed.offered_services, orders_completed.offered_services_prices, orders_completed.offered_services_total_amount,
              orders_completed.shipping_fee_price, orders_completed.total_price, orders_completed.added_date, orders_completed.last_modified, orders_completed.status FROM orders_completed WHERE orders_completed.user_unique_id=:user_unique_id AND orders_completed.order_unique_id=:order_unique_id AND orders_completed.tracker_unique_id=:tracker_unique_id ORDER BY orders_completed.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->bindParam(":order_unique_id", $order_unique_id);
            $query->bindParam(":tracker_unique_id", $tracker_unique_id);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              return $result;
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

      function get_single_completed_order($order_completed_unique_id, $user_unique_id, $order_unique_id, $tracker_unique_id){
        if (!in_array($order_completed_unique_id,$this->not_allowed_values) && !in_array($user_unique_id,$this->not_allowed_values)
        && !in_array($order_unique_id,$this->not_allowed_values) && !in_array($tracker_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT orders_completed.unique_id, orders_completed.user_unique_id, orders_completed.order_unique_id, orders_completed.tracker_unique_id, orders_completed.quantity, orders_completed.payment_method, orders_completed.sub_product_name, orders_completed.sub_product_size, orders_completed.rider_fullname, orders_completed.rider_phone_number,
              orders_completed.coupon_name, orders_completed.coupon_code, orders_completed.coupon_percentage, orders_completed.coupon_price, orders_completed.user_address_fullname, orders_completed.user_full_address, orders_completed.city, orders_completed.state, orders_completed.country, orders_completed.offered_services, orders_completed.offered_services_prices, orders_completed.offered_services_total_amount,
              orders_completed.shipping_fee_price, orders_completed.total_price, orders_completed.added_date, orders_completed.last_modified, orders_completed.status FROM orders_completed WHERE orders_completed.unique_id=:unique_id AND orders_completed.user_unique_id=:user_unique_id AND orders_completed.order_unique_id=:order_unique_id AND orders_completed.tracker_unique_id=:tracker_unique_id ORDER BY orders_completed.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $order_completed_unique_id);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->bindParam(":order_unique_id", $order_unique_id);
            $query->bindParam(":tracker_unique_id", $tracker_unique_id);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              return $result;
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

      function get_user_completed_orders_for_users($user_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT orders_completed.unique_id, orders_completed.user_unique_id, orders_completed.order_unique_id, orders_completed.tracker_unique_id, orders_completed.quantity, orders_completed.payment_method, orders_completed.sub_product_name, orders_completed.sub_product_size, orders_completed.rider_fullname, orders_completed.rider_phone_number,
              orders_completed.coupon_name, orders_completed.coupon_code, orders_completed.coupon_percentage, orders_completed.coupon_price, orders_completed.user_address_fullname, orders_completed.user_full_address, orders_completed.city, orders_completed.state, orders_completed.country, orders_completed.offered_services, orders_completed.offered_services_prices, orders_completed.offered_services_total_amount,
              orders_completed.shipping_fee_price, orders_completed.total_price, orders_completed.added_date FROM orders_completed WHERE orders_completed.user_unique_id=:user_unique_id ORDER BY orders_completed.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              return $result;
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

      function get_single_completed_order_details_for_users($user_unique_id, $order_unique_id, $tracker_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values) && !in_array($order_unique_id,$this->not_allowed_values) && !in_array($tracker_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT orders_completed.unique_id, orders_completed.user_unique_id, orders_completed.order_unique_id, orders_completed.tracker_unique_id, orders_completed.quantity, orders_completed.payment_method, orders_completed.sub_product_name, orders_completed.sub_product_size, orders_completed.rider_fullname, orders_completed.rider_phone_number,
              orders_completed.coupon_name, orders_completed.coupon_code, orders_completed.coupon_percentage, orders_completed.coupon_price, orders_completed.user_address_fullname, orders_completed.user_full_address, orders_completed.city, orders_completed.state, orders_completed.country, orders_completed.offered_services, orders_completed.offered_services_prices, orders_completed.offered_services_total_amount,
              orders_completed.shipping_fee_price, orders_completed.total_price, orders_completed.added_date FROM orders_completed WHERE orders_completed.user_unique_id=:user_unique_id AND orders_completed.order_unique_id=:order_unique_id AND orders_completed.tracker_unique_id=:tracker_unique_id ORDER BY orders_completed.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->bindParam(":order_unique_id", $order_unique_id);
            $query->bindParam(":tracker_unique_id", $tracker_unique_id);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              return $result;
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

  }
?>
