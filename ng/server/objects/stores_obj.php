<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';

  class Stores{

      // database connection and table name
      private $conn;
      private $table_name = "stores";

      // object properties
      public $id;
      public $unique_id;
      public $user_unique_id;
      public $edit_user_unique_id;
      public $name;
      public $details;
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

      function get_all_stores(){

        try {
          $this->conn->beginTransaction();

          $sql = "SELECT stores.id, stores.unique_id, stores.user_unique_id, stores.name, stores.stripped, stores.details, stores.added_date, stores.last_modified, stores.status, store_users.fullname as store_owner_fullname FROM stores
          INNER JOIN store_users ON stores.user_unique_id = store_users.unique_id ORDER BY stores.added_date DESC";
          $query = $this->conn->prepare($sql);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_store = array();
              $current_store['id'] = $value['id'];
              $current_store['unique_id'] = $value['unique_id'];
              $current_store['name'] = $value['name'];
              $current_store['stripped'] = $value['stripped'];
              $current_store['details'] = $value['details'];
              $current_store['added_date'] = $value['added_date'];
              $current_store['last_modified'] = $value['last_modified'];
              $current_store['store_owner_fullname'] = $value['store_owner_fullname'];
              $current_store['status'] = $value['status'];

              $store_id = $value['unique_id'];

              $sql2 = "SELECT image FROM store_images WHERE store_unique_id=:store_unique_id LIMIT 1";
              $query2 = $this->conn->prepare($sql2);
              $query2->bindParam(":store_unique_id", $store_id);
              $query2->execute();

              $images_result = $query2->fetchAll();

              if ($query2->rowCount() > 0) {
                $current_store_images = array();

                foreach ($images_result as $key => $image_value) {
                  $current_store_images[] = $image_value['image'];
                }

                $current_store['store_images'] = $current_store_images;
              }
              else{
                $current_store['store_images'] = null;
              }

              $store_array[] = $current_store;

            }
            return $store_array;
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

      function get_all_stores_for_users(){

        try {
          $this->conn->beginTransaction();

          $active = $this->functions->active;

          $sql = "SELECT stores.unique_id, stores.name, stores.stripped, stores.details FROM stores WHERE stores.status=:status ORDER BY stores.added_date DESC";
          $query = $this->conn->prepare($sql);
          $query->bindParam(":status", $active);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_store = array();
              $current_store['unique_id'] = $value['unique_id'];
              $current_store['name'] = $value['name'];
              $current_store['stripped'] = $value['stripped'];
              $current_store['details'] = $value['details'];

              $store_id = $value['unique_id'];

              $sql2 = "SELECT image FROM store_images WHERE store_unique_id=:store_unique_id LIMIT 1";
              $query2 = $this->conn->prepare($sql2);
              $query2->bindParam(":store_unique_id", $store_id);
              $query2->execute();

              $images_result = $query2->fetchAll();

              if ($query2->rowCount() > 0) {
                $current_store_images = array();

                foreach ($images_result as $key => $image_value) {
                  $current_store_images[] = $image_value['image'];
                }

                $current_store['store_images'] = $current_store_images;
              }
              else{
                $current_store['store_images'] = null;
              }

              $store_array[] = $current_store;

            }
            return $store_array;
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

      function get_store_details($store_unique_id){
        if (!in_array($store_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $store_array = array();

            $sql = "SELECT stores.id, stores.unique_id, stores.user_unique_id, stores.name, stores.stripped, stores.details, stores.added_date, stores.last_modified, stores.status, store_users.fullname as store_owner_fullname FROM stores
            INNER JOIN store_users ON stores.user_unique_id = store_users.unique_id WHERE stores.unique_id=:unique_id ORDER BY stores.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $store_unique_id);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_store = array();
                $current_store['id'] = $value['id'];
                $current_store['unique_id'] = $value['unique_id'];
                $current_store['name'] = $value['name'];
                $current_store['stripped'] = $value['stripped'];
                $current_store['details'] = $value['details'];
                $current_store['added_date'] = $value['added_date'];
                $current_store['last_modified'] = $value['last_modified'];
                $current_store['store_owner_fullname'] = $value['store_owner_fullname'];
                $current_store['status'] = $value['status'];

                $store_id = $value['unique_id'];

                $sql2 = "SELECT image FROM store_images WHERE store_unique_id=:store_unique_id LIMIT 1";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":store_unique_id", $store_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_store_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_store_images[] = $image_value['image'];
                  }

                  $current_store['store_images'] = $current_store_images;
                }
                else{
                  $current_store['store_images'] = null;
                }

                $store_array[] = $current_store;

              }
              return $store_array;
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

      function get_store_details_for_users($store_unique_id){
        if (!in_array($store_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;

            $store_array = array();

            $sql = "SELECT stores.unique_id, stores.name, stores.stripped, stores.details FROM stores WHERE stores.unique_id=:unique_id AND stores.status=:status ORDER BY stores.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $store_unique_id);
            $query->bindParam(":status", $active);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_store = array();
                $current_store['unique_id'] = $value['unique_id'];
                $current_store['name'] = $value['name'];
                $current_store['stripped'] = $value['stripped'];
                $current_store['details'] = $value['details'];

                $store_id = $value['unique_id'];

                $sql2 = "SELECT image FROM store_images WHERE store_unique_id=:store_unique_id LIMIT 1";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":store_unique_id", $store_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_store_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_store_images[] = $image_value['image'];
                  }

                  $current_store['store_images'] = $current_store_images;
                }
                else{
                  $current_store['store_images'] = null;
                }

                $store_array[] = $current_store;

              }
              return $store_array;
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
