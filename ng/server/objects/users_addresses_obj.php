<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';

  class UsersAddresses{

      // database connection and table name
      private $conn;
      private $table_name = "users_addresses";

      // object properties
      public $id;
      public $unique_id;
      public $user_unique_id;
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

      function get_all_users_addresses(){

        try {
          $this->conn->beginTransaction();

          $sql = "SELECT users_addresses.id, users_addresses.unique_id, users_addresses.user_unique_id, users_addresses.firstname, users_addresses.lastname, users_addresses.address,
          users_addresses.additional_information, users_addresses.city, users_addresses.state, users_addresses.country, users_addresses.default_status, users_addresses.added_date, users_addresses.last_modified, users_addresses.status, users.fullname as user_fullname FROM users_addresses
          INNER JOIN users ON users_addresses.user_unique_id = users.unique_id ORDER BY FIELD (users_addresses.default_status, 'Yes') DESC, users_addresses.added_date DESC";
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

      function get_user_addresses($user_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT users_addresses.id, users_addresses.unique_id, users_addresses.user_unique_id, users_addresses.firstname, users_addresses.lastname, users_addresses.address,
            users_addresses.additional_information, users_addresses.city, users_addresses.state, users_addresses.country, users_addresses.default_status, users_addresses.added_date, users_addresses.last_modified, users_addresses.status, users.fullname as user_fullname FROM users_addresses
            INNER JOIN users ON users_addresses.user_unique_id = users.unique_id WHERE users_addresses.user_unique_id=:user_unique_id ORDER BY FIELD (users_addresses.default_status, 'Yes') DESC, users_addresses.added_date DESC";
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

      function get_user_addresses_for_users($user_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;

            $sql = "SELECT users_addresses.id, users_addresses.unique_id, users_addresses.user_unique_id, users_addresses.firstname, users_addresses.lastname, users_addresses.address,
            users_addresses.additional_information, users_addresses.city, users_addresses.state, users_addresses.country, users_addresses.default_status, users_addresses.added_date, users_addresses.last_modified, users_addresses.status, users.fullname as user_fullname FROM users_addresses
            INNER JOIN users ON users_addresses.user_unique_id = users.unique_id WHERE users_addresses.user_unique_id=:user_unique_id AND users_addresses.status=:status ORDER BY FIELD (users_addresses.default_status, 'Yes') DESC, users_addresses.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
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
        else {
          $output['error'] = true;
          $output['message'] = "All fields are required";
          return $output;
        }

      }

      function get_user_default_address($user_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;
            $Yes = $this->functions->Yes;

            $sql = "SELECT users_addresses.id, users_addresses.unique_id, users_addresses.user_unique_id, users_addresses.firstname, users_addresses.lastname, users_addresses.address,
            users_addresses.additional_information, users_addresses.city, users_addresses.state, users_addresses.country, users_addresses.default_status, users_addresses.added_date, users_addresses.last_modified, users_addresses.status, users.fullname as user_fullname FROM users_addresses
            INNER JOIN users ON users_addresses.user_unique_id = users.unique_id WHERE users_addresses.user_unique_id=:user_unique_id AND users_addresses.default_status=:default_status AND users_addresses.status=:status ORDER BY users_addresses.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->bindParam(":default_status", $Yes);
            $query->bindParam(":status", $active);
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

      function get_user_address_details($user_unique_id, $user_address_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values) && !in_array($user_address_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT users_addresses.id, users_addresses.unique_id, users_addresses.user_unique_id, users_addresses.firstname, users_addresses.lastname, users_addresses.address,
            users_addresses.additional_information, users_addresses.city, users_addresses.state, users_addresses.country, users_addresses.default_status, users_addresses.added_date, users_addresses.last_modified, users_addresses.status, users.fullname as user_fullname FROM users_addresses
            INNER JOIN users ON users_addresses.user_unique_id = users.unique_id WHERE users_addresses.unique_id=:unique_id AND users_addresses.user_unique_id=:user_unique_id ORDER BY users_addresses.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $user_address_unique_id);
            $query->bindParam(":user_unique_id", $user_unique_id);
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
