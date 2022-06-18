<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';

  class Disputes{

      // database connection and table name
      private $conn;
      private $table_name = "disputes";

      // object properties
      public $id;
      public $unique_id;
      public $user_unique_id;
      public $order_unique_id;
      public $message;
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

      function get_all_disputes(){

        try {
          $this->conn->beginTransaction();

          $disputes_array = array();

          $sql = "SELECT disputes.id, disputes.unique_id, disputes.user_unique_id, disputes.order_unique_id, disputes.message, disputes.added_date, disputes.last_modified, disputes.status,
          users.fullname as user_fullname, users.email as user_email FROM disputes INNER JOIN users ON disputes.user_unique_id = users.unique_id ORDER BY disputes.added_date DESC";
          $query = $this->conn->prepare($sql);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_disputes = array();
              $current_disputes['id'] = $value['id'];
              $current_disputes['unique_id'] = $value['unique_id'];
              $current_disputes['user_unique_id'] = $value['user_unique_id'];
              $current_disputes['order_unique_id'] = $value['order_unique_id'];
              $current_disputes['message'] = $value['message'];
              $current_disputes['added_date'] = $value['added_date'];
              $current_disputes['last_modified'] = $value['last_modified'];
              $current_disputes['status'] = $value['status'];
              $current_disputes['user_fullname'] = $value['user_fullname'];
              $current_disputes['user_email'] = $value['user_email'];

              $order_unique_id = $value['order_unique_id'];

              $sql3 = "SELECT orders.product_unique_id, products.name, products.size, products.stripped, products.brand_unique_id, products.stock, products.stock_remaining, products.price, products.sales_price, products.favorites, brands.name as brand_name, brands.stripped as brand_name_stripped
              FROM orders LEFT JOIN products ON orders.product_unique_id = products.unique_id LEFT JOIN brands ON products.brand_unique_id = brands.unique_id WHERE orders.unique_id=:unique_id";
              $query3 = $this->conn->prepare($sql3);
              $query3->bindParam(":unique_id", $order_unique_id);
              $query3->execute();

              $result3 = $query3->fetchAll();

              if ($query3->rowCount() > 0) {
                foreach ($result3 as $key => $value3) {

                  $current_disputes['product_unique_id'] = $value3['product_unique_id'];
                  $current_disputes['name'] = $value3['name'];
                  $current_disputes['size'] = $value3['size'];
                  $current_disputes['stripped'] = $value3['stripped'];
                  $current_disputes['brand_unique_id'] = $value3['brand_unique_id'];
                  $current_disputes['stock'] = $value3['stock'];
                  $current_disputes['stock_remaining'] = $value3['stock_remaining'];
                  $current_disputes['product_price'] = $value3['price'];
                  $current_disputes['product_sales_price'] = $value3['sales_price'];
                  $current_disputes['favorites'] = $value3['favorites'];
                  $current_disputes['brand_name'] = $value3['brand_name'];
                  $current_disputes['brand_name_stripped'] = $value3['brand_name_stripped'];

                  $product_id = $value3['product_unique_id'];

                  $sql2 = "SELECT image FROM product_images WHERE product_unique_id=:product_unique_id LIMIT 1";
                  $query2 = $this->conn->prepare($sql2);
                  $query2->bindParam(":product_unique_id", $product_id);
                  $query2->execute();

                  $images_result = $query2->fetchAll();

                  if ($query2->rowCount() > 0) {
                    $current_disputes_images = array();

                    foreach ($images_result as $key => $image_value) {
                      $current_disputes_images[] = $image_value['image'];
                    }

                    $current_disputes['product_images'] = $current_disputes_images;
                  }
                  else{
                    $current_disputes['product_images'] = null;
                  }

                }
              }
              else {
                $current_disputes['product_unique_id'] = null;
                $current_disputes['name'] = null;
                $current_disputes['size'] = null;
                $current_disputes['stripped'] = null;
                $current_disputes['brand_unique_id'] = null;
                $current_disputes['stock'] = null;
                $current_disputes['stock_remaining'] = null;
                $current_disputes['product_price'] = null;
                $current_disputes['product_sales_price'] = null;
                $current_disputes['favorites'] = null;
                $current_disputes['brand_name'] = null;
                $current_disputes['brand_name_stripped'] = null;
                $current_disputes['product_images'] = null;
              }

              $disputes_array[] = $current_disputes;
            }
            return $disputes_array;
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

      function get_user_disputes($user_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $disputes_array = array();

            $sql = "SELECT disputes.id, disputes.unique_id, disputes.user_unique_id, disputes.order_unique_id, disputes.message, disputes.added_date, disputes.last_modified, disputes.status,
            users.fullname as user_fullname, users.email as user_email FROM disputes INNER JOIN users ON disputes.user_unique_id = users.unique_id WHERE disputes.user_unique_id=:user_unique_id ORDER BY disputes.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_disputes = array();
                $current_disputes['id'] = $value['id'];
                $current_disputes['unique_id'] = $value['unique_id'];
                $current_disputes['user_unique_id'] = $value['user_unique_id'];
                $current_disputes['order_unique_id'] = $value['order_unique_id'];
                $current_disputes['message'] = $value['message'];
                $current_disputes['added_date'] = $value['added_date'];
                $current_disputes['last_modified'] = $value['last_modified'];
                $current_disputes['status'] = $value['status'];
                $current_disputes['user_fullname'] = $value['user_fullname'];
                $current_disputes['user_email'] = $value['user_email'];

                $order_unique_id = $value['order_unique_id'];

                $sql3 = "SELECT orders.product_unique_id, products.name, products.size, products.stripped, products.brand_unique_id, products.stock, products.stock_remaining, products.price, products.sales_price, products.favorites, brands.name as brand_name, brands.stripped as brand_name_stripped
                FROM orders LEFT JOIN products ON orders.product_unique_id = products.unique_id LEFT JOIN brands ON products.brand_unique_id = brands.unique_id WHERE orders.unique_id=:unique_id";
                $query3 = $this->conn->prepare($sql3);
                $query3->bindParam(":unique_id", $order_unique_id);
                $query3->execute();

                $result3 = $query3->fetchAll();

                if ($query3->rowCount() > 0) {
                  foreach ($result3 as $key => $value3) {

                    $current_disputes['product_unique_id'] = $value3['product_unique_id'];
                    $current_disputes['name'] = $value3['name'];
                    $current_disputes['size'] = $value3['size'];
                    $current_disputes['stripped'] = $value3['stripped'];
                    $current_disputes['brand_unique_id'] = $value3['brand_unique_id'];
                    $current_disputes['stock'] = $value3['stock'];
                    $current_disputes['stock_remaining'] = $value3['stock_remaining'];
                    $current_disputes['product_price'] = $value3['price'];
                    $current_disputes['product_sales_price'] = $value3['sales_price'];
                    $current_disputes['favorites'] = $value3['favorites'];
                    $current_disputes['brand_name'] = $value3['brand_name'];
                    $current_disputes['brand_name_stripped'] = $value3['brand_name_stripped'];

                    $product_id = $value3['product_unique_id'];

                    $sql2 = "SELECT image FROM product_images WHERE product_unique_id=:product_unique_id LIMIT 1";
                    $query2 = $this->conn->prepare($sql2);
                    $query2->bindParam(":product_unique_id", $product_id);
                    $query2->execute();

                    $images_result = $query2->fetchAll();

                    if ($query2->rowCount() > 0) {
                      $current_disputes_images = array();

                      foreach ($images_result as $key => $image_value) {
                        $current_disputes_images[] = $image_value['image'];
                      }

                      $current_disputes['product_images'] = $current_disputes_images;
                    }
                    else{
                      $current_disputes['product_images'] = null;
                    }

                  }
                }
                else {
                  $current_disputes['product_unique_id'] = null;
                  $current_disputes['name'] = null;
                  $current_disputes['size'] = null;
                  $current_disputes['stripped'] = null;
                  $current_disputes['brand_unique_id'] = null;
                  $current_disputes['stock'] = null;
                  $current_disputes['stock_remaining'] = null;
                  $current_disputes['product_price'] = null;
                  $current_disputes['product_sales_price'] = null;
                  $current_disputes['favorites'] = null;
                  $current_disputes['brand_name'] = null;
                  $current_disputes['brand_name_stripped'] = null;
                  $current_disputes['product_images'] = null;
                }

                $disputes_array[] = $current_disputes;
              }
              return $disputes_array;
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

      function get_user_order_dispute($user_unique_id, $order_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values) || !in_array($order_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $disputes_array = array();

            $sql = "SELECT disputes.id, disputes.unique_id, disputes.user_unique_id, disputes.order_unique_id, disputes.message, disputes.added_date, disputes.last_modified, disputes.status,
            users.fullname as user_fullname, users.email as user_email FROM disputes INNER JOIN users ON disputes.user_unique_id = users.unique_id WHERE disputes.user_unique_id=:user_unique_id AND
            disputes.order_unique_id=:order_unique_id ORDER BY disputes.added_date DESC LIMIT 1";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->bindParam(":order_unique_id", $order_unique_id);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_disputes = array();
                $current_disputes['id'] = $value['id'];
                $current_disputes['unique_id'] = $value['unique_id'];
                $current_disputes['user_unique_id'] = $value['user_unique_id'];
                $current_disputes['order_unique_id'] = $value['order_unique_id'];
                $current_disputes['message'] = $value['message'];
                $current_disputes['added_date'] = $value['added_date'];
                $current_disputes['last_modified'] = $value['last_modified'];
                $current_disputes['status'] = $value['status'];
                $current_disputes['user_fullname'] = $value['user_fullname'];
                $current_disputes['user_email'] = $value['user_email'];

                $order_unique_id = $value['order_unique_id'];

                $sql3 = "SELECT orders.product_unique_id, products.name, products.size, products.stripped, products.brand_unique_id, products.stock, products.stock_remaining, products.price, products.sales_price, products.favorites, brands.name as brand_name, brands.stripped as brand_name_stripped
                FROM orders LEFT JOIN products ON orders.product_unique_id = products.unique_id LEFT JOIN brands ON products.brand_unique_id = brands.unique_id WHERE orders.unique_id=:unique_id";
                $query3 = $this->conn->prepare($sql3);
                $query3->bindParam(":unique_id", $order_unique_id);
                $query3->execute();

                $result3 = $query3->fetchAll();

                if ($query3->rowCount() > 0) {
                  foreach ($result3 as $key => $value3) {

                    $current_disputes['product_unique_id'] = $value3['product_unique_id'];
                    $current_disputes['name'] = $value3['name'];
                    $current_disputes['size'] = $value3['size'];
                    $current_disputes['stripped'] = $value3['stripped'];
                    $current_disputes['brand_unique_id'] = $value3['brand_unique_id'];
                    $current_disputes['stock'] = $value3['stock'];
                    $current_disputes['stock_remaining'] = $value3['stock_remaining'];
                    $current_disputes['product_price'] = $value3['price'];
                    $current_disputes['product_sales_price'] = $value3['sales_price'];
                    $current_disputes['favorites'] = $value3['favorites'];
                    $current_disputes['brand_name'] = $value3['brand_name'];
                    $current_disputes['brand_name_stripped'] = $value3['brand_name_stripped'];

                    $product_id = $value3['product_unique_id'];

                    $sql2 = "SELECT image FROM product_images WHERE product_unique_id=:product_unique_id LIMIT 1";
                    $query2 = $this->conn->prepare($sql2);
                    $query2->bindParam(":product_unique_id", $product_id);
                    $query2->execute();

                    $images_result = $query2->fetchAll();

                    if ($query2->rowCount() > 0) {
                      $current_disputes_images = array();

                      foreach ($images_result as $key => $image_value) {
                        $current_disputes_images[] = $image_value['image'];
                      }

                      $current_disputes['product_images'] = $current_disputes_images;
                    }
                    else{
                      $current_disputes['product_images'] = null;
                    }

                  }
                }
                else {
                  $current_disputes['product_unique_id'] = null;
                  $current_disputes['name'] = null;
                  $current_disputes['size'] = null;
                  $current_disputes['stripped'] = null;
                  $current_disputes['brand_unique_id'] = null;
                  $current_disputes['stock'] = null;
                  $current_disputes['stock_remaining'] = null;
                  $current_disputes['product_price'] = null;
                  $current_disputes['product_sales_price'] = null;
                  $current_disputes['favorites'] = null;
                  $current_disputes['brand_name'] = null;
                  $current_disputes['brand_name_stripped'] = null;
                  $current_disputes['product_images'] = null;
                }

                $disputes_array[] = $current_disputes;
              }
              return $disputes_array;
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
