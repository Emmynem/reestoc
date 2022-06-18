<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';

  class Brands{

      // database connection and table name
      private $conn;
      private $table_name = "brands";

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

      function get_all_brands(){

        try {
          $this->conn->beginTransaction();

          $sql = "SELECT brands.id, brands.unique_id, brands.user_unique_id, brands.edit_user_unique_id, brands.name, brands.stripped, brands.details, brands.added_date, brands.last_modified, brands.status, management.fullname as added_user_fullname, management_alt.fullname as edit_user_fullname FROM brands
          INNER JOIN management ON brands.user_unique_id = management.unique_id INNER JOIN management management_alt ON brands.edit_user_unique_id = management_alt.unique_id ORDER BY brands.added_date DESC";
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

      function get_all_brands_for_select(){

        try {
          $this->conn->beginTransaction();

          $sql = "SELECT brands.id, brands.unique_id, brands.user_unique_id, brands.edit_user_unique_id, brands.name, brands.stripped, brands.details, brands.added_date, brands.last_modified, brands.status, management.fullname as added_user_fullname, management_alt.fullname as edit_user_fullname FROM brands
          INNER JOIN management ON brands.user_unique_id = management.unique_id INNER JOIN management management_alt ON brands.edit_user_unique_id = management_alt.unique_id ORDER BY brands.name ASC";
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

      function get_all_brands_for_users(){

        try {
          $this->conn->beginTransaction();

          $active = $this->functions->active;

          $sql = "SELECT brands.unique_id, brands.name, brands.stripped, brands.details FROM brands WHERE brands.status=:status ORDER BY brands.added_date DESC";
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

      function get_edit_management_brands($edit_user_unique_id){
        if (!in_array($edit_user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT brands.id, brands.unique_id, brands.user_unique_id, brands.edit_user_unique_id, brands.name, brands.stripped, brands.details, brands.added_date, brands.last_modified, brands.status, management.fullname as added_user_fullname, management_alt.fullname as edit_user_fullname FROM brands
            INNER JOIN management ON brands.user_unique_id = management.unique_id INNER JOIN management management_alt ON brands.edit_user_unique_id = management_alt.unique_id WHERE brands.edit_user_unique_id=:edit_user_unique_id ORDER BY brands.added_date DESC";
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

      function get_brand_details($brand_unique_id){
        if (!in_array($brand_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $brand_array = array();

            $sql = "SELECT brands.id, brands.unique_id, brands.user_unique_id, brands.edit_user_unique_id, brands.name, brands.stripped, brands.details, brands.added_date, brands.last_modified, brands.status, management.fullname as added_user_fullname, management_alt.fullname as edit_user_fullname FROM brands
            INNER JOIN management ON brands.user_unique_id = management.unique_id INNER JOIN management management_alt ON brands.edit_user_unique_id = management_alt.unique_id WHERE brands.unique_id=:unique_id ORDER BY brands.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $brand_unique_id);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_brand = array();
                $current_brand['id'] = $value['id'];
                $current_brand['unique_id'] = $value['unique_id'];
                $current_brand['name'] = $value['name'];
                $current_brand['stripped'] = $value['stripped'];
                $current_brand['details'] = $value['details'];
                $current_brand['added_date'] = $value['added_date'];
                $current_brand['last_modified'] = $value['last_modified'];
                $current_brand['added_user_fullname'] = $value['added_user_fullname'];
                $current_brand['edit_user_fullname'] = $value['edit_user_fullname'];
                $current_brand['status'] = $value['status'];

                $brand_id = $value['unique_id'];

                $sql2 = "SELECT image FROM brand_images WHERE brand_unique_id=:brand_unique_id LIMIT 1";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":brand_unique_id", $brand_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_brand_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_brand_images[] = $image_value['image'];
                  }

                  $current_brand['brand_images'] = $current_brand_images;
                }
                else{
                  $current_brand['brand_images'] = null;
                }

                $brand_array[] = $current_brand;

              }
              return $brand_array;
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

      function get_brand_details_for_users($brand_unique_id){
        if (!in_array($brand_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;

            $brand_array = array();

            $sql = "SELECT brands.unique_id, brands.name, brands.stripped, brands.details FROM brands WHERE brands.unique_id=:unique_id AND brands.status=:status ORDER BY brands.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $brand_unique_id);
            $query->bindParam(":status", $active);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_brand = array();
                $current_brand['unique_id'] = $value['unique_id'];
                $current_brand['name'] = $value['name'];
                $current_brand['stripped'] = $value['stripped'];
                $current_brand['details'] = $value['details'];

                $brand_id = $value['unique_id'];

                $sql2 = "SELECT image FROM brand_images WHERE brand_unique_id=:brand_unique_id LIMIT 1";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":brand_unique_id", $brand_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_brand_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_brand_images[] = $image_value['image'];
                  }

                  $current_brand['brand_images'] = $current_brand_images;
                }
                else{
                  $current_brand['brand_images'] = null;
                }

                $brand_array[] = $current_brand;

              }
              return $brand_array;
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
