<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';
  // include_once '../config/connect.php';

  class Management{

      // database connection and table name
      private $conn;
      private $table_name = "management";

      // object properties
      public $id;
      public $unique_id;
      public $edit_user_unique_id;
      public $fullname;
      public $email;
      public $role;
      public $added_date;
      public $last_modified;
      public $access;
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

      function get_all_management_users(){

        try {
          $this->conn->beginTransaction();

          $sql = "SELECT management.id, management.unique_id, management.edit_user_unique_id, management.fullname, management.email, management.phone_number, management.role, management.added_date, management.last_modified, management.access, management.status, management_alt.fullname as edit_user_fullname FROM management
          INNER JOIN management management_alt ON management.edit_user_unique_id = management_alt.unique_id ORDER BY management.added_date DESC";
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

      function get_edit_management_users($edit_user_unique_id){
        if (!in_array($edit_user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT management.id, management.unique_id, management.edit_user_unique_id, management.fullname, management.email, management.phone_number, management.role, management.added_date, management.last_modified, management.access, management.status, management_alt.fullname as edit_user_fullname FROM management
            INNER JOIN management management_alt ON management.edit_user_unique_id = management_alt.unique_id WHERE management.edit_user_unique_id=:edit_user_unique_id ORDER BY management.added_date DESC";
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

      function get_management_user_details($user_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT management.id, management.unique_id, management.edit_user_unique_id, management.fullname, management.email, management.phone_number, management.role, management.added_date, management.last_modified, management.access, management.status, management_alt.fullname as edit_user_fullname FROM management
            INNER JOIN management management_alt ON management.edit_user_unique_id = management_alt.unique_id WHERE management.unique_id=:unique_id ORDER BY management.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $user_unique_id);
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
