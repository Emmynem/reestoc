<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';

  class ManagementAddresses{

      // database connection and table name
      private $conn;
      private $table_name = "management_addresses";

      // object properties
      public $id;
      public $unique_id;
      public $user_unique_id;
      public $firstname;
      public $lastname;
      public $address;
      public $additional_information;
      public $region;
      public $city;
      public $country;
      public $added_date;
      public $last_modified;
      public $status;

      private $functions;
      private $products;

      public $output = array('error' => false, 'success' => false);

      // constructor with $db as database connection
      public function __construct($db){
          $this->conn = $db;
          $this->functions = new Functions();
          $this->not_allowed_values = $this->functions->not_allowed_values;
      }

      function get_all_management_addresses(){

        try {
          $this->conn->beginTransaction();

          $sql = "SELECT management_addresses.id, management_addresses.unique_id, management_addresses.user_unique_id, management_addresses.firstname, management_addresses.lastname, management_addresses.address,
          management_addresses.additional_information, management_addresses.region, management_addresses.city, management_addresses.country, management_addresses.added_date, management_addresses.last_modified, management_addresses.status, management.fullname as user_fullname FROM management_addresses
          INNER JOIN management ON management_addresses.user_unique_id = management.unique_id ORDER BY management_addresses.added_date DESC";
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

      function get_management_user_addresses($management_user_unique_id){
        if (!in_array($management_user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT management_addresses.id, management_addresses.unique_id, management_addresses.user_unique_id, management_addresses.firstname, management_addresses.lastname, management_addresses.address,
            management_addresses.additional_information, management_addresses.region, management_addresses.city, management_addresses.country, management_addresses.added_date, management_addresses.last_modified, management_addresses.status, management.fullname as user_fullname FROM management_addresses
            INNER JOIN management ON management_addresses.user_unique_id = management.unique_id WHERE management_addresses.user_unique_id=:user_unique_id ORDER BY management_addresses.added_date DESC";
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

      function get_management_user_address_details($management_user_unique_id, $management_user_address_unique_id){
        if (!in_array($management_user_unique_id,$this->not_allowed_values) && !in_array($management_user_address_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT management_addresses.id, management_addresses.unique_id, management_addresses.user_unique_id, management_addresses.firstname, management_addresses.lastname, management_addresses.address,
            management_addresses.additional_information, management_addresses.region, management_addresses.city, management_addresses.country, management_addresses.added_date, management_addresses.last_modified, management_addresses.status, management.fullname as user_fullname FROM management_addresses
            INNER JOIN management ON management_addresses.user_unique_id = management.unique_id WHERE management_addresses.unique_id=:unique_id AND management_addresses.user_unique_id=:user_unique_id ORDER BY management_addresses.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $management_user_address_unique_id);
            $query->bindParam(":user_unique_id", $management_user_unique_id);
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
