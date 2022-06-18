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

      $pickup_location_unique_id = isset($_GET['pickup_location_unique_id']) ? $_GET['pickup_location_unique_id'] : $data['pickup_location_unique_id'];
      $user_unique_id = isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : $data['user_unique_id'];
      $sub_product_unique_id = isset($_GET['sub_product_unique_id']) ? $_GET['sub_product_unique_id'] : $data['sub_product_unique_id'];

      $sqlSearchUser = "SELECT unique_id FROM management WHERE unique_id=:unique_id AND status=:status";
      $querySearchUser = $conn->prepare($sqlSearchUser);
      $querySearchUser->bindParam(":unique_id", $user_unique_id);
      $querySearchUser->bindParam(":status", $active);
      $querySearchUser->execute();

      if ($querySearchUser->rowCount() > 0) {

        $sql2 = "SELECT unique_id FROM pickup_locations WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id AND status=:status";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(":unique_id", $pickup_location_unique_id);
        $query2->bindParam(":sub_product_unique_id", $sub_product_unique_id);
        $query2->bindParam(":status", $active);
        $query2->execute();

        if ($query2->rowCount() > 0) {

          $sql = "UPDATE pickup_locations SET status=:status, last_modified=:last_modified WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id";
          $query = $conn->prepare($sql);
          $query->bindParam(":unique_id", $pickup_location_unique_id);
          $query->bindParam(":sub_product_unique_id", $sub_product_unique_id);
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
            $returnvalue->engineErrorMessage = "Not removed (pickup location shipping fee)";
          }

        }
        else {
          $returnvalue = new genericClass();
          $returnvalue->engineError = 2;
          $returnvalue->engineErrorMessage = "Pickup Location Shipping fee not found";
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
