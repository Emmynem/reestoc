<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';

  class SubCategory{

      // database connection and table name
      private $conn;
      private $table_name = "sub_category";

      // object properties
      public $id;
      public $unique_id;
      public $user_unique_id;
      public $edit_user_unique_id;
      public $category_unique_id;
      public $name;
      public $added_date;
      public $last_modified;
      public $status;

      private $functions;
      private $not_allowed_values;
      private $allow_null_values;

      public $output = array('error' => false, 'success' => false);

      // constructor with $db as database connection
      public function __construct($db){
          $this->conn = $db;
          $this->functions = new Functions();
          $this->not_allowed_values = $this->functions->not_allowed_values;
          $this->allow_null_values = $this->functions->allow_null_values;
      }

      function get_all_sub_categories(){

        try {
          $this->conn->beginTransaction();

          $sql = "SELECT sub_category.id, sub_category.unique_id, sub_category.user_unique_id, sub_category.edit_user_unique_id, sub_category.category_unique_id, sub_category.name, sub_category.stripped, sub_category.added_date, sub_category.last_modified, sub_category.status,
          categories.name as category_name, management.fullname as added_fullname, management_alt.fullname as edit_user_fullname FROM sub_category INNER JOIN management ON sub_category.user_unique_id = management.unique_id INNER JOIN management management_alt ON sub_category.edit_user_unique_id = management_alt.unique_id
          LEFT JOIN categories ON sub_category.category_unique_id = categories.unique_id ORDER BY sub_category.added_date DESC";
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

      function get_all_sub_categories_for_users(){

        try {
          $this->conn->beginTransaction();

          $sql = "SELECT sub_category.unique_id, sub_category.category_unique_id, sub_category.name, sub_category.stripped,
          categories.name as category_name FROM sub_category LEFT JOIN categories ON sub_category.category_unique_id = categories.unique_id ORDER BY sub_category.added_date DESC";
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

      function get_user_sub_categories($user_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT sub_category.id, sub_category.unique_id, sub_category.user_unique_id, sub_category.edit_user_unique_id, sub_category.name, sub_category.stripped, sub_category.added_date, sub_category.last_modified, sub_category.status,
            categories.name as category_name, management.fullname as added_fullname, management_alt.fullname as edit_user_fullname FROM sub_category INNER JOIN management ON sub_category.user_unique_id = management.unique_id INNER JOIN management management_alt ON sub_category.edit_user_unique_id = management_alt.unique_id
            LEFT JOIN categories ON sub_category.category_unique_id = categories.unique_id WHERE sub_category.user_unique_id=:user_unique_id ORDER BY sub_category.added_date DESC";
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

      function get_sub_category_details($sub_category_unique_id){
        if (!in_array($sub_category_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT sub_category.id, sub_category.unique_id, sub_category.user_unique_id, sub_category.edit_user_unique_id, sub_category.name, sub_category.stripped, sub_category.added_date, sub_category.last_modified, sub_category.status,
            categories.name as category_name, management.fullname as added_fullname, management_alt.fullname as edit_user_fullname FROM sub_category INNER JOIN management ON sub_category.user_unique_id = management.unique_id INNER JOIN management management_alt ON sub_category.edit_user_unique_id = management_alt.unique_id
            LEFT JOIN categories ON sub_category.category_unique_id = categories.unique_id WHERE sub_category.unique_id=:unique_id ORDER BY sub_category.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $sub_category_unique_id);
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

      function get_sub_category_details_for_users($sub_category_unique_id, $stripped){
        if (!in_array($sub_category_unique_id,$this->not_allowed_values) || !in_array($stripped,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT sub_category.unique_id, sub_category.name, sub_category.stripped, categories.name as category_name FROM sub_category
            LEFT JOIN categories ON sub_category.category_unique_id = categories.unique_id WHERE sub_category.unique_id=:unique_id OR sub_category.stripped=:stripped ORDER BY sub_category.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $sub_category_unique_id);
            $query->bindParam(":stripped", $stripped);
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
          $output['message'] = "At least one value is required";
          return $output;
        }

      }

      function get_category_sub_categories($category_unique_id){
        if (!in_array($category_unique_id,$this->allow_null_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT sub_category.id, sub_category.unique_id, sub_category.user_unique_id, sub_category.edit_user_unique_id, sub_category.category_unique_id, sub_category.name, sub_category.stripped, sub_category.added_date, sub_category.last_modified, sub_category.status,
            categories.name as category_name, management.fullname as added_fullname, management_alt.fullname as edit_user_fullname FROM sub_category INNER JOIN management ON sub_category.user_unique_id = management.unique_id INNER JOIN management management_alt ON sub_category.edit_user_unique_id = management_alt.unique_id
            LEFT JOIN categories ON sub_category.category_unique_id = categories.unique_id WHERE sub_category.category_unique_id=:category_unique_id ORDER BY sub_category.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":category_unique_id", $category_unique_id);
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

      function get_category_sub_categories_for_users($category_unique_id){
        if (!in_array($category_unique_id,$this->allow_null_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT sub_category.unique_id, sub_category.category_unique_id, sub_category.name, sub_category.stripped, categories.name as category_name FROM sub_category
            LEFT JOIN categories ON sub_category.category_unique_id = categories.unique_id WHERE sub_category.category_unique_id=:category_unique_id ORDER BY sub_category.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":category_unique_id", $category_unique_id);
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

      function get_null_category_sub_categories(){
        try {
          $this->conn->beginTransaction();

          $sql = "SELECT sub_category.id, sub_category.unique_id, sub_category.user_unique_id, sub_category.edit_user_unique_id, sub_category.category_unique_id, sub_category.name, sub_category.stripped, sub_category.added_date, sub_category.last_modified, sub_category.status,
          categories.name as category_name, management.fullname as added_fullname, management_alt.fullname as edit_user_fullname FROM sub_category INNER JOIN management ON sub_category.user_unique_id = management.unique_id INNER JOIN management management_alt ON sub_category.edit_user_unique_id = management_alt.unique_id
          LEFT JOIN categories ON sub_category.category_unique_id = categories.unique_id WHERE sub_category.category_unique_id IS NULL OR sub_category.category_unique_id='' ORDER BY sub_category.added_date DESC";
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

      function get_null_category_sub_categories_for_users(){
        try {
          $this->conn->beginTransaction();

          $sql = "SELECT sub_category.unique_id, sub_category.category_unique_id, sub_category.name, sub_category.stripped, categories.name as category_name FROM sub_category
          LEFT JOIN categories ON sub_category.category_unique_id = categories.unique_id WHERE sub_category.category_unique_id IS NULL OR sub_category.category_unique_id='' ORDER BY sub_category.added_date DESC";
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

  }
?>
