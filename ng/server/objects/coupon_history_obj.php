<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';

  class CouponHistory{

      // database connection and table name
      private $conn;
      private $table_name = "coupon_history";

      // object properties
      public $id;
      public $unique_id;
      public $user_unique_id;
      public $product_unique_id;
      public $name;
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

      function get_all_coupon_history(){

        try {
          $this->conn->beginTransaction();

          $coupon_history_array = array();

          $sql = "SELECT coupon_history.id, coupon_history.unique_id, coupon_history.user_unique_id, coupon_history.product_unique_id, coupon_history.name, coupon_history.price, coupon_history.completion, coupon_history.added_date, coupon_history.last_modified, coupon_history.status,
          products.name as product_name, products.size, products.stripped, products.brand_unique_id, products.price as product_price, products.sales_price, products.favorites, brands.name as brand_name, brands.stripped as brand_name_stripped,
          users.fullname as user_fullname, users.email as user_email FROM coupon_history LEFT JOIN users ON coupon_history.user_unique_id = users.unique_id LEFT JOIN products ON coupon_history.product_unique_id = products.unique_id LEFT JOIN brands ON products.brand_unique_id = brands.unique_id ORDER BY coupon_history.added_date DESC";
          $query = $this->conn->prepare($sql);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_coupon_history = array();
              $current_coupon_history['id'] = $value['id'];
              $current_coupon_history['unique_id'] = $value['unique_id'];
              $current_coupon_history['user_unique_id'] = $value['user_unique_id'];
              $current_coupon_history['product_unique_id'] = $value['product_unique_id'];
              $current_coupon_history['name'] = $value['name'];
              $current_coupon_history['price'] = $value['price'];
              $current_coupon_history['completion'] = $value['completion'];
              $current_coupon_history['added_date'] = $value['added_date'];
              $current_coupon_history['last_modified'] = $value['last_modified'];
              $current_coupon_history['status'] = $value['status'];
              $current_coupon_history['user_fullname'] = $value['user_fullname'];
              $current_coupon_history['user_email'] = $value['user_email'];
              $current_coupon_history['product_name'] = $value['product_name'];
              $current_coupon_history['size'] = $value['size'];
              $current_coupon_history['stripped'] = $value['stripped'];
              $current_coupon_history['brand_unique_id'] = $value['brand_unique_id'];
              $current_coupon_history['product_price'] = $value['product_price'];
              $current_coupon_history['product_sales_price'] = $value['sales_price'];
              $current_coupon_history['favorites'] = $value['favorites'];
              $current_coupon_history['brand_name'] = $value['brand_name'];
              $current_coupon_history['brand_name_stripped'] = $value['brand_name_stripped'];

              $product_id = $value['product_unique_id'];

              $sql2 = "SELECT image FROM product_images WHERE product_unique_id=:product_unique_id LIMIT 1";
              $query2 = $this->conn->prepare($sql2);
              $query2->bindParam(":product_unique_id", $product_id);
              $query2->execute();

              $images_result = $query2->fetchAll();

              if ($query2->rowCount() > 0) {
                $current_coupon_history_images = array();

                foreach ($images_result as $key => $image_value) {
                  $current_coupon_history_images[] = $image_value['image'];
                }

                $current_coupon_history['product_images'] = $current_coupon_history_images;
              }
              else{
                $current_coupon_history['product_images'] = null;
              }

              $coupon_history_array[] = $current_coupon_history;
            }
            return $coupon_history_array;
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

      function get_user_coupon_history($user_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $coupon_history_array = array();

            $sql = "SELECT coupon_history.id, coupon_history.unique_id, coupon_history.user_unique_id, coupon_history.product_unique_id, coupon_history.name, coupon_history.price, coupon_history.completion, coupon_history.added_date, coupon_history.last_modified, coupon_history.status,
            products.name as product_name, products.size, products.stripped, products.brand_unique_id, products.price as product_price, products.sales_price, products.favorites, brands.name as brand_name, brands.stripped as brand_name_stripped,
            users.fullname as user_fullname, users.email as user_email FROM coupon_history LEFT JOIN users ON coupon_history.user_unique_id = users.unique_id LEFT JOIN products ON coupon_history.product_unique_id = products.unique_id LEFT JOIN brands ON products.brand_unique_id = brands.unique_id
            WHERE coupon_history.user_unique_id=:user_unique_id ORDER BY coupon_history.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_coupon_history = array();
                $current_coupon_history['id'] = $value['id'];
                $current_coupon_history['unique_id'] = $value['unique_id'];
                $current_coupon_history['user_unique_id'] = $value['user_unique_id'];
                $current_coupon_history['product_unique_id'] = $value['product_unique_id'];
                $current_coupon_history['name'] = $value['name'];
                $current_coupon_history['price'] = $value['price'];
                $current_coupon_history['completion'] = $value['completion'];
                $current_coupon_history['added_date'] = $value['added_date'];
                $current_coupon_history['last_modified'] = $value['last_modified'];
                $current_coupon_history['status'] = $value['status'];
                $current_coupon_history['user_fullname'] = $value['user_fullname'];
                $current_coupon_history['user_email'] = $value['user_email'];
                $current_coupon_history['product_name'] = $value['product_name'];
                $current_coupon_history['size'] = $value['size'];
                $current_coupon_history['stripped'] = $value['stripped'];
                $current_coupon_history['brand_unique_id'] = $value['brand_unique_id'];
                $current_coupon_history['product_price'] = $value['product_price'];
                $current_coupon_history['product_sales_price'] = $value['sales_price'];
                $current_coupon_history['favorites'] = $value['favorites'];
                $current_coupon_history['brand_name'] = $value['brand_name'];
                $current_coupon_history['brand_name_stripped'] = $value['brand_name_stripped'];

                $product_id = $value['product_unique_id'];

                $sql2 = "SELECT image FROM product_images WHERE product_unique_id=:product_unique_id LIMIT 1";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":product_unique_id", $product_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_coupon_history_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_coupon_history_images[] = $image_value['image'];
                  }

                  $current_coupon_history['product_images'] = $current_coupon_history_images;
                }
                else{
                  $current_coupon_history['product_images'] = null;
                }

                $coupon_history_array[] = $current_coupon_history;
              }
              return $coupon_history_array;
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
