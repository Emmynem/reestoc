<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';

  class Category{

      // database connection and table name
      private $conn;
      private $table_name = "categories";

      // object properties
      public $id;
      public $unique_id;
      public $user_unique_id;
      public $edit_user_unique_id;
      public $name;
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

      function get_all_categories(){

        try {
          $this->conn->beginTransaction();

          $sql = "SELECT categories.id, categories.unique_id, categories.user_unique_id, categories.edit_user_unique_id, categories.name, categories.stripped, categories.added_date, categories.last_modified, categories.status, management.fullname as added_fullname, management_alt.fullname as edit_user_fullname FROM categories
          INNER JOIN management ON categories.user_unique_id = management.unique_id INNER JOIN management management_alt ON categories.edit_user_unique_id = management_alt.unique_id ORDER BY categories.added_date DESC";
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

      function get_all_categories_for_users(){

        try {
          $this->conn->beginTransaction();

          $sql = "SELECT categories.unique_id, categories.name, categories.stripped FROM categories ORDER BY categories.added_date DESC";
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

      function get_user_categories($user_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT categories.id, categories.unique_id, categories.user_unique_id, categories.edit_user_unique_id, categories.name, categories.stripped, categories.added_date, categories.last_modified, categories.status, management.fullname as added_fullname, management_alt.fullname as edit_user_fullname FROM categories
            INNER JOIN management ON categories.user_unique_id = management.unique_id INNER JOIN management management_alt ON categories.edit_user_unique_id = management_alt.unique_id WHERE categories.user_unique_id=:user_unique_id ORDER BY categories.added_date DESC";
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

      function get_category_details($category_unique_id){
        if (!in_array($category_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT categories.id, categories.unique_id, categories.user_unique_id, categories.edit_user_unique_id, categories.name, categories.stripped, categories.added_date, categories.last_modified, categories.status, management.fullname as added_fullname, management_alt.fullname as edit_user_fullname FROM categories
            INNER JOIN management ON categories.user_unique_id = management.unique_id INNER JOIN management management_alt ON categories.edit_user_unique_id = management_alt.unique_id WHERE categories.unique_id=:unique_id ORDER BY categories.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $category_unique_id);
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

      function get_category_details_for_users($category_unique_id, $stripped){
        if (!in_array($category_unique_id,$this->not_allowed_values) || !in_array($stripped,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT categories.unique_id, categories.name, categories.stripped FROM categories
            WHERE categories.unique_id=:unique_id OR categories.stripped=:stripped ORDER BY categories.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $category_unique_id);
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

  }
?>
