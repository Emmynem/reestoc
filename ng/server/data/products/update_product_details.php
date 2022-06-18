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
      $name = isset($_GET['name']) ? $_GET['name'] : $data['name'];
      $brand_unique_id = isset($_GET['brand_unique_id']) ? $_GET['brand_unique_id'] : $data['brand_unique_id'];
      $description = isset($_GET['description']) ? $_GET['description'] : $data['description'];

      $sqlSearchUser = "SELECT unique_id FROM management WHERE unique_id=:unique_id AND status=:status";
      $querySearchUser = $conn->prepare($sqlSearchUser);
      $querySearchUser->bindParam(":unique_id", $user_unique_id);
      $querySearchUser->bindParam(":status", $active);
      $querySearchUser->execute();

      if ($querySearchUser->rowCount() > 0) {

        $validation = $functions->product_validation($name, $description);

        if ($validation["error"] == true) {
          $returnvalue = new genericClass();
          $returnvalue->engineError = 2;
          $returnvalue->engineErrorMessage = $validation["message"];
        }
        else {

          $stripped = $functions->strip_text($name);

          $sql2 = "SELECT unique_id FROM products WHERE unique_id=:unique_id AND status=:status";
          $query2 = $conn->prepare($sql2);
          $query2->bindParam(":unique_id", $unique_id);
          $query2->bindParam(":status", $active);
          $query2->execute();

          if ($query2->rowCount() > 0) {

            $sql3 = "SELECT name FROM products WHERE (name=:name OR stripped=:stripped) AND unique_id!=:unique_id AND status=:status";
            $query3 = $conn->prepare($sql3);
            $query3->bindParam(":unique_id", $unique_id);
            $query3->bindParam(":name", $name);
            $query3->bindParam(":stripped", $stripped);
            $query3->bindParam(":status", $active);
            $query3->execute();

            if ($query3->rowCount() > 0) {
              $returnvalue = new genericClass();
              $returnvalue->engineError = 2;
              $returnvalue->engineErrorMessage = "Product name already exists";
            }
            else {

              $brand_unique_id_alt = in_array($brand_unique_id,$not_allowed_values) ? $null : $brand_unique_id;

              $sql = "UPDATE products SET edit_user_unique_id=:edit_user_unique_id, name=:name, stripped=:stripped, brand_unique_id=:brand_unique_id, description=:description, last_modified=:last_modified WHERE unique_id=:unique_id";
              $query = $conn->prepare($sql);
              $query->bindParam(":unique_id", $unique_id);
              $query->bindParam(":edit_user_unique_id", $user_unique_id);
              $query->bindParam(":name", $name);
              $query->bindParam(":stripped", $stripped);
              $query->bindParam(":brand_unique_id", $brand_unique_id_alt);
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
                $returnvalue->engineErrorMessage = "Not edited (product details)";
              }

            }

          }
          else {
            $returnvalue = new genericClass();
            $returnvalue->engineError = 2;
            $returnvalue->engineErrorMessage = "Product not found";
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
