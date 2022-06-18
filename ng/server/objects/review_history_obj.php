<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';

  class ReviewHistory{

      // database connection and table name
      private $conn;
      private $table_name = "review_history";

      // object properties
      public $id;
      public $unique_id;
      public $user_unique_id;
      public $sub_product_unique_id;
      public $rating;
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

      function get_all_review_history(){

        try {
          $this->conn->beginTransaction();

          $review_history_array = array();

          $sql = "SELECT review_history.id, review_history.unique_id, review_history.user_unique_id, review_history.sub_product_unique_id, review_history.rating, review_history.added_date, review_history.last_modified, review_history.status, users.fullname as user_fullname, users.email as user_email, users.phone_number as user_phone_number,
          sub_products.name, sub_products.stripped, sub_products.favorites FROM review_history INNER JOIN users ON review_history.user_unique_id = users.unique_id LEFT JOIN sub_products ON review_history.sub_product_unique_id = sub_products.unique_id ORDER BY review_history.added_date DESC";
          $query = $this->conn->prepare($sql);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_review_history = array();
              $current_review_history['id'] = $value['id'];
              $current_review_history['unique_id'] = $value['unique_id'];
              $current_review_history['user_unique_id'] = $value['user_unique_id'];
              $current_review_history['sub_product_unique_id'] = $value['sub_product_unique_id'];
              $current_review_history['rating'] = $value['rating'];
              $current_review_history['added_date'] = $value['added_date'];
              $current_review_history['last_modified'] = $value['last_modified'];
              $current_review_history['user_fullname'] = $value['user_fullname'];
              $current_review_history['user_email'] = $value['user_email'];
              $current_review_history['user_phone_number'] = $value['user_phone_number'];
              $current_review_history['name'] = $value['name'];
              $current_review_history['stripped'] = $value['stripped'];
              $current_review_history['favorites'] = $value['favorites'];

              $sub_product_id = $value['sub_product_unique_id'];

              $sql2 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id LIMIT 1";
              $query2 = $this->conn->prepare($sql2);
              $query2->bindParam(":sub_product_unique_id", $sub_product_id);
              $query2->execute();

              $images_result = $query2->fetchAll();

              if ($query2->rowCount() > 0) {
                $current_review_history_images = array();

                foreach ($images_result as $key => $image_value) {
                  $current_review_history_images[] = $image_value['image'];
                }

                $current_review_history['sub_product_images'] = $current_review_history_images;
              }
              else{
                $current_review_history['sub_product_images'] = null;
              }

              $review_history_array[] = $current_review_history;
            }
            return $review_history_array;
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

      function get_user_review_history($user_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $review_history_array = array();

            $sql = "SELECT review_history.id, review_history.unique_id, review_history.user_unique_id, review_history.sub_product_unique_id, review_history.rating, review_history.added_date, review_history.last_modified, review_history.status, users.fullname as user_fullname, users.email as user_email, users.phone_number as user_phone_number,
            sub_products.name, sub_products.stripped, sub_products.favorites FROM review_history INNER JOIN users ON review_history.user_unique_id = users.unique_id LEFT JOIN sub_products ON review_history.sub_product_unique_id = sub_products.unique_id WHERE review_history.user_unique_id=:user_unique_id ORDER BY review_history.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_review_history = array();
                $current_review_history['id'] = $value['id'];
                $current_review_history['unique_id'] = $value['unique_id'];
                $current_review_history['user_unique_id'] = $value['user_unique_id'];
                $current_review_history['sub_product_unique_id'] = $value['sub_product_unique_id'];
                $current_review_history['rating'] = $value['rating'];
                $current_review_history['added_date'] = $value['added_date'];
                $current_review_history['last_modified'] = $value['last_modified'];
                $current_review_history['user_fullname'] = $value['user_fullname'];
                $current_review_history['user_email'] = $value['user_email'];
                $current_review_history['user_phone_number'] = $value['user_phone_number'];
                $current_review_history['name'] = $value['name'];
                $current_review_history['stripped'] = $value['stripped'];
                $current_review_history['favorites'] = $value['favorites'];

                $sub_product_id = $value['sub_product_unique_id'];

                $sql2 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id LIMIT 1";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":sub_product_unique_id", $sub_product_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_review_history_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_review_history_images[] = $image_value['image'];
                  }

                  $current_review_history['sub_product_images'] = $current_review_history_images;
                }
                else{
                  $current_review_history['sub_product_images'] = null;
                }

                $review_history_array[] = $current_review_history;
              }
              return $review_history_array;
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

      function get_product_review_history($sub_product_unique_id){
        if (!in_array($sub_product_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $review_history_array = array();

            $sql = "SELECT review_history.id, review_history.unique_id, review_history.user_unique_id, review_history.sub_product_unique_id, review_history.rating, review_history.added_date, review_history.last_modified, review_history.status, users.fullname as user_fullname, users.email as user_email, users.phone_number as user_phone_number,
            sub_products.name, sub_products.stripped, sub_products.favorites FROM review_history INNER JOIN users ON review_history.user_unique_id = users.unique_id LEFT JOIN sub_products ON review_history.sub_product_unique_id = sub_products.unique_id WHERE review_history.sub_product_unique_id=:sub_product_unique_id ORDER BY review_history.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":sub_product_unique_id", $sub_product_unique_id);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_review_history = array();
                $current_review_history['id'] = $value['id'];
                $current_review_history['unique_id'] = $value['unique_id'];
                $current_review_history['user_unique_id'] = $value['user_unique_id'];
                $current_review_history['sub_product_unique_id'] = $value['sub_product_unique_id'];
                $current_review_history['rating'] = $value['rating'];
                $current_review_history['added_date'] = $value['added_date'];
                $current_review_history['last_modified'] = $value['last_modified'];
                $current_review_history['user_fullname'] = $value['user_fullname'];
                $current_review_history['user_email'] = $value['user_email'];
                $current_review_history['user_phone_number'] = $value['user_phone_number'];
                $current_review_history['name'] = $value['name'];
                $current_review_history['stripped'] = $value['stripped'];
                $current_review_history['favorites'] = $value['favorites'];

                $sub_product_id = $value['sub_product_unique_id'];

                $sql2 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id LIMIT 1";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":sub_product_unique_id", $sub_product_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_review_history_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_review_history_images[] = $image_value['image'];
                  }

                  $current_review_history['sub_product_images'] = $current_review_history_images;
                }
                else{
                  $current_review_history['sub_product_images'] = null;
                }

                $review_history_array[] = $current_review_history;
              }
              return $review_history_array;
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
