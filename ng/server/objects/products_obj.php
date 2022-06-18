<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';

  class Products{

      // database connection and table name
      private $conn;
      private $table_name = "products";

      // object properties
      public $id;
      public $unique_id;
      public $user_unique_id;
      public $edit_user_unique_id;
      public $mini_category_unique_id;
      public $sub_category_unique_id;
      public $category_unique_id;
      public $name;
      public $stripped;
      public $brand;
      public $description;
      public $stock;
      public $stock_remaining;
      public $price;
      public $sales_price;
      public $favorites;
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

      function get_all_products(){

        try {
          $this->conn->beginTransaction();

          $product_array = array();

          $sql = "SELECT products.id, products.unique_id, products.user_unique_id, products.edit_user_unique_id, products.mini_category_unique_id, products.sub_category_unique_id, products.category_unique_id, products.name, products.stripped, products.brand_unique_id,
          products.description, products.favorites, products.added_date, products.last_modified, products.status,
          management.fullname as added_fullname, management_alt.fullname as edit_user_fullname, categories.name as category_name, categories.stripped as category_name_stripped, sub_category.name as sub_category_name, sub_category.stripped as sub_category_name_stripped, mini_category.name as mini_category_name,
          mini_category.stripped as mini_category_name_stripped, brands.name as brand_name, brands.stripped as brand_name_stripped FROM products INNER JOIN management ON products.user_unique_id = management.unique_id INNER JOIN management management_alt ON products.edit_user_unique_id = management_alt.unique_id
          LEFT JOIN categories ON products.category_unique_id = categories.unique_id LEFT JOIN sub_category ON products.sub_category_unique_id = sub_category.unique_id LEFT JOIN mini_category ON products.mini_category_unique_id = mini_category.unique_id LEFT JOIN brands ON products.brand_unique_id = brands.unique_id ORDER BY products.added_date DESC";
          $query = $this->conn->prepare($sql);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_product = array();
              $current_product['id'] = $value['id'];
              $current_product['unique_id'] = $value['unique_id'];
              $current_product['user_unique_id'] = $value['user_unique_id'];
              $current_product['edit_user_unique_id'] = $value['edit_user_unique_id'];
              $current_product['mini_category_unique_id'] = $value['mini_category_unique_id'];
              $current_product['sub_category_unique_id'] = $value['sub_category_unique_id'];
              $current_product['category_unique_id'] = $value['category_unique_id'];
              $current_product['name'] = $value['name'];
              $current_product['stripped'] = $value['stripped'];
              $current_product['brand_unique_id'] = $value['brand_unique_id'];
              $current_product['favorites'] = $value['favorites'];
              $current_product['added_date'] = $value['added_date'];
              $current_product['last_modified'] = $value['last_modified'];
              $current_product['status'] = $value['status'];
              $current_product['added_fullname'] = $value['added_fullname'];
              $current_product['edit_user_fullname'] = $value['edit_user_fullname'];
              $current_product['category_name'] = $value['category_name'];
              $current_product['category_name_stripped'] = $value['category_name_stripped'];
              $current_product['sub_category_name'] = $value['sub_category_name'];
              $current_product['sub_category_name_stripped'] = $value['sub_category_name_stripped'];
              $current_product['mini_category_name'] = $value['mini_category_name'];
              $current_product['mini_category_name_stripped'] = $value['mini_category_name_stripped'];
              $current_product['brand_name'] = $value['brand_name'];
              $current_product['brand_name_stripped'] = $value['brand_name_stripped'];

              $product_id = $value['unique_id'];

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

              $sql3 = "SELECT sub_products.id, sub_products.unique_id, sub_products.user_unique_id, sub_products.edit_user_unique_id, sub_products.product_unique_id, sub_products.name, sub_products.size, sub_products.stripped, sub_products.description, sub_products.stock, sub_products.stock_remaining,
              sub_products.price, sub_products.sales_price, sub_products.favorites, products.mini_category_unique_id, products.sub_category_unique_id, products.category_unique_id, products.name as product_name, products.stripped as product_stripped, products.brand_unique_id, sub_products.added_date, sub_products.last_modified, sub_products.status,
              management.fullname as added_fullname, management_alt.fullname as edit_user_fullname, categories.name as category_name, categories.stripped as category_name_stripped, sub_category.name as sub_category_name, sub_category.stripped as sub_category_name_stripped, mini_category.name as mini_category_name,
              mini_category.stripped as mini_category_name_stripped, brands.name as brand_name, brands.stripped as brand_name_stripped FROM sub_products INNER JOIN management ON sub_products.user_unique_id = management.unique_id INNER JOIN management management_alt ON sub_products.edit_user_unique_id = management_alt.unique_id
              LEFT JOIN products ON sub_products.product_unique_id = products.unique_id LEFT JOIN categories ON products.category_unique_id = categories.unique_id LEFT JOIN sub_category ON products.sub_category_unique_id = sub_category.unique_id LEFT JOIN mini_category ON products.mini_category_unique_id = mini_category.unique_id LEFT JOIN brands ON products.brand_unique_id = brands.unique_id
              WHERE sub_products.product_unique_id=:product_unique_id ORDER BY sub_products.added_date DESC LIMIT 1";
              $query3 = $this->conn->prepare($sql3);
              $query3->bindParam(":product_unique_id", $product_id);
              $query3->execute();

              $sub_product_result = $query3->fetchAll();

              if ($query3->rowCount() > 0) {

                $current_sub_product = array();

                foreach ($sub_product_result as $key => $value) {

                  $current_sub_product['id'] = $value['id'];
                  $current_sub_product['unique_id'] = $value['unique_id'];
                  $current_sub_product['user_unique_id'] = $value['user_unique_id'];
                  $current_sub_product['edit_user_unique_id'] = $value['edit_user_unique_id'];
                  $current_sub_product['product_unique_id'] = $value['product_unique_id'];
                  $current_sub_product['name'] = $value['name'];
                  $current_sub_product['size'] = $value['size'];
                  $current_sub_product['stripped'] = $value['stripped'];
                  $current_sub_product['description'] = $value['description'];
                  $current_sub_product['stock'] = $value['stock'];
                  $current_sub_product['stock_remaining'] = $value['stock_remaining'];
                  $current_sub_product['price'] = $value['price'];
                  $current_sub_product['sales_price'] = $value['sales_price'];
                  $current_sub_product['favorites'] = $value['favorites'];
                  $current_sub_product['mini_category_unique_id'] = $value['mini_category_unique_id'];
                  $current_sub_product['sub_category_unique_id'] = $value['sub_category_unique_id'];
                  $current_sub_product['category_unique_id'] = $value['category_unique_id'];
                  $current_sub_product['product_name'] = $value['product_name'];
                  $current_sub_product['product_stripped'] = $value['product_stripped'];
                  $current_sub_product['brand_unique_id'] = $value['brand_unique_id'];
                  $current_sub_product['added_date'] = $value['added_date'];
                  $current_sub_product['last_modified'] = $value['last_modified'];
                  $current_sub_product['status'] = $value['status'];
                  $current_sub_product['added_fullname'] = $value['added_fullname'];
                  $current_sub_product['edit_user_fullname'] = $value['edit_user_fullname'];
                  $current_sub_product['category_name'] = $value['category_name'];
                  $current_sub_product['category_name_stripped'] = $value['category_name_stripped'];
                  $current_sub_product['sub_category_name'] = $value['sub_category_name'];
                  $current_sub_product['sub_category_name_stripped'] = $value['sub_category_name_stripped'];
                  $current_sub_product['mini_category_name'] = $value['mini_category_name'];
                  $current_sub_product['mini_category_name_stripped'] = $value['mini_category_name_stripped'];
                  $current_sub_product['brand_name'] = $value['brand_name'];
                  $current_sub_product['brand_name_stripped'] = $value['brand_name_stripped'];

                  $sub_product_id = $value['unique_id'];

                  $sql4 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id";
                  $query4 = $this->conn->prepare($sql4);
                  $query4->bindParam(":sub_product_unique_id", $sub_product_id);
                  $query4->execute();

                  $sub_product_images_result = $query4->fetchAll();

                  if ($query2->rowCount() > 0) {
                    $current_sub_product_images = array();

                    foreach ($sub_product_images_result as $key => $image_value) {
                      $current_sub_product_images[] = $image_value['image'];
                    }

                    $current_sub_product['sub_product_images'] = $current_sub_product_images;
                  }
                  else{
                    $current_sub_product['sub_product_images'] = null;
                  }

                  $sub_product_array[] = $current_sub_product;
                }

                $current_product['sub_products'] = $current_sub_product;
              }
              else{
                $current_product['sub_products'] = null;
              }

              $product_array[] = $current_product;
            }
            return $product_array;
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

      function get_all_products_for_shipping_fees(){

        try {
          $this->conn->beginTransaction();

          $product_array = array();

          $sql = "SELECT products.unique_id, products.name FROM products ORDER BY products.name ASC";
          $query = $this->conn->prepare($sql);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_product = array();
              $current_product['unique_id'] = $value['unique_id'];
              $current_product['name'] = $value['name'];

              $product_id = $value['unique_id'];

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

              $sub_product_array = array();

              $sql3 = "SELECT sub_products.unique_id, sub_products.name, sub_products.size, sub_products.price, sub_products.sales_price FROM sub_products
              WHERE sub_products.product_unique_id=:product_unique_id ORDER BY sub_products.name ASC";
              $query3 = $this->conn->prepare($sql3);
              $query3->bindParam(":product_unique_id", $product_id);
              $query3->execute();

              $sub_product_result = $query3->fetchAll();

              if ($query3->rowCount() > 0) {

                $current_sub_product = array();

                foreach ($sub_product_result as $key => $value) {

                  $current_sub_product['unique_id'] = $value['unique_id'];
                  $current_sub_product['name'] = $value['name'];
                  $current_sub_product['size'] = $value['size'];
                  $current_sub_product['price'] = $value['price'];
                  $current_sub_product['sales_price'] = $value['sales_price'];

                  $sub_product_id = $value['unique_id'];

                  $sql4 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id";
                  $query4 = $this->conn->prepare($sql4);
                  $query4->bindParam(":sub_product_unique_id", $sub_product_id);
                  $query4->execute();

                  $sub_product_images_result = $query4->fetchAll();

                  if ($query2->rowCount() > 0) {
                    $current_sub_product_images = array();

                    foreach ($sub_product_images_result as $key => $image_value) {
                      $current_sub_product_images[] = $image_value['image'];
                    }

                    $current_sub_product['sub_product_images'] = $current_sub_product_images;
                  }
                  else{
                    $current_sub_product['sub_product_images'] = null;
                  }

                  $sub_product_array[] = $current_sub_product;
                }

                $current_product['sub_products'] = $sub_product_array;
              }
              else{
                $current_product['sub_products'] = null;
              }

              $product_array[] = $current_product;
            }
            return $product_array;
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

      function get_all_products_for_coupons(){

        try {
          $this->conn->beginTransaction();

          $product_array = array();

          $sql = "SELECT products.unique_id, products.name FROM products ORDER BY products.name ASC";
          $query = $this->conn->prepare($sql);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_product = array();
              $current_product['unique_id'] = $value['unique_id'];
              $current_product['name'] = $value['name'];

              $product_id = $value['unique_id'];

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

              $sub_product_array = array();

              $sql3 = "SELECT sub_products.unique_id, sub_products.name, sub_products.size, sub_products.price, sub_products.sales_price FROM sub_products
              WHERE sub_products.product_unique_id=:product_unique_id ORDER BY sub_products.name ASC";
              $query3 = $this->conn->prepare($sql3);
              $query3->bindParam(":product_unique_id", $product_id);
              $query3->execute();

              $sub_product_result = $query3->fetchAll();

              if ($query3->rowCount() > 0) {

                $current_sub_product = array();

                foreach ($sub_product_result as $key => $value) {

                  $current_sub_product['unique_id'] = $value['unique_id'];
                  $current_sub_product['name'] = $value['name'];
                  $current_sub_product['size'] = $value['size'];
                  $current_sub_product['price'] = $value['price'];
                  $current_sub_product['sales_price'] = $value['sales_price'];

                  $sub_product_id = $value['unique_id'];

                  $sql4 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id";
                  $query4 = $this->conn->prepare($sql4);
                  $query4->bindParam(":sub_product_unique_id", $sub_product_id);
                  $query4->execute();

                  $sub_product_images_result = $query4->fetchAll();

                  if ($query2->rowCount() > 0) {
                    $current_sub_product_images = array();

                    foreach ($sub_product_images_result as $key => $image_value) {
                      $current_sub_product_images[] = $image_value['image'];
                    }

                    $current_sub_product['sub_product_images'] = $current_sub_product_images;
                  }
                  else{
                    $current_sub_product['sub_product_images'] = null;
                  }

                  $sub_product_array[] = $current_sub_product;
                }

                $current_product['sub_products'] = $sub_product_array;
              }
              else{
                $current_product['sub_products'] = null;
              }

              $product_array[] = $current_product;
            }
            return $product_array;
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

      function get_all_products_for_select(){

        try {
          $this->conn->beginTransaction();

          $product_array = array();

          $sql = "SELECT products.id, products.unique_id, products.user_unique_id, products.edit_user_unique_id, products.mini_category_unique_id, products.sub_category_unique_id, products.category_unique_id, products.name, products.stripped, products.brand_unique_id,
          products.description, products.favorites, products.added_date, products.last_modified, products.status,
          management.fullname as added_fullname, management_alt.fullname as edit_user_fullname, categories.name as category_name, categories.stripped as category_name_stripped, sub_category.name as sub_category_name, sub_category.stripped as sub_category_name_stripped, mini_category.name as mini_category_name,
          mini_category.stripped as mini_category_name_stripped, brands.name as brand_name, brands.stripped as brand_name_stripped FROM products INNER JOIN management ON products.user_unique_id = management.unique_id INNER JOIN management management_alt ON products.edit_user_unique_id = management_alt.unique_id
          LEFT JOIN categories ON products.category_unique_id = categories.unique_id LEFT JOIN sub_category ON products.sub_category_unique_id = sub_category.unique_id LEFT JOIN mini_category ON products.mini_category_unique_id = mini_category.unique_id LEFT JOIN brands ON products.brand_unique_id = brands.unique_id ORDER BY products.name ASC";
          $query = $this->conn->prepare($sql);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_product = array();
              $current_product['id'] = $value['id'];
              $current_product['unique_id'] = $value['unique_id'];
              $current_product['user_unique_id'] = $value['user_unique_id'];
              $current_product['edit_user_unique_id'] = $value['edit_user_unique_id'];
              $current_product['mini_category_unique_id'] = $value['mini_category_unique_id'];
              $current_product['sub_category_unique_id'] = $value['sub_category_unique_id'];
              $current_product['category_unique_id'] = $value['category_unique_id'];
              $current_product['name'] = $value['name'];
              $current_product['stripped'] = $value['stripped'];
              $current_product['brand_unique_id'] = $value['brand_unique_id'];
              $current_product['favorites'] = $value['favorites'];
              $current_product['added_date'] = $value['added_date'];
              $current_product['last_modified'] = $value['last_modified'];
              $current_product['status'] = $value['status'];
              $current_product['added_fullname'] = $value['added_fullname'];
              $current_product['edit_user_fullname'] = $value['edit_user_fullname'];
              $current_product['category_name'] = $value['category_name'];
              $current_product['category_name_stripped'] = $value['category_name_stripped'];
              $current_product['sub_category_name'] = $value['sub_category_name'];
              $current_product['sub_category_name_stripped'] = $value['sub_category_name_stripped'];
              $current_product['mini_category_name'] = $value['mini_category_name'];
              $current_product['mini_category_name_stripped'] = $value['mini_category_name_stripped'];
              $current_product['brand_name'] = $value['brand_name'];
              $current_product['brand_name_stripped'] = $value['brand_name_stripped'];

              $product_id = $value['unique_id'];

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

              $sql3 = "SELECT sub_products.id, sub_products.unique_id, sub_products.user_unique_id, sub_products.edit_user_unique_id, sub_products.product_unique_id, sub_products.name, sub_products.size, sub_products.stripped, sub_products.description, sub_products.stock, sub_products.stock_remaining,
              sub_products.price, sub_products.sales_price, sub_products.favorites, products.mini_category_unique_id, products.sub_category_unique_id, products.category_unique_id, products.name as product_name, products.stripped as product_stripped, products.brand_unique_id, sub_products.added_date, sub_products.last_modified, sub_products.status,
              management.fullname as added_fullname, management_alt.fullname as edit_user_fullname, categories.name as category_name, categories.stripped as category_name_stripped, sub_category.name as sub_category_name, sub_category.stripped as sub_category_name_stripped, mini_category.name as mini_category_name,
              mini_category.stripped as mini_category_name_stripped, brands.name as brand_name, brands.stripped as brand_name_stripped FROM sub_products INNER JOIN management ON sub_products.user_unique_id = management.unique_id INNER JOIN management management_alt ON sub_products.edit_user_unique_id = management_alt.unique_id
              LEFT JOIN products ON sub_products.product_unique_id = products.unique_id LEFT JOIN categories ON products.category_unique_id = categories.unique_id LEFT JOIN sub_category ON products.sub_category_unique_id = sub_category.unique_id LEFT JOIN mini_category ON products.mini_category_unique_id = mini_category.unique_id LEFT JOIN brands ON products.brand_unique_id = brands.unique_id
              WHERE sub_products.product_unique_id=:product_unique_id ORDER BY sub_products.added_date DESC LIMIT 1";
              $query3 = $this->conn->prepare($sql3);
              $query3->bindParam(":product_unique_id", $product_id);
              $query3->execute();

              $sub_product_result = $query3->fetchAll();

              if ($query3->rowCount() > 0) {

                $current_sub_product = array();

                foreach ($sub_product_result as $key => $value) {

                  $current_sub_product['id'] = $value['id'];
                  $current_sub_product['unique_id'] = $value['unique_id'];
                  $current_sub_product['user_unique_id'] = $value['user_unique_id'];
                  $current_sub_product['edit_user_unique_id'] = $value['edit_user_unique_id'];
                  $current_sub_product['product_unique_id'] = $value['product_unique_id'];
                  $current_sub_product['name'] = $value['name'];
                  $current_sub_product['size'] = $value['size'];
                  $current_sub_product['stripped'] = $value['stripped'];
                  $current_sub_product['description'] = $value['description'];
                  $current_sub_product['stock'] = $value['stock'];
                  $current_sub_product['stock_remaining'] = $value['stock_remaining'];
                  $current_sub_product['price'] = $value['price'];
                  $current_sub_product['sales_price'] = $value['sales_price'];
                  $current_sub_product['favorites'] = $value['favorites'];
                  $current_sub_product['mini_category_unique_id'] = $value['mini_category_unique_id'];
                  $current_sub_product['sub_category_unique_id'] = $value['sub_category_unique_id'];
                  $current_sub_product['category_unique_id'] = $value['category_unique_id'];
                  $current_sub_product['product_name'] = $value['product_name'];
                  $current_sub_product['product_stripped'] = $value['product_stripped'];
                  $current_sub_product['brand_unique_id'] = $value['brand_unique_id'];
                  $current_sub_product['added_date'] = $value['added_date'];
                  $current_sub_product['last_modified'] = $value['last_modified'];
                  $current_sub_product['status'] = $value['status'];
                  $current_sub_product['added_fullname'] = $value['added_fullname'];
                  $current_sub_product['edit_user_fullname'] = $value['edit_user_fullname'];
                  $current_sub_product['category_name'] = $value['category_name'];
                  $current_sub_product['category_name_stripped'] = $value['category_name_stripped'];
                  $current_sub_product['sub_category_name'] = $value['sub_category_name'];
                  $current_sub_product['sub_category_name_stripped'] = $value['sub_category_name_stripped'];
                  $current_sub_product['mini_category_name'] = $value['mini_category_name'];
                  $current_sub_product['mini_category_name_stripped'] = $value['mini_category_name_stripped'];
                  $current_sub_product['brand_name'] = $value['brand_name'];
                  $current_sub_product['brand_name_stripped'] = $value['brand_name_stripped'];

                  $sub_product_id = $value['unique_id'];

                  $sql4 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id";
                  $query4 = $this->conn->prepare($sql4);
                  $query4->bindParam(":sub_product_unique_id", $sub_product_id);
                  $query4->execute();

                  $sub_product_images_result = $query4->fetchAll();

                  if ($query2->rowCount() > 0) {
                    $current_sub_product_images = array();

                    foreach ($sub_product_images_result as $key => $image_value) {
                      $current_sub_product_images[] = $image_value['image'];
                    }

                    $current_sub_product['sub_product_images'] = $current_sub_product_images;
                  }
                  else{
                    $current_sub_product['sub_product_images'] = null;
                  }

                  $sub_product_array[] = $current_sub_product;
                }

                $current_product['sub_products'] = $current_sub_product;
              }
              else{
                $current_product['sub_products'] = null;
              }

              $product_array[] = $current_product;
            }
            return $product_array;
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

      function get_all_sub_products(){

        try {
          $this->conn->beginTransaction();

          $sub_product_array = array();

          $sql = "SELECT sub_products.id, sub_products.unique_id, sub_products.user_unique_id, sub_products.edit_user_unique_id, sub_products.product_unique_id, sub_products.name, sub_products.size, sub_products.stripped, sub_products.description, sub_products.stock, sub_products.stock_remaining,
          sub_products.price, sub_products.sales_price, sub_products.favorites, products.mini_category_unique_id, products.sub_category_unique_id, products.category_unique_id, products.name as product_name, products.stripped as product_stripped, products.brand_unique_id, sub_products.added_date, sub_products.last_modified, sub_products.status,
          management.fullname as added_fullname, management_alt.fullname as edit_user_fullname, categories.name as category_name, categories.stripped as category_name_stripped, sub_category.name as sub_category_name, sub_category.stripped as sub_category_name_stripped, mini_category.name as mini_category_name,
          mini_category.stripped as mini_category_name_stripped, brands.name as brand_name, brands.stripped as brand_name_stripped FROM sub_products INNER JOIN management ON sub_products.user_unique_id = management.unique_id INNER JOIN management management_alt ON sub_products.edit_user_unique_id = management_alt.unique_id
          LEFT JOIN products ON sub_products.product_unique_id = products.unique_id LEFT JOIN categories ON products.category_unique_id = categories.unique_id LEFT JOIN sub_category ON products.sub_category_unique_id = sub_category.unique_id LEFT JOIN mini_category ON products.mini_category_unique_id = mini_category.unique_id LEFT JOIN brands ON products.brand_unique_id = brands.unique_id ORDER BY sub_products.added_date DESC";
          $query = $this->conn->prepare($sql);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_sub_product = array();
              $current_sub_product['id'] = $value['id'];
              $current_sub_product['unique_id'] = $value['unique_id'];
              $current_sub_product['user_unique_id'] = $value['user_unique_id'];
              $current_sub_product['edit_user_unique_id'] = $value['edit_user_unique_id'];
              $current_sub_product['product_unique_id'] = $value['product_unique_id'];
              $current_sub_product['name'] = $value['name'];
              $current_sub_product['size'] = $value['size'];
              $current_sub_product['stripped'] = $value['stripped'];
              $current_sub_product['description'] = $value['description'];
              $current_sub_product['stock'] = $value['stock'];
              $current_sub_product['stock_remaining'] = $value['stock_remaining'];
              $current_sub_product['price'] = $value['price'];
              $current_sub_product['sales_price'] = $value['sales_price'];
              $current_sub_product['favorites'] = $value['favorites'];
              $current_sub_product['mini_category_unique_id'] = $value['mini_category_unique_id'];
              $current_sub_product['sub_category_unique_id'] = $value['sub_category_unique_id'];
              $current_sub_product['category_unique_id'] = $value['category_unique_id'];
              $current_sub_product['product_name'] = $value['product_name'];
              $current_sub_product['product_stripped'] = $value['product_stripped'];
              $current_sub_product['brand_unique_id'] = $value['brand_unique_id'];
              $current_sub_product['added_date'] = $value['added_date'];
              $current_sub_product['last_modified'] = $value['last_modified'];
              $current_sub_product['status'] = $value['status'];
              $current_sub_product['added_fullname'] = $value['added_fullname'];
              $current_sub_product['edit_user_fullname'] = $value['edit_user_fullname'];
              $current_sub_product['category_name'] = $value['category_name'];
              $current_sub_product['category_name_stripped'] = $value['category_name_stripped'];
              $current_sub_product['sub_category_name'] = $value['sub_category_name'];
              $current_sub_product['sub_category_name_stripped'] = $value['sub_category_name_stripped'];
              $current_sub_product['mini_category_name'] = $value['mini_category_name'];
              $current_sub_product['mini_category_name_stripped'] = $value['mini_category_name_stripped'];
              $current_sub_product['brand_name'] = $value['brand_name'];
              $current_sub_product['brand_name_stripped'] = $value['brand_name_stripped'];

              $sub_product_id = $value['unique_id'];

              $sql2 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id";
              $query2 = $this->conn->prepare($sql2);
              $query2->bindParam(":sub_product_unique_id", $sub_product_id);
              $query2->execute();

              $images_result = $query2->fetchAll();

              if ($query2->rowCount() > 0) {
                $current_sub_product_images = array();

                foreach ($images_result as $key => $image_value) {
                  $current_sub_product_images[] = $image_value['image'];
                }

                $current_sub_product['sub_product_images'] = $current_sub_product_images;
              }
              else{
                $current_sub_product['sub_product_images'] = null;
              }

              $sub_product_array[] = $current_sub_product;
            }
            return $sub_product_array;
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

      function get_all_sub_products_for_select(){

        try {
          $this->conn->beginTransaction();

          $sub_product_array = array();

          $sql = "SELECT sub_products.id, sub_products.unique_id, sub_products.user_unique_id, sub_products.edit_user_unique_id, sub_products.product_unique_id, sub_products.name, sub_products.size, sub_products.stripped, sub_products.description, sub_products.stock, sub_products.stock_remaining,
          sub_products.price, sub_products.sales_price, sub_products.favorites, products.mini_category_unique_id, products.sub_category_unique_id, products.category_unique_id, products.name as product_name, products.stripped as product_stripped, products.brand_unique_id, sub_products.added_date, sub_products.last_modified, sub_products.status,
          management.fullname as added_fullname, management_alt.fullname as edit_user_fullname, categories.name as category_name, categories.stripped as category_name_stripped, sub_category.name as sub_category_name, sub_category.stripped as sub_category_name_stripped, mini_category.name as mini_category_name,
          mini_category.stripped as mini_category_name_stripped, brands.name as brand_name, brands.stripped as brand_name_stripped FROM sub_products INNER JOIN management ON sub_products.user_unique_id = management.unique_id INNER JOIN management management_alt ON sub_products.edit_user_unique_id = management_alt.unique_id
          LEFT JOIN products ON sub_products.product_unique_id = products.unique_id LEFT JOIN categories ON products.category_unique_id = categories.unique_id LEFT JOIN sub_category ON products.sub_category_unique_id = sub_category.unique_id LEFT JOIN mini_category ON products.mini_category_unique_id = mini_category.unique_id LEFT JOIN brands ON products.brand_unique_id = brands.unique_id ORDER BY sub_products.name ASC";
          $query = $this->conn->prepare($sql);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_sub_product = array();
              $current_sub_product['id'] = $value['id'];
              $current_sub_product['unique_id'] = $value['unique_id'];
              $current_sub_product['user_unique_id'] = $value['user_unique_id'];
              $current_sub_product['edit_user_unique_id'] = $value['edit_user_unique_id'];
              $current_sub_product['product_unique_id'] = $value['product_unique_id'];
              $current_sub_product['name'] = $value['name'];
              $current_sub_product['size'] = $value['size'];
              $current_sub_product['stripped'] = $value['stripped'];
              $current_sub_product['description'] = $value['description'];
              $current_sub_product['stock'] = $value['stock'];
              $current_sub_product['stock_remaining'] = $value['stock_remaining'];
              $current_sub_product['price'] = $value['price'];
              $current_sub_product['sales_price'] = $value['sales_price'];
              $current_sub_product['favorites'] = $value['favorites'];
              $current_sub_product['mini_category_unique_id'] = $value['mini_category_unique_id'];
              $current_sub_product['sub_category_unique_id'] = $value['sub_category_unique_id'];
              $current_sub_product['category_unique_id'] = $value['category_unique_id'];
              $current_sub_product['product_name'] = $value['product_name'];
              $current_sub_product['product_stripped'] = $value['product_stripped'];
              $current_sub_product['brand_unique_id'] = $value['brand_unique_id'];
              $current_sub_product['added_date'] = $value['added_date'];
              $current_sub_product['last_modified'] = $value['last_modified'];
              $current_sub_product['status'] = $value['status'];
              $current_sub_product['added_fullname'] = $value['added_fullname'];
              $current_sub_product['edit_user_fullname'] = $value['edit_user_fullname'];
              $current_sub_product['category_name'] = $value['category_name'];
              $current_sub_product['category_name_stripped'] = $value['category_name_stripped'];
              $current_sub_product['sub_category_name'] = $value['sub_category_name'];
              $current_sub_product['sub_category_name_stripped'] = $value['sub_category_name_stripped'];
              $current_sub_product['mini_category_name'] = $value['mini_category_name'];
              $current_sub_product['mini_category_name_stripped'] = $value['mini_category_name_stripped'];
              $current_sub_product['brand_name'] = $value['brand_name'];
              $current_sub_product['brand_name_stripped'] = $value['brand_name_stripped'];

              $sub_product_id = $value['unique_id'];

              $sql2 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id";
              $query2 = $this->conn->prepare($sql2);
              $query2->bindParam(":sub_product_unique_id", $sub_product_id);
              $query2->execute();

              $images_result = $query2->fetchAll();

              if ($query2->rowCount() > 0) {
                $current_sub_product_images = array();

                foreach ($images_result as $key => $image_value) {
                  $current_sub_product_images[] = $image_value['image'];
                }

                $current_sub_product['sub_product_images'] = $current_sub_product_images;
              }
              else{
                $current_sub_product['sub_product_images'] = null;
              }

              $sub_product_array[] = $current_sub_product;
            }
            return $sub_product_array;
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

      function get_all_product_sub_products($product_unique_id){

        try {
          $this->conn->beginTransaction();

          $sub_product_array = array();

          $sql = "SELECT sub_products.id, sub_products.unique_id, sub_products.user_unique_id, sub_products.edit_user_unique_id, sub_products.product_unique_id, sub_products.name, sub_products.size, sub_products.stripped, sub_products.description, sub_products.stock, sub_products.stock_remaining,
          sub_products.price, sub_products.sales_price, sub_products.favorites, products.mini_category_unique_id, products.sub_category_unique_id, products.category_unique_id, products.name as product_name, products.stripped as product_stripped, products.brand_unique_id, sub_products.added_date, sub_products.last_modified, sub_products.status,
          management.fullname as added_fullname, management_alt.fullname as edit_user_fullname, categories.name as category_name, categories.stripped as category_name_stripped, sub_category.name as sub_category_name, sub_category.stripped as sub_category_name_stripped, mini_category.name as mini_category_name,
          mini_category.stripped as mini_category_name_stripped, brands.name as brand_name, brands.stripped as brand_name_stripped FROM sub_products INNER JOIN management ON sub_products.user_unique_id = management.unique_id INNER JOIN management management_alt ON sub_products.edit_user_unique_id = management_alt.unique_id
          LEFT JOIN products ON sub_products.product_unique_id = products.unique_id LEFT JOIN categories ON products.category_unique_id = categories.unique_id LEFT JOIN sub_category ON products.sub_category_unique_id = sub_category.unique_id LEFT JOIN mini_category ON products.mini_category_unique_id = mini_category.unique_id LEFT JOIN brands ON products.brand_unique_id = brands.unique_id
          WHERE sub_products.product_unique_id=:product_unique_id ORDER BY sub_products.added_date DESC";
          $query = $this->conn->prepare($sql);
          $query->bindParam(":product_unique_id", $product_unique_id);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_sub_product = array();
              $current_sub_product['id'] = $value['id'];
              $current_sub_product['unique_id'] = $value['unique_id'];
              $current_sub_product['user_unique_id'] = $value['user_unique_id'];
              $current_sub_product['edit_user_unique_id'] = $value['edit_user_unique_id'];
              $current_sub_product['product_unique_id'] = $value['product_unique_id'];
              $current_sub_product['name'] = $value['name'];
              $current_sub_product['size'] = $value['size'];
              $current_sub_product['stripped'] = $value['stripped'];
              $current_sub_product['description'] = $value['description'];
              $current_sub_product['stock'] = $value['stock'];
              $current_sub_product['stock_remaining'] = $value['stock_remaining'];
              $current_sub_product['price'] = $value['price'];
              $current_sub_product['sales_price'] = $value['sales_price'];
              $current_sub_product['favorites'] = $value['favorites'];
              $current_sub_product['mini_category_unique_id'] = $value['mini_category_unique_id'];
              $current_sub_product['sub_category_unique_id'] = $value['sub_category_unique_id'];
              $current_sub_product['category_unique_id'] = $value['category_unique_id'];
              $current_sub_product['product_name'] = $value['product_name'];
              $current_sub_product['product_stripped'] = $value['product_stripped'];
              $current_sub_product['brand_unique_id'] = $value['brand_unique_id'];
              $current_sub_product['added_date'] = $value['added_date'];
              $current_sub_product['last_modified'] = $value['last_modified'];
              $current_sub_product['status'] = $value['status'];
              $current_sub_product['added_fullname'] = $value['added_fullname'];
              $current_sub_product['edit_user_fullname'] = $value['edit_user_fullname'];
              $current_sub_product['category_name'] = $value['category_name'];
              $current_sub_product['category_name_stripped'] = $value['category_name_stripped'];
              $current_sub_product['sub_category_name'] = $value['sub_category_name'];
              $current_sub_product['sub_category_name_stripped'] = $value['sub_category_name_stripped'];
              $current_sub_product['mini_category_name'] = $value['mini_category_name'];
              $current_sub_product['mini_category_name_stripped'] = $value['mini_category_name_stripped'];
              $current_sub_product['brand_name'] = $value['brand_name'];
              $current_sub_product['brand_name_stripped'] = $value['brand_name_stripped'];

              $sub_product_id = $value['unique_id'];

              $sql2 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id";
              $query2 = $this->conn->prepare($sql2);
              $query2->bindParam(":sub_product_unique_id", $sub_product_id);
              $query2->execute();

              $images_result = $query2->fetchAll();

              if ($query2->rowCount() > 0) {
                $current_sub_product_images = array();

                foreach ($images_result as $key => $image_value) {
                  $current_sub_product_images[] = $image_value['image'];
                }

                $current_sub_product['sub_product_images'] = $current_sub_product_images;
              }
              else{
                $current_sub_product['sub_product_images'] = null;
              }

              $sub_product_array[] = $current_sub_product;
            }
            return $sub_product_array;
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

      function get_product_details($unique_id, $stripped){
        if (!in_array($unique_id,$this->not_allowed_values) || !in_array($stripped,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $product_array = array();

            $sql = "SELECT products.id, products.unique_id, products.user_unique_id, products.edit_user_unique_id, products.mini_category_unique_id, products.sub_category_unique_id, products.category_unique_id, products.name, products.stripped, products.brand_unique_id,
            products.description, products.favorites, products.added_date, products.last_modified, products.status,
            management.fullname as added_fullname, management_alt.fullname as edit_user_fullname, categories.name as category_name, categories.stripped as category_name_stripped, sub_category.name as sub_category_name, sub_category.stripped as sub_category_name_stripped, mini_category.name as mini_category_name,
            mini_category.stripped as mini_category_name_stripped, brands.name as brand_name, brands.stripped as brand_name_stripped FROM products INNER JOIN management ON products.user_unique_id = management.unique_id INNER JOIN management management_alt ON products.edit_user_unique_id = management_alt.unique_id
            LEFT JOIN categories ON products.category_unique_id = categories.unique_id LEFT JOIN sub_category ON products.sub_category_unique_id = sub_category.unique_id LEFT JOIN mini_category ON products.mini_category_unique_id = mini_category.unique_id LEFT JOIN brands ON products.brand_unique_id = brands.unique_id
            WHERE products.unique_id=:unique_id OR products.stripped=:stripped ORDER BY products.added_date DESC LIMIT 1";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $unique_id);
            $query->bindParam(":stripped", $stripped);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_product = array();
                $current_product['id'] = $value['id'];
                $current_product['unique_id'] = $value['unique_id'];
                $current_product['user_unique_id'] = $value['user_unique_id'];
                $current_product['edit_user_unique_id'] = $value['edit_user_unique_id'];
                $current_product['mini_category_unique_id'] = $value['mini_category_unique_id'];
                $current_product['sub_category_unique_id'] = $value['sub_category_unique_id'];
                $current_product['category_unique_id'] = $value['category_unique_id'];
                $current_product['name'] = $value['name'];
                $current_product['stripped'] = $value['stripped'];
                $current_product['brand_unique_id'] = $value['brand_unique_id'];
                $current_product['description'] = $value['description'];
                $current_product['favorites'] = $value['favorites'];
                $current_product['added_date'] = $value['added_date'];
                $current_product['last_modified'] = $value['last_modified'];
                $current_product['status'] = $value['status'];
                $current_product['added_fullname'] = $value['added_fullname'];
                $current_product['edit_user_fullname'] = $value['edit_user_fullname'];
                $current_product['category_name'] = $value['category_name'];
                $current_product['category_name_stripped'] = $value['category_name_stripped'];
                $current_product['sub_category_name'] = $value['sub_category_name'];
                $current_product['sub_category_name_stripped'] = $value['sub_category_name_stripped'];
                $current_product['mini_category_name'] = $value['mini_category_name'];
                $current_product['mini_category_name_stripped'] = $value['mini_category_name_stripped'];
                $current_product['brand_name'] = $value['brand_name'];
                $current_product['brand_name_stripped'] = $value['brand_name_stripped'];

                $product_id = $value['unique_id'];

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

                $product_array[] = $current_product;
              }
              return $product_array;
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
          $output['message'] = "At least one value is required";
          return $output;
        }
      }

      function get_sub_product_details($unique_id, $stripped){
        if (!in_array($unique_id,$this->not_allowed_values) || !in_array($stripped,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sub_product_array = array();

            $sql = "SELECT sub_products.id, sub_products.unique_id, sub_products.user_unique_id, sub_products.edit_user_unique_id, sub_products.product_unique_id, sub_products.name, sub_products.size, sub_products.stripped, sub_products.description, sub_products.stock, sub_products.stock_remaining,
            sub_products.price, sub_products.sales_price, sub_products.favorites, products.mini_category_unique_id, products.sub_category_unique_id, products.category_unique_id, products.name as product_name, products.stripped as product_stripped, products.brand_unique_id, sub_products.added_date, sub_products.last_modified, sub_products.status,
            management.fullname as added_fullname, management_alt.fullname as edit_user_fullname, categories.name as category_name, categories.stripped as category_name_stripped, sub_category.name as sub_category_name, sub_category.stripped as sub_category_name_stripped, mini_category.name as mini_category_name,
            mini_category.stripped as mini_category_name_stripped, brands.name as brand_name, brands.stripped as brand_name_stripped FROM sub_products INNER JOIN management ON sub_products.user_unique_id = management.unique_id INNER JOIN management management_alt ON sub_products.edit_user_unique_id = management_alt.unique_id
            LEFT JOIN products ON sub_products.product_unique_id = products.unique_id LEFT JOIN categories ON products.category_unique_id = categories.unique_id LEFT JOIN sub_category ON products.sub_category_unique_id = sub_category.unique_id LEFT JOIN mini_category ON products.mini_category_unique_id = mini_category.unique_id LEFT JOIN brands ON products.brand_unique_id = brands.unique_id
            WHERE sub_products.unique_id=:unique_id OR sub_products.stripped=:stripped ORDER BY sub_products.added_date DESC LIMIT 1";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $unique_id);
            $query->bindParam(":stripped", $stripped);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_sub_product = array();
                $current_sub_product['id'] = $value['id'];
                $current_sub_product['unique_id'] = $value['unique_id'];
                $current_sub_product['user_unique_id'] = $value['user_unique_id'];
                $current_sub_product['edit_user_unique_id'] = $value['edit_user_unique_id'];
                $current_sub_product['product_unique_id'] = $value['product_unique_id'];
                $current_sub_product['name'] = $value['name'];
                $current_sub_product['size'] = $value['size'];
                $current_sub_product['stripped'] = $value['stripped'];
                $current_sub_product['description'] = $value['description'];
                $current_sub_product['stock'] = $value['stock'];
                $current_sub_product['stock_remaining'] = $value['stock_remaining'];
                $current_sub_product['price'] = $value['price'];
                $current_sub_product['sales_price'] = $value['sales_price'];
                $current_sub_product['favorites'] = $value['favorites'];
                $current_sub_product['mini_category_unique_id'] = $value['mini_category_unique_id'];
                $current_sub_product['sub_category_unique_id'] = $value['sub_category_unique_id'];
                $current_sub_product['category_unique_id'] = $value['category_unique_id'];
                $current_sub_product['product_name'] = $value['product_name'];
                $current_sub_product['product_stripped'] = $value['product_stripped'];
                $current_sub_product['brand_unique_id'] = $value['brand_unique_id'];
                $current_sub_product['added_date'] = $value['added_date'];
                $current_sub_product['last_modified'] = $value['last_modified'];
                $current_sub_product['status'] = $value['status'];
                $current_sub_product['added_fullname'] = $value['added_fullname'];
                $current_sub_product['edit_user_fullname'] = $value['edit_user_fullname'];
                $current_sub_product['category_name'] = $value['category_name'];
                $current_sub_product['category_name_stripped'] = $value['category_name_stripped'];
                $current_sub_product['sub_category_name'] = $value['sub_category_name'];
                $current_sub_product['sub_category_name_stripped'] = $value['sub_category_name_stripped'];
                $current_sub_product['mini_category_name'] = $value['mini_category_name'];
                $current_sub_product['mini_category_name_stripped'] = $value['mini_category_name_stripped'];
                $current_sub_product['brand_name'] = $value['brand_name'];
                $current_sub_product['brand_name_stripped'] = $value['brand_name_stripped'];

                $sub_product_id = $value['unique_id'];

                $sql2 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":sub_product_unique_id", $sub_product_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_sub_product_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_sub_product_images[] = $image_value['image'];
                  }

                  $current_sub_product['sub_product_images'] = $current_sub_product_images;
                }
                else{
                  $current_sub_product['sub_product_images'] = null;
                }

                $sub_product_array[] = $current_sub_product;
              }
              return $sub_product_array;
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
          $output['message'] = "At least one value is required";
          return $output;
        }
      }

      function get_product_images($unique_id){
        if (!in_array($unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $product_array = array();

            $sql = "SELECT product_images.id, product_images.unique_id, product_images.user_unique_id, product_images.edit_user_unique_id, product_images.product_unique_id, product_images.image, product_images.file, product_images.file_size, product_images.added_date, product_images.last_modified, product_images.status,
            management.fullname as added_fullname, management_alt.fullname as edit_user_fullname, products.name as product_name FROM product_images INNER JOIN management ON product_images.user_unique_id = management.unique_id INNER JOIN management management_alt ON product_images.edit_user_unique_id = management_alt.unique_id
            LEFT JOIN products ON product_images.product_unique_id = products.unique_id WHERE product_images.product_unique_id=:product_unique_id ORDER BY product_images.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":product_unique_id", $unique_id);
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

      function get_product_image_details($unique_id, $product_unique_id){
        if (!in_array($unique_id,$this->not_allowed_values) && !in_array($product_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $product_array = array();

            $sql = "SELECT product_images.id, product_images.unique_id, product_images.user_unique_id, product_images.edit_user_unique_id, product_images.product_unique_id, product_images.image, product_images.file, product_images.file_size, product_images.added_date, product_images.last_modified, product_images.status,
            management.fullname as added_fullname, management_alt.fullname as edit_user_fullname, products.name as product_name FROM product_images INNER JOIN management ON product_images.user_unique_id = management.unique_id INNER JOIN management management_alt ON product_images.edit_user_unique_id = management_alt.unique_id
            LEFT JOIN products ON product_images.product_unique_id = products.unique_id WHERE product_images.unique_id=:unique_id AND product_images.product_unique_id=:product_unique_id ORDER BY products.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $unique_id);
            $query->bindParam(":product_unique_id", $product_unique_id);
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

      function get_sub_product_images($unique_id){
        if (!in_array($unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $product_array = array();

            $sql = "SELECT sub_product_images.id, sub_product_images.unique_id, sub_product_images.user_unique_id, sub_product_images.edit_user_unique_id, sub_product_images.sub_product_unique_id, sub_product_images.image, sub_product_images.file, sub_product_images.file_size, sub_product_images.added_date, sub_product_images.last_modified, sub_product_images.status,
            management.fullname as added_fullname, management_alt.fullname as edit_user_fullname, sub_products.name as sub_product_name FROM sub_product_images INNER JOIN management ON sub_product_images.user_unique_id = management.unique_id INNER JOIN management management_alt ON sub_product_images.edit_user_unique_id = management_alt.unique_id
            LEFT JOIN sub_products ON sub_product_images.sub_product_unique_id = sub_products.unique_id WHERE sub_product_images.sub_product_unique_id=:sub_product_unique_id ORDER BY sub_product_images.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":sub_product_unique_id", $unique_id);
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

      function get_sub_product_image_details($unique_id, $sub_product_unique_id){
        if (!in_array($unique_id,$this->not_allowed_values) && !in_array($sub_product_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $product_array = array();

            $sql = "SELECT sub_product_images.id, sub_product_images.unique_id, sub_product_images.user_unique_id, sub_product_images.edit_user_unique_id, sub_product_images.sub_product_unique_id, sub_product_images.image, sub_product_images.file, sub_product_images.file_size, sub_product_images.added_date, sub_product_images.last_modified, sub_product_images.status,
            management.fullname as added_fullname, management_alt.fullname as edit_user_fullname, sub_products.name as sub_product_name FROM sub_product_images INNER JOIN management ON sub_product_images.user_unique_id = management.unique_id INNER JOIN management management_alt ON sub_product_images.edit_user_unique_id = management_alt.unique_id
            LEFT JOIN sub_products ON sub_product_images.sub_product_unique_id = sub_products.unique_id WHERE sub_product_images.unique_id=:unique_id AND sub_product_images.sub_product_unique_id=:sub_product_unique_id ORDER BY sub_product_images.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $unique_id);
            $query->bindParam(":sub_product_unique_id", $sub_product_unique_id);
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

      function get_brand_images($unique_id){
        if (!in_array($unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $brand_array = array();

            $sql = "SELECT brand_images.id, brand_images.unique_id, brand_images.user_unique_id, brand_images.edit_user_unique_id, brand_images.brand_unique_id, brand_images.image, brand_images.file, brand_images.file_size, brand_images.added_date, brand_images.last_modified, brand_images.status,
            management.fullname as added_fullname, management_alt.fullname as edit_user_fullname, brands.name as brand_name FROM brand_images INNER JOIN management ON brand_images.user_unique_id = management.unique_id INNER JOIN management management_alt ON brand_images.edit_user_unique_id = management_alt.unique_id
            LEFT JOIN brands ON brand_images.brand_unique_id = brands.unique_id WHERE brand_images.brand_unique_id=:brand_unique_id ORDER BY brand_images.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":brand_unique_id", $unique_id);
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

      function get_brand_image_details($unique_id, $brand_unique_id){
        if (!in_array($unique_id,$this->not_allowed_values) && !in_array($brand_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $brand_array = array();

            $sql = "SELECT brand_images.id, brand_images.unique_id, brand_images.user_unique_id, brand_images.edit_user_unique_id, brand_images.brand_unique_id, brand_images.image, brand_images.file, brand_images.file_size, brand_images.added_date, brand_images.last_modified, brand_images.status,
            management.fullname as added_fullname, management_alt.fullname as edit_user_fullname, brands.name as brand_name FROM brand_images INNER JOIN management ON brand_images.user_unique_id = management.unique_id INNER JOIN management management_alt ON brand_images.edit_user_unique_id = management_alt.unique_id
            LEFT JOIN brands ON brand_images.brand_unique_id = brands.unique_id WHERE brand_images.unique_id=:unique_id AND brand_images.brand_unique_id=:brand_unique_id ORDER BY brand_images.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $unique_id);
            $query->bindParam(":brand_unique_id", $brand_unique_id);
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

      function get_sub_category_images($unique_id){
        if (!in_array($unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sub_category_array = array();

            $sql = "SELECT sub_category_images.id, sub_category_images.unique_id, sub_category_images.user_unique_id, sub_category_images.edit_user_unique_id, sub_category_images.sub_category_unique_id, sub_category_images.image, sub_category_images.file, sub_category_images.file_size, sub_category_images.added_date, sub_category_images.last_modified, sub_category_images.status,
            management.fullname as added_fullname, management_alt.fullname as edit_user_fullname, sub_category.name as sub_category_name FROM sub_category_images INNER JOIN management ON sub_category_images.user_unique_id = management.unique_id INNER JOIN management management_alt ON sub_category_images.edit_user_unique_id = management_alt.unique_id
            LEFT JOIN sub_category ON sub_category_images.sub_category_unique_id = sub_category.unique_id WHERE sub_category_images.sub_category_unique_id=:sub_category_unique_id ORDER BY sub_category_images.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":sub_category_unique_id", $unique_id);
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

      function get_sub_category_image_details($unique_id, $sub_category_unique_id){
        if (!in_array($unique_id,$this->not_allowed_values) && !in_array($sub_category_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sub_category_array = array();

            $sql = "SELECT sub_category_images.id, sub_category_images.unique_id, sub_category_images.user_unique_id, sub_category_images.edit_user_unique_id, sub_category_images.sub_category_unique_id, sub_category_images.image, sub_category_images.file, sub_category_images.file_size, sub_category_images.added_date, sub_category_images.last_modified, sub_category_images.status,
            management.fullname as added_fullname, management_alt.fullname as edit_user_fullname, sub_category.name as sub_category_name FROM sub_category_images INNER JOIN management ON sub_category_images.user_unique_id = management.unique_id INNER JOIN management management_alt ON sub_category_images.edit_user_unique_id = management_alt.unique_id
            LEFT JOIN sub_category ON sub_category_images.sub_category_unique_id = sub_category.unique_id WHERE sub_category_images.unique_id=:unique_id AND sub_category_images.sub_category_unique_id=:sub_category_unique_id ORDER BY sub_category_images.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $unique_id);
            $query->bindParam(":sub_category_unique_id", $sub_category_unique_id);
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

      function get_mini_category_images($unique_id){
        if (!in_array($unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $mini_category_array = array();

            $sql = "SELECT mini_category_images.id, mini_category_images.unique_id, mini_category_images.user_unique_id, mini_category_images.edit_user_unique_id, mini_category_images.mini_category_unique_id, mini_category_images.image, mini_category_images.file, mini_category_images.file_size, mini_category_images.added_date, mini_category_images.last_modified, mini_category_images.status,
            management.fullname as added_fullname, management_alt.fullname as edit_user_fullname, mini_category.name as mini_category_name FROM mini_category_images INNER JOIN management ON mini_category_images.user_unique_id = management.unique_id INNER JOIN management management_alt ON mini_category_images.edit_user_unique_id = management_alt.unique_id
            LEFT JOIN mini_category ON mini_category_images.mini_category_unique_id = mini_category.unique_id WHERE mini_category_images.mini_category_unique_id=:mini_category_unique_id ORDER BY mini_category_images.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":mini_category_unique_id", $unique_id);
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

      function get_mini_category_image_details($unique_id, $mini_category_unique_id){
        if (!in_array($unique_id,$this->not_allowed_values) && !in_array($mini_category_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $mini_category_array = array();

            $sql = "SELECT mini_category_images.id, mini_category_images.unique_id, mini_category_images.user_unique_id, mini_category_images.edit_user_unique_id, mini_category_images.mini_category_unique_id, mini_category_images.image, mini_category_images.file, mini_category_images.file_size, mini_category_images.added_date, mini_category_images.last_modified, mini_category_images.status,
            management.fullname as added_fullname, management_alt.fullname as edit_user_fullname, mini_category.name as mini_category_name FROM mini_category_images INNER JOIN management ON mini_category_images.user_unique_id = management.unique_id INNER JOIN management management_alt ON mini_category_images.edit_user_unique_id = management_alt.unique_id
            LEFT JOIN mini_category ON mini_category_images.mini_category_unique_id = mini_category.unique_id WHERE mini_category_images.unique_id=:unique_id AND mini_category_images.mini_category_unique_id=:mini_category_unique_id ORDER BY mini_category_images.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $unique_id);
            $query->bindParam(":mini_category_unique_id", $mini_category_unique_id);
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

      function get_short_detail_of_products_for_users(){
        try {
          $this->conn->beginTransaction();

          $active = $this->functions->active;

          $product_array = array();

          $sql = "SELECT products.unique_id, products.name, products.stripped, products.brand_unique_id, products.favorites, brands.name as brand_name, brands.stripped as brand_name_stripped FROM products
          LEFT JOIN brands ON products.brand_unique_id = brands.unique_id WHERE products.status=:status ORDER BY products.added_date DESC";
          $query = $this->conn->prepare($sql);
          $query->bindParam(":status", $active);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_product = array();
              $current_product['unique_id'] = $value['unique_id'];
              $current_product['name'] = $value['name'];
              $current_product['stripped'] = $value['stripped'];
              $current_product['brand_unique_id'] = $value['brand_unique_id'];
              $current_product['favorites'] = $value['favorites'];
              $current_product['brand_name'] = $value['brand_name'];
              $current_product['brand_name_stripped'] = $value['brand_name_stripped'];

              $product_id = $value['unique_id'];

              $sql2 = "SELECT image FROM product_images WHERE product_unique_id=:product_unique_id LIMIT 1";
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

              $sql3 = "SELECT sub_products.id, sub_products.unique_id, sub_products.user_unique_id, sub_products.edit_user_unique_id, sub_products.product_unique_id, sub_products.name, sub_products.size, sub_products.stripped, sub_products.description, sub_products.stock, sub_products.stock_remaining,
              sub_products.price, sub_products.sales_price, sub_products.favorites, products.mini_category_unique_id, products.sub_category_unique_id, products.category_unique_id, products.name as product_name, products.stripped as product_stripped, products.brand_unique_id, sub_products.added_date, sub_products.last_modified, sub_products.status,
              management.fullname as added_fullname, management_alt.fullname as edit_user_fullname, categories.name as category_name, categories.stripped as category_name_stripped, sub_category.name as sub_category_name, sub_category.stripped as sub_category_name_stripped, mini_category.name as mini_category_name,
              mini_category.stripped as mini_category_name_stripped, brands.name as brand_name, brands.stripped as brand_name_stripped FROM sub_products INNER JOIN management ON sub_products.user_unique_id = management.unique_id INNER JOIN management management_alt ON sub_products.edit_user_unique_id = management_alt.unique_id
              LEFT JOIN products ON sub_products.product_unique_id = products.unique_id LEFT JOIN categories ON products.category_unique_id = categories.unique_id LEFT JOIN sub_category ON products.sub_category_unique_id = sub_category.unique_id LEFT JOIN mini_category ON products.mini_category_unique_id = mini_category.unique_id LEFT JOIN brands ON products.brand_unique_id = brands.unique_id
              WHERE sub_products.product_unique_id=:product_unique_id ORDER BY sub_products.added_date DESC LIMIT 1";
              $query3 = $this->conn->prepare($sql3);
              $query3->bindParam(":product_unique_id", $product_id);
              $query3->execute();

              $sub_product_result = $query3->fetchAll();

              if ($query3->rowCount() > 0) {

                $current_sub_product = array();

                foreach ($sub_product_result as $key => $value) {

                  $current_sub_product['id'] = $value['id'];
                  $current_sub_product['unique_id'] = $value['unique_id'];
                  $current_sub_product['user_unique_id'] = $value['user_unique_id'];
                  $current_sub_product['edit_user_unique_id'] = $value['edit_user_unique_id'];
                  $current_sub_product['product_unique_id'] = $value['product_unique_id'];
                  $current_sub_product['name'] = $value['name'];
                  $current_sub_product['size'] = $value['size'];
                  $current_sub_product['stripped'] = $value['stripped'];
                  $current_sub_product['description'] = $value['description'];
                  $current_sub_product['stock'] = $value['stock'];
                  $current_sub_product['stock_remaining'] = $value['stock_remaining'];
                  $current_sub_product['price'] = $value['price'];
                  $current_sub_product['sales_price'] = $value['sales_price'];
                  $current_sub_product['favorites'] = $value['favorites'];
                  $current_sub_product['mini_category_unique_id'] = $value['mini_category_unique_id'];
                  $current_sub_product['sub_category_unique_id'] = $value['sub_category_unique_id'];
                  $current_sub_product['category_unique_id'] = $value['category_unique_id'];
                  $current_sub_product['product_name'] = $value['product_name'];
                  $current_sub_product['product_stripped'] = $value['product_stripped'];
                  $current_sub_product['brand_unique_id'] = $value['brand_unique_id'];
                  $current_sub_product['added_date'] = $value['added_date'];
                  $current_sub_product['last_modified'] = $value['last_modified'];
                  $current_sub_product['status'] = $value['status'];
                  $current_sub_product['added_fullname'] = $value['added_fullname'];
                  $current_sub_product['edit_user_fullname'] = $value['edit_user_fullname'];
                  $current_sub_product['category_name'] = $value['category_name'];
                  $current_sub_product['category_name_stripped'] = $value['category_name_stripped'];
                  $current_sub_product['sub_category_name'] = $value['sub_category_name'];
                  $current_sub_product['sub_category_name_stripped'] = $value['sub_category_name_stripped'];
                  $current_sub_product['mini_category_name'] = $value['mini_category_name'];
                  $current_sub_product['mini_category_name_stripped'] = $value['mini_category_name_stripped'];
                  $current_sub_product['brand_name'] = $value['brand_name'];
                  $current_sub_product['brand_name_stripped'] = $value['brand_name_stripped'];

                  $sub_product_id = $value['unique_id'];

                  $sql4 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id";
                  $query4 = $this->conn->prepare($sql4);
                  $query4->bindParam(":sub_product_unique_id", $sub_product_id);
                  $query4->execute();

                  $sub_product_images_result = $query4->fetchAll();

                  if ($query2->rowCount() > 0) {
                    $current_sub_product_images = array();

                    foreach ($sub_product_images_result as $key => $image_value) {
                      $current_sub_product_images[] = $image_value['image'];
                    }

                    $current_sub_product['sub_product_images'] = $current_sub_product_images;
                  }
                  else{
                    $current_sub_product['sub_product_images'] = null;
                  }

                  $sub_product_array[] = $current_sub_product;
                }

                $current_product['sub_products'] = $current_sub_product;
              }
              else{
                $current_product['sub_products'] = null;
              }

              $product_array[] = $current_product;
            }
            return $product_array;
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

      function get_all_new_arrival_products_for_users(){

        try {
          $this->conn->beginTransaction();

          $active = $this->functions->active;

          $product_array = array();

          $sql = "SELECT products.unique_id, products.name, products.stripped, products.brand_unique_id, products.favorites, brands.name as brand_name, brands.stripped as brand_name_stripped FROM products
          LEFT JOIN brands ON products.brand_unique_id = brands.unique_id WHERE products.status=:status ORDER BY products.added_date DESC LIMIT 8";
          $query = $this->conn->prepare($sql);
          $query->bindParam(":status", $active);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_product = array();
              $current_product['unique_id'] = $value['unique_id'];
              $current_product['name'] = $value['name'];
              $current_product['stripped'] = $value['stripped'];
              $current_product['brand_unique_id'] = $value['brand_unique_id'];
              $current_product['favorites'] = $value['favorites'];
              $current_product['brand_name'] = $value['brand_name'];
              $current_product['brand_name_stripped'] = $value['brand_name_stripped'];

              $product_id = $value['unique_id'];

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

              $product_array[] = $current_product;
            }
            return $product_array;
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

      function get_all_featured_products_for_users(){

        try {
          $this->conn->beginTransaction();

          $active = $this->functions->active;

          $product_array = array();

          $sql = "SELECT products.unique_id, products.name, products.stripped, products.brand_unique_id, products.favorites, brands.name as brand_name, brands.stripped as brand_name_stripped FROM products
          LEFT JOIN brands ON products.brand_unique_id = brands.unique_id WHERE products.status=:status ORDER BY products.added_date DESC, products.favorites DESC LIMIT 8";
          $query = $this->conn->prepare($sql);
          $query->bindParam(":status", $active);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_product = array();
              $current_product['unique_id'] = $value['unique_id'];
              $current_product['name'] = $value['name'];
              $current_product['stripped'] = $value['stripped'];
              $current_product['brand_unique_id'] = $value['brand_unique_id'];
              $current_product['favorites'] = $value['favorites'];
              $current_product['brand_name'] = $value['brand_name'];
              $current_product['brand_name_stripped'] = $value['brand_name_stripped'];

              $product_id = $value['unique_id'];

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

              $product_array[] = $current_product;
            }
            return $product_array;
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

      function get_category_page_for_users($category_unique_id, $stripped){
        if (!in_array($category_unique_id,$this->not_allowed_values) || !in_array($stripped,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;

            $category_array = array();

            $sqlCategory = "SELECT categories.unique_id, categories.name, categories.stripped FROM categories WHERE categories.unique_id=:unique_id OR categories.stripped=:stripped AND categories.status=:status ORDER BY categories.added_date DESC";
            $queryCategory = $this->conn->prepare($sqlCategory);
            $queryCategory->bindParam(":unique_id", $category_unique_id);
            $queryCategory->bindParam(":stripped", $stripped);
            $queryCategory->bindParam(":status", $active);
            $queryCategory->execute();

            $resultCategory = $queryCategory->fetch();

            if ($queryCategory->rowCount() > 0) {
              $valueCategory = $resultCategory;
              $category_array['unique_id'] = $valueCategory['unique_id'];
              $category_array['name'] = $valueCategory['name'];
              $category_array['stripped'] = $valueCategory['stripped'];

              $category_unique_id = $category_unique_id == null ? $category_array['unique_id'] : $category_unique_id;

              $sub_category_array = array();

              $sqlSubCate = "SELECT sub_category.unique_id, sub_category.name, sub_category.stripped FROM sub_category WHERE sub_category.category_unique_id=:category_unique_id AND sub_category.status=:status ORDER BY sub_category.added_date DESC";
              $querySubCate = $this->conn->prepare($sqlSubCate);
              $querySubCate->bindParam(":category_unique_id", $category_unique_id);
              $querySubCate->bindParam(":status", $active);
              $querySubCate->execute();

              $resultSubCate = $querySubCate->fetchAll();

              if ($querySubCate->rowCount() > 0) {
                foreach ($resultSubCate as $key => $valueSubCate) {

                  $current_sub_category = array();
                  $current_sub_category['unique_id'] = $valueSubCate['unique_id'];
                  $current_sub_category['name'] = $valueSubCate['name'];
                  $current_sub_category['stripped'] = $valueSubCate['stripped'];

                  $sub_category_unique_id = $valueSubCate['unique_id'];

                  $sqlSubCateImages = "SELECT image FROM sub_category_images WHERE sub_category_unique_id=:sub_category_unique_id LIMIT 1";
                  $querySubCateImages = $this->conn->prepare($sqlSubCateImages);
                  $querySubCateImages->bindParam(":sub_category_unique_id", $sub_category_unique_id);
                  $querySubCateImages->execute();

                  $sub_cate_images_result = $querySubCateImages->fetchAll();

                  if ($querySubCateImages->rowCount() > 0) {
                    $current_sub_category_img = array();

                    foreach ($sub_cate_images_result as $key => $image_value) {
                      $current_sub_category_img[] = $image_value['image'];
                    }

                    $current_sub_category['sub_category_images'] = $current_sub_category_img;
                  }
                  else{
                    $current_sub_category['sub_category_images'] = null;
                  }

                  $sub_category_array[] = $current_sub_category;

                  $category_array['sub_category'] = $sub_category_array;

                  $mini_category_array = array();

                  $sqlMiniCate = "SELECT mini_category.unique_id, mini_category.name, mini_category.stripped FROM mini_category WHERE mini_category.category_unique_id=:category_unique_id AND mini_category.status=:status ORDER BY mini_category.added_date DESC";
                  $queryMiniCate = $this->conn->prepare($sqlMiniCate);
                  $queryMiniCate->bindParam(":category_unique_id", $category_unique_id);
                  $queryMiniCate->bindParam(":status", $active);
                  $queryMiniCate->execute();

                  $resultMiniCate = $queryMiniCate->fetchAll();

                  if ($queryMiniCate->rowCount() > 0) {
                    foreach ($resultMiniCate as $key => $valueMiniCate) {

                      $current_mini_category = array();
                      $current_mini_category['unique_id'] = $valueMiniCate['unique_id'];
                      $current_mini_category['name'] = $valueMiniCate['name'];
                      $current_mini_category['stripped'] = $valueMiniCate['stripped'];

                      $mini_category_unique_id = $valueMiniCate['unique_id'];

                      $sqlMiniCateImages = "SELECT image FROM mini_category_images WHERE mini_category_unique_id=:mini_category_unique_id LIMIT 1";
                      $queryMiniCateImages = $this->conn->prepare($sqlMiniCateImages);
                      $queryMiniCateImages->bindParam(":mini_category_unique_id", $mini_category_unique_id);
                      $queryMiniCateImages->execute();

                      $mini_cate_images_result = $queryMiniCateImages->fetchAll();

                      if ($queryMiniCateImages->rowCount() > 0) {
                        $current_mini_category_img = array();

                        foreach ($mini_cate_images_result as $key => $image_value) {
                          $current_mini_category_img[] = $image_value['image'];
                        }

                        $current_mini_category['mini_category_images'] = $current_mini_category_img;
                      }
                      else{
                        $current_mini_category['mini_category_images'] = null;
                      }

                      $mini_category_array[] = $current_mini_category;

                      $category_array['mini_category'] = $mini_category_array;

                      $product_array = array();

                      $sql = "SELECT products.unique_id, products.name, products.stripped, products.brand_unique_id, products.favorites, brands.name as brand_name, brands.stripped as brand_name_stripped FROM products
                      LEFT JOIN brands ON products.brand_unique_id = brands.unique_id WHERE products.category_unique_id=:category_unique_id AND products.status=:status ORDER BY products.added_date DESC, products.favorites DESC";
                      $query = $this->conn->prepare($sql);
                      $query->bindParam(":category_unique_id", $category_unique_id);
                      $query->bindParam(":status", $active);
                      $query->execute();

                      $result = $query->fetchAll();

                      if ($query->rowCount() > 0) {
                        foreach ($result as $key => $value) {

                          $current_product = array();
                          $current_product['unique_id'] = $value['unique_id'];
                          $current_product['name'] = $value['name'];
                          $current_product['stripped'] = $value['stripped'];
                          $current_product['brand_unique_id'] = $value['brand_unique_id'];
                          $current_product['favorites'] = $value['favorites'];
                          $current_product['brand_name'] = $value['brand_name'];
                          $current_product['brand_name_stripped'] = $value['brand_name_stripped'];

                          $product_id = $value['unique_id'];

                          $sql2 = "SELECT image FROM product_images WHERE product_unique_id=:product_unique_id LIMIT 1";
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

                          $product_array[] = $current_product;

                          $category_array['products'] = $product_array;
                        }

                      }
                      else {
                        $product_array[] = null;
                        $category_array['products'] = $product_array;
                      }

                    }
                  }
                  else {
                    $mini_category_array[] = null;
                    $category_array['mini_category'] = $mini_category_array;

                    $product_array = array();

                    $sql = "SELECT products.unique_id, products.name, products.stripped, products.brand_unique_id, products.favorites, brands.name as brand_name, brands.stripped as brand_name_stripped FROM products
                    LEFT JOIN brands ON products.brand_unique_id = brands.unique_id WHERE products.category_unique_id=:category_unique_id AND products.status=:status ORDER BY products.added_date DESC, products.favorites DESC";
                    $query = $this->conn->prepare($sql);
                    $query->bindParam(":category_unique_id", $category_unique_id);
                    $query->bindParam(":status", $active);
                    $query->execute();

                    $result = $query->fetchAll();

                    if ($query->rowCount() > 0) {
                      foreach ($result as $key => $value) {

                        $current_product = array();
                        $current_product['unique_id'] = $value['unique_id'];
                        $current_product['name'] = $value['name'];
                        $current_product['stripped'] = $value['stripped'];
                        $current_product['brand_unique_id'] = $value['brand_unique_id'];
                        $current_product['favorites'] = $value['favorites'];
                        $current_product['brand_name'] = $value['brand_name'];
                        $current_product['brand_name_stripped'] = $value['brand_name_stripped'];

                        $product_id = $value['unique_id'];

                        $sql2 = "SELECT image FROM product_images WHERE product_unique_id=:product_unique_id LIMIT 1";
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

                        $product_array[] = $current_product;

                        $category_array['products'] = $product_array;
                      }

                    }
                    else {
                      $product_array[] = null;
                      $category_array['products'] = $product_array;
                    }
                  }

                }
              }
              else {
                $sub_category_array[] = null;
                $category_array['sub_category'] = $sub_category_array;

                $mini_category_array = array();

                $sqlMiniCate = "SELECT mini_category.unique_id, mini_category.name, mini_category.stripped FROM mini_category WHERE mini_category.category_unique_id=:category_unique_id AND mini_category.status=:status ORDER BY mini_category.added_date DESC";
                $queryMiniCate = $this->conn->prepare($sqlMiniCate);
                $queryMiniCate->bindParam(":category_unique_id", $category_unique_id);
                $queryMiniCate->bindParam(":status", $active);
                $queryMiniCate->execute();

                $resultMiniCate = $queryMiniCate->fetchAll();

                if ($queryMiniCate->rowCount() > 0) {
                  foreach ($resultMiniCate as $key => $valueMiniCate) {

                    $current_mini_category = array();
                    $current_mini_category['unique_id'] = $valueMiniCate['unique_id'];
                    $current_mini_category['name'] = $valueMiniCate['name'];
                    $current_mini_category['stripped'] = $valueMiniCate['stripped'];

                    $mini_category_unique_id = $valueMiniCate['unique_id'];

                    $sqlMiniCateImages = "SELECT image FROM mini_category_images WHERE mini_category_unique_id=:mini_category_unique_id LIMIT 1";
                    $queryMiniCateImages = $this->conn->prepare($sqlMiniCateImages);
                    $queryMiniCateImages->bindParam(":mini_category_unique_id", $mini_category_unique_id);
                    $queryMiniCateImages->execute();

                    $mini_cate_images_result = $queryMiniCateImages->fetchAll();

                    if ($queryMiniCateImages->rowCount() > 0) {
                      $current_mini_category_img = array();

                      foreach ($mini_cate_images_result as $key => $image_value) {
                        $current_mini_category_img[] = $image_value['image'];
                      }

                      $current_mini_category['mini_category_images'] = $current_mini_category_img;
                    }
                    else{
                      $current_mini_category['mini_category_images'] = null;
                    }

                    $mini_category_array[] = $current_mini_category;

                    $category_array['mini_category'] = $mini_category_array;

                    $product_array = array();

                    $sql = "SELECT products.unique_id, products.name, products.stripped, products.brand_unique_id, products.favorites, brands.name as brand_name, brands.stripped as brand_name_stripped FROM products
                    LEFT JOIN brands ON products.brand_unique_id = brands.unique_id WHERE products.category_unique_id=:category_unique_id AND products.status=:status ORDER BY products.added_date DESC, products.favorites DESC";
                    $query = $this->conn->prepare($sql);
                    $query->bindParam(":category_unique_id", $category_unique_id);
                    $query->bindParam(":status", $active);
                    $query->execute();

                    $result = $query->fetchAll();

                    if ($query->rowCount() > 0) {
                      foreach ($result as $key => $value) {

                        $current_product = array();
                        $current_product['unique_id'] = $value['unique_id'];
                        $current_product['name'] = $value['name'];
                        $current_product['stripped'] = $value['stripped'];
                        $current_product['brand_unique_id'] = $value['brand_unique_id'];
                        $current_product['favorites'] = $value['favorites'];
                        $current_product['brand_name'] = $value['brand_name'];
                        $current_product['brand_name_stripped'] = $value['brand_name_stripped'];

                        $product_id = $value['unique_id'];

                        $sql2 = "SELECT image FROM product_images WHERE product_unique_id=:product_unique_id LIMIT 1";
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

                        $product_array[] = $current_product;

                        $category_array['products'] = $product_array;
                      }

                    }
                    else {
                      $product_array[] = null;
                      $category_array['products'] = $product_array;
                    }

                  }
                }
                else {
                  $mini_category_array[] = null;
                  $category_array['mini_category'] = $mini_category_array;

                  $product_array = array();

                  $sql = "SELECT products.unique_id, products.name, products.stripped, products.brand_unique_id, products.favorites, brands.name as brand_name, brands.stripped as brand_name_stripped FROM products
                  LEFT JOIN brands ON products.brand_unique_id = brands.unique_id WHERE products.category_unique_id=:category_unique_id AND products.status=:status ORDER BY products.added_date DESC, products.favorites DESC";
                  $query = $this->conn->prepare($sql);
                  $query->bindParam(":category_unique_id", $category_unique_id);
                  $query->bindParam(":status", $active);
                  $query->execute();

                  $result = $query->fetchAll();

                  if ($query->rowCount() > 0) {
                    foreach ($result as $key => $value) {

                      $current_product = array();
                      $current_product['unique_id'] = $value['unique_id'];
                      $current_product['name'] = $value['name'];
                      $current_product['stripped'] = $value['stripped'];
                      $current_product['brand_unique_id'] = $value['brand_unique_id'];
                      $current_product['favorites'] = $value['favorites'];
                      $current_product['brand_name'] = $value['brand_name'];
                      $current_product['brand_name_stripped'] = $value['brand_name_stripped'];

                      $product_id = $value['unique_id'];

                      $sql2 = "SELECT image FROM product_images WHERE product_unique_id=:product_unique_id LIMIT 1";
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

                      $product_array[] = $current_product;

                      $category_array['products'] = $product_array;
                    }

                  }
                  else {
                    $product_array[] = null;
                    $category_array['products'] = $product_array;
                  }

                }
              }

              return $category_array;
            }
            else {
              $category_array[] = null;
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
          $output['message'] = "At least one value is required";
          return $output;
        }

      }

      function get_sub_category_page_for_users($sub_category_unique_id, $stripped){
        if (!in_array($sub_category_unique_id,$this->not_allowed_values) || !in_array($stripped,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;

            $sub_category_array = array();

            $sqlSubCategory = "SELECT sub_category.unique_id, sub_category.name, sub_category.stripped FROM sub_category WHERE sub_category.unique_id=:unique_id OR sub_category.stripped=:stripped AND sub_category.status=:status ORDER BY sub_category.added_date DESC";
            $querySubCategory = $this->conn->prepare($sqlSubCategory);
            $querySubCategory->bindParam(":unique_id", $sub_category_unique_id);
            $querySubCategory->bindParam(":stripped", $stripped);
            $querySubCategory->bindParam(":status", $active);
            $querySubCategory->execute();

            $resultSubCategory = $querySubCategory->fetch();

            if ($querySubCategory->rowCount() > 0) {
              $valueSubCategory = $resultSubCategory;
              $sub_category_array['unique_id'] = $valueSubCategory['unique_id'];
              $sub_category_array['name'] = $valueSubCategory['name'];
              $sub_category_array['stripped'] = $valueSubCategory['stripped'];

              $sub_category_unique_id = $sub_category_unique_id == null ? $sub_category_array['unique_id'] : $sub_category_unique_id;

              $mini_category_array = array();

              $sqlMiniCate = "SELECT mini_category.unique_id, mini_category.name, mini_category.stripped FROM mini_category WHERE mini_category.sub_category_unique_id=:sub_category_unique_id AND mini_category.status=:status ORDER BY mini_category.added_date DESC";
              $queryMiniCate = $this->conn->prepare($sqlMiniCate);
              $queryMiniCate->bindParam(":sub_category_unique_id", $sub_category_unique_id);
              $queryMiniCate->bindParam(":status", $active);
              $queryMiniCate->execute();

              $resultMiniCate = $queryMiniCate->fetchAll();

              if ($queryMiniCate->rowCount() > 0) {
                foreach ($resultMiniCate as $key => $valueMiniCate) {

                  $current_mini_category = array();
                  $current_mini_category['unique_id'] = $valueMiniCate['unique_id'];
                  $current_mini_category['name'] = $valueMiniCate['name'];
                  $current_mini_category['stripped'] = $valueMiniCate['stripped'];

                  $mini_category_unique_id = $valueMiniCate['unique_id'];

                  $sqlMiniCateImages = "SELECT image FROM mini_category_images WHERE mini_category_unique_id=:mini_category_unique_id LIMIT 1";
                  $queryMiniCateImages = $this->conn->prepare($sqlMiniCateImages);
                  $queryMiniCateImages->bindParam(":mini_category_unique_id", $mini_category_unique_id);
                  $queryMiniCateImages->execute();

                  $mini_cate_images_result = $queryMiniCateImages->fetchAll();

                  if ($queryMiniCateImages->rowCount() > 0) {
                    $current_mini_category_img = array();

                    foreach ($mini_cate_images_result as $key => $image_value) {
                      $current_mini_category_img[] = $image_value['image'];
                    }

                    $current_mini_category['mini_category_images'] = $current_mini_category_img;
                  }
                  else{
                    $current_mini_category['mini_category_images'] = null;
                  }

                  $mini_category_array[] = $current_mini_category;

                  $sub_category_array['mini_category'] = $mini_category_array;

                  $product_array = array();

                  $sql = "SELECT products.unique_id, products.name, products.stripped, products.brand_unique_id, products.favorites, brands.name as brand_name, brands.stripped as brand_name_stripped FROM products
                  LEFT JOIN brands ON products.brand_unique_id = brands.unique_id WHERE products.sub_category_unique_id=:sub_category_unique_id AND products.status=:status ORDER BY products.added_date DESC, products.favorites DESC";
                  $query = $this->conn->prepare($sql);
                  $query->bindParam(":sub_category_unique_id", $sub_category_unique_id);
                  $query->bindParam(":status", $active);
                  $query->execute();

                  $result = $query->fetchAll();

                  if ($query->rowCount() > 0) {
                    foreach ($result as $key => $value) {

                      $current_product = array();
                      $current_product['unique_id'] = $value['unique_id'];
                      $current_product['name'] = $value['name'];
                      $current_product['stripped'] = $value['stripped'];
                      $current_product['brand_unique_id'] = $value['brand_unique_id'];
                      $current_product['favorites'] = $value['favorites'];
                      $current_product['brand_name'] = $value['brand_name'];
                      $current_product['brand_name_stripped'] = $value['brand_name_stripped'];

                      $product_id = $value['unique_id'];

                      $sql2 = "SELECT image FROM product_images WHERE product_unique_id=:product_unique_id LIMIT 1";
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

                      $product_array[] = $current_product;

                      $sub_category_array['products'] = $product_array;
                    }

                  }
                  else {
                    $product_array[] = null;
                    $sub_category_array['products'] = $product_array;
                  }

                }
              }
              else {
                $mini_category_array[] = null;
                $sub_category_array['mini_category'] = $mini_category_array;

                $product_array = array();

                $sql = "SELECT products.unique_id, products.name, products.stripped, products.brand_unique_id, products.favorites, brands.name as brand_name, brands.stripped as brand_name_stripped FROM products
                LEFT JOIN brands ON products.brand_unique_id = brands.unique_id WHERE products.sub_category_unique_id=:sub_category_unique_id AND products.status=:status ORDER BY products.added_date DESC, products.favorites DESC";
                $query = $this->conn->prepare($sql);
                $query->bindParam(":sub_category_unique_id", $sub_category_unique_id);
                $query->bindParam(":status", $active);
                $query->execute();

                $result = $query->fetchAll();

                if ($query->rowCount() > 0) {
                  foreach ($result as $key => $value) {

                    $current_product = array();
                    $current_product['unique_id'] = $value['unique_id'];
                    $current_product['name'] = $value['name'];
                    $current_product['stripped'] = $value['stripped'];
                    $current_product['brand_unique_id'] = $value['brand_unique_id'];
                    $current_product['favorites'] = $value['favorites'];
                    $current_product['brand_name'] = $value['brand_name'];
                    $current_product['brand_name_stripped'] = $value['brand_name_stripped'];

                    $product_id = $value['unique_id'];

                    $sql2 = "SELECT image FROM product_images WHERE product_unique_id=:product_unique_id LIMIT 1";
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

                    $product_array[] = $current_product;

                    $sub_category_array['products'] = $product_array;
                  }

                }
                else {
                  $product_array[] = null;
                  $sub_category_array['products'] = $product_array;
                }
              }

              return $sub_category_array;
            }
            else {
              $sub_category_array[] = null;
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
          $output['message'] = "At least one value is required";
          return $output;
        }

      }

      function get_mini_category_page_for_users($mini_category_unique_id, $stripped){
        if (!in_array($mini_category_unique_id,$this->not_allowed_values) || !in_array($stripped,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;

            $mini_category_array = array();

            $sqlMiniCategory = "SELECT mini_category.unique_id, mini_category.name, mini_category.stripped FROM mini_category WHERE mini_category.unique_id=:unique_id OR mini_category.stripped=:stripped AND mini_category.status=:status ORDER BY mini_category.added_date DESC";
            $queryMiniCategory = $this->conn->prepare($sqlMiniCategory);
            $queryMiniCategory->bindParam(":unique_id", $mini_category_unique_id);
            $queryMiniCategory->bindParam(":stripped", $stripped);
            $queryMiniCategory->bindParam(":status", $active);
            $queryMiniCategory->execute();

            $resultMiniCategory = $queryMiniCategory->fetch();

            if ($queryMiniCategory->rowCount() > 0) {
              $valueMiniCategory = $resultMiniCategory;
              $mini_category_array['unique_id'] = $valueMiniCategory['unique_id'];
              $mini_category_array['name'] = $valueMiniCategory['name'];
              $mini_category_array['stripped'] = $valueMiniCategory['stripped'];

              $mini_category_unique_id = $mini_category_unique_id == null ? $mini_category_array['unique_id'] : $mini_category_unique_id;

              $product_array = array();

              $sql = "SELECT products.unique_id, products.name, products.stripped, products.brand_unique_id, products.favorites, brands.name as brand_name, brands.stripped as brand_name_stripped FROM products
              LEFT JOIN brands ON products.brand_unique_id = brands.unique_id WHERE products.mini_category_unique_id=:mini_category_unique_id AND products.status=:status ORDER BY products.added_date DESC, products.favorites DESC";
              $query = $this->conn->prepare($sql);
              $query->bindParam(":mini_category_unique_id", $mini_category_unique_id);
              $query->bindParam(":status", $active);
              $query->execute();

              $result = $query->fetchAll();

              if ($query->rowCount() > 0) {
                foreach ($result as $key => $value) {

                  $current_product = array();
                  $current_product['unique_id'] = $value['unique_id'];
                  $current_product['name'] = $value['name'];
                  $current_product['stripped'] = $value['stripped'];
                  $current_product['brand_unique_id'] = $value['brand_unique_id'];
                  $current_product['favorites'] = $value['favorites'];
                  $current_product['brand_name'] = $value['brand_name'];
                  $current_product['brand_name_stripped'] = $value['brand_name_stripped'];

                  $product_id = $value['unique_id'];

                  $sql2 = "SELECT image FROM product_images WHERE product_unique_id=:product_unique_id LIMIT 1";
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

                  $product_array[] = $current_product;

                  $mini_category_array['products'] = $product_array;
                }

              }
              else {
                $product_array[] = null;
                $mini_category_array['products'] = $product_array;
              }

              return $mini_category_array;
            }
            else {
              $mini_category_array[] = null;
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
          $output['message'] = "At least one value is required";
          return $output;
        }

      }

      function get_brand_page_for_users(){
        try {
          $this->conn->beginTransaction();

          $active = $this->functions->active;

          $brand_array = array();

          $sql = "SELECT brands.id, brands.unique_id, brands.name, brands.stripped, brands.details FROM brands WHERE brands.status=:status ORDER BY brands.added_date DESC";
          $query = $this->conn->prepare($sql);
          $query->bindParam(":status", $active);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_brand = array();
              $current_brand['unique_id'] = $value['unique_id'];
              $current_brand['name'] = $value['name'];
              $current_brand['stripped'] = $value['stripped'];
              $current_brand['details'] = $value['details'];

              $brand_id = $value['unique_id'];

              $sql2 = "SELECT image FROM brand_images WHERE brand_unique_id=:brand_unique_id LIMIT 1";
              $query2 = $this->conn->prepare($sql2);
              $query2->bindParam(":brand_unique_id", $brand_id);
              $query2->execute();

              $images_result = $query2->fetchAll();

              if ($query2->rowCount() > 0) {
                $current_brand_images = array();

                foreach ($images_result as $key => $image_value) {
                  $current_brand_images[] = $image_value['image'];
                }

                $current_brand['brand_images'] = $current_brand_images;
              }
              else{
                $current_brand['brand_images'] = null;
              }

              $brand_array[] = $current_brand;

            }
            return $brand_array;
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

      function get_category_short_detail_of_products_for_users($category_unique_id){
        if (!in_array($category_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;

            $product_array = array();

            $sql = "SELECT products.unique_id, products.name, products.stripped, products.brand_unique_id, products.favorites, brands.name as brand_name, brands.stripped as brand_name_stripped FROM products
            LEFT JOIN brands ON products.brand_unique_id = brands.unique_id WHERE products.category_unique_id=:category_unique_id AND products.status=:status ORDER BY products.added_date DESC, products.favorites DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":category_unique_id", $category_unique_id);
            $query->bindParam(":status", $active);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_product = array();
                $current_product['unique_id'] = $value['unique_id'];
                $current_product['name'] = $value['name'];
                $current_product['stripped'] = $value['stripped'];
                $current_product['brand_unique_id'] = $value['brand_unique_id'];
                $current_product['favorites'] = $value['favorites'];
                $current_product['brand_name'] = $value['brand_name'];
                $current_product['brand_name_stripped'] = $value['brand_name_stripped'];

                $product_id = $value['unique_id'];

                $sql2 = "SELECT image FROM product_images WHERE product_unique_id=:product_unique_id LIMIT 1";
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

                $product_array[] = $current_product;
              }
              return $product_array;
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

      function get_sub_category_short_detail_of_products_for_users($sub_category_unique_id){
        if (!in_array($sub_category_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;

            $product_array = array();

            $sql = "SELECT products.unique_id, products.name, products.stripped, products.brand_unique_id, products.favorites, brands.name as brand_name, brands.stripped as brand_name_stripped FROM products
            LEFT JOIN brands ON products.brand_unique_id = brands.unique_id WHERE products.sub_category_unique_id=:sub_category_unique_id AND products.status=:status ORDER BY products.added_date DESC, products.favorites DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":sub_category_unique_id", $sub_category_unique_id);
            $query->bindParam(":status", $active);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_product = array();
                $current_product['unique_id'] = $value['unique_id'];
                $current_product['name'] = $value['name'];
                $current_product['stripped'] = $value['stripped'];
                $current_product['brand_unique_id'] = $value['brand_unique_id'];
                $current_product['favorites'] = $value['favorites'];
                $current_product['brand_name'] = $value['brand_name'];
                $current_product['brand_name_stripped'] = $value['brand_name_stripped'];

                $product_id = $value['unique_id'];

                $sql2 = "SELECT image FROM product_images WHERE product_unique_id=:product_unique_id LIMIT 1";
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

                $product_array[] = $current_product;
              }
              return $product_array;
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

      function get_mini_category_short_detail_of_products_for_users($mini_category_unique_id){
        if (!in_array($mini_category_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;

            $product_array = array();

            $sql = "SELECT products.unique_id, products.name, products.stripped, products.brand_unique_id, products.favorites, brands.name as brand_name, brands.stripped as brand_name_stripped FROM products
            LEFT JOIN brands ON products.brand_unique_id = brands.unique_id WHERE products.mini_category_unique_id=:mini_category_unique_id AND products.status=:status ORDER BY products.added_date DESC, products.favorites DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":mini_category_unique_id", $mini_category_unique_id);
            $query->bindParam(":status", $active);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_product = array();
                $current_product['unique_id'] = $value['unique_id'];
                $current_product['name'] = $value['name'];
                $current_product['stripped'] = $value['stripped'];
                $current_product['brand_unique_id'] = $value['brand_unique_id'];
                $current_product['favorites'] = $value['favorites'];
                $current_product['brand_name'] = $value['brand_name'];
                $current_product['brand_name_stripped'] = $value['brand_name_stripped'];

                $product_id = $value['unique_id'];

                $sql2 = "SELECT image FROM product_images WHERE product_unique_id=:product_unique_id LIMIT 1";
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

                $product_array[] = $current_product;
              }
              return $product_array;
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

      function get_brand_short_detail_of_products_for_users($brand_unique_id){
        if (!in_array($brand_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;

            $product_array = array();

            $sql = "SELECT products.unique_id, products.name, products.stripped, products.brand_unique_id, products.favorites, brands.name as brand_name, brands.stripped as brand_name_stripped FROM products
            LEFT JOIN brands ON products.brand_unique_id = brands.unique_id WHERE products.brand_unique_id=:brand_unique_id AND products.status=:status ORDER BY products.added_date DESC, products.favorites DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":brand_unique_id", $brand_unique_id);
            $query->bindParam(":status", $active);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_product = array();
                $current_product['unique_id'] = $value['unique_id'];
                $current_product['name'] = $value['name'];
                $current_product['stripped'] = $value['stripped'];
                $current_product['brand_unique_id'] = $value['brand_unique_id'];
                $current_product['favorites'] = $value['favorites'];
                $current_product['brand_name'] = $value['brand_name'];
                $current_product['brand_name_stripped'] = $value['brand_name_stripped'];

                $product_id = $value['unique_id'];

                $sql2 = "SELECT image FROM product_images WHERE product_unique_id=:product_unique_id LIMIT 1";
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

                $product_array[] = $current_product;
              }
              return $product_array;
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

      function get_recent_search_short_detail_of_products_for_users(){

        try {
          $this->conn->beginTransaction();

          $search_history_array = array();

          $sql = "SELECT search_history.id, search_history.unique_id, search_history.user_unique_id, search_history.search, search_history.added_date, search_history.last_modified, search_history.status
          FROM search_history ORDER BY CASE WHEN search_history.user_unique_id != 'Anonymous' THEN 0 ELSE 1 END, search_history.added_date DESC LIMIT 50";
          $query = $this->conn->prepare($sql);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_search_history_id = $value['id'];
              $current_search_history_unique_id = $value['unique_id'];
              $current_search_history_user_unique_id = $value['user_unique_id'];
              $current_search_history_added_date = $value['added_date'];
              $current_search_history_last_modified = $value['last_modified'];

              $search_word = $value['search'];

              $keyword = "%".$search_word."%";

              $sql3 = "SELECT products.unique_id, products.name, products.stripped, products.brand_unique_id, products.favorites,
              brands.name as brand_name, brands.stripped as brand_name_stripped FROM products LEFT JOIN brands ON products.brand_unique_id = brands.unique_id LEFT JOIN categories ON products.category_unique_id = categories.unique_id LEFT JOIN sub_category ON products.sub_category_unique_id = sub_category.unique_id
              LEFT JOIN mini_category ON products.mini_category_unique_id = mini_category.unique_id WHERE products.name LIKE :search_word OR sub_products.name LIKE :search_word OR sub_products.size LIKE :search_word OR mini_category.name LIKE :search_word
              OR sub_category.name LIKE :search_word OR categories.name LIKE :search_word OR brands.name LIKE :search_word ORDER BY products.added_date ASC, products.favorites DESC LIMIT 5";
              $query3 = $this->conn->prepare($sql3);
              $query3->bindParam(":search_word", $keyword);
              $query3->execute();

              $product_result = $query3->fetchAll();

              if ($query3->rowCount() > 0) {
                foreach ($product_result as $key => $product_value) {

                  $current_search_history = array();
                  $current_search_history['id'] = $current_search_history_id;
                  $current_search_history['unique_id'] = $current_search_history_unique_id;
                  $current_search_history['user_unique_id'] = $current_search_history_user_unique_id;
                  $current_search_history['product_unique_id'] = $product_value['unique_id'];
                  $current_search_history['added_date'] = $current_search_history_added_date;
                  $current_search_history['last_modified'] = $current_search_history_last_modified;
                  $current_search_history['name'] = $product_value['name'];
                  $current_search_history['stripped'] = $product_value['stripped'];
                  $current_search_history['brand_unique_id'] = $product_value['brand_unique_id'];
                  $current_search_history['favorites'] = $product_value['favorites'];
                  $current_search_history['brand_name'] = $product_value['brand_name'];
                  $current_search_history['brand_name_stripped'] = $product_value['brand_name_stripped'];

                  $product_id = $product_value['unique_id'];

                  $sql2 = "SELECT image FROM product_images WHERE product_unique_id=:product_unique_id LIMIT 1";
                  $query2 = $this->conn->prepare($sql2);
                  $query2->bindParam(":product_unique_id", $product_id);
                  $query2->execute();

                  $images_result = $query2->fetchAll();

                  if ($query2->rowCount() > 0) {
                    $current_search_history_images = array();

                    foreach ($images_result as $key => $image_value) {
                      $current_search_history_images[] = $image_value['image'];
                    }

                    $current_search_history['product_images'] = $current_search_history_images;
                  }
                  else{
                    $current_search_history['product_images'] = null;
                  }

                  $search_history_array[] = $current_search_history;
                }
              }
              else {
                $current_search_history = array();
                $current_search_history['id'] = $current_search_history_id;
                $current_search_history['unique_id'] = $current_search_history_unique_id;
                $current_search_history['user_unique_id'] = $current_search_history_user_unique_id;
                $current_search_history['product_unique_id'] = null;
                $current_search_history['added_date'] = $current_search_history_added_date;
                $current_search_history['last_modified'] = $current_search_history_last_modified;
                $current_search_history['name'] = null;
                $current_search_history['stripped'] = null;
                $current_search_history['brand_unique_id'] = null;
                $current_search_history['favorites'] = null;
                $current_search_history['brand_name'] = null;
                $current_search_history['brand_name_stripped'] = null;

                $current_search_history['product_images'] = null;

                $search_history_array[] = $current_search_history;
              }

            }
            return $search_history_array;
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

      function get_trending_short_detail_of_products_for_users(){

        try {
          $this->conn->beginTransaction();

          $search_history_array = array();

          $sql = "SELECT search_history.id, search_history.unique_id, search_history.user_unique_id, search_history.search, search_history.added_date, search_history.last_modified, search_history.status
          FROM search_history ORDER BY search_history.added_date DESC LIMIT 200";
          $query = $this->conn->prepare($sql);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_search_history_id = $value['id'];
              $current_search_history_unique_id = $value['unique_id'];
              $current_search_history_user_unique_id = $value['user_unique_id'];
              $current_search_history_added_date = $value['added_date'];
              $current_search_history_last_modified = $value['last_modified'];

              $search_word = $value['search'];

              $keyword = "%".$search_word."%";

              $sql3 = "SELECT products.unique_id, products.name, products.stripped, products.brand_unique_id, products.favorites,
              brands.name as brand_name, brands.stripped as brand_name_stripped FROM products LEFT JOIN brands ON products.brand_unique_id = brands.unique_id LEFT JOIN categories ON products.category_unique_id = categories.unique_id LEFT JOIN sub_category ON products.sub_category_unique_id = sub_category.unique_id
              LEFT JOIN mini_category ON products.mini_category_unique_id = mini_category.unique_id WHERE products.name LIKE :search_word OR sub_products.name LIKE :search_word OR sub_products.size LIKE :search_word ORDER BY products.added_date ASC, products.favorites DESC LIMIT 3";
              $query3 = $this->conn->prepare($sql3);
              $query3->bindParam(":search_word", $keyword);
              $query3->execute();

              $product_result = $query3->fetchAll();

              if ($query3->rowCount() > 0) {
                foreach ($product_result as $key => $product_value) {

                  $current_search_history = array();
                  $current_search_history['id'] = $current_search_history_id;
                  $current_search_history['unique_id'] = $current_search_history_unique_id;
                  $current_search_history['user_unique_id'] = $current_search_history_user_unique_id;
                  $current_search_history['product_unique_id'] = $product_value['unique_id'];
                  $current_search_history['added_date'] = $current_search_history_added_date;
                  $current_search_history['last_modified'] = $current_search_history_last_modified;
                  $current_search_history['name'] = $product_value['name'];
                  $current_search_history['stripped'] = $product_value['stripped'];
                  $current_search_history['brand_unique_id'] = $product_value['brand_unique_id'];
                  $current_search_history['favorites'] = $product_value['favorites'];
                  $current_search_history['brand_name'] = $product_value['brand_name'];
                  $current_search_history['brand_name_stripped'] = $product_value['brand_name_stripped'];

                  $product_id = $product_value['unique_id'];

                  $sql2 = "SELECT image FROM product_images WHERE product_unique_id=:product_unique_id LIMIT 1";
                  $query2 = $this->conn->prepare($sql2);
                  $query2->bindParam(":product_unique_id", $product_id);
                  $query2->execute();

                  $images_result = $query2->fetchAll();

                  if ($query2->rowCount() > 0) {
                    $current_search_history_images = array();

                    foreach ($images_result as $key => $image_value) {
                      $current_search_history_images[] = $image_value['image'];
                    }

                    $current_search_history['product_images'] = $current_search_history_images;
                  }
                  else{
                    $current_search_history['product_images'] = null;
                  }

                  $search_history_array[] = $current_search_history;
                }
              }
              else {
                $current_search_history = array();
                $current_search_history['id'] = $current_search_history_id;
                $current_search_history['unique_id'] = $current_search_history_unique_id;
                $current_search_history['user_unique_id'] = $current_search_history_user_unique_id;
                $current_search_history['product_unique_id'] = null;
                $current_search_history['added_date'] = $current_search_history_added_date;
                $current_search_history['last_modified'] = $current_search_history_last_modified;
                $current_search_history['name'] = null;
                $current_search_history['stripped'] = null;
                $current_search_history['brand_unique_id'] = null;
                $current_search_history['favorites'] = null;
                $current_search_history['brand_name'] = null;
                $current_search_history['brand_name_stripped'] = null;

                $current_search_history['product_images'] = null;

                $search_history_array[] = $current_search_history;
              }

            }
            return $search_history_array;
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

      function get_user_search_short_detail_of_products_for_users($search_word){
        if (!in_array($search_word,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $search_history_array = array();

            $keyword = "%".$search_word."%";

            $sql3 = "SELECT products.unique_id, products.name, products.stripped, products.brand_unique_id, products.favorites,
            brands.name as brand_name, brands.stripped as brand_name_stripped FROM products LEFT JOIN brands ON products.brand_unique_id = brands.unique_id LEFT JOIN categories ON products.category_unique_id = categories.unique_id LEFT JOIN sub_category ON products.sub_category_unique_id = sub_category.unique_id
            LEFT JOIN mini_category ON products.mini_category_unique_id = mini_category.unique_id WHERE products.name LIKE :search_word OR sub_products.name LIKE :search_word OR sub_products.size LIKE :search_word OR mini_category.name LIKE :search_word
            OR sub_category.name LIKE :search_word OR categories.name LIKE :search_word OR brands.name LIKE :search_word ORDER BY products.added_date ASC, products.favorites DESC";
            $query3 = $this->conn->prepare($sql3);
            $query3->bindParam(":search_word", $keyword);
            $query3->execute();

            $product_result = $query3->fetchAll();

            if ($query3->rowCount() > 0) {
              foreach ($product_result as $key => $product_value) {

                $current_search_history = array();
                $current_search_history['unique_id'] = $product_value['unique_id'];
                $current_search_history['name'] = $product_value['name'];
                $current_search_history['stripped'] = $product_value['stripped'];
                $current_search_history['brand_unique_id'] = $product_value['brand_unique_id'];
                $current_search_history['favorites'] = $product_value['favorites'];
                $current_search_history['brand_name'] = $product_value['brand_name'];
                $current_search_history['brand_name_stripped'] = $product_value['brand_name_stripped'];

                $product_id = $product_value['unique_id'];

                $sql2 = "SELECT image FROM product_images WHERE product_unique_id=:product_unique_id LIMIT 1";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":product_unique_id", $product_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_search_history_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_search_history_images[] = $image_value['image'];
                  }

                  $current_search_history['product_images'] = $current_search_history_images;
                }
                else{
                  $current_search_history['product_images'] = null;
                }

                $search_history_array[] = $current_search_history;
              }
              return $search_history_array;
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

      function get_view_history_short_detail_of_products_for_users(){
        try {
          $this->conn->beginTransaction();

          $view_history_array = array();

          $sql = "SELECT view_history.id, view_history.unique_id, view_history.user_unique_id, view_history.product_unique_id, view_history.added_date, view_history.last_modified, view_history.status,
          products.name, products.stripped, products.brand_unique_id, products.favorites, brands.name as brand_name, brands.stripped as brand_name_stripped
          FROM view_history LEFT JOIN products ON view_history.product_unique_id = products.unique_id LEFT JOIN brands ON products.brand_unique_id = brands.unique_id ORDER BY CASE WHEN view_history.user_unique_id != 'Anonymous' THEN 0 ELSE 1 END, view_history.added_date DESC";
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
              // $current_view_history['unique_id'] = $value['unique_id'];
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

      function get_all_products_for_users(){

        try {
          $this->conn->beginTransaction();

          $active = $this->functions->active;

          $product_array = array();

          $sql = "SELECT products.unique_id, products.mini_category_unique_id, products.sub_category_unique_id, products.category_unique_id, products.name, products.stripped, products.brand_unique_id,
          products.description, products.favorites,
          categories.name as category_name, categories.stripped as category_name_stripped, sub_category.name as sub_category_name, sub_category.stripped as sub_category_name_stripped, mini_category.name as mini_category_name,
          mini_category.stripped as mini_category_name_stripped, brands.name as brand_name, brands.stripped as brand_name_stripped FROM products
          LEFT JOIN categories ON products.category_unique_id = categories.unique_id LEFT JOIN sub_category ON products.sub_category_unique_id = sub_category.unique_id LEFT JOIN mini_category ON products.mini_category_unique_id = mini_category.unique_id LEFT JOIN brands ON products.brand_unique_id = brands.unique_id WHERE products.status=:status ORDER BY products.added_date ASC, products.favorites DESC";
          $query = $this->conn->prepare($sql);
          $query->bindParam(":status", $active);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_product = array();
              $current_product['unique_id'] = $value['unique_id'];
              $current_product['mini_category_unique_id'] = $value['mini_category_unique_id'];
              $current_product['sub_category_unique_id'] = $value['sub_category_unique_id'];
              $current_product['category_unique_id'] = $value['category_unique_id'];
              $current_product['name'] = $value['name'];
              $current_product['stripped'] = $value['stripped'];
              $current_product['brand_unique_id'] = $value['brand_unique_id'];
              $current_product['favorites'] = $value['favorites'];
              $current_product['category_name'] = $value['category_name'];
              $current_product['category_name_stripped'] = $value['category_name_stripped'];
              $current_product['sub_category_name'] = $value['sub_category_name'];
              $current_product['sub_category_name_stripped'] = $value['sub_category_name_stripped'];
              $current_product['mini_category_name'] = $value['mini_category_name'];
              $current_product['mini_category_name_stripped'] = $value['mini_category_name_stripped'];
              $current_product['brand_name'] = $value['brand_name'];
              $current_product['brand_name_stripped'] = $value['brand_name_stripped'];

              $product_id = $value['unique_id'];

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

              $product_array[] = $current_product;
            }
            return $product_array;
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

      function get_all_product_sub_products_for_users($product_unique_id){

        try {
          $this->conn->beginTransaction();

          $active = $this->functions->active;

          $sub_product_array = array();

          $sql = "SELECT sub_products.unique_id, sub_products.product_unique_id, sub_products.name, sub_products.size, sub_products.stripped, sub_products.description, sub_products.stock, sub_products.stock_remaining, sub_products.price, sub_products.sales_price, sub_products.favorites, products.mini_category_unique_id, products.sub_category_unique_id, products.category_unique_id,
          products.name as product_name, products.stripped as product_stripped, products.brand_unique_id, categories.name as category_name, categories.stripped as category_name_stripped, sub_category.name as sub_category_name, sub_category.stripped as sub_category_name_stripped, mini_category.name as mini_category_name,
          mini_category.stripped as mini_category_name_stripped, brands.name as brand_name, brands.stripped as brand_name_stripped FROM sub_products LEFT JOIN products ON sub_products.product_unique_id = products.unique_id LEFT JOIN categories ON products.category_unique_id = categories.unique_id LEFT JOIN sub_category ON products.sub_category_unique_id = sub_category.unique_id
          LEFT JOIN mini_category ON products.mini_category_unique_id = mini_category.unique_id LEFT JOIN brands ON products.brand_unique_id = brands.unique_id WHERE sub_products.product_unique_id=:product_unique_id AND sub_products.status=:status ORDER BY sub_products.name ASC, sub_products.added_date DESC";
          $query = $this->conn->prepare($sql);
          $query->bindParam(":product_unique_id", $product_unique_id);
          $query->bindParam(":status", $active);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_sub_product = array();
              $current_sub_product['unique_id'] = $value['unique_id'];
              $current_sub_product['product_unique_id'] = $value['product_unique_id'];
              $current_sub_product['name'] = $value['name'];
              $current_sub_product['size'] = $value['size'];
              $current_sub_product['stripped'] = $value['stripped'];
              $current_sub_product['description'] = $value['description'];
              $current_sub_product['stock'] = $value['stock'];
              $current_sub_product['stock_remaining'] = $value['stock_remaining'];
              $current_sub_product['price'] = $value['price'];
              $current_sub_product['sales_price'] = $value['sales_price'];
              $current_sub_product['favorites'] = $value['favorites'];
              $current_sub_product['mini_category_unique_id'] = $value['mini_category_unique_id'];
              $current_sub_product['sub_category_unique_id'] = $value['sub_category_unique_id'];
              $current_sub_product['category_unique_id'] = $value['category_unique_id'];
              $current_sub_product['product_name'] = $value['product_name'];
              $current_sub_product['product_stripped'] = $value['product_stripped'];
              $current_sub_product['brand_unique_id'] = $value['brand_unique_id'];
              $current_sub_product['category_name'] = $value['category_name'];
              $current_sub_product['category_name_stripped'] = $value['category_name_stripped'];
              $current_sub_product['sub_category_name'] = $value['sub_category_name'];
              $current_sub_product['sub_category_name_stripped'] = $value['sub_category_name_stripped'];
              $current_sub_product['mini_category_name'] = $value['mini_category_name'];
              $current_sub_product['mini_category_name_stripped'] = $value['mini_category_name_stripped'];
              $current_sub_product['brand_name'] = $value['brand_name'];
              $current_sub_product['brand_name_stripped'] = $value['brand_name_stripped'];

              $sub_product_id = $value['unique_id'];

              $sql2 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id";
              $query2 = $this->conn->prepare($sql2);
              $query2->bindParam(":sub_product_unique_id", $sub_product_id);
              $query2->execute();

              $images_result = $query2->fetchAll();

              if ($query2->rowCount() > 0) {
                $current_sub_product_images = array();

                foreach ($images_result as $key => $image_value) {
                  $current_sub_product_images[] = $image_value['image'];
                }

                $current_sub_product['sub_product_images'] = $current_sub_product_images;
              }
              else{
                $current_sub_product['sub_product_images'] = null;
              }

              $sub_product_array[] = $current_sub_product;
            }
            return $sub_product_array;
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

      function get_product_details_for_users($unique_id, $stripped){
        if (!in_array($unique_id,$this->not_allowed_values) || !in_array($stripped,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;

            $sql = "SELECT products.unique_id, products.mini_category_unique_id, products.sub_category_unique_id, products.category_unique_id, products.name, products.stripped, products.brand_unique_id,
            products.description, products.favorites, categories.name as category_name,
            categories.stripped as category_name_stripped, sub_category.name as sub_category_name, sub_category.stripped as sub_category_name_stripped, mini_category.name as mini_category_name, mini_category.stripped as mini_category_name_stripped,
            brands.name as brand_name, brands.stripped as brand_name_stripped FROM products LEFT JOIN categories ON products.category_unique_id = categories.unique_id LEFT JOIN sub_category ON products.sub_category_unique_id = sub_category.unique_id
            LEFT JOIN mini_category ON products.mini_category_unique_id = mini_category.unique_id LEFT JOIN brands ON products.brand_unique_id = brands.unique_id WHERE (products.unique_id=:unique_id OR products.stripped=:stripped) AND products.status=:status ORDER BY products.added_date DESC LIMIT 1";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $unique_id);
            $query->bindParam(":stripped", $stripped);
            $query->bindParam(":status", $active);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_product = array();
                $current_product['unique_id'] = $value['unique_id'];
                $current_product['mini_category_unique_id'] = $value['mini_category_unique_id'];
                $current_product['sub_category_unique_id'] = $value['sub_category_unique_id'];
                $current_product['category_unique_id'] = $value['category_unique_id'];
                $current_product['name'] = $value['name'];
                $current_product['stripped'] = $value['stripped'];
                $current_product['brand_unique_id'] = $value['brand_unique_id'];
                $current_product['favorites'] = $value['favorites'];
                $current_product['category_name'] = $value['category_name'];
                $current_product['category_name_stripped'] = $value['category_name_stripped'];
                $current_product['sub_category_name'] = $value['sub_category_name'];
                $current_product['sub_category_name_stripped'] = $value['sub_category_name_stripped'];
                $current_product['mini_category_name'] = $value['mini_category_name'];
                $current_product['mini_category_name_stripped'] = $value['mini_category_name_stripped'];
                $current_product['brand_name'] = $value['brand_name'];
                $current_product['brand_name_stripped'] = $value['brand_name_stripped'];

                $product_id = $value['unique_id'];

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

                $sql3 = "SELECT sub_products.id, sub_products.unique_id, sub_products.user_unique_id, sub_products.edit_user_unique_id, sub_products.product_unique_id, sub_products.name, sub_products.size, sub_products.stripped, sub_products.description, sub_products.stock, sub_products.stock_remaining,
                sub_products.price, sub_products.sales_price, sub_products.favorites, products.mini_category_unique_id, products.sub_category_unique_id, products.category_unique_id, products.name as product_name, products.stripped as product_stripped, products.brand_unique_id, sub_products.added_date, sub_products.last_modified, sub_products.status,
                categories.name as category_name, categories.stripped as category_name_stripped, sub_category.name as sub_category_name, sub_category.stripped as sub_category_name_stripped, mini_category.name as mini_category_name,
                mini_category.stripped as mini_category_name_stripped, brands.name as brand_name, brands.stripped as brand_name_stripped FROM sub_products LEFT JOIN products ON sub_products.product_unique_id = products.unique_id LEFT JOIN categories ON products.category_unique_id = categories.unique_id
                LEFT JOIN sub_category ON products.sub_category_unique_id = sub_category.unique_id LEFT JOIN mini_category ON products.mini_category_unique_id = mini_category.unique_id LEFT JOIN brands ON products.brand_unique_id = brands.unique_id WHERE sub_products.product_unique_id=:product_unique_id ORDER BY sub_products.added_date DESC LIMIT 1";
                $query3 = $this->conn->prepare($sql3);
                $query3->bindParam(":product_unique_id", $product_id);
                $query3->execute();

                $sub_product_result = $query3->fetchAll();

                if ($query3->rowCount() > 0) {

                  $current_sub_product = array();

                  foreach ($sub_product_result as $key => $value) {

                    $current_sub_product['id'] = $value['id'];
                    $current_sub_product['unique_id'] = $value['unique_id'];
                    $current_sub_product['user_unique_id'] = $value['user_unique_id'];
                    $current_sub_product['edit_user_unique_id'] = $value['edit_user_unique_id'];
                    $current_sub_product['product_unique_id'] = $value['product_unique_id'];
                    $current_sub_product['name'] = $value['name'];
                    $current_sub_product['size'] = $value['size'];
                    $current_sub_product['stripped'] = $value['stripped'];
                    $current_sub_product['description'] = $value['description'];
                    $current_sub_product['stock'] = $value['stock'];
                    $current_sub_product['stock_remaining'] = $value['stock_remaining'];
                    $current_sub_product['price'] = $value['price'];
                    $current_sub_product['sales_price'] = $value['sales_price'];
                    $current_sub_product['favorites'] = $value['favorites'];
                    $current_sub_product['mini_category_unique_id'] = $value['mini_category_unique_id'];
                    $current_sub_product['sub_category_unique_id'] = $value['sub_category_unique_id'];
                    $current_sub_product['category_unique_id'] = $value['category_unique_id'];
                    $current_sub_product['product_name'] = $value['product_name'];
                    $current_sub_product['product_stripped'] = $value['product_stripped'];
                    $current_sub_product['brand_unique_id'] = $value['brand_unique_id'];
                    $current_sub_product['added_date'] = $value['added_date'];
                    $current_sub_product['last_modified'] = $value['last_modified'];
                    $current_sub_product['status'] = $value['status'];
                    $current_sub_product['added_fullname'] = $value['added_fullname'];
                    $current_sub_product['edit_user_fullname'] = $value['edit_user_fullname'];
                    $current_sub_product['category_name'] = $value['category_name'];
                    $current_sub_product['category_name_stripped'] = $value['category_name_stripped'];
                    $current_sub_product['sub_category_name'] = $value['sub_category_name'];
                    $current_sub_product['sub_category_name_stripped'] = $value['sub_category_name_stripped'];
                    $current_sub_product['mini_category_name'] = $value['mini_category_name'];
                    $current_sub_product['mini_category_name_stripped'] = $value['mini_category_name_stripped'];
                    $current_sub_product['brand_name'] = $value['brand_name'];
                    $current_sub_product['brand_name_stripped'] = $value['brand_name_stripped'];

                    $sub_product_id = $value['unique_id'];

                    $sql4 = "SELECT image FROM sub_product_images WHERE sub_product_unique_id=:sub_product_unique_id";
                    $query4 = $this->conn->prepare($sql4);
                    $query4->bindParam(":sub_product_unique_id", $sub_product_id);
                    $query4->execute();

                    $sub_product_images_result = $query4->fetchAll();

                    if ($query2->rowCount() > 0) {
                      $current_sub_product_images = array();

                      foreach ($sub_product_images_result as $key => $image_value) {
                        $current_sub_product_images[] = $image_value['image'];
                      }

                      $current_sub_product['sub_product_images'] = $current_sub_product_images;
                    }
                    else{
                      $current_sub_product['sub_product_images'] = null;
                    }

                    $sub_product_array[] = $current_sub_product;
                  }

                  $current_product['sub_products'] = $current_sub_product;
                }
                else{
                  $current_product['sub_products'] = null;
                }

                $product_array[] = $current_product;
              }
              return $product_array;
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
          $output['message'] = "At least one value is required";
          return $output;
        }

      }

      function get_favorite_product_details_for_users($user_unique_id, $product_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values) && !in_array($product_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;

            $sql = "SELECT unique_id FROM favorites WHERE user_unique_id=:user_unique_id AND product_unique_id=:product_unique_id AND status=:status";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->bindParam(":product_unique_id", $product_unique_id);
            $query->bindParam(":status", $active);
            $query->execute();

            $result = $query->fetch();

            if ($query->rowCount() > 0) {
              return json_decode("true");
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
