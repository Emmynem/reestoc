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

      $user_unique_id = isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : $data['user_unique_id'];
      $sub_product_unique_ids = isset($_GET['sub_product_unique_ids']) ? $_GET['sub_product_unique_ids'] : $data['sub_product_unique_ids'];
      $city = isset($_GET['city']) ? $_GET['city'] : $data['city'];
      $state = isset($_GET['state']) ? $_GET['state'] : $data['state'];
      $country = isset($_GET['country']) ? $_GET['country'] : $data['country'];
      $price = isset($_GET['price']) ? $_GET['price'] : $data['price'];

      $sqlSearchUser = "SELECT unique_id FROM management WHERE unique_id=:unique_id AND status=:status";
      $querySearchUser = $conn->prepare($sqlSearchUser);
      $querySearchUser->bindParam(":unique_id", $user_unique_id);
      $querySearchUser->bindParam(":status", $active);
      $querySearchUser->execute();

      if ($querySearchUser->rowCount() > 0) {

        $validation = $functions->shipping_fees_validation($city, $state, $country, $price);

        if ($validation["error"] == true) {
          $returnvalue = new genericClass();
          $returnvalue->engineError = 2;
          $returnvalue->engineErrorMessage = $validation["message"];
        }
        else {

          if (!is_array($sub_product_unique_ids)) {
            $returnvalue = new genericClass();
            $returnvalue->engineError = 2;
            $returnvalue->engineErrorMessage = "Sub Products IDs is not an array";
          }
          else if (in_array($sub_product_unique_ids,$not_allowed_values)) {
            $returnvalue = new genericClass();
            $returnvalue->engineError = 2;
            $returnvalue->engineErrorMessage = "Sub Products IDs is required";
          }
          else {

            foreach ($sub_product_unique_ids as $key => $sub_product_unique_id){

              $sql2 = "SELECT unique_id FROM shipping_fees WHERE sub_product_unique_id=:sub_product_unique_id AND city=:city AND state=:state AND country=:country";
              $query2 = $conn->prepare($sql2);
              $query2->bindParam(":sub_product_unique_id", $sub_product_unique_id);
              $query2->bindParam(":city", $city);
              $query2->bindParam(":state", $state);
              $query2->bindParam(":country", $country);
              $query2->execute();

              if ($query2->rowCount() > 0) {
                $returnvalue = new genericClass();
                $returnvalue->engineError = 2;
                $returnvalue->engineErrorMessage = $key." index, Sub Product shipping location already exists";
              }
              else {
                $unique_id = $functions->random_str(20);

                $sql = "INSERT INTO shipping_fees (unique_id, user_unique_id, edit_user_unique_id, sub_product_unique_id, city, state, country, price, added_date, last_modified, status)
                VALUES (:unique_id, :user_unique_id, :edit_user_unique_id, :sub_product_unique_id, :city, :state, :country, :price, :added_date, :last_modified, :status)";
                $query = $conn->prepare($sql);
                $query->bindParam(":unique_id", $unique_id);
                $query->bindParam(":user_unique_id", $user_unique_id);
                $query->bindParam(":edit_user_unique_id", $user_unique_id);
                $query->bindParam(":sub_product_unique_id", $sub_product_unique_id);
                $query->bindParam(":city", $city);
                $query->bindParam(":state", $state);
                $query->bindParam(":country", $country);
                $query->bindParam(":price", $price);
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
                  $returnvalue->engineErrorMessage = $key." index, Not inserted (new shipping fee)";
                }
              }

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
