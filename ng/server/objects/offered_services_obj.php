<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';

  class OfferedServices{

      // database connection and table name
      private $conn;
      private $table_name = "offered_services";

      // object properties
      public $id;
      public $unique_id;
      public $user_unique_id;
      public $edit_user_unique_id;
      public $product_unique_id;
      public $service;
      public $details;
      public $price;
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

      function get_all_offered_services_categories(){

        try {
          $this->conn->beginTransaction();

          $sql = "SELECT offered_services_category.id, offered_services_category.unique_id, offered_services_category.user_unique_id, offered_services_category.edit_user_unique_id, offered_services_category.sub_product_unique_id, offered_services_category.service_category, offered_services_category.added_date, offered_services_category.last_modified,
          offered_services_category.status, management.fullname as added_user_fullname, management_alt.fullname as edit_user_fullname, sub_products.name as sub_product_name, sub_products.size as sub_product_size
          FROM offered_services_category INNER JOIN management ON offered_services_category.user_unique_id = management.unique_id INNER JOIN management management_alt ON offered_services_category.edit_user_unique_id = management_alt.unique_id LEFT JOIN sub_products ON offered_services_category.sub_product_unique_id = sub_products.unique_id ORDER BY offered_services_category.added_date DESC";
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

      function get_all_offered_services_categories_for_select($sub_product_unique_id){

        try {
          $this->conn->beginTransaction();

          $sql = "SELECT offered_services_category.unique_id, offered_services_category.sub_product_unique_id, offered_services_category.service_category,
          sub_products.name as sub_product_name, sub_products.size as sub_product_size
          FROM offered_services_category INNER JOIN sub_products ON offered_services_category.sub_product_unique_id = sub_products.unique_id WHERE offered_services_category.sub_product_unique_id=:sub_product_unique_id ORDER BY offered_services_category.service_category ASC";
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

      function get_all_offered_services(){

        try {
          $this->conn->beginTransaction();

          $sql = "SELECT offered_services.id, offered_services.unique_id, offered_services.user_unique_id, offered_services.edit_user_unique_id, offered_services.service, offered_services.details, offered_services.price, offered_services.image, offered_services.file, offered_services.file_size, offered_services.added_date, offered_services.last_modified, offered_services.status, management.fullname as added_user_fullname, management_alt.fullname as edit_user_fullname,
          offered_services_category.unique_id AS service_category_unique_id, offered_services_category.sub_product_unique_id, offered_services_category.service_category, sub_products.unique_id as sub_product_unique_id, sub_products.name as sub_product_name, sub_products.size as sub_product_size FROM offered_services INNER JOIN management ON offered_services.user_unique_id = management.unique_id INNER JOIN management management_alt ON offered_services.edit_user_unique_id = management_alt.unique_id
          LEFT JOIN offered_services_category ON offered_services.offered_service_category_unique_id = offered_services_category.unique_id LEFT JOIN sub_products ON offered_services.sub_product_unique_id = sub_products.unique_id ORDER BY offered_services.added_date DESC";
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

      function get_edit_management_offered_services($edit_user_unique_id){
        if (!in_array($edit_user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT offered_services.id, offered_services.unique_id, offered_services.user_unique_id, offered_services.edit_user_unique_id, offered_services.service, offered_services.details, offered_services.price, offered_services.image, offered_services.file, offered_services.file_size, offered_services.added_date, offered_services.last_modified, offered_services.status, management.fullname as added_user_fullname, management_alt.fullname as edit_user_fullname,
            offered_services_category.unique_id AS service_category_unique_id, offered_services_category.sub_product_unique_id, offered_services_category.service_category, sub_products.name as sub_product_name, sub_products.size as sub_product_size FROM offered_services INNER JOIN management ON offered_services.user_unique_id = management.unique_id INNER JOIN management management_alt ON offered_services.edit_user_unique_id = management_alt.unique_id
            LEFT JOIN offered_services_category ON offered_services.offered_service_category_unique_id = offered_services_category.unique_id LEFT JOIN sub_products ON offered_services.sub_product_unique_id = sub_products.unique_id WHERE offered_services.edit_user_unique_id=:edit_user_unique_id ORDER BY offered_services.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":edit_user_unique_id", $edit_user_unique_id);
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

      function get_sub_product_offered_services($sub_product_unique_id){
        if (!in_array($sub_product_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT offered_services.id, offered_services.unique_id, offered_services.user_unique_id, offered_services.edit_user_unique_id, offered_services.service, offered_services.details, offered_services.price, offered_services.image, offered_services.file, offered_services.file_size, offered_services.added_date, offered_services.last_modified, offered_services.status,
            offered_services_category.unique_id AS service_category_unique_id, offered_services_category.sub_product_unique_id, offered_services_category.service_category, sub_products.name as sub_product_name, sub_products.size as sub_product_size FROM offered_services
            LEFT JOIN offered_services_category ON offered_services.offered_service_category_unique_id = offered_services_category.unique_id LEFT JOIN sub_products ON offered_services.sub_product_unique_id = sub_products.unique_id WHERE offered_services.sub_product_unique_id=:sub_product_unique_id AND offered_services_category.sub_product_unique_id=:sub_product_unique_id ORDER BY offered_services.added_date DESC";
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

      function get_sub_product_offered_services_for_users($sub_product_unique_id){
        if (!in_array($sub_product_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;

            $sql = "SELECT offered_services.unique_id, offered_services.service, offered_services.details, offered_services.price, offered_services.image, offered_services.file, offered_services.file_size,
            offered_services_category.unique_id AS service_category_unique_id, offered_services_category.sub_product_unique_id, offered_services_category.service_category, sub_products.name as sub_product_name, sub_products.size as sub_product_size FROM offered_services
            LEFT JOIN offered_services_category ON offered_services.offered_service_category_unique_id = offered_services_category.unique_id LEFT JOIN sub_products ON offered_services.sub_product_unique_id = sub_products.unique_id
            WHERE offered_services.sub_product_unique_id=:sub_product_unique_id AND offered_services_category.sub_product_unique_id=:sub_product_unique_id AND offered_services.status=:offered_services_status AND offered_services_category.status=:offered_services_category_status ORDER BY offered_services.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":sub_product_unique_id", $sub_product_unique_id);
            $query->bindParam(":offered_services_status", $active);
            $query->bindParam(":offered_services_category_status", $active);
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

      function get_offered_service_details($offered_service_unique_id){
        if (!in_array($offered_service_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT offered_services.id, offered_services.unique_id, offered_services.user_unique_id, offered_services.edit_user_unique_id, offered_services.service, offered_services.details, offered_services.price, offered_services.image, offered_services.file, offered_services.file_size, offered_services.added_date, offered_services.last_modified, offered_services.status, management.fullname as added_user_fullname, management_alt.fullname as edit_user_fullname,
            offered_services_category.unique_id AS service_category_unique_id, offered_services_category.sub_product_unique_id, offered_services_category.service_category, sub_products.name as sub_product_name, sub_products.size as sub_product_size FROM offered_services INNER JOIN management ON offered_services.user_unique_id = management.unique_id INNER JOIN management management_alt ON offered_services.edit_user_unique_id = management_alt.unique_id
            LEFT JOIN offered_services_category ON offered_services.offered_service_category_unique_id = offered_services_category.unique_id LEFT JOIN sub_products ON offered_services.sub_product_unique_id = sub_products.unique_id WHERE offered_services.unique_id=:unique_id ORDER BY offered_services.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $offered_service_unique_id);
            $query->execute();

            $result = $query->fetch();

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

      function get_offered_service_details_for_users($offered_service_unique_id){
        if (!in_array($offered_service_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT offered_services.unique_id, offered_services.service, offered_services.details, offered_services.price, offered_services.image, offered_services.file, offered_services_category.unique_id AS service_category_unique_id, offered_services_category.sub_product_unique_id, offered_services_category.service_category, sub_products.name as sub_product_name, sub_products.size as sub_product_size FROM offered_services
            LEFT JOIN offered_services_category ON offered_services.offered_service_category_unique_id = offered_services_category.unique_id LEFT JOIN sub_products ON offered_services.sub_product_unique_id = sub_products.unique_id WHERE offered_services.unique_id=:unique_id ORDER BY offered_services.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $offered_service_unique_id);
            $query->execute();

            $result = $query->fetch();

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
