<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';

  class Favorites{

      // database connection and table name
      private $conn;
      private $table_name = "favorites";

      // object properties
      public $id;
      public $unique_id;
      public $user_unique_id;
      public $product_unique_id;
      public $added_date;
      public $last_modified;
      public $status;

      private $functions;
      private $products;
      private $not_allowed_values;

      public $output = array('error' => false, 'success' => false);

      // constructor with $db as database connection
      public function __construct($db){
          $this->conn = $db;
          $this->functions = new Functions();
          $this->not_allowed_values = $this->functions->not_allowed_values;
      }

      public function get_all_favorites(){

        try {
          $this->conn->beginTransaction();

          $favorite_array = array();

          $sql = "SELECT favorites.id, favorites.unique_id, favorites.user_unique_id, favorites.product_unique_id, favorites.added_date, favorites.last_modified, favorites.status, users.fullname as user_fullname, users.email as user_email, users.phone_number as user_phone_number, products.mini_category_unique_id, products.sub_category_unique_id, products.category_unique_id,
          products.name as product_name, products.stripped as product_stripped, products.brand_unique_id, products.favorites, products.status as product_status, brands.name as brand_name, brands.stripped as brand_stripped FROM favorites
          INNER JOIN users ON favorites.user_unique_id = users.unique_id INNER JOIN products ON favorites.product_unique_id = products.unique_id LEFT JOIN brands ON products.brand_unique_id = brands.unique_id ORDER BY favorites.added_date DESC";
          $query = $this->conn->prepare($sql);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_product = array();
              $current_product['id'] = $value['id'];
              $current_product['unique_id'] = $value['unique_id'];
              $current_product['user_unique_id'] = $value['user_unique_id'];
              $current_product['product_unique_id'] = $value['product_unique_id'];
              $current_product['mini_category_unique_id'] = $value['mini_category_unique_id'];
              $current_product['sub_category_unique_id'] = $value['sub_category_unique_id'];
              $current_product['category_unique_id'] = $value['category_unique_id'];
              $current_product['product_name'] = $value['product_name'];
              $current_product['product_stripped'] = $value['product_stripped'];
              $current_product['brand_unique_id'] = $value['brand_unique_id'];
              $current_product['favorites'] = $value['favorites'];
              $current_product['added_date'] = $value['added_date'];
              $current_product['last_modified'] = $value['last_modified'];
              $current_product['status'] = $value['status'];
              $current_product['product_status'] = $value['product_status'];
              $current_product['user_fullname'] = $value['user_fullname'];
              $current_product['user_email'] = $value['user_email'];
              $current_product['user_phone_number'] = $value['user_phone_number'];
              $current_product['brand_name'] = $value['brand_name'];
              $current_product['brand_stripped'] = $value['brand_stripped'];

              $product_id = $value['product_unique_id'];

              $sql2 = "SELECT image FROM product_images WHERE product_unique_id=:product_unique_id";
              $query2 = $this->conn->prepare($sql2);
              $query2->bindParam(":product_unique_id", $product_id);
              $query2->execute();

              $images_result = $query2->fetchAll();

              if ($query2->rowCount() > 0) {
                $current_product_images = array();

                foreach ($images_result as $key => $image_value) {
                  $current_product_images[] = $image_value['image'];
                }

                $current_product['product_images'] = $current_product_images;
              }
              else{
                $current_product['product_images'] = null;
              }

              $favorite_array[] = $current_product;
            }
            return $favorite_array;
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

      public function get_user_favorites($user_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $favorite_array = array();

            $active = $this->functions->active;

            $sql = "SELECT favorites.unique_id, favorites.user_unique_id, favorites.product_unique_id, users.fullname as user_fullname, users.email as user_email, users.phone_number as user_phone_number, products.mini_category_unique_id, products.sub_category_unique_id, products.category_unique_id,
            products.name as product_name, products.stripped as product_stripped, products.brand_unique_id, products.favorites, brands.name as brand_name, brands.stripped as brand_stripped FROM favorites
            INNER JOIN users ON favorites.user_unique_id = users.unique_id INNER JOIN products ON favorites.product_unique_id = products.unique_id LEFT JOIN brands ON products.brand_unique_id = brands.unique_id WHERE favorites.user_unique_id=:user_unique_id AND favorites.status=:status ORDER BY favorites.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->bindParam(":status", $active);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_product = array();
                $current_product['unique_id'] = $value['unique_id'];
                $current_product['user_unique_id'] = $value['user_unique_id'];
                $current_product['product_unique_id'] = $value['product_unique_id'];
                $current_product['mini_category_unique_id'] = $value['mini_category_unique_id'];
                $current_product['sub_category_unique_id'] = $value['sub_category_unique_id'];
                $current_product['category_unique_id'] = $value['category_unique_id'];
                $current_product['product_name'] = $value['product_name'];
                $current_product['product_stripped'] = $value['product_stripped'];
                $current_product['brand_unique_id'] = $value['brand_unique_id'];
                $current_product['favorites'] = $value['favorites'];
                $current_product['user_fullname'] = $value['user_fullname'];
                $current_product['user_email'] = $value['user_email'];
                $current_product['user_phone_number'] = $value['user_phone_number'];
                $current_product['brand_name'] = $value['brand_name'];
                $current_product['brand_stripped'] = $value['brand_stripped'];

                $product_id = $value['product_unique_id'];

                $sql2 = "SELECT image FROM product_images WHERE product_unique_id=:product_unique_id";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":product_unique_id", $product_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_product_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_product_images[] = $image_value['image'];
                  }

                  $current_product['product_images'] = $current_product_images;
                }
                else{
                  $current_product['product_images'] = null;
                }

                $favorite_array[] = $current_product;
              }
              return $favorite_array;
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

      public function get_user_favorites_limit($user_unique_id, $limit){
        if (!in_array($user_unique_id,$this->not_allowed_values) && !in_array($limit,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $favorite_array = array();

            $active = $this->functions->active;

            $sql = "SELECT favorites.unique_id, favorites.user_unique_id, favorites.product_unique_id, users.fullname as user_fullname, users.email as user_email, users.phone_number as user_phone_number, products.mini_category_unique_id, products.sub_category_unique_id, products.category_unique_id,
            products.name as product_name, products.stripped as product_stripped, products.brand_unique_id, products.favorites, brands.name as brand_name, brands.stripped as brand_stripped FROM favorites
            INNER JOIN users ON favorites.user_unique_id = users.unique_id INNER JOIN products ON favorites.product_unique_id = products.unique_id LEFT JOIN brands ON products.brand_unique_id = brands.unique_id WHERE favorites.user_unique_id=:user_unique_id AND favorites.status=:status ORDER BY favorites.added_date DESC LIMIT ".$limit;
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->bindParam(":status", $active);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_product = array();
                $current_product['unique_id'] = $value['unique_id'];
                $current_product['user_unique_id'] = $value['user_unique_id'];
                $current_product['product_unique_id'] = $value['product_unique_id'];
                $current_product['mini_category_unique_id'] = $value['mini_category_unique_id'];
                $current_product['sub_category_unique_id'] = $value['sub_category_unique_id'];
                $current_product['category_unique_id'] = $value['category_unique_id'];
                $current_product['product_name'] = $value['product_name'];
                $current_product['product_stripped'] = $value['product_stripped'];
                $current_product['brand_unique_id'] = $value['brand_unique_id'];
                $current_product['favorites'] = $value['favorites'];
                $current_product['user_fullname'] = $value['user_fullname'];
                $current_product['user_email'] = $value['user_email'];
                $current_product['user_phone_number'] = $value['user_phone_number'];
                $current_product['brand_name'] = $value['brand_name'];
                $current_product['brand_stripped'] = $value['brand_stripped'];

                $product_id = $value['product_unique_id'];

                $sql2 = "SELECT image FROM product_images WHERE product_unique_id=:product_unique_id";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":product_unique_id", $product_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_product_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_product_images[] = $image_value['image'];
                  }

                  $current_product['product_images'] = $current_product_images;
                }
                else{
                  $current_product['product_images'] = null;
                }

                $favorite_array[] = $current_product;
              }
              return $favorite_array;
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
