<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';

  class OrderServices{

      // database connection and table name
      private $conn;
      private $table_name = "order_services";

      // object properties
      public $id;
      public $unique_id;
      public $user_unique_id;
      public $cart_unique_id;
      public $order_unique_id;
      public $offered_service_unique_id;
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

      function get_all_order_services(){

        try {
          $this->conn->beginTransaction();

          $sql = "SELECT order_services.id, order_services.unique_id, order_services.user_unique_id, order_services.cart_unique_id, order_services.order_unique_id, order_services.offered_service_unique_id, order_services.added_date, order_services.last_modified, order_services.status, users.fullname as added_user_fullname
          FROM order_services INNER JOIN users ON order_services.user_unique_id = users.unique_id ORDER BY order_services.added_date DESC";
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

      function get_cart_offered_services($cart_unique_id){
        if (!in_array($cart_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT order_services.id, order_services.unique_id, order_services.user_unique_id, order_services.cart_unique_id, order_services.order_unique_id, order_services.offered_service_unique_id, order_services.added_date, order_services.last_modified, order_services.status, users.fullname as added_user_fullname
            FROM order_services INNER JOIN users ON order_services.user_unique_id = users.unique_id WHERE order_services.cart_unique_id=:cart_unique_id ORDER BY order_services.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":cart_unique_id", $cart_unique_id);
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

      function get_order_offered_services($order_unique_id){
        if (!in_array($order_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT order_services.id, order_services.unique_id, order_services.user_unique_id, order_services.cart_unique_id, order_services.order_unique_id, order_services.offered_service_unique_id, order_services.added_date, order_services.last_modified, order_services.status, users.fullname as added_user_fullname
            FROM order_services INNER JOIN users ON order_services.user_unique_id = users.unique_id WHERE order_services.order_unique_id=:order_unique_id ORDER BY order_services.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":order_unique_id", $order_unique_id);
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
