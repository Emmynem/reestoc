<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';

  class Shipments{

      // database connection and table name
      private $conn;
      private $table_name = "shipments";

      // object properties
      public $id;
      public $unique_id;
      public $shipment_unique_id;
      public $rider_unique_id;
      public $current_location;
      public $longitude;
      public $latitude;
      public $delivery_status;
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

      function get_all_shipments(){

        try {
          $this->conn->beginTransaction();

          $sql = "SELECT shipments.id, shipments.unique_id, shipments.user_unique_id, shipments.edit_user_unique_id, shipments.shipment_unique_id, shipments.rider_unique_id, shipments.current_location, shipments.longitude, shipments.latitude,
          shipments.delivery_status, shipments.added_date, shipments.last_modified, shipments.status, management.fullname as user_fullname, riders.fullname as rider_fullname, riders.phone_number as rider_phone_number FROM shipments
          INNER JOIN management ON shipments.user_unique_id = management.unique_id INNER JOIN riders ON shipments.rider_unique_id = riders.unique_id ORDER BY shipments.added_date DESC";
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

      function get_shipments_filter($start_date, $end_date){

        if (!in_array($start_date,$this->not_allowed_values) && !in_array($end_date,$this->not_allowed_values)) {

          try {
            $this->conn->beginTransaction();

            $sql = "SELECT shipments.id, shipments.unique_id, shipments.shipment_unique_id, shipments.rider_unique_id, shipments.current_location, shipments.longitude, shipments.latitude,
            shipments.delivery_status, shipments.added_date, shipments.last_modified, shipments.status, management.fullname as user_fullname, riders.fullname as rider_fullname, riders.phone_number as rider_phone_number FROM shipments
            INNER JOIN management ON shipments.user_unique_id = management.unique_id INNER JOIN riders ON shipments.rider_unique_id = riders.unique_id WHERE shipments.added_date >:start_date AND (shipments.added_date <:end_date OR shipments.added_date=:end_date) ORDER BY shipments.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":start_date", $start_date);
            $query->bindParam(":end_date", $end_date);
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

      function get_management_shipment_details($unique_id, $shipment_unique_id){

        if (!in_array($unique_id,$this->not_allowed_values) && !in_array($shipment_unique_id,$this->not_allowed_values)) {

          try {
            $this->conn->beginTransaction();

            $sql = "SELECT shipments.id, shipments.unique_id, shipments.shipment_unique_id, shipments.rider_unique_id, shipments.current_location, shipments.longitude, shipments.latitude,
            shipments.delivery_status, shipments.added_date, shipments.last_modified, shipments.status, riders.fullname as rider_fullname, riders.phone_number as rider_phone_number, riders.email as rider_email FROM shipments
            INNER JOIN riders ON shipments.rider_unique_id = riders.unique_id WHERE shipments.unique_id=:unique_id AND shipments.shipment_unique_id=:shipment_unique_id ORDER BY shipments.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $unique_id);
            $query->bindParam(":shipment_unique_id", $shipment_unique_id);
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

      function get_all_available_shipments(){

        try {
          $this->conn->beginTransaction();

          $processing = $this->functions->processing;

          $sql = "SELECT shipments.id, shipments.unique_id, shipments.shipment_unique_id, shipments.rider_unique_id, shipments.current_location, shipments.longitude, shipments.latitude,
          shipments.delivery_status, shipments.added_date, shipments.last_modified, shipments.status, riders.fullname as rider_fullname, riders.phone_number as rider_phone_number FROM shipments
          INNER JOIN riders ON shipments.rider_unique_id = riders.unique_id WHERE shipments.delivery_status=:delivery_status ORDER BY shipments.added_date DESC";
          $query = $this->conn->prepare($sql);
          $query->bindParam(":delivery_status", $processing);
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

      function get_rider_shipments($rider_unique_id){
        if (!in_array($rider_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT shipments.id, shipments.unique_id, shipments.shipment_unique_id, shipments.rider_unique_id, shipments.current_location, shipments.longitude, shipments.latitude,
            shipments.delivery_status, shipments.added_date, shipments.last_modified, shipments.status, riders.fullname as rider_fullname, riders.phone_number as rider_phone_number FROM shipments
            INNER JOIN riders ON shipments.rider_unique_id = riders.unique_id WHERE shipments.rider_unique_id=:rider_unique_id ORDER BY shipments.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":rider_unique_id", $rider_unique_id);
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
