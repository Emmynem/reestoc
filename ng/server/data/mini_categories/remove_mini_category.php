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

      $edit_user_unique_id = isset($_GET['edit_user_unique_id']) ? $_GET['edit_user_unique_id'] : $data['edit_user_unique_id'];
      $mini_category_unique_id = isset($_GET['mini_category_unique_id']) ? $_GET['mini_category_unique_id'] : $data['mini_category_unique_id'];
      $sub_category_unique_id = isset($_GET['sub_category_unique_id']) ? $_GET['sub_category_unique_id'] : $data['sub_category_unique_id'];
      $category_unique_id = isset($_GET['category_unique_id']) ? $_GET['category_unique_id'] : $data['category_unique_id'];

      $sqlSearchUser = "SELECT unique_id FROM management WHERE unique_id=:unique_id AND status=:status";
      $querySearchUser = $conn->prepare($sqlSearchUser);
      $querySearchUser->bindParam(":unique_id", $edit_user_unique_id);
      $querySearchUser->bindParam(":status", $active);
      $querySearchUser->execute();

      if ($querySearchUser->rowCount() > 0) {

        $sql2 = "SELECT status FROM mini_category WHERE unique_id=:unique_id AND category_unique_id=:category_unique_id AND sub_category_unique_id=:sub_category_unique_id";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(":unique_id", $mini_category_unique_id);
        $query2->bindParam(":category_unique_id", $category_unique_id);
        $query2->bindParam(":sub_category_unique_id", $sub_category_unique_id);
        $query2->execute();

        if ($query2->rowCount() > 0) {

          $sql5 = "UPDATE products SET mini_category_unique_id=:mini_category_unique_id, edit_user_unique_id=:edit_user_unique_id, last_modified=:last_modified WHERE mini_category_unique_id=:mini_category_unique_id_2";
          $query5 = $conn->prepare($sql5);
          $query5->bindParam(":mini_category_unique_id", $null);
          $query5->bindParam(":mini_category_unique_id_2", $mini_category_unique_id);
          $query5->bindParam(":edit_user_unique_id", $edit_user_unique_id);
          $query5->bindParam(":last_modified", $date_added);
          $query5->execute();

          if ($query5->rowCount() > 0) {
            $sql = "DELETE FROM mini_category WHERE unique_id=:unique_id AND category_unique_id=:category_unique_id AND sub_category_unique_id=:sub_category_unique_id";
            $query = $conn->prepare($sql);
            $query->bindParam(":unique_id", $mini_category_unique_id);
            $query->bindParam(":category_unique_id", $category_unique_id);
            $query->bindParam(":sub_category_unique_id", $sub_category_unique_id);
            $query->execute();

            if ($query->rowCount() > 0) {
              $returnvalue = new genericClass();
              $returnvalue->engineMessage = 1;
            }
            else {
              $returnvalue = new genericClass();
              $returnvalue->engineError = 2;
              $returnvalue->engineErrorMessage = "Not deleted (mini category)";
            }
          }
          else {
            $sql = "DELETE FROM mini_category WHERE unique_id=:unique_id AND category_unique_id=:category_unique_id AND sub_category_unique_id=:sub_category_unique_id";
            $query = $conn->prepare($sql);
            $query->bindParam(":unique_id", $mini_category_unique_id);
            $query->bindParam(":category_unique_id", $category_unique_id);
            $query->bindParam(":sub_category_unique_id", $sub_category_unique_id);
            $query->execute();

            if ($query->rowCount() > 0) {
              $returnvalue = new genericClass();
              $returnvalue->engineMessage = 1;
            }
            else {
              $returnvalue = new genericClass();
              $returnvalue->engineError = 2;
              $returnvalue->engineErrorMessage = "Not deleted (mini category)";
            }
          }

        }
        else {
          $returnvalue = new genericClass();
          $returnvalue->engineError = 2;
          $returnvalue->engineErrorMessage = "Mini Category not found";
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
