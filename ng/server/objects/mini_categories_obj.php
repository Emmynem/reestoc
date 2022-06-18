<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';

  class MiniCategory{

      // database connection and table name
      private $conn;
      private $table_name = "mini_category";

      // object properties
      public $id;
      public $unique_id;
      public $user_unique_id;
      public $edit_user_unique_id;
      public $category_unique_id;
      public $mini_category_unique_id;
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

      function get_all_mini_categories(){

        try {
          $this->conn->beginTransaction();

          $sql = "SELECT mini_category.id, mini_category.unique_id, mini_category.user_unique_id, mini_category.edit_user_unique_id, mini_category.category_unique_id, mini_category.sub_category_unique_id, mini_category.name, mini_category.stripped, mini_category.added_date, mini_category.last_modified, mini_category.status,
          categories.name as category_name, sub_category.name as sub_category_name, management.fullname as added_fullname, management_alt.fullname as edit_user_fullname FROM mini_category INNER JOIN management ON mini_category.user_unique_id = management.unique_id INNER JOIN management management_alt ON mini_category.edit_user_unique_id = management_alt.unique_id
          LEFT JOIN categories ON mini_category.category_unique_id = categories.unique_id LEFT JOIN sub_category ON mini_category.sub_category_unique_id = sub_category.unique_id ORDER BY mini_category.added_date DESC";
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

      function get_all_mini_categories_for_users(){

        try {
          $this->conn->beginTransaction();

          $sql = "SELECT mini_category.unique_id, mini_category.category_unique_id, mini_category.sub_category_unique_id, mini_category.name, mini_category.stripped,
          categories.name as category_name, sub_category.name as sub_category_name FROM mini_category LEFT JOIN categories ON mini_category.category_unique_id = categories.unique_id LEFT JOIN sub_category ON mini_category.sub_category_unique_id = sub_category.unique_id ORDER BY mini_category.added_date DESC";
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

      function get_user_mini_categories($user_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT mini_category.id, mini_category.unique_id, mini_category.user_unique_id, mini_category.edit_user_unique_id, mini_category.name, mini_category.stripped, mini_category.added_date, mini_category.last_modified, mini_category.status,
            categories.name as category_name, sub_category.name as sub_category_name, management.fullname as added_fullname, management_alt.fullname as edit_user_fullname FROM mini_category INNER JOIN management ON mini_category.user_unique_id = management.unique_id INNER JOIN management management_alt ON mini_category.edit_user_unique_id = management_alt.unique_id
            LEFT JOIN categories ON mini_category.category_unique_id = categories.unique_id LEFT JOIN sub_category ON mini_category.sub_category_unique_id = sub_category.unique_id WHERE mini_category.user_unique_id=:user_unique_id ORDER BY mini_category.added_date DESC";
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

      function get_mini_category_details($mini_category_unique_id){
        if (!in_array($mini_category_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT mini_category.id, mini_category.unique_id, mini_category.user_unique_id, mini_category.edit_user_unique_id, mini_category.name, mini_category.stripped, mini_category.added_date, mini_category.last_modified, mini_category.status,
            categories.name as category_name, sub_category.name as sub_category_name, management.fullname as added_fullname, management_alt.fullname as edit_user_fullname FROM mini_category INNER JOIN management ON mini_category.user_unique_id = management.unique_id INNER JOIN management management_alt ON mini_category.edit_user_unique_id = management_alt.unique_id
            LEFT JOIN categories ON mini_category.category_unique_id = categories.unique_id LEFT JOIN sub_category ON mini_category.sub_category_unique_id = sub_category.unique_id WHERE mini_category.unique_id=:unique_id ORDER BY mini_category.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $mini_category_unique_id);
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

      function get_mini_category_details_for_users($mini_category_unique_id, $stripped){
        if (!in_array($mini_category_unique_id,$this->not_allowed_values) || !in_array($stripped,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT mini_category.unique_id, mini_category.name, mini_category.stripped, categories.name as category_name, sub_category.name as sub_category_name FROM mini_category
            LEFT JOIN categories ON mini_category.category_unique_id = categories.unique_id LEFT JOIN sub_category ON mini_category.sub_category_unique_id = sub_category.unique_id WHERE mini_category.unique_id=:unique_id OR mini_category.stripped=:stripped ORDER BY mini_category.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $mini_category_unique_id);
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

      function get_sub_category_mini_categories($sub_category_unique_id){
        if (!in_array($sub_category_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT mini_category.id, mini_category.unique_id, mini_category.user_unique_id, mini_category.edit_user_unique_id, mini_category.name, mini_category.stripped, mini_category.added_date, mini_category.last_modified, mini_category.status,
            categories.name as category_name, sub_category.name as sub_category_name, management.fullname as added_fullname, management_alt.fullname as edit_user_fullname FROM mini_category INNER JOIN management ON mini_category.user_unique_id = management.unique_id INNER JOIN management management_alt ON mini_category.edit_user_unique_id = management_alt.unique_id
            LEFT JOIN categories ON mini_category.category_unique_id = categories.unique_id LEFT JOIN sub_category ON mini_category.sub_category_unique_id = sub_category.unique_id WHERE mini_category.sub_category_unique_id=:sub_category_unique_id ORDER BY mini_category.added_date DESC";
            $query = $this->conn->prepare($sql);
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

      function get_sub_category_mini_categories_for_users($sub_category_unique_id){
        if (!in_array($sub_category_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT mini_category.unique_id, mini_category.name, mini_category.stripped, categories.name as category_name, sub_category.name as sub_category_name FROM mini_category
            LEFT JOIN categories ON mini_category.category_unique_id = categories.unique_id LEFT JOIN sub_category ON mini_category.sub_category_unique_id = sub_category.unique_id WHERE mini_category.sub_category_unique_id=:sub_category_unique_id ORDER BY mini_category.added_date DESC";
            $query = $this->conn->prepare($sql);
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

      function get_null_sub_category_mini_categories(){
        try {
          $this->conn->beginTransaction();

          $sql = "SELECT mini_category.id, mini_category.unique_id, mini_category.user_unique_id, mini_category.edit_user_unique_id, mini_category.name, mini_category.stripped, mini_category.added_date, mini_category.last_modified, mini_category.status,
          categories.name as category_name, sub_category.name as sub_category_name, management.fullname as added_fullname, management_alt.fullname as edit_user_fullname FROM mini_category INNER JOIN management ON mini_category.user_unique_id = management.unique_id INNER JOIN management management_alt ON mini_category.edit_user_unique_id = management_alt.unique_id
          LEFT JOIN categories ON mini_category.category_unique_id = categories.unique_id LEFT JOIN sub_category ON mini_category.sub_category_unique_id = sub_category.unique_id WHERE mini_category.sub_category_unique_id IS NULL OR mini_category.sub_category_unique_id='' ORDER BY mini_category.added_date DESC";
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

      function get_null_sub_category_mini_categories_for_users(){
        try {
          $this->conn->beginTransaction();

          $sql = "SELECT mini_category.unique_id, mini_category.name, mini_category.stripped, categories.name as category_name, sub_category.name as sub_category_name FROM mini_category
          LEFT JOIN categories ON mini_category.category_unique_id = categories.unique_id LEFT JOIN sub_category ON mini_category.sub_category_unique_id = sub_category.unique_id WHERE mini_category.sub_category_unique_id IS NULL OR mini_category.sub_category_unique_id='' ORDER BY mini_category.added_date DESC";
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
