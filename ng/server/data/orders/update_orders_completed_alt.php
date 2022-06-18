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
      $null = $functions->null;
      $not_allowed_values = $functions->not_allowed_values;
      $completion_status = $functions->completed;

      $order_unique_ids = isset($_GET['order_unique_ids']) ? $_GET['order_unique_ids'] : $data['order_unique_ids'];
      $management_user_unique_id = isset($_GET['management_user_unique_id']) ? $_GET['management_user_unique_id'] : $data['management_user_unique_id'];

      $sqlSearchUser = "SELECT unique_id FROM management WHERE unique_id=:unique_id AND status=:status";
      $querySearchUser = $conn->prepare($sqlSearchUser);
      $querySearchUser->bindParam(":unique_id", $management_user_unique_id);
      $querySearchUser->bindParam(":status", $active);
      $querySearchUser->execute();

      if ($querySearchUser->rowCount() > 0) {

        if (!is_array($order_unique_ids)) {
          $returnvalue = new genericClass();
          $returnvalue->engineError = 2;
          $returnvalue->engineErrorMessage = "Order IDs is not an array";
        }
        else if (in_array($order_unique_ids,$not_allowed_values)) {
          $returnvalue = new genericClass();
          $returnvalue->engineError = 2;
          $returnvalue->engineErrorMessage = "Order IDs is required";
        }
        else {

          foreach ($order_unique_ids as $key => $order_unique_id){

            $sqlOrder = "SELECT delivery_status, user_unique_id, tracker_unique_id FROM orders WHERE unique_id=:unique_id AND status=:status";
            $queryOrder = $conn->prepare($sqlOrder);
            $queryOrder->bindParam(":unique_id", $order_unique_id);
            $queryOrder->bindParam(":status", $active);
            $queryOrder->execute();

            if ($queryOrder->rowCount() > 0) {

              $the_order_details = $queryOrder->fetch();
              $the_delivery_status = $the_order_details[0];
              $user_unique_id = $the_order_details[1];
              $tracker_unique_id = $the_order_details[2];

              if (strtolower($the_delivery_status) == strtolower($completion_status)) {
                $returnvalue = new genericClass();
                $returnvalue->engineError = 2;
                $returnvalue->engineErrorMessage = "Order already ".$completion_status;
              }
              else {

                $sql2 = "UPDATE orders SET delivery_status=:delivery_status, last_modified=:last_modified WHERE unique_id=:unique_id AND user_unique_id=:user_unique_id AND tracker_unique_id=:tracker_unique_id";
                $query2 = $conn->prepare($sql2);
                $query2->bindParam(":unique_id", $order_unique_id);
                $query2->bindParam(":delivery_status", $completion_status);
                $query2->bindParam(":user_unique_id", $user_unique_id);
                $query2->bindParam(":tracker_unique_id", $tracker_unique_id);
                $query2->bindParam(":last_modified", $date_added);
                $query2->execute();

                if ($query2->rowCount() > 0) {

                  $order_history_unique_id = $functions->random_str(20);

                  $sql = "INSERT INTO order_history (unique_id, user_unique_id, order_unique_id, price, completion, added_date, last_modified, status)
                  VALUES (:unique_id, :user_unique_id, :order_unique_id, :price, :completion, :added_date, :last_modified, :status)";
                  $query = $conn->prepare($sql);
                  $query->bindParam(":unique_id", $order_history_unique_id);
                  $query->bindParam(":user_unique_id", $user_unique_id);
                  $query->bindParam(":order_unique_id", $order_unique_id);
                  $query->bindParam(":price", $null);
                  $query->bindParam(":completion", $completion_status);
                  $query->bindParam(":added_date", $date_added);
                  $query->bindParam(":last_modified", $date_added);
                  $query->bindParam(":status", $active);
                  $query->execute();

                  if ($query->rowCount() > 0) {
                    $returnvalue = new genericClass();
                    $returnvalue->engineMessage = 1;
                  }
                  else {
                    $returnvalue = new genericClass();
                    $returnvalue->engineError = 2;
                    $returnvalue->engineErrorMessage = $key." index, Order history not updated";
                  }

                }
                else {
                  $returnvalue = new genericClass();
                  $returnvalue->engineError = 2;
                  $returnvalue->engineErrorMessage = $key." index, Orders not updated";
                }

              }

            }
            else {
              $returnvalue = new genericClass();
              $returnvalue->engineError = 2;
              $returnvalue->engineErrorMessage = $key." index, Order not found";
            }

          }

        }

      }
      else {
        $returnvalue = new genericClass();
        $returnvalue->engineError = 2;
        $returnvalue->engineErrorMessage = "Management user not found";
      }

      $conn->commit();
    } catch (PDOException $e) {
      $conn->rollback();
      throw $e;
    }

  }
  else {
    $returnvalue = new genericClass();
    $returnvalue->engineError = 3;
    $returnvalue->engineErrorMessage = "No connection";
  }

  echo json_encode($returnvalue);

?>
