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

      $user_unique_id = isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : $data['user_unique_id'];
      $rider_unique_id = isset($_GET['rider_unique_id']) ? $_GET['rider_unique_id'] : $data['rider_unique_id'];
      $current_location = isset($_GET['current_location']) ? $_GET['current_location'] : $data['current_location'];
      $longitude = isset($_GET['longitude']) ? $_GET['longitude'] : $data['longitude'];
      $latitude = isset($_GET['latitude']) ? $_GET['latitude'] : $data['latitude'];

      $sqlSearchUser = "SELECT unique_id FROM management WHERE unique_id=:unique_id AND status=:status";
      $querySearchUser = $conn->prepare($sqlSearchUser);
      $querySearchUser->bindParam(":unique_id", $user_unique_id);
      $querySearchUser->bindParam(":status", $active);
      $querySearchUser->execute();

      if ($querySearchUser->rowCount() > 0) {

        $validation = $functions->shipment_validation($current_location, $longitude, $latitude);

        if ($validation["error"] == true) {
          $returnvalue = new genericClass();
          $returnvalue->engineError = 2;
          $returnvalue->engineErrorMessage = $validation["message"];
        }
        else {

          $null = $functions->null;
          $completion_status = $functions->processing;
          $completed = $functions->completed;

          $sql2 = "SELECT unique_id FROM shipments WHERE rider_unique_id=:rider_unique_id AND delivery_status!=:delivery_status ORDER BY added_date DESC LIMIT 1";
          $query2 = $conn->prepare($sql2);
          $query2->bindParam(":rider_unique_id", $rider_unique_id);
          $query2->bindParam(":delivery_status", $completed);
          $query2->execute();

          if ($query2->rowCount() > 0) {
            $returnvalue = new genericClass();
            $returnvalue->engineError = 2;
            $returnvalue->engineErrorMessage = "Rider's still on an ongoing shipment";
          }
          else {
            $unique_id = $functions->random_str(20);
            $shipment_unique_id = $functions->random_str(20);

            $sql = "INSERT INTO shipments (unique_id, user_unique_id, edit_user_unique_id, shipment_unique_id, rider_unique_id, current_location, longitude, latitude, delivery_status, added_date, last_modified, status)
            VALUES (:unique_id, :user_unique_id, :edit_user_unique_id, :shipment_unique_id, :rider_unique_id, :current_location, :longitude, :latitude, :delivery_status, :added_date, :last_modified, :status)";
            $query = $conn->prepare($sql);
            $query->bindParam(":unique_id", $unique_id);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->bindParam(":edit_user_unique_id", $user_unique_id);
            $query->bindParam(":shipment_unique_id", $shipment_unique_id);
            $query->bindParam(":rider_unique_id", $rider_unique_id);
            $query->bindParam(":current_location", $current_location);
            $query->bindParam(":longitude", $longitude);
            $query->bindParam(":latitude", $latitude);
            $query->bindParam(":delivery_status", $completion_status);
            $query->bindParam(":added_date", $date_added);
            $query->bindParam(":last_modified", $date_added);
            $query->bindParam(":status", $active);
            $query->execute();

            if ($query->rowCount() > 0) {
              $returnvalue = new genericClass();
              $returnvalue->engineMessage = 1;
            }
            else{
              $returnvalue = new genericClass();
              $returnvalue->engineError = 2;
              $returnvalue->engineErrorMessage = "Not inserted (new shipment)";
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
