<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once 'functions.php';

  class Users{

      // database connection and table name
      private $conn;
      private $table_name = "users";

      // object properties
      public $id;
      public $unique_id;
      public $fullname;
      public $email;
      public $added_date;
      public $last_modified;
      public $access;
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

      function get_all_users(){
        try {
          $this->conn->beginTransaction();

          $sql = "SELECT * FROM users ORDER BY added_date DESC";
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

      function get_all_users_for_coupons(){
        try {
          $this->conn->beginTransaction();

          $sql = "SELECT users.unique_id, users.fullname, users.email, users.phone_number, ref_table.referrals_count, orders_completed_table.orders_completed_count FROM users
          LEFT JOIN (SELECT COUNT(*) AS referrals_count, referral_user_unique_id FROM referrals GROUP BY referral_user_unique_id) AS ref_table ON users.unique_id = ref_table.referral_user_unique_id
          LEFT JOIN (SELECT COUNT(*) AS orders_completed_count, user_unique_id FROM orders_completed GROUP BY user_unique_id) AS orders_completed_table ON users.unique_id = orders_completed_table.user_unique_id
          GROUP BY users.unique_id ORDER BY orders_completed_table.orders_completed_count DESC, ref_table.referrals_count DESC";
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

      function get_user_filter($start_date, $end_date){

        if (!in_array($start_date,$this->not_allowed_values) && !in_array($end_date,$this->not_allowed_values)) {

          $sql = "SELECT * FROM users WHERE added_date >:start_date AND (added_date <:end_date OR added_date=:end_date) ORDER BY added_date DESC";
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

        }
        else {
          $output['error'] = true;
          $output['message'] = "All fields are required";
          return $output;
        }

      }

      function get_user_details($user_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT * FROM users WHERE unique_id=:unique_id ORDER BY added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":unique_id", $user_unique_id);
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

      function get_user_referrals($user_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT users.fullname FROM referrals INNER JOIN users ON referrals.user_unique_id = users.unique_id WHERE referral_user_unique_id=:referral_user_unique_id ORDER BY referrals.added_date DESC";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":referral_user_unique_id", $user_unique_id);
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

      function get_user_referral_details($user_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $sql = "SELECT user_referral_link FROM referrals WHERE user_unique_id=:user_unique_id";
            $query = $this->conn->prepare($sql);
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

      function get_user_stats($user_unique_id){
        if (!in_array($user_unique_id,$this->not_allowed_values)) {
          try {
            $this->conn->beginTransaction();

            $active = $this->functions->active;

            $sql_cart = "SELECT COUNT(*) FROM carts WHERE user_unique_id=:user_unique_id AND status=:status";
            $query_cart = $this->conn->prepare($sql_cart);
            $query_cart->bindParam(":user_unique_id", $user_unique_id);
            $query_cart->bindParam(":status", $active);
            $query_cart->execute();

            if ($query_cart->rowCount() > 0) {
              $the_user_cart_count = $query_cart->fetch();
              $user_cart_count = (int)$the_user_cart_count[0];
            }
            else {
              $user_cart_count = 0;
            }

            $sql_order = "SELECT COUNT(*) FROM orders WHERE user_unique_id=:user_unique_id AND status=:status";
            $query_order = $this->conn->prepare($sql_order);
            $query_order->bindParam(":user_unique_id", $user_unique_id);
            $query_order->bindParam(":status", $active);
            $query_order->execute();

            if ($query_order->rowCount() > 0) {
              $the_user_order_count = $query_order->fetch();
              $user_order_count = (int)$the_user_order_count[0];
            }
            else {
              $user_order_count = 0;
            }

            $sql_referral = "SELECT COUNT(*) FROM referrals WHERE referral_user_unique_id=:referral_user_unique_id AND status=:status";
            $query_referral = $this->conn->prepare($sql_referral);
            $query_referral->bindParam(":referral_user_unique_id", $user_unique_id);
            $query_referral->bindParam(":status", $active);
            $query_referral->execute();

            if ($query_referral->rowCount() > 0) {
              $the_user_referral_count = $query_referral->fetch();
              $user_referral_count = (int)$the_user_referral_count[0];
            }
            else {
              $user_referral_count = 0;
            }

            $sql_favorite = "SELECT COUNT(*) FROM favorites WHERE user_unique_id=:user_unique_id AND status=:status";
            $query_favorite = $this->conn->prepare($sql_favorite);
            $query_favorite->bindParam(":user_unique_id", $user_unique_id);
            $query_favorite->bindParam(":status", $active);
            $query_favorite->execute();

            if ($query_favorite->rowCount() > 0) {
              $the_user_favorite_count = $query_favorite->fetch();
              $user_favorite_count = (int)$the_user_favorite_count[0];
            }
            else {
              $user_favorite_count = 0;
            }

            $stats_object = array(
              "user_cart_count"=>$user_cart_count,
              "user_order_count"=>$user_order_count,
              "user_referral_count"=>$user_referral_count,
              "user_favorite_count"=>$user_favorite_count
            );

            return $stats_object;

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
