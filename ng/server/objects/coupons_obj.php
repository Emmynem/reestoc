<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';

  class Coupons{

      // database connection and table name
      private $conn;
      private $table_name = "coupons";

      // object properties
      public $id;
      public $unique_id;
      public $user_unique_id;
      public $sub_product_unique_id;
      public $code;
      public $name;
      public $percentage;
      public $total_count;
      public $current_count;
      public $completion;
      public $expiry_date;
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

      public function get_all_coupons(){

        try {
          $this->conn->beginTransaction();

          $sql = "SELECT coupons.id, coupons.unique_id, coupons.user_unique_id, coupons.sub_product_unique_id, coupons.mini_category_unique_id, coupons.code, coupons.name, coupons.percentage, coupons.total_count, coupons.current_count, coupons.completion, coupons.expiry_date, coupons.added_date, coupons.last_modified, coupons.status,
          users.fullname as user_fullname, users.phone_number as user_phone_number, sub_products.name as sub_product_name, sub_products.size as sub_product_size, categories.name as category_name, sub_category.name as sub_category_name, mini_category.name as mini_category_name FROM coupons LEFT JOIN users ON coupons.user_unique_id = users.unique_id LEFT JOIN sub_products ON coupons.sub_product_unique_id = sub_products.unique_id
          LEFT JOIN mini_category ON coupons.mini_category_unique_id = mini_category.unique_id LEFT JOIN sub_category ON mini_category.sub_category_unique_id = sub_category.unique_id LEFT JOIN categories ON mini_category.category_unique_id = categories.unique_id ORDER BY coupons.added_date DESC";
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

      public function get_coupon_filter($start_date, $end_date){

        if (!in_array($start_date,$this->not_allowed_values) && !in_array($end_date,$this->not_allowed_values)) {

          try {
            $this->conn->beginTransaction();

            $sql = "SELECT coupons.id, coupons.unique_id, coupons.user_unique_id, coupons.sub_product_unique_id, coupons.mini_category_unique_id, coupons.code, coupons.name, coupons.percentage, coupons.total_count, coupons.current_count, coupons.completion, coupons.expiry_date, coupons.added_date, coupons.last_modified, coupons.status,
            users.fullname as user_fullname, users.phone_number as user_phone_number, sub_products.name as sub_product_name, sub_products.size as sub_product_size, categories.name as category_name, sub_category.name as sub_category_name, mini_category.name as mini_category_name FROM coupons LEFT JOIN users ON coupons.user_unique_id = users.unique_id LEFT JOIN sub_products ON coupons.sub_product_unique_id = sub_products.unique_id
            LEFT JOIN mini_category ON coupons.mini_category_unique_id = mini_category.unique_id LEFT JOIN sub_category ON mini_category.sub_category_unique_id = sub_category.unique_id LEFT JOIN categories ON mini_category.category_unique_id = categories.unique_id
            WHERE coupons.added_date >:start_date AND (coupons.added_date <:end_date OR coupons.added_date=:end_date) ORDER BY coupons.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":start_date", $start_date);
            $query->bindParam(":end_date", $end_date);
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

      public function get_coupon_expiry_date_filter($start_date, $end_date){

        if (!in_array($start_date,$this->not_allowed_values) && !in_array($end_date,$this->not_allowed_values)) {

          try {
            $this->conn->beginTransaction();

            $sql = "SELECT coupons.id, coupons.unique_id, coupons.user_unique_id, coupons.sub_product_unique_id, coupons.mini_category_unique_id, coupons.code, coupons.name, coupons.percentage, coupons.total_count, coupons.current_count, coupons.completion, coupons.expiry_date, coupons.added_date, coupons.last_modified, coupons.status,
            users.fullname as user_fullname, users.phone_number as user_phone_number, sub_products.name as sub_product_name, sub_products.size as sub_product_size, categories.name as category_name, sub_category.name as sub_category_name, mini_category.name as mini_category_name FROM coupons LEFT JOIN users ON coupons.user_unique_id = users.unique_id LEFT JOIN sub_products ON coupons.sub_product_unique_id = sub_products.unique_id
            LEFT JOIN mini_category ON coupons.mini_category_unique_id = mini_category.unique_id LEFT JOIN sub_category ON mini_category.sub_category_unique_id = sub_category.unique_id LEFT JOIN categories ON mini_category.category_unique_id = categories.unique_id
            WHERE coupons.expiry_date >:start_date AND (coupons.expiry_date <:end_date OR coupons.expiry_date=:end_date) ORDER BY coupons.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":start_date", $start_date);
            $query->bindParam(":end_date", $end_date);
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

      public function get_user_coupons($user_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $null = $this->functions->null;

            $sql = "SELECT coupons.id, coupons.unique_id, coupons.user_unique_id, coupons.sub_product_unique_id, coupons.mini_category_unique_id, coupons.code, coupons.name, coupons.percentage, coupons.total_count, coupons.current_count, coupons.completion, coupons.expiry_date, coupons.added_date, coupons.last_modified, coupons.status,
            users.fullname as user_fullname, users.phone_number as user_phone_number, sub_products.name as sub_product_name, sub_products.size as sub_product_size, categories.name as category_name, sub_category.name as sub_category_name, mini_category.name as mini_category_name FROM coupons LEFT JOIN users ON coupons.user_unique_id = users.unique_id LEFT JOIN sub_products ON coupons.sub_product_unique_id = sub_products.unique_id
            LEFT JOIN mini_category ON coupons.mini_category_unique_id = mini_category.unique_id LEFT JOIN sub_category ON mini_category.sub_category_unique_id = sub_category.unique_id LEFT JOIN categories ON mini_category.category_unique_id = categories.unique_id WHERE coupons.user_unique_id=:user_unique_id OR coupons.sub_product_unique_id IS NOT NULL OR coupons.mini_category_unique_id IS NOT NULL ORDER BY coupons.expiry_date DESC, coupons.added_date DESC";
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

      public function get_product_coupons($sub_product_unique_id){
        if (!in_array($sub_product_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $null = $this->functions->null;

            $sql = "SELECT coupons.id, coupons.unique_id, coupons.user_unique_id, coupons.sub_product_unique_id, coupons.mini_category_unique_id, coupons.code, coupons.name, coupons.percentage, coupons.total_count, coupons.current_count, coupons.completion, coupons.expiry_date, coupons.added_date, coupons.last_modified, coupons.status,
            users.fullname as user_fullname, users.phone_number as user_phone_number, sub_products.name as sub_product_name, sub_products.size as sub_product_size, categories.name as category_name, sub_category.name as sub_category_name, mini_category.name as mini_category_name FROM coupons LEFT JOIN users ON coupons.user_unique_id = users.unique_id LEFT JOIN sub_products ON coupons.sub_product_unique_id = sub_products.unique_id
            LEFT JOIN mini_category ON coupons.mini_category_unique_id = mini_category.unique_id LEFT JOIN sub_category ON mini_category.sub_category_unique_id = sub_category.unique_id LEFT JOIN categories ON mini_category.category_unique_id = categories.unique_id WHERE coupons.sub_product_unique_id=:sub_product_unique_id ORDER BY coupons.added_date DESC";
            $query = $this->conn->prepare($sql);
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

  }

?>
