<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';

  class Reviews{

      // database connection and table name
      private $conn;
      private $table_name = "reviews";

      // object properties
      public $id;
      public $unique_id;
      public $user_unique_id;
      public $sub_product_unique_id;
      public $message;
      public $yes_rating;
      public $no_rating;
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

      function get_all_reviews(){

        try {
          $this->conn->beginTransaction();

          $review_array = array();

          $sql = "SELECT reviews.id, reviews.unique_id, reviews.user_unique_id, reviews.sub_product_unique_id, reviews.message, reviews.added_date, reviews.last_modified, reviews.status, review_ratings.yes_rating as total_yes_rating, review_ratings.no_rating as total_no_rating, users.fullname as user_fullname, users.email as user_email, users.phone_number as user_phone_number,
          sub_products.name as sub_product_name FROM reviews INNER JOIN users ON reviews.user_unique_id = users.unique_id LEFT JOIN sub_products ON reviews.sub_product_unique_id = sub_products.unique_id LEFT JOIN review_ratings ON reviews.unique_id = review_ratings.unique_id ORDER BY reviews.added_date DESC";
          $query = $this->conn->prepare($sql);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_review = array();
              $current_review['id'] = $value['id'];
              $current_review['unique_id'] = $value['unique_id'];
              $current_review['user_unique_id'] = $value['user_unique_id'];
              $current_review['sub_product_unique_id'] = $value['sub_product_unique_id'];
              $current_review['message'] = $value['message'];
              $current_review['added_date'] = $value['added_date'];
              $current_review['last_modified'] = $value['last_modified'];
              $current_review['status'] = $value['status'];
              $current_review['total_yes_rating'] = $value['total_yes_rating'];
              $current_review['total_no_rating'] = $value['total_no_rating'];
              $current_review['user_fullname'] = $value['user_fullname'];
              $current_review['user_email'] = $value['user_email'];
              $current_review['user_phone_number'] = $value['user_phone_number'];
              $current_review['sub_product_name'] = $value['sub_product_name'];

              $review_id = $value['unique_id'];

              $sql2 = "SELECT image FROM review_images WHERE review_unique_id=:review_unique_id";
              $query2 = $this->conn->prepare($sql2);
              $query2->bindParam(":review_unique_id", $review_id);
              $query2->execute();

              $images_result = $query2->fetchAll();

              if ($query2->rowCount() > 0) {
                $current_review_images = array();

                foreach ($images_result as $key => $image_value) {
                  $current_review_images[] = $image_value['image'];
                }

                $current_review['review_images'] = $current_review_images;
              }
              else{
                $current_review['review_images'] = null;
              }

              $review_array[] = $current_review;
            }
            return $review_array;
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

      function get_all_user_reviews($user_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $review_array = array();

            $sql = "SELECT reviews.id, reviews.unique_id, reviews.user_unique_id, reviews.sub_product_unique_id, reviews.message, reviews.added_date, reviews.last_modified, reviews.status, review_ratings.yes_rating as total_yes_rating, review_ratings.no_rating as total_no_rating, users.fullname as user_fullname, users.email as user_email, users.phone_number as user_phone_number,
            sub_products.name as sub_product_name FROM reviews INNER JOIN users ON reviews.user_unique_id = users.unique_id LEFT JOIN sub_products ON reviews.sub_product_unique_id = sub_products.unique_id LEFT JOIN review_ratings ON reviews.unique_id = review_ratings.unique_id WHERE reviews.user_unique_id=:user_unique_id ORDER BY reviews.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_review = array();
                $current_review['id'] = $value['id'];
                $current_review['unique_id'] = $value['unique_id'];
                $current_review['user_unique_id'] = $value['user_unique_id'];
                $current_review['sub_product_unique_id'] = $value['sub_product_unique_id'];
                $current_review['message'] = $value['message'];
                $current_review['added_date'] = $value['added_date'];
                $current_review['last_modified'] = $value['last_modified'];
                $current_review['status'] = $value['status'];
                $current_review['total_yes_rating'] = $value['total_yes_rating'];
                $current_review['total_no_rating'] = $value['total_no_rating'];
                $current_review['user_fullname'] = $value['user_fullname'];
                $current_review['user_email'] = $value['user_email'];
                $current_review['user_phone_number'] = $value['user_phone_number'];
                $current_review['sub_product_name'] = $value['sub_product_name'];

                $review_id = $value['unique_id'];

                $sql2 = "SELECT image FROM review_images WHERE review_unique_id=:review_unique_id";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":review_unique_id", $review_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_review_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_review_images[] = $image_value['image'];
                  }

                  $current_review['review_images'] = $current_review_images;
                }
                else{
                  $current_review['review_images'] = null;
                }

                $review_array[] = $current_review;
              }
              return $review_array;
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

      function get_user_sub_product_reviews($user_unique_id, $sub_product_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values) && !in_array($sub_product_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $review_array = array();

            $sql = "SELECT reviews.id, reviews.unique_id, reviews.user_unique_id, reviews.sub_product_unique_id, reviews.message, reviews.added_date, reviews.last_modified, reviews.status, review_ratings.yes_rating as total_yes_rating, review_ratings.no_rating as total_no_rating, users.fullname as user_fullname, users.email as user_email, users.phone_number as user_phone_number,
            sub_products.name as sub_product_name FROM reviews INNER JOIN users ON reviews.user_unique_id = users.unique_id LEFT JOIN sub_products ON reviews.sub_product_unique_id = sub_products.unique_id LEFT JOIN review_ratings ON reviews.unique_id = review_ratings.unique_id WHERE reviews.user_unique_id=:user_unique_id AND reviews.sub_product_unique_id=:sub_product_unique_id ORDER BY reviews.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->bindParam(":sub_product_unique_id", $sub_product_unique_id);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_review = array();
                $current_review['id'] = $value['id'];
                $current_review['unique_id'] = $value['unique_id'];
                $current_review['user_unique_id'] = $value['user_unique_id'];
                $current_review['sub_product_unique_id'] = $value['sub_product_unique_id'];
                $current_review['message'] = $value['message'];
                $current_review['added_date'] = $value['added_date'];
                $current_review['last_modified'] = $value['last_modified'];
                $current_review['status'] = $value['status'];
                $current_review['total_yes_rating'] = $value['total_yes_rating'];
                $current_review['total_no_rating'] = $value['total_no_rating'];
                $current_review['user_fullname'] = $value['user_fullname'];
                $current_review['user_email'] = $value['user_email'];
                $current_review['user_phone_number'] = $value['user_phone_number'];
                $current_review['sub_product_name'] = $value['sub_product_name'];

                $review_id = $value['unique_id'];

                $sql2 = "SELECT image FROM review_images WHERE review_unique_id=:review_unique_id";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":review_unique_id", $review_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_review_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_review_images[] = $image_value['image'];
                  }

                  $current_review['review_images'] = $current_review_images;
                }
                else{
                  $current_review['review_images'] = null;
                }

                $review_array[] = $current_review;
              }
              return $review_array;
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

      function get_all_sub_product_reviews($sub_product_unique_id){
        if (!in_array($sub_product_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $review_array = array();

            $sql = "SELECT reviews.id, reviews.unique_id, reviews.user_unique_id, reviews.sub_product_unique_id, reviews.message, reviews.added_date, reviews.last_modified, reviews.status, review_ratings.yes_rating as total_yes_rating, review_ratings.no_rating as total_no_rating, users.fullname as user_fullname, users.email as user_email, users.phone_number as user_phone_number,
            sub_products.name as sub_product_name FROM reviews INNER JOIN users ON reviews.user_unique_id = users.unique_id LEFT JOIN sub_products ON reviews.sub_product_unique_id = sub_products.unique_id LEFT JOIN review_ratings ON reviews.unique_id = review_ratings.unique_id WHERE reviews.sub_product_unique_id=:sub_product_unique_id ORDER BY reviews.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":sub_product_unique_id", $sub_product_unique_id);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_review = array();
                $current_review['id'] = $value['id'];
                $current_review['unique_id'] = $value['unique_id'];
                $current_review['user_unique_id'] = $value['user_unique_id'];
                $current_review['sub_product_unique_id'] = $value['sub_product_unique_id'];
                $current_review['message'] = $value['message'];
                $current_review['added_date'] = $value['added_date'];
                $current_review['last_modified'] = $value['last_modified'];
                $current_review['status'] = $value['status'];
                $current_review['total_yes_rating'] = $value['total_yes_rating'];
                $current_review['total_no_rating'] = $value['total_no_rating'];
                $current_review['user_fullname'] = $value['user_fullname'];
                $current_review['user_email'] = $value['user_email'];
                $current_review['user_phone_number'] = $value['user_phone_number'];
                $current_review['sub_product_name'] = $value['sub_product_name'];

                $review_id = $value['unique_id'];

                $sql2 = "SELECT image FROM review_images WHERE review_unique_id=:review_unique_id";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":review_unique_id", $review_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_review_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_review_images[] = $image_value['image'];
                  }

                  $current_review['review_images'] = $current_review_images;
                }
                else{
                  $current_review['review_images'] = null;
                }

                $review_array[] = $current_review;
              }
              return $review_array;
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
