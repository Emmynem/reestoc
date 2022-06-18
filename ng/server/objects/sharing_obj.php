<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';

  class Sharing{

      // database connection and table name
      private $conn;
      private $table_name = "sharing_items";

      // object properties
      public $id;
      public $unique_id;
      public $user_unique_id;
      public $edit_user_unique_id;
      public $name;
      public $stripped;
      public $description;
      public $total_price;
      public $split_price;
      public $total_no_of_persons;
      public $current_no_of_persons;
      public $expiration;
      public $expiry_date;
      public $completion;
      public $added_date;
      public $last_modified;
      public $status;

      private $functions;
      private $sharing;
      private $not_allowed_values;

      public $output = array('error' => false, 'success' => false);

      // constructor with $db as database connection
      public function __construct($db){
          $this->conn = $db;
          $this->functions = new Functions();
          $this->not_allowed_values = $this->functions->not_allowed_values;
      }

      public function get_all_sharing(){

        try {
          $this->conn->beginTransaction();

          $sharing_array = array();

          $sql = "SELECT sharing_items.id, sharing_items.unique_id, sharing_items.user_unique_id, sharing_items.edit_user_unique_id, sharing_items.name, sharing_items.stripped, sharing_items.description, sharing_items.total_price, sharing_items.split_price, sharing_items.total_no_of_persons,
          sharing_items.current_no_of_persons, sharing_items.expiration, sharing_items.starting_date, sharing_items.expiry_date, sharing_items.completion, sharing_items.added_date, sharing_items.last_modified, sharing_items.status, management.fullname as added_fullname, management_alt.fullname as edit_user_fullname FROM sharing_items
          INNER JOIN management ON sharing_items.user_unique_id = management.unique_id INNER JOIN management management_alt ON sharing_items.edit_user_unique_id = management_alt.unique_id ORDER BY sharing_items.added_date DESC";
          $query = $this->conn->prepare($sql);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_sharing = array();
              $current_sharing['id'] = $value['id'];
              $current_sharing['unique_id'] = $value['unique_id'];
              $current_sharing['user_unique_id'] = $value['user_unique_id'];
              $current_sharing['edit_user_unique_id'] = $value['edit_user_unique_id'];
              $current_sharing['name'] = $value['name'];
              $current_sharing['stripped'] = $value['stripped'];
              $current_sharing['description'] = $value['description'];
              $current_sharing['total_price'] = $value['total_price'];
              $current_sharing['split_price'] = $value['split_price'];
              $current_sharing['total_no_of_persons'] = $value['total_no_of_persons'];
              $current_sharing['current_no_of_persons'] = $value['current_no_of_persons'];
              $current_sharing['expiration'] = $value['expiration'];
              $current_sharing['starting_date'] = $value['starting_date'];
              $current_sharing['expiry_date'] = $value['expiry_date'];
              $current_sharing['completion'] = $value['completion'];
              $current_sharing['added_date'] = $value['added_date'];
              $current_sharing['last_modified'] = $value['last_modified'];
              $current_sharing['status'] = $value['status'];
              $current_sharing['added_fullname'] = $value['added_fullname'];
              $current_sharing['edit_user_fullname'] = $value['edit_user_fullname'];

              $sharing_id = $value['unique_id'];

              $sql2 = "SELECT image FROM sharing_images WHERE sharing_unique_id=:sharing_unique_id";
              $query2 = $this->conn->prepare($sql2);
              $query2->bindParam(":sharing_unique_id", $sharing_id);
              $query2->execute();

              $images_result = $query2->fetchAll();

              if ($query2->rowCount() > 0) {
                $current_sharing_images = array();

                foreach ($images_result as $key => $image_value) {
                  $current_sharing_images[] = $image_value['image'];
                }

                $current_sharing['sharing_images'] = $current_sharing_images;
              }
              else{
                $current_sharing['sharing_images'] = null;
              }

              $sharing_array[] = $current_sharing;
            }
            return $sharing_array;
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

      public function get_all_sharing_items_for_shipping_fees(){

        try {
          $this->conn->beginTransaction();

          $today = $this->functions->today;
          $active = $this->functions->active;
          $completion = $this->functions->started;
          $completed = $this->functions->completed;

          $sharing_array = array();

          $sql = "SELECT unique_id, name, total_price, split_price, total_no_of_persons,
          current_no_of_persons FROM sharing_items WHERE (expiration=:expiration AND expiry_date>:today AND completion!=:completion AND completion!=:completed) || (expiration!=:expiration AND completion!=:completion) ORDER BY added_date DESC";
          $query = $this->conn->prepare($sql);
          $query->bindParam(":expiration", $active);
          $query->bindParam(":today", $today);
          $query->bindParam(":completion", $completion);
          $query->bindParam(":completed", $completed);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_sharing = array();
              $current_sharing['unique_id'] = $value['unique_id'];
              $current_sharing['name'] = $value['name'];
              $current_sharing['total_price'] = $value['total_price'];
              $current_sharing['split_price'] = $value['split_price'];
              $current_sharing['total_no_of_persons'] = $value['total_no_of_persons'];
              $current_sharing['current_no_of_persons'] = $value['current_no_of_persons'];

              $sharing_array[] = $current_sharing;
            }
            return $sharing_array;
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

      public function get_sharing_filter($start_date, $end_date){

        if (!in_array($start_date,$this->not_allowed_values) && !in_array($end_date,$this->not_allowed_values)) {

          try {
            $this->conn->beginTransaction();

            $sharing_array = array();

            $sql = "SELECT sharing_items.id, sharing_items.unique_id, sharing_items.user_unique_id, sharing_items.edit_user_unique_id, sharing_items.name, sharing_items.stripped, sharing_items.description, sharing_items.total_price, sharing_items.split_price, sharing_items.total_no_of_persons,
            sharing_items.current_no_of_persons, sharing_items.expiration, sharing_items.starting_date, sharing_items.expiry_date, sharing_items.completion, sharing_items.added_date, sharing_items.last_modified, sharing_items.status, management.fullname as added_fullname, management_alt.fullname as edit_user_fullname FROM sharing_items
            INNER JOIN management ON sharing_items.user_unique_id = management.unique_id INNER JOIN management management_alt ON sharing_items.edit_user_unique_id = management_alt.unique_id
            WHERE sharing_items.added_date >:start_date AND (sharing_items.added_date <:end_date OR sharing_items.added_date=:end_date) ORDER BY sharing_items.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":start_date", $start_date);
            $query->bindParam(":end_date", $end_date);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_sharing = array();
                $current_sharing['id'] = $value['id'];
                $current_sharing['unique_id'] = $value['unique_id'];
                $current_sharing['user_unique_id'] = $value['user_unique_id'];
                $current_sharing['edit_user_unique_id'] = $value['edit_user_unique_id'];
                $current_sharing['name'] = $value['name'];
                $current_sharing['description'] = $value['description'];
                $current_sharing['total_price'] = $value['total_price'];
                $current_sharing['split_price'] = $value['split_price'];
                $current_sharing['total_no_of_persons'] = $value['total_no_of_persons'];
                $current_sharing['current_no_of_persons'] = $value['current_no_of_persons'];
                $current_sharing['expiration'] = $value['expiration'];
                $current_sharing['starting_date'] = $value['starting_date'];
                $current_sharing['expiry_date'] = $value['expiry_date'];
                $current_sharing['completion'] = $value['completion'];
                $current_sharing['added_date'] = $value['added_date'];
                $current_sharing['last_modified'] = $value['last_modified'];
                $current_sharing['status'] = $value['status'];
                $current_sharing['added_fullname'] = $value['added_fullname'];
                $current_sharing['edit_user_fullname'] = $value['edit_user_fullname'];

                $sharing_id = $value['unique_id'];

                $sql2 = "SELECT image FROM sharing_images WHERE sharing_unique_id=:sharing_unique_id";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":sharing_unique_id", $sharing_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_sharing_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_sharing_images[] = $image_value['image'];
                  }

                  $current_sharing['sharing_images'] = $current_sharing_images;
                }
                else{
                  $current_sharing['sharing_images'] = null;
                }

                $sharing_array[] = $current_sharing;
              }
              return $sharing_array;
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

      public function get_sharing_expiry_date_filter($start_date, $end_date){

        if (!in_array($start_date,$this->not_allowed_values) && !in_array($end_date,$this->not_allowed_values)) {

          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;

            $sharing_array = array();

            $sql = "SELECT sharing_items.id, sharing_items.unique_id, sharing_items.user_unique_id, sharing_items.edit_user_unique_id, sharing_items.name, sharing_items.stripped, sharing_items.description, sharing_items.total_price, sharing_items.split_price, sharing_items.total_no_of_persons,
            sharing_items.current_no_of_persons, sharing_items.expiration, sharing_items.starting_date, sharing_items.expiry_date, sharing_items.completion, sharing_items.added_date, sharing_items.last_modified, sharing_items.status, management.fullname as added_fullname, management_alt.fullname as edit_user_fullname FROM sharing_items
            INNER JOIN management ON sharing_items.user_unique_id = management.unique_id INNER JOIN management management_alt ON sharing_items.edit_user_unique_id = management_alt.unique_id
            WHERE sharing_items.expiration=:expiration AND sharing_items.expiry_date >:start_date AND (sharing_items.expiry_date <:end_date OR sharing_items.expiry_date=:end_date) ORDER BY sharing_items.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":expiration", $active);
            $query->bindParam(":start_date", $start_date);
            $query->bindParam(":end_date", $end_date);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_sharing = array();
                $current_sharing['id'] = $value['id'];
                $current_sharing['unique_id'] = $value['unique_id'];
                $current_sharing['user_unique_id'] = $value['user_unique_id'];
                $current_sharing['edit_user_unique_id'] = $value['edit_user_unique_id'];
                $current_sharing['name'] = $value['name'];
                $current_sharing['description'] = $value['description'];
                $current_sharing['total_price'] = $value['total_price'];
                $current_sharing['split_price'] = $value['split_price'];
                $current_sharing['total_no_of_persons'] = $value['total_no_of_persons'];
                $current_sharing['current_no_of_persons'] = $value['current_no_of_persons'];
                $current_sharing['expiration'] = $value['expiration'];
                $current_sharing['starting_date'] = $value['starting_date'];
                $current_sharing['expiry_date'] = $value['expiry_date'];
                $current_sharing['completion'] = $value['completion'];
                $current_sharing['added_date'] = $value['added_date'];
                $current_sharing['last_modified'] = $value['last_modified'];
                $current_sharing['status'] = $value['status'];
                $current_sharing['added_fullname'] = $value['added_fullname'];
                $current_sharing['edit_user_fullname'] = $value['edit_user_fullname'];

                $sharing_id = $value['unique_id'];

                $sql2 = "SELECT image FROM sharing_images WHERE sharing_unique_id=:sharing_unique_id";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":sharing_unique_id", $sharing_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_sharing_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_sharing_images[] = $image_value['image'];
                  }

                  $current_sharing['sharing_images'] = $current_sharing_images;
                }
                else{
                  $current_sharing['sharing_images'] = null;
                }

                $sharing_array[] = $current_sharing;
              }
              return $sharing_array;
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

      public function get_all_sharing_for_users(){

        try {
          $this->conn->beginTransaction();

          $active = $this->functions->active;
          $today = $this->functions->today;

          $sharing_array = array();

          $sql = "SELECT sharing_items.unique_id, sharing_items.name, sharing_items.stripped, sharing_items.description, sharing_items.total_price, sharing_items.split_price, sharing_items.total_no_of_persons,
          sharing_items.current_no_of_persons, sharing_items.expiration, sharing_items.starting_date, sharing_items.expiry_date, sharing_items.completion, sharing_items.added_date FROM sharing_items
          WHERE sharing_items.status=:status AND sharing_items.current_no_of_persons!=sharing_items.total_no_of_persons AND sharing_items.starting_date <:starting_date AND (sharing_items.expiration != :expiration || (sharing_items.expiration =:expiration && sharing_items.expiry_date >:expiry_date)) ORDER BY sharing_items.added_date DESC";
          $query = $this->conn->prepare($sql);
          $query->bindParam(":status", $active);
          $query->bindParam(":expiration", $active);
          $query->bindParam(":expiry_date", $today);
          $query->bindParam(":starting_date", $today);
          $query->execute();

          $result = $query->fetchAll();

          if ($query->rowCount() > 0) {
            foreach ($result as $key => $value) {

              $current_sharing = array();
              // $current_sharing['unique_id'] = $value['unique_id'];
              $current_sharing['name'] = $value['name'];
              $current_sharing['stripped'] = $value['stripped'];
              // $current_sharing['description'] = $value['description'];
              $current_sharing['total_price'] = $value['total_price'];
              $current_sharing['split_price'] = $value['split_price'];
              $current_sharing['total_no_of_persons'] = $value['total_no_of_persons'];
              $current_sharing['current_no_of_persons'] = $value['current_no_of_persons'];
              $current_sharing['expiration'] = $value['expiration'];
              // $current_sharing['starting_date'] = $value['starting_date'];
              $current_sharing['expiry_date'] = $value['expiry_date'];
              // $current_sharing['completion'] = $value['completion'];
              // $current_sharing['added_date'] = $value['added_date'];

              $sharing_id = $value['unique_id'];

              $sql2 = "SELECT image FROM sharing_images WHERE sharing_unique_id=:sharing_unique_id LIMIT 1";
              $query2 = $this->conn->prepare($sql2);
              $query2->bindParam(":sharing_unique_id", $sharing_id);
              $query2->execute();

              $images_result = $query2->fetchAll();

              if ($query2->rowCount() > 0) {
                $current_sharing_images = array();

                foreach ($images_result as $key => $image_value) {
                  $current_sharing_images[] = $image_value['image'];
                }

                $current_sharing['sharing_images'] = $current_sharing_images;
              }
              else{
                $current_sharing['sharing_images'] = null;
              }

              $sql3 = "SELECT users.image FROM users INNER JOIN sharing_users ON sharing_users.user_unique_id = users.unique_id WHERE sharing_users.sharing_unique_id=:sharing_unique_id LIMIT 3";
              $query3 = $this->conn->prepare($sql3);
              $query3->bindParam(":sharing_unique_id", $sharing_id);
              $query3->execute();

              $sharing_users_images_result = $query3->fetchAll();

              if ($query3->rowCount() > 0) {
                $current_sharing_users_images = array();

                foreach ($sharing_users_images_result as $key => $image_value) {
                  $current_sharing_users_images[] = $image_value['image'];
                }

                $current_sharing['sharing_users_images'] = $current_sharing_users_images;
              }
              else{
                $current_sharing['sharing_users_images'] = null;
              }

              $sharing_array[] = $current_sharing;
            }
            return $sharing_array;
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

      public function get_sharing_details($unique_id, $stripped){
        if (!in_array($unique_id,$this->not_allowed_values) || !in_array($stripped,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sharing_array = array();

            $sql = "SELECT sharing_items.id, sharing_items.unique_id, sharing_items.user_unique_id, sharing_items.edit_user_unique_id, sharing_items.name, sharing_items.stripped, sharing_items.description, sharing_items.total_price, sharing_items.split_price, sharing_items.total_no_of_persons,
            sharing_items.current_no_of_persons, sharing_items.expiration, sharing_items.starting_date, sharing_items.expiry_date, sharing_items.completion, sharing_items.added_date, sharing_items.last_modified, sharing_items.status, management.fullname as added_fullname, management_alt.fullname as edit_user_fullname FROM sharing_items
            INNER JOIN management ON sharing_items.user_unique_id = management.unique_id INNER JOIN management management_alt ON sharing_items.edit_user_unique_id = management_alt.unique_id
            WHERE sharing_items.unique_id=:unique_id OR sharing_items.stripped=:stripped ORDER BY sharing_items.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $unique_id);
            $query->bindParam(":stripped", $stripped);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_sharing = array();
                $current_sharing['id'] = $value['id'];
                $current_sharing['unique_id'] = $value['unique_id'];
                $current_sharing['user_unique_id'] = $value['user_unique_id'];
                $current_sharing['edit_user_unique_id'] = $value['edit_user_unique_id'];
                $current_sharing['name'] = $value['name'];
                $current_sharing['stripped'] = $value['stripped'];
                $current_sharing['description'] = $value['description'];
                $current_sharing['total_price'] = $value['total_price'];
                $current_sharing['split_price'] = $value['split_price'];
                $current_sharing['total_no_of_persons'] = $value['total_no_of_persons'];
                $current_sharing['current_no_of_persons'] = $value['current_no_of_persons'];
                $current_sharing['expiration'] = $value['expiration'];
                $current_sharing['starting_date'] = $value['starting_date'];
                $current_sharing['expiry_date'] = $value['expiry_date'];
                $current_sharing['completion'] = $value['completion'];
                $current_sharing['added_date'] = $value['added_date'];
                $current_sharing['last_modified'] = $value['last_modified'];
                $current_sharing['status'] = $value['status'];
                $current_sharing['added_fullname'] = $value['added_fullname'];
                $current_sharing['edit_user_fullname'] = $value['edit_user_fullname'];

                $sharing_id = $value['unique_id'];

                $sql2 = "SELECT image FROM sharing_images WHERE sharing_unique_id=:sharing_unique_id";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":sharing_unique_id", $sharing_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_sharing_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_sharing_images[] = $image_value['image'];
                  }

                  $current_sharing['sharing_images'] = $current_sharing_images;
                }
                else{
                  $current_sharing['sharing_images'] = null;
                }

                $sharing_array[] = $current_sharing;
              }
              return $sharing_array;
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
          $output['message'] = "At least one value is required";
          return $output;
        }

      }

      public function get_sharing_details_for_users($unique_id, $stripped){
        if (!in_array($unique_id,$this->not_allowed_values) || !in_array($stripped,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;

            $sharing_array = array();

            $sql = "SELECT sharing_items.unique_id, sharing_items.name, sharing_items.stripped, sharing_items.description, sharing_items.total_price, sharing_items.split_price, sharing_items.total_no_of_persons,
            sharing_items.current_no_of_persons, sharing_items.expiration, sharing_items.starting_date, sharing_items.expiry_date, sharing_items.completion, sharing_items.added_date FROM sharing_items WHERE (sharing_items.unique_id=:unique_id OR sharing_items.stripped=:stripped)
            AND sharing_items.status=:status ORDER BY sharing_items.added_date DESC LIMIT 1";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $unique_id);
            $query->bindParam(":stripped", $stripped);
            $query->bindParam(":status", $active);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_sharing = array();
                $current_sharing['unique_id'] = $value['unique_id'];
                $current_sharing['name'] = $value['name'];
                $current_sharing['stripped'] = $value['stripped'];
                $current_sharing['description'] = $value['description'];
                $current_sharing['total_price'] = $value['total_price'];
                $current_sharing['split_price'] = $value['split_price'];
                $current_sharing['total_no_of_persons'] = $value['total_no_of_persons'];
                $current_sharing['current_no_of_persons'] = $value['current_no_of_persons'];
                $current_sharing['expiration'] = $value['expiration'];
                $current_sharing['starting_date'] = $value['starting_date'];
                $current_sharing['expiry_date'] = $value['expiry_date'];
                $current_sharing['completion'] = $value['completion'];
                $current_sharing['added_date'] = $value['added_date'];

                $sharing_id = $value['unique_id'];

                $sql2 = "SELECT image FROM sharing_images WHERE sharing_unique_id=:sharing_unique_id";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":sharing_unique_id", $sharing_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_sharing_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_sharing_images[] = $image_value['image'];
                  }

                  $current_sharing['sharing_images'] = $current_sharing_images;
                }
                else{
                  $current_sharing['sharing_images'] = null;
                }

                $sharing_array[] = $current_sharing;
              }
              return $sharing_array;
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
          $output['message'] = "At least one value is required";
          return $output;
        }

      }

      public function get_all_sharing_details_for_users($unique_id_alt, $stripped){
        if (!in_array($unique_id_alt,$this->not_allowed_values) || !in_array($stripped,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;
            $today = $this->functions->today;
            $Yes = $this->functions->Yes;

            $sharing_array;

            $sql = "SELECT sharing_items.unique_id, sharing_items.name, sharing_items.stripped, sharing_items.description, sharing_items.total_price, sharing_items.split_price, sharing_items.total_no_of_persons,
            sharing_items.current_no_of_persons, sharing_items.expiration, sharing_items.starting_date, sharing_items.expiry_date, sharing_items.completion, sharing_items.added_date FROM sharing_items WHERE (sharing_items.unique_id=:unique_id OR sharing_items.stripped=:stripped)
            AND sharing_items.status=:status ORDER BY sharing_items.added_date DESC LIMIT 1";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $unique_id_alt);
            $query->bindParam(":stripped", $stripped);
            $query->bindParam(":status", $active);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_sharing = array();
                $current_sharing['unique_id'] = $value['unique_id'];
                $current_sharing['name'] = $value['name'];
                $current_sharing['stripped'] = $value['stripped'];
                $current_sharing['description'] = $value['description'];
                $current_sharing['total_price'] = $value['total_price'];
                $current_sharing['split_price'] = $value['split_price'];
                $current_sharing['total_no_of_persons'] = $value['total_no_of_persons'];
                $current_sharing['current_no_of_persons'] = $value['current_no_of_persons'];
                $current_sharing['expiration'] = $value['expiration'];
                $current_sharing['starting_date'] = $value['starting_date'];
                $current_sharing['expiry_date'] = $value['expiry_date'];
                $current_sharing['completion'] = $value['completion'];
                $current_sharing['added_date'] = $value['added_date'];

                $sharing_id = $value['unique_id'];
                $unique_id = $value['unique_id'];

                $sql2 = "SELECT image FROM sharing_images WHERE sharing_unique_id=:sharing_unique_id";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":sharing_unique_id", $sharing_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_sharing_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_sharing_images[] = $image_value['image'];
                  }

                  $current_sharing['sharing_images'] = $current_sharing_images;
                }
                else{
                  $current_sharing['sharing_images'] = null;
                }

                // $sqlLocations = "SELECT DISTINCT sharing_shipping_fees.city, sharing_shipping_fees.state, sharing_shipping_fees.country, sharing_shipping_fees.price FROM sharing_shipping_fees
                // WHERE sharing_shipping_fees.sharing_unique_id=:sharing_unique_id AND sharing_shipping_fees.status=:status ORDER BY sharing_shipping_fees.added_date DESC";
                // $queryLocations = $this->conn->prepare($sqlLocations);
                // $queryLocations->bindParam(":sharing_unique_id", $unique_id);
                // $queryLocations->bindParam(":status", $active);
                // $queryLocations->execute();
                //
                // $resultLocations = $queryLocations->fetchAll();
                //
                // if ($queryLocations->rowCount() > 0) {
                //   $sharing_locations = $resultLocations;
                // }
                // else {
                //   $sharing_locations = null;
                // }

                $sqlPickupLocations = "SELECT default_pickup_locations.address, default_pickup_locations.additional_information,
                default_pickup_locations.firstname, default_pickup_locations.lastname, default_pickup_locations.city,
                default_pickup_locations.state, default_pickup_locations.country, pickup_locations.unique_id as pickup_location_unique_id, pickup_locations.price FROM pickup_locations
                LEFT JOIN default_pickup_locations ON pickup_locations.default_pickup_location_unique_id = default_pickup_locations.unique_id
                WHERE pickup_locations.sharing_unique_id=:sharing_unique_id AND pickup_locations.status=:status ORDER BY pickup_locations.added_date DESC";
                $queryPickupLocations = $this->conn->prepare($sqlPickupLocations);
                $queryPickupLocations->bindParam(":sharing_unique_id", $unique_id);
                $queryPickupLocations->bindParam(":status", $active);
                $queryPickupLocations->execute();

                $resultPickupLocations = $queryPickupLocations->fetchAll();

                if ($queryPickupLocations->rowCount() > 0) {
                  $sharing_pickup_locations = $resultPickupLocations;
                }
                else {
                  $sharing_pickup_locations = null;
                }

                $sql_sharing_details1 = "SELECT sharing_users.amount, sharing_users.payment_method, sharing_users.paid,
                users.fullname as user_fullname, users.image as user_image FROM sharing_users INNER JOIN users ON sharing_users.user_unique_id = users.unique_id
                WHERE sharing_users.sharing_unique_id=:sharing_unique_id AND sharing_users.paid=:paid AND sharing_users.status=:status ORDER BY sharing_users.added_date ASC";
                $query_sharing_details1 = $this->conn->prepare($sql_sharing_details1);
                $query_sharing_details1->bindParam(":sharing_unique_id", $unique_id);
                $query_sharing_details1->bindParam(":paid", $active);
                $query_sharing_details1->bindParam(":status", $active);
                $query_sharing_details1->execute();

                $result_sharing_details1 = $query_sharing_details1->fetchAll();

                if ($query_sharing_details1->rowCount() > 0) {
                  $sharing_user_array = $result_sharing_details1;
                }
                else {
                  $sharing_user_array = null;
                }

                $sql_sharing_details2 = "SELECT COUNT(*) FROM sharing_users WHERE sharing_unique_id=:sharing_unique_id";
                $query_sharing_details2 = $this->conn->prepare($sql_sharing_details2);
                $query_sharing_details2->bindParam(":sharing_unique_id", $unique_id);
                $query_sharing_details2->execute();

                if ($query_sharing_details2->rowCount() > 0) {
                  $the_sharing_users_count = $query_sharing_details2->fetch();
                  $sharing_users_count = (int)$the_sharing_users_count[0];
                }
                else {
                  $sharing_users_count = 0;
                }

                $sql_sharing_details3 = "SELECT COUNT(*) FROM sharing_users WHERE sharing_unique_id=:sharing_unique_id AND paid=:paid";
                $query_sharing_details3 = $this->conn->prepare($sql_sharing_details3);
                $query_sharing_details3->bindParam(":sharing_unique_id", $unique_id);
                $query_sharing_details3->bindParam(":paid", $active);
                $query_sharing_details3->execute();

                if ($query_sharing_details3->rowCount() > 0) {
                  $the_sharing_paid_users_count = $query_sharing_details3->fetch();
                  $sharing_paid_users_count = (int)$the_sharing_paid_users_count[0];
                }
                else {
                  $sharing_paid_users_count = 0;
                }

                $sql_sharing_details4 = "SELECT starting_date, expiry_date, current_no_of_persons, expiration, total_no_of_persons FROM sharing_items WHERE unique_id=:unique_id";
                $query_sharing_details4 = $this->conn->prepare($sql_sharing_details4);
                $query_sharing_details4->bindParam(":unique_id", $unique_id);
                // $query_sharing_details4->bindParam(":status", $active);
                $query_sharing_details4->execute();

                if ($query_sharing_details4->rowCount() > 0) {
                  $the_sharing_details = $query_sharing_details4->fetch();
                  $the_starting_date = $the_sharing_details[0];
                  $the_expiry_date = $the_sharing_details[1];
                  $the_current_no_of_persons = (int)$the_sharing_details[2];
                  $the_expiration = (int)$the_sharing_details[3];
                  $the_total_no_of_persons = (int)$the_sharing_details[4];

                  // if ($the_starting_date < $today) {
                  //   $disable_button = true;
                  // }
                  // else
                  if ($the_expiration == $active && $the_expiry_date < $today) {
                    $disable_button = true;
                  }
                  else if ($the_current_no_of_persons == $the_total_no_of_persons) {
                    $disable_button = true;
                  }
                  else {
                    $disable_button = false;
                  }
                }
                else {
                  $disable_button = true;
                }

                $sharing_users_array = array(
                  $sharing_user_array,
                  $sharing_users_count,
                  $sharing_paid_users_count,
                  $disable_button
                );

                // $sql_sharing_history = "SELECT sharing_history.action, sharing_history.last_modified, users.fullname as user_fullname FROM sharing_history
                // INNER JOIN users ON sharing_history.user_unique_id = users.unique_id
                // WHERE sharing_history.sharing_unique_id=:sharing_unique_id AND sharing_history.status=:status ORDER BY sharing_history.added_date DESC";
                // $query_sharing_history = $this->conn->prepare($sql_sharing_history);
                // $query_sharing_history->bindParam(":sharing_unique_id", $unique_id);
                // $query_sharing_history->bindParam(":status", $active);
                // $query_sharing_history->execute();
                //
                // $result_sharing_history = $query_sharing_history->fetchAll();
                //
                // if ($query_sharing_history->rowCount() > 0) {
                //   $sharing_history = $result_sharing_history;
                // }
                // else {
                //   $sharing_history = null;
                // }

                $sharing_array = $current_sharing;
              }

              $full_array = array(
                $sharing_array,
                // $sharing_locations,
                $sharing_pickup_locations,
                $sharing_users_array
                // $sharing_history
              );

              return $full_array;
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
          $output['message'] = "At least one value is required";
          return $output;
        }

      }

      public function get_sharing_images($unique_id){
        if (!in_array($unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sharing_array = array();

            $sql = "SELECT sharing_images.id, sharing_images.unique_id, sharing_images.user_unique_id, sharing_images.edit_user_unique_id, sharing_images.sharing_unique_id, sharing_images.image, sharing_images.file, sharing_images.file_size, sharing_images.added_date, sharing_images.last_modified, sharing_images.status,
            management.fullname as added_fullname, management_alt.fullname as edit_user_fullname, sharing_items.name as sharing_name FROM sharing_images INNER JOIN management ON sharing_images.user_unique_id = management.unique_id INNER JOIN management management_alt ON sharing_images.edit_user_unique_id = management_alt.unique_id
            LEFT JOIN sharing_items ON sharing_images.sharing_unique_id = sharing_items.unique_id WHERE sharing_images.sharing_unique_id=:sharing_unique_id ORDER BY sharing_images.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":sharing_unique_id", $unique_id);
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

      public function get_sharing_image_details($unique_id, $sharing_unique_id){
        if (!in_array($unique_id,$this->not_allowed_values) && !in_array($sharing_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sharing_array = array();

            $sql = "SELECT sharing_images.id, sharing_images.unique_id, sharing_images.user_unique_id, sharing_images.edit_user_unique_id, sharing_images.sharing_unique_id, sharing_images.image, sharing_images.file, sharing_images.file_size, sharing_images.added_date, sharing_images.last_modified, sharing_images.status,
            management.fullname as added_fullname, management_alt.fullname as edit_user_fullname, sharing_items.name as sharing_name FROM sharing_images INNER JOIN management ON sharing_images.user_unique_id = management.unique_id INNER JOIN management management_alt ON sharing_images.edit_user_unique_id = management_alt.unique_id
            LEFT JOIN sharing_items ON sharing_images.sharing_unique_id = sharing_items.unique_id WHERE sharing_images.unique_id=:unique_id AND sharing_images.sharing_unique_id=:sharing_unique_id ORDER BY sharing_items.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $unique_id);
            $query->bindParam(":sharing_unique_id", $sharing_unique_id);
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

      public function get_sharing_users($unique_id){
        if (!in_array($unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;
            $not_active = $this->functions->not_active;
            $today = $this->functions->today;
            $Yes = $this->functions->Yes;

            $sharing_user_array = array();

            $sql = "SELECT sharing_users.id, sharing_users.unique_id, sharing_users.user_unique_id, sharing_users.sharing_unique_id, sharing_users.shipping_fee_unique_id, sharing_users.pickup_location, sharing_users.amount, sharing_users.payment_method, sharing_users.paid, sharing_users.added_date, sharing_users.last_modified, sharing_users.status,
            users.fullname as user_fullname, users.email as user_email, users.phone_number as user_phone_number, sharing_items.name as sharing_item_name, sharing_shipping_fees.price AS sharing_shipping_fee_price, sharing_shipping_fees.city, sharing_shipping_fees.state, sharing_shipping_fees.country, users_addresses.address,
            users_addresses.additional_information, users_addresses.firstname, users_addresses.lastname FROM sharing_users INNER JOIN users ON sharing_users.user_unique_id = users.unique_id
            INNER JOIN sharing_items ON sharing_users.sharing_unique_id = sharing_items.unique_id
            LEFT JOIN sharing_shipping_fees ON sharing_users.shipping_fee_unique_id = sharing_shipping_fees.unique_id
            LEFT JOIN users_addresses ON sharing_users.user_unique_id = users_addresses.user_unique_id
            WHERE sharing_users.sharing_unique_id=:sharing_unique_id AND sharing_users.pickup_location=:pickup_location_false AND users_addresses.default_status=:default_status

            UNION

            SELECT sharing_users.id, sharing_users.unique_id, sharing_users.user_unique_id, sharing_users.sharing_unique_id, sharing_users.shipping_fee_unique_id, sharing_users.pickup_location, sharing_users.amount, sharing_users.payment_method, sharing_users.paid, sharing_users.added_date, sharing_users.last_modified, sharing_users.status,
            users.fullname as user_fullname, users.email as user_email, users.phone_number as user_phone_number, sharing_items.name as sharing_item_name, pickup_locations.price AS sharing_shipping_fee_price,  default_pickup_locations.city, default_pickup_locations.state, default_pickup_locations.country, default_pickup_locations.address,
            default_pickup_locations.additional_information, default_pickup_locations.firstname, default_pickup_locations.lastname FROM sharing_users INNER JOIN users ON sharing_users.user_unique_id = users.unique_id
            INNER JOIN sharing_items ON sharing_users.sharing_unique_id = sharing_items.unique_id
            LEFT JOIN pickup_locations ON sharing_users.shipping_fee_unique_id = pickup_locations.unique_id
            LEFT JOIN default_pickup_locations ON pickup_locations.default_pickup_location_unique_id = default_pickup_locations.unique_id
            WHERE sharing_users.sharing_unique_id=:sharing_unique_id AND sharing_users.pickup_location=:pickup_location_true";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":sharing_unique_id", $unique_id);
            $query->bindParam(":default_status", $Yes);
            $query->bindParam(":pickup_location_true", $active);
            $query->bindParam(":pickup_location_false", $not_active);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {

              $sharing_user_array = $result;

              $sql2 = "SELECT COUNT(*) FROM sharing_users WHERE sharing_unique_id=:sharing_unique_id";
              $query2 = $this->conn->prepare($sql2);
              $query2->bindParam(":sharing_unique_id", $unique_id);
              $query2->execute();

              if ($query2->rowCount() > 0) {
                $the_sharing_users_count = $query2->fetch();
                $sharing_users_count = (int)$the_sharing_users_count[0];
              }
              else {
                $sharing_users_count = 0;
              }

              $sql3 = "SELECT COUNT(*) FROM sharing_users WHERE sharing_unique_id=:sharing_unique_id AND paid=:paid";
              $query3 = $this->conn->prepare($sql3);
              $query3->bindParam(":sharing_unique_id", $unique_id);
              $query3->bindParam(":paid", $active);
              $query3->execute();

              if ($query3->rowCount() > 0) {
                $the_sharing_paid_users_count = $query3->fetch();
                $sharing_paid_users_count = (int)$the_sharing_paid_users_count[0];
              }
              else {
                $sharing_paid_users_count = 0;
              }

              $sql4 = "SELECT starting_date, expiry_date, current_no_of_persons, expiration, total_no_of_persons FROM sharing_items WHERE unique_id=:unique_id";
              $query4 = $this->conn->prepare($sql4);
              $query4->bindParam(":unique_id", $unique_id);
              // $query4->bindParam(":status", $active);
              $query4->execute();

              if ($query4->rowCount() > 0) {
                $the_sharing_details = $query4->fetch();
                $the_starting_date = $the_sharing_details[0];
                $the_expiry_date = $the_sharing_details[1];
                $the_current_no_of_persons = (int)$the_sharing_details[2];
                $the_expiration = (int)$the_sharing_details[3];
                $the_total_no_of_persons = (int)$the_sharing_details[4];

                // if ($the_starting_date < $today) {
                //   $disable_button = false;
                // }
                // else
                if ($the_expiration == $active && $the_expiry_date < $today) {
                  $disable_button = true;
                }
                else if ($the_current_no_of_persons == $the_total_no_of_persons) {
                  $disable_button = true;
                }
                else {
                  $disable_button = false;
                }
              }
              else {
                $disable_button = true;
              }

              $full_array = array(
                array($sharing_user_array),
                $sharing_users_count,
                $sharing_paid_users_count,
                $disable_button
              );

              return $full_array;
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

      public function get_sharing_users_for_users($unique_id){
        if (!in_array($unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;
            $today = $this->functions->today;
            $Yes = $this->functions->Yes;

            $sql = "SELECT sharing_users.unique_id, sharing_users.sharing_unique_id, sharing_users.amount, sharing_users.payment_method, sharing_users.paid,
            users.fullname as user_fullname, sharing_items.name as sharing_item_name FROM sharing_users INNER JOIN users ON sharing_users.user_unique_id = users.unique_id
            INNER JOIN sharing_items ON sharing_users.sharing_unique_id = sharing_items.unique_id
            WHERE sharing_users.sharing_unique_id=:sharing_unique_id AND sharing_users.paid=:paid AND sharing_users.status=:status ORDER BY sharing_users.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":sharing_unique_id", $unique_id);
            $query->bindParam(":paid", $active);
            $query->bindParam(":status", $active);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              $sharing_user_array = $result;
            }
            else {
              $sharing_user_array = null;
            }

            $sql2 = "SELECT COUNT(*) FROM sharing_users WHERE sharing_unique_id=:sharing_unique_id";
            $query2 = $this->conn->prepare($sql2);
            $query2->bindParam(":sharing_unique_id", $unique_id);
            $query2->execute();

            if ($query2->rowCount() > 0) {
              $the_sharing_users_count = $query2->fetch();
              $sharing_users_count = (int)$the_sharing_users_count[0];
            }
            else {
              $sharing_users_count = 0;
            }

            $sql3 = "SELECT COUNT(*) FROM sharing_users WHERE sharing_unique_id=:sharing_unique_id AND paid=:paid";
            $query3 = $this->conn->prepare($sql3);
            $query3->bindParam(":sharing_unique_id", $unique_id);
            $query3->bindParam(":paid", $active);
            $query3->execute();

            if ($query3->rowCount() > 0) {
              $the_sharing_paid_users_count = $query3->fetch();
              $sharing_paid_users_count = (int)$the_sharing_paid_users_count[0];
            }
            else {
              $sharing_paid_users_count = 0;
            }

            $sql4 = "SELECT starting_date, expiry_date, current_no_of_persons, expiration, total_no_of_persons FROM sharing_items WHERE unique_id=:unique_id";
            $query4 = $this->conn->prepare($sql4);
            $query4->bindParam(":unique_id", $unique_id);
            // $query4->bindParam(":status", $active);
            $query4->execute();

            if ($query4->rowCount() > 0) {
              $the_sharing_details = $query4->fetch();
              $the_starting_date = $the_sharing_details[0];
              $the_expiry_date = $the_sharing_details[1];
              $the_current_no_of_persons = (int)$the_sharing_details[2];
              $the_expiration = (int)$the_sharing_details[3];
              $the_total_no_of_persons = (int)$the_sharing_details[4];

              // if ($the_starting_date < $today) {
              //   $disable_button = true;
              // }
              // else
              if ($the_expiration == $active && $the_expiry_date < $today) {
                $disable_button = true;
              }
              else if ($the_current_no_of_persons == $the_total_no_of_persons) {
                $disable_button = true;
              }
              else {
                $disable_button = false;
              }
            }
            else {
              $disable_button = true;
            }

            $full_array = array(
              array($sharing_user_array),
              $sharing_users_count,
              $sharing_paid_users_count,
              $disable_button
            );

            return $full_array;

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

      public function get_sharing_user_details_for_users($unique_id, $user_unique_id){
        if (!in_array($unique_id,$this->not_allowed_values) && !in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;
            $not_active = $this->functions->not_active;
            $today = $this->functions->today;
            $Yes = $this->functions->Yes;

            $sharing_user_array = array();

            $sql = "SELECT sharing_users.id, sharing_users.unique_id, sharing_users.user_unique_id, sharing_users.sharing_unique_id, sharing_users.shipping_fee_unique_id, sharing_users.pickup_location, sharing_users.amount, sharing_users.payment_method, sharing_users.paid, sharing_users.added_date, sharing_users.last_modified, sharing_users.status,
            sharing_items.name as sharing_item_name, sharing_shipping_fees.price AS sharing_shipping_fee_price, sharing_shipping_fees.city, sharing_shipping_fees.state, sharing_shipping_fees.country, users_addresses.address,
            users_addresses.additional_information, users_addresses.firstname, users_addresses.lastname FROM sharing_users
            INNER JOIN sharing_items ON sharing_users.sharing_unique_id = sharing_items.unique_id
            LEFT JOIN sharing_shipping_fees ON sharing_users.shipping_fee_unique_id = sharing_shipping_fees.unique_id
            LEFT JOIN users_addresses ON sharing_users.user_unique_id = users_addresses.user_unique_id
            WHERE sharing_users.sharing_unique_id=:sharing_unique_id AND sharing_users.user_unique_id=:user_unique_id AND sharing_users.pickup_location=:pickup_location_false AND users_addresses.default_status=:default_status

            UNION

            SELECT sharing_users.id, sharing_users.unique_id, sharing_users.user_unique_id, sharing_users.sharing_unique_id, sharing_users.shipping_fee_unique_id, sharing_users.pickup_location, sharing_users.amount, sharing_users.payment_method, sharing_users.paid, sharing_users.added_date, sharing_users.last_modified, sharing_users.status,
            sharing_items.name as sharing_item_name, pickup_locations.price AS sharing_shipping_fee_price,  default_pickup_locations.city, default_pickup_locations.state, default_pickup_locations.country, default_pickup_locations.address,
            default_pickup_locations.additional_information, default_pickup_locations.firstname, default_pickup_locations.lastname FROM sharing_users
            INNER JOIN sharing_items ON sharing_users.sharing_unique_id = sharing_items.unique_id
            LEFT JOIN pickup_locations ON sharing_users.shipping_fee_unique_id = pickup_locations.unique_id
            LEFT JOIN default_pickup_locations ON pickup_locations.default_pickup_location_unique_id = default_pickup_locations.unique_id
            WHERE sharing_users.sharing_unique_id=:sharing_unique_id AND sharing_users.user_unique_id=:user_unique_id AND sharing_users.pickup_location=:pickup_location_true";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":sharing_unique_id", $unique_id);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->bindParam(":default_status", $Yes);
            $query->bindParam(":pickup_location_true", $active);
            $query->bindParam(":pickup_location_false", $not_active);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {

              $sharing_user_array = $result;

              return $sharing_user_array;
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

      public function get_user_sharing_items_for_users($user_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;

            $sharing_array = array();

            $sql = "SELECT sharing_items.unique_id, sharing_items.name, sharing_items.stripped, sharing_items.description, sharing_items.total_price, sharing_items.split_price, sharing_items.total_no_of_persons,
            sharing_items.current_no_of_persons, sharing_items.expiration, sharing_items.starting_date, sharing_items.expiry_date, sharing_items.completion, sharing_users.amount, sharing_users.paid, sharing_users.added_date FROM sharing_items
            INNER JOIN sharing_users ON sharing_items.unique_id = sharing_users.sharing_unique_id WHERE sharing_users.user_unique_id=:user_unique_id AND sharing_items.status=:status ORDER BY sharing_items.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->bindParam(":status", $active);
            $query->execute();

            $result = $query->fetchAll();

            if ($query->rowCount() > 0) {
              foreach ($result as $key => $value) {

                $current_sharing = array();
                $current_sharing['unique_id'] = $value['unique_id'];
                $current_sharing['name'] = $value['name'];
                $current_sharing['stripped'] = $value['stripped'];
                $current_sharing['description'] = $value['description'];
                $current_sharing['total_price'] = $value['total_price'];
                $current_sharing['split_price'] = $value['split_price'];
                $current_sharing['total_no_of_persons'] = $value['total_no_of_persons'];
                $current_sharing['current_no_of_persons'] = $value['current_no_of_persons'];
                $current_sharing['expiration'] = $value['expiration'];
                $current_sharing['starting_date'] = $value['starting_date'];
                $current_sharing['expiry_date'] = $value['expiry_date'];
                $current_sharing['completion'] = $value['completion'];
                $current_sharing['added_date'] = $value['added_date'];
                $current_sharing['paid'] = $value['paid'];
                $current_sharing['amount'] = $value['amount'];

                $sharing_id = $value['unique_id'];

                $sql2 = "SELECT image FROM sharing_images WHERE sharing_unique_id=:sharing_unique_id";
                $query2 = $this->conn->prepare($sql2);
                $query2->bindParam(":sharing_unique_id", $sharing_id);
                $query2->execute();

                $images_result = $query2->fetchAll();

                if ($query2->rowCount() > 0) {
                  $current_sharing_images = array();

                  foreach ($images_result as $key => $image_value) {
                    $current_sharing_images[] = $image_value['image'];
                  }

                  $current_sharing['sharing_images'] = $current_sharing_images;
                }
                else{
                  $current_sharing['sharing_images'] = null;
                }

                $sharing_array[] = $current_sharing;
              }
              return $sharing_array;
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

      public function get_sharing_history($unique_id){
        if (!in_array($unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;

            $sql = "SELECT sharing_history.unique_id, sharing_history.user_unique_id, sharing_history.sharing_unique_id, sharing_history.action, sharing_history.added_date, sharing_history.last_modified, sharing_history.status,
            users.fullname as user_fullname, users.email as user_email, users.phone_number as user_phone_number, sharing_items.name as sharing_item_name FROM sharing_history INNER JOIN users ON sharing_history.user_unique_id = users.unique_id
            INNER JOIN sharing_items ON sharing_history.sharing_unique_id = sharing_items.unique_id
            WHERE sharing_history.sharing_unique_id=:sharing_unique_id ORDER BY sharing_history.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":sharing_unique_id", $unique_id);
            // $query->bindParam(":status", $active);
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

      public function get_sharing_history_for_users($unique_id){
        if (!in_array($unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;

            $sql = "SELECT sharing_history.action, sharing_history.last_modified, users.fullname as user_fullname, sharing_items.name as sharing_item_name FROM sharing_history
            INNER JOIN users ON sharing_history.user_unique_id = users.unique_id
            INNER JOIN sharing_items ON sharing_history.sharing_unique_id = sharing_items.unique_id
            WHERE sharing_history.sharing_unique_id=:sharing_unique_id AND sharing_history.status=:status ORDER BY sharing_history.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":sharing_unique_id", $unique_id);
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

      public function get_user_sharing_history_for_users($unique_id, $user_unique_id){
        if (!in_array($unique_id,$this->not_allowed_values) && !in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;

            $sql = "SELECT sharing_history.action, sharing_history.last_modified, users.fullname as user_fullname, sharing_items.name as sharing_item_name FROM sharing_history
            INNER JOIN users ON sharing_history.user_unique_id = users.unique_id
            INNER JOIN sharing_items ON sharing_history.sharing_unique_id = sharing_items.unique_id
            WHERE sharing_history.sharing_unique_id=:sharing_unique_id AND sharing_history.user_unique_id=:user_unique_id AND sharing_history.status=:status ORDER BY sharing_history.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":sharing_unique_id", $unique_id);
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

      public function get_sharing_locations_for_users($unique_id){
        if (!in_array($unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;

            $sql = "SELECT DISTINCT sharing_shipping_fees.city, sharing_shipping_fees.state, sharing_shipping_fees.country, sharing_shipping_fees.price, sharing_items.name as sharing_item_name FROM sharing_shipping_fees
            INNER JOIN sharing_items ON sharing_shipping_fees.sharing_unique_id = sharing_items.unique_id
            WHERE sharing_shipping_fees.sharing_unique_id=:sharing_unique_id AND sharing_shipping_fees.status=:status ORDER BY sharing_shipping_fees.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":sharing_unique_id", $unique_id);
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

      public function get_sharing_pickup_locations_for_users($unique_id){
        if (!in_array($unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;

            $sql = "SELECT default_pickup_locations.address, default_pickup_locations.additional_information,
            default_pickup_locations.firstname, default_pickup_locations.lastname, default_pickup_locations.city,
            default_pickup_locations.state, default_pickup_locations.country, pickup_locations.price,
            sharing_items.name as sharing_item_name FROM pickup_locations
            INNER JOIN sharing_items ON pickup_locations.sharing_unique_id = sharing_items.unique_id
            LEFT JOIN default_pickup_locations ON pickup_locations.default_pickup_location_unique_id = default_pickup_locations.unique_id
            WHERE pickup_locations.sharing_unique_id=:sharing_unique_id AND pickup_locations.status=:status ORDER BY pickup_locations.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":sharing_unique_id", $unique_id);
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
