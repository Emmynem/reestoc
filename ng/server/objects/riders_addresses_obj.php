<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';

  class RidersAddresses{

      // database connection and table name
      private $conn;
      private $table_name = "riders_addresses";

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
      private $not_allowed_values;

      public $output = array('error' => false, 'success' => false);

      // constructor with $db as database connection
      public function __construct($db){
          $this->conn = $db;
          $this->functions = new Functions();
          $this->not_allowed_values = $this->functions->not_allowed_values;
      }

      function get_all_riders_addresses(){

        try {
          $this->conn->beginTransaction();

          $sql = "SELECT riders_addresses.id, riders_addresses.unique_id, riders_addresses.user_unique_id, riders_addresses.firstname, riders_addresses.lastname, riders_addresses.address,
          riders_addresses.additional_information, riders_addresses.region, riders_addresses.city, riders_addresses.country, riders_addresses.added_date, riders_addresses.last_modified, riders_addresses.status, riders.fullname as user_fullname FROM riders_addresses
          INNER JOIN riders ON riders_addresses.user_unique_id = riders.unique_id ORDER BY riders_addresses.added_date DESC";
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

      function get_rider_addresses($rider_user_unique_id){
        if (!in_array($rider_user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT riders_addresses.id, riders_addresses.unique_id, riders_addresses.user_unique_id, riders_addresses.firstname, riders_addresses.lastname, riders_addresses.address,
            riders_addresses.additional_information, riders_addresses.region, riders_addresses.city, riders_addresses.country, riders_addresses.added_date, riders_addresses.last_modified, riders_addresses.status, riders.fullname as user_fullname FROM riders_addresses
            INNER JOIN riders ON riders_addresses.user_unique_id = riders.unique_id WHERE riders_addresses.user_unique_id=:user_unique_id ORDER BY riders_addresses.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $rider_user_unique_id);
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

      function get_rider_address_details($rider_user_unique_id, $rider_address_unique_id){
        if (!in_array($rider_user_unique_id,$this->not_allowed_values) && !in_array($rider_address_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT riders_addresses.id, riders_addresses.unique_id, riders_addresses.user_unique_id, riders_addresses.firstname, riders_addresses.lastname, riders_addresses.address,
            riders_addresses.additional_information, riders_addresses.region, riders_addresses.city, riders_addresses.country, riders_addresses.added_date, riders_addresses.last_modified, riders_addresses.status, riders.fullname as user_fullname FROM riders_addresses
            INNER JOIN riders ON riders_addresses.user_unique_id = riders.unique_id WHERE riders_addresses.unique_id=:unique_id AND riders_addresses.user_unique_id=:user_unique_id ORDER BY riders_addresses.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $rider_address_unique_id);
            $query->bindParam(":user_unique_id", $rider_user_unique_id);
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
