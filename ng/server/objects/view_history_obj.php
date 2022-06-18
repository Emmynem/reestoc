<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';

  class ViewHistory{

      // database connection and table name
      private $conn;
      private $table_name = "view_history";

      // object properties
      public $id;
      public $unique_id;
      public $user_unique_id;
      public $product_unique_id;
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

      function get_all_view_history(){

        try {
          $this->conn->beginTransaction();

          $view_history_array = array();

          $sql = "SELECT view_history.id, view_history.unique_id, view_history.user_unique_id, view_history.product_unique_id, view_history.added_date, view_history.last_modified, view_history.status, users.fullname as user_fullname, users.email as user_email, users.phone_number as user_phone_number,
          products.name, products.stripped, products.brand_unique_id, products.favorites, brands.name as brand_name, brands.stripped as brand_name_stripped
          FROM view_history INNER JOIN users ON view_history.user_unique_id = users.unique_id LEFT JOIN products ON view_history.product_unique_id = products.unique_id LEFT JOIN brands ON products.brand_unique_id = brands.unique_id ORDER BY view_history.added_date DESC";
          $query = $this->conn->prepare($sql);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_view_history = array();
              $current_view_history['id'] = $value['id'];
              $current_view_history['unique_id'] = $value['unique_id'];
              $current_view_history['user_unique_id'] = $value['user_unique_id'];
              $current_view_history['product_unique_id'] = $value['product_unique_id'];
              $current_view_history['added_date'] = $value['added_date'];
              $current_view_history['last_modified'] = $value['last_modified'];
              $current_view_history['user_fullname'] = $value['user_fullname'];
              $current_view_history['user_email'] = $value['user_email'];
              $current_view_history['user_phone_number'] = $value['user_phone_number'];
              $current_view_history['name'] = $value['name'];
              $current_view_history['stripped'] = $value['stripped'];
              $current_view_history['brand_unique_id'] = $value['brand_unique_id'];
              $current_view_history['favorites'] = $value['favorites'];
              $current_view_history['brand_name'] = $value['brand_name'];
              $current_view_history['brand_name_stripped'] = $value['brand_name_stripped'];

              $product_id = $value['product_unique_id'];

              $sql2 = "SELECT image FROM product_images WHERE product_unique_id=:product_unique_id LIMIT 1";
              $query2 = $this->conn->prepare($sql2);
              $query2->bindParam(":product_unique_id", $product_id);
              $query2->execute();

              $images_result = $query2->fetchAll();

              if ($query2->rowCount() > 0) {
                $current_view_history_images = array();

                foreach ($images_result as $key => $image_value) {
                  $current_view_history_images[] = $image_value['image'];
                }

                $current_view_history['product_images'] = $current_view_history_images;
              }
              else{
                $current_view_history['product_images'] = null;
              }

              $view_history_array[] = $current_view_history;
            }
            return $view_history_array;
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

      function get_user_view_history($user_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $view_history_array = array();

            $sql = "SELECT view_history.id, view_history.unique_id, view_history.user_unique_id, view_history.product_unique_id, view_history.added_date, view_history.last_modified, view_history.status, users.fullname as user_fullname, users.email as user_email, users.phone_number as user_phone_number,
            products.name, products.stripped, products.brand_unique_id, products.favorites, brands.name as brand_name, brands.stripped as brand_name_stripped
            FROM view_history INNER JOIN users ON view_history.user_unique_id = users.unique_id LEFT JOIN products ON view_history.product_unique_id = products.unique_id LEFT JOIN brands ON products.brand_unique_id = brands.unique_id WHERE view_history.user_unique_id=:user_unique_id GROUP BY view_history.product_unique_id ORDER BY view_history.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_view_history = array();
                $current_view_history['id'] = $value['id'];
                $current_view_history['unique_id'] = $value['unique_id'];
                $current_view_history['user_unique_id'] = $value['user_unique_id'];
                $current_view_history['product_unique_id'] = $value['product_unique_id'];
                $current_view_history['added_date'] = $value['added_date'];
                $current_view_history['last_modified'] = $value['last_modified'];
                $current_view_history['user_fullname'] = $value['user_fullname'];
                $current_view_history['user_email'] = $value['user_email'];
                $current_view_history['user_phone_number'] = $value['user_phone_number'];
                $current_view_history['name'] = $value['name'];
                $current_view_history['stripped'] = $value['stripped'];
                $current_view_history['brand_unique_id'] = $value['brand_unique_id'];
                $current_view_history['favorites'] = $value['favorites'];
                $current_view_history['brand_name'] = $value['brand_name'];
                $current_view_history['brand_name_stripped'] = $value['brand_name_stripped'];

                $product_id = $value['product_unique_id'];

                $sql2 = "SELECT image FROM product_images WHERE product_unique_id=:product_unique_id LIMIT 1";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":product_unique_id", $product_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_view_history_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_view_history_images[] = $image_value['image'];
                  }

                  $current_view_history['product_images'] = $current_view_history_images;
                }
                else{
                  $current_view_history['product_images'] = null;
                }

                $view_history_array[] = $current_view_history;
              }
              return $view_history_array;
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

      function get_product_view_history($product_unique_id){
        if (!in_array($product_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $view_history_array = array();

            $sql = "SELECT view_history.id, view_history.unique_id, view_history.user_unique_id, view_history.product_unique_id, view_history.added_date, view_history.last_modified, view_history.status, users.fullname as user_fullname, users.email as user_email, users.phone_number as user_phone_number,
            products.name, products.stripped, products.brand_unique_id, products.favorites, brands.name as brand_name, brands.stripped as brand_name_stripped
            FROM view_history INNER JOIN users ON view_history.user_unique_id = users.unique_id LEFT JOIN products ON view_history.product_unique_id = products.unique_id LEFT JOIN brands ON products.brand_unique_id = brands.unique_id WHERE view_history.product_unique_id=:product_unique_id ORDER BY view_history.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":product_unique_id", $product_unique_id);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_view_history = array();
                $current_view_history['id'] = $value['id'];
                $current_view_history['unique_id'] = $value['unique_id'];
                $current_view_history['user_unique_id'] = $value['user_unique_id'];
                $current_view_history['product_unique_id'] = $value['product_unique_id'];
                $current_view_history['added_date'] = $value['added_date'];
                $current_view_history['last_modified'] = $value['last_modified'];
                $current_view_history['user_fullname'] = $value['user_fullname'];
                $current_view_history['user_email'] = $value['user_email'];
                $current_view_history['user_phone_number'] = $value['user_phone_number'];
                $current_view_history['name'] = $value['name'];
                $current_view_history['stripped'] = $value['stripped'];
                $current_view_history['brand_unique_id'] = $value['brand_unique_id'];
                $current_view_history['favorites'] = $value['favorites'];
                $current_view_history['brand_name'] = $value['brand_name'];
                $current_view_history['brand_name_stripped'] = $value['brand_name_stripped'];

                $product_id = $value['product_unique_id'];

                $sql2 = "SELECT image FROM product_images WHERE product_unique_id=:product_unique_id LIMIT 1";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":product_unique_id", $product_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_view_history_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_view_history_images[] = $image_value['image'];
                  }

                  $current_view_history['product_images'] = $current_view_history_images;
                }
                else{
                  $current_view_history['product_images'] = null;
                }

                $view_history_array[] = $current_view_history;
              }
              return $view_history_array;
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
