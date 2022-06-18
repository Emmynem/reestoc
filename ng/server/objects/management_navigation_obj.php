<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';
  // include_once '../config/connect.php';

  class ManagementNavigation{

      // database connection and table name
      private $conn;
      private $table_name = "management_navigation";

      // object properties
      public $id;
      public $unique_id;
      public $edit_user_unique_id;
      public $nav_title;
      public $nav_link;
      public $nav_icon;
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

      function get_all_management_navigations(){

        try {
          $this->conn->beginTransaction();

          $sql = "SELECT management_navigation.id, management_navigation.unique_id, management_navigation.user_unique_id, management_navigation.edit_user_unique_id, management_navigation.nav_title, management_navigation.nav_link, management_navigation.nav_icon, management_navigation.added_date,
          management_navigation.last_modified, management_navigation.status, management.fullname as edit_user_fullname FROM management_navigation
          INNER JOIN management ON management_navigation.edit_user_unique_id = management.unique_id ORDER BY management_navigation.nav_title ASC";
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

      function get_management_navigation_details($unique_id){
        if (!in_array($unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT management_navigation.id, management_navigation.unique_id, management_navigation.user_unique_id, management_navigation.edit_user_unique_id, management_navigation.nav_title, management_navigation.nav_link, management_navigation.nav_icon, management_navigation.added_date,
            management_navigation.last_modified, management_navigation.status, management.fullname as edit_user_fullname FROM management_navigation
            INNER JOIN management ON management_navigation.edit_user_unique_id = management.unique_id WHERE management_navigation.unique_id=:unique_id";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $unique_id);
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
