<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';

  class DefaultPickupLocations{

      // database connection and table name
      private $conn;
      private $table_name = "default_pickup_locations";

      // object properties
      public $id;
      public $unique_id;
      public $user_unique_id;
      public $edit_user_unique_id;
      public $firstname;
      public $lastname;
      public $address;
      public $additional_information;
      public $city;
      public $state;
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

      function get_all_default_pickup_locations(){

        try {
          $this->conn->beginTransaction();

          $sql = "SELECT default_pickup_locations.id, default_pickup_locations.unique_id, default_pickup_locations.user_unique_id, default_pickup_locations.edit_user_unique_id, default_pickup_locations.firstname as address_first_name, default_pickup_locations.lastname as address_last_name, default_pickup_locations.address, default_pickup_locations.additional_information, default_pickup_locations.city, default_pickup_locations.state, default_pickup_locations.country,
          default_pickup_locations.added_date, default_pickup_locations.last_modified, default_pickup_locations.status, management.fullname as added_user_fullname, management_alt.fullname as edit_user_fullname FROM default_pickup_locations
          INNER JOIN management ON default_pickup_locations.user_unique_id = management.unique_id INNER JOIN management management_alt ON default_pickup_locations.edit_user_unique_id = management_alt.unique_id ORDER BY default_pickup_locations.added_date DESC";
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

      function get_all_default_pickup_locations_for_shipping_fees(){

        try {
          $this->conn->beginTransaction();

          $active = $this->functions->active;

          $sql = "SELECT default_pickup_locations.unique_id, default_pickup_locations.firstname as address_first_name, default_pickup_locations.lastname as address_last_name, default_pickup_locations.address,
          default_pickup_locations.additional_information, default_pickup_locations.city, default_pickup_locations.state, default_pickup_locations.country
          FROM default_pickup_locations WHERE default_pickup_locations.status=:status ORDER BY default_pickup_locations.added_date DESC";
          $query = $this->conn->prepare($sql);
          $query->bindParam(":status", $active);
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

      function get_all_default_pickup_locations_for_users(){

        try {
          $this->conn->beginTransaction();

          $active = $this->functions->active;

          $sql = "SELECT default_pickup_locations.firstname as address_first_name, default_pickup_locations.lastname as address_last_name, default_pickup_locations.address,
          default_pickup_locations.additional_information, default_pickup_locations.city, default_pickup_locations.state, default_pickup_locations.country
          FROM default_pickup_locations WHERE default_pickup_locations.status=:status ORDER BY default_pickup_locations.country ASC, default_pickup_locations.state ASC, default_pickup_locations.city ASC";
          $query = $this->conn->prepare($sql);
          $query->bindParam(":status", $active);
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
