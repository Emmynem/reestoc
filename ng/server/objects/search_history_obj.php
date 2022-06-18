<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';

  class SearchHistory{

      // database connection and table name
      private $conn;
      private $table_name = "search_history";

      // object properties
      public $id;
      public $unique_id;
      public $user_unique_id;
      public $search;
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

      function get_all_search_history_with_products(){

        try {
          $this->conn->beginTransaction();

          $search_history_array = array();

          $sql = "SELECT search_history.id, search_history.unique_id, search_history.user_unique_id, search_history.search, search_history.type, search_history.added_date, search_history.last_modified, search_history.status
          FROM search_history ORDER BY CASE WHEN search_history.user_unique_id != 'Anonymous' THEN 0 ELSE 1 END, search_history.added_date DESC";
          $query = $this->conn->prepare($sql);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_search_history_id = $value['id'];
              $current_search_history_unique_id = $value['unique_id'];
              $current_search_history_search = $value['search'];
              $current_search_history_search = $value['type'];
              $current_search_history_user_unique_id = $value['user_unique_id'];
              $current_search_history_added_date = $value['added_date'];
              $current_search_history_last_modified = $value['last_modified'];

              $search_word = $value['search'];

              $keyword = "%".$search_word."%";

              $sql3 = "SELECT products.unique_id, products.name, products.size, products.stripped, products.brand_unique_id, products.stock, products.stock_remaining, products.price, products.sales_price, products.favorites,
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
                  $current_search_history['id'] = $current_search_history_id;
                  $current_search_history['unique_id'] = $current_search_history_unique_id;
                  $current_search_history['user_unique_id'] = $current_search_history_user_unique_id;
                  $current_search_history['product_unique_id'] = $product_value['unique_id'];
                  $current_search_history['search_word'] = $current_search_history_search;
                  $current_search_history['added_date'] = $current_search_history_added_date;
                  $current_search_history['last_modified'] = $current_search_history_last_modified;
                  $current_search_history['name'] = $product_value['name'];
                  $current_search_history['size'] = $product_value['size'];
                  $current_search_history['stripped'] = $product_value['stripped'];
                  $current_search_history['brand_unique_id'] = $product_value['brand_unique_id'];
                  $current_search_history['stock'] = $product_value['stock'];
                  $current_search_history['stock_remaining'] = $product_value['stock_remaining'];
                  $current_search_history['price'] = $product_value['price'];
                  $current_search_history['sales_price'] = $product_value['sales_price'];
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
                $current_search_history['search_word'] = $current_search_history_search;
                $current_search_history['added_date'] = $current_search_history_added_date;
                $current_search_history['last_modified'] = $current_search_history_last_modified;
                $current_search_history['name'] = null;
                $current_search_history['size'] = null;
                $current_search_history['stripped'] = null;
                $current_search_history['brand_unique_id'] = null;
                $current_search_history['stock'] = null;
                $current_search_history['stock_remaining'] = null;
                $current_search_history['price'] = null;
                $current_search_history['sales_price'] = null;
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

      function get_user_search_history_with_products($user_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $search_history_array = array();

            $sql = "SELECT search_history.id, search_history.unique_id, search_history.user_unique_id, search_history.search, search_history.type, search_history.added_date, search_history.last_modified, search_history.status
            FROM search_history WHERE search_history.user_unique_id=:user_unique_id ORDER BY CASE WHEN search_history.user_unique_id != 'Anonymous' THEN 0 ELSE 1 END, search_history.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_search_history_id = $value['id'];
                $current_search_history_unique_id = $value['unique_id'];
                $current_search_history_search = $value['search'];
                $current_search_history_search = $value['type'];
                $current_search_history_user_unique_id = $value['user_unique_id'];
                $current_search_history_added_date = $value['added_date'];
                $current_search_history_last_modified = $value['last_modified'];

                $search_word = $value['search'];

                $keyword = "%".$search_word."%";

                $sql3 = "SELECT products.unique_id, products.name, products.size, products.stripped, products.brand_unique_id, products.stock, products.stock_remaining, products.price, products.sales_price, products.favorites,
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
                    $current_search_history['id'] = $current_search_history_id;
                    $current_search_history['unique_id'] = $current_search_history_unique_id;
                    $current_search_history['user_unique_id'] = $current_search_history_user_unique_id;
                    $current_search_history['product_unique_id'] = $product_value['unique_id'];
                    $current_search_history['search_word'] = $current_search_history_search;
                    $current_search_history['added_date'] = $current_search_history_added_date;
                    $current_search_history['last_modified'] = $current_search_history_last_modified;
                    $current_search_history['name'] = $product_value['name'];
                    $current_search_history['size'] = $product_value['size'];
                    $current_search_history['stripped'] = $product_value['stripped'];
                    $current_search_history['brand_unique_id'] = $product_value['brand_unique_id'];
                    $current_search_history['stock'] = $product_value['stock'];
                    $current_search_history['stock_remaining'] = $product_value['stock_remaining'];
                    $current_search_history['price'] = $product_value['price'];
                    $current_search_history['sales_price'] = $product_value['sales_price'];
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
                  $current_search_history['search_word'] = $current_search_history_search;
                  $current_search_history['added_date'] = $current_search_history_added_date;
                  $current_search_history['last_modified'] = $current_search_history_last_modified;
                  $current_search_history['name'] = null;
                  $current_search_history['size'] = null;
                  $current_search_history['stripped'] = null;
                  $current_search_history['brand_unique_id'] = null;
                  $current_search_history['stock'] = null;
                  $current_search_history['stock_remaining'] = null;
                  $current_search_history['price'] = null;
                  $current_search_history['sales_price'] = null;
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
        else {
          $output['error'] = true;
          $output['message'] = "All fields are required";
          return $output;
        }

      }

      function get_all_search_history(){

        try {
          $this->conn->beginTransaction();

          $search_history_array = array();

          $sql = "SELECT search_history.id, search_history.unique_id, search_history.user_unique_id, search_history.search, search_history.type, search_history.added_date, search_history.last_modified, search_history.status
          FROM search_history ORDER BY CASE WHEN search_history.user_unique_id != 'Anonymous' THEN 0 ELSE 1 END, search_history.added_date DESC";
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

      function get_user_search_history($user_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $search_history_array = array();

            $sql = "SELECT search_history.id, search_history.unique_id, search_history.user_unique_id, search_history.search, search_history.type, search_history.added_date, search_history.last_modified, search_history.status
            FROM search_history WHERE search_history.user_unique_id=:user_unique_id ORDER BY CASE WHEN search_history.user_unique_id != 'Anonymous' THEN 0 ELSE 1 END, search_history.added_date DESC";
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

      function get_user_search_for_products($search_word){
        if (!in_array($search_word,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $search_history_array = array();

            $keyword = "%".$search_word."%";

            $sql3 = "SELECT products.unique_id, products.name, products.size, products.stripped, products.brand_unique_id, products.stock, products.stock_remaining, products.price, products.sales_price, products.favorites,
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
                $current_search_history['size'] = $product_value['size'];
                $current_search_history['stripped'] = $product_value['stripped'];
                $current_search_history['brand_unique_id'] = $product_value['brand_unique_id'];
                $current_search_history['stock'] = $product_value['stock'];
                $current_search_history['stock_remaining'] = $product_value['stock_remaining'];
                $current_search_history['price'] = $product_value['price'];
                $current_search_history['sales_price'] = $product_value['sales_price'];
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

  }
?>
