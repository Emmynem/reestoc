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
      $not_active = $functions->not_active;
      $cancelled = $functions->cancelled;

      $shipment_unique_id = isset($_GET['shipment_unique_id']) ? $_GET['shipment_unique_id'] : $data['shipment_unique_id'];
      $user_unique_id = isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : $data['user_unique_id'];

      $sqlSearchUser = "SELECT unique_id FROM management WHERE unique_id=:unique_id AND status=:status";
      $querySearchUser = $conn->prepare($sqlSearchUser);
      $querySearchUser->bindParam(":unique_id", $user_unique_id);
      $querySearchUser->bindParam(":status", $active);
      $querySearchUser->execute();

      if ($querySearchUser->rowCount() > 0) {

        $null = $functions->null;

        $sql2 = "SELECT status FROM shipments WHERE shipment_unique_id=:shipment_unique_id";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(":shipment_unique_id", $shipment_unique_id);
        $query2->execute();

        if ($query2->rowCount() > 0) {

          $sql3 = "UPDATE orders SET shipment_unique_id=:shipment_unique_id, shipped=:shipped, last_modified=:last_modified WHERE shipment_unique_id=:shipment_unique_id_2";
          $query3 = $conn->prepare($sql3);
          $query3->bindParam(":shipment_unique_id", $null);
          $query3->bindParam(":shipped", $not_active);
          $query3->bindParam(":shipment_unique_id_2", $shipment_unique_id);
          $query3->bindParam(":last_modified", $date_added);
          $query3->execute();

          if ($query3->rowCount() > 0) {

            $sql = "UPDATE shipments SET delivery_status=:delivery_status, status=:status, last_modified=:last_modified WHERE shipment_unique_id=:shipment_unique_id";
            $query = $conn->prepare($sql);
            $query->bindParam(":shipment_unique_id", $shipment_unique_id);
            $query->bindParam(":delivery_status", $cancelled);
            $query->bindParam(":status", $not_active);
            $query->bindParam(":last_modified", $date_added);
            $query->execute();

            if ($query->rowCount() > 0) {
              $returnvalue = new genericClass();
              $returnvalue->engineMessage = 1;
            }
            else {
              $returnvalue = new genericClass();
              $returnvalue->engineError = 2;
              $returnvalue->engineErrorMessage = "Not deleted (shipment)";
            }

          }
          else {
            $sql = "UPDATE shipments SET delivery_status=:delivery_status, status=:status, last_modified=:last_modified WHERE shipment_unique_id=:shipment_unique_id";
            $query = $conn->prepare($sql);
            $query->bindParam(":shipment_unique_id", $shipment_unique_id);
            $query->bindParam(":delivery_status", $cancelled);
            $query->bindParam(":status", $not_active);
            $query->bindParam(":last_modified", $date_added);
            $query->execute();

            if ($query->rowCount() > 0) {
              $returnvalue = new genericClass();
              $returnvalue->engineMessage = 1;
            }
            else {
              $returnvalue = new genericClass();
              $returnvalue->engineError = 2;
              $returnvalue->engineErrorMessage = "Not deleted (shipment)";
            }
          }

        }
        else {
          $returnvalue = new genericClass();
          $returnvalue->engineError = 2;
          $returnvalue->engineErrorMessage = "Shipment not found";
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
    $returnvalue->engineError = 2;
    $returnvalue->engineErrorMessage = "No connection";
  }

  echo json_encode($returnvalue);

?>
