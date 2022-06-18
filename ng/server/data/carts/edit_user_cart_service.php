<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");
  require '../../config/connect_data.php';
  include_once "../../objects/functions.php";

  class genericClass {
    public $engineMessage = 0;
    public $engineError = 0;
    public $engineErrorMessage;
    public $resultData;
    public $filteredData;
  }


  $data = json_decode(file_get_contents("php://input"), true);

  $functions = new Functions();

  if ($connected) {

    try {
      $conn->beginTransaction();

      $date_added = $functions->today;
      $active = $functions->active;
      $not_allowed_values = $functions->not_allowed_values;

      $cart_unique_id = isset($_GET['cart_unique_id']) ? $_GET['cart_unique_id'] : $data['cart_unique_id'];
      $user_unique_id = isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : $data['user_unique_id'];
      $sub_product_unique_id = isset($_GET['sub_product_unique_id']) ? $_GET['sub_product_unique_id'] : $data['sub_product_unique_id'];
      $cart_offered_service_unique_id = isset($_GET['cart_offered_service_unique_id']) ? $_GET['cart_offered_service_unique_id'] : $data['cart_offered_service_unique_id'];

      $sqlSearchUser = "SELECT unique_id FROM users WHERE unique_id=:unique_id AND status=:status";
      $querySearchUser = $conn->prepare($sqlSearchUser);
      $querySearchUser->bindParam(":unique_id", $user_unique_id);
      $querySearchUser->bindParam(":status", $active);
      $querySearchUser->execute();

      if ($querySearchUser->rowCount() > 0) {

        $validation = $functions->cart_services_validation($sub_product_unique_id);

        if ($validation["error"] == true) {
          $returnvalue = new genericClass();
          $returnvalue->engineError = 2;
          $returnvalue->engineErrorMessage = $validation["message"];
        }
        else {

          $sql2 = "SELECT status FROM carts WHERE unique_id=:unique_id AND user_unique_id=:user_unique_id AND sub_product_unique_id=:sub_product_unique_id";
          $query2 = $conn->prepare($sql2);
          $query2->bindParam(":unique_id", $cart_unique_id);
          $query2->bindParam(":user_unique_id", $user_unique_id);
          $query2->bindParam(":sub_product_unique_id", $sub_product_unique_id);
          $query2->execute();

          if ($query2->rowCount() > 0) {

            $sql4 = "SELECT status FROM order_services WHERE cart_unique_id=:cart_unique_id AND offered_service_unique_id=:offered_service_unique_id";
            $query4 = $conn->prepare($sql4);
            $query4->bindParam(":cart_unique_id", $cart_unique_id);
            $query4->bindParam(":offered_service_unique_id", $cart_offered_service_unique_id);
            $query4->execute();

            if ($query4->rowCount() > 0) {
              $sql = "DELETE FROM order_services WHERE cart_unique_id=:cart_unique_id AND offered_service_unique_id=:offered_service_unique_id";
              $query = $conn->prepare($sql);
              $query->bindParam(":cart_unique_id", $cart_unique_id);
              $query->bindParam(":offered_service_unique_id", $cart_offered_service_unique_id);
              $query->execute();

              if ($query->rowCount() > 0) {
                $returnvalue = new genericClass();
                $returnvalue->engineMessage = 1;
              }
              else {
                $returnvalue = new genericClass();
                $returnvalue->engineError = 2;
                $returnvalue->engineErrorMessage = "Not deleted (order service)";
              }
            }
            else {
              $order_service_unique_id = $functions->random_str(20);

              $sql5 = "INSERT INTO order_services (unique_id, user_unique_id, cart_unique_id, order_unique_id, offered_service_unique_id, added_date, last_modified, status)
              VALUES (:unique_id, :user_unique_id, :cart_unique_id, :order_unique_id, :offered_service_unique_id, :added_date, :last_modified, :status)";
              $query5 = $conn->prepare($sql5);
              $query5->bindParam(":unique_id", $order_service_unique_id);
              $query5->bindParam(":user_unique_id", $user_unique_id);
              $query5->bindParam(":cart_unique_id", $cart_unique_id);
              $query5->bindParam(":order_unique_id", $null);
              $query5->bindParam(":offered_service_unique_id", $cart_offered_service_unique_id);
              $query5->bindParam(":added_date", $date_added);
              $query5->bindParam(":last_modified", $date_added);
              $query5->bindParam(":status", $active);
              $query5->execute();

              if ($query5->rowCount() > 0) {
                $returnvalue = new genericClass();
                $returnvalue->engineMessage = 1;
              }
              else{
                $counter_now = $key;
                $returnvalue = new genericClass();
                $returnvalue->engineError = 2;
                $returnvalue->engineErrorMessage = $counter_now." index, Not inserted (new order service)";
              }
            }

          }
          else {
            $returnvalue = new genericClass();
            $returnvalue->engineError = 2;
            $returnvalue->engineErrorMessage = "Cart not found";
          }

        }

      }
      else {
        $returnvalue = new genericClass();
        $returnvalue->engineError = 2;
        $returnvalue->engineErrorMessage = "User not found";
      }

      $conn->commit();
    } catch (PDOException $e) {
      $conn->rollback();
      throw $e;
    }

  }
  else {
    $returnvalue = new genericClass();
    $returnvalue->engineError = 2;
    $returnvalue->engineErrorMessage = "No connection";
  }

  echo json_encode($returnvalue);

?>
