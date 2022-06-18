<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';

  class FlashDeals{

      // database connection and table name
      private $conn;
      private $table_name = "flash_deals";

      // object properties
      public $id;
      public $unique_id;
      public $user_unique_id;
      public $url;
      public $image;
      public $file;
      public $file_size;
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

      public function get_all_flash_deals(){

        try {
          $this->conn->beginTransaction();

          $sql = "SELECT flash_deals.id, flash_deals.unique_id, flash_deals.user_unique_id, flash_deals.url, flash_deals.image,
          flash_deals.file, flash_deals.file_size, flash_deals.added_date, flash_deals.last_modified, flash_deals.status,
          management.fullname as added_fullname FROM flash_deals INNER JOIN management ON flash_deals.user_unique_id = management.unique_id ORDER BY flash_deals.added_date DESC";
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

      public function get_flash_deals_filter($start_date, $end_date){

        if (!in_array($start_date,$this->not_allowed_values) && !in_array($end_date,$this->not_allowed_values)) {

          try {
            $this->conn->beginTransaction();

            $sql = "SELECT flash_deals.id, flash_deals.unique_id, flash_deals.user_unique_id, flash_deals.url, flash_deals.image,
            flash_deals.file, flash_deals.file_size, flash_deals.added_date, flash_deals.last_modified, flash_deals.status,
            management.fullname as added_fullname FROM flash_deals INNER JOIN management ON flash_deals.user_unique_id = management.unique_id
            WHERE flash_deals.added_date >:start_date AND (flash_deals.added_date <:end_date OR flash_deals.added_date=:end_date) ORDER BY flash_deals.added_date DESC";
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

      public function get_all_flash_deals_for_users(){

        try {
          $this->conn->beginTransaction();

          $active = $this->functions->active;

          $sql = "SELECT url, image FROM flash_deals WHERE status=:status ORDER BY added_date DESC";
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
