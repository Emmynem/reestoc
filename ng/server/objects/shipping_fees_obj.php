<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';

  class ShippingFees{

      // database connection and table name
      private $conn;
      private $table_name = "shipping_fees";

      // object properties
      public $id;
      public $unique_id;
      public $user_unique_id;
      public $edit_user_unique_id;
      public $sub_product_unique_id;
      public $location;
      public $price;
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

      function get_all_shipping_fees(){

        try {
          $this->conn->beginTransaction();

          $sql = "SELECT shipping_fees.id, shipping_fees.unique_id, shipping_fees.user_unique_id, shipping_fees.edit_user_unique_id, shipping_fees.sub_product_unique_id, shipping_fees.city, shipping_fees.state, shipping_fees.country, shipping_fees.price,
          shipping_fees.added_date, shipping_fees.last_modified, shipping_fees.status, management.fullname as added_user_fullname, management_alt.fullname as edit_user_fullname, sub_products.name as sub_product_name, sub_products.size as sub_product_size FROM shipping_fees
          LEFT JOIN sub_products ON shipping_fees.sub_product_unique_id = sub_products.unique_id INNER JOIN management ON shipping_fees.user_unique_id = management.unique_id INNER JOIN management management_alt ON shipping_fees.edit_user_unique_id = management_alt.unique_id ORDER BY shipping_fees.added_date DESC";
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

      function get_all_pickup_location_shipping_fees(){

        try {
          $this->conn->beginTransaction();

          $sql = "SELECT pickup_locations.id, pickup_locations.unique_id, pickup_locations.user_unique_id, pickup_locations.edit_user_unique_id, pickup_locations.sub_product_unique_id, pickup_locations.default_pickup_location_unique_id, default_pickup_locations.firstname as address_first_name, default_pickup_locations.lastname as address_last_name, default_pickup_locations.address,
          default_pickup_locations.additional_information, default_pickup_locations.city, default_pickup_locations.state, default_pickup_locations.country, pickup_locations.price,
          pickup_locations.added_date, pickup_locations.last_modified, pickup_locations.status, management.fullname as added_user_fullname, management_alt.fullname as edit_user_fullname, sub_products.name as sub_product_name, sub_products.size as sub_product_size FROM pickup_locations
          LEFT JOIN sub_products ON pickup_locations.sub_product_unique_id = sub_products.unique_id INNER JOIN management ON pickup_locations.user_unique_id = management.unique_id INNER JOIN management management_alt ON pickup_locations.edit_user_unique_id = management_alt.unique_id
          INNER JOIN default_pickup_locations ON pickup_locations.default_pickup_location_unique_id = default_pickup_locations.unique_id WHERE pickup_locations.sub_product_unique_id IS NOT NULL ORDER BY pickup_locations.added_date DESC";
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

      function get_all_sharing_shipping_fees(){

        try {
          $this->conn->beginTransaction();

          $sql = "SELECT sharing_shipping_fees.id, sharing_shipping_fees.unique_id, sharing_shipping_fees.user_unique_id, sharing_shipping_fees.edit_user_unique_id, sharing_shipping_fees.sharing_unique_id, sharing_shipping_fees.city, sharing_shipping_fees.state, sharing_shipping_fees.country, sharing_shipping_fees.price,
          sharing_shipping_fees.added_date, sharing_shipping_fees.last_modified, sharing_shipping_fees.status, management.fullname as added_user_fullname, management_alt.fullname as edit_user_fullname, sharing_items.name as sharing_item_name FROM sharing_shipping_fees
          LEFT JOIN sharing_items ON sharing_shipping_fees.sharing_unique_id = sharing_items.unique_id INNER JOIN management ON sharing_shipping_fees.user_unique_id = management.unique_id INNER JOIN management management_alt ON sharing_shipping_fees.edit_user_unique_id = management_alt.unique_id ORDER BY sharing_shipping_fees.added_date DESC";
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

      function get_all_pickup_location_sharing_shipping_fees(){

        try {
          $this->conn->beginTransaction();

          $sql = "SELECT pickup_locations.id, pickup_locations.unique_id, pickup_locations.user_unique_id, pickup_locations.edit_user_unique_id, pickup_locations.sharing_unique_id, pickup_locations.default_pickup_location_unique_id, default_pickup_locations.firstname as address_first_name, default_pickup_locations.lastname as address_last_name, default_pickup_locations.address,
          default_pickup_locations.additional_information, default_pickup_locations.city, default_pickup_locations.state, default_pickup_locations.country, pickup_locations.price,
          pickup_locations.added_date, pickup_locations.last_modified, pickup_locations.status, management.fullname as added_user_fullname, management_alt.fullname as edit_user_fullname, sharing_items.name as sharing_item_name FROM pickup_locations
          LEFT JOIN sharing_items ON pickup_locations.sharing_unique_id = sharing_items.unique_id INNER JOIN management ON pickup_locations.user_unique_id = management.unique_id INNER JOIN management management_alt ON pickup_locations.edit_user_unique_id = management_alt.unique_id
          INNER JOIN default_pickup_locations ON pickup_locations.default_pickup_location_unique_id = default_pickup_locations.unique_id WHERE pickup_locations.sharing_unique_id IS NOT NULL ORDER BY pickup_locations.added_date DESC";
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

      function get_sub_product_shipping_fees($sub_product_unique_id){
        if (!in_array($sub_product_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT shipping_fees.id, shipping_fees.unique_id, shipping_fees.user_unique_id, shipping_fees.edit_user_unique_id, shipping_fees.sub_product_unique_id, shipping_fees.city, shipping_fees.state, shipping_fees.country, shipping_fees.price,
            shipping_fees.added_date, shipping_fees.last_modified, shipping_fees.status, sub_products.name as sub_product_name, sub_products.size as sub_product_size FROM shipping_fees
            LEFT JOIN sub_products ON shipping_fees.sub_product_unique_id = sub_products.unique_id WHERE shipping_fees.sub_product_unique_id=:sub_product_unique_id ORDER BY shipping_fees.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":sub_product_unique_id", $sub_product_unique_id);
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

      function get_sub_product_shipping_fees_for_users($sub_product_unique_id){
        if (!in_array($sub_product_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;

            $sql = "SELECT shipping_fees.unique_id, shipping_fees.sub_product_unique_id, shipping_fees.city, shipping_fees.state, shipping_fees.country, shipping_fees.price, sub_products.name as sub_product_name, sub_products.size as sub_product_size FROM shipping_fees
            LEFT JOIN sub_products ON shipping_fees.sub_product_unique_id = sub_products.unique_id WHERE shipping_fees.sub_product_unique_id=:sub_product_unique_id AND shipping_fees.status=:status ORDER BY shipping_fees.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":sub_product_unique_id", $sub_product_unique_id);
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

  }
?>
