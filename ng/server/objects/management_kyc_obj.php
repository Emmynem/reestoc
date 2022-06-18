<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';

  class ManagementKYC{

      // database connection and table name
      private $conn;
      private $table_name = "management_kyc";

      // object properties
      public $id;
      public $unique_id;
      public $user_unique_id;
      public $type;
      public $code;
      public $front_image;
      public $back_image;
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

      function get_all_management_users_kyc(){

        try {
          $this->conn->beginTransaction();

          $sql = "SELECT management_kyc.id, management_kyc.unique_id, management_kyc.user_unique_id, management_kyc.type, management_kyc.code, management_kyc.front_image, management_kyc.back_image, management_kyc.added_date, management_kyc.last_modified, management_kyc.approval, management_kyc.status, management.fullname as user_fullname FROM management_kyc
          INNER JOIN management ON management_kyc.user_unique_id = management.unique_id ORDER BY management_kyc.added_date DESC";
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

      function get_management_user_kyc_details($management_user_unique_id){
        if (!in_array($management_user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT management_kyc.id, management_kyc.unique_id, management_kyc.user_unique_id, management_kyc.type, management_kyc.code, management_kyc.front_image, management_kyc.back_image, management_kyc.added_date, management_kyc.last_modified, management_kyc.approval, management_kyc.status, management.fullname as user_fullname FROM management_kyc
            INNER JOIN management ON management_kyc.user_unique_id = management.unique_id WHERE management_kyc.user_unique_id=:user_unique_id ORDER BY management_kyc.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $management_user_unique_id);
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
