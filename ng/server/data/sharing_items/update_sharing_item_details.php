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
      $null = $functions->null;
      $not_allowed_values = $functions->not_allowed_values;

      $user_unique_id = isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : $data['user_unique_id'];
      $sharing_unique_id = isset($_GET['sharing_unique_id']) ? $_GET['sharing_unique_id'] : $data['sharing_unique_id'];
      $name = isset($_GET['name']) ? $_GET['name'] : $data['name'];
      $description = isset($_GET['description']) ? $_GET['description'] : $data['description'];
      $total_price = isset($_GET['total_price']) ? $_GET['total_price'] : $data['total_price'];
      $split_price = isset($_GET['split_price']) ? $_GET['split_price'] : $data['split_price'];
      $total_no_of_persons = isset($_GET['total_no_of_persons']) ? $_GET['total_no_of_persons'] : $data['total_no_of_persons'];
      // $current_no_of_persons = isset($_GET['current_no_of_persons']) ? $_GET['current_no_of_persons'] : $data['current_no_of_persons'];
      $expiration = isset($_GET['expiration']) ? $_GET['expiration'] : $data['expiration'];
      $starting_date = isset($_GET['starting_date']) ? $_GET['starting_date'] : $data['starting_date'];
      $expiry_date = isset($_GET['expiry_date']) ? $_GET['expiry_date'] : $data['expiry_date'];

      $sqlSearchUser = "SELECT unique_id FROM management WHERE unique_id=:unique_id AND status=:status";
      $querySearchUser = $conn->prepare($sqlSearchUser);
      $querySearchUser->bindParam(":unique_id", $user_unique_id);
      $querySearchUser->bindParam(":status", $active);
      $querySearchUser->execute();

      if ($querySearchUser->rowCount() > 0) {

        $validation = $functions->sharing_validation($name, $description, $total_price, $split_price, $total_no_of_persons, $expiration, $starting_date, $expiry_date);

        if ($validation["error"] == true) {
          $returnvalue = new genericClass();
          $returnvalue->engineError = 2;
          $returnvalue->engineErrorMessage = $validation["message"];
        }
        else {

          $pending = $functions->pending;
          $zero = $functions->zero;
          $null = $functions->null;
          $stripped = $functions->strip_text($name);

          $sql3 = "SELECT unique_id FROM sharing_items WHERE unique_id=:unique_id AND status=:status";
          $query3 = $conn->prepare($sql3);
          $query3->bindParam(":unique_id", $sharing_unique_id);
          $query3->bindParam(":status", $active);
          $query3->execute();

          if ($query3->rowCount() > 0) {

            $sql2 = "SELECT name, expiration, expiry_date, total_no_of_persons, current_no_of_persons FROM sharing_items WHERE (name=:name OR stripped=:stripped) AND unique_id!=:unique_id ORDER BY added_date DESC LIMIT 1";
            $query2 = $conn->prepare($sql2);
            $query2->bindParam(":unique_id", $sharing_unique_id);
            $query2->bindParam(":name", $name);
            $query2->bindParam(":stripped", $stripped);
            // $query2->bindParam(":status", $active);
            $query2->execute();

            if ($query2->rowCount() > 0) {
              $returnvalue = new genericClass();
              $returnvalue->engineError = 2;
              $returnvalue->engineErrorMessage = "Sharing item name already exists";
            }
            else {

              $expiry_date_alt = $expiration == 0 ? $null : $expiry_date;

              $sql = "UPDATE sharing_items SET edit_user_unique_id=:edit_user_unique_id, name=:name, stripped=:stripped, description=:description, total_price=:total_price, split_price=:split_price, total_no_of_persons=:total_no_of_persons,
              expiration=:expiration, starting_date=:starting_date, expiry_date=:expiry_date, last_modified=:last_modified WHERE unique_id=:unique_id";
              $query = $conn->prepare($sql);
              $query->bindParam(":unique_id", $sharing_unique_id);
              $query->bindParam(":edit_user_unique_id", $user_unique_id);
              $query->bindParam(":name", $name);
              $query->bindParam(":stripped", $stripped);
              $query->bindParam(":description", $description);
              $query->bindParam(":total_price", $total_price);
              $query->bindParam(":split_price", $split_price);
              $query->bindParam(":total_no_of_persons", $total_no_of_persons);
              $query->bindParam(":expiration", $expiration);
              $query->bindParam(":starting_date", $starting_date);
              $query->bindParam(":expiry_date", $expiry_date_alt);
              $query->bindParam(":last_modified", $date_added);
              $query->execute();

              if ($query->rowCount() > 0) {
                $returnvalue = new genericClass();
                $returnvalue->engineMessage = 1;
              }
              else{
                $returnvalue = new genericClass();
                $returnvalue->engineError = 2;
                $returnvalue->engineErrorMessage = "Not edited (sharing item)";
              }

            }

          }
          else {
            $returnvalue = new genericClass();
            $returnvalue->engineError = 2;
            $returnvalue->engineErrorMessage = "Sharing item not found";
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
    $returnvalue->engineError = 2;
    $returnvalue->engineErrorMessage = "No connection";
  }

  echo json_encode($returnvalue);

?>
