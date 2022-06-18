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

      $unique_id = isset($_GET['unique_id']) ? $_GET['unique_id'] : $data['unique_id'];
      $user_unique_id = isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : $data['user_unique_id'];
      $product_unique_id = isset($_GET['product_unique_id']) ? $_GET['product_unique_id'] : $data['product_unique_id'];
      $name = isset($_GET['name']) ? $_GET['name'] : $data['name'];
      $size = isset($_GET['size']) ? $_GET['size'] : $data['size'];
      $description = isset($_GET['description']) ? $_GET['description'] : $data['description'];

      $sqlSearchUser = "SELECT unique_id FROM management WHERE unique_id=:unique_id AND status=:status";
      $querySearchUser = $conn->prepare($sqlSearchUser);
      $querySearchUser->bindParam(":unique_id", $user_unique_id);
      $querySearchUser->bindParam(":status", $active);
      $querySearchUser->execute();

      if ($querySearchUser->rowCount() > 0) {

        $validation = $functions->update_sub_product_validation($product_unique_id, $name, $size, $description);

        if ($validation["error"] == true) {
          $returnvalue = new genericClass();
          $returnvalue->engineError = 2;
          $returnvalue->engineErrorMessage = $validation["message"];
        }
        else {

          $full_strip = in_array($size,$not_allowed_values) ? $name : $name." ".$size;
          $stripped = $functions->strip_text($full_strip);

          $sql2 = "SELECT unique_id FROM sub_products WHERE unique_id=:unique_id AND status=:status";
          $query2 = $conn->prepare($sql2);
          $query2->bindParam(":unique_id", $unique_id);
          $query2->bindParam(":status", $active);
          $query2->execute();

          if ($query2->rowCount() > 0) {

            $sql3 = "SELECT name FROM sub_products WHERE (name=:name OR stripped=:stripped) AND unique_id!=:unique_id AND product_unique_id=:product_unique_id AND status=:status";
            $query3 = $conn->prepare($sql3);
            $query3->bindParam(":unique_id", $unique_id);
            $query3->bindParam(":product_unique_id", $product_unique_id);
            $query3->bindParam(":name", $name);
            $query3->bindParam(":stripped", $stripped);
            $query3->bindParam(":status", $active);
            $query3->execute();

            if ($query3->rowCount() > 0) {
              $returnvalue = new genericClass();
              $returnvalue->engineError = 2;
              $returnvalue->engineErrorMessage = "Sub Product name already exists";
            }
            else {

              $size_alt = in_array($size,$not_allowed_values) ? $null : $size;

              $sql = "UPDATE sub_products SET edit_user_unique_id=:edit_user_unique_id, product_unique_id=:product_unique_id, name=:name, size=:size, stripped=:stripped, description=:description, last_modified=:last_modified WHERE unique_id=:unique_id";
              $query = $conn->prepare($sql);
              $query->bindParam(":unique_id", $unique_id);
              $query->bindParam(":edit_user_unique_id", $user_unique_id);
              $query->bindParam(":product_unique_id", $product_unique_id);
              $query->bindParam(":name", $name);
              $query->bindParam(":size", $size_alt);
              $query->bindParam(":stripped", $stripped);
              $query->bindParam(":description", $description);
              $query->bindParam(":last_modified", $date_added);
              $query->execute();

              if ($query->rowCount() > 0) {
                $returnvalue = new genericClass();
                $returnvalue->engineMessage = 1;
              }
              else {
                $returnvalue = new genericClass();
                $returnvalue->engineError = 2;
                $returnvalue->engineErrorMessage = "Not edited (sub product details)";
              }

            }

          }
          else {
            $returnvalue = new genericClass();
            $returnvalue->engineError = 2;
            $returnvalue->engineErrorMessage = "Sub Product not found";
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
